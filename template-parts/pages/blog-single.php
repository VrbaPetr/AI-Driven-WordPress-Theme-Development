<?php
/**
 * Page: Blog Single
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	return;
}

the_post();

$article_id    = get_the_ID();
$author_id     = get_the_author_meta( 'ID' );
$author_name   = get_the_author_meta( 'display_name' );
$author_bio    = get_the_author_meta( 'description' );
$author_url    = get_author_posts_url( $author_id );
$author_avatar = get_avatar( $author_id, 96, '', esc_attr( $author_name ) );
$thumb_id      = get_post_thumbnail_id( $article_id );
$read_time     = aidriven_get_read_time( $article_id );
$categories    = get_the_category( $article_id );
$article_tags  = get_the_tags( $article_id );
$related_query = aidriven_get_related_posts( $article_id );
$post_url      = get_permalink();

/* translators: %d: number of minutes */
$read_time_label = sprintf( _n( '%d min read', '%d min read', $read_time, 'ai-driven-boilerplate' ), $read_time );

// Global CTA options.
$cta_heading   = get_field( 'cta_heading', 'option' );
$cta_subtext   = get_field( 'cta_subtext', 'option' );
$cta_btn_label = get_field( 'primary_button_label', 'option' );
$cta_btn_url   = get_field( 'primary_button_url', 'option' );

// Social share URLs.
$share_twitter  = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $post_url ) . '&text=' . rawurlencode( get_the_title() );
$share_linkedin = 'https://www.linkedin.com/sharing/share-offsite/?url=' . rawurlencode( $post_url );
?>
<div class="reading-progress-bar" role="progressbar" aria-hidden="true"></div>

<main id="main-content" class="article-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title' => get_the_title(),
		)
	);
	?>

	<div class="article-meta-bar">
		<div class="article-meta-bar-inner">

			<div class="article-meta-author">
				<?php if ( $author_avatar ) : ?>
					<a href="<?php echo esc_url( $author_url ); ?>" class="article-meta-avatar-link" aria-hidden="true" tabindex="-1">
						<?php echo wp_kses_post( $author_avatar ); ?>
					</a>
				<?php endif; ?>
				<div class="article-meta-author-info">
					<a href="<?php echo esc_url( $author_url ); ?>" class="article-meta-author-name">
						<?php echo esc_html( $author_name ); ?>
					</a>
					<span class="article-meta-date">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
						<span aria-hidden="true">&middot;</span>
						<?php echo esc_html( $read_time_label ); ?>
					</span>
				</div>
			</div>

			<?php if ( ! empty( $categories ) ) : ?>
				<div class="article-meta-categories">
					<?php foreach ( $categories as $post_cat ) : ?>
						<?php
						get_template_part(
							'template-parts/components/badge',
							null,
							array(
								'label'   => $post_cat->name,
								'url'     => get_category_link( $post_cat->term_id ),
								'variant' => 'primary',
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
	</div><!-- .article-meta-bar -->

	<?php if ( $thumb_id ) : ?>
		<div class="article-hero">
			<?php
			echo wp_get_attachment_image(
				$thumb_id,
				'hero-full',
				false,
				array(
					'class'   => 'article-hero-img',
					'loading' => 'eager',
					'alt'     => esc_attr( get_the_title() ),
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="article-body">

		<div class="article-content entry-content">
			<?php the_content(); ?>
		</div><!-- .article-content -->

		<?php if ( ! empty( $article_tags ) ) : ?>
			<div class="article-tags">
				<span class="article-tags-label"><?php esc_html_e( 'Tags:', 'ai-driven-boilerplate' ); ?></span>
				<div class="article-tags-list">
					<?php foreach ( $article_tags as $post_tag ) : ?>
						<?php
						get_template_part(
							'template-parts/components/badge',
							null,
							array(
								'label'   => $post_tag->name,
								'url'     => get_tag_link( $post_tag->term_id ),
								'variant' => 'neutral',
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="article-share">
			<span class="article-share-label"><?php esc_html_e( 'Share:', 'ai-driven-boilerplate' ); ?></span>
			<div class="article-share-buttons">

				<a href="<?php echo esc_url( $share_twitter ); ?>"
					class="article-share-btn"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Share on X (Twitter)', 'ai-driven-boilerplate' ); ?>">
					<?php esc_html_e( 'X (Twitter)', 'ai-driven-boilerplate' ); ?>
				</a>

				<a href="<?php echo esc_url( $share_linkedin ); ?>"
					class="article-share-btn"
					target="_blank"
					rel="noopener noreferrer"
					aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'ai-driven-boilerplate' ); ?>">
					<?php esc_html_e( 'LinkedIn', 'ai-driven-boilerplate' ); ?>
				</a>

				<button
					class="article-share-btn"
					data-share-copy="<?php echo esc_attr( $post_url ); ?>"
					data-copied-label="<?php esc_attr_e( 'Link copied!', 'ai-driven-boilerplate' ); ?>"
					aria-label="<?php esc_attr_e( 'Copy link to this article', 'ai-driven-boilerplate' ); ?>">
					<?php esc_html_e( 'Copy Link', 'ai-driven-boilerplate' ); ?>
				</button>

			</div>
		</div><!-- .article-share -->

	</div><!-- .article-body -->

	<section class="article-author" aria-label="<?php esc_attr_e( 'About the author', 'ai-driven-boilerplate' ); ?>">
		<div class="article-author-inner">

			<?php if ( $author_avatar ) : ?>
				<div class="article-author-avatar">
					<?php echo wp_kses_post( $author_avatar ); ?>
				</div>
			<?php endif; ?>

			<div class="article-author-details">
				<h2 class="article-author-name">
					<a href="<?php echo esc_url( $author_url ); ?>">
						<?php echo esc_html( $author_name ); ?>
					</a>
				</h2>
				<?php if ( $author_bio ) : ?>
					<p class="article-author-bio"><?php echo esc_html( $author_bio ); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url( $author_url ); ?>" class="article-author-link">
					<?php
					printf(
						/* translators: %s: author display name */
						esc_html__( 'More articles by %s', 'ai-driven-boilerplate' ),
						esc_html( $author_name )
					);
					?>
				</a>
			</div>

		</div>
	</section><!-- .article-author -->

	<nav class="article-nav" aria-label="<?php esc_attr_e( 'Post navigation', 'ai-driven-boilerplate' ); ?>">
		<div class="article-nav-inner">

			<?php $prev_post = get_previous_post(); ?>
			<?php if ( $prev_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="article-nav-prev">
					<span class="article-nav-dir"><?php esc_html_e( 'Previous Article', 'ai-driven-boilerplate' ); ?></span>
					<span class="article-nav-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
				</a>
			<?php else : ?>
				<span></span>
			<?php endif; ?>

			<?php $next_post = get_next_post(); ?>
			<?php if ( $next_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="article-nav-next">
					<span class="article-nav-dir"><?php esc_html_e( 'Next Article', 'ai-driven-boilerplate' ); ?></span>
					<span class="article-nav-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
				</a>
			<?php endif; ?>

		</div>
	</nav><!-- .article-nav -->

	<?php if ( $related_query->have_posts() ) : ?>
		<section class="article-related">
			<div class="article-related-inner">

				<h2 class="article-related-heading"><?php esc_html_e( 'Related Articles', 'ai-driven-boilerplate' ); ?></h2>

				<ul class="article-related-grid" role="list">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();

						$rel_id        = get_the_ID();
						$rel_cats      = get_the_category( $rel_id );
						$rel_cat_name  = ! empty( $rel_cats ) ? $rel_cats[0]->name : '';
						$rel_cat_url   = ! empty( $rel_cats ) ? get_category_link( $rel_cats[0]->term_id ) : '';
						$rel_read_time = aidriven_get_read_time( $rel_id );
						/* translators: %d: number of minutes */
						$rel_read_label = sprintf( _n( '%d min read', '%d min read', $rel_read_time, 'ai-driven-boilerplate' ), $rel_read_time );
						?>
						<li>
							<?php
							get_template_part(
								'template-parts/components/card',
								null,
								array(
									'image_id'     => get_post_thumbnail_id(),
									'title'        => get_the_title(),
									'title_url'    => get_permalink(),
									'category'     => $rel_cat_name,
									'category_url' => $rel_cat_url,
									'excerpt'      => get_the_excerpt(),
									'meta'         => get_the_date() . ' · ' . $rel_read_label,
									'cta_label'    => __( 'Read More', 'ai-driven-boilerplate' ),
									'cta_url'      => get_permalink(),
								)
							);
							?>
						</li>
						<?php
					}
					wp_reset_postdata();
					?>
				</ul>

			</div>
		</section><!-- .article-related -->
	<?php endif; ?>

	<?php if ( $cta_heading ) : ?>
		<section class="article-cta">
			<div class="article-cta-inner">

				<h2 class="article-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>

				<?php if ( $cta_subtext ) : ?>
					<p class="article-cta-subtext"><?php echo esc_html( $cta_subtext ); ?></p>
				<?php endif; ?>

				<?php if ( $cta_btn_label && $cta_btn_url ) : ?>
					<div class="article-cta-actions">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'   => $cta_btn_label,
								'url'     => $cta_btn_url,
								'variant' => 'primary',
								'size'    => 'lg',
							)
						);
						?>
					</div>
				<?php endif; ?>

			</div>
		</section><!-- .article-cta -->
	<?php endif; ?>

</main><!-- #main-content -->
