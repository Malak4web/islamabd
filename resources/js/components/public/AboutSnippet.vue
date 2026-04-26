<template>
  <section class="py-20 sm:py-32 bg-[#0a0a0a] overflow-hidden">
    <div class="container px-6 mx-auto">
      <div class="flex flex-col items-center gap-16 lg:gap-20 lg:flex-row">
        <!-- Image Column -->
        <div class="relative w-full lg:w-1/2 group">
          <div class="relative overflow-hidden rounded-3xl aspect-[4/5] shadow-2xl">
            <img 
              :src="content?.image || 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&q=80&w=1200'" 
              alt="About InDesign" 
              class="object-cover w-full h-full transition-transform duration-1000 group-hover:scale-110"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          </div>
          
          <!-- Decorative element -->
          <div 
            class="absolute p-6 sm:p-8 -bottom-6 sm:-bottom-10 shadow-2xl bg-[#111] rounded-2xl sm:rounded-3xl border border-white/5 backdrop-blur-xl z-20"
            :class="store.isArabic ? '-left-4 sm:-left-10' : '-right-4 sm:-right-10'"
          >
             <div class="flex items-center gap-3 sm:gap-4">
                <span class="text-3xl sm:text-5xl font-black text-[#d4af37]">15+</span>
                <div class="flex flex-col leading-none">
                   <span class="text-[8px] sm:text-xs font-bold tracking-[0.2em] uppercase text-white">{{ $t('about.years') }}</span>
                   <span class="text-[8px] sm:text-xs font-bold tracking-[0.2em] uppercase text-[#d4af37]">{{ $t('about.excellence') }}</span>
                </div>
             </div>
          </div>
        </div>

        <!-- Text Column -->
        <div class="w-full lg:w-1/2 space-y-8 lg:space-y-10 pt-10 lg:pt-0">
          <div class="space-y-4">
            <span class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase">
                {{ isAr ? (content?.label_ar || $t('about.label')) : (content?.label_en || $t('about.label')) }}
            </span>
            <h2 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
              {{ isAr ? (content?.title_ar || $t('about.title')) : (content?.title_en || $t('about.title')) }}
            </h2>
          </div>

          <p class="text-base sm:text-lg leading-relaxed text-gray-400">
            {{ isAr ? (content?.text_ar || $t('about.body')) : (content?.text_en || $t('about.body')) }}
          </p>

          <div class="grid grid-cols-2 gap-12 pt-6">
            <div v-for="stat in stats" :key="stat.key" class="space-y-2">
              <span class="text-3xl font-black text-white uppercase">{{ stat.value }}</span>
              <p class="text-[10px] font-bold tracking-[0.3em] uppercase text-[#d4af37]">{{ $t(`about.stats.${stat.key}`) }}</p>
            </div>
          </div>

          <div class="pt-10">
            <RouterLink to="/about" class="group flex items-center gap-6 text-xs font-bold tracking-[0.3em] text-white uppercase transition-all duration-300">
               {{ isAr ? (content?.cta_ar || $t('about.cta')) : (content?.cta_en || $t('about.cta')) }}
               <span class="flex items-center justify-center w-12 h-12 transition-all duration-300 bg-white/5 border border-white/10 rounded-full group-hover:bg-[#d4af37] group-hover:text-black group-hover:scale-110">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path :d="store.isArabic ? 'M19 12H5m0 0l7 7m-7-7l7-7' : 'M5 12h14m0 0l-7-7m7 7l-7 7'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                  </svg>
               </span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { useLocaleStore } from '@/stores/localeStore'

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
})

const store = useLocaleStore()
const isAr = computed(() => store.isArabic)

const stats = [
  { key: 'projects', value: '250+' },
  { key: 'clients', value: '180+' },
  { key: 'awards', value: '45' },
  { key: 'architects', value: '30+' }
]
</script>
