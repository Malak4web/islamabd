<template>
  <button
    type="button"
    class="press inline-flex h-11 items-center justify-center gap-2 rounded border border-line px-3.5 text-label font-semibold text-ink transition-colors duration-fast hover:border-line-strong hover:bg-ink/5 sm:px-4"
    :aria-label="$t('nav.switch_language')"
    :lang="store.isArabic ? 'en' : 'ar'"
    @click="toggleLocale"
  >
    <!-- Shows the language you would switch TO, which is what the control does.
         `lang` is set to match so screen readers pronounce it correctly rather
         than reading Arabic glyphs with an English voice. -->
    {{ store.isArabic ? 'EN' : 'العربية' }}
  </button>
</template>

<script setup>
import { useLocaleStore } from '@/stores/localeStore'

const store = useLocaleStore()

const toggleLocale = () => {
  store.setLocale(store.isArabic ? 'en' : 'ar')
  // Full reload so direction, fonts and every data-bound component re-resolve.
  window.location.reload()
}
</script>
