---
name: tailwind-conventions
description: Tailwind CSS v4 conventions — utilities, tokens, layers, naming, and main.css import order.
---

## Inline utilities vs custom classes

- **Up to 3 Tailwind utility classes** → write them inline in the HTML template.
- **4 or more classes** (especially when mixing layout, colour, border, spacing) → extract to a named custom class in the relevant CSS file and use `@apply`.

```html
<!-- 3 or fewer — inline is fine -->
<div class="w-full flex flex-col">

<!-- extracted -->
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

## Design tokens

All tokens are defined in `src/css/variables/` using Tailwind v4 `@theme {}` blocks. Always reference a token in component CSS.

| File | Token pattern | Example |
|---|---|---|
| `color.css` | `--color-{palette}-{weight}` | `--color-blue-950` |
| `color.css` (alpha) | `--color-{palette}-{weight}-{opacity}` | `--color-blue-500-20` |
| `color.css` (semantic) | `--color-text-{role}` | `--color-text-default` |
| `shadow.css` | `--shadow-{size}` | `--shadow-lg` |
| `typography.css` | `--font-{name}` | `--font-figtree` |

Custom breakpoints are also defined in `@theme {}`: `--breakpoint-{name}: {value}`.

## Typography scale

Typography utilities are defined with `@utility {}` so they compose with other Tailwind utilities:

```css
@utility text-heading-2 {
    @apply font-fraunces text-2xl font-semibold tracking-tight leading-6;
    @apply md:text-[32px] md:leading-8;
    @apply lg:text-4xl lg:leading-10;
}
```

Available scales: `text-heading-{1–6}`, `body-{xl|lg|md|sm}-{normal|medium|semibold}`, `text-paragraph`, `text-link`.

## @layer usage

| Layer | What goes in it |
|---|---|
| `@layer base` | Global HTML/body styles, `@font-face`, scrollbar, wysiwyg defaults |
| `@layer components` | All named UI classes (layout, components, blocks, pages) |
| `@utility` | Typography scale utilities that must compose with other utilities |

## Responsive variants

Split responsive breakpoint variants onto separate `@apply` lines for readability:

```css
.my-class {
    @apply flex flex-col gap-4;
    @apply md:flex-row md:gap-6;
    @apply lg:gap-10;
}
```

## Pseudo-classes

Define `:hover`, `:active`, `:focus-visible`, `:disabled` as separate CSS rules inside the component block:

```css
.btn-primary { @apply bg-brand-100 text-blue-900; }
.btn-primary:hover { @apply bg-brand-300 text-white; }
.btn-primary:active { @apply bg-brand-500 text-white; }
```

## CSS nesting

Use native CSS nesting for child elements and sub-parts of a component:

```css
.my-card {
    @apply flex flex-col gap-4;
    .my-card-title { @apply text-heading-3; }
    .my-card-body  { @apply body-lg-normal; }
}
```

## Naming

- All class names in kebab-case.
- **Components:** base class + modifier suffix (`.btn`, `.btn-primary`, `.btn-lg`).
- **State modifiers:** BEM-style double-dash suffix (`.checkbox--error`, `.checkbox--disabled`).
- **Blocks:** root class matches the block name; sub-elements use `.block-name-{element}` (e.g. `.wwa-wrapper`, `.wwa-headline`, `.wwa-image`).
- **Layout sections:** descriptive prefix (`.header-wrapper`, `.desktop-header`, `.footer-top-section`).
- **Pages:** page-name prefix (`.article-main`, `.article-post-sidebar`).

## Contextual overrides

When a component renders differently inside a specific parent, scope the override with the parent selector:

```css
.state-sidebar .cta-bar {
    @apply flex-col;
}
```

## Fonts

- Register fonts via `@font-face` inside `@layer base {}` in `src/css/variables/typography.css`.
- Always use WOFF2 format and `font-display: swap`.
- Path must point to `../../assets/fonts/` (relative from the CSS file location).
- Reference fonts in `@theme {}` as `--font-{name}` tokens so Tailwind utilities (`font-figtree`) are auto-generated.

## Safelist (dynamic classes)

Use `@source inline()` in `main.css` for Tailwind classes generated dynamically from PHP or JS (e.g. classes built from ACF field values):

```css
@source inline("bg-blue-100 bg-red-100 border-blue-200");
```

## main.css import order

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
