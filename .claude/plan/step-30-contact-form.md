# Step 30 — Contact AJAX Form

**Phase:** 7 — Forms & Site Utilities  
**Depends on:** Steps 01, 09  
**Required by:** Step 29

---

## Summary

Build a custom AJAX-powered contact/enquiry form with server-side validation, honeypot spam protection, nonce verification, and an email notification to the admin. The form UI uses Alpine.js to manage loading, success, and error states without page reload.

---

## User Stories

- **As a visitor**, I want to submit an enquiry and get instant feedback (success or error message) without leaving the page so the experience feels fast and modern.
- **As a site admin**, I want to receive an email notification for every new enquiry so I can follow up promptly.
- **As a developer**, I want spam protection that doesn't require a third-party API key so the theme works out of the box on any server.

---

## Business Value

A well-designed, reliable contact form is the most important functional element on a B2B marketing site. AJAX submission prevents full page reloads, reducing perceived friction and form abandonment.

---

## Acceptance Criteria

- [ ] Form template renders: Name, Email, Subject, Message fields, hidden Honeypot field, nonce field, Submit button.
- [ ] Honeypot: a hidden field (visually hidden with CSS, not `display:none`) that must remain empty; bots fill it and get silently rejected.
- [ ] Nonce: `wp_nonce_field('aidriven_contact_form')` output; verified server-side with `wp_verify_nonce()`.
- [ ] Alpine.js `x-data` controls: `loading` (bool), `success` (bool), `errors` (object), `serverError` (string).
- [ ] On submit: button shows spinner, form fields disabled.
- [ ] On success: form replaced by success message (Alert component, `variant: success`).
- [ ] On validation error: inline error messages displayed below relevant fields.
- [ ] On server error: generic error Alert shown; form re-enabled.
- [ ] Server-side validation: all fields required, email format checked, honeypot checked, nonce checked.
- [ ] Admin notification email: plain text, contains all form fields, sent to `get_option('admin_email')`.
- [ ] Email sender name/address uses options page company name and a no-reply address.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/forms/contact.php` | Form markup with Alpine.js data bindings |
| `src/css/forms/contact.css` | Form styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-ajax.php` | Add `aidriven_handle_contact_form` action (both `wp_ajax_` and `wp_ajax_nopriv_`) |
| `inc/functions-form.php` | Add `aidriven_validate_contact_form( $data )` validation function |
| `src/css/main.css` | Import `forms/contact.css` |

---

## Form Fields

| Field | Type | Validation |
|---|---|---|
| Name | text input | Required, max 100 chars |
| Email | email input | Required, valid email format |
| Subject | text input | Required, max 200 chars |
| Message | textarea | Required, min 10 chars |
| Honeypot | text input (hidden) | Must be empty |

---

## AJAX Handler Flow

```
POST /wp-admin/admin-ajax.php
action: aidriven_contact_form
Fields: name, email, subject, message, honeypot, nonce

1. Verify nonce              → 403 on fail
2. Check honeypot empty      → silent 200 OK (fake success) on fail
3. Sanitise all inputs
4. Validate all inputs       → 422 JSON {errors: {...}} on fail
5. Send admin email          → wp_mail()
6. Return 200 JSON {success: true}
```

---

## Alpine.js Data Object

```js
x-data="{
  loading: false,
  success: false,
  errors: {},
  serverError: '',
  async submit(form) {
    this.loading = true;
    this.errors = {};
    this.serverError = '';
    const res = await fetch(ajaxurl, { method: 'POST', body: new FormData(form) });
    const data = await res.json();
    this.loading = false;
    if (data.success) { this.success = true; }
    else if (data.errors) { this.errors = data.errors; }
    else { this.serverError = data.message; }
  }
}"
```

`ajaxurl` is localised via `wp_localize_script()` in `functions.php`.

---

## Accessibility

- [ ] Each input has a visible `<label>` (not placeholder-only)
- [ ] Error messages are associated with their input via `aria-describedby`
- [ ] Submit button has a loading state that updates its `aria-label` ("Sending…")
- [ ] Success message receives focus (or is in `aria-live` region) so screen readers announce it

---

## Out of Scope

- File upload field
- CRM integration (Mailchimp, HubSpot, etc.)
- Multi-step form
- CAPTCHA / reCAPTCHA (honeypot only per survey)
