# Admin Panel Light Mode Redesign Survey & Mapping Analysis

**Explorer**: explorer_2  
**Target Area**: Admin Panel Vue Layouts, Components, and Views (`resources/js/{layouts,components/admin,views/admin}/**/*.vue`)  
**Date**: 2026-08-08  

---

## Executive Summary

A comprehensive audit was performed across all **19 Admin Panel Vue files** (1 layout, 8 components, and 10 views). The existing admin panel uses a dark slate/black visual architecture (`bg-slate-950`, `bg-slate-900`, `bg-[#0a0a0a]`, `bg-[#141414]`, `text-white`, `border-slate-800`, `border-[#222]`, and gold accents `#d4af37` / `bg-amber-500`).

To align with the brand redesign, a light mode mapping has been established using the target palette:
- **Canvas / Base Backgrounds**: `#F7F5F0` (light warm off-white)
- **Cards & Surface Backgrounds**: `#FFFFFF` (clean white)
- **Sub-elements, Pills & Secondary Surface**: `#F0ECE1` (warm taupe tint)
- **Primary Text & Headings**: `#111111` (crisp deep charcoal)
- **Body & Secondary Text**: `#222222` / `#555555` (neutral dark slate/gray)
- **Accents & Primary Buttons**: `#C5A880` (warm gold/taupe accent)
- **Borders & Dividers**: `#E0DACE` (subtle warm border)

---

## 1. Inventory of Admin Panel Files

| Category | File Path | Description |
|---|---|---|
| **Layouts** | `resources/js/layouts/AdminLayout.vue` | Root layout wrapper for admin panel |
| **Components** | `resources/js/components/admin/AdminSidebar.vue` | Collapsible sidebar navigation |
| | `resources/js/components/admin/AdminTopBar.vue` | Top bar with breadcrumbs, language switcher & links |
| | `resources/js/components/admin/ConfirmModal.vue` | Delete/action confirmation modal dialog |
| | `resources/js/components/admin/ContactDetailModal.vue` | Contact inquiry reader modal |
| | `resources/js/components/admin/ProjectFormModal.vue` | Project add/edit modal form |
| | `resources/js/components/admin/ServiceFormModal.vue` | Service add/edit modal form |
| | `resources/js/components/admin/StatCard.vue` | Dashboard statistical metric card |
| | `resources/js/components/admin/ToastNotification.vue` | System toast notification container |
| **Views** | `resources/js/views/admin/AdminDashboard.vue` | Main admin control center dashboard |
| | `resources/js/views/admin/AdminSettings.vue` | General, contact & social site settings |
| | `resources/js/views/admin/AdminServices.vue` | Service catalog management & reordering |
| | `resources/js/views/admin/AdminProjects.vue` | Portfolio project management (grid & list view) |
| | `resources/js/views/admin/AdminSections.vue` | Page section content & SEO editor |
| | `resources/js/views/admin/AdminPages.vue` | Site page overview |
| | `resources/js/views/admin/AdminContacts.vue` | Inbox message management & filtering |
| | `resources/js/views/admin/AdminMedia.vue` | Media asset library & dropzone |
| | `resources/js/views/admin/AdminCodeInjection.vue` | Header/footer code injection editor |
| | `resources/js/views/admin/AdminLogin.vue` | Admin authentication login view |

---

## 2. Global Dark Class Inventory & General Mapping Rules

| Category | Current Dark Classes / Colors | Recommended Light Mode Replacements |
|---|---|---|
| **Root Canvas Background** | `bg-slate-950`, `bg-[#0a0a0a]` | `bg-[#F7F5F0]` |
| **Card & Modal Surface** | `bg-slate-900`, `bg-[#141414]` | `bg-[#FFFFFF]` |
| **Input & Secondary Box** | `bg-slate-950`, `bg-[#1a1a1a]`, `bg-[#222]` | `bg-[#F7F5F0]` or `bg-[#F0ECE1]` |
| **Header / Table Bar** | `bg-slate-950/50`, `bg-slate-950/30`, `bg-[#1a1a1a]` | `bg-[#F7F5F0]` |
| **Pills & Minor Tags** | `bg-slate-800`, `bg-slate-700/20`, `bg-[#222]` | `bg-[#F0ECE1]` |
| **Headings & Titles** | `text-white`, `text-slate-100` | `text-[#111111]` |
| **Secondary Text / Subtitles**| `text-slate-400`, `text-slate-500`, `text-[#888]`, `text-[#555]` | `text-[#555555]` |
| **Primary Accents / Buttons** | `bg-amber-500`, `bg-gradient-to-r from-[#d4af37]...`, `bg-[#d4af37]` | `bg-[#C5A880] text-white hover:bg-[#111111]` |
| **Accent Text / Links** | `text-amber-500`, `text-[#d4af37]`, `text-amber-400` | `text-[#C5A880]` |
| **Borders & Dividers** | `border-slate-800`, `border-slate-900`, `border-[#222]`, `border-[#333]` | `border-[#E0DACE]` |
| **Focus / Hover Borders** | `border-amber-500`, `hover:border-amber-500/30`, `border-[#d4af37]` | `border-[#C5A880]` / `hover:border-[#C5A880]/30` |
| **Modal Backdrop Tint** | `bg-slate-950/80`, `bg-slate-950/90`, `bg-black/80` | `bg-[#111111]/40` |

---

## 3. File-by-File Light Mode Replacement Mapping

### 3.1. `AdminLayout.vue` (`resources/js/layouts/AdminLayout.vue`)
- **Root wrapper** (line 2): `bg-slate-950 font-sans text-slate-100 selection:bg-amber-500 selection:text-slate-950`  
  → `bg-[#F7F5F0] font-sans text-[#111111] selection:bg-[#C5A880] selection:text-white`
- **Footer divider** (line 24): `border-slate-900` → `border-[#E0DACE]`
- **Footer text** (line 25): `text-slate-600` → `text-[#555555]`

### 3.2. `AdminSidebar.vue` (`resources/js/components/admin/AdminSidebar.vue`)
- **Aside container** (line 3, 6): `bg-slate-950`, `border-slate-800` → `bg-[#FFFFFF]`, `border-[#E0DACE]`
- **Header logo badge & text** (line 10, 12, 13, 15): `border-slate-800`, `bg-amber-500 text-slate-950`, `text-white`  
  → `border-[#E0DACE]`, `bg-[#C5A880] text-white`, `text-[#111111]`
- **Nav active link** (line 25): `bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20`  
  → `bg-[#C5A880] text-white shadow-lg shadow-[#C5A880]/20`
- **Nav inactive link** (line 25): `text-slate-400 hover:bg-slate-900 hover:text-white`  
  → `text-[#555555] hover:bg-[#F0ECE1] hover:text-[#111111]`
- **User Footer Box** (line 40, 41, 42, 46, 47): `border-slate-800 bg-slate-950`, `bg-slate-900 border-slate-800`, `bg-slate-700 text-white`, `text-white`, `text-slate-500`  
  → `border-[#E0DACE] bg-[#FFFFFF]`, `bg-[#F7F5F0] border-[#E0DACE]`, `bg-[#E0DACE] text-[#111111]`, `text-[#111111]`, `text-[#555555]`
- **Collapse Toggle** (line 57): `bg-slate-800 border-slate-700 text-slate-400 hover:text-amber-500`  
  → `bg-[#FFFFFF] border-[#E0DACE] text-[#555555] hover:text-[#C5A880]`

### 3.3. `AdminTopBar.vue` (`resources/js/components/admin/AdminTopBar.vue`)
- **Header bar** (line 2): `bg-slate-900/50 backdrop-blur-md border-b border-slate-800`  
  → `bg-[#FFFFFF]/80 backdrop-blur-md border-b border-[#E0DACE]`
- **Page title** (line 5): `text-slate-100` → `text-[#111111]`
- **Language toggle wrapper** (line 11): `bg-slate-950 border-slate-800` → `bg-[#F0ECE1] border-[#E0DACE]`
- **Language toggle active/inactive** (line 17): `bg-amber-500 text-slate-950` / `text-slate-500 hover:text-white`  
  → `bg-[#C5A880] text-white` / `text-[#555555] hover:text-[#111111]`
- **View Site Link** (line 30, 31): `bg-white/5 hover:bg-white/10 text-white border-white/5`, icon `text-amber-500`  
  → `bg-[#F7F5F0] hover:bg-[#F0ECE1] text-[#111111] border-[#E0DACE]`, icon `text-[#C5A880]`

### 3.4. `ConfirmModal.vue` (`resources/js/components/admin/ConfirmModal.vue`)
- **Backdrop** (line 3): `bg-slate-950/80` → `bg-[#111111]/40`
- **Modal body** (line 4): `bg-slate-900 border-slate-800` → `bg-[#FFFFFF] border-[#E0DACE]`
- **Title & Text** (line 11, 12): `text-white`, `text-slate-400` → `text-[#111111]`, `text-[#555555]`
- **Cancel button** (line 18): `text-slate-400 bg-slate-800 hover:bg-slate-700` → `text-[#555555] bg-[#F0ECE1] border border-[#E0DACE] hover:bg-[#E0DACE] hover:text-[#111111]`

### 3.5. `ContactDetailModal.vue` (`resources/js/components/admin/ContactDetailModal.vue`)
- **Backdrop & Container** (line 3, 4, 6): `bg-slate-950/90`, `bg-slate-900 border-slate-800`  
  → `bg-[#111111]/40`, `bg-[#FFFFFF] border-[#E0DACE]`
- **Title & Text** (line 9, 21): `text-white`, `text-slate-500` → `text-[#111111]`, `text-[#555555]`
- **Info Blocks** (line 32, 41, 50, 59): `bg-slate-950 border-slate-800` → `bg-[#F7F5F0] border-[#E0DACE]`
- **Info Text & Values** (line 34, 39, 52, 59): `text-slate-600`, `text-white`, `text-amber-500`, `text-slate-300`  
  → `text-[#555555]`, `text-[#111111]`, `text-[#C5A880]`, `text-[#222222]`
- **Footer Actions** (line 66, 71): `bg-slate-950/50 border-slate-800`, `bg-slate-800 text-white`  
  → `bg-[#F7F5F0] border-[#E0DACE]`, `bg-[#F0ECE1] text-[#111111] border border-[#E0DACE]`

### 3.6. `ProjectFormModal.vue` (`resources/js/components/admin/ProjectFormModal.vue`)
- **Backdrop & Modal Container** (line 3, 4, 6, 21, 159): `bg-slate-950/90`, `bg-slate-900 border-slate-800`  
  → `bg-[#111111]/40`, `bg-[#FFFFFF] border-[#E0DACE]`
- **Tabs** (line 21, 27, 30): `bg-slate-950/30`, active: `text-amber-500` & `bg-amber-500`, inactive: `text-slate-500 hover:text-slate-300`  
  → `bg-[#F7F5F0]/50`, active: `text-[#C5A880]` & `bg-[#C5A880]`, inactive: `text-[#555555] hover:text-[#111111]`
- **Inputs, Selects, Textareas** (line 42, 76, 80, 86, 90): `bg-slate-950 border-slate-800 focus:border-amber-500 text-white`  
  → `bg-[#F7F5F0] border-[#E0DACE] focus:border-[#C5A880] text-[#111111]`
- **Media Upload Areas** (line 98, 126): `border-slate-800 hover:border-amber-500/50 bg-slate-950/50`  
  → `border-[#E0DACE] hover:border-[#C5A880]/50 bg-[#F7F5F0]`
- **Footer Buttons** (line 160, 166): Discard: `text-slate-500 hover:text-white` → `text-[#555555] hover:text-[#111111]`, Submit: `bg-amber-500 text-slate-950 hover:bg-white` → `bg-[#C5A880] text-white hover:bg-[#111111]`

### 3.7. `ServiceFormModal.vue` (`resources/js/components/admin/ServiceFormModal.vue`)
- Matches `ProjectFormModal.vue` replacement structure:
  - Container: `bg-[#FFFFFF] border-[#E0DACE]`
  - Inputs: `bg-[#F7F5F0] border-[#E0DACE] focus:border-[#C5A880] text-[#111111]`
  - Tabs: active `text-[#C5A880]`, inactive `text-[#555555]`
  - Footer & Submit: `bg-[#F7F5F0]`, Submit `bg-[#C5A880] text-white hover:bg-[#111111]`

### 3.8. `StatCard.vue` (`resources/js/components/admin/StatCard.vue`)
- **Card Container** (line 2): `bg-slate-900 border-slate-800 hover:border-amber-500/30`  
  → `bg-[#FFFFFF] border-[#E0DACE] hover:border-[#C5A880]/30`
- **Titles & Values** (line 5, 6): `text-slate-500`, `text-white` → `text-[#555555]`, `text-[#111111]`
- **Footer Link & Divider** (line 16, 20): `border-slate-800/50`, `text-amber-500 hover:text-white`  
  → `border-[#E0DACE]`, `text-[#C5A880] hover:text-[#111111]`
- **Amber Color Badge** (line 45): `bg-amber-500/10 text-amber-500 group-hover:bg-amber-500 group-hover:text-slate-950`  
  → `bg-[#C5A880]/10 text-[#C5A880] group-hover:bg-[#C5A880] group-hover:text-white`

### 3.9. `ToastNotification.vue` (`resources/js/components/admin/ToastNotification.vue`)
- **Toast Box** (line 7): `bg-slate-900 border-slate-800` → `bg-[#FFFFFF] border-[#E0DACE] shadow-2xl`
- **Text & Close** (line 23, 26, 29): `text-slate-500`, `text-white`, `text-slate-700 hover:text-white`  
  → `text-[#555555]`, `text-[#111111]`, `text-[#555555] hover:text-[#111111]`

### 3.10. `AdminDashboard.vue` (`resources/js/views/admin/AdminDashboard.vue`)
- **Heading** (line 6, 7): `text-white`, `text-slate-500` → `text-[#111111]`, `text-[#555555]`
- **Quick Add Link** (line 11): `bg-white/5 hover:bg-white/10 text-white border-white/5`  
  → `bg-[#F0ECE1] hover:bg-[#E0DACE] text-[#111111] border-[#E0DACE]`
- **Table Card** (line 51, 52, 62, 70, 71): `bg-slate-900 border-slate-800`, header `bg-slate-950/50`, rows `divide-slate-800/50 hover:bg-white/[0.02]`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, header `bg-[#F7F5F0]`, rows `divide-[#E0DACE] hover:bg-[#F7F5F0]`
- **Table Text** (line 63, 73, 76, 84): `text-slate-500`, `text-white`, `text-slate-400` → `text-[#555555]`, `text-[#111111]`, `text-[#555555]`

### 3.11. `AdminSettings.vue` (`resources/js/views/admin/AdminSettings.vue`)
- **Root & Title** (line 2, 4): `bg-[#0a0a0a] text-white`, `bg-gradient-to-r from-[#d4af37]...`  
  → `bg-[#F7F5F0] text-[#111111]`, `text-[#111111]`
- **Tabs Nav** (line 15, 22): `border-[#222]`, active: `border-[#d4af37] text-[#d4af37]`, inactive: `text-[#888] hover:text-[#ccc]`  
  → `border-[#E0DACE]`, active: `border-[#C5A880] text-[#C5A880]`, inactive: `text-[#555555] hover:text-[#111111]`
- **Form Card** (line 30): `bg-[#141414] border-[#222]` → `bg-[#FFFFFF] border-[#E0DACE]`
- **Upload Box** (line 36, 47): `bg-[#1a1a1a] border-[#333]` → `bg-[#F7F5F0] border-[#E0DACE]`
- **Form Inputs** (line 60, 64, 75, 93, 106): `bg-[#1a1a1a] border-[#333] focus:ring-[#d4af37]`  
  → `bg-[#F7F5F0] border-[#E0DACE] focus:ring-[#C5A880] text-[#111111]`
- **Save Button** (line 114): `bg-gradient-to-r from-[#d4af37] to-[#b8860b] text-[#0a0a0a]`  
  → `bg-[#C5A880] text-white hover:bg-[#111111]`

### 3.12. `AdminServices.vue` (`resources/js/views/admin/AdminServices.vue`)
- **New Service Button** (line 11): `bg-amber-500 text-slate-950 hover:bg-white` → `bg-[#C5A880] text-white hover:bg-[#111111]`
- **Stat Cards** (line 20, 29, 38): `bg-slate-900 border-slate-800` → `bg-[#FFFFFF] border-[#E0DACE]`
- **Draggable Table Box** (line 50, 51, 64, 67): `bg-slate-900 border-slate-800`, header `bg-slate-950/30`, rows `divide-slate-800/50 hover:bg-white/[0.02]`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, header `bg-[#F7F5F0]`, rows `divide-[#E0DACE] hover:bg-[#F7F5F0]`
- **Drag Handle & Item Icon** (line 69, 74): `bg-slate-950 border-slate-800` → `bg-[#F7F5F0] border-[#E0DACE]`
- **Action Buttons** (line 102, 105): `bg-slate-950 text-slate-500 hover:text-amber-500 border-slate-800`  
  → `bg-[#F7F5F0] text-[#555555] hover:text-[#C5A880] border-[#E0DACE]`

### 3.13. `AdminProjects.vue` (`resources/js/views/admin/AdminProjects.vue`)
- **Toolbar & Inputs** (line 19, 25, 42): `bg-slate-900 border-slate-800`, inputs `bg-slate-950 border-slate-800 text-white focus:border-amber-500`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, inputs `bg-[#F7F5F0] border-[#E0DACE] text-[#111111] focus:border-[#C5A880]`
- **View Switcher** (line 47, 51): `bg-slate-950 border-slate-800`, active `bg-amber-500 text-slate-950`, inactive `text-slate-500 hover:text-white`  
  → `bg-[#F0ECE1] border-[#E0DACE]`, active `bg-[#C5A880] text-white`, inactive `text-[#555555] hover:text-[#111111]`
- **Grid Cards** (line 67, 68): `bg-slate-900 border-slate-800 hover:border-amber-500/30`, img bg `bg-slate-950`  
  → `bg-[#FFFFFF] border-[#E0DACE] hover:border-[#C5A880]/30`, img bg `bg-[#F7F5F0]`
- **List View Table** (line 120, 123, 131, 132): `bg-slate-900 border-slate-800`, header `bg-slate-950/30`, rows `divide-slate-800/50 hover:bg-white/[0.02]`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, header `bg-[#F7F5F0]`, rows `divide-[#E0DACE] hover:bg-[#F7F5F0]`

### 3.14. `AdminSections.vue` (`resources/js/views/admin/AdminSections.vue`)
- **Root & Title** (line 2, 9): `bg-[#0a0a0a] text-white`, title gradient  
  → `bg-[#F7F5F0] text-[#111111]`, title `text-[#111111]`
- **Section List Item** (line 35, 37, 54, 68): `bg-[#141414] border-[#222] hover:border-[#333]`, drag handle `text-[#333]`, buttons `border-[#222] text-[#888] bg-[#1a1a1a]`  
  → `bg-[#FFFFFF] border-[#E0DACE] hover:border-[#C5A880]/40`, drag handle `text-[#555555]`, buttons `border-[#E0DACE] text-[#555555] bg-[#F7F5F0]`
- **SEO Card & Inputs** (line 84, 95, 99, 105): `bg-[#141414] border-[#222]`, inputs `bg-[#1a1a1a] border-[#333] focus:ring-[#d4af37]`, button gradient  
  → `bg-[#FFFFFF] border-[#E0DACE]`, inputs `bg-[#F7F5F0] border-[#E0DACE] focus:ring-[#C5A880] text-[#111111]`, button `bg-[#C5A880] text-white hover:bg-[#111111]`

### 3.15. `AdminPages.vue` (`resources/js/views/admin/AdminPages.vue`)
- **Root & Heading** (line 2, 3): `bg-[#0a0a0a] text-white`, title gradient → `bg-[#F7F5F0] text-[#111111]`, title `text-[#111111]`
- **Page Link Cards** (line 17, 21, 24): `bg-[#141414] border-[#222] hover:border-[#d4af37]`, title `group-hover:text-[#d4af37]`, icon `bg-[#1a1a1a] text-[#888]`  
  → `bg-[#FFFFFF] border-[#E0DACE] hover:border-[#C5A880]`, title `group-hover:text-[#C5A880]`, icon `bg-[#F7F5F0] text-[#555555]`

### 3.16. `AdminContacts.vue` (`resources/js/views/admin/AdminContacts.vue`)
- **Filter Switcher** (line 10, 16): `bg-slate-900 border-slate-800`, active `bg-amber-500 text-slate-950`, inactive `text-slate-500 hover:text-white`  
  → `bg-[#F0ECE1] border-[#E0DACE]`, active `bg-[#C5A880] text-white`, inactive `text-[#555555] hover:text-[#111111]`
- **Contacts Table Box** (line 42, 43, 65, 69): `bg-slate-900 border-slate-800`, header `bg-slate-950/30`, rows `divide-slate-800/50 hover:bg-white/[0.02]`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, header `bg-[#F7F5F0]`, rows `divide-[#E0DACE] hover:bg-[#F7F5F0]`
- **Pagination Bar** (line 115, 119, 125): `border-slate-800 bg-slate-950/30`, buttons `bg-slate-900 text-white border-slate-800 hover:bg-white hover:text-slate-950`  
  → `border-[#E0DACE] bg-[#F7F5F0]`, buttons `bg-[#F0ECE1] text-[#111111] border-[#E0DACE] hover:bg-[#C5A880] hover:text-white`

### 3.17. `AdminMedia.vue` (`resources/js/views/admin/AdminMedia.vue`)
- **Upload Zone** (line 13, 23): `border-slate-800 hover:border-amber-500/50 bg-slate-900`, icon box `bg-slate-950 text-slate-700 group-hover:text-amber-500`  
  → `border-[#E0DACE] hover:border-[#C5A880]/50 bg-[#FFFFFF]`, icon box `bg-[#F7F5F0] text-[#555555] group-hover:text-[#C5A880]`
- **Media Cards** (line 66, 70, 73): `bg-slate-900 border-slate-800 hover:border-amber-500/30`, overlay `bg-slate-950/60`, copy button `bg-white text-slate-950 hover:bg-amber-500`  
  → `bg-[#FFFFFF] border-[#E0DACE] hover:border-[#C5A880]/30`, overlay `bg-[#111111]/40`, copy button `bg-[#FFFFFF] text-[#111111] border-[#E0DACE] hover:bg-[#C5A880] hover:text-white`

### 3.18. `AdminCodeInjection.vue` (`resources/js/views/admin/AdminCodeInjection.vue`)
- **Root & Add Button** (line 2, 4, 5): `bg-[#0a0a0a] text-white`, title gradient, button `bg-[#d4af37] text-black hover:bg-white`  
  → `bg-[#F7F5F0] text-[#111111]`, title `text-[#111111]`, button `bg-[#C5A880] text-white hover:bg-[#111111]`
- **Injections Table Box** (line 14, 15, 31, 32): `bg-[#141414] border-[#222]`, header `bg-[#1a1a1a] text-[#555]`, rows `divide-[#222] hover:bg-[#1a1a1a]`  
  → `bg-[#FFFFFF] border-[#E0DACE]`, header `bg-[#F7F5F0] text-[#555555]`, rows `divide-[#E0DACE] hover:bg-[#F7F5F0]`
- **Monaco Editor Theme** (line 141): `theme="vs-dark"` → `theme="vs"` (light theme for code editor)

### 3.19. `AdminLogin.vue` (`resources/js/views/admin/AdminLogin.vue`)
- **Root Container** (line 2): `min-h-screen flex items-center justify-center bg-[#0a0a0a]` → `bg-[#F7F5F0]`
- **Card Container** (line 3): `bg-[#141414] border border-[#2a2a2a] rounded-2xl shadow-2xl` → `bg-[#FFFFFF] border border-[#E0DACE] rounded-2xl shadow-2xl`
- **Logo Badge** (line 5, 6): `bg-gradient-to-tr from-[#d4af37] to-[#f3e5ab]`, icon `text-[#0a0a0a]` → `bg-[#C5A880]`, icon `text-white`
- **Titles & Labels** (line 10, 11, 16, 28): `text-white`, `text-[#888]`, `text-[#ccc]` → `text-[#111111]`, `text-[#555555]`, `text-[#555555]`
- **Input Fields** (line 22, 35): `bg-[#1a1a1a] border border-[#333] text-white focus:ring-[#d4af37]`  
  → `bg-[#F7F5F0] border border-[#E0DACE] text-[#111111] focus:ring-[#C5A880]`
- **Submit Button** (line 63): `bg-gradient-to-r from-[#d4af37] to-[#b8860b] text-[#0a0a0a]` → `bg-[#C5A880] text-white hover:bg-[#111111]`

---

## 4. Verification Plan

1. **Static Analysis**: Verify all 19 files contain zero remaining dark background/border/text utility classes (`bg-slate-950`, `bg-[#0a0a0a]`, `bg-[#141414]`, `text-white`, `border-slate-800`, `border-[#222]`, `bg-amber-500`, `text-[#d4af37]`).
2. **Build Compilation**: Run `npm run build` to confirm Vue templates compile cleanly without syntax or utility class syntax errors.
3. **Automated Test Suite**: Run `php artisan test` and `npx vitest` (or relevant test runners) to confirm admin view test suite passes without visual regression or broken element selectors.
