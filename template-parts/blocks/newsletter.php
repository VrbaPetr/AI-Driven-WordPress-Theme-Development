<?php
/**
 * Block: Newsletter
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$heading              = get_field( 'heading' );
	$subtext              = get_field( 'subtext' );
	$input_placeholder    = get_field( 'input_placeholder' );
	$button_label         = get_field( 'button_label' );
	$privacy_note         = get_field( 'privacy_note' );
	$provider_webhook_url = get_field( 'provider_webhook_url' );

	if ( empty( $heading ) ) {
		return;
	}

	$ajax_url        = admin_url( 'admin-ajax.php' );
	$submit_target   = ! empty( $provider_webhook_url ) ? esc_url( $provider_webhook_url ) : esc_url( $ajax_url );
	$form_action     = ! empty( $provider_webhook_url ) ? esc_url( $provider_webhook_url ) : '#';
	$error_fallback  = esc_attr( __( 'Something went wrong. Please try again.', 'ai-driven-boilerplate' ) );
	$label_subscribe = esc_attr( $button_label ? $button_label : __( 'Subscribe', 'ai-driven-boilerplate' ) );
	$label_loading   = esc_attr( __( 'Subscribing…', 'ai-driven-boilerplate' ) );
	?>
	<section class="newsletter-block">
		<div class="newsletter-inner">

			<?php if ( ! empty( $heading ) ) : ?>
			<h2 class="newsletter-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $subtext ) ) : ?>
			<p class="block-subheading"><?php echo esc_html( $subtext ); ?></p>
			<?php endif; ?>

			<div
				class="newsletter-form-wrapper"
				data-target-url="<?php echo esc_attr( $submit_target ); ?>"
				data-is-ajax="<?php echo empty( $provider_webhook_url ) ? '1' : '0'; ?>"
				data-error-fallback="<?php echo $error_fallback; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
				aria-live="polite"
				x-data="{
					loading: false,
					success: false,
					errorMsg: '',
					async submit(form) {
						this.loading  = true;
						this.errorMsg = '';
						const d       = this.$el.dataset;
						const body    = new FormData(form);
						if (d.isAjax === '1') { body.append('action', 'aidriven_newsletter_subscribe'); }
						try {
							const res  = await fetch(d.targetUrl, { method: 'POST', body });
							const data = await res.json();
							if (data.success) {
								this.success = true;
							} else {
								this.errorMsg = (data.data && data.data.message) ? data.data.message : d.errorFallback;
							}
						} catch (e) {
							this.errorMsg = d.errorFallback;
						}
						this.loading = false;
					}
				}"
			>

				<div
					x-show="success"
					class="newsletter-success"
				>
					<?php
					get_template_part(
						'template-parts/components/alert',
						null,
						array(
							'variant' => 'success',
							'message' => __( 'Thanks for subscribing! Please check your inbox to confirm.', 'ai-driven-boilerplate' ),
						)
					);
					?>
				</div>

				<form
					x-show="!success"
					class="newsletter-form"
					@submit.prevent="submit($el)"
					action="<?php echo $form_action; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
					method="post"
					novalidate
				>
					<?php wp_nonce_field( 'aidriven_newsletter_subscribe' ); ?>

					<div class="newsletter-honeypot" aria-hidden="true">
						<label for="newsletter_website"><?php esc_html_e( 'Website', 'ai-driven-boilerplate' ); ?></label>
						<input
							type="text"
							id="newsletter_website"
							name="website"
							tabindex="-1"
							autocomplete="off"
						>
					</div>

					<div class="newsletter-fields">
						<input
							type="email"
							name="email"
							class="newsletter-input"
							placeholder="<?php echo esc_attr( $input_placeholder ? $input_placeholder : __( 'Your email address', 'ai-driven-boilerplate' ) ); ?>"
							required
							autocomplete="email"
							:disabled="loading"
						>
						<button
							type="submit"
							class="btn btn-primary btn-lg newsletter-submit"
							data-label-subscribe="<?php echo $label_subscribe; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
							data-label-loading="<?php echo $label_loading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above ?>"
							:disabled="loading"
							:aria-label="loading ? $el.dataset.labelLoading : $el.dataset.labelSubscribe"
						>
							<span x-show="!loading"><?php echo esc_html( $label_subscribe ); ?></span>
							<span
								x-show="loading"
								class="newsletter-spinner"
								aria-hidden="true"
							></span>
						</button>
					</div>

					<div x-show="errorMsg" class="newsletter-error" role="alert">
						<p class="newsletter-error-text" x-text="errorMsg"></p>
					</div>
				</form>

				<?php if ( ! empty( $privacy_note ) ) : ?>
				<p class="newsletter-privacy"><?php echo esc_html( $privacy_note ); ?></p>
				<?php endif; ?>

			</div>

		</div>
	</section>
<?php endif; ?>
