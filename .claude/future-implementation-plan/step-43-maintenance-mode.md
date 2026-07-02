# Step 43 — Maintenance / Coming Soon Page

**Phase:** 10 — Site UX & Utilities  
**Depends on:** Steps 01, 04  
**Required by:** nothing (end feature)

---

## Summary

Add a maintenance mode toggle to the ACF options page. When enabled, all logged-out visitors who are not site administrators are intercepted via a `template_redirect` hook and shown a branded coming-soon page. The page returns HTTP 503 with a `Retry-After: 3600` header so search engines know to come back later. Administrators see the site normally. The coming-soon template is a standalone full-page PHP file — no `get_header()` or `get_footer()` — so it renders correctly even when the site is mid-build and the theme may be only partially configured.

---

## User Stories

- **As a site owner**, I want to flip a toggle in the options page to put my site into maintenance mode so that visitors see a branded holding page while I work on the site.
- **As a visitor arriving during maintenance**, I want to see a clear message that the site will be back shortly, along with a way to contact the team, so that I am not left confused by a blank or broken page.
- **As a search engine crawler**, I want to receive a 503 status with a `Retry-After` header so that my index is not harmed by the temporary unavailability.
- **As a site administrator**, I want to be able to view and test the live site while maintenance mode is active so that I can verify changes before taking the site back online.

---

## Business Value

Maintenance mode is a basic operational requirement for any professionally managed site. Without it, mid-update visitors encounter broken layouts or half-deployed content. A branded holding page maintains trust and keeps search engine rankings intact by correctly signalling temporary unavailability rather than a permanent error.

---

## Acceptance Criteria

### ACF Fields

- [ ] A `maintenance_mode` true/false field exists on the options page with a default value of `false` (unchecked).
- [ ] A `maintenance_heading` text field exists with a default value of `"We'll be back soon"`.
- [ ] A `maintenance_message` textarea field exists for an optional extended message to visitors.
- [ ] All three fields are grouped logically (either appended to the existing Site Settings group or in a dedicated `group_ss_maintenance.json`).

### Redirect Logic

- [ ] A `template_redirect` action is registered in `inc/functions-helpers.php`.
- [ ] The hook fires only on the front end — guarded by `!is_admin()`.
- [ ] Maintenance mode is active when `get_field('maintenance_mode', 'option')` returns truthy.
- [ ] When active, users who satisfy both `!is_user_logged_in()` and `!current_user_can('manage_options')` are redirected to the coming-soon template.
- [ ] The hook sends HTTP status `503` and a `Retry-After: 3600` header before loading the template.
- [ ] After loading the template, `exit` is called to prevent further WordPress processing.
- [ ] The `wp-login.php` page and `wp-admin` are never intercepted — the `is_admin()` guard and the fact that `template_redirect` does not fire on those routes together ensure this.
- [ ] Logged-in administrators (`current_user_can('manage_options')`) always see the live site.

### Coming-Soon Template (`coming-soon.php`)

- [ ] File lives at the theme root (`coming-soon.php`), loaded directly via `get_template_directory() . '/coming-soon.php'`.
- [ ] Outputs a complete, valid HTML document (`<!DOCTYPE html>` through `</html>`) — no `get_header()` or `get_footer()`.
- [ ] Company logo is pulled from `get_field('company_logo', 'option')` and rendered as an `<img>` with `alt` text from the logo field's `alt` sub-key; hidden gracefully when not set.
- [ ] Heading rendered from `get_field('maintenance_heading', 'option')`; falls back to `"We'll be back soon"` if empty.
- [ ] Message rendered from `get_field('maintenance_message', 'option')`; section omitted entirely when the field is empty.
- [ ] Contact email pulled from `get_field('contact_email', 'option')` (Step 04 options field); rendered as a `mailto:` link; omitted when not set.
- [ ] Page `<title>` is `esc_html( get_bloginfo('name') ) . ' — Coming Soon'`.
- [ ] `assets/css/coming-soon.css` is enqueued via a `<link>` tag with a versioned query string using `wp_get_theme()->get('Version')`.
- [ ] No Vite manifest or `wp_enqueue_scripts` used — the stylesheet is referenced directly in the template's `<head>`.
- [ ] All output is escaped (`esc_html`, `esc_url`, `esc_attr`, `wp_kses_post` for textarea).

### Stylesheet (`assets/css/coming-soon.css`)

- [ ] Standalone vanilla CSS — not imported into `main.css`, not processed by Vite.
- [ ] Full-viewport centred layout using flexbox.
- [ ] References `--color-primary` and neutral tokens from the site's CSS custom properties where available; uses safe fallback values (plain hex) for any critical colour not guaranteed to resolve outside the main stylesheet context.
- [ ] Logo constrained to a sensible `max-width` and `max-height`.
- [ ] Typography is clean, readable sans-serif; no web font loaded — `font-family: system-ui, sans-serif`.
- [ ] Contact link styled to stand out without relying on Tailwind utilities.

### Quality

- [ ] `make check` passes (PHPCS).
- [ ] No PHP warnings or notices when maintenance mode is off or when optional fields are empty.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `coming-soon.php` | Standalone full-page maintenance template, loaded directly by the redirect hook |
| `assets/css/coming-soon.css` | Standalone styles for the coming-soon page — not part of the Vite build |
| `acf-json/group_ss_maintenance.json` | ACF field group defining `maintenance_mode`, `maintenance_heading`, and `maintenance_message` on the options page |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-helpers.php` | Add `aidriven_maybe_show_maintenance_page()` function and `add_action( 'template_redirect', ... )` |

---

## Implementation Notes

### Hook (`inc/functions-helpers.php`)

```php
/**
 * Intercept front-end requests and serve the coming-soon page when maintenance mode is active.
 */
function aidriven_maybe_show_maintenance_page(): void {
    if ( is_admin() ) {
        return;
    }

    if ( ! get_field( 'maintenance_mode', 'option' ) ) {
        return;
    }

    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        return;
    }

    header( 'HTTP/1.1 503 Service Unavailable' );
    header( 'Retry-After: 3600' );

    $template = get_template_directory() . '/coming-soon.php';
    if ( file_exists( $template ) ) {
        include $template;
    }

    exit;
}
add_action( 'template_redirect', 'aidriven_maybe_show_maintenance_page' );
```

### ACF JSON (`acf-json/group_ss_maintenance.json`)

Create a new field group with `location` rule `options_page == site-settings`. Include three fields in order:

1. `maintenance_mode` — `true_false` type, `default_value: 0`, `ui: 1`.
2. `maintenance_heading` — `text` type, `default_value: "We'll be back soon"`.
3. `maintenance_message` — `textarea` type, `rows: 4`, no default value.

Use deterministic keys: `group_ss_maintenance`, `field_ss_maintenance_mode`, `field_ss_maintenance_heading`, `field_ss_maintenance_message`.

### Template structure (`coming-soon.php`)

The template must be entirely self-contained. Key points:

- WordPress has already bootstrapped by the time `template_redirect` fires, so all WP functions including `get_field()` are available.
- Load the stylesheet with a direct `<link>` tag rather than `wp_head()` to avoid enqueuing the full theme stylesheet chain.
- Wrap the optional logo, message, and contact sections in `if ( ! empty( ... ) )` guards so no empty `<div>` elements are emitted.
- The `<html>` tag should include `lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>"`.

### CSS approach (`assets/css/coming-soon.css`)

Write vanilla CSS only. Key rules:

- `body` — `margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: var(--color-bg, #f9f9f9); font-family: system-ui, sans-serif;`
- `.coming-soon` — centred container, `max-width: 480px`, adequate padding, `text-align: center`.
- `.coming-soon__logo img` — `max-width: 180px; max-height: 80px; width: auto; height: auto; margin-bottom: 2rem;`
- `.coming-soon__heading` — large, prominent heading using `var(--color-text, #111)`.
- `.coming-soon__message` — muted body text using `var(--color-muted, #555)`.
- `.coming-soon__contact a` — styled with `var(--color-primary, #0066cc)`, `font-weight: 600`.

---

## Accessibility

- [ ] The coming-soon page `<html lang>` attribute matches the WordPress site language setting.
- [ ] Logo `<img>` has a meaningful `alt` attribute — falls back to `get_bloginfo('name')` when the ACF `alt` sub-key is empty.
- [ ] Contact link text is descriptive — rendered as the email address itself, not "click here".
- [ ] Colour contrast between heading/body text and the background must meet WCAG AA.

---

## Notes

- The `template_redirect` hook does not fire for `wp-login.php` or `wp-admin` requests by design — WordPress routes those before `template_redirect`. The additional `is_admin()` guard is a defensive belt-and-braces measure.
- The `exit` call after `include` is essential — without it WordPress will continue processing and may output a second document.
- Because the coming-soon template includes its own `<!DOCTYPE html>` and `<html>`, calling `get_header()` or `get_footer()` inside it would produce duplicate document wrappers and invalid HTML.
- The ACF true/false field with `ui: 1` renders as a toggle switch in the admin, which is more intuitive for a binary setting than a checkbox.
- If the site is behind a reverse proxy or CDN, the 503 response must be verified at the edge layer — WordPress-emitted headers may be cached or overridden depending on proxy configuration.

---

## Out of Scope

- IP allowlist to bypass maintenance mode for specific addresses
- Countdown timer on the coming-soon page
- Email capture / notification sign-up on the holding page
- Cron-based scheduled maintenance windows
- WP-CLI command to toggle maintenance mode
- Network/multisite-aware maintenance mode (per-site vs. network-wide)
