## 2026-08-08T09:11:08Z
You are worker_m2_1. Working directory: c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2_1\
Read the original request at c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md.

Task: Implement Milestone 2 - Logo Integration & SettingSeeder Configuration.

Reference Explorer Analysis Report:
- Logo & Seeder Survey: c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_m0_3\analysis.md

Detailed Requirements:
1. Update database/seeders/SettingSeeder.php:
   - Update setting entry for 'logo' to set value to 'settings/logo.jpg'.
   - Ensure 'logo_light' and 'logo_dark' settings entries exist in SettingSeeder.php with value 'settings/logo.jpg'.
   - Run `php artisan db:seed --class=SettingSeeder` using run_command to seed the database with logo settings.
2. Integrate Logo Image in Vue Components:
   - AppHeader.vue (resources/js/components/public/AppHeader.vue): Verify and ensure `settingStore.settings.logo` renders cleanly as an img tag in header logo container.
   - AppFooter.vue (resources/js/components/public/AppFooter.vue): Add `settingStore.settings.logo` image rendering in footer branding section. Import/use `useSettingStore` if needed.
   - Mobile Nav drawer (AppHeader.vue mobile menu overlay): Render logo image in mobile drawer header.
   - AdminSidebar.vue (resources/js/components/admin/AdminSidebar.vue): Render logo image (`settingStore.settings.logo` or `/storage/settings/logo.jpg`) in admin sidebar branding section. Import/use `useSettingStore` if needed.
   - AdminLogin.vue (resources/js/views/admin/AdminLogin.vue): Render logo image (`settingStore.settings.logo` or `/storage/settings/logo.jpg`) on admin login card header. Import/use `useSettingStore` if needed.
3. Verification:
   - Run `npm run build` using run_command to verify frontend compilation succeeds without errors.
   - Run `php artisan test` using run_command to confirm test suite integrity.

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

4. Write handoff report to c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m2_1\handoff.md and update progress.md. Send completion message to parent when done.
