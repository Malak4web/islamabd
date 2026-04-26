import { setActivePinia, createPinia } from 'pinia'
import { describe, it, expect, beforeEach, vi } from 'vitest'
import { useContactStore } from '@/stores/contactStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('contactStore', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('submitContact sends post to public api', async () => {
        const store = useContactStore()
        api.post.mockResolvedValueOnce({ data: { message: 'Success' } })

        const result = await store.submitContact({ name: 'Sara', message: 'Hello' })

        expect(result).toBe(true)
        expect(api.post).toHaveBeenCalledWith('/v1/contacts', { name: 'Sara', message: 'Hello' })
    })

    it('submitContact sets field errors on 422', async () => {
        const store = useContactStore()
        api.post.mockRejectedValueOnce({
            response: { status: 422, data: { errors: { name: ['Required'] } } }
        })

        try {
            await store.submitContact({})
        } catch (e) {}

        expect(store.formErrors.name).toEqual(['Required'])
    })

    it('markAsRead updates status in local state', async () => {
        const store = useContactStore()
        store.contacts = [{ id: 1, status: 'new' }]
        api.patch.mockResolvedValueOnce({ data: { data: { id: 1, status: 'read' } } })

        await store.markAsRead(1)

        expect(store.contacts[0].status).toBe('read')
    })

    it('bulkDelete removes contacts from array', async () => {
        const store = useContactStore()
        store.contacts = [{ id: 1 }, { id: 2 }, { id: 3 }]
        api.delete.mockResolvedValueOnce({})

        await store.bulkDelete([1, 2])

        expect(store.contacts).toHaveLength(1)
        expect(store.contacts[0].id).toBe(3)
        expect(api.delete).toHaveBeenCalledWith('/admin/contacts/bulk', { data: { ids: [1, 2] } })
    })
})
