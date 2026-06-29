# Step 19 — Testimonials Slider Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Steps 01, 03  
**Required by:** Steps 23, 24

---

## Summary

Build an ACF block that pulls testimonials from the Testimonials CPT and displays them as a slider/carousel. Alpine.js handles prev/next navigation and autoplay. The block supports an optional section heading.

---

## User Stories

- **As a site editor**, I want to add new testimonials via the Testimonials CPT and have them appear in the slider automatically so I don't edit block content each time.
- **As a visitor**, I want to read multiple client quotes in a compact slider format so I get social proof without infinite scrolling.

---

## Business Value

Client testimonials are the most credible form of social proof for service businesses. A slider format condenses multiple quotes into a single, engaging section rather than an unwieldy list.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Pulls from Testimonials CPT via `WP_Query`; number of testimonials configurable.
- [ ] Each slide: client photo (thumbnail), quote text, client name, client title/company.
- [ ] Prev/Next navigation buttons.
- [ ] Dot indicators showing current slide position.
- [ ] Optional autoplay with configurable interval; autoplay pauses on hover and focus.
- [ ] Swipe gesture support for touch devices (CSS scroll snap or Alpine.js).
- [ ] Optional section heading (`<h2>`).
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped; `wp_reset_postdata()` called after loop.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/testimonials.php` | Block template |
| `src/css/blocks/testimonials.css` | Slider styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/testimonials` block |
| `src/css/main.css` | Import `blocks/testimonials.css` |

---

## ACF Field Group: Testimonials Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Number of Testimonials | Number | Default: 5 |
| Autoplay | True/False | Default: false |
| Autoplay Interval (ms) | Number | Default: 4000; conditional: Autoplay only |

---

## CPT ACF Fields (created in WP admin for the Testimonial CPT)

| Field label | Field type | Notes |
|---|---|---|
| Quote | Textarea | The testimonial body |
| Client Name | Text | |
| Client Title / Company | Text | e.g. "CTO, Acme Corp" |
| Client Photo | Image | Optional; falls back to a generic avatar |
| Rating | Select | 1–5 stars (optional) |

---

## Alpine.js Component Structure

```html
<div x-data="testimonialSlider()" ...>
  <div class="slides">...</div>
  <button @click="prev()">‹</button>
  <button @click="next()">›</button>
  <div class="dots">...</div>
</div>
```

The Alpine component tracks `currentIndex`, handles wrapping, and exposes `prev()`, `next()` methods. The component is defined inline in the template using `x-data="{ currentIndex: 0, ... }"`.

---

## Accessibility

- [ ] Prev/Next buttons have descriptive `aria-label` ("Previous testimonial", "Next testimonial")
- [ ] Autoplay pauses on `:hover` and `:focus-within`
- [ ] Active slide is `aria-current="true"` or equivalent
- [ ] `prefers-reduced-motion`: disable autoplay and transitions

---

## Out of Scope

- Star rating display (captured in CPT fields, not required to render in this block)
- Video testimonials
- Testimonials grid layout (slider only per survey selection)
