<?php
/**
 * Product template for Discovery Kit landing products.
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	$product_id = get_the_ID();
	$data       = WCP_Product_Meta::get_template_data( $product_id );
	$product    = wc_get_product( $product_id );

	include WCP_PLUGIN_DIR . 'templates/content-discovery-landing.php';
endwhile;
?>

<?php
get_footer();
