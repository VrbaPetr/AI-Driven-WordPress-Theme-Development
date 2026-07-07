# Step 44 — Reading Time & Progress Bar

**Phase:** 11 — Blog Enhancements  
**Depends on:** Step 28 (blog single template)  
**Required by:** nothing (end feature)

---

## Summary

Two complementary features for the single post template. First, a `ai_driven_reading_time()` helper function that estimates how many minutes a post takes to read and outputs the result in the post meta section alongside author and date. Second, a thin reading progress bar fixed to the top of the viewport that fills from left to right as the visitor scrolls through the article, with a smooth CSS transition and full `prefers-reduced-motion` support.

---

## User Stories

- **As a visitor browsing the blog**, I want to see an estimated reading time next to the post date so I can decide before I start whether I have time to read the full article.
- **As a visitor reading a long post**, I want a visual indicator of how far through the article I am so I can gauge how much is left without scrolling to the bottom.
- **As a visitor who prefers reduced motion**, I want the progress bar to update instantly without animation so my accessibility preference is respected.

---

## Business Value

Reading time signals set expectations and reduce bounce — visitors who know an article is short are more likely to commit. The progress bar reinforces engagement on long-form content by making forward progress visible and satisfying. Both features are low-cost, widely expected on editorial sites, and require no third-party dependencies.

---

## Acceptance Criteria

### Reading Time Helper

- [ ] `ai_driven_reading_time( int $post_id ): string` is defined in `inc/functions-helpers.php`.
- [ ] Formula: `ceil( str_word_count( strip_tags( get_the_content( null, false, $post_id ) ) ) / 200 )`.
- [ ] Result is clamped to a minimum of 1 minute.
- [ ] Return value is a translated string in the form `"X min read"` using `sprintf()` and `_n()` so singular and plural are handled correctly, with the `ai-driven` text domain.
- [ ] All output from the function is escaped at the call site in the template.
- [ ] `make check` passes with the new function.

### Reading Time Display

- [ ] Reading time is output in the post meta section of `single.php`, on the same line or row as author and date.
- [ ] It is wrapped in a `<span>` with a descriptive class (e.g. `post-meta__reading-time`) so it can be targeted with CSS.
- [ ] Output is escaped with `esc_html()`.
- [ ] The element is only rendered on single posts (already guaranteed by `single.php` context).

### Reading Progress Bar Element

- [ ] A `<div class="reading-progress-bar" role="progressbar" aria-hidden="true">` is added to `single.php`, placed before the opening `<article>` or directly inside `<body>` so it can be positioned fixed without stacking-context conflicts.
- [ ] `aria-hidden="true"` prevents screen readers from announcing a raw percentage number.

### Reading Progress Bar Styles

- [ ] Selector `.reading-progress-bar` in `src/css/pages/blog.css`.
- [ ] `position: fixed; top: 0; left: 0; z-index: 100` — rendered above the sticky header.
- [ ] `height: 3px; width: 0%; max-width: 100%`.
- [ ] Background uses a color token from `src/css/variables/colors.css` — no hardcoded hex or Tailwind built-in palette class.
- [ ] `transition: width 0.1s linear` for a smooth fill effect.
- [ ] Inside a `@media (prefers-reduced-motion: reduce)` block, `transition: none` is set so the bar jumps instantly for users who have opted out of motion.

### Reading Progress Bar Script

- [ ] File `src/js/scripts/reading-progress.js` exports a single `initReadingProgress()` function (no default export needed — named export is fine).
- [ ] The function exits immediately if `document.body` does not have the class `single-post` (WordPress body class for single posts).
- [ ] The function exits immediately if no `.reading-progress-bar` element is found in the DOM.
- [ ] A `scroll` event listener on `window` calculates: `(window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100`.
- [ ] The calculated percentage is clamped to `[0, 100]` to guard against sub-pixel division edge cases.
- [ ] The bar's `style.width` is set to the clamped percentage followed by `%`.
- [ ] The listener is registered with `{ passive: true }` for scroll performance.
- [ ] `initReadingProgress()` is called once on `DOMContentLoaded` inside `src/js/main.js`.

### Integration

- [ ] `src/js/main.js` imports and calls `initReadingProgress()`.
- [ ] `make check` passes after all changes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `src/js/scripts/reading-progress.js` | Scroll listener that drives the progress bar width |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-helpers.php` | Add `ai_driven_reading_time( $post_id )` helper function |
| `single.php` | Add `<div class="reading-progress-bar">` element; output reading time in post meta section |
| `src/css/pages/blog.css` | Add `.reading-progress-bar` styles including reduced-motion override |
| `src/js/main.js` | Import and call `initReadingProgress()` |

---

## Notes

- Average reading speed of 200 words per minute is a commonly cited estimate for web content; it is intentionally conservative to avoid under-promising.
- `get_the_content( null, false, $post_id )` is used instead of `get_post_field( 'post_content', $post_id )` so ACF block content rendered via `the_content` filter is included in the word count where relevant.
- The progress bar's `z-index: 100` must exceed the header's z-index. Verify against the value used in the header styles when implementing.
- The `{ passive: true }` scroll listener option is important — omitting it causes Chrome to warn about blocking scroll performance.
- The reduced-motion media query targets the CSS `transition` only; the bar still updates its width on scroll — it simply does so without animation.
- No `requestAnimationFrame` throttling is required at this scope; the `passive` flag is sufficient. Add `requestAnimationFrame` only if scroll jank is observed during manual QA.

---

## Out of Scope

- Reading time based on media or video content duration
- Displaying reading time in blog archive / post card components
- Saving or displaying reading progress across sessions (localStorage persistence)
- A percentage label or tooltip displayed alongside the bar
- Per-category or per-author average reading time analytics
