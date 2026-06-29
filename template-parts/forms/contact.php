<?php
/**
 * Form: Contact
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ajax_url       = admin_url( 'admin-ajax.php' );
$error_fallback = esc_attr( __( 'An unexpected error occurred. Please try again.', 'ai-driven-boilerplate' ) );
$label_send     = esc_attr( __( 'Send Message', 'ai-driven-boilerplate' ) );
$label_sending  = esc_attr( __( 'Sending…', 'ai-driven-boilerplate' ) );
?>

<div
	class="contact-form-wrapper"
	data-ajax-url="<?php echo esc_url( $ajax_url ); ?>"
	data-error-fallback="<?php echo $error_fallback; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
	data-label-send="<?php echo $label_send; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
	data-label-sending="<?php echo $label_sending; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
	aria-live="polite"
	x-data="{
		loading: false,
		success: false,
		errors: {},
		serverError: '',
		async submit(form) {
			this.loading = true;
			this.errors = {};
			this.serverError = '';
			const d = this.$el.dataset;
			try {
				const res = await fetch(d.ajaxUrl, { method: 'POST', body: new FormData(form) });
				const data = await res.json();
				if (data.success) {
					this.success = true;
					this.$nextTick(() => this.$refs.successMsg && this.$refs.successMsg.focus());
				} else if (data.data && data.data.errors) {
					this.errors = data.data.errors;
				} else {
					this.serverError = (data.data && data.data.message) ? data.data.message : d.errorFallback;
				}
			} catch (e) {
				this.serverError = d.errorFallback;
			}
			this.loading = false;
		}
	}"
>

	<div
		x-show="success"
		x-ref="successMsg"
		tabindex="-1"
		class="contact-form-success"
	>
		<?php
		get_template_part(
			'template-parts/components/alert',
			null,
			array(
				'variant' => 'success',
				'message' => __( 'Thank you! Your message has been sent. We will get back to you shortly.', 'ai-driven-boilerplate' ),
			)
		);
		?>
	</div>

	<form
		x-show="!success"
		x-ref="contactForm"
		class="contact-form"
		@submit.prevent="submit($refs.contactForm)"
		novalidate
	>
		<?php wp_nonce_field( 'aidriven_contact_form', 'contact_nonce' ); ?>
		<input type="hidden" name="action" value="aidriven_contact_form">

		<div class="contact-form-honeypot" aria-hidden="true">
			<label for="contact_website"><?php esc_html_e( 'Website', 'ai-driven-boilerplate' ); ?></label>
			<input
				type="text"
				id="contact_website"
				name="honeypot"
				tabindex="-1"
				autocomplete="off"
			>
		</div>

		<div class="contact-form-field" :class="{ 'contact-form-field--error': errors.name }">
			<label class="contact-form-label" for="contact_name">
				<?php esc_html_e( 'Name', 'ai-driven-boilerplate' ); ?>
				<span class="contact-form-required" aria-hidden="true">*</span>
			</label>
			<input
				type="text"
				id="contact_name"
				name="name"
				class="contact-form-input"
				maxlength="100"
				required
				:disabled="loading"
				:aria-describedby="errors.name ? 'error-name' : undefined"
				aria-required="true"
			>
			<span
				x-show="errors.name"
				id="error-name"
				class="contact-form-error"
				role="alert"
				x-text="errors.name"
			></span>
		</div>

		<div class="contact-form-field" :class="{ 'contact-form-field--error': errors.email }">
			<label class="contact-form-label" for="contact_email">
				<?php esc_html_e( 'Email', 'ai-driven-boilerplate' ); ?>
				<span class="contact-form-required" aria-hidden="true">*</span>
			</label>
			<input
				type="email"
				id="contact_email"
				name="email"
				class="contact-form-input"
				required
				:disabled="loading"
				:aria-describedby="errors.email ? 'error-email' : undefined"
				aria-required="true"
			>
			<span
				x-show="errors.email"
				id="error-email"
				class="contact-form-error"
				role="alert"
				x-text="errors.email"
			></span>
		</div>

		<div class="contact-form-field" :class="{ 'contact-form-field--error': errors.subject }">
			<label class="contact-form-label" for="contact_subject">
				<?php esc_html_e( 'Subject', 'ai-driven-boilerplate' ); ?>
				<span class="contact-form-required" aria-hidden="true">*</span>
			</label>
			<input
				type="text"
				id="contact_subject"
				name="subject"
				class="contact-form-input"
				maxlength="200"
				required
				:disabled="loading"
				:aria-describedby="errors.subject ? 'error-subject' : undefined"
				aria-required="true"
			>
			<span
				x-show="errors.subject"
				id="error-subject"
				class="contact-form-error"
				role="alert"
				x-text="errors.subject"
			></span>
		</div>

		<div class="contact-form-field" :class="{ 'contact-form-field--error': errors.message }">
			<label class="contact-form-label" for="contact_message">
				<?php esc_html_e( 'Message', 'ai-driven-boilerplate' ); ?>
				<span class="contact-form-required" aria-hidden="true">*</span>
			</label>
			<textarea
				id="contact_message"
				name="message"
				class="contact-form-textarea"
				rows="6"
				required
				:disabled="loading"
				:aria-describedby="errors.message ? 'error-message' : undefined"
				aria-required="true"
			></textarea>
			<span
				x-show="errors.message"
				id="error-message"
				class="contact-form-error"
				role="alert"
				x-text="errors.message"
			></span>
		</div>

		<div x-show="serverError" class="contact-form-server-error" role="alert">
			<p class="contact-form-server-error-text" x-text="serverError"></p>
		</div>

		<div class="contact-form-submit">
			<button
				type="submit"
				class="btn btn-primary btn-lg"
				:disabled="loading"
				:aria-label="loading ? $el.dataset.labelSending : $el.dataset.labelSend"
			>
				<span x-show="!loading"><?php esc_html_e( 'Send Message', 'ai-driven-boilerplate' ); ?></span>
				<span
					x-show="loading"
					class="contact-form-spinner"
					aria-hidden="true"
				></span>
			</button>
		</div>

	</form>

</div>
