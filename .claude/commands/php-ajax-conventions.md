---
name: php-ajax-conventions
description: Conventions for writing AJAX handlers in inc/functions-ajax.php.
---

All AJAX handlers live in `inc/functions-ajax.php`. They call data functions from `functions-data.php` for the actual queries.

## Handler structure

Always follow this order inside every handler:

1. **Nonce verification** — first line, fail with 403
2. **Sanitize** all `$_POST` inputs immediately after
3. **Validate** sanitized values — fail with 400 or 404 for bad input
4. **Query** data by calling functions from `functions-data.php`
5. **Respond** with `wp_send_json_success()` or `wp_send_json_error()`

```php
function textdomain_ajax_load_items() {
    // 1. Nonce
    if ( ! check_ajax_referer( 'textdomain_load_items', 'nonce', false ) ) {
        wp_send_json_error( array( 'message' => 'Invalid nonce.' ), 403 );
    }

    // 2. Sanitize
    $post_id = isset( $_POST['post_id'] ) ? (int) wp_unslash( $_POST['post_id'] ) : 0;
    $filter  = isset( $_POST['filter'] ) ? sanitize_text_field( wp_unslash( $_POST['filter'] ) ) : '';

    // 3. Validate
    if ( ! $post_id ) {
        wp_send_json_error( array( 'message' => 'Missing post ID.' ), 400 );
    }

    // 4. Query
    $result = textdomain_get_items( $post_id, $filter );

    // 5. Respond
    wp_send_json_success( array( 'items' => $result ) );
}
add_action( 'wp_ajax_textdomain_load_items', 'textdomain_ajax_load_items' );
add_action( 'wp_ajax_nopriv_textdomain_load_items', 'textdomain_ajax_load_items' );
```

## Input sanitization

| Input type | Pattern |
|---|---|
| Integer | `(int) wp_unslash( $_POST['key'] )` |
| String | `sanitize_text_field( wp_unslash( $_POST['key'] ) )` |
| URL | `esc_url_raw( wp_unslash( $_POST['key'] ) )` |

Always check `isset()` and provide a safe default before casting or sanitizing.

## HTML in JSON responses

When the response payload contains HTML (e.g. rendered template parts), use `ob_start()` / `ob_get_clean()` to capture `get_template_part()` output as a string:

```php
ob_start();
get_template_part( 'template-parts/components/card', null, array( 'id' => $post_id ) );
$html = ob_get_clean();

wp_send_json_success( array( 'html' => $html ) );
```

## Hook registration

Register both `wp_ajax_` and `wp_ajax_nopriv_` immediately after the handler function. Use `wp_ajax_nopriv_` for all handlers that must work for logged-out visitors (front-end AJAX).
