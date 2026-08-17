<template>
  <div class="min-h-screen bg-[#F7F5F0] font-sans text-[#111111] selection:bg-[#C5A880] selection:text-white">
    <AdminSidebar ref="sidebar" />
    
    <div 
      class="transition-all duration-300 min-h-screen flex flex-col"
      :class="[
        localeStore.isArabic 
          ? (isSidebarCollapsed ? 'lg:pr-20 lg:pl-0' : 'lg:pr-64 lg:pl-0') 
          : (isSidebarCollapsed ? 'lg:pl-20 lg:pr-0' : 'lg:pl-64 lg:pr-0')
      ]"
    >
      <AdminTopBar />
      
      <main class="flex-1 p-8">
        <RouterView v-slot="{ Component }">
          <Transition name="fade-slide" mode="out-in">
            <component :is="Component" />
          </Transition>
        </RouterView>
      </main>
      
      <!-- Footer -->
      <footer class="p-8 text-center border-t border-[#E0DACE]">
        <p class="text-[10px] font-bold tracking-[0.2em] text-[#555555] uppercase">
          &copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS Control Panel. All Rights Reserved. • Developed by <a href="https://zadians.com/" target="_blank" rel="noopener noreferrer" class="text-gold-deep hover:underline">Zadians</a>
        </p>
      </footer>
    </div>

    <!-- Global Components -->
    <ToastNotification />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
import AdminTopBar from '@/components/admin/AdminTopBar.vue'
import ToastNotification from '@/components/admin/ToastNotification.vue'
import { useLocaleStore } from '@/stores/localeStore'

const sidebar = ref(null)
const localeStore = useLocaleStore()
const isSidebarCollapsed = computed(() => sidebar.value?.isCollapsed || false)
</script>

<style>
.fade-slide-enter-from {
  opacity: 0;
  transform: translateX(10px);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.25s ease;
}
</style>
