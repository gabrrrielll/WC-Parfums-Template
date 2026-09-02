<?php
/**
 * Plugin shortcodes.
 */

defined( 'ABSPATH' ) || exit;

class WCP_Shortcodes {

	/**
	 * Register hooks and shortcodes.
	 */
	public static function init() {
		add_shortcode( 'wcp_find_love_intro', array( __CLASS__, 'render_find_love_intro' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'maybe_enqueue_intro_assets' ) );
	}

	/**
	 * Enqueue intro assets when the current singular post contains the shortcode.
	 */
	public static function maybe_enqueue_intro_assets() {
		if ( self::page_has_intro_shortcode() ) {
			self::enqueue_intro_assets();
		}
	}

	/**
	 * Whether the current post content includes the intro shortcode.
	 *
	 * @return bool
	 */
	private static function page_has_intro_shortcode() {
		global $post;

		if ( ! is_singular() || ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		return has_shortcode( $post->post_content, 'wcp_find_love_intro' );
	}

	/**
	 * Enqueue Find Love intro landing styles and fonts.
	 */
	public static function enqueue_intro_assets() {
		static $enqueued = false;

		if ( $enqueued ) {
			return;
		}

		$enqueued = true;

		wp_enqueue_style(
			'wcp-intro-fonts',
			'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,300;0,400;0,600;1,400&display=swap',
			array(),
			null
		);

		wp_register_style( 'wcp-intro', false, array( 'wcp-intro-fonts' ), WCP_VERSION );
		wp_enqueue_style( 'wcp-intro' );

		$css_file = WCP_PLUGIN_DIR . 'assets/css/intro.css';
		if ( file_exists( $css_file ) ) {
			wp_add_inline_style( 'wcp-intro', file_get_contents( $css_file ) );
		}
	}

	/**
	 * Render Find Love Scent collection intro landing page.
	 *
	 * Usage: [wcp_find_love_intro]
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_find_love_intro( $atts ) {
		self::enqueue_intro_assets();

		ob_start();
		include WCP_PLUGIN_DIR . 'templates/content-find-love-intro.php';
		return ob_get_clean();
	}
}
