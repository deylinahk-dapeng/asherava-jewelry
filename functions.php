<?php
/**
 * Asherava Jaxxon child theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ASHERAVA_JAXXON_VERSION', '1.8.0' );

/**
 * Force storefront language to English (avoid zh_CN core/plugin strings on the frontend).
 */
add_filter( 'locale', 'asherava_force_english_locale' );
add_filter( 'determine_locale', 'asherava_force_english_locale' );
function asherava_force_english_locale( $locale ) {
	if ( is_admin() ) {
		return $locale;
	}

	return 'en_US';
}

require_once get_stylesheet_directory() . '/inc/catalog-categories.php';
require_once get_stylesheet_directory() . '/inc/woocommerce-pdp.php';
require_once get_stylesheet_directory() . '/inc/blog.php';
require_once get_stylesheet_directory() . '/inc/contact-support.php';

/**
 * Keep cart, checkout, and account usable while WooCommerce Coming Soon is on.
 */
add_filter( 'woocommerce_coming_soon_exclude', 'asherava_checkout_excluded_from_coming_soon' );
function asherava_checkout_excluded_from_coming_soon( $exclude ) {
	if ( $exclude ) {
		return true;
	}

	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return true;
	}

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$uri = wp_unslash( $_SERVER['REQUEST_URI'] );
		foreach ( array( '/cart', '/checkout', '/my-account' ) as $path ) {
			if ( false !== strpos( $uri, $path ) ) {
				return true;
			}
		}
	}

	return $exclude;
}

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
 * Whether the public navigation should show blog links.
 */
function asherava_should_show_blog_nav() {
	return (bool) apply_filters( 'asherava_show_blog_nav', get_option( 'asherava_show_blog_nav', false ) );
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
		'asherava-jaxxon-inter',
		'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'asherava-jaxxon',
		get_stylesheet_directory_uri() . '/assets/css/jaxxon.css',
		array( 'generate-style', 'asherava-jaxxon-inter' ),
		ASHERAVA_JAXXON_VERSION
	);

	wp_enqueue_style(
		'asherava-typography',
		get_stylesheet_directory_uri() . '/assets/css/asherava-typography.css',
		array( 'asherava-jaxxon' ),
		ASHERAVA_JAXXON_VERSION
	);

	wp_enqueue_script(
		'asherava-jaxxon',
		get_stylesheet_directory_uri() . '/assets/js/jaxxon.js',
		array(),
		ASHERAVA_JAXXON_VERSION,
		true
	);

	wp_script_add_data( 'asherava-jaxxon', 'strategy', 'defer' );
}

add_filter( 'wp_resource_hints', 'asherava_jaxxon_resource_hints', 10, 2 );
/**
 * Warm up third-party font connections before the stylesheet is requested.
 *
 * @param array<int, string|array<string, string>> $urls          Resource hint URLs.
 * @param string                                   $relation_type Hint relation type.
 */
function asherava_jaxxon_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array(
		'href'        => 'https://fonts.gstatic.com',
		'crossorigin' => 'anonymous',
	);

	return $urls;
}

add_filter( 'script_loader_tag', 'asherava_jaxxon_defer_script', 10, 3 );
/**
 * Back-compat defer attribute for WordPress versions that ignore script strategy.
 *
 * @param string $tag    Script HTML.
 * @param string $handle Script handle.
 * @param string $src    Script source.
 */
function asherava_jaxxon_defer_script( $tag, $handle, $src ) {
	if ( 'asherava-jaxxon' !== $handle || false !== strpos( $tag, ' defer' ) ) {
		return $tag;
	}

	return str_replace( '<script ', '<script defer ', $tag );
}

/**
 * Homepage hero asset URL.
 */
function asherava_jaxxon_get_hero_image_url() {
	return get_stylesheet_directory_uri() . '/assets/images/products/3mm-rope-chain-black.png';
}

add_action( 'wp_head', 'asherava_jaxxon_preload_home_hero_image', 2 );
/**
 * Preload the CSS background hero image because browsers discover it late.
 */
function asherava_jaxxon_preload_home_hero_image() {
	if ( ! is_front_page() ) {
		return;
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n",
		esc_url( asherava_jaxxon_get_hero_image_url() )
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

	if ( is_front_page() ) {
		$classes[] = 'av-home-fullbleed';
	}

	$posts_page_id = (int) get_option( 'page_for_posts' );

	if (
		is_home()
		|| is_singular( 'post' )
		|| is_category()
		|| is_page( 'blog' )
		|| ( $posts_page_id && is_page( $posts_page_id ) )
		|| ( is_archive() && ! is_post_type_archive( 'product' ) )
	) {
		$classes[] = 'av-blog-page';
	}

	if ( is_home() || is_page( 'blog' ) || ( $posts_page_id && is_page( $posts_page_id ) ) ) {
		$classes[] = 'av-blog-index';
	}

	if ( is_singular( 'post' ) ) {
		$classes[] = 'av-blog-single';
	}

	if ( is_page() ) {
		$policy_slugs = array(
			'contact-us',
			'contact',
			'shipping-policy',
			'refund-policy',
			'faq',
			'privacy-policy',
			'terms-of-service',
		);
		$post = get_queried_object();
		if ( $post instanceof WP_Post && in_array( $post->post_name, $policy_slugs, true ) ) {
			$classes[] = 'av-policy-layout';
		}
	}

	return $classes;
}

add_action( 'generate_after_footer_content', 'asherava_jaxxon_footer_links', 6 );
/**
 * Compact launch footer links when footer widgets are not fully configured.
 */
function asherava_jaxxon_footer_links() {
	$links = array(
		'contact-us'       => __( 'Contact Us', 'asherava-jaxxon' ),
		'shipping-policy'  => __( 'Shipping Policy', 'asherava-jaxxon' ),
		'refund-policy'    => __( 'Refund Policy', 'asherava-jaxxon' ),
		'faq'              => __( 'FAQ', 'asherava-jaxxon' ),
		'privacy-policy'   => __( 'Privacy Policy', 'asherava-jaxxon' ),
		'terms-of-service' => __( 'Terms of Service', 'asherava-jaxxon' ),
	);

	echo '<nav class="av-footer-links" aria-label="' . esc_attr__( 'Customer service links', 'asherava-jaxxon' ) . '">';
	foreach ( $links as $slug => $label ) {
		$url = asherava_get_page_link( $slug );
		if ( ! $url ) {
			$url = home_url( '/' . $slug . '/' );
		}

		printf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $url ),
			esc_html( $label )
		);
	}
	echo '</nav>';
}

add_action( 'generate_after_footer_content', 'asherava_jaxxon_footer_contact', 8 );
/**
 * Footer contact line on policy and shop pages.
 */
function asherava_jaxxon_footer_contact() {
	$email = get_option( 'asherava_store_email', '' );
	$phone = get_option( 'asherava_store_phone', '' );

	if ( ! $email && ! $phone ) {
		return;
	}

	echo '<div class="av-footer-contact">';
	if ( $email ) {
		printf(
			'<a href="mailto:%1$s">%2$s</a>',
			esc_attr( $email ),
			esc_html( $email )
		);
	}
	if ( $email && $phone ) {
		echo '<span class="av-footer-contact__sep" aria-hidden="true"> · </span>';
	}
	if ( $phone ) {
		$tel = preg_replace( '/[^\d+]/', '', $phone );
		printf(
			'<a href="tel:%1$s">%2$s</a>',
			esc_attr( $tel ),
			esc_html( $phone )
		);
	}
	echo '</div>';
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
	if ( is_admin() || is_front_page() ) {
		return;
	}

	asherava_render_announcement_bar( 'top' );
}

add_filter( 'generate_site_title_output', 'asherava_jaxxon_site_title' );
/**
 * Header wordmark logo (HTML text — avoids SVG-as-img font/letter-spacing bugs).
 *
 * @param string $variant `auto`, `dark`, or `white`.
 */
function asherava_get_logo_html( $variant = 'auto' ) {
	if ( 'white' === $variant ) {
		$use_white = true;
	} elseif ( 'dark' === $variant ) {
		$use_white = false;
	} else {
		$use_white = is_front_page();
	}

	$home_url = esc_url( home_url( '/' ) );
	$label    = esc_attr( get_bloginfo( 'name' ) );
	$classes  = 'av-logo-link av-logo-wordmark' . ( $use_white ? ' av-logo-wordmark--white' : '' );

	return sprintf(
		'<a href="%1$s" class="%2$s" rel="home" aria-label="%3$s"><span class="av-logo-wordmark__text" aria-hidden="true">AsherAva</span></a>',
		$home_url,
		esc_attr( $classes ),
		$label
	);
}

function asherava_jaxxon_site_title( $output ) {
	return asherava_get_logo_html();
}

add_filter( 'generate_site_logo_output', 'asherava_jaxxon_site_title' );

add_filter( 'generate_show_site_title', '__return_false' );

add_filter( 'generate_show_title', 'asherava_jaxxon_hide_blog_gp_title' );
/**
 * Hide GeneratePress default page title on blog templates.
 *
 * @param bool $show Whether to show the title.
 */
function asherava_jaxxon_hide_blog_gp_title( $show ) {
	$posts_page_id = (int) get_option( 'page_for_posts' );

	if (
		is_home()
		|| is_singular( 'post' )
		|| is_category()
		|| is_page( 'blog' )
		|| ( $posts_page_id && is_page( $posts_page_id ) )
	) {
		return false;
	}

	return $show;
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
	asherava_sync_blog_categories();
}

add_action( 'after_setup_theme', 'asherava_jaxxon_sync_blog_categories', 35 );
function asherava_jaxxon_sync_blog_categories() {
	if ( get_option( 'asherava_blog_categories_synced' ) ) {
		return;
	}

	asherava_sync_blog_categories();
	update_option( 'asherava_blog_categories_synced', true, false );
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
