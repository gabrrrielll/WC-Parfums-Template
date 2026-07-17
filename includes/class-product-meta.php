<?php
/**
 * Product meta field definitions and helpers.
 */

defined( 'ABSPATH' ) || exit;

class WCP_Product_Meta {

	const META_TEMPLATE      = '_wcp_template';
	const META_SEO_TITLE     = '_wcp_seo_title';
	const META_BACKGROUND    = '_wcp_background_image';
	const META_HERO_IMAGE    = '_wcp_hero_product_image';
	const META_TITLE_LOGO    = '_wcp_title_logo_image';
	const META_TAGLINE       = '_wcp_tagline';
	const META_DESCRIPTION   = '_wcp_hero_description';
	const META_NOTE_TOP_IMG  = '_wcp_note_top_image';
	const META_NOTE_TOP_TEXT = '_wcp_note_top_text';
	const META_NOTE_MID_IMG  = '_wcp_note_middle_image';
	const META_NOTE_MID_TEXT = '_wcp_note_middle_text';
	const META_NOTE_BASE_IMG = '_wcp_note_base_image';
	const META_NOTE_BASE_TEXT = '_wcp_note_base_text';
	const META_BRAND_IMAGE   = '_wcp_brand_image';
	const META_LIFESTYLE_IMG = '_wcp_lifestyle_image';
	const META_PRICE_DISPLAY = '_wcp_price_display_mode';

	const META_KIT_TITLE        = '_wcp_kit_title';
	const META_KIT_PROMO        = '_wcp_kit_promo';
	const META_KIT_MIDDLE_TITLE = '_wcp_kit_middle_title';

	const TEMPLATE_DEFAULT   = 'default';
	const TEMPLATE_PARFUM    = 'parfum_landing';
	const TEMPLATE_DISCOVERY = 'discovery_kit';

	const PRICE_DISPLAY_ABOVE     = 'above_button';
	const PRICE_DISPLAY_IN_BUTTON = 'in_button';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'save_post_product', array( __CLASS__, 'save' ), 10, 2 );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_wc' ), 10, 1 );
	}

	/**
	 * Template selector options.
	 *
	 * @return array
	 */
	public static function get_template_options() {
		return array(
			self::TEMPLATE_DEFAULT   => 'WooCommerce standard',
			self::TEMPLATE_PARFUM    => 'Parfum Find Love (landing)',
			self::TEMPLATE_DISCOVERY => '5 Sirene Discovery Kit',
		);
	}

	/**
	 * Valid template slugs.
	 *
	 * @return array
	 */
	public static function get_valid_templates() {
		return array(
			self::TEMPLATE_DEFAULT,
			self::TEMPLATE_PARFUM,
			self::TEMPLATE_DISCOVERY,
		);
	}

	/**
	 * Price display mode options for the visual editor.
	 *
	 * @return array
	 */
	public static function get_price_display_options() {
		return array(
			self::PRICE_DISPLAY_ABOVE     => 'Clasic - deasupra butonului',
			self::PRICE_DISPLAY_IN_BUTTON => 'Inclus in butonul COMANDA ACUM',
		);
	}

	/**
	 * Shared fields used by custom landing templates.
	 *
	 * @return array
	 */
	public static function get_shared_fields() {
		return array(
			'price_display_mode' => array(
				'type'    => 'select',
				'label'   => 'Afisare pret (din WooCommerce)',
				'options' => self::get_price_display_options(),
			),
			'background_image' => array(
				'type'  => 'image',
				'label' => 'Fundal pagina',
			),
			'hero_product_image' => array(
				'type'  => 'image',
				'label' => 'Imagine produs (sus stanga)',
			),
			'title_logo_image' => array(
				'type'  => 'image',
				'label' => 'Logo brand (sus dreapta)',
			),
			'brand_image' => array(
				'type'  => 'image',
				'label' => 'Imagine brand (jos stanga)',
			),
			'lifestyle_image' => array(
				'type'  => 'image',
				'label' => 'Imagine lifestyle (jos dreapta)',
			),
		);
	}

	/**
	 * Parfum landing content fields.
	 *
	 * @return array
	 */
	public static function get_parfum_fields() {
		return array(
			'tagline' => array(
				'type'  => 'text',
				'label' => 'Tagline (ex: Calm. Armonie. Iubire de sine.)',
			),
			'hero_description' => array(
				'type'  => 'textarea',
				'label' => 'Descriere principala',
			),
			'note_top_image' => array(
				'type'  => 'image',
				'label' => 'Note de varf - imagine',
			),
			'note_top_text' => array(
				'type'  => 'textarea',
				'label' => 'Note de varf - text',
			),
			'note_middle_image' => array(
				'type'  => 'image',
				'label' => 'Note de mijloc - imagine',
			),
			'note_middle_text' => array(
				'type'  => 'textarea',
				'label' => 'Note de mijloc - text',
			),
			'note_base_image' => array(
				'type'  => 'image',
				'label' => 'Note de baza - imagine',
			),
			'note_base_text' => array(
				'type'  => 'textarea',
				'label' => 'Note de baza - text',
			),
		);
	}

	/**
	 * Discovery kit content fields.
	 *
	 * @return array
	 */
	public static function get_discovery_fields() {
		$fields = array(
			'kit_title' => array(
				'type'  => 'text',
				'label' => 'Titlu kit (ex: 5 Sirene - Discovery Kit.)',
			),
			'kit_description' => array(
				'type'  => 'textarea',
				'label' => 'Descriere kit',
			),
			'kit_promo' => array(
				'type'  => 'text',
				'label' => 'Text promotie (ex: Comanda acum tot setul...)',
			),
			'kit_middle_title' => array(
				'type'  => 'text',
				'label' => 'Titlu zona mijloc (ex: 5 Sirene – Discovery Kit)',
			),
		);

		$defaults = array(
			1 => 'Senzuala (Siren Call)',
			2 => 'Puternica (Siren Rock)',
			3 => 'Nebunatica (Mad Love)',
			4 => 'Calma (Inner Peace)',
			5 => 'Valoroasa (Rose Pearl)',
		);

		for ( $i = 1; $i <= 5; $i++ ) {
			$fields[ 'scent_' . $i . '_label' ] = array(
				'type'        => 'text',
				'label'       => sprintf( 'Bullet %d - text', $i ),
				'placeholder' => $defaults[ $i ],
			);
			$fields[ 'scent_' . $i . '_image' ] = array(
				'type'  => 'image',
				'label' => sprintf( 'Parfum %d - imagine cerc', $i ),
			);
			$fields[ 'scent_' . $i . '_product' ] = array(
				'type'  => 'product',
				'label' => sprintf( 'Parfum %d - link produs WooCommerce (ID)', $i ),
			);
		}

		return $fields;
	}

	/**
	 * Content fields for the parfum visual editor.
	 *
	 * @return array
	 */
	public static function get_content_fields() {
		return array_merge( self::get_shared_fields(), self::get_parfum_fields() );
	}

	/**
	 * Content fields for the discovery visual editor.
	 *
	 * @return array
	 */
	public static function get_discovery_content_fields() {
		return array_merge( self::get_shared_fields(), self::get_discovery_fields() );
	}

	/**
	 * All fields including template (for data loading/saving).
	 *
	 * @return array
	 */
	public static function get_fields() {
		return array_merge(
			array(
				'template' => array(
					'type'    => 'select',
					'label'   => 'Template pagina produs',
					'options' => self::get_template_options(),
				),
			),
			self::get_shared_fields(),
			self::get_parfum_fields(),
			self::get_discovery_fields()
		);
	}

	/**
	 * Meta key for a field slug.
	 *
	 * @param string $field Field slug.
	 * @return string
	 */
	public static function meta_key( $field ) {
		$map = array(
			'template'           => self::META_TEMPLATE,
			'seo_title'          => self::META_SEO_TITLE,
			'background_image'   => self::META_BACKGROUND,
			'hero_product_image' => self::META_HERO_IMAGE,
			'title_logo_image'   => self::META_TITLE_LOGO,
			'tagline'            => self::META_TAGLINE,
			'hero_description'   => self::META_DESCRIPTION,
			'note_top_image'     => self::META_NOTE_TOP_IMG,
			'note_top_text'      => self::META_NOTE_TOP_TEXT,
			'note_middle_image'  => self::META_NOTE_MID_IMG,
			'note_middle_text'   => self::META_NOTE_MID_TEXT,
			'note_base_image'    => self::META_NOTE_BASE_IMG,
			'note_base_text'     => self::META_NOTE_BASE_TEXT,
			'brand_image'        => self::META_BRAND_IMAGE,
			'lifestyle_image'    => self::META_LIFESTYLE_IMG,
			'price_display_mode' => self::META_PRICE_DISPLAY,
			'kit_title'          => self::META_KIT_TITLE,
			'kit_description'    => '_wcp_kit_description',
			'kit_promo'          => self::META_KIT_PROMO,
			'kit_middle_title'   => self::META_KIT_MIDDLE_TITLE,
		);

		for ( $i = 1; $i <= 5; $i++ ) {
			$map[ 'scent_' . $i . '_label' ]   = '_wcp_scent_' . $i . '_label';
			$map[ 'scent_' . $i . '_image' ]   = '_wcp_scent_' . $i . '_image';
			$map[ 'scent_' . $i . '_product' ] = '_wcp_scent_' . $i . '_product';
		}

		return isset( $map[ $field ] ) ? $map[ $field ] : '_wcp_' . $field;
	}

	/**
	 * Whether product uses a custom Find Love landing template.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public static function uses_custom_template( $product_id ) {
		$template = self::get_template( $product_id );
		return in_array( $template, array( self::TEMPLATE_PARFUM, self::TEMPLATE_DISCOVERY ), true );
	}

	/**
	 * Whether product uses the parfum landing template.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public static function uses_parfum_template( $product_id ) {
		return self::TEMPLATE_PARFUM === self::get_template( $product_id );
	}

	/**
	 * Whether product uses the discovery kit template.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public static function uses_discovery_template( $product_id ) {
		return self::TEMPLATE_DISCOVERY === self::get_template( $product_id );
	}

	/**
	 * Get saved template slug for a product.
	 *
	 * @param int $product_id Product ID.
	 * @return string
	 */
	public static function get_template( $product_id ) {
		$template = get_post_meta( $product_id, self::META_TEMPLATE, true );
		return in_array( $template, self::get_valid_templates(), true )
			? $template
			: self::TEMPLATE_DEFAULT;
	}

	/**
	 * Get saved price display mode for a product.
	 *
	 * @param int $product_id Product ID.
	 * @return string
	 */
	public static function get_price_display_mode( $product_id ) {
		$mode = get_post_meta( $product_id, self::META_PRICE_DISPLAY, true );

		return in_array( $mode, array( self::PRICE_DISPLAY_ABOVE, self::PRICE_DISPLAY_IN_BUTTON ), true )
			? $mode
			: self::PRICE_DISPLAY_ABOVE;
	}

	/**
	 * Get all template data for a product.
	 *
	 * @param int $product_id Product ID.
	 * @return array
	 */
	public static function get_template_data( $product_id ) {
		$data = array();

		foreach ( self::get_fields() as $slug => $field ) {
			$value = get_post_meta( $product_id, self::meta_key( $slug ), true );

			if ( 'image' === $field['type'] && $value ) {
				$data[ $slug ] = array(
					'id'  => absint( $value ),
					'url' => wp_get_attachment_image_url( absint( $value ), 'full' ),
					'alt' => get_post_meta( absint( $value ), '_wp_attachment_image_alt', true ),
				);
			} else {
				$data[ $slug ] = $value;
			}
		}

		return $data;
	}

	/**
	 * Save via WooCommerce product meta hook.
	 *
	 * @param int $product_id Product ID.
	 */
	public static function save_wc( $product_id ) {
		self::persist_meta( $product_id );
	}

	/**
	 * Save product meta.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $post || 'product' !== $post->post_type ) {
			return;
		}

		self::persist_meta( $post_id );
	}

	/**
	 * Persist template and field values.
	 *
	 * @param int $post_id Product ID.
	 */
	private static function persist_meta( $post_id ) {
		if ( ! isset( $_POST['wcp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wcp_meta_nonce'] ) ), 'wcp_save_meta' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$template = isset( $_POST['wcp_template'] ) ? sanitize_text_field( wp_unslash( $_POST['wcp_template'] ) ) : self::TEMPLATE_DEFAULT;
		if ( ! in_array( $template, self::get_valid_templates(), true ) ) {
			$template = self::TEMPLATE_DEFAULT;
		}
		update_post_meta( $post_id, self::META_TEMPLATE, $template );

		$text_fields = array(
			'tagline'          => 'sanitize_text_field',
			'hero_description' => 'sanitize_textarea_field',
			'note_top_text'    => 'sanitize_textarea_field',
			'note_middle_text' => 'sanitize_textarea_field',
			'note_base_text'   => 'sanitize_textarea_field',
			'kit_title'        => 'sanitize_text_field',
			'kit_description'  => 'sanitize_textarea_field',
			'kit_promo'        => 'sanitize_text_field',
			'kit_middle_title' => 'sanitize_text_field',
		);

		for ( $i = 1; $i <= 5; $i++ ) {
			$text_fields[ 'scent_' . $i . '_label' ] = 'sanitize_text_field';
		}

		foreach ( $text_fields as $field => $sanitize ) {
			$key = 'wcp_' . $field;
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, self::meta_key( $field ), call_user_func( $sanitize, wp_unslash( $_POST[ $key ] ) ) );
			}
		}

		$image_fields = array(
			'background_image',
			'hero_product_image',
			'title_logo_image',
			'note_top_image',
			'note_middle_image',
			'note_base_image',
			'brand_image',
			'lifestyle_image',
		);

		for ( $i = 1; $i <= 5; $i++ ) {
			$image_fields[] = 'scent_' . $i . '_image';
		}

		foreach ( $image_fields as $field ) {
			$key = 'wcp_' . $field;
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, self::meta_key( $field ), absint( $_POST[ $key ] ) );
			}
		}

		for ( $i = 1; $i <= 5; $i++ ) {
			$key = 'wcp_scent_' . $i . '_product';
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, self::meta_key( 'scent_' . $i . '_product' ), absint( $_POST[ $key ] ) );
			}
		}

		$key = 'wcp_price_display_mode';
		if ( isset( $_POST[ $key ] ) ) {
			$mode = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			if ( ! in_array( $mode, array( self::PRICE_DISPLAY_ABOVE, self::PRICE_DISPLAY_IN_BUTTON ), true ) ) {
				$mode = self::PRICE_DISPLAY_ABOVE;
			}
			update_post_meta( $post_id, self::META_PRICE_DISPLAY, $mode );
		}
	}
}
