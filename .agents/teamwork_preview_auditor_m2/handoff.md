# Milestone 2 Forensic Audit Report

**Work Product**: Backend Seeders, Database Records Update & API Fallbacks (`app/`, `database/`, `database/database.sqlite`)  
**Profile**: General Project / Development Mode  
**Verdict: INTEGRITY_VIOLATION**

---

## 1. Observation

Direct empirical observations from source code analysis, database queries, and API response testing:

1. **Un-updated SQLite Database Records (`database/database.sqlite`)**:
   - Querying the `settings` table in `database/database.sqlite` revealed multiple rows containing old brand values:
     - `id: 36 | key: "site_name" | value: "إن ديزاين"`
     - `id: 37 | key: "footer_text" | value: "All Rights reserved to Indesign"`
     - `id: 40 | key: "email_main" | value: "info@indesign-co.com"`
     - `id: 16 | key: "google_maps_eg" | value: "https://www.google.com/maps/place/INdesign+general+contracting%26real+estate/@30.0487358,30.9769539,15z"`
     - `id: 17 | key: "facebook_url" | value: "https://www.facebook.com/Indesign_co-107586265299910/"`
     - `id: 18 | key: "instagram_url" | value: "https://www.instagram.com/indesign_co/"`
     - `id: 44 | key: "facebook" | value: "https://www.facebook.com/Indesign_co-107586265299910/"`
     - `id: 45 | key: "instagram" | value: "https://www.instagram.com/indesign_co/"`

2. **API Endpoint Behavior (`SettingController.php`)**:
   - In `app/Http/Controllers/Api/SettingController.php`:
     ```php
     $s['site_name'] = $s['site_name'] ?? ($locale === 'ar' ? ($s['site_name_ar'] ?? 'إسلام عبد الغني ديزاينز') : ($s['site_name_en'] ?? 'Eslam Abdulghani Designs'));
     ```
   - Executing `SettingController::index()` via PHP returns:
     - `site_name` => `"إن ديزاين"` (in both English and Arabic locales).
     - `footer_text` => `"All Rights reserved to Indesign"`.
     - `email_main` => `"info@indesign-co.com"`.
   - Because `$s['site_name']` exists in the database as `"إن ديزاين"`, the null-coalescing operator (`??`) evaluates to `"إن ديزاين"`, causing the public API to serve the un-rebranded name.

3. **Incomplete Seeder Coverage (`SettingSeeder.php`)**:
   - `database/seeders/SettingSeeder.php` seeds `site_name_en` and `site_name_ar`, but fails to seed or update the legacy `site_name`, `footer_text`, or `email_main` keys. Running `php artisan db:seed` completes without error, but leaves these database records containing `"إن ديزاين"` and `"Indesign"`.

4. **Hardcoded Fallbacks in `app/Providers/AppServiceProvider.php`**:
   - `app/Providers/AppServiceProvider.php` line 30 contains:
     ```php
     $favicon = 'https://indesign-co.com/wp-content/uploads/2023/07/cropped-Fav-32x32.png';
     ```

---

## 2. Logic Chain

1. **Requirement Check**:
   - `ORIGINAL_REQUEST.md` (R2) mandates: *"Update database seeders (`SettingSeeder.php`, `SectionSeeder.php`, `AdminSeeder.php`, `PageSeeder.php`) and execute a database update/seeder script to update existing database records in SQLite (`settings`, `sections`, `pages`) so that all stored branding values reflect 'Eslam Abdulghani Designs' / 'إسلام عبد الغني ديزاينز'. Update API controller fallbacks in `SettingController.php`."*
   - Acceptance Criteria state: *"No remaining occurrences of 'InDesign', 'In Design', or 'إن ديزاين' in public or admin UI texts"* and *"site_name, site_name_en, site_name_ar, meta_title, copyright notices, and header titles display 'Eslam Abdulghani Designs' or 'إسلام عبد الغني ديزاينز'"*.

2. **Deduction from Observations**:
   - `SettingSeeder.php` did not update key `site_name` in SQLite database table `settings`.
   - SQLite table `settings` still contains `site_name` = `"إن ديزاين"`.
   - `SettingController.php` checks `$s['site_name'] = $s['site_name'] ?? ...`. Because `$s['site_name']` is non-null (`"إن ديزاين"`), the API output for `site_name` remains `"إن ديزاين"`.
   - Therefore, the database update requirement was incompletely executed, and API fallbacks failed to mask/update stored legacy brand strings.

3. **Conclusion**:
   - The work product for Milestone 2 fails the acceptance criteria due to remaining un-rebranded database records and defective API fallback handling.

---

## 3. Caveats

- Media image URLs (e.g. `wp-content/uploads/...` links pointing to `indesign-co.com` domain for legacy image assets) in `SectionSeeder.php` / `ProjectSeeder.php` / `ServiceSeeder.php` were not flagged as primary branding text violations, as they represent asset paths. However, stored text keys (`site_name`, `footer_text`, `email_main`) and `AppServiceProvider.php` fallbacks directly violate branding criteria.
- No other caveats.

---

## 4. Conclusion

**Verdict: INTEGRITY_VIOLATION**

Milestone 2 cannot be certified as clean. The database update was incomplete, leaving legacy brand strings (`"إن ديزاين"`, `"Indesign"`, `"info@indesign-co.com"`) in SQLite table `settings`, which are subsequently emitted by `/api/settings`.

---

## 5. Verification Method

To verify these findings independently, execute the following commands in `c:\xampp\htdocs\islamabd`:

1. **Check SQLite DB Settings Table**:
   ```bash
   php -r "$pdo = new PDO('sqlite:database/database.sqlite'); $stmt = $pdo->query('SELECT key, value FROM settings WHERE key IN (\'site_name\', \'footer_text\', \'email_main\')'); print_r($stmt->fetchAll(PDO::FETCH_ASSOC));"
   ```
   *Expected result showing violation*: `site_name` is `"إن ديزاين"`, `footer_text` is `"All Rights reserved to Indesign"`, `email_main` is `"info@indesign-co.com"`.

2. **Check API Controller Output**:
   ```bash
   php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); $c = new App\Http\Controllers\Api\SettingController(); print_r($c->index()->getData(true)['data']['site_name']);"
   ```
   *Expected result showing violation*: Outputs `"إن ديزاين"`.
