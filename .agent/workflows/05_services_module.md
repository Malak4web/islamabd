---
description: w5
---

# WORKFLOW 05 — Services Module
# Gate: ALL services tests green before WF-06
# Prerequisite: WF-04 gate fully green
════════════════════════════════════════════════════════

## Purpose
Full CRUD for interior design services with bilingual content (EN/AR),
image + icon upload, drag-drop reorder, active toggle, and public API.
Includes ServicesView.vue, ServiceDetailView.vue, AdminServices.vue.

## Services List
  Residential Interior Design | Commercial Fit-Out
  Hospitality & Hotels | Retail & Showrooms
  Office Design | Landscape & Outdoor
  3D Visualization | Project Management

════════════════════════════════════════════════════════
## STEP 1 — Public API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/ServicePublicTest.php

  test_can_get_list_of_active_services
    4 active + 2 inactive. GET /api/v1/services. assertJsonCount(4,'data')

  test_services_returned_in_order
    order 3,1,2 → GET /api/v1/services → data[0].order === 1

  test_services_response_has_correct_structure
    assertJsonStructure([data=>[['id','title','description','icon','image','order']]])

  test_services_returns_localized_title_based_on_header
    Create ['title_en'=>'Residential','title_ar'=>'سكني']
    GET /api/v1/services (Accept-Language:ar) → data[0].title === 'سكني'
    GET /api/v1/services (Accept-Language:en) → data[0].title === 'Residential'

  test_can_get_single_active_service_by_id
    GET /api/v1/services/{id} → assertOk() | assertJsonPath('data.id',id)

  test_inactive_service_returns_404
    service is_active=false → GET /api/v1/services/{id} → assertNotFound()

  test_services_endpoint_is_publicly_accessible
    GET /api/v1/services (no auth) → assertOk() — NOT 401

════════════════════════════════════════════════════════
## STEP 2 — Public ServiceController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/ServiceController.php
    index():
      $locale = request()->header('Accept-Language','en')
      $services = Service::active()->ordered()->get()
      return mapped with title/description in correct locale

    show($id):
      $service = Service::active()->findOrFail($id)
      return localized service data

  ROUTES:
    Route::get('/services', [Api\ServiceController::class,'index']);
    Route::get('/services/{id}', [Api\ServiceController::class,'show']);

  Run: php artisan test tests/Feature/Api/ServicePublicTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin CRUD Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/ServiceAdminTest.php

  test_unauthenticated_cannot_access_admin_services
    GET /api/admin/services → assertUnauthorized()

  test_admin_can_list_all_services_including_inactive
    3 active + 2 inactive. actingAs. GET /api/admin/services
    assertJsonCount(5,'data') ← admin sees ALL

  test_admin_can_create_service
    POST /api/admin/services {
      title_en:'Residential Interior Design',
      title_ar:'تصميم داخلي سكني',
      description_en:'We transform living spaces.',
      description_ar:'نحول المساحات المعيشية.',
      order:1, is_active:true
    }
    assertCreated()
    assertDatabaseHas('services',['title_en'=>'Residential Interior Design'])

  test_create_requires_title_en_and_title_ar
    POST {} → assertUnprocessable()
    assertJsonValidationErrors(['title_en','title_ar'])

  test_admin_can_update_service
    PUT /api/admin/services/{id} { title_en:'Updated', title_ar:'محدث', ... }
    assertOk()
    assertDatabaseHas('services',['title_en'=>'Updated'])

  test_admin_can_toggle_service_active_status
    service is_active=true → PATCH /api/admin/services/{id}/toggle
    Assert service->fresh()->is_active === false

  test_admin_can_delete_service
    DELETE /api/admin/services/{id} → assertNoContent()
    assertDatabaseMissing('services',['id'=>id])

  test_admin_can_reorder_services
    s1=order1, s2=order2, s3=order3
    PATCH /api/admin/services/reorder { order:[s3->id,s1->id,s2->id] }
    Assert s3->fresh()->order===1, s1->fresh()->order===2

  test_admin_can_upload_service_image
    POST /api/admin/services/{id}/image { file:fake()->image('s.jpg') }
    assertOk() | assertJsonStructure([data=>[url]])

  test_admin_can_upload_service_icon
    POST /api/admin/services/{id}/icon { file:fake()->image('icon.svg') }
    assertOk() | assertJsonStructure([data=>[url]])

════════════════════════════════════════════════════════
## STEP 4 — Admin ServiceController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/ServiceController.php
    index():        Service::orderBy('order')->get()
    store():        Validate + Service::create($validated)
    update($id):    Validate + service->update($validated)
    destroy($id):   service->delete()
    toggle($id):    service->update(['is_active'=>!service->is_active])
    reorder(Req):   foreach order as i=>id: Service::find(id)?->update(['order'=>i+1])
    uploadImage($id): Store image → service->update(['image'=>$path])
    uploadIcon($id):  Store icon  → service->update(['icon'=>$path])

  ROUTES in admin group:
    Route::patch('/services/reorder',[Admin\ServiceController::class,'reorder']); ← FIRST
    Route::apiResource('/services', Admin\ServiceController::class);
    Route::patch('/services/{id}/toggle',[Admin\ServiceController::class,'toggle']);
    Route::post('/services/{id}/image',[Admin\ServiceController::class,'uploadImage']);
    Route::post('/services/{id}/icon',[Admin\ServiceController::class,'uploadIcon']);

  Run: php artisan test tests/Feature/Admin/ServiceAdminTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Pinia serviceStore
════════════════════════════════════════════════════════
  state: services[], isLoading, error
  actions:
    fetchServices()          GET /api/v1/services
    fetchAdminServices()     GET /api/admin/services
    createService(data)      POST /api/admin/services
    updateService(id,data)   PUT /api/admin/services/{id}
    deleteService(id)        DELETE /api/admin/services/{id}
    toggleService(id)        PATCH /api/admin/services/{id}/toggle
    reorderServices(ids)     PATCH /api/admin/services/reorder
    uploadImage(id,file)     POST /api/admin/services/{id}/image
    uploadIcon(id,file)      POST /api/admin/services/{id}/icon

════════════════════════════════════════════════════════
## STEP 6 — Vue Components (Public)
════════════════════════════════════════════════════════
  ServicesView.vue:
    Grid (3-2-1 cols responsive)
    onMounted: serviceStore.fetchServices()
    Renders ServiceCard.vue for each active service

  ServiceCard.vue:
    Props: service{}, compact (bool)
    Shows: icon, title (localized), description excerpt
    Hover: lift shadow, read more arrow
    Click: navigate to ServiceDetailView

  ServiceDetailView.vue:
    Large header image, title, full description
    CTA: "Get In Touch" → /contact

════════════════════════════════════════════════════════
## STEP 7 — AdminServices.vue (Dashboard)
════════════════════════════════════════════════════════
  Toolbar: Search input + "Add New Service" button
  Draggable list:
    Row: icon thumb | title_en | title_ar | active badge | order | actions
    Drag handle (≡) for reorder → on drop: reorderServices(newIds)
    After drop: PATCH /api/admin/services/reorder

  Actions: Edit → ServiceFormModal | Toggle (eye) | Delete (trash+confirm)

  ServiceFormModal.vue (tabbed):
    Tab EN: title_en, description_en
    Tab AR: title_ar (dir=rtl), description_ar (dir=rtl)
    Tab Media: icon upload + image upload (each with current preview)
    Fields: order input, is_active toggle
    Save: spinner + success toast

════════════════════════════════════════════════════════
## STEP 8 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/serviceStore.test.js:
    test_initial_services_is_empty_array
    test_fetchServices_populates_services
    test_createService_adds_to_services_array
    test_deleteService_removes_from_services_array
    test_reorderServices_sends_ids_to_api

  tests/views/AdminServices.test.js:
    test_renders_add_new_button
    test_renders_list_of_services
    test_click_add_new_opens_form_modal
    test_modal_has_en_and_ar_tabs
    test_delete_shows_confirm_modal
    test_toggle_calls_store_toggleService

  tests/components/ServiceCard.test.js:
    test_renders_service_title
    test_renders_service_icon_if_provided
    test_shows_fallback_when_no_icon
    test_emits_click_when_card_clicked

════════════════════════════════════════════════════════
## 🔴 TDD GATE 05 — ALL MUST GREEN BEFORE WF-06
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-06 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/ServicePublicTest.php
        EXPECTED: 7 tests GREEN

  [ ] php artisan test tests/Feature/Admin/ServiceAdminTest.php
        EXPECTED: 10 tests GREEN

  [ ] npm run test -- tests/stores/serviceStore.test.js
  [ ] npm run test -- tests/views/AdminServices.test.js
  [ ] npm run test -- tests/components/ServiceCard.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests still green (WF-00 to WF-05)

  MANUAL:
  [ ] Public /api/v1/services returns active services in order
  [ ] Language header changes title language
  [ ] Admin creates service with bilingual content
  [ ] Admin uploads image → visible in public API
  [ ] Admin reorders → public order changes
  [ ] Inactive service NOT in public API
  [ ] ServicesView.vue renders service cards
  [ ] ServiceDetailView.vue shows full content

  ALL GREEN → ✅ PROCEED TO WF-06
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Reorder route conflicts with {id}:
    Route::patch('/services/reorder',...) MUST be BEFORE apiResource
    'reorder' is treated as {id} if route registered after

  Admin sees only active (should see all):
    Admin controller: Service::orderBy('order')->get() ← NO active() scope
    Public controller: Service::active()->ordered()->get() ← WITH scope

  Localization returns wrong language:
    Check SetLocale middleware on api.php v1 group
    Controller reads header: $request->header('Accept-Language','en')

  Image upload: method not found:
    Check route is POST (not PUT)
    Check controller method name matches route action name

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-06
════════════════════════════════════════════════════════
  [ ] All services tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-05)
  [ ] 8 services seeded with bilingual content
  [ ] git commit -m "feat: services module with bilingual content and media"
  [ ] NEXT → 06_projects_module.md
