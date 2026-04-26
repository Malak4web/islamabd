<template>
    <div class="p-8 font-['Outfit'] bg-[#0a0a0a] min-h-screen text-white">
        <h1 class="text-3xl font-bold mb-8 bg-gradient-to-r from-[#d4af37] to-[#f3e5ab] bg-clip-text text-transparent">Content Management</h1>

        <div v-if="pageStore.isLoading" class="flex justify-center py-20">
            <svg class="animate-spin h-10 w-10 text-[#d4af37]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <router-link 
                v-for="page in pageStore.pages" 
                :key="page.id"
                :to="{ name: 'admin.sections', params: { id: page.id }}"
                class="group bg-[#141414] border border-[#222] p-6 rounded-2xl hover:border-[#d4af37] transition-all"
            >
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-bold group-hover:text-[#d4af37] transition-colors">{{ page.title_en }}</h2>
                        <p class="text-[#555] text-sm font-mono mt-1">/{{ page.slug }}</p>
                    </div>
                    <div class="p-2 bg-[#1a1a1a] rounded-lg text-[#888] group-hover:text-[#d4af37]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-[#888] text-sm">
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Sections
                    </span>
                    <span v-if="page.meta_title" class="px-2 py-0.5 bg-green-900/20 text-green-500 rounded-md text-[10px] uppercase font-bold tracking-wider">SEO Ready</span>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { usePageStore } from '@/stores/pageStore'

const pageStore = usePageStore()

onMounted(() => {
    pageStore.fetchPages()
})
</script>
