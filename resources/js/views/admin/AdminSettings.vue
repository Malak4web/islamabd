<template>
    <div class="p-8 font-['Outfit'] bg-[#0a0a0a] min-h-screen text-white">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-[#d4af37] to-[#f3e5ab] bg-clip-text text-transparent">{{ $t('admin.settings') }}</h1>
            <div v-if="store.isLoading" class="flex items-center gap-2 text-[#d4af37]">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ $t('admin.saving') }}
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-8 border-b border-[#222]">
            <button 
                v-for="tab in tabs" 
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                    'pb-4 px-4 font-medium transition-all',
                    activeTab === tab.id ? 'border-b-2 border-[#d4af37] text-[#d4af37]' : 'text-[#888] hover:text-[#ccc]'
                ]"
            >
                {{ tab.id === 'general' ? $t('admin.tab_general') : (tab.id === 'contact' ? $t('admin.tab_contact') : $t('admin.tab_social')) }}
            </button>
        </div>

        <!-- Settings Form -->
        <div class="bg-[#141414] border border-[#222] rounded-2xl p-8 shadow-xl max-w-4xl text-start">
            <!-- General Tab -->
            <div v-if="activeTab === 'general'" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-4">{{ $t('admin.site_logo') }}</label>
                        <div class="relative group w-40 h-40 bg-[#1a1a1a] border-2 border-dashed border-[#333] rounded-xl flex items-center justify-center overflow-hidden mb-4">
                            <img v-if="getSetting('logo')" :src="getSetting('logo')" class="w-full h-full object-contain p-2">
                            <div v-else class="text-[#444]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="file" @change="handleFileUpload('logo', $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>

                        <label class="block text-sm font-medium text-[#888] mb-4">{{ $t('admin.site_favicon') }}</label>
                        <div class="relative group w-20 h-20 bg-[#1a1a1a] border-2 border-dashed border-[#333] rounded-xl flex items-center justify-center overflow-hidden">
                            <img v-if="getSetting('favicon')" :src="getSetting('favicon')" class="w-full h-full object-contain p-2">
                            <div v-else class="text-[#444]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="file" @change="handleFileUpload('favicon', $event)" class="absolute inset-0 opacity-0 cursor-pointer">
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.site_name') }}</label>
                            <input v-model="form.site_name" type="text" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.footer_copyright_text') }}</label>
                            <input v-model="form.footer_text" type="text" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Tab -->
            <div v-if="activeTab === 'contact'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.phone_primary') }}</label>
                        <input v-model="form.phone_1" type="tel" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.phone_secondary') }}</label>
                        <input v-model="form.phone_2" type="tel" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.email_main') }}</label>
                        <input v-model="form.email_main" type="email" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.email_inquiries') }}</label>
                        <input v-model="form.email_inquiries" type="email" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2">{{ $t('admin.address_en') }}</label>
                        <textarea v-model="form.address_en" rows="3" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#888] mb-2 text-right">{{ $t('admin.address_ar') }}</label>
                        <textarea v-model="form.address_ar" dir="rtl" rows="3" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Social Tab -->
            <div v-if="activeTab === 'social'" class="space-y-6">
                <div v-for="sc in ['facebook', 'instagram', 'linkedin', 'whatsapp', 'youtube']" :key="sc">
                    <label class="block text-sm font-medium text-[#888] mb-2 capitalize">{{ sc }}</label>
                    <input v-model="form[sc]" type="url" class="w-full px-4 py-3 bg-[#1a1a1a] border border-[#333] rounded-xl focus:ring-2 focus:ring-[#d4af37] outline-none">
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button 
                    @click="saveSettings"
                    :disabled="store.isLoading"
                    class="px-10 py-4 bg-gradient-to-r from-[#d4af37] to-[#b8860b] text-[#0a0a0a] font-bold rounded-xl hover:opacity-90 transition-all disabled:opacity-50"
                >
                    {{ $t('admin.save') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useI18n } from 'vue-i18n'

const store = useSettingStore()
const { t } = useI18n()
const activeTab = ref('general')
const rawSettings = ref([])

const tabs = [
    { id: 'general', name: 'General' },
    { id: 'contact', name: 'Contact' },
    { id: 'social', name: 'Social' }
]

const form = reactive({
    site_name: '',
    footer_text: '',
    phone_1: '',
    phone_2: '',
    email_main: '',
    email_inquiries: '',
    address_en: '',
    address_ar: '',
    facebook: '',
    instagram: '',
    linkedin: '',
    whatsapp: '',
    youtube: ''
})

const getSetting = (key) => store.settings[key]

onMounted(async () => {
    await store.fetchSettings()
    // Populate form from store
    Object.keys(form).forEach(key => {
        form[key] = store.settings[key] || ''
    })
})

const handleFileUpload = async (key, event) => {
    const file = event.target.files[0]
    if (file) {
        await store.uploadImage(key, file)
    }
}

const saveSettings = async () => {
    const settingsToSave = Object.keys(form).map(key => ({
        key: key,
        value: form[key]
    }))
    
    try {
        await store.bulkUpdate(settingsToSave)
        alert(t('admin.save_success'))
    } catch (err) {
        alert(t('admin.save_failed'))
    }
}
</script>
