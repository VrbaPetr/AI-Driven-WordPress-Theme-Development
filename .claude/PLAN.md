# AI-Driven Boilerplate — Build Plan

## Overview

A WordPress boilerplate theme for IT agencies and freelancers. Classic PHP theme (no FSE), ACF Pro-backed Gutenberg blocks, Tailwind CSS v4, Alpine.js, and Vite. The finished theme is a ready-to-deploy starting point that covers a full marketing site: landing pages, CPT archives, a blog, and a contact form.

## Guiding Decisions (from survey)

| Decision | Choice |
|---|---|
| Audience | Both IT agency and freelancer/solo |
| Primary conversion goal | Form, booking, and portfolio — all supported |
| Blog | Full: archive, single, categories, tags |
| i18n | WPML/Polylang-compatible, no RTL |
| Navigation | Two-level dropdown + CTA button, sticky header |
| Footer | Multi-column with widget areas, social icons, copyright |
| CPTs | Services, Portfolio/Projects, Team Members, Testimonials |
| Page templates | Home, About, Services (archive + single), Portfolio (archive + single), Team, Blog (archive + single), Contact |
| Cookie consent | Plugin (no custom implementation) |
| Search | Custom styled results page |
| Accessibility | WCAG 2.1 AA |
| SEO | Semantic HTML + Open Graph + FAQ schema |
| Performance | Critical/non-critical asset split, lazy images |
| ACF fields | JSON generated directly into `acf-json/` — no admin UI step required |

---

## Phases & Steps

### Phase 1 — Foundation
Infrastructure only, no visible UI. Every subsequent step depends on this phase.

| Step | Feature | Summary |
|---|---|---|
| [01](plan/step-01-theme-foundation.md) | Theme Foundation Setup | functions.php structure, Vite asset enqueueing, theme supports, nav menus, image sizes, helper library, scaffold includes |
| [02](plan/step-02-design-tokens.md) | Design Tokens | Tailwind v4 `@theme` tokens — colour palette, typography scale, spacing, radii, shadows, z-index, transitions |
| [03](plan/step-03-cpt-registration.md) | Custom Post Types | Services, Portfolio/Projects, Team Members, Testimonials — labels, supports, rewrite slugs |
| [04](plan/step-04-acf-options-page.md) | ACF Global Options Page | Company info, social links, contact details, footer text, global CTA — accessible site-wide via `get_field('', 'option')` |

### Phase 2 — Global Layout
Sitewide chrome that wraps every page.

| Step | Feature | Summary |
|---|---|---|
| [05](plan/step-05-header.md) | Header & Navigation | Sticky header, two-level dropdown, mobile hamburger (Alpine.js), CTA button, WCAG AA keyboard nav |
| [06](plan/step-06-footer.md) | Footer | Multi-column layout, footer widget areas, social icons from options, copyright bar |

### Phase 3 — Reusable UI Components
Atomic components used across blocks and templates.

| Step | Feature | Summary |
|---|---|---|
| [07](plan/step-07-component-button.md) | Button Component | Primary/secondary/outline/ghost variants, sm/md/lg sizes, icon support, `<a>` and `<button>` |
| [08](plan/step-08-component-card.md) | Card Component | Generic image + category + title + excerpt + link card; used by blog, services, portfolio |
| [09](plan/step-09-component-badge-alert.md) | Badge & Alert Components | Inline badges (status/category labels) and dismissible alert/notice banners |
| [10](plan/step-10-component-breadcrumbs-pagination.md) | Breadcrumbs & Pagination | Auto-generated breadcrumbs with BreadcrumbList schema; standard WP pagination wrapper |

### Phase 4 — Content Blocks
Gutenberg blocks with manual/editor-controlled content.

| Step | Feature | Summary |
|---|---|---|
| [11](plan/step-11-block-hero.md) | Hero Block | Two variants: full-width image background and split text/image layout; two CTA buttons |
| [12](plan/step-12-block-text-image.md) | Text & Image Block | WYSIWYG text with image, left/right position toggle, optional CTA button |
| [13](plan/step-13-block-stats.md) | Stats / Counters Block | Up to 4 metrics with count-up animation on scroll (Intersection Observer) |
| [14](plan/step-14-block-process.md) | Process / Steps Block | Numbered steps with icon + title + description; horizontal or vertical layout |
| [15](plan/step-15-block-cta.md) | CTA Block | Headline, subtext, up to two buttons, solid colour or gradient background |
| [16](plan/step-16-block-faq.md) | FAQ Accordion Block | Alpine.js accordion, optional multi-open, JSON-LD FAQ structured data |

### Phase 5 — CPT-Backed Blocks
Gutenberg blocks that pull content from Custom Post Types.

| Step | Feature | Summary |
|---|---|---|
| [17](plan/step-17-block-services.md) | Services Block | Card grid from Services CPT or manual repeater; section heading |
| [18](plan/step-18-block-portfolio-grid.md) | Portfolio Filterable Grid Block | Filterable by Portfolio category; Alpine.js filter UI; AJAX load-more |
| [19](plan/step-19-block-testimonials.md) | Testimonials Slider Block | Slider from Testimonials CPT; Alpine.js carousel |
| [20](plan/step-20-block-clients.md) | Clients / Logos Strip Block | Manual logos repeater; grayscale/colour toggle; optional marquee scroll |
| [21](plan/step-21-block-team.md) | Team Block | Grid from Team Members CPT; photo, name, role, social links |
| [22](plan/step-22-block-pricing.md) | Pricing Block | Up to 4 plan cards; featured card highlight; feature list; CTA button |

### Phase 6 — Page Templates
Full page templates and CPT archive/single layouts.

| Step | Feature | Summary |
|---|---|---|
| [23](plan/step-23-page-home.md) | Home Page Template | Block-composed; sets OG meta; default block order suggestion in editor |
| [24](plan/step-24-page-about.md) | About Page Template | Block-composed page with page header section |
| [25](plan/step-25-page-services.md) | Services Archive & Single | Archive: card grid; Single: service detail with related services |
| [26](plan/step-26-page-portfolio.md) | Portfolio Archive & Single | Archive: filterable grid; Single: case study layout with gallery and related work |
| [27](plan/step-27-page-team.md) | Team Page Template | Block-composed page housing the Team Block |
| [28](plan/step-28-page-blog.md) | Blog Archive & Single | Archive: card grid with category filters; Single: article with author, tags, related posts |
| [29](plan/step-29-page-contact.md) | Contact Page Template | Page header + contact form + contact details from options |

### Phase 7 — Forms & Site Utilities
Functional features and site-wide utilities.

| Step | Feature | Summary |
|---|---|---|
| [30](plan/step-30-contact-form.md) | Contact AJAX Form | Custom form with AJAX submission, honeypot, nonce, email notification, Alpine.js state |
| [31](plan/step-31-search-results.md) | Search Results Page | Styled results list, "no results" state, search form in page header |
| [32](plan/step-32-404.md) | Custom 404 Page | Branded error page with headline, message, search bar, home link |
| [33](plan/step-33-seo-foundations.md) | SEO Foundations | Open Graph + Twitter Card meta, Organisation schema, Article schema on posts |

---

## Key Dependencies

```
Phase 1 (01–04) → all other phases
Step 02 (tokens) → all CSS files (Steps 05–33)

Phase 2 (05–06) → needed before any page template (Phase 6)

Phase 3 (07–10) → components used by Phase 4–5 blocks and Phase 6 templates

Phase 4–5 (11–22) → blocks used in Phase 6 templates

Step 30 (form) → must come before or alongside Step 29 (contact page)
```

## Process Conventions

### Version bumping

Bump the theme version in `style.css` after every phase is complete. Use minor version increments — one per phase:

| After phase | Version |
|---|---|
| Phase 1 — Foundation | `0.1.0` |
| Phase 2 — Global Layout | `0.2.0` |
| Phase 3 — UI Components | `0.3.0` |
| Phase 4 — Content Blocks | `0.4.0` |
| Phase 5 — CPT Blocks | `0.5.0` |
| Phase 6 — Page Templates | `0.6.0` |
| Phase 7 — Forms & Utilities | `1.0.0` |

The final phase produces `1.0.0` — the first production-ready release.

---

## Excluded from Scope

- Cookie consent (handled by plugin)
- RTL layout
- Full Site Editing (FSE) / block themes
- Page builders
- Headless / REST-only setup
- CPT for FAQs (FAQ block uses manual repeater)
- Team member single-page templates
