# Step 20 — Clients / Logos Strip Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Step 01  
**Required by:** Steps 23, 24

---

## Summary

Build an ACF block for displaying client or partner logos. Logos are manually entered via a repeater (each with an image, alt text, and optional link). The editor can choose between a static grid and a scrolling marquee. A greyscale/colour display toggle is available.

---

## User Stories

- **As a site editor**, I want to showcase client logos on the Home page to build trust with new visitors without writing any code.
- **As a site editor**, I want to toggle logos between greyscale and colour so the section can match the page's visual tone.

---

## Business Value

A client logos strip is a high-impact trust signal placed above the fold or just after the hero. Recognisable brand logos shortcut the credibility-building process for new visitors.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Logos entered via ACF Repeater: logo image (Image field), alt text (Text), URL (URL, optional).
- [ ] **Display Style: Static Grid** — logos in a CSS flex/grid row, wrapping on smaller screens.
- [ ] **Display Style: Marquee** — logos scroll horizontally on a loop (CSS animation); pauses on hover and respects `prefers-reduced-motion`.
- [ ] **Colour Mode: Greyscale** — CSS `filter: grayscale(1)` applied; colour on hover.
- [ ] **Colour Mode: Colour** — logos displayed as-is.
- [ ] Optional section heading (`<h2>`) and subtext.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/clients.php` | Block template |
| `src/css/blocks/clients.css` | Marquee animation + grid styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/clients` block |
| `src/css/main.css` | Import `blocks/clients.css` |

---

## ACF Field Group: Clients Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Subtext | Text | Optional supporting sentence |
| Display Style | Select | Static Grid, Marquee |
| Colour Mode | Select | Greyscale, Colour |
| Logos | Repeater | |
| — Logo Image | Image | Recommend SVG or PNG with transparency |
| — Alt Text | Text | Describes the logo/brand (e.g. "Acme Corp logo") |
| — Link URL | URL | Optional; wraps logo in `<a>` |

---

## Marquee Animation

Pure CSS keyframe scroll — no JS required:

```css
@keyframes marquee {
  from { transform: translateX(0); }
  to   { transform: translateX(-50%); }
}
.marquee__track { animation: marquee 30s linear infinite; }
.marquee__track:hover { animation-play-state: paused; }
@media (prefers-reduced-motion: reduce) {
  .marquee__track { animation: none; }
}
```

Logos are duplicated in the HTML to create a seamless loop.

---

## Accessibility

- [ ] Each logo has meaningful alt text (not empty — these are brand identifiers)
- [ ] Linked logos have `aria-label` describing the destination or the brand
- [ ] Marquee pauses on focus for keyboard navigation
- [ ] `prefers-reduced-motion`: marquee displays as static grid

---

## Out of Scope

- Logos pulled from a CPT (manual repeater only per survey selection)
- Lightbox on logo click
