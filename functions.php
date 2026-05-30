<?php
/**
 * Asherava Jaxxon child theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ASHERAVA_JAXXON_VERSION', '1.1.5' );

require_once get_stylesheet_directory() . '/inc/catalog-categories.php';

add_action( 'wp_enqueue_scripts', 'asherava_jaxxon_enqueue_assets', 20 );
function asherava_jaxxon_enqueue_assets() {
	wp_enqueue_style(
		'asherava-jaxxon-google-fonts',
		'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Source+Sans+Pro:ital,wght@0,300;0,400;0,600;0,700;1,300&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'asherava-jaxxon',
		get_stylesheet_directory_uri() . '/assets/css/jaxxon.css',
		array( 'generate-style' ),
		ASHERAVA_JAXXON_VERSION
	);

	wp_enqueue_script(
		'asherava-jaxxon',
		get_stylesheet_directory_uri() . '/assets/js/jaxxon.js',
		array(),
		ASHERAVA_JAXXON_VERSION,
		true
	);
}

add_action( 'after_setup_theme', 'asherava_jaxxon_setup' );
function asherava_jaxxon_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}

add_filter( 'body_class', 'asherava_jaxxon_body_class' );
function asherava_jaxxon_body_class( $classes ) {
	$classes[] = 'asherava-jaxxon';
	return $classes;
}

add_action( 'generate_before_header', 'asherava_jaxxon_announcement_bar', 5 );
function asherava_jaxxon_announcement_bar() {
	if ( is_admin() ) {
		return;
	}
	?>
	<div class="av-announcement" aria-label="Promotions">
		<p class="av-announcement__static">Luxury Men's Chain Jewelry &bull; Free US Shipping</p>
		<div class="av-announcement__track">
			<span>UP TO 47% OFF &bull; FREE SHIPPING ON ALL US ORDERS &bull; 30-DAY RETURNS</span>
			<span>UP TO 47% OFF &bull; FREE SHIPPING ON ALL US ORDERS &bull; 30-DAY RETURNS</span>
			<span>UP TO 47% OFF &bull; FREE SHIPPING ON ALL US ORDERS &bull; 30-DAY RETURNS</span>
			<span>UP TO 47% OFF &bull; FREE SHIPPING ON ALL US ORDERS &bull; 30-DAY RETURNS</span>
		</div>
	</div>
	<?php
}

add_filter( 'generate_site_title_output', 'asherava_jaxxon_site_title' );
function asherava_get_logo_html() {
	$logo_url = get_stylesheet_directory_uri() . '/assets/images/asherava-logo-white.png';
	$home_url = esc_url( home_url( '/' ) );

	return sprintf(
		'<a href="%1$s" class="av-logo-link" rel="home" aria-label="%2$s"><img src="%3$s" alt="%2$s" class="av-logo-img" width="220" height="32" decoding="async" /></a>',
		$home_url,
		esc_attr( get_bloginfo( 'name' ) ),
		esc_url( $logo_url )
	);
}

function asherava_jaxxon_site_title( $output ) {
	return asherava_get_logo_html();
}

add_filter( 'woocommerce_product_add_to_cart_text', 'asherava_jaxxon_add_to_cart_text' );
function asherava_jaxxon_add_to_cart_text() {
	return __( 'Add to Cart', 'asherava-jaxxon' );
}

add_filter( 'woocommerce_sale_flash', 'asherava_jaxxon_sale_flash', 10, 3 );
function asherava_jaxxon_sale_flash( $html, $post, $product ) {
	return '<span class="av-badge av-badge--sale">' . esc_html__( 'Sale', 'asherava-jaxxon' ) . '</span>';
}

add_action( 'after_setup_theme', 'asherava_jaxxon_sync_categories', 30 );
function asherava_jaxxon_sync_categories() {
	if ( get_option( 'asherava_categories_synced' ) ) {
		return;
	}

	asherava_sync_product_categories();
	update_option( 'asherava_categories_synced', true, false );
}

add_action( 'after_switch_theme', 'asherava_jaxxon_reset_category_sync' );
function asherava_jaxxon_reset_category_sync() {
	delete_option( 'asherava_categories_synced' );
	asherava_sync_product_categories();
	update_option( 'asherava_categories_synced', true, false );
}

add_action( 'wp', 'asherava_jaxxon_replace_default_nav' );
function asherava_jaxxon_replace_default_nav() {
	if ( is_admin() ) {
		return;
	}

	remove_action( 'generate_navigation_position', 'generate_add_navigation_after_header', 5 );
	remove_action( 'generate_navigation_position', 'generate_add_navigation_before_header', 5 );
	remove_action( 'generate_navigation_position', 'generate_add_navigation_float_right', 5 );
	remove_action( 'generate_navigation_position', 'generate_add_navigation_below_header', 5 );
}

add_action( 'generate_after_header_content', 'asherava_jaxxon_render_catalog_nav', 12 );
function asherava_jaxxon_render_catalog_nav() {
	if ( is_admin() ) {
		return;
	}

	get_template_part( 'template-parts/catalog', 'nav' );
}

add_action( 'woocommerce_before_main_content', 'asherava_jaxxon_render_category_rail', 12 );
function asherava_jaxxon_render_category_rail() {
	if ( ! is_shop() && ! is_product_taxonomy() ) {
		return;
	}

	static $rendered = false;
	if ( $rendered ) {
		return;
	}
	$rendered = true;

	get_template_part( 'template-parts/category', 'rail' );
}
