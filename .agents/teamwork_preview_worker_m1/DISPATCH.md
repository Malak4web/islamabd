## 2026-08-08T09:44:43Z

<USER_REQUEST>
You are a Worker subagent implementing Milestone M1 for project c:\xampp\htdocs\islamabd.
Your working directory is c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m1.

MANDATORY READS:
- Read `c:\xampp\htdocs\islamabd\.agents\ORIGINAL_REQUEST.md`
- Read `c:\xampp\htdocs\islamabd\PROJECT.md`
- Read `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_explorer_survey_1\handoff.md`

MANDATORY INTEGRITY WARNING:
DO NOT CHEAT. All implementations must be genuine. DO NOT hardcode test results, create dummy/facade implementations, or circumvent the intended task. A teamwork_preview_auditor will independently verify your work. Integrity violations WILL be detected and your work WILL be rejected.

EXCLUSIVE WRITE OWNERSHIP:
- `resources/js/components/public/HeroSlider.vue`
- `resources/js/components/public/AboutSnippet.vue`
- `resources/js/components/public/ServicesPreview.vue`
- `resources/js/components/public/ProjectsPreview.vue`
- `resources/js/components/public/CtaBanner.vue`

TASK (Milestone M1: Home Page Hero & Overlay Harmonization):
1. In `HeroSlider.vue`:
   - Replace dark gradient overlay `from-black/50 via-black/30` with `from-[#F7F5F0]/70 via-[#F7F5F0]/30 to-[#F7F5F0]`.
   - Replace hero title text `text-white` with `text-[#111111]`.
   - Update text gradient span from `from-white via-white/90 to-[#C5A880]` to `from-[#111111] via-[#222222] to-[#C5A880]`.
   - Update paragraph text from `text-gray-200` to `text-[#444444]`.
   - Update secondary button styling to `border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`.
   - Update non-active dots from `bg-white/30 hover:bg-white/60` to `bg-[#111111]/20 hover:bg-[#111111]/50`.
2. In `AboutSnippet.vue`:
   - Replace dark overlay `from-black/40 to-transparent` with `from-[#F7F5F0]/40 via-transparent to-transparent`.
   - Update paragraph text from `text-[#555555]` to `text-[#444444]`.
3. In `ServicesPreview.vue`:
   - Check section styling and ensure text/borders align with light theme.
4. In `ProjectsPreview.vue`:
   - Update secondary button styling to `border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`.
5. In `CtaBanner.vue`:
   - Update paragraph text from `text-[#555555]` to `text-[#444444]`.
   - Update secondary button styling to `border border-[#111111]/20 text-[#111111] hover:bg-[#111111] hover:text-white`.

VERIFICATION:
- Run `npm run build` and verify 0 compilation errors.
- Run `php artisan test` and verify 100% tests passing.
- Run `npm run test` and verify 100% frontend tests passing.

Write report to `c:\xampp\htdocs\islamabd\.agents\teamwork_preview_worker_m1\handoff.md` and send message when complete.
</USER_REQUEST>
