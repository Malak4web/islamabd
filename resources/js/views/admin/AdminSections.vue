<template>
    <div class="p-8 font-['Outfit'] bg-[#F7F5F0] min-h-screen text-[#111111]">
        <div class="flex items-center gap-4 mb-8">
            <router-link :to="{ name: 'admin.pages' }" class="p-2 bg-[#FFFFFF] border border-[#E0DACE] rounded-xl text-[#555555] hover:text-[#C5A880]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </router-link>
            <h1 class="text-3xl font-bold text-[#111111] uppercase tracking-tighter">
                {{ $t('admin.edit_page') }}: {{ isAr ? page?.title_ar : page?.title_en }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Sections List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C5A880]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        {{ $t('admin.sections') }}
                    </h2>
                    <span class="text-xs text-[#555555]">{{ $t('admin.drag_reorder') }}</span>
                </div>

                <draggable 
                    v-model="sections" 
                    item-key="id" 
                    @end="handleReorder"
                    class="space-y-4"
                    handle=".drag-handle"
                >
                    <template #item="{ element }">
                        <div class="bg-[#FFFFFF] border border-[#E0DACE] p-6 rounded-2xl flex items-center justify-between group hover:border-[#C5A880]/30 shadow-sm transition-all">
                            <div class="flex items-center gap-4">
                                <div class="drag-handle cursor-grab active:cursor-grabbing p-2 text-[#555555] group-hover:text-[#C5A880]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="font-bold text-lg capitalize text-[#111111]">{{ element.key.replace('_', ' ') }}</h3>
                                        <span v-if="!element.is_active" class="px-2 py-0.5 bg-red-500/10 text-red-500 rounded text-[10px] uppercase font-bold">{{ $t('admin.hidden') }}</span>
                                    </div>
                                    <p class="text-[#555555] text-xs font-mono">ID: #{{ element.id }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button 
                                    @click="toggleSection(element)"
                                    class="p-2 rounded-xl border border-[#E0DACE] text-[#555555] hover:bg-[#F7F5F0] transition-all"
                                    :title="element.is_active ? $t('admin.hide_section') : $t('admin.show_section')"
                                >
                                    <svg v-if="element.is_active" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.882 9.882L2.457 2.457M21.543 12a10.057 10.057 0 01-1.563 3.029m-5.858-.908a3 3 0 11-4.243-4.243M21.543 12c-1.274-4.057-5.064-7-9.542-7-1.274 0-2.407.254-3.414.715m13.88 13.88L21.543 21.543" />
                                    </svg>
                                </button>
                                <button 
                                    @click="editSection(element)"
                                    data-edit-section
                                    class="px-4 py-2 bg-[#F7F5F0] border border-[#E0DACE] rounded-xl text-[#111111] hover:border-[#C5A880] hover:text-[#C5A880] transition-all flex items-center gap-2"
                                >

                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    {{ $t('admin.edit') }}
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- Right: SEO Panel -->
            <div class="space-y-8">
                <div class="bg-[#FFFFFF] border border-[#E0DACE] p-8 rounded-2xl shadow-sm">
                    <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-[#111111]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#C5A880]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ $t('admin.seo_meta') }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-[#555555] mb-2">{{ $t('admin.meta_title') }}</label>
                            <input v-model="seoForm.meta_title" type="text" class="w-full px-4 py-3 bg-[#F7F5F0] border border-[#E0DACE] text-[#111111] rounded-xl outline-none focus:ring-2 focus:ring-[#C5A880]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#555555] mb-2">{{ $t('admin.meta_description') }}</label>
                            <textarea v-model="seoForm.meta_description" rows="4" class="w-full px-4 py-3 bg-[#F7F5F0] border border-[#E0DACE] text-[#111111] rounded-xl outline-none focus:ring-2 focus:ring-[#C5A880]"></textarea>
                        </div>
                        <button 
                            @click="saveSEO"
                            :disabled="pageStore.isLoading"
                            class="w-full py-3 bg-[#C5A880] text-white font-bold rounded-xl hover:bg-[#111111] transition-all disabled:opacity-50 shadow-md"
                        >
                            {{ $t('admin.update_seo') }}
                        </button>
                    </div>
                </div>

                <!-- Google Preview -->
                <div class="bg-[#FFFFFF] border border-[#E0DACE] p-6 rounded-2xl shadow-sm">
                    <p class="text-[#1a0dab] text-xl truncate mb-1">{{ seoForm.meta_title || $t('admin.site_title') }}</p>
                    <p class="text-[#006621] text-sm mb-1">eslamabdulghanidesigns.com › {{ page?.slug }}</p>
                    <p class="text-[#545454] text-sm line-clamp-2">{{ seoForm.meta_description || $t('admin.seo_desc_placeholder') }}</p>
                </div>
            </div>
        </div>

        <!-- Section Editor Modal -->
        <div v-if="editingSection" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#111111]/40 backdrop-blur-sm">
            <div class="bg-[#FFFFFF] border border-[#E0DACE] w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl">
                <div class="p-6 border-b border-[#E0DACE] flex justify-between items-center">
                    <h3 class="text-xl font-bold text-[#111111]">{{ $t('admin.edit_section') }}: {{ editingSection.key }}</h3>
                    <button @click="editingSection = null" class="text-[#555555] hover:text-[#111111]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">
                    <!-- Simple JSON-like editor for this demo -->
                    <div v-for="(value, key) in editingSection.content" :key="key">
                        <label class="block text-sm font-medium text-[#555555] mb-2 capitalize">{{ key.replace('_', ' ') }}</label>
                        <textarea 
                            v-model="editingSection.content[key]" 
                            data-content-textarea
                            rows="2" 
                            class="w-full px-4 py-3 bg-[#F7F5F0] border border-[#E0DACE] text-[#111111] rounded-xl outline-none focus:ring-2 focus:ring-[#C5A880]"
                        ></textarea>
                    </div>

                </div>
                <div class="p-6 bg-[#F7F5F0] border-t border-[#E0DACE] flex justify-end gap-3">
                    <button @click="editingSection = null" class="px-6 py-2 text-[#555555] hover:text-[#111111] font-bold">{{ $t('admin.cancel') }}</button>
                    <button @click="saveSection" class="px-8 py-2 bg-[#C5A880] text-white font-bold rounded-xl hover:bg-[#111111] transition-all shadow-md">{{ $t('admin.save_content') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive, watch, computed } from 'vue'
import { useRoute } from 'vue-router'
import draggable from 'vuedraggable'
import { usePageStore } from '@/stores/pageStore'
import { useSectionStore } from '@/stores/sectionStore'
import { useI18n } from 'vue-i18n'

const route = useRoute()
const pageStore = usePageStore()
const sectionStore = useSectionStore()
const { t } = useI18n()

const page = ref(null)
const sections = ref([])
const editingSection = ref(null)
const isAr = computed(() => localStorage.getItem('locale') === 'ar')

const seoForm = reactive({
    meta_title: '',
    meta_description: ''
})

onMounted(async () => {
    // We need to fetch pages first to find the metadata
    if (pageStore.pages.length === 0) await pageStore.fetchPages()
    page.value = pageStore.pages.find(p => p.id == route.params.id)
    
    if (page.value) {
        seoForm.meta_title = page.value.meta_title || ''
        seoForm.meta_description = page.value.meta_description || ''
    }

    await sectionStore.fetchSections(route.params.id)
    sections.value = [...sectionStore.sections]
})

const handleReorder = async () => {
    const ids = sections.value.map(s => s.id)
    await sectionStore.reorderSections(ids)
    alert(t('admin.reordered_success'))
}

const toggleSection = async (section) => {
    await sectionStore.toggleSection(section.id)
    // Update local state
    section.is_active = !section.is_active
}

const editSection = (section) => {
    editingSection.value = JSON.parse(JSON.stringify(section)) // Deep clone
}

const saveSection = async () => {
    await sectionStore.updateSection(editingSection.value.id, editingSection.value.content)
    // Refresh list
    await sectionStore.fetchSections(route.params.id)
    sections.value = [...sectionStore.sections]
    editingSection.value = null
}

const saveSEO = async () => {
    await pageStore.updatePageSEO(page.value.id, {
        ...seoForm,
        title_en: page.value.title_en,
        title_ar: page.value.title_ar
    })
    alert('SEO updated successfully!')
}
</script>
