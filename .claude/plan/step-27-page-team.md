# Step 27 — Team Page Template

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 05, 06, 10, 21, 24  
**Required by:** nothing (end feature)

---

## Summary

Create a Team page template. This is a block-composed page that uses the page header layout component and houses the Team Block. The template itself is intentionally thin — the Team Block (Step 21) delivers the primary content.

---

## User Stories

- **As a site editor**, I want a pre-built Team page template so a new project site has a fully functional team page from day one.
- **As a visitor**, I want to find all team member profiles on a single branded page so I can get a sense of the whole organisation.

---

## Business Value

Providing a Team page template ensures every new project deployment has a consistent, well-structured team showcase out of the box without the editor having to assemble blocks from scratch.

---

## Acceptance Criteria

- [ ] `page-team.php` created with `Template Name: Team` header comment.
- [ ] Includes header and footer via `get_template_part()`.
- [ ] Page header (from Step 24's `page-header.php` component): `<h1>` from page title, breadcrumbs.
- [ ] Main content rendered via `the_content()` — Gutenberg block content.
- [ ] `id="main-content"` on `<main>`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `page-team.php` | WordPress named template |
| `template-parts/pages/team.php` | Page header + `the_content()` wrapper |

---

## Default Block Layout Suggestion

1. **Team Block** (pulled from Team Members CPT — all members, 3 columns)
2. **CTA Block** (e.g. "Join our team — we're hiring")

---

## Accessibility

- [ ] Page `<h1>` is the page title (e.g. "Our Team")
- [ ] Team Block renders member names as `<h3>` — correct heading hierarchy under the page `<h2>` (if any) or `<h1>`

---

## Out of Scope

- Team member individual single-page templates (out of scope per plan)
- Hiring/vacancies section
- Department filter on team page
