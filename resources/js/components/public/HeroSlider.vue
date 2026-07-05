<template>
  <section class="relative h-screen overflow-hidden bg-black">
    <!-- Background Slides -->
    <div 
        v-for="(slide, index) in slides" 
        :key="index"
        class="absolute inset-0 transition-all duration-1000 ease-in-out transform"
        :class="[
            currentSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-110 pointer-events-none'
        ]"
    >
      <div 
        class="absolute inset-0 bg-center bg-cover scale-110 animate-ken-burns" 
        :style="{ backgroundImage: `url(${slide.image})` }"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-[#0a0a0a]"></div>
    </div>

    <!-- Content -->
    <div class="relative flex items-center h-full container mx-auto px-6 pt-40">
      <div class="max-w-4xl space-y-8">
        <div class="overflow-hidden">
           <span class="inline-block text-[#d4af37] text-sm md:text-base font-black tracking-[0.2em] uppercase mb-4 animate-slide-up">
              {{ currentSlideData.subtitle }}
           </span>
        </div>
        
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-black text-white uppercase tracking-tighter leading-tight">
          <span class="block overflow-hidden">
            <span class="inline-block animate-slide-up animation-delay-200">
                {{ currentSlideData.title_top }}
            </span>
          </span>
          <span class="block overflow-hidden">
            <span class="inline-block text-transparent bg-clip-text bg-gradient-to-r from-white via-white/80 to-[#d4af37] animate-slide-up animation-delay-400">
                {{ currentSlideData.title_bottom }}
            </span>
          </span>
        </h1>

        <p class="max-w-2xl text-base md:text-xl text-gray-300 leading-relaxed animate-fade-in animation-delay-600">
          {{ currentSlideData.description }}
        </p>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 pt-8 animate-fade-in animation-delay-800">
          <RouterLink to="/projects" class="w-full sm:w-auto px-10 py-4 text-center text-xs font-bold tracking-[0.2em] text-black uppercase transition-all duration-300 bg-[#d4af37] rounded-full hover:bg-white hover:scale-105 active:scale-95 shadow-[0_10px_30px_rgba(212,175,55,0.3)]">
            {{ $t('hero.cta_primary') }}
          </RouterLink>
          <RouterLink to="/contact" class="w-full sm:w-auto px-10 py-4 text-center text-xs font-bold tracking-[0.2em] text-white uppercase transition-all duration-300 border border-white/20 rounded-full hover:bg-white hover:text-black backdrop-blur-sm">
            {{ $t('hero.cta_secondary') }}
          </RouterLink>
        </div>
      </div>
    </div>

    <!-- Navigation dots -->
    <div class="absolute flex flex-col gap-4 right-4 sm:right-10 top-1/2 -translate-y-1/2 z-20">
      <button 
        v-for="(_, index) in slides" 
        :key="index"
        @click="goToSlide(index)"
        class="w-1.5 sm:w-2 transition-all duration-500 rounded-full"
        :class="currentSlide === index ? 'h-8 sm:h-12 bg-[#d4af37]' : 'h-1.5 sm:h-2 bg-white/20 hover:bg-white/40'"
      ></button>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 opacity-50 pointer-events-none">
      <div class="w-[1px] h-10 sm:h-16 bg-gradient-to-b from-transparent to-[#d4af37] animate-scroll-line"></div>
      <span class="text-[8px] sm:text-[10px] tracking-[0.4em] uppercase font-bold text-white/50">Scroll</span>
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    slides: {
        type: Array,
        default: () => [
            { 
                image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=2000',
                title_top: 'Innovative',
                title_bottom: 'Architecture',
                subtitle: 'Crafting Excellence',
                description: 'We transform visions into timeless structures, blending luxury with functional brilliance.'
            },
            { 
                image: 'https://images.unsplash.com/photo-1600607687940-4e524cb35a5a?auto=format&fit=crop&q=80&w=2000',
                title_top: 'Modern',
                title_bottom: 'Interiors',
                subtitle: 'Elegant Spaces',
                description: 'Designing bespoke interiors that reflect your personality and elevate your lifestyle.'
            }
        ]
    }
})

const currentSlide = ref(0)
const currentSlideData = computed(() => props.slides[currentSlide.value])

let interval;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.slides.length
}

const goToSlide = (index) => {
    currentSlide.value = index
    resetInterval()
}

const resetInterval = () => {
    clearInterval(interval)
    interval = setInterval(nextSlide, 8000)
}

onMounted(() => {
    resetInterval()
})

onUnmounted(() => {
    clearInterval(interval)
})
</script>

<style scoped>
@keyframes slide-up {
  from { transform: translateY(100%); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@keyframes fade-in {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
@keyframes scroll-line {
  0% { transform: scaleY(0); transform-origin: top; }
  50% { transform: scaleY(1); transform-origin: top; }
  50.1% { transform: scaleY(1); transform-origin: bottom; }
  100% { transform: scaleY(0); transform-origin: bottom; }
}

.animate-slide-up { animation: slide-up 1s cubic-bezier(0.19, 1, 0.22, 1) forwards; }
.animate-fade-in { animation: fade-in 1.2s cubic-bezier(0.19, 1, 0.22, 1) forwards; opacity: 0; }
.animate-scroll-line { animation: scroll-line 2s infinite cubic-bezier(0.77, 0, 0.175, 1); }

.animation-delay-200 { animation-delay: 0.2s; }
.animation-delay-400 { animation-delay: 0.4s; }
.animation-delay-600 { animation-delay: 0.6s; }
.animation-delay-800 { animation-delay: 0.8s; }
</style>
