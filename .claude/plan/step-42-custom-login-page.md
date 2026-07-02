# Step 42 — Custom Login Page

**Phase:** 10 — Site UX & Utilities  
**Depends on:** Steps 01, 04  
**Required by:** nothing (end feature)

---

## Summary

Replace the default WordPress login screen with a fully branded version using WP login hooks — no template file override required. The company logo is pulled from the ACF options page, displayed above the login form via an inline CSS custom property, and the form inherits the site's primary colour for the submit button. The result is a login page that feels like part of the site rather than a generic WordPress screen.

---

## User Stories

- **As a site owner**, I want the login page to display my company logo and brand colours so that the admin experience feels professional and consistent with the front-end.
- **As a developer handing off the site**, I want the login page to automatically pick up the logo and primary colour from the options page so there is nothing to configure manually after launch.

---

## Business Value

A branded login page is a small but visible trust signal, especially for clients logging in to manage content. It reinforces brand identity at every touchpoint and removes the generic WordPress branding that can make a bespoke site feel off-the-shelf.

---

## Acceptance Criteria

- [ ] `login_enqueue_scripts` hook registered in `inc/functions-design.php`.
- [ ] `assets/css/login.css` enqueued as a standalone stylesheet (not part of the Vite build).
- [ ] Company logo from `get_field('company_logo', 'option')` injected as a CSS custom property (`--login-logo-url`) via `wp_add_inline_style`.
- [ ] When no logo is set, the logo area collapses gracefully — no broken image or empty gap.
- [ ] `login_headerurl` returns `home_url()`.
- [ ] `login_headertext` returns the site name from `get_bloginfo('name')`.
- [ ] `login_body_class` appends the theme slug class (`aidriven-login`).
- [ ] Login form is centred vertically and horizontally on the page.
- [ ] Logo is displayed above the login box using `background-image` driven by `--login-logo-url`.
- [ ] Submit button uses `--color-primary` (the site's brand primary colour token).
- [ ] Typography is clean sans-serif, matching the front-end font stack.
- [ ] All PHP output is escaped.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `assets/css/login.css` | Standalone login page styles — loaded directly, not compiled by Vite |

### Files to Modify

| File | Change |
|---|---|
| `inc/functions-design.php` | Add `login_enqueue_scripts`, `login_headerurl`, `login_headertext`, and `login_body_class` hooks |

---

## Implementation Notes

### Hook registration (`inc/functions-design.php`)

All four hooks live in `inc/functions-design.php` because they control presentation rather than data or AJAX logic.

```php
/**
 * Enqueue styles and inject logo variable on the login screen.
 */
function aidriven_login_styles(): void {
    wp_enqueue_style(
        'aidriven-login',
        get_template_directory_uri() . '/assets/css/login.css',
        array(),
        wp_get_theme()->get( 'Version' )
    );

    $logo_field = get_field( 'company_logo', 'option' );
    $logo_url   = ! empty( $logo_field['url'] ) ? esc_url( $logo_field['url'] ) : '';

    if ( $logo_url ) {
        $inline = '--login-logo-url: url("' . $logo_url . '");';
        wp_add_inline_style( 'aidriven-login', ':root { ' . $inline . ' }' );
    }
}
add_action( 'login_enqueue_scripts', 'aidriven_login_styles' );

add_filter( 'login_headerurl', fn() => home_url() );
add_filter( 'login_headertext', fn() => get_bloginfo( 'name' ) );

add_filter( 'login_body_class', function ( array $classes ): array {
    $classes[] = 'aidriven-login';
    return $classes;
} );
```

### CSS approach (`assets/css/login.css`)

`login.css` is a standalone file — it is never imported into `main.css` and is not processed by Vite. Write vanilla CSS only; no Tailwind utilities. Reference the design token `--color-primary` (already available site-wide via the root stylesheet) and the injected `--login-logo-url`.

Key rules to include:

- `body.login` — full-viewport background, flexbox centering, brand-neutral background colour.
- `body.login #login h1 a` — logo block: fixed height, `background-image: var(--login-logo-url)`, `background-size: contain`, `background-repeat: no-repeat`, `background-position: center`, `width: 100%`. When `--login-logo-url` is not set the element renders with zero effective height — use `min-height` only when the variable is defined (use `@supports` or accept graceful collapse).
- `body.login #loginform` — white card, subtle box-shadow, rounded corners, adequate padding.
- `body.login .button-primary` — background `var(--color-primary)`, no WordPress blue.
- `body.login #nav a, body.login #backtoblog a` — muted link colour using a neutral token.
- Clean sans-serif via `font-family: inherit` so the operating-system / browser default sans-serif is used (the front-end web font is not loaded on the login screen).

---

## Accessibility

- [ ] Logo link `title` attribute is set by `login_headertext` — screen readers announce the site name.
- [ ] Focus styles on form inputs must remain visible — do not reset `outline` without a replacement.
- [ ] Colour contrast between button text and `--color-primary` background must meet WCAG AA (4.5 : 1 for normal text).

---

## Out of Scope

- Two-factor authentication UI
- Custom registration or password-reset page styling (these can be addressed as follow-on steps)
- Limiting login attempts or adding CAPTCHA
- Custom login redirect logic (that belongs in `functions-helpers.php` or a separate step)
- Loading the full front-end stylesheet on the login page
