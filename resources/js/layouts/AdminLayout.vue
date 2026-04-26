<template>
  <div class="min-h-screen bg-slate-950 font-sans text-slate-100 selection:bg-amber-500 selection:text-slate-950">
    <AdminSidebar ref="sidebar" />
    
    <div 
      class="transition-all duration-300 min-h-screen flex flex-col"
      :class="isSidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'"
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
      <footer class="p-8 text-center border-t border-slate-900">
        <p class="text-[10px] font-bold tracking-[0.3em] text-slate-600 uppercase">
          &copy; {{ new Date().getFullYear() }} INDESIGN Control Panel. All Rights Reserved.
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

const sidebar = ref(null)
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
