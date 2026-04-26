---
description: w7
---

# WORKFLOW 07 — Contacts Module
# Gate: ALL contacts tests green before WF-08
# Prerequisite: WF-06 gate fully green
════════════════════════════════════════════════════════

## Purpose
Contact form submission system. Visitors submit a form stored in DB.
Admin manages an inbox with status tracking (new/read/replied),
bulk operations, export, and rate limiting to prevent spam.

════════════════════════════════════════════════════════
## STEP 1 — Public Form API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Api/ContactPublicTest.php

  test_visitor_can_submit_contact_form_successfully
    POST /api/v1/contacts { name:'Sara', phone:'+971501234567', message:'Hello world' }
    assertCreated()
    assertJsonStructure([message, data=>[id,name,created_at]])
    assertDatabaseHas('contacts',['phone'=>'+971501234567','status'=>'new'])

  test_contact_form_requires_name
    POST { phone, message } → assertUnprocessable() | assertJsonValidationErrors(['name'])

  test_contact_form_requires_message
    POST { name, phone } → assertUnprocessable() | assertJsonValidationErrors(['message'])

  test_contact_form_requires_phone
    POST { name, message } → assertUnprocessable() | assertJsonValidationErrors(['phone'])

  test_contact_name_max_255_characters
    POST { name:str_repeat('a',256), phone, message } → assertJsonValidationErrors(['name'])

  test_contact_message_minimum_10_characters
    POST { name, phone, message:'Hi' } → assertJsonValidationErrors(['message'])

  test_contact_optional_email_must_be_valid_format
    POST { name, phone, message:'Hello world', email:'not-valid' }
    assertJsonValidationErrors(['email'])

  test_contact_optional_fields_accepted
    POST { name, phone, email:'sara@example.com', service:'Residential', message:'Hello world' }
    assertCreated()
    assertDatabaseHas('contacts',['email'=>'sara@example.com','service'=>'Residential'])

  test_contact_form_is_rate_limited_after_5_attempts
    POST valid data 6× → 6th: assertTooManyRequests() (429)
    IMPORTANT: Do NOT use WithoutMiddleware trait on this test

  test_contact_default_status_is_new
    POST valid → assertDatabaseHas('contacts',['status'=>'new'])

════════════════════════════════════════════════════════
## STEP 2 — Public ContactController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Api/ContactController.php
    store(StoreContactRequest $request):
      $contact = Contact::create($request->validated() + ['status'=>'new'])
      return response()->json([
        'message' => 'Thank you! We will contact you shortly.',
        'data'    => ['id'=>$contact->id, 'name'=>$contact->name,
                      'created_at'=>$contact->created_at]
      ], 201)

  FILE: app/Http/Requests/StoreContactRequest.php
    rules:
      name:    required|string|max:255
      phone:   required|string|max:20
      email:   nullable|email|max:255
      service: nullable|string|max:255
      message: required|string|min:10|max:2000

  ROUTE:
    Route::post('/contacts', [Api\ContactController::class,'store'])
         ->middleware('throttle:5,1');

  Run: php artisan test tests/Feature/Api/ContactPublicTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 3 — Admin Inbox API Tests (write FIRST → RED ❌)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/ContactAdminTest.php

  test_unauthenticated_cannot_access_admin_contacts
    GET /api/admin/contacts → assertUnauthorized()

  test_admin_can_list_all_contacts_paginated
    25 contacts. GET /api/admin/contacts.
    assertJsonPath('meta.per_page',15) | assertJsonPath('meta.total',25)
    assertJsonCount(15,'data')

  test_admin_can_filter_by_status_new
    4 new + 3 read. GET /api/admin/contacts?status=new. assertJsonCount(4,'data')

  test_admin_can_filter_by_status_read
    GET /api/admin/contacts?status=read. assertJsonCount(3,'data')

  test_admin_can_view_single_contact
    GET /api/admin/contacts/{id}
    assertJsonStructure([data=>[id,name,phone,email,service,message,status,created_at]])

  test_admin_can_mark_contact_as_read
    contact status='new' → PATCH /api/admin/contacts/{id}/read
    assertOk() | Assert contact->fresh()->status === 'read'

  test_admin_can_mark_contact_as_replied
    contact status='read' → PATCH /api/admin/contacts/{id}/replied
    Assert contact->fresh()->status === 'replied'

  test_marking_one_does_not_affect_others
    c1=new, c2=new → mark c1 read → c2->fresh()->status === 'new'

  test_admin_can_delete_contact
    DELETE → assertNoContent() | assertDatabaseMissing('contacts',['id'=>id])

  test_admin_can_bulk_delete_contacts
    c1,c2,c3 → DELETE /api/admin/contacts/bulk {ids:[c1->id,c2->id]}
    assertDatabaseMissing(c1) | assertDatabaseMissing(c2)
    assertDatabaseHas(c3) ← NOT deleted

  test_dashboard_shows_new_contacts_count
    5 new + 3 read. GET /api/admin/dashboard.
    assertJsonPath('data.new_contacts_count',5)

════════════════════════════════════════════════════════
## STEP 4 — Admin ContactController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/ContactController.php
    index():         Contact::latest()->paginate(15) filter by ?status=
    show($id):       Contact::findOrFail($id)
    markRead($id):   contact->update(['status'=>'read'])
    markReplied($id):contact->update(['status'=>'replied'])
    destroy($id):    contact->delete()
    bulkDestroy():   Contact::whereIn('id',$request->ids)->delete()

  ROUTES:
    Route::get('/contacts',[Admin\ContactController::class,'index']);
    Route::get('/contacts/{id}',[Admin\ContactController::class,'show']);
    Route::patch('/contacts/{id}/read',[Admin\ContactController::class,'markRead']);
    Route::patch('/contacts/{id}/replied',[Admin\ContactController::class,'markReplied']);
    Route::delete('/contacts/{id}',[Admin\ContactController::class,'destroy']);
    Route::delete('/contacts/bulk',[Admin\ContactController::class,'bulkDestroy']);
    ← bulk route BEFORE {id} route to avoid conflict

  Run: php artisan test tests/Feature/Admin/ContactAdminTest.php → GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Pinia contactStore & Vue Components
════════════════════════════════════════════════════════
  contactStore actions:
    submitContact(data)      POST /api/v1/contacts
    fetchAdminContacts(f)    GET /api/admin/contacts?status=&page=
    fetchContact(id)         GET /api/admin/contacts/{id}
    markAsRead(id)           PATCH /api/admin/contacts/{id}/read
    markAsReplied(id)        PATCH /api/admin/contacts/{id}/replied
    deleteContact(id)        DELETE /api/admin/contacts/{id}
    bulkDelete(ids)          DELETE /api/admin/contacts/bulk

  ContactForm.vue (public):
    Fields: name*, phone*, email (opt), service select (opt), message* (min 10)
    Client validation (Vuelidate) before submit
    Submit button: loading spinner while awaiting API
    Success state: thank-you card replaces form
    Error state: field-level error messages
    Labels localized via {{ $t('contact.name') }}

  ContactView.vue (public):
    Left: ContactInfo.vue (phone, email, address, map embed)
    Right: ContactForm.vue | Social links bar

  AdminContacts.vue (dashboard):
    Filter tabs: All | 🔴 New (N) | Read | 🟢 Replied
    Table: ☐ | name | phone | service | date | status badge | actions
    Status badges: NEW=red pill+pulse, READ=slate, REPLIED=emerald
    Row actions: View (modal) | Mark Read | Mark Replied | Delete
    Bulk: select all checkbox → "Delete Selected (N)" bar
    ContactDetailModal: all fields, phone+email click-to-copy

════════════════════════════════════════════════════════
## STEP 6 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/contactStore.test.js:
    test_submitContact_sends_post_to_public_api
    test_submitContact_sets_success_state_on_200
    test_submitContact_sets_field_errors_on_422
    test_markAsRead_updates_status_in_local_state
    test_bulkDelete_removes_contacts_from_array

  tests/views/AdminContacts.test.js:
    test_renders_contacts_table_with_rows
    test_filter_tabs_call_store_with_correct_status
    test_new_badge_shows_red_styling
    test_click_row_opens_detail_modal
    test_bulk_select_enables_bulk_delete_bar

  tests/components/ContactForm.test.js:
    test_renders_all_required_fields
    test_shows_error_when_name_empty_on_submit
    test_shows_error_when_message_too_short
    test_submit_button_loading_while_submitting
    test_shows_success_message_after_submit
    test_form_resets_after_successful_submit

════════════════════════════════════════════════════════
## 🔴 TDD GATE 07 — ALL MUST GREEN BEFORE WF-08
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-08 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Api/ContactPublicTest.php
        EXPECTED: 10 tests GREEN

  [ ] php artisan test tests/Feature/Admin/ContactAdminTest.php
        EXPECTED: 11 tests GREEN

  [ ] npm run test -- tests/stores/contactStore.test.js
  [ ] npm run test -- tests/views/AdminContacts.test.js
  [ ] npm run test -- tests/components/ContactForm.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests green (WF-00 to WF-07)

  MANUAL:
  [ ] Form submits → record appears in admin inbox with status NEW
  [ ] Submit missing name → error on name field shown
  [ ] 6th submit from same IP → 429 returned
  [ ] Status filter tabs work (New/Read/Replied)
  [ ] Mark as read changes badge from red to gray
  [ ] Bulk select + delete removes selected contacts
  [ ] Dashboard shows correct "New Messages" count
  [ ] Thank-you card shown after successful submit

  ALL GREEN → ✅ PROCEED TO WF-08
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Rate limit test fails (429 not returned):
    Do NOT use WithoutMiddleware in rate limit test
    Other tests can use WithoutMiddleware if needed
    In testing env check throttle middleware is active

  Bulk delete conflicts with /{id}:
    Route::delete('/contacts/bulk',...) MUST be BEFORE Route::delete('/{id}',...)
    Or use POST /contacts/bulk-delete instead of DELETE

  Dashboard count wrong:
    DashboardController: Contact::where('status','new')->count()
    Response key: 'data.new_contacts_count'

  ContactForm validation test fails:
    Vuelidate rules must match server rules (min:10 for message)
    data-error attribute on error elements for test queries
    await nextTick() after trigger('submit') before asserting

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-08
════════════════════════════════════════════════════════
  [ ] All contacts tests GREEN (backend + frontend)
  [ ] Regression suite GREEN (WF-00 through WF-07)
  [ ] Manual form + inbox verified
  [ ] git commit -m "feat: contacts module with form, inbox, and rate limiting"
  [ ] NEXT → 08_code_injection_module.md
