import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import i18n from '@/i18n'
import axios from 'axios'
import api from '@/api/axios'

export const useLocaleStore = defineStore('locale', () => {
    const locale = ref(localStorage.getItem('locale') || 'en')

    const isArabic = computed(() => locale.value === 'ar')
    const isRTL = computed(() => locale.value === 'ar')

    function setLocale(newLocale) {
        locale.value = newLocale
        localStorage.setItem('locale', newLocale)
        
        i18n.global.locale.value = newLocale
        
        document.documentElement.setAttribute('lang', newLocale)
        document.documentElement.setAttribute('dir', isArabic.value ? 'rtl' : 'ltr')
        
        // Update both the default axios and our custom api instance headers
        axios.defaults.headers['Accept-Language'] = newLocale
        api.defaults.headers['Accept-Language'] = newLocale
    }

    function initLocale() {
        const saved = localStorage.getItem('locale')
        setLocale(saved || 'en')
    }

    return {
        locale,
        isArabic,
        isRTL,
        setLocale,
        initLocale
    }
})
