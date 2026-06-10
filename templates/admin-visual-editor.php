<?php
/**
 * Visual WYSIWYG admin editor for parfum template.
 *
 * @var WP_Post $post Current post.
 * @var array   $data Template field data.
 */

defined( 'ABSPATH' ) || exit;

$fields = WCP_Product_Meta::get_content_fields();

$bg_url   = ! empty( $data['background_image']['url'] ) ? $data['background_image']['url'] : '';
$bg_style = $bg_url ? 'background-image:url(' . esc_url( $bg_url ) . ');' : '';
?>

<div class="wcp-visual-editor" id="wcp-visual-editor">
	<div class="wcp-visual-editor__toolbar">
		<?php WCP_Admin::render_field( 'background_image', $fields['background_image'], $data['background_image'] ); ?>
	</div>

	<div class="wcp-visual-canvas" id="wcp-visual-canvas" style="<?php echo esc_attr( $bg_style ); ?>">
		<div class="wcp-visual-canvas__inner">
		<p class="wcp-visual-canvas__hint">Previzualizare layout - campurile sunt pozitionate ca pe pagina finala.</p>

		<section class="wcp-visual-section wcp-visual-hero">
			<div class="wcp-visual-hero__grid">
				<div class="wcp-visual-hero__media">
					<div class="wcp-visual-frame wcp-visual-frame--arch-tall">
						<?php WCP_Admin::render_field( 'hero_product_image', $fields['hero_product_image'], $data['hero_product_image'] ); ?>
					</div>
				</div>

				<div class="wcp-visual-hero__content">
					<?php WCP_Admin::render_field( 'title_logo_image', $fields['title_logo_image'], $data['title_logo_image'] ); ?>
					<?php WCP_Admin::render_field( 'tagline', $fields['tagline'], $data['tagline'] ); ?>
					<?php WCP_Admin::render_field( 'hero_description', $fields['hero_description'], $data['hero_description'] ); ?>
					<div class="wcp-visual-cta wcp-visual-cta--static">COMANDA ACUM (automat din WooCommerce)</div>
				</div>
			</div>
		</section>

		<section class="wcp-visual-section wcp-visual-notes">
			<h2 class="wcp-visual-notes__title">Note</h2>

			<div class="wcp-visual-notes__grid">
				<div class="wcp-visual-note wcp-visual-note--top">
					<span class="wcp-visual-note__heading">Note de varf</span>
					<div class="wcp-visual-note__text-wrap wcp-visual-frame wcp-visual-frame--arch-text-top">
						<?php WCP_Admin::render_field( 'note_top_text', $fields['note_top_text'], $data['note_top_text'] ); ?>
					</div>
					<div class="wcp-visual-frame wcp-visual-frame--circle">
						<?php WCP_Admin::render_field( 'note_top_image', $fields['note_top_image'], $data['note_top_image'] ); ?>
					</div>
				</div>

				<div class="wcp-visual-note wcp-visual-note--middle">
					<span class="wcp-visual-note__heading">Note de mijloc</span>
					<div class="wcp-visual-frame wcp-visual-frame--circle">
						<?php WCP_Admin::render_field( 'note_middle_image', $fields['note_middle_image'], $data['note_middle_image'] ); ?>
					</div>
					<div class="wcp-visual-note__text-wrap wcp-visual-frame wcp-visual-frame--arch-text-bottom">
						<?php WCP_Admin::render_field( 'note_middle_text', $fields['note_middle_text'], $data['note_middle_text'] ); ?>
					</div>
				</div>

				<div class="wcp-visual-note wcp-visual-note--base">
					<span class="wcp-visual-note__heading">Note de baza</span>
					<div class="wcp-visual-note__text-wrap wcp-visual-frame wcp-visual-frame--arch-text-top">
						<?php WCP_Admin::render_field( 'note_base_text', $fields['note_base_text'], $data['note_base_text'] ); ?>
					</div>
					<div class="wcp-visual-frame wcp-visual-frame--circle">
						<?php WCP_Admin::render_field( 'note_base_image', $fields['note_base_image'], $data['note_base_image'] ); ?>
					</div>
				</div>
			</div>
		</section>

		<section class="wcp-visual-section wcp-visual-bottom">
			<div class="wcp-visual-bottom__grid">
				<div class="wcp-visual-bottom__content">
					<?php WCP_Admin::render_field( 'brand_image', $fields['brand_image'], $data['brand_image'] ); ?>
					<div class="wcp-visual-cta wcp-visual-cta--static">COMANDA ACUM (automat din WooCommerce)</div>
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
