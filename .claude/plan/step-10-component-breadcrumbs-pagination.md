# Step 10 — Breadcrumbs & Pagination Components

**Phase:** 3 — Reusable UI Components  
**Depends on:** Step 01  
**Required by:** Steps 24, 25, 26, 27, 28, 29, 31

---

## Summary

Build two navigation utility components: **Breadcrumbs** (auto-generated from the page hierarchy with BreadcrumbList schema markup) and **Pagination** (a styled wrapper around WordPress's `paginate_links()`).

---

## User Stories

- **As a visitor on a service detail page**, I want to see where I am in the site hierarchy so I can navigate back to the services overview.
- **As a visitor on a blog archive**, I want a page navigation element so I can browse older posts without losing context.
- **As an SEO manager**, I want breadcrumb structured data so Google can display rich snippets in search results.

---

## Business Value

Breadcrumbs reduce pogo-sticking on CPT single pages and improve crawlability. Pagination is required for any archive with more than one page of results.

---

## Acceptance Criteria

### Breadcrumbs

- [ ] Auto-generates the crumb trail from: Home → (optional CPT archive) → (optional taxonomy term) → Current Page.
- [ ] Outputs `<nav aria-label="Breadcrumb">` wrapping an `<ol>`.
- [ ] Each crumb is an `<li>` with an `<a>` (except the last item which is `aria-current="page"`).
- [ ] Outputs `<script type="application/ld+json">` with a valid `BreadcrumbList` schema.
- [ ] Accepts: `classes` (string, optional).
- [ ] Does not render on the Home page (guard: `is_front_page()`).
- [ ] Output escaped.

### Pagination

- [ ] Wraps `paginate_links()` in a `<nav aria-label="Page navigation">` with an `<ul>`.
- [ ] Previous/Next arrows included with proper `aria-label`.
- [ ] Active page has `aria-current="page"`.
- [ ] Accepts: `query` (WP_Query object, optional — falls back to global `$wp_query`), `classes` (string).
- [ ] Does not render if only one page of results.
- [ ] Output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/components/breadcrumbs.php` | Breadcrumbs markup + JSON-LD schema |
| `template-parts/components/pagination.php` | Pagination markup |
| `src/css/components/breadcrumbs.css` | Breadcrumb styles |
| `src/css/components/pagination.css` | Pagination styles |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `components/breadcrumbs.css` and `components/pagination.css` |

---

## Component Interfaces

```php
// Breadcrumbs — called near top of inner page templates
get_template_part( 'template-parts/components/breadcrumbs' );

// Pagination — called after the posts loop
get_template_part( 'template-parts/components/pagination', null, [
    'query' => $custom_query, // optional
] );
```

---

## BreadcrumbList Schema Structure

```json
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://example.com/" },
    { "@type": "ListItem", "position": 2, "name": "Services", "item": "https://example.com/services/" },
    { "@type": "ListItem", "position": 3, "name": "Web Development" }
  ]
}
```

---

## Accessibility

- [ ] Breadcrumb `<nav>` has `aria-label="Breadcrumb"`
- [ ] Current page crumb has `aria-current="page"`
- [ ] Pagination `<nav>` has `aria-label="Page navigation"`
- [ ] Pagination prev/next links have descriptive `aria-label` not just `‹`/`›` symbols

---

## Out of Scope

- Third-party breadcrumb plugin integration (Yoast, RankMath breadcrumbs)
- Load more / infinite scroll pagination (not selected in survey)
