<template>
  <!-- `min-h-dvh`, not `min-h-screen`: on a phone `100vh` is the viewport with
       the browser chrome retracted, so a full-height section always overshoots
       by the height of the address bar and leaves a dead scroll at the end of
       every page. -->
  <div class="min-h-dvh bg-canvas text-ink-muted">
    <template v-if="!isAdmin">
      <!-- The first tab stop on every page. Without it a keyboard or switch
           user walks the whole header — and, on a phone, the tab bar — before
           reaching a single word of content. -->
      <a href="#main" class="skip-link">{{ $t('common.skip_to_content') }}</a>
      <AppHeader />
      <FloatingSocial />
      <!-- Outside `#main` on purpose: the route transition puts a transform on
           that wrapper, and a transformed ancestor is the containing block for
           anything `fixed` inside it. -->
      <ScrollMeasure />
    </template>

    <CodeInjector />

    <!-- Clears the two fixed bars: the app bar above, the tab bar below.
         Both heights are tokens (`--bar-h`, `--shell-bottom`) so this stays
         correct when either bar changes, and `--shell-bottom` is 0 from `lg`
         where the tab bar does not exist. -->
    <div id="main" class="pb-shell pt-[var(--bar-h)] lg:pt-0">
      <RouterView v-slot="{ Component }">
        <Transition name="page" mode="out-in">
          <component :is="Component" />
        </Transition>
      </RouterView>

      <AppFooter v-if="!isAdmin" />
    </div>

    <BottomNav v-if="!isAdmin" />
  </div>
</template>

<script setup>
import { onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useSettingStore } from '@/stores/settingStore'
import AppHeader from '@/components/public/AppHeader.vue'
import AppFooter from '@/components/public/AppFooter.vue'
import BottomNav from '@/components/public/BottomNav.vue'
import FloatingSocial from '@/components/public/FloatingSocial.vue'
import ScrollMeasure from '@/components/public/ScrollMeasure.vue'
import CodeInjector from '@/components/CodeInjector.vue'

const route = useRoute()
const settingStore = useSettingStore()

const isAdmin = computed(() => route.path.startsWith('/admin'))

// Watch for favicon changes and update the browser tab icon
watch(() => settingStore.settings.favicon, (url) => {
  if (url) {
    const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
    link.type = 'image/png';
    link.rel = 'shortcut icon';
    link.href = url + '?v=' + Date.now();
    document.getElementsByTagName('head')[0].appendChild(link);
  }
}, { immediate: true })

onMounted(() => {
  settingStore.fetchSettings()
})
</script>

<style>
/* Route transitions animate opacity and transform only — `transition: all`
   also animated layout properties on every route change.

   The change moves through the volume rather than cross-fading across it: the
   page you are leaving recedes, the one arriving comes forward out of the same
   depth. `mode="out-in"` sequences the two, so what a visitor reads is one
   continuous movement backward and forward through the site rather than two
   pages briefly sharing a screen.

   `perspective()` inside the transform, never the property on a parent — a
   `perspective` on the wrapper would make it the containing block for every
   `position: fixed` descendant, and the project gallery's lightbox is fixed.
   The transform here is also removed the instant the transition ends, so it
   holds a containing block for 320ms and not one frame longer. */
.page-enter-from {
  opacity: 0;
  transform: perspective(1600px) translate3d(0, 14px, -90px);
}
.page-leave-to {
  opacity: 0;
  transform: perspective(1600px) translate3d(0, 0, -46px);
}
.page-enter-active {
  transition: opacity var(--dur-base) var(--ease-out-expo),
    transform var(--dur-base) var(--ease-out-expo);
}
.page-leave-active {
  transition: opacity var(--dur-fast) linear,
    transform var(--dur-fast) var(--ease-out-quart);
}

@media (prefers-reduced-motion: reduce) {
  .page-enter-from,
  .page-leave-to {
    transform: none;
  }
  .page-enter-active,
  .page-leave-active {
    transition: opacity 0.01ms linear;
  }
}

::-webkit-scrollbar {
  width: 10px;
}
::-webkit-scrollbar-track {
  background: oklch(var(--c-canvas));
}
::-webkit-scrollbar-thumb {
  background: oklch(var(--c-line-strong));
  border: 3px solid oklch(var(--c-canvas));
  border-radius: 999px;
}
::-webkit-scrollbar-thumb:hover {
  background: oklch(var(--c-ink-subtle));
}
</style>


