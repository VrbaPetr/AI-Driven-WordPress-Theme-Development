# Step 07 — Button Component

**Phase:** 3 — Reusable UI Components  
**Depends on:** Step 01  
**Required by:** Steps 11, 12, 15, 17, 22, 23–29

---

## Summary

Build a flexible Button component that renders either an `<a>` or `<button>` element with configurable variants (primary, secondary, outline, ghost), sizes (sm, md, lg), and optional leading/trailing icon support.

---

## User Stories

- **As a developer**, I want a single component to render all button styles so I only update styles in one place.
- **As a developer**, I want to pass an optional icon name and the component handles rendering so I don't write SVG logic in every block.

---

## Business Value

A consistent button component eliminates visual inconsistency across blocks and pages, and reduces the time to implement each new block that contains a CTA.

---

## Acceptance Criteria

- [ ] Component accepts these PHP arguments: `label` (string, required), `url` (string, optional — renders `<a>` if provided, `<button>` if not), `variant` (primary | secondary | outline | ghost, default: primary), `size` (sm | md | lg, default: md), `icon_before` (string, icon name, optional), `icon_after` (string, icon name, optional), `attributes` (array, extra HTML attributes e.g. `target`, `type`, `aria-*`), `classes` (string, extra CSS classes).
- [ ] Renders `<a href="">` when `url` is provided; renders `<button type="button">` otherwise.
- [ ] All four variants are visually distinct.
- [ ] All three sizes are visually distinct.
- [ ] Icon is rendered via `aidriven_get_svg_icon()` helper with `aria-hidden="true"`.
- [ ] Output is fully escaped.
- [ ] Component is called via `get_template_part('template-parts/components/button', null, $args)`.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/components/button.php` | Component markup |
| `src/css/components/button.css` | Button styles (imported into `main.css`) |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `components/button.css` |

---

## Component Interface

```php
get_template_part(
    'template-parts/components/button',
    null,
    [
        'label'       => __( 'Get in Touch', 'ai-driven-boilerplate' ),
        'url'         => '/contact',
        'variant'     => 'primary',   // primary | secondary | outline | ghost
        'size'        => 'md',        // sm | md | lg
        'icon_after'  => 'arrow-right',
        'attributes'  => [ 'target' => '_blank', 'rel' => 'noopener' ],
        'classes'     => 'w-full',
    ]
);
```

---

## Variant Descriptions

| Variant | Use case |
|---|---|
| `primary` | Main CTA — filled, high contrast |
| `secondary` | Supporting action — filled, lower visual weight |
| `outline` | Alternative action — transparent with border |
| `ghost` | Low-emphasis action — no border, text only with hover state |

---

## Accessibility

- [ ] Visible focus ring on all variants
- [ ] Colour contrast ratio ≥ 4.5:1 for text on button background
- [ ] Icon-only usage (if any) must include visually-hidden label text

---

## Out of Scope

- Loading/spinner state button
- Icon-only button variant
- Button group component
