<?php
/**
 * Visual admin editor for Discovery Kit template.
 *
 * @var WP_Post $post Current post.
 * @var array   $data Template field data.
 */

defined( 'ABSPATH' ) || exit;

$fields = WCP_Product_Meta::get_discovery_content_fields();

$bg_url   = ! empty( $data['background_image']['url'] ) ? $data['background_image']['url'] : '';
$bg_style = $bg_url ? 'background-image:url(' . esc_url( $bg_url ) . ');' : '';

$default_labels = array(
	1 => 'Senzuala (Siren Call)',
	2 => 'Puternica (Siren Rock)',
	3 => 'Nebunatica (Mad Love)',
	4 => 'Calma (Inner Peace)',
	5 => 'Valoroasa (Rose Pearl)',
);
?>

<div class="wcp-visual-editor wcp-discovery-editor" id="wcp-discovery-editor">
	<div class="wcp-visual-editor__toolbar">
		<?php
		WCP_Admin::render_field(
			'price_display_mode',
			$fields['price_display_mode'],
			WCP_Product_Meta::get_price_display_mode( $post->ID )
		);
		WCP_Admin::render_field( 'background_image', $fields['background_image'], $data['background_image'] );
		?>
	</div>

	<div class="wcp-visual-canvas" id="wcp-discovery-canvas" style="<?php echo esc_attr( $bg_style ); ?>">
		<div class="wcp-visual-canvas__inner">
			<p class="wcp-visual-canvas__hint">Previzualizare Discovery Kit - campurile sunt pozitionate ca pe pagina finala.</p>

			<section class="wcp-visual-section wcp-visual-hero">
				<div class="wcp-visual-hero__grid">
					<div class="wcp-visual-hero__media">
						<div class="wcp-visual-frame wcp-visual-frame--arch-tall">
							<?php WCP_Admin::render_field( 'hero_product_image', $fields['hero_product_image'], $data['hero_product_image'] ); ?>
						</div>
					</div>

					<div class="wcp-visual-hero__content">
						<?php WCP_Admin::render_field( 'title_logo_image', $fields['title_logo_image'], $data['title_logo_image'] ); ?>
						<?php WCP_Admin::render_field( 'kit_title', $fields['kit_title'], $data['kit_title'] ); ?>
						<?php WCP_Admin::render_field( 'kit_description', $fields['kit_description'], $data['kit_description'] ); ?>

						<div class="wcp-visual-scent-list">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<?php
								WCP_Admin::render_field(
									'scent_' . $i . '_label',
									$fields[ 'scent_' . $i . '_label' ],
									! empty( $data[ 'scent_' . $i . '_label' ] ) ? $data[ 'scent_' . $i . '_label' ] : $default_labels[ $i ]
								);
								?>
							<?php endfor; ?>
						</div>

						<?php WCP_Admin::render_field( 'kit_promo', $fields['kit_promo'], $data['kit_promo'] ); ?>
						<div class="wcp-visual-cta wcp-visual-cta--static" data-wcp-cta-preview>COMANDA ACUM</div>
					</div>
				</div>
			</section>

			<section class="wcp-visual-section wcp-visual-scents">
				<div class="wcp-visual-scents__grid">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<div class="wcp-visual-scent wcp-visual-scent--<?php echo esc_attr( (string) $i ); ?>">
							<div class="wcp-visual-frame wcp-visual-frame--circle">
								<?php WCP_Admin::render_field( 'scent_' . $i . '_image', $fields[ 'scent_' . $i . '_image' ], $data[ 'scent_' . $i . '_image' ] ); ?>
							</div>
							<?php WCP_Admin::render_field( 'scent_' . $i . '_product', $fields[ 'scent_' . $i . '_product' ], $data[ 'scent_' . $i . '_product' ] ); ?>
						</div>
					<?php endfor; ?>
				</div>
				<?php WCP_Admin::render_field( 'kit_middle_title', $fields['kit_middle_title'], $data['kit_middle_title'] ); ?>
			</section>

			<section class="wcp-visual-section wcp-visual-bottom">
				<div class="wcp-visual-bottom__grid">
					<div class="wcp-visual-bottom__content">
						<?php WCP_Admin::render_field( 'brand_image', $fields['brand_image'], $data['brand_image'] ); ?>
						<div class="wcp-visual-cta wcp-visual-cta--static" data-wcp-cta-preview>COMANDA ACUM</div>
					</div>

					<div class="wcp-visual-bottom__media">
						<div class="wcp-visual-frame wcp-visual-frame--arch-wide">
							<?php WCP_Admin::render_field( 'lifestyle_image', $fields['lifestyle_image'], $data['lifestyle_image'] ); ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
