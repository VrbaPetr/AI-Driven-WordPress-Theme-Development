# Step 34 — Accessibility Fixes

**Phase:** 8 — Accessibility & Editor Fixes
**Depends on:** Steps 01, 05
**Required by:** Steps 41, 45

---

## Summary

Add a visually hidden "Skip to main content" anchor as the very first element inside `<header>`, and apply a matching `id="main-content"` target to the `<main>` element in every page template. The link becomes visible only when it receives keyboard focus. No JavaScript is required.

This resolves a WCAG 2.1 AA Level violation (Criterion 2.4.1 — Bypass Blocks) that is currently absent from the theme. Keyboard and assistive-technology users will be able to skip repeated navigation and jump directly to the page's primary content region.

## User Stories

- **As a keyboard-only user,** I want a visible focus target at the top of every page so I can skip repeated navigation and reach the main content without tabbing through the entire header.
- **As a screen reader user,** I want a correctly labelled landmark region (`main` with a consistent `id`) so my assistive technology can jump directly to the primary content area.
- **As a developer,** I want the skip link implemented in a single location (`header.php`) and the `id` applied consistently across all templates so future maintenance is contained to one touch-point each.

## Business Value

WCAG 2.1 AA compliance is a legal baseline in the EU, UK, and Canada for publicly accessible websites. Shipping the boilerplate without Criterion 2.4.1 exposes every downstream site built on it to accessibility audits that fail on this point. A single-file fix in the header, combined with a consistent template convention, closes the gap permanently and makes the theme a compliant starting point out of the box.

## Acceptance Criteria

- [ ] A `<a class="skip-to-content" href="#main-content">` element exists as the first child inside `<header>` in `template-parts/layout/header.php`.
- [ ] The skip link is visually hidden by default (positioned off-screen via `transform: translateY(-100%)`) and becomes fully visible when it receives `:focus`.
- [ ] The skip link is reachable as the very first Tab stop on every page — before any navigation link.
- [ ] Every page template contains `<main id="main-content">` as its primary content wrapper.
- [ ] Activating the skip link moves browser focus to `#main-content` and scrolls the viewport to the top of the main content region.
- [ ] The link text reads "Skip to main content" (or equivalent translated string using `__( 'Skip to main content', 'ai-driven-boilerplate' )`).
- [ ] The skip link is styled with sufficient color contrast against its visible state background (WCAG AA minimum 4.5:1).
- [ ] No JS is involved — the feature works with JavaScript disabled.
- [ ] PHPCS passes with no new violations in modified PHP files.

## Technical Scope

### Files to Create

No new files required. All changes are additions to existing files.

### Files to Modify

| File | Change |
|---|---|
| `template-parts/layout/header.php` | Add `.skip-to-content` anchor as the first element inside `<header>`, before any nav markup |
| `src/css/layout/header.css` | Add `.skip-to-content` rules: absolute position, off-screen by default, slides in on `:focus` via `transform` |
| `front-page.php` | Add `id="main-content"` to `<main>` |
| `page-about.php` | Add `id="main-content"` to `<main>` |
| `page-contact.php` | Add `id="main-content"` to `<main>` |
| `page-team.php` | Add `id="main-content"` to `<main>` |
| `archive-service.php` | Add `id="main-content"` to `<main>` |
| `archive-project.php` | Add `id="main-content"` to `<main>` |
| `single-service.php` | Add `id="main-content"` to `<main>` |
| `single-project.php` | Add `id="main-content"` to `<main>` |
| `single.php` | Add `id="main-content"` to `<main>` |
| `home.php` | Add `id="main-content"` to `<main>` |
| `search.php` | Add `id="main-content"` to `<main>` |
| `404.php` | Add `id="main-content"` to `<main>` |
| `category.php` | Add `id="main-content"` to `<main>` |
| `tag.php` | Add `id="main-content"` to `<main>` |

## Accessibility / Notes

**WCAG 2.1 AA — Criterion 2.4.1 (Bypass Blocks):** pages that contain blocks of content repeated across multiple pages must provide a mechanism to skip those blocks. This skip link is the standard mechanism.

The link must be in the DOM before any navigation element — not merely visually first. Placing it as the literal first child of `<header>` guarantees correct tab order regardless of CSS. Use `position: absolute` with `transform: translateY(-100%)` for off-screen hiding rather than `display: none` or `visibility: hidden`, which would remove it from the tab order entirely.

Wrap the link text in `__( 'Skip to main content', 'ai-driven-boilerplate' )` so multilingual sites can provide a translated string. Escape output with `esc_html_e()`.

The `id="main-content"` attribute must appear on the `<main>` element only — not on a wrapper `<div>` — so the landmark role and the skip target are the same element, giving screen readers a single consistent anchor.

## Out of Scope

- Any other WCAG criteria beyond 2.4.1 — focus order, color contrast auditing of existing components, ARIA roles — are deferred to a dedicated audit step.
- Animated or delayed reveal of the skip link beyond the basic `:focus` transform transition.
- A "Skip to footer" or secondary skip target.
- Automated accessibility testing tooling (axe, Lighthouse CI) — infrastructure work tracked separately.
- Changes to block templates inside `template-parts/blocks/` — blocks are embedded inside `<main>` and inherit the landmark automatically.
