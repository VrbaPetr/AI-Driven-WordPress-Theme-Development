# Step 02 — Design Tokens

**Phase:** 1 — Foundation  
**Depends on:** Step 01  
**Required by:** Steps 05–33 (every CSS file in the theme)

---

## Summary

Define all design tokens for the theme using Tailwind CSS v4's `@theme` directive in `src/css/variables/`. This establishes the colour palette, typography scale, spacing, border radii, shadows, and breakpoints that every block, component, and layout style will reference. No visible UI is produced — this step creates the visual language the whole theme speaks.

---

## User Stories

- **As a developer**, I want a single source of truth for the colour palette so changing the brand primary colour propagates to every component without hunting through CSS files.
- **As a developer**, I want a consistent type scale and spacing system so layout decisions are systematic rather than arbitrary pixel values.
- **As a client/designer**, I want the theme's colour and typography tokens to match the brand so deploying a new project only requires swapping token values, not rewriting CSS.

---

## Business Value

Design tokens are the single highest-leverage investment in a reusable boilerplate. Every new client project starts by overriding token values — without tokens, every new project requires hunting through scattered hardcoded values. Getting tokens right here eliminates that rework permanently.

---

## Acceptance Criteria

- [ ] All token files live in `src/css/variables/` and are imported into `src/css/main.css`.
- [ ] Tokens defined using Tailwind v4 `@theme` directive — no `tailwind.config.js`.
- [ ] **Colour palette** defined: primary scale (50–950), neutral/grey scale (50–950), semantic aliases (success, warning, error, info), and surface/background aliases.
- [ ] **Typography** defined: font family tokens (`--font-sans`, `--font-mono`), font size scale, line height scale, font weight tokens.
- [ ] **Spacing** defined: custom spacing scale if deviating from Tailwind defaults; otherwise explicitly documented that defaults are used.
- [ ] **Border radius** defined: `--radius-sm`, `--radius-md`, `--radius-lg`, `--radius-full`.
- [ ] **Shadows** defined: `--shadow-sm`, `--shadow-md`, `--shadow-lg`, `--shadow-card`.
- [ ] **Breakpoints** defined explicitly (even if matching Tailwind defaults) so they are visible and overridable: `sm`, `md`, `lg`, `xl`, `2xl`.
- [ ] **Z-index** scale defined: `--z-header`, `--z-dropdown`, `--z-modal`, `--z-toast`.
- [ ] **Transition** tokens defined: `--transition-fast` (150ms), `--transition-base` (250ms), `--transition-slow` (400ms), all using `ease-out`.
- [ ] All token names use kebab-case and follow the `--token-category-variant` pattern.
- [ ] `make build` compiles without errors.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `src/css/variables/colors.css` | Colour palette and semantic aliases |
| `src/css/variables/typography.css` | Font families, size scale, weights, line heights |
| `src/css/variables/spacing.css` | Spacing scale (if customised) |
| `src/css/variables/radii.css` | Border radius tokens |
| `src/css/variables/shadows.css` | Shadow/elevation tokens |
| `src/css/variables/motion.css` | Transition duration and easing tokens |
| `src/css/variables/z-index.css` | Z-index scale tokens |

### Files to Modify

| File | Change |
|---|---|
| `src/css/main.css` | Add `@import` for each variables file at the top of the import list |

---

## Token Structure Reference

### Colour Tokens (`colors.css`)

```css
@theme {
  /* Primary brand colour — replace with client brand */
  --color-primary-50:  oklch(97% 0.02 250);
  --color-primary-100: oklch(93% 0.05 250);
  /* … 200 through 800 … */
  --color-primary-900: oklch(25% 0.08 250);
  --color-primary-950: oklch(15% 0.06 250);

  /* Neutral / grey scale */
  --color-neutral-50:  oklch(98% 0 0);
  /* … through … */
  --color-neutral-950: oklch(10% 0 0);

  /* Semantic aliases */
  --color-success: oklch(60% 0.15 145);
  --color-warning: oklch(75% 0.17 70);
  --color-error:   oklch(55% 0.20 25);
  --color-info:    oklch(60% 0.13 240);

  /* Surface aliases */
  --color-background: var(--color-neutral-50);
  --color-surface:    #ffffff;
  --color-border:     var(--color-neutral-200);
  --color-text:       var(--color-neutral-900);
  --color-text-muted: var(--color-neutral-500);
}
```

### Typography Tokens (`typography.css`)

```css
@theme {
  --font-sans: 'Inter', ui-sans-serif, system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', ui-monospace, monospace;

  /* Font sizes — fluid or fixed; align with Tailwind defaults or override */
  --text-xs:   0.75rem;
  --text-sm:   0.875rem;
  --text-base: 1rem;
  --text-lg:   1.125rem;
  --text-xl:   1.25rem;
  --text-2xl:  1.5rem;
  --text-3xl:  1.875rem;
  --text-4xl:  2.25rem;
  --text-5xl:  3rem;

  --font-weight-normal:   400;
  --font-weight-medium:   500;
  --font-weight-semibold: 600;
  --font-weight-bold:     700;
}
```

### Radius Tokens (`radii.css`)

```css
@theme {
  --radius-sm:   0.25rem;
  --radius-md:   0.5rem;
  --radius-lg:   0.75rem;
  --radius-xl:   1rem;
  --radius-full: 9999px;
}
```

### Shadow Tokens (`shadows.css`)

```css
@theme {
  --shadow-sm:   0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-md:   0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  --shadow-lg:   0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
  --shadow-card: 0 2px 8px 0 rgb(0 0 0 / 0.08);
}
```

### Motion Tokens (`motion.css`)

```css
@theme {
  --transition-fast: 150ms ease-out;
  --transition-base: 250ms ease-out;
  --transition-slow: 400ms ease-out;
}
```

### Z-Index Tokens (`z-index.css`)

```css
@theme {
  --z-header:   100;
  --z-dropdown: 200;
  --z-modal:    300;
  --z-toast:    400;
}
```

---

## Font Loading

If using a web font (e.g. Inter), add a `@font-face` or Google Fonts `@import` in `typography.css`. Prefer self-hosted fonts in `assets/fonts/` for performance and privacy.

---

## Notes

- Token values in this file are **placeholders** — the intent is the structure and naming convention, not the exact hue. Each new project overrides `colors.css` with the client's brand palette.
- Tailwind v4 reads `@theme` tokens directly — utilities like `bg-primary-600`, `text-neutral-900`, `rounded-lg` are generated automatically from these tokens.
- Avoid hardcoding hex/rgb values anywhere outside `variables/` — always reference a token.

---

## Out of Scope

- Dark mode token set
- Per-component CSS custom property overrides (defined within each component's CSS file)
- Animation keyframes (defined per component/block as needed)
