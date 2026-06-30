<?php
/**
 * Component: Search Overlay
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$overlay_heading   = get_field( 'search_overlay_heading', 'option' );
$input_placeholder = get_field( 'search_input_placeholder', 'option' );

if ( ! $input_placeholder ) {
	$input_placeholder = __( 'Search…', 'ai-driven-boilerplate' );
}
?>
<div
	class="search-overlay"
	role="dialog"
	aria-modal="true"
	aria-label="<?php esc_attr_e( 'Site search', 'ai-driven-boilerplate' ); ?>"
	x-show="$store.search.open"
	x-cloak
	x-data="{
		trapFocus( e ) {
			const focusable = [ $refs.searchInput, $refs.closeBtn ];
			const first     = focusable[0];
			const last      = focusable[ focusable.length - 1 ];
			if ( e.shiftKey ) {
				if ( document.activeElement === first ) { e.preventDefault(); last.focus(); }
			} else {
				if ( document.activeElement === last )  { e.preventDefault(); first.focus(); }
			}
		}
	}"
	x-init="$watch( '$store.search.open', opened => { if ( opened ) $nextTick( () => $refs.searchInput.focus() ) } )"
	x-transition:enter="transition-opacity ease-out duration-200"
	x-transition:enter-start="opacity-0"
	x-transition:enter-end="opacity-100"
	x-transition:leave="transition-opacity ease-in duration-150"
	x-transition:leave-start="opacity-100"
	x-transition:leave-end="opacity-0"
	@keydown.tab="trapFocus( $event )"
	@keydown.escape.window="$store.search.close()"
	@click.self="$store.search.close()"
>
	<div class="search-overlay-panel">

		<button
			class="search-overlay-close"
			type="button"
			x-ref="closeBtn"
			@click="$store.search.close()"
			aria-label="<?php esc_attr_e( 'Close search', 'ai-driven-boilerplate' ); ?>"
		>
			<?php echo aidriven_get_svg_icon( 'ui/x-close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme's own files. ?>
		</button>

		<?php if ( $overlay_heading ) : ?>
			<h2 class="search-overlay-heading"><?php echo esc_html( $overlay_heading ); ?></h2>
		<?php endif; ?>

		<form class="search-overlay-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="search-overlay-input" class="sr-only">
				<?php esc_html_e( 'Search', 'ai-driven-boilerplate' ); ?>
			</label>
			<input
				id="search-overlay-input"
				class="search-overlay-input"
				type="search"
				name="s"
				x-ref="searchInput"
				placeholder="<?php echo esc_attr( $input_placeholder ); ?>"
				autocomplete="off"
			>
			<button
				class="search-overlay-submit"
				type="submit"
				aria-label="<?php esc_attr_e( 'Submit search', 'ai-driven-boilerplate' ); ?>"
			>
				<?php echo aidriven_get_svg_icon( 'ui/search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme's own files. ?>
				<span class="sr-only"><?php esc_html_e( 'Search', 'ai-driven-boilerplate' ); ?></span>
			</button>
		</form>

	</div><!-- .search-overlay-panel -->
</div><!-- .search-overlay -->
