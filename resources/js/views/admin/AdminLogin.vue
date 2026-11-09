<template>
    <div class="min-h-screen flex items-center justify-center bg-[#F7F5F0] font-['Outfit']">
        <div class="w-full max-w-md p-8 bg-[#FFFFFF] border border-[#E0DACE] rounded-2xl shadow-xl">
            <div class="text-center mb-10">
                <div v-if="settingStore.settings.logo" class="flex justify-center mb-4">
                    <img :src="settingStore.settings.logo" alt="Eslam Abdulghani Designs Logo" class="h-16 w-auto object-contain" />
                </div>
                <div v-else class="inline-flex p-3 rounded-xl bg-[#C5A880] mb-4 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-[#111111] mb-2 uppercase tracking-tight">Eslam Abdulghani Designs</h1>
                <p class="text-[#555555] text-xs font-bold uppercase tracking-widest">Secure Administrative Access</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <div>
                    <label for="admin-email" class="block text-xs font-bold uppercase tracking-widest text-[#222222] mb-2">Email Address</label>
                    <input 
                        id="admin-email"
                        v-model="form.email"
                        type="email" 
                        required
                        class="w-full px-4 py-3 bg-[#F7F5F0] border border-[#E0DACE] rounded-xl text-[#111111] focus:outline-none focus:ring-2 focus:ring-[#C5A880] focus:border-transparent transition-all"
                        placeholder="admin@eslamabdulghanidesigns.com"
                    >
                </div>

                <div>
                    <label for="admin-password" class="block text-xs font-bold uppercase tracking-widest text-[#222222] mb-2">Password</label>
                    <div class="relative">
                        <input 
                            id="admin-password"
                            v-model="form.password"
                            :type="showPassword ? 'text' : 'password'" 
                            required
                            class="w-full px-4 py-3 bg-[#F7F5F0] border border-[#E0DACE] rounded-xl text-[#111111] focus:outline-none focus:ring-2 focus:ring-[#C5A880] focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#555555] hover:text-[#111111] transition-colors"
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

                <div v-if="auth.error" class="p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-500 text-sm font-bold" data-error>
                    {{ auth.error }}
                </div>

                <button 
                    id="admin-login-submit"
                    type="submit"
                    :disabled="auth.isLoading"
                    class="w-full py-4 bg-[#C5A880] text-white font-bold rounded-xl hover:bg-[#111111] transition-all disabled:opacity-50 flex items-center justify-center gap-2 shadow-md uppercase tracking-widest text-xs"
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
import { reactive, ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useSettingStore } from '@/stores/settingStore'

const auth = useAuthStore()
const settingStore = useSettingStore()
const showPassword = ref(false)

onMounted(() => {
    if (!settingStore.settings.logo) {
        settingStore.fetchSettings()
    }
})

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
