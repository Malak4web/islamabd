## 2026-08-08T11:32:36Z

<USER_REQUEST>
You are Worker M2-3 (Implementer for Milestone 2 Iteration 3 Case-Insensitivity Remediation).
Working directory: c:\xampp\htdocs\islamabd\.agents\worker_m2_3
Project directory: c:\xampp\htdocs\islamabd

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

Your objective:
Fix the case-sensitivity gaps reported by Challenger 2 in `c:\xampp\htdocs\islamabd\.agents\challenger_m2_2_2\handoff.md`:

1. Read `c:\xampp\htdocs\islamabd\.agents\challenger_m2_2_2\handoff.md`.
2. In `app/Http/Controllers/Api/SettingController.php`:
   - Make fallback check logic case-insensitive so that any variation (`Indesign`, `InDesign`, `indesign`, `INDESIGN`, `in design`, `In Design`, `إن ديزاين`, `ان ديزين`, `ان ديزاين`, `إن ديزين`) is properly detected and falls back to rebranded values (`Eslam Abdulghani Designs` / `إسلام عبد الغني ديزاينز`).
   - Expand the final sanitization array in `SettingController.php` to handle all casing and spacing variations:
     `['INdesign', 'InDesign', 'Indesign', 'indesign', 'INDESIGN', 'IN DESIGN', 'In Design', 'in design', 'indesign-co.com', 'Indesign_co', 'indesign_co', 'إن ديزاين', 'ان ديزين', 'ان ديزاين', 'إن ديزين']`
     mapped to sanitized values:
     `['Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'eslamabdulghanidesigns.com', 'eslamabdulghanidesigns', 'eslamabdulghanidesigns', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز']`
     or use regex replacement (`preg_replace`).
3. In `database/seeders/SettingSeeder.php`:
   - Update the post-seeding database sweep to use the expanded replacement array or regex replacement so any injected custom setting with lowercase/uppercase legacy brand strings is sanitized during `php artisan db:seed`.
4. Re-run `php artisan db:seed` to execute all seeders.
5. Run the empirical verification command from Section 5 of `c:\xampp\htdocs\islamabd\.agents\challenger_m2_2_2\handoff.md` to confirm dirty injections (`indesign`, `INDESIGN`, `ان ديزاين`) are sanitized properly.
6. Run `php artisan test` to confirm all 157+ tests pass.

Write your report to `c:\xampp\htdocs\islamabd\.agents\worker_m2_3\handoff.md` and send a message back with your verification output.
</USER_REQUEST>
