# Step 38 — Icon Library

**Phase:** 9 — Icon System  
**Depends on:** Step 37 (icon system foundation — subdirectory structure, helper function, and CSS must exist)  
**Required by:** Step 41 (search icon), Step 49 (feature grid icons), Step 51 (timeline icons)

---

## Summary

Populate the icon library by creating 81 SVG icon files across three category subdirectories (`social/`, `tech/`, `ui/`). Each file is a standalone, self-contained SVG that the `aidriven_icon()` helper (from Step 37) inlines at render time. Social and tech icons use the brand/logo format; UI icons use the outlined stroke format.

---

## User Stories

- **As a developer building blocks**, I want a ready-to-use set of social, tech, and UI icons so I can call `aidriven_icon( 'ui/arrow-right' )` without sourcing or optimising SVGs manually.
- **As a content editor**, I want consistent icon sizes across the site so the interface looks polished and professional.
- **As a site owner**, I want recognisable brand logos (React, WordPress, AWS) available as icons so technology stack sections and partner logos render crisply at any size.

---

## Business Value

A pre-built icon library eliminates per-project icon hunting and ensures visual consistency across all blocks that use the icon system. Having social, tech-stack, and general-purpose UI icons in one place covers the majority of IT company / freelancer site needs out of the box.

---

## Acceptance Criteria

### SVG File Conventions

- [ ] All SVG files have `viewBox="0 0 24 24"` and `aria-hidden="true"`.
- [ ] All SVG files do **not** include `width` or `height` attributes (size is controlled via CSS).
- [ ] All SVG files do **not** include `xmlns` — the helper wraps them in an inline context.
- [ ] Brand/logo icons (`social/`, `tech/`): `fill="currentColor"`, single `<path>` element, no `stroke` attributes.
- [ ] UI icons (`ui/`): `fill="none"`, `stroke="currentColor"`, `stroke-width="2"`, `stroke-linecap="round"`, `stroke-linejoin="round"`.

### File Coverage

- [ ] All 11 `social/` icons exist as individual `.svg` files.
- [ ] All 44 `tech/` icons exist as individual `.svg` files.
- [ ] All 26 `ui/` icons exist as individual `.svg` files.
- [ ] Total: 81 files across the three subdirectories.

### Rendering

- [ ] Every icon renders correctly at 24 × 24 px (default inline size).
- [ ] Every icon renders correctly at 48 × 48 px (scaled via CSS `width`/`height`).
- [ ] No icon shows a blank or invisible output at either size.
- [ ] Icons inherit colour from the surrounding text (i.e. `currentColor` works correctly).

### Validation

- [ ] Every `.svg` file is well-formed XML (no unclosed tags, no stray attributes).
- [ ] `make check` passes (PHPCS — no PHP files introduced in this step, so no new violations).

---

## Technical Scope

### Files to Create

All files go into `assets/media/icons/` inside the category subdirectory created in Step 37.

#### `social/` — 11 icons

| Filename | Icon |
|---|---|
| `facebook.svg` | Facebook logo |
| `x.svg` | X (formerly Twitter) logo |
| `youtube.svg` | YouTube logo |
| `tiktok.svg` | TikTok logo |
| `whatsapp.svg` | WhatsApp logo |
| `telegram.svg` | Telegram logo |
| `discord.svg` | Discord logo |
| `slack.svg` | Slack logo |
| `behance.svg` | Behance logo |
| `pinterest.svg` | Pinterest logo |
| `vimeo.svg` | Vimeo logo |

#### `tech/` — 44 icons

| Filename | Icon |
|---|---|
| `html5.svg` | HTML5 logo |
| `css3.svg` | CSS3 logo |
| `javascript.svg` | JavaScript logo |
| `typescript.svg` | TypeScript logo |
| `react.svg` | React logo |
| `vue.svg` | Vue.js logo |
| `angular.svg` | Angular logo |
| `svelte.svg` | Svelte logo |
| `nextjs.svg` | Next.js logo |
| `nuxtjs.svg` | Nuxt.js logo |
| `tailwindcss.svg` | Tailwind CSS logo |
| `sass.svg` | Sass logo |
| `php.svg` | PHP logo |
| `python.svg` | Python logo |
| `nodejs.svg` | Node.js logo |
| `ruby.svg` | Ruby logo |
| `go.svg` | Go logo |
| `rust.svg` | Rust logo |
| `java.svg` | Java logo |
| `dotnet.svg` | .NET logo |
| `laravel.svg` | Laravel logo |
| `symfony.svg` | Symfony logo |
| `wordpress-logo.svg` | WordPress logo |
| `docker.svg` | Docker logo |
| `kubernetes.svg` | Kubernetes logo |
| `aws.svg` | Amazon Web Services logo |
| `googlecloud.svg` | Google Cloud logo |
| `azure.svg` | Microsoft Azure logo |
| `digitalocean.svg` | DigitalOcean logo |
| `vercel.svg` | Vercel logo |
| `netlify.svg` | Netlify logo |
| `git.svg` | Git logo |
| `linux.svg` | Linux (Tux) logo |
| `mysql.svg` | MySQL logo |
| `postgresql.svg` | PostgreSQL logo |
| `mongodb.svg` | MongoDB logo |
| `redis.svg` | Redis logo |
| `sqlite.svg` | SQLite logo |
| `supabase.svg` | Supabase logo |
| `figma.svg` | Figma logo |
| `sketch.svg` | Sketch logo |
| `adobexd.svg` | Adobe XD logo |
| `notion.svg` | Notion logo |
| `jira.svg` | Jira logo |

#### `ui/` — 26 icons

| Filename | Icon |
|---|---|
| `phone.svg` | Phone / telephone handset |
| `map-pin.svg` | Map pin / location marker |
| `clock.svg` | Clock face |
| `calendar.svg` | Calendar |
| `globe.svg` | Globe / world |
| `user.svg` | Single user silhouette |
| `users.svg` | Two users / group |
| `star.svg` | Five-pointed star |
| `heart.svg` | Heart |
| `search.svg` | Magnifying glass |
| `menu.svg` | Hamburger menu (three lines) |
| `x-close.svg` | × close / dismiss |
| `chevron-down.svg` | Chevron pointing down |
| `chevron-right.svg` | Chevron pointing right |
| `arrow-right.svg` | Arrow pointing right |
| `external-link.svg` | External link (box with arrow) |
| `download.svg` | Download arrow |
| `upload.svg` | Upload arrow |
| `settings.svg` | Settings / gear |
| `lock.svg` | Padlock |
| `bell.svg` | Notification bell |
| `share.svg` | Share / network nodes |
| `copy.svg` | Copy to clipboard |
| `check.svg` | Checkmark / tick |
| `minus.svg` | Minus / dash |
| `plus.svg` | Plus / add |
| `quote.svg` | Opening quotation mark |

### Files to Modify

None. This step creates new SVG files only. The `aidriven_icon()` helper registered in Step 37 picks them up automatically by file path.

---

## SVG Format Reference

### Brand / logo icon template (`social/`, `tech/`)

```svg
<svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
  <path d="…"/>
</svg>
```

### UI / outlined icon template (`ui/`)

```svg
<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <!-- path, circle, rect, polyline, etc. -->
</svg>
```

---

## Notes

- Use well-established, recognisable path data for each logo — the Simple Icons project (simpleicons.org) is a reliable source for brand SVG paths normalised to a 24 × 24 viewBox.
- For UI icons, Heroicons (heroicons.com) stroke-style icons (24 × 24 outline set) are a reliable source and are already designed to the same spec.
- If a brand icon requires more than one `<path>` to render correctly (e.g. multi-part logos), multiple `<path>` elements within the same `<svg>` are acceptable — the single-path guideline is a preference for simplicity, not a hard constraint.
- `wordpress-logo.svg` is named with the `-logo` suffix to avoid a clash with any future `wordpress` block or component file.
- The `quote.svg` icon is a UI-category icon representing an opening typographic quotation mark, used by the Testimonial and Blockquote blocks.

---

## Out of Scope

- Animated SVG icons
- Coloured / multi-tone brand icons (all icons use `currentColor` only)
- Icon sprite sheets or symbol-based SVG sprites
- Icon picker UI in the Gutenberg editor
- Any PHP, JS, or CSS changes (Step 37 covers all infrastructure)
- Licensing / attribution tracking for icon sources
