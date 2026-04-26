<template>
  <footer class="pt-16 pb-12 md:pt-24 bg-black border-t border-white/5">
    <div class="container px-6 mx-auto">
      <div class="grid grid-cols-1 gap-12 md:gap-16 md:grid-cols-2 lg:grid-cols-4">
        <!-- Brand & About -->
        <div class="space-y-8">
          <RouterLink to="/" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 bg-white/10 rounded-xl">
               <span class="text-sm font-black text-white uppercase">ID</span>
            </div>
            <span class="text-xl font-black tracking-widest text-white uppercase">INDESIGN</span>
          </RouterLink>
          <p class="text-sm leading-relaxed text-gray-400 max-w-xs">
            {{ $t('footer.desc') }}
          </p>
          <div class="flex items-center gap-4">
            <a v-for="social in socialLinks" :key="social.name" :href="social.url" target="_blank" class="flex items-center justify-center w-10 h-10 transition-all duration-300 rounded-lg bg-white/5 hover:bg-[#d4af37] hover:text-black text-white/50">
               <i :class="social.icon"></i>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="space-y-8">
          <h4 class="text-[1.75rem] leading-[2] font-bold text-[#d4af37] uppercase">{{ $t('footer.quick_links') }}</h4>
          <nav class="flex flex-col gap-4">
            <RouterLink v-for="link in navLinks" :key="link.path" :to="link.path" class="text-sm text-gray-400 transition-colors duration-300 hover:text-white">
              {{ $t(`nav.${link.key}`) }}
            </RouterLink>
          </nav>
        </div>

        <!-- Services -->
        <div class="space-y-8">
          <h4 class="text-[1.75rem] leading-[2] font-bold text-[#d4af37] uppercase">{{ $t('footer.expertise') }}</h4>
          <nav class="flex flex-col gap-4">
             <RouterLink v-for="service in serviceStore.services.slice(0, 5)" :key="service.id" :to="`/services/${service.id}`" class="text-sm text-gray-400 transition-colors duration-300 hover:text-white">
                {{ service.title }}
             </RouterLink>
          </nav>
        </div>

        <!-- Contact Info -->
        <div class="space-y-8">
          <h4 class="text-[1.75rem] leading-[2] font-bold text-[#d4af37] uppercase">{{ $t('footer.contact') }}</h4>
          <ul class="space-y-6">
            <li class="flex items-start gap-4">
              <span class="flex-shrink-0 w-5 h-5 mt-1 text-[#d4af37]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
              </span>
              <span class="text-sm text-gray-400">{{ settingStore.settings.address }}</span>
            </li>
            <li class="flex items-center gap-4">
               <span class="flex-shrink-0 w-5 h-5 text-[#d4af37]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
              </span>
              <span class="text-sm text-gray-400">{{ settingStore.settings.phone_main }}</span>
            </li>
            <li class="flex items-center gap-4">
               <span class="flex-shrink-0 w-5 h-5 text-[#d4af37]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
              </span>
              <span class="text-sm text-gray-400">{{ settingStore.settings.email_main }}</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Copyright -->
      <div class="flex flex-col items-center justify-between gap-6 pt-12 mt-24 border-t border-white/5 md:flex-row">
        <p class="text-xs text-gray-500 uppercase tracking-[0.2em]">
          &copy; {{ new Date().getFullYear() }} INDESIGN. {{ $t('footer.rights') }}
        </p>
        <button @click="scrollToTop" class="group flex items-center gap-4 text-xs font-bold tracking-[0.3em] text-[#d4af37] uppercase">
          {{ $t('footer.back_to_top') }}
          <span class="flex items-center justify-center w-8 h-8 transition-transform duration-300 border border-[#d4af37]/30 rounded-full group-hover:-translate-y-1">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
          </span>
        </button>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'

const settingStore = useSettingStore()
const serviceStore = useServiceStore()
const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

onMounted(() => {
    if (!serviceStore.services.length) {
        serviceStore.fetchServices()
    }
})

const navLinks = [
  { path: '/', key: 'home' },
  { path: '/about', key: 'about' },
  { path: '/services', key: 'services' },
  { path: '/projects', key: 'projects' },
  { path: '/contact', key: 'contact' }
]

const socialLinks = computed(() => [
  { name: 'Facebook', icon: 'fab fa-facebook-f', url: settingStore.settings.facebook },
  { name: 'Instagram', icon: 'fab fa-instagram', url: settingStore.settings.instagram },
  { name: 'LinkedIn', icon: 'fab fa-linkedin-in', url: settingStore.settings.linkedin },
  { name: 'Twitter', icon: 'fab fa-twitter', url: settingStore.settings.twitter }
].filter(s => s.url))

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>
