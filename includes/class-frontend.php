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
		add_filter( 'body_class', array( __CLASS__, 'body_class' ) );
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

		$product_title = get_the_title( get_queried_object_id() );
		if ( $product_title ) {
			$parts['title'] = $product_title;
		}

		return $parts;
	}

	/**
	 * Add custom body class while keeping the theme header/footer.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public static function body_class( $classes ) {
		if ( self::is_parfum_product() ) {
			$classes[] = 'wcp-parfum-body';
		}

		return $classes;
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
	 * Plain-text product price label for CTA display.
	 *
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	public static function get_product_price_label( $product ) {
		if ( ! $product || '' === $product->get_price() ) {
			return '';
		}

		$price = wc_get_price_to_display( $product );
		if ( '' === $price || null === $price ) {
			return '';
		}

		return html_entity_decode( wp_strip_all_tags( wc_price( $price ) ), ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Build CTA button label based on price display mode.
	 *
	 * @param string     $price_label   Formatted price label.
	 * @param string     $display_mode  Price display mode.
	 * @return string
	 */
	public static function get_order_button_label( $price_label, $display_mode ) {
		$button_text = 'COMANDA ACUM';

		if ( $price_label && WCP_Product_Meta::PRICE_DISPLAY_IN_BUTTON === $display_mode ) {
			return $button_text . ' - ' . $price_label;
		}

		return $button_text;
	}

	/**
	 * Render add to cart button markup.
	 *
	 * @param WC_Product $product Product object.
	 * @return void
	 */
	public static function render_add_to_cart_button( $product ) {
		if ( ! $product ) {
			return;
		}

		$display_mode = WCP_Product_Meta::get_price_display_mode( $product->get_id() );
		$price_label  = self::get_product_price_label( $product );
		$button_text  = self::get_order_button_label( $price_label, $display_mode );

		echo '<div class="wcp-add-to-cart-block wcp-add-to-cart-block--' . esc_attr( $display_mode ) . '">';

		if ( $price_label && WCP_Product_Meta::PRICE_DISPLAY_ABOVE === $display_mode ) {
			printf(
				'<p class="wcp-product-price">%s</p>',
				esc_html( sprintf( 'PREȚ - %s', $price_label ) )
			);
		}

		if ( $product->is_type( 'simple' ) ) {
			echo '<form class="wcp-add-to-cart" method="post" action="' . esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ) . '">';
			echo '<input type="hidden" name="quantity" value="1" />';
			printf(
				'<button type="submit" name="add-to-cart" value="%1$d" class="wcp-btn-order">%2$s</button>',
				esc_attr( $product->get_id() ),
				esc_html( $button_text )
			);
			echo '</form>';
			echo '</div>';
			return;
		}

		if ( $product->is_type( 'variable' ) && $product->is_purchasable() ) {
			woocommerce_variable_add_to_cart();
			echo '</div>';
			return;
		}

		if ( $product->is_purchasable() ) {
			woocommerce_template_loop_add_to_cart();
			echo '</div>';
			return;
		}

		printf(
			'<button type="button" class="wcp-btn-order" disabled>%s</button>',
			esc_html( $button_text )
		);
		echo '</div>';
	}
}
