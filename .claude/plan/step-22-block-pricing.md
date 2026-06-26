# Step 22 — Pricing Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Steps 01, 07  
**Required by:** Step 23

---

## Summary

Build an ACF block that displays up to four pricing plan cards. One plan can be visually highlighted as "featured". Each card contains a plan name, price, billing period, description, feature list, and a CTA button.

---

## User Stories

- **As a site editor**, I want to publish a pricing section without writing custom HTML so I can update pricing during a promotion without developer involvement.
- **As a visitor**, I want to compare plans side by side at a glance so I can identify the right tier before contacting the company.

---

## Business Value

Transparent pricing reduces sales friction and pre-qualifies leads — visitors who contact after seeing pricing are more serious than those with no price context.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Plans defined via ACF Repeater (max 4 items).
- [ ] Each plan card: plan name, price (number or text for "Custom"), billing period (e.g. "/ month"), description, feature list (text repeater), CTA button (label + URL), featured flag.
- [ ] Featured plan receives a distinct visual treatment: elevated card, different background, badge label (e.g. "Most Popular").
- [ ] Plans displayed in a horizontal row on desktop; 2 columns on tablet; stacked on mobile.
- [ ] Featured plan is visually centred and slightly larger on desktop (optional — implement if design supports it).
- [ ] Optional section heading (`<h2>`) and subheading.
- [ ] CTA buttons rendered via Button component; featured plan uses `variant: primary`, others use `variant: outline`.
- [ ] Feature list items: checked icon for included features; optional strikethrough/cross icon for excluded features.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/pricing.php` | Block template |
| `src/css/blocks/pricing.css` | Pricing card styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/pricing` block |
| `src/css/main.css` | Import `blocks/pricing.css` |

---

## ACF Field Group: Pricing Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Section Subheading | Text | Optional |
| Plans | Repeater (max 4) | |
| — Plan Name | Text | e.g. "Starter", "Pro" |
| — Price | Text | Number or "Custom" |
| — Billing Period | Text | e.g. "/ month", "/ project" |
| — Description | Text | One-line plan summary |
| — Features | Repeater | |
| ——— Feature Text | Text | |
| ——— Included | True/False | Default: true |
| — CTA Label | Text | |
| — CTA URL | URL | |
| — Featured | True/False | Highlights this card |
| — Featured Badge Label | Text | Default: "Most Popular"; conditional: Featured only |

---

## Accessibility

- [ ] Heading hierarchy: section `<h2>` → plan name `<h3>`
- [ ] Feature list uses `<ul>` with `<li>` items; checked/cross icons are `aria-hidden`, meaning conveyed by text (e.g. screen reader hears "Included: Unlimited projects")
- [ ] Buttons are descriptive: "Get started with Starter" not generic "Get started"

---

## Out of Scope

- Monthly/annual price toggle (not selected in survey)
- Comparison table view
- Stripe or payment integration
