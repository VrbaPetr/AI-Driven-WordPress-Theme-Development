# Step 21 — Team Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Steps 01, 03  
**Required by:** Steps 24, 27

---

## Summary

Build an ACF block that pulls Team Members from the Team Members CPT and displays them as a card grid. Each card shows a portrait photo, name, job title, short bio, and social links.

---

## User Stories

- **As a site editor**, I want to manage team members via the Team Members CPT so the About page updates automatically when someone joins or leaves.
- **As a visitor**, I want to see who is behind the company with photos and roles so I can assess the team's expertise and culture.

---

## Business Value

Humanising an IT agency or freelancer with real faces and bios builds trust and reduces the anonymous "faceless company" barrier. This is especially important for smaller agencies competing against enterprise vendors.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Pulls from Team Members CPT via `WP_Query`; number of members and column count configurable.
- [ ] Each card: portrait image (using `team-portrait` image size), name (`<h3>`), job title, bio excerpt (truncated), social links row.
- [ ] Social links rendered using SVG icons from `assets/media/icons/`; only platforms with a filled URL are shown.
- [ ] Responsive grid: matches configured column count on desktop; 2 columns on tablet; 1 on mobile.
- [ ] Optional section heading (`<h2>`).
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped; `wp_reset_postdata()` after loop.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/team.php` | Block template |
| `src/css/blocks/team.css` | Block styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/team` block |
| `src/css/main.css` | Import `blocks/team.css` |

---

## ACF Field Group: Team Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Number of Members | Number | Default: all; set to limit |
| Columns | Select | 2, 3, 4 (default: 3) |

---

## CPT ACF Fields (created in WP admin for the Team Member CPT)

| Field label | Field type | Notes |
|---|---|---|
| Job Title | Text | |
| Short Bio | Textarea | 2–3 sentences; used in card excerpt |
| Social Links | Repeater | |
| — Platform | Select | LinkedIn, GitHub, Twitter/X, Instagram, Dribbble, Email, Other |
| — URL | URL | |

---

## Accessibility

- [ ] Portrait image has alt text: "{Name}, {Job Title}" — generated from CPT data if no custom alt set
- [ ] Social icon links have `aria-label` with platform and person name (e.g. "John Smith on LinkedIn")
- [ ] Card heading level `<h3>` appropriate under a section `<h2>`

---

## Out of Scope

- Team member single-page template (out of scope per plan)
- Leadership spotlight / featured card variant (not selected in survey)
- Department/role filtering
