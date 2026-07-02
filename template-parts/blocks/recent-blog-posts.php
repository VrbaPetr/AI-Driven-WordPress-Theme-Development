<?php
/**
 * Block: Recent Blog Posts
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$number_of_posts    = get_field( 'number_of_posts' );
	$filter_by_category = get_field( 'filter_by_category' );
	$show_view_all      = get_field( 'show_view_all' );
	$view_all_label     = get_field( 'view_all_label' );

	// Defaults.
	$number_of_posts = $number_of_posts ? absint( $number_of_posts ) : 3;
	$category_id     = ! empty( $filter_by_category ) ? (int) $filter_by_category : 0;
	$view_all_label  = $view_all_label ? $view_all_label : __( 'View all posts', 'ai-driven-boilerplate' );

	$post_cards = ai_driven_get_recent_posts( $number_of_posts, $category_id );

	$blog_archive_url = get_post_type_archive_link( 'post' );
	?>
	<section class="recent-blog-posts-block">
		<div class="recent-blog-posts-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="recent-blog-posts-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $section_subheading ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<?php if ( empty( $posts ) ) : ?>
			<p><?php esc_html_e( 'No posts found.', 'ai-driven-boilerplate' ); ?></p>
			<?php else : ?>
			<div class="recent-blog-posts-grid">
				<?php
				foreach ( $posts as $card_args ) :
					get_template_part( 'template-parts/components/card', null, $card_args );
				endforeach;
				?>
			</div>
			<?php endif; ?>

			<?php if ( $show_view_all && $view_all_label && $blog_archive_url ) : ?>
			<div class="recent-blog-posts-footer">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => $view_all_label,
						'url'     => $blog_archive_url,
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
