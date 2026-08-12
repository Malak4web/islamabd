<?php
require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Setting;
use Illuminate\Support\Facades\DB;

// 1. Simulate proposed SettingSeeder run + DB cleanup logic
$settingsToSeed = [
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

foreach ($settingsToSeed as $s) {
    Setting::updateOrCreate(['key' => $s['key']], $s);
}

// Global DB Replace sweep on all settings table
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

// Also sweep projects, sections, services tables if needed
foreach (['projects' => ['cover_image', 'gallery'], 'sections' => ['content'], 'services' => ['image', 'gallery', 'icon']] as $tbl => $cols) {
    DB::table($tbl)->get()->each(function ($row) use ($tbl, $cols) {
        $updates = [];
        foreach ($cols as $col) {
            $val = $row->$col;
            if ($val) {
                $newVal = str_replace('indesign-co.com', 'eslamabdulghanidesigns.com', $val);
                if ($newVal !== $val) {
                    $updates[$col] = $newVal;
                }
            }
        }
        if (!empty($updates)) {
            DB::table($tbl)->where('id', $row->id)->update($updates);
        }
    });
}

echo "--- AFTER PROPOSED SIMULATED SEED & CLEANUP ---\n";
$stmt = DB::select("SELECT id, key, value FROM settings WHERE key IN ('site_name', 'footer_text', 'email_main', 'google_maps_eg', 'facebook_url', 'instagram_url', 'facebook', 'instagram')");
foreach ($stmt as $row) {
    echo "id: {$row->id} | key: {$row->key} | value: {$row->value}\n";
}

echo "\n--- VERIFYING VERIFICATION COMMAND 1 ---\n";
$stmt = DB::select("SELECT key, value FROM settings WHERE key IN ('site_name', 'footer_text', 'email_main')");
print_r(json_decode(json_encode($stmt), true));

echo "\n--- VERIFYING VERIFICATION COMMAND 2 ---\n";
$c = new App\Http\Controllers\Api\SettingController();
print_r($c->index()->getData(true)['data']['site_name']);
echo "\n";
