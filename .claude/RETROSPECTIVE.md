# Build Retrospective

---

## Step 01 — Theme Foundation

### Issue 1: `Squiz.Commenting.FileComment.Missing` on `functions.php`

**What happened:** PHPCS reported "Missing file doc comment" at line 1 even though a `/** */` docblock was present.

**Root cause:** The `Squiz.Commenting.FileComment` sniff fires "Missing" not only when the docblock is absent, but also when the **first token after the docblock's closing `*/`** is in an explicit ignore list that includes `T_REQUIRE_ONCE`, `T_FUNCTION`, `T_CLASS`, `T_INCLUDE`, etc. PHPCS interprets the docblock as belonging to that following token (e.g. a function docblock), not the file, and therefore reports the file docblock as missing.

In `functions.php` the first thing after the file docblock was `require_once` — triggering the sniff.
In `inc/functions-design.php` the first thing after the file docblock was another `/**` (the function docblock) — `T_DOC_COMMENT_OPEN_TAG` is NOT in the ignore list, so no error.

**Fix:** Add the standard WordPress ABSPATH guard immediately after the file docblock, before any `require_once` calls. `T_IF` is not in the ignore list, so the sniff passes.

```php
<?php
/**
 * File description.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once ...;
```

**Apply this pattern to:** Any PHP file that has `require_once` / `include` / a class / a function as the very first code after the file docblock AND has no preceding `/**` function docblock to act as a buffer.

---

### Issue 2: `WordPress.WP.EnqueuedResourceParameters.MissingVersion` warnings

**What happened:** Three warnings fired for `wp_enqueue_style()` and `wp_enqueue_script()` calls that pass `null` as the version argument.

**Root cause:** The sniff flags any enqueue call without an explicit version string. We intentionally pass `null` (no version query string) because Vite already hashes asset filenames — adding a version query string would double-version the cache key.

**Fix:** Added `<severity>0</severity>` for this specific sniff in `phpcs.xml` at the project level, with an explanatory comment. Do NOT use phpcs:ignore inline — the reason applies to all future Vite-managed assets too.

```xml
<!-- Vite-compiled assets use null version: the manifest hashes the filename -->
<rule ref="WordPress.WP.EnqueuedResourceParameters.MissingVersion">
    <severity>0</severity>
</rule>
```

---

### Issue 3: Reserved keyword `class` as a function parameter name

**What happened:** PHPCS warned on `function aidriven_get_svg_icon( $icon_name, $class = '' )` — `class` is a PHP reserved keyword.

**Fix:** Rename to `$css_class`. Update all uses in the function body.

**Pattern to remember:** Never use `$class`, `$interface`, `$function`, `$match`, `$fn`, etc. as parameter names. Prefer descriptive variants: `$css_class`, `$wrapper_class`.

---

### Issue 4: `.gitignore` missing from step plan

**What happened:** `vendor/` (Composer) was not gitignored and would have been staged accidentally.

**Fix:** Created `.gitignore` as part of this step with: `vendor/`, `node_modules/`, `dist/`, compiled asset dirs (`assets/css/`, `assets/js/`, `assets/.vite/`), OS/editor noise.

**For future steps:** `.gitignore` should be listed in the scope of Step 01 (foundation) because it is a prerequisite for correct version control of all subsequent steps. Check for it at the start of any project that lacks one.

---

### Issue 5: `composer.json` not in step plan scope

**What happened:** The step plan listed `make check` as an acceptance criterion but did not list `composer.json` as a file to create. Without it, `vendor/bin/phpcs` cannot exist and `make check` will always fail.

**Fix:** Created `composer.json` with `squizlabs/php_codesniffer`, `wp-coding-standards/wpcs`, and `dealerdirect/phpcodesniffer-composer-installer` as dev dependencies.

**For future steps:** When a step's acceptance criteria include `make check` or any linting command, verify that `composer.json` exists before writing PHP files.
