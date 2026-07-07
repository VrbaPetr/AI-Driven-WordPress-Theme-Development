# Step 53 — Awards & Certifications Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Step 01 (theme foundation), Step 36 (section_subheading pattern)  
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block that displays a structured row of awards, certifications, and partner badges. Unlike the Clients / Logos block (logo-only, flat image list), this block adds semantic structure around each item: a badge image, a human-readable award name, the issuing body, and the year received. Item count is typically low (under 10), so no CPT is needed — a repeater field is the correct data model. The layout is a centred flex-wrap row; badge images render at a consistent height. A subtle CSS-only hover effect (scale or opacity) provides interactivity without JavaScript.

---

## User Stories

- **As a site editor**, I want to add an awards and certifications section to any page so I can showcase credentials and partnerships without writing code.
- **As a site editor**, I want to enter a badge image, award name, issuing body, and year for each item so that the block communicates more than a raw logo — it provides context that builds trust.
- **As a site visitor**, I want to see a visually consistent row of badges with their names and issuers so I can quickly scan the agency's credentials and understand their significance.
- **As a developer**, I want this block to be distinct from the Clients / Logos block so that the two blocks serve clearly different editorial intents and are not confused during page composition.

---

## Business Value

ISO certifications, Google / Meta Partner badges, and industry awards are strong trust signals for IT agencies and freelancers. Displaying them in a structured, branded way — rather than as an unstyled image strip — reinforces credibility on home pages, about pages, and proposal-support landing pages. A reusable block means editors can add or update credentials without developer involvement.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/awards-certifications`.
- [ ] Block title is `Awards & Certifications`; category is `formatting` or the theme's custom block category.
- [ ] Block preview image exists at `assets/media/block-preview/awards-certifications.jpg` (or `.png`).

### ACF Field Group

- [ ] ACF field group JSON file written directly to `acf-json/group_awards_certifications_block.json`.
- [ ] All field keys follow the pattern `field_awards_cert_{field_name}`.
- [ ] Field group assigned to the `acf/awards-certifications` block.

### Fields

- [ ] `section_heading` (Text, not required) — renders as `<h2>` when non-empty.
- [ ] `section_subheading` (Text, not required) — renders as `<p class="block-subheading">` when non-empty; follows the Step 36 pattern.
- [ ] `awards` (Repeater, required, min 1) — sub-fields:
  - `badge_image` (Image, not required) — return format `array`; rendered via `wp_get_attachment_image()` at a consistent registered size.
  - `award_name` (Text, required) — the human-readable name of the award or certification.
  - `issued_by` (Text, not required) — the issuing organisation or body.
  - `year` (Text, not required) — the year the award or certification was received or is valid for.

### Template Rendering

- [ ] Section heading renders as `<h2>` only when non-empty; escaped with `esc_html()`.
- [ ] Section subheading renders as `<p class="block-subheading">` only when non-empty; escaped with `esc_html()`.
- [ ] Each award item renders: badge image (when set) → award name → issued by → year.
- [ ] Badge image rendered via `wp_get_attachment_image()` using the theme-registered `awards-badge` image size; `alt` attribute sourced from ACF image array (`alt` key) falling back to `award_name`.
- [ ] When `badge_image` is empty the image slot is omitted entirely — no empty `<img>` or wrapper left in the DOM.
- [ ] `award_name` rendered inside a semantically appropriate element (e.g. `<strong>` or `<p>`); escaped with `esc_html()`.
- [ ] `issued_by` rendered only when non-empty; escaped with `esc_html()`.
- [ ] `year` rendered only when non-empty; escaped with `esc_html()`.
- [ ] The repeater loop uses `have_rows()` / `the_row()` / `get_sub_field()`.
- [ ] The outer awards list is wrapped in a single container element; no extra wrapper when the list is empty.

### Styles

- [ ] Awards row uses `display: flex; flex-wrap: wrap; justify-content: center` so items centre and wrap naturally at any viewport width.
- [ ] Badge images render at a consistent height (approximately 80 px); width scales proportionally (`height: 80px; width: auto`).
- [ ] CSS-only hover effect on each item: subtle scale (`transform: scale(1.05)`) or opacity decrease — no JavaScript.
- [ ] Transition duration and easing applied via CSS `transition` property on the item element.
- [ ] Item layout is column-direction flex: badge image on top, text below.
- [ ] Text beneath the badge (award name, issued by, year) is centred and uses token-defined colours and font sizes.
- [ ] No inline styles — all sizing, spacing, and colour from design tokens in `src/css/variables/`.
- [ ] Only token-defined colours used — no Tailwind built-in palette references, no hardcoded hex or OKLCH values.
- [ ] Responsive: adequate gap between items at all viewport widths; items do not overflow on mobile.

### Image Size

- [ ] Custom image size `awards-badge` registered in `inc/functions-design.php` via `add_image_size( 'awards-badge', 0, 160 )` (height-constrained, proportional width; `0` means unconstrained width; use `160` as `2×` retina target for an 80 px display height).

### Quality

- [ ] No JavaScript required or added.
- [ ] All output escaped — no exceptions.
- [ ] `make check` passes with zero new PHPCS errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/awards-certifications.php` | Block template — reads fields, renders heading/subheading and flex-wrap awards row |
| `src/css/blocks/awards-certifications.css` | Block styles — flex row layout, badge sizing, hover effect, text styling |
| `acf-json/group_awards_certifications_block.json` | ACF field group definition |
| `assets/media/block-preview/awards-certifications.jpg` | Editor preview image |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/awards-certifications` block |
| `inc/functions-design.php` | Register `awards-badge` image size via `add_image_size()` |
| `src/css/main.css` | Import `blocks/awards-certifications.css` |

---

## ACF Field Group

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Not required; renders as `<h2>` when non-empty |
| Section Subheading | `section_subheading` | Text | Not required; renders as `<p class="block-subheading">` when non-empty |
| Awards | `awards` | Repeater | Required; min 1; no max enforced |
| — Badge Image | `badge_image` | Image | Not required; return format `array` |
| — Award Name | `award_name` | Text | Required |
| — Issued By | `issued_by` | Text | Not required |
| — Year | `year` | Text | Not required |

ACF field key convention: `field_awards_cert_section_heading`, `field_awards_cert_section_subheading`, `field_awards_cert_awards`, `field_awards_cert_badge_image`, `field_awards_cert_award_name`, `field_awards_cert_issued_by`, `field_awards_cert_year`.

Group key: `group_awards_certifications_block`.  
Location rule: Block → is equal to → `acf/awards-certifications`.

---

## Notes

- The `year` field is deliberately typed as Text (not Number or Date Picker) because editors may need to enter values like `2023–2025` for multi-year certifications or `ongoing` for perpetual partner statuses.
- The `badge_image` return format must be `array` so the template can access both the attachment ID (for `wp_get_attachment_image()`) and the `alt` key for accessible image alt text.
- The `awards-badge` image size uses `0` for width so WordPress does not crop images horizontally — badges come in many aspect ratios and must never be distorted. The height of `160` (2× retina) targets an 80 px rendered height at standard DPR.
- The hover effect (scale or opacity) is applied to the individual item wrapper via `transition` and `transform` / `opacity` in CSS. No `:active` state beyond what the browser provides by default is required.
- This block is intentionally distinct from the Clients / Logos block: the Clients block renders a flat row of client logos with no additional metadata; the Awards block adds editorial structure (name, issuer, year) that gives each badge meaning. Do not merge them.
- After generating `acf-json/group_awards_certifications_block.json`, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before building the template.
- The `issued_by` and `year` fields are both optional because some badges (e.g. a generic "Top Agency" seal) may not have a meaningful issuer or year to display.

---

## Out of Scope

- Links on individual award items (e.g. linking to a verification page)
- Filtering or sorting awards by category or year
- A CPT-backed dynamic source for awards data
- Animated entrance effects requiring JavaScript
- A "View all awards" link or pagination
- Dark-mode specific badge treatment (can be added as a follow-on step)
- Modal or tooltip pop-up with extended award description
