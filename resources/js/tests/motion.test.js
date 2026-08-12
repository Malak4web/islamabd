import { describe, it, expect, beforeEach, afterEach, vi } from 'vitest'
import {
  addFrameReader,
  clamp,
  hasFinePointer,
  prefersReducedMotion,
  resetFrameReaders,
  viewportProgress,
} from '@/lib/motion'
import depth from '@/directives/depth'
import tilt from '@/directives/tilt'

/** Replaces window.matchMedia with one that answers a fixed map of queries. */
function stubMedia(answers) {
  const original = window.matchMedia
  window.matchMedia = (query) => ({
    matches: Boolean(answers[query]),
    media: query,
    addEventListener() {},
    removeEventListener() {},
  })
  return () => {
    window.matchMedia = original
  }
}

const REDUCE = '(prefers-reduced-motion: reduce)'
const FINE = '(hover: hover) and (pointer: fine)'

describe('viewportProgress', () => {
  const rect = (top, height) => ({ top, height })

  it('is zero when the element is centred in the viewport', () => {
    expect(viewportProgress(rect(400, 200), 1000)).toBe(0)
  })

  it('runs from -1 entering to +1 leaving', () => {
    // Centre sitting exactly on the bottom edge, then on the top edge.
    expect(viewportProgress(rect(1000, 200), 1000)).toBeCloseTo(-1, 5)
    expect(viewportProgress(rect(-200, 200), 1000)).toBeCloseTo(1, 5)
  })

  it('clamps past the edges rather than running away', () => {
    expect(viewportProgress(rect(4000, 200), 1000)).toBe(-1)
    expect(viewportProgress(rect(-4000, 200), 1000)).toBe(1)
  })

  /* What is normalised is the element's own lifetime, not the rate: a hero and
     a thumbnail each span exactly 0..1 between arriving and leaving, so one
     coefficient in CSS spends the same total travel on both. The tall one gets
     there over more scrolling, which is the point — it is on screen longer. */
  it('normalises by the element it is measuring, whatever its height', () => {
    expect(viewportProgress(rect(-1000, 1000), 1000)).toBeCloseTo(1, 5)
    expect(viewportProgress(rect(-100, 100), 1000)).toBeCloseTo(1, 5)
    expect(viewportProgress(rect(-500, 1000), 1000)).toBeLessThan(
      viewportProgress(rect(-50, 100), 1000)
    )
  })

  it('survives a zero-height viewport', () => {
    expect(viewportProgress(rect(0, 0), 0)).toBe(0)
  })
})

describe('clamp', () => {
  it('bounds both ends and passes the middle through', () => {
    expect(clamp(-5, -1, 1)).toBe(-1)
    expect(clamp(5, -1, 1)).toBe(1)
    expect(clamp(0.25, -1, 1)).toBe(0.25)
  })
})

describe('motion preferences', () => {
  let restore = null

  afterEach(() => {
    restore?.()
    restore = null
  })

  it('reports reduced motion when the query matches', () => {
    restore = stubMedia({ [REDUCE]: true })
    expect(prefersReducedMotion()).toBe(true)
  })

  it('defaults to reduced motion when it cannot be determined', () => {
    const original = window.matchMedia
    window.matchMedia = undefined
    expect(prefersReducedMotion()).toBe(true)
    expect(hasFinePointer()).toBe(false)
    window.matchMedia = original
  })

  it('only reports a fine pointer when there is a real cursor', () => {
    restore = stubMedia({ [FINE]: false })
    expect(hasFinePointer()).toBe(false)
    restore()
    restore = stubMedia({ [FINE]: true })
    expect(hasFinePointer()).toBe(true)
  })
})

describe('the shared ticker', () => {
  beforeEach(() => resetFrameReaders())
  afterEach(() => resetFrameReaders())

  it('batches every reader into one frame and detaches on the last removal', async () => {
    const addSpy = vi.spyOn(window, 'addEventListener')
    const removeSpy = vi.spyOn(window, 'removeEventListener')
    const a = vi.fn()
    const b = vi.fn()

    const offA = addFrameReader(a)
    const offB = addFrameReader(b)

    expect(addSpy).toHaveBeenCalledWith('scroll', expect.any(Function), { passive: true })

    await new Promise((resolve) => requestAnimationFrame(resolve))
    await new Promise((resolve) => requestAnimationFrame(resolve))
    expect(a).toHaveBeenCalled()
    expect(b).toHaveBeenCalled()

    // One reader left: the listeners stay.
    offA()
    expect(removeSpy).not.toHaveBeenCalledWith('scroll', expect.any(Function))
    offB()
    expect(removeSpy).toHaveBeenCalledWith('scroll', expect.any(Function))

    addSpy.mockRestore()
    removeSpy.mockRestore()
  })
})

describe('v-depth', () => {
  let restore = null

  afterEach(() => {
    restore?.()
    restore = null
    delete window.IntersectionObserver
    resetFrameReaders()
  })

  it('declines to run without an observer, leaving the element untouched', () => {
    restore = stubMedia({ [REDUCE]: false })
    const el = document.createElement('div')
    depth.mounted(el, {})
    expect(el.dataset.depth).toBeUndefined()
    expect(el.style.getPropertyValue('--depth')).toBe('')
  })

  it('declines to run under reduced motion even where it could', () => {
    restore = stubMedia({ [REDUCE]: true })
    window.IntersectionObserver = class {
      observe() {}
      disconnect() {}
    }
    const el = document.createElement('div')
    depth.mounted(el, {})
    expect(el.dataset.depth).toBeUndefined()
  })

  it('publishes a signed progress once the element is on screen', async () => {
    restore = stubMedia({ [REDUCE]: false })
    let notify = null
    window.IntersectionObserver = class {
      constructor(callback) {
        notify = callback
      }
      observe() {}
      disconnect() {}
    }

    const el = document.createElement('div')
    // Centred in a 1000px viewport: progress is exactly 0.
    el.getBoundingClientRect = () => ({ top: 400, height: 200 })
    window.innerHeight = 1000

    depth.mounted(el, {})
    expect(el.dataset.depth).toBe('on')

    notify([{ isIntersecting: true }])
    expect(Number(el.style.getPropertyValue('--depth'))).toBeCloseTo(0, 4)

    // Scrolled so the element's centre is above the middle. The observer does
    // not fire again — the update has to come through the shared ticker, which
    // is exactly the path this asserts.
    el.getBoundingClientRect = () => ({ top: 0, height: 200 })
    window.dispatchEvent(new Event('scroll'))
    await new Promise((resolve) => requestAnimationFrame(resolve))
    await new Promise((resolve) => requestAnimationFrame(resolve))
    expect(Number(el.style.getPropertyValue('--depth'))).toBeCloseTo(0.6667, 3)

    depth.unmounted(el)
  })

  it('scales the curve by the strength binding', () => {
    restore = stubMedia({ [REDUCE]: false })
    let notify = null
    window.IntersectionObserver = class {
      constructor(callback) {
        notify = callback
      }
      observe() {}
      disconnect() {}
    }

    const el = document.createElement('div')
    el.getBoundingClientRect = () => ({ top: -200, height: 200 })
    window.innerHeight = 1000

    depth.mounted(el, { value: { strength: 0.5 } })
    notify([{ isIntersecting: true }])
    expect(Number(el.style.getPropertyValue('--depth'))).toBeCloseTo(0.5, 4)

    depth.unmounted(el)
  })
})

describe('v-tilt', () => {
  let restore = null

  afterEach(() => {
    restore?.()
    restore = null
  })

  /* The whole reason the phone story is v-depth: a rotation driven by a finger
     that is already covering the card is a rotation nobody can see. */
  it('never engages on a touch device', () => {
    restore = stubMedia({ [FINE]: false, [REDUCE]: false })
    const el = document.createElement('div')
    tilt.mounted(el, {})
    expect(el.dataset.tilt).toBeUndefined()
  })

  it('never engages under reduced motion', () => {
    restore = stubMedia({ [FINE]: true, [REDUCE]: true })
    const el = document.createElement('div')
    tilt.mounted(el, {})
    expect(el.dataset.tilt).toBeUndefined()
  })

  it('rests flat until a pointer arrives, and returns flat when it leaves', async () => {
    restore = stubMedia({ [FINE]: true, [REDUCE]: false })
    const el = document.createElement('div')
    el.getBoundingClientRect = () => ({ left: 0, top: 0, width: 200, height: 100 })
    document.body.appendChild(el)

    tilt.mounted(el, {})
    expect(el.dataset.tilt).toBe('off')

    el.dispatchEvent(
      new PointerEvent('pointerenter', { clientX: 200, clientY: 100, bubbles: false })
    )
    await new Promise((resolve) => requestAnimationFrame(resolve))

    expect(el.dataset.tilt).toBe('on')
    // Bottom-right corner: both axes at their positive limit.
    expect(Number(el.style.getPropertyValue('--tx'))).toBeCloseTo(1, 3)
    expect(Number(el.style.getPropertyValue('--ty'))).toBeCloseTo(1, 3)

    el.dispatchEvent(new PointerEvent('pointerleave'))
    expect(el.dataset.tilt).toBe('off')
    expect(Number(el.style.getPropertyValue('--tx'))).toBe(0)

    tilt.unmounted(el)
    el.remove()
  })

  /* Focus is not a position — a keyboard visitor gets the state without a
     rotation whose origin they have no way to guess. */
  it('lifts on focus without rotating', () => {
    restore = stubMedia({ [FINE]: true, [REDUCE]: false })
    const el = document.createElement('div')
    document.body.appendChild(el)
    tilt.mounted(el, {})

    el.dispatchEvent(new FocusEvent('focusin', { bubbles: true }))
    expect(el.dataset.tilt).toBe('on')
    expect(Number(el.style.getPropertyValue('--tx'))).toBe(0)
    expect(Number(el.style.getPropertyValue('--ty'))).toBe(0)

    tilt.unmounted(el)
    el.remove()
  })

  it('clamps the max angle it will accept', () => {
    restore = stubMedia({ [FINE]: true, [REDUCE]: false })
    const el = document.createElement('div')
    tilt.mounted(el, { value: { max: 400 } })
    expect(el.style.getPropertyValue('--tilt-max')).toBe('15deg')
    tilt.unmounted(el)
  })
})
