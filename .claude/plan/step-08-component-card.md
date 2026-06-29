# Step 08 — Card Component

**Phase:** 3 — Reusable UI Components  
**Depends on:** Steps 01, 07  
**Required by:** Steps 17, 25, 26, 28

---

## Summary

Build a generic Card component used across blog post listings, service grids, and portfolio archives. The card displays a thumbnail image, category badge, title, short excerpt, and an optional CTA link.

---

## User Stories

- **As a visitor**, I want consistent card layouts when browsing services, projects, and blog posts so the site feels coherent.
- **As a developer**, I want one card template that handles all listing contexts so I don't maintain three near-identical templates.

---

## Business Value

A single card component normalises presentation across CPT archives and the blog. Updates to the card design (hover effect, typography) propagate everywhere automatically.

---

## Acceptance Criteria

- [ ] Component accepts: `image_id` (int, WP attachment ID), `image_size` (string, default `card-thumbnail`), `category` (string, label text), `category_url` (string, optional), `title` (string, required), `title_url` (string, required), `excerpt` (string, optional), `cta_label` (string, optional), `cta_url` (string, optional), `classes` (string, extra classes).
- [ ] Image rendered via `wp_get_attachment_image()` with the passed `image_size`; lazy loading applied.
- [ ] Category rendered using the Badge component (Step 09) if available, otherwise a styled `<span>`.
- [ ] Title is an `<h3>` wrapped in an `<a>`.
- [ ] Excerpt truncated to a reasonable length if not pre-truncated by the caller.
- [ ] CTA link rendered at the bottom if `cta_label` and `cta_url` are passed.
- [ ] Entire card has a hover state (lift shadow or subtle background change).
- [ ] Output fully escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/components/card.php` | Card markup |
| `src/css/components/card.css` | Card styles (imported into `main.css`) |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `components/card.css` |

---

## Component Interface

```php
get_template_part(
    'template-parts/components/card',
    null,
    [
        'image_id'     => get_post_thumbnail_id(),
        'image_size'   => 'card-thumbnail',
        'category'     => 'Web Development',
        'category_url' => get_term_link( $term ),
        'title'        => get_the_title(),
        'title_url'    => get_permalink(),
        'excerpt'      => get_the_excerpt(),
        'cta_label'    => __( 'Read more', 'ai-driven-boilerplate' ),
        'cta_url'      => get_permalink(),
    ]
);
```

---

## Accessibility

- [ ] Image has meaningful alt text (from WP attachment alt field)
- [ ] Card link target large enough for touch (min 44 × 44 px)
- [ ] Hover/focus state visible for keyboard users

---

## Out of Scope

- Horizontal card layout (image left, text right)
- Card with video thumbnail
- Pricing card (handled in Step 22 — Pricing Block)
