<?php
/**
 * Layout: Footer
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_light       = get_field( 'logo_light', 'option' );
$company_name     = get_field( 'company_name', 'option' );
$tagline          = get_field( 'tagline', 'option' );
$footer_desc      = get_field( 'footer_description', 'option' );
$copyright_text   = get_field( 'copyright_text', 'option' );
$show_back_to_top = get_field( 'show_back_to_top', 'option' );
$social_links     = aidriven_get_social_links();

$display_name = $company_name ? $company_name : get_bloginfo( 'name' );
?>
<footer class="site-footer" aria-label="<?php esc_attr_e( 'Site Footer', 'ai-driven-boilerplate' ); ?>">

	<div class="footer-inner">

		<?php /* Brand column */ ?>
		<div class="footer-brand">

			<?php if ( $logo_light && ! empty( $logo_light['ID'] ) ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php echo esc_attr( $display_name ); ?>">
					<?php
					echo wp_get_attachment_image(
						$logo_light['ID'],
						'full',
						false,
						array(
							'class' => 'footer-logo-img',
							'alt'   => esc_attr( $logo_light['alt'] ? $logo_light['alt'] : $display_name ),
						)
					);
					?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo footer-logo-text">
					<?php echo esc_html( $display_name ); ?>
				</a>
			<?php endif; ?>

			<?php if ( $tagline ) : ?>
				<p class="footer-tagline"><?php echo esc_html( $tagline ); ?></p>
			<?php endif; ?>

			<?php if ( $footer_desc ) : ?>
				<p class="footer-description">
					<?php echo wp_kses( $footer_desc, array( 'br' => array() ) ); ?>
				</p>
			<?php endif; ?>

		</div>

		<?php /* Navigation column */ ?>
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'ai-driven-boilerplate' ); ?>">
				<p class="footer-col-heading"><?php esc_html_e( 'Navigation', 'ai-driven-boilerplate' ); ?></p>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'footer-nav-list',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php /* Social column */ ?>
		<?php if ( $social_links ) : ?>
			<div class="footer-social">
				<p class="footer-col-heading"><?php esc_html_e( 'Follow Us', 'ai-driven-boilerplate' ); ?></p>
				<ul class="footer-social-list">
					<?php foreach ( $social_links as $social_link ) : ?>
						<?php
						if ( empty( $social_link['url'] ) ) {
							continue;
						}
						$aria_label = ! empty( $social_link['label'] ) ? $social_link['label'] : ucfirst( $social_link['platform'] );
						$icon       = aidriven_get_svg_icon( $social_link['platform'] );
						?>
						<li>
							<a href="<?php echo esc_url( $social_link['url'] ); ?>"
								class="footer-social-link"
								aria-label="<?php echo esc_attr( $aria_label ); ?>"
								target="_blank"
								rel="noopener noreferrer">
								<?php if ( $icon ) : ?>
									<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme's own files. ?>
								<?php else : ?>
									<?php echo esc_html( ucfirst( $social_link['platform'] ) ); ?>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

	</div>

	<div class="footer-bottom">
		<div class="footer-bottom-inner">
			<p class="footer-copy">
				<?php if ( $copyright_text ) : ?>
					<?php echo esc_html( $copyright_text ); ?>
				<?php else : ?>
					&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( $display_name ); ?>. <?php esc_html_e( 'All rights reserved.', 'ai-driven-boilerplate' ); ?>
				<?php endif; ?>
			</p>
		</div>
	</div>


	<?php if ( false !== $show_back_to_top && $show_back_to_top ) : ?>
		<button class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'ai-driven-boilerplate' ); ?>" hidden>
			<?php echo aidriven_get_svg_icon( 'ui/arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme's own files. ?>
		</button>
	<?php endif; ?>

</footer>
