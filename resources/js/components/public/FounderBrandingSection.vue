<template>
  <section class="relative overflow-hidden bg-canvas py-14 sm:py-20 lg:py-36 border-t border-line">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Section Header -->
      <div class="max-w-3xl">
        <div class="flex items-center gap-3">
          <span v-reveal:rule class="block h-px w-10 sm:w-12 bg-gold" aria-hidden="true"></span>
          <span v-reveal class="text-[10px] sm:text-xs uppercase tracking-widest font-semibold text-gold-deep">
            {{ $t('branding.section_badge') }}
          </span>
        </div>
        
        <h2 v-reveal="{ delay: 60 }" class="mt-3 sm:mt-4 text-xl sm:text-2xl lg:text-heading font-light text-ink">
          {{ $t('branding.founder_title') }}
        </h2>
        
        <p v-reveal="{ delay: 120 }" class="mt-3 sm:mt-4 text-sm sm:text-base lg:text-lede text-ink-muted leading-relaxed">
          {{ $t('branding.philosophy_desc') }}
        </p>
      </div>

      <!-- ═══════════════════════════════════════════════════════════════════
           MOBILE LAYOUT (< lg) — stacked, compact, thumb-friendly
           ═══════════════════════════════════════════════════════════════════ -->
      <div class="mt-8 sm:mt-10 lg:hidden">

        <!-- Mobile: Primary Portrait (shorter aspect ratio for phones) -->
        <div class="relative overflow-hidden rounded-xl bg-canvas-inset border border-line">
          <img
            :src="activePillarData.mainImage"
            :alt="activePillarData.tag"
            class="w-full aspect-[3/4] sm:aspect-[4/5] object-cover object-top"
            loading="lazy"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
          
          <div class="absolute bottom-4 start-4 end-4 text-white">
            <span class="inline-block px-2.5 py-1 text-[10px] sm:text-xs font-medium bg-gold-deep text-white rounded-full mb-1.5">
              {{ activePillarData.tag }}
            </span>
            <p class="text-xs sm:text-sm font-light text-stone-200">
              {{ $t('branding.founder_role') }}
            </p>
          </div>
        </div>

        <!-- Mobile: Founder Quote -->
        <blockquote class="mt-6 border-s-2 border-gold ps-4 sm:ps-5 py-1">
          <p class="text-sm sm:text-base font-light italic text-ink leading-relaxed">
            {{ $t('branding.founder_quote') }}
          </p>
          <footer class="mt-3 text-xs sm:text-sm font-semibold text-gold-deep">
            — {{ $t('branding.founder_title') }}
          </footer>
        </blockquote>

        <!-- Mobile: Horizontal Pillar Scroller -->
        <div class="mt-6 sm:mt-8">
          <h3 class="text-[10px] sm:text-xs uppercase tracking-wider font-semibold text-ink-subtle mb-3">
            {{ isAr ? 'محاور الخبرة المعمارية' : 'Architectural Pillars' }}
          </h3>
          
          <!-- Horizontal scroll container — snaps to each card -->
          <div class="-mx-4 px-4 sm:-mx-6 sm:px-6 overflow-x-auto scrollbar-hide">
            <div class="flex gap-3 pb-2" style="min-width: max-content;">
              <button
                v-for="(pillar, key) in PILLARS"
                :key="key"
                @click="activePillar = key"
                class="flex-shrink-0 w-[160px] sm:w-[200px] text-start p-3 sm:p-4 rounded-xl border transition-all duration-300"
                :class="activePillar === key 
                  ? 'bg-canvas border-gold shadow-md' 
                  : 'bg-canvas/50 border-line hover:border-gold/40'"
              >
                <div class="flex items-center justify-between mb-1.5">
                  <span class="text-[10px] sm:text-xs font-mono font-bold text-gold-deep">0{{ pillar.num }}</span>
                  <span 
                    class="h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full transition-all"
                    :class="activePillar === key ? 'bg-gold-deep scale-125' : 'bg-line'"
                  ></span>
                </div>
                <h4 class="text-xs sm:text-sm font-semibold text-ink leading-tight">
                  {{ $t(`branding.pillars.${key}.title`) }}
                </h4>
              </button>
            </div>
          </div>
        </div>

        <!-- Mobile: Gallery Thumbnails (2×2 grid) -->
        <div class="mt-6 border-t border-line pt-5">
          <h4 class="text-[10px] sm:text-xs font-semibold text-ink-subtle uppercase tracking-wider mb-2.5">
            {{ isAr ? 'معرض صور المهندس إسلام' : 'Photography Showcase' }}
          </h4>
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
            <div 
              v-for="(thumb, idx) in activePillarData.gallery" 
              :key="idx"
              class="overflow-hidden rounded-lg border border-line aspect-square bg-canvas-inset group cursor-pointer active:scale-95 transition-transform"
              @click="previewImage = thumb"
            >
              <img 
                :src="thumb" 
                :alt="`${$t('branding.founder_title')} – ${idx + 1}`"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- ═══════════════════════════════════════════════════════════════════
           DESKTOP LAYOUT (≥ lg) — two-column showcase card
           ═══════════════════════════════════════════════════════════════════ -->
      <div class="mt-12 hidden lg:grid gap-10 lg:grid-cols-12 items-center bg-canvas-raised rounded-2xl p-12 border border-line shadow-sm">
        
        <!-- Left Column: Primary Portrait -->
        <div class="lg:col-span-5 relative">
          <div class="relative group overflow-hidden rounded-xl bg-canvas-inset border border-line">
            <img
              :src="activePillarData.mainImage"
              :alt="activePillarData.tag"
              class="w-full aspect-[4/5] object-cover transition-transform duration-700 ease-out group-hover:scale-105"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80"></div>
            
            <div class="absolute bottom-6 start-6 end-6 text-white">
              <span class="inline-block px-3 py-1 text-xs font-medium bg-gold-deep text-white rounded-full mb-2">
                {{ activePillarData.tag }}
              </span>
              <p class="text-sm font-light text-stone-200">
                {{ $t('branding.founder_role') }}
              </p>
            </div>
          </div>
        </div>

        <!-- Right Column: Founder Quote & Pillar Tabs -->
        <div class="lg:col-span-7 flex flex-col justify-between">
          <div>
            <!-- Personal Quote Block -->
            <blockquote class="relative border-s-2 border-gold ps-6 py-2">
              <p class="text-xl font-light italic text-ink leading-relaxed">
                {{ $t('branding.founder_quote') }}
              </p>
              <footer class="mt-4 text-sm font-semibold text-gold-deep">
                — {{ $t('branding.founder_title') }}
              </footer>
            </blockquote>

            <!-- Interactive Pillar Navigation -->
            <div class="mt-10">
              <h3 class="text-xs uppercase tracking-wider font-semibold text-ink-subtle mb-4">
                {{ isAr ? 'محاور الخبرة والقيادة المعمارية' : 'Pillars of Architectural Leadership' }}
              </h3>
              
              <div class="grid grid-cols-2 gap-4">
                <button
                  v-for="(pillar, key) in PILLARS"
                  :key="key"
                  @click="activePillar = key"
                  class="text-start p-4 rounded-xl border transition-all duration-300 flex flex-col justify-between"
                  :class="activePillar === key 
                    ? 'bg-canvas border-gold shadow-md text-ink' 
                    : 'bg-canvas/50 border-line text-ink-muted hover:border-gold/50 hover:bg-canvas'"
                >
                  <div class="flex items-center justify-between w-full mb-2">
                    <span class="text-xs font-mono font-bold text-gold-deep">0{{ pillar.num }}</span>
                    <span 
                      class="h-2 w-2 rounded-full transition-all"
                      :class="activePillar === key ? 'bg-gold-deep scale-125' : 'bg-line'"
                    ></span>
                  </div>
                  <h4 class="text-sm font-semibold text-ink">
                    {{ $t(`branding.pillars.${key}.title`) }}
                  </h4>
                  <p class="mt-1 text-xs text-ink-subtle line-clamp-2">
                    {{ $t(`branding.pillars.${key}.subtitle`) }}
                  </p>
                </button>
              </div>
            </div>
          </div>

          <!-- Bottom Gallery Thumbnail Strip -->
          <div class="mt-8 border-t border-line pt-6">
            <h4 class="text-xs font-semibold text-ink-subtle uppercase tracking-wider mb-3">
              {{ isAr ? 'معرض صور اللقطات الحية للمهندس إسلام' : 'Live Photography Showcase' }}
            </h4>
            <div class="grid grid-cols-4 gap-3">
              <div 
                v-for="(thumb, idx) in activePillarData.gallery" 
                :key="idx"
                class="overflow-hidden rounded-lg border border-line aspect-square bg-canvas-inset group cursor-pointer"
                @click="previewImage = thumb"
              >
                <img 
                  :src="thumb" 
                  :alt="`${$t('branding.founder_title')} – ${idx + 1}`"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                />
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Branding Photography Cards — responsive grid -->
      <div class="mt-10 sm:mt-12 lg:mt-16 grid gap-4 sm:gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
        <div 
          v-for="(card, i) in BRAND_HIGHLIGHTS" 
          :key="i"
          v-reveal="{ delay: i * 80 }"
          class="group relative overflow-hidden rounded-xl border border-line bg-canvas-raised p-3 sm:p-4 transition-all duration-300 hover:shadow-lg hover:border-gold/50"
        >
          <!-- Mobile: horizontal card / Desktop: vertical card -->
          <div class="flex sm:block gap-4 items-start">
            <div class="w-24 h-24 sm:w-full sm:h-auto flex-shrink-0 overflow-hidden rounded-lg sm:aspect-[4/3] bg-canvas-inset sm:mb-4">
              <img 
                :src="card.image" 
                :alt="card.title[isAr ? 'ar' : 'en']"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
            </div>
            <div class="flex-1 min-w-0">
              <span class="text-[10px] sm:text-xs font-semibold text-gold-deep block uppercase tracking-wider mb-0.5 sm:mb-1">
                {{ card.badge[isAr ? 'ar' : 'en'] }}
              </span>
              <h4 class="text-sm sm:text-base font-medium text-ink mb-1 sm:mb-2">
                {{ card.title[isAr ? 'ar' : 'en'] }}
              </h4>
              <p class="text-[11px] sm:text-xs text-ink-muted leading-relaxed line-clamp-3 sm:line-clamp-none">
                {{ card.desc[isAr ? 'ar' : 'en'] }}
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Image Preview Modal — fullscreen on mobile -->
    <Teleport to="body">
      <Transition name="modal-fade">
        <div 
          v-if="previewImage" 
          class="fixed inset-0 z-50 flex items-center justify-center bg-black/85 p-2 sm:p-4 backdrop-blur-sm"
          @click.self="previewImage = null"
        >
          <div class="relative w-full sm:max-w-4xl max-h-[92vh] sm:max-h-[90vh] bg-canvas p-1.5 sm:p-2 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl">
            <button 
              @click="previewImage = null"
              class="absolute top-2 end-2 sm:top-4 sm:end-4 z-10 h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black active:scale-90 transition-all text-sm sm:text-base"
              aria-label="Close preview"
            >
              ✕
            </button>
            <img 
              :src="previewImage" 
              :alt="$t('branding.founder_title')"
              class="w-full max-h-[88vh] sm:max-h-[85vh] object-contain rounded-lg sm:rounded-xl" 
            />
          </div>
        </div>
      </Transition>
    </Teleport>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useLocaleStore } from '@/stores/localeStore'
import { ESLAM_BRAND_IMAGES } from '@/lib/media'

const localeStore = useLocaleStore()
const isAr = computed(() => localeStore.isArabic)

const activePillar = ref('leadership')
const previewImage = ref(null)

const PILLARS = {
  leadership: {
    num: 1,
    mainImage: ESLAM_BRAND_IMAGES.executiveSeated.src,
    tag: 'الرؤية والقيادة المعمارية',
    gallery: [
      ESLAM_BRAND_IMAGES.executiveSeated.src,
      ESLAM_BRAND_IMAGES.executiveSmiling.src,
      ESLAM_BRAND_IMAGES.executiveProfile.src,
      ESLAM_BRAND_IMAGES.studioDesk.src
    ]
  },
  craftsmanship: {
    num: 2,
    mainImage: ESLAM_BRAND_IMAGES.studioMeasuring.src,
    tag: 'الحرفية واختيار الخامات',
    gallery: [
      ESLAM_BRAND_IMAGES.studioMeasuring.src,
      ESLAM_BRAND_IMAGES.studioMaterials.src,
      ESLAM_BRAND_IMAGES.draftingTable.src,
      ESLAM_BRAND_IMAGES.studioDesk.src
    ]
  },
  consultation: {
    num: 3,
    mainImage: ESLAM_BRAND_IMAGES.consultationPresenting.src,
    tag: 'الشراكة واستشارات العملاء',
    gallery: [
      ESLAM_BRAND_IMAGES.consultationPresenting.src,
      ESLAM_BRAND_IMAGES.consultationMeeting.src,
      ESLAM_BRAND_IMAGES.consultationMoodboard.src,
      ESLAM_BRAND_IMAGES.tech3dTablet.src
    ]
  },
  precision: {
    num: 4,
    mainImage: ESLAM_BRAND_IMAGES.siteVisit.src,
    tag: 'الدقة والتنفيذ الميداني',
    gallery: [
      ESLAM_BRAND_IMAGES.siteVisit.src,
      ESLAM_BRAND_IMAGES.tech3dTablet.src,
      ESLAM_BRAND_IMAGES.draftingTable.src,
      ESLAM_BRAND_IMAGES.studioMeasuring.src
    ]
  }
}

const activePillarData = computed(() => PILLARS[activePillar.value] || PILLARS.leadership)

const BRAND_HIGHLIGHTS = [
  {
    image: ESLAM_BRAND_IMAGES.executiveSeated.src,
    badge: { ar: 'المؤسس والرئيس', en: 'Founder & Principal' },
    title: { ar: 'قيادة الاستوديو التنفيذي', en: 'Executive Leadership' },
    desc: { ar: 'توجيه الفريق الهندسي نحو أعلى درجات الابتكار والفخامة.', en: 'Guiding the design practice toward timeless innovation.' }
  },
  {
    image: ESLAM_BRAND_IMAGES.studioMaterials.src,
    badge: { ar: 'اختيار الخامات', en: 'Material Selection' },
    title: { ar: 'عناية فائقة بالتفاصيل', en: 'Obsessive Detail' },
    desc: { ar: 'معاينة حية ومباشرة لأفخم عينات الرخام والأنسجة والأخشاب.', en: 'Tactile inspection of luxury marbles, veneers & moodboards.' }
  },
  {
    image: ESLAM_BRAND_IMAGES.consultationPresenting.src,
    badge: { ar: 'الاستشارات المعمارية', en: 'Client Consultation' },
    title: { ar: 'تطوير الحلول المخصصة', en: 'Bespoke Co-Creation' },
    desc: { ar: 'جلسات استشارية تفاعلية لتحويل أفكار العملاء لمخططات دقيقة.', en: 'Translating client lifestyle needs into custom architectural plans.' }
  },
  {
    image: ESLAM_BRAND_IMAGES.siteVisit.src,
    badge: { ar: 'الإشراف الميداني', en: 'On-Site Oversight' },
    title: { ar: 'دقة التنفيذ في الموقع', en: 'Site Precision' },
    desc: { ar: 'تواجد ميداني مستمر لضمان تطابق الإنجاز مع المخططات الهندسة.', en: 'Rigorous engineering site inspection for 100% fidelity.' }
  }
]
</script>

<style scoped>
/* Hide scrollbar on horizontal pillar scroller */
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

/* Modal fade transition */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.25s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
