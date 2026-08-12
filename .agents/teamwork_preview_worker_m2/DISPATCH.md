## 2026-08-08T10:02:26Z
You are a Worker subagent implementing Milestone M2 for project c:\xampp\htdocs\islamabd.
Your working directory is c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2.

MANDATORY READS:
- Read `c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md`
- Read `c:\xampp\htdocs\islamabd\PROJECT.md`
- Read `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_survey_2\handoff.md`

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

EXCLUSIVE WRITE OWNERSHIP:
- `resources/js/components/public/HeroSlider.vue`
- `resources/js/views/public/HomeView.vue`
- `database/seeders/ServiceSeeder.php`
- `database/seeders/ProjectSeeder.php`
- `database/seeders/SectionSeeder.php`
- `database/seeders/SettingSeeder.php`

TASK (Milestone M2: High-Resolution Luxury Interior Asset Refresh):
1. In `HeroSlider.vue`:
   - Update `props.slides` default array to contain 3 ultra high-resolution luxury interior design slides:
     - Slide 1: Modern luxury villa living room in cream and warm taupe tones (`https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&q=80&w=2000`)
     - Slide 2: Architectural interior design studio and bespoke furniture setup (`https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&q=80&w=2000`)
     - Slide 3: High-end executive suite with floor-to-ceiling panoramic windows and warm lighting (`https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&q=80&w=2000`)
2. In `HomeView.vue`:
   - Ensure fallback hero slide array includes all 3 slides with the same high-resolution URLs.
3. In `database/seeders/ServiceSeeder.php`:
   - Replace duplicate image URLs so every service has a unique, high-resolution interior photography URL:
     - Administrative: `https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&q=80&w=1200`
     - Commercial Design: `https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200`
     - Residential Design: `https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&q=80&w=1200`
     - Exterior Design: `https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&q=80&w=1200`
     - Hospitality Design: `https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&q=80&w=1200`
     - Landscape Design: `https://images.unsplash.com/photo-1584467735871-8e85353a8413?auto=format&fit=crop&q=80&w=1200`
     - Retail Design: `https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?auto=format&fit=crop&q=80&w=1200`
     - Industrial Design: `https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?auto=format&fit=crop&q=80&w=1200`
   - Clean up external Flaticon PNG icon URLs or replace with valid SVG / Lucide icon names so icons render cleanly without dark borders or artifacts.
4. In `database/seeders/SettingSeeder.php`:
   - Fix `favicon` setting from `'/images/defaults/about_fallback.jpg'` to a proper icon asset or SVG icon URL (`'/images/favicon.png'` or transparent icon asset).
5. In `database/seeders/ProjectSeeder.php` and `database/seeders/SectionSeeder.php`:
   - Verify image URLs point to high quality luxury interior design visual assets.

VERIFICATION:
- Re-run database seeder `php artisan db:seed` (or test seeder classes).
- Run `npm run build` and verify 0 compilation errors.
- Run `php artisan test` and verify 100% tests passing.
- Run `npm run test` and verify 100% frontend tests passing.

Write report to `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2\handoff.md` and send message when complete.
