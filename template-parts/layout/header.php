<?php
/**
 * Layout: Header
 *
 * Sticky site header: logo, two-level dropdown navigation (Alpine.js),
 * and a mobile hamburger menu. The .is-scrolled class is toggled by navigation.js.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo = get_field( 'logo_light', 'option' );
?>

<header id="site-header" class="site-header" x-data="{ mobileOpen: false }">

	<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to main content', 'ai-driven-boilerplate' ); ?></a>

	<div class="header-inner">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo" rel="home">
			<?php if ( ! empty( $logo ) ) : ?>
				<img
					src="<?php echo esc_url( $logo['url'] ); ?>"
					alt="<?php echo esc_attr( $logo['alt'] ? $logo['alt'] : get_bloginfo( 'name' ) ); ?>"
					width="<?php echo absint( $logo['width'] ); ?>"
					height="<?php echo absint( $logo['height'] ); ?>"
					loading="eager"
				>
			<?php else : ?>
				<span class="header-logo-text"><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<nav class="header-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'ai-driven-boilerplate' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'walker'         => new Aidriven_Nav_Walker(),
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<button
			class="header-hamburger"
			type="button"
			@click="mobileOpen = ! mobileOpen"
			:aria-expanded="mobileOpen.toString()"
			aria-controls="mobile-menu"
			aria-label="<?php esc_attr_e( 'Toggle navigation', 'ai-driven-boilerplate' ); ?>"
		>
			<span x-show="! mobileOpen" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="3" y1="6" x2="21" y2="6"/>
					<line x1="3" y1="12" x2="21" y2="12"/>
					<line x1="3" y1="18" x2="21" y2="18"/>
				</svg>
			</span>
			<span x-show="mobileOpen" x-cloak aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<line x1="18" y1="6" x2="6" y2="18"/>
					<line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</span>
		</button>

	</div><!-- .header-inner -->

	<div
		id="mobile-menu"
		class="mobile-menu"
		x-show="mobileOpen"
		x-cloak
		x-transition
		@keydown.escape.window="mobileOpen = false"
	>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-nav-list',
				'fallback_cb'    => false,
			)
		);
		?>
	</div><!-- #mobile-menu -->

</header><!-- #site-header -->
