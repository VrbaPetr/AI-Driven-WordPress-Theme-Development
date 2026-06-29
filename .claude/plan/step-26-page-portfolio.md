# Step 26 — Portfolio Archive & Single Templates

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 03, 05, 06, 08, 09, 10, 18, 24  
**Required by:** nothing (end feature)

---

## Summary

Create two templates for the Portfolio/Projects CPT: an **archive** that mirrors the Portfolio Grid Block with category filters, and a **single** (case study) layout with a dedicated structure — hero image, challenge/solution/result sections, a gallery, and related projects.

---

## User Stories

- **As a visitor**, I want to browse case studies and filter by project type so I can find examples most relevant to my industry.
- **As a prospect**, I want to read how a project unfolded — the challenge, the approach, the outcome — so I can assess whether the company can solve my problem.
- **As a site editor**, I want a structured single-project layout with named sections so I can write consistent case studies without formatting fights in the block editor.

---

## Business Value

Portfolio / case studies are the primary proof-of-work for IT agencies. A structured single-project layout (challenge → solution → result) tells the story in the format that converts best, rather than relying on editors to compose a good story from scratch.

---

## Acceptance Criteria

### Archive (`archive-project.php`)

- [ ] Filterable grid matching Portfolio Grid Block (Step 18) behaviour.
- [ ] Category filter buttons (Portfolio Categories); Alpine.js client-side filtering.
- [ ] Pagination component.
- [ ] Page header: `<h1>`, breadcrumbs.
- [ ] All output escaped.

### Single (`single-project.php`)

- [ ] Page header: project title as `<h1>`, Portfolio Category badge, breadcrumbs.
- [ ] Hero: full-width featured image using `hero-full` image size.
- [ ] Project meta sidebar: Client, Year, Services Used (Repeater of service names), Project URL (if public).
- [ ] Structured content sections: Challenge, Solution, Results — each from an ACF Textarea or WYSIWYG field.
- [ ] Gallery section: ACF Gallery field, rendered as a CSS grid of thumbnails; lightbox optional.
- [ ] Related Projects section: 3 projects from the same Portfolio Category, rendered as cards.
- [ ] CTA block or inline CTA at the bottom.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `archive-project.php` | Archive template |
| `single-project.php` | Single template |
| `template-parts/pages/portfolio-archive.php` | Archive content part |
| `template-parts/pages/portfolio-single.php` | Case study content part |

---

## CPT ACF Fields for Project Single (created in WP admin)

These supplement the fields defined in Step 18:

| Field label | Field type | Notes |
|---|---|---|
| Client Name | Text | |
| Project Year | Number | |
| Services Used | Repeater | |
| — Service Name | Text | Name of a service delivered in this project |
| Live Project URL | URL | Optional |
| Challenge | WYSIWYG | The problem the client faced |
| Solution | WYSIWYG | The approach taken |
| Results | WYSIWYG | Measurable outcomes |
| Gallery | Gallery | Project screenshots / photos |

---

## Accessibility

- [ ] Gallery images have alt text from WP attachment alt fields
- [ ] If lightbox is implemented, it is keyboard and focus-trap accessible
- [ ] Related project cards are descriptive links (project title)

---

## Out of Scope

- Client testimonial quote embedded in case study (use Testimonials block separately)
- Password-protected case studies
- PDF version of case study
