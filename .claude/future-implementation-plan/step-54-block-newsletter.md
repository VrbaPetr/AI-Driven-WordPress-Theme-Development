# Step 54 — Newsletter / Email Capture Block

**Phase:** 12 — New Content Blocks
**Depends on:** Steps 01, 07 (button component), 30 (contact form AJAX pattern — honeypot + nonce), 36 (subheading pattern)
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block for inline newsletter / email opt-in capture. The block renders a self-contained strip with a heading, subtext, an email input, a submit button, and an optional privacy note. Security uses the same honeypot + nonce pattern established in Step 30. Alpine.js manages loading, success, and error states identically to the contact form. When the editor supplies a `provider_webhook_url` (Mailchimp, Brevo, etc.) the form POSTs directly to that URL; otherwise it falls back to a WP AJAX handler (`wp_ajax_nopriv_aidriven_newsletter_subscribe`) in `inc/functions-ajax.php` that fires an action hook developers can bind to.

---

## User Stories

- **As a site editor**, I want to add a newsletter opt-in strip to any page so I can grow an email list without embedding third-party widget code.
- **As a site editor**, I want to customise the heading, subtext, button label, and privacy note so the block matches the surrounding page copy without involving a developer.
- **As a site editor**, I want to connect the form to my email provider (Mailchimp, Brevo, etc.) by pasting a webhook URL into the block settings so I can go live without writing PHP.
- **As a developer**, I want a clean action hook (`aidriven_newsletter_subscribe`) fired from the AJAX handler so I can wire up any provider integration without touching the theme.
- **As a visitor**, I want to see a success message after subscribing and an error message if something goes wrong so I know what happened without the page reloading.
- **As a visitor using keyboard navigation**, I want to tab through the email field and submit button and see visible focus states so I can complete the form without a mouse.

---

## Business Value

Email capture is a primary lead-generation mechanism on IT agency and freelancer sites. Providing it as a configurable block lets editors deploy opt-in strips on landing pages, the home page footer, and blog post bottoms without developer involvement. The webhook URL escape hatch means no custom PHP integration is required for the most common providers, while the action hook keeps the codebase extensible for bespoke setups.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/newsletter`.
- [ ] Block title is `Newsletter / Email Capture`; category is the theme's custom block category or `common`.
- [ ] Block preview image placed at `assets/media/block-preview/newsletter.jpg` (or `.png`).
- [ ] ACF field group generated as `acf-json/group_newsletter_block.json`.

### ACF Field Group

- [ ] All field keys follow the pattern `field_newsletter_{field_name}`.
- [ ] Field group is assigned to the `acf/newsletter` block.

### Fields

- [ ] `heading` (Text, required) — rendered as `<h2>` inside the block.
- [ ] `subtext` (Text, not required) — rendered as `<p class="block-subheading">` following the Step 36 pattern; omitted when empty.
- [ ] `input_placeholder` (Text, not required, default `"Your email address"`) — used as the `placeholder` attribute on the email `<input>`.
- [ ] `button_label` (Text, not required, default `"Subscribe"`) — used as the visible button text.
- [ ] `privacy_note` (Text, not required) — rendered as a small paragraph below the form; omitted when empty.
- [ ] `provider_webhook_url` (URL, not required) — when non-empty the form `action` points here and the Alpine.js fetch target switches accordingly; when empty the form posts to the WP AJAX handler.

### Template Rendering

- [ ] `heading` rendered as `<h2>` escaped with `esc_html()`.
- [ ] `subtext` rendered as `<p class="block-subheading">` escaped with `esc_html()` only when non-empty.
- [ ] Email `<input>` has `type="email"`, `name="email"`, `required`, `autocomplete="email"`, and the `placeholder` from `input_placeholder` escaped with `esc_attr()`.
- [ ] Submit button label escaped with `esc_html()`.
- [ ] `privacy_note` rendered as `<p class="newsletter-privacy">` escaped with `esc_html()` only when non-empty.
- [ ] Honeypot: a text `<input>` with `name="website"` (or similar) visually hidden via CSS (not `display:none` or `visibility:hidden`); must be empty for the submission to proceed.
- [ ] Nonce: `wp_nonce_field( 'aidriven_newsletter_subscribe' )` output inside the form.
- [ ] When `provider_webhook_url` is non-empty the form's `action` attribute is set to `esc_url( $provider_webhook_url )`; the Alpine.js `fetch` URL is also set to the provider webhook rather than `ajaxurl`.
- [ ] When `provider_webhook_url` is empty the form `action` is `#` and the Alpine.js handler posts to `ajaxurl` with `action: 'aidriven_newsletter_subscribe'`.

### Alpine.js Behaviour

- [ ] `x-data` on the form wrapper exposes: `loading` (bool), `success` (bool), `errorMsg` (string).
- [ ] On submit: `loading = true`; email field and button disabled via `:disabled="loading"`.
- [ ] Button shows a spinner or "…" text while `loading` is true; `aria-label` updated to `"Subscribing…"`.
- [ ] On success: the form is replaced by a success message (use the Alert component at `variant: success` or equivalent inline markup); `success = true`.
- [ ] On error: `errorMsg` is set to the server's error string; an inline error message is shown below the form; form re-enabled.
- [ ] When `provider_webhook_url` is set the Alpine.js handler still controls loading/success/error states via the provider's HTTP response.

### AJAX Handler (WP fallback path)

- [ ] Action registered for both `wp_ajax_aidriven_newsletter_subscribe` and `wp_ajax_nopriv_aidriven_newsletter_subscribe`.
- [ ] Handler verifies nonce with `wp_verify_nonce()`; returns HTTP 403 on failure.
- [ ] Handler checks honeypot is empty; returns silent 200 JSON `{success: true}` on bot detection (fake success).
- [ ] Sanitises email with `sanitize_email()`.
- [ ] Validates email with `is_email()`; returns HTTP 422 JSON `{success: false, message: '…'}` on invalid email.
- [ ] Fires `do_action( 'aidriven_newsletter_subscribe', $email )` so developers can hook in provider integrations.
- [ ] Returns HTTP 200 JSON `{success: true}` after firing the action.
- [ ] No third-party HTTP requests made by default — the action hook is the only integration point.

### Styles

- [ ] Block layout is responsive: single-column on mobile, side-by-side input + button on tablet and above.
- [ ] Input and button are visually aligned (same height baseline).
- [ ] Privacy note is visually smaller (e.g. font-size token for small text) and muted in colour using a token-defined value.
- [ ] Honeypot input is visually hidden without using `display:none` or `visibility:hidden` (use `position:absolute; opacity:0; …` or equivalent CSS-only technique).
- [ ] Only token-defined colors used — no Tailwind built-in palette references, no hardcoded hex or OKLCH values.
- [ ] All focus states use the theme's standard focus ring token.
- [ ] `@media (prefers-reduced-motion: reduce)` suppresses any loading spinner animation.

### Quality

- [ ] All output escaped — no exceptions.
- [ ] `make check` passes with zero new PHPCS errors.
- [ ] No JS files added — all interactivity is inline Alpine.js on the template.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_newsletter_block.json` | ACF field group definition for all newsletter block fields |
| `template-parts/blocks/newsletter.php` | Block template — heading, subtext, form, honeypot, nonce, privacy note |
| `src/css/blocks/newsletter.css` | Block styles — layout, input/button alignment, privacy note, honeypot hiding |
| `assets/media/block-preview/newsletter.jpg` | Editor preview image shown in the Gutenberg block inserter |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/newsletter` block |
| `inc/functions-ajax.php` | Add `aidriven_handle_newsletter_subscribe` handler registered on both `wp_ajax_` and `wp_ajax_nopriv_` hooks |
| `src/css/main.css` | Add `@import "blocks/newsletter.css"` |

---

## ACF Field Group

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Heading | `heading` | Text | Required; rendered as `<h2>` |
| Subtext | `subtext` | Text | Not required; rendered as `<p class="block-subheading">` when non-empty |
| Input Placeholder | `input_placeholder` | Text | Not required; default `"Your email address"` |
| Button Label | `button_label` | Text | Not required; default `"Subscribe"` |
| Privacy Note | `privacy_note` | Text | Not required; rendered as small muted paragraph below the form when non-empty |
| Provider Webhook URL | `provider_webhook_url` | URL | Not required; when set the form POSTs directly to this URL instead of the WP AJAX handler |

ACF field key convention: `field_newsletter_heading`, `field_newsletter_subtext`, `field_newsletter_input_placeholder`, `field_newsletter_button_label`, `field_newsletter_privacy_note`, `field_newsletter_provider_webhook_url`.

---

## AJAX Handler Flow (WP fallback path)

```
POST /wp-admin/admin-ajax.php
action: aidriven_newsletter_subscribe
Fields: email, website (honeypot), _wpnonce

1. Verify nonce (wp_verify_nonce)           → 403 JSON {success:false} on fail
2. Check honeypot field is empty            → 200 JSON {success:true} silently on bot
3. Sanitise email (sanitize_email)
4. Validate email (is_email)               → 422 JSON {success:false, message:'…'} on fail
5. do_action('aidriven_newsletter_subscribe', $email)
6. Return 200 JSON {success:true}
```

---

## Alpine.js Pattern

The `x-data` attribute on the form wrapper:

```html
<form
    x-data="{
        loading: false,
        success: false,
        errorMsg: '',
        async submit(form) {
            this.loading  = true;
            this.errorMsg = '';
            const target  = form.action !== '#' ? form.action : ajaxurl;
            const body    = new FormData(form);
            if (target === ajaxurl) { body.append('action', 'aidriven_newsletter_subscribe'); }
            try {
                const res  = await fetch(target, { method: 'POST', body });
                const data = await res.json();
                if (data.success) { this.success = true; }
                else { this.errorMsg = data.message || 'Something went wrong. Please try again.'; }
            } catch (e) {
                this.errorMsg = 'A network error occurred. Please try again.';
            } finally {
                this.loading = false;
            }
        }
    }"
    @submit.prevent="submit($el)"
    action="<?php echo $provider_webhook_url ? esc_url( $provider_webhook_url ) : '#'; ?>"
    method="post"
    novalidate
>
```

`ajaxurl` is localised via `wp_localize_script()` in `functions.php` (already available from the contact form implementation in Step 30).

---

## Notes

- The `input_placeholder` and `button_label` fields carry ACF `default_value` entries (`"Your email address"` and `"Subscribe"` respectively) so new block instances are immediately usable without editor input.
- The honeypot field must use `position:absolute; left:-9999px; opacity:0; width:1px; height:1px; overflow:hidden;` (or equivalent) rather than `display:none` — screen-reader-invisible but still submitted by bots.
- When `provider_webhook_url` is set the block template sets `action="<?php echo esc_url($provider_webhook_url); ?>"` directly on the `<form>`. The Alpine.js handler still intercepts `@submit.prevent` and manages state, but the `fetch` target becomes the provider URL rather than `ajaxurl`. Developers integrating a provider that requires a redirect instead of a JSON response should leave `provider_webhook_url` empty and bind to the `aidriven_newsletter_subscribe` action hook instead.
- The `do_action( 'aidriven_newsletter_subscribe', $email )` hook in the AJAX handler is the intended integration point. A developer would add something like `add_action( 'aidriven_newsletter_subscribe', 'my_plugin_add_subscriber' )` in a plugin or child theme — no theme code needs to change.
- The nonce field is always rendered regardless of whether `provider_webhook_url` is set. When the form posts to a third-party webhook the nonce field is harmlessly ignored by the provider.
- There is no need for a new Alpine.js script file — the `x-data` object is self-contained inline on the template, consistent with the contact form pattern from Step 30.
- After generating `acf-json/group_newsletter_block.json`, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors before building the template.

---

## Out of Scope

- Double opt-in confirmation email (provider-side responsibility)
- Name or additional field collection beyond email
- CAPTCHA / reCAPTCHA (honeypot only, consistent with Step 30)
- Built-in Mailchimp / Brevo / ConvertKit SDK integration in PHP (use the action hook)
- Subscriber storage in a WordPress custom table (use the action hook)
- Unsubscribe flow
- Popup or modal variant (block is always inline)
- Multiple email inputs or multi-step flows
