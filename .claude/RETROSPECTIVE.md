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

---

## Step 02 — Design Tokens

### Issue 1: Vite build infrastructure missing from Step 01 scope

**What happened:** Step 02 requires `src/css/main.css` to exist and `make build` to pass. Neither was possible because `package.json`, `vite.config.js`, and the entire `src/` directory were never created in Step 01.

**Root cause:** Step 01's plan listed Vite manifest-based asset enqueueing as an acceptance criterion and even wrote `functions.php` referencing `assets/.vite/manifest.json` — but never listed `package.json`, `vite.config.js`, or `src/` in its file scope. The Node/Vite side of the stack was silently omitted.

**Fix:** Created the missing infrastructure as part of Step 02: `package.json` (Tailwind v4, Vite 6, Alpine.js), `vite.config.js`, `src/js/critical.js`, `src/js/main.js`, and all empty `src/css/` subdirectories with `.gitkeep` files.

**For future steps / plan updates:** Step 01's "Files to Create" table should include `package.json` and `vite.config.js`. The acceptance criterion "Vite manifest is read at runtime" cannot be verified without a working build.

---

### Issue 2: pnpm v11 blocks esbuild build scripts by default

**What happened:** `pnpm install` exited with `ERR_PNPM_IGNORED_BUILDS` — esbuild (a Vite dependency) has a postinstall script that pnpm v11 blocks unless explicitly approved.

**Root cause:** pnpm v11 introduced a security policy that requires opt-in approval for any package that runs build scripts during install.

**Fix:** Run `pnpm approve-builds esbuild` once. pnpm writes the approval to `pnpm-workspace.yaml`:

```yaml
allowBuilds:
  esbuild: true
```

This file must be committed alongside `pnpm-lock.yaml` so CI and other developers do not hit the same block.

**Do not:** Add `pnpm.onlyBuiltDependencies` to `package.json` — that field was removed in pnpm v11 and is silently ignored.

---

### Issue 3: Design token placeholder values not surfaced to user

**What happened:** The step plan notes that token values are "placeholders — the intent is the structure and naming convention, not the exact hue." I took this literally and created the tokens without asking the user about their colour or font preferences.

**Root cause:** The plan's note was written for a reusable boilerplate context, but the user reasonably expected a consultation before values were committed.

**Fix (process):** At the start of any step that introduces visible design values — colours, fonts, spacing — surface the placeholder vs. opinionated-defaults decision to the user before writing files. A one-sentence ask ("the plan treats these as placeholders; do you want to set real values now or continue with generics?") is enough.

**Outcome:** User confirmed placeholders are acceptable for now; real values will be chosen before Step 05 (the first step that produces visible UI).
