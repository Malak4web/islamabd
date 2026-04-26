import { mount, RouterLinkStub } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import ServiceCard from '@/components/public/ServiceCard.vue'


describe('ServiceCard.vue', () => {
    const service = {
        id: 1,
        title: 'Interior Design',
        description: 'Quality design for your home.',
        icon: '/storage/icons/test.svg',
        image: '/storage/images/test.jpg'
    }

    it('renders service title and description', () => {
        const wrapper = mount(ServiceCard, {
            props: { service },
            global: {
                stubs: {
                    RouterLink: RouterLinkStub
                }
            }
        })

        
        expect(wrapper.text()).toContain('Interior Design')
        expect(wrapper.text()).toContain('Quality design for your home.')
    })

    it('renders service icon if provided', () => {
        const wrapper = mount(ServiceCard, {
            props: { service },
            global: {
                stubs: {
                    RouterLink: RouterLinkStub
                }
            }
        })

        
        expect(wrapper.find('img').attributes('src')).toBe('/storage/icons/test.svg')
    })

    it('shows fallback when no icon', () => {
        const wrapper = mount(ServiceCard, {
            props: { 
                service: { ...service, icon: null, image: null } 
            },

            global: {
                stubs: {
                    RouterLink: RouterLinkStub
                }
            }
        })
        
        expect(wrapper.find('svg').exists()).toBe(true)
        expect(wrapper.find('img').exists()).toBe(false)
    })
})
