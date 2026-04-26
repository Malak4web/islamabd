# InDesign-co.com — Project Overview & Architecture Workflow
> File: 00_project_overview.md | Characters: ~11,000

---

## Confirmed Stack & Configuration

| Setting            | Decision                                      |
|--------------------|-----------------------------------------------|
| Backend Framework  | Laravel 11 (latest stable)                    |
| Frontend Framework | Vue.js 3 (Composition API, inside Laravel)    |
| Build Tool         | Vite with @vitejs/plugin-vue                  |
| State Management   | Pinia                                         |
| Frontend Routing   | Vue Router 4 with Navigation Guards           |
| CSS Framework      | Tailwind CSS v3                               |
| Database (Dev)     | SQLite (file: database/database.sqlite)       |
| Database (Prod)    | MySQL (XAMPP)                                 |
| Authentication     | Laravel Sanctum — SPA cookie-based            |
| Media Storage      | Local disk via Spatie Laravel MediaLibrary    |
| Multilingual       | EN (default) + AR (RTL) via Vue i18n v9       |
| Testing Backend    | PHPUnit + Laravel HTTP Feature Tests          |
| Testing Frontend   | Vitest + Vue Test Utils                       |
| Code Editor (CMS)  | Monaco Editor (admin code injection UI)       |
| Project Path       | c:\xampp\htdocs\indesign                      |

---

## Overall Sprint Workflow

```
Phase 1  ──▶  Phase 2  ──▶  Phase 3  ──▶  Phase 4  ──▶  Phase 5
Scaffold       DB + Models    Public API     Admin API      Vue Public
& Config       (TDD)          (TDD)          (TDD)          Frontend

Phase 5  ──▶  Phase 6  ──▶  Phase 7  ──▶  Phase 8  ──▶  Phase 9
Vue Public     Vue Admin      Code           Multilingual   Polish &
Frontend       Dashboard      Injection      EN / AR        Final QA
```

### Phase Details

**Phase 1 — Scaffolding & Configuration**
- Create Laravel 11 project inside `c:\xampp\htdocs\indesign`
- Install all Composer packages: sanctum, spatie/medialibrary, spatie/permission, telescope
- Install NPM packages: vue@3, vue-router@4, pinia, vue-i18n, @vitejs/plugin-vue, tailwindcss, vitest
- Configure `vite.config.js` with Laravel + Vue plugin
- Configure `tailwind.config.js` with content paths for Blade + Vue files
- Create `.env` with SQLite driver for dev
- Create `.env.testing` with in-memory SQLite for PHPUnit
- Create single `resources/views/app.blade.php` shell that loads the Vue bundle
- Add catch-all web route: `Route::get('/{any}', fn() => view('app'))->where('any','.*')`
- Run `php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"`
- Run `php artisan storage:link` to expose public storage

**Phase 2 — Database Migrations & Models (TDD)**
- Write unit test for each model FIRST (red), then write migration + model (green), then refactor
- Tables: admins, pages, sections, services, projects, contacts, code_injections, settings, media
- Each model tested for: fillable fields, casts, relationships, factory states

**Phase 3 — Public API Controllers (TDD)**
- All routes under `/api/v1/` — no auth required
- Write feature test first, then controller, then pass test
- Endpoints: settings, pages/{slug}, services, projects, contacts (POST), code-injections

**Phase 4 — Admin API Controllers (TDD)**
- All routes under `/api/admin/` — protected by `auth:sanctum` middleware
- Tests include: unauthenticated 401, authenticated CRUD, validation errors, file uploads
- Controllers: Auth, Dashboard, Settings, Pages, Sections, Services, Projects, Contacts, CodeInjections, Media

**Phase 5 — Vue Public Frontend**
- HomeView, AboutView, ServicesView, ServiceDetailView, ProjectsView, ProjectDetailView, ContactView
- Global components: AppHeader, AppFooter, HeroSlider, CodeInjector, LanguageSwitcher

**Phase 6 — Vue Admin Dashboard**
- AdminLogin, AdminDashboard, AdminSettings, AdminPages, AdminSections
- AdminServices, AdminProjects, AdminContacts, AdminCodeInjection, AdminMedia

**Phase 7 — Code Injection System**
- Monaco editor in admin for raw HTML/JS code snippets
- Stored in `code_injections` table with `location` (head/body_start/body_end)
- `CodeInjector.vue` fetches and injects on every route change

**Phase 8 — Multilingual EN/AR**
- Vue i18n v9 with locale detection from localStorage
- All DB content has `_en` and `_ar` columns
- RTL toggle via `document.documentElement.setAttribute('dir','rtl')`
- API responds in correct language via `Accept-Language` header

**Phase 9 — Polish & Final QA**
- Loading skeletons on all data fetches
- Smooth page transitions (Vue `<Transition>`)
- All PHPUnit tests green
- All Vitest tests green

---

## Folder Structure

```
c:\xampp\htdocs\indesign\
│
├── .agent\                          ← Agent workflow files (this folder)
│   ├── 00_project_overview.md
│   ├── 01_tdd_workflow.md
│   ├── 02_auth_content_flow.md
│   ├── 03_api_admin_flow.md
│   └── 04_features_flow.md
│
├── app\
│   ├── Http\
│   │   ├── Controllers\
│   │   │   ├── Api\                 ← Public API controllers
│   │   │   └── Admin\              ← Admin API controllers
│   │   ├── Middleware\
│   │   │   └── SetLocale.php       ← Reads Accept-Language header
│   │   └── Requests\               ← FormRequest validators
│   ├── Models\                     ← All Eloquent models
│   └── Services\                   ← Business logic classes
│
├── database\
│   ├── migrations\                 ← All table migrations
│   ├── factories\                  ← Model factories for testing
│   ├── seeders\                    ← Demo data seeders
│   └── database.sqlite             ← SQLite dev database
│
├── resources\
│   ├── js\                         ← Vue.js SPA root
│   │   ├── app.js                  ← Vue bootstrap + plugin registration
│   │   ├── components\
│   │   │   ├── public\             ← Shared public site components
│   │   │   └── admin\             ← Shared admin UI components
│   │   ├── views\
│   │   │   ├── public\            ← Public page components
│   │   │   └── admin\            ← Admin page components
│   │   ├── router\
│   │   │   └── index.js           ← Vue Router with auth guard
│   │   ├── stores\                ← Pinia stores (one per domain)
│   │   ├── composables\           ← Reusable Vue composables
│   │   ├── i18n\
│   │   │   ├── index.js           ← Vue i18n setup
│   │   │   ├── en.json            ← English UI strings
│   │   │   └── ar.json            ← Arabic UI strings
│   │   └── api\
│   │       └── axios.js           ← Axios instance + interceptors
│   └── views\
│       └── app.blade.php          ← Single Blade shell for Vue SPA
│
├── routes\
│   ├── api.php                    ← All API routes (public + admin)
│   └── web.php                    ← Catch-all for Vue SPA
│
├── storage\app\public\            ← Uploaded files (symlinked)
│
├── tests\
│   ├── Unit\
│   │   └── Models\               ← Model unit tests (one per model)
│   └── Feature\
│       ├── Api\                  ← Public API feature tests
│       └── Admin\               ← Admin API feature tests
│
├── .env                          ← Dev environment (SQLite)
├── .env.testing                  ← Test environment (SQLite in-memory)
├── vite.config.js                ← Vite + Laravel + Vue configuration
├── tailwind.config.js            ← Tailwind content paths
└── package.json                  ← NPM dependencies
```

---

## Database Schema Summary

| Table            | Purpose                                          |
|------------------|--------------------------------------------------|
| admins           | Admin users with email/password for dashboard   |
| pages            | Page slugs + per-page SEO meta data             |
| sections         | Page content blocks (JSON flexible content)     |
| services         | Interior design services (EN/AR)                |
| projects         | Portfolio projects with gallery (EN/AR)         |
| contacts         | Contact form submissions inbox                  |
| code_injections  | GTM / Meta Pixel / custom tracking scripts      |
| settings         | Global site settings (logo, phone, email, etc.) |
| media            | Spatie MediaLibrary file records                |

---

## Route Architecture Summary

```
PUBLIC ROUTES (no auth)
  GET  /api/v1/settings                → global site settings
  GET  /api/v1/pages/{slug}           → page + sections + SEO
  GET  /api/v1/services               → services list
  GET  /api/v1/services/{slug}        → single service detail
  GET  /api/v1/projects               → projects list (filterable)
  GET  /api/v1/projects/{slug}        → single project detail
  POST /api/v1/contacts               → submit contact form
  GET  /api/v1/code-injections        → active code snippets for page

ADMIN ROUTES (auth:sanctum required)
  POST   /api/admin/login             → authenticate admin
  POST   /api/admin/logout            → revoke session
  GET    /api/admin/dashboard         → stats overview
  CRUD   /api/admin/settings          → manage global settings
  CRUD   /api/admin/pages             → manage page SEO
  CRUD   /api/admin/sections          → manage section content
  CRUD   /api/admin/services          → manage services
  CRUD   /api/admin/projects          → manage portfolio
  CRUD   /api/admin/contacts          → manage inbox
  CRUD   /api/admin/code-injections   → manage tracking scripts
  POST   /api/admin/media             → upload media file
  DELETE /api/admin/media/{id}        → delete media file

WEB CATCH-ALL
  GET  /{any}  → app.blade.php (Vue SPA takes over)
```

---

## Execution Checklist

- [ ] Phase 1: Laravel 11 project created, Vite + Vue running, Tailwind working
- [ ] Phase 2: All migrations written, all models tested (Unit tests green)
- [ ] Phase 3: Public API endpoints tested (Feature tests green)
- [ ] Phase 4: Admin API endpoints tested (Feature tests green, auth guards working)
- [ ] Phase 5: All public Vue pages rendering with live API data
- [ ] Phase 6: All admin dashboard pages functional (CRUD working)
- [ ] Phase 7: Code injection system working end-to-end
- [ ] Phase 8: Language toggle working, RTL layout correct in Arabic
- [ ] Phase 9: All tests green, animations polished, ready for demo
