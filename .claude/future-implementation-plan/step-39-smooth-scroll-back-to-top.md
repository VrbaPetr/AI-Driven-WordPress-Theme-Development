# Step 39 — Smooth Scroll & Back to Top

**Phase:** 10 — Site UX & Utilities  
**Depends on:** Steps 01, 05, 06  
**Required by:** Step 45 (TOC anchor links rely on offset-aware smooth scroll)

---

## Summary

Two progressive-enhancement UX features added site-wide with no new dependencies.

**Smooth scroll** — a CSS baseline (`scroll-behavior: smooth` on `<html>`) plus a JS intercept in `src/js/scripts/smooth-scroll.js` that corrects anchor scroll position for the sticky header height. Respects `prefers-reduced-motion` by jumping instantly instead.

**Back to top button** — a `<button class="back-to-top">` rendered inside `footer.php`, always in the DOM but visually hidden until the user scrolls past 300 px. The existing `navigation.js` script is extended to toggle an `.is-visible` class and handle the click. The button uses the existing `arrow-right.svg` icon rotated 270 degrees — no new asset required. An optional ACF true/false field `show_back_to_top` in `group_ss_footer.json` lets site owners disable the button from the options panel.

---

## User Stories

- **As a visitor reading a long page**, I want anchor links (e.g. in a nav or TOC) to scroll smoothly and land below the sticky header so the target heading is not hidden behind it.
- **As a visitor who has scrolled far down the page**, I want a visible back-to-top button so I can return to the top without scrolling manually.
- **As a site owner with a short page**, I want to be able to hide the back-to-top button from the options panel so it does not appear unnecessarily on pages where it is not useful.
- **As a user who prefers reduced motion**, I want all scroll animations to be instant so the site does not cause discomfort.

---

## Business Value

Both features are small, zero-dependency UX improvements that improve perceived quality on long-form pages (services, blog posts, case studies). Offset-aware anchor scroll prevents the most common sticky-header scrolling regression — a defect that affects every site with a fixed/sticky header and any in-page links.

---

## Acceptance Criteria

### Smooth Scroll — CSS Baseline

- [ ] `html { scroll-behavior: smooth; }` added to the main layout stylesheet.
- [ ] `@media (prefers-reduced-motion: reduce) { html { scroll-behavior: auto; } }` cancels the smooth behaviour for motion-sensitive users.

### Smooth Scroll — JS Offset Correction

- [ ] `src/js/scripts/smooth-scroll.js` created and imported in `main.js`.
- [ ] Script intercepts clicks on all `a[href^="#"]` links that target an element present in the DOM.
- [ ] Reads the height of `#site-header` (`getBoundingClientRect().height`) at click time (handles dynamic height changes).
- [ ] Calculates corrected Y: `element.getBoundingClientRect().top + window.scrollY - headerHeight`.
- [ ] Calls `window.scrollTo({ top: correctedY, behavior: 'smooth' })`.
- [ ] When `prefers-reduced-motion: reduce` matches (`window.matchMedia`), calls `window.scrollTo({ top: correctedY, behavior: 'instant' })` instead.
- [ ] Does not fire on links with `href="#"` (empty hash — no target element).
- [ ] Does not fire when the modifier keys Ctrl, Meta, Shift, or Alt are held (allows open-in-new-tab).
- [ ] Uses `e.preventDefault()` only after confirming a valid target exists.

### Back to Top — Markup

- [ ] `<button class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'aidriven' ); ?>" hidden>` rendered in `footer.php` before the closing `</footer>` tag.
- [ ] Button contains the `arrow-right.svg` icon inline (via `get_template_part` or direct inline SVG include) visually rotated 270 degrees via CSS.
- [ ] When the ACF field `show_back_to_top` evaluates to `false`, the button is not rendered at all (PHP conditional around the markup).
- [ ] When `show_back_to_top` is not set (field absent or null), the button is rendered by default (field defaults to `1`/true in ACF JSON).

### Back to Top — JS Behaviour

- [ ] `navigation.js` extended with a `back-to-top` section inside the existing IIFE.
- [ ] On `scroll` (passive listener), toggles `.is-visible` on `.back-to-top` when `window.scrollY > 300`.
- [ ] Click handler calls `window.scrollTo({ top: 0, behavior: 'smooth' })`.
- [ ] When `prefers-reduced-motion: reduce` matches, click handler calls `window.scrollTo({ top: 0, behavior: 'instant' })` instead.
- [ ] `hidden` HTML attribute removed by JS on `init` (progressive enhancement — button is invisible in no-JS environments without relying solely on CSS).

### Back to Top — Styles

- [ ] Button fixed-positioned, bottom-right corner, above the footer visually.
- [ ] Default state: `opacity: 0; pointer-events: none; visibility: hidden;` (hidden from assistive tech too when not visible).
- [ ] `.is-visible` state: `opacity: 1; pointer-events: auto; visibility: visible;` with a CSS transition on opacity and transform.
- [ ] Arrow icon rotated 270 degrees via `transform: rotate(270deg)` scoped to `.back-to-top svg` (not a global utility).
- [ ] Focus ring visible (`outline` not removed).
- [ ] Uses only token-defined colors from `src/css/variables/colors.css`.

### ACF Field

- [ ] `show_back_to_top` true/false field added to `acf-json/group_ss_footer.json`.
- [ ] Field key: `field_ss_show_back_to_top`.
- [ ] Field label: "Show Back to Top Button".
- [ ] Default value: `1` (enabled).
- [ ] Instructions: "Show a floating button that scrolls the visitor back to the top of the page."
- [ ] Appended after the existing `copyright_text` field in the JSON array.

### Quality

- [ ] `make check` passes (PHPCS clean).
- [ ] No JS errors in browser console on pages with no `#` anchor links.
- [ ] No JS errors on pages where `#site-header` does not exist (script guards with early return).

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `src/js/scripts/smooth-scroll.js` | Offset-aware anchor scroll intercept |
| `src/css/navigation/back-to-top.css` | Back to top button styles |

### Files to Modify

| File | Change |
|---|---|
| `src/css/layout/header.css` (or `main.css`) | Add `html { scroll-behavior: smooth }` and reduced-motion override |
| `src/css/main.css` | Import `navigation/back-to-top.css` |
| `src/js/main.js` | Import `./scripts/smooth-scroll.js` |
| `src/js/scripts/navigation.js` | Add back-to-top scroll listener and click handler |
| `footer.php` | Render `<button class="back-to-top">` conditionally on ACF field |
| `acf-json/group_ss_footer.json` | Add `field_ss_show_back_to_top` true/false field |

---

## Implementation Notes

### Scroll offset calculation

Read `#site-header` height at click time, not on page load, because the header height changes on scroll (it applies `.is-scrolled` which may reduce padding).

```js
const header = document.getElementById( 'site-header' );
const headerHeight = header ? header.getBoundingClientRect().height : 0;
```

### reduced-motion helper

Declare once at the top of each script file:

```js
const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
```

### Back to top progressive enhancement

Set the `hidden` attribute in PHP markup. JS removes it on init. This means the button is truly hidden for users without JS — no need to rely on CSS alone to hide a focusable element.

```js
btn.removeAttribute( 'hidden' );
```

### ACF field read in footer.php

```php
$show_back_to_top = get_field( 'show_back_to_top', 'option' );
if ( false !== $show_back_to_top && $show_back_to_top ) :
```

Use a strict `false !==` check so that an unset field (returns `false` from `get_field`) still renders the button (default on).

---

## Accessibility

- [ ] Button has `aria-label` — the SVG icon is decorative (`aria-hidden="true"` on the `<svg>`).
- [ ] Button is hidden from assistive technology when not visible (`visibility: hidden` in default state — `opacity: 0` alone is insufficient).
- [ ] Smooth scroll does not trap focus — focus moves to the target element naturally after the JS `scrollTo` call (no `focus()` call needed for anchor navigation; the browser handles it if the target has `tabindex` or is natively focusable).
- [ ] `prefers-reduced-motion` respected by both the CSS `scroll-behavior` override and both JS scroll calls.

---

## Out of Scope

- Customising button position (always bottom-right).
- Progress indicator ring around the button.
- Scroll-to-top for individual sections (full-page scroll only).
- Per-page ACF override (the options field is site-wide).
- Hash URL update after anchor scroll (browser default behaviour — not suppressed, not added).
