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
        $s['site_name']  = $locale === 'ar' ? ($s['site_name_ar']  ?? 'إن ديزاين')          : ($s['site_name_en']  ?? 'InDesign');
        $s['tagline']    = $locale === 'ar' ? ($s['tagline_ar']    ?? '')                   : ($s['tagline_en']    ?? '');
        $s['about']      = $locale === 'ar' ? ($s['about_short_ar'] ?? '')                  : ($s['about_short_en'] ?? '');

        // Contact convenience aliases (used by ContactView & AppHeader)
        $s['address']    = $locale === 'ar'
            ? ($s['address_kw_ar'] ?? $s['address_eg_ar'] ?? $s['address_ar'] ?? '')
            : ($s['address_kw_en'] ?? $s['address_eg_en'] ?? $s['address_en'] ?? '');

        $s['address_kw'] = $locale === 'ar' ? ($s['address_kw_ar'] ?? '') : ($s['address_kw_en'] ?? '');
        $s['address_eg'] = $locale === 'ar' ? ($s['address_eg_ar'] ?? '') : ($s['address_eg_en'] ?? '');

        $s['phone_main'] = $s['contact_phone_kw'] ?? '';
        $s['phone_2']    = $s['contact_phone_eg'] ?? '';
        $s['email_main'] = $s['contact_email']    ?? '';

        // Hero convenience
        $s['hero_title']    = $locale === 'ar' ? ($s['hero_title_ar']    ?? '') : ($s['hero_title_en']    ?? '');
        $s['hero_subtitle'] = $locale === 'ar' ? ($s['hero_subtitle_ar'] ?? '') : ($s['hero_subtitle_en'] ?? '');
        $s['hero_cta']      = $locale === 'ar' ? ($s['hero_cta_ar']      ?? '') : ($s['hero_cta_en']      ?? '');

        // Footer convenience
        $s['footer_tagline'] = $locale === 'ar' ? ($s['footer_tagline_ar'] ?? '') : ($s['footer_tagline_en'] ?? '');
        $s['copyright']      = $locale === 'ar' ? ($s['copyright_ar']      ?? '') : ($s['copyright_en']      ?? '');

        // Format Image URLs
        foreach (['favicon', 'logo', 'logo_light', 'logo_dark', 'og_image'] as $key) {
            if (isset($s[$key]) && $s[$key] && !str_starts_with($s[$key], 'http')) {
                $s[$key] = asset('storage/' . ltrim($s[$key], '/'));
            }
        }

        return response()->json(['data' => $s]);
    }
}
