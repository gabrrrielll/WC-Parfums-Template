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
	<style id="wcp-critical-layout">
		.wcp-parfum-page{box-sizing:border-box;width:100%;min-height:100vh}
		.wcp-parfum-page *,.wcp-parfum-page *:before,.wcp-parfum-page *:after{box-sizing:border-box}
		.wcp-hero__grid,.wcp-notes__grid,.wcp-bottom__grid{display:grid}
		.wcp-hero__grid{grid-template-columns:minmax(240px,340px) 1fr;gap:48px 64px;align-items:center}
		.wcp-notes__grid{grid-template-columns:repeat(3,1fr);gap:24px 32px}
		.wcp-bottom__grid{grid-template-columns:1fr minmax(280px,420px);gap:48px 64px;align-items:center}
		.wcp-hero__content{text-align:left;display:flex;flex-direction:column;align-items:flex-start;max-width:560px}
		.wcp-title-logo{display:block;max-width:min(100%,220px);height:auto;margin:0}
		.wcp-title-logo--bottom{max-width:min(100%,420px);margin:0 auto}
		.wcp-brand-image{display:block;max-width:min(100%,320px);height:auto}
		.wcp-description{font-family:Arial,sans-serif;line-height:1.35}
		.wcp-hero__content .wcp-add-to-cart{display:flex;align-items:center;gap:34px;width:min(100%,560px);margin-top:36px}
		.wcp-hero__content .wcp-add-to-cart:after{content:"";display:block;flex:1 1 auto;height:1px;min-width:120px;background:rgba(196,160,82,.65)}
		.wcp-decor--lines,.wcp-decor--circle{display:none!important}
		.wcp-arch-frame{border:0!important;border-radius:0!important;padding:0!important;background:transparent!important}
		.wcp-arch-frame img{display:block;width:100%;height:auto;object-fit:cover}
		.wcp-circle-frame{width:min(100%,220px);aspect-ratio:1;border-radius:50%;overflow:hidden}
		@media (max-width:960px){.wcp-hero__grid,.wcp-notes__grid,.wcp-bottom__grid{grid-template-columns:1fr}}
	</style>
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
