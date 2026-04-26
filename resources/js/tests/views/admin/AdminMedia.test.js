import { mount, flushPromises } from '@vue/test-utils'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { createPinia, setActivePinia } from 'pinia'
import AdminMedia from '@/views/admin/AdminMedia.vue'
import { useMediaStore } from '@/stores/mediaStore'

describe('AdminMedia.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('renders media grid', async () => {
        const store = useMediaStore()
        store.items = [
            { id: 1, url: '/test.jpg', filename: 'test.jpg' }
        ]
        vi.spyOn(store, 'fetchMedia').mockResolvedValue([])
        
        const wrapper = mount(AdminMedia, {
            global: {
                stubs: ['ConfirmModal']
            }
        })
        
        expect(wrapper.find('img').exists()).toBe(true)
        expect(wrapper.text()).toContain('test.jpg')
    })

    it('fetches media on mount', async () => {
        const store = useMediaStore()
        vi.spyOn(store, 'fetchMedia').mockResolvedValue([])
        
        mount(AdminMedia, {
            global: {
                stubs: ['ConfirmModal']
            }
        })
        
        expect(store.fetchMedia).toHaveBeenCalled()
    })
})
