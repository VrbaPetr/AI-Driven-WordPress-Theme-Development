<?php
/**
 * Block: FAQ
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$faq_items          = get_field( 'faq_items' );

	if ( empty( $faq_items ) ) {
		return;
	}

	// Build JSON-LD FAQPage schema.
	$schema_entities = array();
	foreach ( $faq_items as $item ) {
		$q = ! empty( $item['question'] ) ? wp_strip_all_tags( $item['question'] ) : '';
		$a = ! empty( $item['answer'] ) ? wp_strip_all_tags( $item['answer'] ) : '';
		if ( $q && $a ) {
			$schema_entities[] = array(
				'@type'          => 'Question',
				'name'           => $q,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $a,
				),
			);
		}
	}

	?>
	<section class="faq">
		<div class="faq-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
				<h2 class="faq-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $section_subheading ) : ?>
				<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div class="faq-list">
				<?php
				foreach ( $faq_items as $index => $item ) :
					if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
						continue;
					}
					$btn_id   = 'faq-btn-' . absint( $index );
					$panel_id = 'faq-panel-' . absint( $index );
					?>
					<div class="faq-item" x-data="{ open: false }">
						<h3 class="faq-question-heading">
							<button
								type="button"
								class="faq-trigger"
								id="<?php echo esc_attr( $btn_id ); ?>"
								@click="open = !open"
								:aria-expanded="open ? 'true' : 'false'"
								aria-controls="<?php echo esc_attr( $panel_id ); ?>"
							>
								<span class="faq-trigger-text"><?php echo esc_html( $item['question'] ); ?></span>
								<span class="faq-trigger-icon" :class="{ 'is-open': open }" aria-hidden="true">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
										<polyline points="6 9 12 15 18 9"></polyline>
									</svg>
								</span>
							</button>
						</h3>
						<div
							class="faq-answer"
							id="<?php echo esc_attr( $panel_id ); ?>"
							role="region"
							aria-labelledby="<?php echo esc_attr( $btn_id ); ?>"
							x-show="open"
							x-collapse
						>
							<div class="faq-answer-body">
								<?php echo wp_kses_post( $item['answer'] ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php if ( ! empty( $schema_entities ) ) : ?>
		<script type="application/ld+json">
			<?php
			echo wp_json_encode(
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $schema_entities,
				),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
			); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</script>
		<?php endif; ?>
	</section>
<?php endif; ?>
