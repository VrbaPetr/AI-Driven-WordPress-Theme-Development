# Step 35 — Editor & Analytics Enhancements

**Phase:** 8 — Accessibility & Editor Fixes
**Depends on:** Steps 01, 02 (design tokens)
**Required by:** none

---

## Summary

Two targeted enhancements to tighten the editor experience and enable analytics integration. First, register the theme's brand color palette in the Gutenberg editor so editors see only approved colors instead of WordPress's default 14-color swatch grid; also disable custom colors and gradient presets to enforce brand consistency. Second, add a GTM (Google Tag Manager) options panel backed by ACF Local JSON, and output the GTM `<head>` script and `<noscript>` iframe via WordPress hooks — only when a container ID is supplied and the toggle is enabled.

---

## User Stories

- **As a site editor**, I want the Gutenberg color picker to show only brand colors so I cannot accidentally apply off-brand colors to block content.
- **As a developer**, I want GTM enabled or disabled from WP admin without touching theme code so I can hand the site to a client without giving them file access.
- **As a marketing manager**, I want to paste a GTM container ID into an options field and have the tag fire immediately on all pages.
- **As a site editor**, I want to turn GTM off with a single checkbox during development or testing without removing the container ID.

---

## Business Value

Exposing only brand colors in the editor reduces content inconsistency and shortens the style-review cycle. Native GTM integration removes the need for a third-party plugin, keeps the tag in the correct position (immediately after `<body>`), and gives non-developers a safe UI to manage the container ID.

---

## Acceptance Criteria

- [ ] The Gutenberg color picker shows only the theme's registered brand colors; no custom-color input field is visible.
- [ ] Gradient presets are empty; no gradient picker is shown.
- [ ] An ACF Options sub-page (or section) labelled "Analytics" exists under the site settings options page.
- [ ] The Analytics options panel contains a `gtm_container_id` text field and an `enable_gtm` true/false field.
- [ ] When `enable_gtm` is `true` and `gtm_container_id` is non-empty, the GTM `<script>` tag is output inside `<head>` (via `wp_head`, priority 1).
- [ ] When the same conditions are met, the GTM `<noscript>` iframe is output immediately after `<body>` opens (via `wp_body_open`).
- [ ] When `enable_gtm` is `false` or `gtm_container_id` is empty, no GTM output is rendered.
- [ ] The container ID is escaped with `esc_attr()` wherever it is interpolated into markup.
- [ ] `make check` passes with no new PHPCS errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_ss_analytics.json` | ACF Local JSON field group — Analytics options panel with `gtm_container_id` (text) and `enable_gtm` (true/false) fields, attached to the site settings options page |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-design.php` | Add `add_theme_support('editor-color-palette', [...])` mapping brand tokens to slugs/labels; add `add_theme_support('disable-custom-colors')`; add `add_theme_support('editor-gradient-presets', [])`; add `wp_head` (priority 1) and `wp_body_open` action callbacks that read ACF options and output GTM tags |

---

## Notes

### Editor Color Palette

The color slugs passed to `editor-color-palette` must match CSS custom property names from `src/css/variables/colors.css` so the editor-applied classes (`has-{slug}-color`, `has-{slug}-background-color`) resolve to the correct token values. Each entry in the array requires `name` (translatable label), `slug` (kebab-case, no prefix), and `color` (hex or `oklch()` value read from the token file).

Disabling custom colors (`disable-custom-colors`) prevents the "Custom color" picker from appearing; disabling gradient presets (`editor-gradient-presets` set to an empty array `[]`) hides the gradient UI entirely. Both supports are additive — they do not affect front-end CSS.

### GTM Output

The `wp_body_open` hook requires the theme to call `wp_body_open()` inside `<body>` in `header.php`. Verify this call exists before relying on the hook. If it is absent, add it immediately after the opening `<body>` tag as part of this step.

Container ID format expected by GTM is `GTM-XXXXXXX`. No format validation is performed server-side; the field label should include a placeholder hint. Escaping with `esc_attr()` is sufficient because the ID appears only inside a string literal within a `<script>` tag attribute context and as a query-string parameter in the `<noscript>` `src` URL.

The GTM `<noscript>` snippet must be output at `wp_body_open` rather than `wp_head` to comply with Google's placement requirement (immediately after `<body>`).

ACF options fields are retrieved with `get_field('field_name', 'option')`.

### ACF JSON Group Key

Use the key `group_ss_analytics` and derive field keys deterministically: `field_ss_analytics_gtm_container_id` and `field_ss_analytics_enable_gtm`. The group must be assigned to the correct options page location — match the options page slug used by the existing site settings group.

---

## Out of Scope

- Google Analytics 4 (GA4) direct integration — GTM should be used as the delivery mechanism for GA4
- Consent management / cookie banner logic
- Server-side GTM
- Any other third-party script injection (Facebook Pixel, LinkedIn Insight, etc.)
- Front-end color palette preview page
