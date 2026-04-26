import { describe, it, expect, beforeEach, vi } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import AdminLogin from '@/views/admin/AdminLogin.vue'
import { useAuthStore } from '@/stores/authStore'

describe('AdminLogin.vue', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })

    it('renders login form fields', () => {
        const wrapper = mount(AdminLogin)
        expect(wrapper.find('#admin-email').exists()).toBe(true)
        expect(wrapper.find('#admin-password').exists()).toBe(true)
        expect(wrapper.find('#admin-login-submit').exists()).toBe(true)
    })

    it('submits form calls login action', async () => {
        const auth = useAuthStore()
        const loginSpy = vi.spyOn(auth, 'login').mockResolvedValue({})
        
        const wrapper = mount(AdminLogin)
        
        await wrapper.find('#admin-email').setValue('admin@test.com')
        await wrapper.find('#admin-password').setValue('password')
        await wrapper.find('form').trigger('submit.prevent')
        
        expect(loginSpy).toHaveBeenCalledWith('admin@test.com', 'password')
    })

    it('shows error message when auth.error is set', async () => {
        const auth = useAuthStore()
        auth.error = 'Invalid credentials'
        
        const wrapper = mount(AdminLogin)
        
        expect(wrapper.find('[data-error]').text()).toContain('Invalid credentials')
    })

    it('disables button while loading', () => {
        const auth = useAuthStore()
        auth.isLoading = true
        
        const wrapper = mount(AdminLogin)
        
        expect(wrapper.find('#admin-login-submit').attributes('disabled')).toBeDefined()
    })
})
