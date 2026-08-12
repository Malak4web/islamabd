# Comprehensive Backend & Database Survey Report

## Executive Summary
This survey provides a complete mapping of all occurrences of the legacy brand names ("InDesign", "In Design", "إن ديزاين", and "Indesign") across the backend codebase (`app/`, `database/`, `config/`) and live SQLite database (`database/database.sqlite`).

All database seeders (`SettingSeeder.php`, `SectionSeeder.php`, `AdminSeeder.php`, `PageSeeder.php`, `PagesSeeder.php`), API controllers (`SettingController.php`), console commands (`ImportFromWordpress.php`), service providers (`AppServiceProvider.php`), and database tables (`settings`, `pages`, `sections`, `admins`) have been cataloged with exact file locations, line numbers, table keys, and target replacements.

---

## 1. Backend Codebase Inventory & Rebranding Map

### 1.1 Controllers & Services (`app/`)

#### 1. `app/Http/Controllers/Api/SettingController.php`
* **Line 21**: Fallback values when site settings are absent:
  ```php
  // Current:
  $s['site_name'] = $s['site_name'] ?? ($locale === 'ar' ? ($s['site_name_ar'] ?? 'إن ديزاين') : ($s['site_name_en'] ?? 'InDesign'));
  
  // Proposed Target Replacement:
  $s['site_name'] = $s['site_name'] ?? ($locale === 'ar' ? ($s['site_name_ar'] ?? 'إسلام عبد الغني ديزاينز') : ($s['site_name_en'] ?? 'Eslam Abdulghani Designs'));
  ```

#### 2. `app/Console/Commands/ImportFromWordpress.php`
* **Line 39**: `'description_en' => "At INDESIGN, we focus on two core aspects..."`
  * Target: Replace `"INDESIGN"` with `"Eslam Abdulghani Designs"`
* **Line 40**: `'description_ar' => "التصميم الإداري في ان ديزين ... في ان ديزين ، نركز ... في ان ديزين تتميز ..."`
  * Target: Replace `"ان ديزين"` with `"إسلام عبد الغني ديزاينز"`
* **Line 91**: `'description_en' => "Commercial Design at INDESIGN ... At INDESIGN | Design & Built..."`
  * Target: Replace `"INDESIGN"` with `"Eslam Abdulghani Designs"`
* **Line 92**: `'description_ar' => "في ان ديزين ، نحن ملتزمون..."`
  * Target: Replace `"ان ديزين"` with `"إسلام عبد الغني ديزاينز"`
* **Line 174**: `'description_ar' => "في ان ديزين ، متخصصون..."`
  * Target: Replace `"ان ديزين"` with `"إسلام عبد الغني ديزاينز"`

#### 3. `app/Providers/AppServiceProvider.php`
* **Line 30**: `$favicon = 'https://indesign-co.com/wp-content/uploads/2023/07/cropped-Fav-32x32.png';`
  * Domain URL fallback (Note: asset fallback domain string; legacy domain reference).

---

### 1.2 Database Seeders (`database/seeders/`)

#### 1. `database/seeders/SettingSeeder.php`
* **Line 14**: `['key' => 'site_name_en', 'value' => 'InDesign']`
  * Target: `'value' => 'Eslam Abdulghani Designs'`
* **Line 15**: `['key' => 'site_name_ar', 'value' => 'إن ديزاين']`
  * Target: `'value' => 'إسلام عبد الغني ديزاينز'`
* **Line 18**: `['key' => 'about_short_en', 'value' => 'Founded in 1983 as a carpentry business, Indesign has grown into...']`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 19**: `['key' => 'about_short_ar', 'value' => 'تأسست شركة إن ديزاين عام 1983 كمصنع للنجارة...']`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 39**: `['key' => 'meta_title_en', 'value' => 'InDesign – Create Designs Inspire']`
  * Target: `'value' => 'Eslam Abdulghani Designs – Create Designs Inspire'`
* **Line 40**: `['key' => 'meta_title_ar', 'value' => 'إن ديزاين – نصمم لنلهم']`
  * Target: `'value' => 'إسلام عبد الغني ديزاينز – نصمم لنلهم'`
* **Line 41**: `['key' => 'meta_description_en', 'value' => 'InDesign is a leader in providing interior fit-out...']`
  * Target: Replace `'InDesign'` with `'Eslam Abdulghani Designs'`
* **Line 42**: `['key' => 'meta_description_ar', 'value' => 'إن ديزاين رائدة في خدمات التشييد...']`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 53**: `['key' => 'footer_tagline_en', 'value' => 'Indesign company is a leader in providing...']`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 54**: `['key' => 'footer_tagline_ar', 'value' => 'شركة إن ديزاين رائدة في تقديم خدمات...']`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 55**: `['key' => 'copyright_en', 'value' => 'All Rights reserved to Indesign']`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 56**: `['key' => 'copyright_ar', 'value' => 'جميع الحقوق محفوظة لشركة إن ديزاين']`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`

#### 2. `database/seeders/SectionSeeder.php`
* **Line 35** (`about_intro`): `'text_en' => "Founded in 1983 as a carpentry business, Indesign has grown..."`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 36** (`about_intro`): `'text_ar' => "تأسست إن ديزاين عام 1983 كمصنع للنجارة..."`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 79** (`contact_section`): `'body_en' => 'InDesign is a leader in providing interior fit-out...'`
  * Target: Replace `'InDesign'` with `'Eslam Abdulghani Designs'`
* **Line 80** (`contact_section`): `'body_ar' => 'إن ديزاين رائدة في تقديم خدمات التشييد...'`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 101** (`about` page `hero`): `'title_en' => 'About InDesign'`
  * Target: `'title_en' => 'About Eslam Abdulghani Designs'`
* **Line 102** (`about` page `hero`): `'title_ar' => 'عن إن ديزاين'`
  * Target: `'title_ar' => 'عن إسلام عبد الغني ديزاينز'`
* **Line 114** (`about` page `story`): `'body_en' => "Founded in 1983 as a carpentry business, Indesign has grown..."`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 115** (`about` page `story`): `'body_ar' => "تأسست إن ديزاين عام 1983 كمصنع للنجارة..."`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`
* **Line 125** (`about` page `mission`): `'body_en' => 'Indesign is a leader in providing interior fit-out...'`
  * Target: Replace `'Indesign'` with `'Eslam Abdulghani Designs'`
* **Line 126** (`about` page `mission`): `'body_ar' => 'إن ديزاين رائدة في تقديم خدمات التشييد...'`
  * Target: Replace `'إن ديزاين'` with `'إسلام عبد الغني ديزاينز'`

#### 3. `database/seeders/AdminSeeder.php`
* **Line 19**: `'name' => 'InDesign Admin'`
  * Target: Replace `'InDesign Admin'` with `'Eslam Abdulghani Designs Admin'`

#### 4. `database/seeders/PageSeeder.php`
* **Line 17** (`home`): `'meta_title' => 'Home – InDesign | Design & Built'` -> `'Home – Eslam Abdulghani Designs | Design & Built'`
* **Line 18** (`home`): `'meta_description' => 'InDesign is a full-service design...'` -> `'Eslam Abdulghani Designs is a full-service design...'`
* **Line 25** (`about`): `'meta_title' => 'About Us – InDesign | Design & Built'` -> `'About Us – Eslam Abdulghani Designs | Design & Built'`
* **Line 26** (`about`): `'meta_description' => 'Founded in 1983, InDesign has grown...'` -> `'Founded in 1983, Eslam Abdulghani Designs has grown...'`
* **Line 33** (`services`): `'meta_title' => 'Services – InDesign | Design & Built'` -> `'Services – Eslam Abdulghani Designs | Design & Built'`
* **Line 34** (`services`): `'meta_description' => 'Explore InDesign\'s comprehensive design...'` -> `'Explore Eslam Abdulghani Designs\'s comprehensive design...'`
* **Line 41** (`projects`): `'meta_title' => 'Projects – InDesign | Design & Built'` -> `'Projects – Eslam Abdulghani Designs | Design & Built'`
* **Line 42** (`projects`): `'meta_description' => 'Browse InDesign\'s portfolio...'` -> `'Browse Eslam Abdulghani Designs\'s portfolio...'`
* **Line 49** (`contact`): `'meta_title' => 'Contact Us – InDesign | Design & Built'` -> `'Contact Us – Eslam Abdulghani Designs | Design & Built'`
* **Line 50** (`contact`): `'meta_description' => 'Get in touch with InDesign...'` -> `'Get in touch with Eslam Abdulghani Designs...'`

#### 5. `database/seeders/PagesSeeder.php`
* **Line 21**: `'meta_title' => 'InDesign | Premium Interior Design'` -> `'Eslam Abdulghani Designs | Premium Interior Design'`
* **Line 50**: `'meta_title' => 'About InDesign | Our Story'` -> `'About Eslam Abdulghani Designs | Our Story'`
* **Line 57**: `'description_en' => 'Founded in 2012, InDesign has been...'` -> Replace `'InDesign'` with `'Eslam Abdulghani Designs'`

---

## 2. Live SQLite Database Survey (`database/database.sqlite`)

A complete automated scan was performed across all 28 tables and 100+ columns in `database/database.sqlite`.

### Summary of Database Findings
Total brand text occurrences: **55 matches** across 6 database tables:

| Table Name | Matching Records Count | Primary Columns Affected | Primary Keys / Identifiers |
|---|---|---|---|
| `settings` | 21 entries | `value` | `id` = 1, 2, 5, 6, 20, 21, 22, 23, 30, 31, 32, 33, 36, 37 (plus contact/social URLs: 7, 16, 17, 18, 40, 44, 45) |
| `pages` | 10 entries | `meta_title`, `meta_description` | `id` = 1 (home), 2 (about), 3 (services), 4 (projects), 5 (contact) |
| `sections` | 7 entries | `content` (JSON structure) | `id` = 2 (`about_intro`), 6 (`contact_section`), 7 (`hero`), 8 (`story`), 9 (`mission`), 18 (`offices`) |
| `admins` | 2 entries | `name`, `email` | `id` = 1 (`InDesign Admin`, `admin@indesign-co.com`) |
| `projects` | 12 entries | `cover_image`, `gallery` | Image URL domains (`https://indesign-co.com/wp-content/...`) |
| `services` | 3 entries | `icon`, `image`, `gallery` | Image URL domains (`https://indesign-co.com/wp-content/...`) |

### Detailed Table Breakdown & Target Replacements

#### A. `settings` Table
1. `key='site_name_en'` (`id=1`): `"InDesign"` -> `"Eslam Abdulghani Designs"`
2. `key='site_name_ar'` (`id=2`): `"إن ديزاين"` -> `"إسلام عبد الغني ديزاينز"`
3. `key='about_short_en'` (`id=5`): `"Founded in 1983 as a carpentry business, Indesign has grown..."` -> `"Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown..."`
4. `key='about_short_ar'` (`id=6`): `"تأسست شركة إن ديزاين عام 1983..."` -> `"تأسست شركة إسلام عبد الغني ديزاينز عام 1983..."`
5. `key='meta_title_en'` (`id=20`): `"InDesign – Create Designs Inspire"` -> `"Eslam Abdulghani Designs – Create Designs Inspire"`
6. `key='meta_title_ar'` (`id=21`): `"إن ديزاين – نصمم لنلهم"` -> `"إسلام عبد الغني ديزاينز – نصمم لنلهم"`
7. `key='meta_description_en'` (`id=22`): `"InDesign is a leader in providing..."` -> `"Eslam Abdulghani Designs is a leader in providing..."`
8. `key='meta_description_ar'` (`id=23`): `"إن ديزاين رائدة في خدمات..."` -> `"إسلام عبد الغني ديزاينز رائدة في خدمات..."`
9. `key='footer_tagline_en'` (`id=30`): `"Indesign company is a leader..."` -> `"Eslam Abdulghani Designs company is a leader..."`
10. `key='footer_tagline_ar'` (`id=31`): `"شركة إن ديزاين رائدة في..."` -> `"شركة إسلام عبد الغني ديزاينز رائدة في..."`
11. `key='copyright_en'` (`id=32`): `"All Rights reserved to Indesign"` -> `"All Rights reserved to Eslam Abdulghani Designs"`
12. `key='copyright_ar'` (`id=33`): `"جميع الحقوق محفوظة لشركة إن ديزاين"` -> `"جميع الحقوق محفوظة لشركة إسلام عبد الغني ديزاينز"`
13. `key='site_name'` (`id=36`): `"إن ديزاين"` -> `"إسلام عبد الغني ديزاينز"`
14. `key='copyright'` (`id=37`): `"All Rights reserved to Indesign"` -> `"All Rights reserved to Eslam Abdulghani Designs"`

#### B. `pages` Table
1. `id=1` (`home`):
   - `meta_title`: `"Home – InDesign | Design & Built"` -> `"Home – Eslam Abdulghani Designs | Design & Built"`
   - `meta_description`: `"InDesign is a full-service design..."` -> `"Eslam Abdulghani Designs is a full-service design..."`
2. `id=2` (`about`):
   - `meta_title`: `"About Us – InDesign | Design & Built"` -> `"About Us – Eslam Abdulghani Designs | Design & Built"`
   - `meta_description`: `"Founded in 1983, InDesign has grown..."` -> `"Founded in 1983, Eslam Abdulghani Designs has grown..."`
3. `id=3` (`services`):
   - `meta_title`: `"Services – InDesign | Design & Built"` -> `"Services – Eslam Abdulghani Designs | Design & Built"`
   - `meta_description`: `"Explore InDesign's comprehensive..."` -> `"Explore Eslam Abdulghani Designs's comprehensive..."`
4. `id=4` (`projects`):
   - `meta_title`: `"Projects – InDesign | Design & Built"` -> `"Projects – Eslam Abdulghani Designs | Design & Built"`
   - `meta_description`: `"Browse InDesign's portfolio..."` -> `"Browse Eslam Abdulghani Designs's portfolio..."`
5. `id=5` (`contact`):
   - `meta_title`: `"Contact Us – InDesign | Design & Built"` -> `"Contact Us – Eslam Abdulghani Designs | Design & Built"`
   - `meta_description`: `"Get in touch with InDesign..."` -> `"Get in touch with Eslam Abdulghani Designs..."`

#### C. `sections` Table (JSON payload values)
1. `id=2` (`key='about_intro'`):
   - JSON key `text_en`: `"Founded in 1983 as a carpentry business, Indesign has grown..."` -> `"Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown..."`
   - JSON key `text_ar`: `"تأسست إن ديزاين عام 1983..."` -> `"تأسست إسلام عبد الغني ديزاينز عام 1983..."`
2. `id=6` (`key='contact_section'`):
   - JSON key `body_en`: `"InDesign is a leader..."` -> `"Eslam Abdulghani Designs is a leader..."`
   - JSON key `body_ar`: `"إن ديزاين رائدة في..."` -> `"إسلام عبد الغني ديزاينز رائدة في..."`
3. `id=7` (`key='hero'`):
   - JSON key `title_en`: `"About InDesign"` -> `"About Eslam Abdulghani Designs"`
   - JSON key `title_ar`: `"عن إن ديزاين"` -> `"عن إسلام عبد الغني ديزاينز"`
4. `id=8` (`key='story'`):
   - JSON key `body_en`: `"Founded in 1983 as a carpentry business, Indesign has grown..."` -> `"Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown..."`
   - JSON key `body_ar`: `"تأسست إن ديزاين عام 1983..."` -> `"تأسست إسلام عبد الغني ديزاينز عام 1983..."`
5. `id=9` (`key='mission'`):
   - JSON key `body_en`: `"Indesign is a leader..."` -> `"Eslam Abdulghani Designs is a leader..."`
   - JSON key `body_ar`: `"إن ديزاين رائدة في..."` -> `"إسلام عبد الغني ديزاينز رائدة في..."`

#### D. `admins` Table
1. `id=1`: `name`: `"InDesign Admin"` -> `"Eslam Abdulghani Designs Admin"`

---

## 3. Database Update Strategy & Execution Mechanism

### 3.1 Seeder Synchronization (`php artisan db:seed`)
All seeders (`SettingSeeder.php`, `SectionSeeder.php`, `AdminSeeder.php`, `PageSeeder.php`) construct their updates using `Model::updateOrCreate(...)` targeting unique attributes (`key`, `slug`, `['page_id', 'key']`, `email`).

Therefore:
1. Updating the source strings in `SettingSeeder.php`, `SectionSeeder.php`, `AdminSeeder.php`, and `PageSeeder.php`.
2. Running `php artisan db:seed` in terminal cleanly updates all existing records in the live SQLite database (`settings`, `sections`, `pages`, `admins`).

### 3.2 Custom Database Update Command / Artisan Script
To guarantee 100% database consistency without risking data loss or resetting unmodified fields, a dedicated Artisan command or database update seeder (e.g. `RebrandDatabaseSeeder` or `php artisan db:seed --class=DatabaseSeeder`) can perform string-replacement queries directly on existing database rows:

```php
DB::table('settings')->where('value', 'LIKE', '%InDesign%')->orWhere('value', 'LIKE', '%Indesign%')->...
```

For JSON fields in `sections.content`, decoding the JSON, replacing strings, and re-saving ensures proper formatting.

---

## 4. Summary Table of Files to Update in Implementation Phase

| Category | File Path | Lines | Target Action |
|---|---|---|---|
| API Controller | `app/Http/Controllers/Api/SettingController.php` | 21 | Update site name fallbacks for English & Arabic |
| Seeder | `database/seeders/SettingSeeder.php` | 14, 15, 18, 19, 39, 40, 41, 42, 53, 54, 55, 56 | Replace branding text values |
| Seeder | `database/seeders/SectionSeeder.php` | 35, 36, 79, 80, 101, 102, 114, 115, 125, 126 | Replace branding text in section content JSONs |
| Seeder | `database/seeders/AdminSeeder.php` | 19 | Update admin user name |
| Seeder | `database/seeders/PageSeeder.php` | 17, 18, 25, 26, 33, 34, 41, 42, 49, 50 | Update page meta titles & meta descriptions |
| Seeder | `database/seeders/PagesSeeder.php` | 21, 50, 57 | Update page titles & story text |
| Console Command | `app/Console/Commands/ImportFromWordpress.php` | 39, 40, 91, 92, 174 | Update hardcoded import text strings |
| Live SQLite DB | `database/database.sqlite` | Tables: `settings`, `pages`, `sections`, `admins` | Execute `php artisan db:seed` or update script |
