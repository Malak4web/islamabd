import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useProjectStore } from '@/stores/projectStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('projectStore', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('fetchProjects with category filter sends correct params', async () => {
        const store = useProjectStore()
        api.get.mockResolvedValueOnce({ data: { data: [], meta: {} } })

        await store.fetchProjects({ category: 'residential' })

        expect(api.get).toHaveBeenCalledWith('/v1/projects', { 
            params: { category: 'residential' } 
        })
    })

    it('uploadGallery adds images to project gallery', async () => {
        const store = useProjectStore()
        const newGallery = ['img1.jpg', 'img2.jpg']
        api.post.mockResolvedValueOnce({ data: { data: { gallery: newGallery } } })

        const result = await store.uploadGallery(1, [new File([], 'test.jpg')])

        expect(result).toEqual(newGallery)
        expect(api.post).toHaveBeenCalledWith('/admin/projects/1/gallery', expect.any(FormData))
    })

    it('removeGalleryImage sends delete request', async () => {
        const store = useProjectStore()
        api.delete.mockResolvedValueOnce({ data: { data: { gallery: [] } } })

        await store.removeGalleryImage(1, 'img1.jpg')

        expect(api.delete).toHaveBeenCalledWith('/admin/projects/1/gallery', {
            data: { image: 'img1.jpg' }
        })
    })
})
