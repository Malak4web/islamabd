<template>
  <Transition name="fade">
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-[#111111]/40 backdrop-blur-md">
      <div class="w-full max-w-5xl bg-[#FFFFFF] border border-[#E0DACE] rounded-[3rem] shadow-2xl overflow-hidden flex flex-col max-h-[95vh] animate-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-10 border-b border-[#E0DACE] flex justify-between items-center">
          <div class="space-y-1">
            <h2 class="text-3xl font-black text-[#111111] uppercase tracking-tighter">
              {{ isEditing ? $t('admin.edit_project') : $t('admin.add_project') }}
            </h2>
            <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.2em]">
              {{ isEditing ? $t('admin.edit_project_desc') : $t('admin.add_project_desc') }}
            </p>
          </div>
          <button @click="$emit('close')" class="w-12 h-12 flex items-center justify-center text-[#555555] hover:text-[#111111] transition-colors bg-[#F7F5F0] rounded-2xl border border-[#E0DACE]">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Tabs -->
        <div class="flex px-10 border-b border-[#E0DACE] bg-[#F7F5F0]/50 overflow-x-auto no-scrollbar">
          <button 
            v-for="tab in [{ id: 'General', label: $t('admin.tab_general') }, { id: 'Bilingual', label: $t('admin.tab_bilingual') }, { id: 'Media', label: $t('admin.tab_media') }, { id: 'Gallery', label: $t('admin.tab_gallery') }]" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="px-8 py-5 text-[10px] font-bold uppercase tracking-[0.2em] transition-all relative shrink-0"
            :class="activeTab === tab.id ? 'text-[#C5A880]' : 'text-[#555555] hover:text-[#111111]'"
          >
            {{ tab.label }}
            <span v-if="activeTab === tab.id" class="absolute bottom-0 left-0 w-full h-1 bg-[#C5A880] rounded-full"></span>
          </button>
        </div>

        <!-- Form Content -->
        <div class="flex-1 p-10 overflow-y-auto space-y-10 custom-scrollbar">
          <!-- General Tab -->
          <div v-if="activeTab === 'General'" class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-4">
              <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.architectural_category') }}</label>
              <select 
                v-model="form.category" 
                class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] font-bold appearance-none"
              >
                <option value="residential">{{ $t('admin.cat_residential') }}</option>
                <option value="commercial">{{ $t('admin.cat_commercial') }}</option>
                <option value="hospitality">{{ $t('admin.cat_hospitality') }}</option>
                <option value="landscape">{{ $t('admin.cat_landscape') }}</option>
                <option value="retail">{{ $t('admin.cat_retail') }}</option>
              </select>
            </div>
            
            <div class="flex flex-col justify-center gap-6">
               <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-2xl hover:bg-[#F7F5F0] transition-colors">
                  <div class="relative w-12 h-6 bg-[#E0DACE] rounded-full p-1 transition-colors group-hover:bg-[#E0DACE]/80" :class="{ 'bg-[#C5A880] group-hover:bg-[#C5A880]/90': form.is_featured }">
                     <div class="w-4 h-4 bg-white rounded-full transition-transform duration-300 shadow-lg" :class="{ 'translate-x-6': form.is_featured }"></div>
                  </div>
                  <input type="checkbox" v-model="form.is_featured" class="hidden">
                  <span class="text-[10px] font-bold text-[#555555] uppercase tracking-widest group-hover:text-[#111111] transition-colors">{{ $t('admin.featured_on_home') }}</span>
               </label>
               
               <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-2xl hover:bg-[#F7F5F0] transition-colors">
                  <div class="relative w-12 h-6 bg-[#E0DACE] rounded-full p-1 transition-colors group-hover:bg-[#E0DACE]/80" :class="{ 'bg-emerald-500 group-hover:bg-emerald-400': form.is_active }">
                     <div class="w-4 h-4 bg-white rounded-full transition-transform duration-300 shadow-lg" :class="{ 'translate-x-6': form.is_active }"></div>
                  </div>
                  <input type="checkbox" v-model="form.is_active" class="hidden">
                  <span class="text-[10px] font-bold text-[#555555] uppercase tracking-widest group-hover:text-[#111111] transition-colors">{{ $t('admin.publicly_visible') }}</span>
               </label>
            </div>
          </div>

          <!-- Bilingual Tab -->
          <div v-if="activeTab === 'Bilingual'" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
              <div class="space-y-4">
                <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.project_title_en') }}</label>
                <input v-model="form.title_en" type="text" class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] font-bold">
              </div>
              <div class="space-y-4" dir="rtl">
                <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest text-right">{{ $t('admin.project_title_ar') }}</label>
                <input v-model="form.title_ar" type="text" class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] font-bold text-right">
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
              <div class="space-y-4">
                <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.design_narrative_en') }}</label>
                <textarea v-model="form.description_en" rows="8" class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] leading-relaxed"></textarea>
              </div>
              <div class="space-y-4" dir="rtl">
                <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest text-right">{{ $t('admin.design_narrative_ar') }}</label>
                <textarea v-model="form.description_ar" rows="8" class="w-full bg-[#F7F5F0] border border-[#E0DACE] p-5 rounded-2xl outline-none focus:border-[#C5A880] transition-all text-[#111111] leading-relaxed text-right"></textarea>
              </div>
            </div>
          </div>

          <!-- Media Tab (Cover) -->
          <div v-if="activeTab === 'Media'" class="space-y-8">
            <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.primary_showcase_image') }}</label>
            <div class="relative group border-2 border-dashed border-[#E0DACE] hover:border-[#C5A880]/50 rounded-[3rem] p-16 text-center transition-all bg-[#F7F5F0]">
              <input type="file" @change="e => $emit('upload-cover', e)" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
              <div v-if="form.cover_image" class="mb-8 relative group">
                <div class="h-80 rounded-[2.5rem] overflow-hidden border border-[#E0DACE] shadow-2xl group-hover:scale-[1.02] transition-transform duration-700">
                  <img :src="form.cover_image" class="w-full h-full object-cover" />
                </div>
                <div class="absolute inset-0 bg-[#111111]/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-[2.5rem]">
                   <p class="text-[10px] font-black uppercase tracking-widest text-white">{{ $t('admin.click_replace') }}</p>
                </div>
              </div>
              <div v-else class="h-64 flex flex-col items-center justify-center space-y-4">
                 <div class="w-20 h-20 bg-[#F0ECE1] rounded-3xl flex items-center justify-center text-[#C5A880]">
                    <Upload class="w-10 h-10" />
                  </div>
                  <div class="space-y-1">
                     <p class="text-xs font-bold text-[#555555] uppercase tracking-widest">
                        {{ isEditing ? $t('admin.upload_main_cover') : $t('admin.save_general_first') }}
                     </p>
                     <p class="text-[10px] text-[#555555] uppercase tracking-tighter">{{ $t('admin.resolution_recommendation') }}</p>
                  </div>
              </div>
            </div>
          </div>

          <!-- Gallery Tab -->
          <div v-if="activeTab === 'Gallery'" class="space-y-10">
             <div class="space-y-6">
                <label class="block text-[10px] font-bold uppercase text-[#555555] tracking-widest">{{ $t('admin.project_visual_collection') }}</label>
                <div class="relative group border-2 border-dashed border-[#E0DACE] hover:border-[#C5A880]/50 rounded-[2.5rem] p-10 text-center transition-all bg-[#F7F5F0]">
                  <input type="file" multiple @change="e => $emit('upload-gallery', e)" class="absolute inset-0 opacity-0 cursor-pointer" :disabled="!isEditing">
                  <div class="flex flex-col items-center justify-center space-y-4">
                     <div class="w-16 h-16 bg-[#F0ECE1] rounded-2xl flex items-center justify-center text-[#C5A880] group-hover:text-[#111111] transition-colors">
                        <Plus class="w-8 h-8" />
                     </div>
                     <p class="text-xs font-bold text-[#555555] uppercase tracking-widest">{{ $t('admin.select_multiple_perspectives') }}</p>
                  </div>
                </div>
             </div>

             <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                <TransitionGroup name="gallery">
                  <div v-for="(img, index) in form.gallery" :key="img" class="relative group aspect-[4/3] rounded-2xl overflow-hidden border border-[#E0DACE] hover:border-[#C5A880]/50 transition-all shadow-lg">
                    <img :src="img" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-[#111111]/40 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <button 
                      @click="$emit('remove-image', img)"
                      class="absolute top-3 right-3 w-8 h-8 bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-white hover:text-red-500 shadow-xl"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </TransitionGroup>
             </div>
             
             <div v-if="!form.gallery?.length" class="py-20 text-center">
                <p class="text-[10px] font-bold text-[#555555] uppercase tracking-[0.4em]">{{ $t('admin.empty_gallery') }}</p>
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
            {{ loading ? $t('admin.synchronizing') : (isEditing ? $t('admin.push_updates') : $t('admin.initialize_portfolio')) }}
          </button>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref } from 'vue'
import { X, Upload, Plus, Trash2 } from 'lucide-vue-next'

defineProps({
  isOpen: Boolean,
  isEditing: Boolean,
  form: Object,
  loading: Boolean
})

defineEmits(['close', 'save', 'upload-cover', 'upload-gallery', 'remove-image'])

const activeTab = ref('General')
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.gallery-move, .gallery-enter-active, .gallery-leave-active { transition: all 0.5s ease; }
.gallery-enter-from, .gallery-leave-to { opacity: 0; transform: scale(0.9); }

.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #E0DACE; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #C5A880; }
</style>
