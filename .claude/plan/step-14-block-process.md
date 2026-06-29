# Step 14 — Process / Steps Block

**Phase:** 4 — Content Blocks  
**Depends on:** Step 01  
**Required by:** Steps 23, 24

---

## Summary

Build an ACF block for displaying a numbered "how we work" or onboarding process. The editor defines the steps via a repeater and chooses between a horizontal (row of cards) or vertical (stacked list) layout.

---

## User Stories

- **As a site editor**, I want to explain our delivery process in a visual step sequence so prospects understand how a project unfolds before they enquire.
- **As a site editor**, I want to toggle between a horizontal and vertical layout so the same content adapts to different page contexts (landing page vs. inner page column).

---

## Business Value

A visible process section reduces sales friction — it answers the unspoken question "what happens after I get in touch?" This directly improves enquiry-to-proposal conversion for IT agencies and freelancers.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Section heading field (optional `<h2>`).
- [ ] Steps defined via ACF Repeater; no minimum, maximum of 8 recommended by UX.
- [ ] Each step: step number (auto-generated from repeater index, not manual), icon (optional SVG name), title, description.
- [ ] **Horizontal layout**: steps displayed in a row with connecting line/arrow between them on desktop; wraps to vertical on mobile.
- [ ] **Vertical layout**: steps stacked with a vertical connecting line on the left side.
- [ ] Layout toggle controlled by an ACF Select field.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/process.php` | Block template |
| `src/css/blocks/process.css` | Block styles including connector lines |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/process` block |
| `src/css/main.css` | Import `blocks/process.css` |

---

## ACF Field Group: Process Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Section Subheading | Text | Optional supporting sentence |
| Layout | Select | Horizontal, Vertical (default: Horizontal) |
| Steps | Repeater | |
| — Icon | Text | SVG icon name (optional) |
| — Title | Text | Step title |
| — Description | Textarea | 1–2 sentence description |

---

## Layout Details

### Horizontal

- CSS Grid: equal-width columns, one per step.
- Connector: a CSS `::after` pseudo-element on each item (except last) draws an arrow or line.
- On mobile (< `md`): collapses to vertical list.

### Vertical

- Single column; step number/icon on the left, content on the right.
- Vertical connector drawn as a left-border or pseudo-element line between items.

---

## Accessibility

- [ ] Step numbers are meaningful to screen readers (rendered as text or `aria-label`, not just styled CSS counters)
- [ ] Decorative connector lines hidden from assistive tech (`aria-hidden` or CSS-only)

---

## Out of Scope

- Timeline variant with dates (not selected in survey)
- Interactive/clickable steps
