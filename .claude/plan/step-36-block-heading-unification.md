# Step 36 — Block Heading Unification

**Phase:** 8 — Accessibility & Editor Fixes  
**Depends on:** Steps 12, 13, 15, 16, 17, 18, 19, 20, 21 (existing blocks)  
**Required by:** Phase 12 new blocks (establishes the subheading pattern for all future blocks)

---

## Summary

Nine existing blocks expose a `section_heading` text field but have no way for editors to add a supporting subheading beneath it. This step adds a `section_subheading` text field immediately after `section_heading` in each block's ACF JSON file and renders it conditionally in each block template as a lead paragraph (`<p>`). No heading level is used — the subheading is presentational support copy, not a structural heading. Existing pages that leave the field empty are completely unaffected.

---

## User Stories

- **As a site editor**, I want to add a short supporting sentence below a block's section heading so I can give visitors more context without editing the page source.
- **As a developer**, I want a consistent subheading field across all blocks so the editor experience is predictable and future blocks can follow the same pattern without debate.

---

## Business Value

Section headings alone are often too terse to communicate value clearly. A one-line subheading slot doubles the copywriting flexibility for every major content block without requiring a new block or a WYSIWYG field. Standardising the field name and render pattern across all blocks reduces cognitive overhead for both editors and future developers.

---

## Acceptance Criteria

### Field Definition (per block)

- [ ] A `section_subheading` Text field is present in each of the 9 ACF JSON files.
- [ ] The field appears immediately after `section_heading` in the field order.
- [ ] Field key follows the pattern `field_{block_slug}_section_subheading` (e.g. `field_text_image_section_subheading`).
- [ ] The field label is `Section Subheading`; it is not required.
- [ ] No default value is set (empty by default).

### Template Render (per block)

- [ ] The subheading is output only when the field value is non-empty (conditional render).
- [ ] It is rendered as a `<p>` tag — never as a heading element (`h1`–`h6`).
- [ ] Output is escaped with `esc_html()`.
- [ ] The `<p>` tag carries a utility class that styles it as a lead paragraph (e.g. `block-subheading` or equivalent token-based class matching the design system).
- [ ] When the field is empty, no empty `<p>` tag is rendered.

### Regression

- [ ] Existing pages that do not have a subheading value saved are visually identical before and after the change.
- [ ] `make check` passes across all modified PHP files.

---

## Technical Scope

### Files to Modify

#### ACF JSON files

| File | Change |
|---|---|
| `acf-json/group_text_image_block.json` | Add `field_text_image_section_subheading` Text field after `section_heading` |
| `acf-json/group_stats_block.json` | Add `field_stats_section_subheading` Text field after `section_heading` |
| `acf-json/group_faq_block.json` | Add `field_faq_section_subheading` Text field after `section_heading` |
| `acf-json/group_services_block.json` | Add `field_services_section_subheading` Text field after `section_heading` |
| `acf-json/group_portfolio_grid_block.json` | Add `field_portfolio_grid_section_subheading` Text field after `section_heading` |
| `acf-json/group_testimonials_block.json` | Add `field_testimonials_section_subheading` Text field after `section_heading` |
| `acf-json/group_clients_block.json` | Add `field_clients_section_subheading` Text field after `section_heading` |
| `acf-json/group_team_block.json` | Add `field_team_section_subheading` Text field after `section_heading` |
| `acf-json/group_cta_block.json` | Add `field_cta_section_subheading` Text field after `section_heading` |

#### Block templates

| File | Change |
|---|---|
| `template-parts/blocks/text-image.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/stats.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/faq.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/services.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/portfolio-grid.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/testimonials.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/clients.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/team.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |
| `template-parts/blocks/cta.php` | Read `section_subheading`; render conditional `<p>` after the `<h2>` |

---

## ACF JSON Field Snippet (reference for all 9 files)

```json
{
  "key": "field_{block_slug}_section_subheading",
  "label": "Section Subheading",
  "name": "section_subheading",
  "type": "text",
  "required": 0,
  "default_value": "",
  "placeholder": "",
  "prepend": "",
  "append": "",
  "maxlength": ""
}
```

Place this object in the `fields` array directly after the `section_heading` field object. Update the `modified` timestamp on the group.

---

## Template Render Pattern (reference for all 9 templates)

```php
$section_subheading = get_field( 'section_subheading' );

if ( $section_subheading ) : ?>
    <p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
<?php endif;
```

The `block-subheading` class (or the project's equivalent lead-paragraph utility) must be defined in the shared block styles or the design token layer — it must not be a new one-off class. Use whatever lead/intro paragraph style is already established in the project CSS.

---

## Notes

- After editing each JSON file, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before moving to the next block.
- The `modified` timestamp in each JSON file must be updated to the current Unix timestamp so ACF detects the change and offers a sync.
- The field is intentionally a plain Text field — not WYSIWYG or Textarea — to keep subheadings concise and free of markup.
- Do not rename or reorder any existing fields in the JSON files; only insert the new field in the correct position.

---

## Out of Scope

- Adding `section_subheading` to the Hero block (Step 11) — the Hero has a dedicated `subtitle` field that serves the same purpose.
- Adding `section_subheading` to the Process block (Step 14) or Pricing block (Step 22) — handled separately if needed.
- Styling changes to `section_heading` itself.
- Introducing a Textarea or WYSIWYG variant of the subheading field.
- Per-block subheading font size or colour overrides.
