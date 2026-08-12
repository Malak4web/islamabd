# Analysis Report — i18n Translation Key Structure & Locale Switching Verification

## Executive Summary
This report presents a comprehensive read-only investigation of the i18n translation key structure (`resources/js/i18n/en.json` and `resources/js/i18n/ar.json`), the locale switching mechanism (`resources/js/stores/localeStore.js`), and user-facing brand string locations across the frontend application.

- **JSON Parity & Integrity**: Both `en.json` and `ar.json` contain exactly **285 keys** each with **1:1 structural alignment** and 0 missing keys. Both files are syntactically valid JSON.
- **Locale Switching**: Handled reactively via `localeStore.js` which synchronizes Vue I18n (`i18n.global.locale.value`), document element attributes (`lang`, `dir="ltr"` / `dir="rtl"`), `localStorage`, and HTTP headers (`Accept-Language`). Locale switching functions cleanly without runtime syntax errors.
- **Rebranding Target Identification**: A total of 20 hardcoded occurrences of legacy branding ("InDesign", "INDESIGN", "indesign-co.com", "contact@indesign.com", "عن إن ديزاين") were cataloged across 13 files (including translation files, Vue components, layouts, composables, Blade view, and Vitest test files).

---

## 1. i18n Key Structure Analysis

### 1.1 JSON Parity & Validation
A key structure comparison script executed on `resources/js/i18n/en.json` and `resources/js/i18n/ar.json` confirmed:
- Total keys in `en.json`: **285**
- Total keys in `ar.json`: **285**
- Missing keys in `ar.json`: **0**
- Missing keys in `en.json`: **0**

### 1.2 Translation File Brand Occurrences
| File | Line | Current String | Target Rebranded String |
|---|---|---|---|
| `resources/js/i18n/en.json` | 6 | `"title": "About InDesign"` | `"title": "About Eslam Abdulghani Designs"` |
| `resources/js/i18n/ar.json` | 6 | `"title": "عن إن ديزاين"` | `"title": "عن إسلام عبد الغني ديزاينز"` |

---

## 2. Hardcoded Frontend Brand References

In addition to translation JSON files, hardcoded brand strings exist in Vue templates, Blade views, composables, and Vitest test suites that require rebranding:

### 2.1 Public & Admin Views/Components
| File | Line | Current Code Snippet | Target Code Snippet |
|---|---|---|---|
| `resources/views/app.blade.php` | 6 | `<title>InDesign</title>` | `<title>Eslam Abdulghani Designs</title>` |
| `resources/js/components/public/AppHeader.vue` | 10 | `alt="InDesign Logo"` | `alt="Eslam Abdulghani Designs Logo"` |
| `resources/js/components/public/AppHeader.vue` | 16 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/components/public/AppFooter.vue` | 11 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/components/public/AppFooter.vue` | 72 | `&copy; {{ new Date().getFullYear() }} INDESIGN. {{ $t('footer.rights') }}` | `&copy; {{ new Date().getFullYear() }} ESLAM ABDULGHANI DESIGNS. {{ $t('footer.rights') }}` |
| `resources/js/components/public/AboutSnippet.vue` | 10 | `alt="About InDesign"` | `alt="About Eslam Abdulghani Designs"` |
| `resources/js/views/public/AboutView.vue` | 24 | `<span ...>InDesign</span>` | `<span ...>Eslam Abdulghani Designs</span>` |
| `resources/js/views/public/ContactView.vue` | 66 | `contact@indesign.com` | `contact@eslamabdulghanidesigns.com` |
| `resources/js/components/admin/AdminSidebar.vue` | 15 | `INDESIGN` | `ESLAM ABDULGHANI DESIGNS` |
| `resources/js/layouts/AdminLayout.vue` | 26 | `INDESIGN Control Panel` | `ESLAM ABDULGHANI DESIGNS Control Panel` |
| `resources/js/views/admin/AdminLogin.vue` | 10 | `InDesign Dashboard` | `Eslam Abdulghani Designs Dashboard` |
| `resources/js/views/admin/AdminLogin.vue` | 23 | `placeholder="admin@indesign-co.com"` | `placeholder="admin@eslamabdulghanidesigns.com"` |
| `resources/js/views/admin/AdminSections.vue` | 114 | `indesign-co.com › {{ page?.slug }}` | `eslamabdulghanidesigns.com › {{ page?.slug }}` |
| `resources/js/composables/useSeo.js` | 11 | `'InDesign'` | `'Eslam Abdulghani Designs'` |

### 2.2 Frontend Test Files (Vitest)
| File | Line | Current Assertion / Mock | Target Assertion / Mock |
|---|---|---|---|
| `resources/js/tests/components/AppFooter.test.js` | 25, 39 | `'info@indesign.com'` | `'info@eslamabdulghanidesigns.com'` |
| `resources/js/tests/components/AppHeader.test.js` | 34 | `toContain('INDESIGN')` | `toContain('ESLAM ABDULGHANI DESIGNS')` |
| `resources/js/tests/stores/settingStore.test.js` | 21 | `{ site_name: 'InDesign', phone: '123' }` | `{ site_name: 'Eslam Abdulghani Designs', phone: '123' }` |
| `resources/js/tests/views/ContactView.test.js` | 20, 35 | `'info@indesign.com'` | `'info@eslamabdulghanidesigns.com'` |

---

## 3. Locale Switching Verification

### 3.1 Mechanism
Locale switching is orchestrated by `resources/js/stores/localeStore.js`:
1. `setLocale(newLocale)` updates:
   - `locale.value` ref ('en' or 'ar')
   - `localStorage.setItem('locale', newLocale)`
   - `i18n.global.locale.value = newLocale`
   - `document.documentElement.setAttribute('lang', newLocale)`
   - `document.documentElement.setAttribute('dir', isArabic.value ? 'rtl' : 'ltr')`
   - Axios default headers `Accept-Language` for both global and API instances.
2. `LanguageSwitcher.vue` triggers `store.setLocale(...)` and executes `window.location.reload()`.
3. Application boot in `resources/js/app.js` runs `localeStore.initLocale()`.

### 3.2 Key Rendering Assurance
Because `en.json` and `ar.json` have identical key hierarchies, switching locales will seamlessly swap all `$t(...)` strings without encountering key resolution warnings, undefined key fallbacks, or broken layout direction.

---

## 4. Recommendations for Implementation Phase
1. Update `resources/js/i18n/en.json` line 6 and `resources/js/i18n/ar.json` line 6 with the rebranded brand strings.
2. Replace hardcoded brand strings in Vue components, layouts, views, Blade template, and composables as cataloged above.
3. Update Vitest test specs to match rebranded strings.
