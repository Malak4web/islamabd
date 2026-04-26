import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminProjects from '@/views/admin/AdminProjects.vue'
import { useProjectStore } from '@/stores/projectStore'
import api from '@/api/axios'

vi.mock('@/api/axios')
vi.mock('vuedraggable', () => ({
    default: {
        template: '<div><div v-for="item in modelValue" :key="item.id"><slot name="item" :element="item"></slot></div></div>',
        props: ['modelValue', 'itemKey']
    }
}))

describe('AdminProjects.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
        api.get.mockResolvedValue({ data: { data: [] } })
    })

    it('renders project list with covers', async () => {
        const mockProjects = [
            { id: 1, title_en: 'P1', category: 'residential', cover_image: 'c1.jpg' },
            { id: 2, title_en: 'P2', category: 'commercial', cover_image: null }
        ]
        api.get.mockResolvedValue({ data: { data: mockProjects } })
        
        const wrapper = mount(AdminProjects, {
            global: { stubs: ['router-link'] }
        })
        
        await flushPromises()
        
        expect(wrapper.text()).toContain('P1')
        expect(wrapper.text()).toContain('P2')
        expect(wrapper.text().toLowerCase()).toContain('residential')
        expect(wrapper.findAll('img').length).toBeGreaterThan(0)
    })

    it('toggle calls store toggleProject', async () => {
        const mockProjects = [{ id: 1, title_en: 'P1', is_active: true }]
        api.get.mockResolvedValue({ data: { data: mockProjects } })
        api.patch.mockResolvedValue({ data: { data: { id: 1, is_active: false } } })
        
        const wrapper = mount(AdminProjects, {
            global: { stubs: ['router-link'] }
        })
        
        await flushPromises()
        await wrapper.find('[data-test-toggle]').trigger('click')
        
        expect(api.patch).toHaveBeenCalledWith('/admin/projects/1/toggle')
    })

})
