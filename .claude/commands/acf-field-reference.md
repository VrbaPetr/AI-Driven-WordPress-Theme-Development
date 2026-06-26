---
name: acf-field-reference
description: ACF field type reference — how to load and render every ACF field type using get_field().
---

Every field is loaded with `get_field()`. The return value contains everything needed.

**Text / Textarea / Number / Email / URL** — returns a scalar value:

```php
$title = get_field( 'title' );
```

**True / False** — returns a boolean:

```php
$is_featured = get_field( 'is_featured' );
if ( $is_featured ) : ... endif;
```

**Select / Radio** — returns the selected value as a string (or the label, depending on field setting — check the field config in `acf-json/`):

```php
$color = get_field( 'color' ); // e.g. 'blue'
```

**Checkbox** — returns an array of selected values:

```php
$tags = get_field( 'tags' ); // e.g. [ 'tag-a', 'tag-b' ]
if ( ! empty( $tags ) ) :
    foreach ( $tags as $tag ) : ... endforeach;
endif;
```

**Image** — returns an associative array. Render via `wp_get_attachment_image()` using the `ID` key:

```php
$image = get_field( 'image' );
if ( ! empty( $image ) ) :
    echo wp_get_attachment_image( $image['ID'], 'full' );
endif;
```

**Link** — returns an associative array. Destructure with a safe `_self` default for target:

```php
$cta_link = get_field( 'cta_button' );
if ( $cta_link ) {
    $cta_title  = $cta_link['title'];
    $cta_url    = $cta_link['url'];
    $cta_target = $cta_link['target'] ? $cta_link['target'] : '_self';
}
```

**Gallery** — returns an array of image arrays, same structure as a single image field. Render each via `wp_get_attachment_image()`:

```php
$gallery = get_field( 'gallery' );
if ( ! empty( $gallery ) ) :
    foreach ( $gallery as $image ) :
        echo wp_get_attachment_image( $image['ID'], 'large' );
    endforeach;
endif;
```

**Repeater** — returns an array of rows. Each row is an associative array keyed by sub-field name:

```php
$items = get_field( 'items' );
if ( ! empty( $items ) ) :
    foreach ( $items as $item ) :
        $item_title = $item['title'];
        $item_text  = $item['description'];
    endforeach;
endif;
```

**Group** — returns an associative array keyed by sub-field name:

```php
$settings = get_field( 'settings' );
if ( ! empty( $settings ) ) {
    $bg_color = $settings['background_color'];
    $layout   = $settings['layout'];
}
```

**Relationship / Post Object** — returns a `WP_Post` object (single) or an array of `WP_Post` objects (multiple). Use `->ID` for IDs and standard WP functions for other data:

```php
$related_posts = get_field( 'related_posts' ); // array of WP_Post objects
if ( ! empty( $related_posts ) ) :
    foreach ( $related_posts as $post ) :
        $post_title     = esc_html( $post->post_title );
        $post_permalink = esc_url( get_permalink( $post->ID ) );
    endforeach;
endif;
```

**Flexible Content** — returns an array of layout rows. Each row has an `acf_fc_layout` key identifying the layout name, plus keys for all sub-fields of that layout:

```php
$sections = get_field( 'sections' );
if ( ! empty( $sections ) ) :
    foreach ( $sections as $section ) :
        $layout = $section['acf_fc_layout'];

        if ( 'text_block' === $layout ) :
            $text = $section['text'];
        elseif ( 'image_block' === $layout ) :
            $image = $section['image'];
        endif;
    endforeach;
endif;
```
