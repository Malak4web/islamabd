<template>
  <div 
    class="fixed z-40 flex flex-col gap-6 bottom-12 floating-container"
    :class="store.isArabic ? 'right-8' : 'left-8'"
  >
    <a 
      v-for="social in floatingLinks" 
      :key="social.name" 
      :href="social.url" 
      target="_blank"
      class="group relative flex items-center justify-center w-14 h-14 rounded-full border border-[#d4af37] bg-[#1a1a1a] text-[#d4af37] transition-all duration-500 hover:bg-[#d4af37] hover:text-black hover:scale-110 active:scale-95 shadow-2xl"
      :aria-label="social.name"
    >
      <i :class="[social.icon, 'text-xl relative z-10']"></i>
      
      <!-- Tooltip -->
      <span 
        class="absolute whitespace-nowrap px-4 py-2 bg-black border border-[#d4af37]/20 text-[#d4af37] text-[10px] font-black tracking-[0.2em] uppercase rounded-lg opacity-0 transition-all duration-500 pointer-events-none group-hover:opacity-100"
        :class="store.isArabic ? 'right-full mr-6' : 'left-full ml-6'"
      >
        {{ social.name }}
      </span>

      <!-- Subtle inner glow -->
      <span class="absolute inset-0 rounded-full border border-[#d4af37]/20 animate-pulse-slow z-0"></span>
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useLocaleStore } from '@/stores/localeStore'

const settingStore = useSettingStore()
const store = useLocaleStore()

const floatingLinks = computed(() => [
  { 
    name: 'Phone', 
    key: 'phone',
    icon: 'fas fa-phone', 
    url: `tel:${settingStore.settings.phone_main || settingStore.settings.contact_phone_eg}`,
  },
  { 
    name: 'WhatsApp', 
    key: 'whatsapp',
    icon: 'fab fa-whatsapp', 
    url: settingStore.settings.whatsapp_url || (settingStore.settings.whatsapp_number ? `https://wa.me/${settingStore.settings.whatsapp_number}` : null),
  },
  { 
    name: 'Instagram', 
    key: 'instagram',
    icon: 'fab fa-instagram', 
    url: settingStore.settings.instagram_url,
  }
].filter(l => l.url && !l.url.includes('undefined')))
</script>

<style scoped>
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-15px); }
  100% { transform: translateY(0px); }
}

@keyframes pulse-slow {
  0%, 100% { transform: scale(1); opacity: 0.2; }
  50% { transform: scale(1.2); opacity: 0.5; }
}

.floating-container {
  animation: float 4s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}
</style>
