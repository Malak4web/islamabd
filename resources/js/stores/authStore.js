import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'
import router from '@/router'

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null)
    const isLoading = ref(false)
    const error = ref(null)

    const isLoggedIn = computed(() => !!user.value)

    async function login(email, password) {
        isLoading.value = true
        error.value = null
        try {
            // First, get the CSRF cookie (Sanctum SPA requirement)
            await api.get('/sanctum/csrf-cookie', { baseURL: '' })
            
            const response = await api.post('/admin/login', { email, password })
            user.value = response.data.data
            
            // Redirect to dashboard or intended page
            const redirect = router.currentRoute.value.query.redirect || { name: 'admin.dashboard' }
            router.push(redirect)
            
            return response.data
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed'
            throw err
        } finally {
            isLoading.value = false
        }
    }

    async function logout() {
        try {
            await api.post('/admin/logout')
        } finally {
            clearUser()
            router.push({ name: 'admin.login' })
        }
    }

    async function fetchUser() {
        if (user.value) return user.value
        
        try {
            const response = await api.get('/admin/user')
            user.value = response.data.data
            return user.value
        } catch (err) {
            clearUser()
            throw err
        }
    }

    function clearUser() {
        user.value = null
    }

    return {
        user,
        isLoading,
        error,
        isLoggedIn,
        login,
        logout,
        fetchUser,
        clearUser
    }
})
