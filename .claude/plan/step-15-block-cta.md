# Step 15 — CTA Block

**Phase:** 4 — Content Blocks  
**Depends on:** Steps 01, 07  
**Required by:** Steps 23, 24, 25, 26, 28, 29

---

## Summary

Build a full-width call-to-action banner block with a headline, supporting subtext, up to two buttons, and a configurable background (solid colour or gradient). This block serves as the conversion prompt that can be placed at the bottom of any page or between content sections.

---

## User Stories

- **As a site editor**, I want to drop a branded CTA banner anywhere on a page so I can drive enquiries without relying on a developer to build a new section.
- **As a site editor**, I want to choose a background colour or gradient so the CTA visually stands out from surrounding content.

---

## Business Value

A prominent, reusable CTA block directly supports the primary conversion goal. Placing it just above the footer on every major page ensures visitors always have a visible next step regardless of how far they read.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Section heading rendered as `<h2>`.
- [ ] Subtext rendered as a `<p>`.
- [ ] Primary button rendered via Button component (`variant: primary`).
- [ ] Secondary button optional; rendered via Button component (`variant: outline` or `ghost`).
- [ ] Background style: **Solid** (single colour picker) or **Gradient** (two colour pickers, gradient direction selector).
- [ ] Text colour automatically adjusts based on background luminosity (light bg → dark text; dark bg → light text) OR provide separate text colour picker.
- [ ] Block is full-width, centred content, with generous vertical padding.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/cta.php` | Block template |
| `src/css/blocks/cta.css` | Block styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/cta` block |
| `src/css/main.css` | Import `blocks/cta.css` |

---

## ACF Field Group: CTA Block

| Field label | Field type | Notes |
|---|---|---|
| Heading | Text | Renders as `<h2>` |
| Subtext | Text | Supporting sentence |
| Primary Button Label | Text | Required for CTA to show |
| Primary Button URL | URL | |
| Secondary Button Label | Text | Optional |
| Secondary Button URL | URL | Optional |
| Background Style | Select | Solid, Gradient |
| Background Colour | Colour Picker | Conditional: Solid only |
| Gradient Start Colour | Colour Picker | Conditional: Gradient only |
| Gradient End Colour | Colour Picker | Conditional: Gradient only |
| Gradient Direction | Select | To Right, To Bottom Right, To Bottom; conditional: Gradient only |
| Text Colour | Select | Light (white), Dark (near-black) |

---

## Inline Style Pattern

Background colour is applied as an inline `style` attribute using `wp_kses` or a controlled colour field output:

```php
$bg_colour = esc_attr( get_field( 'background_colour' ) );
// output: style="background-color: <?php echo $bg_colour; ?>;"
```

---

## Accessibility

- [ ] Text contrast ≥ 4.5:1 against chosen background colour (editor responsibility — document in field description)
- [ ] Heading level `<h2>` appropriate (this block should not be the first content on a page)

---

## Out of Scope

- Background image variant (not selected in survey)
- Form embedded inside CTA block
- Countdown timer
