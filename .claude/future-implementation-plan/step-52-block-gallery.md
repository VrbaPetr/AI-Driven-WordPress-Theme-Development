# Step 52 — Gallery / Image Grid Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Step 01 (theme foundation), Step 36 (section_subheading pattern)  
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block that renders a uniform image grid for office photos, culture sections, and events. The editor uploads images via a single ACF Gallery field and controls the number of columns (2, 3, or 4). Each image is rendered as a `<figure>` with `wp_get_attachment_image()` and lazy loading. When the optional lightbox is enabled, an Alpine.js-powered overlay replaces the need for any external library — clicking an image opens a full-size view with previous/next navigation, a close button, and ESC key dismissal.

---

## User Stories

- **As a site editor**, I want to add a grid of photos to any page so I can showcase office culture, event highlights, or project imagery without involving a developer.
- **As a site editor**, I want to choose the number of columns so the grid adapts to the quantity and aspect ratio of the images I have.
- **As a site editor**, I want to enable an optional lightbox so visitors can view full-size images without leaving the page.
- **As a visitor**, I want to navigate between lightbox images using arrow buttons or the keyboard so I do not have to close and re-open the overlay for each photo.
- **As a visitor on a screen reader**, I want the lightbox to be announced correctly and to be dismissible with the keyboard so I can use the feature without a mouse.

---

## Business Value

Image grids are a standard content pattern on IT company and agency sites — team culture pages, event recaps, and project galleries all rely on them. A single configurable block with an optional lightbox replaces ad-hoc shortcodes and plugin dependencies, keeps the asset pipeline centralised, and gives editors a polished experience without writing code.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/gallery-image-grid`.
- [ ] Block title is `Gallery / Image Grid`; category is `formatting` or the theme's custom block category.
- [ ] Block preview image exists at `assets/media/block-preview/gallery-image-grid.jpg` (or `.png`).
- [ ] ACF field group generated as `acf-json/group_gallery_image_grid_block.json`.

### ACF Fields

- [ ] `section_heading` (Text, not required) — renders as `<h2>` when non-empty.
- [ ] `section_subheading` (Text, not required) — renders as `<p class="block-subheading">` when non-empty; follows the Step 36 pattern.
- [ ] `images` (Gallery, required) — return format `array`; stores image objects with at minimum `ID`, `url`, `alt`, `title`.
- [ ] `columns` (Select, required, default `3`) — choices: `2 => 2 Columns`, `3 => 3 Columns`, `4 => 4 Columns`.
- [ ] `enable_lightbox` (True/False, default false) — when true, Alpine.js lightbox overlay is rendered and click handlers are attached.

### Template Rendering — Grid

- [ ] Section heading renders as `<h2>` only when the field is non-empty; escaped with `esc_html()`.
- [ ] Section subheading renders as `<p class="block-subheading">` only when the field is non-empty; escaped with `esc_html()`.
- [ ] Images array is retrieved with `get_field( 'images' )` and iterated with a standard `foreach`.
- [ ] Each image renders as a `<figure>` element containing `wp_get_attachment_image()`.
- [ ] `wp_get_attachment_image()` is called with the image `ID` and a registered image size appropriate for the grid (e.g. `gallery-grid`); never `'full'`.
- [ ] `loading="lazy"` is applied to every image via the `$attr` argument of `wp_get_attachment_image()`.
- [ ] The `<figure>` `alt` text is sourced from the image object's `alt` key; falls back to the `title` key if `alt` is empty; escaped with `esc_attr()`.
- [ ] The grid wrapper receives a `data-columns` attribute reflecting the `columns` field value.
- [ ] When `images` is empty or not set, the block renders nothing (no empty wrapper markup).

### Template Rendering — Lightbox

- [ ] When `enable_lightbox` is false, images are rendered as plain `<figure>` elements with no JS attributes.
- [ ] When `enable_lightbox` is true, the outer grid wrapper carries Alpine `x-data` with `lightboxOpen` (bool, initially false), `currentIndex` (int, initially 0), and `total` (int, image count).
- [ ] Each `<figure>` is a `<button>`-wrapped or `@click`-instrumented element that sets `currentIndex` to its index and `lightboxOpen = true`.
- [ ] The lightbox overlay is a `<div>` with `x-show="lightboxOpen"` positioned `fixed inset-0` with a dark semi-transparent background; it sits above all page content via a high `z-index` token.
- [ ] The overlay displays the full-size image using the image object's `url` in an `<img>` tag with the appropriate `alt` text; `loading="eager"` on the active lightbox image.
- [ ] A previous button decrements `currentIndex` with wraparound: `currentIndex = (currentIndex - 1 + total) % total`.
- [ ] A next button increments `currentIndex` with wraparound: `currentIndex = (currentIndex + 1) % total`.
- [ ] A close button sets `lightboxOpen = false`.
- [ ] ESC key closes the lightbox via `@keydown.escape.window="lightboxOpen = false"` on the overlay or wrapper.
- [ ] The lightbox is not rendered in the DOM at all when `enable_lightbox` is false — no empty markup, no Alpine attributes.

### Markup & Semantics

- [ ] Close button has `aria-label="Close lightbox"`.
- [ ] Previous button has `aria-label="Previous image"`.
- [ ] Next button has `aria-label="Next image"`.
- [ ] Lightbox overlay has `role="dialog"`, `aria-modal="true"`, and `aria-label="Image lightbox"`.
- [ ] When the lightbox opens, focus is moved to the overlay or close button (via Alpine `x-effect` or `@click` focus call).
- [ ] All output escaped — `esc_html()`, `esc_attr()`, `esc_url()` — no exceptions.
- [ ] `make check` passes with zero new PHPCS errors.

### Styles

- [ ] CSS grid layout; column count driven by `data-columns` attribute selectors (`[data-columns="2"]`, `[data-columns="3"]`, `[data-columns="4"]`).
- [ ] Images fill their grid cell with `object-fit: cover` and a consistent aspect ratio (e.g. `aspect-ratio: 1 / 1` or `4 / 3` — token-driven, not hardcoded).
- [ ] Responsive: 4-column grid collapses to 2 columns on tablet and 1 on mobile; 3-column collapses to 2 on tablet and 1 on mobile; 2-column collapses to 1 on mobile.
- [ ] When `enable_lightbox` is true, each figure has a subtle hover state (e.g. overlay tint or scale) to signal interactivity — implemented in CSS, not JS.
- [ ] Lightbox overlay background colour uses a token-defined dark colour — no hardcoded hex or OKLCH values.
- [ ] Lightbox image is `max-width: 90vw` / `max-height: 85vh` and centred in the viewport.
- [ ] Navigation and close buttons use token-defined colours; no hardcoded values.
- [ ] `@media (prefers-reduced-motion: reduce)` disables any CSS transitions on grid figures.
- [ ] Only token-defined colors used — no Tailwind built-in palette references, no hardcoded hex or OKLCH values.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_gallery_image_grid_block.json` | ACF field group — all gallery block fields |
| `template-parts/blocks/gallery-image-grid.php` | Block template — grid loop and optional Alpine lightbox |
| `src/css/blocks/gallery-image-grid.css` | Block styles — grid layout, figure hover, lightbox overlay |
| `assets/media/block-preview/gallery-image-grid.jpg` | Editor preview image |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/gallery-image-grid` block |
| `src/css/main.css` | Add `@import "blocks/gallery-image-grid.css"` |
| `inc/functions-design.php` | Register `gallery-grid` image size via `add_image_size()` if not already present |

---

## ACF Field Group

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Not required; renders as `<h2>` |
| Section Subheading | `section_subheading` | Text | Not required; renders as `<p class="block-subheading">` (Step 36 pattern) |
| Images | `images` | Gallery | Required; return format: array |
| Columns | `columns` | Select | Required; choices: `2`, `3` (default), `4` |
| Enable Lightbox | `enable_lightbox` | True/False | Default: false |

ACF field key convention: `field_gallery_image_grid_section_heading`, `field_gallery_image_grid_section_subheading`, `field_gallery_image_grid_images`, `field_gallery_image_grid_columns`, `field_gallery_image_grid_enable_lightbox`.

---

## Alpine.js Data Object

The `x-data` attribute is rendered by PHP only when `enable_lightbox` is true, embedding the image count directly:

```php
x-data="{
    lightboxOpen: false,
    currentIndex: 0,
    total: <?php echo intval( count( $images ) ); ?>,
    open( index ) {
        this.currentIndex = index;
        this.lightboxOpen = true;
    },
    close() {
        this.lightboxOpen = false;
    },
    prev() {
        this.currentIndex = ( this.currentIndex - 1 + this.total ) % this.total;
    },
    next() {
        this.currentIndex = ( this.currentIndex + 1 ) % this.total;
    }
}"
```

The ESC key is handled on the overlay element:

```html
@keydown.escape.window="close()"
```

Per-image click trigger (index passed from PHP loop):

```html
@click="open( <?php echo $index; ?> )"
```

The lightbox full-size image source is rendered using an Alpine expression that selects from a PHP-generated JS array of image URLs:

```php
// PHP renders the image URL array inline:
:src="<?php echo wp_json_encode( array_column( $images, 'url' ) ); ?>[currentIndex]"
```

---

## Notes

- The `images` Gallery field returns an array of image objects when `return_format` is set to `array` in the ACF JSON. Each object contains at minimum `ID`, `url`, `alt`, and `title`. The template accesses these keys directly — no secondary `get_post_meta()` calls needed.
- `wp_get_attachment_image()` is used for the grid thumbnails (registered size, lazy-loaded, srcset/sizes generated automatically by WordPress). The lightbox shows the full-resolution `url` directly via an `<img>` tag because the lightbox overlay operates outside normal flow and needs the largest available source.
- The `gallery-grid` image size should be registered in `inc/functions-design.php` with dimensions appropriate for the largest column width at full viewport (e.g. 800 × 600 or square 600 × 600). If it already exists, do not register it again.
- The lightbox overlay must be appended as the last child of the Alpine `x-data` wrapper so it renders above the grid figures in stacking context without requiring a portal or teleport.
- Because the lightbox is self-contained (no external library, no dynamic `fetch`), the block remains fully functional with the strict CSP applied to Artifacts and does not introduce any new network dependencies.
- After generating `acf-json/group_gallery_image_grid_block.json`, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before building the template.

---

## Out of Scope

- Masonry or Pinterest-style variable-height layouts (uniform grid only)
- Caption display below images (no `<figcaption>` in the initial implementation)
- Per-image links to an attachment page or external URL
- Touch / swipe gesture navigation in the lightbox (can be added in a later step)
- Video items in the gallery
- Lazy loading of off-screen lightbox images (all full-size URLs are embedded in the data array at render time)
- Pagination or "load more" for very large galleries
- Image download button in the lightbox
- Thumbnail strip navigation inside the lightbox overlay
