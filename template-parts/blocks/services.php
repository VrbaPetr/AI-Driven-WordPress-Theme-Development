<?php
/**
 * Block: Services
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading = get_field( 'section_heading' );
	$content_source  = get_field( 'content_source' );
	$number_of_items = get_field( 'number_of_items' );
	$filter_category = get_field( 'filter_by_category' );
	$service_cards   = get_field( 'service_cards' );
	$show_view_all   = get_field( 'show_view_all' );
	$view_all_label  = get_field( 'view_all_label' );

	// Defaults.
	$content_source  = $content_source ? $content_source : 'cpt_query';
	$number_of_items = $number_of_items ? absint( $number_of_items ) : 6;
	$view_all_label  = $view_all_label ? $view_all_label : __( 'View all services', 'ai-driven-boilerplate' );

	// Build normalised cards array from either source.
	$cards = array();

	if ( 'cpt_query' === $content_source ) {
		$query_args = array(
			'post_type'      => 'service',
			'posts_per_page' => $number_of_items,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		);

		if ( ! empty( $filter_category ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'service-category',
					'field'    => 'term_id',
					'terms'    => $filter_category,
				),
			);
		}

		$services_query = new WP_Query( $query_args );

		if ( $services_query->have_posts() ) {
			while ( $services_query->have_posts() ) {
				$services_query->the_post();
				$cards[] = array(
					'icon'  => get_field( 'service_icon', get_the_ID() ),
					'title' => get_the_title(),
					'desc'  => get_field( 'short_description', get_the_ID() ),
					'url'   => get_permalink(),
				);
			}
			wp_reset_postdata();
		}
	} elseif ( ! empty( $service_cards ) ) {
		foreach ( $service_cards as $card ) {
			$cards[] = array(
				'icon'  => isset( $card['icon'] ) ? $card['icon'] : '',
				'title' => isset( $card['title'] ) ? $card['title'] : '',
				'desc'  => isset( $card['description'] ) ? $card['description'] : '',
				'url'   => isset( $card['link_url'] ) ? $card['link_url'] : '',
			);
		}
	}

	if ( empty( $cards ) ) {
		return;
	}

	$services_archive_url = get_post_type_archive_link( 'service' );
	?>
	<section class="services-block">
		<div class="services-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="services-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>

			<ul class="services-grid" role="list">
				<?php foreach ( $cards as $card ) : ?>
					<?php
					$card_icon  = $card['icon'];
					$card_title = $card['title'];
					$card_desc  = $card['desc'];
					$card_url   = $card['url'];

					if ( empty( $card_title ) ) {
						continue;
					}
					?>
					<li class="services-card">

						<?php
						if ( ! empty( $card_icon ) ) :
							$icon_name = sanitize_file_name( basename( $card_icon ) );
							$icon_path = get_template_directory() . '/assets/media/icons/' . $icon_name . '.svg';
							if ( file_exists( $icon_path ) ) :
								?>
						<span class="services-card-icon" aria-hidden="true"><?php include $icon_path; ?></span>
								<?php
						endif;
						endif;
						?>

						<h3 class="services-card-title">
							<?php if ( ! empty( $card_url ) ) : ?>
							<a href="<?php echo esc_url( $card_url ); ?>"><?php echo esc_html( $card_title ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $card_title ); ?>
							<?php endif; ?>
						</h3>

						<?php if ( ! empty( $card_desc ) ) : ?>
						<p class="services-card-desc"><?php echo esc_html( $card_desc ); ?></p>
						<?php endif; ?>

					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $show_view_all && $services_archive_url ) : ?>
			<div class="services-footer">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => $view_all_label,
						'url'     => $services_archive_url,
						'variant' => 'outline',
						'size'    => 'md',
					)
				);
				?>
			</div>
			<?php endif; ?>

		</div>
	</section>
<?php endif; ?>
