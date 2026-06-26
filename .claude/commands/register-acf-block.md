---
name: register-acf-block
description: Register an ACF Gutenberg block and create all required files in the correct directories.
---

# Step 1. Register the ACF Block

- Open `inc/register-blocks.php` and locate the `register_acf_blocks()` function
- Add a new block definition inside the function using the template below
- **Block name** must always be provided by the user or defined in the plan — ask if missing
- **Text domain** must be read from `style.css` (e.g. `Text Domain: ai-driven-boilerplate`)
- **Icon** — choose a dashicon appropriate to the block's purpose if not specified; use the name without the `dashicons-` prefix (e.g. use `clipboard`, not `dashicons-clipboard`)

```php
// Block Name
acf_register_block_type(
    array(
        'name'            => 'textdomain-block-name',
        'title'           => __( 'Block Title', 'textdomain' ),
        'description'     => __( 'Block description — generate one if not provided.', 'textdomain' ),
        'render_template' => 'template-parts/blocks/block-name.php',
        'category'        => 'textdomain-blocks',
        'icon'            => 'dashicon-icon',
        'keywords'        => array( __( 'keyword1', 'textdomain' ), __( 'keyword2', 'textdomain' ) ),
        'mode'            => 'preview',
        'supports'        => array(
            'mode'  => true,
            'align' => false,
            'jsx'   => true,
        ),
        'example'         => array(
            'attributes' => array(
                'mode' => 'preview',
                'data' => array(
                    'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/block-name.jpg',
                ),
            ),
        ),
    )
);
```

Some blocks may require specific assets to be enqueued. Extend the definition array with the snippet below **only** when assets are explicitly specified by the user or in the plan.

```php
'enqueue_assets' => function () {
    wp_enqueue_script( 'script-handle', get_template_directory_uri() . '/assets/js/script-name.js', array(), wp_get_theme()->get( 'Version' ), true );
    wp_enqueue_style( 'style-handle', get_template_directory_uri() . '/assets/css/style-name.css', array(), wp_get_theme()->get( 'Version' ) );
},
```

# Step 2. Create the Block Template

- Create the render template at `template-parts/blocks/block-name.php`
- Add the following snippet at the top of the file — it handles the Gutenberg editor preview and wraps the future block implementation

```php
<?php
/**
 * Block: {Block Title}
 *
 * @package {textdomain}
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
    echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

    // Future block implementation

endif;
?>
```

# Step 3. Create the Block Stylesheet

- Create a new stylesheet at `src/css/blocks/block-name.css` with the following content:

```css
@layer components {
    /* block-name styles */
}
```

- Register the new stylesheet in `src/css/main.css` by adding an `@import` under the **/* Content Blocks */** section, in alphabetical order. If the section does not exist yet, create it.

```css
/* Content Blocks */
@import './blocks/block-name.css';
```
