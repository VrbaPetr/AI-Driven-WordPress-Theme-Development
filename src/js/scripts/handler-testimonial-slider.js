document.addEventListener( 'alpine:init', function () {
	'use strict';

	window.Alpine.data( 'testimonialSlider', function () {
		return {
			currentIndex: 0,
			total: 0,
			autoplay: false,
			interval: 4000,
			timer: null,
			prefersReducedMotion: false,

			init() {
				this.total                = parseInt( this.$el.dataset.total, 10 ) || 0;
				this.autoplay             = this.$el.dataset.autoplay === '1';
				this.interval             = parseInt( this.$el.dataset.interval, 10 ) || 4000;
				this.prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

				if ( this.autoplay && ! this.prefersReducedMotion ) {
					this.startTimer();
				}

				// Touch swipe support.
				let startX = 0;
				this.$el.addEventListener( 'touchstart', ( e ) => {
					startX = e.changedTouches[ 0 ].screenX;
				}, { passive: true } );
				this.$el.addEventListener( 'touchend', ( e ) => {
					const diff = startX - e.changedTouches[ 0 ].screenX;
					if ( Math.abs( diff ) > 50 ) {
						diff > 0 ? this.next() : this.prev();
					}
				}, { passive: true } );
			},

			destroy() {
				this.stopTimer();
			},

			startTimer() {
				// Always clear any existing timer before creating a new one.
				this.stopTimer();
				this.timer = setInterval( () => this.next(), this.interval );
			},

			stopTimer() {
				if ( this.timer ) {
					clearInterval( this.timer );
					this.timer = null;
				}
			},

			pause() {
				this.stopTimer();
			},

			resume() {
				if ( this.autoplay && ! this.prefersReducedMotion ) {
					this.startTimer();
				}
			},

			// Only resume autoplay when focus truly leaves the component,
			// not when it moves between elements inside it.
			handleFocusOut( event ) {
				if ( ! this.$el.contains( event.relatedTarget ) ) {
					this.resume();
				}
			},

			prev() {
				this.currentIndex = ( this.currentIndex - 1 + this.total ) % this.total;
			},

			next() {
				this.currentIndex = ( this.currentIndex + 1 ) % this.total;
			},

			goTo( index ) {
				this.currentIndex = index;
			},
		};
	} );
} );
