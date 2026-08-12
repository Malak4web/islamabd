import { describe, it, expect, afterEach } from 'vitest'
import { mount } from '@vue/test-utils'
import ScrollMeasure from '@/components/public/ScrollMeasure.vue'
import { resetFrameReaders } from '@/lib/motion'

/** Fakes a document of `height` in a `viewport`-tall window, scrolled to `y`. */
function stubDocument({ height, viewport, y }) {
  const doc = document.documentElement
  const original = {
    scrollHeight: Object.getOwnPropertyDescriptor(doc, 'scrollHeight'),
    clientHeight: Object.getOwnPropertyDescriptor(doc, 'clientHeight'),
  }
  Object.defineProperty(doc, 'scrollHeight', { value: height, configurable: true })
  Object.defineProperty(doc, 'clientHeight', { value: viewport, configurable: true })
  window.scrollY = y
  return () => {
    if (original.scrollHeight) Object.defineProperty(doc, 'scrollHeight', original.scrollHeight)
    else delete doc.scrollHeight
    if (original.clientHeight) Object.defineProperty(doc, 'clientHeight', original.clientHeight)
    else delete doc.clientHeight
    window.scrollY = 0
  }
}

describe('ScrollMeasure', () => {
  let restore = null

  afterEach(() => {
    restore?.()
    restore = null
    resetFrameReaders()
  })

  it('draws a dimension, not a bar: two witness ticks and a station', () => {
    restore = stubDocument({ height: 3000, viewport: 1000, y: 0 })
    const wrapper = mount(ScrollMeasure)

    expect(wrapper.find('.measure__rail').exists()).toBe(true)
    expect(wrapper.find('.measure__run').exists()).toBe(true)
    expect(wrapper.findAll('.measure__witness')).toHaveLength(2)
    expect(wrapper.find('.measure__station').exists()).toBe(true)
    wrapper.unmount()
  })

  /* Decorative: the scrollbar is the real affordance, and a second reading of
     the same position announced to a screen reader is noise. */
  it('stays out of the accessibility tree', () => {
    restore = stubDocument({ height: 3000, viewport: 1000, y: 0 })
    const wrapper = mount(ScrollMeasure)
    expect(wrapper.attributes('aria-hidden')).toBe('true')
    wrapper.unmount()
  })

  it('is a desktop affordance only', () => {
    restore = stubDocument({ height: 3000, viewport: 1000, y: 0 })
    const wrapper = mount(ScrollMeasure)
    const classes = wrapper.classes()
    expect(classes).toContain('hidden')
    expect(classes).toContain('lg:block')
    wrapper.unmount()
  })

  it('publishes the run as a fraction of the travel, not of the document', () => {
    // 3000 tall in a 1000 viewport: 2000px of travel, half of it used.
    restore = stubDocument({ height: 3000, viewport: 1000, y: 1000 })
    const wrapper = mount(ScrollMeasure)
    expect(Number(wrapper.element.style.getPropertyValue('--run'))).toBeCloseTo(0.5, 4)
    wrapper.unmount()
  })

  it('clamps past the ends rather than overshooting the rail', () => {
    restore = stubDocument({ height: 3000, viewport: 1000, y: 9999 })
    const wrapper = mount(ScrollMeasure)
    expect(Number(wrapper.element.style.getPropertyValue('--run'))).toBe(1)
    wrapper.unmount()
  })

  /* A page with nothing to scroll has no span to measure. Without the guard
     this divides by zero and parks the station at NaN. */
  it('parks at the head when there is nothing to measure', () => {
    restore = stubDocument({ height: 800, viewport: 800, y: 0 })
    const wrapper = mount(ScrollMeasure)
    expect(Number(wrapper.element.style.getPropertyValue('--run'))).toBe(0)
    wrapper.unmount()
  })

  /* The station travels on `transform`, where a percentage would resolve
     against its own 2px height — so the rail's height has to reach CSS as a
     length. */
  it('publishes the travel as a pixel length', () => {
    restore = stubDocument({ height: 3000, viewport: 1000, y: 0 })
    const wrapper = mount(ScrollMeasure)
    expect(wrapper.element.style.getPropertyValue('--span')).toMatch(/px$/)
    wrapper.unmount()
  })
})
