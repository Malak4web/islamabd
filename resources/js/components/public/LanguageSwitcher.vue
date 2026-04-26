<template>
    <button 
        @click="toggleLocale" 
        class="flex items-center justify-center px-4 py-2 text-sm font-bold tracking-widest text-white transition-colors duration-300 rounded-lg hover:text-[#d4af37] bg-black/20 hover:bg-black/40 backdrop-blur-sm"
        aria-label="Toggle Language"
    >
        <span v-if="!store.isArabic">ع AR</span>
        <span v-else>EN</span>
    </button>
</template>

<script setup>
import { useLocaleStore } from '@/stores/localeStore'

const store = useLocaleStore()

const toggleLocale = () => {
    store.setLocale(store.isArabic ? 'en' : 'ar')
    // Full reload to ensure all data-bound components and styles refresh correctly
    window.location.reload()
}
</script>
