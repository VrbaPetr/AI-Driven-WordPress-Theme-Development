( function () {
	'use strict';

	const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function init() {
		document.addEventListener( 'click', function ( e ) {
			const link = e.target.closest( 'a[href^="#"]' );
			if ( ! link ) return;

			const href = link.getAttribute( 'href' );
			if ( href === '#' ) return;

			if ( e.ctrlKey || e.metaKey || e.shiftKey || e.altKey ) return;

			const targetId = href.slice( 1 );
			const target   = document.getElementById( targetId );
			if ( ! target ) return;

			e.preventDefault();

			const header       = document.getElementById( 'site-header' );
			const headerHeight = header ? header.getBoundingClientRect().height : 0;
			const correctedY   = target.getBoundingClientRect().top + window.scrollY - headerHeight;

			window.scrollTo( {
				top:      correctedY,
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
