import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useCodeInjectionStore } from '@/stores/codeInjectionStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('codeInjectionStore', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('fetchAdminInjections populates state', async () => {
        const store = useCodeInjectionStore()
        api.get.mockResolvedValueOnce({ data: { data: [{ id: 1 }] } })

        await store.fetchAdminInjections()

        expect(store.injections).toHaveLength(1)
        expect(api.get).toHaveBeenCalledWith('/admin/code-injections')
    })

    it('createInjection adds to beginning of array', async () => {
        const store = useCodeInjectionStore()
        store.injections = [{ id: 2 }]
        api.post.mockResolvedValueOnce({ data: { data: { id: 1 } } })

        await store.createInjection({ name: 'Test' })

        expect(store.injections).toHaveLength(2)
        expect(store.injections[0].id).toBe(1)
    })

    it('updateInjection updates existing array item', async () => {
        const store = useCodeInjectionStore()
        store.injections = [{ id: 1, name: 'Old' }]
        api.put.mockResolvedValueOnce({ data: { data: { id: 1, name: 'New' } } })

        await store.updateInjection(1, { name: 'New' })

        expect(store.injections[0].name).toBe('New')
    })

    it('toggleInjection flips is_active locally', async () => {
        const store = useCodeInjectionStore()
        store.injections = [{ id: 1, is_active: true }]
        api.patch.mockResolvedValueOnce({ data: { data: { id: 1, is_active: false } } })

        await store.toggleInjection(1)

        expect(store.injections[0].is_active).toBe(false)
    })

    it('deleteInjection removes from list', async () => {
        const store = useCodeInjectionStore()
        store.injections = [{ id: 1 }, { id: 2 }]
        api.delete.mockResolvedValueOnce({})

        await store.deleteInjection(1)

        expect(store.injections).toHaveLength(1)
        expect(store.injections[0].id).toBe(2)
    })
})
