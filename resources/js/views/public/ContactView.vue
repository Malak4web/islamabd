<template>
  <main class="bg-[#0a0a0a] min-h-screen">
    <!-- Page Header -->
    <section class="relative py-48 md:py-64 overflow-hidden bg-[#050505] flex items-center justify-center">
      <!-- Background with Ken Burns animation -->
      <div 
        class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns opacity-60" 
        :style="{ backgroundImage: 'url(https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&q=80&w=2000)' }"
      ></div>
      <div class="absolute inset-0 bg-black/40"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-[#0a0a0a]"></div>
      
      <div class="relative container px-6 mx-auto text-center z-10">
        <span class="text-sm md:text-base font-black tracking-[0.6em] text-[#d4af37] uppercase mb-8 block animate-fade-in-up">
            {{ $t('contact.subtitle') }}
        </span>
        <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white uppercase tracking-tighter leading-[0.9] animate-fade-in-up delay-100">
           {{ $t('contact.title') }}
        </h1>
        
        <!-- Architectural Decorative Line -->
        <div class="w-24 h-[2px] bg-[#d4af37] mx-auto mt-12 animate-scale-x"></div>
      </div>
    </section>

    <!-- Contact Content -->
    <section class="py-20 md:py-32">
       <div class="container px-6 mx-auto">
          <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 md:gap-24">
             <!-- Contact Info Sidebar -->
             <div class="lg:col-span-5 space-y-12 md:space-y-16">
                 <div class="space-y-6" :class="isAr ? 'text-right' : 'text-left'">
                    <h2 class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase">{{ $t('contact.details') }}</h2>
                    <h3 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
                       {{ $t('contact.help') }}
                    </h3>
                 </div>

                <ul class="space-y-12">
                   <li class="flex items-start gap-8 group">
                      <div class="w-16 h-16 flex-shrink-0 bg-white/5 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:bg-[#d4af37] group-hover:text-black text-[#d4af37]">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                      </div>
                      <div class="space-y-2">
                         <span class="text-[10px] font-bold tracking-[0.3em] text-gray-500 uppercase">{{ $t('contact.studio') }}</span>
                         <p class="text-lg text-gray-300 font-medium max-w-xs">{{ settingStore.settings.address }}</p>
                      </div>
                   </li>
                   <li class="flex items-start gap-8 group">
                      <div class="w-16 h-16 flex-shrink-0 bg-white/5 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:bg-[#d4af37] group-hover:text-black text-[#d4af37]">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                      </div>
                      <div class="space-y-2">
                         <span class="text-[10px] font-bold tracking-[0.3em] text-gray-500 uppercase">{{ $t('contact.call') }}</span>
                         <p class="text-xl text-gray-300 font-bold transition-colors group-hover:text-white">{{ settingStore.settings.phone_main }}</p>
                         <p class="text-sm text-gray-600">{{ settingStore.settings.phone_2 }}</p>
                      </div>
                   </li>
                   <li class="flex items-start gap-8 group">
                      <div class="w-16 h-16 flex-shrink-0 bg-white/5 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:bg-[#d4af37] group-hover:text-black text-[#d4af37]">
                         <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                      </div>
                      <div class="space-y-2">
                         <span class="text-[10px] font-bold tracking-[0.3em] text-gray-500 uppercase">{{ $t('contact.email_us') }}</span>
                         <p class="text-xl text-gray-300 font-bold transition-colors group-hover:text-white">{{ settingStore.settings.email_main }}</p>
                         <p class="text-sm text-gray-600">contact@indesign.com</p>
                      </div>
                   </li>
                </ul>
             </div>

             <!-- Contact Form -->
             <div class="lg:col-span-7">
                <div class="p-6 sm:p-12 md:p-16 bg-[#111] rounded-[2rem] sm:rounded-[3rem] border border-white/5 shadow-2xl relative overflow-hidden">
                   <!-- Decorative bg -->
                   <div class="absolute top-0 right-0 w-64 h-64 bg-[#d4af37] opacity-5 rounded-full blur-[100px]"></div>
                   <div class="relative z-10">
                      <ContactForm />
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>

    <!-- Map Placeholder or Real Map -->
    <section class="h-[60vh] bg-black border-y border-white/5 grayscale hover:grayscale-0 transition-all duration-1000">
       <iframe 
         src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3608.2678688402127!2d55.2707828!3d25.2048493!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjXCsDEyJzE3LjUiTiA1NcKwMTYnMTQuOCJF!5e0!3m2!1sen!2sae!4v1620000000000!5m2!1sen!2sae" 
         width="100%" 
         height="100%" 
         style="border:0;" 
         allowfullscreen="" 
         loading="lazy"
       ></iframe>
    </section>
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import ContactForm from '@/components/public/ContactForm.vue'

const settingStore = useSettingStore()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const isAr = computed(() => localeStore.isArabic)

useSeo(currentPage)

onMounted(() => {
    pageStore.fetchPage('contact')
})
</script>
