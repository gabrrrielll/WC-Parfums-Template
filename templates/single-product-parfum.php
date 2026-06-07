<?php
/**
 * Minimal full-page template for parfum landing products.
 * Bypasses theme header, footer and newsletter.
 */

defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'wcp-parfum-body' ); ?>>
<?php wp_body_open(); ?>

<?php
while ( have_posts() ) :
	the_post();
	$product_id = get_the_ID();
	$data       = WCP_Product_Meta::get_template_data( $product_id );
	$product    = wc_get_product( $product_id );

	include WCP_PLUGIN_DIR . 'templates/content-parfum-landing.php';
endwhile;
?>

<?php wp_footer(); ?>
</body>
</html>
