import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useSettingStore } from '@/stores/settingStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('Setting Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('initial state is empty', () => {
        const store = useSettingStore()
        expect(store.settings).toEqual({})
    })

    it('fetchSettings populates state', async () => {
        const store = useSettingStore()
        const mockData = { site_name: 'InDesign', phone: '123' }
        api.get.mockResolvedValue({ data: { data: mockData } })

        await store.fetchSettings()

        expect(store.settings).toEqual(mockData)
    })

    it('get helper returns computed value', async () => {
        const store = useSettingStore()
        store.settings = { site_name: 'Test' }
        
        const siteName = store.get('site_name')
        expect(siteName.value).toBe('Test')
        
        const phone = store.get('phone', 'N/A')
        expect(phone.value).toBe('N/A')
    })

    it('updateSetting performs optimistic update', async () => {
        const store = useSettingStore()
        api.put.mockResolvedValue({ data: {} })

        await store.updateSetting('site_name', 'New Name')

        expect(store.settings.site_name).toBe('New Name')
        expect(api.put).toHaveBeenCalledWith('/admin/settings/site_name', { value: 'New Name' })
    })
})
