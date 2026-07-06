<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-slate-950/90 backdrop-blur-md">
      <div class="w-full max-w-4xl bg-slate-900 border border-slate-800 rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-10 border-b border-slate-800 flex justify-between items-center">
          <div class="space-y-1">
            <h2 class="text-3xl font-black text-white uppercase tracking-tighter">
              {{ isEditing ? $t('admin.edit_service') : $t('admin.add_service') }}
            </h2>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">
              {{ isEditing ? $t('admin.edit_service_desc') : $t('admin.add_service_desc') }}
            </p>
          </div>
          <button @click="$emit('close')" class="w-12 h-12 flex items-center justify-center text-slate-500 hover:text-white transition-colors bg-white/5 rounded-2xl">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Tabs -->
        <div class="flex px-10 border-b border-slate-800 bg-slate-950/30">
          <button 
            v-for="tab in [{ id: 'English', label: $t('admin.tab_english') }, { id: 'Arabic', label: $t('admin.tab_arabic') }, { id: 'Media', label: $t('admin.tab_media') }]" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-8 py-5 text-[10px] font-bold uppercase tracking-[0.2em] transition-all relative"
            :class="activeTab === tab.id ? 'text-amber-500' : 'text-slate-500 hover:text-slate-300'"
          >
            {{ tab.label }}
            <span v-if="activeTab === tab.id" class="absolute bottom-0 left-0 w-full h-1 bg-amber-500 rounded-full"></span>
          </button>
        </div>

        <!-- Form Content -->
        <div class="flex-1 p-10 overflow-y-auto space-y-10 custom-scrollbar">
          <!-- English Tab -->
          <div v-if="activeTab === 'English'" class="space-y-8">
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest">{{ $t('admin.service_title_en') }}</label>
              <input 
                v-model="form.title_en" 
                type="text" 
                placeholder="e.g. Interior Design"
                class="w-full bg-slate-950 border border-slate-800 p-5 rounded-2xl outline-none focus:border-amber-500 transition-all text-white font-bold"
              >
            </div>
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest">{{ $t('admin.service_desc_en') }}</label>
              <textarea 
                v-model="form.description_en" 
                rows="6" 
                placeholder="Describe the scope and excellence of this service..."
                class="w-full bg-slate-950 border border-slate-800 p-5 rounded-2xl outline-none focus:border-amber-500 transition-all text-white leading-relaxed"
              ></textarea>
            </div>
          </div>

          <!-- Arabic Tab -->
          <div v-if="activeTab === 'Arabic'" class="space-y-8" dir="rtl">
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest text-right">{{ $t('admin.service_title_ar') }}</label>
              <input 
                v-model="form.title_ar" 
                type="text" 
                class="w-full bg-slate-950 border border-slate-800 p-5 rounded-2xl outline-none focus:border-amber-500 transition-all text-white font-bold text-right"
              >
            </div>
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest text-right">{{ $t('admin.service_desc_ar') }}</label>
              <textarea 
                v-model="form.description_ar" 
                rows="6" 
                class="w-full bg-slate-950 border border-slate-800 p-5 rounded-2xl outline-none focus:border-amber-500 transition-all text-white leading-relaxed text-right"
              ></textarea>
            </div>
          </div>

          <!-- Media Tab -->
          <div v-if="activeTab === 'Media'" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest">{{ $t('admin.iconography') }}</label>
              <div class="relative group border-2 border-dashed border-slate-800 hover:border-amber-500/50 rounded-[2.5rem] p-10 text-center transition-all bg-slate-950/50">
                <input type="file" @change="e => $emit('upload', { event: e, type: 'icon' })" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
                <div v-if="form.icon" class="mb-6 relative inline-block">
                  <div class="w-24 h-24 bg-white/5 rounded-2xl p-4 flex items-center justify-center border border-white/5 group-hover:scale-105 transition-transform duration-500">
                    <img :src="form.icon" class="w-full h-full object-contain filter invert opacity-80" />
                  </div>
                </div>
                <div v-else class="w-24 h-24 mx-auto mb-6 bg-slate-900 rounded-2xl flex items-center justify-center text-slate-700">
                  <ImageIcon class="w-10 h-10" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ isEditing ? $t('admin.change_icon') : $t('admin.save_first_upload') }}</p>
                <p class="mt-2 text-[10px] text-slate-600 uppercase tracking-tighter">{{ $t('admin.svg_png_hint') }}</p>
              </div>
            </div>

            <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-slate-500 tracking-widest">{{ $t('admin.cover_narrative') }}</label>
              <div class="relative group border-2 border-dashed border-slate-800 hover:border-amber-500/50 rounded-[2.5rem] p-10 text-center transition-all bg-slate-950/50">
                <input type="file" @change="e => $emit('upload', { event: e, type: 'image' })" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
                <div v-if="form.image" class="mb-6 h-24 overflow-hidden rounded-2xl border border-white/5 group-hover:scale-105 transition-transform duration-500">
                  <img :src="form.image" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-24 h-24 mx-auto mb-6 bg-slate-900 rounded-2xl flex items-center justify-center text-slate-700">
                  <Upload class="w-10 h-10" />
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ isEditing ? $t('admin.change_image') : $t('admin.save_first_upload') }}</p>
                <p class="mt-2 text-[10px] text-slate-600 uppercase tracking-tighter">{{ $t('admin.high_res_jpg_hint') }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-10 bg-slate-950/50 border-t border-slate-800 flex justify-end gap-6">
          <button @click="$emit('close')" class="px-8 py-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest hover:text-white transition-colors">
            {{ $t('admin.discard') }}
          </button>
          <button 
            @click="$emit('save')"
            :disabled="loading"
            class="px-12 py-4 bg-amber-500 text-slate-950 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-white hover:scale-105 active:scale-95 transition-all shadow-xl shadow-amber-500/10 disabled:opacity-50"
          >
            {{ loading ? $t('admin.saving') : (isEditing ? $t('admin.update_service') : $t('admin.initialize_service')) }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import { X, Image as ImageIcon, Upload } from 'lucide-vue-next'

defineProps({
  isOpen: Boolean,
  isEditing: Boolean,
  form: Object,
  loading: Boolean
})

defineEmits(['close', 'save', 'upload'])

const activeTab = ref('English')
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d4af37; }
</style>
