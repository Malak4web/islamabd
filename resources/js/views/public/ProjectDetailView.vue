<template>
  <div v-if="projectStore.loading && !projectStore.currentProject" class="flex items-center justify-center min-h-screen bg-black">
    <div class="w-16 h-16 border-4 border-[#d4af37]/20 border-t-[#d4af37] rounded-full animate-spin"></div>
  </div>

  <main v-else-if="projectStore.currentProject" class="bg-[#0a0a0a] min-h-screen">
    <!-- Hero / Cover Image -->
    <section class="relative h-[80vh] overflow-hidden bg-black">
       <img 
         :src="projectStore.currentProject.cover_image" 
         :alt="isAr ? projectStore.currentProject.title_ar : projectStore.currentProject.title"
         class="object-cover w-full h-full opacity-60"
       />
       <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-black/40"></div>
       
       <div class="absolute bottom-12 sm:bottom-24 left-0 w-full">
         <div class="container px-6 mx-auto">
            <div class="max-w-4xl space-y-4 sm:space-y-6">
               <span class="px-4 py-2 text-[10px] font-bold tracking-[0.3em] text-[#d4af37] uppercase bg-[#d4af37]/10 backdrop-blur-md rounded-lg inline-block border border-[#d4af37]/20">
                 {{ isAr ? projectStore.currentProject.category_ar : projectStore.currentProject.category }}
               </span>
               <h1 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
                  {{ isAr ? projectStore.currentProject.title_ar : projectStore.currentProject.title }}
               </h1>
            </div>
         </div>
       </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 md:py-24">
      <div class="container px-6 mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
          <!-- Main Content -->
          <div class="lg:col-span-8 space-y-10 md:space-y-12">
            <div class="space-y-6">
               <h2 class="text-[1.75rem] leading-[2] font-black text-[#d4af37] uppercase">{{ $t('projects.concept') }}</h2>
               <p class="text-lg sm:text-xl text-gray-300 leading-relaxed whitespace-pre-line">
                  {{ isAr ? projectStore.currentProject.description_ar : projectStore.currentProject.description }}
               </p>
            </div>
            
            <!-- Gallery -->
            <div v-if="projectStore.currentProject.gallery?.length" class="space-y-12 pt-12">
               <h2 class="text-xs font-bold tracking-[0.5em] text-[#d4af37] uppercase">{{ $t('projects.journey') }}</h2>
               <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div 
                    v-for="(img, idx) in projectStore.currentProject.gallery" 
                    :key="idx"
                    @click="openLightbox(idx)"
                    class="relative group cursor-pointer overflow-hidden rounded-[2rem] bg-white/5 aspect-[4/3]"
                  >
                    <img :src="img" class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-[#d4af37]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center">
                       <span class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white scale-75 group-hover:scale-100 transition-transform duration-500">
                          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                       </span>
                    </div>
                  </div>
               </div>
            </div>
          </div>

          <!-- Project Metadata Sidebar -->
          <div class="lg:col-span-4">
             <div class="sticky top-32 p-10 bg-[#111] rounded-[2.5rem] border border-white/5 shadow-2xl space-y-10">
                <h3 class="text-[1.75rem] leading-[2] font-black text-white uppercase border-b border-white/10 pb-6">{{ $t('projects.brief') }}</h3>
                
                <div class="space-y-8">
                   <div v-if="projectStore.currentProject.client" class="space-y-2">
                      <span class="text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase">{{ $t('projects.client') }}</span>
                      <p class="text-sm font-bold text-white">{{ projectStore.currentProject.client }}</p>
                   </div>
                   <div v-if="projectStore.currentProject.location" class="space-y-2">
                      <span class="text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase">{{ $t('projects.location') }}</span>
                      <p class="text-sm font-bold text-white">{{ projectStore.currentProject.location }}</p>
                   </div>
                   <div v-if="projectStore.currentProject.year" class="space-y-2">
                      <span class="text-[10px] font-bold tracking-[0.2em] text-gray-500 uppercase">{{ $t('projects.year') }}</span>
                      <p class="text-sm font-bold text-white">{{ projectStore.currentProject.year }}</p>
                   </div>
                </div>

                <div class="pt-6">
                   <RouterLink to="/contact" class="flex items-center justify-center w-full py-5 text-xs font-bold tracking-[0.3em] text-black uppercase bg-[#d4af37] rounded-full transition-transform hover:scale-105 active:scale-95 shadow-xl shadow-[#d4af37]/10">
                      {{ $t('projects.inquire') }}
                   </RouterLink>
                </div>
             </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Lightbox overlay -->
    <Transition name="fade">
       <div v-if="lightbox.isOpen" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-2xl">
          <button @click="closeLightbox" class="absolute top-10 right-10 w-16 h-16 flex items-center justify-center text-white/50 hover:text-white transition-colors">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
          </button>
          
          <div class="relative w-full h-full p-12 flex items-center justify-center select-none">
             <button @click="prevSlide" class="absolute left-10 w-16 h-16 flex items-center justify-center text-white/30 hover:text-white transition-colors bg-white/5 rounded-full backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'" /></svg>
             </button>
             
             <div class="max-w-7xl max-h-full overflow-hidden rounded-3xl">
                <img :src="projectStore.currentProject.gallery[lightbox.index]" class="object-contain max-h-[80vh] mx-auto shadow-2xl" />
                <div class="mt-8 text-center">
                   <span class="text-[10px] font-bold tracking-[0.5em] text-[#d4af37] uppercase">
                      {{ lightbox.index + 1 }} / {{ projectStore.currentProject.gallery.length }}
                   </span>
                </div>
             </div>

             <button @click="nextSlide" class="absolute right-10 w-16 h-16 flex items-center justify-center text-white/30 hover:text-white transition-colors bg-white/5 rounded-full backdrop-blur-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M15 19l-7-7 7-7' : 'M9 5l7 7-7 7'" /></svg>
             </button>
          </div>
       </div>
    </Transition>

    <CtaBanner />
  </main>
</template>

<script setup>
import { reactive, onMounted, onUnmounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProjectStore } from '@/stores/projectStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import CtaBanner from '@/components/public/CtaBanner.vue'

const route = useRoute()
const projectStore = useProjectStore()
const localeStore = useLocaleStore()
const { currentProject } = storeToRefs(projectStore)

const isAr = computed(() => localeStore.isArabic)

useSeo(currentProject)

const lightbox = reactive({
    isOpen: false,
    index: 0
})

const openLightbox = (index) => {
    lightbox.index = index
    lightbox.isOpen = true
    document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
    lightbox.isOpen = false
    document.body.style.overflow = ''
}

const nextSlide = () => {
    lightbox.index = (lightbox.index + 1) % projectStore.currentProject.gallery.length
}

const prevSlide = () => {
    lightbox.index = (lightbox.index - 1 + projectStore.currentProject.gallery.length) % projectStore.currentProject.gallery.length
}

const handleKeydown = (e) => {
    if (!lightbox.isOpen) return
    if (e.key === 'Escape') closeLightbox()
    if (e.key === 'ArrowRight') nextSlide()
    if (e.key === 'ArrowLeft') prevSlide()
}

onMounted(() => {
    projectStore.fetchProject(route.params.id)
    window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = ''
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.5s cubic-bezier(0.19, 1, 0.22, 1); }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
