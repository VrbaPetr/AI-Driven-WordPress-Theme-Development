---
name: php-component-conventions
description: Conventions for writing component template parts in template-parts/components/.
---

Component template parts live in `template-parts/components/`. They are called via `get_template_part()` with an args array and contain only presentation logic.

## Docblock

Use `Component:` as the prefix and document every accepted arg with `@type` inside the `@param array $args` block:

```php
<?php
/**
 * Component: Button
 *
 * @package {textdomain}
 *
 * @param array $args {
 *     @type string $label    Button text.
 *     @type string $url      Button href.
 *     @type string $target   Link target. Default '_self'.
 *     @type string $variant  Visual variant: 'primary' | 'secondary'. Default 'primary'.
 *     @type string $class    Additional CSS classes.
 * }
 */
```

## Args destructuring

Destructure all args at the top of the file as named variables using `??` for defaults:

```php
$label   = $args['label'] ?? '';
$url     = $args['url'] ?? '#';
$target  = $args['target'] ?? '_self';
$variant = $args['variant'] ?? 'primary';
$class   = $args['class'] ?? '';
```

**Boolean args** — use `! empty()`:

```php
$disabled = ! empty( $args['disabled'] );
$checked  = ! empty( $args['checked'] );
```

## Computed class strings

Build modifier-based class strings as a variable before output, then escape with `esc_attr()`. Follow the BEM state modifier pattern (`component--modifier`):

```php
$wrapper_class = 'btn btn-' . $variant;
if ( $disabled ) {
    $wrapper_class .= ' btn--disabled';
}
```

```php
<button class="<?php echo esc_attr( $wrapper_class . ' ' . $class ); ?>">
```

## WordPress form helpers

Use WordPress built-in helpers for boolean HTML attributes:

```php
<input type="checkbox" <?php checked( $checked ); ?> <?php disabled( $disabled ); ?>>
```

## Structure order

1. Docblock
2. Args destructuring (`$args['key'] ?? default`)
3. Computed values (class strings, conditionals)
4. Markup (PHP/HTML interleaved)
