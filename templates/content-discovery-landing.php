<?php
/**
 * Discovery Kit landing page content.
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

$page_title = ! empty( $data['kit_title'] ) ? $data['kit_title'] : get_the_title( $product_id );

$default_labels = array(
	1 => 'Senzuala (Siren Call)',
	2 => 'Puternica (Siren Rock)',
	3 => 'Nebunatica (Mad Love)',
	4 => 'Calma (Inner Peace)',
	5 => 'Valoroasa (Rose Pearl)',
);

$middle_title = ! empty( $data['kit_middle_title'] ) ? $data['kit_middle_title'] : '5 Sirene – Discovery Kit';
?>

<main class="wcp-parfum-page wcp-discovery-page" style="<?php echo esc_attr( $bg_style ); ?>">
	<h1 class="screen-reader-text"><?php echo esc_html( $page_title ); ?></h1>

	<section class="wcp-section wcp-hero wcp-discovery-hero">
		<div class="wcp-hero__grid">
			<div class="wcp-hero__media">
				<div class="wcp-arch-frame wcp-arch-frame--tall">
					<?php WCP_Frontend::render_image( $data['hero_product_image'], 'wcp-hero__product-img', $page_title ); ?>
				</div>
			</div>

			<div class="wcp-hero__content">
				<?php WCP_Frontend::render_image( $data['title_logo_image'], 'wcp-title-logo', 'Find Love the scent' ); ?>

				<?php if ( ! empty( $data['kit_title'] ) ) : ?>
					<p class="wcp-kit-title"><?php echo esc_html( $data['kit_title'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['kit_description'] ) ) : ?>
					<div class="wcp-description">
						<?php echo nl2br( esc_html( $data['kit_description'] ) ); ?>
					</div>
				<?php endif; ?>

				<ul class="wcp-scent-bullets">
					<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
						<?php
						$label = ! empty( $data[ 'scent_' . $i . '_label' ] )
							? $data[ 'scent_' . $i . '_label' ]
							: $default_labels[ $i ];
						?>
						<li><?php echo esc_html( $label ); ?></li>
					<?php endfor; ?>
				</ul>

				<?php if ( ! empty( $data['kit_promo'] ) ) : ?>
					<p class="wcp-kit-promo"><?php echo esc_html( $data['kit_promo'] ); ?></p>
				<?php endif; ?>

				<?php WCP_Frontend::render_add_to_cart_button( $product ); ?>
			</div>
		</div>
	</section>

	<section class="wcp-section wcp-discovery-scents">
		<div class="wcp-discovery-scents__grid">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<?php
				$image      = isset( $data[ 'scent_' . $i . '_image' ] ) ? $data[ 'scent_' . $i . '_image' ] : array();
				$linked_id  = ! empty( $data[ 'scent_' . $i . '_product' ] ) ? absint( $data[ 'scent_' . $i . '_product' ] ) : 0;
				$linked_url = $linked_id ? get_permalink( $linked_id ) : '';
				$label      = ! empty( $data[ 'scent_' . $i . '_label' ] )
					? $data[ 'scent_' . $i . '_label' ]
					: $default_labels[ $i ];
				?>
				<article class="wcp-discovery-scent wcp-discovery-scent--<?php echo esc_attr( (string) $i ); ?>">
					<?php if ( $linked_url ) : ?>
						<a class="wcp-discovery-scent__link" href="<?php echo esc_url( $linked_url ); ?>">
							<div class="wcp-discovery-scent__image wcp-circle-frame">
								<?php WCP_Frontend::render_image( $image, 'wcp-discovery-scent__img', $label ); ?>
							</div>
						</a>
					<?php else : ?>
						<div class="wcp-discovery-scent__image wcp-circle-frame">
							<?php WCP_Frontend::render_image( $image, 'wcp-discovery-scent__img', $label ); ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endfor; ?>
		</div>
		<h2 class="wcp-discovery-scents__title"><?php echo esc_html( $middle_title ); ?></h2>
	</section>

	<section class="wcp-section wcp-bottom">
		<div class="wcp-bottom__grid">
			<div class="wcp-bottom__content">
				<?php WCP_Frontend::render_image( $data['brand_image'], 'wcp-brand-image', 'Find Love the scent' ); ?>
				<?php WCP_Frontend::render_add_to_cart_button( $product ); ?>
			</div>

			<div class="wcp-bottom__media">
				<div class="wcp-arch-frame wcp-arch-frame--wide wcp-arch-frame--pill">
					<?php WCP_Frontend::render_image( $data['lifestyle_image'], 'wcp-lifestyle-img', $page_title ); ?>
				</div>
				<div class="wcp-decor wcp-decor--circle" aria-hidden="true"></div>
			</div>
		</div>
	</section>
</main>
