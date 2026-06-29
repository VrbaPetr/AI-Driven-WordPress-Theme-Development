<?php
/**
 * Block: Testimonials
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading   = get_field( 'section_heading' );
	$num_testimonials  = get_field( 'number_of_testimonials' );
	$autoplay          = get_field( 'autoplay' );
	$autoplay_interval = get_field( 'autoplay_interval' );

	// Defaults.
	$num_testimonials  = $num_testimonials ? absint( $num_testimonials ) : 5;
	$autoplay_interval = $autoplay_interval ? absint( $autoplay_interval ) : 4000;

	// Query testimonials.
	$tquery = new WP_Query(
		array(
			'post_type'      => 'testimonial',
			'posts_per_page' => $num_testimonials,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);

	$testimonials = array();

	if ( $tquery->have_posts() ) {
		while ( $tquery->have_posts() ) {
			$tquery->the_post();
			$current_id     = get_the_ID();
			$testimonials[] = array(
				'quote' => get_field( 'quote', $current_id ),
				'name'  => get_field( 'client_name', $current_id ),
				'role'  => get_field( 'client_title', $current_id ),
				'photo' => get_field( 'client_photo', $current_id ),
			);
		}
		wp_reset_postdata();
	}

	if ( empty( $testimonials ) ) {
		return;
	}

	$slide_count = count( $testimonials );
	?>
	<section
		class="testimonials-block"
		x-data="testimonialSlider"
		data-total="<?php echo esc_attr( $slide_count ); ?>"
		data-autoplay="<?php echo esc_attr( $autoplay ? '1' : '0' ); ?>"
		data-interval="<?php echo esc_attr( $autoplay_interval ); ?>"
		role="region"
		aria-roledescription="carousel"
		aria-label="<?php esc_attr_e( 'Testimonials', 'ai-driven-boilerplate' ); ?>"
		@mouseenter="pause()"
		@mouseleave="resume()"
		@focusin="pause()"
		@focusout="handleFocusOut($event)"
	>
		<div class="testimonials-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
				<h2 class="testimonials-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>

			<div class="testimonials-slider">

				<div class="testimonials-track" aria-live="polite">
					<div
						class="testimonials-slides-wrapper"
						:style="'transform: translateX(-' + ( currentIndex * 100 ) + '%)'"
					>
					<?php foreach ( $testimonials as $slide_index => $testimonial ) : ?>
						<?php
						if ( empty( $testimonial['quote'] ) ) {
							continue;
						}
						?>
						<div
							class="testimonials-slide"
							role="group"
							aria-roledescription="slide"
							aria-label="<?php echo esc_attr( ( $slide_index + 1 ) . ' of ' . $slide_count ); ?>"
							:aria-hidden="currentIndex !== <?php echo absint( $slide_index ); ?> ? 'true' : 'false'"
						>
							<blockquote class="testimonials-quote">
								<p class="testimonials-quote-text"><?php echo esc_html( $testimonial['quote'] ); ?></p>
								<footer class="testimonials-author">
									<?php if ( ! empty( $testimonial['photo'] ) ) : ?>
										<div class="testimonials-author-photo">
											<?php
											echo wp_get_attachment_image(
												absint( $testimonial['photo'] ),
												'thumbnail',
												false,
												array(
													'class' => 'testimonials-author-img',
													'alt' => esc_attr( $testimonial['name'] ?? '' ),
												)
											);
											?>
										</div>
									<?php else : ?>
										<div class="testimonials-author-photo testimonials-author-photo--fallback" aria-hidden="true">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
												<path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
											</svg>
										</div>
									<?php endif; ?>
									<div class="testimonials-author-meta">
										<?php if ( ! empty( $testimonial['name'] ) ) : ?>
											<cite class="testimonials-author-name"><?php echo esc_html( $testimonial['name'] ); ?></cite>
										<?php endif; ?>
										<?php if ( ! empty( $testimonial['role'] ) ) : ?>
											<span class="testimonials-author-role"><?php echo esc_html( $testimonial['role'] ); ?></span>
										<?php endif; ?>
									</div>
								</footer>
							</blockquote>
						</div>
					<?php endforeach; ?>
					</div>
				</div>

				<?php if ( $slide_count > 1 ) : ?>
					<div class="testimonials-controls">
						<button
							type="button"
							class="testimonials-nav-btn testimonials-nav-btn--prev"
							@click="prev()"
							aria-label="<?php esc_attr_e( 'Previous testimonial', 'ai-driven-boilerplate' ); ?>"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<polyline points="15 18 9 12 15 6"/>
							</svg>
						</button>

						<div class="testimonials-dots" role="tablist" aria-label="<?php esc_attr_e( 'Slides', 'ai-driven-boilerplate' ); ?>">
							<?php for ( $dot_index = 0; $dot_index < $slide_count; $dot_index++ ) : ?>
								<button
									type="button"
									class="testimonials-dot"
									role="tab"
									:class="{ 'is-active': currentIndex === <?php echo absint( $dot_index ); ?> }"
									:aria-selected="currentIndex === <?php echo absint( $dot_index ); ?> ? 'true' : 'false'"
									:aria-label="'<?php echo esc_js( __( 'Go to slide', 'ai-driven-boilerplate' ) ); ?> <?php echo absint( $dot_index + 1 ); ?>'"
									@click="goTo(<?php echo absint( $dot_index ); ?>)"
								></button>
							<?php endfor; ?>
						</div>

						<button
							type="button"
							class="testimonials-nav-btn testimonials-nav-btn--next"
							@click="next()"
							aria-label="<?php esc_attr_e( 'Next testimonial', 'ai-driven-boilerplate' ); ?>"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
								<polyline points="9 18 15 12 9 6"/>
							</svg>
						</button>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>
<?php endif; ?>
