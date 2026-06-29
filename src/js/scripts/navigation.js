( function () {
	'use strict';

	const SCROLLED_CLASS    = 'is-scrolled';
	const SCROLL_THRESHOLD  = 50;

	function init() {
		const header = document.getElementById( 'site-header' );
		if ( ! header ) return;

		function onScroll() {
			header.classList.toggle( SCROLLED_CLASS, window.scrollY > SCROLL_THRESHOLD );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
