---
name: build-acf-block
description: Implement an ACF Gutenberg block based on its ACF field definition — loads fields, builds the template, and adds styles.
---

# Step 1. Find the ACF Field Definition

- Search the `acf-json/` directory for the field group whose `location` targets this block
- The block name in the location value matches the registered block name (e.g. `acf/textdomain-block-name`)

```json
"location": [
    [
        {
            "param": "block",
            "operator": "==",
            "value": "acf/textdomain-block-name"
        }
    ]
]
```

- Read all fields from the definition — note each field's `name`, `type`, and whether it is required

**Important:** If no matching definition is found, stop and notify the user that the ACF field group is missing before continuing.

# Step 2. Load Fields in the Block Template

- Open the block template at `template-parts/blocks/block-name.php`
- Inside the `else` block (after the preview screenshot check), load all ACF fields at the top as named variables using `get_field()` before any HTML output
- Follow all conventions in the **Block PHP Conventions** section of `CLAUDE.md`

```php
$block_title = get_field( 'title' );
$block_desc  = get_field( 'description' );
```

For **link fields**, always destructure into separate variables with a safe default for target:

```php
$cta_link = get_field( 'cta_button' );
if ( $cta_link ) {
    $cta_title  = $cta_link['title'];
    $cta_url    = $cta_link['url'];
    $cta_target = $cta_link['target'] ? $cta_link['target'] : '_self';
}
```

# Step 3. Build the Block Structure

**Important:** The desired structure and functionality must be described by the user or defined in the plan. Ask for everything that is missing before writing any markup.

**PHP template** (`template-parts/blocks/block-name.php`):
- Guard required fields with `! empty()` before rendering — never trust ACF's required setting alone
- Escape all output without exception (`esc_html()`, `esc_attr()`, `wp_kses_post()`, `esc_url()`)
- Use `get_template_part()` with an args array for any reusable components
- See **Block PHP Conventions** in `CLAUDE.md` for the full reference

**Stylesheet** (`src/css/blocks/block-name.css`):
- All block-specific CSS goes here
- See **Tailwind Conventions** in `CLAUDE.md` for the full reference

**JavaScript** (`src/js/scripts/script-name.js`):
- Only create if the block requires JavaScript
- Follow all conventions in the **JavaScript Conventions** section of `CLAUDE.md`
- Import the script in `src/js/main.js` for deferred scripts, or `src/js/critical.js` for blocking scripts that must be ready on load

**Important:** If any of the expected files are missing, run `/register-acf-block` to identify what needs to be scaffolded first, then inform the user.
