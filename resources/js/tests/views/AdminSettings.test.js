import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import AdminSettings from '@/views/admin/AdminSettings.vue'
import { useSettingStore } from '@/stores/settingStore'

vi.mock('@/api/axios', () => ({
    default: {
        get: vi.fn(() => Promise.resolve({ data: { data: {} } })),
        put: vi.fn(() => Promise.resolve({ data: {} })),
        post: vi.fn(() => Promise.resolve({ data: {} }))
    }
}))

describe('AdminSettings.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.stubGlobal('alert', vi.fn())
    })


    it('renders tabs correctly', () => {
        const wrapper = mount(AdminSettings)
        expect(wrapper.text()).toContain('General')
        expect(wrapper.text()).toContain('Contact')
        expect(wrapper.text()).toContain('Social')
    })

    it('shows general tab by default', () => {
        const wrapper = mount(AdminSettings)
        expect(wrapper.find('input[type="file"]').exists()).toBe(true)
        expect(wrapper.text()).toContain('Site Name')
    })

    it('switches tabs on click', async () => {
        const wrapper = mount(AdminSettings)
        const buttons = wrapper.findAll('button')
        const contactTab = buttons.find(b => b.text() === 'Contact')
        
        await contactTab.trigger('click')
        
        expect(wrapper.text()).toContain('Primary Phone')
        expect(wrapper.text()).toContain('Main Email')
    })

    it('calls bulkUpdate on save', async () => {
        const store = useSettingStore()
        const bulkSpy = vi.spyOn(store, 'bulkUpdate').mockResolvedValue({})
        // Mock fetchSettings since it's called in onMounted
        vi.spyOn(store, 'fetchSettings').mockResolvedValue({})
        
        const wrapper = mount(AdminSettings)
        await wrapper.find('button.bg-gradient-to-r').trigger('click')
        
        expect(bulkSpy).toHaveBeenCalled()
    })
})
