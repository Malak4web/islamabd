<template>
  <!-- Five chips wrapped onto two rows and pushed the first project a third of
       a screen further down. A phone shows a set of filters the way an
       application does: one row that scrolls, with the next chip peeking so
       the row announces that it continues. From `sm` there is width for the
       whole set at once and it goes back to a wrapped row.

       `.rail-bleed` runs the track to the viewport edge while keeping the
       first chip on the page gutter, so the row reads as continuing off-screen
       rather than stopping at an arbitrary margin. Logical properties
       throughout — it scrolls right-to-left in Arabic without a line of
       direction code. -->
  <div
    class="rail-sm rail-bleed mb-8 gap-2 pb-1 sm:mb-12 sm:flex sm:flex-wrap"
    role="group"
    :aria-label="$t('projects.filter_label')"
  >
    <button
      v-for="cat in categories"
      :key="cat.value"
      type="button"
      class="press flex min-h-[2.75rem] items-center whitespace-nowrap rounded-xs border px-5 text-label font-semibold transition duration-fast ease-out-quart sm:px-6"
      :class="
        activeCategory === cat.value
          ? 'border-ink bg-ink text-canvas'
          : 'border-line text-ink-muted hover:border-line-strong hover:text-ink'
      "
      :aria-pressed="activeCategory === cat.value ? 'true' : 'false'"
      @click="$emit('filter', cat.value)"
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
  activeCategory: { type: String, default: '' },
})

defineEmits(['filter'])

/**
 * The selected state is ink-on-canvas. It was previously white on the brand
 * gold — 2.3:1, which made the *currently active* filter the hardest chip on
 * the row to read, and left `aria-pressed` off entirely so the selection was
 * invisible to assistive tech.
 */
const categories = computed(() => [
  { label: t('projects.filter_all'), value: '' },
  { label: t('projects.filter_commercial'), value: 'commercial' },
  { label: t('projects.filter_administrative'), value: 'administrative' },
  { label: t('projects.filter_residential'), value: 'residential' },
  { label: t('projects.filter_exterior'), value: 'exterior' },
])
</script>
