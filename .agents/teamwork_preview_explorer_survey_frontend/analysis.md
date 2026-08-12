# Frontend Rebranding Survey Report

## Executive Summary
This report catalogs all occurrences of `"InDesign"`, `"In Design"`, and `"إن ديزاين"` within the frontend codebase located under `resources/js` (components, views, layouts, composables, translation files, tests) and `resources/views` (Blade views).

A total of **22 occurrences** across **14 files** were identified.

---

## Detailed Occurrences & Proposed Replacements

### 1. Translation Files (`resources/js/i18n/`)

#### File: `resources/js/i18n/en.json`
- **Line 6**:
  - *Current snippet*: `"title": "About InDesign",`
  - *Target snippet*: `"title": "About Eslam Abdulghani Designs",`
  - *Replacement String*: `About Eslam Abdulghani Designs`

#### File: `resources/js/i18n/ar.json`
- **Line 6**:
  - *Current snippet*: `"title": "عن إن ديزاين",`
  - *Target snippet*: `"title": "عن إسلام عبد الغني ديزاينز",`
  - *Replacement String*: `عن إسلام عبد الغني ديزاينز`

---

### 2. Blade Views (`resources/views/`)

#### File: `resources/views/app.blade.php`
- **Line 6**:
  - *Current snippet*: `<title>InDesign</title>`
  - *Target snippet*: `<title>Eslam Abdulghani Designs</title>`
  - *Replacement String*: `Eslam Abdulghani Designs`

---

### 3. Vue Components (`resources/js/components/`)

#### File: `resources/js/components/admin/AdminSidebar.vue`
- **Line 15**:
  - *Current snippet*: `<span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">INDESIGN</span>`
  - *Target snippet*: `<span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">ESLAM ABDULGHANI DESIGNS</span>`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`

#### File: `resources/js/components/public/AboutSnippet.vue`
- **Line 10**:
  - *Current snippet*: `alt="About InDesign"`
  - *Target snippet*: `alt="About Eslam Abdulghani Designs"`
  - *Replacement String*: `About Eslam Abdulghani Designs`

#### File: `resources/js/components/public/AppFooter.vue`
- **Line 11**:
  - *Current snippet*: `<span class="text-xl font-black tracking-widest text-white uppercase">INDESIGN</span>`
  - *Target snippet*: `<span class="text-xl font-black tracking-widest text-white uppercase">ESLAM ABDULGHANI DESIGNS</span>`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`
- **Line 72**:
  - *Current snippet*: `&copy; {{ new Date().getFullYear() }} INDESIGN. {{ $t('footer.rights') }}`
  - *Target snippet*: `&copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS. {{ $t('footer.rights') }}`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`

#### File: `resources/js/components/public/AppHeader.vue`
- **Line 10**:
  - *Current snippet*: `alt="InDesign Logo"`
  - *Target snippet*: `alt="Eslam Abdulghani Designs Logo"`
  - *Replacement String*: `Eslam Abdulghani Designs Logo`
- **Line 16**:
  - *Current snippet*: `<span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">INDESIGN</span>`
  - *Target snippet*: `<span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">ESLAM ABDULGHANI DESIGNS</span>`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`

---

### 4. Composables (`resources/js/composables/`)

#### File: `resources/js/composables/useSeo.js`
- **Line 11**:
  - *Current snippet*: `const title = page.value.meta_title || page.value.title || 'InDesign'`
  - *Target snippet*: `const title = page.value.meta_title || page.value.title || 'Eslam Abdulghani Designs'`
  - *Replacement String*: `Eslam Abdulghani Designs`

---

### 5. Layouts (`resources/js/layouts/`)

#### File: `resources/js/layouts/AdminLayout.vue`
- **Line 26**:
  - *Current snippet*: `&copy; {{ new Date().getFullYear() }} INDESIGN Control Panel. All Rights Reserved.`
  - *Target snippet*: `&copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS Control Panel. All Rights Reserved.`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`

---

### 6. Vue Views (`resources/js/views/`)

#### File: `resources/js/views/admin/AdminLogin.vue`
- **Line 10**:
  - *Current snippet*: `<h1 class="text-3xl font-bold text-white mb-2">InDesign Dashboard</h1>`
  - *Target snippet*: `<h1 class="text-3xl font-bold text-white mb-2">Eslam Abdulghani Designs Dashboard</h1>`
  - *Replacement String*: `Eslam Abdulghani Designs Dashboard`
- **Line 23**:
  - *Current snippet*: `placeholder="admin@indesign-co.com"`
  - *Target snippet*: `placeholder="admin@eslamabdulghanidesigns.com"`
  - *Replacement String*: `admin@eslamabdulghanidesigns.com`

#### File: `resources/js/views/admin/AdminSections.vue`
- **Line 114**:
  - *Current snippet*: `<p class="text-[#006621] text-sm mb-1">indesign-co.com › {{ page?.slug }}</p>`
  - *Target snippet*: `<p class="text-[#006621] text-sm mb-1">eslamabdulghanidesigns.com › {{ page?.slug }}</p>`
  - *Replacement String*: `eslamabdulghanidesigns.com`

#### File: `resources/js/views/public/AboutView.vue`
- **Line 24**:
  - *Current snippet*: `<span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">InDesign</span>`
  - *Target snippet*: `<span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">Eslam Abdulghani Designs</span>`
  - *Replacement String*: `Eslam Abdulghani Designs`

#### File: `resources/js/views/public/ContactView.vue`
- **Line 66**:
  - *Current snippet*: `<p class="text-sm text-gray-600">contact@indesign.com</p>`
  - *Target snippet*: `<p class="text-sm text-gray-600">contact@eslamabdulghanidesigns.com</p>`
  - *Replacement String*: `contact@eslamabdulghanidesigns.com`

---

### 7. JS Unit Tests (`resources/js/tests/`)

#### File: `resources/js/tests/components/AppFooter.test.js`
- **Line 25**:
  - *Current snippet*: `email_main: 'info@indesign.com',`
  - *Target snippet*: `email_main: 'info@eslamabdulghanidesigns.com',`
  - *Replacement String*: `info@eslamabdulghanidesigns.com`
- **Line 39**:
  - *Current snippet*: `expect(wrapper.text()).toContain('info@indesign.com')`
  - *Target snippet*: `expect(wrapper.text()).toContain('info@eslamabdulghanidesigns.com')`
  - *Replacement String*: `info@eslamabdulghanidesigns.com`

#### File: `resources/js/tests/components/AppHeader.test.js`
- **Line 34**:
  - *Current snippet*: `expect(wrapper.text()).toContain('INDESIGN')`
  - *Target snippet*: `expect(wrapper.text()).toContain('ESLAM ABDULGHANI DESIGNS')`
  - *Replacement String*: `ESLAM ABDULGHANI DESIGNS`

#### File: `resources/js/tests/stores/settingStore.test.js`
- **Line 21**:
  - *Current snippet*: `const mockData = { site_name: 'InDesign', phone: '123' }`
  - *Target snippet*: `const mockData = { site_name: 'Eslam Abdulghani Designs', phone: '123' }`
  - *Replacement String*: `Eslam Abdulghani Designs`

#### File: `resources/js/tests/views/ContactView.test.js`
- **Line 20**:
  - *Current snippet*: `email_main: 'info@indesign.com'`
  - *Target snippet*: `email_main: 'info@eslamabdulghanidesigns.com'`
  - *Replacement String*: `info@eslamabdulghanidesigns.com`
- **Line 35**:
  - *Current snippet*: `expect(wrapper.text()).toContain('info@indesign.com')`
  - *Target snippet*: `expect(wrapper.text()).toContain('info@eslamabdulghanidesigns.com')`
  - *Replacement String*: `info@eslamabdulghanidesigns.com`

---

## Summary Matrix

| Category | File Path | Line # | Current Text | Proposed Text |
|---|---|---|---|---|
| Translation | `resources/js/i18n/en.json` | 6 | `"title": "About InDesign"` | `"title": "About Eslam Abdulghani Designs"` |
| Translation | `resources/js/i18n/ar.json` | 6 | `"title": "عن إن ديزاين"` | `"title": "عن إسلام عبد الغني ديزاينز"` |
| Blade View | `resources/views/app.blade.php` | 6 | `<title>InDesign</title>` | `<title>Eslam Abdulghani Designs</title>` |
| Component | `resources/js/components/admin/AdminSidebar.vue` | 15 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| Component | `resources/js/components/public/AboutSnippet.vue` | 10 | `alt="About InDesign"` | `alt="About Eslam Abdulghani Designs"` |
| Component | `resources/js/components/public/AppFooter.vue` | 11 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| Component | `resources/js/components/public/AppFooter.vue` | 72 | `&copy; ... INDESIGN.` | `&copy; ... ESLAM ABDULGHANI DESIGNS.` |
| Component | `resources/js/components/public/AppHeader.vue` | 10 | `alt="InDesign Logo"` | `alt="Eslam Abdulghani Designs Logo"` |
| Component | `resources/js/components/public/AppHeader.vue` | 16 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| Composable | `resources/js/composables/useSeo.js` | 11 | `'InDesign'` | `'Eslam Abdulghani Designs'` |
| Layout | `resources/js/layouts/AdminLayout.vue` | 26 | `INDESIGN Control Panel` | `ESLAM ABDULGHANI DESIGNS Control Panel` |
| View | `resources/js/views/admin/AdminLogin.vue` | 10 | `InDesign Dashboard` | `Eslam Abdulghani Designs Dashboard` |
| View | `resources/js/views/admin/AdminLogin.vue` | 23 | `placeholder="admin@indesign-co.com"` | `placeholder="admin@eslamabdulghanidesigns.com"` |
| View | `resources/js/views/admin/AdminSections.vue` | 114 | `indesign-co.com` | `eslamabdulghanidesigns.com` |
| View | `resources/js/views/public/AboutView.vue` | 24 | `InDesign` | `Eslam Abdulghani Designs` |
| View | `resources/js/views/public/ContactView.vue` | 66 | `contact@indesign.com` | `contact@eslamabdulghanidesigns.com` |
| Test | `resources/js/tests/components/AppFooter.test.js` | 25 | `info@indesign.com` | `info@eslamabdulghanidesigns.com` |
| Test | `resources/js/tests/components/AppFooter.test.js` | 39 | `info@indesign.com` | `info@eslamabdulghanidesigns.com` |
| Test | `resources/js/tests/components/AppHeader.test.js` | 34 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| Test | `resources/js/tests/stores/settingStore.test.js` | 21 | `'InDesign'` | `'Eslam Abdulghani Designs'` |
| Test | `resources/js/tests/views/ContactView.test.js` | 20 | `info@indesign.com` | `info@eslamabdulghanidesigns.com` |
| Test | `resources/js/tests/views/ContactView.test.js` | 35 | `info@indesign.com` | `info@eslamabdulghanidesigns.com` |

---

## Verification Plan
1. Re-run regex search across `resources/js` and `resources/views` after replacement to ensure zero occurrences of `"InDesign"`, `"In Design"`, and `"إن ديزاين"`.
2. Run `npx vitest run` or `npm run test` to verify all JS unit tests pass.
3. Run `npm run build` to verify frontend compilation succeeds without asset errors.
