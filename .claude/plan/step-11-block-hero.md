# Step 11 — Hero Block

**Phase:** 4 — Content Blocks  
**Depends on:** Steps 01, 07  
**Required by:** Steps 23, 24

---

## Summary

Build an ACF Gutenberg block with two layout variants: **Image Background** (full-width banner with text overlay) and **Split** (text on the left, image on the right). Both variants support a heading, subheading, and up to two CTA buttons.

---

## User Stories

- **As a site editor**, I want to choose between a cinematic full-image hero and a clean split layout from the block settings so I can match the page's visual tone.
- **As a visitor**, I want to immediately understand what the company offers and have a clear next step so I can decide whether to engage further.

---

## Business Value

The hero block is the single most impactful element on any landing page. Providing two distinct variants covers both agency (bold visual) and freelancer (clean, personal) use cases without requiring two separate blocks.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php` with a block preview image in `assets/media/block-preview/`.
- [ ] ACF field group for the block created in WP admin and saved to `acf-json/`.
- [ ] **Variant: Image Background** — full-width section, background image with configurable overlay opacity, text centred or left-aligned, white text.
- [ ] **Variant: Split** — two-column layout: text column (left) with dark text, image column (right); reverses to image-above-text on mobile.
- [ ] Heading rendered as `<h1>` when the block is the first block on the page, `<h2>` otherwise — controlled via an ACF True/False field "Is Page Title".
- [ ] Primary CTA uses `variant: primary` Button component; secondary CTA uses `variant: outline`.
- [ ] Background image uses `wp_get_attachment_image()` with the `hero-full` image size.
- [ ] Block is responsive: full-screen height optional on desktop, reasonable min-height on mobile.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/hero.php` | Block template |
| `src/css/blocks/hero.css` | Block styles (imported into `main.css`) |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/hero` block |
| `src/css/main.css` | Import `blocks/hero.css` |

---

## ACF Field Group: Hero Block

> Generated directly into `acf-json/` — loaded by ACF as Local JSON.

| Field label | Field type | Notes |
|---|---|---|
| Variant | Select | Image Background, Split |
| Is Page Title | True/False | If true, heading is `<h1>`; default false → `<h2>` |
| Heading | Text | Main headline |
| Subheading | Textarea | Supporting text, 1–2 sentences |
| Primary CTA Label | Text | |
| Primary CTA URL | URL | |
| Secondary CTA Label | Text | Optional |
| Secondary CTA URL | URL | Optional |
| Background Image | Image | Used by both variants (overlay in Image Bg; right column in Split) |
| Overlay Opacity | Range | 0–100, default 50; conditional: only shown for Image Background variant |
| Text Alignment | Select | Left, Centre; conditional: only shown for Image Background variant |

---

## Accessibility

- [ ] Heading level appropriate (`h1` or `h2` based on field)
- [ ] Background image used as CSS background (decorative) when in Image Background variant; meaningful image in Split variant has alt text
- [ ] Overlay opacity sufficient for text contrast ≥ 4.5:1
- [ ] CTAs are descriptive links, not generic "Click here"

---

## Out of Scope

- Video background variant (not selected in survey)
- Full-screen height toggle per variant
- Animated text or particle effects
