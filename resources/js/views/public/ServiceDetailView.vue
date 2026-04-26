<template>
  <div v-if="serviceStore.loading && !serviceStore.currentService" class="flex items-center justify-center min-h-screen bg-black">
    <div class="w-16 h-16 border-4 border-[#d4af37]/20 border-t-[#d4af37] rounded-full animate-spin"></div>
  </div>

  <main v-else-if="serviceStore.currentService" class="bg-[#0a0a0a] min-h-screen">
    <!-- Page Header -->
    <section class="relative py-32 md:py-48 overflow-hidden bg-black">
      <div class="absolute inset-0 bg-center bg-cover opacity-40" :style="{ backgroundImage: `url(${serviceStore.currentService.image || 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&q=80&w=2000'})` }"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/40 to-[#0a0a0a]"></div>
      
      <div class="relative container px-6 mx-auto text-center">
        <span class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase mb-6 block">{{ $t('services.expertise') }}</span>
        <h1 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
           {{ serviceStore.currentService.title }}
        </h1>
      </div>
    </section>

    <!-- Content -->
    <section class="py-20 md:py-32">
       <div class="container px-6 mx-auto">
          <div class="space-y-24">
             <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 md:gap-16 items-start">
                <div class="lg:col-span-8 space-y-10 md:space-y-12">
                   <div class="space-y-6 md:space-y-8">
                      <h2 class="text-[1.75rem] leading-[2] font-bold text-[#d4af37] uppercase">{{ $t('services.overview') }}</h2>
                      <p class="text-lg sm:text-2xl text-gray-300 leading-relaxed font-light whitespace-pre-line">
                         {{ isAr ? serviceStore.currentService.description_ar : serviceStore.currentService.description }}
                      </p>
                   </div>

                   <!-- Service Gallery -->
                   <div v-if="serviceStore.currentService.gallery && serviceStore.currentService.gallery.length > 0" class="space-y-8 pt-12">
                      <h2 class="text-[1.75rem] leading-[2] font-bold text-[#d4af37] uppercase">{{ $t('services.gallery') }}</h2>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         <div v-for="(image, index) in serviceStore.currentService.gallery" :key="index" class="aspect-video rounded-2xl overflow-hidden bg-white/5 border border-white/10 group">
                            <img :src="image" :alt="serviceStore.currentService.title" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                         </div>
                      </div>
                   </div>
                </div>

                <!-- Sidebar Inquiry -->
                <div class="lg:col-span-4 sticky top-32">
                   <div class="p-10 bg-gradient-to-br from-[#111] to-black rounded-3xl border border-white/5 shadow-2xl space-y-10">
                      <h3 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tight">{{ $t('services.inquiry_title') }}</h3>
                      <p class="text-sm text-gray-500 leading-relaxed">{{ $t('services.inquiry_text') }}</p>
                      <RouterLink to="/contact" class="flex items-center justify-center w-full py-5 text-xs font-bold tracking-[0.3em] text-black uppercase bg-[#d4af37] rounded-full transition-all hover:bg-white active:scale-95 shadow-xl shadow-[#d4af37]/10">
                         {{ $t('services.get_quote') }}
                      </RouterLink>
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>

    <CtaBanner />
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import CtaBanner from '@/components/public/CtaBanner.vue'

const route = useRoute()
const serviceStore = useServiceStore()
const localeStore = useLocaleStore()
const { currentService } = storeToRefs(serviceStore)

const isAr = computed(() => localeStore.isArabic)

useSeo(currentService)

onMounted(() => {
    serviceStore.fetchService(route.params.id)
})
</script>
