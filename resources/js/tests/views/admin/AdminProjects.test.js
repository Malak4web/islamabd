import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminProjects from '@/views/admin/AdminProjects.vue'
import { useProjectStore } from '@/stores/projectStore'

describe('AdminProjects.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders project grid by default', async () => {
        const store = useProjectStore()
        store.projects = [
            { id: 1, title_en: 'Villa X', title_ar: 'فيلا', category: 'residential', is_active: true }
        ]
        vi.spyOn(store, 'fetchAdminProjects').mockResolvedValue([])
        
        const wrapper = mount(AdminProjects, {
            global: {
                stubs: ['ProjectFormModal', 'ConfirmModal']
            }
        })
        
        expect(wrapper.text()).toContain('Villa X')
        expect(wrapper.vm.viewMode).toBe('grid')
    })

    it('switches to list view', async () => {
        const store = useProjectStore()
        vi.spyOn(store, 'fetchAdminProjects').mockResolvedValue([])
        
        const wrapper = mount(AdminProjects, {
            global: {
                stubs: ['ProjectFormModal', 'ConfirmModal']
            }
        })
        
        const listBtn = wrapper.findAll('button').find(b => b.html().includes('List'))
        if (listBtn) {
            await listBtn.trigger('click')
            expect(wrapper.vm.viewMode).toBe('list')
        }
    })
})
