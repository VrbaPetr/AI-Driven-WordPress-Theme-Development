---
name: php-block-conventions
description: Conventions for writing ACF Gutenberg block template files in template-parts/blocks/.
---

## Docblock

Always use this format at the top of every block template:

```php
<?php
/**
 * Block: {Block Title}
 *
 * @package {textdomain}
 */
```

## Field loading

Load all ACF fields at the top of the `else` block as named variables before any HTML:

```php
$block_title = get_field( 'title' );
$block_desc  = get_field( 'description' );
```

## Link fields

Destructure into separate variables with a safe default for target:

```php
$cta_link = get_field( 'cta_button' );
if ( $cta_link ) {
    $cta_title  = $cta_link['title'];
    $cta_url    = $cta_link['url'];
    $cta_target = $cta_link['target'] ? $cta_link['target'] : '_self';
}
```

## Image fields

Load via `get_field()`, render via `wp_get_attachment_image()`:

```php
$image = get_field( 'image' );
```

## Guard clause

Always check required fields with `! empty()` before rendering:

```php
if ( ! empty( $block_title ) && ! empty( $image ) ) :
    // block markup
endif;
```

## Output escaping

Escape everything that is output:

| Context | Function |
|---|---|
| Plain text | `esc_html()` |
| HTML attributes (incl. conditional classes) | `esc_attr()` |
| Rich text / HTML content | `wp_kses_post()` |
| URLs | `esc_url()` |

## Reusable components

Include via `get_template_part()` with an args array:

```php
get_template_part(
    'template-parts/components/button',
    null,
    array(
        'label'   => $cta_title,
        'url'     => $cta_url,
        'target'  => $cta_target,
        'variant' => 'primary',
    )
);
```

## PHP / HTML interleaving

Use PHP open/close tags around HTML sections:

```php
if ( $title ) : ?>
    <h1 class="block-title"><?php echo esc_html( $title ); ?></h1>
<?php endif; ?>
```

## Images

Always use `wp_get_attachment_image()` with the attachment ID — it handles srcset, sizes, alt text, and lazy loading automatically:

```php
$image = get_field( 'image' );
if ( ! empty( $image ) ) : ?>
    <?php echo wp_get_attachment_image( $image['ID'], 'full' ); ?>
<?php endif; ?>
```

Replace `'full'` with the appropriate registered image size for the context. Custom image sizes are registered in `inc/functions-design.php` via `add_image_size()`.
