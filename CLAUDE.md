# AI-Driven Boilerplate — WordPress Theme

WordPress boilerplate theme for IT companies / freelancers. Classic PHP theme with ACF, Gutenberg blocks backed by ACF fields, Tailwind CSS, Alpine.js, and Vite.

## Tech Stack

| Layer | Choice |
|---|---|
| PHP | 8.2+ |
| CSS | Tailwind CSS v4 |
| JS | Vanilla JS + Alpine.js |
| Build | Vite |
| CMS fields | Advanced Custom Fields (ACF Pro) |
| Editor | Gutenberg blocks with ACF field backing |
| Linting | ESLint (JS), PHPCS with WordPress coding standards (PHP) |

## Directory Structure

```
ai-driven-boilerplate/
├── assets/                      # Compiled and static files served to the browser
│   ├── js/                      # Minified JS output + standalone JS files loaded directly on the site
│   ├── css/                     # Minified CSS output + standalone CSS files loaded directly on the site
│   ├── fonts/                   # Locally hosted font files
│   └── media/                   # Static media used in the theme
│       ├── icons/               # Icons used across the site
│       └── block-preview/       # Block preview images shown in the Gutenberg editor
├── acf-json/                    # ACF Local JSON — all field group and options page definitions, auto-saved by the ACF admin UI, never edited manually
├── inc/                         # All PHP functionality, loaded via functions.php
│   ├── register-blocks.php      # Register ACF Gutenberg blocks and block-related hooks
│   ├── register-cpt.php         # Register Custom Post Types
│   ├── register-taxonomy.php    # Register custom taxonomies
│   ├── register-shortcodes.php  # Register shortcodes
│   ├── functions-design.php     # Design-related functions (menus, image sizes, theme supports)
│   ├── functions-data.php       # Data-related functions (custom queries, data transformation)
│   ├── functions-helpers.php    # General-purpose helper functions
│   ├── functions-form.php       # Form handling, logic, and validation
│   └── functions-ajax.php       # WordPress AJAX handlers and their add_action registrations
├── template-parts/              # Reusable template parts, included via get_template_part()
│   ├── blocks/                  # Template parts rendered inside Gutenberg blocks
│   ├── components/              # Reusable UI components (buttons, cards, badges, etc.)
│   ├── forms/                   # Form templates
│   ├── layout/                  # Global layout section templates (header, footer, etc.)
│   └── pages/                   # Page-specific template parts
├── src/                         # Source files for styles and scripts, compiled by Vite
│   ├── js/                      # JavaScript source files
│   │   ├── main.js              # Main JS entry point — non-critical scripts (deferred / delayed)
│   │   ├── critical.js          # Critical JS entry point — blocking scripts loaded in <head>
│   │   └── scripts/             # Standalone script files imported by entry points
│   └── css/                     # Tailwind CSS source files
│       ├── main.css             # Main CSS entry point
│       ├── blocks/              # Styles scoped to individual Gutenberg blocks
│       ├── components/          # Styles for reusable UI components
│       ├── forms/               # Form element styles
│       ├── layout/              # Styles for global layout sections (header, footer, etc.)
│       ├── navigation/          # Navigation and menu styles
│       ├── pages/               # Page-specific styles
│       └── variables/           # CSS custom properties and Tailwind @theme design tokens
├── style.css                    # Required WordPress theme header (no actual styles — all CSS lives in src/)
├── 404.php                      # 404 error page template
├── archive.php                  # Archive template (categories, tags, CPT archives)
├── footer.php                   # Site footer markup
├── functions.php                # Theme setup, asset enqueuing, and includes of all inc/ files
├── header.php                   # Site header markup
├── page.php                     # Default page template
├── search.php                   # Search results template
├── single.php                   # Single post template
├── vite.config.js               # Vite build configuration
├── package.json                 # Node dependencies and pnpm scripts
├── phpcs.xml                    # PHP_CodeSniffer ruleset (WordPress coding standards)
├── .eslintrc.json               # ESLint configuration
└── Makefile                     # Project task runner (install, watch, build, zip, lint)
```

## Development Commands

All commands are run via `make`. Run `make help` to see all available targets.

```bash
make install     # Install all dependencies (pnpm + composer)
make update      # Update dependencies within declared version ranges
make watch       # Start the Vite dev server with live-reload
make build       # Build production assets via Vite
make zip         # Build and package a deployable theme zip into ./dist
make check       # Run all linters (PHPCS)
make fix         # Auto-fix all linting issues (PHPCBF)
```

## ACF Conventions

- All field groups, options pages, and block field definitions are created in the **WP admin UI** — never written in PHP code.
- ACF saves definitions automatically to `acf-json/` as Local JSON — this directory is version-controlled.
- Never edit files in `acf-json/` manually.
- Access options page fields with `get_field( 'field_name', 'option' )`.

### Field type reference

Every field is loaded with `get_field()`. No ACF loop functions (`have_rows()`, `the_row()`, `get_sub_field()`) — the return value of `get_field()` contains everything needed.

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

**Image** — returns an associative array. Always render via `wp_get_attachment_image()` using the `ID` key — never write a raw `<img>` tag:

```php
$image = get_field( 'image' );
if ( ! empty( $image ) ) :
    echo wp_get_attachment_image( $image['ID'], 'full' );
endif;
```

**Link** — returns an associative array. Always destructure with a safe `_self` default for target:

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

**Repeater** — returns an array of rows. Each row is an associative array keyed by sub-field name. Never use `have_rows()` / `the_row()`:

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

## Block PHP Conventions

Use the `/register-acf-block` skill to scaffold a new block and `/build-acf-block` to implement it.

**Docblock** — always use this format at the top of every block template:

```php
<?php
/**
 * Block: {Block Title}
 *
 * @package {textdomain}
 */
```

**Field loading** — load all ACF fields at the top of the `else` block as named variables before any HTML. Never call `get_field()` inline in the markup.

```php
$block_title = get_field( 'title' );
$block_desc  = get_field( 'description' );
```

**Link fields** — always destructure into separate variables with a safe default for target:

```php
$cta_link = get_field( 'cta_button' );
if ( $cta_link ) {
    $cta_title  = $cta_link['title'];
    $cta_url    = $cta_link['url'];
    $cta_target = $cta_link['target'] ? $cta_link['target'] : '_self';
}
```

**Image fields** — load via `get_field()`, render via `wp_get_attachment_image()`. Never write a raw `<img>` tag:

```php
$image = get_field( 'image' );
```

**Guard clause** — always check required fields with `! empty()` before rendering. Never rely solely on ACF's required setting — it can fail silently and cause undefined PHP notices.

```php
if ( ! empty( $block_title ) && ! empty( $image ) ) :
    // block markup
endif;
```

**Output escaping** — escape everything that is output, without exception:

| Context | Function |
|---|---|
| Plain text | `esc_html()` |
| HTML attributes (incl. conditional classes) | `esc_attr()` |
| Rich text / HTML content | `wp_kses_post()` |
| URLs | `esc_url()` |

**Reusable components** — include via `get_template_part()` with an args array. See **Component Template Part Conventions** for how to build the component file itself.



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

**PHP / HTML interleaving** — use PHP open/close tags around HTML sections. Never build markup with `echo` string concatenation:

```php
// ✗ wrong
if ( $title ) {
    echo '<h1 class="block-title">' . esc_html( $title ) . '</h1>';
}

// ✓ correct
if ( $title ) : ?>
    <h1 class="block-title"><?php echo esc_html( $title ); ?></h1>
<?php endif; ?>
```

**Images** — always use `wp_get_attachment_image()` with the attachment ID. Never write a raw `<img>` tag — the WordPress function handles srcset, sizes, alt text, and lazy loading automatically:

```php
$image = get_field( 'image' );
if ( ! empty( $image ) ) : ?>
    <?php echo wp_get_attachment_image( $image['ID'], 'full' ); ?>
<?php endif; ?>
```

Replace `'full'` with the appropriate registered image size for the context.

## PHP Conventions

- Follow WordPress coding standards (PHPCS ruleset in `phpcs.xml`).
- Always include the text domain in translation functions: `__( 'Text', 'textdomain' )`.
- No business logic in templates — move to `inc/` and call from the template.

### File docblock

Every PHP file must open with a file-level docblock. The description format depends on the file location:

| Location | Format | Example |
|---|---|---|
| Root templates (`page.php`, `single.php`, …) | Plain description | `The template for displaying all pages.` |
| `inc/` files | Plain description | `Data-related functions and custom queries.` |
| `template-parts/pages/` | `Page: {Name}` | `Page: Testimonial` |
| `template-parts/layout/` | `Layout: {Name}` | `Layout: Header` |
| `template-parts/forms/` | `Form: {Name}` | `Form: Contact` |
| `template-parts/components/` | `Component: {Name}` | `Component: Button` — see **Component Template Part Conventions** |
| `template-parts/blocks/` | `Block: {Name}` | `Block: Hero` — see **Block PHP Conventions** |

```php
<?php
/**
 * Page: Testimonial
 *
 * @package {textdomain}
 */
```

### Image sizes

Custom image sizes are registered in `inc/functions-design.php` via `add_image_size()`. Use kebab-case names that describe the context:

```php
add_image_size( 'block-hero', 1440, 600, true );
add_image_size( 'card-thumbnail', 400, 300, true );
```

When calling `wp_get_attachment_image()`, always use the most appropriate registered size for the context — never default to `'full'` unless the image truly needs to be full resolution.

## Component Template Part Conventions

Component template parts live in `template-parts/components/`. They are called via `get_template_part()` with an args array and must never contain business logic — only presentation.

### Docblock

Use `Component:` (not `Block:`) and document every accepted arg with `@type` inside the `@param array $args` block:

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

### Args destructuring

Destructure all args at the top of the file as named variables using `??` for defaults. Never access `$args` directly in the markup:

```php
$label   = $args['label'] ?? '';
$url     = $args['url'] ?? '#';
$target  = $args['target'] ?? '_self';
$variant = $args['variant'] ?? 'primary';
$class   = $args['class'] ?? '';
```

**Boolean args** — use `! empty()`, not `?? false`:

```php
$disabled = ! empty( $args['disabled'] );
$checked  = ! empty( $args['checked'] );
```

### Computed class strings

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

### WordPress form helpers

Use WordPress built-in helpers for boolean HTML attributes — never echo `checked="checked"` or `disabled="disabled"` manually:

```php
<input type="checkbox" <?php checked( $checked ); ?> <?php disabled( $disabled ); ?>>
```

### Structure order

1. Docblock
2. Args destructuring (`$args['key'] ?? default`)
3. Computed values (class strings, conditionals)
4. Markup (PHP/HTML interleaved — no `echo` for markup)

## PHP Data Function Conventions

All data functions live in `inc/functions-data.php`. They **return data only** — never echo or print markup. Any HTML output belongs in a template part called via `get_template_part()`.

### Naming

- Prefix every function with the text domain, replacing hyphens with underscores — read it from `style.css` (`Text Domain: ai-driven-boilerplate` → prefix `ai_driven_boilerplate_`).
- Follow the prefix with a verb: `get_`, `fill_`, `construct_`, `attach_`, `build_`.

```php
function textdomain_get_related_posts( $post_id ) { ... }
function textdomain_fill_filter_with_unique( $objects, $field ) { ... }
```

### Docblock

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

### Guard clauses

Always guard `get_term()`, `get_terms()`, and `get_the_terms()` results before accessing properties. Never access `$result[0]` without first checking the result is valid:

```php
$state = get_the_terms( $post_id, 'state' );

if ( empty( $state ) || is_wp_error( $state ) ) {
    return null;
}

$state_object = $state[0];
```

Always use real booleans — never string `'true'` / `'false'` in query args:

```php
// ✓
'hide_empty' => true,

// ✗
'hide_empty' => 'true',
```

### `phpcs:ignore` usage

Add an inline `// phpcs:ignore` comment only on the exact line that triggers a known PHPCS false positive. Use it exclusively for `meta_query` and `tax_query` slow-query warnings — never to silence real problems:

```php
'meta_query' => array( // phpcs:ignore
    array( ... ),
),
```

## PHP AJAX Handler Conventions

All AJAX handlers live in `inc/functions-ajax.php`. They call data functions from `functions-data.php` for the actual queries — no query logic inside a handler.

### Handler structure

Always follow this order inside every handler:

1. **Nonce verification** — first line, fail with 403
2. **Sanitize** all `$_POST` inputs immediately after
3. **Validate** sanitized values — fail with 400 or 404 for bad input
4. **Query** data by calling functions from `functions-data.php`
5. **Respond** with `wp_send_json_success()` or `wp_send_json_error()` — never `echo` or `die()`

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

### Input sanitization

| Input type | Pattern |
|---|---|
| Integer | `(int) wp_unslash( $_POST['key'] )` |
| String | `sanitize_text_field( wp_unslash( $_POST['key'] ) )` |
| URL | `esc_url_raw( wp_unslash( $_POST['key'] ) )` |

Always check `isset()` and provide a safe default before casting or sanitizing.

### HTML in JSON responses

When the response payload contains HTML (e.g. rendered template parts), use `ob_start()` / `ob_get_clean()` to capture `get_template_part()` output as a string:

```php
ob_start();
get_template_part( 'template-parts/components/card', null, array( 'id' => $post_id ) );
$html = ob_get_clean();

wp_send_json_success( array( 'html' => $html ) );
```

### Hook registration

Register both `wp_ajax_` and `wp_ajax_nopriv_` immediately after the handler function — never in a separate location. Use `wp_ajax_nopriv_` for all handlers that must work for logged-out visitors (front-end AJAX).

## Tailwind Conventions

### Inline utilities vs custom classes

- **Up to 3 Tailwind utility classes** → write them inline in the HTML template.
- **4 or more classes** (especially when mixing layout, colour, border, spacing) → extract to a named custom class in the relevant CSS file and use `@apply`.

```html
<!-- ✓ 3 or fewer — inline is fine -->
<div class="w-full flex flex-col">

<!-- ✗ too many — extract to a class -->
<div class="w-full flex flex-col bg-white border border-gray-200 rounded-3xl shadow-md px-6 py-4">

<!-- ✓ extracted -->
<div class="my-card">
```

```css
/* src/css/components/my-card.css */
@layer components {
    .my-card {
        @apply w-full flex flex-col bg-white border border-gray-200 rounded-3xl shadow-md px-6 py-4;
    }
}
```

### Design tokens

All tokens are defined in `src/css/variables/` using Tailwind v4 `@theme {}` blocks. Never hardcode raw colour hex or shadow values in component CSS — always reference a token.

| File | Token pattern | Example |
|---|---|---|
| `color.css` | `--color-{palette}-{weight}` | `--color-blue-950` |
| `color.css` (alpha) | `--color-{palette}-{weight}-{opacity}` | `--color-blue-500-20` |
| `color.css` (semantic) | `--color-text-{role}` | `--color-text-default` |
| `shadow.css` | `--shadow-{size}` | `--shadow-lg` |
| `typography.css` | `--font-{name}` | `--font-figtree` |

Custom breakpoints are also defined in `@theme {}`: `--breakpoint-{name}: {value}`.

### Typography scale

Typography utilities are defined with `@utility {}` (not `@layer components`) so they compose with other Tailwind utilities:

```css
@utility text-heading-2 {
    @apply font-fraunces text-2xl font-semibold tracking-tight leading-6;
    @apply md:text-[32px] md:leading-8;
    @apply lg:text-4xl lg:leading-10;
}
```

Available scales: `text-heading-{1–6}`, `body-{xl|lg|md|sm}-{normal|medium|semibold}`, `text-paragraph`, `text-link`.

### @layer usage

| Layer | What goes in it |
|---|---|
| `@layer base` | Global HTML/body styles, `@font-face`, scrollbar, wysiwyg defaults |
| `@layer components` | All named UI classes (layout, components, blocks, pages) |
| `@utility` | Typography scale utilities that must compose with other utilities |

### Responsive variants

Split responsive breakpoint variants onto separate `@apply` lines for readability:

```css
.my-class {
    @apply flex flex-col gap-4;
    @apply md:flex-row md:gap-6;
    @apply lg:gap-10;
}
```

### Pseudo-classes

Define `:hover`, `:active`, `:focus-visible`, `:disabled` as separate CSS rules inside the component block — do not use `hover:` / `focus:` inline utilities inside `@apply`:

```css
.btn-primary { @apply bg-brand-100 text-blue-900; }
.btn-primary:hover { @apply bg-brand-300 text-white; }
.btn-primary:active { @apply bg-brand-500 text-white; }
```

### CSS nesting

Use native CSS nesting for child elements and sub-parts of a component:

```css
.my-card {
    @apply flex flex-col gap-4;
    .my-card-title { @apply text-heading-3; }
    .my-card-body  { @apply body-lg-normal; }
}
```

### Naming

- All class names in kebab-case.
- **Components:** base class + modifier suffix (`.btn`, `.btn-primary`, `.btn-lg`).
- **State modifiers:** BEM-style double-dash suffix (`.checkbox--error`, `.checkbox--disabled`).
- **Blocks:** root class matches the block name; sub-elements use `.block-name-{element}` (e.g. `.wwa-wrapper`, `.wwa-headline`, `.wwa-image`).
- **Layout sections:** descriptive prefix (`.header-wrapper`, `.desktop-header`, `.footer-top-section`).
- **Pages:** page-name prefix (`.article-main`, `.article-post-sidebar`).

### Contextual overrides

When a component renders differently inside a specific parent, scope the override with the parent selector rather than creating a new class:

```css
/* ✓ contextual override */
.state-sidebar .cta-bar {
    @apply flex-col;
}

/* ✗ don't create a new class just for a context */
.cta-bar-sidebar { ... }
```

### Fonts

- Register fonts via `@font-face` inside `@layer base {}` in `src/css/variables/typography.css`.
- Always use WOFF2 format and `font-display: swap`.
- Path must point to `../../assets/fonts/` (relative from the CSS file location).
- Reference fonts in `@theme {}` as `--font-{name}` tokens so Tailwind utilities (`font-figtree`) are auto-generated.

### Safelist (dynamic classes)

Use `@source inline()` in `main.css` for Tailwind classes generated dynamically from PHP or JS (e.g. classes built from ACF field values):

```css
@source inline("bg-blue-100 bg-red-100 border-blue-200");
```

### main.css import order

1. `@import "tailwindcss"`
2. `@source inline(...)` safelist
3. `variables/` (color → typography → shadow)
4. `layout/`
5. `navigation/`
6. `components/`
7. `forms/`
8. `@theme {}` for custom breakpoints
9. `blocks/` (alphabetical)
10. `pages/` (alphabetical)
11. `@layer base {}` global base styles

## JavaScript Conventions

### Entry points

`src/js/main.js` (deferred / delayed) and `src/js/critical.js` (blocking, loaded in `<head>`) are the two entry points. Each imported script gets a single-line comment describing its purpose:

```js
// Modal Windows.
import './scripts/handler-modal.js';

// Mobile Navigation.
import './scripts/handler-mobile-nav.js';
```

### File naming

All script files live in `src/js/scripts/` and use kebab-case names with a descriptive prefix:

| Prefix | Purpose | Example |
|---|---|---|
| `handler-` | UI interaction logic (menus, modals, filters) | `handler-modal.js` |
| `design-helper-` | Layout/visual helpers with no business logic | `design-helper-sticky-headline.js` |
| `{page-name}-` | Page-specific scripts | `city-ajax-loader.js` |

### Script structure

**Complex or stateful scripts** — wrap in an IIFE with `'use strict'`:

```js
( function () {
    'use strict';

    const OPEN_CLASS = 'is-open';

    function init() { ... }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} () );
```

**Simple scripts** — plain code inside a `DOMContentLoaded` listener is fine:

```js
document.addEventListener( 'DOMContentLoaded', () => {
    const el = document.querySelector( '.my-element' );
    if ( ! el ) return;
    // ...
} );
```

Always use the `readyState` guard pattern (not a bare `DOMContentLoaded`) for scripts that may be injected after parse:

```js
if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', init );
} else {
    init();
}
```

### No jQuery

All scripts use vanilla JS DOM APIs. Do not introduce jQuery.

### Constants

Define CSS class strings and selectors as named constants in SCREAMING_SNAKE_CASE at the top of the file:

```js
const OPEN_CLASS   = 'is-open';
const HIDDEN_CLASS = 'hidden';
const FORM_SELECTOR = 'form.my-form';
```

### DOM guard clauses

Always guard every `querySelector` / `getElementById` result before use. Return early if the element is absent — the script may run on pages where the element does not exist:

```js
const container = document.getElementById( 'facilities' );
if ( ! container ) return;
```

### JS hooks vs CSS classes

Use `data-*` attributes as JS hooks — never use CSS classes as JS selectors. CSS classes are for styling; `data-*` attributes signal JS behaviour:

```html
<!-- ✓ JS hook via data attribute -->
<div data-filter-wrapper>

<!-- ✗ don't use CSS class as JS hook -->
<div class="filter-wrapper">
```

### State management

- **Single-instance state** — module-level `let` variables inside the IIFE or closure.
- **Multi-instance state** (e.g. multiple widgets on one page) — `WeakMap` keyed by the DOM element.
- **Double-init prevention** — set a `data-*-init` attribute on the element after setup and skip elements that already have it.

```js
document.querySelectorAll( '.my-widget:not([data-init])' ).forEach( el => {
    el.dataset.init = '1';
    setup( el );
} );
```

### AJAX

Use the native `fetch` API with `async/await`. Never use jQuery AJAX.

```js
async function fetchData( ajaxUrl, nonce ) {
    const formData = new FormData();
    formData.append( 'action', 'my_action' );
    formData.append( 'nonce', nonce );

    const response = await fetch( ajaxUrl, { method: 'POST', body: formData } );
    if ( ! response.ok ) throw new Error( `Request failed: ${ response.status }` );

    const json = await response.json();
    if ( ! json.success ) throw new Error( json.data?.message || 'Request unsuccessful' );

    return json.data;
}
```

Use `AbortController` to cancel an in-flight request when a new one starts:

```js
let inFlightController = null;

async function load() {
    if ( inFlightController ) inFlightController.abort();
    inFlightController = new AbortController();
    // pass signal: inFlightController.signal to fetch
}
```

### PHP → JS data

Pass AJAX URLs, nonces, and other PHP-side values via `data-*` attributes on the container element. Do **not** use `wp_localize_script` globals:

```php
<div id="facilities"
     data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
     data-nonce="<?php echo esc_attr( wp_create_nonce( 'my_nonce' ) ); ?>">
```

```js
const ajaxUrl = container.dataset.ajaxUrl;
const nonce   = container.dataset.nonce;
```

### Global exposure

Avoid attaching functions to `window`. Only expose a re-init hook when the script must be called again after AJAX-injected HTML:

```js
window.myWidgetInit = initAll;
```

## Alpine.js Conventions

Alpine.js is **not the default** — vanilla JS is. Only use Alpine when it is explicitly specified for a given project or component.

If Alpine is used, scripts follow the same file and import conventions as vanilla JS scripts (`src/js/scripts/`, imported by the relevant entry point).

## Vite + WordPress Integration

Source files live in `src/` — Vite compiles them to `assets/css/` and `assets/js/`. The Vite manifest (`.vite/manifest.json` inside `assets/`) maps entry point paths to their versioned output filenames.

### Asset enqueuing

Assets are enqueued in `functions.php` by reading the Vite manifest. A helper reads the manifest once and returns the versioned URL for a given entry:

```php
/**
 * Return the versioned asset URL for a Vite entry point.
 *
 * @param string $entry Entry point path as defined in vite.config.js (e.g. 'src/css/main.css').
 * @return string Full URL to the compiled asset, or empty string if not found.
 */
function textdomain_vite_asset( $entry ) {
    static $manifest = null;

    if ( null === $manifest ) {
        $path     = get_template_directory() . '/assets/.vite/manifest.json';
        $manifest = file_exists( $path ) ? json_decode( file_get_contents( $path ), true ) : array(); // phpcs:ignore
    }

    if ( ! isset( $manifest[ $entry ] ) ) {
        return '';
    }

    return get_template_directory_uri() . '/assets/' . $manifest[ $entry ]['file'];
}
```

Enqueue the two JS entry points and the main CSS inside a `wp_enqueue_scripts` hook:

```php
add_action( 'wp_enqueue_scripts', function () {
    // Main stylesheet.
    wp_enqueue_style( 'theme-main', textdomain_vite_asset( 'src/css/main.css' ), array(), null );

    // Critical JS — loaded in <head>, blocking.
    wp_enqueue_script( 'theme-critical', textdomain_vite_asset( 'src/js/critical.js' ), array(), null, false );

    // Main JS — loaded in <footer>, deferred.
    wp_enqueue_script( 'theme-main', textdomain_vite_asset( 'src/js/main.js' ), array(), null, true );
} );
```

Standalone files in `assets/js/` or `assets/css/` that are not compiled by Vite (e.g. third-party scripts) are enqueued directly with a theme version:

```php
wp_enqueue_script( 'my-lib', get_template_directory_uri() . '/assets/js/my-lib.js', array(), wp_get_theme()->get( 'Version' ), true );
```

## Git Workflow

### Branch structure

| Branch | Purpose |
|---|---|
| `main` | Production-ready code — only updated via PR from `develop` for releases |
| `develop` | Integration branch — base for all feature/fix branches |
| `feat/*`, `fix/*`, `chore/*` … | Short-lived branches, one per task |

Never commit directly to `main` or `develop`.

### Starting a task

Before touching any files:

1. Confirm the working branch is `develop` (or switch to it)
2. Pull the latest: `git pull origin develop`
3. Create a new branch named after the task — name is given in the plan or by the user:

```bash
git checkout -b feat/hero-block
```

**Branch naming** — use the commit type as prefix, followed by a short kebab-case description:

```
feat/hero-block
feat/button-component
fix/mobile-nav-outside-click
chore/acf-json-sync
build/vite-config
docs/claude-md-conventions
```

### During a task

- Commit in logical increments — don't wait until everything is done
- Follow the **Commit Conventions** below for every commit message
- Keep the branch focused — one feature or fix per branch

### Finishing a task

When a task is complete:

1. Make a final commit with any remaining changes
2. Push the branch and open a PR into `develop` using `gh pr create`
3. PR title follows the same conventional commit format as the commits inside it
4. PR description summarises what changed and why — one bullet point per logical change
5. **Never merge the PR** — the user reviews and merges manually

### My behaviour (Claude)

- **Before starting any implementation** — check the current branch. If it is `main` or `develop`, stop and ask the user to create a feature branch first.
- **Branch name not provided** — suggest a name following the `type/short-description` convention and ask the user to confirm before creating it.
- **When a task feels complete or a new task is mentioned** — remind the user to merge the current branch back to `develop` before continuing, and ask for the name of the next branch.
- **Never merge PRs** — always leave merging to the user.
- **Never commit to `main` or `develop`** directly — always work on a feature branch.

## Commit Conventions

Follow the [Conventional Commits](https://www.conventionalcommits.org/) format:

```
<type>(<scope>): <subject>
```

- **Subject** — imperative mood, lowercase, no period, max 72 characters
- **Scope** — optional, describes what area was changed (e.g. `hero-block`, `navigation`, `button`)

```
feat(hero-block): add cta link field support
fix(mobile-nav): close menu on outside click
```

### Commit types

| Type | When to use |
|---|---|
| `feat` | New block, component, CPT, taxonomy, shortcode, page template, or any new functionality |
| `fix` | Bug fix — broken markup, wrong field, JS error, CSS regression |
| `style` | Visual / CSS-only change with no logic change |
| `refactor` | Code restructured without changing behaviour (no new feature, no bug fix) |
| `chore` | ACF JSON sync, dependency updates, config file changes (`phpcs.xml`, `.eslintrc.json`, `vite.config.js`) |
| `build` | Makefile, `package.json`, Vite, or PHPCS setup changes |
| `docs` | `CLAUDE.md`, `README.md`, or inline documentation only |
| `revert` | Reverting a previous commit |

### Examples

```
feat(cta-block): scaffold block registration and template
feat(button): add outline variant
fix(hero-block): guard empty image field before rendering
style(footer): adjust spacing on mobile breakpoint
refactor(functions-data): extract address builder to helper function
chore: sync ACF JSON after adding timeline block fields
chore: update pnpm dependencies
build: add zip target exclusions to Makefile
docs: add component template part conventions to CLAUDE.md
```

## Out of Scope

- Page builders (Elementor, WPBakery)
- Full Site Editing (FSE) / block themes
- Headless / REST-only setups

## Key Decisions

- **ACF fields via admin UI, not code** — all definitions are saved as Local JSON to `acf-json/` and version-controlled from there.
- **Tailwind v4 CSS-first config** — no `tailwind.config.js`; tokens live in `src/css/variables/`.
- **Always escape all output** — no exceptions, even for values that appear safe (e.g. conditional class strings).
