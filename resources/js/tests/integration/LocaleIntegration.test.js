import { describe, it, expect, beforeEach, vi, afterEach } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import { useLocaleStore } from '@/stores/localeStore'
import api from '@/api/axios'

describe('LocaleIntegration.test.js', () => {
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
    })

    afterEach(() => {
        vi.unstubAllGlobals()
    })

    it('switching to ar sets dir rtl on html element', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        
        expect(document.documentElement.getAttribute('dir')).toBe('rtl')
        expect(document.documentElement.getAttribute('lang')).toBe('ar')
    })

    it('switching to ar persists after page reload', () => {
        // Mock that localStorage has 'ar' from a previous session
        localStorageMock.getItem.mockReturnValue('ar')
        
        const store = useLocaleStore()
        store.initLocale()
        
        expect(store.locale).toBe('ar')
        expect(document.documentElement.getAttribute('dir')).toBe('rtl')
    })

    it('switching locale sends new accept language header', () => {
        const store = useLocaleStore()
        store.setLocale('ar')
        
        expect(api.defaults.headers['Accept-Language']).toBe('ar')
    })
})
