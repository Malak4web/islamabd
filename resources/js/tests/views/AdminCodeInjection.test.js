import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminCodeInjection from '@/views/admin/AdminCodeInjection.vue'
import { useCodeInjectionStore } from '@/stores/codeInjectionStore'
import api from '@/api/axios'

vi.mock('@/api/axios')
vi.mock('monaco-editor-vue3', () => ({
    default: {
        name: 'VueMonacoEditor',
        template: '<div><textarea :value="value" @input="$emit(\'update:value\', $event.target.value)"></textarea></div>',
        props: ['value'],
        emits: ['update:value']
    }
}))

describe('AdminCodeInjection.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
        api.get.mockResolvedValue({ data: { data: [] } })
    })

    it('renders list of injections', async () => {
        api.get.mockResolvedValueOnce({ 
            data: { 
                data: [{ id: 1, name: 'GTM', location: 'head', code: '<script>', is_active: true, pages: null }] 
            } 
        })
        
        const wrapper = mount(AdminCodeInjection)
        await flushPromises()
        
        expect(wrapper.text()).toContain('GTM')
        expect(wrapper.text()).toContain('head')
        expect(wrapper.text()).toContain('All Pages')
    })

    it('location badge shows correct color', async () => {
        api.get.mockResolvedValueOnce({ 
            data: { 
                data: [
                    { id: 1, name: 'T1', location: 'head', code: '', is_active: true, pages: null },
                    { id: 2, name: 'T2', location: 'body_start', code: '', is_active: true, pages: null },
                    { id: 3, name: 'T3', location: 'body_end', code: '', is_active: true, pages: null }
                ] 
            } 
        })
        
        const wrapper = mount(AdminCodeInjection)
        await flushPromises()
        
        expect(wrapper.find('.text-blue-500').exists()).toBe(true) // head
        expect(wrapper.find('.text-purple-500').exists()).toBe(true) // body_start
        expect(wrapper.find('.text-orange-500').exists()).toBe(true) // body_end
    })

    it('add new button opens modal', async () => {
        const wrapper = mount(AdminCodeInjection)
        await flushPromises()
        
        const btn = wrapper.findAll('button').find(b => b.text().includes('Add New'))
        await btn.trigger('click')
        
        expect(wrapper.text()).toContain('New Snippet')
        expect(wrapper.find('input[type="text"]').exists()).toBe(true)
    })

    it('all pages option sends null pages', async () => {
        const store = useCodeInjectionStore()
        vi.spyOn(store, 'createInjection').mockResolvedValue(true)
        
        const wrapper = mount(AdminCodeInjection)
        await flushPromises()
        
        await wrapper.findAll('button').find(b => b.text().includes('Add New')).trigger('click')
        
        // Mode is "all" by default
        await wrapper.findAll('input[type="text"]')[0].setValue('GTM')
        await wrapper.find('select').setValue('head')
        
        await wrapper.findAll('button').find(b => b.text() === 'Save Snippet').trigger('click')
        await flushPromises()
        
        expect(store.createInjection).toHaveBeenCalledWith(expect.objectContaining({
            pages: null
        }))
    })

    it('active toggle calls store toggle', async () => {
        api.get.mockResolvedValueOnce({ 
            data: { 
                data: [{ id: 1, name: 'GTM', location: 'head', code: '<script>', is_active: true, pages: null }] 
            } 
        })
        
        const store = useCodeInjectionStore()
        vi.spyOn(store, 'toggleInjection').mockResolvedValue(true)
        
        const wrapper = mount(AdminCodeInjection)
        await flushPromises()
        
        // Find the toggle button in the list (the one with the relative class)
        const toggleBtn = wrapper.find('button.relative')
        await toggleBtn.trigger('click')
        
        expect(store.toggleInjection).toHaveBeenCalledWith(1)
    })
})
