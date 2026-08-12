# Project Context

## Project Details
- **Project Directory**: `c:\xampp\htdocs\islamabd`
- **Orchestrator Directory**: `c:\xampp\htdocs\islamabd\.agents\orchestrator`
- **Original Request**: `c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md`

## Target Replacement Mapping
- **English**:
  - `"InDesign"` -> `"Eslam Abdulghani Designs"`
  - `"In Design"` -> `"Eslam Abdulghani Designs"`
- **Arabic**:
  - `"إن ديزاين"` -> `"إسلام عبد الغني ديزاينز"`

## Key Areas to Update
1. Vue Components: `resources/js/views`, `resources/js/components`, `resources/js/layouts`, composables
2. Translation files: `resources/js/i18n/en.json`, `resources/js/i18n/ar.json`
3. Blade views: `resources/views/app.blade.php`
4. Seeders: `SettingSeeder.php`, `SectionSeeder.php`, `AdminSeeder.php`, `PageSeeder.php`
5. Database Update / Script: SQLite `settings`, `sections`, `pages`
6. Controllers & API Fallbacks: `SettingController.php`
7. Tests: PHPUnit and Vue test files
