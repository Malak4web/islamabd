---
description: w10
---

# WORKFLOW 10 — Public Frontend (Vue.js)
# Gate: ALL public frontend tests green before WF-11
# Prerequisite: WF-09 gate fully green
════════════════════════════════════════════════════════

## Purpose
All public-facing Vue.js pages and components. Homepage with hero,
services grid, projects gallery, about, and contact. All bilingual,
SEO-optimized via useSeo composable, and data-driven from public APIs.

## Pages
  /                      HomeView.vue
  /about                 AboutView.vue
  /services              ServicesView.vue
  /services/:id          ServiceDetailView.vue
  /projects              ProjectsView.vue
  /projects/:id          ProjectDetailView.vue
  /contact               ContactView.vue

════════════════════════════════════════════════════════
## STEP 1 — Component Tree
════════════════════════════════════════════════════════
  App.vue
  ├── AppHeader.vue
  │    ├── NavLinks.vue (RouterLink per page)
  │    ├── LanguageSwitcher.vue
  │    └── MobileMenuToggle.vue
  ├── CodeInjector.vue (invisible, global)
  ├── <RouterView />
  │    ├── HomeView.vue
  │    │    ├── HeroSlider.vue
  │    │    ├── AboutSnippet.vue
  │    │    ├── ServicesPreview.vue → ServiceCard.vue ×6
  │    │    ├── ProjectsPreview.vue → ProjectCard.vue ×3
  │    │    └── CtaBanner.vue
  │    ├── AboutView.vue
  │    │    ├── StorySection.vue
  │    │    ├── StatsBar.vue
  │    │    └── VisionMission.vue
  │    ├── ServicesView.vue → ServiceCard.vue ×N
  │    ├── ServiceDetailView.vue
  │    ├── ProjectsView.vue
  │    │    ├── CategoryFilter.vue
  │    │    └── ProjectCard.vue ×N (paginated)
  │    ├── ProjectDetailView.vue → ImageGallery.vue
  │    └── ContactView.vue
  │         ├── ContactInfo.vue
  │         └── ContactForm.vue
  ├── AppFooter.vue
  └── FloatingSocial.vue (fixed left/right sidebar)

════════════════════════════════════════════════════════
## STEP 2 — App.vue & Global Layout
════════════════════════════════════════════════════════
  onMounted:
    localeStore.initLocale()
    settingStore.fetchSettings()

  Page transitions (fade+slide):
    <RouterView v-slot="{Component}">
      <Transition name="page" mode="out-in">
        <component :is="Component" />
      </Transition>
    </RouterView>

  CSS:
    .page-enter-from { opacity:0; transform:translateY(20px) }
    .page-leave-to   { opacity:0; transform:translateY(-10px) }
    .page-enter-active, .page-leave-active { transition: all 0.3s }

════════════════════════════════════════════════════════
## STEP 3 — AppHeader.vue
════════════════════════════════════════════════════════
  Sticky header that transitions on scroll:
    const isScrolled = ref(false)
    window.addEventListener('scroll', () => isScrolled.value = window.scrollY > 80)
    :class="isScrolled ? 'bg-slate-900/95 backdrop-blur shadow-lg' : 'bg-transparent'"

  Logo: from settingStore.settings.logo | fallback: text "InDesign"
  Desktop nav: RouterLink to Home, About, Services, Projects, Contact
  Right: LanguageSwitcher | "Contact Us" CTA button → /contact
  Mobile: hamburger button → slide-down overlay with all nav links

════════════════════════════════════════════════════════
## STEP 4 — HomeView.vue Sections
════════════════════════════════════════════════════════
  onMounted: pageStore.fetchPage('home')
  All section data from currentPage.sections

  HeroSlider.vue:
    Full-screen (100vh) with parallax background image
    Animated text: title, subtitle (slide up + fade in)
    Two CTA buttons: "View Projects" → /projects | "Contact Us" → /contact
    Scroll indicator arrow

  AboutSnippet.vue:
    2-column layout: text left, image right (rtl:reversed)
    Animated counters: Years, Projects, Clients
    "Learn More" → /about

  ServicesPreview.vue:
    Section heading from content
    Grid of first 6 active ServiceCard.vue
    "View All Services" → /services

  ProjectsPreview.vue:
    3 featured ProjectCard.vue
    "View All Projects" → /projects

  CtaBanner.vue:
    Full-width dark gradient banner
    Heading, subheading from section content
    "Get In Touch" → /contact

════════════════════════════════════════════════════════
## STEP 5 — ProjectsView.vue with Filter
════════════════════════════════════════════════════════
  CategoryFilter.vue:
    Tabs: All | Residential | Commercial | Hospitality | Retail | Landscape
    Active tab: brand gold underline
    Click → router.push({ query:{ category:slug } })
    URL-based: shareable links (?category=residential)

  ProjectsView.vue:
    watch(route.query, () => refetch with new filter, { immediate:true })
    Masonry grid layout, 3 cols → 2 → 1 responsive
    Loading skeleton while fetching
    "Load More" button (or infinite scroll)
    "No projects found" empty state

  ImageGallery.vue (in ProjectDetailView):
    Main image + thumbnail strip
    Click thumbnail → swap main
    Click main → lightbox (fullscreen)
    Lightbox: prev/next arrows, keyboard ESC, swipe on touch

════════════════════════════════════════════════════════
## STEP 6 — useSeo Composable
════════════════════════════════════════════════════════
  FILE: resources/js/composables/useSeo.js
    export function useSeo(page) {
      watchEffect(() => {
        if (!page.value) return
        document.title = page.value.meta_title || page.value.title || 'InDesign'
        setMeta('description', page.value.meta_description)
        setMeta('og:title', page.value.meta_title)
        setMeta('og:description', page.value.meta_description)
        setMeta('og:image', page.value.og_image)
      })
    }
    function setMeta(name, content) {
      let el = document.querySelector(`meta[name="${name}"],meta[property="${name}"]`)
      if (!el) {
        el = document.createElement('meta')
        el.setAttribute(name.startsWith('og:') ? 'property' : 'name', name)
        document.head.appendChild(el)
      }
      el.setAttribute('content', content || '')
    }

  Used in every page view:
    const { currentPage } = storeToRefs(pageStore)
    useSeo(currentPage)

════════════════════════════════════════════════════════
## STEP 7 — AppFooter.vue & FloatingSocial.vue
════════════════════════════════════════════════════════
  AppFooter.vue (3 columns):
    About: logo, site description, social icons row
    Links: nav links to all 5 pages
    Contact: phone, email, address (all from settingStore)
    Bottom: footer_text + back-to-top button

  FloatingSocial.vue (fixed left sidebar, rtl:right):
    WhatsApp (green, pulse animation) → wa.me/{whatsapp number}
    Phone (blue) → tel:{phone_1}
    Email (slate) → mailto:{email_main}
    Labels appear on hover

════════════════════════════════════════════════════════
## STEP 8 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/views/HomeView.test.js:
    test_homeview_fetches_home_page_on_mount
    test_renders_hero_section_when_data_loaded
    test_renders_services_preview
    test_renders_projects_preview
    test_renders_cta_banner

  tests/views/ProjectsView.test.js:
    test_renders_category_filter_tabs
    test_residential_tab_fetches_filtered_projects
    test_category_filter_updates_url_query
    test_shows_loading_state_while_fetching
    test_shows_empty_state_when_no_projects

  tests/views/ContactView.test.js:
    test_renders_contact_info_with_phone_and_email
    test_renders_contact_form_component
    test_address_shows_in_current_locale

  tests/components/AppHeader.test.js:
    test_renders_nav_links_for_all_pages
    test_shows_mobile_menu_on_hamburger_click
    test_header_becomes_scrolled_after_scroll
    test_renders_language_switcher

  tests/components/AppFooter.test.js:
    test_renders_social_links_from_settings
    test_renders_nav_links
    test_renders_phone_and_email_from_settings
    test_renders_copyright_text

  tests/composables/useSeo.test.js:
    test_sets_document_title_from_meta_title
    test_sets_og_image_meta_tag
    test_creates_meta_tag_if_not_exists
    test_updates_title_when_page_changes

════════════════════════════════════════════════════════
## 🔴 TDD GATE 10 — ALL MUST GREEN BEFORE WF-11
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-11 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] npm run test -- tests/views/HomeView.test.js
  [ ] npm run test -- tests/views/ProjectsView.test.js
  [ ] npm run test -- tests/views/ContactView.test.js
  [ ] npm run test -- tests/components/AppHeader.test.js
  [ ] npm run test -- tests/components/AppFooter.test.js
  [ ] npm run test -- tests/composables/useSeo.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests green (WF-00 to WF-10)

  MANUAL VISUAL REVIEW:
  [ ] Homepage: hero, about snippet, services, projects, CTA
  [ ] Hero: full-screen image, animated text, two CTA buttons
  [ ] Header: transparent at top → frosted on scroll
  [ ] Mobile menu: opens/closes with hamburger
  [ ] Arabic mode: RTL layout, Cairo font
  [ ] Projects: category filter tabs, URL updates
  [ ] Project detail: gallery thumbnails + lightbox
  [ ] Contact: form + map + info panel
  [ ] Footer: logo, links, socials, copyright
  [ ] WhatsApp floating button visible and clickable
  [ ] SEO tags correct in view-source

  ALL GREEN → ✅ PROCEED TO WF-11
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  HomeView sections not rendering:
    Check pageStore.fetchPage('home') in onMounted
    Add v-if="currentPage" guard before rendering sections
    Verify /api/v1/pages/home returns 200 with sections

  Category filter not updating URL:
    Use router.push({ query:{ category:value } })
    Watch route.query.category with { immediate:true }
    Don't v-model directly on route.query

  Lightbox not closing on ESC:
    document.addEventListener('keydown', e => e.key==='Escape' && close())
    Remove listener in onUnmounted to prevent leaks

  useSeo test fails (meta not in document):
    Clear document.head.innerHTML = '' in afterEach
    Check useSeo creates <meta> in document.head (not document.body)

  Header scroll test fails:
    window.scrollY cannot be manually set in jsdom
    Test reactive ref directly: isScrolled.value = true, check class

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-11
════════════════════════════════════════════════════════
  [ ] All public frontend tests GREEN
  [ ] Regression suite GREEN (WF-00 through WF-10)
  [ ] Visual review of all pages complete
  [ ] All pages responsive (320px → 1920px)
  [ ] git commit -m "feat: complete public frontend with all pages"
  [ ] NEXT → 11_admin_dashboard.md
