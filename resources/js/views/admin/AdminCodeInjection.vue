<template>
    <div class="p-8 font-['Outfit'] bg-[#F7F5F0] min-h-screen text-[#111111]">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-[#111111] uppercase tracking-tighter">{{ $t('admin.code_injection') }}</h1>
            <button @click="openModal()" class="px-6 py-3 bg-[#C5A880] text-white font-bold uppercase tracking-widest text-xs rounded-xl hover:bg-[#111111] transition-colors flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ $t('admin.add_new_snippet') }}
            </button>
        </div>

        <!-- Injections List -->
        <div class="bg-[#FFFFFF] border border-[#E0DACE] rounded-3xl overflow-hidden shadow-md">
            <div class="grid grid-cols-12 gap-4 p-6 border-b border-[#E0DACE] text-[#555555] text-xs font-bold uppercase tracking-widest bg-[#F7F5F0] text-start">
                <div class="col-span-3 text-start">{{ $t('admin.tbl_name') }}</div>
                <div class="col-span-2 text-start">{{ $t('admin.location') }}</div>
                <div class="col-span-3 text-start">{{ $t('admin.pages') }}</div>
                <div class="col-span-2 text-center">{{ $t('admin.active') }}</div>
                <div class="col-span-2 text-end">{{ $t('admin.actions') }}</div>
            </div>

            <div v-if="store.isLoading" class="p-12 text-center text-[#555555]">
                {{ $t('admin.loading_snippets') }}
            </div>
            
            <div v-else-if="store.injections.length === 0" class="p-12 text-center text-[#555555]">
                {{ $t('admin.no_injections_found') }}
            </div>

            <div v-else class="divide-y divide-[#E0DACE]">
                <div v-for="inj in store.injections" :key="inj.id" class="grid grid-cols-12 gap-4 p-6 items-center group hover:bg-[#F0ECE1]/50 transition-colors">
                    <div class="col-span-3 text-start">
                        <h3 class="font-bold text-[#111111] group-hover:text-[#C5A880] transition-colors truncate">{{ inj.name }}</h3>
                        <p class="text-[#555555] text-xs truncate mt-1">&lt;{{ inj.code.substring(0, 30) }}...&gt;</p>
                    </div>

                    <div class="col-span-2 text-start">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-widest border"
                              :class="{
                                  'border-blue-500/30 bg-blue-500/10 text-blue-500': inj.location === 'head',
                                  'border-purple-500/30 bg-purple-500/10 text-purple-500': inj.location === 'body_start',
                                  'border-orange-500/30 bg-orange-500/10 text-orange-500': inj.location === 'body_end'
                              }">
                            {{ inj.location.replace('_', ' ') }}
                        </span>
                    </div>

                    <div class="col-span-3 text-[#555555] text-sm text-start">
                        <span v-if="!inj.pages || inj.pages.length === 0" class="text-[#555555]">{{ $t('admin.all_pages') }}</span>
                        <div v-else class="flex flex-wrap gap-1">
                            <span v-for="page in inj.pages" :key="page" class="px-2 py-0.5 bg-[#F0ECE1] text-[#111111] border border-[#E0DACE] rounded text-xs">
                                {{ page }}
                            </span>
                        </div>
                    </div>

                    <div class="col-span-2 flex justify-center">
                        <button @click="store.toggleInjection(inj.id)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="inj.is_active ? 'bg-[#C5A880]' : 'bg-[#E0DACE]'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="inj.is_active ? 'translate-x-6' : 'translate-x-1'" />
                        </button>
                    </div>

                    <div class="col-span-2 text-end flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openModal(inj)" class="p-2 text-[#555555] hover:text-[#C5A880] transition-colors" :title="$t('admin.edit')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button @click="deleteInjection(inj)" class="p-2 text-[#555555] hover:text-red-500 transition-colors" :title="$t('admin.delete')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Injection Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#111111]/40 backdrop-blur-sm" @click.self="closeModal">
            <div class="bg-[#FFFFFF] border border-[#E0DACE] w-full max-w-4xl rounded-3xl shadow-2xl flex flex-col max-h-[90vh] text-start">
                <div class="p-8 border-b border-[#E0DACE] flex justify-between items-center shrink-0">
                    <h2 class="text-2xl font-black tracking-tight text-[#111111]">{{ editingId ? $t('admin.edit_snippet') : $t('admin.new_snippet') }}</h2>
                    <button @click="closeModal" class="text-[#555555] hover:text-[#111111] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto grow space-y-8">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-[#555555] mb-2">{{ $t('admin.tbl_name') }}</label>
                            <input v-model="form.name" type="text" class="w-full bg-[#F7F5F0] border border-[#E0DACE] rounded-xl p-4 text-[#111111] focus:border-[#C5A880] outline-none" placeholder="e.g. Google Tag Manager">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-[#555555] mb-2">{{ $t('admin.location') }}</label>
                            <select v-model="form.location" class="w-full bg-[#F7F5F0] border border-[#E0DACE] rounded-xl p-4 text-[#111111] focus:border-[#C5A880] outline-none appearance-none">
                                <option value="head">HEAD</option>
                                <option value="body_start">BODY START</option>
                                <option value="body_end">BODY END</option>
                            </select>
                        </div>
                    </div>

                    <!-- Page Targeting -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-[#555555] mb-3">{{ $t('admin.page_targeting') }}</label>
                        <div class="space-y-4 bg-[#F7F5F0] p-6 rounded-2xl border border-[#E0DACE]">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" v-model="pageMode" value="all" class="w-4 h-4 bg-[#FFFFFF] border-[#E0DACE] accent-[#C5A880]">
                                <span class="font-bold text-sm text-[#111111]">{{ $t('admin.all_pages') }}</span>
                            </label>
                            
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" v-model="pageMode" value="specific" class="w-4 h-4 bg-[#FFFFFF] border-[#E0DACE] accent-[#C5A880]">
                                <span class="font-bold text-sm text-[#111111]">{{ $t('admin.specific_pages') }}</span>
                            </label>

                            <div v-if="pageMode === 'specific'" class="pl-7 grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                                <label v-for="page in availablePages" :key="page" class="flex items-center gap-2 cursor-pointer text-sm text-[#111111]">
                                    <input type="checkbox" :value="page" v-model="form.pages" class="w-4 h-4 bg-[#FFFFFF] border-[#E0DACE] rounded accent-[#C5A880]">
                                    {{ page.charAt(0).toUpperCase() + page.slice(1) }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Code Editor -->
                    <div>
                        <div class="flex justify-between mb-2">
                            <label class="block text-xs font-bold uppercase tracking-widest text-[#555555]">{{ $t('admin.snippet_code') }}</label>
                            <span class="text-xs text-[#555555]">HTML / JS</span>
                        </div>
                        <div class="h-[300px] rounded-xl overflow-hidden border border-[#E0DACE]">
                            <vue-monaco-editor
                                v-model:value="form.code"
                                theme="vs"
                                language="html"
                                :options="{ minimap: { enabled: false }, tabSize: 2 }"
                            />
                        </div>
                    </div>

                    <!-- Status -->
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors" :class="form.is_active ? 'bg-[#C5A880]' : 'bg-[#E0DACE]'">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="form.is_active ? 'translate-x-6' : 'translate-x-1'" />
                        </div>
                        <span class="font-bold text-sm uppercase tracking-widest text-[#555555]">{{ $t('admin.active') }}</span>
                    </label>
                </div>

                <div class="p-6 bg-[#F7F5F0] border-t border-[#E0DACE] shrink-0 flex justify-end gap-4 rounded-b-3xl">
                    <button @click="closeModal" class="px-6 py-3 text-[#555555] font-bold hover:text-[#111111] transition-colors">{{ $t('admin.cancel') }}</button>
                    <button @click="saveInjection" :disabled="isSaving" class="px-8 py-3 bg-[#C5A880] text-white font-bold uppercase tracking-widest text-sm rounded-xl hover:bg-[#111111] transition-colors disabled:opacity-50 shadow-md">
                        {{ isSaving ? $t('admin.saving') : $t('admin.save_snippet') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useCodeInjectionStore } from '@/stores/codeInjectionStore'
import VueMonacoEditor from 'monaco-editor-vue3'
import { useI18n } from 'vue-i18n'

const store = useCodeInjectionStore()
const { t } = useI18n()
const showModal = ref(false)
const isSaving = ref(false)
const editingId = ref(null)
const pageMode = ref('all')

const availablePages = ['home', 'about', 'services', 'projects', 'contact']

const form = reactive({
    name: '',
    location: 'head',
    code: '',
    pages: [],
    is_active: true
})

onMounted(() => {
    store.fetchAdminInjections()
})

const openModal = (inj = null) => {
    if (inj) {
        editingId.value = inj.id
        form.name = inj.name
        form.location = inj.location
        form.code = inj.code
        form.is_active = inj.is_active
        if (inj.pages && inj.pages.length > 0) {
            pageMode.value = 'specific'
            form.pages = [...inj.pages]
        } else {
            pageMode.value = 'all'
            form.pages = []
        }
    } else {
        editingId.value = null
        pageMode.value = 'all'
        Object.assign(form, {
            name: '',
            location: 'head',
            code: '',
            pages: [],
            is_active: true
        })
    }
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
}

const saveInjection = async () => {
    isSaving.value = true
    try {
        const payload = { ...form }
        if (pageMode.value === 'all') {
            payload.pages = null
        }

        if (editingId.value) {
            await store.updateInjection(editingId.value, payload)
        } else {
            await store.createInjection(payload)
        }
        closeModal()
    } catch (e) {
        console.error('Failed to save injection', e)
    } finally {
        isSaving.value = false
    }
}

const deleteInjection = async (inj) => {
    if (confirm(t('admin.confirm_delete') + ' (' + inj.name + ')')) {
        await store.deleteInjection(inj.id)
    }
}
</script>
