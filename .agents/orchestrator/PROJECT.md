# Project: Website Light Mode Redesign & Logo Integration

## Architecture
- **Framework**: Laravel 11 backend, Vue 3 SPA frontend (Inertia/Vite/Tailwind CSS).
- **Theme System**: Light Mode redesign using brand palette:
  - Backgrounds: `#F7F5F0` (canvas), `#FFFFFF` (cards/surfaces), `#F0ECE1` (secondary/alternate sections)
  - Text & Headers: `#111111` (dark charcoal titles), `#222222` (section headers), `#555555` (neutral body text)
  - Accents & Borders: `#C5A880` (taupe/gold accents/highlights), `#E0DACE` (subtle borders)
- **Settings & Logo System**: Logo asset stored at `storage/app/public/settings/logo.jpg` and `public/images/logo.jpg`. `SettingSeeder.php` populates `logo`, `logo_light`, `logo_dark` settings keys. API formats logo URL via `SettingController.php`.

## Feature Inventory
| # | Feature | Description | Milestone | Source |
|---|---------|-------------|-----------|--------|
| 1 | Public Frontend Light Mode | Convert all public views, components, layouts, app.blade.php to palette `#F7F5F0`, `#FFFFFF`, `#F0ECE1`, `#111111`, `#222222`, `#555555`, `#C5A880`, `#E0DACE` | Milestone 1 | ORIGINAL_REQUEST R1 |
| 2 | Admin Panel Light Mode | Convert AdminLayout, AdminSidebar, AdminDashboard, AdminSettings, AdminServices, AdminProjects, AdminSections, AdminPages, AdminContacts, AdminMedia, AdminCodeInjection, AdminLogin to Light Mode | Milestone 1 | ORIGINAL_REQUEST R1 |
| 3 | Logo Integration | Render logo image (`settings/logo.jpg`) in AppHeader, AppFooter, Mobile Nav, AdminSidebar, AdminLogin | Milestone 2 | ORIGINAL_REQUEST R2 |
| 4 | SettingSeeder Configuration | Update SettingSeeder.php & DB setting records so `logo`, `logo_light`, `logo_dark` default to `settings/logo.jpg` | Milestone 2 | ORIGINAL_REQUEST R2 |
| 5 | Build & Test Integrity | Execute `npm run build` with 0 errors and `php artisan test` passing 100% | Milestone 3 | ORIGINAL_REQUEST R3 |
| 6 | Forensic Audit | Pass independent integrity audit verifying no hardcoded outputs or facade implementations | Milestone 3 | ORIGINAL_REQUEST Integrity |

## Milestones
| # | Name | Scope | Dependencies | Status |
|---|------|-------|-------------|--------|
| M1 | Light Mode Redesign | Public frontend & admin panel views/components/layouts/app.blade.php | None | DONE |
| M2 | Logo & Seeder Integration | Logo image display in headers/footers/sidebars & SettingSeeder.php update | M1 | DONE |
| M3 | Build & Test & Audit | `npm run build`, `php artisan test`, Reviewer check & Forensic Auditor check | M1, M2 | DONE |

## Interface Contracts
### Settings API ↔ Frontend Vue Pinia Store
- `settingStore.settings.logo`: absolute URL pointing to `http://<domain>/storage/settings/logo.jpg`
- `settingStore.settings.logo_light`: absolute URL pointing to `http://<domain>/storage/settings/logo.jpg`
- `settingStore.settings.logo_dark`: absolute URL pointing to `http://<domain>/storage/settings/logo.jpg`

## Code Layout
- **Public Views**: `resources/js/views/public/*.vue`
- **Public Components**: `resources/js/components/public/*.vue`
- **Admin Views**: `resources/js/views/admin/*.vue`
- **Admin Components**: `resources/js/components/admin/*.vue`
- **Layouts**: `resources/js/layouts/*.vue`, `resources/views/app.blade.php`
- **Root App**: `resources/js/App.vue`
- **Database Seeders**: `database/seeders/SettingSeeder.php`
- **Logo Storage**: `storage/app/public/settings/logo.jpg`, `public/images/logo.jpg`
