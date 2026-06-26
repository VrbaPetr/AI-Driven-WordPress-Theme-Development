# Step 17 — Services Block

**Phase:** 5 — CPT-Backed Blocks  
**Depends on:** Steps 01, 03, 07, 08  
**Required by:** Steps 23, 25

---

## Summary

Build an ACF block that displays services as a card grid. The editor can choose between pulling cards automatically from the Services CPT or entering them manually in the block. A section heading is optional.

---

## User Stories

- **As a site editor**, I want to embed a services grid on the Home page that automatically reflects the Services CPT so I only maintain service content in one place.
- **As a site editor**, I want to manually enter a short curated set of services for a landing page without affecting the full Services archive.

---

## Business Value

Services are the primary product for IT agencies and freelancers. Surfacing them on the Home page and About page without requiring a developer to maintain hardcoded HTML is essential for ongoing content management.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] **Source: CPT** — runs a `WP_Query` for Services CPT; number of items configurable; optional filter by Service Category.
- [ ] **Source: Manual** — editor fills a repeater with individual cards (icon, title, description, optional link).
- [ ] Each card rendered via the Card component (Step 08) or a dedicated service card layout.
- [ ] Service icon: SVG icon name (stored in the Service CPT ACF fields) or fallback dashicon.
- [ ] Cards displayed in a responsive CSS grid: 3 columns on desktop, 2 on tablet, 1 on mobile.
- [ ] Optional "View All Services" link below the grid that points to the Services archive.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped; `WP_Query` uses `wp_reset_postdata()` after the loop.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/services.php` | Block template |
| `src/css/blocks/services.css` | Block styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/services` block |
| `src/css/main.css` | Import `blocks/services.css` |

---

## ACF Field Group: Services Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| Content Source | Select | CPT Query, Manual |
| — (CPT) Number of Items | Number | Default: 6; conditional: CPT only |
| — (CPT) Filter by Category | Taxonomy | Service Category; optional; conditional: CPT only |
| — (Manual) Service Cards | Repeater | Conditional: Manual only |
| ——— Icon | Text | SVG icon name |
| ——— Title | Text | |
| ——— Description | Textarea | |
| ——— Link URL | URL | Optional |
| Show "View All" Link | True/False | |
| "View All" Link Label | Text | Default: "View all services" |

---

## CPT ACF Fields (created separately in WP admin for the Service CPT)

When building this block, the following fields must also exist on the Service CPT (created in WP admin):

| Field label | Field type | Notes |
|---|---|---|
| Service Icon | Text | SVG icon name |
| Short Description | Textarea | 1–2 sentences for card display |

---

## Accessibility

- [ ] Card links are descriptive (service title, not "Read more")
- [ ] Icon is decorative (`aria-hidden="true"`)

---

## Out of Scope

- Service category filter UI on the block (handled by Services archive — Step 25)
- Service card with expandable description
