/**
 * Local image manifest.
 *
 * Every asset here is served from `public/images/` rather than hotlinked from
 * a CDN. Hotlinking cost us three things: an uncontrolled third-party request
 * on the critical path, no responsive variants (a 2000px hero was shipped to
 * phones), and silent breakage whenever an upstream URL changed.
 *
 * Each entry is a base path; the real files are `<base>-<width>.jpg`.
 * Provenance is recorded in `public/images/CREDITS.txt`.
 */

/** Builds a `srcset` string from a base path and the widths actually on disk. */
export const srcset = (base, widths) => widths.map((w) => `${base}-${w}.jpg ${w}w`).join(', ')

/** Largest variant, used as the `src` fallback for browsers without srcset. */
export const fallback = (base, widths) => `${base}-${widths[widths.length - 1]}.jpg`

const HERO_WIDTHS = [960, 1600, 2400]
const ABOUT_WIDTHS = [800, 1400]
const SERVICE_WIDTHS = [640, 1080]
const PROJECT_WIDTHS = [800, 1600]
const BANNER_WIDTHS = [1200, 2000]

/** Bundles a base path into the shape every image consumer here expects. */
const asset = (base, widths) => ({
  src: fallback(base, widths),
  srcset: srcset(base, widths),
})

/**
 * Alt text is part of the voice, not an accessibility chore. It should describe
 * the room a visitor is looking at — the thing that makes it worth showing —
 * not restate the heading.
 */
export const HERO_SLIDES = [
  {
    base: '/images/hero/hero-1-villa',
    widths: HERO_WIDTHS,
    alt: {
      en: 'A daylit living room in pale oak and off-white, shelving built into the wall and full-height windows along one side.',
      ar: 'غرفة معيشة مضاءة بضوء النهار بخشب البلوط الفاتح والأبيض المكسور، مع رفوف مدمجة في الجدار ونوافذ ممتدة بكامل الارتفاع.',
    },
  },
  {
    base: '/images/hero/hero-2-studio',
    widths: HERO_WIDTHS,
    alt: {
      en: 'A reception hall in warm timber and plaster, a single olive tree set against the counter.',
      ar: 'بهو استقبال بالخشب الدافئ والجبس، وشجرة زيتون منفردة أمام الكاونتر.',
    },
  },
  {
    base: '/images/hero/hero-3-suite',
    widths: HERO_WIDTHS,
    alt: {
      en: 'A cream-toned suite lounge with a sculptural ring light and a wall of glass onto the skyline.',
      ar: 'صالة جناح بدرجات الكريمي مع وحدة إضاءة دائرية نحتية وجدار زجاجي يطل على أفق المدينة.',
    },
  },
]

export const ABOUT_IMAGE = {
  src: '/images/eslam/eslam_studio_blueprint_desk.jpg',
  alt: {
    en: 'Eng. Eslam Abdulghani reviewing architectural blueprints and material samples in the design studio.',
    ar: 'المهندس إسلام عبد الغني يراجع المخططات المعمارية وعينات الخامات في استوديو التصميم.',
  },
}

export const ESLAM_BRAND_IMAGES = {
  executiveSeated: {
    src: '/images/eslam/eslam_executive_seated.jpg',
    alt: {
      en: 'Eng. Eslam Abdulghani, Founder & Design Principal in executive office setting.',
      ar: 'المهندس إسلام عبد الغني، المؤسس ورئيس قطاع التصميم في المكتب التنفيذي.',
    },
  },
  executiveSmiling: {
    src: '/images/eslam/eslam_executive_smiling.jpg',
    alt: {
      en: 'Eng. Eslam Abdulghani standing in executive suit.',
      ar: 'المهندس إسلام عبد الغني بالزي الرسمي التنفيذي.',
    },
  },
  executiveProfile: {
    src: '/images/eslam/eslam_executive_profile.jpg',
    alt: {
      en: 'Eng. Eslam Abdulghani portrait looking forward.',
      ar: 'بورتريه تنفيذي للمهندس إسلام عبد الغني.',
    },
  },
  studioMeasuring: {
    src: '/images/eslam/eslam_studio_measuring_banner.jpg',
    alt: {
      en: 'Eng. Eslam measuring architectural blueprints with precision.',
      ar: 'المهندس إسلام يقيس المخططات المعمارية بكل دقة.',
    },
  },
  studioDesk: {
    src: '/images/eslam/eslam_studio_blueprint_desk.jpg',
    alt: {
      en: 'Eng. Eslam studying floor plans on design desk.',
      ar: 'المهندس إسلام يدرس المخططات على مكتب التصميم.',
    },
  },
  studioMaterials: {
    src: '/images/eslam/eslam_studio_materials.jpg',
    alt: {
      en: 'Eng. Eslam selecting premium material swatches and moodboards.',
      ar: 'المهندس إسلام يختار لوحات عينات الخامات الفاخرة.',
    },
  },
  consultationPresenting: {
    src: '/images/eslam/eslam_consultation_presenting.jpg',
    alt: {
      en: 'Eng. Eslam presenting design proposals to client.',
      ar: 'المهندس إسلام يقدم مقترحات التصميم في جلسة استشارية.',
    },
  },
  consultationMeeting: {
    src: '/images/eslam/eslam_consultation_meeting.jpg',
    alt: {
      en: 'Eng. Eslam consulting with clients over architectural plans.',
      ar: 'المهندس إسلام يناقش التفاصيل المعمارية مع العملاء.',
    },
  },
  consultationMoodboard: {
    src: '/images/eslam/eslam_consultation_moodboard.jpg',
    alt: {
      en: 'Eng. Eslam demonstrating material concept board.',
      ar: 'المهندس إسلام يستعرض مفهوم لوحة المواد والخامات.',
    },
  },
  tech3dTablet: {
    src: '/images/eslam/eslam_tech_3d_tablet.jpg',
    alt: {
      en: 'Eng. Eslam presenting 3D architectural villa rendering on digital tablet.',
      ar: 'المهندس إسلام يعرض منظورا معمارياً ثلاثي الأبعاد لفيلّا عبر اللوح الرقمي.',
    },
  },
  siteVisit: {
    src: '/images/eslam/eslam_site_visit.jpg',
    alt: {
      en: 'Eng. Eslam inspecting active construction site with engineering team.',
      ar: 'المهندس إسلام يشرف ميدانياً على موقع البناء مع فريق المهندسين.',
    },
  },
  draftingTable: {
    src: '/images/eslam/eslam_drafting_table.jpg',
    alt: {
      en: 'Eng. Eslam drafting detailed drawings at architectural drawing board.',
      ar: 'المهندس إسلام يرسم التفاصيل المعمارية على طاولة الرسم.',
    },
  },
}

/**
 * Default imagery per service line. The studio replaces these from the admin
 * dashboard with their own project photography; these exist so a fresh install
 * is never blank and never shows an unrelated stock photo.
 */
export const SERVICE_IMAGES = {
  administrative: '/images/services/administrative',
  commercial: '/images/services/commercial',
  residential: '/images/services/residential',
  exterior: '/images/services/exterior',
  hospitality: '/images/services/hospitality',
  landscape: '/images/services/landscape',
  retail: '/images/services/retail',
  industrial: '/images/services/industrial',
}

export const SERVICE_IMAGE_WIDTHS = SERVICE_WIDTHS

/**
 * Maps a service title to its architectural icon and default photograph.
 * Matching is done on the English title because that is what the seeder writes;
 * Arabic titles fall through to the order-based default rather than guessing.
 */
const SERVICE_KEYS = [
  ['administrative', ['administrative', 'إداري']],
  ['commercial', ['commercial', 'تجاري']],
  ['residential', ['residential', 'سكني']],
  ['exterior', ['exterior', 'خارجي']],
  ['hospitality', ['hospitality', 'ضياف']],
  ['landscape', ['landscape', 'لاند', 'حدائق']],
  ['retail', ['retail', 'تجزئة', 'متاجر']],
  ['industrial', ['industrial', 'صناع']],
]

/** Matches a free-text label against the service lines. Null when nothing fits. */
export function matchKey(text) {
  const hay = String(text ?? '').toLowerCase()
  if (!hay.trim()) return null
  for (const [key, needles] of SERVICE_KEYS) {
    if (needles.some((n) => hay.includes(n))) return key
  }
  return null
}

/** Resolves a service record to a stable key, or null when nothing matches. */
export function serviceKey(service) {
  return matchKey(
    `${service?.title ?? ''} ${service?.title_en ?? ''} ${service?.title_ar ?? ''}`
  )
}

/* --------------------------------------------------------------------------
   Project covers

   The studio's own project photography lives on a WordPress install whose
   domain does not currently resolve, so every seeded `cover_image` 404s. These
   locally hosted covers are what a record falls back to — chosen per category
   so a hospital never illustrates itself with a boutique, and picked
   deterministically from the record's id so the grid does not reshuffle on
   every render or filter change.

   They are placeholders by design: the studio replaces them per project from
   the dashboard, and the moment a real cover loads this pool is never reached.
   -------------------------------------------------------------------------- */
const PROJECT_POOL = {
  administrative: ['administrative-1', 'administrative-2', 'administrative-3'],
  commercial: ['commercial-1', 'commercial-2'],
  residential: ['residential-1', 'residential-2'],
  exterior: ['exterior-1', 'exterior-2'],
  hospitality: ['hospitality-1', 'hospitality-2'],
  landscape: ['landscape-1'],
  retail: ['retail-1'],
  industrial: ['industrial-1'],
}

const EVERY_PROJECT_IMAGE = Object.values(PROJECT_POOL).flat()

export function projectImage(project) {
  const key = matchKey(project?.category) ?? matchKey(project?.category_ar)
  const pool = (key && PROJECT_POOL[key]) || EVERY_PROJECT_IMAGE
  const id = Number(project?.id)
  const index = Number.isFinite(id) ? Math.abs(Math.trunc(id)) % pool.length : 0
  return asset(`/images/projects/${pool[index]}`, PROJECT_WIDTHS)
}

/**
 * A stand-in for the nth frame of a project's gallery.
 *
 * Every seeded gallery URL points at the studio's WordPress domain, which no
 * longer resolves — so a detail page currently shows six empty wells where the
 * work should be. This walks the whole pool rather than the category's, so a
 * six-frame gallery does not repeat the same two photographs, and it is offset
 * by the record id so two projects opened side by side do not show an identical
 * set. Deterministic: the same slot always resolves to the same frame.
 *
 * Like `projectImage`, this is only ever reached when the record's own URL
 * fails. The moment the studio's images load, none of it runs.
 */
export function projectGalleryImage(project, index) {
  const pool = EVERY_PROJECT_IMAGE
  const id = Number(project?.id)
  const offset = Number.isFinite(id) ? Math.abs(Math.trunc(id)) : 0
  const n = Number.isFinite(Number(index)) ? Math.abs(Math.trunc(Number(index))) : 0
  return asset(`/images/projects/${pool[(offset * 3 + n) % pool.length]}`, PROJECT_WIDTHS)
}

/* --------------------------------------------------------------------------
   Page banners

   One wide plate per inner page, so Services, Projects and Contact open on the
   work rather than on a heading floating in empty canvas. Alt text describes
   the frame in both languages; it is voice, not compliance.
   -------------------------------------------------------------------------- */
export const PAGE_BANNERS = {
  about: {
    ...asset('/images/banners/banner-about', BANNER_WIDTHS),
    alt: {
      en: 'A limestone-walled ramp opening onto daylight, a figure walking toward it.',
      ar: 'ممر بجدران من الحجر الجيري ينفتح على ضوء النهار، وشخص يسير باتجاهه.',
    },
  },
  services: {
    ...asset('/images/banners/banner-services', BANNER_WIDTHS),
    alt: {
      en: 'A glazed pavilion under a slatted timber soffit, opening onto mature trees.',
      ar: 'جناح زجاجي أسفل سقف خشبي مشرَّح، ينفتح على أشجار وارفة.',
    },
  },
  projects: {
    ...asset('/images/banners/banner-projects', BANNER_WIDTHS),
    alt: {
      en: 'Stacked concrete canopies cutting across an open sky.',
      ar: 'مظلات خرسانية متراكبة تقطع صفحة السماء المفتوحة.',
    },
  },
  contact: {
    ...asset('/images/banners/banner-contact', BANNER_WIDTHS),
    // The desktop and the plant sit in the upper third; a centred 3:1 crop
    // lands on the trestle legs.
    position: 'center 28%',
    alt: {
      en: 'A trestle desk against a white wall, cleared and ready for the next drawing.',
      ar: 'مكتب على حوامل خشبية أمام جدار أبيض، خالٍ ومهيّأ للرسم التالي.',
    },
  },
}

/**
 * Imagery for the About page's CMS sections.
 *
 * Each section is a PAIR: a `main` photograph that fills whatever height the
 * copy beside it takes, and a small square `detail` plate cut into its base.
 * The pair is what makes each section specific — the story gets the workshop
 * and the tools that built it, the mission gets the room and the stone it is
 * made of, the capabilities get the structure and the drawing of it.
 *
 * Every section previously resolved to the same single photograph at a fixed
 * 4:5, so the page showed one frame three times and none of them lined up with
 * the paragraph beside them.
 */
const DETAIL_WIDTHS = [400, 800]

export const ABOUT_SECTION_IMAGES = {
  story: {
    main: {
      ...asset('/images/about/about-story', ABOUT_WIDTHS),
      alt: {
        en: 'A hand guiding a chisel along timber, shavings curling away from the cut.',
        ar: 'يد توجّه إزميلًا على الخشب، والنشارة تتجعّد بعيدًا عن مسار القطع.',
      },
    },
    detail: {
      ...asset('/images/about/detail-story', DETAIL_WIDTHS),
      alt: {
        en: 'Hand planes and saws racked in order on a dark board.',
        ar: 'مساحج ومناشير يدوية مرتّبة على لوح داكن.',
      },
    },
  },
  mission: {
    main: {
      ...asset('/images/about/about-mission', ABOUT_WIDTHS),
      alt: {
        en: 'A dark steel window wall framing the sea at dusk.',
        ar: 'جدار نوافذ من الحديد الداكن يؤطّر البحر عند الغروب.',
      },
    },
    detail: {
      ...asset('/images/about/detail-mission', DETAIL_WIDTHS),
      alt: {
        en: 'Travertine, close enough to read the grain.',
        ar: 'حجر ترافرتين، عن قرب يكفي لقراءة عروقه.',
      },
    },
  },
  expertise: {
    main: {
      ...asset('/images/about/about-expertise', ABOUT_WIDTHS),
      alt: {
        en: 'A vaulted concrete interior with light raking across the floor.',
        ar: 'فراغ خرساني مقبّى ينساب الضوء فيه مائلًا على الأرضية.',
      },
    },
    detail: {
      ...asset('/images/about/detail-expertise', DETAIL_WIDTHS),
      alt: {
        en: 'Measured elevations of a building, drawn in line.',
        ar: 'واجهات مقاسة لمبنى، مرسومة بالخط.',
      },
    },
  },
}
