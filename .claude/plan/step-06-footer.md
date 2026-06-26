# Step 06 — Footer

**Phase:** 2 — Global Layout  
**Depends on:** Steps 01, 04  
**Required by:** all page templates (Steps 23–29)

---

## Summary

Build the sitewide multi-column footer: company logo and description, two columns of navigation links from the `footer` menu location, a contact info column from ACF options, social icons, and a bottom copyright bar.

---

## User Stories

- **As a visitor**, I want the footer to show the company's contact details and social links so I can reach out without going to the contact page.
- **As a site editor**, I want to manage footer navigation links from WP admin Menus so I don't need a developer to update them.
- **As a site editor**, I want the copyright year to update automatically so I don't need to edit code each January.

---

## Business Value

The footer is a trust signal and a wayfinding fallback. It should surface contact information and key links to visitors who have reached the bottom of the page — improving conversions without requiring a CTA block on every page.

---

## Acceptance Criteria

- [ ] Footer uses a four-column layout on desktop, stacks gracefully to two columns on tablet and one column on mobile.
- [ ] **Column 1:** Logo (light variant from options), company tagline/description from options.
- [ ] **Column 2 & 3:** Navigation links driven by the `footer` WP menu location, split automatically or via menu item grouping.
- [ ] **Column 4:** Address, phone, and email from ACF options; each clickable (`tel:`, `mailto:`).
- [ ] Social icons row displayed in column 1 (below description) using icon files from `assets/media/icons/`; pulled from the Social Links options repeater.
- [ ] Bottom bar: copyright text from options, current year auto-inserted, optional secondary links (Privacy Policy, Terms).
- [ ] Footer widget area (`footer-col-2`, `footer-col-3`) registered so editors can optionally replace link columns with widgets.
- [ ] All linked URLs are escaped with `esc_url()`; all text output escaped with `esc_html()` or `wp_kses_post()`.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/layout/footer.php` | Full footer markup — called from `footer.php` |
| `src/css/layout/footer.css` | Footer styles (imported into `main.css`) |

### Files to Modify

| File | Change |
|---|---|
| `footer.php` | Add `get_template_part('template-parts/layout/footer')` before `wp_footer()` |
| `inc/functions-design.php` | Register `footer-col-2` and `footer-col-3` sidebar/widget areas |
| `src/css/main.css` | Import `layout/footer.css` |

---

## Design Notes

### Column Layout

Use CSS Grid or Flexbox. Suggested proportions: `1fr 1fr 1fr 1fr` on desktop. Below `lg`: two columns. Below `sm`: single column stacked.

### Social Icons

Render from the `aidriven_get_social_links()` helper (Step 04). Each icon is an inline SVG from `assets/media/icons/{platform}.svg` loaded via `aidriven_get_svg_icon()`.

### Copyright Year

```php
echo esc_html( get_field( 'copyright_text', 'option' ) );
echo ' ' . esc_html( gmdate( 'Y' ) );
```

### Footer Nav Handling

If only one menu is assigned to `footer`, display all items in column 2 and leave column 3 as the widget area fallback. Document this convention in the template file.

---

## Widget Area Specification

| Sidebar ID | Title | Description |
|---|---|---|
| `footer-col-2` | Footer Links 1 | Second footer column — navigation links or custom widget |
| `footer-col-3` | Footer Links 2 | Third footer column — navigation links or custom widget |

---

## Accessibility

- [ ] `<footer>` element used (landmark role)
- [ ] Social icon links have descriptive `aria-label` from the options Label sub-field
- [ ] Colour contrast ≥ 4.5:1 for footer text on background

---

## Out of Scope

- Newsletter signup in footer (not selected in survey)
- Full-width "rich" footer variant (option D — not selected)
