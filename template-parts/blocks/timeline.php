<?php
/**
 * Block: Timeline
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );

	if ( ! have_rows( 'events' ) ) {
		return;
	}
	?>
	<section class="timeline-block">
		<div class="timeline-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="timeline-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_subheading ) ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div class="timeline">
				<?php
				while ( have_rows( 'events' ) ) :
					the_row();

					$event_year  = get_sub_field( 'year' );
					$event_title = get_sub_field( 'title' );
					$description = get_sub_field( 'description' );
					$image       = get_sub_field( 'image' );

					if ( empty( $event_title ) ) {
						continue;
					}

					$datetime = ( 1 === preg_match( '/^\d{4}$/', $event_year ) ) ? $event_year : '';
					?>
				<div class="timeline-item" data-timeline-item>
					<div class="timeline-item-content">
						<time class="timeline-item-date"<?php echo $datetime ? ' datetime="' . esc_attr( $datetime ) . '"' : ''; ?>><?php echo esc_html( $event_year ); ?></time>
						<h3 class="timeline-item-title"><?php echo esc_html( $event_title ); ?></h3>
						<?php if ( ! empty( $description ) ) : ?>
						<p class="timeline-item-desc"><?php echo nl2br( esc_html( $description ) ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $image ) ) : ?>
						<div class="timeline-item-image">
							<?php echo wp_get_attachment_image( $image['ID'], 'timeline-logo' ); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endwhile; ?>
			</div>

		</div>
	</section>
<?php endif; ?>
