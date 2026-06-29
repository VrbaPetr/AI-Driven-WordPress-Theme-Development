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

/**
 * Handle contact form AJAX submission.
 *
 * Expected POST params: action, contact_nonce, name, email, subject, message, honeypot.
 *
 * @return void Sends JSON response and exits.
 */
function ai_driven_ajax_handle_contact_form() {
	// 1. Nonce verification.
	if ( ! check_ajax_referer( 'aidriven_contact_form', 'contact_nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'ai-driven-boilerplate' ) ), 403 );
	}

	// 2. Honeypot — silently return success so bots think the submission worked.
	$honeypot = isset( $_POST['honeypot'] ) ? sanitize_text_field( wp_unslash( $_POST['honeypot'] ) ) : '';
	if ( ! empty( $honeypot ) ) {
		wp_send_json_success();
	}

	// 3. Sanitize.
	$data = array(
		'name'    => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
		'email'   => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
		'subject' => isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '',
		'message' => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
	);

	// 4. Validate.
	$errors = ai_driven_validate_contact_form( $data );
	if ( ! empty( $errors ) ) {
		wp_send_json_error( array( 'errors' => $errors ), 422 );
	}

	// 5. Send admin notification email.
	$company_name = get_field( 'company_name', 'option' );
	if ( empty( $company_name ) ) {
		$company_name = get_bloginfo( 'name' );
	}

	$host       = wp_parse_url( home_url(), PHP_URL_HOST );
	$from_email = 'noreply@' . $host;
	$headers    = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . $company_name . ' <' . $from_email . '>',
	);

	$to = get_option( 'admin_email' );
	/* translators: 1: site name, 2: subject submitted by visitor */
	$subject = sprintf( __( '[%1$s] New Enquiry: %2$s', 'ai-driven-boilerplate' ), get_bloginfo( 'name' ), $data['subject'] );
	$body    = sprintf(
		/* translators: contact form email body placeholders */
		__( "Name: %1\$s\nEmail: %2\$s\nSubject: %3\$s\n\nMessage:\n%4\$s", 'ai-driven-boilerplate' ),
		$data['name'],
		$data['email'],
		$data['subject'],
		$data['message']
	);

	wp_mail( $to, $subject, $body, $headers );

	// 6. Respond.
	wp_send_json_success();
}
add_action( 'wp_ajax_aidriven_contact_form', 'ai_driven_ajax_handle_contact_form' );
add_action( 'wp_ajax_nopriv_aidriven_contact_form', 'ai_driven_ajax_handle_contact_form' );
