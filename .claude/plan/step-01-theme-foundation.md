# Step 01 — Theme Foundation Setup

**Phase:** 1 — Foundation  
**Depends on:** nothing  
**Required by:** all other steps

---

## Summary

Establish the complete PHP infrastructure of the theme: a well-structured `functions.php` that includes all sub-files, Vite manifest-based asset enqueueing, WordPress theme supports, navigation menu locations, custom image sizes, a shared helper functions library, and scaffold files for blocks, CPTs, and taxonomies. No visible UI is produced — this step creates the skeleton every other step builds on.

---

## User Stories

- **As a developer**, I want a clean `functions.php` that only requires sub-files so I can find and edit any concern in isolation.
- **As a developer**, I want assets enqueued via the Vite manifest so versioned filenames are resolved automatically in both dev and production.
- **As a site editor**, I want nav menus and image sizes registered so WordPress admin shows the correct options without custom configuration.

---

## Business Value

Every subsequent implementation step depends on this foundation being correct. Getting enqueueing, includes, and helper patterns right here prevents rework and inconsistencies across all 31 remaining steps.

---

## Acceptance Criteria

- [ ] `functions.php` requires all `inc/` sub-files and contains no business logic of its own.
- [ ] Vite manifest is read at runtime; hashed filenames are resolved correctly for both `main.css`/`main.js` and `critical.js`.
- [ ] Critical JS (`critical.js`) is enqueued in `<head>` (blocking); main JS (`main.js`) is deferred.
- [ ] Theme supports registered: `post-thumbnails`, `title-tag`, `html5` (search-form, comment-form, comment-list, gallery, caption, script, style), `responsive-embeds`, `editor-styles`.
- [ ] Navigation menu locations registered: `primary`, `footer`.
- [ ] At least three custom image sizes registered with descriptive kebab-case names (e.g. `card-thumbnail`, `hero-full`, `team-portrait`).
- [ ] `inc/functions-helpers.php` exists and contains at minimum: `aidriven_get_svg_icon()`, `aidriven_truncate_text()`, `aidriven_get_social_links()`.
- [ ] Scaffold files exist and are included: `inc/register-blocks.php`, `inc/register-cpt.php`, `inc/register-taxonomy.php`, `inc/functions-data.php`, `inc/functions-ajax.php`, `inc/functions-form.php`, `inc/functions-design.php`.
- [ ] A skip-to-content link (`<a href="#main-content">`) is output before the header in every page, visually hidden until focused (WCAG 2.1 AA).
- [ ] All PHP files carry the correct file-level docblock per the project convention.
- [ ] `make check` (PHPCS) passes with zero errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `functions.php` | Entry point: theme setup, enqueue hook, requires all `inc/` files |
| `inc/functions-design.php` | Theme supports, menu locations, image sizes |
| `inc/functions-helpers.php` | Shared utility functions |
| `inc/functions-data.php` | Scaffold only — data/query functions added in later steps |
| `inc/functions-form.php` | Scaffold only — form validation functions added in Step 30 |
| `inc/functions-ajax.php` | Scaffold only — AJAX handlers added in Step 30 |
| `inc/register-blocks.php` | Scaffold only — block registrations added per block step |
| `inc/register-cpt.php` | Scaffold only — CPT registrations added in Step 03 |
| `inc/register-taxonomy.php` | Scaffold only — taxonomy registrations added in Step 03 |
| `header.php` | Minimal WP header shell (`wp_head()`, skip link, opens `<body>`) |
| `footer.php` | Minimal WP footer shell (`wp_footer()`, closes `<body>`) |
| `index.php` | Fallback template (required by WP) |

### Files to Modify

| File | Change |
|---|---|
| `style.css` | Verify theme header comments are correct (name, version, text domain) |

---

## Notes

- Text domain must match the folder name: `ai-driven-boilerplate`.
- The Vite manifest lives at `assets/.vite/manifest.json`. Read it with `file_get_contents` + `json_decode`; cache the result in a static variable to avoid repeated file reads per request.
- `header.php` and `footer.php` are minimal shells at this stage — visual header/footer are added in Steps 05 and 06 via `get_template_part()`.
- Image size names must be documented in `inc/functions-design.php` as inline comments listing the dimensions.

---

## Out of Scope

- Visible header or footer UI (Steps 05–06)
- ACF options page (Step 04)
- Block or CPT registrations (Steps 03, 11–22)
