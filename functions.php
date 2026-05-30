<?php
/**
 * Asherava Jaxxon child theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ASHERAVA_JAXXON_VERSION', '1.1.8' );

require_once get_stylesheet_directory() . '/inc/catalog-categories.php';

/**
 * Permalink for a published page by slug, or empty string.
 *
 * @param string $slug Page path slug.
 */
function asherava_get_page_link( $slug ) {
	$page = get_page_by_path( $slug );
	if ( ! $page || 'publish' !== $page->post_status ) {
		return '';
	}

	return get_permalink( $page );
}

/**
 * Resolve a menu URL from page slug(s) or a fallback path.
 *
 * @param array<int, string> $slugs        Candidate page slugs (first match wins).
 * @param string             $fallback_path Path under home URL, e.g. /about-us/.
 */
function asherava_resolve_menu_url( array $slugs, $fallback_path = '' ) {
	foreach ( $slugs as $slug ) {
		$url = asherava_get_page_link( $slug );
		if ( $url ) {
			return $url;
		}
	}

	if ( $fallback_path ) {
		return home_url( $fallback_path );
	}

	return '';
}

/**
 * Blog / posts archive URL for drawer menu.
 */
function asherava_get_blog_url() {
	$posts_page_id = (int) get_option( 'page_for_posts' );

	if ( $posts_page_id ) {
		return get_permalink( $posts_page_id );
	}

	foreach ( array( 'blog', 'blogs' ) as $slug ) {
		$url = asherava_get_page_link( $slug );
		if ( $url ) {
			return $url;
		}
	}

	return home_url( '/blog/' );
}

/**
 * LZJ-style mobile drawer links after Shop (always shown).
 *
 * @return array<int, array{label: string, url: string, icon?: string}>
 */
function asherava_get_drawer_primary_links() {
	return array(
		array(
			'label' => __( 'About Us', 'asherava-jaxxon' ),
			'url'   => asherava_resolve_menu_url( array( 'about-us', 'about' ), '/about-us/' ),
		),
		array(
			'label' => __( 'Contact Us', 'asherava-jaxxon' ),
			'url'   => asherava_resolve_menu_url( array( 'contact-us', 'contact' ), '/contact-us/' ),
		),
		array(
			'label' => __( 'Blogs', 'asherava-jaxxon' ),
			'url'   => asherava_get_blog_url(),
		),
		array(
			'label' => __( 'Silver Chain Guides & Resources', 'asherava-jaxxon' ),
			'url'   => asherava_resolve_menu_url(
				array( 'silver-chain-guides', 'silver-chain-guides-resources', 'chain-guides', 'guides' ),
				'/silver-chain-guides/'
			),
		),
	);
}

/**
 * @return array<int, array{label: string, url: string, icon?: string}>
 */
function asherava_get_drawer_secondary_links() {
	$links = array();

	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$account_url = wc_get_page_permalink( 'myaccount' );
		if ( $account_url ) {
			$links[] = array(
				'label' => is_user_logged_in() ? __( 'My Account', 'asherava-jaxxon' ) : __( 'Login', 'asherava-jaxxon' ),
				'url'   => $account_url,
				'icon'  => 'login',
			);
		}
	}

	$policy_pages = array(
		'shipping-policy'  => __( 'Shipping Policy', 'asherava-jaxxon' ),
		'refund-policy'    => __( 'Refund Policy', 'asherava-jaxxon' ),
		'faq'              => __( 'FAQ', 'asherava-jaxxon' ),
		'privacy-policy'   => __( 'Privacy Policy', 'asherava-jaxxon' ),
		'terms-of-service' => __( 'Terms of Service', 'asherava-jaxxon' ),
	);

	foreach ( $policy_pages as $slug => $label ) {
		$url = asherava_get_page_link( $slug );
		if ( $url ) {
			$links[] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}

	return $links;
}

/**
 * Whether a menu URL matches the current request.
 *
 * @param string $url Menu link URL.
 */
function asherava_is_current_menu_link( $url ) {
	if ( ! $url ) {
		return false;
	}

	$current = home_url( add_query_arg( array() ) );

	if ( is_front_page() ) {
		$current = home_url( '/' );
	}

	return untrailingslashit( $url ) === untrailingslashit( $current );
}

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

/**
 * Render announcement strip.
 *
 * @param string $placement `top` (before header) or `hero` (over homepage hero).
 */
function asherava_render_announcement_bar( $placement = 'top' ) {
	get_template_part( 'template-parts/announcement', 'bar', array( 'placement' => $placement ) );
}

add_action( 'generate_before_header', 'asherava_jaxxon_announcement_bar', 5 );
function asherava_jaxxon_announcement_bar() {
	if ( is_admin() ) {
		return;
	}

	asherava_render_announcement_bar( 'top' );
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
