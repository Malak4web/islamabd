<template>
  <main class="bg-canvas">
    <PageHeader
      :title="$t('services.premium')"
      :lede="$t('services.expertise')"
      :image="banner"
      :image-alt="banner.alt[localeStore.isArabic ? 'ar' : 'en']"
    />

    <MarkTicker />

    <section class="py-16 sm:py-24">
      <div class="container mx-auto px-6 sm:px-8">
        <div v-if="serviceStore.isLoading" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="i in 6" :key="i" class="animate-pulse">
            <!-- Same frame the real card uses, so the grid does not resize
                 under the visitor the moment the data lands. -->
            <div class="arch aspect-[4/5] w-full bg-canvas-inset [--arch-rise:22%]"></div>
            <div class="mt-6 h-4 w-2/3 bg-canvas-inset"></div>
            <div class="mt-3 h-3 w-full bg-canvas-inset"></div>
          </div>
        </div>

        <div v-else-if="serviceStore.services.length" class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
          <ServiceCard
            v-for="service in serviceStore.services"
            :key="service.id"
            :service="service"
          />
        </div>

        <p v-else class="py-16 text-center text-ink-subtle">{{ $t('services.empty') }}</p>
      </div>
    </section>

    <CtaBanner />
  </main>
</template>

<script setup>
import { onMounted } from 'vue'
import { useServiceStore } from '@/stores/serviceStore'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import PageHeader from '@/components/public/PageHeader.vue'
import ServiceCard from '@/components/public/ServiceCard.vue'
import MarkTicker from '@/components/public/MarkTicker.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'
import { PAGE_BANNERS } from '@/lib/media'

const serviceStore = useServiceStore()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const banner = PAGE_BANNERS.services

useSeo(currentPage)

onMounted(() => {
  serviceStore.fetchServices()
  pageStore.fetchPage('services')
})
</script>
