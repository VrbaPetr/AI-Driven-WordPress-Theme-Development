<?php
/**
 * WordPress AJAX handlers and their add_action registrations.
 *
 * AJAX handlers are added in Step 30 (contact form) and Step 18 (portfolio
 * load-more). Register all wp_ajax_* and wp_ajax_nopriv_* hooks here.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Load the next batch of portfolio posts for the Portfolio Grid block.
 *
 * Expected POST params: action, nonce, paged, per_page, category.
 *
 * @return void Sends JSON response and exits.
 */
function ai_driven_ajax_load_portfolio() {
	// 1. Nonce verification.
	if ( ! check_ajax_referer( 'ai_driven_load_portfolio', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => 'Invalid nonce.' ), 403 );
	}

	// 2. Sanitize inputs.
	$paged    = isset( $_POST['paged'] ) ? absint( wp_unslash( $_POST['paged'] ) ) : 2;
	$per_page = isset( $_POST['per_page'] ) ? absint( wp_unslash( $_POST['per_page'] ) ) : 3;
	$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

	// 3. Validate.
	if ( $paged < 2 ) {
		wp_send_json_error( array( 'message' => 'Invalid page number.' ), 400 );
	}

	$per_page = min( $per_page, 12 );

	// 4. Query.
	$result = ai_driven_get_portfolio_posts( $paged, $per_page, $category );

	// 5. Render HTML for each card.
	$html = '';
	foreach ( $result['posts'] as $post_data ) {
		ob_start();
		get_template_part(
			'template-parts/components/portfolio-card',
			null,
			array(
				'post_id'       => $post_data['id'],
				'with_alpine'   => false,
				'categories'    => $post_data['categories'],
				'category_name' => $post_data['category_name'],
			)
		);
		$html .= ob_get_clean();
	}

	wp_send_json_success(
		array(
			'html'     => $html,
			'has_more' => $result['has_more'],
		)
	);
}
add_action( 'wp_ajax_ai_driven_load_portfolio', 'ai_driven_ajax_load_portfolio' );
add_action( 'wp_ajax_nopriv_ai_driven_load_portfolio', 'ai_driven_ajax_load_portfolio' );
