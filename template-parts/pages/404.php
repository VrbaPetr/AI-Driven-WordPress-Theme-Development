<?php
/**
 * Page: 404
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_page_id      = (int) get_option( 'page_for_posts' );
$service_archive   = get_post_type_archive_link( 'service' );
$portfolio_archive = get_post_type_archive_link( 'project' );
$blog_permalink    = $blog_page_id ? get_permalink( $blog_page_id ) : '';

$quick_links = array(
	array(
		'label' => __( 'Services', 'ai-driven-boilerplate' ),
		'url'   => $service_archive ? $service_archive : home_url( '/services/' ),
	),
	array(
		'label' => __( 'Portfolio', 'ai-driven-boilerplate' ),
		'url'   => $portfolio_archive ? $portfolio_archive : home_url( '/portfolio/' ),
	),
	array(
		'label' => __( 'Blog', 'ai-driven-boilerplate' ),
		'url'   => $blog_permalink ? $blog_permalink : home_url( '/blog/' ),
	),
	array(
		'label' => __( 'Contact', 'ai-driven-boilerplate' ),
		'url'   => home_url( '/contact/' ),
	),
);
?>
<main id="main-content" class="not-found-main">
	<div class="not-found-inner">

		<p class="not-found-number" aria-hidden="true">404</p>

		<h1 class="not-found-title"><?php esc_html_e( 'Page Not Found', 'ai-driven-boilerplate' ); ?></h1>

		<p class="not-found-message">
			<?php esc_html_e( "The page you were looking for doesn't exist or may have been moved.", 'ai-driven-boilerplate' ); ?>
		</p>

		<form role="search" method="get" class="search-form not-found-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="search-input-404" class="screen-reader-text">
				<?php esc_html_e( 'Search the site', 'ai-driven-boilerplate' ); ?>
			</label>
			<input
				type="search"
				id="search-input-404"
				class="search-form-input"
				name="s"
				placeholder="<?php esc_attr_e( 'Search…', 'ai-driven-boilerplate' ); ?>"
				autocomplete="off"
			>
			<button type="submit" class="search-form-btn">
				<?php esc_html_e( 'Search', 'ai-driven-boilerplate' ); ?>
			</button>
		</form>

		<div class="not-found-actions">
			<?php
			get_template_part(
				'template-parts/components/button',
				null,
				array(
					'label'   => __( '← Back to Home', 'ai-driven-boilerplate' ),
					'url'     => home_url( '/' ),
					'variant' => 'primary',
					'size'    => 'md',
				)
			);
			?>
		</div>

		<nav class="not-found-links" aria-label="<?php esc_attr_e( 'Quick links', 'ai-driven-boilerplate' ); ?>">
			<p class="not-found-links-label"><?php esc_html_e( 'Quick links:', 'ai-driven-boilerplate' ); ?></p>
			<ul class="not-found-links-list" role="list">
				<?php foreach ( $quick_links as $quick_link ) : ?>
					<li class="not-found-links-item">
						<a href="<?php echo esc_url( $quick_link['url'] ); ?>" class="not-found-links-anchor">
							<?php echo esc_html( $quick_link['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

	</div><!-- .not-found-inner -->
</main><!-- #main-content -->
