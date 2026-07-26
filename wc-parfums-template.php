<?php
/**
 * Plugin Name: WC Parfums Template
 * Description: Extinde WooCommerce cu un template custom pentru paginile de produs parfum Find Love.
 * Version: 1.0.26
 * Author: Gabriel Sandu
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 7.0
 * WC tested up to: 9.0
 * Text Domain: wc-parfums-template
 */

defined( 'ABSPATH' ) || exit;

define( 'WCP_VERSION', '1.0.26' );
define( 'WCP_PLUGIN_FILE', __FILE__ );
define( 'WCP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check WooCommerce dependency.
 */
function wcp_check_dependencies() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		add_action(
			'admin_notices',
			function () {
				echo '<div class="notice notice-error"><p>';
				esc_html_e( 'WC Parfums Template necesit? WooCommerce activ.', 'wc-parfums-template' );
				echo '</p></div>';
			}
		);
		return false;
	}
	return true;
}

/**
 * Bootstrap plugin.
 */
function wcp_init() {
	if ( ! wcp_check_dependencies() ) {
		return;
	}

	require_once WCP_PLUGIN_DIR . 'includes/class-product-meta.php';
	require_once WCP_PLUGIN_DIR . 'includes/class-admin.php';
	require_once WCP_PLUGIN_DIR . 'includes/class-frontend.php';

	WCP_Product_Meta::init();
	WCP_Admin::init();
	WCP_Frontend::init();
}
add_action( 'plugins_loaded', 'wcp_init' );

/**
 * Declare HPOS compatibility.
 */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
