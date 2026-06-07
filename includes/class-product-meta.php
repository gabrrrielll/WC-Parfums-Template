<?php
/**
 * Product meta field definitions and helpers.
 */

defined( 'ABSPATH' ) || exit;

class WCP_Product_Meta {

	const META_TEMPLATE       = '_wcp_template';
	const META_SEO_TITLE      = '_wcp_seo_title';
	const META_BACKGROUND     = '_wcp_background_image';
	const META_HERO_IMAGE     = '_wcp_hero_product_image';
	const META_TITLE_LOGO     = '_wcp_title_logo_image';
	const META_TAGLINE        = '_wcp_tagline';
	const META_DESCRIPTION    = '_wcp_hero_description';
	const META_NOTE_TOP_IMG   = '_wcp_note_top_image';
	const META_NOTE_TOP_TEXT  = '_wcp_note_top_text';
	const META_NOTE_MID_IMG   = '_wcp_note_middle_image';
	const META_NOTE_MID_TEXT  = '_wcp_note_middle_text';
	const META_NOTE_BASE_IMG  = '_wcp_note_base_image';
	const META_NOTE_BASE_TEXT = '_wcp_note_base_text';
	const META_BRAND_IMAGE    = '_wcp_brand_image';
	const META_LIFESTYLE_IMG  = '_wcp_lifestyle_image';

	const TEMPLATE_DEFAULT = 'default';
	const TEMPLATE_PARFUM  = 'parfum_landing';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'save_post_product', array( __CLASS__, 'save' ), 10, 2 );
	}

	/**
	 * Template selector options.
	 *
	 * @return array
	 */
	public static function get_template_options() {
		return array(
			self::TEMPLATE_DEFAULT => 'WooCommerce standard',
			self::TEMPLATE_PARFUM  => 'Parfum Find Love (landing)',
		);
	}

	/**
	 * Content field definitions for the visual editor (excludes template).
	 *
	 * @return array
	 */
	public static function get_content_fields() {
		return array(
			'seo_title' => array(
				'type'  => 'text',
				'label' => 'Titlu SEO (nu se afiseaza vizual)',
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
				'label' => 'Logo titlu parfum (sus dreapta si jos stanga)',
			),
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
			'brand_image' => array(
				'type'  => 'image',
				'label' => 'Imagine brand (ex: + MAGNETIC ATTRACTION +)',
			),
			'lifestyle_image' => array(
				'type'  => 'image',
				'label' => 'Imagine lifestyle (jos dreapta)',
			),
		);
	}

	/**
	 * All fields including template (for data loading).
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
			self::get_content_fields()
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
		);

		return isset( $map[ $field ] ) ? $map[ $field ] : '_wcp_' . $field;
	}

	/**
	 * Whether product uses the parfum landing template.
	 *
	 * @param int $product_id Product ID.
	 * @return bool
	 */
	public static function uses_parfum_template( $product_id ) {
		$template = get_post_meta( $product_id, self::META_TEMPLATE, true );
		return self::TEMPLATE_PARFUM === $template;
	}

	/**
	 * Get saved template slug for a product.
	 *
	 * @param int $product_id Product ID.
	 * @return string
	 */
	public static function get_template( $product_id ) {
		$template = get_post_meta( $product_id, self::META_TEMPLATE, true );
		return in_array( $template, array( self::TEMPLATE_DEFAULT, self::TEMPLATE_PARFUM ), true )
			? $template
			: self::TEMPLATE_DEFAULT;
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
	 * Save product meta.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST['wcp_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wcp_meta_nonce'] ) ), 'wcp_save_meta' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$template = isset( $_POST['wcp_template'] ) ? sanitize_text_field( wp_unslash( $_POST['wcp_template'] ) ) : self::TEMPLATE_DEFAULT;
		if ( ! in_array( $template, array( self::TEMPLATE_DEFAULT, self::TEMPLATE_PARFUM ), true ) ) {
			$template = self::TEMPLATE_DEFAULT;
		}
		update_post_meta( $post_id, self::META_TEMPLATE, $template );

		$text_fields = array(
			'seo_title'          => 'sanitize_text_field',
			'tagline'            => 'sanitize_text_field',
			'hero_description'   => 'sanitize_textarea_field',
			'note_top_text'      => 'sanitize_textarea_field',
			'note_middle_text'   => 'sanitize_textarea_field',
			'note_base_text'     => 'sanitize_textarea_field',
		);

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

		foreach ( $image_fields as $field ) {
			$key = 'wcp_' . $field;
			if ( isset( $_POST[ $key ] ) ) {
				update_post_meta( $post_id, self::meta_key( $field ), absint( $_POST[ $key ] ) );
			}
		}
	}
}
