<template>
  <div class="space-y-12">
    <!-- Welcome Header -->
    <div class="flex items-end justify-between">
      <div class="space-y-2">
        <h1 class="text-4xl font-black text-[#111111] uppercase tracking-tighter">{{ $t('admin.control_center') }}</h1>
        <p class="text-sm text-[#555555] font-bold tracking-widest uppercase">{{ $t('admin.welcome_back') }}, {{ auth.user?.name }}</p>
      </div>
      
      <div class="flex items-center gap-4">
        <RouterLink to="/admin/projects" class="px-6 py-3 bg-[#FFFFFF] hover:bg-[#F0ECE1] text-[#111111] text-[10px] font-bold uppercase tracking-widest rounded-xl border border-[#E0DACE] transition-all shadow-xs">
           {{ $t('admin.quick_add_project') }}
        </RouterLink>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
      <StatCard 
        :title="$t('admin.new_messages')" 
        :value="dashboardStore.stats.new_contacts_count" 
        :icon="Mail" 
        link="/admin/contacts"
        color="rose"
      />
      <StatCard 
        :title="$t('admin.total_projects')" 
        :value="dashboardStore.stats.total_projects" 
        :icon="Construction" 
        link="/admin/projects"
        color="amber"
      />
      <StatCard 
        :title="$t('admin.active_services')" 
        :value="dashboardStore.stats.active_services" 
        :icon="Wrench" 
        link="/admin/services"
        color="blue"
      />
      <StatCard 
        :title="$t('admin.media_files')" 
        :value="dashboardStore.stats.media_count" 
        :icon="ImageIcon" 
        link="/admin/media"
        color="emerald"
      />
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
      <div class="bg-[#FFFFFF] border border-[#E0DACE] rounded-[2.5rem] overflow-hidden shadow-md">
        <div class="p-8 border-b border-[#E0DACE] flex items-center justify-between">
          <h3 class="text-xs font-bold text-[#111111] uppercase tracking-[0.3em]">{{ $t('admin.recent_contacts') }}</h3>
          <RouterLink to="/admin/contacts" class="text-[10px] font-bold text-[#C5A880] uppercase hover:text-[#111111] transition-colors">
            {{ $t('admin.view_inbox') }} &rarr;
          </RouterLink>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full text-start">
            <thead>
              <tr class="bg-[#F7F5F0]">
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest">{{ $t('admin.tbl_name') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest">{{ $t('admin.tbl_phone') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest">{{ $t('admin.tbl_service') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest">{{ $t('admin.tbl_date') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-[#555555] uppercase tracking-widest">{{ $t('admin.tbl_status') }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-[#E0DACE]">
              <tr v-for="contact in dashboardStore.stats.recent_contacts" :key="contact.id" class="hover:bg-[#F0ECE1]/50 transition-colors group">
                <td class="px-8 py-6">
                  <span class="text-sm font-bold text-[#111111]">{{ contact.name }}</span>
                </td>
                <td class="px-8 py-6">
                  <span class="text-xs text-[#555555]">{{ contact.phone }}</span>
                </td>
                <td class="px-8 py-6">
                  <span class="px-3 py-1 bg-[#F0ECE1] text-[10px] font-bold text-[#111111] rounded-lg border border-[#E0DACE] uppercase tracking-tighter">
                    {{ contact.service }}
                  </span>
                </td>
                <td class="px-8 py-6">
                  <span class="text-[10px] font-bold text-[#555555] uppercase">{{ new Date(contact.created_at).toLocaleDateString() }}</span>
                </td>
                <td class="px-8 py-6">
                  <span 
                    class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter"
                    :class="{
                      'bg-red-500/10 text-red-500': contact.status === 'new',
                      'bg-[#F0ECE1] text-[#555555]': contact.status === 'read',
                      'bg-emerald-500/10 text-emerald-500': contact.status === 'replied'
                    }"
                  >
                    {{ $t('admin.status_' + contact.status) }}
                  </span>
                </td>
              </tr>
              <tr v-if="!dashboardStore.stats.recent_contacts.length">
                <td colspan="5" class="px-8 py-20 text-center">
                  <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.no_recent_activity') }}</p>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useDashboardStore } from '@/stores/dashboardStore'
import StatCard from '@/components/admin/StatCard.vue'
import { Mail, Construction, Wrench, Image as ImageIcon } from 'lucide-vue-next'

const auth = useAuthStore()
const dashboardStore = useDashboardStore()

onMounted(() => {
    dashboardStore.fetchStats()
})
</script>
