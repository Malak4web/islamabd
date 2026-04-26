<template>
    <div class="min-h-screen flex items-center justify-center bg-[#0a0a0a] font-['Outfit']">
        <div class="w-full max-w-md p-8 bg-[#141414] border border-[#2a2a2a] rounded-2xl shadow-2xl">
            <div class="text-center mb-10">
                <div class="inline-flex p-3 rounded-xl bg-gradient-to-tr from-[#d4af37] to-[#f3e5ab] mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#0a0a0a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">InDesign Dashboard</h1>
                <p class="text-[#888]">Secure Administrative Access</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label for="admin-email" class="block text-sm font-medium text-[#ccc] mb-2">Email Address</label>
                    <input 
                        id="admin-email"
                        v-model="form.email"
                        type="email" 
                        required
                        class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent transition-all"
                        placeholder="admin@indesign-co.com"
                    >
                </div>

                <div>
                    <label for="admin-password" class="block text-sm font-medium text-[#ccc] mb-2">Password</label>
                    <div class="relative">
                        <input 
                            id="admin-password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'" 
                            required
                            class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#555] hover:text-[#888] transition-colors"
                        >
                            <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div v-if="auth.error" class="p-3 rounded-lg bg-red-900/20 border border-red-900/50 text-red-400 text-sm" data-error>
                    {{ auth.error }}
                </div>

                <button 
                    id="admin-login-submit"
                    type="submit"
                    :disabled="auth.isLoading"
                    class="w-full py-4 bg-gradient-to-r from-[#d4af37] to-[#b8860b] text-[#0a0a0a] font-bold rounded-xl hover:opacity-90 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                >
                    <svg v-if="auth.isLoading" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ auth.isLoading ? 'Authenticating...' : 'Sign In' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '@/stores/authStore'

const auth = useAuthStore()
const showPassword = ref(false)

const form = reactive({
    email: '',
    password: ''
})

const handleLogin = async () => {
    try {
        await auth.login(form.email, form.password)
    } catch (err) {
        // Error is handled by store
    }
}
</script>
