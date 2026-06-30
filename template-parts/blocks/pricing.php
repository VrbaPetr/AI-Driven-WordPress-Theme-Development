<?php
/**
 * Block: Pricing
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$plans              = get_field( 'plans' );

	if ( empty( $plans ) ) {
		return;
	}

	$plan_count = min( count( $plans ), 4 );
	?>
	<section class="pricing-block">
		<div class="pricing-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="pricing-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $section_subheading ) ) : ?>
			<p class="pricing-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div class="pricing-grid pricing-grid--count-<?php echo esc_attr( (string) $plan_count ); ?>">
				<?php
				foreach ( $plans as $plan ) :
					$plan_name   = isset( $plan['plan_name'] ) ? $plan['plan_name'] : '';
					$price       = isset( $plan['price'] ) ? $plan['price'] : '';
					$billing     = isset( $plan['billing_period'] ) ? $plan['billing_period'] : '';
					$description = isset( $plan['description'] ) ? $plan['description'] : '';
					$features    = isset( $plan['features'] ) ? $plan['features'] : array();
					$cta_label   = isset( $plan['cta_label'] ) ? $plan['cta_label'] : '';
					$cta_url     = isset( $plan['cta_url'] ) ? $plan['cta_url'] : '';
					$is_featured = ! empty( $plan['featured'] );
					$badge_label = isset( $plan['featured_badge_label'] ) ? $plan['featured_badge_label'] : __( 'Most Popular', 'ai-driven-boilerplate' );

					if ( empty( $plan_name ) ) {
						continue;
					}

					$card_class = 'pricing-card';
					if ( $is_featured ) {
						$card_class .= ' pricing-card--featured';
					}
					?>
					<div class="<?php echo esc_attr( $card_class ); ?>">

						<?php if ( $is_featured && ! empty( $badge_label ) ) : ?>
						<div class="pricing-card-badge">
							<span class="pricing-badge-text"><?php echo esc_html( $badge_label ); ?></span>
						</div>
						<?php endif; ?>

						<div class="pricing-card-header">
							<h3 class="pricing-card-name"><?php echo esc_html( $plan_name ); ?></h3>

							<div class="pricing-card-price-row">
								<span class="pricing-card-price"><?php echo esc_html( $price ); ?></span>
								<?php if ( ! empty( $billing ) ) : ?>
								<span class="pricing-card-period"><?php echo esc_html( $billing ); ?></span>
								<?php endif; ?>
							</div>

							<?php if ( ! empty( $description ) ) : ?>
							<p class="pricing-card-desc"><?php echo esc_html( $description ); ?></p>
							<?php endif; ?>
						</div>

						<?php if ( ! empty( $features ) ) : ?>
						<ul class="pricing-card-features" role="list">
							<?php
							foreach ( $features as $feature_item ) :
								$feature_text     = isset( $feature_item['feature_text'] ) ? $feature_item['feature_text'] : '';
								$feature_included = ! empty( $feature_item['included'] );

								if ( empty( $feature_text ) ) {
									continue;
								}

								$icon_name  = $feature_included ? 'circle-check' : 'circle-x';
								$icon_path  = aidriven_get_icon_path( $icon_name );
								$item_class = 'pricing-feature' . ( $feature_included ? '' : ' pricing-feature--excluded' );
								$sr_prefix  = $feature_included
									? __( 'Included:', 'ai-driven-boilerplate' )
									: __( 'Not included:', 'ai-driven-boilerplate' );
								?>
								<li class="<?php echo esc_attr( $item_class ); ?>">
									<?php if ( $icon_path ) : ?>
									<span class="pricing-feature-icon" aria-hidden="true">
										<?php include $icon_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable ?>
									</span>
									<?php endif; ?>
									<span class="pricing-feature-text">
										<span class="screen-reader-text"><?php echo esc_html( $sr_prefix ); ?> </span><?php echo esc_html( $feature_text ); ?>
									</span>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php endif; ?>

						<?php
						if ( ! empty( $cta_label ) && ! empty( $cta_url ) ) :
							get_template_part(
								'template-parts/components/button',
								null,
								array(
									'label'   => $cta_label,
									'url'     => $cta_url,
									'variant' => $is_featured ? 'primary' : 'outline',
									'size'    => 'md',
									'classes' => 'pricing-card-cta',
								)
							);
						endif;
						?>

					</div><!-- .pricing-card -->
				<?php endforeach; ?>
			</div><!-- .pricing-grid -->

		</div><!-- .pricing-inner -->
	</section>
<?php endif; ?>
