export function initReadingProgress() {
	if ( ! document.body.classList.contains( 'single-post' ) ) {
		return;
	}

	const bar = document.querySelector( '.reading-progress-bar' );
	if ( ! bar ) {
		return;
	}

	window.addEventListener(
		'scroll',
		() => {
			const scrollable = document.documentElement.scrollHeight - window.innerHeight;
			const pct        = scrollable > 0 ? ( window.scrollY / scrollable ) * 100 : 0;
			bar.style.width  = Math.min( 100, Math.max( 0, pct ) ) + '%';
		},
		{ passive: true }
	);
}
