<template>
  <div class="min-h-screen bg-[#0a0a0a] text-white selection:bg-[#d4af37] selection:text-black">
    <template v-if="!isAdmin">
      <AppHeader />
      <FloatingSocial />
    </template>
    
    <CodeInjector />
    
    <RouterView v-slot="{ Component }">
      <Transition name="page" mode="out-in">
        <component :is="Component" />
      </Transition>
    </RouterView>

    <AppFooter v-if="!isAdmin" />
  </div>
</template>

<script setup>
import { onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useSettingStore } from '@/stores/settingStore'
import AppHeader from '@/components/public/AppHeader.vue'
import AppFooter from '@/components/public/AppFooter.vue'
import FloatingSocial from '@/components/public/FloatingSocial.vue'
import CodeInjector from '@/components/CodeInjector.vue'

const route = useRoute()
const settingStore = useSettingStore()

const isAdmin = computed(() => route.path.startsWith('/admin'))

// Watch for favicon changes and update the browser tab icon
watch(() => settingStore.settings.favicon, (url) => {
  if (url) {
    const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/png';
    link.rel = 'shortcut icon';
    link.href = url + '?v=' + Date.now();
    document.getElementsByTagName('head')[0].appendChild(link);
  }
}, { immediate: true })

onMounted(() => {
  settingStore.fetchSettings()
})
</script>

<style>
.page-enter-from {
  opacity: 0;
  transform: translateY(20px);
}
.page-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
.page-enter-active,
.page-leave-active {
  transition: all 0.3s ease;
}

/* Global scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-track {
  background: #0a0a0a;
}
::-webkit-scrollbar-thumb {
  background: #222;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #d4af37;
}
</style>


