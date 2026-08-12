# Project: Eslam Abdulghani Interiors — Light Mode Visual & Color Harmonization

## Architecture
- Public Frontend: Vue 3 + Tailwind CSS + Lucide icons + Vue Router + Pinia + Vue I18n (`resources/js/components/public/`, `resources/js/views/public/`)
- Admin Frontend: Vue 3 + Tailwind CSS + Lucide icons (`resources/js/components/admin/`, `resources/js/views/admin/`)
- Backend & Database Seeders: Laravel 11 + SQLite (`database/seeders/ServiceSeeder.php`, `database/seeders/ProjectSeeder.php`, `database/seeders/SectionSeeder.php`, `database/seeders/SettingSeeder.php`, `database/seeders/PageSeeder.php`)
- Asset Pipeline: Vite 6 + Tailwind CSS v3 (`resources/css/app.css`, `public/images/`)
- Color Palette: Off-white canvas `#F7F5F0`, Charcoal Black text/icons `#111111`, Warm Taupe Gold accents/hovers `#C5A880`, subtle body text `#444444`.

## Feature Inventory
| # | Feature | Description | Milestone | Source |
|---|---------|-------------|-----------|--------|
| 1 | R1.1 Hero Overlay & Text Harmonization | Update `HeroSlider.vue` overlays (`from-[#F7F5F0]/70 via-[#F7F5F0]/30 to-[#F7F5F0]`), title text (`text-[#111111]`), gradient (`from-[#111111] via-[#222222] to-[#C5A880]`), paragraph (`text-[#444444]`), and secondary button (`border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`) | M1 | ORIGINAL_REQUEST §Follow-up R1 |
| 2 | R1.2 Home Section Overlays & Styling | Update `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, and `CtaBanner.vue` overlays, text colors (`#111111`, `#444444`), and secondary buttons | M1 | ORIGINAL_REQUEST §Follow-up R1 |
| 3 | R2.1 Hero Slides High-Res Photography | Update `HeroSlider.vue` (3 default slides), `HomeView.vue` fallback, `ServiceSeeder.php`, `ProjectSeeder.php`, `SectionSeeder.php`, and `SettingSeeder.php` with ultra high-res luxury interior photography (Slide 1: Villa Living Room, Slide 2: Design Studio, Slide 3: Executive Suite) | M2 | ORIGINAL_REQUEST §Follow-up R2 |
| 4 | R2.2 Database Seeders & Image Asset Refresh | Clean up duplicate images/icons in `ServiceSeeder.php`, replace Flaticon raster PNG icons with SVG/Lucide icons, fix `SettingSeeder.php` favicon pointing to JPG | M2 | ORIGINAL_REQUEST §Follow-up R2 & Initial R2 |
| 5 | R3.1 Home Component Icons & Lines | Standardize inline SVGs, Lucide icons, scroll indicators, dots, and architectural gold `#C5A880` lines across `HeroSlider.vue`, `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, and `CtaBanner.vue` | M3 | ORIGINAL_REQUEST §Follow-up R3 |
| 6 | R3.2 Site-Wide Public & Admin Component Icons | Standardize icons, wrappers, hover states across all remaining public and admin components (`ServiceCard.vue`, `ProjectCard.vue`, `AppHeader.vue`, `AppFooter.vue`, `FloatingSocial.vue`, `ContactForm.vue`, `AboutView.vue`, `ContactView.vue`, `ServicesView.vue`, `ProjectsView.vue`, `ProjectDetailView.vue`, `AdminSidebar.vue`, `AdminProjects.vue`, `AdminMedia.vue`, `ProjectFormModal.vue`) to Charcoal `#111111` or Gold `#C5A880` | M3 | ORIGINAL_REQUEST §Initial R1 |
| 7 | R4.1 Frontend Build Compilation | Execute `npm run build` with zero errors to verify production asset bundling | M4 | ORIGINAL_REQUEST §Follow-up R4 |
| 8 | R4.2 Test Suite Execution | Execute `php artisan test` and `npm run test` with 100% clean pass | M4 | ORIGINAL_REQUEST §Follow-up R4 |

## Milestones
| # | Name | Scope | Dependencies | Status |
|---|------|-------|-------------|--------|
| 1 | M1: Home Page Hero & Overlay Harmonization | `HeroSlider.vue`, `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, `CtaBanner.vue` overlay gradients, typography colors, secondary buttons | none | DONE |
| 2 | M2: High-Resolution Luxury Asset & Seeder Refresh | `HeroSlider.vue` slides, `HomeView.vue`, `ServiceSeeder.php`, `ProjectSeeder.php`, `SectionSeeder.php`, `SettingSeeder.php` image URLs & icons | M1 | DONE |
| 3 | M3: Icon & Decorative Element Harmonization | Inline SVGs, Lucide icons, hover states, scroll indicators, and architectural decorative lines across public and admin Vue components | M1, M2 | DONE |
| 4 | M4: Build Verification & Test Integrity | Execute `npm run build` and `php artisan test` (plus `npm run test`) and verify zero errors/failures | M1, M2, M3 | DONE |

## Interface Contracts
### Public Components ↔ Theme Colors
- Canvas background: `#F7F5F0`
- Primary text / Charcoal: `#111111`
- Accent / Gold: `#C5A880`
- Body text / Warm gray: `#444444`
- Light warm overlay gradient: `from-[#F7F5F0]/70 via-[#F7F5F0]/30 to-[#F7F5F0]`
- Secondary button class: `border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`
- Icon default color: `#111111` or `#C5A880`
- Icon hover color: transition smoothly to `#C5A880` (gold) or `#FFFFFF` (on gold background)

## Code Layout
- Public Components: `resources/js/components/public/` (`HeroSlider.vue`, `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, `CtaBanner.vue`, `ServiceCard.vue`, `ProjectCard.vue`, `AppHeader.vue`, `AppFooter.vue`, `FloatingSocial.vue`, `ContactForm.vue`)
- Public Views: `resources/js/views/public/` (`HomeView.vue`, `AboutView.vue`, `ContactView.vue`, `ProjectsView.vue`, `ProjectDetailView.vue`, `ServicesView.vue`, `ServiceDetailView.vue`)
- Admin Components & Views: `resources/js/components/admin/`, `resources/js/views/admin/` (`AdminSidebar.vue`, `AdminProjects.vue`, `AdminMedia.vue`, `ProjectFormModal.vue`)
- Seeders: `database/seeders/` (`ServiceSeeder.php`, `ProjectSeeder.php`, `SectionSeeder.php`, `SettingSeeder.php`, `PageSeeder.php`)
- Static Images: `public/images/`
