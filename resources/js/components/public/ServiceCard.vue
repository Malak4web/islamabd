<template>
    <router-link 
        :to="{ name: 'service.detail', params: { id: service.id }}"
        class="group relative p-12 rounded-[3.5rem] bg-[#111] transition-all duration-700 hover:-translate-y-6 shadow-2xl hover:shadow-[0_40px_100px_rgba(212,175,55,0.15)] overflow-hidden flex flex-col h-full border border-white/5 hover:border-[#d4af37]/30"
    >
        <!-- Background Index Number -->
        <div class="absolute top-8 right-12 text-[10rem] font-black text-white/[0.03] select-none leading-none transition-all duration-700 group-hover:text-[#d4af37]/5 group-hover:-translate-y-4">
            {{ String(service.id).padStart(2, '0') }}
        </div>

        <!-- Architectural Decorative Lines -->
        <div class="absolute top-0 left-12 w-[1px] h-24 bg-gradient-to-b from-[#d4af37]/40 to-transparent"></div>
        <div class="absolute top-24 left-0 w-16 h-[1px] bg-gradient-to-r from-[#d4af37]/40 to-transparent"></div>

        <!-- Icon Section -->
        <div class="relative z-10 mb-16">
            <div class="w-28 h-28 flex items-center justify-center rounded-[2.5rem] bg-white/5 border border-white/5 group-hover:bg-[#d4af37] transition-all duration-1000 transform group-hover:rotate-[15deg] group-hover:scale-110 shadow-2xl relative overflow-hidden">
                <!-- Inner glow -->
                <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <img 
                    v-if="service.icon" 
                    :src="service.icon" 
                    :alt="service.title" 
                    class="w-14 h-14 object-contain transition-all duration-1000 group-hover:brightness-0 group-hover:scale-110"
                >
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-[#d4af37] group-hover:text-black transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>

        <!-- Content Section -->
        <div class="relative z-10 flex-grow">
            <h3 class="text-[2.2rem] leading-[1] font-black text-white uppercase tracking-tighter mb-8 transition-all duration-700 group-hover:text-[#d4af37] group-hover:translate-x-3">
                {{ service.title }}
            </h3>
            
            <p class="text-gray-400 text-lg leading-relaxed line-clamp-3 mb-12 group-hover:text-gray-200 transition-colors duration-700">
                {{ service.description }}
            </p>
        </div>

        <!-- Footer Action -->
        <div class="relative z-10 flex items-center justify-between mt-auto pt-8 border-t border-white/5">
            <div class="flex items-center gap-4 text-[#d4af37] text-[11px] font-black uppercase tracking-[0.4em]">
                <span class="relative overflow-hidden inline-block py-1">
                    {{ $t('services.view_all') }}
                    <span class="absolute bottom-0 left-0 w-full h-[2px] bg-[#d4af37] transform -translate-x-full group-hover:translate-x-0 transition-transform duration-700 ease-out"></span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-full border border-[#d4af37]/30 flex items-center justify-center text-[#d4af37] transition-all duration-700 group-hover:bg-[#d4af37] group-hover:text-black group-hover:rotate-[360deg] group-hover:scale-110 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M7 16l-4-4m0 0l4-4m-4 4h18' : 'M17 8l4 4m0 0l-4 4m4-4H3'" />
                </svg>
            </div>
        </div>
        
        <!-- Subtle background reveal pattern -->
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-1000 pointer-events-none">
            <div class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_top_right,#d4af3711,transparent_70%)]"></div>
        </div>
    </router-link>
</template>

<script setup>
import { computed } from 'vue'
import { useLocaleStore } from '@/stores/localeStore'

const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

defineProps({
    service: {
        type: Object,
        required: true
    }
})
</script>
