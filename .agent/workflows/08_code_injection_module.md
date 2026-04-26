---
description: w8
---

# WORKFLOW 08 — Code Injection Module
# Gate: ALL code injection tests green before WF-09
# Prerequisite: WF-07 gate fully green
════════════════════════════════════════════════════════

## Purpose
Admin adds raw HTML/JS snippets (GTM, Meta Pixel, TikTok Pixel, live
chat, custom CSS) and injects them into <head>, body start, or body end.
Snippets target all pages or specific page slugs. Frontend
CodeInjector.vue fetches and injects on every route change.

## Injection Locations
  head        → Google Tag Manager, Meta Pixel, GA4, custom CSS
  body_start  → Noscript GTM fallback, A/B test scripts
  body_end    → Live chat widgets, deferred analytics

════════════════════════════════════════════════════════
## STEP 1 — Public API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/CodeInjectionPublicTest.php

  test_returns_active_injections_grouped_by_location
    Create: active+head+pages=null | active+body_end+pages=null | inactive+head
    GET /api/v1/code-injections?page=home
    assertJsonCount(1,'data.head')     ← only active
    assertJsonCount(1,'data.body_end') ← only active
    assertJsonCount(0,'data.body_start')

  test_all_pages_injection_appears_on_every_page
    Create injection pages=null (all)
    GET ?page=home   → head count=1
    GET ?page=about  → head count=1
    GET ?page=contact→ head count=1

  test_specific_page_injection_only_on_that_page
    Create injection pages=['home']
    GET ?page=home  → head count=1  ← appears
    GET ?page=about → head count=0  ← does NOT appear

  test_multiple_pages_injection_appears_on_each
    Create pages=['home','about']
    GET ?page=home  → body_end count=1
    GET ?page=about → body_end count=1
    GET ?page=contact → body_end count=0

  test_inactive_injections_not_returned
    Create is_active=false. GET /api/v1/code-injections?page=home
    All location arrays empty

  test_response_structure_has_head_body_start_body_end
    GET /api/v1/code-injections?page=home
    assertJsonStructure([data=>[head,body_start,body_end]])

  test_each_injection_has_id_name_code_fields
    Create active injection with code='<script>gtm()</script>'
    assertJsonStructure([data=>[head=>[[id,name,code]]]])

  test_endpoint_publicly_accessible_no_auth
    GET /api/v1/code-injections?page=home → assertOk() — NOT 401

════════════════════════════════════════════════════════
## STEP 2 — Public CodeInjectionController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/CodeInjectionController.php
    index(Request $request):
      $slug = $request->query('page','home')
      $injections = CodeInjection::active()
        ->where(function($q) use ($slug) {
          $q->whereNull('pages')
            ->orWhere('pages','LIKE',"%\"$slug\"%"); ← SQLite compat
        })
        ->get()

      return response()->json(['data' => [
        'head'       => $injections->where('location','head')->values(),
        'body_start' => $injections->where('location','body_start')->values(),
        'body_end'   => $injections->where('location','body_end')->values(),
      ]])

  ROUTE:
    Route::get('/code-injections', [Api\CodeInjectionController::class,'index']);

  Run: php artisan test tests/Feature/Api/CodeInjectionPublicTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin CRUD Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/CodeInjectionAdminTest.php

  test_unauthenticated_cannot_manage_injections
    GET /api/admin/code-injections → assertUnauthorized()

  test_admin_can_list_all_code_injections
    4 injections. assertOk() | assertJsonCount(4,'data')
    assertJsonStructure([data=>[['id','name','code','location','is_active','pages']]])

  test_admin_can_create_gtm_injection
    POST /api/admin/code-injections {
      name:'Google Tag Manager', code:'<!-- GTM --><script>...</script>',
      location:'head', is_active:true, pages:null
    }
    assertCreated()
    assertDatabaseHas('code_injections',['name'=>'Google Tag Manager'])

  test_create_requires_name_and_code
    POST { location:'head' } → assertJsonValidationErrors(['name','code'])

  test_create_requires_valid_location
    POST { name:'T', code:'..', location:'invalid' }
    assertJsonValidationErrors(['location'])

  test_create_with_specific_pages
    POST { ..., pages:['home','about'] }
    assertCreated()
    inj = CodeInjection::first()
    Assert count(inj->pages) === 2
    Assert in_array('home', inj->pages)

  test_admin_can_update_injection
    PUT /api/admin/code-injections/{id} { name:'New Name', code:'...', ... }
    assertDatabaseHas('code_injections',['name'=>'New Name'])

  test_admin_can_toggle_injection_active
    is_active=true → PATCH /api/admin/code-injections/{id}/toggle
    Assert is_active === false

  test_admin_can_delete_injection
    DELETE → assertNoContent() | assertDatabaseMissing

  test_admin_can_get_single_injection_with_full_code
    Create injection with long code string
    GET /api/admin/code-injections/{id}
    assertJsonPath('data.code', $fullCode)

════════════════════════════════════════════════════════
## STEP 4 — Admin CodeInjectionController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/CodeInjectionController.php
    index():      CodeInjection::latest()->get()
    store():      Validate + create (pages nullable|array)
    show($id):    findOrFail
    update($id):  Validate + update
    toggle($id):  flip is_active
    destroy($id): delete

  ROUTES:
    Route::apiResource('/code-injections', Admin\CodeInjectionController::class);
    Route::patch('/code-injections/{id}/toggle',
                 [Admin\CodeInjectionController::class,'toggle']);

  Run: php artisan test tests/Feature/Admin/CodeInjectionAdminTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — CodeInjector.vue (Global, Invisible)
════════════════════════════════════════════════════════
  FILE: resources/js/components/CodeInjector.vue
  Mounted inside App.vue — no visible output

  Logic (watches route changes):
    watch(route, async (newRoute) => {
      const slug = newRoute.name?.replace('public.','') || 'home'
      const { data } = await api.get(`/v1/code-injections?page=${slug}`)

      // Inject head snippets
      data.head.forEach(snippet => {
        if (document.querySelector(`[data-snip="${snippet.id}"]`)) return
        const el = document.createElement('div')
        el.innerHTML = snippet.code
        el.setAttribute('data-snip', snippet.id)
        document.head.appendChild(el)
      })

      // Inject body_start snippets
      data.body_start.forEach(snippet => {
        if (document.querySelector(`[data-snip-bs="${snippet.id}"]`)) return
        const el = document.createElement('div')
        el.innerHTML = snippet.code
        el.setAttribute('data-snip-bs', snippet.id)
        document.body.insertBefore(el, document.body.firstChild)
      })

      // Inject body_end snippets
      data.body_end.forEach(snippet => {
        if (document.querySelector(`[data-snip-be="${snippet.id}"]`)) return
        const el = document.createElement('div')
        el.innerHTML = snippet.code
        el.setAttribute('data-snip-be', snippet.id)
        document.body.appendChild(el)
      })
    }, { immediate: true })

  Template: <template><!-- CodeInjector --></template>

════════════════════════════════════════════════════════
## STEP 6 — AdminCodeInjection.vue (Dashboard)
════════════════════════════════════════════════════════
  Header: "Add New Code Snippet" button
  Snippets table:
    Columns: Name | Location badge | Pages | Active toggle | Actions
    Location badges: HEAD=blue | BODY START=purple | BODY END=orange
    Pages: "All Pages" OR comma-joined slugs
    Active toggle → store.toggleInjection(id)
    Actions: Edit | Duplicate | Delete (confirm)

  InjectionFormModal.vue:
    Name field (text, admin reference label)
    Location select: <head> | Body Start | Body End
    Pages:
      ◉ All Pages (pages=null)
      ○ Specific: checkboxes [Home] [About] [Services] [Projects] [Contact]
    Monaco Editor (html mode, vs-dark theme, 300px height)
    Active toggle
    Save button with loading state

════════════════════════════════════════════════════════
## STEP 7 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/codeInjectionStore.test.js:
    test_fetchForPage_sends_correct_page_param
    test_fetchForPage_returns_grouped_by_location
    test_toggleInjection_flips_is_active_in_admin_list
    test_deleteInjection_removes_from_admin_list

  tests/views/AdminCodeInjection.test.js:
    test_renders_list_of_injections
    test_location_badge_shows_correct_color
    test_add_new_button_opens_modal
    test_all_pages_option_sends_null_pages
    test_specific_pages_sends_array_of_slugs
    test_active_toggle_calls_store_toggle

  tests/components/CodeInjector.test.js:
    test_fetches_injections_on_mount
    test_injects_head_snippet_into_document_head
    test_does_not_duplicate_already_injected_snippet
    test_fetches_new_injections_on_route_change

  Monaco Editor mock in setup.js:
    vi.mock('@monaco-editor/vue3', () => ({
      default: { template:'<textarea data-monaco-editor />' }
    }))

════════════════════════════════════════════════════════
## 🔴 TDD GATE 08 — ALL MUST GREEN BEFORE WF-09
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-09 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/CodeInjectionPublicTest.php
        EXPECTED: 8 tests GREEN

  [ ] php artisan test tests/Feature/Admin/CodeInjectionAdminTest.php
        EXPECTED: 10 tests GREEN

  [ ] npm run test -- tests/stores/codeInjectionStore.test.js
  [ ] npm run test -- tests/views/AdminCodeInjection.test.js
  [ ] npm run test -- tests/components/CodeInjector.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests green (WF-00 to WF-08)

  MANUAL:
  [ ] Add GTM snippet in admin (head, all pages)
  [ ] Open public site → view-source → GTM code in <head>
  [ ] Add home-only snippet → NOT in /about page source
  [ ] Toggle off → snippet disappears from page source
  [ ] Monaco editor shows syntax highlighting
  [ ] CodeInjector.vue does NOT duplicate on re-render

  ALL GREEN → ✅ PROCEED TO WF-09
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  forPage scope returns wrong results on SQLite:
    whereJsonContains may not work on SQLite
    Use: ->orWhere('pages','LIKE',"%\"$slug\"%")
    Test with both null pages (all) AND specific pages=['home']

  CodeInjector duplicates snippets:
    Check [data-snip="{id}"] attribute set BEFORE appendChild
    querySelector must use string ID: `[data-snip="${snippet.id}"]`

  Monaco Editor not loading in test:
    Add mock to resources/js/tests/setup.js
    vi.mock('@monaco-editor/vue3', () => ({...}))

  pages JSON column array vs null:
    pages=null stored as NULL in DB (not "null" string)
    pages=['home'] stored as JSON array string
    Cast in model: 'pages' => 'array' (Laravel handles null→null)

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-09
════════════════════════════════════════════════════════
  [ ] All code injection tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-08)
  [ ] Manual: GTM code visible in page source
  [ ] git commit -m "feat: code injection system with Monaco editor"
  [ ] NEXT → 09_multilingual_module.md
