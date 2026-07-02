# Step 50 — Video Block

**Phase:** 12 — New Content Blocks  
**Depends on:** Steps 01, 36 (subheading pattern established)  
**Required by:** nothing

---

## Summary

Build an ACF Gutenberg block that renders a YouTube or Vimeo video as a lightweight facade embed. A custom thumbnail and a play button are shown on initial page load — the heavy iframe is only injected when the visitor clicks play. This avoids loading ~500 KB of YouTube embed scripts on every page view. The block supports an optional section heading, subheading, and caption. Aspect ratio is editor-selectable. Alpine.js manages the play/stopped state; the embed URL is derived server-side from the raw video URL.

---

## User Stories

- **As a site editor**, I want to embed a YouTube or Vimeo video in a page without degrading page load performance so that visitors get a fast first paint and only download the video player when they actually want to watch.
- **As a site editor**, I want to set a custom thumbnail image so I can use a branded or higher-quality frame instead of the platform's auto-generated one.
- **As a site editor**, I want to add an optional heading, subheading, and caption to give the video editorial context without using a separate text block.
- **As a site editor**, I want to choose between a 16:9 and 4:3 aspect ratio so the block fits both widescreen and legacy video formats.
- **As a visitor**, I want the thumbnail to fill the embed area with no layout shift when I click play so the experience feels seamless.
- **As a visitor using keyboard navigation**, I want to focus and activate the play button using the keyboard so I can access the video without a mouse.

---

## Business Value

Embedding videos directly via an `<iframe>` causes a significant performance penalty (extra DNS lookups, large third-party scripts, CLS from unsized iframes). A facade pattern keeps Core Web Vitals — especially LCP and CLS — in a healthy range while still giving editors a one-field video integration. This is a high-reuse pattern needed on About, Case Study, and Services pages.

---

## Acceptance Criteria

### Block Registration

- [ ] Block registered in `inc/register-blocks.php` as `acf/video`.
- [ ] Block preview image placed in `assets/media/block-preview/video.jpg`.
- [ ] ACF field group generated as `acf-json/group_video_block.json`.

### Facade Behaviour

- [ ] On initial render the thumbnail is visible and the iframe is not present in the DOM.
- [ ] Clicking the play button sets `playing = true` in the Alpine.js data object.
- [ ] When `playing` is true the thumbnail and play button are hidden and an `<iframe>` is rendered using `x-show` / conditional rendering.
- [ ] The `<iframe>` `src` is emitted by PHP as an `autoplay=1` embed URL so playback starts immediately after the click.
- [ ] Once `playing` is true the play button is not shown again (no toggle back to thumbnail).

### Embed URL Extraction

- [ ] PHP derives the embed URL from `video_url` before passing it to the template.
- [ ] YouTube URLs (all common formats: `youtube.com/watch?v=`, `youtu.be/`, `youtube.com/shorts/`) are converted to `https://www.youtube.com/embed/{ID}?autoplay=1`.
- [ ] Vimeo URLs (`vimeo.com/{ID}`) are converted to `https://player.vimeo.com/video/{ID}?autoplay=1`.
- [ ] If the URL does not match either pattern the block renders nothing and logs no fatal error.
- [ ] Embed URL derivation is handled by a helper function in `inc/functions-helpers.php` — not inline in the template.

### Thumbnail

- [ ] Thumbnail rendered via `wp_get_attachment_image()` using a registered image size appropriate for the embed width (e.g. `video-thumbnail`).
- [ ] Thumbnail image is not lazy-loaded (`loading="eager"`) because it is an LCP candidate.
- [ ] Thumbnail fills the facade container with `object-fit: cover`.
- [ ] When `playing` is true the thumbnail `<div>` is hidden via `x-show="!playing"`.

### Aspect Ratio

- [ ] Aspect ratio wrapper uses the CSS `aspect-ratio` property.
- [ ] `16:9` maps to `aspect-ratio: 16 / 9`; `4:3` maps to `aspect-ratio: 4 / 3`.
- [ ] The ratio class is conditionally applied from the ACF `aspect_ratio` select field value.
- [ ] The `<iframe>` fills its parent 100% width and height.

### Section Heading & Subheading

- [ ] `section_heading` rendered as `<h2>` when non-empty.
- [ ] `section_subheading` rendered as a `<p class="block-subheading">` when non-empty, following the Step 36 pattern.
- [ ] Caption rendered as a `<figcaption>` inside a `<figure>` that wraps the aspect-ratio container, when the caption field is non-empty.

### Markup & Semantics

- [ ] Play button is a `<button>` element with `aria-label="Play video"`.
- [ ] When `playing` is true the play button has `aria-hidden="true"` or is removed from the DOM via `x-show`.
- [ ] `<iframe>` has `title` attribute set to the section heading value (or a fallback string `"Video"`) for screen readers.
- [ ] `<iframe>` has `allow="autoplay; fullscreen; picture-in-picture"` and `allowfullscreen`.
- [ ] All PHP output escaped (`esc_url()`, `esc_html()`, `esc_attr()`).
- [ ] `make check` passes.

### Accessibility

- [ ] Play button is reachable and activatable via keyboard (it is a native `<button>`).
- [ ] No autoplay on page load — video only plays after explicit user interaction.
- [ ] `@media (prefers-reduced-motion: reduce)` suppresses any decorative transition on the facade (e.g. hover scale on play icon).

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `acf-json/group_video_block.json` | ACF field group — all video block fields |
| `template-parts/blocks/video.php` | Block template — facade container, thumbnail, play button, iframe, heading, caption |
| `src/css/blocks/video.css` | Block styles — aspect ratio wrapper, facade overlay, play button, iframe sizing |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/video` block |
| `inc/functions-helpers.php` | Add `aidriven_get_video_embed_url( string $url ): string` helper |
| `src/css/main.css` | Add `@import "blocks/video.css"` |

---

## ACF Field Group: Video Block

> Generated directly into `acf-json/` — loaded by ACF as Local JSON.

| Field label | Field name | Field type | Notes |
|---|---|---|---|
| Section Heading | `section_heading` | Text | Optional; rendered as `<h2>` |
| Section Subheading | `section_subheading` | Text | Optional; rendered as `<p class="block-subheading">` |
| Video URL | `video_url` | URL | Required; YouTube or Vimeo URL |
| Thumbnail | `thumbnail` | Image | Required; return format: array; LCP candidate |
| Caption | `caption` | Text | Optional; rendered as `<figcaption>` |
| Aspect Ratio | `aspect_ratio` | Select | Choices: `16:9` → "16:9 (Widescreen)", `4:3` → "4:3 (Standard)"; default `16:9` |

---

## Embed URL Helper

The helper lives in `inc/functions-helpers.php` and is called from the block template.

```php
/**
 * Derive an embed URL with autoplay from a YouTube or Vimeo watch URL.
 *
 * @param string $url Raw video URL from the editor.
 * @return string     Embed URL with autoplay=1, or empty string on no match.
 */
function aidriven_get_video_embed_url( string $url ): string {
    // YouTube: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/shorts/ID
    if ( preg_match( '/(?:youtube\.com\/(?:watch\?v=|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $m ) ) {
        return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1';
    }
    // Vimeo: vimeo.com/ID
    if ( preg_match( '/vimeo\.com\/(\d+)/', $url, $m ) ) {
        return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
    }
    return '';
}
```

---

## Alpine.js Pattern

The `x-data` attribute on the facade wrapper contains a single boolean:

```html
<div x-data="{ playing: false }">
```

The thumbnail and play button are shown while `!playing`:

```html
<div x-show="!playing">
    <!-- thumbnail image + play button -->
</div>
```

The iframe is shown only when `playing` is true. Because the `src` is a static string emitted by PHP (with `autoplay=1` already appended), the video starts immediately when Alpine renders the element:

```html
<div x-show="playing">
    <iframe
        src="<?php echo esc_url( $embed_url ); ?>"
        title="<?php echo esc_attr( $iframe_title ); ?>"
        allow="autoplay; fullscreen; picture-in-picture"
        allowfullscreen
    ></iframe>
</div>
```

The play button triggers the state change:

```html
<button
    type="button"
    aria-label="<?php esc_attr_e( 'Play video', 'ai-driven-boilerplate' ); ?>"
    @click="playing = true"
>
    <!-- SVG play icon -->
</button>
```

---

## Aspect Ratio CSS Pattern

Two utility classes are added in `src/css/blocks/video.css`:

```css
.video-ratio-16-9 {
    aspect-ratio: 16 / 9;
}

.video-ratio-4-3 {
    aspect-ratio: 4 / 3;
}
```

The class is selected in PHP before rendering:

```php
$ratio_class = ( '4:3' === $aspect_ratio ) ? 'video-ratio-4-3' : 'video-ratio-16-9';
```

---

## Notes

- Register a custom image size `video-thumbnail` (e.g. 1280 × 720) in `inc/functions-design.php` via `add_image_size()` if a suitable size does not already exist.
- The `thumbnail` field is marked required in ACF, but the template should degrade gracefully if the image is not set (render the iframe facade without a thumbnail layer).
- `loading="eager"` on the thumbnail `<img>` is intentional — the thumbnail is typically the LCP element for this block and must not be deferred.
- The `autoplay=1` parameter in the embed URL requires the iframe to have `allow="autoplay"` or browsers will block autoplay even on user gesture.
- Do not output the `<iframe>` in the initial HTML — it must only appear after the user clicks play. Some crawlers execute JavaScript; emitting the iframe unconditionally would negate the performance benefit of the facade.
- After generating the ACF JSON file, reload the field group in WP admin (ACF → Field Groups → "Sync available") to confirm it loads without errors.

---

## Out of Scope

- Self-hosted video (`<video>` element with `src` pointing to an uploaded file)
- Wistia or other third-party video platforms
- Multiple videos in a single block (use separate block instances)
- Video autoplay on page load (no-interaction autoplay)
- Playlist or chapter support
- Custom play button icon upload via ACF (play icon is theme-defined)
- Poster frame selection (thumbnail is the only frame control given to editors)
