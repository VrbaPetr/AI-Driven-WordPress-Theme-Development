# Step 46 — Welcome Slider Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Steps 01, 07, 36  
**Required by:** nothing (typically placed on `front-page.php`)

---

## Summary

Build an ACF Gutenberg block that renders a full-viewport carousel slider for homepages. Each slide is a full-bleed section with an optional background image, a configurable colour or gradient overlay, a display-scale heading, a subheading, and up to two CTA buttons. Alpine.js manages slide state, autoplay, arrow navigation, and dot indicators entirely on the client side with no jQuery dependency.

---

## User Stories

- **As a site editor**, I want to create a visually immersive homepage slider with multiple slides so I can showcase different service areas or value propositions in sequence without building separate hero sections.
- **As a site editor**, I want to control autoplay speed, arrows, and dot indicators from the block settings so I can tailor the slider behaviour to each project without writing code.
- **As a visitor**, I want autoplay to pause when I hover over or focus into the slider so fast-moving content does not impede my reading or interaction.
- **As a visitor on a screen reader**, I want each slide's heading and body copy to be announced correctly and navigation controls to be labelled so I can use the slider without visual cues.

---

## Business Value

A full-viewport welcome slider is the most-requested homepage hero pattern for IT company websites. Providing it as a reusable, accessible block with fine-grained editor controls reduces per-project customisation time and ensures accessibility compliance out of the box.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/welcome-slider`.
- [ ] Block preview image placed in `assets/media/block-preview/welcome-slider.jpg`.
- [ ] ACF field group generated as `acf-json/group_welcome_slider_block.json`.

### Layout & Sizing

- [ ] Slider wrapper is `min-height: 100svh` with a `min-height: 100vh` fallback for browsers that do not support `svh` units.
- [ ] Each slide fills the full wrapper height (`height: 100%`).
- [ ] Background image applied via inline `style="background-image: url(…)"` using `wp_get_attachment_image_src()` — not an `<img>` tag.
- [ ] Background is `background-size: cover` and `background-position: center`.

### Overlay

- [ ] Overlay is an absolutely positioned `inset-0` `<div>` rendered inside the slide when `enable_overlay` is true.
- [ ] Overlay colour and gradient are expressed as CSS custom properties referencing design tokens — no hardcoded hex or OKLCH values.
- [ ] Three overlay styles are supported: `solid` (flat colour at chosen opacity), `gradient-bottom` (transparent top → colour at bottom), `gradient-center` (colour edges → transparent centre).
- [ ] Overlay opacity is applied via an inline CSS custom property (`--slide-overlay-opacity`) set from the ACF field value divided by 100.
- [ ] `overlay_style` and `overlay_opacity` fields are conditional on `enable_overlay` being true.

### Slide Content

- [ ] Heading on the **first slide** rendered as `<h1>`; all subsequent slides rendered as `<h2>`.
- [ ] Heading uses the `--font-size-display` token (added to `src/css/variables/typography.css` if not already present).
- [ ] Subheading rendered as `<p>` — not a heading element — when the field is non-empty.
- [ ] Text alignment (left / center / right) applied via a utility class on the slide content wrapper.
- [ ] Primary CTA uses `variant: primary` Button component; secondary CTA uses `variant: outline`.
- [ ] Both CTA buttons are conditionally rendered — neither label nor URL is required.

### Alpine.js Behaviour

- [ ] Alpine `x-data` object on the slider wrapper contains: `currentSlide`, `total`, `autoplayTimer`, `autoplayEnabled`, `autoplayInterval`.
- [ ] Field values for `autoplay`, `autoplay_interval`, `show_arrows`, and `show_dots` are passed into `x-data` via PHP-rendered inline values (not `data-*` attributes).
- [ ] Autoplay starts on `x-init` when `autoplayEnabled` is true; each tick advances `currentSlide` with wraparound.
- [ ] Autoplay pauses on `@mouseenter` and `@focusin` on the slider wrapper.
- [ ] Autoplay resumes on `@mouseleave` and `@focusout` on the slider wrapper.
- [ ] Previous / next arrow buttons are rendered conditionally when `show_arrows` is true.
- [ ] Dot indicators are rendered conditionally when `show_dots` is true; the active dot has a distinct visual state.
- [ ] Each slide has `x-show="currentSlide === index"` with a short CSS cross-fade transition.

### Markup & Semantics

- [ ] Slider wrapper has `role="region"` and `aria-label` (e.g. "Welcome slider" or from an ACF label field).
- [ ] Arrow buttons have descriptive `aria-label` values ("Previous slide", "Next slide").
- [ ] Each dot button has `aria-label="Go to slide N"` and `aria-current="true"` on the active dot.
- [ ] Arrow and dot buttons hidden from assistive technology when `show_arrows` / `show_dots` is false (rendered with `x-show`, not just visually hidden).
- [ ] All output escaped (`esc_url()`, `esc_html()`, `esc_attr()`).
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_welcome_slider_block.json` | ACF field group — all slider and slide fields |
| `template-parts/blocks/welcome-slider.php` | Block template — Alpine wrapper, slides loop, arrows, dots |
| `src/css/blocks/welcome-slider.css` | Block styles — viewport sizing, slide transitions, overlay variants, arrow and dot styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/welcome-slider` block |
| `src/css/main.css` | Add `@import "blocks/welcome-slider.css"` |
| `src/css/variables/typography.css` | Add `--font-size-display` token if not already defined |

---

## ACF Field Group: Welcome Slider Block

> Generated directly into `acf-json/` — loaded by ACF as Local JSON.

### Top-level fields

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Autoplay | `autoplay` | True/False | Default: true |
| Autoplay Interval (seconds) | `autoplay_interval` | Number | Min 1, max 30, default 5 |
| Show Arrows | `show_arrows` | True/False | Default: true |
| Show Dots | `show_dots` | True/False | Default: true |
| Slides | `slides` | Repeater | Min 1 row; sub-fields below |

### Slides repeater sub-fields

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Background Image | `background_image` | Image | Return format: array |
| Enable Overlay | `enable_overlay` | True/False | Default: false |
| Overlay Style | `overlay_style` | Select | `solid`, `gradient-bottom`, `gradient-center`; conditional on `enable_overlay` |
| Overlay Opacity | `overlay_opacity` | Number | 0–100, default 50; conditional on `enable_overlay` |
| Heading | `heading` | Text | Required |
| Subheading | `subheading` | Textarea | Optional; new lines preserved |
| Text Alignment | `text_alignment` | Select | `left`, `center`, `right`; default `center` |
| Primary Button Label | `primary_button_label` | Text | Optional |
| Primary Button URL | `primary_button_url` | URL | Optional |
| Secondary Button Label | `secondary_button_label` | Text | Optional |
| Secondary Button URL | `secondary_button_url` | URL | Optional |

---

## Alpine.js Data Object

The `x-data` attribute on the slider wrapper is rendered by PHP to embed field values directly:

```php
x-data="{
    currentSlide: 0,
    total: <?php echo intval( count( $slides ) ); ?>,
    autoplayEnabled: <?php echo $autoplay ? 'true' : 'false'; ?>,
    autoplayInterval: <?php echo intval( $autoplay_interval ) * 1000; ?>,
    autoplayTimer: null,
    init() {
        if ( this.autoplayEnabled ) { this.startAutoplay(); }
    },
    startAutoplay() {
        this.autoplayTimer = setInterval( () => {
            this.currentSlide = ( this.currentSlide + 1 ) % this.total;
        }, this.autoplayInterval );
    },
    stopAutoplay() {
        clearInterval( this.autoplayTimer );
        this.autoplayTimer = null;
    },
    prev() { this.currentSlide = ( this.currentSlide - 1 + this.total ) % this.total; },
    next() { this.currentSlide = ( this.currentSlide + 1 ) % this.total; }
}"
```

Pause / resume hooks go on the wrapper element:

```html
@mouseenter="stopAutoplay()"
@mouseleave="if (autoplayEnabled) startAutoplay()"
@focusin="stopAutoplay()"
@focusout="if (autoplayEnabled) startAutoplay()"
```

---

## Overlay CSS Pattern

Each overlay style is expressed with CSS custom properties so no hardcoded colour values appear in the template or stylesheet:

```css
.slide-overlay--solid {
    background-color: color-mix(in oklch, var(--color-overlay) calc(var(--slide-overlay-opacity) * 1%), transparent);
}

.slide-overlay--gradient-bottom {
    background: linear-gradient(
        to top,
        color-mix(in oklch, var(--color-overlay) calc(var(--slide-overlay-opacity) * 1%), transparent),
        transparent
    );
}

.slide-overlay--gradient-center {
    background: radial-gradient(
        ellipse at center,
        transparent 20%,
        color-mix(in oklch, var(--color-overlay) calc(var(--slide-overlay-opacity) * 1%), transparent)
    );
}
```

`--color-overlay` must be defined in `src/css/variables/colors.css` — typically mapped to a dark neutral token. Do not add it here if it is not already present; ask the user to add it first.

---

## Accessibility Checklist

- [ ] Heading level is `h1` on the first slide only — subsequent slides use `h2` — correct for SEO and document outline
- [ ] Arrow and dot controls are `<button>` elements, not `<div>` or `<span>`
- [ ] Arrow buttons have `aria-label="Previous slide"` and `aria-label="Next slide"`
- [ ] Active dot has `aria-current="true"`; all dots have `aria-label="Go to slide N"`
- [ ] Autoplay pauses on `mouseenter` and `focusin` (WCAG 2.1 SC 2.2.2 — Pause, Stop, Hide)
- [ ] Slide content not hidden from the accessibility tree when the slide is visually hidden — use `x-show` (which toggles `display`) rather than opacity/visibility-only hiding, or add `aria-hidden` on inactive slides
- [ ] Text contrast over background image meets 4.5:1 with overlay enabled at default opacity
- [ ] Slider region has `role="region"` and a meaningful `aria-label`
- [ ] No autoplay motion if the user prefers reduced motion (`@media (prefers-reduced-motion: reduce)` disables transitions)

---

## Out of Scope

- Video background slides
- Thumbnail strip navigation
- Touch / swipe gesture support (can be added in a later step)
- Per-slide custom overlay colour (single overlay colour token shared across all slides)
- Lazy loading of off-screen slide background images
- Animated text entrance effects
