<template>
    <router-link 
        :to="{ name: 'project.detail', params: { id: project.id }}"
        class="group relative aspect-[4/5] overflow-hidden rounded-3xl bg-[#111] border border-[#222] hover:border-[#d4af37]/50 transition-all duration-700"
    >
        <!-- Image -->
        <img 
            v-if="project.cover_image" 
            :src="project.cover_image" 
            :alt="project.title"
            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
        >
        <div v-else class="w-full h-full flex items-center justify-center text-[#222]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>

        <!-- Badges -->
        <div class="absolute top-6 left-6 flex gap-2 z-20">
            <span class="px-3 py-1 bg-[#d4af37] text-[#0a0a0a] text-[10px] font-black uppercase tracking-widest rounded-full">
                {{ project.category }}
            </span>
            <span v-if="project.is_featured" class="px-3 py-1 bg-white text-[#0a0a0a] text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ $t('projects.featured') }}
            </span>
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity duration-500"></div>

        <!-- Content -->
        <div class="absolute bottom-0 left-0 w-full p-8 z-20 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
            <h3 class="text-[1.75rem] leading-[2] font-black text-white mb-2 tracking-tighter">
                {{ project.title }}
            </h3>
            <div class="flex items-center gap-2 text-[#d4af37] text-sm font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                {{ $t('projects.view_all') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M7 16l-4-4m0 0l4-4m-4 4h18' : 'M17 8l4 4m0 0l-4 4m4-4H3'" />
                </svg>
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { computed } from 'vue'
import { useLocaleStore } from '@/stores/localeStore'

const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

defineProps({
    project: {
        type: Object,
        required: true
    }
})
</script>
