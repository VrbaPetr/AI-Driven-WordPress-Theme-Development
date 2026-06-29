# Step 18 — Portfolio Filterable Grid Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Steps 01, 03, 08, 09  
**Required by:** Steps 23, 26

---

## Summary

Build an ACF block that displays Portfolio/Projects CPT posts as a filterable grid. Visitors can filter by Portfolio Category using Alpine.js-powered filter buttons. A "Load More" button fetches additional posts via AJAX.

---

## User Stories

- **As a visitor**, I want to filter the portfolio by project type so I can see only the work that is relevant to my industry.
- **As a site editor**, I want the block to pull from the Portfolio CPT automatically so I only need to add a new Project post and it appears in the grid.

---

## Business Value

A filterable portfolio directly increases the relevance of case studies for different visitor segments (e.g. a healthcare client filters to see only healthcare projects), improving conversion from portfolio view to enquiry.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Pulls from Portfolio CPT via `WP_Query`; initial number of items configurable.
- [ ] Filter buttons auto-generated from Portfolio Categories that have at least one post.
- [ ] Alpine.js filters cards client-side by toggling visibility (no page reload for initial set).
- [ ] "Load More" button triggers an AJAX call that returns the next batch of posts matching the active filter.
- [ ] Optional section heading.
- [ ] Cards: thumbnail, Portfolio Category badge (Step 09), project title, short excerpt; clicking card goes to single Project page.
- [ ] Hover overlay on card thumbnail showing project title and a view icon (optional but recommended).
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped; AJAX handler registered in `inc/functions-ajax.php`; nonce verified on AJAX request.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/portfolio-grid.php` | Block template |
| `src/css/blocks/portfolio-grid.css` | Block styles |
| `src/js/scripts/portfolio-filter.js` | Alpine component or AJAX load-more logic |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/portfolio-grid` block |
| `inc/functions-ajax.php` | Add `wp_ajax_load_portfolio` and `wp_ajax_nopriv_load_portfolio` handlers |
| `src/css/main.css` | Import `blocks/portfolio-grid.css` |
| `src/js/main.js` | Import `./scripts/portfolio-filter.js` |

---

## ACF Field Group: Portfolio Grid Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Initial Items Count | Number | Default: 6 |
| Items Per Load | Number | How many more to load per AJAX call; default: 3 |
| Show Filter Buttons | True/False | Default: true |
| Show Load More | True/False | Default: true |

---

## CPT ACF Fields (created in WP admin for the Project CPT)

| Field label | Field type | Notes |
|---|---|---|
| Client Name | Text | Optional, shown on single page |
| Project Year | Number | Optional, shown on single page |
| Short Description | Textarea | Used in grid card excerpt |

---

## AJAX Handler Sketch

- Action: `load_portfolio`
- POST params: `paged`, `category`, `nonce`, `per_page`
- Returns: JSON with HTML for new card items + `has_more` boolean

---

## Accessibility

- [ ] Filter buttons use `<button>` (not links); active filter has `aria-pressed="true"`
- [ ] Load More button announces result count to screen readers via `aria-live`
- [ ] Card links are descriptive (project title)

---

## Out of Scope

- Masonry layout
- Lightbox/modal project preview
- Client-side pagination (Load More replaces pagination for this block)
