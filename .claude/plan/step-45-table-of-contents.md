# Step 45 — Table of Contents

**Phase:** 11 — Blog Enhancements  
**Depends on:** Steps 28, 39  
**Required by:** nothing (end feature)

---

## Summary

Auto-generate a Table of Contents (TOC) from `<h2>` and `<h3>` headings in single post content. A `the_content` filter in `inc/functions-helpers.php` injects `id` attributes (derived via `sanitize_title()`) into every `<h2>` and `<h3>` in the rendered output, with duplicate-heading de-duplication. A data function `ai_driven_get_toc()` parses the raw post content and returns a structured array of headings. In `single.php`, when the TOC array contains three or more entries (or the per-post ACF field `show_toc` is explicitly enabled), a `<nav class="toc">` block is rendered above `the_content()` with a nested ordered list reflecting the h2/h3 hierarchy. TOC anchor links are standard `href="#slug"` links — the offset-aware smooth scroll from Step 39 handles the sticky header correction automatically.

---

## User Stories

- **As a visitor reading a long blog post**, I want a Table of Contents at the top of the article so I can see at a glance what the post covers and jump directly to the section I care about.
- **As a visitor who clicks a TOC link**, I want the page to scroll smoothly and land with the heading visible below the sticky header so nothing is obscured.
- **As a content editor**, I want the TOC to appear automatically when a post has enough headings so I do not need to maintain it manually.
- **As a content editor writing a short post**, I want to be able to force the TOC on or suppress it via a post-level toggle so I am not locked into the automatic threshold.
- **As a site owner**, I want duplicate headings to be handled gracefully so anchor links always target the correct section.

---

## Business Value

A TOC improves readability and time-on-page for long-form content — a key asset type for IT service companies using the blog for thought leadership. It signals content quality to both readers and search engines and provides structured in-page navigation that reduces bounce on dense technical articles.

---

## Acceptance Criteria

### Content Filter — ID Injection

- [ ] A `the_content` filter named `aidriven_inject_heading_ids` is registered in `inc/functions-helpers.php`.
- [ ] The filter uses `preg_replace_callback` to find all `<h2>` and `<h3>` opening tags in the rendered HTML.
- [ ] For each heading, the slug is generated with `sanitize_title( wp_strip_all_tags( $heading_text ) )`.
- [ ] Duplicate slugs are de-duplicated by appending `-2`, `-3`, etc. (a counter array is maintained across the `preg_replace_callback` run).
- [ ] If a heading tag already has an `id` attribute, it is left unchanged (no double-injection on re-runs or cached content).
- [ ] The filter only modifies `<h2>` and `<h3>` tags — `<h1>`, `<h4>`, `<h5>`, `<h6>` are untouched.
- [ ] The filter returns the full modified content string.
- [ ] The filter priority is `10`, accepted arguments `1`.

### Data Function — `ai_driven_get_toc()`

- [ ] `ai_driven_get_toc( int $post_id ): array` defined in `inc/functions-data.php`.
- [ ] Uses `get_post_field( 'post_content', $post_id )` to retrieve raw content (before filter processing).
- [ ] Applies `do_shortcode()` to handle any shortcodes that emit headings.
- [ ] Uses `preg_match_all` to extract `<h2>` and `<h3>` tags and their inner text.
- [ ] Returns an array of associative arrays: `[ 'id' => string, 'level' => int, 'text' => string ]`.
- [ ] `id` values are generated with the same `sanitize_title()` + de-duplication logic as the content filter, so IDs match.
- [ ] `text` is the heading inner text with HTML tags stripped (`wp_strip_all_tags()`).
- [ ] `level` is `2` or `3` (integer).
- [ ] Returns an empty array when the post has no `<h2>` or `<h3>` headings.
- [ ] Returns an empty array when `$post_id` is invalid or the post does not exist.

### TOC Rendering — `single.php`

- [ ] `ai_driven_get_toc( get_the_ID() )` is called before `the_content()`.
- [ ] The `show_toc` ACF field value is retrieved with `get_field( 'show_toc' )` (no second argument — reads from the current post).
- [ ] TOC is rendered when either condition is true:
  - `count( $toc_items ) >= 3` (automatic threshold), OR
  - `true === $show_toc` (editor explicitly enabled it).
- [ ] TOC is suppressed when `false === $show_toc` (editor explicitly disabled it), regardless of heading count.
- [ ] When `show_toc` is not set (field absent or null), only the automatic `count >= 3` threshold applies.
- [ ] The TOC block is rendered immediately before `the_content()`, inside the post content column.

### TOC Markup

- [ ] Outer element: `<nav class="toc" aria-label="<?php esc_attr_e( 'Table of contents', 'aidriven' ); ?>">`.
- [ ] A heading inside the nav: `<p class="toc__title"><?php esc_html_e( 'Contents', 'aidriven' ); ?></p>`.
- [ ] A single `<ol class="toc__list">` contains all items.
- [ ] Each `<h2>` heading renders as `<li class="toc__item toc__item--h2"><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>`.
- [ ] Each `<h3>` heading renders as a nested `<ol class="toc__sublist">` inside the preceding `<li>`, containing `<li class="toc__item toc__item--h3">`.
- [ ] An `<h3>` that appears before any `<h2>` is rendered at the top level of the outer `<ol>` without nesting.
- [ ] All output is escaped (`esc_attr`, `esc_html`, `esc_url`).

### ACF Field — `show_toc`

- [ ] `show_toc` true/false field exists in `acf-json/group_post_fields.json` (create the file if it does not exist; append to it if it does).
- [ ] Field key: `field_post_show_toc`.
- [ ] Field label: "Show Table of Contents".
- [ ] Default value: `''` (empty — neither true nor false; lets the automatic threshold decide).
- [ ] Instructions: "Leave blank to show the TOC automatically when the post has 3 or more headings. Enable to force it on; disable to hide it."
- [ ] Field type: `true_false`.
- [ ] `ui`: `1` (toggle UI).
- [ ] Location rule: post type `post`.

### Styles

- [ ] `src/css/pages/toc.css` created with styles scoped to `.toc`.
- [ ] The TOC box has a distinct background using a token-defined color, a border, and padding — visually separated from body text.
- [ ] `.toc__title` is styled as a small label (uppercase, letter-spacing, muted color from token).
- [ ] `.toc__list` and `.toc__sublist` reset default `<ol>` list-style; custom left padding distinguishes nesting level.
- [ ] `.toc__item a` uses the primary link color token; underline on hover.
- [ ] Active state is not implemented (no JS scroll-spy — out of scope).
- [ ] `src/css/pages/toc.css` imported in `src/css/main.css`.
- [ ] Uses only token-defined colors from `src/css/variables/colors.css`.

### Quality

- [ ] `make check` passes (PHPCS clean).
- [ ] No JS errors — the TOC relies entirely on CSS and the existing smooth-scroll script from Step 39; no new JS is added.
- [ ] TOC does not render on archive pages, home page, or pages — only inside `single.php`.
- [ ] Posts with zero or one or two headings and no explicit `show_toc` field render no TOC and no empty `<nav>` element.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `src/css/pages/toc.css` | Styles for the `.toc` navigation block |
| `acf-json/group_post_fields.json` | ACF field group for post-level fields, containing `show_toc` (create if absent) |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-helpers.php` | Add `aidriven_inject_heading_ids` filter (id injection into rendered content) |
| `inc/functions-data.php` | Add `ai_driven_get_toc( int $post_id ): array` |
| `single.php` | Call `ai_driven_get_toc()`, evaluate `show_toc` field, render TOC nav before `the_content()` |
| `src/css/main.css` | Add `@import "pages/toc.css";` in the pages imports section |

---

## Implementation Notes

### Shared slug-generation logic

Both the content filter and `ai_driven_get_toc()` must produce identical IDs so that TOC `href` values match the injected `id` attributes. Extract the de-duplication counter into a closure or use a consistent inline pattern in both places:

```php
$seen = array();
$slug = sanitize_title( wp_strip_all_tags( $heading_text ) );
if ( isset( $seen[ $slug ] ) ) {
    ++$seen[ $slug ];
    $slug .= '-' . $seen[ $slug ];
} else {
    $seen[ $slug ] = 1;
}
```

Both the filter and the data function must process headings in document order so the counters stay in sync.

### Content filter — skipping headings that already have an id

```php
// Do not overwrite existing id attributes.
if ( preg_match( '/\bid\s*=/i', $tag ) ) {
    return $full_match;
}
```

### Parsing heading text from raw content

The inner text of a heading in raw `post_content` may contain HTML (bold, links). Strip tags before slugifying; preserve the original markup for display when building the TOC array:

```php
'text' => wp_strip_all_tags( $inner_html ),
```

### Nesting h3 under h2 in the template

Track the last-seen level while iterating `$toc_items`. Open a `<ol class="toc__sublist">` inside the current `<li>` when level increases to 3, and close it when level returns to 2. Handle edge case: first item is h3 — render at top level without nesting.

### ACF field group JSON — group_post_fields.json

If the file does not yet exist, create it with a minimal valid ACF Local JSON structure:

```json
{
    "key": "group_post_fields",
    "title": "Post Fields",
    "fields": [ ... ],
    "location": [ [ { "param": "post_type", "operator": "==", "value": "post" } ] ],
    "menu_order": 0,
    "position": "side",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "active": true
}
```

If the file already exists with other post fields, append the `show_toc` field object to the `fields` array.

---

## Accessibility

- [ ] `<nav aria-label="Table of contents">` is a landmark — screen readers expose it in the landmarks list.
- [ ] The TOC heading (`<p class="toc__title">`) is decorative/visible text, not an ARIA label duplicate — the `aria-label` on `<nav>` is sufficient.
- [ ] All anchor links have meaningful text (the heading text itself) — no "click here" or icon-only links.
- [ ] Focus styles on TOC links must remain visible — do not suppress `outline`.
- [ ] Smooth scroll from Step 39 does not trap keyboard focus; the browser moves focus to the target heading after navigation.

---

## Out of Scope

- Scroll-spy (highlighting the active TOC item as the user scrolls)
- Sticky or floating TOC sidebar (inline box only)
- Support for `<h4>`–`<h6>` nesting
- TOC on page templates other than `single.php`
- Collapsible / expandable TOC toggle
- Custom TOC title text editable via ACF
- Excluding specific headings from the TOC via a CSS class or data attribute
