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
- [ ] 4. Home page: hero, featured services, certifications (ASNT, ASME, AWS, API), contact CTA.
- [ ] 5. Services: content collection, `/servicios` index, 5 technique pages.
- [ ] 6. Institutional pages: nosotros, equipos, sistemas de gestión (factura electrónica without link).
- [ ] 7. SEO and performance: metadata, sitemap, structured data, Lighthouse pass.
- [ ] 8. Contact page and form (PHP handler on hosting).
- [ ] 9. Deploy: build and upload guide for mysitearea.com file manager.

## Evidence

(commit identities recorded per task)

- Task 1: `825595b` chore: scaffold Astro site with Tailwind and ENDE brand tokens. `pnpm build` OK, `pnpm check` 0 errors. Native review not applicable: root commit has no base (empty_candidate_base_ref_required).
- Task 2: `4de0a00` docs: extract legacy ende.com.ec content and assets. 11/11 pages, 210 images (27 MB, ~60 are breakpoint variants), `public/docs/quejas.pdf` + `r-mjc-09-rev02.xlsx`. Dead links: P-MJC-05 REV04 PDF, Quejas.xlsx (404). Review skipped: passive docs/assets only.
- Task 3: `1d1a8a7` feat: add base layout, header, footer and UI components. `pnpm build` OK, `pnpm check` 0/0/0. Independent verify: pass-with-notes; fixed dark eyebrow contrast (brand-300 -> brand-100), focus outline (brand-500 -> brand-700), copyright double period, Card nesting note. Open: API link uses api.org (legacy used americanpetroleuminstitute.com, which redirects); no og:image yet (task 7).

## Content findings

- Address: Av. Amazonas N26-179 y Av. Orellana, Edificio Torrealba PH (piso 11), Of. 1101, Quito. Phones 2529713 / 2526229 / 0992525570; quejas 0992527759; ende@ende.com.ec; Facebook page.
- Certifications: ISO 9001:2015; ISO/IEC 17020:2013 accredited inspection body SAE-ACR-0124-2021; ASNT Level III, AWS-CWI personnel. Member logos ASME, ASNT, AWS, API.
- Legacy Misión/Visión/Política panels pair texts and backgrounds inconsistently; use text by tab label.
