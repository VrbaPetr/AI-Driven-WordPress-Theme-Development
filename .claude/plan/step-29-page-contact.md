# Step 29 — Contact Page Template

**Phase:** 6 — Page Templates  
**Depends on:** Steps 01, 05, 06, 10, 24, 30  
**Required by:** nothing (end feature)

---

## Summary

Create a Contact page template with three sections: a page header, the contact form (from Step 30), and a contact details panel sourced from ACF Options. An optional Google Maps embed area is included.

---

## User Stories

- **As a visitor ready to get in touch**, I want a contact page that shows the form and the company's contact details side by side so I can choose my preferred method of contact.
- **As a site editor**, I want the phone number and address on the contact page to come from the global Options so I only update it in one place.

---

## Business Value

The contact page is the primary conversion endpoint. A clean layout that places the form prominently and backs it up with direct contact details (for visitors who prefer phone/email) maximises the chance of an enquiry.

---

## Acceptance Criteria

- [ ] `page-contact.php` created with `Template Name: Contact` header comment.
- [ ] Page header: `<h1>` from page title, optional subtitle from page excerpt, breadcrumbs.
- [ ] Two-column layout on desktop: **Left column** — contact form (Step 30); **Right column** — contact details panel.
- [ ] Contact details panel pulls from ACF Options: address, phone, email, Google Maps URL.
- [ ] Address formatted with `<address>` element.
- [ ] Phone and email are clickable links (`tel:`, `mailto:`).
- [ ] Google Maps URL renders an `<a>` "View on Google Maps" link; does not embed an iframe (avoids cookie consent complexity).
- [ ] Social links row at the bottom of the details column (from options).
- [ ] Stacks to single column on mobile: form first, then details.
- [ ] All output escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `page-contact.php` | WordPress named template |
| `template-parts/pages/contact.php` | Page header + two-column layout |

---

## Layout Sketch

```
┌─────────────────────────────┐
│  Page Header (h1 + breadcrumbs) │
├──────────────────┬──────────┤
│   Contact Form   │ Details  │
│   (Step 30)      │ Address  │
│                  │ Phone    │
│                  │ Email    │
│                  │ Maps Lnk │
│                  │ Socials  │
└──────────────────┴──────────┘
```

---

## Dependency Note

Step 30 (Contact AJAX Form) must be implemented before Step 29, as the contact page template includes the form template part via `get_template_part('template-parts/forms/contact')`.

---

## Accessibility

- [ ] Form and details panel have appropriate heading structure
- [ ] `<address>` element wraps the postal address
- [ ] Social icons have `aria-label`

---

## Out of Scope

- Embedded Google Maps iframe (avoids cookie consent issue)
- Calendar booking embed (supported as a use case but not templated — editor can embed via Gutenberg block)
- Multiple office locations layout
