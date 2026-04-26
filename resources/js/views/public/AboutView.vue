<template>
  <div v-if="pageStore.isLoading" class="flex items-center justify-center min-h-screen bg-black">
    <div class="w-16 h-16 border-4 border-[#d4af37]/20 border-t-[#d4af37] rounded-full animate-spin"></div>
  </div>

  <main v-else class="bg-[#0a0a0a]">
    <!-- Page Header -->
    <section class="relative py-48 md:py-64 overflow-hidden bg-black flex items-center justify-center">
      <!-- Background with Ken Burns animation -->
      <div 
        class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns opacity-40" 
        :style="{ 
          backgroundImage: `url(${pageStore.currentPage?.sections?.find(s => s.key === 'hero')?.content.image || 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2000'})`
        }"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-transparent to-[#0a0a0a]"></div>
      
      <div class="relative container px-6 mx-auto text-center z-10">
        <span class="text-sm md:text-base font-black tracking-[0.6em] text-[#d4af37] uppercase mb-8 block animate-fade-in-up">
            {{ isAr ? (heroSection?.content.subtitle_ar || $t('about.legacy')) : (heroSection?.content.subtitle_en || $t('about.legacy')) }}
        </span>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter leading-tight animate-fade-in-up delay-100">
           {{ isAr ? (heroSection?.content.title_ar || $t('about.title')) : (heroSection?.content.title_en || $t('about.title')) }} 
           <span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">InDesign</span>
        </h1>
        
        <!-- Architectural Decorative Line -->
        <div class="w-24 h-[2px] bg-[#d4af37] mx-auto mt-12 animate-scale-x"></div>
      </div>
    </section>

    <!-- Sections from DB -->
    <div v-for="(section, index) in pageStore.currentPage?.sections?.filter(s => s.key !== 'hero')" :key="section.id">
        <!-- Alternating Content Sections (Story, Mission) -->
        <section v-if="section.key === 'story' || section.key === 'mission'" class="py-20 md:py-40 overflow-hidden" :class="index % 2 === 0 ? 'bg-[#0a0a0a]' : 'bg-[#0f0f0f]'">
          <div class="container px-6 mx-auto">
            <div class="grid grid-cols-1 gap-12 md:gap-24 lg:grid-cols-2 items-center" :class="{ 'lg:flex-row-reverse': index % 2 !== 0 }">
              <div class="space-y-8 md:space-y-10" :class="index % 2 !== 0 ? 'lg:order-2' : ''">
                <div class="space-y-4">
                   <h3 class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase">{{ $t(`about.${section.key}`) }}</h3>
                   <h2 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
                      {{ isAr ? section.content.title_ar : section.content.title_en }}
                   </h2>
                </div>
                <div class="w-24 h-1.5 bg-gradient-to-r from-[#d4af37] to-transparent rounded-full"></div>
                <p class="text-base sm:text-xl text-gray-400 leading-relaxed font-light whitespace-pre-line">
                   {{ isAr ? section.content.body_ar : section.content.body_en }}
                </p>
                <div v-if="isAr ? section.content.cta_ar : section.content.cta_en" class="pt-6">
                   <RouterLink to="/contact" class="inline-flex items-center gap-4 text-xs font-bold tracking-[0.3em] text-white uppercase group">
                      {{ isAr ? section.content.cta_ar : section.content.cta_en }}
                      <span class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center transition-all duration-500 group-hover:bg-[#d4af37] group-hover:border-[#d4af37] group-hover:translate-x-2">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M19 12H5m0 0l7 7m-7-7l7-7' : 'M17 8l4 4m0 0l-4 4m4-4H3'" />
                         </svg>
                      </span>
                   </RouterLink>
                </div>
              </div>
              <div class="relative group" :class="index % 2 !== 0 ? 'lg:order-1' : ''">
                  <div class="relative overflow-hidden rounded-[2rem] sm:rounded-[3rem] aspect-square lg:aspect-[4/5] shadow-2xl">
                     <img :src="section.content.image || 'https://images.unsplash.com/photo-1503387762-592dea58ef21?auto=format&fit=crop&q=80&w=1200'" :alt="section.content.title_en" class="object-cover w-full h-full transition-transform duration-1000 group-hover:scale-110" />
                     <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                  </div>
                  <!-- Decorative Elements -->
                  <div class="absolute -top-6 -left-6 sm:-top-10 sm:-left-10 w-20 sm:w-40 h-20 sm:h-40 border-l-2 border-t-2 border-[#d4af37]/30 rounded-tl-[2rem] sm:rounded-tl-[3rem] -z-10"></div>
                  <div class="absolute -bottom-6 -right-6 sm:-bottom-10 sm:-right-10 w-20 sm:w-40 h-20 sm:h-40 border-r-2 border-b-2 border-[#d4af37]/30 rounded-br-[2rem] sm:rounded-br-[3rem] -z-10"></div>
              </div>
            </div>
          </div>
        </section>

        <!-- Expertise Grid Section -->
        <section v-if="section.key === 'expertise'" class="py-20 md:py-40 bg-black relative overflow-hidden">
           <div class="absolute top-0 right-0 w-1/2 h-full bg-[#d4af37]/5 blur-[120px] rounded-full translate-x-1/2"></div>
           <div class="container px-6 mx-auto relative z-10">
              <div class="max-w-3xl mb-16 md:mb-24">
                 <h3 class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase mb-6">{{ $t('about.capabilities') }}</h3>
                 <h2 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
                    {{ isAr ? section.content.title_ar : section.content.title_en }}
                 </h2>
              </div>
              
              <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                 <div class="lg:col-span-7">
                    <p class="text-2xl md:text-3xl text-gray-300 font-light leading-relaxed mb-16">
                       {{ isAr ? section.content.body_ar : section.content.body_en }}
                    </p>
                    <div class="grid grid-cols-2 gap-10">
                       <div v-for="skill in ['construction', 'interior', 'landscape', 'architectural']" :key="skill" class="space-y-4">
                          <div class="w-12 h-1 border-t-2 border-[#d4af37]"></div>
                          <h4 class="text-[1.75rem] leading-[2] font-bold text-white uppercase tracking-widest">{{ $t(`about.${skill}`) }}</h4>
                       </div>
                    </div>
                 </div>
                 <div class="lg:col-span-5">
                    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/5] shadow-2xl group">
                       <img :src="section.content.image" class="w-full h-full object-cover grayscale transition-all duration-700 group-hover:grayscale-0 group-hover:scale-105" />
                       <div class="absolute inset-0 bg-[#d4af37]/20 mix-blend-overlay"></div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
    </div>

    <!-- Fallback if no sections in DB -->
    <template v-if="!pageStore.currentPage?.sections?.length">
        <section class="py-32 text-center">
            <p class="text-gray-500 uppercase tracking-widest">{{ $t('about.coming_soon') }}</p>
        </section>
    </template>
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'

const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const isAr = computed(() => localeStore.isArabic)
const heroSection = computed(() => pageStore.currentPage?.sections?.find(s => s.key === 'hero'))

useSeo(currentPage)

onMounted(() => {
    pageStore.fetchPage('about')
})
</script>
