<template>
    <div class="bg-[#141414] p-8 md:p-12 rounded-3xl border border-[#222]">
        <div v-if="success" class="text-center py-12">
            <div class="w-20 h-20 bg-[#d4af37]/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#d4af37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-[1.75rem] leading-[2] font-bold mb-4 font-['Outfit'] text-white">{{ $t('contact.success_title') }}</h3>
            <p class="text-[#888] mb-8">{{ $t('contact.success_msg') }}</p>
            <button @click="resetForm" class="px-8 py-3 bg-[#d4af37] text-black font-bold uppercase tracking-widest text-sm rounded-xl hover:opacity-90 transition-opacity">
                {{ $t('contact.send_another') }}
            </button>
        </div>

        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
            <h3 class="text-[1.75rem] leading-[2] font-bold font-['Outfit'] mb-8 text-white">{{ $t('contact.form_heading') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase text-[#555] tracking-widest">{{ $t('contact.name') }} <span class="text-red-500">*</span></label>
                    <input v-model="form.name" type="text" class="w-full bg-[#1a1a1a] border border-[#333] p-4 rounded-xl outline-none focus:border-[#d4af37] transition-colors text-white" :class="{ 'border-red-500': v$.name.$error || store.formErrors.name }">
                    <p v-if="v$.name.$error" data-error class="text-red-500 text-xs mt-2">{{ $t('contact.req_name') }}</p>
                    <p v-if="store.formErrors.name" data-error class="text-red-500 text-xs mt-2">{{ store.formErrors.name[0] }}</p>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase text-[#555] tracking-widest">{{ $t('contact.phone') }} <span class="text-red-500">*</span></label>
                    <input v-model="form.phone" type="tel" @input="form.phone = form.phone.replace(/[^0-9+]/g, '')" class="w-full bg-[#1a1a1a] border border-[#333] p-4 rounded-xl outline-none focus:border-[#d4af37] transition-colors text-white" :class="{ 'border-red-500': v$.phone.$error || store.formErrors.phone }">
                    <p v-if="v$.phone.$error" data-error class="text-red-500 text-xs mt-2">{{ $t('contact.req_phone') }}</p>
                    <p v-if="store.formErrors.phone" data-error class="text-red-500 text-xs mt-2">{{ store.formErrors.phone[0] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase text-[#555] tracking-widest">{{ $t('contact.email') }}</label>
                    <input v-model="form.email" type="email" class="w-full bg-[#1a1a1a] border border-[#333] p-4 rounded-xl outline-none focus:border-[#d4af37] transition-colors text-white" :class="{ 'border-red-500': v$.email.$error || store.formErrors.email }">
                    <p v-if="v$.email.$error" data-error class="text-red-500 text-xs mt-2">{{ $t('contact.req_email') }}</p>
                    <p v-if="store.formErrors.email" data-error class="text-red-500 text-xs mt-2">{{ store.formErrors.email[0] }}</p>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase text-[#555] tracking-widest">{{ $t('contact.service') }}</label>
                    <div class="relative">
                        <select v-model="form.service" class="w-full bg-[#1a1a1a] border border-[#333] p-4 rounded-xl outline-none focus:border-[#d4af37] transition-colors appearance-none text-white">
                            <option value="">{{ $t('contact.select_service') }}</option>
                            <option v-for="service in serviceStore.services" :key="service.id" :value="service.title">{{ service.title }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-500">
                           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase text-[#555] tracking-widest">{{ $t('contact.message') }} <span class="text-red-500">*</span></label>
                <textarea v-model="form.message" rows="5" class="w-full bg-[#1a1a1a] border border-[#333] p-4 rounded-xl outline-none focus:border-[#d4af37] transition-colors text-white" :class="{ 'border-red-500': v$.message.$error || store.formErrors.message }"></textarea>
                <p v-if="v$.message.$error" data-error class="text-red-500 text-xs mt-2">{{ $t('contact.req_msg') }}</p>
                <p v-if="store.formErrors.message" data-error class="text-red-500 text-xs mt-2">{{ store.formErrors.message[0] }}</p>
            </div>

            <button 
                type="submit" 
                :disabled="store.isLoading"
                class="w-full py-4 bg-[#d4af37] text-black font-bold uppercase tracking-widest rounded-xl hover:bg-white transition-colors disabled:opacity-50 flex justify-center items-center"
            >
                <svg v-if="store.isLoading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ store.isLoading ? $t('contact.sending') : $t('contact.submit') }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required, minLength, email } from '@vuelidate/validators'
import { useContactStore } from '@/stores/contactStore'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'

const store = useContactStore()
const serviceStore = useServiceStore()
const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)
const success = ref(false)

const form = reactive({
    name: '',
    phone: '',
    email: '',
    service: '',
    message: ''
})

const numeric = (value) => !value || /^[0-9+]+$/.test(value)

const rules = {
    name: { required },
    phone: { required, numeric },
    email: { email },
    message: { required, minLength: minLength(10) }
}

const v$ = useVuelidate(rules, form)

const handleSubmit = async () => {
    const isFormCorrect = await v$.value.$validate()
    if (!isFormCorrect) return

    try {
        await store.submitContact(form)
        success.value = true
    } catch (error) {
        // Errors handled by store
    }
}

const resetForm = () => {
    success.value = false
    Object.assign(form, {
        name: '',
        phone: '',
        email: '',
        service: '',
        message: ''
    })
    v$.value.$reset()
}

onMounted(() => {
    if (!serviceStore.services.length) {
        serviceStore.fetchServices()
    }
})
</script>
