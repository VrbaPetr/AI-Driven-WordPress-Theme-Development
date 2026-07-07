# AI-Driven Boilerplate

A production-ready WordPress starter theme for IT agencies and freelancers. Classic PHP theme (no Full Site Editing) backed by ACF Pro field groups, Gutenberg blocks, Tailwind CSS v4, Alpine.js, and Vite.

## Requirements

| Dependency | Version |
|---|---|
| WordPress | 6.4+ |
| PHP | 8.2+ |
| Node.js | 18+ (with Corepack for pnpm) |
| Composer | 2.x |
| ACF Pro | 6.x |

## What's included

### Custom Post Types
- **Services** — agency offerings with ACF-backed fields (icon, excerpt, full description)
- **Portfolio / Projects** — case studies with gallery, tech stack, testimonial
- **Team Members** — staff profiles with photo, role, bio, and social links
- **Testimonials** — client quotes with author name, role, company, and photo

### Gutenberg Blocks (ACF-backed)
| Block | Description |
|---|---|
| Hero | Full-width image background or split text/image layout, two CTA buttons |
| Text & Image | WYSIWYG content with image, left/right position toggle |
| Stats / Counters | Up to 4 animated metrics with count-up on scroll |
| Process / Steps | Numbered steps with icon, title, and description; horizontal or vertical |
| CTA | Headline, subtext, up to two buttons, solid or gradient background |
| FAQ Accordion | Alpine.js accordion with optional multi-open and JSON-LD FAQ schema |
| Services Grid | Card grid pulled from the Services CPT or a manual repeater |
| Portfolio Grid | Filterable grid from the Portfolio CPT with Alpine.js filters and AJAX load-more |
| Testimonials Slider | Alpine.js carousel pulled from the Testimonials CPT |
| Clients / Logos | Manual logo repeater with grayscale/colour toggle and optional marquee |
| Team Grid | Grid pulled from the Team Members CPT |
| Pricing | Up to 4 plan cards with featured highlight, feature list, and CTA |

### Page Templates
- Home (`front-page.php`)
- About (`page-about.php`)
- Services archive + single (`archive-service.php`, `single-service.php`)
- Portfolio archive + single (`archive-project.php`, `single-project.php`)
- Team (`page-team.php`)
- Blog archive + single (`home.php`, `single.php`)
- Contact with AJAX form (`page-contact.php`)
- Search results (`search.php`)
- Custom 404 (`404.php`)
- Category / tag archives (`category.php`, `tag.php`)

### UI Components
Button, Card, Portfolio Card, Badge, Alert, Breadcrumbs (with `BreadcrumbList` JSON-LD), Pagination.

### Site Features
- Sticky header with two-level dropdown and mobile hamburger (Alpine.js)
- Multi-column footer with widget areas and social icons
- AJAX contact form with honeypot, nonce, and email notification
- Open Graph + Twitter Card meta tags
- Organisation and Article JSON-LD structured data
- WCAG 2.1 AA keyboard navigation
- Critical / non-critical asset split for performance

### ACF Options Pages (site-wide fields)
- Company info, social links, contact details, footer text, global CTA

---

## Getting started

### 1. Install the theme

Clone or copy this repository into your WordPress themes directory:

```bash
cd wp-content/themes/
git clone https://github.com/VrbaPetr/ai-driven-boilerplate.git
```

### 2. Install dependencies

```bash
make install
```

This installs Node packages (via pnpm) and PHP dev tools (via Composer).

### 3. Build assets

For development with live-reload:

```bash
make watch
```

For a production build:

```bash
make build
```

### 4. Activate the theme

Activate **AI-Driven Boilerplate** from _WP Admin → Appearance → Themes_.

### 5. Sync ACF field groups

ACF field group definitions ship as JSON files in `acf-json/`. After activation, go to _WP Admin → ACF → Field Groups_ and click **Sync available** to import all field groups — no manual field setup required.

### 6. Configure the Options pages

Fill in company info, social links, contact details, and global CTA at _WP Admin → Site Settings_.

---

## Development

All tasks run through `make`. Run `make help` to see every available target.

```bash
make install     # Install all dependencies (pnpm + composer)
make update      # Update dependencies within declared version ranges
make watch       # Start the Vite dev server with live-reload
make build       # Build production assets
make zip         # Build and package a deployable theme zip into ./dist
make check       # Run PHP linter (PHPCS with WP Coding Standards)
make fix         # Auto-fix PHP linting issues (PHPCBF)
```

### Asset pipeline

Source files live in `src/`. Vite compiles them to `assets/`:

| Source | Output |
|---|---|
| `src/css/main.css` | `assets/css/main-[hash].css` |
| `src/js/main.js` | `assets/js/main-[hash].js` |
| `src/js/critical.js` | `assets/js/critical-[hash].js` |

The Vite manifest at `assets/.vite/manifest.json` maps source paths to hashed filenames. `functions.php` reads this manifest to enqueue the correct versioned files — no manual filename updates needed.

### Design tokens

All design tokens (colours, typography, spacing, radii, shadows, z-index, transitions) live in `src/css/variables/`. They are defined as Tailwind v4 `@theme` tokens — no `tailwind.config.js` required.

**Color rule:** only use token-defined colors. Never reference Tailwind's built-in palette (e.g. `blue-500`, `gray-200`) or hardcode hex/OKLCH values in CSS files. All colors must come from `src/css/variables/colors.css`.

### ACF JSON

Field group definitions in `acf-json/` are the source of truth. To add or modify fields:

1. Edit the relevant JSON file in `acf-json/`.
2. Go to _WP Admin → ACF → Field Groups_ and sync.
3. Commit the updated JSON file.

Never create fields via the WP admin UI alone — changes won't be tracked in version control.

---

## Project structure

```
ai-driven-boilerplate/
├── acf-json/                    # ACF Local JSON field group definitions
├── assets/                      # Compiled CSS/JS output (generated, do not edit)
│   ├── css/
│   ├── js/
│   ├── fonts/
│   └── media/
├── inc/                         # PHP functionality included by functions.php
│   ├── register-blocks.php
│   ├── register-cpt.php
│   ├── register-taxonomy.php
│   ├── functions-design.php
│   ├── functions-data.php
│   ├── functions-helpers.php
│   ├── functions-form.php
│   ├── functions-ajax.php
│   └── functions-seo.php
├── src/                         # Vite source files
│   ├── css/
│   │   ├── main.css             # Main CSS entry point
│   │   ├── variables/           # Design tokens (@theme)
│   │   ├── blocks/
│   │   ├── components/
│   │   ├── layout/
│   │   ├── navigation/
│   │   ├── pages/
│   │   └── forms/
│   └── js/
│       ├── main.js              # Main JS entry (deferred)
│       ├── critical.js          # Critical JS (loaded in <head>)
│       └── scripts/
├── template-parts/
│   ├── blocks/                  # ACF block templates
│   ├── components/              # Reusable UI components
│   ├── forms/
│   ├── layout/                  # Header, footer
│   └── pages/
├── functions.php
├── style.css                    # WP theme header (no styles here)
├── vite.config.js
├── package.json
├── composer.json
└── Makefile
```

---

## Deployment

Run `make zip` to produce a ready-to-upload zip in `./dist/`:

```bash
make zip
# → dist/ai-driven-boilerplate-1.0.0.zip
```

The zip excludes source files, `node_modules`, dev configs, and build tooling — only what WordPress needs to run the theme is included.

Upload the zip via _WP Admin → Appearance → Themes → Add New → Upload Theme_, or extract it directly into the server's themes directory.

---

## Browser & accessibility support

- **Browsers:** all evergreen browsers (Chrome, Firefox, Safari, Edge)
- **Accessibility:** WCAG 2.1 AA — keyboard-navigable menus and accordions, semantic landmarks, sufficient colour contrast

## Multilingual

WPML and Polylang compatible. All strings are wrapped with translation functions and use the `ai-driven-boilerplate` text domain. RTL layouts are not included.

## License

Proprietary — all rights reserved. Not for redistribution.
