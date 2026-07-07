( function () {
	'use strict';

	const SCROLLED_CLASS   = 'is-scrolled';
	const SCROLL_THRESHOLD = 50;
	const VISIBLE_CLASS    = 'is-visible';
	const BTN_THRESHOLD    = 300;

	const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function init() {
		const header = document.getElementById( 'site-header' );
		const btn    = document.querySelector( '.back-to-top' );

		if ( ! header && ! btn ) return;

		function onScroll() {
			if ( header ) {
				header.classList.toggle( SCROLLED_CLASS, window.scrollY > SCROLL_THRESHOLD );
			}
			if ( btn ) {
				btn.classList.toggle( VISIBLE_CLASS, window.scrollY > BTN_THRESHOLD );
			}
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();

		// ── Back to top ─────────────────────────────────────────────────────

		if ( ! btn ) return;

		btn.removeAttribute( 'hidden' );

		btn.addEventListener( 'click', function () {
			window.scrollTo( {
				top:      0,
				behavior: prefersReducedMotion ? 'instant' : 'smooth',
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
