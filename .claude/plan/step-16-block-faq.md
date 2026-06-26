# Step 16 — FAQ Accordion Block

**Phase:** 4 — Content Blocks  
**Depends on:** Step 01  
**Required by:** Steps 23, 25

---

## Summary

Build an ACF Gutenberg block for Frequently Asked Questions. Questions and answers are entered as a repeater in the block editor. Items expand/collapse via Alpine.js. The block outputs JSON-LD FAQ structured data for Google rich snippets.

---

## User Stories

- **As a site editor**, I want to add a FAQ section to any page by inserting this block and filling in questions so I don't need a separate FAQ plugin.
- **As a visitor**, I want to click a question to reveal the answer without the page reloading so browsing FAQs feels fast.
- **As an SEO manager**, I want FAQ structured data output automatically so Google can show expanded Q&A in search results without extra configuration.

---

## Business Value

FAQ content directly addresses objections before a prospect contacts the company. FAQ schema can win "People Also Ask" positions in Google, increasing organic traffic without additional content investment.

---

## Acceptance Criteria

- [ ] Block registered in `inc/register-blocks.php`.
- [ ] ACF field group JSON file generated directly into `acf-json/`.
- [ ] Section heading field (optional `<h2>`).
- [ ] FAQ items defined via ACF Repeater: question (Text) + answer (WYSIWYG, restricted).
- [ ] Each item renders as a disclosure widget: question is the trigger (`<button>`), answer is the panel (`<div>`).
- [ ] Alpine.js controls open/close state; smooth height transition via CSS.
- [ ] Multiple items can be open simultaneously (no "only one open" restriction).
- [ ] JSON-LD `FAQPage` schema block output inside `<script type="application/ld+json">` — includes all Q&A pairs, answer stripped of HTML tags.
- [ ] Block preview image in `assets/media/block-preview/`.
- [ ] All output escaped; answer HTML sanitised with `wp_kses_post()`.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `template-parts/blocks/faq.php` | Block template + JSON-LD output |
| `src/css/blocks/faq.css` | Accordion styles |

### Files to Modify

| File | Change |
|---|---|
| `inc/register-blocks.php` | Register `acf/faq` block |
| `src/css/main.css` | Import `blocks/faq.css` |

---

## ACF Field Group: FAQ Block

| Field label | Field type | Notes |
|---|---|---|
| Section Heading | Text | Optional; `<h2>` |
| FAQ Items | Repeater | |
| — Question | Text | Plain text; used in schema and as button label |
| — Answer | WYSIWYG | Restricted toolbar: bold, italic, links, lists |

---

## Alpine.js Pattern

```html
<div x-data="{}">
  <template x-for="(item, index) in items">
    <div x-data="{ open: false }">
      <button @click="open = !open" :aria-expanded="open">
        Question text
      </button>
      <div x-show="open" x-collapse>
        Answer content
      </div>
    </div>
  </template>
</div>
```

In the PHP template, each item is a static HTML element with `x-data="{ open: false }"` — no JS loop needed since data is rendered server-side.

---

## JSON-LD Schema Pattern

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Question text?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Answer text (HTML stripped)."
      }
    }
  ]
}
```

---

## Accessibility

- [ ] Each question is a `<button>` (not an `<a>`)
- [ ] `aria-expanded` reflects open/closed state
- [ ] `aria-controls` links button to panel `id`
- [ ] Answer panel has matching `id` attribute
- [ ] No content hidden from assistive tech when panel is open

---

## Out of Scope

- FAQs CPT backend (not selected in survey — FAQ block uses manual repeater only)
- "Only one open at a time" accordion mode (not required)
