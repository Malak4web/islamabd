import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useServiceStore } from '@/stores/serviceStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('serviceStore', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('initial services is empty array', () => {
        const store = useServiceStore()
        expect(store.services).toEqual([])
    })

    it('fetchServices populates services', async () => {
        const store = useServiceStore()
        const mockData = [{ id: 1, title: 'Service 1' }]
        api.get.mockResolvedValueOnce({ data: { data: mockData } })

        await store.fetchServices()

        expect(store.services).toEqual(mockData)
        expect(api.get).toHaveBeenCalledWith('/v1/services')
    })

    it('createService adds to services array', async () => {
        const store = useServiceStore()
        const newService = { id: 2, title: 'New' }
        api.post.mockResolvedValueOnce({ data: { data: newService } })

        await store.createService({ title_en: 'New' })

        expect(store.services).toContainEqual(newService)
    })

    it('deleteService removes from services array', async () => {
        const store = useServiceStore()
        store.services = [{ id: 1 }, { id: 2 }]
        api.delete.mockResolvedValueOnce({})

        await store.deleteService(1)

        expect(store.services).toHaveLength(1)
        expect(store.services[0].id).toBe(2)
    })

    it('reorderServices sends ids to api', async () => {
        const store = useServiceStore()
        api.patch.mockResolvedValueOnce({})

        await store.reorderServices([3, 1, 2])

        expect(api.patch).toHaveBeenCalledWith('/admin/services/reorder', { order: [3, 1, 2] })
    })
})
