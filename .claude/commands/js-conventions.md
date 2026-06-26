---
name: js-conventions
description: JavaScript conventions — file naming, script structure, DOM guards, AJAX, and Alpine.js notes.
---

## Entry points

`src/js/main.js` (deferred / delayed) and `src/js/critical.js` (blocking, loaded in `<head>`) are the two entry points. Each imported script gets a single-line comment describing its purpose:

```js
// Modal Windows.
import './scripts/handler-modal.js';

// Mobile Navigation.
import './scripts/handler-mobile-nav.js';
```

## File naming

All script files live in `src/js/scripts/` and use kebab-case names with a descriptive prefix:

| Prefix | Purpose | Example |
|---|---|---|
| `handler-` | UI interaction logic (menus, modals, filters) | `handler-modal.js` |
| `design-helper-` | Layout/visual helpers with no business logic | `design-helper-sticky-headline.js` |
| `{page-name}-` | Page-specific scripts | `city-ajax-loader.js` |

## Script structure

**Complex or stateful scripts** — wrap in an IIFE with `'use strict'`:

```js
( function () {
    'use strict';

    const OPEN_CLASS = 'is-open';

    function init() { ... }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} () );
```

**Simple scripts** — plain code inside a `DOMContentLoaded` listener is fine:

```js
document.addEventListener( 'DOMContentLoaded', () => {
    const el = document.querySelector( '.my-element' );
    if ( ! el ) return;
    // ...
} );
```

Always use the `readyState` guard pattern for scripts that may be injected after parse:

```js
if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', init );
} else {
    init();
}
```

## Constants

Define CSS class strings and selectors as named constants in SCREAMING_SNAKE_CASE at the top of the file:

```js
const OPEN_CLASS    = 'is-open';
const HIDDEN_CLASS  = 'hidden';
const FORM_SELECTOR = 'form.my-form';
```

## DOM guard clauses

Always guard every `querySelector` / `getElementById` result before use. Return early if the element is absent — the script may run on pages where the element does not exist:

```js
const container = document.getElementById( 'facilities' );
if ( ! container ) return;
```

## JS hooks

Use `data-*` attributes as JS hooks. CSS classes are for styling; `data-*` attributes signal JS behaviour:

```html
<div data-filter-wrapper>
```

## State management

- **Single-instance state** — module-level `let` variables inside the IIFE or closure.
- **Multi-instance state** (e.g. multiple widgets on one page) — `WeakMap` keyed by the DOM element.
- **Double-init prevention** — set a `data-*-init` attribute on the element after setup and skip elements that already have it.

```js
document.querySelectorAll( '.my-widget:not([data-init])' ).forEach( el => {
    el.dataset.init = '1';
    setup( el );
} );
```

## AJAX

Use the native `fetch` API with `async/await`:

```js
async function fetchData( ajaxUrl, nonce ) {
    const formData = new FormData();
    formData.append( 'action', 'my_action' );
    formData.append( 'nonce', nonce );

    const response = await fetch( ajaxUrl, { method: 'POST', body: formData } );
    if ( ! response.ok ) throw new Error( `Request failed: ${ response.status }` );

    const json = await response.json();
    if ( ! json.success ) throw new Error( json.data?.message || 'Request unsuccessful' );

    return json.data;
}
```

Use `AbortController` to cancel an in-flight request when a new one starts:

```js
let inFlightController = null;

async function load() {
    if ( inFlightController ) inFlightController.abort();
    inFlightController = new AbortController();
    // pass signal: inFlightController.signal to fetch
}
```

## PHP → JS data

Pass AJAX URLs, nonces, and other PHP-side values via `data-*` attributes on the container element:

```php
<div id="facilities"
     data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
     data-nonce="<?php echo esc_attr( wp_create_nonce( 'my_nonce' ) ); ?>">
```

```js
const ajaxUrl = container.dataset.ajaxUrl;
const nonce   = container.dataset.nonce;
```

## Global exposure

Expose a re-init hook on `window` only when the script must be called again after AJAX-injected HTML:

```js
window.myWidgetInit = initAll;
```

## Alpine.js

Use Alpine when explicitly specified for a project or component. Scripts follow the same file and import conventions (`src/js/scripts/`, imported by the relevant entry point).
