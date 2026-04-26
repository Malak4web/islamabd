---
description: w9
---

# WORKFLOW 09 — Multilingual Module (EN / AR)
# Gate: ALL locale tests green before WF-10
# Prerequisite: WF-08 gate fully green
════════════════════════════════════════════════════════

## Purpose
Full bilingual support: English (LTR, Inter font) and Arabic (RTL,
Cairo font). Covers locale middleware, vue-i18n, Pinia localeStore,
LanguageSwitcher.vue, RTL layout, font switching, localStorage
persistence, and axios header propagation.

## Multilingual Strategy
  Layer 1: UI strings   → vue-i18n (en.json / ar.json)
  Layer 2: Content data → DB columns title_en/title_ar + Accept-Language
  Layer 3: Layout       → dir="rtl" on <html> element
  Layer 4: Typography   → Inter (EN) / Cairo (AR)

════════════════════════════════════════════════════════
## STEP 1 — Backend Locale Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/LocaleMiddlewareTest.php

  test_api_returns_english_content_by_default
    service = ['title_en'=>'Residential','title_ar'=>'سكني',is_active:true]
    GET /api/v1/services (no Accept-Language)
    assertJsonPath('data.0.title','Residential')

  test_api_returns_arabic_content_with_ar_header
    GET /api/v1/services (Accept-Language:ar)
    assertJsonPath('data.0.title','سكني')

  test_api_falls_back_to_english_for_unsupported_locale
    GET /api/v1/services (Accept-Language:fr)
    assertJsonPath('data.0.title','Residential')

  test_pages_endpoint_returns_localized_title
    page = ['title_en'=>'Home','title_ar'=>'الرئيسية']
    GET /api/v1/pages/home (Accept-Language:ar)
    assertJsonPath('data.title','الرئيسية')
    GET /api/v1/pages/home (Accept-Language:en)
    assertJsonPath('data.title','Home')

  test_settings_address_returns_localized
    Setting::set('address_en','Dubai Office') | Setting::set('address_ar','مكتب دبي')
    GET /api/v1/settings (Accept-Language:ar)
    assertJsonPath('data.address','مكتب دبي')

════════════════════════════════════════════════════════
## STEP 2 — SetLocale Middleware
════════════════════════════════════════════════════════
  FILE: app/Http/Middleware/SetLocale.php
    handle(Request $request, Closure $next):
      $locale = $request->header('Accept-Language','en')
      if (!in_array($locale, ['en','ar'])) $locale = 'en'
      App::setLocale($locale)
      return $next($request)->header('Content-Language',$locale)

  Register on api.php v1 and admin groups:
    Route::prefix('v1')->middleware([\App\Http\Middleware\SetLocale::class])->...
    Route::prefix('admin')->middleware(['auth:sanctum',\App\Http\Middleware\SetLocale::class])->...

  Update all public controllers to use app()->getLocale():
    $locale = app()->getLocale()
    'title' => $locale==='ar' ? $model->title_ar : $model->title_en
    'description' => $locale==='ar' ? $model->description_ar : $model->description_en

  Run: php artisan test tests/Feature/Api/LocaleMiddlewareTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — vue-i18n Translation Files
════════════════════════════════════════════════════════
  FILE: resources/js/i18n/en.json
  {
    "nav":{"home":"Home","about":"About Us","services":"Services",
           "projects":"Projects","contact":"Contact","lang_btn":"العربية"},
    "hero":{"cta_primary":"View Our Projects","cta_secondary":"Contact Us"},
    "services":{"title":"Our Services","view_all":"View All Services"},
    "projects":{"title":"Our Projects","view_all":"View All Projects",
                "filter_all":"All","filter_residential":"Residential",
                "filter_commercial":"Commercial","filter_hospitality":"Hospitality"},
    "contact":{"title":"Get In Touch","name":"Full Name","phone":"Phone Number",
               "email":"Email Address","service":"Service Interested In",
               "message":"Your Message","submit":"Send Message",
               "success_title":"Message Sent!",
               "success_msg":"Thank you! We will contact you within 24 hours.",
               "error_required":"This field is required.",
               "error_email":"Please enter a valid email.",
               "error_min":"Message must be at least 10 characters."},
    "footer":{"rights":"All Rights Reserved.","follow":"Follow Us"},
    "admin":{"login":"Login","logout":"Logout","dashboard":"Dashboard",
             "settings":"Settings","services":"Services","projects":"Projects",
             "contacts":"Contacts","code_injection":"Code Injection",
             "save":"Save","cancel":"Cancel","delete":"Delete","edit":"Edit",
             "add_new":"Add New","saved":"Saved successfully!",
             "error":"An error occurred.","confirm_delete":"Are you sure?"}
  }

  FILE: resources/js/i18n/ar.json (same keys, Arabic values):
  {
    "nav":{"home":"الرئيسية","about":"من نحن","services":"خدماتنا",
           "projects":"أعمالنا","contact":"تواصل معنا","lang_btn":"English"},
    "hero":{"cta_primary":"تصفح أعمالنا","cta_secondary":"تواصل معنا"},
    "services":{"title":"خدماتنا","view_all":"عرض كل الخدمات"},
    "projects":{"title":"أعمالنا","view_all":"عرض كل الأعمال",
                "filter_all":"الكل","filter_residential":"سكني",
                "filter_commercial":"تجاري","filter_hospitality":"ضيافة"},
    "contact":{"title":"تواصل معنا","name":"الاسم الكامل","phone":"رقم الجوال",
               "email":"البريد الإلكتروني","service":"الخدمة المطلوبة",
               "message":"رسالتك","submit":"إرسال الرسالة",
               "success_title":"تم الإرسال!",
               "success_msg":"شكراً لك! سنتواصل معك خلال 24 ساعة.",
               "error_required":"هذا الحقل مطلوب.",
               "error_email":"يرجى إدخال بريد إلكتروني صحيح.",
               "error_min":"يجب أن تكون الرسالة 10 أحرف على الأقل."},
    "footer":{"rights":"جميع الحقوق محفوظة.","follow":"تابعنا"},
    "admin":{"login":"تسجيل الدخول","logout":"تسجيل الخروج",
             "dashboard":"لوحة التحكم","settings":"الإعدادات",
             "services":"الخدمات","projects":"المشاريع",
             "contacts":"الرسائل","code_injection":"أكواد التتبع",
             "save":"حفظ","cancel":"إلغاء","delete":"حذف","edit":"تعديل",
             "add_new":"إضافة جديد","saved":"تم الحفظ بنجاح!",
             "error":"حدث خطأ ما.","confirm_delete":"هل أنت متأكد؟"}
  }

════════════════════════════════════════════════════════
## STEP 4 — Pinia localeStore
════════════════════════════════════════════════════════
  FILE: resources/js/stores/localeStore.js
    state:   locale = ref(localStorage.getItem('locale') || 'en')
    getters: isArabic = computed(() => locale.value === 'ar')
             isRTL    = computed(() => locale.value === 'ar')
    actions:
      setLocale(newLocale):
        locale.value = newLocale
        localStorage.setItem('locale', newLocale)
        i18n.global.locale.value = newLocale
        document.documentElement.setAttribute('lang', newLocale)
        document.documentElement.setAttribute('dir', isArabic.value ? 'rtl' : 'ltr')
        axios.defaults.headers['Accept-Language'] = newLocale

      initLocale():
        const saved = localStorage.getItem('locale')
        setLocale(saved || 'en')

════════════════════════════════════════════════════════
## STEP 5 — LanguageSwitcher.vue & RTL CSS
════════════════════════════════════════════════════════
  FILE: resources/js/components/public/LanguageSwitcher.vue
    English mode → shows "ع AR" button
    Arabic mode  → shows "EN" button
    Click → localeStore.setLocale(isArabic ? 'en' : 'ar')
    Fade transition on switch
    Position: AppHeader.vue nav right side

  RTL CSS in app.css:
    :root { font-family: 'Inter', sans-serif; }
    [lang="ar"] { font-family: 'Cairo', sans-serif; }
    [lang="ar"] input, [lang="ar"] textarea {
      direction: rtl; text-align: right;
    }

  All components use Tailwind RTL utilities:
    text-left  rtl:text-right
    pl-4       rtl:pl-0 rtl:pr-4
    mr-2       rtl:mr-0 rtl:ml-2
    left-0     rtl:left-auto rtl:right-0
    flex-row   rtl:flex-row-reverse

════════════════════════════════════════════════════════
## STEP 6 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/localeStore.test.js:
    test_initial_locale_defaults_to_en
    test_setLocale_ar_sets_locale_to_ar
    test_setLocale_saves_to_localStorage
    test_setLocale_ar_updates_i18n_global_locale
    test_setLocale_ar_sets_html_dir_to_rtl
    test_setLocale_en_sets_html_dir_to_ltr
    test_setLocale_updates_axios_header
    test_isArabic_returns_true_when_locale_is_ar
    test_initLocale_reads_saved_locale_from_storage

  tests/components/LanguageSwitcher.test.js:
    test_renders_ar_button_when_locale_is_en
    test_renders_en_button_when_locale_is_ar
    test_click_calls_setLocale_with_opposite_locale
    test_button_label_updates_after_locale_change

  tests/integration/LocaleIntegration.test.js:
    test_switching_to_ar_sets_dir_rtl_on_html_element
    test_switching_to_ar_persists_after_page_reload
    test_switching_locale_sends_new_accept_language_header

════════════════════════════════════════════════════════
## 🔴 TDD GATE 09 — ALL MUST GREEN BEFORE WF-10
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-10 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/LocaleMiddlewareTest.php
        EXPECTED: 5 tests GREEN

  [ ] npm run test -- tests/stores/localeStore.test.js
        EXPECTED: 9 tests GREEN

  [ ] npm run test -- tests/components/LanguageSwitcher.test.js
        EXPECTED: 4 tests GREEN

  [ ] npm run test -- tests/integration/LocaleIntegration.test.js
        EXPECTED: 3 tests GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests green (WF-00 to WF-09)

  MANUAL:
  [ ] Default site loads in English (LTR, Inter font)
  [ ] Click "ع AR" → site switches to Arabic
  [ ] Arabic text renders right-aligned
  [ ] Layout flips RTL (nav reversed, padding swapped)
  [ ] Font changes from Inter to Cairo in Arabic mode
  [ ] Refresh browser → Arabic persists from localStorage
  [ ] Click "EN" → switches back to English
  [ ] API calls have Accept-Language:ar header in Arabic mode
  [ ] All en.json and ar.json keys present (no missing translations)

  ALL GREEN → ✅ PROCEED TO WF-10
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  RTL not applying visually:
    Check setLocale sets dir on document.documentElement (not body)
    Tailwind config: add rtl: prefix support in theme
    Verify component classes use rtl: prefix correctly

  Font not switching to Cairo:
    Verify Google Fonts loads Cairo
    CSS selector must be [lang="ar"] on <html> not on .ar class
    document.documentElement.setAttribute('lang','ar') must be called

  API not sending Arabic header after switch:
    Check axios.defaults.headers['Accept-Language'] = newLocale
    Verify api/axios.js uses shared axios instance (not new instance)

  Locale persistence test fails:
    vi.stubGlobal('localStorage', localStorageMock) in test setup
    Clear localStorage in beforeEach for isolation
    localeStore.initLocale() must read from localStorage before fallback

  i18n strings not updating:
    Use i18n.global.locale.value = newLocale (note: .value)
    Ensure same i18n instance shared across all components

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-10
════════════════════════════════════════════════════════
  [ ] All multilingual tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-09)
  [ ] Manual: RTL, font, locale persistence all verified
  [ ] en.json and ar.json complete (no untranslated keys)
  [ ] git commit -m "feat: multilingual EN/AR with RTL layout and fonts"
  [ ] NEXT → 10_public_frontend.md
