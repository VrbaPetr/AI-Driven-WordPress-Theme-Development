( function () {
    'use strict';

    const COUNTER_SELECTOR = '[data-counter]';
    const DURATION_DEFAULT = 1800;

    function easeOutQuad( t ) {
        return t * ( 2 - t );
    }

    function animateCounter( el ) {
        const target   = parseFloat( el.dataset.target );
        const duration = parseInt( el.dataset.duration, 10 ) || DURATION_DEFAULT;
        const decimals = ( el.dataset.target.indexOf( '.' ) !== -1 )
            ? el.dataset.target.split( '.' )[ 1 ].length
            : 0;
        const start    = performance.now();

        function step( now ) {
            const elapsed  = now - start;
            const progress = Math.min( elapsed / duration, 1 );
            const value    = easeOutQuad( progress ) * target;

            el.textContent = value.toFixed( decimals );

            if ( progress < 1 ) {
                requestAnimationFrame( step );
            } else {
                el.textContent = target.toFixed( decimals );
            }
        }

        requestAnimationFrame( step );
    }

    function init() {
        const counters = document.querySelectorAll( COUNTER_SELECTOR );
        if ( ! counters.length ) {
            return;
        }

        const prefersReduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

        if ( prefersReduced ) {
            counters.forEach( function ( el ) {
                const decimals = ( el.dataset.target.indexOf( '.' ) !== -1 )
                    ? el.dataset.target.split( '.' )[ 1 ].length
                    : 0;
                el.textContent = parseFloat( el.dataset.target ).toFixed( decimals );
            } );
            return;
        }

        const observer = new IntersectionObserver(
            function ( entries ) {
                entries.forEach( function ( entry ) {
                    if ( entry.isIntersecting ) {
                        animateCounter( entry.target );
                        observer.unobserve( entry.target );
                    }
                } );
            },
            { threshold: 0.3 }
        );

        counters.forEach( function ( el ) {
            observer.observe( el );
        } );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', init );
    } else {
        init();
    }
} () );
