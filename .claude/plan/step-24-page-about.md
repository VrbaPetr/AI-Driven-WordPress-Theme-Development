# Step 24 — About Page Template

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 05, 06, 10, 11, 12, 13, 15, 21  
**Required by:** nothing (end feature)

---

## Summary

Create an About page template. Like the Home page, it is fully block-composed. The template adds a standardised page header section (title + optional subtitle + breadcrumbs) above the Gutenberg block content.

---

## User Stories

- **As a site editor**, I want the About page to have a consistent page header with the title and breadcrumbs so it matches the style of other inner pages.
- **As a visitor**, I want to learn about the company's team, history, and values so I can decide whether to trust them with my project.

---

## Business Value

The About page is the second most visited page on most agency sites. A clean template with a page header component ensures visual consistency across all inner pages without rebuilding the same header markup in every template.

---

## Acceptance Criteria

- [ ] `page-about.php` WordPress template file created (or uses the `Template Name: About` comment header for the page attributes).
- [ ] Includes header (Step 05) and footer (Step 06).
- [ ] Page header section: page `<h1>` (from `get_the_title()`), optional subtitle (from a Page-level ACF field or excerpt), breadcrumbs component (Step 10).
- [ ] Main content area renders Gutenberg block content via `the_content()`.
- [ ] `id="main-content"` on the `<main>` element.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `page-about.php` | WordPress named template with `Template Name: About` |
| `template-parts/pages/about.php` | Page header + `the_content()` wrapper |
| `template-parts/layout/page-header.php` | Reusable page header component (title + subtitle + breadcrumbs) — used across all inner page templates |
| `src/css/layout/page-header.css` | Page header styles |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `layout/page-header.css` |

---

## Page Header Component

The `template-parts/layout/page-header.php` component is introduced in this step and reused in Steps 25–29. It accepts:

| Argument | Type | Notes |
|---|---|---|
| `title` | string | Defaults to `get_the_title()` |
| `subtitle` | string | Optional; from page excerpt or ACF field |
| `show_breadcrumbs` | bool | Default: true |
| `classes` | string | Extra CSS classes on the wrapper |

---

## Recommended Default Block Layout (About Page)

1. **Text & Image Block** (company story / founding narrative)
2. **Stats / Counters Block** (years in business, clients, projects)
3. **Team Block** (pulled from Team Members CPT)
4. **Process / Steps Block** (how the team works)
5. **CTA Block**

---

## Accessibility

- [ ] `<h1>` is the page title in the page header — no blocks should add a second `<h1>`
- [ ] Breadcrumbs rendered per Step 10 spec (schema + `aria-label`)

---

## Out of Scope

- Company timeline (not in scope — use Process block for this)
- Values/mission section as a separate template part (use Text & Image block)
