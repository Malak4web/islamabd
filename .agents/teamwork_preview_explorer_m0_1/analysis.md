# Public Frontend Light Mode Survey & Redesign Strategy Analysis Report

**Target Project**: Eslam Abdulghani Designs (`c:\xampp\htdocs\islamabd`)  
**Investigator**: `explorer_1`  
**Working Directory**: `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_m0_1\`  
**Date**: 2026-08-08  

---

## 1. Executive Summary

This report surveys all Public Frontend files in the Laravel/Vue application to map out the transition from the legacy Dark Mode palette to a refined, luxury Light Mode theme. The design palette is inspired by the brand logo, featuring:
- **Canvas / Primary Backgrounds**: `#F7F5F0` (warm off-white), `#FFFFFF` (pure white for cards/modals), `#F0ECE1` (soft taupe/beige for alternating sections & footers)
- **Text & Structural Headers**: `#111111` (deep charcoal black for primary titles/headings), `#222222` (dark charcoal for subtitles/card titles), `#555555` (neutral slate gray for body text/labels)
- **Accents & Borders**: `#C5A880` (warm taupe/gold accent), `#E0DACE` (subtle light warm border)

---

## 2. File Inventory (Public Frontend Scope)

### 2.1 Blade Template & Root Vue Layout
1. `resources/views/app.blade.php` (Root HTML container)
2. `resources/js/App.vue` (Main Vue app wrapper & global scrollbar styles)
3. `resources/css/app.css` (Global Tailwind base/utility styles)

### 2.2 Public Components (`resources/js/components/`)
1. `resources/js/components/CodeInjector.vue` (Head/body injection - non-visual)
2. `resources/js/components/public/AboutSnippet.vue`
3. `resources/js/components/public/AppFooter.vue`
4. `resources/js/components/public/AppHeader.vue`
5. `resources/js/components/public/CategoryFilter.vue`
6. `resources/js/components/public/ContactForm.vue`
7. `resources/js/components/public/CtaBanner.vue`
8. `resources/js/components/public/FloatingSocial.vue`
9. `resources/js/components/public/HeroSlider.vue`
10. `resources/js/components/public/LanguageSwitcher.vue`
11. `resources/js/components/public/MobileMenuToggle.vue`
12. `resources/js/components/public/NavLinks.vue`
13. `resources/js/components/public/ProjectCard.vue`
14. `resources/js/components/public/ProjectsPreview.vue`
15. `resources/js/components/public/ServiceCard.vue`
16. `resources/js/components/public/ServicesPreview.vue`

### 2.3 Public Views (`resources/js/views/public/`)
1. `resources/js/views/public/AboutView.vue`
2. `resources/js/views/public/ContactView.vue`
3. `resources/js/views/public/HomeView.vue`
4. `resources/js/views/public/ProjectDetailView.vue`
5. `resources/js/views/public/ProjectsView.vue`
6. `resources/js/views/public/ServiceDetailView.vue`
7. `resources/js/views/public/ServicesView.vue`

*(Note: `resources/js/layouts/AdminLayout.vue` and `resources/js/views/admin/*` are Admin Panel files and strictly excluded from the public frontend scope).*

---

## 3. Dark Mode CSS Classes & Token Inventory

The following legacy dark mode tokens were detected across the public frontend codebase:

| Category | Legacy Dark Code / Class | Occurrences / Context | Target Light Mode Token | Target Color Code |
|---|---|---|---|---|
| **Background (Main Canvas)** | `bg-[#0a0a0a]`, `bg-slate-950` | `app.blade.php`, `App.vue`, `AboutSnippet.vue`, `ProjectsPreview.vue`, `AboutView.vue`, `ContactView.vue`, `ProjectDetailView.vue`, `ProjectsView.vue`, `ServiceDetailView.vue`, `ServicesView.vue` | `bg-[#F7F5F0]` | `#F7F5F0` |
| **Background (Cards & Modals)** | `bg-[#111]`, `bg-[#141414]`, `bg-[#1a1a1a]`, `bg-[#050505]` | `AboutSnippet.vue`, `ContactForm.vue`, `ProjectCard.vue`, `ServiceCard.vue`, `ContactView.vue`, `ProjectDetailView.vue`, `AppHeader.vue` | `bg-[#FFFFFF]` or `bg-[#F0ECE1]` | `#FFFFFF` / `#F0ECE1` |
| **Background (Alternating Sections)** | `bg-[#0f0f0f]`, `bg-black` | `AboutView.vue`, `AppFooter.vue`, `ServicesPreview.vue` | `bg-[#F0ECE1]` or `bg-[#F7F5F0]` | `#F0ECE1` |
| **Background Overlays** | `from-black/60 via-transparent to-[#0a0a0a]`, `bg-black/90`, `bg-black/60` | `AppHeader.vue`, `HeroSlider.vue`, `ServicesPreview.vue`, `AboutView.vue`, `ContactView.vue` | `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`, `bg-[#F7F5F0]/90 backdrop-blur-md` | Soft off-white gradients & backdrop blurs |
| **Primary Text** | `text-white` | Page titles, section headings, card titles, header nav | `text-[#111111]` | `#111111` |
| **Secondary Text** | `text-gray-300`, `text-gray-400` | Subtitles, body paragraphs, service descriptions | `text-[#555555]` or `text-[#222222]` | `#555555` / `#222222` |
| **Muted Text / Badges** | `text-gray-500`, `text-[#888]`, `text-[#555]` | Labels, metadata, copyright, form placeholders | `text-[#555555]` | `#555555` |
| **Legacy Accent / Gold** | `text-[#d4af37]`, `bg-[#d4af37]`, `border-[#d4af37]` | Buttons, active filters, icons, active nav links, lines | `text-[#C5A880]`, `bg-[#C5A880]`, `border-[#C5A880]` | `#C5A880` |
| **Legacy Borders** | `border-[#222]`, `border-[#333]`, `border-white/5`, `border-white/10` | Form inputs, cards, headers, footers | `border-[#E0DACE]` | `#E0DACE` |

---

## 4. Light Mode Replacement Mapping Matrix

### 4.1 Root Files & Shell

#### `resources/views/app.blade.php`
- **Line 14**: `<body class="antialiased font-sans bg-slate-950 text-slate-200">`  
  👉 **Replace with**: `<body class="antialiased font-sans bg-[#F7F5F0] text-[#111111]">`

#### `resources/js/App.vue`
- **Line 2**: `<div class="min-h-screen bg-[#0a0a0a] text-white selection:bg-[#d4af37] selection:text-black">`  
  👉 **Replace with**: `<div class="min-h-screen bg-[#F7F5F0] text-[#111111] selection:bg-[#C5A880] selection:text-white">`
- **Lines 69, 72, 76** (Scrollbar CSS):
  - `::-webkit-scrollbar-track { background: #0a0a0a; }` 👉 `#F7F5F0`
  - `::-webkit-scrollbar-thumb { background: #222; }` 👉 `#E0DACE`
  - `::-webkit-scrollbar-thumb:hover { background: #d4af37; }` 👉 `#C5A880`

---

### 4.2 Public Components

#### 1. `resources/js/components/public/AppHeader.vue`
- **Scrolled Header** (Line 4): `bg-black/90 backdrop-blur-md shadow-2xl` 👉 `bg-[#F7F5F0]/90 backdrop-blur-md shadow-sm border-b border-[#E0DACE]`
- **Logo Fallback Badge** (Line 12): `bg-white/10` 👉 `bg-[#111111]/5`, `text-white` 👉 `text-[#111111]`, `text-[#d4af37]` 👉 `text-[#C5A880]`
- **Header Text** (Line 16): `text-white` 👉 `text-[#111111]`, Tagline `text-[#d4af37]` 👉 `text-[#C5A880]`
- **CTA Contact Button** (Line 34): `bg-[#d4af37] text-black hover:bg-white` 👉 `bg-[#C5A880] text-white hover:bg-[#111111]`
- **Mobile Drawer** (Line 51, 55): `bg-black/60` 👉 `bg-black/40`; `bg-[#050505] border-l border-white/5` 👉 `bg-[#F7F5F0] border-l border-[#E0DACE]`
- **Mobile Menu Elements** (Lines 57-111): `border-white/5` 👉 `border-[#E0DACE]`; Close button `bg-white/5 text-white hover:bg-[#d4af37]` 👉 `bg-[#F0ECE1] text-[#111111] hover:bg-[#C5A880] hover:text-white`; `text-[#d4af37]` 👉 `text-[#C5A880]`

#### 2. `resources/js/components/public/AppFooter.vue`
- **Footer Container** (Line 2): `bg-black border-t border-white/5` 👉 `bg-[#F0ECE1] border-t border-[#E0DACE]`
- **Brand & Logo** (Line 8, 11): `bg-white/10` 👉 `bg-[#111111]/5`, `text-white` 👉 `text-[#111111]`
- **Text & Descriptions** (Line 13): `text-gray-400` 👉 `text-[#555555]`
- **Social Links** (Line 17): `bg-white/5 hover:bg-[#d4af37] hover:text-black text-white/50` 👉 `bg-[#FFFFFF] border border-[#E0DACE] text-[#555555] hover:bg-[#C5A880] hover:text-white hover:border-[#C5A880]`
- **Headings** (Lines 25, 35, 45): `text-[#d4af37]` 👉 `text-[#C5A880]`
- **Links** (Lines 27, 37): `text-gray-400 hover:text-white` 👉 `text-[#555555] hover:text-[#111111]`
- **Contact Icons & Text** (Lines 48-64): `text-[#d4af37]` 👉 `text-[#C5A880]`, `text-gray-400` 👉 `text-[#555555]`
- **Bottom Bar** (Lines 70-76): `border-white/5` 👉 `border-[#E0DACE]`, `text-gray-500` 👉 `text-[#555555]`, Back to top `text-[#d4af37] border-[#d4af37]/30` 👉 `text-[#C5A880] border-[#C5A880]/30`

#### 3. `resources/js/components/public/AboutSnippet.vue`
- **Section Canvas** (Line 2): `bg-[#0a0a0a]` 👉 `bg-[#F7F5F0]`
- **Decorative Stat Card** (Line 18): `bg-[#111] border border-white/5` 👉 `bg-[#FFFFFF] border border-[#E0DACE] shadow-lg`
- **Stat Values & Accents** (Line 22, 25): `text-[#d4af37]` 👉 `text-[#C5A880]`, `text-white` 👉 `text-[#111111]`
- **Subhead & Headings** (Line 34, 37): `text-[#d4af37]` 👉 `text-[#C5A880]`, `text-white` 👉 `text-[#111111]`
- **Body Text** (Line 42): `text-gray-400` 👉 `text-[#555555]`
- **CTA Link & Circle Button** (Lines 54-56): `text-white` 👉 `text-[#111111]`; Circle `bg-white/5 border border-white/10 group-hover:bg-[#d4af37] group-hover:text-black` 👉 `bg-[#F0ECE1] border border-[#E0DACE] group-hover:bg-[#C5A880] group-hover:text-white`

#### 4. `resources/js/components/public/CategoryFilter.vue`
- **Active Button** (Line 9): `bg-[#d4af37] border-[#d4af37] text-[#0a0a0a]` 👉 `bg-[#C5A880] border-[#C5A880] text-white`
- **Inactive Button** (Line 10): `bg-transparent border-[#222] text-[#888] hover:border-[#d4af37] hover:text-[#d4af37]` 👉 `bg-transparent border-[#E0DACE] text-[#555555] hover:border-[#C5A880] hover:text-[#C5A880]`

#### 5. `resources/js/components/public/ContactForm.vue`
- **Form Card Container** (Line 2): `bg-[#141414] border border-[#222]` 👉 `bg-[#FFFFFF] border border-[#E0DACE] shadow-md`
- **Success State** (Line 4, 9, 10, 11): `bg-[#d4af37]/20` 👉 `bg-[#C5A880]/20`, `text-[#d4af37]` 👉 `text-[#C5A880]`, Title `text-white` 👉 `text-[#111111]`, Message `text-[#888]` 👉 `text-[#555555]`, Button `bg-[#d4af37] text-black` 👉 `bg-[#C5A880] text-white`
- **Form Heading & Labels** (Line 17, 21, 28, 37, 44, 58): Title `text-white` 👉 `text-[#111111]`, Labels `text-[#555]` 👉 `text-[#555555]`
- **Input Fields & Textarea** (Lines 22, 29, 38, 46, 59): `bg-[#1a1a1a] border border-[#333] focus:border-[#d4af37] text-white` 👉 `bg-[#F7F5F0] border border-[#E0DACE] focus:border-[#C5A880] text-[#111111]`
- **Submit Button** (Line 67): `bg-[#d4af37] text-black hover:bg-white` 👉 `bg-[#C5A880] text-white hover:bg-[#111111]`

#### 6. `resources/js/components/public/CtaBanner.vue`
- **Banner Container** (Line 4): `bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] border border-white/5` 👉 `bg-gradient-to-br from-[#F0ECE1] to-[#F7F5F0] border border-[#E0DACE]`
- **Background Blobs** (Line 6, 7): `bg-[#d4af37] opacity-10` 👉 `bg-[#C5A880] opacity-20`; `bg-white opacity-5` 👉 `bg-[#FFFFFF] opacity-50`
- **Heading & Subtitle** (Line 11, 14): `text-white` 👉 `text-[#111111]`, `text-gray-400` 👉 `text-[#555555]`
- **Buttons** (Line 20, 23): Primary `bg-[#d4af37] text-black hover:bg-white` 👉 `bg-[#C5A880] text-white hover:bg-[#111111]`; Secondary `text-white border-white/10 hover:bg-white/10` 👉 `text-[#111111] border-[#E0DACE] hover:bg-[#FFFFFF]`

#### 7. `resources/js/components/public/FloatingSocial.vue`
- **Floating Buttons** (Line 11): `border border-[#d4af37] bg-[#1a1a1a] text-[#d4af37] hover:bg-[#d4af37] hover:text-black` 👉 `border border-[#C5A880] bg-[#FFFFFF] text-[#C5A880] hover:bg-[#C5A880] hover:text-white shadow-lg`
- **Tooltip** (Line 18): `bg-black border border-[#d4af37]/20 text-[#d4af37]` 👉 `bg-[#111111] border border-[#C5A880]/30 text-[#C5A880]`

#### 8. `resources/js/components/public/HeroSlider.vue`
- **Container** (Line 2): `bg-black` 👉 `bg-[#F7F5F0]`
- **Slide Overlay Gradient** (Line 16): `from-black/60 via-black/40 to-[#0a0a0a]` 👉 `from-black/50 via-black/30 to-[#F7F5F0]`
- **Subtitle Accent** (Line 23): `text-[#d4af37]` 👉 `text-[#C5A880]`
- **Primary CTA Button** (Line 46): `bg-[#d4af37] text-black hover:bg-white` 👉 `bg-[#C5A880] text-white hover:bg-[#111111]`
- **Secondary CTA Button** (Line 49): `text-white border-white/20 hover:bg-white hover:text-black` 👉 `text-white border-white/30 hover:bg-[#FFFFFF] hover:text-[#111111]`
- **Dots & Scroll Line** (Lines 63, 69): Active dot `bg-[#d4af37]` 👉 `bg-[#C5A880]`; Scroll line `to-[#d4af37]` 👉 `to-[#C5A880]`

#### 9. `resources/js/components/public/LanguageSwitcher.vue`
- **Button** (Line 4): `text-white hover:text-[#d4af37] bg-black/20 hover:bg-black/40` 👉 `text-[#111111] hover:text-[#C5A880] bg-[#111111]/5 hover:bg-[#111111]/10 border border-[#E0DACE]`

#### 10. `resources/js/components/public/MobileMenuToggle.vue`
- **Hover & Lines** (Line 4, 8, 12, 16): `hover:bg-white/5` 👉 `hover:bg-[#111111]/5`; `bg-white` 👉 `bg-[#111111]`

#### 11. `resources/js/components/public/NavLinks.vue`
- **Hover & Active Underline** (Lines 7, 8, 12): `hover:text-[#d4af37]`, `active-class="text-[#d4af37]"`, `bg-[#d4af37]` 👉 `hover:text-[#C5A880]`, `active-class="text-[#C5A880]"`, `bg-[#C5A880]`

#### 12. `resources/js/components/public/ProjectCard.vue`
- **Card Wrapper** (Line 4): `bg-[#111] border border-[#222] hover:border-[#d4af37]/50` 👉 `bg-[#FFFFFF] border border-[#E0DACE] hover:border-[#C5A880] shadow-md`
- **Category Badge** (Line 21): `bg-[#d4af37] text-[#0a0a0a]` 👉 `bg-[#C5A880] text-white`
- **Featured Badge** (Line 24): `bg-white text-[#0a0a0a]` 👉 `bg-[#111111] text-white`
- **Link Text & Icon** (Line 40): `text-[#d4af37]` 👉 `text-[#C5A880]`

#### 13. `resources/js/components/public/ProjectsPreview.vue`
- **Section Canvas** (Line 2): `bg-[#0a0a0a]` 👉 `bg-[#F7F5F0]`
- **Glow & Subhead** (Line 4, 8): `bg-[#d4af37]/5` 👉 `bg-[#C5A880]/10`; `text-[#d4af37]` 👉 `text-[#C5A880]`
- **Title** (Line 11): `text-white` 👉 `text-[#111111]`
- **View All Button** (Line 16): `text-white border-white/10 hover:bg-white hover:text-black` 👉 `text-[#111111] border-[#E0DACE] hover:bg-[#C5A880] hover:text-white hover:border-[#C5A880]`
- **Skeleton** (Line 22): `bg-white/5` 👉 `bg-[#E0DACE]/30`

#### 14. `resources/js/components/public/ServiceCard.vue`
- **Card Wrapper** (Line 4): `bg-[#111] border border-white/5 hover:border-[#d4af37]/30` 👉 `bg-[#FFFFFF] border border-[#E0DACE] hover:border-[#C5A880] shadow-sm hover:shadow-xl`
- **Index Number** (Line 7): `text-white/[0.03] group-hover:text-[#d4af37]/5` 👉 `text-[#111111]/[0.05] group-hover:text-[#C5A880]/10`
- **Icon Container & SVG** (Line 17, 27): `bg-white/5 border border-white/5 group-hover:bg-[#d4af37]` 👉 `bg-[#F0ECE1] border border-[#E0DACE] group-hover:bg-[#C5A880]`; SVG `text-[#d4af37] group-hover:text-black` 👉 `text-[#C5A880] group-hover:text-white`
- **Title & Body** (Line 35, 39): Title `text-white group-hover:text-[#d4af37]` 👉 `text-[#111111] group-hover:text-[#C5A880]`; Paragraph `text-gray-400` 👉 `text-[#555555]`
- **Footer Action** (Lines 45-52): `border-white/5` 👉 `border-[#E0DACE]`; `text-[#d4af37]` 👉 `text-[#C5A880]`; Button `border-[#d4af37]/30 text-[#d4af37] group-hover:bg-[#d4af37] group-hover:text-black` 👉 `border-[#C5A880]/40 text-[#C5A880] group-hover:bg-[#C5A880] group-hover:text-white`

#### 15. `resources/js/components/public/ServicesPreview.vue`
- **Section Canvas & Overlay** (Lines 2, 8, 9): `bg-black`, `bg-black/40`, `from-black/60 via-transparent to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `bg-[#F7F5F0]/60`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`
- **Header Elements** (Lines 14, 17, 21, 24, 26): Subtitle `text-[#d4af37]` 👉 `text-[#C5A880]`; Heading `text-white` 👉 `text-[#111111]`; Line `bg-[#d4af37]` 👉 `bg-[#C5A880]`; Link & line `text-[#d4af37]` / `bg-[#d4af37]` 👉 `hover:text-[#111111]` / `group-hover:bg-[#111111]`

---

### 4.3 Public Views

#### 1. `resources/js/views/public/AboutView.vue`
- **Loading Spinner** (Line 2, 3): `bg-black`, `border-[#d4af37]/20 border-t-[#d4af37]` 👉 `bg-[#F7F5F0]`, `border-[#C5A880]/20 border-t-[#C5A880]`
- **Main Canvas & Hero** (Line 6, 8, 16): `bg-[#0a0a0a]`, Hero `bg-black`, Overlay `from-black/80 via-transparent to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`
- **Hero Title & Gradient Accent** (Line 19, 22, 24, 28): Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Gradient `from-[#d4af37] via-white to-[#d4af37]` 👉 `from-[#C5A880] via-[#111111] to-[#C5A880]`; Line `bg-[#d4af37]` 👉 `bg-[#C5A880]`
- **Alternating Sections** (Line 35): `index % 2 === 0 ? 'bg-[#0a0a0a]' : 'bg-[#0f0f0f]'` 👉 `index % 2 === 0 ? 'bg-[#F7F5F0]' : 'bg-[#F0ECE1]'`
- **Story / Mission Content** (Lines 40, 41, 45, 46, 50-52, 66, 67): Headers `text-[#d4af37]` 👉 `text-[#C5A880]`, `text-white` 👉 `text-[#111111]`; Line `from-[#d4af37]` 👉 `from-[#C5A880]`; Body `text-gray-400` 👉 `text-[#555555]`; CTA `text-white border-white/20 group-hover:bg-[#d4af37]` 👉 `text-[#111111] border-[#E0DACE] group-hover:bg-[#C5A880] group-hover:text-white`; Brackets `border-[#d4af37]/30` 👉 `border-[#C5A880]/40`
- **Expertise Section** (Lines 74, 75, 78, 79, 86, 91, 92, 99): `bg-black` 👉 `bg-[#FFFFFF]`; Glow `bg-[#d4af37]/5` 👉 `bg-[#C5A880]/10`; Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Body `text-gray-300` 👉 `text-[#555555]`; Skills `border-[#d4af37]` 👉 `border-[#C5A880]`, `text-white` 👉 `text-[#111111]`; Overlay `bg-[#d4af37]/20` 👉 `bg-[#C5A880]/20`

#### 2. `resources/js/views/public/ContactView.vue`
- **Main Canvas & Header** (Line 2, 4, 11, 14, 17, 22): `bg-[#0a0a0a]`, `bg-[#050505]`, Overlay `from-black/60 via-transparent to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`; Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Line `bg-[#d4af37]` 👉 `bg-[#C5A880]`
- **Sidebar Details** (Lines 33, 34, 41, 45, 46, 50, 54, 55, 56, 60, 64, 65, 66): Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Icon circle `bg-white/5 text-[#d4af37] group-hover:bg-[#d4af37] group-hover:text-black` 👉 `bg-[#F0ECE1] text-[#C5A880] group-hover:bg-[#C5A880] group-hover:text-white`; Labels `text-gray-500` 👉 `text-[#555555]`; Info `text-gray-300 group-hover:text-white` 👉 `text-[#222222] group-hover:text-[#111111]`; Sub-text `text-gray-600` 👉 `text-[#555555]`
- **Form Wrapper Card** (Line 74, 76): `bg-[#111] border border-white/5` 👉 `bg-[#FFFFFF] border border-[#E0DACE] shadow-lg`; Glow `bg-[#d4af37] opacity-5` 👉 `bg-[#C5A880] opacity-10`
- **Map Container** (Line 87): `bg-black border-y border-white/5` 👉 `bg-[#F0ECE1] border-y border-[#E0DACE]`

#### 3. `resources/js/views/public/HomeView.vue`
- **Loading Spinner** (Line 2, 3): `bg-black`, `border-[#d4af37]/20 border-t-[#d4af37]` 👉 `bg-[#F7F5F0]`, `border-[#C5A880]/20 border-t-[#C5A880]`

#### 4. `resources/js/views/public/ProjectDetailView.vue`
- **Loading & Canvas** (Line 2, 3, 6): `bg-black`, `bg-[#0a0a0a]` 👉 `bg-[#F7F5F0]`
- **Hero & Cover** (Line 8, 14, 19, 22): `bg-black`, Overlay `from-[#0a0a0a] via-transparent to-black/40` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0] via-transparent to-black/30`; Category badge `text-[#d4af37] bg-[#d4af37]/10 border-[#d4af37]/20` 👉 `text-[#C5A880] bg-[#C5A880]/15 border-[#C5A880]/30`; Title `text-white` 👉 `text-[#111111]`
- **Concept & Description** (Line 37, 38): Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Paragraph `text-gray-300` 👉 `text-[#555555]`
- **Gallery Grid** (Line 45, 51, 54, 55): Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Image box `bg-white/5` 👉 `bg-[#F0ECE1]`; Hover overlay `bg-[#d4af37]/20` 👉 `bg-[#C5A880]/30`; Icon container `bg-white/10 text-white` 👉 `bg-white/50 text-[#111111]`
- **Metadata Sidebar Card** (Line 66, 67, 71, 72, 85): `bg-[#111] border border-white/5` 👉 `bg-[#FFFFFF] border border-[#E0DACE] shadow-lg`; Header `text-white border-white/10` 👉 `text-[#111111] border-[#E0DACE]`; Labels `text-gray-500` 👉 `text-[#555555]`; Values `text-white` 👉 `text-[#111111]`; Button `bg-[#d4af37] text-black shadow-[#d4af37]/10` 👉 `bg-[#C5A880] text-white shadow-[#C5A880]/20`
- **Lightbox Overlay** (Line 110): Index text `text-[#d4af37]` 👉 `text-[#C5A880]`

#### 5. `resources/js/views/public/ProjectsView.vue`
- **Main Canvas & Header** (Line 2, 4, 11, 14, 17, 22): `bg-[#0a0a0a]`, `bg-black`, Overlay `from-black/60 via-transparent to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`; Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Line `bg-[#d4af37]` 👉 `bg-[#C5A880]`
- **Skeleton & Empty State** (Line 37, 50, 55, 56): Skeleton `bg-white/5` 👉 `bg-[#E0DACE]/30`; Empty box `bg-white/5 text-gray-700` 👉 `bg-[#F0ECE1] text-[#555555]`; Text `text-gray-500`, `text-gray-600` 👉 `text-[#555555]`
- **Load More Button** (Line 64): `text-white border-white/10 hover:bg-[#d4af37] hover:text-black hover:border-[#d4af37]` 👉 `text-[#111111] border-[#E0DACE] hover:bg-[#C5A880] hover:text-white hover:border-[#C5A880]`

#### 6. `resources/js/views/public/ServiceDetailView.vue`
- **Loading & Canvas** (Line 2, 3, 6): `bg-black`, `bg-[#0a0a0a]` 👉 `bg-[#F7F5F0]`
- **Header & Overlay** (Line 8, 10, 13, 14): `bg-black`, Overlay `from-black/80 via-black/40 to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`; Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`
- **Overview & Gallery** (Lines 27, 28, 35, 37): Headers `text-[#d4af37]` 👉 `text-[#C5A880]`; Body `text-gray-300` 👉 `text-[#555555]`; Gallery item `bg-white/5 border border-white/10` 👉 `bg-[#F0ECE1] border border-[#E0DACE]`
- **Sidebar Inquiry Card** (Line 46, 47, 48, 49): `bg-gradient-to-br from-[#111] to-black border border-white/5` 👉 `bg-[#FFFFFF] border border-[#E0DACE] shadow-lg`; Title `text-white` 👉 `text-[#111111]`; Text `text-gray-500` 👉 `text-[#555555]`; Button `bg-[#d4af37] text-black hover:bg-white` 👉 `bg-[#C5A880] text-white hover:bg-[#111111]`

#### 7. `resources/js/views/public/ServicesView.vue`
- **Main Canvas & Header** (Line 2, 4, 10, 11, 14, 17, 22): `bg-[#0a0a0a]`, `bg-[#050505]`, `bg-black/50`, Overlay `from-black/60 via-transparent to-[#0a0a0a]` 👉 `bg-[#F7F5F0]`, `from-[#F7F5F0]/80 via-transparent to-[#F7F5F0]`; Subhead `text-[#d4af37]` 👉 `text-[#C5A880]`; Title `text-white` 👉 `text-[#111111]`; Line `bg-[#d4af37]` 👉 `bg-[#C5A880]`
- **Skeleton & Empty State** (Line 30, 42): Skeleton `bg-white/5` 👉 `bg-[#E0DACE]/30`; Empty text `text-gray-500` 👉 `text-[#555555]`

---

## 5. Verification Roadmap

1. **Compilation Check**: Run `npm run build` after changes to confirm zero Vite/PostCSS compilation errors.
2. **Automated Test Check**: Run `php artisan test` to confirm backend API & router test suite passes clean.
3. **Visual Verification Matrix**:
   - Background canvas renders warm off-white `#F7F5F0`.
   - Card components render crisp `#FFFFFF` with `#E0DACE` subtle borders.
   - Text headers render in sharp `#111111` charcoal black.
   - Accents, active filters, and primary buttons highlight in warm gold `#C5A880`.
   - Mobile menu & footer transition seamlessly with clean light-mode elements.

---
*Report completed by `explorer_1`.*
