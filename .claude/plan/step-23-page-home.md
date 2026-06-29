# Step 23 — Home Page Template

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 05, 06, 07, 11, 13, 15, 17, 18, 19, 20, 22  
**Required by:** nothing (end feature)

---

## Summary

Create a dedicated Home page template that is fully block-composed via Gutenberg. The template sets the correct `<h1>` context, outputs Open Graph meta for the home page, and establishes the recommended default block layout in the editor.

---

## User Stories

- **As a site editor**, I want a Home page template that already has the right Gutenberg block layout suggested so I don't have to figure out block ordering from scratch on a new project.
- **As a developer**, I want the Home page to output correct OG meta so social media shares look professional without a third-party SEO plugin.

---

## Business Value

The Home page is the highest-traffic page and the primary conversion surface. A well-structured template with sensible defaults speeds up new project setup and ensures consistent quality across client deployments.

---

## Acceptance Criteria

- [ ] `front-page.php` template file created (WP uses this for the static front page).
- [ ] Template includes header (Step 05), main content area, and footer (Step 06).
- [ ] Main content area has `id="main-content"` (skip-to-content target from Step 01).
- [ ] Open Graph meta tags output for home page: `og:title`, `og:description`, `og:url`, `og:image` (site logo from options), `og:type: website`.
- [ ] Gutenberg block content rendered via `the_content()`.
- [ ] No hardcoded sections — all content is driven by Gutenberg blocks.
- [ ] Default block layout suggestion documented in the step (editor reference, not code).
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `front-page.php` | WordPress front page template |
| `template-parts/pages/home.php` | Semantic wrapper, `the_content()` call |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-design.php` | Ensure `front-page` is part of OG meta hook coverage (covered in Step 33, may be a dependency note) |

---

## Recommended Default Block Layout

Document the following as a setup reference for new projects:

1. **Hero Block** (Image Background or Split variant)
2. **Clients / Logos Strip** (trust signal above the fold)
3. **Services Block** (CPT-backed, 3–6 items)
4. **Text & Image Block** (about/value proposition teaser)
5. **Stats / Counters Block**
6. **Portfolio Grid Block** (6 items, filtered)
7. **Testimonials Slider Block**
8. **CTA Block** (primary conversion prompt)

---

## OG Meta Notes

OG tags at this stage can be minimal inline logic in the template using options page fields. Step 33 (SEO Foundations) will refactor these into a centralised `inc/functions-seo.php`.

---

## Accessibility

- [ ] Page `<title>` is meaningful and unique (handled by WordPress `title-tag` theme support)
- [ ] `<main id="main-content">` landmark present for skip link

---

## Out of Scope

- Hardcoded design layout (all via blocks)
- Custom page builder metabox
- Animated page transitions
