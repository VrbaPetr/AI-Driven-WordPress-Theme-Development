# Future Implementation Ideas

---

## Missing Standard Features

Identified by auditing the current codebase. All items below are absent from the theme today.

---

### 1. Skip to Main Content Link ⚠️ WCAG violation

**What:** A visually hidden `<a href="#main-content">Skip to main content</a>` link as the very first element inside `<body>`. It becomes visible on `:focus` so keyboard users can bypass the header navigation.

**Why it's needed:** WCAG 2.1 AA criterion 2.4.1 (Bypass Blocks) — the theme claims AA compliance but this link is absent, making it non-compliant. It is the simplest fix in this list.

**Implementation:** One line in `header.php` + a utility class in `src/css/layout/header.css`. The link targets `id="main-content"` which must be added to the `<main>` element in each page template.

---

### 2. Gutenberg Editor Color Palette

**What:** Register the theme's brand color tokens in the Gutenberg block editor via `add_theme_support( 'editor-color-palette', [...] )` in `inc/functions-design.php`.

**Why it's needed:** Without this, editors see WordPress's default 14-color palette when setting text or background color in any core block. The theme has a carefully designed token system in `src/css/variables/colors.css` that editors never see, leading to off-brand color choices.

**Implementation notes:**
- Read the CSS token file or hardcode the token values into the `editor-color-palette` array — token name, label, and hex value per entry.
- Add `add_theme_support( 'disable-custom-colors' )` alongside it to prevent editors from entering arbitrary hex values outside the palette.
- Also add `add_theme_support( 'editor-gradient-presets', [] )` with an empty array to remove the default gradient options unless theme gradients are defined.

---

### 3. Google Tag Manager / `wp_body_open` Integration Point

**What:** A hooked action on `wp_body_open` that outputs the GTM `<noscript>` iframe snippet, and a corresponding `<head>` snippet via `wp_head`. The GTM container ID is stored as an ACF options field.

**Why it's needed:** Every agency client uses Google Tag Manager. Currently there is no integration point — developers have to manually edit `header.php` or `functions.php` per deployment. A GTM ID field in options makes it a one-field setup.

**Suggested ACF fields** (new section `group_ss_analytics.json`):
- `gtm_container_id` (text — e.g. `GTM-XXXXXX`)
- `enable_gtm` (true/false — allows disabling without clearing the ID)

**Implementation notes:**
- Output the `<head>` script via `add_action( 'wp_head', ... , 1 )` (priority 1 = as early as possible).
- Output the `<noscript>` iframe via `add_action( 'wp_body_open', ... )`.
- Only output when `enable_gtm` is true and `gtm_container_id` is non-empty.
- Escape the container ID with `esc_attr()` — it is injected into a URL string.

---

### 4. Back to Top Button

**What:** A small button fixed to the bottom-right corner that scrolls the page back to top. Hidden until the user scrolls past a threshold, then fades in.

**Why it's needed:** Standard on marketing and long-form pages. The theme has no scroll utility of any kind.

**Implementation notes:**
- Rendered in `footer.php` as a single `<button>` element, always in the DOM.
- Visibility controlled by a CSS class (`.is-visible`) toggled by `navigation.js` on scroll — consistent with how the sticky header's `.is-scrolled` class already works.
- Smooth scroll to top via `window.scrollTo({ top: 0, behavior: 'smooth' })`.
- An optional toggle in site options (`show_back_to_top` true/false) — or always on since it is unintrusive.
- Use the `arrow-right.svg` icon rotated 270° — no new icon needed.

---

### 5. Blog Post Enhancements

Three small features for `single.php` that readers expect on professional blogs.

#### 5a. Estimated Reading Time

**What:** "5 min read" displayed near the post meta (author, date). Calculated from the post word count.

**Implementation:** A helper function in `inc/functions-helpers.php` — `ai_driven_reading_time( $post_id )` — divides `str_word_count( strip_tags( get_the_content() ) )` by 200 (average WPM) and returns a rounded minute count.

#### 5b. Reading Progress Bar

**What:** A thin bar fixed to the top of the viewport that fills from 0 % to 100 % as the reader scrolls through the post content.

**Implementation:** A `<div class="reading-progress-bar">` injected into `single.php`. Width updated via a scroll listener in `main.js` — `( scrollTop / ( docHeight - windowHeight ) ) * 100`. Only initialised on `single.php` (guard with `document.body.classList.contains('single-post')`).

#### 5c. Table of Contents

**What:** An auto-generated list of anchor links built from the `<h2>` and `<h3>` tags inside the post content. Shown as a sticky sidebar card or inline box above the content on long posts.

**Implementation notes:**
- PHP function in `inc/functions-helpers.php` parses `get_the_content()` with a regex to extract headings and build a `<nav>` list with auto-generated `id` attributes.
- An ACF field on the post (`show_toc` true/false, or auto-show when heading count ≥ 3) controls visibility.
- Smooth scroll to each heading on click.
- The heading `id` attributes are injected into the rendered content via a `the_content` filter — same pattern used by many WP TOC plugins.

---

### 6. Custom Login Page

**What:** A branded replacement for the default `wp-login.php` screen — theme logo (from options), brand background colour, and the default WP login form unstyled underneath.

**Why it's needed:** A standard agency deliverable. The default WordPress login page is visually jarring when handed to a client.

**Implementation notes:**
- Hook into `login_enqueue_scripts` to enqueue a dedicated `assets/css/login.css` stylesheet (compiled separately by Vite or written as a standalone file in `assets/css/`).
- Hook into `login_headerurl` to return the site URL instead of wordpress.org.
- Hook into `login_headertext` to return the site name.
- Logo sourced from the ACF options field `company_logo` (already exists in `group_ss_company_info.json`).
- No new template file — WP's login form is customised entirely via hooks and CSS.

---

### 7. Maintenance / Coming Soon Page

**What:** A full-screen branded page shown to logged-out visitors when the site is in "maintenance mode". Logged-in admins see the site as normal.

**Implementation notes:**
- A toggle in ACF site options: `maintenance_mode` (true/false) and `maintenance_message` (textarea).
- Implemented via a `template_redirect` hook in `inc/functions-helpers.php` — if enabled and current user is not an admin, load a dedicated template and `exit`.
- The template (`coming-soon.php` at theme root) is a standalone full-page HTML document — no header/footer includes — with logo, headline, message, and optionally the contact email from options.
- Returns HTTP `503` with `Retry-After` header so search engines don't index the page.

---

### 8. Smooth Scroll Behaviour

**What:** Smooth animated scrolling for all same-page anchor links (`href="#section-id"`).

**Why it's needed:** The theme has anchor links (breadcrumb schema, TOC, back to top) but no smooth scroll. Native CSS `scroll-behavior: smooth` is one line but gives no control over offset. A JS solution accounts for the sticky header height.

**Implementation notes:**
- Add `scroll-behavior: smooth` to `html` in `main.css` as a baseline.
- A small script in `src/js/scripts/` intercepts clicks on `a[href^="#"]`, calculates the sticky header offset (`site-header` height), and calls `window.scrollTo()` with the corrected position.
- Respect `prefers-reduced-motion: reduce` — skip smooth scroll for users who have requested reduced motion.

---

### 9. Print Styles

**What:** Basic `@media print` styles that hide the header, footer, navigation, buttons, and sidebar; set black text on white; and ensure content is readable on paper.

**Why it's needed:** Often forgotten, but professional deliverable for client sites. Contact pages and blog posts are the most commonly printed pages.

**Implementation:** A `src/css/pages/print.css` file imported into `main.css` under a `@media print` layer. Key rules: hide `.site-header`, `.site-footer`, `.back-to-top`, `[role="navigation"]`, `.wp-block-button`; set `body { color: #000; background: #fff; font-size: 12pt; }`.

---

## Icon Library Expansion

The current icon set (`assets/media/icons/`) contains only 10 icons. The following library should be built out for full IT agency / corporate use.

### Currently available
`triangle-alert` · `circle-check` · `circle-info` · `circle-x` · `email` · `github` · `instagram` · `linkedin` · `twitter` · `dribbble`

### Format conventions
- Brand / tech logos — `fill="currentColor"`, single `<path>`, `viewBox="0 0 24 24"`
- UI / general icons — `fill="none"`, `stroke="currentColor"`, `viewBox="0 0 24 24"`
- All icons: `aria-hidden="true"`, filename in `kebab-case`

---

### Social & Communication

| Filename | Icon |
|---|---|
| `facebook.svg` | Facebook |
| `x.svg` | X (Twitter rebrand) |
| `youtube.svg` | YouTube |
| `tiktok.svg` | TikTok |
| `whatsapp.svg` | WhatsApp |
| `telegram.svg` | Telegram |
| `discord.svg` | Discord |
| `slack.svg` | Slack |
| `behance.svg` | Behance |
| `pinterest.svg` | Pinterest |
| `vimeo.svg` | Vimeo |

### Frontend Technologies

| Filename | Icon |
|---|---|
| `html5.svg` | HTML5 |
| `css3.svg` | CSS3 |
| `javascript.svg` | JavaScript |
| `typescript.svg` | TypeScript |
| `react.svg` | React |
| `vue.svg` | Vue.js |
| `angular.svg` | Angular |
| `svelte.svg` | Svelte |
| `nextjs.svg` | Next.js |
| `nuxtjs.svg` | Nuxt.js |
| `tailwindcss.svg` | Tailwind CSS |
| `sass.svg` | Sass |

### Backend & Languages

| Filename | Icon |
|---|---|
| `php.svg` | PHP |
| `python.svg` | Python |
| `nodejs.svg` | Node.js |
| `ruby.svg` | Ruby |
| `go.svg` | Go |
| `rust.svg` | Rust |
| `java.svg` | Java |
| `dotnet.svg` | .NET |
| `laravel.svg` | Laravel |
| `symfony.svg` | Symfony |
| `wordpress-logo.svg` | WordPress (W mark, distinct from theme) |

### Cloud & DevOps

| Filename | Icon |
|---|---|
| `docker.svg` | Docker |
| `kubernetes.svg` | Kubernetes |
| `aws.svg` | Amazon Web Services |
| `googlecloud.svg` | Google Cloud |
| `azure.svg` | Microsoft Azure |
| `digitalocean.svg` | DigitalOcean |
| `vercel.svg` | Vercel |
| `netlify.svg` | Netlify |
| `git.svg` | Git |
| `linux.svg` | Linux |

### Databases

| Filename | Icon |
|---|---|
| `mysql.svg` | MySQL |
| `postgresql.svg` | PostgreSQL |
| `mongodb.svg` | MongoDB |
| `redis.svg` | Redis |
| `sqlite.svg` | SQLite |
| `supabase.svg` | Supabase |

### Design & Productivity Tools

| Filename | Icon |
|---|---|
| `figma.svg` | Figma |
| `sketch.svg` | Sketch |
| `adobexd.svg` | Adobe XD |
| `notion.svg` | Notion |
| `jira.svg` | Jira |

### UI / General Purpose

| Filename | Icon |
|---|---|
| `phone.svg` | Phone / call |
| `map-pin.svg` | Location / address |
| `clock.svg` | Time / hours |
| `calendar.svg` | Date / booking |
| `globe.svg` | Website / language |
| `user.svg` | Single user / profile |
| `users.svg` | Team / group |
| `star.svg` | Rating / favourite |
| `heart.svg` | Like / love |
| `search.svg` | Search |
| `menu.svg` | Hamburger menu |
| `x-close.svg` | Close / dismiss |
| `chevron-down.svg` | Dropdown indicator |
| `chevron-right.svg` | Next / breadcrumb |
| `arrow-right.svg` | CTA / continue |
| `external-link.svg` | Opens in new tab |
| `download.svg` | Download |
| `upload.svg` | Upload |
| `settings.svg` | Settings / configuration |
| `lock.svg` | Security / private |
| `bell.svg` | Notification |
| `share.svg` | Share |
| `copy.svg` | Copy to clipboard |
| `check.svg` | Standalone checkmark (non-circle) |
| `minus.svg` | Collapse / remove |
| `plus.svg` | Expand / add |
| `quote.svg` | Blockquote / testimonial |

---

### Dynamic ACF icon field population

#### Problem

Icon selector fields in ACF are currently defined as `select` fields with a hardcoded `choices` array inside each JSON file. Adding new icons to `assets/media/icons/` requires manually updating every JSON file that contains an icon selector — currently 4 fields across 3 blocks and 1 CPT, growing as new blocks are added.

**Affected fields today:**
| Field name | Location |
|---|---|
| `icon` | `group_services_block.json` (repeater sub-field) |
| `icon` | `group_process_block.json` (repeater sub-field) |
| `icon` | `group_stats_block.json` (repeater sub-field) |
| `service_icon` | `group_service_cpt_fields.json` |

#### Chosen solution: `acf/load_field` PHP filter

ACF provides the `acf/load_field` hook, which fires at render time (admin only) and allows the `choices` array of any select field to be overridden dynamically. By reading `assets/media/icons/*.svg` at that point, the dropdown is always in sync with the filesystem — no JSON edits required when icons are added or removed.

**How it works:**
1. All icon selector fields in ACF JSON keep `"choices": {}` (empty).
2. A single PHP function in `inc/functions-design.php` reads `glob()` over the icons directory and returns a sorted `[ 'filename' => 'Filename label' ]` array.
3. One `add_filter` call per targeted field name populates the choices at runtime.

**Naming convention (must be standardised first):**
All icon selector fields must share a consistent field name so one filter handles them all. The convention: any ACF `select` field with the name `icon` or a name ending in `_icon` is treated as an icon selector.

- `service_icon` in `group_service_cpt_fields.json` already follows this — keep it.
- All repeater sub-fields already named `icon` — keep them.
- Future blocks must follow the same pattern.

**Implementation sketch (`inc/functions-design.php`):**
```php
// Build the icon choices array once from the filesystem.
function ai_driven_get_icon_choices() {
    $files   = glob( get_template_directory() . '/assets/media/icons/*.svg' ) ?: array();
    $choices = array();
    foreach ( $files as $file ) {
        $name             = basename( $file, '.svg' );
        $label            = ucwords( str_replace( '-', ' ', $name ) );
        $choices[ $name ] = $label;
    }
    ksort( $choices );
    return $choices;
}

// Populate any select field named 'icon' or ending in '_icon'.
add_filter( 'acf/load_field', function ( $field ) {
    if ( 'select' === $field['type'] &&
         ( 'icon' === $field['name'] || str_ends_with( $field['name'], '_icon' ) ) ) {
        $field['choices'] = ai_driven_get_icon_choices();
    }
    return $field;
} );
```

**Why not other approaches:**
| Approach | Why rejected |
|---|---|
| Update JSON files manually | Exactly the problem we're solving |
| Register fields in PHP instead of JSON | Breaks the JSON-as-source-of-truth convention |
| ACF clone / shared field group | Still requires maintaining a source group; doesn't eliminate the update step |
| Custom JS in editor | Complex, fragile, non-standard |

#### Category filtering

A single field named `icon` loading all 90+ icons into one dropdown is unusable. A Social Media block should only offer social icons; a Tech Stack block should only offer tech logos.

**Solution: subdirectory structure + field name convention**

Organise `assets/media/icons/` into subdirectories by category. The field name prefix maps directly to a subdirectory — no manifest or extra configuration needed.

**Proposed directory layout:**
```
assets/media/icons/
├── social/          # facebook, youtube, linkedin, instagram …
├── tech/            # react, vue, docker, aws, php …
├── ui/              # phone, map-pin, arrow-right, chevron-down …
└── (root)           # kept for any icon used as a standalone file in templates
```

**Field naming convention:**
| Field name | Icons loaded from |
|---|---|
| `social_icon` | `icons/social/*.svg` |
| `tech_icon` | `icons/tech/*.svg` |
| `ui_icon` | `icons/ui/*.svg` |
| `icon` (generic) | all subdirectories merged |

The `acf/load_field` filter extracts the prefix from the field name and passes it as a `$category` argument to the helper:

```php
function ai_driven_get_icon_choices( $category = null ) {
    if ( $category ) {
        $pattern = get_template_directory() . '/assets/media/icons/' . $category . '/*.svg';
        $files   = glob( $pattern ) ?: array();
    } else {
        // Merge all subdirectories for generic 'icon' fields.
        $dirs  = glob( get_template_directory() . '/assets/media/icons/*', GLOB_ONLYDIR ) ?: array();
        $files = array();
        foreach ( $dirs as $dir ) {
            $files = array_merge( $files, glob( $dir . '/*.svg' ) ?: array() );
        }
    }

    $choices = array();
    foreach ( $files as $file ) {
        $name             = basename( $file, '.svg' );
        $label            = ucwords( str_replace( '-', ' ', $name ) );
        $choices[ $name ] = $label;
    }
    ksort( $choices );
    return $choices;
}

add_filter( 'acf/load_field', function ( $field ) {
    if ( 'select' !== $field['type'] ) {
        return $field;
    }
    if ( str_ends_with( $field['name'], '_icon' ) ) {
        $category        = str_replace( '_icon', '', $field['name'] );
        $field['choices'] = ai_driven_get_icon_choices( $category );
    } elseif ( 'icon' === $field['name'] ) {
        $field['choices'] = ai_driven_get_icon_choices();
    }
    return $field;
} );
```

**Stored value:** ACF stores only the filename (e.g. `facebook`) — not the path. Templates prepend the correct directory using a helper like `ai_driven_get_icon_path( 'facebook', 'social' )`. This keeps the stored data portable and subdirectory-agnostic.

**Migration checklist — must be done together in one commit:**

1. **Move existing icon files** into the appropriate subdirectory (`social/`, `ui/`, etc.). The 10 current flat-root icons all need relocating.
2. **Rename existing ACF field names** in their JSON files so the new convention applies. All 4 current icon fields need attention:

| JSON file | Current field name | Rename to | Reason |
|---|---|---|---|
| `group_stats_block.json` | `icon` | `ui_icon` | Stats icons are UI / general |
| `group_process_block.json` | `icon` | `ui_icon` | Step icons are UI / general |
| `group_services_block.json` | `icon` | `ui_icon` | Service icons are UI / general |
| `group_service_cpt_fields.json` | `service_icon` | `ui_icon` | `service` is not a valid subdirectory; would return empty choices |

3. **Update block templates** — any `get_field( 'icon' )` or `get_field( 'service_icon' )` call must be updated to match the renamed field.
4. **Update the template path helper** to prepend the correct subdirectory when rendering an icon inline.
5. **Re-sync ACF** — after JSON edits, go to ACF → Field Groups → Sync available.

---

#### Impact on icon addition workflow

After this is implemented, adding icons becomes a one-step operation:
1. Drop the `.svg` file into `assets/media/icons/`.
2. Done — every icon selector in every block and CPT updates automatically on next admin page load.

No JSON edits. No ACF sync step. No block-by-block updates.

---

### Summary

| Category | Count |
|---|---|
| Social & Communication | 11 |
| Frontend Technologies | 12 |
| Backend & Languages | 11 |
| Cloud & DevOps | 10 |
| Databases | 6 |
| Design & Productivity | 5 |
| UI / General | 26 |
| **Total new icons** | **81** |
| Already available | 10 |
| **Full library total** | **91** |

---

## Header Search Overlay

### Overview

An optional site-wide search trigger in the header. When enabled, a search icon appears in the header navigation. Clicking it opens a full-screen overlay (dark background with opacity) containing a centred search input and optional heading text. Enabled/disabled and configured entirely from the ACF Theme Options page — no code changes required per site.

### ACF Options fields — new section "Search"

New JSON file: `acf-json/group_ss_search.json` (follows the existing `group_ss_*` naming convention for site settings).

| Field name | Type | Purpose |
|---|---|---|
| `enable_header_search` | true/false | Master toggle — shows/hides the search icon in the header |
| `search_overlay_heading` | text | Large prompt text shown above the input (e.g. "What are you looking for?") |
| `search_input_placeholder` | text | Input placeholder (e.g. "Type and press Enter…") |

### Files to create / modify

| Action | File |
|---|---|
| Create | `acf-json/group_ss_search.json` |
| Create | `template-parts/components/search-overlay.php` |
| Create | `src/css/components/search-overlay.css` |
| Modify | `template-parts/layout/header.php` — add icon button + include overlay |
| Modify | `src/js/scripts/` — register Alpine store |

### Alpine.js state — `Alpine.store`

The search icon lives inside the `<header>` element, but the overlay is full-screen and must render outside it (appended after the header in the DOM). They cannot share a single `x-data` scope. The correct pattern is **Alpine global store**:

```js
// registered in src/js/main.js during Alpine initialisation
Alpine.store( 'search', { open: false } );
```

- Header button: `@click="$store.search.open = true"`
- Overlay root: `x-show="$store.search.open"`
- Close button and ESC key: `$store.search.open = false`

This keeps the header's existing `x-data="{ mobileOpen: false }"` untouched and avoids coupling two unrelated UI concerns in one scope.

### Header changes

- Check `get_field( 'enable_header_search', 'option' )` before rendering the icon — no icon rendered when disabled.
- The icon button renders after the existing nav CTA button, using the `search.svg` icon from the UI icon library.
- The search overlay component is included unconditionally at the bottom of `header.php` (it is hidden via `x-show` — only the toggle is conditional).

### Search overlay component

`template-parts/components/search-overlay.php` — structure:

```
fixed inset-0 z-[60]              ← above the sticky header (z-50)
├── backdrop  (dark bg + opacity, closes on click)
└── content wrapper (centred column)
    ├── close button (top-right)
    ├── heading (from options field, optional)
    └── <form role="search">
            <input type="search" …>
        </form>
```

- Overlay `z-index` must exceed the sticky header (`z-50`) — use `z-[60]` or a dedicated token.
- Backdrop click closes the overlay: `@click.self="$store.search.open = false"`.
- ESC key closes: `@keydown.escape.window="$store.search.open = false"`.
- On open, focus moves automatically to the search input: `x-init="$watch('$store.search.open', v => v && $nextTick(() => $refs.searchInput.focus()))`.
- Body scroll is locked while open: add/remove `overflow-hidden` on `<body>` via a watcher.
- Transition: fade in/out via Alpine `x-transition` — opacity only, no slide (keeps it snappy).

### Accessibility checklist

- Search icon button has `aria-label="Open search"` and `aria-expanded` bound to store state.
- Overlay has `role="dialog"`, `aria-modal="true"`, and `aria-label="Site search"`.
- Focus trap: Tab/Shift+Tab cycle between the input and the close button only while the overlay is open.
- ESC always closes.
- When the overlay closes, focus returns to the search icon button.

---

## Block Heading Unification

### Problem

Blocks inconsistently expose a `section_subheading` field. Currently only **Hero**, **Process**, and **Pricing** have both a heading and a subheading. The remaining 9 blocks have a heading field only, which limits editors' ability to add context below a section title without workarounds.

### Blocks that need a `section_subheading` field added

| Block | ACF JSON file |
|---|---|
| Text & Image | `group_text_image_block.json` |
| Stats / Counters | `group_stats_block.json` |
| FAQ Accordion | `group_faq_block.json` |
| Services Grid | `group_services_block.json` |
| Portfolio Grid | `group_portfolio_grid_block.json` |
| Testimonials Slider | `group_testimonials_block.json` |
| Clients / Logos | `group_clients_block.json` |
| Team Grid | `group_team_block.json` |
| CTA | `group_cta_block.json` |

### Implementation notes

- Add a `section_subheading` text field immediately after `section_heading` in each JSON file. Field key pattern: `field_{block}_section_subheading`.
- In each block template, render the subheading conditionally below the heading — only output the element when the field is non-empty, so existing layouts using only a heading are unaffected.
- The subheading should use a `<p>` tag styled as a lead/intro paragraph, not a second heading level.
- All 7 proposed new blocks in the **Content Blocks** section below already include `section_subheading` in their field specs — apply the same pattern there for consistency.

---

## Content Blocks

### Existing block coverage (reference)

| Block | Pattern |
|---|---|
| Hero | Page header / above-the-fold |
| Text & Image | Editorial text + media |
| Stats / Counters | Animated metrics |
| Process / Steps | Numbered workflow |
| CTA | Conversion band |
| FAQ Accordion | Q&A with JSON-LD |
| Services Grid | CPT-backed service cards |
| Portfolio Grid | CPT-backed filterable grid + AJAX load-more |
| Testimonials Slider | CPT-backed Alpine.js carousel |
| Clients / Logos | Manual logo strip with marquee option |
| Team Grid | CPT-backed member cards |
| Pricing | Plan cards with feature list |

### Gap analysis

The following content patterns are absent from the current set and appear consistently on IT agency / freelancer sites. Listed roughly by expected frequency of use.

---

#### 1. Recent Blog Posts Block ⭐⭐⭐

**What:** A "From our blog" teaser section — a card grid pulling the latest posts from the WP blog, optionally filtered by category.

**Why it's needed:** Every agency home page and About page uses this pattern. There is no existing block that queries standard `post` post-type content.

**Suggested ACF fields:**
- `section_heading` (text)
- `section_subheading` (text)
- `number_of_posts` (number, default 3)
- `filter_by_category` (taxonomy — `category`)
- `show_view_all` (true/false)
- `view_all_label` (text)

**Notes:** Reuse the existing `card` component for each post. Layout mirrors the Services block (CPT query → normalised cards array) but targets `post` instead of `service`.

---

#### 2. Feature / Icon Grid Block ⭐⭐⭐

**What:** A scannable grid of icon + short label + optional description items — the classic "Why choose us" / "Our approach" / "Key benefits" section.

**Why it's needed:** Distinct from the Services block (which is CPT-backed and links to detail pages). This is a purely manual, lightweight block for 3–8 inline feature bullets — no CPT required, no individual page per item.

**Suggested ACF fields:**
- `section_heading` (text)
- `section_subheading` (text)
- `columns` (select: 2 / 3 / 4, default 3)
- `features` (repeater)
  - `icon` (text — icon filename, same convention as Stats/Process)
  - `title` (text)
  - `description` (textarea)

**Notes:** No link per item — if linking is needed, the Services block already covers that. Alpine.js not required.

---

#### 3. Video Block ⭐⭐

**What:** A full-width or contained video section — YouTube/Vimeo embed with a custom thumbnail overlay and a play button that reveals the iframe on click (facade pattern for performance).

**Why it's needed:** Showreels, case study walkthroughs, and product demos are common on agency sites. None of the current blocks handles video.

**Suggested ACF fields:**
- `section_heading` (text)
- `video_url` (url — YouTube or Vimeo)
- `thumbnail` (image — shown before play, improves CLP)
- `caption` (text, optional)
- `aspect_ratio` (select: 16:9 / 4:3, default 16:9)

**Notes:** Alpine.js `x-data` to toggle `show_iframe` on click. Thumbnail acts as the LCP image; iframe injected only after click to avoid the ~500 KB YouTube embed weight on page load.

---

#### 4. Timeline Block ⭐⭐

**What:** A vertical chronological timeline for company milestones, founding story, or project history.

**Why it's needed:** "Our story" / "Company history" sections are common on About pages. The Process block is for sequential workflow steps; this is for dated, narrative history — semantically and visually distinct.

**Suggested ACF fields:**
- `section_heading` (text)
- `section_subheading` (text)
- `events` (repeater)
  - `year` (text — e.g. "2019" or "March 2021")
  - `title` (text)
  - `description` (textarea)
  - `image` (image, optional)

**Notes:** CSS-driven alternating left/right layout on desktop, linear on mobile. No JS needed. Intersection Observer fade-in can be added via the existing scroll animation pattern from the Stats block.

---

#### 5. Gallery / Image Grid Block ⭐⭐

**What:** A uniform or masonry grid of images — office photos, event photography, portfolio shots used outside the Portfolio CPT.

**Why it's needed:** There is no block for displaying a collection of images without a CPT. This fills that gap for team pages, culture sections, and event pages.

**Suggested ACF fields:**
- `section_heading` (text)
- `images` (gallery field — array of image IDs)
- `columns` (select: 2 / 3 / 4, default 3)
- `enable_lightbox` (true/false)

**Notes:** If lightbox is enabled, Alpine.js `x-data` handles the overlay; otherwise images are plain `<figure>` elements. Keep `loading="lazy"` on all images. Lightbox is a self-contained Alpine component — no external library needed.

---

#### 6. Awards & Certifications Block ⭐

**What:** A structured display of badges, certifications, or awards — logo + name + issuing body + year.

**Why it's needed:** IT agencies commonly display ISO certifications, Google Partner badges, industry awards. The Clients block is logo-only; this adds semantic structure (name, issuer, year) and is visually distinct.

**Suggested ACF fields:**
- `section_heading` (text)
- `awards` (repeater)
  - `badge_image` (image)
  - `award_name` (text)
  - `issued_by` (text)
  - `year` (text)

**Notes:** No CPT — repeater is sufficient since award count is typically low (< 10). Display as a centred flex row, wrapping naturally.

---

#### 7. Newsletter / Email Capture Block ⭐

**What:** An email opt-in strip — heading, subtext, email input, and submit button.

**Why it's needed:** Common footer-adjacent section for lead capture. No existing block covers form inputs beyond the full contact form on `page-contact.php`.

**Suggested ACF fields:**
- `heading` (text)
- `subtext` (text)
- `input_placeholder` (text, default "Your email address")
- `button_label` (text, default "Subscribe")
- `privacy_note` (text, optional — e.g. "No spam, unsubscribe anytime.")
- `provider_webhook_url` (url — Mailchimp/Brevo/etc. form action, optional)

**Notes:** Use the same honeypot + nonce pattern as the contact form (`inc/functions-form.php`). If `provider_webhook_url` is empty, POST to the WP AJAX handler and let the site owner wire up the integration. Alpine.js handles loading/success/error states.

---

#### 8. Text Content Block ⭐⭐⭐

**What:** A full WYSIWYG editor block — the editorial workhorse that replaces native WP paragraph/heading/list blocks with a single, consistently styled rich-text area. All theme typography (headings, body, lists, blockquotes, tables, inline code) is applied via a wrapping prose class so output always matches the design system.

**Why it's needed:** Native WP core blocks (Paragraph, Heading, List, Quote…) render with browser or theme.json defaults and are hard to style globally without FSE. A single WYSIWYG block gives editors a familiar interface while the template controls every typographic style through one CSS class, keeping design consistent across all pages and posts.

**Suggested ACF fields:**
- `content` (wysiwyg — full toolbar, media uploads enabled)
- `width` (select: `narrow` / `default` / `wide`, default `default`) — controls the max-width of the content column
- `text_align` (select: `left` / `center`, default `left`)

**Notes:**
- Render `content` with `wp_kses_post()` — never with `echo esc_html()`, which would strip tags.
- Wrap output in a single element with a `prose` utility class (defined in `src/css/components/prose.css`) that styles all descendant elements — `h2`–`h4`, `p`, `ul`, `ol`, `blockquote`, `table`, `code`, `a`. This is the Tailwind Typography pattern adapted to our own tokens; no external plugin needed.
- The `width` field maps to a max-width token: `narrow` ≈ 65ch, `default` ≈ 80ch, `wide` = full column.
- No section heading field — if editors need a labelled section, they compose this block below a Hero or CTA block. Keeping it heading-free makes it a true drop-in for mid-page editorial content.
- No JS required.

---

#### 9. Welcome Slider Block ⭐⭐⭐

**What:** A full-viewport, full-bleed slider designed for homepages and landing screens. Each slide occupies the full browser width and height with a background image, large display-scale heading, optional subheading, and up to two CTA buttons. An optional colour/gradient overlay sits between the background and the text to ensure legibility regardless of image brightness.

**Why it's needed:** The existing Hero block is a single static section — it does not support multiple slides. A slider is a distinct, commonly requested pattern for agency homepages where the client wants to cycle through key messages (e.g. "We design · We build · We grow"). No current block fills this role.

**Distinction from the Hero block:**
| | Hero Block | Welcome Slider |
|---|---|---|
| Slides | 1 (static) | 1–N (carousel) |
| Height | Content-driven | Full viewport (100svh) |
| Layout variants | Full-width bg / split | Full-width bg only |
| Overlay | None | Optional gradient |
| JS | None | Alpine.js carousel |

**Suggested ACF fields:**

Top-level (slider settings):
- `autoplay` (true/false, default true)
- `autoplay_interval` (number — seconds, default 5)
- `show_arrows` (true/false, default true)
- `show_dots` (true/false, default true)

`slides` (repeater — each slide):
- `background_image` (image)
- `enable_overlay` (true/false)
- `overlay_style` (select: `solid` / `gradient-bottom` / `gradient-center`, shown conditionally when overlay enabled)
- `overlay_opacity` (number 0–100, default 50)
- `heading` (text — display-scale, rendered as `<h1>` on the first slide, `<h2>` on subsequent)
- `subheading` (textarea)
- `text_alignment` (select: `left` / `center` / `right`, default `center`)
- `primary_button_label` (text)
- `primary_button_url` (url)
- `secondary_button_label` (text)
- `secondary_button_url` (url)

**Notes:**
- Full viewport height via `min-height: 100svh` (uses small viewport height unit to avoid mobile browser chrome issues). `svh` falls back to `vh` in older browsers — add the fallback in CSS.
- Background image set as an inline `style` attribute via `wp_get_attachment_image_src()` — not as an `<img>` tag — so CSS `object-fit: cover` handles scaling without layout shift.
- Overlay implemented as an absolutely-positioned `<div>` with `inset-0` and a CSS custom-property-driven gradient or solid colour drawn from the design token palette. No hardcoded hex values.
- Alpine.js `x-data` manages `currentSlide`, arrow prev/next handlers, autoplay interval (`setInterval` started with `x-init`, cleared with `$destroy`), and dot navigation. Pattern mirrors the Testimonials Slider block.
- Autoplay pauses on `mouseenter` and `focusin` for accessibility — users interacting with slide content should not be interrupted.
- The `<h1>` / `<h2>` distinction on heading level is important for SEO: only the first slide gets `<h1>` since the page should have exactly one.
- Heading uses display-scale type tokens (larger than the standard `text-4xl` / `text-5xl` used in Hero) — define a `--font-size-display` token in `src/css/variables/typography.css` if not already present.
- No JS dependency beyond Alpine.js — no Swiper, Splide, or other slider library.

---

### Implementation priority recommendation

| Priority | Block | Reasoning |
|---|---|---|
| 1 | Welcome Slider | Homepage hero replacement; high visual impact, frequently the first client request |
| 2 | Recent Blog Posts | Near-universal need; trivial query (mirrors Services block pattern) |
| 3 | Feature / Icon Grid | "Why us" sections appear on virtually every agency site |
| 4 | Text Content | Fills the editorial gap left by unstyled core blocks — high daily use |
| 5 | Video | High visual impact; facade pattern keeps performance intact |
| 6 | Timeline | About-page staple; CSS-only, low implementation cost |
| 7 | Gallery | Fills a genuine gap; lightbox adds interaction |
| 8 | Awards & Certifications | Niche but easy to build |
| 9 | Newsletter Capture | Useful but depends on third-party integration |
