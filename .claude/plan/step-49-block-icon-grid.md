# Step 49 — Icon Grid Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Step 01 (theme foundation), Step 36 (section_subheading pattern), Step 37 (icon system — ui_icon field naming, aidriven_get_icon_path() helper, acf/load_field filter)  
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block for "why choose us" / "key benefits" content: a manually authored grid of 3–8 items, each with an icon, title, and short description. The editor controls the number of columns via a select field (2, 3, or 4). No CPT backing, no per-item links. This block is intentionally distinct from the Services block (Step 17) — it communicates value differentiators rather than service offerings, and carries no CPT relationship or "view all" link.

---

## User Stories

- **As a site editor**, I want to add a "why choose us" section to any page so I can communicate the agency's key differentiators with icons and short copy without writing code.
- **As a site editor**, I want to choose the number of grid columns so the block adapts to the number of items I have and the visual weight I want on the page.
- **As a developer**, I want icon selection to come from the same dynamic ACF dropdown as all other icon fields so I do not need to maintain a separate icon list for this block.

---

## Business Value

A standalone benefits / differentiators grid is one of the most common sections on IT agency and freelancer sites. Providing it as a configurable block means editors can deploy it on any page — home, about, landing pages — without developer involvement. Reusing the icon system from Step 37 keeps the icon asset catalogue centralised and self-maintaining.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/icon-grid`.
- [ ] Block title is `Icon Grid`; category is `formatting` or the theme's custom block category.
- [ ] Block preview image exists at `assets/media/block-preview/icon-grid.jpg` (or `.png`).

### ACF Field Group

- [ ] ACF field group JSON file written directly to `acf-json/group_icon_grid_block.json`.
- [ ] All field keys follow the pattern `field_icon_grid_{field_name}`.
- [ ] Field group assigned to the `acf/icon-grid` block.

### Fields

- [ ] `section_heading` (Text, not required) — renders as `<h2>` when non-empty.
- [ ] `section_subheading` (Text, not required) — renders as `<p class="block-subheading">` when non-empty; follows the Step 36 pattern.
- [ ] `columns` (Select, required, default `3`) — choices: `2 => 2 Columns`, `3 => 3 Columns`, `4 => 4 Columns`.
- [ ] `features` (Repeater, required, min 3, max 8) — sub-fields:
  - `ui_icon` (Select, not required) — choices injected at runtime by the `acf/load_field` filter from Step 37; allow null.
  - `title` (Text, required).
  - `description` (Textarea, not required).

### Template Rendering

- [ ] Section heading renders as `<h2>` only when non-empty; escaped with `esc_html()`.
- [ ] Section subheading renders as `<p class="block-subheading">` only when non-empty; escaped with `esc_html()`.
- [ ] The grid wrapper receives a `data-columns` attribute (or a modifier class) reflecting the `columns` field value so CSS can apply the correct column count.
- [ ] Each feature item renders: icon (when set) → title → description.
- [ ] Icon is inlined via `aidriven_get_icon_path()` (Step 37 helper); icon container has `aria-hidden="true"`.
- [ ] When `ui_icon` is empty the icon slot is omitted entirely — no empty markup left in the DOM.
- [ ] `title` output escaped with `esc_html()`.
- [ ] `description` output escaped with `esc_textarea()` or `esc_html()`.
- [ ] The repeater loop uses `have_rows()` / `the_row()` / `get_sub_field()`.

### Styles

- [ ] CSS grid layout; column count driven by the `columns` value via a CSS custom property or `data-columns` selector.
- [ ] Icon rendered at a consistent size (e.g. 48 × 48 px) above the title using the token-defined icon colour.
- [ ] Responsive: 2-column grid collapses to 1 column on small screens; 3- and 4-column grids collapse to 2 columns on tablet and 1 on mobile.
- [ ] No inline styles — all sizing and colour from design tokens in `src/css/variables/`.
- [ ] Only token-defined colors used — no Tailwind built-in palette references, no hardcoded hex or OKLCH values.

### Quality

- [ ] No JavaScript required or added.
- [ ] All output escaped — no exceptions.
- [ ] `make check` passes with zero new PHPCS errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/icon-grid.php` | Block template |
| `src/css/blocks/icon-grid.css` | Block styles — grid layout and item styling |
| `acf-json/group_icon_grid_block.json` | ACF field group definition |
| `assets/media/block-preview/icon-grid.jpg` | Editor preview image |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/icon-grid` block |
| `src/css/main.css` | Import `blocks/icon-grid.css` |

---

## ACF Field Group

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Not required; renders as `<h2>` |
| Section Subheading | `section_subheading` | Text | Not required; renders as `<p class="block-subheading">` |
| Columns | `columns` | Select | Required; choices: `2`, `3` (default), `4` |
| Features | `features` | Repeater | Required; min 3, max 8 |
| — Icon | `ui_icon` | Select | Not required; allow null; choices populated by `acf/load_field` filter (Step 37) |
| — Title | `title` | Text | Required |
| — Description | `description` | Textarea | Not required |

ACF field key convention: `field_icon_grid_section_heading`, `field_icon_grid_section_subheading`, `field_icon_grid_columns`, `field_icon_grid_features`, `field_icon_grid_ui_icon`, `field_icon_grid_title`, `field_icon_grid_description`.

The `ui_icon` sub-field name ends in `_icon`, so the `acf/load_field` filter registered in Step 37 automatically strips the `_icon` suffix, resolves the category as `ui`, and populates choices from `assets/media/icons/ui/`. No manual choice list is needed in the JSON.

---

## Notes

- The `columns` select stores the raw integer string (`'2'`, `'3'`, `'4'`). The block template passes this value to the grid wrapper via `data-columns="<?php echo esc_attr( $columns ); ?>"`. The CSS targets `[data-columns="2"]`, `[data-columns="3"]`, `[data-columns="4"]` to set `grid-template-columns` accordingly.
- The `features` repeater enforces `min_rows: 3` and `max_rows: 8` in the ACF JSON so editors cannot accidentally create a one-item grid or an oversized one.
- Icon is rendered by reading the stored value from `get_sub_field( 'ui_icon' )`, passing it to `aidriven_get_icon_path()`, and using `file_get_contents()` to inline the SVG — the same pattern used by Stats (Step 13), Process (Step 14), and Services (Step 17) blocks after Step 37.
- This block is purposefully free of CPT relationships and per-item links. If a linked variant is needed in future, that is a separate block.
- The Services block (Step 17) also renders a card grid with icons, but its items come from a CPT or a manual repeater that includes a link and is designed to showcase service offerings. The Icon Grid block is a lighter, purely presentational "benefits" block with a different editorial intent.
- After generating `acf-json/group_icon_grid_block.json`, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before building the template.

---

## Out of Scope

- Per-item links or CTAs (use the Services block or a custom block variant for linked cards)
- CPT-backed or dynamic item source
- Icon picker preview in the Gutenberg block sidebar
- Animated entrance effects or hover animations requiring JS
- A "Learn more" or "View all" link below the grid
- More than 4 columns (layout becomes unusable at smaller viewport widths)
- Icon colour or size overrides per item (all items use the same token-defined values)
