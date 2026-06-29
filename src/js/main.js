import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin( Collapse );
Alpine.start();

// Sticky header scroll behaviour.
import './scripts/navigation.js';

// Stats count-up animation on scroll.
import './scripts/stats-counter.js';

// Portfolio Grid filter and load-more.
import './scripts/handler-portfolio-filter.js';
