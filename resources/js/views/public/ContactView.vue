<template>
  <main class="min-h-[62svh] bg-canvas">
    <PageHeader
      :title="$t('contact.title')"
      :lede="$t('contact.subtitle')"
      :image="banner"
      :image-alt="banner.alt[localeStore.isArabic ? 'ar' : 'en']"
    />

    <section class="py-16 sm:py-24">
      <div class="container mx-auto px-6 sm:px-8">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
          <!-- Details -->
          <div class="lg:col-span-5">
            <h2 class="text-heading font-light text-ink">{{ $t('contact.help') }}</h2>
            <p class="mt-3 text-label font-semibold text-gold-deep">{{ $t('contact.details') }}</p>

            <ul class="mt-10 space-y-8">
              <li v-if="settingStore.settings.address" class="flex items-start gap-4 border-t border-line pt-6">
                <EaiIcon name="pin" :size="22" class="mt-1 flex-none text-gold-deep" />
                <div>
                  <span class="text-label text-ink-subtle">{{ $t('contact.studio') }}</span>
                  <p class="mt-1 max-w-xs text-ink">{{ settingStore.settings.address }}</p>
                </div>
              </li>

              <li v-if="phones.length" class="flex items-start gap-4 border-t border-line pt-6">
                <EaiIcon name="phone" :size="22" class="mt-1 flex-none text-gold-deep" />
                <div>
                  <span class="text-label text-ink-subtle">{{ $t('contact.call') }}</span>
                  <!-- These were plain text. On the device most visitors are
                       holding, a phone number that is not a tel: link is a
                       number they have to retype. -->
                  <p v-for="p in phones" :key="p" class="mt-1">
                    <a
                      :href="`tel:${p}`"
                      dir="ltr"
                      class="text-ink transition-colors duration-fast hover:text-gold-deep"
                    >{{ p }}</a>
                  </p>
                </div>
              </li>

              <li v-if="emails.length" class="flex items-start gap-4 border-t border-line pt-6">
                <EaiIcon name="mail" :size="22" class="mt-1 flex-none text-gold-deep" />
                <div class="min-w-0">
                  <span class="text-label text-ink-subtle">{{ $t('contact.email_us') }}</span>
                  <p v-for="e in emails" :key="e" class="mt-1">
                    <a
                      :href="`mailto:${e}`"
                      dir="ltr"
                      class="break-all text-ink transition-colors duration-fast hover:text-gold-deep"
                    >{{ e }}</a>
                  </p>
                </div>
              </li>
            </ul>
          </div>

          <!-- Form -->
          <div class="lg:col-span-7">
            <ContactForm />
          </div>
        </div>
      </div>
    </section>

    <!-- Map. Previously hardcoded to a fixed Dubai coordinate pair regardless
         of the address in settings, greyscaled until hover, and with no title —
         an unlabelled iframe is an unnavigable stop for screen reader users. -->
    <section v-if="mapSrc" class="border-t border-line">
      <iframe
        :src="mapSrc"
        :title="$t('contact.map_title')"
        class="block h-[55vh] min-h-[20rem] w-full"
        style="border: 0"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
      ></iframe>
    </section>
  </main>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { usePageStore } from '@/stores/pageStore'
import { useLocaleStore } from '@/stores/localeStore'
import { useSeo } from '@/composables/useSeo'
import { storeToRefs } from 'pinia'
import PageHeader from '@/components/public/PageHeader.vue'
import ContactForm from '@/components/public/ContactForm.vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import { PAGE_BANNERS } from '@/lib/media'

const settingStore = useSettingStore()
const pageStore = usePageStore()
const localeStore = useLocaleStore()
const { currentPage } = storeToRefs(pageStore)

const banner = PAGE_BANNERS.contact

const uniq = (arr) => [...new Set(arr.filter(Boolean).map((v) => String(v).trim()).filter(Boolean))]

const phones = computed(() =>
  uniq([
    settingStore.settings.phone_main,
    settingStore.settings.phone_2,
    settingStore.settings.contact_phone_eg,
  ])
)

// The second address was hardcoded in the markup, so it could never be changed
// from the dashboard and silently contradicted whatever settings held.
const emails = computed(() =>
  uniq([settingStore.settings.email_main, settingStore.settings.contact_email])
)

/** Follows the address in settings rather than a fixed coordinate pair. */
const mapSrc = computed(() => {
  if (settingStore.settings.map_embed) return settingStore.settings.map_embed
  const address = settingStore.settings.address
  if (!address) return null
  return `https://maps.google.com/maps?q=${encodeURIComponent(address)}&output=embed`
})

useSeo(currentPage)

onMounted(() => {
  pageStore.fetchPage('contact')
})
</script>
