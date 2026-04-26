import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useSectionStore } from '@/stores/sectionStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('Section Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('fetchSections populates sections state', async () => {
        const store = useSectionStore()
        const mockSections = [{ id: 1, key: 'hero' }]
        api.get.mockResolvedValue({ data: { data: mockSections } })

        await store.fetchSections(1)

        expect(store.sections).toEqual(mockSections)
        expect(api.get).toHaveBeenCalledWith('/admin/sections/1')
    })

    it('updateSection sends put request', async () => {
        const store = useSectionStore()
        const mockContent = { title: 'New' }
        api.put.mockResolvedValue({ data: { data: { id: 1, content: mockContent } } })
        
        store.sections = [{ id: 1, content: { title: 'Old' } }]

        await store.updateSection(1, mockContent)

        expect(api.put).toHaveBeenCalledWith('/admin/sections/1', { content: mockContent })
        expect(store.sections[0].content.title).toBe('New')
    })

    it('reorderSections sends ordered ids', async () => {
        const store = useSectionStore()
        api.patch.mockResolvedValue({})

        await store.reorderSections([3, 1, 2])

        expect(api.patch).toHaveBeenCalledWith('/admin/sections/reorder', { order: [3, 1, 2] })
    })
})
