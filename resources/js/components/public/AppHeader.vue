<template>
  <header 
    class="fixed top-0 left-0 z-50 w-full transition-all duration-500"
    :class="isScrolled ? 'py-4 bg-black/90 backdrop-blur-md shadow-2xl' : 'py-8 bg-transparent'"
  >
    <div class="container flex items-center justify-between px-6 mx-auto">
      <!-- Logo -->
      <RouterLink to="/" class="flex items-center group">
        <div class="relative flex items-center h-12 md:h-16 transition-all duration-500">
           <img v-if="settingStore.settings.logo" :src="settingStore.settings.logo" alt="InDesign Logo" class="h-full w-auto object-contain transition-all duration-500 group-hover:brightness-125" />
           <div v-else class="flex items-center gap-3">
              <div class="relative flex items-center justify-center w-12 h-12 overflow-hidden transition-all duration-500 bg-white/10 rounded-2xl group-hover:bg-[#d4af37]/20">
                 <span class="text-xl font-bold tracking-tighter text-white uppercase transition-colors duration-500 group-hover:text-[#d4af37]">ID</span>
              </div>
              <div class="flex flex-col leading-none">
                <span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">INDESIGN</span>
                <span class="text-[10px] tracking-[0.3em] text-[#d4af37] uppercase font-bold">{{ $t('brand.tagline') }}</span>
              </div>
           </div>
        </div>
      </RouterLink>

      <!-- Desktop Nav -->
      <NavLinks class="hidden lg:flex items-center gap-10" />

      <!-- Right Side Actions -->
      <div class="flex items-center gap-6">
        <div class="hidden sm:block">
          <LanguageSwitcher />
        </div>
        
        <RouterLink 
          to="/contact" 
          class="hidden px-8 py-3 text-xs font-bold tracking-[0.2em] text-black uppercase transition-all duration-300 bg-[#d4af37] rounded-full hover:bg-white hover:scale-105 active:scale-95 md:block"
        >
          {{ $t('nav.contact') }}
        </RouterLink>

        <!-- Mobile Menu Toggle -->
        <MobileMenuToggle 
          class="lg:hidden" 
          :isOpen="isMenuOpen" 
          @toggle="isMenuOpen = !isMenuOpen" 
        />
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <Teleport to="body">
      <Transition name="fade">
        <div v-if="isMenuOpen" @click="isMenuOpen = false" class="fixed inset-0 z-[9998] lg:hidden bg-black/60 backdrop-blur-md"></div>
      </Transition>

      <Transition name="slide-right">
        <div v-if="isMenuOpen" class="fixed top-0 right-0 bottom-0 w-[85%] max-w-sm z-[9999] bg-[#050505] shadow-[-20px_0_80px_rgba(0,0,0,0.8)] lg:hidden flex flex-col border-l border-white/5">
          <!-- Close Header -->
          <div class="flex items-center justify-between p-8 border-b border-white/5">
             <span class="text-[10px] font-black tracking-[0.5em] text-[#d4af37] uppercase">{{ $t('nav.menu') || 'MENU' }}</span>
             <button @click="isMenuOpen = false" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/5 text-white hover:bg-[#d4af37] hover:text-black transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
             </button>
          </div>

          <!-- Navigation Links -->
          <div class="flex-grow py-8 px-8 overflow-y-auto">
            <nav class="flex flex-col gap-1">
               <RouterLink 
                  v-for="(link, index) in [
                    { name: 'Home', path: '/', key: 'home' },
                    { name: 'About', path: '/about', key: 'about' },
                    { name: 'Services', path: '/services', key: 'services' },
                    { name: 'Projects', path: '/projects', key: 'projects' },
                    { name: 'Contact', path: '/contact', key: 'contact' }
                  ]" 
                  :key="link.name"
                  :to="link.path"
                  class="group flex items-center justify-between py-5 border-b border-white/5 transition-all duration-300"
                  @click="isMenuOpen = false"
               >
                  <div class="flex items-baseline gap-4">
                     <span class="text-[9px] font-black text-[#d4af37] opacity-40">0{{ index + 1 }}</span>
                     <span class="text-xl font-black tracking-widest text-white uppercase group-hover:text-[#d4af37] group-hover:translate-x-2 transition-all duration-300">
                        {{ $t(`nav.${link.key}`) }}
                     </span>
                  </div>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#d4af37] opacity-0 group-hover:opacity-100 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="isAr ? 'M17 8l-4-4m0 0l4 4m-4-4H3' : 'M17 8l4 4m0 0l-4 4m4-4H3'" />
                  </svg>
               </RouterLink>
            </nav>
          </div>

          <!-- Mobile Menu Footer -->
          <div class="p-8 border-t border-white/5 bg-black/50 space-y-10">
            <div class="flex items-center justify-between">
              <LanguageSwitcher />
              <div class="flex gap-4">
                 <a v-for="social in ['facebook', 'instagram']" :key="social" href="#" class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-white/40">
                    <i :class="`fab fa-${social}`"></i>
                 </a>
              </div>
            </div>
            <RouterLink 
              to="/contact" 
              class="flex items-center justify-center w-full py-6 text-[11px] font-black tracking-[0.4em] text-black uppercase transition-all duration-300 bg-[#d4af37] rounded-2xl hover:bg-white active:scale-95"
              @click="isMenuOpen = false"
            >
              {{ $t('nav.contact') }}
            </RouterLink>
          </div>
        </div>
      </Transition>
    </Teleport>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useLocaleStore } from '@/stores/localeStore'
import NavLinks from './NavLinks.vue'
import LanguageSwitcher from './LanguageSwitcher.vue'
import MobileMenuToggle from './MobileMenuToggle.vue'

const settingStore = useSettingStore()
const localeStore = useLocaleStore()
const isScrolled = ref(false)
const isMenuOpen = ref(false)
const isAr = computed(() => localeStore.isArabic)

const watchMenu = (val) => {
  if (val) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}

import { watch } from 'vue'
watch(isMenuOpen, watchMenu)

const handleScroll = () => {
  isScrolled.value = window.scrollY > 50
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.slide-right-enter-active, .slide-right-leave-active {
  transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.slide-right-enter-from, .slide-right-leave-to {
  transform: translateX(100%);
}
</style>
