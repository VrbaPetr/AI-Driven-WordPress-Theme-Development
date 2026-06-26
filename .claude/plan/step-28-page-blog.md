# Step 28 — Blog Archive & Single Post Templates

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 05, 06, 07, 08, 09, 10, 24  
**Required by:** nothing (end feature)

---

## Summary

Create the full blog system: a blog archive listing (with category and tag filters), a single post template (with author info, social share, prev/next navigation, related posts), and category/tag archive templates.

---

## User Stories

- **As a visitor**, I want to browse blog posts filtered by category so I can find articles on topics I care about.
- **As a reader**, I want to see the author, publish date, and tags on a post so I can assess credibility and navigate related content.
- **As a site editor**, I want a clean single-post template with a sidebar or related posts so readers stay engaged after finishing an article.

---

## Business Value

A blog builds organic search traffic and demonstrates expertise over time. Well-structured blog templates (with related posts and clear CTAs) turn one-time readers into leads.

---

## Acceptance Criteria

### Blog Archive (`home.php` / `archive.php`)

- [ ] Blog index uses WP's `home.php` for the designated Posts page; `archive.php` for category/tag archives.
- [ ] Posts listed as cards using the Card component (Step 08): featured image, category badge, title, excerpt, date, read time estimate.
- [ ] Category filter links above the grid (auto-generated from post categories).
- [ ] Responsive grid: 3 columns → 2 → 1.
- [ ] Pagination component at the bottom.
- [ ] Page header: archive title (`<h1>`), breadcrumbs.

### Single Post (`single.php`)

- [ ] Page header: post title as `<h1>`, publish date, author name + avatar, estimated read time, category badges.
- [ ] Featured image displayed full-width below the page header.
- [ ] Post content rendered via `the_content()`.
- [ ] Tags listed below the content.
- [ ] Social share buttons: Twitter/X, LinkedIn, copy link (Alpine.js clipboard copy).
- [ ] Author box below content: avatar, name, bio (from user profile), link to author archive.
- [ ] Prev/Next navigation (adjacent posts).
- [ ] Related Posts section: 3 posts from the same primary category, rendered as cards.
- [ ] CTA block or inline CTA ("Enjoyed this article? Let's talk") at the bottom.

### Category & Tag Archives

- [ ] `category.php` and `tag.php` reuse `archive.php` with term title/description in page header.

- [ ] All output escaped; `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `home.php` | Blog index (posts page) |
| `archive.php` | Category, tag, date archives |
| `single.php` | Single post template |
| `category.php` | Category archive (can `get_template_part` from archive) |
| `tag.php` | Tag archive (can `get_template_part` from archive) |
| `template-parts/pages/blog-archive.php` | Archive loop and card grid |
| `template-parts/pages/blog-single.php` | Single post content + metadata |
| `src/css/pages/blog.css` | Blog-specific styles |
| `src/js/scripts/share.js` | Clipboard copy for share link |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Import `pages/blog.css` |
| `src/js/main.js` | Import `./scripts/share.js` |
| `inc/functions-helpers.php` | Add `aidriven_get_read_time( $post_id )` helper |

---

## Read Time Calculation

```php
function aidriven_get_read_time( $post_id ) {
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes    = max( 1, (int) round( $word_count / 200 ) );
    return $minutes;
}
```

---

## Accessibility

- [ ] Social share buttons have descriptive `aria-label`
- [ ] Author bio section is a `<section>` with `aria-label="About the author"`
- [ ] Prev/Next navigation: descriptive link text includes post title, not just "Previous"/"Next"
- [ ] Related posts heading is `<h2>` ("Related Articles")

---

## Out of Scope

- Comments section (turned off or plugin-handled)
- Infinite scroll (pagination only per survey)
- Newsletter signup embedded in single post (no newsletter feature selected)
- Custom article CPT (blog uses standard Posts)
