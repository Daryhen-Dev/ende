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

- [ ] 1. Project scaffold: Astro, Tailwind, ENDE palette tokens, typography, feature branch.
- [ ] 2. Content extraction: download 11 legacy pages, move text to Markdown, fetch images/logos/PDFs.
- [ ] 3. Design system: layout, responsive header/menu, footer, hero/card/section/button components.
- [ ] 4. Home page: hero, featured services, certifications (ASNT, ASME, AWS, API), contact CTA.
- [ ] 5. Services: content collection, `/servicios` index, 5 technique pages.
- [ ] 6. Institutional pages: nosotros, equipos, sistemas de gestión (factura electrónica without link).
- [ ] 7. SEO and performance: metadata, sitemap, structured data, Lighthouse pass.
- [ ] 8. Contact page and form (PHP handler on hosting).
- [ ] 9. Deploy: build and upload guide for mysitearea.com file manager.

## Evidence

(commit identities recorded per task)
