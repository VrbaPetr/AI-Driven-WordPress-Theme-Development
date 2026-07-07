<?php
/**
 * Block: Icon Grid
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$columns            = get_field( 'columns' );

	// Defaults.
	$columns = $columns ? $columns : '3';

	if ( ! have_rows( 'features' ) ) {
		return;
	}
	?>
	<section class="icon-grid-block">
		<div class="icon-grid-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="icon-grid-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $section_subheading ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div class="icon-grid-items" data-columns="<?php echo esc_attr( $columns ); ?>">
				<?php
				while ( have_rows( 'features' ) ) :
					the_row();

					$feature_icon  = get_sub_field( 'ui_icon' );
					$feature_title = get_sub_field( 'title' );
					$feature_desc  = get_sub_field( 'description' );

					if ( empty( $feature_title ) ) {
						continue;
					}
					?>
				<div class="icon-grid-item">

					<?php
					if ( ! empty( $feature_icon ) ) :
						$icon_path = aidriven_get_icon_path( $feature_icon );
						if ( $icon_path ) :
							?>
					<span class="icon-grid-item-icon" aria-hidden="true"><?php include $icon_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable ?></span>
							<?php
						endif;
					endif;
					?>

					<h3 class="icon-grid-item-title"><?php echo esc_html( $feature_title ); ?></h3>

					<?php if ( ! empty( $feature_desc ) ) : ?>
					<p class="icon-grid-item-desc"><?php echo esc_html( $feature_desc ); ?></p>
					<?php endif; ?>

				</div>
				<?php endwhile; ?>
			</div>

		</div>
	</section>
<?php endif; ?>
