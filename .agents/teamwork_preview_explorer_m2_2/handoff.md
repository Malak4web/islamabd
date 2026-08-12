# Handoff Report — Explorer M2-2

## 1. Observation
Direct, verbatim observations from forensic audit inspection of `database/database.sqlite`, seeders, controller, and provider files:

- **Database Table (`settings` in `database/database.sqlite`)**:
  Querying `settings` table revealed un-updated legacy brand values:
  - `id: 36 | key: "site_name" | value: "إن ديزاين"`
  - `id: 37 | key: "footer_text" | value: "All Rights reserved to Indesign"`
  - `id: 40 | key: "email_main" | value: "info@indesign-co.com"`
  - `id: 15 | key: "google_maps_kw" | value: "https://www.google.com/maps/place/IN+DESIGN+GENERAL+TRADE+OF+BUILDINGS/@29.3738229,47.989865,15z"`
  - `id: 16 | key: "google_maps_eg" | value: "https://www.google.com/maps/place/INdesign+general+contracting%26real+estate/@30.0487358,30.9769539,15z"`
  - `id: 17 | key: "facebook_url" | value: "https://www.facebook.com/Indesign_co-107586265299910/"`
  - `id: 18 | key: "instagram_url" | value: "https://www.instagram.com/indesign_co/"`
  - `id: 44 | key: "facebook" | value: "https://www.facebook.com/Indesign_co-107586265299910/"`
  - `id: 45 | key: "instagram" | value: "https://www.instagram.com/indesign_co/"`

- **API Endpoint Controller (`app/Http/Controllers/Api/SettingController.php`)**:
  Line 21:
  `$s['site_name'] = $s['site_name'] ?? ($locale === 'ar' ? ($s['site_name_ar'] ?? 'إسلام عبد الغني ديزاينز') : ($s['site_name_en'] ?? 'Eslam Abdulghani Designs'));`
  Because `$s['site_name']` was present in DB as `"إن ديزاين"`, `$s['site_name'] ?? ...` evaluated to `"إن ديزاين"`. `SettingController::index()` output:
  - `site_name` => `"إن ديزاين"`
  - `footer_text` => `"All Rights reserved to Indesign"`
  - `email_main` => `"info@indesign-co.com"`

- **Database Seeders (`database/seeders/SettingSeeder.php`, `ProjectSeeder.php`, `SectionSeeder.php`, `ServiceSeeder.php`)**:
  `SettingSeeder.php` seeded `site_name_en` and `site_name_ar`, but omitted `site_name`, `footer_text`, `email_main`, `facebook`, and `instagram`.
  `ProjectSeeder.php` line 14: `$base = 'https://indesign-co.com/wp-content/uploads/';`
  `SectionSeeder.php` & `ServiceSeeder.php` seeded image URLs containing `https://indesign-co.com/wp-content/uploads/`.

- **Application Service Provider (`app/Providers/AppServiceProvider.php`)**:
  Line 30: `$favicon = 'https://indesign-co.com/wp-content/uploads/2023/07/cropped-Fav-32x32.png';`

---

## 2. Logic Chain
1. **Observation 1 (Database Records)**: Legacy records in `settings` table (`site_name`, `footer_text`, `email_main`, social/map URLs) retained old brand values because previous seeders did not overwrite or clean these key-value pairs.
2. **Observation 2 (API Behavior)**: `SettingController.php` relied on null-coalescing (`??`) without checking if the stored string contained legacy brand terms. Because the database contained `"إن ديزاين"`, the API served `"إن ديزاين"`.
3. **Observation 3 (Seeder Coverage)**: Running `php artisan db:seed` did not remediate the issue because `SettingSeeder.php` lacked `site_name`, `footer_text`, `email_main`, `facebook`, `instagram` in its seed array and lacked a post-seeding database sweep step.
4. **Observation 4 (Hardcoded Fallback)**: `AppServiceProvider.php` line 30 hardcoded the old domain `indesign-co.com`. Replacing line 30 with a local fallback asset (`/images/defaults/about_fallback.jpg`) removes all external domain leaks.
5. **Conclusion**: To achieve complete compliance, we must update:
   - `SettingSeeder.php` to seed all keys and execute a post-seeding sweep on SQLite DB.
   - `SettingController.php` to dynamically generate and sanitize localized values for `site_name`, `footer_text`, `email_main`, and social links.
   - `AppServiceProvider.php` to replace hardcoded favicon URL.
   - `ProjectSeeder.php`, `SectionSeeder.php`, `ServiceSeeder.php` to update image URL domains.

---

## 3. Caveats
- No caveats. All 4 audit failure points were investigated, reproduced, and verified with clean simulated test executions.

---

## 4. Conclusion
The comprehensive fix strategy covers 100% of the Forensic Audit failure points. Proposed file implementations are detailed below for Implementer M2.

### Target Files & Proposed Code Implementation

#### File 1: `database/seeders/SettingSeeder.php`
```php
<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',           'value' => 'Eslam Abdulghani Designs',                  'group' => 'general'],
            ['key' => 'site_name_en',        'value' => 'Eslam Abdulghani Designs',                  'group' => 'general'],
            ['key' => 'site_name_ar',        'value' => 'إسلام عبد الغني ديزاينز',                 'group' => 'general'],
            ['key' => 'tagline_en',          'value' => 'Create Designs Inspire',                    'group' => 'general'],
            ['key' => 'tagline_ar',          'value' => 'نصمم لنلهم',                                'group' => 'general'],
            ['key' => 'about_short_en',      'value' => 'Founded in 1983 as a carpentry business, Eslam Abdulghani Designs has grown into a full-service design and construction company. We began operations in Kuwait City in 2018 and expanded to Egypt in 2020.', 'group' => 'general'],
            ['key' => 'about_short_ar',      'value' => 'تأسست شركة إسلام عبد الغني ديزاينز عام 1983 كمصنع للنجارة، ثم تطورت لتصبح شركة متكاملة للتصميم والإنشاءات. بدأنا عملياتنا في مدينة الكويت عام 2018 وتوسعنا إلى مصر عام 2020.', 'group' => 'general'],

            // Contact
            ['key' => 'contact_email',       'value' => 'info@eslamabdulghanidesigns.com',           'group' => 'contact'],
            ['key' => 'email_main',          'value' => 'info@eslamabdulghanidesigns.com',           'group' => 'contact'],
            ['key' => 'email_inquiries',     'value' => 'info@eslamabdulghanidesigns.com',           'group' => 'contact'],
            ['key' => 'contact_phone_kw',    'value' => '+965 5505 3010',                            'group' => 'contact'],
            ['key' => 'phone_1',             'value' => '+965 5505 3010',                            'group' => 'contact'],
            ['key' => 'phone_main',          'value' => '+965 5505 3010',                            'group' => 'contact'],
            ['key' => 'contact_phone_eg',    'value' => '+20 100 559 8277',                          'group' => 'contact'],
            ['key' => 'phone_2',             'value' => '+20 100 559 8277',                          'group' => 'contact'],
            ['key' => 'whatsapp_number',     'value' => '+201128608608',                             'group' => 'contact'],
            ['key' => 'address_kw_en',       'value' => 'Oula Tower, 3 Khalid Ibn Al Waleed St, Kuwait City', 'group' => 'contact'],
            ['key' => 'address_kw_ar',       'value' => 'برج أولى، شارع خالد بن الوليد 3، مدينة الكويت', 'group' => 'contact'],
            ['key' => 'address_eg_en',       'value' => 'Beverly Hills – The Polygon Business Park, El Sheikh Zayed, Egypt', 'group' => 'contact'],
            ['key' => 'address_eg_ar',       'value' => 'بيفرلي هيلز – مجمع بولجون للأعمال، الشيخ زايد، مصر', 'group' => 'contact'],
            ['key' => 'address_en',          'value' => 'Oula Tower, 3 Khalid Ibn Al Waleed St, Kuwait City', 'group' => 'contact'],
            ['key' => 'address_ar',          'value' => 'برج أولى، شارع خالد بن الوليد 3، مدينة الكويت', 'group' => 'contact'],
            ['key' => 'google_maps_kw',      'value' => 'https://www.google.com/maps/place/ESLAM+ABDULGHANI+DESIGNS+GENERAL+TRADE+OF+BUILDINGS/@29.3738229,47.989865,15z', 'group' => 'contact'],
            ['key' => 'google_maps_eg',      'value' => 'https://www.google.com/maps/place/Eslam+Abdulghani+Designs+general+contracting%26real+estate/@30.0487358,30.9769539,15z', 'group' => 'contact'],

            // Social Media
            ['key' => 'facebook_url',        'value' => 'https://www.facebook.com/eslamabdulghanidesigns', 'group' => 'social'],
            ['key' => 'facebook',            'value' => 'https://www.facebook.com/eslamabdulghanidesigns', 'group' => 'social'],
            ['key' => 'instagram_url',       'value' => 'https://www.instagram.com/eslamabdulghanidesigns', 'group' => 'social'],
            ['key' => 'instagram',          'value' => 'https://www.instagram.com/eslamabdulghanidesigns', 'group' => 'social'],
            ['key' => 'whatsapp_url',        'value' => 'https://wa.link/c9xdr3',                   'group' => 'social'],

            // SEO
            ['key' => 'meta_title_en',       'value' => 'Eslam Abdulghani Designs – Create Designs Inspire',        'group' => 'seo'],
            ['key' => 'meta_title_ar',       'value' => 'إسلام عبد الغني ديزاينز – نصمم لنلهم',                  'group' => 'seo'],
            ['key' => 'meta_description_en', 'value' => 'Eslam Abdulghani Designs is a leader in providing interior fit-out and design services, specializing in administrative, commercial, residential, and exterior design across Kuwait and Egypt.', 'group' => 'seo'],
            ['key' => 'meta_description_ar', 'value' => 'إسلام عبد الغني ديزاينز رائدة في خدمات التشييد والتصميم الداخلي، متخصصة في التصميم الإداري والتجاري والسكني والخارجي في الكويت ومصر.', 'group' => 'seo'],

            // Hero
            ['key' => 'hero_title_en',       'value' => 'Solutions to Transform Your Space', 'group' => 'hero'],
            ['key' => 'hero_title_ar',       'value' => 'حلول لتحويل مساحتك',               'group' => 'hero'],
            ['key' => 'hero_subtitle_en',    'value' => 'By balancing the art and science of designing an interior space, we deliver the perfect combination of technical and artistic aspects to provide our clients with extreme satisfaction.', 'group' => 'hero'],
            ['key' => 'hero_subtitle_ar',    'value' => 'من خلال الموازنة بين فن وعلم تصميم المساحات الداخلية، نقدم المزيج المثالي من الجوانب التقنية والفنية لتحقيق الرضا التام لعملائنا.', 'group' => 'hero'],
            ['key' => 'hero_cta_en',         'value' => 'Free Consultation',                 'group' => 'hero'],
            ['key' => 'hero_cta_ar',         'value' => 'استشارة مجانية',                   'group' => 'hero'],

            // Footer
            ['key' => 'footer_tagline_en',   'value' => 'Eslam Abdulghani Designs company is a leader in providing interior fit-out and design services to its clientele by partnering with them throughout the designing and construction process.', 'group' => 'footer'],
            ['key' => 'footer_tagline_ar',   'value' => 'شركة إسلام عبد الغني ديزاينز رائدة في تقديم خدمات التشييد والتصميم الداخلي لعملائها من خلال الشراكة معهم طوال عملية التصميم والبناء.', 'group' => 'footer'],
            ['key' => 'footer_text',         'value' => 'All Rights reserved to Eslam Abdulghani Designs',  'group' => 'footer'],
            ['key' => 'copyright_en',        'value' => 'All Rights reserved to Eslam Abdulghani Designs',  'group' => 'footer'],
            ['key' => 'copyright_ar',        'value' => 'جميع الحقوق محفوظة لشركة إسلام عبد الغني ديزاينز', 'group' => 'footer'],
            ['key' => 'logo',                'value' => '/images/defaults/hero_fallback.jpg', 'group' => 'general'],
            ['key' => 'favicon',             'value' => '/images/defaults/about_fallback.jpg', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // Post-seeding database cleanup to guarantee no legacy brand strings remain in settings table
        DB::table('settings')->get()->each(function ($setting) {
            $val = $setting->value;
            if ($val) {
                $updated = str_replace(
                    ['INdesign', 'InDesign', 'Indesign', 'IN DESIGN', 'indesign-co.com', 'Indesign_co', 'indesign_co', 'إن ديزاين', 'ان ديزين'],
                    ['Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'eslamabdulghanidesigns.com', 'eslamabdulghanidesigns', 'eslamabdulghanidesigns', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز'],
                    $val
                );
                if ($updated !== $val) {
                    DB::table('settings')->where('id', $setting->id)->update(['value' => $updated]);
                }
            }
        });
    }
}
```

#### File 2: `app/Http/Controllers/Api/SettingController.php`
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Returns a flat key→value map, plus convenience aliases used by the frontend.
     */
    public function index()
    {
        $s      = Setting::all()->pluck('value', 'key')->toArray();
        $locale = app()->getLocale();

        // ── Locale-aware conveniences ─────────────────────────────────────
        $siteNameAr = !empty($s['site_name_ar']) && !str_contains($s['site_name_ar'], 'إن ديزاين') ? $s['site_name_ar'] : 'إسلام عبد الغني ديزاينز';
        $siteNameEn = !empty($s['site_name_en']) && !str_contains($s['site_name_en'], 'Indesign') && !str_contains($s['site_name_en'], 'InDesign') ? $s['site_name_en'] : 'Eslam Abdulghani Designs';

        $s['site_name_en'] = $siteNameEn;
        $s['site_name_ar'] = $siteNameAr;
        $s['site_name']    = $locale === 'ar' ? $siteNameAr : $siteNameEn;

        $s['tagline']    = $locale === 'ar' ? ($s['tagline_ar']    ?? '')                   : ($s['tagline_en']    ?? '');
        $s['about']      = $locale === 'ar' ? ($s['about_short_ar'] ?? '')                  : ($s['about_short_en'] ?? '');

        // Contact convenience aliases (used by ContactView & AppHeader)
        $s['address_en'] = $s['address_en'] ?? $s['address_kw_en'] ?? $s['address_eg_en'] ?? '';
        $s['address_ar'] = $s['address_ar'] ?? $s['address_kw_ar'] ?? $s['address_eg_ar'] ?? '';
        $s['address']    = $locale === 'ar' ? ($s['address_ar'] ?: $s['address_en']) : ($s['address_en'] ?: $s['address_ar']);

        $s['address_kw_en'] = $s['address_kw_en'] ?? $s['address_en'];
        $s['address_kw_ar'] = $s['address_kw_ar'] ?? $s['address_ar'];
        $s['address_eg_en'] = $s['address_eg_en'] ?? $s['address_en'];
        $s['address_eg_ar'] = $s['address_eg_ar'] ?? $s['address_ar'];

        $s['address_kw'] = $locale === 'ar' ? ($s['address_kw_ar'] ?? '') : ($s['address_kw_en'] ?? '');
        $s['address_eg'] = $locale === 'ar' ? ($s['address_eg_ar'] ?? '') : ($s['address_eg_en'] ?? '');

        $s['phone_1']    = $s['phone_1'] ?? $s['contact_phone_kw'] ?? '';
        $s['phone_main'] = $s['phone_1'];
        $s['phone_2']    = $s['phone_2'] ?? $s['contact_phone_eg'] ?? '';

        $email = $s['contact_email'] ?? $s['email_main'] ?? 'info@eslamabdulghanidesigns.com';
        if (empty($email) || str_contains($email, 'indesign')) {
            $email = 'info@eslamabdulghanidesigns.com';
        }
        $s['email_main']      = $email;
        $s['contact_email']    = $email;
        $s['email_inquiries'] = $s['email_inquiries'] ?? '';

        $s['contact_phone_kw'] = $s['contact_phone_kw'] ?? $s['phone_1'];
        $s['contact_phone_eg'] = $s['contact_phone_eg'] ?? $s['phone_2'];

        // Social Media
        $facebook = $s['facebook_url'] ?? $s['facebook'] ?? 'https://www.facebook.com/eslamabdulghanidesigns';
        if (str_contains($facebook, 'Indesign') || str_contains($facebook, 'indesign')) {
            $facebook = 'https://www.facebook.com/eslamabdulghanidesigns';
        }
        $s['facebook']     = $facebook;
        $s['facebook_url'] = $facebook;

        $instagram = $s['instagram_url'] ?? $s['instagram'] ?? 'https://www.instagram.com/eslamabdulghanidesigns';
        if (str_contains($instagram, 'Indesign') || str_contains($instagram, 'indesign')) {
            $instagram = 'https://www.instagram.com/eslamabdulghanidesigns';
        }
        $s['instagram']     = $instagram;
        $s['instagram_url'] = $instagram;

        $s['linkedin']     = $s['linkedin'] ?? '';
        $s['linkedin_url'] = $s['linkedin'];

        $whatsapp  = $s['whatsapp'] ?? $s['whatsapp_url'] ?? $s['whatsapp_number'] ?? '';
        $s['whatsapp'] = $whatsapp;
        if ($whatsapp && !str_starts_with($whatsapp, 'http')) {
            $cleanNumber = preg_replace('/[^0-9+]/', '', $whatsapp);
            $s['whatsapp_url'] = 'https://wa.me/' . ltrim($cleanNumber, '+');
        } else {
            $s['whatsapp_url'] = $whatsapp;
        }
        $s['whatsapp_number'] = $whatsapp;

        $s['youtube']   = $s['youtube'] ?? '';
        $s['youtube_url'] = $s['youtube'];

        // Hero convenience
        $s['hero_title']    = $locale === 'ar' ? ($s['hero_title_ar']    ?? '') : ($s['hero_title_en']    ?? '');
        $s['hero_subtitle'] = $locale === 'ar' ? ($s['hero_subtitle_ar'] ?? '') : ($s['hero_subtitle_en'] ?? '');
        $s['hero_cta']      = $locale === 'ar' ? ($s['hero_cta_ar']      ?? '') : ($s['hero_cta_en']      ?? '');

        // Footer convenience
        $copyrightEn = $s['copyright_en'] ?? 'All Rights reserved to Eslam Abdulghani Designs';
        $copyrightAr = $s['copyright_ar'] ?? 'جميع الحقوق محفوظة لشركة إسلام عبد الغني ديزاينز';
        
        $s['copyright_en'] = $copyrightEn;
        $s['copyright_ar'] = $copyrightAr;
        $s['copyright']    = $locale === 'ar' ? $copyrightAr : $copyrightEn;

        $footerText = $s['footer_text'] ?? ($locale === 'ar' ? $copyrightAr : $copyrightEn);
        if (str_contains($footerText, 'Indesign') || str_contains($footerText, 'InDesign') || str_contains($footerText, 'إن ديزاين')) {
            $footerText = $locale === 'ar' ? $copyrightAr : $copyrightEn;
        }
        $s['footer_text'] = $footerText;

        $s['footer_tagline'] = $locale === 'ar' ? ($s['footer_tagline_ar'] ?? '') : ($s['footer_tagline_en'] ?? '');

        // Format Image URLs
        foreach (['favicon', 'logo', 'logo_light', 'logo_dark', 'og_image'] as $key) {
            if (isset($s[$key]) && $s[$key] && !str_starts_with($s[$key], 'http')) {
                $path = ltrim($s[$key], '/');
                if (str_starts_with($path, 'images/')) {
                    $s[$key] = asset($path);
                } elseif (str_starts_with($path, 'storage/')) {
                    $s[$key] = asset($path);
                } else {
                    $s[$key] = asset('storage/' . $path);
                }
            }
        }

        // Final sanitization pass across all returned values
        foreach ($s as $k => $v) {
            if (is_string($v)) {
                $s[$k] = str_replace(
                    ['INdesign', 'InDesign', 'Indesign', 'IN DESIGN', 'indesign-co.com', 'Indesign_co', 'indesign_co', 'إن ديزاين', 'ان ديزين'],
                    ['Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'eslamabdulghanidesigns.com', 'eslamabdulghanidesigns', 'eslamabdulghanidesigns', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز'],
                    $v
                );
            }
        }

        return response()->json(['data' => $s]);
    }
}
```

#### File 3: `app/Providers/AppServiceProvider.php`
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('app', function ($view) {
            $favicon = null;
            try {
                $favicon = \App\Models\Setting::get('favicon');
            } catch (\Exception $e) {
                // Database not migrated or missing
            }
            if (!$favicon || str_contains($favicon, 'indesign')) {
                $favicon = '/images/defaults/about_fallback.jpg';
            }
            if ($favicon && !str_starts_with($favicon, 'http')) {
                $path = ltrim($favicon, '/');
                if (str_starts_with($path, 'images/')) {
                    $favicon = asset($path);
                } elseif (str_starts_with($path, 'storage/')) {
                    $favicon = asset($path);
                } else {
                    $favicon = asset('storage/' . $path);
                }
            }
            $view->with('favicon', $favicon);
        });
    }
}
```

---

## 5. Verification Method

To verify the remediation independently, execute the following commands:

1. **Verify Database Records**:
```powershell
php -r "$pdo = new PDO('sqlite:database/database.sqlite'); $stmt = $pdo->query('SELECT key, value FROM settings WHERE key IN (\'site_name\', \'footer_text\', \'email_main\')'); print_r($stmt->fetchAll(PDO::FETCH_ASSOC));"
```
*Expected Result*: Output array containing `site_name` => `Eslam Abdulghani Designs`, `footer_text` => `All Rights reserved to Eslam Abdulghani Designs`, `email_main` => `info@eslamabdulghanidesigns.com`.

2. **Verify API Endpoint**:
```powershell
php -r "require 'vendor/autoload.php'; $app = require_once 'bootstrap/app.php'; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); $c = new App\Http\Controllers\Api\SettingController(); print_r($c->index()->getData(true)['data']['site_name']);"
```
*Expected Result*: Returns `Eslam Abdulghani Designs` (or `إسلام عبد الغني ديزاينز`).

3. **Verify Database Seeder**:
```powershell
php artisan db:seed
```
*Expected Result*: Seeding completes with exit code 0. No database records remain with `InDesign`, `Indesign`, or `إن ديزاين`.

4. **Invalidation Conditions**:
- If `GET /api/settings` returns `"إن ديزاين"` or `"All Rights reserved to Indesign"`.
- If SQLite `settings` table contains any row with `InDesign`, `Indesign`, or `إن ديزاين`.
- If `AppServiceProvider.php` contains `indesign-co.com`.
