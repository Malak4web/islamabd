<template>
  <!-- The About page's section image: three elements that read as one thing.
       Nothing here is a fixed rectangle beside a paragraph.

       1. A hairline arch struck behind and offset — the opening as drawn.
       2. The photograph, arched, filling the FULL HEIGHT of the copy beside it
          (see the note on `lg:h-full` below). This is the whole point: the
          image is sized by the text, not by a ratio picked in advance, so it
          stays matched when the studio edits the copy.
       3. A square detail plate cut into the base — the material, the tool, the
          drawing. It is what makes each section specific instead of generic. -->
  <figure class="relative w-full max-w-[360px] sm:max-w-[400px] lg:max-w-[380px] xl:max-w-[420px] mx-auto">
    <!-- The drawn opening lags the built one as the section scrolls: same
         offset as before, now with a distance in it. -->
    <span
      v-depth
      class="arch depth-plane pointer-events-none absolute -top-6 bottom-10 start-10 hidden border border-gold/40 lg:block [inset-inline-end:-1.5rem] [--arch-rise:22%]"
      aria-hidden="true"
    ></span>

    <div
      v-depth
      v-tilt="{ max: 3 }"
      v-reveal:wipe
      class="arch group set-plate relative overflow-hidden bg-canvas-inset shadow-xl border border-line/60 rounded-t-[3rem] [--arch-rise:22%] [--set-max:3deg] [--set-z:-14px]"
    >
      <AppImage
        :src="cmsImage"
        :fallback-src="image.main.src"
        :fallback-srcset="image.main.srcset"
        :sizes="sizes"
        :alt="mainAlt"
        img-class="plate-img aspect-[4/5] w-full object-cover"
      />
      <span class="sheen" aria-hidden="true"></span>
    </div>

    <!-- Matted in canvas so it separates from the photograph behind it. It sits
         on the near plane and stays there — a detail plate laid on top of the
         photograph, which is exactly what the drift behind it now proves. -->
    <div class="absolute -bottom-4 start-0 bg-canvas p-2 sm:p-2.5 rounded-2xl shadow-lg border border-line">
      <img
        :src="image.detail.src"
        :srcset="image.detail.srcset"
        sizes="(min-width: 640px) 140px, 100px"
        :alt="detailAlt"
        loading="lazy"
        decoding="async"
        width="400"
        height="400"
        class="aspect-square w-20 object-cover sm:w-28 lg:w-32 rounded-xl"
      />
    </div>
  </figure>
</template>

<script setup>
import { computed } from 'vue'
import AppImage from './AppImage.vue'

const props = defineProps({
  /** An entry from ABOUT_SECTION_IMAGES: `{ main, detail }`. */
  image: { type: Object, required: true },
  /** The studio's own photograph for this section, if the CMS has one. */
  cmsImage: { type: String, default: '' },
  /** Overrides the main photograph's alt — the CMS section title, when set. */
  mainAltOverride: { type: String, default: '' },
  arabic: { type: Boolean, default: false },
  sizes: { type: String, default: '(min-width: 1024px) 50vw, 100vw' },
})

const lang = computed(() => (props.arabic ? 'ar' : 'en'))
const mainAlt = computed(() => props.mainAltOverride || props.image.main.alt[lang.value])
const detailAlt = computed(() => props.image.detail.alt[lang.value])
</script>
