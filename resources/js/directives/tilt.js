/**
 * v-tilt — the card as a physical plate.
 *
 *   v-tilt                  ±4.5deg toward the cursor
 *   v-tilt="{ max: 3 }"     a shallower plate
 *
 * Publishes the cursor's position over the element as `--tx` / `--ty`, both
 * normalised to -1..+1, and flips `data-tilt` between `off` and `on`. As with
 * `v-depth`, the rotation itself is written in CSS: a transform declared here
 * would clobber the `press` feedback and the reveal animations that land on the
 * same elements.
 *
 * Runs only where there is a real cursor. A phone reports `pointer: coarse`, and
 * a tilt driven by a finger that is already covering the card is a rotation
 * nobody can see — the touch story is `v-depth`, which needs no pointer at all.
 *
 * The paired CSS lives in `resources/css/app.css` under "Depth".
 */

import { clamp, hasFinePointer, prefersReducedMotion } from '@/lib/motion'

function canTilt() {
  if (typeof window === 'undefined') return false
  if (typeof requestAnimationFrame !== 'function') return false
  return hasFinePointer() && !prefersReducedMotion()
}

export const tilt = {
  mounted(el, binding) {
    if (!canTilt()) return

    const max = Number(binding.value?.max)
    if (Number.isFinite(max)) el.style.setProperty('--tilt-max', `${clamp(max, 0, 15)}deg`)

    const set = (x, y) => {
      el.style.setProperty('--tx', x.toFixed(3))
      el.style.setProperty('--ty', y.toFixed(3))
    }

    // pointermove fires faster than the screen refreshes, and each handler both
    // writes a custom property and reads a rect — the classic layout thrash.
    // The event is stored and read once per frame instead.
    const state = { frame: 0, event: null }

    const apply = () => {
      state.frame = 0
      const event = state.event
      if (!event) return
      const rect = el.getBoundingClientRect()
      if (!rect.width || !rect.height) return
      set(
        clamp(((event.clientX - rect.left) / rect.width) * 2 - 1, -1, 1),
        clamp(((event.clientY - rect.top) / rect.height) * 2 - 1, -1, 1)
      )
    }

    const onMove = (event) => {
      state.event = event
      if (!state.frame) state.frame = requestAnimationFrame(apply)
    }

    const onEnter = (event) => {
      el.dataset.tilt = 'on'
      onMove(event)
    }

    const rest = () => {
      el.dataset.tilt = 'off'
      state.event = null
      set(0, 0)
    }

    // Focus is not a position: a keyboard visitor gets the lift and the light,
    // squarely, with no rotation to guess the origin of.
    const onFocus = () => {
      el.dataset.tilt = 'on'
      state.event = null
      set(0, 0)
    }

    el.dataset.tilt = 'off'
    el.addEventListener('pointerenter', onEnter)
    el.addEventListener('pointermove', onMove)
    el.addEventListener('pointerleave', rest)
    el.addEventListener('pointercancel', rest)
    el.addEventListener('focusin', onFocus)
    el.addEventListener('focusout', rest)

    el.__tilt = { onEnter, onMove, rest, onFocus, state }
  },

  unmounted(el) {
    const bound = el.__tilt
    if (!bound) return
    el.removeEventListener('pointerenter', bound.onEnter)
    el.removeEventListener('pointermove', bound.onMove)
    el.removeEventListener('pointerleave', bound.rest)
    el.removeEventListener('pointercancel', bound.rest)
    el.removeEventListener('focusin', bound.onFocus)
    el.removeEventListener('focusout', bound.rest)
    if (bound.state.frame && typeof cancelAnimationFrame === 'function') {
      cancelAnimationFrame(bound.state.frame)
    }
    delete el.__tilt
  },
}

export default tilt
