<?php
/**
 * Block: Team
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$number_of_members  = get_field( 'number_of_members' );
	$columns            = get_field( 'columns' );

	// Defaults.
	$posts_per_page = ( $number_of_members && absint( $number_of_members ) > 0 ) ? absint( $number_of_members ) : -1;
	$columns        = in_array( $columns, array( '2', '3', '4' ), true ) ? $columns : '3';

	// Query team members.
	$team_query = new WP_Query(
		array(
			'post_type'      => 'team-member',
			'posts_per_page' => $posts_per_page,
			'post_status'    => 'publish',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	if ( ! $team_query->have_posts() ) {
		return;
	}

	$allowed_platforms = array( 'linkedin', 'github', 'twitter', 'instagram', 'dribbble', 'email' );
	$platform_labels   = array(
		'linkedin'  => 'LinkedIn',
		'github'    => 'GitHub',
		'twitter'   => 'Twitter / X',
		'instagram' => 'Instagram',
		'dribbble'  => 'Dribbble',
		'email'     => 'Email',
	);
	?>
	<section class="team-block">
		<div class="team-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="team-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $section_subheading ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<ul class="team-grid team-grid--cols-<?php echo esc_attr( $columns ); ?>" role="list">
				<?php
				while ( $team_query->have_posts() ) :
					$team_query->the_post();

					$member_id    = get_the_ID();
					$member_name  = get_the_title();
					$job_title    = get_field( 'job_title', $member_id );
					$short_bio    = get_field( 'short_bio', $member_id );
					$social_links = get_field( 'social_links', $member_id );
					$thumbnail_id = get_post_thumbnail_id( $member_id );

					// Build accessible alt text.
					$alt_text = trim( $member_name . ( $job_title ? ', ' . $job_title : '' ) );
					?>
					<li class="team-card">

						<?php if ( $thumbnail_id ) : ?>
						<div class="team-card-photo">
							<?php
							echo wp_get_attachment_image(
								$thumbnail_id,
								'team-portrait',
								false,
								array(
									'class'   => 'team-card-img',
									'alt'     => esc_attr( $alt_text ),
									'loading' => 'lazy',
								)
							);
							?>
						</div>
						<?php endif; ?>

						<div class="team-card-body">

							<h3 class="team-card-name"><?php echo esc_html( $member_name ); ?></h3>

							<?php if ( ! empty( $job_title ) ) : ?>
							<p class="team-card-role"><?php echo esc_html( $job_title ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $short_bio ) ) : ?>
							<p class="team-card-bio"><?php echo esc_html( wp_trim_words( $short_bio, 20, '…' ) ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $social_links ) ) : ?>
							<ul class="team-card-social" role="list">
								<?php
								foreach ( $social_links as $social_item ) :
									$platform = isset( $social_item['platform'] ) ? $social_item['platform'] : '';
									$url      = isset( $social_item['url'] ) ? $social_item['url'] : '';

									if ( empty( $url ) || ! in_array( $platform, $allowed_platforms, true ) ) {
										continue;
									}

									$icon_path      = aidriven_get_icon_path( $platform );
									$platform_label = isset( $platform_labels[ $platform ] ) ? $platform_labels[ $platform ] : ucfirst( $platform );
									/* translators: 1: person name, 2: social platform name */
									$aria_label = sprintf( __( '%1$s on %2$s', 'ai-driven-boilerplate' ), $member_name, $platform_label );
									?>
									<li class="team-card-social-item">
										<a
											href="<?php echo esc_url( $url ); ?>"
											class="team-card-social-link"
											aria-label="<?php echo esc_attr( $aria_label ); ?>"
											<?php if ( 'email' !== $platform ) : ?>
											target="_blank"
											rel="noopener noreferrer"
											<?php endif; ?>
										>
											<?php if ( $icon_path ) : ?>
												<?php include $icon_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable ?>
											<?php endif; ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>

						</div><!-- .team-card-body -->
					</li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>

		</div><!-- .team-inner -->
	</section>
<?php endif; ?>
