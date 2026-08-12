# Handoff Report — Frontend Survey (Explorer 1)

## 1. Observation
- Target directory: `c:\xampp\htdocs\islamabd\resources\js` and `c:\xampp\htdocs\islamabd\resources\views`
- Search queries performed: `InDesign`, `In Design`, `إن ديزاين` (case-insensitive & exact regex matches)
- Output findings: Total 22 occurrences in 14 files.

### Exact File & Line Observations:
1. `resources/js/i18n/en.json:6` -> `"title": "About InDesign",`
2. `resources/js/i18n/ar.json:6` -> `"title": "عن إن ديزاين",`
3. `resources/views/app.blade.php:6` -> `<title>InDesign</title>`
4. `resources/js/components/admin/AdminSidebar.vue:15` -> `<span v-if="!isCollapsed" class="text-lg font-black tracking-tighter text-white uppercase">INDESIGN</span>`
5. `resources/js/components/public/AboutSnippet.vue:10` -> `alt="About InDesign"`
6. `resources/js/components/public/AppFooter.vue:11` -> `<span class="text-xl font-black tracking-widest text-white uppercase">INDESIGN</span>`
7. `resources/js/components/public/AppFooter.vue:72` -> `&copy; {{ new Date().getFullYear() }} INDESIGN. {{ $t('footer.rights') }}`
8. `resources/js/components/public/AppHeader.vue:10` -> `alt="InDesign Logo"`
9. `resources/js/components/public/AppHeader.vue:16` -> `<span class="text-2xl font-black tracking-widest text-white uppercase transition-colors duration-300 group-hover:text-[#d4af37]">INDESIGN</span>`
10. `resources/js/composables/useSeo.js:11` -> `const title = page.value.meta_title || page.value.title || 'InDesign'`
11. `resources/js/layouts/AdminLayout.vue:26` -> `&copy; {{ new Date().getFullYear() }} INDESIGN Control Panel. All Rights Reserved.`
12. `resources/js/views/admin/AdminLogin.vue:10` -> `<h1 class="text-3xl font-bold text-white mb-2">InDesign Dashboard</h1>`
13. `resources/js/views/admin/AdminLogin.vue:23` -> `placeholder="admin@indesign-co.com"`
14. `resources/js/views/admin/AdminSections.vue:114` -> `<p class="text-[#006621] text-sm mb-1">indesign-co.com › {{ page?.slug }}</p>`
15. `resources/js/views/public/AboutView.vue:24` -> `<span class="block text-transparent bg-clip-text bg-gradient-to-r from-[#d4af37] via-white to-[#d4af37] bg-[length:200%_auto] animate-gradient-text mt-4">InDesign</span>`
16. `resources/js/views/public/ContactView.vue:66` -> `<p class="text-sm text-gray-600">contact@indesign.com</p>`
17. `resources/js/tests/components/AppFooter.test.js:25` -> `email_main: 'info@indesign.com',`
18. `resources/js/tests/components/AppFooter.test.js:39` -> `expect(wrapper.text()).toContain('info@indesign.com')`
19. `resources/js/tests/components/AppHeader.test.js:34` -> `expect(wrapper.text()).toContain('INDESIGN')`
20. `resources/js/tests/stores/settingStore.test.js:21` -> `const mockData = { site_name: 'InDesign', phone: '123' }`
21. `resources/js/tests/views/ContactView.test.js:20` -> `email_main: 'info@indesign.com'`
22. `resources/js/tests/views/ContactView.test.js:35` -> `expect(wrapper.text()).toContain('info@indesign.com')`

## 2. Logic Chain
1. Executed global case-insensitive grep search for `InDesign`, `In Design`, and `إن ديزاين` across `resources/js` and `resources/views`.
2. Checked Blade view template (`app.blade.php`), Vue i18n translation files (`en.json`, `ar.json`), components, layouts, composables, views, and JS test specs under `resources/js/tests/`.
3. Verified each line number and context using `view_file`.
4. Mapped all occurrences to their respective targets:
   - English "InDesign" / "INDESIGN" -> "Eslam Abdulghani Designs" / "ESLAM ABDULGHANI DESIGNS"
   - Domain placeholders (`indesign-co.com`, `indesign.com`) -> `eslamabdulghanidesigns.com`
   - Arabic "إن ديزاين" -> "إسلام عبد الغني ديزاينز"

## 3. Caveats
- Read-only investigation: No frontend code changes were applied during this survey phase.
- `resources/js/tests/` files were surveyed and included here since they reside within `resources/js/`; Explorer 3 (Tests) may also reference them.

## 4. Conclusion
The frontend rebranding requirements are fully surveyed and mapped across 14 files (22 occurrences). A complete replacement matrix has been provided in `analysis.md`.

## 5. Verification Method
After implementation by Implementer:
1. Run grep search on `resources/js` and `resources/views` for `InDesign` and `إن ديزاين`:
   ```bash
   rg -i "InDesign|In Design|إن ديزاين" resources/js resources/views
   ```
   Expectation: 0 matches found.
2. Build frontend assets:
   ```bash
   npm run build
   ```
   Expectation: Clean build without errors.
3. Run JS unit tests:
   ```bash
   npx vitest run
   ```
   Expectation: All unit tests pass cleanly.
