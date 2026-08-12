<template>
  <header class="h-20 bg-[#FFFFFF]/80 backdrop-blur-md border-b border-[#E0DACE] flex items-center justify-between px-8 sticky top-0 z-40">
    <!-- Left: Breadcrumbs -->
    <div class="flex items-center gap-4">
      <h2 class="text-sm font-bold text-[#111111] uppercase tracking-widest">{{ pageTitle }}</h2>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-6">
      <!-- Language Toggle -->
      <div class="flex items-center gap-2 p-1 bg-[#F0ECE1] rounded-lg border border-[#E0DACE]">
         <button 
           v-for="lang in ['en', 'ar']" 
           :key="lang"
           @click="localeStore.setLocale(lang)"
           class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition-all"
           :class="localeStore.locale === lang ? 'bg-[#C5A880] text-white shadow-xs' : 'text-[#555555] hover:text-[#111111]'"
         >
           {{ lang }}
         </button>
      </div>

      <!-- Notifications -->
      <button class="relative p-2 text-[#555555] hover:text-[#111111] transition-colors">
        <Bell class="w-5 h-5" />
        <span v-if="newContactsCount" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- Site Link -->
      <a href="/" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-[#F7F5F0] hover:bg-[#F0ECE1] text-[#111111] rounded-lg transition-all border border-[#E0DACE]">
        <ExternalLink class="w-4 h-4 text-[#C5A880]" />
        <span class="text-[10px] font-bold uppercase tracking-widest">View Site</span>
      </a>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useLocaleStore } from '@/stores/localeStore'
import { Bell, ExternalLink } from 'lucide-vue-next'

const route = useRoute()
const localeStore = useLocaleStore()

const newContactsCount = 0 // Mock

const pageTitle = computed(() => {
    const name = route.name?.split('.').pop()
    return name?.replace('_', ' ') || 'Dashboard'
})
</script>
