<?php
/**
 * Block: Hero
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$variant         = get_field( 'variant' );
	$variant         = $variant ? $variant : 'image-bg';
	$is_page_title   = get_field( 'is_page_title' );
	$heading         = get_field( 'heading' );
	$subheading      = get_field( 'subheading' );
	$primary_label   = get_field( 'primary_cta_label' );
	$primary_url     = get_field( 'primary_cta_url' );
	$secondary_label = get_field( 'secondary_cta_label' );
	$secondary_url   = get_field( 'secondary_cta_url' );
	$bg_image        = get_field( 'background_image' );
	$overlay_opacity = get_field( 'overlay_opacity' );
	$text_alignment  = get_field( 'text_alignment' );
	$text_alignment  = $text_alignment ? $text_alignment : 'left';

	if ( empty( $heading ) ) {
		return;
	}

	$heading_tag     = $is_page_title ? 'h1' : 'h2';
	$overlay_opacity = is_numeric( $overlay_opacity ) ? (int) $overlay_opacity : 50;
	$has_primary     = ! empty( $primary_label ) && ! empty( $primary_url );
	$has_secondary   = ! empty( $secondary_label ) && ! empty( $secondary_url );

	// Section classes.
	$hero_classes = 'hero hero--' . esc_attr( $variant );
	if ( 'image-bg' === $variant && 'centre' === $text_alignment ) {
		$hero_classes .= ' hero--centre';
	}

	if ( 'image-bg' === $variant ) :
		$bg_url        = ! empty( $bg_image ) ? wp_get_attachment_image_url( $bg_image['ID'], 'hero-full' ) : '';
		$section_style = $bg_url ? 'background-image: url(\'' . esc_url( $bg_url ) . '\');' : '';
		?>
		<section class="<?php echo esc_attr( $hero_classes ); ?>"<?php echo $section_style ? ' style="' . esc_attr( $section_style ) . '"' : ''; ?>>
			<div class="hero-overlay" style="--hero-overlay-opacity: <?php echo esc_attr( $overlay_opacity / 100 ); ?>;"></div>
			<div class="hero-inner">
				<div class="hero-content">
					<<?php echo esc_html( $heading_tag ); ?> class="hero-heading"><?php echo esc_html( $heading ); ?></<?php echo esc_html( $heading_tag ); ?>>
					<?php if ( ! empty( $subheading ) ) : ?>
					<p class="hero-subheading"><?php echo esc_html( $subheading ); ?></p>
					<?php endif; ?>
					<?php if ( $has_primary || $has_secondary ) : ?>
					<div class="hero-ctas">
						<?php if ( $has_primary ) : ?>
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'   => $primary_label,
									'url'     => $primary_url,
									'variant' => 'primary',
									'size'    => 'lg',
								)
							);
							?>
						<?php endif; ?>
						<?php if ( $has_secondary ) : ?>
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'   => $secondary_label,
									'url'     => $secondary_url,
									'variant' => 'outline',
									'size'    => 'lg',
								)
							);
							?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php else : ?>
		<section class="<?php echo esc_attr( $hero_classes ); ?>">
			<div class="hero-inner">
				<div class="hero-content">
					<<?php echo esc_html( $heading_tag ); ?> class="hero-heading"><?php echo esc_html( $heading ); ?></<?php echo esc_html( $heading_tag ); ?>>
					<?php if ( ! empty( $subheading ) ) : ?>
					<p class="hero-subheading"><?php echo esc_html( $subheading ); ?></p>
					<?php endif; ?>
					<?php if ( $has_primary || $has_secondary ) : ?>
					<div class="hero-ctas">
						<?php if ( $has_primary ) : ?>
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'   => $primary_label,
									'url'     => $primary_url,
									'variant' => 'primary',
									'size'    => 'lg',
								)
							);
							?>
						<?php endif; ?>
						<?php if ( $has_secondary ) : ?>
							<?php
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'   => $secondary_label,
									'url'     => $secondary_url,
									'variant' => 'outline',
									'size'    => 'lg',
								)
							);
							?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $bg_image ) ) : ?>
				<div class="hero-media">
					<?php echo wp_get_attachment_image( $bg_image['ID'], 'hero-split' ); ?>
				</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
<?php endif; ?>
