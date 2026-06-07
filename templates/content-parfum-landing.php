<?php
/**
 * Parfum landing page content.
 *
 * @var array      $data       Template field data.
 * @var WC_Product $product    WooCommerce product.
 * @var int        $product_id Product ID.
 */

defined( 'ABSPATH' ) || exit;

$bg_style = '';
if ( ! empty( $data['background_image']['url'] ) ) {
	$bg_style = 'background-image: url(' . esc_url( $data['background_image']['url'] ) . ');';
}

$seo_title = ! empty( $data['seo_title'] ) ? $data['seo_title'] : get_the_title( $product_id );
?>

<main class="wcp-parfum-page" style="<?php echo esc_attr( $bg_style ); ?>">
	<h1 class="screen-reader-text"><?php echo esc_html( $seo_title ); ?></h1>

	<div class="wcp-decor wcp-decor--lines" aria-hidden="true"></div>

	<section class="wcp-section wcp-hero">
		<div class="wcp-hero__grid">
			<div class="wcp-hero__media">
				<div class="wcp-arch-frame wcp-arch-frame--tall">
					<?php WCP_Frontend::render_image( $data['hero_product_image'], 'wcp-hero__product-img', $seo_title ); ?>
				</div>
			</div>

			<div class="wcp-hero__content">
				<?php WCP_Frontend::render_image( $data['title_logo_image'], 'wcp-title-logo', $seo_title ); ?>

				<?php if ( ! empty( $data['tagline'] ) ) : ?>
					<p class="wcp-tagline"><?php echo esc_html( $data['tagline'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['hero_description'] ) ) : ?>
					<div class="wcp-description">
						<?php echo nl2br( esc_html( $data['hero_description'] ) ); ?>
					</div>
				<?php endif; ?>

				<?php WCP_Frontend::render_add_to_cart_button( $product ); ?>
			</div>
		</div>
	</section>

	<section class="wcp-section wcp-notes">
		<h2 class="wcp-notes__title" aria-hidden="true">Note</h2>

		<div class="wcp-notes__grid">
			<article class="wcp-note wcp-note--top">
				<p class="wcp-note__label wcp-note__label--top"><?php esc_html_e( 'Note de vrf', 'wc-parfums-template' ); ?></p>
				<?php if ( ! empty( $data['note_top_text'] ) ) : ?>
					<div class="wcp-note__text wcp-arch-text wcp-arch-text--top">
						<?php echo nl2br( esc_html( $data['note_top_text'] ) ); ?>
					</div>
				<?php endif; ?>
				<div class="wcp-note__image wcp-circle-frame">
					<?php WCP_Frontend::render_image( $data['note_top_image'], 'wcp-note__img', __( 'Note de vrf', 'wc-parfums-template' ) ); ?>
				</div>
			</article>

			<article class="wcp-note wcp-note--middle">
				<p class="wcp-note__label wcp-note__label--middle"><?php esc_html_e( 'Note de mijloc', 'wc-parfums-template' ); ?></p>
				<div class="wcp-note__image wcp-circle-frame">
					<?php WCP_Frontend::render_image( $data['note_middle_image'], 'wcp-note__img', __( 'Note de mijloc', 'wc-parfums-template' ) ); ?>
				</div>
				<?php if ( ! empty( $data['note_middle_text'] ) ) : ?>
					<div class="wcp-note__text wcp-arch-text wcp-arch-text--bottom">
						<?php echo nl2br( esc_html( $data['note_middle_text'] ) ); ?>
					</div>
				<?php endif; ?>
			</article>

			<article class="wcp-note wcp-note--base">
				<p class="wcp-note__label wcp-note__label--base"><?php esc_html_e( 'Note de baz?', 'wc-parfums-template' ); ?></p>
				<?php if ( ! empty( $data['note_base_text'] ) ) : ?>
					<div class="wcp-note__text wcp-arch-text wcp-arch-text--top">
						<?php echo nl2br( esc_html( $data['note_base_text'] ) ); ?>
					</div>
				<?php endif; ?>
				<div class="wcp-note__image wcp-circle-frame">
					<?php WCP_Frontend::render_image( $data['note_base_image'], 'wcp-note__img', __( 'Note de baz?', 'wc-parfums-template' ) ); ?>
				</div>
			</article>
		</div>
	</section>

	<section class="wcp-section wcp-bottom">
		<div class="wcp-bottom__grid">
			<div class="wcp-bottom__content">
				<?php WCP_Frontend::render_image( $data['title_logo_image'], 'wcp-title-logo wcp-title-logo--bottom', $seo_title ); ?>
				<?php WCP_Frontend::render_image( $data['brand_image'], 'wcp-brand-image', __( 'Brand', 'wc-parfums-template' ) ); ?>
				<?php WCP_Frontend::render_add_to_cart_button( $product ); ?>
			</div>

			<div class="wcp-bottom__media">
				<div class="wcp-arch-frame wcp-arch-frame--wide">
					<?php WCP_Frontend::render_image( $data['lifestyle_image'], 'wcp-lifestyle-img', $seo_title ); ?>
				</div>
				<div class="wcp-decor wcp-decor--circle" aria-hidden="true"></div>
			</div>
		</div>
	</section>
</main>
