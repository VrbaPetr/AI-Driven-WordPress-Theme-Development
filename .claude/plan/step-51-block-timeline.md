# Step 51 — Timeline Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Step 01 (theme foundation), Step 36 (section_subheading pattern)  
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block that renders a vertical chronological timeline for company history or milestone pages. Each event carries a date label, title, description, and an optional image. On desktop the layout alternates items left and right of a central vertical line using pure CSS `:nth-child` selectors — no JavaScript is required for the layout. On mobile the timeline collapses to a single left-aligned column. An optional Intersection Observer scroll fade-in can be added by importing a lightweight script that follows the same pattern as `stats-counter.js` from Step 13. The block is semantically and visually distinct from the Process block (Step 14): Process models ordered workflow steps; Timeline models dated narrative history.

---

## User Stories

- **As a site editor**, I want to add a company history section to the About page so visitors can understand our background and milestones without me writing any code.
- **As a site editor**, I want to attach an image to individual timeline events so I can make key milestones more visually memorable.
- **As a visitor**, I want events to fade in as I scroll down so the timeline feels dynamic and easy to follow.
- **As a visitor on a small screen**, I want a clean single-column layout so I can read the timeline comfortably on mobile.
- **As a developer**, I want the alternating left/right layout controlled entirely by CSS so there is no JavaScript dependency for the core visual structure.

---

## Business Value

A chronological company history block is a standard fixture on IT agency "About" pages. Providing it as a reusable, CSS-driven block lets editors build credibility-focused pages without custom development. The alternating desktop layout and optional image slot give the block enough visual richness to stand on its own without requiring a designer for each deployment.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/timeline`.
- [ ] Block title is `Timeline`; category is the theme's custom block category or `formatting`.
- [ ] Block preview image exists at `assets/media/block-preview/timeline.jpg` (or `.png`).
- [ ] ACF field group JSON written directly to `acf-json/group_timeline_block.json`.

### ACF Field Group

- [ ] All field keys follow the pattern `field_timeline_{field_name}`.
- [ ] Field group is assigned to the `acf/timeline` block.

### Fields

- [ ] `section_heading` (Text, not required) — renders as `<h2>` when non-empty.
- [ ] `section_subheading` (Text, not required) — renders as `<p class="block-subheading">` when non-empty; follows the Step 36 pattern.
- [ ] `events` (Repeater, required, min 1) — sub-fields:
  - `year` (Text, required) — free-form date label, e.g. `"2019"` or `"March 2021"`.
  - `title` (Text, required).
  - `description` (Textarea, not required).
  - `image` (Image, not required) — return format: array.

### Template Rendering

- [ ] Section heading renders as `<h2>` only when non-empty; escaped with `esc_html()`.
- [ ] Section subheading renders as `<p class="block-subheading">` only when non-empty; escaped with `esc_html()`.
- [ ] The events repeater uses `have_rows()` / `the_row()` / `get_sub_field()`.
- [ ] Each event item renders in order: date label → title → description → image (when set).
- [ ] Date label (`year`) rendered in a `<time>` element; escaped with `esc_html()`.
- [ ] `title` escaped with `esc_html()`.
- [ ] `description` rendered with `nl2br( esc_html() )` to preserve line breaks.
- [ ] Image rendered via `wp_get_attachment_image()` using an appropriate registered image size; image is conditionally omitted when the field is empty.
- [ ] Each event wrapper carries a `data-timeline-item` attribute so the optional Intersection Observer can target it without coupling to a presentational class.
- [ ] The central vertical line is a CSS pseudo-element on the timeline container — no extra DOM node needed.

### Layout & Styles

- [ ] Central vertical line rendered as a `::before` or `::after` pseudo-element on the timeline wrapper — no extra HTML element.
- [ ] On desktop (`min-width` breakpoint matching the theme's `md` or `lg` token): odd-numbered items align to the left half; even-numbered items align to the right half; each item's content box is offset so it clears the central line.
- [ ] Alternating positioning achieved with `:nth-child(odd)` and `:nth-child(even)` selectors — no JavaScript class toggling.
- [ ] A small circle or dot marker sits on the central line at the level of each event; implemented as a CSS pseudo-element on the event item — no extra HTML element.
- [ ] On mobile (below the desktop breakpoint): single-column linear layout, all items left-aligned; central line remains as a left-side rail.
- [ ] Image rendered within the event content box; constrained to a maximum width so it does not overwhelm the text.
- [ ] `prefers-reduced-motion: reduce` disables the fade-in transition so items are immediately visible; this is required even when the optional JS is not added.
- [ ] No inline styles — all sizing, spacing, and colour from design tokens in `src/css/variables/`.
- [ ] Only token-defined colors used — no Tailwind built-in palette references, no hardcoded hex or OKLCH values.

### Optional Scroll Fade-in

- [ ] When the optional JS is added, each `[data-timeline-item]` element starts with an `is-hidden` class applied via JS on `DOMContentLoaded` — not hardcoded in PHP — so the block degrades gracefully without JS.
- [ ] An `IntersectionObserver` watches each `[data-timeline-item]`; on intersection it removes the `is-hidden` class (or adds an `is-visible` class), triggering a CSS opacity + translate transition.
- [ ] The observer calls `unobserve()` after triggering so each item animates only once.
- [ ] If `prefers-reduced-motion` is detected at script initialisation time, the script skips observer setup entirely and leaves all items visible.
- [ ] The script is imported in `src/js/main.js` only if it is added; it is not a hard dependency of this step.

### Code Quality

- [ ] No JavaScript required for the core layout — the alternating column structure works without JS.
- [ ] All PHP output is escaped — no exceptions.
- [ ] `make check` passes with zero new PHPCS errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/timeline.php` | Block template — heading, subheading, events repeater loop |
| `src/css/blocks/timeline.css` | Block styles — central line, alternating layout, dot markers, mobile collapse, optional fade-in states |
| `acf-json/group_timeline_block.json` | ACF field group definition |
| `assets/media/block-preview/timeline.jpg` | Editor preview image |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/timeline` block |
| `src/css/main.css` | Add `@import "blocks/timeline.css"` |
| `src/js/main.js` | Add `import './scripts/timeline-fade.js'` if the optional fade-in script is implemented |

> The JS file `src/js/scripts/timeline-fade.js` is optional. If implemented it follows the same Intersection Observer pattern as `src/js/scripts/stats-counter.js` (Step 13). It is listed here for reference but its creation is not a blocking acceptance criterion.

---

## ACF Field Group

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Not required; renders as `<h2>` |
| Section Subheading | `section_subheading` | Text | Not required; renders as `<p class="block-subheading">` |
| Events | `events` | Repeater | Required; min 1 row |
| — Year / Date | `year` | Text | Required; free-form, e.g. `"2019"` or `"March 2021"` |
| — Title | `title` | Text | Required |
| — Description | `description` | Textarea | Not required |
| — Image | `image` | Image | Not required; return format: array |

ACF field key convention: `field_timeline_section_heading`, `field_timeline_section_subheading`, `field_timeline_events`, `field_timeline_year`, `field_timeline_title`, `field_timeline_description`, `field_timeline_image`.

---

## Notes

- The `year` field is typed as Text (not Number) to accommodate free-form date labels such as `"Q3 2022"`, `"Spring 2019"`, or `"March 2021"`. Store it in a `<time>` element; if the value is a plain four-digit year it can also carry a `datetime` attribute set to the same value.
- The alternating layout uses CSS only: `.timeline__item:nth-child(odd)` floats or grid-places to the left half; `.timeline__item:nth-child(even)` to the right. A CSS Grid approach with named areas or `justify-self` is preferred over floats as it avoids clearfix markup.
- The central line and dot markers are implemented as CSS pseudo-elements (`::before` / `::after`) on `.timeline` and `.timeline__item` respectively. This avoids adding non-semantic HTML for decorative chrome.
- On mobile the layout becomes a single left-aligned column. The central line shifts to a left-side vertical rail, and all items align to the right of it — this avoids the complexity of a true alternating layout on narrow viewports.
- The fade-in pattern mirrors `stats-counter.js` (Step 13): an `IntersectionObserver` targets `[data-timeline-item]` elements, adds or removes a CSS class, and then calls `unobserve()`. The `is-hidden` class is only added by JS after page load, so the block is fully readable without JS.
- This block is semantically distinct from the Process block (Step 14): Process models numbered workflow steps with no dates, optimised for "how we work" content. Timeline models dated narrative history, optimised for "about us / milestones" content. They should not be merged or consolidated.
- After generating `acf-json/group_timeline_block.json`, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before building the template.
- The block preview image can be a placeholder JPG at the correct path; a real screenshot is produced during QA.

---

## Out of Scope

- Horizontal timeline layout (the vertical layout is standard and mobile-friendly; horizontal requires JS for scroll management)
- Filtering or sorting events by date in the browser (content is ordered by the editor in the repeater)
- Linking individual events to a post or page
- Animated connector lines between events
- Category or tag grouping of events
- A "load more" control for long timelines (all events render on page load)
- Auto-populating events from a CPT (all content is manually authored in the repeater)
