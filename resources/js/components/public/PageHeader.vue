<template>
  <!-- One header for every inner page.
       The heading and the photograph are one object, not two stacked ones: the
       plate carries the image, and the title sits in a canvas block that is cut
       into its bottom inline-start corner — the title block of a drawing sheet,
       at page scale. It is the same device the service cards use for their
       marks, which is what makes it read as this practice's geometry rather
       than as a layout trick.

       It also solves the thing an overlaid heading normally breaks: the type is
       on solid canvas, so its contrast is 17.6:1 regardless of what the
       photograph happens to be. -->
  <header class="border-b border-line bg-canvas">
    <!-- Below `lg` the app shell already reserves the top bar's height, so this
         states only its own breathing room. From `lg` the shell reserves
         nothing — the desktop header floats — and the full clearance is back. -->
    <div class="container mx-auto px-6 pb-12 pt-6 sm:px-8 sm:pb-16 sm:pt-8 lg:pt-32">
      <div v-if="image" class="relative">
        <figure v-depth class="aperture-frame bg-canvas-inset lg:[--chamfer:3.25rem]">
          <div v-reveal:wipe class="aperture overflow-hidden">
            <AppImage
              :src="image.src"
              :srcset="image.srcset"
              :fallback-src="image.fallbackSrc"
              :fallback-srcset="image.fallbackSrcset"
              sizes="(min-width: 1536px) 1400px, 100vw"
              :alt="imageAlt"
              eager
              :placeholder="placeholder"
              :placeholder-size="64"
              img-class="plate-img aspect-[4/3] w-full object-cover sm:aspect-[16/9] lg:aspect-[21/9]"
              :img-style="image.position ? { objectPosition: image.position } : undefined"
            />
          </div>
          <span class="aperture-edge" aria-hidden="true"></span>
          <!-- The plotter line. Passes the top edge every seven seconds. -->
          <span class="sweep-rail" aria-hidden="true"></span>
        </figure>

        <!-- The block. Overlaps upward into the plate; its own chamfer opens
             the corner so the photograph shows through the cut. -->
        <!-- Narrower than the plate at every width, including a phone. When
             this ran full-bleed on mobile it stopped being a corner cut and
             became a bar across the bottom of the photograph — the chamfer
             needs a corner to cut into, and the image needs to keep showing
             past the block's trailing edge for the overlap to read at all. -->
        <div
          class="aperture-frame relative z-raised -mt-12 w-[86%] sm:-mt-20 sm:w-[76%] lg:-mt-28 lg:w-[62%] xl:w-[56%] [--chamfer:1.75rem] sm:[--chamfer:2.25rem]"
        >
          <div class="aperture bg-canvas pb-1 pe-8 pt-6 sm:pe-14 sm:pt-10">
            <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>
            <h1 v-reveal class="mt-6 text-title font-light text-ink">
              <slot name="title">{{ title }}</slot>
            </h1>
            <p
              v-if="lede || $slots.lede"
              v-reveal="{ delay: 90 }"
              class="mt-4 max-w-prose text-lede text-ink-muted"
            >
              <slot name="lede">{{ lede }}</slot>
            </p>
          </div>
          <span class="aperture-edge" aria-hidden="true"></span>
        </div>
      </div>

      <!-- Without a plate there is nothing to cut into, so the block stands on
           its own rather than pretending to overlap something. -->
      <div v-else class="max-w-3xl">
        <span v-reveal:rule class="block h-px w-14 bg-gold" aria-hidden="true"></span>
        <h1 v-reveal class="mt-7 text-title font-light text-ink">
          <slot name="title">{{ title }}</slot>
        </h1>
        <p
          v-if="lede || $slots.lede"
          v-reveal="{ delay: 90 }"
          class="mt-5 max-w-prose text-lede text-ink-muted"
        >
          <slot name="lede">{{ lede }}</slot>
        </p>
      </div>

      <slot name="after" />
    </div>
  </header>
</template>

<script setup>
import AppImage from './AppImage.vue'

defineProps({
  title: { type: String, default: '' },
  lede: { type: String, default: '' },
  /**
   * `{ src, srcset, fallbackSrc?, fallbackSrcset?, position? }` — the plate.
   * Optional; omitting it falls back to a plain heading block.
   * `position` is an `object-position`, for frames whose subject does not sit
   * in the middle third that a 21:9 crop keeps.
   */
  image: { type: Object, default: null },
  imageAlt: { type: String, default: '' },
  /** Mark shown if every source for the plate fails. */
  placeholder: { type: String, default: 'image' },
})
</script>
