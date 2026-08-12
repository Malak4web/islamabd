<template>
  <header
    class="fixed inset-x-0 top-0 z-header transition-[padding,background-color,border-color] duration-base ease-out-quart"
    :class="[
      isScrolled
        ? 'border-b border-line bg-canvas/92 backdrop-blur-md supports-[backdrop-filter]:bg-canvas/80 lg:py-3'
        : isOverHero
          ? // Solid on a phone, transparent on a desktop. Below `lg` the hero
            // is a photographic plate with the copy on canvas beneath it —
            // there is no scrim up here to guarantee contrast, so the bar
            // carries its own ground. Above `lg` the scrim is back and the
            // bar can dissolve into the photograph again.
            'border-b border-line bg-canvas lg:border-transparent lg:bg-transparent lg:py-6'
          : 'border-b border-line bg-canvas lg:py-6',
    ]"
  >
    <div
      class="container mx-auto flex h-[var(--bar-h)] items-center justify-between gap-4 px-6 sm:px-8 lg:h-auto"
    >
      <!-- Wordmark ------------------------------------------------------- -->
      <RouterLink to="/" class="group flex min-w-0 items-center gap-3 focus-visible:outline-offset-4">
        <!-- The uploaded lockup shipped as a 1024px square JPEG with a flat
             grey field baked in: a social avatar, which in a header renders as
             a tile with a hard edge against the canvas. `public/images/brand/
             lockup.png` is the same artwork with that field lifted off and the
             ink trimmed to its own bounds, so it sits on the canvas instead of
             on a card. Re-uploading a logo in the dashboard still overrides it. -->
        <img
          v-if="settingStore.settings.logo"
          :src="settingStore.settings.logo"
          alt="Eslam Abdulghani Designs"
          class="h-10 w-auto object-contain sm:h-11 lg:h-12"
        />
        <template v-else>
          <!-- A drawn monogram rather than letters in a rounded tile. -->
          <span
            class="flex h-9 w-9 flex-none items-center justify-center border border-ink text-[0.75rem] font-semibold tracking-normal text-ink transition-colors duration-base group-hover:border-gold-deep group-hover:text-gold-deep lg:h-10 lg:w-10 lg:text-[0.8125rem]"
            aria-hidden="true"
          >EA</span>
          <span class="flex min-w-0 flex-col leading-none">
            <!-- Was text-2xl font-black tracking-widest: a 24-character name at
                 that size and tracking overflowed every phone viewport. -->
            <span class="truncate text-[0.75rem] font-semibold tracking-wide text-ink sm:text-[0.8125rem] lg:text-[0.9375rem]">
              ESLAM ABDULGHANI DESIGNS
            </span>
            <!-- The tagline is a second line of type in a 56px bar. It earns
                 its place on a desktop header and costs legibility on a
                 phone, so it appears from `sm`. -->
            <span class="mt-1 hidden truncate text-[0.6875rem] text-ink-subtle sm:block">
              {{ $t('brand.tagline') }}
            </span>
          </span>
        </template>
      </RouterLink>

      <NavLinks class="hidden items-center gap-9 lg:flex" />

      <div class="flex flex-none items-center gap-2 sm:gap-3 lg:gap-5">
        <!-- Was `hidden sm:block`: on a bilingual site, the control that
             switches language was unreachable below 640px without opening a
             drawer first. It is the one control that must never be a level
             deep, so it is in the bar at every width. -->
        <LanguageSwitcher />

        <RouterLink
          to="/contact"
          class="press hidden rounded-xs bg-gold px-6 py-3 text-label font-semibold tracking-wide text-ink transition duration-base ease-out-quart hover:bg-gold-soft lg:block"
        >
          {{ $t('nav.contact') }}
        </RouterLink>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import { useSettingStore } from '@/stores/settingStore'
import NavLinks from './NavLinks.vue'
import LanguageSwitcher from './LanguageSwitcher.vue'

const route = useRoute()
const settingStore = useSettingStore()
const isScrolled = ref(false)

/**
 * A transparent header is only safe where something guarantees contrast beneath
 * it, and the hero's scrim is the only thing that does. On the project detail
 * page the first element is a full-bleed cover photograph — ink nav links over
 * an unknown, often dark image. Everywhere but the home hero, the bar is solid.
 */
// Optional chaining, not a bare read: mounted outside a router the answer must
// be "no", which is the state that stays legible over anything.
const isOverHero = computed(() => route?.path === '/')

const handleScroll = () => {
  isScrolled.value = window.scrollY > 40
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
