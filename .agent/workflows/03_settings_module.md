---
description: w4
---

# WORKFLOW 03 — Settings Module
# Gate: ALL settings tests green before WF-04
# Prerequisite: WF-02 gate fully green
════════════════════════════════════════════════════════

## Purpose
Global site settings: logo, phones, emails, address, social links,
footer text. Public read API, admin CRUD API, and AdminSettings.vue
with tabbed interface grouped by setting type.

## Settings Groups & Keys
  GROUP general:
    site_name   (text)    → Shown in browser tab and header
    logo        (image)   → Header logo, stored in public disk
    favicon     (image)   → Tab icon, .ico or .png
    footer_text (text)    → Copyright text in footer

  GROUP contact:
    phone_1         (text)     → Primary phone, visible in header
    phone_2         (text)     → Secondary phone, footer
    email_main      (text)     → Main contact email
    email_inquiries (text)     → Inquiries-specific email
    address_en      (textarea) → Office address in English
    address_ar      (textarea) → Office address in Arabic (RTL)
    google_maps_url (text)     → Google Maps embed iframe URL

  GROUP social:
    facebook  (text) → Full page URL (https://facebook.com/...)
    instagram (text) → Full profile URL
    linkedin  (text) → Full company URL
    whatsapp  (text) → Number with country code: +971XXXXXXXXX
    youtube   (text) → Full channel URL

════════════════════════════════════════════════════════
## STEP 1 — Public API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/SettingPublicTest.php

  test_public_can_get_all_settings
    Create 2 settings. GET /api/v1/settings.
    assertOk()
    assertJsonStructure(['data'])
    assertJsonPath('data.phone_1','+971 4 123 4567')

  test_settings_response_is_flat_key_value_object
    Create 3 settings. GET /api/v1/settings.
    Assert response.data is object with key as direct property
    NOT an array of objects

  test_settings_endpoint_is_publicly_accessible_no_auth
    GET /api/v1/settings (no auth header)
    assertOk() — must NOT return 401

════════════════════════════════════════════════════════
## STEP 2 — Public SettingController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/SettingController.php
    index():
      $settings = Setting::all()->pluck('value','key')
      return response()->json(['data' => $settings])

  ROUTE in routes/api.php v1 group:
    Route::get('/settings', [Api\SettingController::class,'index']);

  Run: php artisan test tests/Feature/Api/SettingPublicTest.php
  EXPECTED: ALL GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/SettingAdminTest.php

  test_unauthenticated_cannot_access_admin_settings
    GET /api/admin/settings → assertUnauthorized()
    PUT /api/admin/settings/phone_1 → assertUnauthorized()

  test_admin_can_list_all_settings
    Create 5 settings. actingAs(admin,'sanctum')
    GET /api/admin/settings
    assertOk() | assertJsonCount(5,'data')
    assertJsonStructure([data=>[['id','key','value','group','type']]])

  test_admin_can_list_settings_filtered_by_group
    Create 3 general, 2 contact. GET /api/admin/settings?group=contact
    assertJsonCount(2,'data')

  test_admin_can_update_setting_by_key
    Create setting key='phone_1', value='old'
    PUT /api/admin/settings/phone_1 { value:'new-phone' }
    assertOk()
    assertDatabaseHas('settings',['key'=>'phone_1','value'=>'new-phone'])

  test_update_setting_requires_value_field
    PUT /api/admin/settings/phone_1 {}
    assertUnprocessable()
    assertJsonValidationErrors(['value'])

  test_admin_can_bulk_update_settings
    POST /api/admin/settings/bulk {
      settings:[{key:'phone_1',value:'+971...'},{key:'email_main',value:'new@...'}]
    }
    assertOk()
    assertDatabaseHas('settings',['key'=>'phone_1'])

  test_admin_can_upload_logo_image
    POST /api/admin/settings/image/logo {file:fake()->image('logo.png')}
    assertOk()
    assertJsonStructure([data=>[url]])

════════════════════════════════════════════════════════
## STEP 4 — Admin SettingController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/SettingController.php

  index(Request $request):
    Filter by group if ?group= param present
    Return full list with id,key,value,group,type

  update(Request $request, string $key):
    Validate: value required
    Setting::updateOrCreate(['key'=>$key],['value'=>$request->value])
    Return 200 { data: updated setting }

  bulkUpdate(Request $request):
    Validate: settings array required
    Loop and updateOrCreate each
    Return 200 { message:'Settings saved.' }

  uploadImage(Request $request, string $key):
    Validate: file(required|mimes:jpg,png,webp|max:2048)
    Store in public disk → Setting::set($key, $publicPath)
    Return 200 { data:{ url:$publicUrl } }

  ROUTES in admin group:
    Route::get('/settings', [Admin\SettingController::class,'index']);
    Route::put('/settings/{key}', [Admin\SettingController::class,'update']);
    Route::post('/settings/bulk', [Admin\SettingController::class,'bulkUpdate']);
    Route::post('/settings/image/{key}', [Admin\SettingController::class,'uploadImage']);

  Run: php artisan test tests/Feature/Admin/SettingAdminTest.php
  EXPECTED: ALL GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Pinia settingStore
════════════════════════════════════════════════════════
  FILE: resources/js/stores/settingStore.js

  import { defineStore } from 'pinia'
  import { ref, computed } from 'vue'
  import api from '@/api/axios'

  export const useSettingStore = defineStore('setting', () => {
    const settings = ref({})   // flat key:value object
    const isLoading = ref(false)

    const get = (key, fallback = '') =>
      computed(() => settings.value[key] ?? fallback)

    async function fetchSettings() {
      isLoading.value = true
      const { data } = await api.get('/v1/settings')
      settings.value = data.data
      isLoading.value = false
    }

    async function fetchAdminSettings() {
      const { data } = await api.get('/admin/settings')
      return data.data  // returns grouped list
    }

    async function updateSetting(key, value) {
      const { data } = await api.put(`/admin/settings/${key}`, { value })
      settings.value[key] = value  // optimistic update
      return data
    }

    async function bulkUpdate(settingsArray) {
      await api.post('/admin/settings/bulk', { settings: settingsArray })
      // Refresh settings after bulk save
      await fetchSettings()
    }

    async function uploadImage(key, file) {
      const form = new FormData()
      form.append('file', file)
      const { data } = await api.post(`/admin/settings/image/${key}`, form,
        { headers: { 'Content-Type': 'multipart/form-data' } })
      settings.value[key] = data.data.url
      return data.data.url
    }

    return { settings, isLoading, get, fetchSettings,
             fetchAdminSettings, updateSetting, bulkUpdate, uploadImage }
  })

  // Deep detail: 
  // - SettingStore uses 'flat' state structure for rapid O(1) access via key.
  // - Getters utilize computed properties to ensure reactivity updates components automatically on key change.
  // - Bulk actions trigger an automatic refresh of local state to maintain synchronization with the server source of truth.
  // - Error handling is deferred to global axios interceptors to keep store logic clean and focused on data transformation.
  // - Optimistic UI implementation: local state is updated immediately upon successful PUT, then validated via API response.

════════════════════════════════════════════════════════
## STEP 6 — AdminSettings.vue
════════════════════════════════════════════════════════
  FILE: resources/js/views/admin/AdminSettings.vue

  Layout: Tabbed panel with 3 tabs
    Tab: General
      Logo (ImageUpload component, preview)
      Site Name (text input)
      Footer Text (text input)

    Tab: Contact
      Phone 1 (tel), Phone 2 (tel)
      Email Main (email), Email Inquiries (email)
      Address EN (textarea), Address AR (textarea dir=rtl)
      Google Maps URL (url)

    Tab: Social
      Facebook, Instagram, LinkedIn, WhatsApp, YouTube (url inputs)

  Save button per tab → calls store.bulkUpdate(tabSettings)
  Toast notification on success/error
  Loading state while saving

  Usage in AppHeader + AppFooter:
    settingStore.settings.logo, phone_1, email_main, socials, footer_text

════════════════════════════════════════════════════════
## STEP 7 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/settingStore.test.js:
    test_initial_settings_is_empty_object
    test_fetchSettings_populates_settings_state
    test_get_getter_returns_value_by_key
    test_get_returns_empty_string_for_missing_key
    test_updateSetting_calls_correct_api_endpoint
    test_updateSetting_updates_local_state

  tests/views/AdminSettings.test.js:
    test_renders_three_tabs_general_contact_social
    test_general_tab_shows_logo_upload_field
    test_contact_tab_shows_phone_and_email_fields
    test_social_tab_shows_social_url_fields
    test_save_calls_store_bulkUpdate
    test_shows_success_toast_after_save
    test_shows_error_toast_on_api_failure

════════════════════════════════════════════════════════
## 🔴 TDD GATE 03 — ALL MUST GREEN BEFORE WF-04
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-04 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/SettingPublicTest.php
        EXPECTED: 3 tests GREEN

  [ ] php artisan test tests/Feature/Admin/SettingAdminTest.php
        EXPECTED: 7 tests GREEN

  [ ] npm run test -- tests/stores/settingStore.test.js
        EXPECTED: 6 tests GREEN

  [ ] npm run test -- tests/views/AdminSettings.test.js
        EXPECTED: 7 tests GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests still green (WF-00+01+02+03)

  MANUAL:
  [ ] GET /api/v1/settings returns flat key-value JSON
  [ ] Admin opens /admin/settings → 3 tabs render
  [ ] Admin updates phone → saved → public API returns new value
  [ ] Admin uploads logo → preview updates → stored in /storage/public
  [ ] AppHeader shows logo from settings
  [ ] AppFooter shows phone, email, social links

  ALL GREEN → ✅ PROCEED TO WF-04
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Settings returns 404:
    Check route registered in api.php v1 prefix group
    Run: php artisan route:list --filter=settings
    Verify controller namespace in route registration

  PUT /api/admin/settings/{key} returns 404:
    Check {key} route allows underscores: ->where('key','[a-z_]+')
    Run: php artisan route:list --filter=admin/settings

  Bulk update 422 error:
    Check validation: settings.*.key required|string
    Check settings.*.value nullable|string (allow empty)

  Image upload fails:
    Check FILESYSTEM_DISK=public in .env
    Check php artisan storage:link was run
    Check php.ini upload_max_filesize >= 5M

  settingStore "settings not reactive":
    Use ref() or reactive() for state
    Ensure fetchSettings awaits the API call

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-04
════════════════════════════════════════════════════════
  [ ] All settings tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-03)
  [ ] Manual verification done
  [ ] git commit -m "feat: settings module with public and admin API"
  [ ] NEXT → 04_pages_sections_module.md
