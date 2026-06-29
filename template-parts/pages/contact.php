<?php
/**
 * Page: Contact
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
	}
}

// ACF Options — contact details.
$contact_address = get_field( 'address', 'option' );
$contact_phone   = get_field( 'phone_number', 'option' );
$contact_email   = get_field( 'email_address', 'option' );
$maps_url        = get_field( 'google_maps_url', 'option' );
$social_links    = aidriven_get_social_links();

// Strip non-digit / non-plus chars for the tel: href.
$phone_href = $contact_phone ? 'tel:' . preg_replace( '/[^\d+]/', '', $contact_phone ) : '';

$has_details = $contact_address || $contact_phone || $contact_email || $maps_url || ! empty( $social_links );
?>
<main id="main-content" class="contact-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => get_the_excerpt(),
		)
	);
	?>

	<div class="contact-inner">

		<div class="contact-form-col">
			<?php get_template_part( 'template-parts/forms/contact' ); ?>
		</div><!-- .contact-form-col -->

		<?php if ( $has_details ) : ?>
			<aside class="contact-details-col" aria-label="<?php esc_attr_e( 'Contact Details', 'ai-driven-boilerplate' ); ?>">

				<h2 class="contact-details-heading"><?php esc_html_e( 'Get in Touch', 'ai-driven-boilerplate' ); ?></h2>

				<dl class="contact-details-list">

					<?php if ( $contact_address ) : ?>
						<div class="contact-details-item">
							<dt class="contact-details-term"><?php esc_html_e( 'Address', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="contact-details-desc">
								<address class="contact-address">
									<?php echo wp_kses( $contact_address, array( 'br' => array() ) ); ?>
								</address>
							</dd>
						</div>
					<?php endif; ?>

					<?php if ( $contact_phone ) : ?>
						<div class="contact-details-item">
							<dt class="contact-details-term"><?php esc_html_e( 'Phone', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="contact-details-desc">
								<a href="<?php echo esc_url( $phone_href ); ?>" class="contact-details-link">
									<?php echo esc_html( $contact_phone ); ?>
								</a>
							</dd>
						</div>
					<?php endif; ?>

					<?php if ( $contact_email ) : ?>
						<div class="contact-details-item">
							<dt class="contact-details-term"><?php esc_html_e( 'Email', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="contact-details-desc">
								<a href="<?php echo esc_url( 'mailto:' . antispambot( $contact_email ) ); ?>" class="contact-details-link">
									<?php echo esc_html( antispambot( $contact_email ) ); ?>
								</a>
							</dd>
						</div>
					<?php endif; ?>

					<?php if ( $maps_url ) : ?>
						<div class="contact-details-item">
							<dt class="contact-details-term"><?php esc_html_e( 'Location', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="contact-details-desc">
								<a href="<?php echo esc_url( $maps_url ); ?>"
									class="contact-details-link"
									target="_blank"
									rel="noopener noreferrer">
									<?php esc_html_e( 'View on Google Maps', 'ai-driven-boilerplate' ); ?>
									<span class="screen-reader-text"><?php esc_html_e( '(opens in new tab)', 'ai-driven-boilerplate' ); ?></span>
								</a>
							</dd>
						</div>
					<?php endif; ?>

				</dl>

				<?php if ( ! empty( $social_links ) ) : ?>
					<div class="contact-social">
						<p class="contact-social-label"><?php esc_html_e( 'Follow Us', 'ai-driven-boilerplate' ); ?></p>
						<ul class="contact-social-list">
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
										class="contact-social-link"
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

			</aside><!-- .contact-details-col -->
		<?php endif; ?>

	</div><!-- .contact-inner -->

</main><!-- #main-content -->
