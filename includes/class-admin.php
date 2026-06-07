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
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
	}

	/**
	 * Add meta box to product edit screen.
	 */
	public static function add_meta_box() {
		add_meta_box(
			'wcp-parfum-template',
			__( 'Template Parfum Find Love', 'wc-parfums-template' ),
			array( __CLASS__, 'render_meta_box' ),
			'product',
			'normal',
			'high'
		);
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

		wp_enqueue_media();
		wp_enqueue_style(
			'wcp-admin',
			WCP_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			WCP_VERSION
		);
		wp_enqueue_script(
			'wcp-admin',
			WCP_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			WCP_VERSION,
			true
		);
	}

	/**
	 * Render meta box content.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_meta_box( $post ) {
		wp_nonce_field( 'wcp_save_meta', 'wcp_meta_nonce' );

		$fields = WCP_Product_Meta::get_fields();
		$data   = WCP_Product_Meta::get_template_data( $post->ID );

		echo '<div class="wcp-admin-fields">';

		foreach ( $fields as $slug => $field ) {
			$key   = WCP_Product_Meta::meta_key( $slug );
			$value = isset( $data[ $slug ] ) ? $data[ $slug ] : '';
			$name  = 'wcp_' . $slug;

			echo '<div class="wcp-field wcp-field--' . esc_attr( $field['type'] ) . '" data-wcp-field="' . esc_attr( $slug ) . '">';
			echo '<label class="wcp-field__label" for="' . esc_attr( $name ) . '">' . esc_html( $field['label'] ) . '</label>';

			switch ( $field['type'] ) {
				case 'select':
					echo '<select id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" class="wcp-field__select">';
					foreach ( $field['options'] as $option_value => $option_label ) {
						printf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $option_value ),
							selected( $value, $option_value, false ),
							esc_html( $option_label )
						);
					}
					echo '</select>';
					break;

				case 'text':
					printf(
						'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="wcp-field__text widefat" />',
						esc_attr( $name ),
						esc_attr( is_string( $value ) ? $value : '' )
					);
					break;

				case 'textarea':
					printf(
						'<textarea id="%1$s" name="%1$s" rows="4" class="wcp-field__textarea widefat">%2$s</textarea>',
						esc_attr( $name ),
						esc_textarea( is_string( $value ) ? $value : '' )
					);
					break;

				case 'image':
					$image_id  = is_array( $value ) ? (int) $value['id'] : absint( $value );
					$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';

					echo '<div class="wcp-image-field">';
					printf(
						'<input type="hidden" id="%1$s" name="%1$s" value="%2$d" class="wcp-image-field__input" />',
						esc_attr( $name ),
						$image_id
					);
					echo '<div class="wcp-image-field__preview">';
					if ( $image_url ) {
						echo '<img src="' . esc_url( $image_url ) . '" alt="" />';
					}
					echo '</div>';
					echo '<div class="wcp-image-field__actions">';
					echo '<button type="button" class="button wcp-image-field__upload">' . esc_html__( 'Selecteaz? imagine', 'wc-parfums-template' ) . '</button>';
					echo '<button type="button" class="button wcp-image-field__remove' . ( $image_id ? '' : ' hidden' ) . '">' . esc_html__( 'Elimin?', 'wc-parfums-template' ) . '</button>';
					echo '</div></div>';
					break;
			}

			echo '</div>';
		}

		echo '</div>';
	}
}
