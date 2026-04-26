<template>
  <header class="h-20 bg-slate-900/50 backdrop-blur-md border-b border-slate-800 flex items-center justify-between px-8 sticky top-0 z-40">
    <!-- Left: Breadcrumbs -->
    <div class="flex items-center gap-4">
      <h2 class="text-sm font-bold text-slate-100 uppercase tracking-widest">{{ pageTitle }}</h2>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-6">
      <!-- Language Toggle -->
      <div class="flex items-center gap-2 p-1 bg-slate-950 rounded-lg border border-slate-800">
         <button 
           v-for="lang in ['en', 'ar']" 
           :key="lang"
           @click="localeStore.setLocale(lang)"
           class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition-all"
           :class="localeStore.locale === lang ? 'bg-amber-500 text-slate-950' : 'text-slate-500 hover:text-white'"
         >
           {{ lang }}
         </button>
      </div>

      <!-- Notifications -->
      <button class="relative p-2 text-slate-400 hover:text-white transition-colors">
        <Bell class="w-5 h-5" />
        <span v-if="newContactsCount" class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- Site Link -->
      <a href="/" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-white/5 hover:bg-white/10 text-white rounded-lg transition-all border border-white/5">
        <ExternalLink class="w-4 h-4 text-amber-500" />
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
