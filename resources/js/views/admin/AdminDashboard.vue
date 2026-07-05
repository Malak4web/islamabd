<template>
  <div class="space-y-12">
    <!-- Welcome Header -->
    <div class="flex items-end justify-between">
      <div class="space-y-2">
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $t('admin.control_center') }}</h1>
        <p class="text-sm text-slate-500 font-bold tracking-widest uppercase">{{ $t('admin.welcome_back') }}, {{ auth.user?.name }}</p>
      </div>
      
      <div class="flex items-center gap-4">
        <RouterLink to="/admin/projects" class="px-6 py-3 bg-white/5 hover:bg-white/10 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl border border-white/5 transition-all">
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
      <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-slate-800 flex items-center justify-between">
          <h3 class="text-xs font-bold text-white uppercase tracking-[0.3em]">{{ $t('admin.recent_contacts') }}</h3>
          <RouterLink to="/admin/contacts" class="text-[10px] font-bold text-amber-500 uppercase hover:text-white transition-colors">
            {{ $t('admin.view_inbox') }} &rarr;
          </RouterLink>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full text-start">
            <thead>
              <tr class="bg-slate-950/50">
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.tbl_name') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.tbl_phone') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.tbl_service') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.tbl_date') }}</th>
                <th class="px-8 py-5 text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $t('admin.tbl_status') }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/50">
              <tr v-for="contact in dashboardStore.stats.recent_contacts" :key="contact.id" class="hover:bg-white/[0.02] transition-colors group">
                <td class="px-8 py-6">
                  <span class="text-sm font-bold text-white">{{ contact.name }}</span>
                </td>
                <td class="px-8 py-6">
                  <span class="text-xs text-slate-400">{{ contact.phone }}</span>
                </td>
                <td class="px-8 py-6">
                  <span class="px-3 py-1 bg-slate-800 text-[10px] font-bold text-slate-300 rounded-lg border border-slate-700 uppercase tracking-tighter">
                    {{ contact.service }}
                  </span>
                </td>
                <td class="px-8 py-6">
                  <span class="text-[10px] font-bold text-slate-500 uppercase">{{ new Date(contact.created_at).toLocaleDateString() }}</span>
                </td>
                <td class="px-8 py-6">
                  <span 
                    class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter"
                    :class="{
                      'bg-red-500/10 text-red-500': contact.status === 'new',
                      'bg-slate-700/20 text-slate-400': contact.status === 'read',
                      'bg-emerald-500/10 text-emerald-500': contact.status === 'replied'
                    }"
                  >
                    {{ $t('admin.status_' + contact.status) }}
                  </span>
                </td>
              </tr>
              <tr v-if="!dashboardStore.stats.recent_contacts.length">
                <td colspan="5" class="px-8 py-20 text-center">
                  <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.4em]">{{ $t('admin.no_recent_activity') }}</p>
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
