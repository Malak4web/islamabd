import { mount, RouterLinkStub } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import ProjectCard from '@/components/public/ProjectCard.vue'

describe('ProjectCard.vue', () => {
    const project = {
        id: 1,
        title: 'Modern Villa',
        category: 'residential',
        cover_image: '/storage/covers/1.jpg',
        is_featured: true
    }

    it('renders title and cover', () => {
        const wrapper = mount(ProjectCard, {
            props: { project },
            global: { stubs: { RouterLink: RouterLinkStub } }
        })
        
        expect(wrapper.text()).toContain('Modern Villa')
        expect(wrapper.find('img').attributes('src')).toBe('/storage/covers/1.jpg')
    })

    it('shows featured badge when is_featured', () => {
        const wrapper = mount(ProjectCard, {
            props: { project },
            global: { stubs: { RouterLink: RouterLinkStub } }
        })
        
        expect(wrapper.text()).toContain('Featured')
    })

    it('shows category badge', () => {
        const wrapper = mount(ProjectCard, {
            props: { project },
            global: { stubs: { RouterLink: RouterLinkStub } }
        })
        
        expect(wrapper.text()).toContain('residential')
    })
})
