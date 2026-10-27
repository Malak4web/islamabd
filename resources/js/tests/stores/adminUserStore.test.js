import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAdminUserStore } from '@/stores/adminUserStore'
import { useAuthStore } from '@/stores/authStore'
import api from '@/api/axios'

vi.mock('@/api/axios')

describe('Admin User Store & Roles', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('initial state has empty admins array', () => {
        const store = useAdminUserStore()
        expect(store.admins).toEqual([])
        expect(store.isLoading).toBe(false)
    })

    it('fetchAdmins loads members from api', async () => {
        const store = useAdminUserStore()
        const mockMembers = [
            { id: 1, name: 'Admin', email: 'admin@test.com', role: 'admin' },
            { id: 2, name: 'Editor', email: 'editor@test.com', role: 'data_entry' }
        ]
        api.get.mockResolvedValue({ data: { data: mockMembers } })

        await store.fetchAdmins()

        expect(api.get).toHaveBeenCalledWith('/admin/admins')
        expect(store.admins).toEqual(mockMembers)
    })

    it('createAdmin appends new member', async () => {
        const store = useAdminUserStore()
        const newMember = { id: 3, name: 'New Editor', email: 'new@test.com', role: 'data_entry' }
        api.post.mockResolvedValue({ data: { data: newMember } })

        const res = await store.createAdmin({ name: 'New Editor', email: 'new@test.com', role: 'data_entry', password: 'password123' })

        expect(api.post).toHaveBeenCalledWith('/admin/admins', expect.any(Object))
        expect(res).toEqual(newMember)
        expect(store.admins).toContainEqual(newMember)
    })

    it('deleteAdmin removes member from local list', async () => {
        const store = useAdminUserStore()
        store.admins = [
            { id: 1, name: 'Admin', role: 'admin' },
            { id: 2, name: 'Editor', role: 'data_entry' }
        ]
        api.delete.mockResolvedValue({ data: {} })

        await store.deleteAdmin(2)

        expect(api.delete).toHaveBeenCalledWith('/admin/admins/2')
        expect(store.admins).toHaveLength(1)
        expect(store.admins[0].id).toBe(1)
    })

    it('authStore computes isAdmin and isDataEntry accurately', () => {
        const auth = useAuthStore()

        auth.user = { id: 1, name: 'Admin', role: 'admin' }
        expect(auth.isAdmin).toBe(true)
        expect(auth.isDataEntry).toBe(false)

        auth.user = { id: 2, name: 'Editor', role: 'data_entry' }
        expect(auth.isAdmin).toBe(false)
        expect(auth.isDataEntry).toBe(true)
    })
})
