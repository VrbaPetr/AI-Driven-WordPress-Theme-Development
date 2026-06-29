# Step 31 — Search Results Page

**Phase:** 7 — Forms & Site Utilities  
**Depends on:** Steps 01, 05, 06, 08, 10, 24  
**Required by:** nothing (end feature)

---

## Summary

Create a custom styled search results page template that displays matched posts as cards, shows the search query and result count, handles the "no results" state gracefully, and includes a search form in the page header for refined searches.

---

## User Stories

- **As a visitor who used the site search**, I want to see well-formatted results with titles, excerpts, and thumbnails so I can quickly identify the relevant item.
- **As a visitor who gets no results**, I want helpful suggestions (try different keywords, browse categories) so I'm not stuck at a dead end.

---

## Business Value

WordPress's default search results page is unstyled and off-brand. A properly styled search experience reduces bounces from the search interaction and keeps visitors in the site's design system.

---

## Acceptance Criteria

- [ ] `search.php` template created.
- [ ] Page header: `<h1>` showing search query ("Results for: {query}") and total result count.
- [ ] Search form displayed in the page header for refinement.
- [ ] Results displayed as cards (Card component, Step 08): thumbnail, post type label, title, excerpt, date.
- [ ] Post type label badge (e.g. "Service", "Project", "Post") from the Badge component (Step 09).
- [ ] Pagination via Pagination component (Step 10).
- [ ] **No Results state**: friendly message, suggestions to browse by category or return to home, a second search form.
- [ ] Search query is escaped with `get_search_query()` (WP function, auto-escapes).
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `search.php` | WordPress search template |
| `template-parts/pages/search-results.php` | Results list and no-results state |

---

## No Results Template

```
┌──────────────────────────────┐
│  No results for "foo bar"    │
│                              │
│  Try:                        │
│  • Check your spelling       │
│  • Use broader terms         │
│  • [Browse all services →]   │
│  • [View all projects →]     │
│                              │
│  [Search form]               │
└──────────────────────────────┘
```

---

## Post Type Label Mapping

| Post type | Badge label |
|---|---|
| `post` | Article |
| `service` | Service |
| `project` | Project |
| `team-member` | Team |
| `page` | Page |

---

## Accessibility

- [ ] Page `<h1>` includes search term for context
- [ ] Result count announced via `aria-live` if results load dynamically (not applicable here — server rendered)
- [ ] Search form: `<label>` associated with input, submit button has meaningful text

---

## Out of Scope

- Live/AJAX search (not selected in survey — separate feature if needed later)
- Search filters by post type or date
- Highlighted keyword matches in excerpts
