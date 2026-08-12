import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import AppImage from '@/components/public/AppImage.vue'

/**
 * The fallback chain is the reason this component exists: every seeded project
 * cover points at a WordPress domain that no longer resolves, and a failed
 * <img> renders its alt text at body size over whatever is layered on top.
 */
describe('AppImage.vue', () => {
  const mountImage = (props) =>
    mount(AppImage, { props, global: { stubs: { EaiIcon: true } } })

  it('renders the primary source first', () => {
    const wrapper = mountImage({ src: '/remote.jpg', fallbackSrc: '/local.jpg', alt: 'A room' })

    expect(wrapper.find('img').attributes('src')).toBe('/remote.jpg')
  })

  it('steps down to the local fallback when the primary source fails', async () => {
    const wrapper = mountImage({ src: '/dead.jpg', fallbackSrc: '/local.jpg', alt: 'A room' })

    await wrapper.find('img').trigger('error')

    expect(wrapper.find('img').attributes('src')).toBe('/local.jpg')
  })

  it('falls through to the placeholder mark once every source is exhausted', async () => {
    const wrapper = mountImage({ src: '/dead.jpg', fallbackSrc: '/also-dead.jpg', alt: 'A room' })

    await wrapper.find('img').trigger('error')
    await wrapper.find('img').trigger('error')

    expect(wrapper.find('img').exists()).toBe(false)
    // The placeholder still announces the image it stood in for.
    expect(wrapper.find('[role="img"]').attributes('aria-label')).toBe('A room')
  })

  it('starts at the fallback when no primary source is given', () => {
    const wrapper = mountImage({ fallbackSrc: '/local.jpg', alt: 'A room' })

    expect(wrapper.find('img').attributes('src')).toBe('/local.jpg')
  })

  it('retries from the top when the source changes', async () => {
    const wrapper = mountImage({ src: '/dead.jpg', fallbackSrc: '/local.jpg', alt: 'A room' })
    await wrapper.find('img').trigger('error')
    expect(wrapper.find('img').attributes('src')).toBe('/local.jpg')

    // One dead URL must not poison the slot for the next record rendered
    // through the same recycled node.
    await wrapper.setProps({ src: '/fresh.jpg' })

    expect(wrapper.find('img').attributes('src')).toBe('/fresh.jpg')
  })
})
