import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '@/stores/authStore'
import api from '@/api/axios'
import router from '@/router'

vi.mock('@/api/axios')
vi.mock('@/router', () => ({
    default: {
        push: vi.fn(),
        currentRoute: { value: { query: {} } }
    }
}))

describe('Auth Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
        vi.clearAllMocks()
    })

    it('initial state has null user', () => {
        const auth = useAuthStore()
        expect(auth.user).toBeNull()
        expect(auth.isLoggedIn).toBe(false)
    })

    it('login sets user on success', async () => {
        const auth = useAuthStore()
        const mockUser = { id: 1, name: 'Admin', email: 'admin@test.com' }
        
        api.get.mockResolvedValue({})
        api.post.mockResolvedValue({ data: { data: mockUser } })

        await auth.login('admin@test.com', 'password')

        expect(api.get).toHaveBeenCalledWith('/sanctum/csrf-cookie', { baseURL: '' })
        expect(auth.user).toEqual(mockUser)
        expect(auth.isLoggedIn).toBe(true)
        expect(router.push).toHaveBeenCalledWith({ name: 'admin.dashboard' })
    })

    it('logout clears user', async () => {
        const auth = useAuthStore()
        auth.user = { id: 1 }
        
        api.post.mockResolvedValue({})

        await auth.logout()

        expect(auth.user).toBeNull()
        expect(router.push).toHaveBeenCalledWith({ name: 'admin.login' })
    })

    it('fetchUser sets user if not already set', async () => {
        const auth = useAuthStore()
        const mockUser = { id: 1, name: 'Admin' }
        
        api.get.mockResolvedValue({ data: { data: mockUser } })

        const user = await auth.fetchUser()

        expect(user).toEqual(mockUser)
        expect(auth.user).toEqual(mockUser)
    })
})
