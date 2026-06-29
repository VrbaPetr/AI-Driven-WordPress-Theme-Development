# Step 12 — Text & Image Block

**Phase:** 4 — Content Blocks  
**Depends on:** Steps 01, 07  
**Required by:** Steps 23, 24

---

## Summary

Build a general-purpose split-content block that pairs a WYSIWYG text area (heading, rich text body) with an image. The editor can toggle the image position (left or right of the text) and optionally add a CTA button below the text.

---

## User Stories

- **As a site editor**, I want to compose "About Us" style sections on any page by choosing this block and uploading an image so I don't need a developer for every layout change.
- **As a developer**, I want a single reusable block for all text+media pairings so I maintain one CSS file instead of several near-identical ones.

---

## Business Value

The text + image pattern is the most common content pattern on IT/agency marketing sites (value props, process highlights, about sections). A flexible block prevents editors from using the Hero block inappropriately for interior content.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Two-column layout: text and image side by side on desktop; stacked (text above image) on mobile.
- [ ] Image position toggle: left or right — controlled via ACF Select field.
- [ ] Heading field rendered as `<h2>` by default.
- [ ] Body text is a WYSIWYG field, rendered with `wp_kses_post()`.
- [ ] Optional CTA button rendered via Button component if label and URL are provided.
- [ ] Image rendered via `wp_get_attachment_image()` with an appropriate registered size.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/text-image.php` | Block template |
| `src/css/blocks/text-image.css` | Block styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/text-image` block |
| `src/css/main.css` | Import `blocks/text-image.css` |

---

## ACF Field Group: Text & Image Block

| Field label | Field type | Notes |
|---|---|---|
| Heading | Text | Renders as `<h2>` |
| Body | WYSIWYG | Restricted toolbar: bold, italic, links, lists |
| CTA Label | Text | Optional |
| CTA URL | URL | Optional |
| Image | Image | |
| Image Position | Select | Left, Right (default: Right) |

---

## Accessibility

- [ ] Image alt text sourced from WP attachment alt field
- [ ] WYSIWYG output sanitised with `wp_kses_post()` before output
- [ ] Column stacking order on mobile: text first, then image (logical reading order)

---

## Out of Scope

- Video embed variant (not selected in survey)
- Full-width media section variant
- Multiple images / gallery
