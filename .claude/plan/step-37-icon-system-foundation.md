# Step 37 — Icon System Foundation

**Phase:** 9 — Icon System  
**Depends on:** Steps 01, 03  
**Required by:** Step 38 (icon library), Step 49 (feature grid block)

---

## Summary

Restructure `assets/media/icons/` into three category subdirectories (`social/`, `tech/`, `ui/`), move the 10 existing icons into the correct subdirectory, and introduce the PHP infrastructure that keeps ACF icon dropdowns automatically in sync with the filesystem. A new `aidriven_get_icon_choices()` function scans the subdirectories at runtime; an `acf/load_field` filter populates any ACF field named `icon` or ending in `_icon` with the matching choice list. Existing ACF icon fields in four field groups are renamed to `ui_icon` and their corresponding block template references are updated to match.

---

## User Stories

- **As a developer**, I want icon files organised by category so I can find and add icons quickly without scanning a flat directory of growing length.
- **As a developer**, I want ACF icon dropdowns to update automatically when I drop a new SVG into a subdirectory so I never have to manually sync field definitions with the filesystem.
- **As a content editor**, I want icon pickers in the block editor to show meaningful labels grouped by category so I can choose the right icon without guessing filenames.

---

## Business Value

A flat icon directory does not scale past a handful of icons. Introducing subdirectories and dynamic ACF population means new icons added in Step 38 (and any future additions) are immediately available in every icon field across the site without any PHP or JSON changes. The rename from `icon` / `service_icon` to `ui_icon` makes field intent explicit and aligns all icon fields under a single naming convention from the start.

---

## Acceptance Criteria

### Directory structure

- [ ] `assets/media/icons/social/` directory exists.
- [ ] `assets/media/icons/tech/` directory exists.
- [ ] `assets/media/icons/ui/` directory exists.
- [ ] `assets/media/icons/` root contains no loose `.svg` files after migration.

### Icon migration

- [ ] `social/` contains: `dribbble.svg`, `email.svg`, `github.svg`, `instagram.svg`, `linkedin.svg`, `twitter.svg` (6 files).
- [ ] `ui/` contains: `circle-check.svg`, `circle-info.svg`, `circle-x.svg`, `triangle-alert.svg` (4 files).
- [ ] No icon file is duplicated or lost — total remains 10.

### `aidriven_get_icon_choices()` function

- [ ] Function is added to `inc/functions-design.php`.
- [ ] Accepts an optional `$category` parameter (string, default `null`).
- [ ] When `$category` is `null`, scans all three subdirectories and returns a merged flat array of `'category/filename' => 'Category: Filename'` choices, sorted alphabetically by label.
- [ ] When `$category` is a valid subdirectory name (`social`, `tech`, `ui`), returns only icons from that subdirectory using the same `'filename' => 'Filename'` format (without the category prefix in the key).
- [ ] Returns an empty array when the directory does not exist or contains no `.svg` files.
- [ ] Labels are derived from the filename: extension stripped, hyphens replaced with spaces, each word title-cased.

### `acf/load_field` filter

- [ ] Filter is registered in `inc/functions-design.php`.
- [ ] Fields whose `name` is exactly `icon` receive choices from `aidriven_get_icon_choices()` (all categories merged, key format `category/filename`).
- [ ] Fields whose `name` ends in `_icon` (e.g. `ui_icon`, `social_icon`, `tech_icon`) have the suffix `_icon` stripped and the remainder used as the category slug passed to `aidriven_get_icon_choices()`.
- [ ] If the derived category slug does not match a known subdirectory the filter falls back to all categories merged.
- [ ] The filter only modifies fields of `type` = `select` — text fields named `icon` are left unchanged.
- [ ] Existing saved values (stored as bare filenames, e.g. `circle-check`) continue to be readable after migration; stored values for migrated fields must include the category path, e.g. `ui/circle-check`.

### ACF JSON renames

- [ ] `group_stats_block.json`: sub-field `icon` (key `field_stats_icon`) renamed to `ui_icon` (key `field_stats_ui_icon`), field type changed from `text` to `select`.
- [ ] `group_process_block.json`: sub-field `icon` (key `field_proc_step_icon`) renamed to `ui_icon` (key `field_proc_step_ui_icon`), field type changed from `text` to `select`.
- [ ] `group_services_block.json`: sub-field `icon` (key `field_services_card_icon`) renamed to `ui_icon` (key `field_services_card_ui_icon`), field type changed from `text` to `select`.
- [ ] `group_service_cpt_fields.json`: field `service_icon` (key `field_service_icon`) renamed to `ui_icon` (key `field_service_ui_icon`), field type changed from `text` to `select`.
- [ ] All renamed fields have `allow_null` set to `1` and an empty `choices` array (choices are injected at runtime by the filter).
- [ ] `modified` timestamp updated in each JSON file.

### Block template updates

- [ ] `template-parts/blocks/stats.php`: reads `$stat['ui_icon']` instead of `$stat['icon']`; icon path uses `aidriven_get_icon_path()` helper.
- [ ] `template-parts/blocks/process.php`: reads `$step['ui_icon']` instead of `$step['icon']`; icon path uses `aidriven_get_icon_path()` helper.
- [ ] `template-parts/blocks/services.php`: reads `get_field('ui_icon', get_the_ID())` instead of `get_field('service_icon', get_the_ID())`; icon path uses `aidriven_get_icon_path()` helper.
- [ ] `template-parts/pages/services-single.php`: reads `get_field('ui_icon', $service_id)` instead of `get_field('service_icon', $service_id)`; icon path uses `aidriven_get_icon_path()` helper.

### `aidriven_get_icon_path()` helper

- [ ] Function is added to `inc/functions-helpers.php`.
- [ ] Signature: `aidriven_get_icon_path( string $value, bool $absolute = true ): string`.
- [ ] `$value` is the raw ACF stored value, e.g. `ui/circle-check` or a bare `circle-check` (legacy fallback).
- [ ] When `$value` contains a `/`, constructs path as `assets/media/icons/{$value}.svg`.
- [ ] When `$value` contains no `/` (legacy bare filename), constructs path as `assets/media/icons/{$value}.svg` for backward compatibility with any pre-migration saved data.
- [ ] When `$absolute` is `true`, prepends `get_template_directory()`.
- [ ] Returns empty string when the resolved file does not exist.

### Existing `aidriven_get_svg_icon()` helper update

- [ ] `aidriven_get_svg_icon()` in `inc/functions-helpers.php` updated to accept a value in `category/filename` format and resolve it via `aidriven_get_icon_path()` internally, so existing call sites continue to work without change.

### General

- [ ] `make check` passes with zero new PHPCS errors.
- [ ] No block rendering is broken — all four blocks that used icon fields continue to render icons after the rename.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `assets/media/icons/social/` | New subdirectory (populated by moving existing social icons) |
| `assets/media/icons/tech/` | New subdirectory (empty at this step; populated in Step 38) |
| `assets/media/icons/ui/` | New subdirectory (populated by moving existing UI icons) |

### Files to Modify

| File | Change |
|---|---|
| `assets/media/icons/social/dribbble.svg` | Moved from root |
| `assets/media/icons/social/email.svg` | Moved from root |
| `assets/media/icons/social/github.svg` | Moved from root |
| `assets/media/icons/social/instagram.svg` | Moved from root |
| `assets/media/icons/social/linkedin.svg` | Moved from root |
| `assets/media/icons/social/twitter.svg` | Moved from root |
| `assets/media/icons/ui/circle-check.svg` | Moved from root |
| `assets/media/icons/ui/circle-info.svg` | Moved from root |
| `assets/media/icons/ui/circle-x.svg` | Moved from root |
| `assets/media/icons/ui/triangle-alert.svg` | Moved from root |
| `inc/functions-design.php` | Add `aidriven_get_icon_choices()` and `acf/load_field` filter |
| `inc/functions-helpers.php` | Add `aidriven_get_icon_path()`; update `aidriven_get_svg_icon()` |
| `acf-json/group_stats_block.json` | Rename `icon` sub-field to `ui_icon`; change type to `select` |
| `acf-json/group_process_block.json` | Rename `icon` sub-field to `ui_icon`; change type to `select` |
| `acf-json/group_services_block.json` | Rename `icon` sub-field to `ui_icon`; change type to `select` |
| `acf-json/group_service_cpt_fields.json` | Rename `service_icon` field to `ui_icon`; change type to `select` |
| `template-parts/blocks/stats.php` | Use `ui_icon` key; use `aidriven_get_icon_path()` |
| `template-parts/blocks/process.php` | Use `ui_icon` key; use `aidriven_get_icon_path()` |
| `template-parts/blocks/services.php` | Use `ui_icon`; use `aidriven_get_icon_path()` |
| `template-parts/pages/services-single.php` | Use `ui_icon`; use `aidriven_get_icon_path()` |

---

## Implementation Notes

### Stored value format

ACF stores only the selected choice key. After this step the stored value for any `ui_icon` field is `ui/circle-check` (category prefix included). This avoids ambiguity when the same filename exists in multiple categories. Legacy bare-filename values saved before this step (if any) are handled gracefully by `aidriven_get_icon_path()` falling back to the root path.

### `acf/load_field` filter pattern

```php
add_filter( 'acf/load_field', 'aidriven_load_icon_field_choices' );

function aidriven_load_icon_field_choices( array $field ): array {
    if ( 'select' !== $field['type'] ) {
        return $field;
    }
    if ( 'icon' === $field['name'] ) {
        $field['choices'] = aidriven_get_icon_choices();
        return $field;
    }
    if ( str_ends_with( $field['name'], '_icon' ) ) {
        $category         = substr( $field['name'], 0, -5 ); // strip '_icon'
        $field['choices'] = aidriven_get_icon_choices( $category );
    }
    return $field;
}
```

### ACF field group sync

After updating the JSON files, open **ACF → Field Groups** in WP admin and click **Sync available** to confirm all four groups load without errors. No manual field creation is needed.

### `tech/` subdirectory

The `tech/` directory is created empty at this step. It is populated with 44 SVG files in Step 38. The `aidriven_get_icon_choices()` function handles empty directories gracefully by returning an empty array for that category.

---

## Out of Scope

- Adding any new SVG icon files (that is Step 38)
- An icon picker preview UI in the block editor sidebar
- A front-end icon sprite system or `<use>` references
- Any CSS changes for icon sizing or colour (already handled per block in existing stylesheets)
- Renaming icon fields in blocks not yet built (e.g. Step 49 feature grid — those fields are defined when the block is implemented)
