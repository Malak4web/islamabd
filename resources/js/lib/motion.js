/**
 * Motion primitives shared by the depth directives.
 *
 * Two rules everything below is built on:
 *
 * 1. Nothing is hidden, displaced or gated until we have confirmed we can also
 *    put it back — the same contract `v-reveal` keeps. A depth effect enhances
 *    a layout that is already correct without it, so every directive here bails
 *    out completely rather than half-applying.
 *
 * 2. One animation frame for the whole document. Each plane reads its own
 *    `getBoundingClientRect`, and a rect read after a style write forces a
 *    synchronous layout; a dozen elements each with their own scroll listener
 *    is a dozen forced layouts per frame, which is how a parallax page arrives
 *    at 30fps on the phone it was supposed to impress. Readers register here,
 *    the ticker batches them into a single rAF, and the listeners come off the
 *    window entirely when the last one leaves.
 */

const REDUCED_MOTION = '(prefers-reduced-motion: reduce)'
/** A tilt needs a cursor. `hover: hover` excludes the phone that emulates one. */
const FINE_POINTER = '(hover: hover) and (pointer: fine)'

const media = (query) => {
  if (typeof window === 'undefined' || typeof window.matchMedia !== 'function') return null
  return window.matchMedia(query)
}

/** Defaults to `true` where it cannot be determined: less motion, never more. */
export function prefersReducedMotion() {
  const mq = media(REDUCED_MOTION)
  return mq ? mq.matches : true
}

export function hasFinePointer() {
  const mq = media(FINE_POINTER)
  return mq ? mq.matches : false
}

export const clamp = (n, min, max) => (n < min ? min : n > max ? max : n)

/* -------------------------------------------------------------------------
   The shared ticker
   ------------------------------------------------------------------------- */

const readers = new Set()
let queued = 0
let listening = false

function flush() {
  queued = 0
  readers.forEach((read) => read())
}

function schedule() {
  if (queued || typeof requestAnimationFrame !== 'function') return
  queued = requestAnimationFrame(flush)
}

function listen() {
  if (listening || typeof window === 'undefined') return
  listening = true
  // Passive: none of these readers calls preventDefault, and saying so is what
  // keeps the scroll thread from waiting on them.
  window.addEventListener('scroll', schedule, { passive: true })
  window.addEventListener('resize', schedule, { passive: true })
  window.addEventListener('orientationchange', schedule, { passive: true })
}

function unlisten() {
  if (!listening) return
  listening = false
  window.removeEventListener('scroll', schedule)
  window.removeEventListener('resize', schedule)
  window.removeEventListener('orientationchange', schedule)
  if (queued && typeof cancelAnimationFrame === 'function') cancelAnimationFrame(queued)
  queued = 0
}

/**
 * Register a per-frame reader. Returns its own unsubscribe.
 * @param {() => void} read
 * @returns {() => void}
 */
export function addFrameReader(read) {
  readers.add(read)
  listen()
  schedule()
  return () => {
    readers.delete(read)
    if (!readers.size) unlisten()
  }
}

/** Test seam: drops every reader and detaches the window listeners. */
export function resetFrameReaders() {
  readers.clear()
  unlisten()
}

/**
 * Signed progress of an element through the viewport.
 *
 *   -1  its centre sits on the bottom edge (about to enter)
 *    0  its centre sits on the viewport centre
 *   +1  its centre sits on the top edge (about to leave)
 *
 * Normalising by `(viewport + height) / 2` rather than by the viewport alone is
 * what makes a full-height hero and a 200px thumbnail travel the same 0..1, so
 * one coefficient in CSS produces a comparable amount of movement on both.
 */
export function viewportProgress(rect, viewportHeight) {
  const span = (viewportHeight + rect.height) / 2
  if (span <= 0) return 0
  return clamp((viewportHeight / 2 - (rect.top + rect.height / 2)) / span, -1, 1)
}
