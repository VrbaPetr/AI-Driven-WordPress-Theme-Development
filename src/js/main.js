import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin( Collapse );

document.addEventListener( 'alpine:init', () => {
	Alpine.store( 'search', {
		open: false,
		close() {
			this.open = false;
			document.querySelector( '.header-search-btn' )?.focus();
		},
	} );

	Alpine.effect( () => {
		document.body.classList.toggle( 'overflow-hidden', Alpine.store( 'search' ).open );
	} );
} );

Alpine.start();

// Sticky header scroll behaviour and back-to-top button.
import './scripts/navigation.js';

// Smooth scroll with sticky-header offset correction.
import './scripts/smooth-scroll.js';

// Stats count-up animation on scroll.
import './scripts/stats-counter.js';

// Portfolio Grid filter and load-more.
import './scripts/handler-portfolio-filter.js';

// Testimonials slider with autoplay and swipe support.
import './scripts/handler-testimonial-slider.js';

// Social share copy-link button.
import './scripts/handler-share.js';

// Reading progress bar on single posts.
import { initReadingProgress } from './scripts/reading-progress.js';
document.addEventListener( 'DOMContentLoaded', initReadingProgress );
