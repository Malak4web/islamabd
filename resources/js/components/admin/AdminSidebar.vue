<template>
  <aside 
    class="fixed top-0 left-0 z-50 h-screen transition-all duration-300 bg-slate-950 border-r border-slate-800"
    :class="isCollapsed ? 'w-20' : 'w-64'"
  >
    <!-- Header -->
    <div class="flex items-center h-20 px-6 border-b border-slate-800">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
          <span class="font-black text-slate-950 text-xs">ID</span>
        </div>
        <span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">INDESIGN</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
      <div v-for="item in navItems" :key="item.name">
        <RouterLink 
          :to="item.path" 
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group"
          :class="$route.name === item.routeName ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-white'"
          :title="isCollapsed ? item.name : ''"
        >
          <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
          <span v-if="!isCollapsed" class="font-bold text-xs uppercase tracking-widest">{{ item.name }}</span>
          
          <!-- Badge for contacts -->
          <span v-if="!isCollapsed && item.name === 'Inbox' && newContactsCount" class="ml-auto px-2 py-0.5 bg-red-500 text-white text-[10px] rounded-full">
            {{ newContactsCount }}
          </span>
        </RouterLink>
      </div>
    </nav>

    <!-- Footer / User -->
    <div class="absolute bottom-0 left-0 w-full p-4 border-t border-slate-800 bg-slate-950">
      <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-900 border border-slate-800">
        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold">
           {{ auth.user?.name?.charAt(0) }}
        </div>
        <div v-if="!isCollapsed" class="flex flex-col min-w-0">
          <span class="text-xs font-bold text-white truncate">{{ auth.user?.name }}</span>
          <button @click="handleLogout" class="text-[10px] font-bold text-slate-500 hover:text-red-400 text-left uppercase tracking-tighter">Logout</button>
        </div>
      </div>
    </div>

    <!-- Collapse Toggle -->
    <button 
      @click="isCollapsed = !isCollapsed"
      class="absolute -right-3 top-24 w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center text-slate-400 hover:text-amber-500 transition-colors hidden lg:flex"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 transition-transform" :class="{ 'rotate-180': isCollapsed }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useContactStore } from '@/stores/contactStore'
import { 
  LayoutDashboard, 
  Settings, 
  FileText, 
  Wrench, 
  Construction, 
  Mail, 
  Code, 
  Image as ImageIcon 
} from 'lucide-vue-next'

const auth = useAuthStore()
const contactStore = useContactStore()
const router = useRouter()
const isCollapsed = ref(false)

const newContactsCount = ref(0) // Should come from store

const navItems = [
  { name: 'Dashboard', path: '/admin', routeName: 'admin.dashboard', icon: LayoutDashboard },
  { name: 'Pages', path: '/admin/pages', routeName: 'admin.pages', icon: FileText },
  { name: 'Services', path: '/admin/services', routeName: 'admin.services', icon: Wrench },
  { name: 'Projects', path: '/admin/projects', routeName: 'admin.projects', icon: Construction },
  { name: 'Inbox', path: '/admin/contacts', routeName: 'admin.contacts', icon: Mail },
  { name: 'Media', path: '/admin/media', routeName: 'admin.media', icon: ImageIcon },
  { name: 'Code Injection', path: '/admin/code-injections', routeName: 'admin.code_injections', icon: Code },
  { name: 'Settings', path: '/admin/settings', routeName: 'admin.settings', icon: Settings }
]

const handleLogout = async () => {
  await auth.logout()
  router.push({ name: 'admin.login' })
}

onMounted(async () => {
   // Fetch new contacts count if needed
})
</script>
