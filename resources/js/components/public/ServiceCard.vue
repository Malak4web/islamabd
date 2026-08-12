<template>
  <RouterLink
    :to="{ name: 'service.detail', params: { id: service.id } }"
    class="press group block focus-visible:outline-offset-4"
  >
    <!-- The photograph sits in an arch, not a rectangle.
         Portrait images arch, wide plates chamfer — that is the whole rule.
         An arch is the most legible architectural form there is, it is native
         to the contemporary interiors this practice builds in Kuwait and Cairo,
         and it is symmetric, so unlike every other shape on this site it needs
         no RTL handling at all.

         The mark sits in a title block cut into the base, where the arch's
         springing line meets the ground: it is the only place on a photograph
         where an icon can be given a guaranteed background, and it makes the
         drawing and the room two readings of one thing.

         The frame is 4:5, matching ProjectCard. It had been changed to 16/10,
         and an arch on a landscape box is not an arch — the elliptical rise is
         a percentage of the height, so on a wide frame it flattens into two
         softly rounded top corners and the site's one signature form
         disappears from half its cards. Portrait images arch. -->
    <figure v-depth v-tilt class="set-plate relative">
      <!-- The arch and the title block cut into its base are one object, so
           they ride forward together. Lifting them separately would shear the
           mark off the corner it is set into the moment the plate tipped —
           hence the wrapper rather than the class on each. -->
      <div class="tilt-lift relative">
        <div
          v-reveal:wipe
          class="arch relative overflow-hidden bg-canvas-inset [--arch-rise:22%]"
        >
          <AppImage
            :src="service.image"
            :fallback-src="photo?.src"
            :fallback-srcset="photo?.srcset"
            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
            :alt="service.title"
            :placeholder="markName"
            :placeholder-size="48"
            img-class="plate-img aspect-[4/5] w-full object-cover"
          />
          <span class="sheen" aria-hidden="true"></span>
        </div>

        <div
          v-reveal
          class="absolute bottom-0 start-0 flex h-16 w-16 items-center justify-center border-e border-t border-line bg-canvas text-gold-deep transition-colors duration-base ease-out-quart group-hover:border-gold group-hover:bg-gold group-hover:text-ink"
        >
          <img
            v-if="service.icon"
            :src="service.icon"
            alt=""
            aria-hidden="true"
            width="30"
            height="30"
            class="h-[30px] w-[30px] object-contain"
          />
          <EaiIcon v-else :name="markName" :size="30" draw />
        </div>
      </div>
    </figure>

    <div class="pt-6">
      <h3 class="text-subhead font-medium text-ink transition-colors duration-base group-hover:text-gold-deep">
        {{ service.title }}
      </h3>

      <p v-if="service.description" class="mt-3 line-clamp-3 max-w-prose text-ink-muted">
        {{ service.description }}
      </p>

      <span class="mt-5 inline-flex items-center gap-2 text-label font-semibold text-gold-deep">
        {{ $t('services.view_one') }}
        <EaiIcon
          name="arrow-right"
          :size="15"
          flip
          class="transition-transform duration-base ease-out-quart group-hover:translate-x-1 rtl:group-hover:-translate-x-1"
        />
      </span>
    </div>
  </RouterLink>
</template>

<script setup>
import { computed } from 'vue'
import EaiIcon from '@/components/icons/EaiIcon.vue'
import AppImage from './AppImage.vue'
import { SERVICE_IMAGES, SERVICE_IMAGE_WIDTHS, serviceKey, srcset, fallback } from '@/lib/media'

const props = defineProps({
  service: { type: Object, required: true },
})

/** Falls back to the generic building mark when the title matches no service line. */
const markName = computed(() => serviceKey(props.service) || 'building')

/** Locally hosted default for this service line; the record's own image wins. */
const photo = computed(() => {
  const key = serviceKey(props.service)
  if (!key || !SERVICE_IMAGES[key]) return null
  return {
    src: fallback(SERVICE_IMAGES[key], SERVICE_IMAGE_WIDTHS),
    srcset: srcset(SERVICE_IMAGES[key], SERVICE_IMAGE_WIDTHS),
  }
})
</script>
