import { mount, RouterLinkStub } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import ServiceCard from '@/components/public/ServiceCard.vue'
import i18n from '@/i18n'

const mountCard = (service) =>
    mount(ServiceCard, {
        props: { service },
        global: {
            plugins: [i18n],
            stubs: { RouterLink: RouterLinkStub },
        },
    })

describe('ServiceCard.vue', () => {
    const service = {
        id: 1,
        title: 'Interior Design',
        description: 'Quality design for your home.',
        icon: '/storage/icons/test.svg',
        image: '/storage/images/test.jpg',
    }

    it('renders service title and description', () => {
        const wrapper = mountCard(service)

        expect(wrapper.text()).toContain('Interior Design')
        expect(wrapper.text()).toContain('Quality design for your home.')
    })

    it('leads with the service photograph', () => {
        const wrapper = mountCard(service)

        // The photograph is the first image in the card: for a design practice
        // the work carries the pitch, so it sits above the label.
        expect(wrapper.find('figure img').attributes('src')).toBe('/storage/images/test.jpg')
    })

    it('renders a custom uploaded icon when one is provided', () => {
        const wrapper = mountCard(service)

        const icon = wrapper.findAll('img').find((img) => img.attributes('src') === '/storage/icons/test.svg')
        expect(icon).toBeTruthy()
        // Decorative: the adjacent heading already names the service.
        expect(icon.attributes('aria-hidden')).toBe('true')
    })

    it('falls back to the built-in architectural mark when no icon is set', () => {
        const wrapper = mountCard({ ...service, icon: null, image: null })

        expect(wrapper.find('svg').exists()).toBe(true)
        expect(wrapper.find('img').exists()).toBe(false)
    })
})
