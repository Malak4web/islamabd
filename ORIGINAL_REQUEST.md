# Original User Request

## Initial Request — 2026-08-08T09:29:03Z

<USER_REQUEST>
# Teamwork Project Prompt

Update and harmonize all icons (SVGs, Lucide icons, icon styling) and images (hero banners, section images, service thumbnails, project fallbacks, default placeholders) across the entire website so they perfectly match the new Light Mode color palette (`#F7F5F0` off-white canvas, `#111111` charcoal black, and `#C5A880` warm taupe gold).

Working directory: c:\xampp\htdocs\islamabd
Integrity mode: development

## Requirements

### R1. SVG & Icon Color Harmonization
Review and update all SVG icons, Lucide icons, and icon wrappers across public frontend components (`resources/js/components/public/**/*.vue`, `resources/js/views/public/**/*.vue`) and admin components (`resources/js/components/admin/**/*.vue`, `resources/js/views/admin/**/*.vue`):
- Replace dark-mode icon colors (`fill-[#141414]`, `stroke-[#333]`, `text-[#888]`, `#ffffff` on dark cards) with Light Mode harmonious colors: charcoal (`#111111`), warm taupe gold (`#C5A880`), or subtle warm gray (`#666666`).
- Ensure hover states for icons transition smoothly to warm taupe gold (`#C5A880`).

### R2. High-Quality Light Theme Image Asset Refresh
Inspect and refresh all static and default images across `public/images/` and database seeders (`ServiceSeeder.php`, `ProjectSeeder.php`, `SectionSeeder.php`, `PageSeeder.php`):
- Ensure hero banners, about section images, service cards, and project preview images use bright, high-resolution luxury interior design visuals with warm, elegant lighting.
- Ensure transparent background PNG icons or badges render cleanly on light backgrounds without dark borders or artifacts.

### R3. Build & Test Verification
Execute `npm run build` to verify frontend compilation with zero errors. Re-run `php artisan test` to confirm test suite integrity.

## Acceptance Criteria

### Icon & Visual Consistency
- [ ] All icons across public pages and admin dashboard display cleanly in Charcoal (`#111111`) or Gold (`#C5A880`) matching the Light theme.
- [ ] Hero banners and section images feature bright, premium interior design aesthetics matching "Eslam Abdulghani Interiors".

### Build & Integrity Verification
- [ ] `npm run build` completes with zero errors.
- [ ] `php artisan test` passes clean.

## Follow-up — 2026-08-08T09:42:26Z

<USER_REQUEST>
# Teamwork Project Prompt

Achieve 100% visual and color harmony across the Home Page and entire site by replacing all hero slider images, background overlays, section icons, typography gradients, and fallback assets with ultra high-resolution, light-themed luxury interior design photography and harmonized Light Mode styling (`#F7F5F0` off-white canvas, `#111111` charcoal text, `#C5A880` warm taupe gold).

Working directory: c:\xampp\htdocs\islamabd
Integrity mode: development

## Requirements

### R1. Home Page Hero & Overlay Light Mode Harmonization
Refine `HeroSlider.vue`, `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, and `CtaBanner.vue`:
- Replace dark overlay gradients (`from-black/50 via-black/30`) with light warm gradients (`from-[#F7F5F0]/70 via-[#F7F5F0]/30 to-[#F7F5F0]`).
- Update hero text colors from `text-white` to `text-[#111111]` (Charcoal Black), text-gradient from `from-[#111111] via-[#222222] to-[#C5A880]`, and paragraph text to `text-[#444444]`.
- Update secondary button styling to `border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`.

### R2. High-Resolution Luxury Interior Asset Refresh
Update default hero slides in `HeroSlider.vue`, `HomeView.vue`, `ProjectSeeder.php`, and `ServiceSeeder.php` with high-resolution, ultra-luxurious bright interior design photography (warm cream walls, natural sunlight, gold accents, marble surfaces):
- Hero slide 1: Modern luxury villa living room in cream and warm taupe tones.
- Hero slide 2: Architectural interior design studio and bespoke furniture setup.
- Hero slide 3: High-end executive suite with floor-to-ceiling panoramic windows and warm lighting.

### R3. Icon & Decorative Element Harmonization
Harmonize all inline SVGs, Lucide icons, hover states, scroll indicators, and architectural decorative lines across `HeroSlider.vue`, `AboutSnippet.vue`, `ServicesPreview.vue`, `ProjectsPreview.vue`, and `CtaBanner.vue`:
- Standardize all icon strokes/fills to charcoal `#111111` or gold `#C5A880`.
- Update hover states so icons transition smoothly to gold `#C5A880`.

### R4. Build Verification & Test Integrity
Execute `npm run build` to verify frontend compilation without errors. Re-run `php artisan test` to confirm test suite integrity.

## Acceptance Criteria

### Visual Harmony Criteria
- [ ] Home page hero section displays crisp charcoal text over beautifully lit light interior design slides with warm gradients.
- [ ] Every section on the Home Page (Hero, About, Services, Projects, CTA) features consistent `#111111` charcoal typography, `#C5A880` gold accents, and bright luxury imagery.
- [ ] Icons and decorative lines across all components strictly adhere to the brand Light Mode palette.

### Build & Integrity Verification
- [ ] `npm run build` completes with zero errors.
- [ ] `php artisan test` passes clean.
</USER_REQUEST>

