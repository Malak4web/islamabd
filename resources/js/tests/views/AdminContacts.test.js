import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminContacts from '@/views/admin/AdminContacts.vue'
import { useContactStore } from '@/stores/contactStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('AdminContacts.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
        api.get.mockResolvedValue({ 
            data: { data: [], meta: { current_page: 1, last_page: 1 } } 
        })
    })

    it('renders contacts table with rows', async () => {
        api.get.mockResolvedValue({ 
            data: { 
                data: [{ id: 1, name: 'Sara', status: 'new', created_at: '2023-01-01' }], 
                meta: { current_page: 1, last_page: 1 } 
            } 
        })
        
        const wrapper = mount(AdminContacts)
        await flushPromises()
        
        expect(wrapper.text()).toContain('Sara')
        expect(wrapper.text().toLowerCase()).toContain('new')
    })

    it('filter tabs call store with correct status', async () => {
        const wrapper = mount(AdminContacts)
        await flushPromises()
        
        const tabs = wrapper.findAll('button')
        const readTab = tabs.find(b => b.text().toLowerCase() === 'read')
        await readTab.trigger('click')
        
        expect(api.get).toHaveBeenCalledWith('/admin/contacts', {
            params: { page: 1, status: 'read' }
        })
    })

    it('new badge shows red styling', async () => {
        api.get.mockResolvedValue({ 
            data: { 
                data: [{ id: 1, name: 'Sara', status: 'new', created_at: '2023-01-01' }], 
                meta: { current_page: 1, last_page: 1 } 
            } 
        })
        
        const wrapper = mount(AdminContacts)
        await flushPromises()
        
        const badge = wrapper.find('.text-red-500')
        expect(badge.exists()).toBe(true)
    })

    it('click row opens detail modal', async () => {
        api.get.mockResolvedValue({ 
            data: { 
                data: [{ id: 1, name: 'Sara', message: 'Hello world', status: 'read', created_at: '2023-01-01' }], 
                meta: { current_page: 1, last_page: 1 } 
            } 
        })
        
        const wrapper = mount(AdminContacts)
        await flushPromises()
        
        const row = wrapper.findAll('h3').find(h => h.text() === 'Sara')
        await row.trigger('click')
        
        expect(wrapper.text()).toContain('Hello world')
    })

    it('bulk select enables bulk delete bar', async () => {
        api.get.mockResolvedValue({ 
            data: { 
                data: [{ id: 1, name: 'Sara', status: 'new', created_at: '2023-01-01' }], 
                meta: { current_page: 1, last_page: 1 } 
            } 
        })
        
        const wrapper = mount(AdminContacts)
        await flushPromises()
        
        const checkboxes = wrapper.findAll('input[type="checkbox"]')
        await checkboxes[1].setValue(true) // Select the first contact (index 0 is select all)
        
        expect(wrapper.text()).toContain('1Selected Inquiries')
        expect(wrapper.text()).toContain('Delete Permanently')
    })
})
