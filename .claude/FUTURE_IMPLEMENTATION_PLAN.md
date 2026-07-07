# AI-Driven Boilerplate — Future Implementation Plan

## Overview

Continuation of the original build plan (Phases 1–7, Steps 01–33). This document covers all features identified in `FUTURE_IMPLEMENTATION.md` — accessibility fixes, icon system, site UX utilities, blog enhancements, and 9 new content blocks.

The theme enters this plan at version `1.0.1`. Each completed phase bumps the minor version.

---

## Phases & Steps

### Phase 8 — Accessibility & Editor Fixes
Correctness fixes to existing code. No new features — these unblock compliant and on-brand editing.

| Step | Feature | Summary |
|---|---|---|
| [34](future-implementation-plan/step-34-accessibility-fixes.md) | Accessibility Fixes | Skip to content link in header; `id="main-content"` on all page templates |
| [35](future-implementation-plan/step-35-editor-enhancements.md) | Editor & Analytics Enhancements | Gutenberg color palette + disable custom colors; GTM `wp_body_open` integration |
| [36](future-implementation-plan/step-36-block-heading-unification.md) | Block Heading Unification | Add `section_subheading` field to 9 existing blocks — ACF JSON + template updates |

### Phase 9 — Icon System
Restructure the icon library and make ACF icon selectors self-maintaining.

| Step | Feature | Summary |
|---|---|---|
| [37](future-implementation-plan/step-37-icon-system-foundation.md) | Icon System Foundation | Subdirectory structure; dynamic `acf/load_field` filter; field renames; path helper |
| [38](future-implementation-plan/step-38-icon-library.md) | Icon Library | 81 new SVG icons across `social/`, `tech/`, and `ui/` subdirectories |

### Phase 10 — Site UX & Utilities
Sitewide features that improve usability, client handoff quality, and analytics integration.

| Step | Feature | Summary |
|---|---|---|
| [39](future-implementation-plan/step-39-smooth-scroll-back-to-top.md) | Smooth Scroll & Back to Top | Anchor scroll with header offset; fixed back-to-top button with fade-in |
| [40](future-implementation-plan/step-40-print-styles.md) | Print Styles | `@media print` stylesheet hiding chrome, setting readable black-on-white output |
| [41](future-implementation-plan/step-41-header-search-overlay.md) | Header Search Overlay | Optional full-screen search overlay triggered from header; Alpine.store state |
| [42](future-implementation-plan/step-42-custom-login-page.md) | Custom Login Page | Branded `wp-login.php` with theme logo and colors via WP login hooks |
| [43](future-implementation-plan/step-43-maintenance-mode.md) | Maintenance / Coming Soon | `template_redirect` maintenance mode; `coming-soon.php` standalone template; 503 header |

### Phase 11 — Blog Enhancements
Reader-experience improvements for `single.php`.

| Step | Feature | Summary |
|---|---|---|
| [44](future-implementation-plan/step-44-reading-time-progress.md) | Reading Time & Progress Bar | Estimated read time in post meta; scroll-driven progress bar fixed to viewport top |
| [45](future-implementation-plan/step-45-table-of-contents.md) | Table of Contents | Auto-generated TOC from post headings; injected `id` attributes via `the_content` filter |

### Phase 12 — New Content Blocks
Nine new Gutenberg blocks expanding the editor's page-building toolkit.

| Step | Feature | Summary |
|---|---|---|
| [46](future-implementation-plan/step-46-block-welcome-slider.md) | Welcome Slider Block | Full-viewport slider; per-slide bg image + overlay + heading + 2 CTAs; Alpine.js carousel |
| [47](future-implementation-plan/step-47-block-text-content.md) | Text Content Block | WYSIWYG block with prose wrapper; width selector; replaces unstyled core blocks |
| [48](future-implementation-plan/step-48-block-recent-posts.md) | Recent Blog Posts Block | Card grid from WP posts; category filter; optional "View all" link |
| [49](future-implementation-plan/step-49-block-feature-grid.md) | Feature / Icon Grid Block | Manual icon + title + description grid; 2/3/4 column select; "Why us" pattern |
| [50](future-implementation-plan/step-50-block-video.md) | Video Block | YouTube/Vimeo facade embed; custom thumbnail; click-to-load iframe for performance |
| [51](future-implementation-plan/step-51-block-timeline.md) | Timeline Block | Dated milestones repeater; alternating desktop layout; Intersection Observer fade-in |
| [52](future-implementation-plan/step-52-block-gallery.md) | Gallery / Image Grid Block | ACF gallery field; 2/3/4 columns; optional Alpine.js lightbox |
| [53](future-implementation-plan/step-53-block-awards.md) | Awards & Certifications Block | Badge + name + issuer + year repeater; centred flex display |
| [54](future-implementation-plan/step-54-block-newsletter.md) | Newsletter / Email Capture Block | Email opt-in strip; honeypot + nonce; Alpine.js states; optional provider webhook |

---

## Key Dependencies

```
Phase 8 (34–36) → should be completed before Phase 12 blocks
  Step 36 (heading unification) → establishes the section_subheading pattern all new blocks follow

Phase 9 (37–38) → must be completed before any block that uses icon selectors
  Step 37 (icon system) → Step 38 (icons), Step 49 (feature grid)

Phase 10 (39–43) → independent of blocks; can run alongside Phase 12
  Step 41 (search overlay) → depends on Step 38 (search.svg icon)
  Step 39 (smooth scroll) → Step 45 (TOC anchor links)

Phase 11 (44–45) → independent; targets single.php only
  Step 39 (smooth scroll) → Step 45 (TOC click-to-scroll)

Phase 12 (46–54) → depends on Phase 8 and Phase 9
  Step 37 (icon system) → Step 49 (feature grid), Step 51 (timeline)
```

---

## Process Conventions

### Version bumping

Bump the theme version in `style.css` after every phase is complete:

| After phase | Version |
|---|---|
| Phase 8 — Accessibility & Editor Fixes | `1.1.0` |
| Phase 9 — Icon System | `1.2.0` |
| Phase 10 — Site UX & Utilities | `1.3.0` |
| Phase 11 — Blog Enhancements | `1.4.0` |
| Phase 12 — New Content Blocks | `1.5.0` |

### Branch & commit conventions

Same as the original plan — one feature branch per step (`feat/step-34-accessibility-fixes`), PR into `develop`, user merges.

---

## Reference

- Original plan: `.claude/PLAN.md` (Steps 01–33, Phases 1–7)
- Feature analysis: `.claude/FUTURE_IMPLEMENTATION.md`
- Step detail files: `.claude/future-implementation-plan/`
