<?php
/**
 * Frontend template rendering and WooCommerce integration.
 */

defined( 'ABSPATH' ) || exit;

class WCP_Frontend {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_filter( 'template_include', array( __CLASS__, 'template_include' ), 99 );
		add_filter( 'document_title_parts', array( __CLASS__, 'document_title_parts' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	/**
	 * Whether current request is a parfum template product.
	 *
	 * @return bool
	 */
	public static function is_parfum_product() {
		if ( ! is_singular( 'product' ) ) {
			return false;
		}

		return WCP_Product_Meta::uses_parfum_template( get_queried_object_id() );
	}

	/**
	 * Load minimal full-page template without theme header/footer.
	 *
	 * @param string $template Current template path.
	 * @return string
	 */
	public static function template_include( $template ) {
		if ( ! self::is_parfum_product() ) {
			return $template;
		}

		$plugin_template = WCP_PLUGIN_DIR . 'templates/single-product-parfum.php';
		return file_exists( $plugin_template ) ? $plugin_template : $template;
	}

	/**
	 * Override document title with SEO field.
	 *
	 * @param array $parts Title parts.
	 * @return array
	 */
	public static function document_title_parts( $parts ) {
		if ( ! self::is_parfum_product() ) {
			return $parts;
		}

		$seo_title = get_post_meta( get_queried_object_id(), WCP_Product_Meta::META_SEO_TITLE, true );
		if ( $seo_title ) {
			$parts['title'] = $seo_title;
		}

		return $parts;
	}

	/**
	 * Enqueue frontend styles.
	 */
	public static function enqueue_assets() {
		if ( ! self::is_parfum_product() ) {
			return;
		}

		wp_enqueue_style(
			'wcp-fonts',
			'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@400;600;700&display=swap',
			array(),
			null
		);

		wp_register_style( 'wcp-frontend', false, array( 'wcp-fonts' ), WCP_VERSION );
		wp_enqueue_style( 'wcp-frontend' );

		$frontend_css = WCP_PLUGIN_DIR . 'assets/css/frontend.css';
		if ( file_exists( $frontend_css ) ) {
			wp_add_inline_style( 'wcp-frontend', file_get_contents( $frontend_css ) );
		}
	}

	/**
	 * Render an uploaded image field.
	 *
	 * @param mixed  $image       Image field data.
	 * @param string $class       CSS class.
	 * @param string $default_alt Default alt text.
	 */
	public static function render_image( $image, $class, $default_alt = '' ) {
		if ( ! is_array( $image ) || empty( $image['url'] ) ) {
			return;
		}

		$alt = ! empty( $image['alt'] ) ? $image['alt'] : $default_alt;

		printf(
			'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" />',
			esc_url( $image['url'] ),
			esc_attr( $alt ),
			esc_attr( $class )
		);
	}

	/**
	 * Render add to cart button markup.
	 *
	 * @param WC_Product $product Product object.
	 * @return void
	 */
	public static function render_add_to_cart_button( $product ) {
		if ( ! $product || ! $product->is_purchasable() ) {
			return;
		}

		$button_text = 'COMANDA ACUM';

		if ( $product->is_type( 'simple' ) ) {
			echo '<form class="wcp-add-to-cart" method="post" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '">';
			echo '<input type="hidden" name="quantity" value="1" />';
			printf(
				'<button type="submit" name="add-to-cart" value="%1$d" class="wcp-btn-order">%2$s</button>',
				esc_attr( $product->get_id() ),
				esc_html( $button_text )
			);
			echo '</form>';
			return;
		}

		if ( $product->is_type( 'variable' ) ) {
			woocommerce_variable_add_to_cart();
			return;
		}

		woocommerce_template_loop_add_to_cart();
	}
}
