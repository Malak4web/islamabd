---
description: w11
---

# WORKFLOW 11 — Admin Dashboard Frontend
# Gate: ALL admin dashboard tests green before WF-12
# Prerequisite: WF-10 gate fully green
════════════════════════════════════════════════════════

## Purpose
Complete Vue.js admin dashboard: sidebar layout, stats overview,
and management pages for all content types. Dark premium theme
with smooth interactions throughout.

## Admin Routes
  /admin/login           AdminLogin.vue        (guest only)
  /admin                 AdminDashboard.vue    (auth required)
  /admin/settings        AdminSettings.vue     (auth required)
  /admin/pages           AdminPages.vue        (auth required)
  /admin/sections/:slug  AdminSections.vue     (auth required)
  /admin/services        AdminServices.vue     (auth required)
  /admin/projects        AdminProjects.vue     (auth required)
  /admin/contacts        AdminContacts.vue     (auth required)
  /admin/code-injection  AdminCodeInjection.vue(auth required)
  /admin/media           AdminMedia.vue        (auth required)

════════════════════════════════════════════════════════
## STEP 1 — Admin Layout Shell
════════════════════════════════════════════════════════
  AdminLayout.vue (wraps all admin pages):
    Sidebar: fixed left, 260px (collapses to icons on mobile)
    Top bar: fixed top, full width minus sidebar
    Content: scrollable <RouterView /> in main area

  AdminSidebar.vue nav items (icon + label + active highlight):
    🏠 Dashboard    | ⚙️ Settings     | 📄 Pages
    🛠️ Services     | 🏗️ Projects     | 📬 Contacts (red badge: new count)
    💻 Code Injection | 🖼️ Media
    Bottom: admin avatar + name + Logout button

  AdminTopBar.vue:
    Left: current page breadcrumb
    Right: new contacts bell icon, locale toggle, admin name

  Admin Design Tokens:
    Background:   slate-900 / slate-800
    Sidebar:      slate-950
    Cards:        slate-800 border border-slate-700
    Text:         slate-100 / slate-400
    Brand accent: amber-500 / amber-400 (InDesign gold)
    Success: emerald-500 | Error: red-500

════════════════════════════════════════════════════════
## STEP 2 — AdminDashboard.vue
════════════════════════════════════════════════════════
  4 stat cards (StatCard.vue):
    📬 New Messages (red count) → links to /admin/contacts?status=new
    🏗️ Total Projects            → links to /admin/projects
    🛠️ Active Services           → links to /admin/services
    🖼️ Media Files               → links to /admin/media

  Quick Actions bar:
    [Add Project] [Add Service] [View Contacts] [Code Injection]

  Recent Contacts table:
    Last 5 contacts: name | phone | service | date | status badge
    "View All" → /admin/contacts

  Data: dashboardStore.fetchStats() → GET /api/admin/dashboard
  Dashboard API returns: new_contacts_count, total_projects,
                         active_services, recent_contacts

════════════════════════════════════════════════════════
## STEP 3 — AdminServices.vue
════════════════════════════════════════════════════════
  Toolbar: Search input | "Add New Service" button
  Draggable list (drag handle ≡):
    Row: icon thumb | title_en | title_ar | active badge | order | actions
    On drop: store.reorderServices(newOrderIds)

  Row actions: Edit | Toggle (eye) | Delete (trash + confirm)

  ServiceFormModal.vue (tabbed):
    Tab EN: title_en, description_en, order, is_active
    Tab AR: title_ar (dir=rtl), description_ar (dir=rtl)
    Tab Media: icon upload (preview), image upload (preview)
    Save: loading spinner + success toast

════════════════════════════════════════════════════════
## STEP 4 — AdminProjects.vue
════════════════════════════════════════════════════════
  Toolbar: Category select | Search | Add New | Grid/List toggle

  Grid cards: cover thumb, title, category badge, ⭐ featured, active badge
  List rows: denser table layout

  Row actions: Edit | Feature/Unfeature (star) | Toggle (eye) | Delete

  ProjectFormModal.vue (4 tabs):
    EN:     title_en, description_en
    AR:     title_ar, description_ar (dir=rtl)
    Options: category select, is_featured toggle, is_active toggle, order
    Media:  cover upload zone + gallery multi-upload
            Gallery shows image thumbnails grid with ✕ remove per image
            File upload progress bar

════════════════════════════════════════════════════════
## STEP 5 — AdminContacts.vue (Inbox)
════════════════════════════════════════════════════════
  Filter tabs: All | 🔴 New (N) | Read | 🟢 Replied
  Table columns: ☐ | Name | Phone | Service | Date | Status | Actions
  Status badges:
    NEW    → red pill with pulse dot
    READ   → slate gray pill
    REPLIED→ emerald green pill

  Row actions: View (modal) | Mark Read | Mark Replied | Delete

  ContactDetailModal.vue:
    Shows: name, phone, email, service, full message, date, status
    Actions: Mark as Read | Mark as Replied | Delete
    Phone + email: click-to-copy (clipboard API) with toast

  Bulk operations:
    ☐ Select all header checkbox
    Selecting rows shows "Delete Selected (N)" bar at bottom
    DELETE /api/admin/contacts/bulk

  Pagination: 15/page, prev/next

════════════════════════════════════════════════════════
## STEP 6 — AdminMedia.vue (Library)
════════════════════════════════════════════════════════
  Upload zone:
    Large drag-drop area: "Drop images here or click to browse"
    Supports: jpg, png, webp, svg | Max: 5MB per file | Multi-file
    Progress bar per file during upload
    POST /api/admin/media

  Media grid:
    All files as thumbnails (3-6 cols responsive)
    Hover overlay: filename + 📋 copy URL + 🗑️ delete
    Click thumbnail → copies URL to clipboard + toast
    DELETE /api/admin/media/{id}

  Filter bar: All | Images | SVG | Recent

════════════════════════════════════════════════════════
## STEP 7 — Shared Admin Components
════════════════════════════════════════════════════════
  ConfirmModal.vue:
    Props: title, message, confirmLabel, onConfirm
    Reused by all delete actions across all admin pages

  ToastNotification.vue:
    Auto-dismiss after 3s
    Types: success (emerald), error (red), info (blue)
    Stacks at bottom-right

  ImageUpload.vue:
    Reusable drag-drop upload with preview
    Props: value (current URL), endpoint, collection
    Emits: uploaded (new URL)

  StatCard.vue:
    Props: icon, title, value, link, color
    Consistent stat tile for dashboard

  composables/useToast.js:
    const { toast } = useToast()
    toast.success('Saved!') | toast.error('Failed!') | toast.info('...')

════════════════════════════════════════════════════════
## STEP 8 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/views/admin/AdminDashboard.test.js:
    test_renders_4_stat_cards
    test_fetches_dashboard_stats_on_mount
    test_new_contacts_count_shows_in_contacts_card
    test_recent_contacts_table_renders_rows

  tests/views/admin/AdminServices.test.js:
    test_renders_services_from_store
    test_add_new_button_opens_modal
    test_modal_has_en_and_ar_tabs
    test_save_calls_createService
    test_toggle_calls_toggleService
    test_delete_shows_confirm_modal

  tests/views/admin/AdminProjects.test.js:
    test_renders_project_grid
    test_category_filter_calls_store
    test_feature_star_calls_featureProject
    test_gallery_tab_has_upload_zone

  tests/views/admin/AdminContacts.test.js:
    test_renders_contacts_table
    test_filter_tabs_show_counts
    test_mark_read_calls_store_markAsRead
    test_bulk_select_enables_bulk_delete_bar

  tests/components/admin/ConfirmModal.test.js:
    test_renders_title_and_message_props
    test_confirm_calls_onConfirm_prop
    test_cancel_closes_modal_without_action

  tests/components/admin/ToastNotification.test.js:
    test_renders_success_toast_with_emerald_style
    test_renders_error_toast_with_red_style
    test_auto_dismisses_after_3_seconds

════════════════════════════════════════════════════════
## 🔴 TDD GATE 11 — ALL MUST GREEN BEFORE WF-12
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-12 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] npm run test -- tests/views/admin/AdminDashboard.test.js
  [ ] npm run test -- tests/views/admin/AdminServices.test.js
  [ ] npm run test -- tests/views/admin/AdminProjects.test.js
  [ ] npm run test -- tests/views/admin/AdminContacts.test.js
  [ ] npm run test -- tests/components/admin/ConfirmModal.test.js
  [ ] npm run test -- tests/components/admin/ToastNotification.test.js
        EXPECTED: All GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests green (WF-00 to WF-11)

  MANUAL VISUAL:
  [ ] Login → dashboard, stat cards show correct counts
  [ ] Sidebar navigation highlights current page
  [ ] Mobile sidebar collapses to icon-only
  [ ] Drag-drop service reorder saves new order
  [ ] Service modal: EN + AR tabs with correct input directions
  [ ] Project modal gallery: multi-upload + ✕ remove per image
  [ ] Contacts: colored status badges (red/gray/green)
  [ ] Bulk delete: select + delete removes contacts
  [ ] Contact modal: click phone → clipboard copy
  [ ] Media library: drag-drop upload, grid, copy URL
  [ ] Toast appears and auto-dismisses after 3s
  [ ] Confirm modal blocks accidental delete

  ALL GREEN → ✅ PROCEED TO WF-12
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  Drag-drop reorder not saving:
    Collect new order of IDs after drop event
    Call store.reorderServices(newOrderIds)
    Update local state optimistically before API call

  Gallery upload preview not showing:
    Use FileReader API for local preview BEFORE API upload
    reader.onload = (e) => previews.value.push(e.target.result)
    Don't wait for API to show preview

  Toast auto-dismiss test fails:
    vi.useFakeTimers() before triggering toast
    vi.advanceTimersByTime(3000)
    Assert toast is no longer visible

  Bulk select not working:
    Use Set() for selectedIds
    Select all: new Set(contacts.map(c => c.id))
    Individual: toggle in/out of Set
    computed: hasBulkSelection = selectedIds.size > 0

  Dashboard stats 404:
    Check GET /api/admin/dashboard route exists
    Check DashboardController returns new_contacts_count etc.

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-12
════════════════════════════════════════════════════════
  [ ] All admin dashboard tests GREEN
  [ ] Regression suite GREEN (WF-00 through WF-11)
  [ ] Visual review of full admin dashboard
  [ ] All CRUD operations working in browser
  [ ] git commit -m "feat: complete admin dashboard"
  [ ] NEXT → 12_final_qa_and_revision.md
