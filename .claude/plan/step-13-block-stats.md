# Step 13 — Stats / Counters Block

**Phase:** 4 — Content Blocks  
**Depends on:** Step 01  
**Required by:** Steps 23, 24

---

## Summary

Build an ACF block that displays up to four key metrics (e.g. "150+ Projects", "12 Years Experience") with an animated count-up effect that triggers when the block scrolls into the viewport.

---

## User Stories

- **As a site editor**, I want to showcase company achievements with bold numbers so visitors immediately grasp the company's scale and track record.
- **As a visitor**, I want numbers to count up as I scroll to them so the data feels dynamic and engaging rather than static.

---

## Business Value

Quantified proof points (years in business, clients served, projects delivered) build credibility and trust quickly. The count-up animation draws visual attention without requiring custom design work per project.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Supports 1–4 stat items via ACF Repeater field.
- [ ] Each stat item: number (integer or decimal), suffix (e.g. `+`, `k`, `%`), label text, optional icon (SVG name).
- [ ] Count-up animation runs once when the block enters the viewport (Intersection Observer in `src/js/scripts/stats-counter.js`).
- [ ] Animation respects `prefers-reduced-motion`: if set, numbers appear immediately without animation.
- [ ] Optional section heading above the stats grid.
- [ ] Stats displayed in a responsive grid: 4 columns on desktop, 2 on tablet, 1–2 on mobile.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/stats.php` | Block template |
| `src/css/blocks/stats.css` | Block styles |
| `src/js/scripts/stats-counter.js` | Count-up animation with Intersection Observer |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/stats` block |
| `src/css/main.css` | Import `blocks/stats.css` |
| `src/js/main.js` | Import `./scripts/stats-counter.js` |

---

## ACF Field Group: Stats Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; renders as `<h2>` if provided |
| Stats | Repeater (max 4) | |
| — Number | Number | The target count value |
| — Suffix | Text | e.g. `+`, `k`, `%` — appended after number |
| — Label | Text | e.g. "Projects Delivered" |
| — Icon | Text | SVG icon name from `assets/media/icons/` (optional) |

---

## JavaScript Behaviour

```js
// stats-counter.js — pseudo-code
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      animateCounter(entry.target);
      observer.unobserve(entry.target);
    }
  });
});

document.querySelectorAll('[data-counter]').forEach(el => observer.observe(el));
```

Each `[data-counter]` element has `data-target` (the end number) and `data-duration` attributes set by the PHP template.

---

## Accessibility

- [ ] `aria-label` on each stat element provides full context (e.g. "150 plus Projects Delivered") since the visual split of number/suffix/label could be confusing
- [ ] Count-up animation does not trigger for users with `prefers-reduced-motion: reduce`

---

## Out of Scope

- Background image or colour fill behind stats (not selected in survey)
- Animated progress bars
