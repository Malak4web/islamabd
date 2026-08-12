## 2026-08-08T13:13:17Z
You are a Worker subagent (iteration 2) for Milestone M2 in project c:\xampp\htdocs\islamabd.
Your working directory is c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2_gen2.

MANDATORY READS:
- Read `c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md`
- Read `c:\xampp\htdocs\islamabd\PROJECT.md`
- Read `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_reviewer_m2_2\handoff.md`

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

EXCLUSIVE WRITE OWNERSHIP:
- `database/seeders/SettingSeeder.php`
- `app/Http/Controllers/Api/SettingController.php`

TASK:
Fix legacy brand strings in `SettingSeeder.php` & database settings:
1. In `database/seeders/SettingSeeder.php`:
   - Add explicit re-branding for legacy setting keys in `$settings` array:
     - `'site_name' => 'Eslam Abdulghani Designs'`
     - `'site_name_en' => 'Eslam Abdulghani Designs'`
     - `'site_name_ar' => 'إسلام عبد الغني ديزاينز'`
     - `'footer_text' => 'All Rights reserved to Eslam Abdulghani Designs'`
     - `'email_main' => 'info@eslamabdulghanidesigns.com'`
     - Rebrand any remaining social/map links containing `indesign` to `eslamabdulghanidesigns`.
2. In `app/Http/Controllers/Api/SettingController.php`:
   - Ensure `site_name` fallback dynamically returns the locale-specific brand name (`site_name_ar` or `site_name_en`) when queried.
3. Re-run `php artisan db:seed` so database records in `database/database.sqlite` are cleanly updated.
4. Re-run `npm run build`, `php artisan test`, and `npm run test` to verify 100% test & build integrity.

Write report to `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2_gen2\handoff.md` and send message when complete.
