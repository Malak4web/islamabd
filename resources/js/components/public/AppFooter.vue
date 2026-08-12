<template>
  <footer class="border-t border-line bg-canvas-raised">
    <div class="container mx-auto px-6 pb-8 pt-14 sm:px-8 sm:pt-16 md:pt-24">
      <!-- Two columns of links on a phone. Four stacked blocks made the footer
           four screens tall, which is four screens between the last section and
           the copyright line. -->
      <div class="grid grid-cols-2 gap-x-6 gap-y-10 sm:gap-y-12 md:grid-cols-2 md:gap-14 lg:grid-cols-4">
        <!-- Identity -->
        <div class="col-span-2 lg:col-span-1 lg:pe-6">
          <RouterLink to="/" class="flex items-center gap-3 focus-visible:outline-offset-4">
            <img
              v-if="settingStore.settings.logo"
              :src="settingStore.settings.logo"
              alt="Eslam Abdulghani Designs"
              class="h-11 w-auto object-contain"
            />
            <template v-else>
              <span
                class="flex h-9 w-9 flex-none items-center justify-center border border-ink text-[0.75rem] font-semibold text-ink"
                aria-hidden="true"
              >EA</span>
              <span class="text-[0.8125rem] font-semibold tracking-wide text-ink">
                ESLAM ABDULGHANI DESIGNS
              </span>
            </template>
          </RouterLink>

          <p class="mt-6 max-w-xs text-ink-muted">{{ $t('footer.desc') }}</p>

          <div v-if="socialLinks.length" class="mt-7 flex items-center gap-2">
            <a
              v-for="social in socialLinks"
              :key="social.name"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              class="press flex h-11 w-11 items-center justify-center border border-line text-ink-muted transition-colors duration-fast hover:border-gold-deep hover:text-gold-deep"
            >
              <EaiIcon :name="social.icon" :size="17" :label="social.name" />
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <nav aria-labelledby="footer-nav">
          <!-- Column headings were 1.75rem/leading-2 gold on this surface:
               2.1:1, and physically larger than the page's own body copy.
               They are small ink labels now; gold survives as the rule. -->
          <h2 id="footer-nav" class="footer-heading">{{ $t('footer.quick_links') }}</h2>
          <ul class="mt-4 flex flex-col">
            <li v-for="link in navLinks" :key="link.path">
              <RouterLink
                :to="link.path"
                class="flex min-h-[2.75rem] items-center text-ink-muted transition-colors duration-fast hover:text-ink"
              >
                {{ $t(`nav.${link.key}`) }}
              </RouterLink>
            </li>
          </ul>
        </nav>

        <!-- Expertise -->
        <nav v-if="serviceStore.services.length" aria-labelledby="footer-expertise">
          <h2 id="footer-expertise" class="footer-heading">{{ $t('footer.expertise') }}</h2>
          <ul class="mt-4 flex flex-col">
            <li v-for="service in serviceStore.services.slice(0, 5)" :key="service.id">
              <RouterLink
                :to="`/services/${service.id}`"
                class="flex min-h-[2.75rem] items-center text-ink-muted transition-colors duration-fast hover:text-ink"
              >
                {{ service.title }}
              </RouterLink>
            </li>
          </ul>
        </nav>

        <!-- Contact -->
        <div class="col-span-2 md:col-span-1">
          <h2 class="footer-heading">{{ $t('footer.contact') }}</h2>
          <ul class="mt-5 flex flex-col gap-4">
            <li v-if="settingStore.settings.address" class="flex items-start gap-3">
              <EaiIcon name="pin" :size="18" class="mt-[3px] flex-none text-gold-deep" />
              <span class="text-ink-muted">{{ settingStore.settings.address }}</span>
            </li>
            <li v-if="phone" class="flex items-start gap-3">
              <EaiIcon name="phone" :size="18" class="mt-[3px] flex-none text-gold-deep" />
              <!-- Was inert text; a phone number on a phone should dial. -->
              <a
                :href="`tel:${phone}`"
                dir="ltr"
                class="flex min-h-[2.75rem] items-center text-ink-muted transition-colors duration-fast hover:text-ink"
              >{{ phone }}</a>
            </li>
            <li v-if="email" class="flex items-start gap-3">
              <EaiIcon name="mail" :size="18" class="mt-[3px] flex-none text-gold-deep" />
              <a
                :href="`mailto:${email}`"
                dir="ltr"
                class="break-all text-ink-muted transition-colors duration-fast hover:text-ink"
              >{{ email }}</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="mt-10 flex flex-col items-start justify-between gap-4 border-t border-line pt-7 sm:mt-16 md:mt-20 md:flex-row md:items-center md:pt-8">
        <p class="text-label text-ink-subtle">
          &copy; {{ year }} ESLAM ABDULGHANI DESIGNS. {{ $t('footer.rights') }}
        </p>

        <button
          type="button"
          class="press group flex items-center gap-3 text-label font-semibold text-ink-muted transition-colors duration-fast hover:text-ink"
          @click="scrollToTop"
        >
          {{ $t('footer.back_to_top') }}
          <span
            class="flex h-8 w-8 items-center justify-center rounded-pill border border-line-strong transition-transform duration-base ease-out-quart group-hover:-translate-y-0.5"
          >
            <EaiIcon name="arrow-up" :size="15" />
          </span>
        </button>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import { useServiceStore } from '@/stores/serviceStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const settingStore = useSettingStore()
const serviceStore = useServiceStore()

const year = new Date().getFullYear()

const navLinks = [
  { path: '/', key: 'home' },
  { path: '/about', key: 'about' },
  { path: '/services', key: 'services' },
  { path: '/projects', key: 'projects' },
  { path: '/contact', key: 'contact' },
]

const socialLinks = computed(() =>
  [
    { name: 'Instagram', icon: 'instagram', url: settingStore.settings.instagram },
    { name: 'Facebook', icon: 'facebook', url: settingStore.settings.facebook },
    { name: 'LinkedIn', icon: 'linkedin', url: settingStore.settings.linkedin },
    { name: 'X', icon: 'twitter', url: settingStore.settings.twitter },
  ].filter((s) => s.url && !String(s.url).includes('undefined'))
)

const phone = computed(
  () => settingStore.settings.phone_main || settingStore.settings.contact_phone_eg || ''
)
const email = computed(
  () => settingStore.settings.email_main || settingStore.settings.contact_email || ''
)

onMounted(() => {
  if (!serviceStore.services.length) serviceStore.fetchServices()
})

const scrollToTop = () => {
  const reduce = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches
  window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' })
}
</script>

<style scoped>
/* No `text-transform: uppercase` and no tracking. Both are no-ops in Arabic —
   the script has no case and letter-spacing breaks its glyph joins — so the
   English footer was shouting in tracked caps while the Arabic one spoke
   normally. Weight carries the hierarchy in both. */
.footer-heading {
  /* The annotating face, not the display one. These are `<h2>`s for the
     outline and captions on the page; see the note on `.text-label`. */
  font-family: var(--font-body);
  font-size: 0.8125rem;
  font-weight: 700;
  color: oklch(var(--c-ink));
}
</style>
