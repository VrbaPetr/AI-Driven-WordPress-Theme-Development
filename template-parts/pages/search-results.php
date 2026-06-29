<?php
/**
 * Page: Search Results
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$search_query = get_search_query( false );
$found_posts  = (int) $wp_query->found_posts;
$has_results  = have_posts();

$post_type_labels = array(
	'post'        => __( 'Article', 'ai-driven-boilerplate' ),
	'service'     => __( 'Service', 'ai-driven-boilerplate' ),
	'project'     => __( 'Project', 'ai-driven-boilerplate' ),
	'team-member' => __( 'Team', 'ai-driven-boilerplate' ),
	'page'        => __( 'Page', 'ai-driven-boilerplate' ),
);

/* translators: %s: search query entered by the user */
$page_title = sprintf( __( 'Results for: &#8220;%s&#8221;', 'ai-driven-boilerplate' ), esc_html( $search_query ) );

if ( $has_results ) {
	/* translators: %d: number of search results found */
	$count_text = sprintf( _n( '%d result found', '%d results found', $found_posts, 'ai-driven-boilerplate' ), $found_posts );
} else {
	$count_text = '';
}
?>
<main id="main-content" class="search-main">

	<div class="search-header">
		<div class="search-header-inner">

			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

			<h1 class="search-header-title"><?php echo wp_kses( $page_title, array() ); ?></h1>

			<?php if ( $count_text ) : ?>
				<p class="search-header-count"><?php echo esc_html( $count_text ); ?></p>
			<?php endif; ?>

			<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="search-input-header" class="screen-reader-text">
					<?php esc_html_e( 'Refine your search', 'ai-driven-boilerplate' ); ?>
				</label>
				<input
					type="search"
					id="search-input-header"
					class="search-form-input"
					name="s"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Search…', 'ai-driven-boilerplate' ); ?>"
					autocomplete="off"
				>
				<button type="submit" class="search-form-btn">
					<?php esc_html_e( 'Search', 'ai-driven-boilerplate' ); ?>
				</button>
			</form>

		</div><!-- .search-header-inner -->
	</div><!-- .search-header -->

	<div class="search-inner">

		<?php if ( $has_results ) : ?>

			<ul class="search-results-grid" role="list">
				<?php
				while ( have_posts() ) {
					the_post();

					$item_post_type = get_post_type();
					$type_label     = isset( $post_type_labels[ $item_post_type ] ) ? $post_type_labels[ $item_post_type ] : ucfirst( str_replace( '-', ' ', $item_post_type ) );
					?>
					<li>
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id'  => get_post_thumbnail_id(),
								'title'     => get_the_title(),
								'title_url' => get_permalink(),
								'category'  => $type_label,
								'excerpt'   => get_the_excerpt(),
								'meta'      => get_the_date( '', get_the_ID() ),
							)
						);
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php get_template_part( 'template-parts/components/pagination' ); ?>

		<?php else : ?>

			<div class="search-no-results">

				<h2 class="search-no-results-title">
					<?php
					/* translators: %s: search query entered by the user */
					printf( esc_html__( 'No results for &#8220;%s&#8221;', 'ai-driven-boilerplate' ), esc_html( $search_query ) );
					?>
				</h2>

				<p class="search-no-results-intro">
					<?php esc_html_e( 'Try the following:', 'ai-driven-boilerplate' ); ?>
				</p>

				<ul class="search-no-results-tips">
					<li><?php esc_html_e( 'Check your spelling', 'ai-driven-boilerplate' ); ?></li>
					<li><?php esc_html_e( 'Use fewer or broader keywords', 'ai-driven-boilerplate' ); ?></li>
					<li>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="search-no-results-link">
							<?php esc_html_e( 'Browse all services', 'ai-driven-boilerplate' ); ?> &rarr;
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="search-no-results-link">
							<?php esc_html_e( 'View all projects', 'ai-driven-boilerplate' ); ?> &rarr;
						</a>
					</li>
				</ul>

				<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<label for="search-input-noresults" class="screen-reader-text">
						<?php esc_html_e( 'Try a new search', 'ai-driven-boilerplate' ); ?>
					</label>
					<input
						type="search"
						id="search-input-noresults"
						class="search-form-input"
						name="s"
						placeholder="<?php esc_attr_e( 'Try again…', 'ai-driven-boilerplate' ); ?>"
						autocomplete="off"
					>
					<button type="submit" class="search-form-btn">
						<?php esc_html_e( 'Search', 'ai-driven-boilerplate' ); ?>
					</button>
				</form>

			</div><!-- .search-no-results -->

		<?php endif; ?>

	</div><!-- .search-inner -->

</main><!-- #main-content -->
