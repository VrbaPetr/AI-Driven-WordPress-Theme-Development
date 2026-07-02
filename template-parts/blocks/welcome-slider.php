<?php
/**
 * Block: Welcome Slider
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$autoplay          = get_field( 'autoplay' );
	$autoplay_interval = get_field( 'autoplay_interval' );
	$show_arrows       = get_field( 'show_arrows' );
	$show_dots         = get_field( 'show_dots' );
	$slides            = get_field( 'slides' );

	if ( empty( $slides ) ) {
		return;
	}

	// Defaults.
	$autoplay_interval = $autoplay_interval ? absint( $autoplay_interval ) : 5;
	$slide_count       = count( $slides );

	$alpine_data = sprintf(
		'{
			currentSlide: 0,
			total: %1$d,
			autoplayEnabled: %2$s,
			autoplayInterval: %3$d,
			autoplayTimer: null,
			showArrows: %4$s,
			showDots: %5$s,
			init() {
				if ( this.autoplayEnabled ) { this.startAutoplay(); }
			},
			startAutoplay() {
				this.autoplayTimer = setInterval( () => {
					this.currentSlide = ( this.currentSlide + 1 ) %% this.total;
				}, this.autoplayInterval );
			},
			stopAutoplay() {
				clearInterval( this.autoplayTimer );
				this.autoplayTimer = null;
			},
			prev() { this.currentSlide = ( this.currentSlide - 1 + this.total ) %% this.total; },
			next() { this.currentSlide = ( this.currentSlide + 1 ) %% this.total; },
			goTo( index ) { this.currentSlide = index; }
		}',
		absint( $slide_count ),
		$autoplay ? 'true' : 'false',
		absint( $autoplay_interval ) * 1000,
		$show_arrows ? 'true' : 'false',
		$show_dots ? 'true' : 'false'
	);
	?>
	<section
		class="welcome-slider"
		x-data="<?php echo esc_attr( $alpine_data ); ?>"
		role="region"
		aria-label="<?php esc_attr_e( 'Welcome slider', 'ai-driven-boilerplate' ); ?>"
		@mouseenter="stopAutoplay()"
		@mouseleave="if (autoplayEnabled) startAutoplay()"
		@focusin="stopAutoplay()"
		@focusout="if (autoplayEnabled) startAutoplay()"
	>
		<?php foreach ( $slides as $slide_index => $slide ) : ?>
			<?php
			$heading         = $slide['heading'];
			$subheading      = $slide['subheading'];
			$bg_image        = $slide['background_image'];
			$enable_overlay  = $slide['enable_overlay'];
			$overlay_style   = $slide['overlay_style'] ? $slide['overlay_style'] : 'solid';
			$overlay_opacity = is_numeric( $slide['overlay_opacity'] ) ? (int) $slide['overlay_opacity'] : 50;
			$text_alignment  = $slide['text_alignment'] ? $slide['text_alignment'] : 'center';
			$primary_label   = $slide['primary_button_label'];
			$primary_url     = $slide['primary_button_url'];
			$secondary_label = $slide['secondary_button_label'];
			$secondary_url   = $slide['secondary_button_url'];

			if ( empty( $heading ) ) {
				continue;
			}

			$heading_tag   = 0 === $slide_index ? 'h1' : 'h2';
			$has_primary   = ! empty( $primary_label ) && ! empty( $primary_url );
			$has_secondary = ! empty( $secondary_label ) && ! empty( $secondary_url );

			$bg_url   = ! empty( $bg_image['ID'] ) ? wp_get_attachment_image_src( $bg_image['ID'], 'slide-full' ) : false;
			$bg_style = $bg_url ? 'background-image: url(\'' . esc_url( $bg_url[0] ) . '\');' : '';
			?>
			<div
				class="welcome-slider-slide"
				<?php echo $bg_style ? 'style="' . esc_attr( $bg_style ) . '"' : ''; ?>
				x-show="currentSlide === <?php echo absint( $slide_index ); ?>"
				x-transition:enter="welcome-slider-slide-enter"
				x-transition:enter-start="welcome-slider-slide-enter-start"
				x-transition:enter-end="welcome-slider-slide-enter-end"
				x-transition:leave="welcome-slider-slide-leave"
				x-transition:leave-start="welcome-slider-slide-leave-start"
				x-transition:leave-end="welcome-slider-slide-leave-end"
				:aria-hidden="currentSlide === <?php echo absint( $slide_index ); ?> ? 'false' : 'true'"
				role="group"
				aria-roledescription="slide"
				aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total slides */ __( '%1$d of %2$d', 'ai-driven-boilerplate' ), $slide_index + 1, $slide_count ) ); ?>"
			>
				<?php if ( $enable_overlay ) : ?>
					<div
						class="welcome-slider-overlay welcome-slider-overlay--<?php echo esc_attr( $overlay_style ); ?>"
						style="--slide-overlay-opacity: <?php echo esc_attr( $overlay_opacity / 100 ); ?>;"
					></div>
				<?php endif; ?>

				<div class="welcome-slider-content welcome-slider-content--<?php echo esc_attr( $text_alignment ); ?>">
					<<?php echo esc_html( $heading_tag ); ?> class="welcome-slider-heading"><?php echo esc_html( $heading ); ?></<?php echo esc_html( $heading_tag ); ?>>

					<?php if ( ! empty( $subheading ) ) : ?>
						<p class="welcome-slider-subheading"><?php echo esc_html( $subheading ); ?></p>
					<?php endif; ?>

					<?php if ( $has_primary || $has_secondary ) : ?>
						<div class="welcome-slider-ctas">
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
		<?php endforeach; ?>

		<div class="welcome-slider-controls" x-show="showArrows" x-cloak>
			<button
				type="button"
				class="welcome-slider-arrow welcome-slider-arrow--prev"
				@click="prev()"
				aria-label="<?php esc_attr_e( 'Previous slide', 'ai-driven-boilerplate' ); ?>"
			>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
					<polyline points="15 18 9 12 15 6"/>
				</svg>
			</button>
			<button
				type="button"
				class="welcome-slider-arrow welcome-slider-arrow--next"
				@click="next()"
				aria-label="<?php esc_attr_e( 'Next slide', 'ai-driven-boilerplate' ); ?>"
			>
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
					<polyline points="9 18 15 12 9 6"/>
				</svg>
			</button>
		</div>

		<div class="welcome-slider-dots" x-show="showDots" x-cloak role="group" aria-label="<?php esc_attr_e( 'Slides', 'ai-driven-boilerplate' ); ?>">
			<?php for ( $dot_index = 0; $dot_index < $slide_count; $dot_index++ ) : ?>
				<button
					type="button"
					class="welcome-slider-dot"
					:class="{ 'is-active': currentSlide === <?php echo absint( $dot_index ); ?> }"
					:aria-current="currentSlide === <?php echo absint( $dot_index ); ?> ? 'true' : 'false'"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Go to slide %d', 'ai-driven-boilerplate' ), $dot_index + 1 ) ); ?>"
					@click="goTo(<?php echo absint( $dot_index ); ?>)"
				></button>
			<?php endfor; ?>
		</div>
	</section>
<?php endif; ?>
