# Step 09 — Badge & Alert/Notice Components

**Phase:** 3 — Reusable UI Components  
**Depends on:** Step 01  
**Required by:** Steps 08, 18, 26, 28

---

## Summary

Build two small UI components: a **Badge** for inline labels (categories, status tags) and an **Alert/Notice** for contextual messages (info, success, warning, error). Both are purely presentational with no JS interaction, except the Alert which supports an optional Alpine.js dismiss button.

---

## User Stories

- **As a visitor**, I want category badges on cards and archive pages so I can quickly identify content type without reading the full text.
- **As a developer**, I want a consistent notice component to display form feedback, error messages, and informational banners without writing new markup each time.

---

## Business Value

Consistent inline labels and feedback states improve scannability and UX, particularly on archive pages and after form submissions (Step 30).

---

## Acceptance Criteria

### Badge

- [ ] Accepts: `label` (string, required), `variant` (primary | success | warning | error | neutral, default: neutral), `url` (string, optional — wraps badge in `<a>`), `classes` (string, extra classes).
- [ ] Renders as `<span>` or `<a>` depending on `url`.
- [ ] Five visually distinct colour variants.
- [ ] Small text, rounded pill or rectangular shape.
- [ ] Output escaped.

### Alert / Notice

- [ ] Accepts: `message` (string, required), `variant` (info | success | warning | error, default: info), `dismissible` (bool, default: false), `icon` (bool, show variant icon, default: true), `classes` (string).
- [ ] Renders with an icon (SVG from `assets/media/icons/`) indicating type.
- [ ] When `dismissible: true` renders an `×` button; Alpine.js `x-data="{ show: true }" x-show="show"` controls visibility.
- [ ] `role="alert"` on the element for screen readers.
- [ ] Output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/components/badge.php` | Badge markup |
| `template-parts/components/alert.php` | Alert/notice markup |
| `src/css/components/badge.css` | Badge styles |
| `src/css/components/alert.css` | Alert styles |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `components/badge.css` and `components/alert.css` |

---

## Component Interfaces

```php
// Badge
get_template_part( 'template-parts/components/badge', null, [
    'label'   => 'Web Development',
    'variant' => 'primary',
    'url'     => '/category/web-development',
] );

// Alert
get_template_part( 'template-parts/components/alert', null, [
    'message'     => __( 'Your message has been sent successfully.', 'ai-driven-boilerplate' ),
    'variant'     => 'success',
    'dismissible' => true,
] );
```

---

## Accessibility

- [ ] Badge colour is not the only indicator of meaning (label text always present)
- [ ] Alert uses `role="alert"` so assistive technologies announce it
- [ ] Dismiss button has `aria-label="Dismiss notification"`
- [ ] Contrast ≥ 4.5:1 for badge text on badge background

---

## Out of Scope

- Toast / floating notification component
- Progress indicator / step badge
