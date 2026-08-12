# Analysis Report: Milestone 1 (Frontend & Translations Rebranding)

**Explorer**: Explorer M1-1  
**Target Scope**: Vue components, Blade template, translation JSON files, composables  
**Status**: Investigation Complete — Ready for Worker Implementation  

---

## Executive Summary

A comprehensive scan of all 13 mapped frontend files in `resources/` identified 14 distinct brand string occurrences requiring replacement. The rebranding rules follow strict case-sensitivity, locale mapping, and domain placeholder conventions:
1. **Title Case English** (`InDesign` / `In Design` -> `Eslam Abdulghani Designs`)
2. **ALL CAPS English** (`INDESIGN` -> `ESLAM ABDULGHANI DESIGNS`)
3. **Arabic Brand** (`عن إن ديزاين` -> `عن إسلام عبد الغني ديزاينز`)
4. **Domain & Email Placeholders** (`indesign-co.com` -> `eslamabdulghanidesigns.com`, `contact@indesign.com` -> `contact@eslamabdulghanidesigns.com`, `admin@indesign-co.com` -> `admin@eslamabdulghanidesigns.com`)

---

## File-by-File Edit Specifications

### 1. `resources/js/i18n/en.json`
- **Line**: 6
- **Target Content**: `"title": "About InDesign",`
- **Replacement Content**: `"title": "About Eslam Abdulghani Designs",`
- **Rationale**: English translation string for the About page title.

### 2. `resources/js/i18n/ar.json`
- **Line**: 6
- **Target Content**: `"title": "عن إن ديزاين",`
- **Replacement Content**: `"title": "عن إسلام عبد الغني ديزاينز",`
- **Rationale**: Arabic translation string for the About page title.

### 3. `resources/views/app.blade.php`
- **Line**: 6
- **Target Content**: `<title>InDesign</title>`
- **Replacement Content**: `<title>Eslam Abdulghani Designs</title>`
- **Rationale**: HTML document head title fallback.

### 4. `resources/js/components/admin/AdminSidebar.vue`
- **Line**: 15
- **Target Content**: `<span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">INDESIGN</span>`
- **Replacement Content**: `<span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">ESLAM ABDULGHANI DESIGNS</span>`
- **Rationale**: Uppercase brand title in admin sidebar.

### 5. `resources/js/components/public/AboutSnippet.vue`
- **Line**: 10
- **Target Content**: `alt="About InDesign"`
- **Replacement Content**: `alt="About Eslam Abdulghani Designs"`
- **Rationale**: Accessibility image alt attribute.

### 6. `resources/js/components/public/AppFooter.vue`
- **Line**: 11
- **Target Content**: `<span class="text-xl font-black tracking-widest text-white uppercase">INDESIGN</span>`
- **Replacement Content**: `<span class="text-xl font-black tracking-widest text-white uppercase">ESLAM ABDULGHANI DESIGNS</span>`
- **Rationale**: Uppercase brand logo in public footer.

- **Line**: 72
- **Target Content**: `&copy; {{ new Date().getFullYear() }} INDESIGN. {{ $t('footer.rights') }}`
- **Replacement Content**: `&copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS. {{ $t('footer.rights') }}`
- **Rationale**: Uppercase brand name in copyright notice.

### 7. `resources/js/components/public/AppHeader.vue`
- **Line**: 10
- **Target Content**: `alt="InDesign Logo"`
- **Replacement Content**: `alt="Eslam Abdulghani Designs Logo"`
- **Rationale**: Accessibility image alt attribute for header logo.

- **Line**: 16
- **Target Content**: `<span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">INDESIGN</span>`
- **Replacement Content**: `<span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">ESLAM ABDULGHANI DESIGNS</span>`
- **Rationale**: Uppercase text logo fallback in header.

### 8. `resources/js/composables/useSeo.js`
- **Line**: 11
- **Target Content**: `const title = page.value.meta_title || page.value.title || 'InDesign'`
- **Replacement Content**: `const title = page.value.meta_title || page.value.title || 'Eslam Abdulghani Designs'`
- **Rationale**: Default title fallback for SEO head management.

### 9. `resources/js/layouts/AdminLayout.vue`
- **Line**: 26
- **Target Content**: `&copy; {{ new Date().getFullYear() }} INDESIGN Control Panel. All Rights Reserved.`
- **Replacement Content**: `&copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS Control Panel. All Rights Reserved.`
- **Rationale**: Uppercase brand in admin panel copyright footer.

### 10. `resources/js/views/admin/AdminLogin.vue`
- **Line**: 10
- **Target Content**: `<h1 class="text-3xl font-bold text-white mb-2">InDesign Dashboard</h1>`
- **Replacement Content**: `<h1 class="text-3xl font-bold text-white mb-2">Eslam Abdulghani Designs Dashboard</h1>`
- **Rationale**: Title header on administrative login view.

- **Line**: 23
- **Target Content**: `placeholder="admin@indesign-co.com"`
- **Replacement Content**: `placeholder="admin@eslamabdulghanidesigns.com"`
- **Rationale**: Input placeholder email domain.

### 11. `resources/js/views/admin/AdminSections.vue`
- **Line**: 114
- **Target Content**: `<p class="text-[#006621] text-sm mb-1">indesign-co.com › {{ page?.slug }}</p>`
- **Replacement Content**: `<p class="text-[#006621] text-sm mb-1">eslamabdulghanidesigns.com › {{ page?.slug }}</p>`
- **Rationale**: Google search result snippet URL preview domain.

### 12. `resources/js/views/public/AboutView.vue`
- **Line**: 24
- **Target Content**: `<span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">InDesign</span>`
- **Replacement Content**: `<span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">Eslam Abdulghani Designs</span>`
- **Rationale**: Hero section stylized title span on public About page.

### 13. `resources/js/views/public/ContactView.vue`
- **Line**: 66
- **Target Content**: `<p class="text-sm text-gray-600">contact@indesign.com</p>`
- **Replacement Content**: `<p class="text-sm text-gray-600">contact@eslamabdulghanidesigns.com</p>`
- **Rationale**: Secondary contact email display string.

---

## Verification Matrix

| File Path | Line(s) | Match Pattern | Proposed Replacement |
|---|---|---|---|
| `resources/js/i18n/en.json` | 6 | `"About InDesign"` | `"About Eslam Abdulghani Designs"` |
| `resources/js/i18n/ar.json` | 6 | `"عن إن ديزاين"` | `"عن إسلام عبد الغني ديزاينز"` |
| `resources/views/app.blade.php` | 6 | `<title>InDesign</title>` | `<title>Eslam Abdulghani Designs</title>` |
| `resources/js/components/admin/AdminSidebar.vue` | 15 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/components/public/AboutSnippet.vue` | 10 | `alt="About InDesign"` | `alt="About Eslam Abdulghani Designs"` |
| `resources/js/components/public/AppFooter.vue` | 11, 72 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/components/public/AppHeader.vue` | 10, 16 | `alt="InDesign Logo"` / `INDESIGN` | `alt="Eslam Abdulghani Designs Logo"` / `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/composables/useSeo.js` | 11 | `'InDesign'` | `'Eslam Abdulghani Designs'` |
| `resources/js/layouts/AdminLayout.vue` | 26 | `INDESIGN Control Panel` | `ESLAM ABDULGHANI DESIGNS Control Panel` |
| `resources/js/views/admin/AdminLogin.vue` | 10, 23 | `InDesign Dashboard` / `admin@indesign-co.com` | `Eslam Abdulghani Designs Dashboard` / `admin@eslamabdulghanidesigns.com` |
| `resources/js/views/admin/AdminSections.vue` | 114 | `indesign-co.com` | `eslamabdulghanidesigns.com` |
| `resources/js/views/public/AboutView.vue` | 24 | `InDesign` | `Eslam Abdulghani Designs` |
| `resources/js/views/public/ContactView.vue` | 66 | `contact@indesign.com` | `contact@eslamabdulghanidesigns.com` |
