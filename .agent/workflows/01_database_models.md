---
description: w2
---

# WORKFLOW 01 — Database Migrations & Models
# Gate: ALL model tests green before WF-02
# Prerequisite: WF-00 gate fully green
════════════════════════════════════════════════════════

## Purpose
Create all migrations, Eloquent models, factories, and unit tests.
TDD order per table: write test (RED) → migration → model → factory → GREEN.

## Tables (dependency order)
  admins | settings | pages | sections | services
  projects | contacts | code_injections
  (media table auto-created by Spatie MediaLibrary)

════════════════════════════════════════════════════════
## TDD ORDER PER TABLE
  1. Write unit test → Run → RED ❌
  2. Create migration → Run → still RED ❌
  3. Create model + factory → Run → GREEN ✅
  4. Move to next table
════════════════════════════════════════════════════════

════════════════════════════════════════════════════════
## TABLE 1 — admins
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/AdminTest.php
    test_admin_has_correct_fillable_fields
      Assert fillable contains: name, email, password
    test_admin_password_is_hidden
      Assert hidden contains: password, remember_token
    test_admin_factory_creates_valid_instance
      Assert Admin::factory()->create() returns Admin

  MIGRATION columns:
    id, name (string), email (string unique), password (string),
    remember_token (nullable), email_verified_at (nullable), timestamps

  MODEL: HasFactory, Notifiable
    fillable: [name, email, password]
    hidden: [password, remember_token]
    casts: [email_verified_at=>datetime, password=>hashed]

  FACTORY: name=faker->name, email=faker->unique->safeEmail,
           password=Hash::make('password')

════════════════════════════════════════════════════════
## TABLE 2 — settings
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/SettingTest.php
    test_setting_has_correct_fillable_fields
      Assert fillable: key, value, group, type
    test_setting_key_must_be_unique
      Assert duplicate key throws QueryException
    test_setting_get_helper_returns_value_by_key
      Create key='phone', value='+971...'
      Assert Setting::get('phone') === '+971...'
    test_setting_get_returns_default_when_key_missing
      Assert Setting::get('missing','fallback') === 'fallback'
    test_setting_set_creates_or_updates
      Setting::set('email','test@test.com')
      Assert DB has key='email'
      Setting::set('email','new@test.com')
      Assert DB has value='new@test.com' (not duplicate)

  MIGRATION columns:
    id, key (string unique), value (text nullable),
    group (string default 'general'),
    type (enum: text,textarea,image,boolean default text), timestamps

  MODEL: static get($key, $default=null), static set($key, $value)
    get → Setting::where('key',$key)->value('value') ?? $default
    set → Setting::updateOrCreate(['key'=>$key],['value'=>$value])

════════════════════════════════════════════════════════
## TABLE 3 — pages
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/PageTest.php
    test_page_has_correct_fillable_fields
      Assert: slug, title_en, title_ar, meta_title, meta_description, og_image
    test_page_slug_is_unique
      Assert duplicate slug throws QueryException
    test_page_has_many_sections
      Create Page + 3 Sections with page_id
      Assert page->sections->count() === 3
    test_page_sections_ordered_by_order_column
      Create sections with order 3,1,2
      Assert page->sections->first()->order === 1

  MIGRATION columns:
    id, slug (string unique), title_en, title_ar,
    meta_title (nullable), meta_description (text nullable),
    og_image (nullable), timestamps

  MODEL: hasMany Sections, Factory

════════════════════════════════════════════════════════
## TABLE 4 — sections
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/SectionTest.php
    test_section_has_correct_fillable_fields
      Assert: page_id, key, content, order, is_active
    test_section_content_cast_to_array
      Create with JSON string content
      Assert section->content is array
    test_section_belongs_to_page
      Assert section->page returns Page instance
    test_scope_active_filters_correctly
      2 active, 1 inactive → Section::active()->count() === 2
    test_scope_ordered_sorts_ascending
      order 3,1,2 → Section::ordered()->first()->order === 1

  MIGRATION columns:
    id, page_id (FK→pages), key (string),
    content (json nullable), order (int default 0),
    is_active (boolean default true), timestamps
    Unique: [page_id, key]

  MODEL: casts content→array, belongsTo Page, scopes: active, ordered

════════════════════════════════════════════════════════
## TABLE 5 — services
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/ServiceTest.php
    test_service_has_correct_fillable_fields
      Assert: title_en, title_ar, description_en, description_ar,
              icon, image, order, is_active
    test_is_active_cast_to_boolean
      Assert is_active is bool after create(['is_active'=>1])
    test_scope_active_filters_correctly
      3 active, 2 inactive → active()->count() === 3
    test_scope_ordered_sorts_ascending
      order 3,1,2 → ordered()->first()->order === 1
    test_factory_creates_bilingual_fields
      Assert title_en not empty, title_ar not empty

  MIGRATION columns:
    id, title_en, title_ar, description_en (text),
    description_ar (text), icon (nullable), image (nullable),
    order (int default 0), is_active (boolean default true), timestamps

  MODEL: scopes active/ordered, HasMedia (Spatie), Factory

════════════════════════════════════════════════════════
## TABLE 6 — projects
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/ProjectTest.php
    test_project_has_correct_fillable_fields
      Assert: title_en, title_ar, category, description_en,
              description_ar, cover_image, gallery, is_featured,
              is_active, order
    test_gallery_cast_to_array
      Create ['gallery'=>['a.jpg','b.jpg']]
      Assert project->gallery is array, count === 2
    test_scope_featured_returns_featured_only
      2 featured, 3 not → featured()->count() === 2
    test_scope_by_category_filters
      3 residential, 2 commercial
      byCategory('residential')->count() === 3
    test_scope_active_and_ordered
      active order 2, active order 1, inactive order 3
      active()->ordered()->first()->order === 1

  MIGRATION columns:
    id, title_en, title_ar, category (string),
    description_en (text), description_ar (text),
    cover_image (nullable), gallery (json nullable),
    is_featured (boolean default false),
    is_active (boolean default true), order (int default 0), timestamps

  MODEL: casts gallery→array, scopes: active, ordered, featured, byCategory

════════════════════════════════════════════════════════
## TABLE 7 — contacts
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/ContactTest.php
    test_contact_has_correct_fillable_fields
      Assert: name, phone, email, service, message, status
    test_default_status_is_new
      contact = Contact::factory()->create()
      Assert contact->status === 'new'
    test_scope_unread_returns_new_status_only
      2 new, 2 read → unread()->count() === 2
    test_mark_as_read_method_updates_status
      contact->markAsRead()
      Assert contact->fresh()->status === 'read'

  MIGRATION columns:
    id, name (string), phone (string), email (nullable),
    service (nullable), message (text),
    status (enum: new,read,replied default new), timestamps

  MODEL: markAsRead() method, scopes: unread, byStatus

════════════════════════════════════════════════════════
## TABLE 8 — code_injections
════════════════════════════════════════════════════════
  TEST FILE: tests/Unit/Models/CodeInjectionTest.php
    test_has_correct_fillable_fields
      Assert: name, code, location, is_active, pages
    test_pages_cast_to_array
      Create ['pages'=>['home','about']]
      Assert pages is array, count === 2
    test_null_pages_means_all_pages
      Create ['pages'=>null]
      Assert pages === null
    test_scope_active_returns_active_only
      3 active, 1 inactive → active()->count() === 3
    test_scope_for_page_returns_all_pages_plus_specific
      Create inj pages=null (all)
      Create inj pages=['home']
      Create inj pages=['about']
      forPage('home')->count() === 2

  MIGRATION columns:
    id, name (string), code (text),
    location (enum: head,body_start,body_end default head),
    is_active (boolean default true), pages (json nullable), timestamps

  MODEL: forPage scope: whereNull('pages')->orWhereJsonContains('pages',$slug)

════════════════════════════════════════════════════════
## STEP 9 — Seeders & Migration Check
════════════════════════════════════════════════════════
  php artisan migrate:fresh
  Expected: All 8+ tables created, no errors

  php artisan make:seeder DatabaseSeeder
  Seeds: AdminSeeder, SettingsSeeder, PagesSeeder, ServicesSeeder, ProjectsSeeder
  php artisan db:seed

════════════════════════════════════════════════════════
## 🔴 TDD GATE 01 — ALL MUST GREEN BEFORE WF-02
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-02 UNTIL ALL CHECKS ARE GREEN ⛔

  COMMAND: php artisan test tests/Unit/Models/ --env=testing
  EXPECTED: 30+ tests passed, 0 failed

  INDIVIDUAL FILES:
  [ ] AdminTest.php         — GREEN ✅
  [ ] SettingTest.php       — GREEN ✅
  [ ] PageTest.php          — GREEN ✅
  [ ] SectionTest.php       — GREEN ✅
  [ ] ServiceTest.php       — GREEN ✅
  [ ] ProjectTest.php       — GREEN ✅
  [ ] ContactTest.php       — GREEN ✅
  [ ] CodeInjectionTest.php — GREEN ✅
  [ ] php artisan migrate:fresh — no errors
  [ ] php artisan db:seed       — no errors
  [ ] Full regression: php artisan test --env=testing — ALL green

  ALL GREEN → ✅ PROCEED TO WF-02
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  "Class not found" error:
    composer dump-autoload
    Check model namespace: App\Models\ModelName
    Check file exists in app/Models/

  Migration fails "Column already exists":
    php artisan migrate:fresh --env=testing
    Never manually edit test SQLite DB

  Factory fails "Call to undefined method":
    Check factory extends Factory
    Check definition() method exists
    Check model's newFactory() if custom factory path

  Relationship test fails:
    Check FK column name: page_id not pages_id
    Check hasMany/belongsTo direction
    Check migration FK constraint references correct table

  Scope test fails:
    Scope named scopeActive → called as ::active()
    Check WHERE clause targets correct column
    Check Builder return type

  RULE: After every fix, re-run the failing test file first,
  then re-run the FULL GATE command.

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-02
════════════════════════════════════════════════════════
  [ ] All 8 model test files GREEN (30+ tests)
  [ ] migrate:fresh clean, db:seed clean
  [ ] Full regression suite GREEN
  [ ] git commit -m "feat: database migrations and models with unit tests"
  [ ] NEXT → 02_authentication_module.md
