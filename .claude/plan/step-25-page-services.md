# Step 25 — Services Archive & Single Templates

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 03, 05, 06, 07, 08, 09, 10, 15, 24  
**Required by:** nothing (end feature)

---

## Summary

Create two templates for the Services CPT: an **archive** that lists all services in a card grid with optional category filter, and a **single** page that displays the full service detail with a related services section.

---

## User Stories

- **As a visitor**, I want to browse all services in one place and filter by category so I can quickly find services relevant to my needs.
- **As a visitor on a service detail page**, I want to read the full description and see related services so I understand what the service includes and can explore adjacent offerings.
- **As a site editor**, I want new Services CPT posts to appear in the archive automatically so I don't manually update any list page.

---

## Business Value

A dedicated Services archive legitimises the company's offerings and improves SEO for service-category keywords. Individual service pages support long-tail keyword targeting ("WordPress development for healthcare", etc.).

---

## Acceptance Criteria

### Archive (`archive-services.php`)

- [ ] Displays all Services CPT posts in a responsive card grid (3 col → 2 → 1).
- [ ] Category filter buttons above the grid (Service Categories); filters with Alpine.js or page reload.
- [ ] Each card: service icon, title, short description, "Learn More" link.
- [ ] Pagination via Pagination component (Step 10) when there are more posts than `posts_per_page`.
- [ ] Page header: `<h1>` "Services" or custom archive title, breadcrumbs.
- [ ] All output escaped.

### Single (`single-service.php`)

- [ ] Page header: service title as `<h1>`, breadcrumbs (Home → Services → Service Name).
- [ ] Service content: featured image, full editor content via `the_content()`.
- [ ] Sidebar or below-content: service icon, short description summary box (from ACF fields).
- [ ] Related Services section: 3 other services from the same Service Category, rendered as cards.
- [ ] CTA Block or inline CTA (hardcoded or via ACF field) prompting contact.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `archive-service.php` | Archive template for Services CPT |
| `single-service.php` | Single post template for Services CPT |
| `template-parts/pages/services-archive.php` | Archive content part |
| `template-parts/pages/services-single.php` | Single content part |

---

## Related Services Query

```php
$related = new WP_Query([
    'post_type'      => 'service',
    'posts_per_page' => 3,
    'post__not_in'   => [ get_the_ID() ],
    'tax_query'      => [[ 'taxonomy' => 'service-category', 'field' => 'term_id', 'terms' => $term_ids ]],
]);
```

---

## ACF Fields Needed on Services CPT (in WP admin)

These supplement the fields defined in Step 17:

| Field label | Field type | Notes |
|---|---|---|
| Service Icon | Text | SVG icon name |
| Short Description | Textarea | Used in archive card and single summary box |

---

## Accessibility

- [ ] Archive filter buttons: `aria-pressed` state on active category
- [ ] Service card links descriptive (service name, not "Read more")
- [ ] Breadcrumbs on single page with correct schema

---

## Out of Scope

- Service comparison page
- Service pricing display on archive (use Pricing block on individual service pages)
