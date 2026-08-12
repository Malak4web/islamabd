<template>
  <!-- `end-*` is already direction-aware: right in LTR, left in RTL. Pairing it
       with an `isArabic` conditional flips it twice and cancels out.
       It sits at the inline END rather than the start because this is a fixed
       overlay on top of the reading column: at the start it lands on the first
       40px of every line at that scroll position, and on a phone it was cutting
       into card titles. The end is where lines run ragged — and it is where
       users reach for a floating contact button anyway. -->
  <!-- `bottom-shell` rides above the tab bar and the home indicator on a phone
       and resolves to a plain 1.5rem inset from `lg`, where no tab bar exists.
       A floating action button that sits under the navigation is a button
       nobody can press. -->
  <div
    v-if="links.length"
    class="bottom-shell fixed end-4 z-sticky flex flex-col gap-2 sm:end-6"
  >
    <a
      v-for="(link, index) in links"
      :key="link.name"
      :href="link.url"
      :target="link.external ? '_blank' : undefined"
      :rel="link.external ? 'noopener noreferrer' : undefined"
      class="press group relative flex h-12 w-12 items-center justify-center rounded-pill border border-line bg-canvas text-ink shadow-raise transition duration-base ease-out-quart hover:border-gold-deep hover:bg-gold hover:text-ink"
      :class="index === 0 ? 'flex' : 'hidden sm:flex'"
    >
      <EaiIcon :name="link.icon" :size="19" :label="link.name" />

      <!-- Label rides out of the button on hover/focus, toward the reading
           column — the other way it would run off the edge of the viewport.
           Pointer-events stay off so it can never intercept its own tap target. -->
      <span
        class="pointer-events-none absolute end-full me-3 whitespace-nowrap rounded bg-ink px-3 py-1.5 text-[0.6875rem] font-medium text-canvas opacity-0 transition-opacity duration-base group-hover:opacity-100 group-focus-visible:opacity-100"
      >
        {{ link.name }}
      </span>
    </a>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useSettingStore } from '@/stores/settingStore'
import EaiIcon from '@/components/icons/EaiIcon.vue'

const settingStore = useSettingStore()

/**
 * The old version drew these with Font Awesome and wrapped each in a
 * `float` + `pulse-slow` animation pair that ran forever with no
 * reduced-motion path — three ring elements pulsing at the edge of every
 * page, permanently.
 *
 * Order matters: only the first entry is shown below `sm`, because three
 * stacked buttons occlude a third of a phone's reading column. WhatsApp leads
 * — it is the channel this market actually uses — and the full set is in the
 * footer on every page.
 */
const links = computed(() => {
  const s = settingStore.settings
  const whatsapp = s.whatsapp_url || (s.whatsapp_number ? `https://wa.me/${s.whatsapp_number}` : null)
  const phone = s.phone_main || s.contact_phone_eg

  return [
    { name: 'WhatsApp', icon: 'whatsapp', url: whatsapp, external: true },
    { name: 'Instagram', icon: 'instagram', url: s.instagram || s.instagram_url, external: true },
    { name: phone, icon: 'phone', url: phone ? `tel:${phone}` : null, external: false },
  ].filter((l) => l.url && !String(l.url).includes('undefined'))
})
</script>
