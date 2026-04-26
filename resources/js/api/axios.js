import axios from 'axios'
import { useAuthStore } from '@/stores/authStore'
import router from '@/router'

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
})

// Add a request interceptor to handle locale
api.interceptors.request.use(config => {
    config.headers['Accept-Language'] = localStorage.getItem('locale') || 'en'
    return config
})

// Add a response interceptor to handle 401 Unauthorized
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            const auth = useAuthStore()
            auth.clearUser()
            
            // Only redirect to login if we're not already on the login page
            if (router.currentRoute.value.name !== 'admin.login') {
                router.push({ name: 'admin.login' })
            }
        }
        return Promise.reject(error)
    }
)

export default api
