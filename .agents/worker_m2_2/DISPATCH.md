## 2026-08-08T08:22:44Z
<USER_REQUEST>
You are Worker M2-2 (Implementer for Milestone 2 Iteration 2).
Working directory: c:\xampp\htdocs\islamabd\.agents\worker_m2_2
Project directory: c:\xampp\htdocs\islamabd

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

Your objective:
Implement the remediation strategy detailed in `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_m2_2\handoff.md`:
1. Read `c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md` and `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_m2_2\handoff.md`.
2. Update `database/seeders/SettingSeeder.php` per the handoff code specification (includes seeding site_name, footer_text, email_main, social links, map URLs, and performing a post-seeding database sweep on the `settings` table to sanitize any remaining legacy strings).
3. Update `app/Http/Controllers/Api/SettingController.php` per the handoff code specification (locale-aware conveniences, fallbacks, and final sanitization pass).
4. Update `app/Providers/AppServiceProvider.php` (line 30 fallback favicon URL).
5. Update image/domain URLs in `database/seeders/ProjectSeeder.php`, `database/seeders/SectionSeeder.php`, and `database/seeders/ServiceSeeder.php` if any legacy brand URLs remain.
6. Run `php artisan db:seed` to execute all seeders and populate/update `database/database.sqlite`.
7. Verify database records and API controller output using the verification commands in Section 5 of `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_m2_2\handoff.md`.

Write your handoff report to `c:\xampp\htdocs\islamabd\.agents\worker_m2_2\handoff.md` and send a message back with your findings and build/test results.
</USER_REQUEST>
