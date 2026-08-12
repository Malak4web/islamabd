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
        $hasLegacyAr = !empty($s['site_name_ar']) && (
            str_contains($s['site_name_ar'], 'إن ديزاين') ||
            str_contains($s['site_name_ar'], 'ان ديزين') ||
            str_contains($s['site_name_ar'], 'ان ديزاين') ||
            str_contains($s['site_name_ar'], 'إن ديزين')
        );
        $siteNameAr = (!empty($s['site_name_ar']) && !$hasLegacyAr) ? $s['site_name_ar'] : 'إسلام عبد الغني ديزاينز';

        $hasLegacyEn = !empty($s['site_name_en']) && (
            mb_stripos($s['site_name_en'], 'indesign') !== false ||
            mb_stripos($s['site_name_en'], 'in design') !== false
        );
        $siteNameEn = (!empty($s['site_name_en']) && !$hasLegacyEn) ? $s['site_name_en'] : 'Eslam Abdulghani Designs';

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
        if (empty($email) || mb_stripos($email, 'indesign') !== false) {
            $email = 'info@eslamabdulghanidesigns.com';
        }
        $s['email_main']      = $email;
        $s['contact_email']    = $email;
        $s['email_inquiries'] = $s['email_inquiries'] ?? '';

        $s['contact_phone_kw'] = $s['contact_phone_kw'] ?? $s['phone_1'];
        $s['contact_phone_eg'] = $s['contact_phone_eg'] ?? $s['phone_2'];

        // Social Media
        $facebook = $s['facebook_url'] ?? $s['facebook'] ?? 'https://www.facebook.com/eslamabdulghanidesigns';
        if (mb_stripos($facebook, 'indesign') !== false) {
            $facebook = 'https://www.facebook.com/eslamabdulghanidesigns';
        }
        $s['facebook']     = $facebook;
        $s['facebook_url'] = $facebook;

        $instagram = $s['instagram_url'] ?? $s['instagram'] ?? 'https://www.instagram.com/eslamabdulghanidesigns';
        if (mb_stripos($instagram, 'indesign') !== false) {
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
        if (
            mb_stripos($footerText, 'indesign') !== false ||
            mb_stripos($footerText, 'in design') !== false ||
            str_contains($footerText, 'إن ديزاين') ||
            str_contains($footerText, 'ان ديزين') ||
            str_contains($footerText, 'ان ديزاين') ||
            str_contains($footerText, 'إن ديزين')
        ) {
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
        $search = [
            'indesign-co.com', 'Indesign_co', 'indesign_co',
            'IN DESIGN', 'In Design', 'in design',
            'INdesign', 'InDesign', 'Indesign', 'indesign', 'INDESIGN',
            'إن ديزاين', 'ان ديزين', 'ان ديزاين', 'إن ديزين',
        ];
        $replace = [
            'eslamabdulghanidesigns.com', 'eslamabdulghanidesigns', 'eslamabdulghanidesigns',
            'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs',
            'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs', 'Eslam Abdulghani Designs',
            'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز', 'إسلام عبد الغني ديزاينز',
        ];

        foreach ($s as $k => $v) {
            if (is_string($v)) {
                $s[$k] = str_replace($search, $replace, $v);
            }
        }

        return response()->json(['data' => $s]);
    }
}

