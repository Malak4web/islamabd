import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminServices from '@/views/admin/AdminServices.vue'
import { useServiceStore } from '@/stores/serviceStore'
import api from '@/api/axios'

vi.mock('@/api/axios')



vi.mock('vuedraggable', () => ({
    default: {
        template: '<div><div v-for="item in modelValue" :key="item.id"><slot name="item" :element="item"></slot></div></div>',
        props: ['modelValue', 'itemKey']
    }
}))


describe('AdminServices.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })


    it('renders list of services', async () => {
        const store = useServiceStore()
        const mockServices = [
            { id: 1, title_en: 'S1', title_ar: 'س1', order: 1, is_active: true },
            { id: 2, title_en: 'S2', title_ar: 'س2', order: 2, is_active: false }
        ]
        api.get.mockResolvedValue({ data: { data: mockServices } })
        
        const wrapper = mount(AdminServices, {
            global: {
                stubs: ['router-link']
            }
        })
        
        await flushPromises()
        
        expect(wrapper.text()).toContain('S1')
        expect(wrapper.text()).toContain('S2')
        expect(wrapper.text()).toContain('Active')
        expect(wrapper.text()).toContain('Draft')
    })

    it('opens add modal when clicking add button', async () => {
        api.get.mockResolvedValue({ data: { data: [] } })
        const wrapper = mount(AdminServices, {
            global: {
                stubs: ['router-link']
            }
        })
        
        await wrapper.find('button').trigger('click') // The "Add New Service" button is the first one
        
        expect(wrapper.text()).toContain('Add Service')
    })

    it('toggle calls store toggleService', async () => {
        const store = useServiceStore()
        const mockServices = [{ id: 1, title_en: 'S1', is_active: true }]
        api.get.mockResolvedValue({ data: { data: mockServices } })
        const spy = vi.spyOn(store, 'toggleService').mockResolvedValue({})
        
        const wrapper = mount(AdminServices, {
            global: {
                stubs: ['router-link']
            }
        })
        
        await flushPromises()
        await wrapper.find('[data-test-toggle]').trigger('click')
        
        expect(spy).toHaveBeenCalledWith(1)

    })
})
