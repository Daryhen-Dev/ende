# ENDE site rebuild in Astro

Rebuild ende.com.ec (legacy Adobe Muse static site) as a modern Astro site.

## Decisions

- Light, modern style inspired by gentlemanprogramming.com, ENDE blue palette:
  `#29ABE2` (primary sky), `#0071BC` (mid blue), `#2E3192` / `#333399` (navy), `#777777` (muted text).
- Astro + TypeScript + Tailwind CSS, Content Collections, `astro:assets`, sitemap, SEO component.
- New clean URL structure; legacy SEO not preserved (no redirects required).
- Content copied from the legacy site with polished wording; all business data unchanged.
- "Factura electrónica" portal link is down: keep the entry without a link for now.
- Logo source: `ende 3.png` at repo root.
- Hosting: mysitearea.com via file manager upload of `dist/`; PHP supported (contact form option).
- Contact form is the last task.

## Tasks

- [x] 1. Project scaffold: Astro, Tailwind, ENDE palette tokens, typography, feature branch.
- [x] 2. Content extraction: download 11 legacy pages, move text to Markdown, fetch images/logos/PDFs.
- [x] 3. Design system: layout, responsive header/menu, footer, hero/card/section/button components.
- [x] 4. Home page: hero, featured services, certifications (ASNT, ASME, AWS, API), contact CTA.
- [x] 5. Services: content collection, `/servicios` index, 5 technique pages.
- [x] 6. Institutional pages: nosotros, equipos, sistemas de gestión (factura electrónica without link).
- [x] 7. SEO and performance: metadata, sitemap, structured data, Lighthouse pass.
- [ ] 8. Contact page and form (PHP handler on hosting).
- [ ] 9. Deploy: build and upload guide for mysitearea.com file manager.

## Evidence

(commit identities recorded per task)

- Task 1: `825595b` chore: scaffold Astro site with Tailwind and ENDE brand tokens. `pnpm build` OK, `pnpm check` 0 errors. Native review not applicable: root commit has no base (empty_candidate_base_ref_required).
- Task 2: `4de0a00` docs: extract legacy ende.com.ec content and assets. 11/11 pages, 210 images (27 MB, ~60 are breakpoint variants), `public/docs/quejas.pdf` + `r-mjc-09-rev02.xlsx`. Dead links: P-MJC-05 REV04 PDF, Quejas.xlsx (404). Review skipped: passive docs/assets only.
- Task 3: `1d1a8a7` feat: add base layout, header, footer and UI components. `pnpm build` OK, `pnpm check` 0/0/0. Independent verify: pass-with-notes; fixed dark eyebrow contrast (brand-300 -> brand-100), focus outline (brand-500 -> brand-700), copyright double period, Card nesting note. Open: API link uses api.org (legacy used americanpetroleuminstitute.com, which redirects); no og:image yet (task 7). Native review lineage review-953456413c451d66 (lens reliability) APPROVED with 5 advisory findings; acknowledgement returned not-current because HEAD moved (f9965f8 tracker commit) before acknowledging, so authority was not burned.
- Tracker: `f9965f8` docs: record progress for tasks 1-3 (native review low risk, approved + acknowledged).
- Task 5: `c5d762b` feat: add services content collection and technique pages. 7 pages built, check 0/0/0, 30 images (7.8 MB). Native review review-95a63100ad7f4a12 approved + acknowledged; advisory: no render tests, order uniqueness not enforced, sort tiebreaker.
- Task 4: `5aea75c` feat: build home page with hero, services, sectors and quality sections. Build 7 pages, check 0 errors. Native review review-e9998730bfd40385 approved + acknowledged; advisory: membership logo lookup guard, empty phone guard, services sort. Authored copy (h1, leads, CTA) needs owner sign-off.
- Task 6: `1375077` feat: add nosotros, equipos and sistemas de gestion pages. Build 10 pages, check 0 errors. Native review review-761d072a285ca2ce (third START, consent resolved) approved + acknowledged; 5 advisory findings in sistemas-de-gestion.astro and equipos.astro. Owner to update Visión text ("próximos cinco años" is dated).
- Task 7: `19f6774` feat: add SEO metadata, structured data and performance tuning. 11 pages, check 0 errors; JSON-LD 26 blocks valid; 2 font subsets; trailing slashes; robots.txt, .htaccess, 404, og-default.jpg. Lighthouse with compression (serve@14): mobile perf 98 (/) and 100 (/servicios/ultrasonido/), desktop 100; the earlier 57 came from astro preview serving uncompressed. Hero srcset widened to 400/640/800/1150. Native review review-a1025139543b5627 ESCALATED (state escalated, stop native_stop_required, cause unknown_causality, finding R3-CRASH-missing-cover). Parent check: `cover` is required `image()` in src/content.config.ts, so a missing cover fails schema validation at build, not at runtime. Resolved: owner chose to add a guard and re-review the whole range. `b7a51b2` fix: guard service OG image against a missing cover. Native review review-6ab841e4e4254074 over 5b67f37..b7a51b2 (all of task 7 plus the fix) approved and acknowledged; 6 suggestion-level advisory findings (OrganizationSchema, BreadcrumbSchema, astro.config sitemap filter, generate-og script, [slug] guard, BaseLayout props). Escalated lineage review-a1025139543b5627 left as is (terminal).

## Follow-ups (advisory, non-blocking)

- BaseLayout canonical falls back when `site` undefined; footer year baked at build; membership logo lookup unguarded; mobile menu untested in browser; reduced-motion does not cover view transitions.
- Services: enforce unique `order`, add tiebreaker sort.

## Content findings

- Address: Av. Amazonas N26-179 y Av. Orellana, Edificio Torrealba PH (piso 11), Of. 1101, Quito. Phones 2529713 / 2526229 / 0992525570; quejas 0992527759; ende@ende.com.ec; Facebook page.
- Certifications: ISO 9001:2015; ISO/IEC 17020:2013 accredited inspection body SAE-ACR-0124-2021; ASNT Level III, AWS-CWI personnel. Member logos ASME, ASNT, AWS, API.
- Legacy Misión/Visión/Política panels pair texts and backgrounds inconsistently; use text by tab label.
