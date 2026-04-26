import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest'
import { useLocaleStore } from '@/stores/localeStore'
import i18n from '@/i18n'
import axios from 'axios'
import api from '@/api/axios'

describe('localeStore', () => {
    let localStorageMock;

    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
        
        localStorageMock = {
            getItem: vi.fn(),
            setItem: vi.fn(),
            clear: vi.fn()
        };
        vi.stubGlobal('localStorage', localStorageMock)
        document.documentElement.setAttribute('lang', 'en')
        document.documentElement.setAttribute('dir', 'ltr')
        i18n.global.locale.value = 'en'
    })

    afterEach(() => {
        vi.unstubAllGlobals()
    })

    it('initial locale defaults to en', () => {
        localStorageMock.getItem.mockReturnValue(null)
        const store = useLocaleStore()
        expect(store.locale).toBe('en')
    })

    it('setLocale ar sets locale to ar', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(store.locale).toBe('ar')
    })

    it('setLocale saves to localStorage', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(localStorageMock.setItem).toHaveBeenCalledWith('locale', 'ar')
    })

    it('setLocale ar updates i18n global locale', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(i18n.global.locale.value).toBe('ar')
    })

    it('setLocale ar sets html dir to rtl', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(document.documentElement.getAttribute('lang')).toBe('ar')
        expect(document.documentElement.getAttribute('dir')).toBe('rtl')
    })

    it('setLocale en sets html dir to ltr', () => {
        const store = useLocaleStore()
        store.setLocale('en')
        expect(document.documentElement.getAttribute('lang')).toBe('en')
        expect(document.documentElement.getAttribute('dir')).toBe('ltr')
    })

    it('setLocale updates axios header', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(axios.defaults.headers['Accept-Language']).toBe('ar')
        expect(api.defaults.headers['Accept-Language']).toBe('ar')
    })

    it('isArabic returns true when locale is ar', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        expect(store.isArabic).toBe(true)
        expect(store.isRTL).toBe(true)
    })

    it('initLocale reads saved locale from storage', () => {
        localStorageMock.getItem.mockReturnValue('ar')
        const store = useLocaleStore()
        store.initLocale()
        expect(store.locale).toBe('ar')
        expect(i18n.global.locale.value).toBe('ar')
    })
})
