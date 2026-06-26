# Step 03 — Custom Post Types Registration

**Phase:** 1 — Foundation  
**Depends on:** Step 01  
**Required by:** Steps 17, 18, 19, 21, 25, 26

---

## Summary

Register four Custom Post Types: **Services**, **Portfolio/Projects**, **Team Members**, and **Testimonials**. Each CPT gets proper labels, admin icon, capability type, rewrite slug, and `supports` array. Companion taxonomies (Service Category, Portfolio Category) are also registered.

---

## User Stories

- **As a site editor**, I want a Services section in WP admin so I can manage service offerings without touching page content.
- **As a site editor**, I want a Portfolio section so I can publish case studies and filter them by category in the front-end grid.
- **As a site editor**, I want a Team Members section so I can manage staff profiles separately from pages.
- **As a site editor**, I want a Testimonials section so I can add client quotes that feed the testimonials slider automatically.

---

## Business Value

CPTs separate editorial content from design. An editor can add a new team member or case study without needing a developer to create a page and wire it up to a block.

---

## Acceptance Criteria

- [ ] **Services** CPT registered: slug `service`, supports `title + editor + thumbnail + excerpt`, has-archive `services`, i18n labels, dashicon `dashicons-hammer`.
- [ ] **Portfolio** CPT registered: slug `project`, supports `title + editor + thumbnail + excerpt`, has-archive `portfolio`, i18n labels, dashicon `dashicons-portfolio`.
- [ ] **Team Members** CPT registered: slug `team-member`, supports `title + editor + thumbnail`, no archive (single pages not required by plan), i18n labels, dashicon `dashicons-groups`.
- [ ] **Testimonials** CPT registered: slug `testimonial`, supports `title + editor + thumbnail`, no public archive, i18n labels, dashicon `dashicons-format-quote`.
- [ ] **Service Category** taxonomy registered: hierarchical, linked to Services CPT, slug `service-category`.
- [ ] **Portfolio Category** taxonomy registered: hierarchical, linked to Portfolio CPT, slug `portfolio-category`.
- [ ] All CPTs and taxonomies appear correctly in WP admin sidebar.
- [ ] All label strings are wrapped in `__()` with the `ai-driven-boilerplate` text domain.
- [ ] `make check` passes with zero errors.

---

## Technical Scope

### Files to Modify

| File | Change |
|---|---|
| `inc/register-cpt.php` | Add four `register_post_type()` calls hooked to `init` |
| `inc/register-taxonomy.php` | Add two `register_taxonomy()` calls hooked to `init` |

---

## CPT Specifications

### Services
| Property | Value |
|---|---|
| Post type key | `service` |
| Rewrite slug | `services` |
| Has archive | `services` |
| Supports | `title`, `editor`, `thumbnail`, `excerpt`, `page-attributes` |
| Menu icon | `dashicons-hammer` |

### Portfolio / Projects
| Property | Value |
|---|---|
| Post type key | `project` |
| Rewrite slug | `portfolio` |
| Has archive | `portfolio` |
| Supports | `title`, `editor`, `thumbnail`, `excerpt`, `page-attributes` |
| Menu icon | `dashicons-portfolio` |

### Team Members
| Property | Value |
|---|---|
| Post type key | `team-member` |
| Rewrite slug | `team` |
| Has archive | `false` |
| Supports | `title`, `editor`, `thumbnail` |
| Menu icon | `dashicons-groups` |

### Testimonials
| Property | Value |
|---|---|
| Post type key | `testimonial` |
| Rewrite slug | `testimonials` |
| Has archive | `false` |
| Supports | `title`, `editor`, `thumbnail` |
| Menu icon | `dashicons-format-quote` |

---

## Taxonomy Specifications

### Service Category
| Property | Value |
|---|---|
| Taxonomy key | `service-category` |
| Linked CPT | `service` |
| Hierarchical | Yes |
| Rewrite slug | `service-category` |

### Portfolio Category
| Property | Value |
|---|---|
| Taxonomy key | `portfolio-category` |
| Linked CPT | `project` |
| Hierarchical | Yes |
| Rewrite slug | `portfolio-category` |

---

## Out of Scope

- ACF field groups for these CPTs (created in WP admin when implementing the blocks that use them)
- Single-page templates for Team Members (out of scope per plan)
- Single-page templates for Testimonials (out of scope per plan)
