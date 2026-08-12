import { describe, it, expect, afterEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { nextTick } from 'vue'
import VillaWalk from '@/components/public/VillaWalk.vue'
import { resetFrameReaders } from '@/lib/motion'

function stubMedia(matches) {
  const original = window.matchMedia
  window.matchMedia = (query) => ({
    matches: Boolean(matches[query]),
    media: query,
    addEventListener() {},
    removeEventListener() {},
  })
  return () => {
    window.matchMedia = original
  }
}

const REDUCE = '(prefers-reduced-motion: reduce)'

async function mountWalk() {
  const wrapper = mount(VillaWalk)
  await nextTick()
  await nextTick()
  return wrapper
}

describe('VillaWalk', () => {
  let restore = null

  afterEach(() => {
    restore?.()
    restore = null
    resetFrameReaders()
    vi.restoreAllMocks()
  })

  it('walks five spaces, each a real photograph', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const wrapper = await mountWalk()

    const rooms = wrapper.findAll('.walk__room')
    expect(rooms).toHaveLength(5)
    rooms.forEach((room) => {
      const img = room.find('img')
      expect(img.exists()).toBe(true)
      expect(img.attributes('alt')).toBeTruthy()
      expect(img.attributes('srcset')).toContain('/images/projects/')
    })
    wrapper.unmount()
  })

  /* The plan is the thing that makes it a house rather than a slideshow: one
     cell per space, in the order they are walked. */
  it('draws a plan cell for every space and a route through them', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const wrapper = await mountWalk()

    expect(wrapper.findAll('.plan__cell')).toHaveLength(5)
    const route = wrapper.find('.plan__route')
    expect(route.exists()).toBe(true)
    expect(route.attributes('points').split(' ')).toHaveLength(5)
    wrapper.unmount()
  })

  it('names and annotates every space in the caption', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const wrapper = await mountWalk()

    const captions = wrapper.findAll('.walk__caption')
    expect(captions).toHaveLength(5)
    expect(captions[0].find('.walk__caption-name').text()).toBe('The approach')
    expect(captions[0].find('.walk__caption-note').text()).toContain('Limestone')
    wrapper.unmount()
  })

  it('carries a heading and a region label', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const wrapper = await mountWalk()

    expect(wrapper.attributes('aria-label')).toBeTruthy()
    expect(wrapper.find('h2.walk__heading').text()).toBeTruthy()
    wrapper.unmount()
  })

  it('goes live when it can drive the camera', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const wrapper = await mountWalk()
    expect(wrapper.classes()).toContain('walk--live')
    wrapper.unmount()
  })

  /* The contract: `walk--live` is the only class that pins, stacks and hides
     anything, and it is added by the same code that can un-hide them. Without
     it the section is five photographs in flow with visible captions — which is
     what a crawler, a reduced-motion visitor and a failed script all get. */
  it('stays a plain photo essay under reduced motion', async () => {
    restore = stubMedia({ [REDUCE]: true })
    const wrapper = await mountWalk()

    expect(wrapper.classes()).not.toContain('walk--live')
    // Nothing was hidden, moved or clipped on the way out.
    wrapper.findAll('.walk__room').forEach((room) => {
      expect(room.element.style.visibility).toBe('')
      expect(room.element.style.clipPath).toBe('')
    })
    expect(wrapper.findAll('.walk__caption')).toHaveLength(5)
    wrapper.unmount()
  })

  it('declines to run without an animation frame', async () => {
    restore = stubMedia({ [REDUCE]: false })
    const raf = window.requestAnimationFrame
    // eslint-disable-next-line no-global-assign
    window.requestAnimationFrame = undefined
    const wrapper = await mountWalk()
    expect(wrapper.classes()).not.toContain('walk--live')
    window.requestAnimationFrame = raf
    wrapper.unmount()
  })
})
