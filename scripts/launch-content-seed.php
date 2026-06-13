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
		array( 'slug' => '3mm-rope-chain-sterling-silver', 'legacy_slugs' => array( '3mm-rope-chain' ), 'name' => '3mm Rope Chain', 'sku' => 'ASH-3MM-ROPE-SS', 'price' => '79', 'cat' => '3mm-rope-chains' ),
		array( 'slug' => '1-8mm-rope-chain-sterling-silver', 'legacy_slugs' => array( '1-8mm-rope-chain' ), 'name' => '1.8mm Rope Chain', 'sku' => 'ASH-18MM-ROPE-SS', 'price' => '59', 'cat' => '1-8mm-rope-chains' ),
		array( 'slug' => '4-5mm-rope-chain-sterling-silver', 'legacy_slugs' => array( '4-5mm-rope-chain' ), 'name' => '4.5mm Rope Chain', 'sku' => 'ASH-45MM-ROPE-SS', 'price' => '109', 'cat' => '4-5mm-rope-chains' ),
		array( 'slug' => '5-5mm-rope-chain-sterling-silver', 'legacy_slugs' => array( '5-5mm-rope-chain' ), 'name' => '5.5mm Rope Chain', 'sku' => 'ASH-55MM-ROPE-SS', 'price' => '139', 'cat' => '5-5mm-rope-chains' ),
		array( 'slug' => '4mm-rope-chain-sterling-silver', 'legacy_slugs' => array( '4mm-rope-chain' ), 'name' => '4mm Rope Chain', 'sku' => 'ASH-4MM-ROPE-SS', 'price' => '99', 'cat' => '4mm-rope-chains' ),
	);

	$legacy_draft_slugs = array(
		'5mm-rope-chain',
		'5mm-rope-chain-sterling-silver',
		'8mm-cuban-link-chain',
		'8mm-cuban-link-chain-sterling-silver',
	);

	foreach ( $legacy_draft_slugs as $legacy_slug ) {
		$legacy = get_page_by_path( $legacy_slug, OBJECT, 'product' );
		if ( $legacy && 'publish' !== get_post_status( $legacy->ID ) ) {
			wp_trash_post( $legacy->ID );
		}
	}

	$legacy_draft_titles = array(
		'5mm Rope Chain',
		'8mm Cuban Link Chain',
	);

	foreach ( $legacy_draft_titles as $legacy_title ) {
		$legacy_posts = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'draft', 'pending', 'private' ),
				'title'          => $legacy_title,
				'posts_per_page' => 20,
				'fields'         => 'ids',
			)
		);

		foreach ( $legacy_posts as $legacy_id ) {
			wp_trash_post( (int) $legacy_id );
		}
	}

	$find_product = static function ( $item ) {
		$existing = get_page_by_path( $item['slug'], OBJECT, 'product' );
		if ( $existing ) {
			return $existing;
		}

		if ( ! empty( $item['legacy_slugs'] ) ) {
			foreach ( $item['legacy_slugs'] as $legacy_slug ) {
				$legacy = get_page_by_path( $legacy_slug, OBJECT, 'product' );
				if ( $legacy ) {
					return $legacy;
				}
			}
		}

		$matches = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => array( 'draft', 'pending', 'private' ),
				'title'          => $item['name'],
				'posts_per_page' => 1,
			)
		);

		return $matches ? $matches[0] : null;
	};

	foreach ( $products as $item ) {
		$existing = $find_product( $item );

		$product  = $existing ? wc_get_product( $existing->ID ) : new WC_Product_Simple();

		if ( ! $product ) {
			continue;
		}

		$product->set_name( $item['name'] );
		$product->set_slug( $item['slug'] );
		$product->set_status( 'draft' );
		$product->set_catalog_visibility( 'visible' );
		try {
			$product->set_sku( $item['sku'] );
		} catch ( Exception $exception ) {
			// Keep the seed idempotent if a merchant already reused the SKU.
		}
		$product->set_regular_price( $item['price'] );
		$product->set_short_description( '925 sterling silver rope chain. Final length, weight, clasp, and product photos should be completed before publishing.' );
		$product->set_description( 'Draft product page for the Asherava launch rope chain lineup. Add final product images, measurements, weight table, clasp details, packaging, and shipping notes before publishing.' );

		$product_id = $product->save();
		$term       = get_term_by( 'slug', $item['cat'], 'product_cat' );

		if ( ! $term || is_wp_error( $term ) ) {
			$catalog_item = null;
			foreach ( asherava_get_product_catalog() as $category ) {
				if ( $item['cat'] === $category['slug'] ) {
					$catalog_item = $category;
					break;
				}
			}

			if ( $catalog_item ) {
				$inserted = wp_insert_term(
					$catalog_item['title'],
					'product_cat',
					array(
						'slug' => $catalog_item['slug'],
					)
				);
				if ( ! is_wp_error( $inserted ) ) {
					$term = get_term_by( 'slug', $item['cat'], 'product_cat' );
				}
			}
		}

		if ( $term && ! is_wp_error( $term ) ) {
			wp_set_object_terms( $product_id, array( (int) $term->term_id ), 'product_cat', false );
		}

		$root = get_term_by( 'slug', 'rope-chains', 'product_cat' );
		if ( ( ! $root || is_wp_error( $root ) ) ) {
			wp_insert_term( 'Rope Chains', 'product_cat', array( 'slug' => 'rope-chains' ) );
			$root = get_term_by( 'slug', 'rope-chains', 'product_cat' );
		}

		if ( $root && ! is_wp_error( $root ) ) {
			wp_set_object_terms( $product_id, array( (int) $root->term_id ), 'product_cat', true );
		}
	}
}

echo "Asherava launch content seed complete.\n";
