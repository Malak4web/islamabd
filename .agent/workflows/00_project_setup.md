---
description: w1
---

# WORKFLOW 00 — Project Setup & Scaffolding
# Gate: ALL checks green before WF-01
# Chars: 10000-12000 | This is the foundation workflow
# Every subsequent workflow depends on this one being fully complete
════════════════════════════════════════════════════════

## Purpose
Bootstrap Laravel 11 + Vue.js 3 inside c:\xampp\htdocs\indesign.
All dependencies installed, environment configured, dev server running.

## Prerequisites
  PHP 8.2+      → php -v to verify
  Composer 2.x  → composer --version
  Node.js 20+   → node --version
  NPM 10+       → npm --version
  XAMPP running → Apache + MySQL (MySQL not needed yet, using SQLite)
  c:\xampp\htdocs\indesign → folder must exist and be EMPTY

════════════════════════════════════════════════════════
## STEP 1 — Create Laravel 11 Project
════════════════════════════════════════════════════════
  cd c:\xampp\htdocs\indesign
  composer create-project laravel/laravel:^11 .
  Verify: php artisan --version → "Laravel Framework 11.x.x"

════════════════════════════════════════════════════════
## STEP 2 — Composer Packages
════════════════════════════════════════════════════════
  PRODUCTION packages:
    composer require laravel/sanctum              ← SPA authentication
    composer require spatie/laravel-medialibrary  ← Image/file management
    composer require spatie/laravel-permission    ← Role/permission system

  DEV packages:
    composer require --dev laravel/telescope      ← Debug panel (dev only)

  Publish vendor configs:
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    php artisan telescope:install

  After publishing, run migrations to create Sanctum + Spatie tables:
    php artisan migrate
  Expected new tables: personal_access_tokens, media, permissions,
                       roles, model_has_roles, model_has_permissions,
                       role_has_permissions, telescope_entries, etc.

════════════════════════════════════════════════════════
## STEP 3 — NPM Packages
════════════════════════════════════════════════════════
  RUNTIME:
    vue@^3.4            Vue SPA framework
    @vitejs/plugin-vue  Vite integration
    vue-router@^4.3     SPA routing
    pinia@^2.1          State management
    vue-i18n@^9.13      EN/AR translations
    @vueuse/core        Vue composables
    axios@^1.7          HTTP client
    @monaco-editor/vue3 Code editor for CMS

  DEV:
    tailwindcss@^3.4    CSS framework
    @tailwindcss/forms  Form styling
    autoprefixer        Vendor prefixes
    postcss             CSS processing
    vitest@^1.6         Frontend testing
    @vue/test-utils     Component testing
    jsdom               DOM simulation

  npm install vue@^3.4 @vitejs/plugin-vue@^5.0 vue-router@^4.3 pinia@^2.1 vue-i18n@^9.13 @vueuse/core axios @monaco-editor/vue3
  npm install -D tailwindcss @tailwindcss/forms autoprefixer postcss vitest @vue/test-utils jsdom

════════════════════════════════════════════════════════
## STEP 4 — vite.config.js
════════════════════════════════════════════════════════
  import { defineConfig } from 'vite'
  import laravel from 'laravel-vite-plugin'
  import vue from '@vitejs/plugin-vue'
  import path from 'path'

  export default defineConfig({
    plugins: [
      laravel({ input:['resources/css/app.css','resources/js/app.js'], refresh:true }),
      vue({ template:{ transformAssetUrls:{ base:null, includeAbsolute:false } } }),
    ],
    resolve: { alias: { '@': path.resolve(__dirname,'resources/js') } },
    test: { environment:'jsdom', globals:true, setupFiles:'resources/js/tests/setup.js' },
  })

════════════════════════════════════════════════════════
## STEP 5 — Tailwind Config
════════════════════════════════════════════════════════
  npx tailwindcss init -p
  This creates tailwind.config.js and postcss.config.js

  tailwind.config.js full config:
    /** @type {import('tailwindcss').Config} */
    export default {
      content: [
        './resources/**/*.{vue,js,ts,blade.php}',
        './resources/js/components/**/*.vue',
        './resources/js/views/**/*.vue',
      ],
      plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
      ],
      theme: {
        extend: {
          fontFamily: { sans: ['Inter','Cairo','sans-serif'] },
          colors: {
            brand: { 50:'#fdf8f0', 100:'#faefd9', 200:'#f4dba4',
                     300:'#ecc56e', 400:'#dea33c', 500:'#c9933a',
                     600:'#a87030', 700:'#8a5e1e', 800:'#6b4614',
                     900:'#4d3210' }
          }
        }
      }
    }

════════════════════════════════════════════════════════
## STEP 6 — Environment Files
════════════════════════════════════════════════════════
  .env (development):
    APP_NAME="InDesign"
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost/indesign/public
    DB_CONNECTION=sqlite
    FILESYSTEM_DISK=public
    SANCTUM_STATEFUL_DOMAINS=localhost
    SESSION_DRIVER=cookie

  .env.testing (PHPUnit):
    APP_ENV=testing
    DB_CONNECTION=sqlite
    DB_DATABASE=:memory:
    MAIL_MAILER=array
    QUEUE_CONNECTION=sync
    FILESYSTEM_DISK=fake

  php artisan key:generate

════════════════════════════════════════════════════════
## STEP 7 — Blade Shell & Routes
════════════════════════════════════════════════════════
  resources/views/app.blade.php:
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>InDesign</title>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
      @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body><div id="app"></div></body>
    </html>

  routes/web.php:
    Route::get('/{any}', fn() => view('app'))->where('any','.*');

  routes/api.php skeleton:
    Route::prefix('v1')->group(fn() => null);
    Route::prefix('admin')->middleware(['auth:sanctum'])->group(fn() => null);

════════════════════════════════════════════════════════
## STEP 8 — Vue Bootstrap Entry
════════════════════════════════════════════════════════
  resources/js/app.js:
    import { createApp } from 'vue'
    import { createPinia } from 'pinia'
    import { createI18n } from 'vue-i18n'
    import router from './router/index.js'
    import App from './App.vue'
    import en from './i18n/en.json'
    import ar from './i18n/ar.json'
    import '../css/app.css'

    const i18n = createI18n({ locale:'en', fallbackLocale:'en', messages:{en,ar} })
    createApp(App).use(createPinia()).use(router).use(i18n).mount('#app')

  Create empty files:
    resources/js/App.vue              ← root with <RouterView />
    resources/js/router/index.js      ← empty router
    resources/js/i18n/en.json         ← {}
    resources/js/i18n/ar.json         ← {}
    resources/js/tests/setup.js       ← Vitest global setup

  package.json scripts:
    "dev":          "vite",
    "build":        "vite build",
    "test":         "vitest run",
    "test:watch":   "vitest",
    "test:coverage":"vitest run --coverage"

  php artisan storage:link
  ← Creates public/storage → storage/app/public symlink for file access

════════════════════════════════════════════════════════
## STEP 9 — Directory Structure (expected after scaffold)
════════════════════════════════════════════════════════
  indesign/
  ├── app/
  │   ├── Http/
  │   │   ├── Controllers/
  │   │   │   ├── Api/        ← Public API controllers
  │   │   │   └── Admin/      ← Admin API controllers
  │   │   ├── Middleware/
  │   │   │   └── SetLocale.php
  │   │   └── Requests/
  │   │       └── Admin/
  │   └── Models/             ← All Eloquent models
  ├── resources/
  │   ├── css/
  │   │   └── app.css         ← Tailwind directives + custom CSS
  │   ├── js/
  │   │   ├── api/
  │   │   │   └── axios.js    ← Shared axios instance
  │   │   ├── components/
  │   │   │   ├── public/     ← Public site components
  │   │   │   └── admin/      ← Admin panel components
  │   │   ├── composables/    ← Reusable Vue composables
  │   │   ├── i18n/
  │   │   │   ├── en.json     ← English UI strings
  │   │   │   └── ar.json     ← Arabic UI strings
  │   │   ├── router/
  │   │   │   └── index.js    ← Vue Router config
  │   │   ├── stores/         ← Pinia stores
  │   │   ├── tests/          ← Vitest test files
  │   │   └── views/
  │   │       ├── admin/      ← Admin dashboard pages
  │   │       └── public/     ← Public website pages
  │   └── views/
  │       └── app.blade.php   ← Single Blade shell
  ├── routes/
  │   ├── api.php             ← All API routes
  │   └── web.php             ← Single catch-all route
  └── tests/
      ├── Feature/
      │   ├── Admin/          ← Admin endpoint tests
      │   └── Api/            ← Public API tests
      └── Unit/
          └── Models/         ← Model unit tests

════════════════════════════════════════════════════════
## 🔴 TDD GATE 00 — ALL MUST GREEN BEFORE WF-01
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-01 UNTIL ALL 7 CHECKS ARE GREEN ⛔

  [ ] CHECK 01 — php artisan --version
        PASS: "Laravel Framework 11.x.x"

  [ ] CHECK 02 — php artisan test
        PASS: "Tests: 2 passed" (default Laravel tests)

  [ ] CHECK 03 — php artisan test --env=testing
        PASS: "Tests: 2 passed" using :memory: SQLite

  [ ] CHECK 04 — php artisan migrate
        PASS: Migrations run without errors

  [ ] CHECK 05 — npm run build
        PASS: public/build/ created, no errors

  [ ] CHECK 06 — npm run test
        PASS: "No test files found" OR "0 tests passed"

  [ ] CHECK 07 — Browser: http://127.0.0.1:8000
        PASS: Blank page loads (Vue mounted, no errors in console)

  GATE RESULT:
    ALL GREEN → ✅ PROCEED TO WORKFLOW 01
    ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW — Fix & Retry
════════════════════════════════════════════════════════

  CHECK 02/03 fails (php artisan test):
    php artisan config:clear && php artisan cache:clear
    php artisan key:generate
    php artisan migrate --fresh
    Re-run: php artisan test

  CHECK 05 fails (npm run build):
    rm -rf node_modules package-lock.json
    npm install
    Check vite.config.js syntax
    npm run build

  CHECK 07 fails (blank screen / 404):
    Verify routes/web.php catch-all route exists
    Verify @vite() directive in app.blade.php
    Run npm run dev, check Vite output for errors
    Clear browser cache (Ctrl+Shift+R)

  RULE: After every fix, re-run the FULL GATE from CHECK 01.
  Never skip a check even if you think it is fine.

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-01
════════════════════════════════════════════════════════
  [ ] All 7 gate checks GREEN
  [ ] .env and .env.testing created and correct
  [ ] npm run dev starts without errors
  [ ] git commit -m "chore: initial Laravel+Vue scaffold"
  [ ] NEXT → 01_database_models.md
