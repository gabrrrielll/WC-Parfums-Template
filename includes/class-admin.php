<?php
/**
 * Admin UI for product template fields.
 */

defined( 'ABSPATH' ) || exit;

class WCP_Admin {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ), 1 );
		add_filter( 'admin_body_class', array( __CLASS__, 'admin_body_class' ) );
	}

	/**
	 * Register metaboxes.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'wcp-template-selector',
			'Template pagina produs',
			array( __CLASS__, 'render_template_selector' ),
			'product',
			'side',
			'high'
		);

		add_meta_box(
			'wcp-parfum-visual-editor',
			'Editor vizual - Parfum Find Love',
			array( __CLASS__, 'render_visual_editor' ),
			'product',
			'normal',
			'high'
		);

		add_meta_box(
			'wcp-discovery-visual-editor',
			'Editor vizual - 5 Sirene Discovery Kit',
			array( __CLASS__, 'render_discovery_editor' ),
			'product',
			'normal',
			'high'
		);
	}

	/**
	 * Add body class for current template mode.
	 *
	 * @param string $classes Existing classes.
	 * @return string
	 */
	public static function admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( ! $screen || 'product' !== $screen->post_type || ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
			return $classes;
		}

		$template = self::get_current_template();
		return $classes . ' wcp-edit-mode-' . $template;
	}

	/**
	 * Resolve template for the product edit screen.
	 *
	 * @return string
	 */
	private static function get_current_template() {
		global $post;

		if ( $post && 'product' === $post->post_type && $post->ID ) {
			return WCP_Product_Meta::get_template( $post->ID );
		}

		return WCP_Product_Meta::TEMPLATE_DEFAULT;
	}

	/**
	 * Enqueue admin assets on product screen.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || 'product' !== $screen->post_type ) {
			return;
		}

		self::enqueue_dompurify_compat();
		wp_enqueue_media();

		wp_register_style( 'wcp-admin', false, array(), WCP_VERSION );
		wp_enqueue_style( 'wcp-admin' );

		$admin_css = WCP_PLUGIN_DIR . 'assets/css/admin.css';
		if ( file_exists( $admin_css ) ) {
			wp_add_inline_style( 'wcp-admin', file_get_contents( $admin_css ) );
		}

		wp_register_script( 'wcp-admin', false, array( 'jquery' ), WCP_VERSION, true );
		wp_enqueue_script( 'wcp-admin' );

		wp_localize_script(
			'wcp-admin',
			'wcpAdmin',
			array(
				'templateParfum'    => WCP_Product_Meta::TEMPLATE_PARFUM,
				'templateDiscovery' => WCP_Product_Meta::TEMPLATE_DISCOVERY,
				'templateDefault'   => WCP_Product_Meta::TEMPLATE_DEFAULT,
				'currencySymbol'    => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : 'RON',
			)
		);

		$admin_js = WCP_PLUGIN_DIR . 'assets/js/admin.js';
		if ( file_exists( $admin_js ) ) {
			wp_add_inline_script( 'wcp-admin', file_get_contents( $admin_js ), 'after' );
		}
	}

	/**
	 * Prevent WooCommerce admin scripts from failing when DOMPurify is not loaded.
	 */
	private static function enqueue_dompurify_compat() {
		foreach ( array( 'wp-dompurify', 'dompurify' ) as $handle ) {
			if ( wp_script_is( $handle, 'registered' ) ) {
				wp_enqueue_script( $handle );
				return;
			}
		}

		wp_add_inline_script(
			'jquery',
			'window.DOMPurify = window.DOMPurify || { sanitize: function (value) { return value == null ? "" : String(value); } };',
			'after'
		);
	}

	/**
	 * Render template selector in sidebar.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_template_selector( $post ) {
		wp_nonce_field( 'wcp_save_meta', 'wcp_meta_nonce' );

		$template = WCP_Product_Meta::get_template( $post->ID );

		echo '<p class="wcp-template-selector-desc">Alege layout-ul paginii de produs. La template-urile Find Love se ascunde descrierea clasica si se afiseaza editorul vizual aferent.</p>';
		echo '<select id="wcp_template" name="wcp_template" class="widefat wcp-template-select">';

		foreach ( WCP_Product_Meta::get_template_options() as $value => $label ) {
			printf(
				'<option value="%1$s" %2$s>%3$s</option>',
				esc_attr( $value ),
				selected( $template, $value, false ),
				esc_html( $label )
			);
		}

		echo '</select>';
	}

	/**
	 * Render parfum visual editor metabox.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_visual_editor( $post ) {
		$data = WCP_Product_Meta::get_template_data( $post->ID );

		echo '<div id="wcp-visual-editor-wrap" class="wcp-visual-editor-wrap">';
		include WCP_PLUGIN_DIR . 'templates/admin-visual-editor.php';
		echo '</div>';
	}

	/**
	 * Render discovery kit visual editor metabox.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_discovery_editor( $post ) {
		$data = WCP_Product_Meta::get_template_data( $post->ID );

		echo '<div id="wcp-discovery-editor-wrap" class="wcp-visual-editor-wrap">';
		include WCP_PLUGIN_DIR . 'templates/admin-discovery-editor.php';
		echo '</div>';
	}

	/**
	 * Render a single admin field control.
	 *
	 * @param string $slug  Field slug.
	 * @param array  $field Field definition.
	 * @param mixed  $value Field value.
	 */
	public static function render_field( $slug, $field, $value ) {
		$name = 'wcp_' . $slug;

		echo '<div class="wcp-slot wcp-slot--' . esc_attr( $slug ) . ' wcp-slot--' . esc_attr( $field['type'] ) . '" data-wcp-field="' . esc_attr( $slug ) . '">';
		echo '<label class="wcp-slot__label" for="' . esc_attr( $name ) . '">' . esc_html( $field['label'] ) . '</label>';

		switch ( $field['type'] ) {
			case 'text':
				printf(
					'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="wcp-slot__text widefat" placeholder="%3$s" />',
					esc_attr( $name ),
					esc_attr( is_string( $value ) ? $value : '' ),
					esc_attr( isset( $field['placeholder'] ) ? $field['placeholder'] : '' )
				);
				break;

			case 'textarea':
				printf(
					'<textarea id="%1$s" name="%1$s" rows="5" class="wcp-slot__textarea widefat">%2$s</textarea>',
					esc_attr( $name ),
					esc_textarea( is_string( $value ) ? $value : '' )
				);
				break;

			case 'image':
				self::render_image_field( $name, $value );
				break;

			case 'select':
				$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array();
				printf(
					'<select id="%1$s" name="%1$s" class="wcp-slot__select widefat">',
					esc_attr( $name )
				);
				foreach ( $options as $option_value => $option_label ) {
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $option_value ),
						selected( $value, $option_value, false ),
						esc_html( $option_label )
					);
				}
				echo '</select>';
				break;

			case 'product':
				$product_id = absint( $value );
				$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
				printf(
					'<input type="number" min="0" step="1" id="%1$s" name="%1$s" value="%2$s" class="wcp-slot__text widefat" placeholder="%3$s" />',
					esc_attr( $name ),
					$product_id ? esc_attr( (string) $product_id ) : '',
					esc_attr( $placeholder ? $placeholder : 'ID produs WooCommerce' )
				);
				if ( $product_id ) {
					$linked = get_the_title( $product_id );
					if ( $linked ) {
						echo '<p class="description">' . esc_html( $linked ) . '</p>';
					}
				}
				break;
		}

		echo '</div>';
	}

	/**
	 * Render image upload field.
	 *
	 * @param string $name  Input name.
	 * @param mixed  $value Field value.
	 */
	public static function render_image_field( $name, $value ) {
		$image_id  = is_array( $value ) ? (int) $value['id'] : absint( $value );
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

		echo '<div class="wcp-image-field" data-wcp-image-field="' . esc_attr( $name ) . '">';
		printf(
			'<input type="hidden" id="%1$s" name="%1$s" value="%2$d" class="wcp-image-field__input" />',
			esc_attr( $name ),
			$image_id
		);
		echo '<div class="wcp-image-field__preview">';
		if ( $image_url ) {
			echo '<img src="' . esc_url( $image_url ) . '" alt="" />';
		} else {
			echo '<span class="wcp-image-field__placeholder">Fara imagine</span>';
		}
		echo '</div>';
		echo '<div class="wcp-image-field__actions">';
		echo '<button type="button" class="button wcp-image-field__upload">Selecteaza imagine</button>';
		echo '<button type="button" class="button wcp-image-field__remove' . ( $image_id ? '' : ' hidden' ) . '">Elimina</button>';
		echo '</div></div>';
	}
}
