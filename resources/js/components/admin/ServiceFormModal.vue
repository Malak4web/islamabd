<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#111111]/40 backdrop-blur-md">
      <div class="w-full max-w-4xl bg-[#FFFFFF] border border-[#E0DACE] rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-10 border-b border-[#E0DACE] flex justify-between items-center">
          <div class="space-y-1">
            <h2 class="text-3xl font-black text-[#111111] uppercase tracking-tighter">
              {{ isEditing ? $t('admin.edit_service') : $t('admin.add_service') }}
            </h2>
            <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">
              {{ isEditing ? $t('admin.edit_service_desc') : $t('admin.add_service_desc') }}
            </p>
          </div>
          <button @click="$emit('close')" class="w-12 h-12 flex items-center justify-center text-[#555555] hover:text-[#111111] transition-colors bg-[#F7F5F0] rounded-2xl border border-[#E0DACE]">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Tabs -->
        <div class="flex px-10 border-b border-[#E0DACE] bg-[#F7F5F0]/50">
          <button 
            v-for="tab in [{ id: 'English', label: $t('admin.tab_english') }, { id: 'Arabic', label: $t('admin.tab_arabic') }, { id: 'Media', label: $t('admin.tab_media') }]" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-8 py-5 text-[10px] font-bold uppercase tracking-[0.2em] transition-all relative"
            :class="activeTab === tab.id ? 'text-[#C5A880]' : 'text-[#555555] hover:text-[#111111]'"
          >
            {{ tab.label }}
            <span v-if="activeTab === tab.id" class="absolute bottom-0 left-0 w-full h-1 bg-[#C5A880] rounded-full"></span>
          </button>
        </div>

        <!-- Form Content -->
        <div class="flex-1 p-10 overflow-y-auto space-y-10 custom-scrollbar">
          <!-- English Tab -->
          <div v-if="activeTab === 'English'" class="space-y-8">
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.service_title_en') }}</label>
              <input 
                v-model="form.title_en" 
                type="text" 
                placeholder="e.g. Interior Design"
                class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] font-bold"
              >
            </div>
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.service_desc_en') }}</label>
              <textarea 
                v-model="form.description_en" 
                rows="6" 
                placeholder="Describe the scope and excellence of this service..."
                class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] leading-relaxed"
              ></textarea>
            </div>
          </div>

          <!-- Arabic Tab -->
          <div v-if="activeTab === 'Arabic'" class="space-y-8" dir="rtl">
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest text-right">{{ $t('admin.service_title_ar') }}</label>
              <input 
                v-model="form.title_ar" 
                type="text" 
                class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] font-bold text-right"
              >
            </div>
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest text-right">{{ $t('admin.service_desc_ar') }}</label>
              <textarea 
                v-model="form.description_ar" 
                rows="6" 
                class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] leading-relaxed text-right"
              ></textarea>
            </div>
          </div>

          <!-- Media Tab -->
          <div v-if="activeTab === 'Media'" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.iconography') }}</label>
              <div class="relative group border-2 border-dashed border-[#E0DACE] hover:border-[#C5A880]/50 rounded-[2.5rem] p-10 text-center transition-all bg-[#F7F5F0]">
                <input type="file" @change="e => $emit('upload', { event: e, type: 'icon' })" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
                <div v-if="form.icon" class="mb-6 relative inline-block">
                  <div class="w-24 h-24 bg-[#F0ECE1] rounded-2xl p-4 flex items-center justify-center border border-[#E0DACE] group-hover:scale-105 transition-transform duration-500">
                    <img :src="form.icon" class="w-full h-full object-contain opacity-80" />
                  </div>
                </div>
                <div v-else class="w-24 h-24 mx-auto mb-6 bg-[#F0ECE1] rounded-2xl flex items-center justify-center text-[#555555]">
                  <ImageIcon class="w-10 h-10" />
                </div>
                <p class="text-xs font-bold text-[#555555] uppercase tracking-widest">{{ isEditing ? $t('admin.change_icon') : $t('admin.save_first_upload') }}</p>
                <p class="mt-2 text-[10px] text-[#555555] uppercase tracking-tighter">{{ $t('admin.svg_png_hint') }}</p>
              </div>
            </div>

            <div class="space-y-6">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.cover_narrative') }}</label>
              <div class="relative group border-2 border-dashed border-[#E0DACE] hover:border-[#C5A880]/50 rounded-[2.5rem] p-10 text-center transition-all bg-[#F7F5F0]">
                <input type="file" @change="e => $emit('upload', { event: e, type: 'image' })" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
                <div v-if="form.image" class="mb-6 h-24 overflow-hidden rounded-2xl border border-[#E0DACE] group-hover:scale-105 transition-transform duration-500">
                  <img :src="form.image" class="w-full h-full object-cover" />
                </div>
                <div v-else class="w-24 h-24 mx-auto mb-6 bg-[#F0ECE1] rounded-2xl flex items-center justify-center text-[#555555]">
                  <Upload class="w-10 h-10" />
                </div>
                <p class="text-xs font-bold text-[#555555] uppercase tracking-widest">{{ isEditing ? $t('admin.change_image') : $t('admin.save_first_upload') }}</p>
                <p class="mt-2 text-[10px] text-[#555555] uppercase tracking-tighter">{{ $t('admin.high_res_jpg_hint') }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer Actions -->
        <div class="p-10 bg-[#F7F5F0] border-t border-[#E0DACE] flex justify-end gap-6">
          <button @click="$emit('close')" class="px-8 py-4 text-[10px] font-bold text-[#555555] uppercase tracking-widest hover:text-[#111111] transition-colors">
            {{ $t('admin.discard') }}
          </button>
          <button 
            @click="$emit('save')"
            :disabled="loading"
            class="px-12 py-4 bg-[#C5A880] text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-[#111111] transition-all shadow-xl shadow-[#C5A880]/10 disabled:opacity-50"
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
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E0DACE; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #C5A880; }
</style>
