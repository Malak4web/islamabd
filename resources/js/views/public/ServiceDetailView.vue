<template>
  <div v-if="serviceStore.loading && !service" class="flex min-h-[62svh] items-center justify-center bg-canvas">
    <span class="sr-only">{{ $t('common.loading') }}</span>
    <div class="h-10 w-10 animate-spin rounded-pill border-2 border-line border-t-gold-deep" role="status"></div>
  </div>

  <main v-else-if="service" class="min-h-[62svh] bg-canvas">
    <!-- The image was a background layer at 30% opacity behind the title, which
         is neither a legible photograph nor a clean type surface. It is the
         header's plate now, at full strength, with the title cut into it. -->
    <PageHeader
      :title="service.title"
      :lede="$t('services.expertise')"
      :image="plate"
      :image-alt="service.title"
      :placeholder="markName"
    />

    <section class="py-12 sm:py-24">
      <div class="container mx-auto px-6 sm:px-8">
        <!-- The enquiry card leads in the document and is placed back into the
             right-hand column from `lg`. On a phone it used to sit under the
             whole overview and gallery — the one action on the page, last. -->
        <div class="grid items-start gap-10 lg:grid-cols-12 lg:gap-16">
          <aside class="lg:col-span-4 lg:col-start-9 lg:row-start-1 lg:sticky lg:top-28">
            <div v-reveal class="border border-line bg-canvas-raised p-6 sm:p-8">
              <EaiIcon :name="markName" :size="30" draw class="text-gold-deep" />
              <h2 class="mt-4 text-subhead font-medium text-ink sm:mt-5">{{ $t('services.inquiry_title') }}</h2>
              <p class="mt-3 text-ink-muted">{{ $t('services.inquiry_text') }}</p>
              <RouterLink
                to="/contact"
                class="press mt-6 flex items-center justify-center gap-2.5 rounded-xs bg-gold px-6 py-4 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft sm:mt-7"
              >
                {{ $t('services.get_quote') }}
                <EaiIcon name="arrow-right" :size="16" flip />
              </RouterLink>
            </div>
          </aside>

          <div class="min-w-0 lg:col-span-8 lg:col-start-1 lg:row-start-1">
            <h2 class="text-label font-semibold text-gold-deep">{{ $t('services.overview') }}</h2>
            <p class="mt-4 max-w-prose whitespace-pre-line text-lede text-ink-muted">
              {{ description }}
            </p>

            <div v-if="gallery.length" class="mt-12 sm:mt-14">
              <h2 class="text-label font-semibold text-gold-deep">{{ $t('services.gallery') }}</h2>
              <div class="rail-sm rail-bleed mt-5 gap-4 pb-2 sm:grid sm:grid-cols-2">
                <figure
                  v-for="(image, index) in gallery"
                  :key="index"
                  v-depth
                  v-reveal:wipe
                  class="group set-plate w-[86%] overflow-hidden bg-canvas-inset sm:w-auto [--set-max:4deg]"
                >
                  <AppImage
                    :src="image"
                    sizes="(min-width: 640px) 33vw, 86vw"
                    :alt="`${service.title} — ${index + 1}`"
                    :placeholder="markName"
                    img-class="plate-img aspect-[4/3] w-full object-cover"
                  />
                </figure>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </main>
</template>

<script setup>
import { onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useServiceStore } from '@/stores/serviceStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import PageHeader from '@/components/public/PageHeader.vue'
import CtaBanner from '@/components/public/CtaBanner.vue'
import AppImage from '@/components/public/AppImage.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { SERVICE_IMAGES, SERVICE_IMAGE_WIDTHS, serviceKey, srcset, fallback } from '@/lib/media'

const route = useRoute()
const serviceStore = useServiceStore()
const localeStore = useLocaleStore()
const { currentService } = storeToRefs(serviceStore)

const service = computed(() => serviceStore.currentService)
const isAr = computed(() => localeStore.isArabic)

const markName = computed(() => serviceKey(service.value) || 'building')

const description = computed(() =>
  isAr.value
    ? service.value?.description_ar || service.value?.description
    : service.value?.description
)

/**
 * The header plate: the record's own image, backed by the locally hosted
 * default for its service line if that image is absent or fails to load.
 */
const plate = computed(() => {
  const key = serviceKey(service.value)
  const local = key && SERVICE_IMAGES[key] ? SERVICE_IMAGES[key] : null
  return {
    src: service.value?.image || (local ? fallback(local, SERVICE_IMAGE_WIDTHS) : ''),
    srcset: service.value?.image || !local ? undefined : srcset(local, SERVICE_IMAGE_WIDTHS),
    fallbackSrc: local ? fallback(local, SERVICE_IMAGE_WIDTHS) : '',
    fallbackSrcset: local ? srcset(local, SERVICE_IMAGE_WIDTHS) : '',
  }
})

const gallery = computed(() =>
  Array.isArray(service.value?.gallery) ? service.value.gallery.filter(Boolean) : []
)

useSeo(currentService)

onMounted(() => serviceStore.fetchService(route.params.id))

// Navigating between two service pages reuses the component, so without this
// the route id changes and the old service stays on screen.
watch(() => route.params.id, (id) => id && serviceStore.fetchService(id))
</script>
