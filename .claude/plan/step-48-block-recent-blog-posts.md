# Step 48 — Recent Blog Posts Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Steps 01, 08 (card component), 36 (section_subheading pattern)  
**Required by:** nothing (end feature)

---

## Summary

Build an ACF Gutenberg block that surfaces a "From our blog" card grid on any page. The block runs a `WP_Query` for the latest published posts, optionally filtered to a single category, and renders each result through the existing Card component. The editor controls the section heading, subheading, the number of posts to display, an optional category filter, and an optional "View all posts" call-to-action link below the grid. No JavaScript or AJAX is involved — the grid is fully server-rendered on page load.

---

## User Stories

- **As a site editor**, I want to embed a "From our blog" section on the Home page so that visitors can discover recent articles without navigating away.
- **As a site editor**, I want to set how many posts appear in the grid so I can control the visual weight of the section on each page.
- **As a site editor**, I want to optionally restrict the grid to a specific category so I can promote relevant content on a service or topic page.
- **As a site editor**, I want an optional "View all posts" link below the grid so visitors have a clear path to the full blog archive.
- **As a developer**, I want post data normalised into the shared Card component args so the blog grid uses the same markup and styles as every other card grid in the theme.

---

## Business Value

A blog feed block turns the blog into an active lead-nurturing tool — surfacing recent content on high-traffic pages keeps the site feeling current and gives visitors reasons to return. Reusing the Card component means zero additional CSS maintenance cost and guaranteed visual consistency with the Services, Portfolio, and Team grids already present in the theme.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` with the slug `acf/recent-blog-posts`.
- [ ] ACF field group JSON written directly to `acf-json/group_recent_blog_posts_block.json`.
- [ ] Block preview image placeholder present at `assets/media/block-preview/recent-blog-posts.jpg`.

### Query

- [ ] `WP_Query` runs with `post_type => 'post'`, `post_status => 'publish'`, and `posts_per_page` set to the `number_of_posts` field value.
- [ ] When `filter_by_category` is non-empty, a `tax_query` is added targeting the `category` taxonomy.
- [ ] `wp_reset_postdata()` is called after the loop.
- [ ] If the query returns no posts, a short fallback message is rendered (e.g. `<p>No posts found.</p>`), escaped with `esc_html__()`.

### Card Normalisation

- [ ] Each post is normalised into an associative array of Card component args before `get_template_part()` is called.
- [ ] The following args are populated from post data:
  - `image` — post thumbnail ID (via `get_post_thumbnail_id()`); empty string when no thumbnail is set.
  - `category` — first assigned category name (via `get_the_category()`); empty string when none.
  - `title` — post title escaped at the call site.
  - `excerpt` — post excerpt (via `get_the_excerpt()`); trimmed to theme default length.
  - `link` — permalink (via `get_permalink()`).
- [ ] The Card component is included via `get_template_part( 'template-parts/components/card', null, $args )`.

### Layout

- [ ] Cards displayed in a responsive CSS grid: 3 columns on desktop, 2 on tablet, 1 on mobile.
- [ ] Layout mirrors the Services block grid (`template-parts/blocks/services.php`).

### Section Heading & Subheading

- [ ] `section_heading` renders as an `<h2>` when non-empty.
- [ ] `section_subheading` renders as a `<p class="block-subheading">` immediately after the `<h2>`, only when non-empty, following the pattern established in Step 36.

### View All Link

- [ ] When `show_view_all` is true and `view_all_label` is non-empty, a link to the blog archive (`get_post_type_archive_link( 'post' )`) is rendered below the grid.
- [ ] The link is rendered via the Button component or an equivalent theme button class.
- [ ] All link output is escaped with `esc_url()` and `esc_html()`.

### Code Quality

- [ ] All PHP output is escaped.
- [ ] No business logic in the block template — query and normalisation logic lives in a dedicated helper function in `inc/functions-data.php`.
- [ ] `make check` passes with no new PHPCS errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/recent-blog-posts.php` | Block template — reads ACF fields, calls data helper, renders heading, card grid, and view-all link |
| `src/css/blocks/recent-blog-posts.css` | Block-scoped styles for the card grid layout |
| `acf-json/group_recent_blog_posts_block.json` | ACF Local JSON field group definition |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/recent-blog-posts` block |
| `inc/functions-data.php` | Add `ai_driven_get_recent_posts( int $count, int $category_id ): array` — runs `WP_Query`, normalises results into card args arrays, calls `wp_reset_postdata()`, returns the array |
| `src/css/main.css` | Add `@import "blocks/recent-blog-posts.css"` |

---

## ACF Field Group: Recent Blog Posts Block

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Optional; rendered as `<h2>` |
| Section Subheading | `section_subheading` | Text | Optional; rendered as `<p class="block-subheading">` |
| Number of Posts | `number_of_posts` | Number | Default: 3; min: 1; max: 12 |
| Filter by Category | `filter_by_category` | Taxonomy | Taxonomy: `category`; field type: select; optional; allow null |
| Show "View All" Link | `show_view_all` | True/False | Default: off |
| "View All" Label | `view_all_label` | Text | Default: `View all posts`; conditional on `show_view_all == 1` |

---

## Data Helper: `ai_driven_get_recent_posts()`

```php
/**
 * Query and normalise recent posts into card component args.
 *
 * @param int $count       Number of posts to retrieve.
 * @param int $category_id Optional category term ID; 0 means no filter.
 * @return array[] Array of card args arrays.
 */
function ai_driven_get_recent_posts( int $count, int $category_id = 0 ): array {}
```

- Builds `WP_Query` args; adds `tax_query` only when `$category_id > 0`.
- Iterates the query loop; for each post calls `setup_postdata()`, builds the card args array, then appends to the return array.
- Calls `wp_reset_postdata()` before returning.
- Returns an empty array when the query finds no posts.

---

## Notes

- The `filter_by_category` field stores the term ID. Pass it directly as `$category_id` to the helper; guard against a falsy value (empty selection) with `(int) $category_id ?: 0`.
- `get_the_excerpt()` respects the `excerpt_length` and `excerpt_more` filters already set in the theme — no manual truncation needed in the helper.
- `get_post_thumbnail_id()` returns `0` when no thumbnail is set; the Card component must handle a falsy image arg gracefully (this should already be the case from Step 08).
- If `get_the_category()` returns an empty array, pass an empty string for the `category` card arg rather than triggering an array offset notice.
- The block preview image can be a placeholder JPG at the correct path; a real screenshot is produced during QA.
- The field key naming convention for this block is `field_recent_blog_posts_{field_name}` (e.g. `field_recent_blog_posts_section_heading`).

---

## Out of Scope

- Pagination or "load more" for the blog grid (handled by the Blog archive — Step 28)
- AJAX-powered category switching without a page reload
- Custom post ordering (sticky posts, manual sort)
- Displaying post author or reading time in the card (those are single-post features from Step 44)
- A dedicated blog-specific card layout that differs from the shared Card component
