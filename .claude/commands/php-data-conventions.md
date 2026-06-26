---
name: php-data-conventions
description: Conventions for writing data functions in inc/functions-data.php.
---

All data functions live in `inc/functions-data.php`. They **return data only**. Any HTML output belongs in a template part called via `get_template_part()`.

## Naming

- Prefix every function with the text domain, replacing hyphens with underscores — read it from `style.css` (`Text Domain: ai-driven-boilerplate` → prefix `ai_driven_boilerplate_`).
- Follow the prefix with a verb: `get_`, `fill_`, `construct_`, `attach_`, `build_`.

```php
function textdomain_get_related_posts( $post_id ) { ... }
function textdomain_fill_filter_with_unique( $objects, $field ) { ... }
```

## Docblock

Every function must have a full docblock with typed `@param` and `@return` annotations. Types should be specific — `WP_Term|null`, `array|null`, not `mixed`:

```php
/**
 * Return the parent term for the given child term ID.
 *
 * @param int $child_id ID of the child term.
 * @return WP_Term|null parent term object or null if not found.
 */
function textdomain_get_parent_term( $child_id ) {
```

## Guard clauses

Always guard `get_term()`, `get_terms()`, and `get_the_terms()` results before accessing properties:

```php
$state = get_the_terms( $post_id, 'state' );

if ( empty( $state ) || is_wp_error( $state ) ) {
    return null;
}

$state_object = $state[0];
```

Always use real booleans in query args:

```php
'hide_empty' => true,
```

## `phpcs:ignore` usage

Add an inline `// phpcs:ignore` comment only on the exact line that triggers a known PHPCS false positive. Use it exclusively for `meta_query` and `tax_query` slow-query warnings:

```php
'meta_query' => array( // phpcs:ignore
    array( ... ),
),
```
