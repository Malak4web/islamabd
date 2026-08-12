/**
 * v-depth — publishes an element's travel through the viewport as `--depth`.
 *
 *   v-depth                       -1 .. +1 as the element crosses the screen
 *   v-depth="{ strength: 0.6 }"   the same curve, scaled down
 *
 * The directive deliberately does not animate anything. It writes one number
 * and the stylesheet decides what that number means — a photograph drifting
 * inside its frame, a column of copy running ahead of the scroll, a rule
 * lengthening. Keeping the effect in CSS is what lets a plane carry a scroll
 * parallax and a hover zoom at once without either resetting the other, and it
 * keeps the visual vocabulary in the one file that documents it.
 *
 * `--depth` defaults to 0 in `app.css`, so an element whose directive never
 * runs — reduced motion, no IntersectionObserver, a headless render — sits
 * exactly where the stylesheet put it. Depth enhances; it never positions.
 *
 * The paired CSS lives in `resources/css/app.css` under "Depth".
 */

import { addFrameReader, clamp, prefersReducedMotion, viewportProgress } from '@/lib/motion'

function canTrack() {
  if (typeof window === 'undefined') return false
  if (typeof IntersectionObserver !== 'function') return false
  if (typeof requestAnimationFrame !== 'function') return false
  return !prefersReducedMotion()
}

export const depth = {
  mounted(el, binding) {
    if (!canTrack()) return

    const strength = Number(binding.value?.strength)
    const scale = Number.isFinite(strength) ? clamp(strength, 0, 2) : 1

    const read = () => {
      const rect = el.getBoundingClientRect()
      const vh = window.innerHeight || document.documentElement.clientHeight || 0
      const p = viewportProgress(rect, vh) * scale
      el.style.setProperty('--depth', p.toFixed(4))
    }

    const state = { off: null }

    // Only elements on screen cost a frame. The margin starts them a fifth of
    // a viewport early so the first value written is already the right one —
    // registering at the moment of entry would snap the plane from 0 to -0.9.
    const io = new IntersectionObserver(
      (entries) => {
        const visible = entries.some((entry) => entry.isIntersecting)
        if (visible && !state.off) {
          state.off = addFrameReader(read)
          read()
        } else if (!visible && state.off) {
          state.off()
          state.off = null
        }
      },
      { rootMargin: '20% 0px 20% 0px' }
    )

    io.observe(el)
    el.dataset.depth = 'on'
    el.__depth = { io, state }
  },

  unmounted(el) {
    if (!el.__depth) return
    el.__depth.io.disconnect()
    if (el.__depth.state.off) el.__depth.state.off()
    delete el.__depth
  },
}

export default depth
