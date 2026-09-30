# Legacy content — www.ende.com.ec (Adobe Muse)

Verbatim extraction of the legacy static Muse site, captured 2026-09-30 for the Astro rebuild. Source: https://www.ende.com.ec/ (robots.txt Crawl-delay: 10; requests throttled ~2–3s).

## Pages

| Legacy page | Content file | Content images listed | Notes |
| --- | --- | --- | --- |
| index.html | [index.md](index.md) | 16 | Home is a slideshow (5 photos + 5 rasterized caption images). "1990 - 2016" footer text only exists in mobile breakpoints. |
| filosofia.html | [filosofia.md](filosofia.md) | 8 | 3-panel pamphlet widget; CSS panel backgrounds mision/vision/politicas.png; tab↔panel pairing inconsistent on original. |
| radiografia.html | [radiografia.md](radiografia.md) | 10 | |
| ultrasonido.html | [ultrasonido.md](ultrasonido.md) | 9 | |
| particulas-magneticas.html | [particulas-magneticas.md](particulas-magneticas.md) | 10 | |
| liquidos-penetrantes.html | [liquidos-penetrantes.md](liquidos-penetrantes.md) | 8 | |
| otras-tecnicas.html | [otras-tecnicas.md](otras-tecnicas.md) | 19 | PMI, dureza, calificación de soldadores, capacitación sections with photo galleries. |
| equipos.html | [equipos.md](equipos.md) | 12 | 7 equipment categories, one image each. |
| sistemas-de-gestion.html | [sistemas-de-gestion.md](sistemas-de-gestion.md) | 7 | ISO 9001:2015 + ISO/IEC 17020:2013 SAE accreditation. |
| factura-electronica.html | [factura-electronica.md](factura-electronica.md) | 10 | Links to external portal https://server.fcelectronica.com/docelectronicos/index.html (status not verified, likely legacy/limited availability). |
| contacto.html | [contacto.md](contacto.md) | 9 | Form backed by scripts/form-u8098.php (not extracted); address, phones, email, quejas procedure + downloads; Google Maps embed. |

Business data: [business-data.md](business-data.md)

## Images

- All content images referenced by the 11 pages (HTML `img` / `data-orig-src` plus CSS `url()` backgrounds) were downloaded to `src/assets/legacy/` (198 files, ~26 MB). Original filenames preserved, normalized to lowercase kebab-case (%20 → `-`).
- Muse generates responsive breakpoint variants (e.g. `liqui1327x208.png`, `liqui1335x214.png`, `liqui1374x238.png` are resizes of `liqui1.png`). All variants were downloaded and kept; the Astro rebuild can use the base image plus its own responsive pipeline and ignore the variants.
- Skipped Muse chrome (not content): `images/blank.gif` (lazy-load placeholder), `images/loading.gif` (slideshow loader, downloaded but chrome), `images/menu45blanco.png` (mobile hamburger icon, downloaded but chrome), `componentes-favicon.ico`, `ende-cia.-ltda-favicon.ico`, `servicios-favicon.ico` (not downloaded; all are the same favicon file, crc 43309573).

## Documents

- `public/docs/quejas.pdf` — from `Descargas/quejas.pdf` (valid PDF 1.7, ~970 KB)
- `public/docs/r-mjc-09-rev02.xlsx` — from `Descargas/R-MJC-09-REV02.xlsx`
- DEAD on the live site at extraction time (404): `Descargas/P-MJC-05%20REV04%20ACTUAL.pdf` and `Descargas/Quejas.xlsx` (both linked from contacto.html text).

## Extraction method

Raw HTML downloaded to a temp dir (deleted after extraction). Text, images and links were extracted from the first (desktop) breakpoint (`bp_infinity`) only; Muse duplicates the whole DOM per responsive breakpoint (copies carry `temp_no_id` / `data-orig-id`), so first-breakpoint extraction yields each text block exactly once in reading order. Page titles from `<title>`; meta description preserved in business-data.md.
