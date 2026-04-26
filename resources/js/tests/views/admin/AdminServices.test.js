import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminServices from '@/views/admin/AdminServices.vue'
import { useServiceStore } from '@/stores/serviceStore'

describe('AdminServices.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders services from store', async () => {
        const store = useServiceStore()
        store.services = [
            { id: 1, title_en: 'Interior', title_ar: 'تصميم', is_active: true, order: 1 }
        ]
        vi.spyOn(store, 'fetchAdminServices').mockResolvedValue([])
        
        const wrapper = mount(AdminServices, {
            global: {
                stubs: {
                    draggable: {
                        template: '<div><slot v-for="item in $attrs.modelValue" :element="item" name="item" /></div>'
                    },
                    ServiceFormModal: true,
                    ConfirmModal: true
                }
            }
        })
        
        await flushPromises()
        expect(wrapper.text()).toContain('Interior')
    })

    it('add new button opens modal', async () => {
        const store = useServiceStore()
        vi.spyOn(store, 'fetchAdminServices').mockResolvedValue([])
        
        const wrapper = mount(AdminServices, {
            global: {
                stubs: ['draggable', 'ServiceFormModal', 'ConfirmModal']
            }
        })
        
        await wrapper.find('button').trigger('click')
        expect(wrapper.vm.showModal).toBe(true)
    })
})
