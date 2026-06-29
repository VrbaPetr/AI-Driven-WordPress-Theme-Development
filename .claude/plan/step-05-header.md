# Step 05 — Header & Navigation

**Phase:** 2 — Global Layout  
**Depends on:** Steps 01, 04  
**Required by:** all page templates (Steps 23–29)

---

## Summary

Build the sitewide sticky header: logo, two-level dropdown navigation driven by the `primary` WP menu, a CTA button, and a mobile hamburger menu. The header turns solid (adds background and shadow) when the page is scrolled. Full WCAG 2.1 AA keyboard navigation support.

---

## User Stories

- **As a visitor**, I want the navigation to remain accessible as I scroll so I can jump between pages without scrolling back to the top.
- **As a visitor on mobile**, I want a hamburger menu that opens a full navigation panel so I can access all pages on a small screen.
- **As a keyboard user**, I want to open sub-menus with Enter/Space and close them with Escape so I can navigate without a mouse.
- **As a site editor**, I want to control the nav links and the CTA button text/URL from WP admin without touching code.

---

## Business Value

Navigation is the primary wayfinding tool. An inaccessible or non-sticky nav increases bounce rate, especially on long landing pages. A CTA button in the header drives the primary conversion goal on every page.

---

## Acceptance Criteria

- [ ] Header is sticky (fixed to top); on scroll past 50 px it gains a white/opaque background and a subtle box-shadow.
- [ ] Logo (dark variant from options) is displayed on the left; links in the center; CTA button on the right.
- [ ] Two-level dropdown: hovering/focusing a parent menu item with children reveals a sub-menu panel.
- [ ] Mobile breakpoint (< `lg`): hamburger icon button toggles a full-height or slide-in menu panel.
- [ ] Alpine.js handles open/close state for dropdowns and mobile menu.
- [ ] Keyboard: `Tab` moves through items; `Enter`/`Space` on parent opens sub-menu; `Escape` closes open sub-menu and returns focus to parent.
- [ ] `aria-expanded`, `aria-haspopup="true"`, and `aria-controls` are set correctly on parent items and sub-menu triggers.
- [ ] CTA button text and URL are editable via the WP admin Menus screen (or a custom nav walker with a CSS class convention: `btn-cta`).
- [ ] Skip-to-content link (from Step 01) is the first focusable element in the DOM.
- [ ] The `primary` WP menu location is used.
- [ ] All output is escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/layout/header.php` | Full header markup — called from `header.php` |
| `src/css/layout/header.css` | Header and nav styles (imported into `main.css`) |
| `src/js/scripts/navigation.js` | Scroll-sticky class toggle; imported by `main.js` |

### Files to Modify

| File | Change |
|---|---|
| `header.php` | Add `get_template_part('template-parts/layout/header')` |
| `src/css/main.css` | Import `layout/header.css` |
| `src/js/main.js` | Import `./scripts/navigation.js` |

---

## Design Notes

### Sticky Behaviour

Use a scroll event listener in `navigation.js` to toggle a `.is-scrolled` class on `<header>`. CSS transitions handle the background and shadow change.

### Dropdown Mechanism

Alpine.js `x-data` on each parent `<li>` with a child `<ul>`. `x-show` on the sub-menu; `@mouseenter`/`@mouseleave` for pointer users; `@keydown.enter`, `@keydown.space`, `@keydown.escape` for keyboard users.

### Mobile Menu

Single `x-data` at the `<nav>` level controls `mobileOpen`. Hamburger button toggles it. The mobile panel overlays the page with a backdrop.

### CTA Button

Render the last menu item with class `btn-cta` as a button-styled link using the Button component from Step 07 (or inline styles at this stage if Step 07 is not yet complete).

---

## Accessibility Checklist

- [ ] Colour contrast ratio ≥ 4.5:1 for nav text on background
- [ ] Focus ring visible on all interactive elements
- [ ] Sub-menu does not trap focus when closed
- [ ] Mobile menu can be closed by pressing Escape
- [ ] `<nav>` has `aria-label="Primary navigation"`

---

## Out of Scope

- Mega menu layout (not selected in survey)
- Transparent-to-solid hero overlay header (not selected)
- Dark mode header variant
