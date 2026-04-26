---
description: w12
---

# WORKFLOW 12 — Final QA, Regression & Revision Protocol
# This is the FINAL workflow — No more features after this
# Prerequisite: ALL WF-00 through WF-11 gates must be fully green
════════════════════════════════════════════════════════

## Purpose
Full quality assurance across the entire project: regression suites,
performance checks, security review, cross-browser testing, and the
formal revision protocol for any bugs found post-completion.

════════════════════════════════════════════════════════
## PHASE A — FULL REGRESSION TEST SUITE
════════════════════════════════════════════════════════

## A1 — Backend Full Suite
  Command: php artisan test --env=testing --coverage
  Target: 80+ tests | 0 failed | 0 skipped | Coverage ≥ 90%

  Expected files (ALL must PASS):
    tests/Unit/Models/AdminTest.php          ✅
    tests/Unit/Models/SettingTest.php        ✅
    tests/Unit/Models/PageTest.php           ✅
    tests/Unit/Models/SectionTest.php        ✅
    tests/Unit/Models/ServiceTest.php        ✅
    tests/Unit/Models/ProjectTest.php        ✅
    tests/Unit/Models/ContactTest.php        ✅
    tests/Unit/Models/CodeInjectionTest.php  ✅
    tests/Feature/Admin/AuthTest.php         ✅
    tests/Feature/Admin/SettingAdminTest.php ✅
    tests/Feature/Admin/PageAdminTest.php    ✅
    tests/Feature/Admin/ServiceAdminTest.php ✅
    tests/Feature/Admin/ProjectAdminTest.php ✅
    tests/Feature/Admin/ContactAdminTest.php ✅
    tests/Feature/Admin/CodeInjectionAdminTest.php ✅
    tests/Feature/Api/SettingPublicTest.php  ✅
    tests/Feature/Api/PagePublicTest.php     ✅
    tests/Feature/Api/ServicePublicTest.php  ✅
    tests/Feature/Api/ProjectPublicTest.php  ✅
    tests/Feature/Api/ContactPublicTest.php  ✅
    tests/Feature/Api/CodeInjectionPublicTest.php ✅
    tests/Feature/Api/LocaleMiddlewareTest.php ✅

## A2 — Frontend Full Suite
  Command: npm run test -- --coverage
  Target: 80+ tests | 0 failed | Coverage ≥ 80%

  Expected files (ALL must PASS):
    tests/stores/authStore.test.js           ✅
    tests/stores/settingStore.test.js        ✅
    tests/stores/pageStore.test.js           ✅
    tests/stores/sectionStore.test.js        ✅
    tests/stores/serviceStore.test.js        ✅
    tests/stores/projectStore.test.js        ✅
    tests/stores/contactStore.test.js        ✅
    tests/stores/codeInjectionStore.test.js  ✅
    tests/stores/localeStore.test.js         ✅
    tests/views/HomeView.test.js             ✅
    tests/views/ProjectsView.test.js         ✅
    tests/views/ContactView.test.js          ✅
    tests/views/admin/AdminDashboard.test.js ✅
    tests/views/admin/AdminServices.test.js  ✅
    tests/views/admin/AdminProjects.test.js  ✅
    tests/views/admin/AdminContacts.test.js  ✅
    tests/components/AppHeader.test.js       ✅
    tests/components/AppFooter.test.js       ✅
    tests/components/ServiceCard.test.js     ✅
    tests/components/ProjectCard.test.js     ✅
    tests/components/ContactForm.test.js     ✅
    tests/components/CodeInjector.test.js    ✅
    tests/components/LanguageSwitcher.test.js✅
    tests/components/admin/ConfirmModal.test.js ✅
    tests/components/admin/ToastNotification.test.js ✅
    tests/composables/useSeo.test.js         ✅
    tests/integration/LocaleIntegration.test.js ✅

════════════════════════════════════════════════════════
## PHASE B — PERFORMANCE CHECKS
════════════════════════════════════════════════════════

## B1 — Build Size
  Command: npm run build
  Targets:
    Total JS bundle < 500KB gzipped
    CSS bundle < 50KB gzipped

  If too large:
    Dynamic imports for heavy pages:
      AdminCodeInjection: () => import('./AdminCodeInjection.vue')
      Monaco editor: loaded only on code injection page
    Use vite-plugin-compression for brotli/gzip

## B2 — API Response Speed
  Target: Each endpoint < 200ms locally

  Test endpoints:
    GET /api/v1/settings              < 50ms
    GET /api/v1/pages/home            < 100ms
    GET /api/v1/services              < 100ms
    GET /api/v1/projects              < 150ms (paginated)
    GET /api/v1/code-injections?page  < 50ms

  If slow → add DB indexes:
    services:         is_active, order
    projects:         is_active, is_featured, category, order
    contacts:         status, created_at
    code_injections:  is_active, location

## B3 — Image Optimization
  Spatie MediaLibrary conversions on Service and Project models:
    thumb:  300×200
    medium: 800×600
    large:  1920×1080
    og:     1200×630

  Verify: covers use medium | gallery uses medium | thumbs use thumb

════════════════════════════════════════════════════════
## PHASE C — SECURITY CHECKLIST
════════════════════════════════════════════════════════

  AUTHENTICATION:
  [ ] All admin routes: auth:sanctum middleware
  [ ] CSRF: Sanctum stateful mode active
  [ ] Login rate limited: throttle:5,1
  [ ] Contact form rate limited: throttle:5,1
  [ ] No public registration endpoint
  [ ] Admin password hashed with Hash::make

  INPUT VALIDATION:
  [ ] All store() use FormRequest validation
  [ ] All update() validate each field
  [ ] File uploads: mimes validated server-side
  [ ] Max string lengths enforced
  [ ] No raw SQL queries without bindings (Eloquent only)

  FILE UPLOADS:
  [ ] Only allowed mimes: jpg, png, webp, svg
  [ ] Max file size validated server-side
  [ ] Files stored in storage/app/public (not public/ directly)
  [ ] Generated filenames (not user-supplied)

  HEADERS:
  [ ] CORS: only configured origins allowed
  [ ] X-Content-Type-Options: nosniff
  [ ] X-Frame-Options: SAMEORIGIN

════════════════════════════════════════════════════════
## PHASE D — CROSS-BROWSER & DEVICE CHECKLIST
════════════════════════════════════════════════════════

  Browsers:
  [ ] Chrome (latest) — Desktop
  [ ] Firefox (latest) — Desktop
  [ ] Safari (latest) — macOS + iPhone
  [ ] Edge (latest) — Desktop
  [ ] Chrome Mobile — Android

  Screen widths:
  [ ] 320px  (small mobile)
  [ ] 375px  (iPhone SE)
  [ ] 768px  (tablet portrait)
  [ ] 1024px (tablet landscape)
  [ ] 1280px (desktop)
  [ ] 1920px (large desktop)

  For each combination verify:
  [ ] Hero fills viewport correctly
  [ ] Nav: desktop full, mobile hamburger
  [ ] Service/project cards: 1/2/3 col responsive
  [ ] Contact form: readable on all sizes
  [ ] Admin sidebar: collapses on tablet/mobile
  [ ] RTL layout works in all browsers

════════════════════════════════════════════════════════
## PHASE E — REVISION PROTOCOL (Bug Handling)
════════════════════════════════════════════════════════

## Severity Classification
  P1 CRITICAL: Site down, data loss, security breach → fix NOW
  P2 HIGH:     Feature broken, wrong data shown → fix this sprint
  P3 MEDIUM:   UI glitch, minor functional issue → fix before launch
  P4 LOW:      Cosmetic, minor UX → backlog

## Mandatory Steps for Every Bug (TDD applies to bugs)
  1. Write a FAILING test that reproduces the bug
       tests/Feature/Regression/BugNNNTest.php (backend)
       tests/regression/BugNNNTest.js          (frontend)
  2. Run → confirm RED ❌
  3. Fix the source code
  4. Run → confirm GREEN ✅
  5. Run full regression suite → NO new failures
  6. Commit: "fix: [description] (#NNN)"

  Revision Log (maintain this table):
  ─────────────────────────────────────────────────────
  ID   | Module       | Description         | Status
  ─────────────────────────────────────────────────────
  R001 |              |                     |
  R002 |              |                     |
  ─────────────────────────────────────────────────────

════════════════════════════════════════════════════════
## PHASE F — LAUNCH CHECKLIST
════════════════════════════════════════════════════════

  BACKEND:
  [ ] php artisan test --env=testing → ALL green
  [ ] php artisan optimize (config + route + view cache)
  [ ] php artisan migrate → all applied
  [ ] php artisan db:seed → content seeded
  [ ] php artisan storage:link → symlink created
  [ ] .env APP_DEBUG=false, APP_ENV=production
  [ ] Remove Telescope from production

  FRONTEND:
  [ ] npm run test → ALL green
  [ ] npm run build → clean production bundle
  [ ] No console.log() in production code

  CONTENT:
  [ ] All 5 pages: meta_title, meta_description, og_image
  [ ] All settings: logo, phones, emails, address, socials
  [ ] 8 services with bilingual content
  [ ] 6+ projects with covers and galleries
  [ ] Code injection: GTM snippet added
  [ ] Admin account seeded with secure password

  SERVER (XAMPP):
  [ ] DocumentRoot → indesign/public/
  [ ] mod_rewrite enabled | .htaccess configured
  [ ] php.ini upload_max_filesize ≥ 10M

════════════════════════════════════════════════════════
## 🔴 FINAL TDD GATE — PROJECT COMPLETE
════════════════════════════════════════════════════════

  ⛔ PROJECT NOT COMPLETE UNTIL THIS GATE IS FULLY GREEN ⛔

  COMBINED COMMAND (both must pass):
    php artisan test --env=testing && npm run test
    EXPECTED: 0 failures across all test files

  FINAL CHECKLIST:
  ─────────────────────────────────────────────────────
  [ ] Backend: 22 test files GREEN, coverage ≥ 90%
  [ ] Frontend: 27 test files GREEN, coverage ≥ 80%
  [ ] Build: npm run build → clean output
  [ ] Performance: all API endpoints < 200ms
  [ ] Security: all Phase C items checked
  [ ] Cross-browser: Chrome / Firefox / Safari / Edge
  [ ] Responsive: 320px → 1920px verified
  [ ] Content: no Lorem Ipsum in production
  [ ] SEO: unique meta on all 5 pages
  [ ] GTM: code injection visible in page source
  [ ] EN + AR: both fully functional
  [ ] Admin CRUD: all features work end-to-end
  [ ] Contacts: form → DB → admin inbox working
  [ ] No uncommitted changes | all commits clean
  ─────────────────────────────────────────────────────
  ALL CHECKED → ✅ 🎉 PROJECT COMPLETE — READY FOR LAUNCH
  ANY UNCHECKED → ❌ USE REVISION PROTOCOL (PHASE E)

════════════════════════════════════════════════════════
## WORKFLOW INDEX
════════════════════════════════════════════════════════
  WF-00  Project Setup              00_project_setup.md
  WF-01  Database Models            01_database_models.md
  WF-02  Authentication             02_authentication_module.md
  WF-03  Settings Module            03_settings_module.md
  WF-04  Pages & Sections           04_pages_sections_module.md
  WF-05  Services Module            05_services_module.md
  WF-06  Projects Module            06_projects_module.md
  WF-07  Contacts Module            07_contacts_module.md
  WF-08  Code Injection             08_code_injection_module.md
  WF-09  Multilingual EN/AR         09_multilingual_module.md
  WF-10  Public Frontend            10_public_frontend.md
  WF-11  Admin Dashboard            11_admin_dashboard.md
  WF-12  Final QA & Revision        12_final_qa_and_revision.md

  RULE: Every gate must be green before moving to the next.
  RULE: All bugs get a failing test first, then a fix.
  RULE: Full suite must stay green after every fix.
