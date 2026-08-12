/**
 * v-reveal — scroll-triggered entrance.
 *
 *   v-reveal              rise (default)
 *   v-reveal:rule         scale out along the inline axis, for hairlines
 *   v-reveal:wipe         clip-path wipe, for photographs
 *   v-reveal="{ delay: 120 }"
 *
 * The rule this directive exists to honour: the element's DEFAULT state is
 * visible. Nothing is hidden until this code has confirmed it can also show it
 * again — IntersectionObserver present, reduced motion off — and even then a
 * failsafe timer guarantees the reveal fires. A reveal animation that silently
 * never runs ships a blank section, which is worse than no animation at all.
 *
 * The paired CSS lives in `resources/css/app.css` under "Scroll reveal".
 */

const ANIMS = new Set(['rise', 'rule', 'wipe'])

/** Nothing is hidden unless we can guarantee it comes back. */
function canAnimate() {
  if (typeof window === 'undefined') return false
  if (!('IntersectionObserver' in window)) return false
  if (typeof window.matchMedia !== 'function') return false
  return !window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

export const reveal = {
  mounted(el, binding) {
    if (!canAnimate()) return

    const anim = ANIMS.has(binding.arg) ? binding.arg : 'rise'
    const delay = Number(binding.value?.delay) || 0

    el.dataset.revealAnim = anim
    el.dataset.reveal = 'pending'
    if (delay) el.style.setProperty('--reveal-delay', `${delay}ms`)

    const show = () => {
      if (el.dataset.reveal === 'in') return
      el.dataset.reveal = 'in'
      state.io.disconnect()
      clearTimeout(state.failsafe)
    }

    const state = {
      // If the observer never reports — a container that never scrolls, a tab
      // that stays hidden, a headless renderer — the content still arrives.
      failsafe: setTimeout(show, 2500),
      io: new IntersectionObserver(
        (entries) => {
          if (entries.some((e) => e.isIntersecting)) show()
        },
        // Fires a little before the element is fully on screen, so the motion
        // reads as the section settling into place rather than reacting to it.
        { rootMargin: '0px 0px -10% 0px', threshold: 0.01 }
      ),
    }

    state.io.observe(el)
    el.__reveal = state
  },

  unmounted(el) {
    if (!el.__reveal) return
    el.__reveal.io.disconnect()
    clearTimeout(el.__reveal.failsafe)
    delete el.__reveal
  },
}

export default reveal
