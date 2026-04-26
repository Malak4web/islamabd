<template>
  <section class="relative py-48 overflow-hidden bg-black flex items-center justify-center">
    <!-- Background with Ken Burns animation -->
    <div 
      class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns opacity-60" 
      :style="{ backgroundImage: 'url(https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&q=80&w=2000)' }"
    ></div>
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-[#0a0a0a]"></div>
    
    <div class="container px-6 mx-auto relative z-10">
      <div class="flex flex-col items-center text-center gap-12 mb-32">
        <div class="space-y-6 max-w-4xl">
           <span class="text-sm md:text-base font-black tracking-[0.6em] text-[#d4af37] uppercase block animate-fade-in-up">
                {{ isAr ? (content?.title_ar || $t('services.title')) : (content?.title_en || $t('services.title')) }}
           </span>
           <h2 class="text-2xl md:text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter leading-tight animate-fade-in-up delay-100">
              {{ isAr ? (content?.subtitle_ar || $t('services.subtitle')) : (content?.subtitle_en || $t('services.subtitle')) }}
           </h2>
           <!-- Decorative Line -->
           <div class="w-24 h-[2px] bg-[#d4af37] mx-auto mt-12 animate-scale-x"></div>
        </div>
        
        <RouterLink to="/services" class="group flex items-center gap-6 text-[11px] font-black tracking-[0.5em] text-[#d4af37] uppercase transition-all duration-500 hover:text-white">
          {{ $t('services.view_all') }}
          <span class="w-16 h-[1px] bg-[#d4af37] transition-all duration-500 group-hover:w-24 group-hover:bg-white"></span>
        </RouterLink>
      </div>

      <div v-if="serviceStore.isLoading" class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
         <div v-for="i in 3" :key="i" class="h-[400px] rounded-3xl bg-white/5 animate-pulse"></div>
      </div>
      
      <div v-else class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-3">
        <ServiceCard 
          v-for="service in serviceStore.services.slice(0, 6)" 
          :key="service.id" 
          :service="service"
        />
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'
import ServiceCard from './ServiceCard.vue'

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
})

const serviceStore = useServiceStore()
const store = useLocaleStore()
const isAr = computed(() => store.isArabic)

onMounted(() => {
    serviceStore.fetchServices()
})
</script>
