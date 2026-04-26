<template>
  <section class="py-24">
    <div class="container px-6 mx-auto">
      <div class="relative overflow-hidden rounded-[3rem] bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] border border-white/5 shadow-2xl">
        <!-- Background decorative circles -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#d4af37] opacity-10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white opacity-5 rounded-full blur-[100px]"></div>

        <div class="relative flex flex-col items-center gap-12 px-8 py-24 text-center">
          <div class="space-y-6 max-w-3xl">
            <h2 class="text-[1.75rem] leading-[2] font-black text-white uppercase tracking-tighter">
               {{ isAr ? (content?.title_ar || $t('cta.title')) : (content?.title_en || $t('cta.title')) }}
            </h2>
            <p class="text-lg text-gray-400 max-w-xl mx-auto leading-relaxed">
               {{ isAr ? (content?.body_ar || $t('cta.subtitle')) : (content?.body_en || $t('cta.subtitle')) }}
            </p>
          </div>

          <div class="flex flex-wrap justify-center gap-6">
            <RouterLink to="/contact" class="px-12 py-5 text-sm font-bold tracking-[0.2em] text-black uppercase transition-all duration-300 bg-[#d4af37] rounded-full hover:bg-white hover:scale-105 active:scale-95 shadow-[0_15px_40px_rgba(212,175,55,0.2)]">
               {{ isAr ? (content?.cta_ar || $t('cta.primary')) : (content?.cta_en || $t('cta.primary')) }}
            </RouterLink>
            <a :href="`tel:${settingStore.settings.phone_main}`" class="px-12 py-5 text-sm font-bold tracking-[0.2em] text-white uppercase transition-all duration-300 border border-white/10 rounded-full hover:bg-white/10">
               {{ $t('cta.secondary') }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useLocaleStore } from '@/stores/localeStore'

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
})

const settingStore = useSettingStore()
const store = useLocaleStore()
const isAr = computed(() => store.isArabic)
</script>
