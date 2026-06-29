# Step 33 — SEO Foundations

**Phase:** 7 — Forms & Site Utilities  
**Depends on:** Steps 01, 04  
**Required by:** nothing (end feature)

---

## Summary

Consolidate and formalise SEO-related output into `inc/functions-seo.php`: Open Graph and Twitter Card meta tags for all page types, Organisation schema, Article schema on blog posts, and a site-wide canonical URL output. FAQ schema is already handled in Step 16 — this step covers everything else.

---

## User Stories

- **As a site owner**, I want Open Graph tags on every page so when links are shared on LinkedIn or Facebook they show the correct title, description, and image.
- **As an SEO manager**, I want Organisation structured data output so Google understands the business entity behind the site.
- **As a content editor**, I want Article schema on blog posts automatically so I don't need to configure a schema plugin for each post.

---

## Business Value

OG tags and structured data are zero-marginal-effort SEO wins at the theme level. Getting them right in the boilerplate means every new client site starts with correct social sharing and schema out of the box.

---

## Acceptance Criteria

### Open Graph Tags (all page types)

- [ ] `og:title` — post/page title or site name for the front page.
- [ ] `og:description` — post excerpt, page excerpt, or site tagline from options.
- [ ] `og:url` — canonical URL (`get_permalink()` or home URL).
- [ ] `og:type` — `website` for front page, `article` for posts, `website` for all others.
- [ ] `og:image` — featured image URL if set; fallback to logo from options.
- [ ] `og:image:width` and `og:image:height` when image dimensions are available.
- [ ] `og:site_name` — company name from options.

### Twitter Card Tags

- [ ] `twitter:card` — `summary_large_image` when featured image exists; `summary` otherwise.
- [ ] `twitter:title`, `twitter:description`, `twitter:image`.

### Organisation Schema (site-wide, output on front page only)

```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Company Name",
  "url": "https://example.com",
  "logo": "https://example.com/logo.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+1-555-555-5555",
    "contactType": "Customer Service"
  },
  "sameAs": ["https://linkedin.com/company/...", "https://github.com/..."]
}
```

### Article Schema (single blog posts only)

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "Post Title",
  "author": { "@type": "Person", "name": "Author Name" },
  "datePublished": "2024-01-01",
  "dateModified": "2024-01-15",
  "image": "https://example.com/featured.jpg",
  "publisher": {
    "@type": "Organization",
    "name": "Company Name",
    "logo": { "@type": "ImageObject", "url": "https://example.com/logo.png" }
  }
}
```

### Canonical URL

- [ ] `<link rel="canonical" href="...">` output for all pages using `wp_get_canonical_url()`.

### Implementation

- [ ] All meta output hooked to `wp_head` with priority 1.
- [ ] Separate helper functions per output type: `aidriven_og_meta()`, `aidriven_twitter_meta()`, `aidriven_organisation_schema()`, `aidriven_article_schema()`.
- [ ] Functions check for SEO plugin presence (`Yoast`, `RankMath`) and skip output to avoid duplication.
- [ ] `make check` passes.

---

## Technical Scope

### Files to Create

| File | Purpose |
|---|---|
| `inc/functions-seo.php` | All SEO output functions and hooks |

### Files to Modify

| File | Change |
|---|---|
| `functions.php` | `require_once` `inc/functions-seo.php` |

---

## SEO Plugin Conflict Guard

```php
function aidriven_og_meta() {
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
        return; // Yoast or RankMath is active — skip
    }
    // output OG tags
}
```

---

## Accessibility

Not directly applicable — this step produces `<head>` meta only.

---

## Out of Scope

- Sitemap generation (WordPress 5.5+ built-in, or use Yoast/RankMath)
- Robots.txt management
- Hreflang tags for multilingual sites (WPML/Polylang handle this)
- BreadcrumbList schema (handled in Step 10, Breadcrumbs component)
- FAQ schema (handled in Step 16, FAQ Block)
