<?php
/**
 * Seed launch-safe pages, categories, options, and draft rope chain products.
 *
 * Run from the WordPress root:
 * wp eval-file wp-content/themes/asherava-jaxxon/scripts/launch-content-seed.php
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/catalog-categories.php';

update_option( 'asherava_show_blog_nav', false, false );
update_option( 'asherava_show_accessory_nav', false, false );
update_option( 'asherava_store_email', 'support@asherava.com', false );

asherava_sync_product_categories();

$pages = array(
	'about-us'         => array(
		'title'   => 'About Asherava',
		'content' => '<h2>Built for the long run</h2><p>Asherava is named after Asher and Ava. The brand is built with the intention of creating something honest, durable, and worth leaving behind.</p><p>We focus first on 925 sterling silver rope chains, supported by Italian chain sourcing and finishing relationships in Panyu, Guangzhou.</p>',
	),
	'contact-us'       => array(
		'title'   => 'Contact Us',
		'content' => '<p>Email: support@asherava.com</p><p>We usually respond within 1-2 business days.</p>',
	),
	'shipping-policy'  => array(
		'title'   => 'Shipping Policy',
		'content' => '<p>Asherava launches with shipping coverage for the United States and Canada. Final delivery times and carrier details should be confirmed before public launch.</p>',
	),
	'refund-policy'    => array(
		'title'   => 'Refund Policy',
		'content' => '<p>We offer a 30-day return window on eligible unworn items. Final return address and condition rules should be completed before launch.</p>',
	),
	'faq'              => array(
		'title'   => 'FAQ',
		'content' => '<h2>Is it sterling silver?</h2><p>Our launch focus is 925 sterling silver rope chains.</p><h2>Which widths are available?</h2><p>The first lineup is 3mm, 1.8mm, 4.5mm, 5.5mm, and 4mm rope chains.</p>',
	),
	'privacy-policy'   => array(
		'title'   => 'Privacy Policy',
		'content' => '<p>This page should be reviewed and finalized with the store privacy settings before launch.</p>',
	),
	'terms-of-service' => array(
		'title'   => 'Terms of Service',
		'content' => '<p>This page should be reviewed and finalized before launch.</p>',
	),
);

foreach ( $pages as $slug => $page ) {
	$existing = get_page_by_path( $slug );
	$postarr  = array(
		'post_title'   => $page['title'],
		'post_name'    => $slug,
		'post_content' => $page['content'],
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);

	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		wp_update_post( $postarr );
		continue;
	}

	wp_insert_post( $postarr );
}

$guide_posts = array(
	'how-to-choose-a-sterling-silver-rope-chain' => 'How to Choose a Sterling Silver Rope Chain',
	'sterling-silver-chain-care-guide'           => 'Sterling Silver Chain Care Guide',
	'rope-chain-width-guide'                     => 'Rope Chain Width Guide',
);

foreach ( $guide_posts as $slug => $title ) {
	if ( get_page_by_path( $slug, OBJECT, 'post' ) ) {
		continue;
	}

	wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => '<p>Draft guide content for launch SEO. Complete before publishing.</p>',
			'post_status'  => 'draft',
			'post_type'    => 'post',
		)
	);
}

if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Product_Simple' ) ) {
	$products = array(
		array( 'slug' => '3mm-rope-chain-sterling-silver', 'name' => '3mm Rope Chain', 'price' => '79', 'cat' => '3mm-rope-chains' ),
		array( 'slug' => '1-8mm-rope-chain-sterling-silver', 'name' => '1.8mm Rope Chain', 'price' => '59', 'cat' => '1-8mm-rope-chains' ),
		array( 'slug' => '4-5mm-rope-chain-sterling-silver', 'name' => '4.5mm Rope Chain', 'price' => '109', 'cat' => '4-5mm-rope-chains' ),
		array( 'slug' => '5-5mm-rope-chain-sterling-silver', 'name' => '5.5mm Rope Chain', 'price' => '139', 'cat' => '5-5mm-rope-chains' ),
		array( 'slug' => '4mm-rope-chain-sterling-silver', 'name' => '4mm Rope Chain', 'price' => '99', 'cat' => '4mm-rope-chains' ),
	);

	foreach ( $products as $item ) {
		$existing = get_page_by_path( $item['slug'], OBJECT, 'product' );
		$product  = $existing ? wc_get_product( $existing->ID ) : new WC_Product_Simple();

		if ( ! $product ) {
			continue;
		}

		$product->set_name( $item['name'] );
		$product->set_slug( $item['slug'] );
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $item['price'] );
		$product->set_short_description( '925 sterling silver rope chain. Final length, weight, clasp, and product photos should be completed before publishing.' );
		$product->set_description( 'Draft product page for the Asherava launch rope chain lineup. Add final product images, measurements, weight table, clasp details, packaging, and shipping notes before publishing.' );

		$product_id = $product->save();
		$term       = get_term_by( 'slug', $item['cat'], 'product_cat' );

		if ( $term && ! is_wp_error( $term ) ) {
			wp_set_object_terms( $product_id, array( (int) $term->term_id ), 'product_cat', false );
		}

		$root = get_term_by( 'slug', 'rope-chains', 'product_cat' );
		if ( $root && ! is_wp_error( $root ) ) {
			wp_set_object_terms( $product_id, array( (int) $root->term_id ), 'product_cat', true );
		}
	}
}

echo "Asherava launch content seed complete.\n";
