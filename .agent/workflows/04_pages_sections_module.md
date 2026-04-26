---
description: w4
---

# WORKFLOW 04 — Pages & Sections Module
# Gate: ALL page/section tests green before WF-05
# Prerequisite: WF-03 gate fully green
════════════════════════════════════════════════════════

## Purpose
Page content management system. Each page has SEO metadata and
dynamic sections (JSON content blobs). Admin edits any section
inline. Public API returns page + sections per slug.

## Pages & Their Sections
  home:     hero, about_snippet, services_preview, projects_preview, cta_banner
  about:    story, team, vision_mission, stats
  services: services_header, services_grid
  projects: projects_header, projects_grid
  contact:  contact_header, contact_info, contact_form

════════════════════════════════════════════════════════
## STEP 1 — Public Page API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/PagePublicTest.php

  test_can_get_home_page_with_sections
    Create Page slug='home'. Section::factory()->count(3)->create(['page_id'=>page->id,'is_active'=>true])
    GET /api/v1/pages/home
    assertOk()
    assertJsonStructure([data=>[id,slug,title_en,title_ar,meta_title,meta_description,og_image,sections]])
    assertJsonCount(3,'data.sections')

  test_inactive_sections_excluded
    1 active + 1 inactive → GET /api/v1/pages/{slug}
    assertJsonCount(1,'data.sections')

  test_sections_returned_in_order
    Sections with order 3,1,2 → sections[0].key is order=1

  test_page_returns_404_for_invalid_slug
    GET /api/v1/pages/nonexistent
    assertNotFound()
    assertJson(['message'=>'Page not found.'])

  test_page_returns_arabic_title_on_ar_header
    Create page ['title_ar'=>'الرئيسية']
    GET /api/v1/pages/home (Accept-Language: ar)
    assertJsonPath('data.title','الرئيسية')

  test_page_endpoint_is_publicly_accessible
    GET /api/v1/pages/home (no auth) → assertOk()

════════════════════════════════════════════════════════
## STEP 2 — Public PageController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/PageController.php
    show(Request $request, string $slug):
      $page = Page::where('slug',$slug)->firstOrFail()
      $sections = $page->sections()->active()->ordered()->get()
      $locale = $request->header('Accept-Language','en')
      return response()->json(['data' => [
        ...$page->toArray(),
        'title' => $locale==='ar' ? $page->title_ar : $page->title_en,
        'sections' => $sections->map(fn($s)=>[
          'key'=>$s->key, 'order'=>$s->order, 'content'=>$s->content
        ])
      ]])

  ROUTE: Route::get('/pages/{slug}', [Api\PageController::class,'show']);

  Run: php artisan test tests/Feature/Api/PagePublicTest.php
  EXPECTED: ALL GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin Page & Section Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/PageAdminTest.php

  test_unauthenticated_cannot_access_admin_pages
    GET /api/admin/pages → assertUnauthorized()

  test_admin_can_list_all_pages
    Page::factory()->count(5)->create()
    actingAs(admin,'sanctum'). GET /api/admin/pages
    assertOk() | assertJsonCount(5,'data')

  test_admin_can_update_page_seo
    page = Page::factory()->create(['slug'=>'home'])
    PUT /api/admin/pages/{page->id} {
      meta_title:'New Meta', meta_description:'Description', og_image:'/img.jpg'
    }
    assertOk()
    assertDatabaseHas('pages',['id'=>page->id,'meta_title'=>'New Meta'])

  test_admin_can_get_sections_for_page
    page + 4 sections → GET /api/admin/sections/{page->id}
    assertJsonCount(4,'data')

  test_admin_can_update_section_content
    section = Section::factory()->create(['content'=>['title'=>'Old']])
    PUT /api/admin/sections/{section->id} { content:{title:'New',subtitle:'Sub'} }
    assertOk()
    Assert section->fresh()->content['title'] === 'New'

  test_admin_can_toggle_section_active_status
    section active=true → PATCH /api/admin/sections/{id}/toggle
    assertOk()
    Assert section->fresh()->is_active === false

  test_admin_can_reorder_sections
    s1 order=1, s2 order=2, s3 order=3
    PATCH /api/admin/sections/reorder { order:[s3->id,s1->id,s2->id] }
    assertOk()
    Assert s3->fresh()->order === 1, s1->fresh()->order === 2

════════════════════════════════════════════════════════
## STEP 4 — Admin Controllers
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/PageController.php
    index():    Page::all()
    update($id): Validate + update meta_title, meta_description, og_image, titles

  FILE: app/Http/Controllers/Admin/SectionController.php
    index($pageId): Page::findOrFail($pageId)->sections()->ordered()->get()
    update($id):    Validate + $section->update(['content'=>$validated['content']])
    toggle($id):    $section->update(['is_active'=>!$section->is_active])
    reorder(Request $request):
      foreach ($request->order as $i => $id)
        Section::find($id)?->update(['order'=>$i+1])

  ROUTES in admin group:
    Route::get('/pages', [Admin\PageController::class,'index']);
    Route::put('/pages/{id}', [Admin\PageController::class,'update']);
    Route::get('/sections/{pageId}', [Admin\SectionController::class,'index']);
    Route::put('/sections/{id}', [Admin\SectionController::class,'update']);
    Route::patch('/sections/{id}/toggle', [Admin\SectionController::class,'toggle']);
    Route::patch('/sections/reorder', [Admin\SectionController::class,'reorder']);

  Run: php artisan test tests/Feature/Admin/PageAdminTest.php
  EXPECTED: ALL GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Pinia Stores
════════════════════════════════════════════════════════
  pageStore:
    state: pages[], currentPage{}, seo{}
    actions: fetchPages(), fetchPage(slug), fetchAdminPages()

  sectionStore:
    state: sections[], isLoading
    actions:
      fetchSections(pageId)  GET /api/admin/sections/{pageId}
      updateSection(id,content) PUT /api/admin/sections/{id}
      toggleSection(id)      PATCH /api/admin/sections/{id}/toggle
      reorderSections(ids)   PATCH /api/admin/sections/reorder

════════════════════════════════════════════════════════
## STEP 6 — Admin Vue Components
════════════════════════════════════════════════════════
  AdminPages.vue:
    Lists all 5 pages. Click → navigate to AdminSections.vue

  AdminSections.vue:
    SEO Panel: meta_title, meta_description, og_image upload
    Google snippet preview (shows how it looks in search results)
    Sections list (draggable for reorder)
    Each section card: key badge, active toggle, Edit button
    SectionEditor modal per key:
      hero:         title_en/ar, subtitle_en/ar, bg_image, cta_text, cta_link
      about_snippet:title_en/ar, description_en/ar, image
      services_preview: heading_en/ar, subheading_en/ar
      cta_banner:   heading_en/ar, button_text, button_link
    Live preview panel alongside editor

════════════════════════════════════════════════════════
## STEP 7 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/pageStore.test.js:
    test_initial_currentPage_is_null
    test_fetchPage_sets_currentPage_with_sections
    test_sections_ordered_by_order_field

  tests/stores/sectionStore.test.js:
    test_fetchSections_populates_sections_array
    test_updateSection_sends_put_with_content
    test_reorderSections_sends_ordered_ids_array
    test_toggleSection_flips_is_active_locally

  tests/views/AdminSections.test.js:
    test_renders_seo_panel_with_meta_fields
    test_renders_section_cards
    test_active_toggle_calls_store_toggleSection
    test_edit_button_opens_section_editor_modal
    test_drag_calls_store_reorderSections

════════════════════════════════════════════════════════
## STEP 8 — Demo Seeder
════════════════════════════════════════════════════════
  php artisan make:seeder PagesSeeder
  Seeds 5 pages with realistic meta data
  Seeds sections for each page with initial JSON content:
    hero: { title_en:'Crafting Exceptional Spaces',
            title_ar:'نصنع مساحات استثنائية',
            subtitle_en:'Premium Interior Design',
            subtitle_ar:'تصميم داخلي راقٍ',
            cta_text_en:'View Projects', cta_link:'/projects' }
  php artisan db:seed --class=PagesSeeder

════════════════════════════════════════════════════════
## 🔴 TDD GATE 04 — ALL MUST GREEN BEFORE WF-05
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-05 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/PagePublicTest.php
        EXPECTED: 6 tests GREEN

  [ ] php artisan test tests/Feature/Admin/PageAdminTest.php
        EXPECTED: 7 tests GREEN

  [ ] npm run test -- tests/stores/pageStore.test.js
  [ ] npm run test -- tests/stores/sectionStore.test.js
  [ ] npm run test -- tests/views/AdminSections.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests still green (WF-00 to WF-04)

  MANUAL:
  [ ] GET /api/v1/pages/home returns JSON with sections
  [ ] Accept-Language:ar returns Arabic title
  [ ] Admin edits hero title → saved → public API returns new title
  [ ] Section toggle off → not in public API response
  [ ] Reorder drag works and persists

  ALL GREEN → ✅ PROCEED TO WF-05
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Section content not updating:
    Check content column cast to array in model
    Check controller: $section->update(['content'=>$request->content])
    Content must be sent as JSON body, not form-data

  Reorder route conflicts with {id}:
    Register: Route::patch('/sections/reorder',...) BEFORE apiResource
    'reorder' literal must not be confused with an {id}

  Accept-Language test fails:
    Check SetLocale middleware on api.php v1 group
    Controller reads: $locale = $request->header('Accept-Language','en')

  Sections not ordered:
    Check model scopeOrdered: orderBy('order','asc')
    Check controller calls ->ordered() on query

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-05
════════════════════════════════════════════════════════
  [ ] All page/section tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-04)
  [ ] 5 pages seeded with their sections
  [ ] git commit -m "feat: pages and sections module"
  [ ] NEXT → 05_services_module.md
