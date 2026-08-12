import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import LanguageSwitcher from '@/components/public/LanguageSwitcher.vue'
import { useLocaleStore } from '@/stores/localeStore'
import i18n from '@/i18n'

const mountSwitcher = () => mount(LanguageSwitcher, { global: { plugins: [i18n] } })

describe('LanguageSwitcher.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.stubGlobal('localStorage', {
            getItem: vi.fn(),
            setItem: vi.fn(),
            clear: vi.fn(),
        })
    })

    // The control is labelled with the language it switches TO, written in that
    // language's own script — the convention users actually recognise.
    it('offers Arabic when the locale is English', () => {
        useLocaleStore().setLocale('en')

        const wrapper = mountSwitcher()
        expect(wrapper.text()).toContain('العربية')
        expect(wrapper.find('button').attributes('lang')).toBe('ar')
    })

    it('offers English when the locale is Arabic', () => {
        useLocaleStore().setLocale('ar')

        const wrapper = mountSwitcher()
        expect(wrapper.text()).toContain('EN')
        expect(wrapper.find('button').attributes('lang')).toBe('en')
    })

    it('carries an accessible name beyond the two-letter label', () => {
        useLocaleStore().setLocale('en')

        expect(mountSwitcher().find('button').attributes('aria-label')).toBeTruthy()
    })

    it('click calls setLocale with the opposite locale', async () => {
        const store = useLocaleStore()
        store.setLocale('en')
        vi.spyOn(store, 'setLocale')

        const wrapper = mountSwitcher()
        await wrapper.find('button').trigger('click')

        expect(store.setLocale).toHaveBeenCalledWith('ar')
    })

    it('button label updates after locale change', async () => {
        const store = useLocaleStore()
        store.setLocale('en')

        const wrapper = mountSwitcher()
        expect(wrapper.text()).toContain('العربية')

        store.setLocale('ar')
        await flushPromises()

        expect(wrapper.text()).toContain('EN')
    })
})
