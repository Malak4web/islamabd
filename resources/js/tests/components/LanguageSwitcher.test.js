import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import LanguageSwitcher from '@/components/public/LanguageSwitcher.vue'
import { useLocaleStore } from '@/stores/localeStore'

describe('LanguageSwitcher.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.stubGlobal('localStorage', {
            getItem: vi.fn(),
            setItem: vi.fn(),
            clear: vi.fn()
        })
    })

    it('renders ar button when locale is en', () => {
        const store = useLocaleStore()
        store.setLocale('en')
        
        const wrapper = mount(LanguageSwitcher)
        expect(wrapper.text()).toContain('ع AR')
    })

    it('renders en button when locale is ar', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        
        const wrapper = mount(LanguageSwitcher)
        expect(wrapper.text()).toContain('EN')
    })

    it('click calls setLocale with opposite locale', async () => {
        const store = useLocaleStore()
        store.setLocale('en')
        vi.spyOn(store, 'setLocale')
        
        const wrapper = mount(LanguageSwitcher)
        await wrapper.find('button').trigger('click')
        
        expect(store.setLocale).toHaveBeenCalledWith('ar')
    })

    it('button label updates after locale change', async () => {
        const store = useLocaleStore()
        store.setLocale('en')
        
        const wrapper = mount(LanguageSwitcher)
        expect(wrapper.text()).toContain('ع AR')
        
        store.setLocale('ar')
        await flushPromises()
        
        expect(wrapper.text()).toContain('EN')
    })
})
