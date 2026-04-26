---
description: w6
---

# WORKFLOW 06 — Projects Module
# Gate: ALL projects tests green before WF-07
# Prerequisite: WF-05 gate fully green
════════════════════════════════════════════════════════

## Purpose
Portfolio/projects module with categories, multi-image galleries,
featured flags, pagination, bilingual content. Public gallery with
category filter. Admin CRUD with gallery management and reorder.

## Project Categories
  residential | commercial | hospitality | landscape | retail

════════════════════════════════════════════════════════
## STEP 1 — Public API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/ProjectPublicTest.php

  test_can_get_list_of_active_projects
    5 active + 2 inactive. GET /api/v1/projects. assertJsonCount(5,'data')

  test_inactive_projects_excluded
    assertJsonMissing(['is_active'=>false])

  test_can_filter_projects_by_category
    3 residential + 2 commercial. GET /api/v1/projects?category=residential
    assertJsonCount(3,'data')

  test_can_filter_featured_projects
    2 featured + 3 not. GET /api/v1/projects?featured=1
    assertJsonCount(2,'data')

  test_projects_returned_in_order
    order 3,1 → GET /api/v1/projects → data[0].order === 1

  test_projects_response_structure
    assertJsonStructure([data=>[['id','title','category','cover_image','is_featured','order']]])

  test_can_get_single_project_with_gallery
    Create project ['gallery'=>['img1.jpg','img2.jpg','img3.jpg']]
    GET /api/v1/projects/{id}
    assertJsonCount(3,'data.gallery')
    assertJsonStructure([data=>[id,title,category,description,cover_image,gallery]])

  test_inactive_project_returns_404
    project is_active=false → GET /api/v1/projects/{id} → assertNotFound()

  test_projects_localized_by_accept_language
    ['title_en'=>'Palm Villa','title_ar'=>'فيلا النخيل']
    GET /api/v1/projects (Accept-Language:ar) → data[0].title === 'فيلا النخيل'

  test_projects_support_pagination
    15 active projects. GET /api/v1/projects?per_page=9
    assertJsonCount(9,'data')
    assertJsonStructure([meta=>[total,per_page,current_page,last_page]])
    assertJsonPath('meta.total',15)

════════════════════════════════════════════════════════
## STEP 2 — Public ProjectController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/ProjectController.php
    index():
      $q = Project::active()->ordered()
      if $request->category: $q->byCategory($request->category)
      if $request->featured: $q->featured()
      $perPage = min($request->per_page ?? 9, 24)
      $result = $q->paginate($perPage)
      return ['data'=>mapped+localized, 'meta'=>[total,per_page,current_page,last_page]]

    show($id):
      $project = Project::active()->findOrFail($id)
      return localized project with gallery array

  ROUTES:
    Route::get('/projects', [Api\ProjectController::class,'index']);
    Route::get('/projects/{id}', [Api\ProjectController::class,'show']);

  Run: php artisan test tests/Feature/Api/ProjectPublicTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin CRUD Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/ProjectAdminTest.php

  test_unauthenticated_cannot_manage_projects
    GET /api/admin/projects → assertUnauthorized()

  test_admin_can_list_all_projects_including_inactive
    4 active + 2 inactive. assertJsonCount(6,'data') ← admin sees ALL

  test_admin_can_create_project
    POST /api/admin/projects {
      title_en:'Dubai Marina Penthouse', title_ar:'بنتهاوس دبي مارينا',
      category:'residential', description_en:'...', description_ar:'...',
      is_featured:true, is_active:true, order:1
    }
    assertCreated()
    assertDatabaseHas('projects',['title_en'=>'Dubai Marina Penthouse'])

  test_create_requires_title_en_and_title_ar
    POST {} → assertJsonValidationErrors(['title_en','title_ar'])

  test_create_requires_valid_category
    POST { category:'invalid' } → assertJsonValidationErrors(['category'])
    Valid: residential|commercial|hospitality|landscape|retail

  test_admin_can_update_project
    PUT /api/admin/projects/{id} { title_en:'Updated', category:'commercial' }
    assertDatabaseHas('projects',['category'=>'commercial'])

  test_admin_can_upload_cover_image
    POST /api/admin/projects/{id}/cover { file:fake()->image('cover.jpg',1920,1080) }
    assertOk() | assertJsonStructure([data=>[url]])
    Assert project->fresh()->cover_image not null

  test_admin_can_upload_gallery_images
    project gallery=[]
    POST /api/admin/projects/{id}/gallery { images:[fake()->image(),fake()->image()] }
    Assert count(project->fresh()->gallery) === 2

  test_admin_can_remove_gallery_image
    project gallery=['img1.jpg','img2.jpg','img3.jpg']
    DELETE /api/admin/projects/{id}/gallery { image:'img2.jpg' }
    Assert count(project->fresh()->gallery) === 2
    assertNotContains('img2.jpg', project->fresh()->gallery)

  test_admin_can_toggle_featured
    project is_featured=false → PATCH /api/admin/projects/{id}/feature
    Assert project->fresh()->is_featured === true

  test_admin_can_toggle_active
    project is_active=true → PATCH /api/admin/projects/{id}/toggle
    Assert project->fresh()->is_active === false

  test_admin_can_delete_project
    DELETE → assertNoContent() | assertDatabaseMissing('projects',['id'=>id])

  test_admin_can_reorder_projects
    p1=1,p2=2,p3=3 → PATCH reorder { order:[p3->id,p1->id,p2->id] }
    Assert p3->fresh()->order===1

════════════════════════════════════════════════════════
## STEP 4 — Admin ProjectController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/ProjectController.php
    index():       Project::orderBy('order')->get()
    store():       Validate + create
    update($id):   Validate + update
    destroy($id):  delete
    toggle($id):   flip is_active
    feature($id):  flip is_featured
    reorder():     loop IDs, update order column
    uploadCover():  store file → project->update(['cover_image'=>$path])
    uploadGallery():store files → merge into gallery array → update
    removeGallery():filter gallery array → remove image → update

  ROUTES in admin group (reorder/feature/toggle BEFORE apiResource):
    Route::patch('/projects/reorder',[Admin\ProjectController::class,'reorder']);
    Route::apiResource('/projects', Admin\ProjectController::class);
    Route::patch('/projects/{id}/toggle',[Admin\ProjectController::class,'toggle']);
    Route::patch('/projects/{id}/feature',[Admin\ProjectController::class,'feature']);
    Route::post('/projects/{id}/cover',[Admin\ProjectController::class,'uploadCover']);
    Route::post('/projects/{id}/gallery',[Admin\ProjectController::class,'uploadGallery']);
    Route::delete('/projects/{id}/gallery',[Admin\ProjectController::class,'removeGallery']);

  Run: php artisan test tests/Feature/Admin/ProjectAdminTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Pinia projectStore & Vue Components
════════════════════════════════════════════════════════
  projectStore actions:
    fetchProjects(filters)    | fetchProject(id)
    fetchAdminProjects(f)     | createProject(data)
    updateProject(id,data)    | deleteProject(id)
    toggleProject(id)         | featureProject(id)
    reorderProjects(ids)      | uploadCover(id,file)
    uploadGallery(id,files)   | removeGalleryImage(id,img)

  ProjectsView.vue:
    CategoryFilter.vue tabs: All|Residential|Commercial|Hospitality|Retail
    Masonry grid of ProjectCard.vue (paginated, "Load More" btn)
    URL-based category: ?category=residential (shareable links)
    Loading skeleton while fetching | "No projects" empty state

  ProjectCard.vue:
    Cover image fills card. Hover overlay: title + category badge
    Featured star badge if is_featured

  ProjectDetailView.vue:
    Cover hero, title, category, description (localized)
    ImageGallery.vue: main image + thumbnails, lightbox on click
    Lightbox: prev/next arrows, keyboard nav, swipe on touch
    Related projects (same category, 3 cards)

  AdminProjects.vue:
    Toolbar: category filter + search + "Add New"
    Grid/list toggle view
    ProjectFormModal.vue (4 tabs: EN | AR | Category+Options | Media)
    Media tab: cover upload + gallery multi-upload with ✕ per image

════════════════════════════════════════════════════════
## STEP 6 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/projectStore.test.js:
    test_fetchProjects_with_category_filter_sends_correct_params
    test_fetchProjects_returns_paginated_result
    test_uploadGallery_adds_images_to_project_gallery
    test_removeGalleryImage_removes_from_local_state

  tests/views/AdminProjects.test.js:
    test_renders_project_list_with_covers
    test_category_filter_calls_store_with_filter
    test_feature_star_calls_store_featureProject
    test_delete_shows_confirmation_first

  tests/components/ProjectCard.test.js:
    test_renders_title_and_cover
    test_shows_featured_badge_when_is_featured
    test_shows_category_badge
    test_navigates_to_detail_on_click

════════════════════════════════════════════════════════
## 🔴 TDD GATE 06 — ALL MUST GREEN BEFORE WF-07
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-07 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/ProjectPublicTest.php
        EXPECTED: 10 tests GREEN

  [ ] php artisan test tests/Feature/Admin/ProjectAdminTest.php
        EXPECTED: 13 tests GREEN

  [ ] npm run test -- tests/stores/projectStore.test.js
  [ ] npm run test -- tests/views/AdminProjects.test.js
  [ ] npm run test -- tests/components/ProjectCard.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests still green (WF-00 to WF-06)

  MANUAL:
  [ ] Public projects list with category filter working
  [ ] Pagination returns correct meta (total, last_page)
  [ ] Featured filter works
  [ ] Single project shows gallery array
  [ ] Admin creates project with cover + gallery
  [ ] Admin removes individual gallery image
  [ ] Admin toggles featured status
  [ ] Lightbox opens on gallery image click
  [ ] URL ?category= updates and persists on share

  ALL GREEN → ✅ PROCEED TO WF-07
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Gallery not updating in DB:
    Check gallery cast to array in Project model
    Controller merges new uploads into existing array
    json_encode back before update

  Pagination meta missing:
    Use ->paginate($perPage) not ->get()
    Return: meta=[total=>$r->total(),per_page=>$r->perPage(),...]

  Category validation rejects valid value:
    Check Rule::in(['residential','commercial','hospitality','landscape','retail'])
    No typos in the allowed list

  Gallery upload not working in test:
    Use UploadedFile::fake()->image() in test
    $request->file('images') returns array
    Check max file size in validation vs php.ini

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-07
════════════════════════════════════════════════════════
  [ ] All projects tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-06)
  [ ] Sample projects seeded in each category
  [ ] git commit -m "feat: projects module with gallery and category filter"
  [ ] NEXT → 07_contacts_module.md
