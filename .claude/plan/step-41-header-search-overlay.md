# Step 41 — Header Search Overlay

**Phase:** 10 — Site UX & Utilities  
**Depends on:** Steps 05, 38  
**Required by:** nothing (end feature)

---

## Summary

Add an optional site-wide search icon to the header. Clicking the icon opens a full-screen overlay — dark semi-transparent backdrop, centered search input, and an optional heading — controlled entirely by ACF options. The overlay is driven by an Alpine.js global store so any part of the page can open or close it programmatically. Keyboard and accessibility requirements follow WCAG 2.1 AA.

---

## User Stories

- **As a visitor**, I want a visible search entry point in the header so I can search the site without navigating to a dedicated search page first.
- **As a visitor**, I want to dismiss the search overlay by pressing Escape or clicking outside the input panel so I can return to what I was reading quickly.
- **As a keyboard user**, I want focus to be trapped inside the overlay while it is open so I am not accidentally tabbing to hidden content behind it.
- **As a site editor**, I want to enable or disable the header search icon and customise the overlay heading and input placeholder from WP admin without touching code.

---

## Business Value

A persistent, accessible search entry point lowers friction for returning visitors who know what they need. Keeping it optional via ACF options means the feature can be toggled per-project without code changes.

---

## Acceptance Criteria

### ACF Options

- [ ] `enable_header_search` (true/false) toggle controls whether the icon and overlay are rendered at all — no markup output when disabled.
- [ ] `search_overlay_heading` (text, optional) renders as a heading above the input; hidden when blank.
- [ ] `search_input_placeholder` (text) sets the `placeholder` attribute on the search `<input>`; falls back to a sensible default when blank.

### Header Icon Button

- [ ] Icon button using `search.svg` (from Step 38) is appended to the existing header nav area when `enable_header_search` is true.
- [ ] Button has `aria-label="Open search"`.
- [ ] `aria-expanded` is bound to the Alpine store value so screen readers announce the current state.
- [ ] Button `@click` sets `$store.search.open = true`.

### Overlay Behaviour

- [ ] Overlay is `position: fixed; inset: 0; z-index: 60` — rendered above the sticky header (z-index 50).
- [ ] Backdrop uses a dark, semi-transparent colour token (not a hardcoded value).
- [ ] `x-show` on the overlay root, with a short CSS opacity/scale transition.
- [ ] Pressing `Escape` anywhere in the window closes the overlay (`@keydown.escape.window`).
- [ ] Clicking the backdrop outside the content panel closes the overlay (`@click.self` on the backdrop element).
- [ ] A visible close button inside the overlay also sets `$store.search.open = false`.
- [ ] `<body>` receives `overflow-hidden` while the overlay is open to prevent background scroll.
- [ ] Search `<input>` receives focus automatically when the overlay opens (`$watch` + `$nextTick`).
- [ ] On close, focus returns to the header icon button that triggered the overlay.

### Focus Trap

- [ ] `Tab` cycles only between the search `<input>` and the close button while the overlay is open.
- [ ] `Shift+Tab` cycles in reverse between the same two elements.
- [ ] Focus does not reach any element behind the overlay.

### Markup & Semantics

- [ ] Overlay root has `role="dialog"` and `aria-modal="true"`.
- [ ] Overlay root has `aria-label="Site search"` (or equivalent descriptive label).
- [ ] Search form uses `<form role="search">` with `method="get"` and `action="/"` (WordPress standard).
- [ ] Input is `<input type="search" name="s">` with an associated `<label>` (visually hidden is acceptable).
- [ ] Submit button is present and has meaningful accessible text.
- [ ] All output is escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_ss_search.json` | ACF field group — `enable_header_search`, `search_overlay_heading`, `search_input_placeholder` attached to the site options page |
| `template-parts/components/search-overlay.php` | Full overlay markup — backdrop, content panel, close button, optional heading, search form |
| `src/css/components/search-overlay.css` | Overlay styles: backdrop colour, transition, panel layout, focus-visible ring on input and close button |

### Files to Modify

| File | Change |
|---|---|
| `template-parts/layout/header.php` | Read `enable_header_search` option; conditionally render the icon button and include `search-overlay` component |
| `src/css/main.css` | Add `@import "components/search-overlay.css"` |
| `src/js/main.js` | Register `Alpine.store('search', { open: false })` inside the `alpine:init` event listener before `Alpine.start()` |

---

## Alpine.js Store

Register the store during Alpine initialisation in `main.js`:

```js
document.addEventListener('alpine:init', () => {
    Alpine.store('search', {
        open: false,
    });
});
```

The header button and the overlay both reference the store via `$store.search.open`. No local `x-data` state is needed for the open/close toggle.

---

## Focus Trap Implementation

Implement focus trapping with a `@keydown.tab` handler on the overlay's content panel. On `Tab`, check `document.activeElement` — if it is the last focusable element (close button), redirect focus to the first (input), and vice versa for `Shift+Tab`. A small inline Alpine method or a shared `focusTrap` helper in `main.js` is acceptable.

---

## Body Scroll Lock

Add and remove the `overflow-hidden` class on `document.body` by watching the store:

```js
// inside alpine:init, after store registration
Alpine.effect(() => {
    document.body.classList.toggle('overflow-hidden', Alpine.store('search').open);
});
```

---

## ACF JSON Field Group

Key naming convention: `group_ss_search` for the group; `field_ss_enable_header_search`, `field_ss_search_overlay_heading`, `field_ss_search_input_placeholder` for the fields. Location rule: Options Page — Site Settings (from Step 04).

---

## Accessibility Checklist

- [ ] Colour contrast of placeholder text meets 3:1 minimum against input background
- [ ] Colour contrast of overlay heading and input text meets 4.5:1
- [ ] Focus ring is visible on the close button and the search input
- [ ] Overlay cannot be opened or interacted with by a screen reader when `x-show` evaluates to `false` (Alpine removes the element from the accessibility tree with `display:none`)
- [ ] `aria-expanded` on the header trigger button reflects live state
- [ ] `aria-modal="true"` prevents virtual cursor from escaping the dialog in supported screen readers

---

## Out of Scope

- Live / AJAX search suggestions (autocomplete dropdown)
- Search filtering by post type or date
- Animation beyond a simple opacity fade
- Mobile-specific layout differences for the overlay content panel
- Search history or recent searches
