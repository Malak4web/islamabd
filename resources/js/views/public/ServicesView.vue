<template>
  <main class="bg-[#0a0a0a]">
    <!-- Page Header -->
    <section class="relative py-48 md:py-64 overflow-hidden bg-[#050505] flex items-center justify-center">
      <!-- Background with Ken Burns animation -->
      <div 
        class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns opacity-50" 
        :style="{ backgroundImage: 'url(/images/services-bg.png)' }"
      ></div>
      <div class="absolute inset-0 bg-black/50"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-[#0a0a0a]"></div>
      
      <div class="relative container px-6 mx-auto text-center z-10">
        <span class="text-sm md:text-base font-black tracking-[0.6em] text-[#d4af37] uppercase mb-8 block animate-fade-in-up">
            {{ $t('services.expertise') }}
        </span>
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter leading-tight animate-fade-in-up delay-100">
           {{ $t('services.premium') }}
        </h1>
        
        <!-- Architectural Decorative Line -->
        <div class="w-24 h-[2px] bg-[#d4af37] mx-auto mt-12 animate-scale-x"></div>
      </div>
    </section>

    <!-- Services Grid -->
    <section class="py-20 md:py-32">
      <div class="container px-6 mx-auto">
        <div v-if="serviceStore.loading" class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
           <div v-for="i in 6" :key="i" class="h-[400px] rounded-[2rem] bg-white/5 animate-pulse"></div>
        </div>
        
        <div v-else class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
          <ServiceCard 
            v-for="service in serviceStore.services" 
            :key="service.id" 
            :service="service"
          />
        </div>
        
        <div v-if="!serviceStore.loading && !serviceStore.services.length" class="py-24 text-center">
           <p class="text-gray-500 uppercase tracking-[0.3em]">{{ $t('services.empty') }}</p>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <CtaBanner />
  </main>
</template>

<script setup>
import { onMounted } from 'vue'
import { useServiceStore } from '@/stores/serviceStore'
import { usePageStore } from '@/stores/pageStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import ServiceCard from '@/components/public/ServiceCard.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'

const serviceStore = useServiceStore()
const pageStore = usePageStore()
const { currentPage } = storeToRefs(pageStore)

useSeo(currentPage)

onMounted(() => {
    serviceStore.fetchServices()
    pageStore.fetchPage('services')
})
</script>
