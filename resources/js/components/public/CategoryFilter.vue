<template>
    <div class="flex flex-wrap gap-4 mb-16 justify-center">
        <button 
            v-for="cat in categories" 
            :key="cat.value"
            @click="$emit('filter', cat.value)"
            class="px-8 py-3 rounded-full text-sm font-bold uppercase tracking-widest transition-all border"
            :class="activeCategory === cat.value 
                ? 'bg-[#d4af37] border-[#d4af37] text-[#0a0a0a]' 
                : 'bg-transparent border-[#222] text-[#888] hover:border-[#d4af37] hover:text-[#d4af37]'"
        >
            {{ cat.label }}
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
defineProps({
    activeCategory: {
        type: String,
        default: ''
    }
})

defineEmits(['filter'])

const categories = computed(() => [
    { label: t('projects.filter_all'), value: '' },
    { label: t('projects.filter_commercial'), value: 'commercial' },
    { label: t('projects.filter_administrative'), value: 'administrative' },
    { label: t('projects.filter_residential'), value: 'residential' },
    { label: t('projects.filter_exterior'), value: 'exterior' },
])
</script>
