# Step 47 — Text Content Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Steps 01, 02 (design tokens)  
**Required by:** nothing (end feature)

---

## Summary

A WYSIWYG Gutenberg block that replaces unstyled WordPress core paragraph and heading blocks for long-form content. An editor authors rich text in a single ACF `wysiwyg` field; the front end wraps the output in a `prose` container whose CSS class applies consistent, token-driven typography to every descendant element — headings, paragraphs, lists, blockquotes, tables, code, links, and inline emphasis. Width and text-alignment are configurable via block fields. No JavaScript is required.

---

## User Stories

- **As a content editor**, I want a single WYSIWYG block that accepts rich text so I can write long-form copy — including headings, lists, and blockquotes — without assembling multiple separate blocks.
- **As a content editor**, I want to choose between narrow, default, and wide layouts so I can control reading line length to suit the content type.
- **As a developer**, I want a dedicated `prose` CSS class that controls the typography of all rich-text output in one place so that any future style change propagates consistently across the site.
- **As a site owner**, I want rich text to inherit the site's design tokens for colour, spacing, and font so the WYSIWYG output always matches the surrounding design without manual overrides.

---

## Business Value

WordPress's native text blocks are unstyled by default and require per-block formatting effort. A single prose block with centralised CSS ensures typographic consistency across all long-form content, reduces editor friction, and makes global typography changes a one-file edit rather than a theme.json archaeology exercise.

---

## Acceptance Criteria

### Block registration

- [ ] Block registered in `inc/register-blocks.php` with name `text-content` and title `Text Content`.
- [ ] ACF field group JSON file present in `acf-json/` and valid.
- [ ] Block template file present at `template-parts/blocks/text-content.php`.
- [ ] Block preview image placeholder registered (or `mode` set to `preview` if no image is supplied).

### ACF fields

- [ ] `content` field: `wysiwyg`, full toolbar, media uploads enabled.
- [ ] `width` field: `select` with choices `narrow`, `default`, `wide`; default value `default`.
- [ ] `text_align` field: `select` with choices `left`, `center`; default value `left`.
- [ ] All three fields belong to a single field group scoped to the `text-content` block.

### Rendering

- [ ] `content` field value rendered with `wp_kses_post()` — never `esc_html()` or `echo` without sanitisation.
- [ ] Output wrapped in an element carrying the `prose` CSS class.
- [ ] `width` value maps to a `max-width` constraint: `narrow` ≈ 65 ch, `default` ≈ 80 ch, `wide` = full column width.
- [ ] `text_align` value maps to `text-left` or `text-center` utility on the wrapper.
- [ ] When `content` is empty the block renders nothing (no empty `<div>`).
- [ ] Block renders correctly in the Gutenberg editor preview.

### Prose styles

- [ ] `src/css/components/prose.css` created and imported in `main.css`.
- [ ] Prose styles cover: `h2`, `h3`, `h4`, `p`, `ul`, `ol`, `li`, `blockquote`, `table`, `thead`, `tbody`, `tr`, `th`, `td`, `code`, `pre`, `a`, `strong`, `em`.
- [ ] Every colour value references a design token from `src/css/variables/colors.css` — no Tailwind built-in palette names, no hardcoded hex or OKLCH values.
- [ ] Every spacing value references a design token or a Tailwind spacing utility — no magic numbers.
- [ ] Link styles include a hover state and an underline.
- [ ] `blockquote` has a left border and distinct text colour.
- [ ] `code` and `pre` have a background tint and monospace font.
- [ ] `table` has bordered cells and alternating row backgrounds using token colours.
- [ ] Heading hierarchy (`h2` > `h3` > `h4`) is visually distinct in size and weight.

### Quality

- [ ] `make check` passes with no new PHPCS violations.
- [ ] All PHP output is escaped.
- [ ] No JavaScript files added or modified.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_text_content.json` | ACF field group — `content` (wysiwyg), `width` (select), `text_align` (select) |
| `template-parts/blocks/text-content.php` | Block template — reads fields, applies width map, renders `prose`-wrapped output |
| `src/css/components/prose.css` | Typography styles scoped to `.prose` — all descendant element rules |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register the `text-content` ACF block |
| `src/css/main.css` | Import `src/css/components/prose.css` |

---

## ACF Field Group

| Field label | Field name | Type | Options | Default |
|---|---|---|---|---|
| Content | `content` | wysiwyg | Toolbar: full; Media upload: yes | *(empty)* |
| Width | `width` | select | `narrow` → Narrow, `default` → Default, `wide` → Wide | `default` |
| Text Alignment | `text_align` | select | `left` → Left, `center` → Center | `left` |

Field group key: `group_text_content`  
Location rule: Block → is equal to → `acf/text-content`

---

## Implementation Notes

### Width map (`template-parts/blocks/text-content.php`)

```php
$width_map = array(
    'narrow'  => 'max-w-prose',   // ~65ch
    'default' => 'max-w-3xl',     // ~80ch — adjust to token if defined
    'wide'    => 'max-w-none',
);
$width_class = $width_map[ $width ] ?? $width_map['default'];
```

Prefer token-mapped classes if the project defines `--width-prose` or similar tokens; otherwise use Tailwind's built-in `max-w-*` scale which is safe to reference for layout (not colour) utilities.

### Rendering guard (`template-parts/blocks/text-content.php`)

```php
$content = get_field( 'content' );

if ( empty( $content ) ) {
    return;
}
```

Always bail early on empty content to avoid outputting an unsemantic empty wrapper.

### Output sanitisation

```php
echo wp_kses_post( $content );
```

`wp_kses_post()` strips disallowed tags while preserving all formatting markup produced by the WYSIWYG editor. Using `esc_html()` here would strip tags and break the output — this is the one context where `esc_html()` must not be used.

### Prose CSS structure (`src/css/components/prose.css`)

Scope all rules under a `.prose` selector so styles do not leak outside the block:

```css
.prose h2 { … }
.prose h3 { … }
.prose h4 { … }
.prose p  { … }
.prose ul, .prose ol { … }
.prose li { … }
.prose blockquote { … }
.prose a { … }
.prose a:hover { … }
.prose strong { … }
.prose em { … }
.prose code { … }
.prose pre { … }
.prose table { … }
.prose th, .prose td { … }
.prose tr:nth-child(even) { … }
```

All values must reference CSS custom properties defined in `src/css/variables/` — for example `var(--color-text)`, `var(--color-primary)`, `var(--color-surface-alt)`, `var(--font-size-lg)`. If a required token is missing, request it from the user rather than using a hardcoded value.

### `main.css` import order

Add the import after existing component imports, keeping alphabetical order within the `components` group:

```css
@import "./components/prose.css";
```

---

## Notes

- The `prose` class is intentionally generic and reusable — future blocks (e.g. a blog post body block) can apply the same class to get consistent typography at no extra cost.
- There is no `section_heading` field on this block by design. If a heading above the text is needed, editors compose with a separate heading block.
- This block intentionally has no JavaScript dependency — all behaviour is CSS-only.
- Do not apply prose styles globally on `body` or `main` — scope them exclusively to `.prose` so they never affect navigation, cards, or other non-prose UI elements.
- The `width` field controls the block's own reading-width constraint, not the outer column grid. Outer column layout is the responsibility of the section or layout block that wraps it.

---

## Out of Scope

- Drop-cap or pull-quote typographic variants
- Dark-mode prose colour overrides (can be added as a follow-on step)
- Per-heading anchor link generation
- Reading-time estimate or word count display
- Any custom toolbar buttons or TinyMCE extensions
- Replacing the block editor's native paragraph or heading blocks at the registration level
