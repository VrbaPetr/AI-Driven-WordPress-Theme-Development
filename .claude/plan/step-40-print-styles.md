# Step 40 — Print Styles

**Phase:** 10 — Site UX & Utilities  
**Depends on:** Steps 01, 02  
**Required by:** nothing (end feature)

---

## Summary

Add a dedicated `src/css/pages/print.css` file containing all `@media print` rules. The file is imported into `main.css`. Print styles hide chrome elements that have no meaning on paper (header, footer, navigation, buttons, cookie consent), expose full link URLs inline, lock body typography to print-safe values, prevent awkward page breaks inside headings and images, and constrain image widths to the page.

---

## User Stories

- **As a visitor who prints an article or service page**, I want a clean, readable printout that shows only the content and hides navigation, buttons, and banners so the page wastes no ink on elements that don't work on paper.
- **As a visitor reading a printed page**, I want to see the full URL next to every hyperlink so I can revisit the page later even without clicking.
- **As a site owner**, I want headings and images to stay intact across page breaks so printouts look professional and are easy to read.

---

## Business Value

Print styles are a low-effort, high-trust signal. Service-oriented and content-heavy sites are often printed or saved as PDF for offline sharing, proposals, or records. A clean printout reflects positively on the brand and avoids embarrassing layouts with half-rendered navigation bars and empty button outlines.

---

## Acceptance Criteria

### Hidden Elements

- [ ] `.site-header` — hidden.
- [ ] `.site-footer` — hidden.
- [ ] `.back-to-top` — hidden.
- [ ] `[role="navigation"]` — hidden.
- [ ] `.wp-block-button`, `.wp-block-buttons` — hidden.
- [ ] `.breadcrumbs` — hidden.
- [ ] `.pagination` — hidden.
- [ ] Cookie consent elements (`.cookie-notice`, `.cookie-banner`, `#cookie-law-info-bar`, `#cky-consent`) — hidden.

### Body Typography

- [ ] `body` sets `color: #000`, `background: #fff`, `font-size: 12pt`, `line-height: 1.5`.
- [ ] No Tailwind utility class or CSS custom property is used for these values — plain CSS values only, as print context has no guarantee the custom property cascade resolves correctly.

### Link URL Exposure

- [ ] `a[href]::after` outputs ` (` + the `href` attribute + `)` so printed URLs are visible.
- [ ] Exclude fragment-only links: `a[href^="#"]::after { content: none; }`.
- [ ] Exclude JavaScript pseudo-links: `a[href^="javascript:"]::after { content: none; }`.

### Images

- [ ] `img` sets `max-width: 100%` and `page-break-inside: avoid` (aliased as `break-inside: avoid`).

### Page Break Rules

- [ ] `h1, h2, h3, h4, h5, h6` — `page-break-after: avoid` (aliased as `break-after: avoid`) and `page-break-inside: avoid` (aliased as `break-inside: avoid`).
- [ ] `figure` — `page-break-inside: avoid` (aliased as `break-inside: avoid`).

### Implementation

- [ ] All rules are wrapped inside a single `@media print { }` block.
- [ ] File is imported in `src/css/main.css` at the end of the page imports section.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `src/css/pages/print.css` | All `@media print` rules for the theme |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Add `@import "pages/print.css";` at the end of the page-level imports |

---

## Notes

- Plain `#000` and `#fff` values are intentional — CSS custom properties defined in `:root` are not guaranteed to be available in print context across all browsers, so absolute values are used here as an exception to the token-only color rule.
- Both legacy (`page-break-*`) and modern (`break-*`) properties are declared together for maximum browser compatibility in print engines (notably older versions of WebKit used in macOS Print to PDF).
- The `@media print` block does not need a Tailwind `@layer` wrapper — it is plain CSS and will not conflict with utility classes.

---

## Out of Scope

- Print-specific header with logo (e.g. replacing the nav with a printed logo image)
- Page margin/size control via `@page` rules
- Print-specific table of contents or running headers/footers
- Per-block or per-page-template print overrides
