<template>
  <aside 
    class="fixed top-0 z-50 h-screen transition-all duration-300 bg-[#FFFFFF]"
    :class="[
      isCollapsed ? 'w-20' : 'w-64',
      localeStore.isArabic ? 'right-0 border-l border-[#E0DACE]' : 'left-0 border-r border-[#E0DACE]'
    ]"
  >
    <!-- Header -->
    <div class="flex items-center h-20 px-6 border-b border-[#E0DACE]">
      <RouterLink to="/admin" class="flex items-center gap-3 overflow-hidden">
        <img 
          v-if="settingStore.settings.logo" 
          :src="settingStore.settings.logo" 
          alt="Eslam Abdulghani Designs Logo" 
          class="h-10 w-auto object-contain flex-shrink-0" 
        />
        <template v-else>
          <div class="w-8 h-8 bg-[#C5A880] rounded-lg flex items-center justify-center flex-shrink-0">
            <span class="font-black text-white text-xs">ID</span>
          </div>
          <span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-[#111111] uppercase truncate">ESLAM ABDULGHANI DESIGNS</span>
        </template>
      </RouterLink>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">
      <div v-for="item in navItems" :key="item.key">
        <RouterLink 
          :to="item.path" 
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group"
          :class="$route.name === item.routeName ? 'bg-[#C5A880] text-white shadow-md shadow-[#C5A880]/20' : 'text-[#555555] hover:bg-[#F0ECE1] hover:text-[#111111]'"
          :title="isCollapsed ? $t('admin.' + item.key) : ''"
        >
          <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
          <span v-if="!isCollapsed" class="font-bold text-xs uppercase tracking-widest">{{ $t('admin.' + item.key) }}</span>
          
          <!-- Badge for contacts -->
          <span v-if="!isCollapsed && item.key === 'inbox' && newContactsCount" class="ml-auto rtl:ml-0 rtl:mr-auto px-2 py-0.5 bg-red-500 text-white text-[10px] rounded-full">
            {{ newContactsCount }}
          </span>
        </RouterLink>
      </div>
    </nav>

    <!-- Footer / User -->
    <div class="absolute bottom-0 left-0 w-full p-4 border-t border-[#E0DACE] bg-[#FFFFFF]">
      <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F7F5F0] border border-[#E0DACE]">
        <div class="w-10 h-10 rounded-full bg-[#E0DACE] flex items-center justify-center text-[#111111] font-bold">
           {{ auth.user?.name?.charAt(0) }}
        </div>
        <div v-if="!isCollapsed" class="flex flex-col min-w-0">
          <span class="text-xs font-bold text-[#111111] truncate">{{ auth.user?.name }}</span>
          <button @click="handleLogout" class="text-[10px] font-bold text-[#555555] hover:text-red-500 text-left rtl:text-right uppercase tracking-tighter">
            {{ $t('admin.logout') }}
          </button>
        </div>
      </div>
    </div>

    <!-- Collapse Toggle -->
    <button 
      @click="isCollapsed = !isCollapsed"
      class="absolute top-24 w-6 h-6 bg-[#FFFFFF] border border-[#E0DACE] rounded-full flex items-center justify-center text-[#555555] hover:text-[#C5A880] transition-colors hidden lg:flex shadow-sm"
      :class="localeStore.isArabic ? '-left-3' : '-right-3'"
    >
      <svg 
        xmlns="http://www.w3.org/2000/svg" 
        class="w-3 h-3 transition-transform" 
        :class="{ 'rotate-180': isCollapsed }" 
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="localeStore.isArabic ? 'M9 5l7 7-7 7' : 'M15 19l-7-7 7-7'" />
      </svg>
    </button>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useContactStore } from '@/stores/contactStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSettingStore } from '@/stores/settingStore'
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
const localeStore = useLocaleStore()
const settingStore = useSettingStore()
const router = useRouter()
const isCollapsed = ref(false)

const newContactsCount = ref(0) // Should come from store

const navItems = [
  { name: 'Dashboard', key: 'dashboard', path: '/admin', routeName: 'admin.dashboard', icon: LayoutDashboard },
  { name: 'Pages', key: 'pages', path: '/admin/pages', routeName: 'admin.pages', icon: FileText },
  { name: 'Services', key: 'services', path: '/admin/services', routeName: 'admin.services', icon: Wrench },
  { name: 'Projects', key: 'projects', path: '/admin/projects', routeName: 'admin.projects', icon: Construction },
  { name: 'Inbox', key: 'inbox', path: '/admin/contacts', routeName: 'admin.contacts', icon: Mail },
  { name: 'Media', key: 'media', path: '/admin/media', routeName: 'admin.media', icon: ImageIcon },
  { name: 'Code Injection', key: 'code_injection', path: '/admin/code-injections', routeName: 'admin.code_injections', icon: Code },
  { name: 'Settings', key: 'settings', path: '/admin/settings', routeName: 'admin.settings', icon: Settings }
]

const handleLogout = async () => {
  await auth.logout()
  router.push({ name: 'admin.login' })
}

onMounted(async () => {
   if (!settingStore.settings.logo) {
       settingStore.fetchSettings()
   }
})
</script>
