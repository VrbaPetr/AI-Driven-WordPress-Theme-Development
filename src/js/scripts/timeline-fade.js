( function () {
    'use strict';

    const ITEM_SELECTOR = '[data-timeline-item]';

    function init() {
        const items = document.querySelectorAll( ITEM_SELECTOR );
        if ( ! items.length ) {
            return;
        }

        const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
        if ( prefersReduced ) {
            return;
        }

        items.forEach( function ( item ) {
            item.classList.add( 'is-hidden' );
        } );

        const observer = new IntersectionObserver(
            function ( entries ) {
                entries.forEach( function ( entry ) {
                    if ( entry.isIntersecting ) {
                        entry.target.classList.remove( 'is-hidden' );
                        observer.unobserve( entry.target );
                    }
                } );
            },
            { threshold: 0.2 }
        );

        items.forEach( function ( item ) {
            observer.observe( item );
        } );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} () );
