export function initReadingProgress() {
	if ( ! document.body.classList.contains( 'single-post' ) ) {
		return;
	}

	const bar     = document.querySelector( '.reading-progress-bar' );
	const article = document.querySelector( '.article-body' );
	if ( ! bar || ! article ) {
		return;
	}

	window.addEventListener(
		'scroll',
		() => {
			const rect  = article.getBoundingClientRect();
			const start = rect.top + window.scrollY;
			const end   = start + article.offsetHeight - window.innerHeight;
			const pct   = end > start ? ( ( window.scrollY - start ) / ( end - start ) ) * 100 : 0;
			bar.style.width = Math.min( 100, Math.max( 0, pct ) ) + '%';
		},
		{ passive: true }
	);
}
