( function () {
	'use strict';

	const COPY_SELECTOR = '[data-share-copy]';
	const COPIED_CLASS  = 'is-copied';
	const RESET_DELAY   = 2000;

	function init() {
		document.querySelectorAll( COPY_SELECTOR ).forEach( function ( btn ) {
			if ( btn.dataset.shareInit ) {
				return;
			}
			btn.dataset.shareInit = '1';

			btn.addEventListener( 'click', function () {
				if ( ! navigator.clipboard ) {
					return;
				}

				var url           = btn.dataset.shareCopy || window.location.href;
				var originalLabel = btn.getAttribute( 'aria-label' );
				var copiedLabel   = btn.dataset.copiedLabel || 'Copied!';

				navigator.clipboard.writeText( url ).then( function () {
					btn.classList.add( COPIED_CLASS );
					btn.setAttribute( 'aria-label', copiedLabel );

					setTimeout( function () {
						btn.classList.remove( COPIED_CLASS );
						if ( originalLabel ) {
							btn.setAttribute( 'aria-label', originalLabel );
						}
					}, RESET_DELAY );
				} );
			} );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} () );
