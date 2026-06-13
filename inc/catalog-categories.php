<?php
/**
 * Product catalog categories.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Full category catalog.
 *
 * @return array<int, array<string, mixed>>
 */
function asherava_get_product_catalog() {
	$image = static function ( $id ) {
		return 'https://images.unsplash.com/photo-' . $id . '?auto=format&fit=crop&w=900&q=80';
	};

	return array(
		array( 'title' => 'Rope Chains', 'slug' => 'rope-chains', 'group' => 'chains', 'featured' => false, 'nav' => true, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => '3mm Rope Chains', 'slug' => '3mm-rope-chains', 'group' => 'chains', 'featured' => true, 'nav' => true, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => '1.8mm Rope Chains', 'slug' => '1-8mm-rope-chains', 'group' => 'chains', 'featured' => true, 'nav' => true, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => '4.5mm Rope Chains', 'slug' => '4-5mm-rope-chains', 'group' => 'chains', 'featured' => true, 'nav' => true, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => '5.5mm Rope Chains', 'slug' => '5-5mm-rope-chains', 'group' => 'chains', 'featured' => true, 'nav' => true, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => '4mm Rope Chains', 'slug' => '4mm-rope-chains', 'group' => 'chains', 'featured' => true, 'nav' => true, 'image' => $image( '1515562141207-7a88fb7ce338' ) ),
		array( 'title' => 'Cuban Chains', 'slug' => 'cuban-chains', 'group' => 'chains', 'featured' => false, 'nav' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Franco Chains', 'slug' => 'franco-chains', 'group' => 'chains', 'featured' => false, 'nav' => false, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => 'Figaro Chains', 'slug' => 'figaro-chains', 'group' => 'chains', 'featured' => false, 'nav' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => 'Curb Chains', 'slug' => 'curb-chains', 'group' => 'chains', 'featured' => false, 'nav' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Bracelets', 'slug' => 'bracelets', 'group' => 'accessories', 'featured' => false, 'nav' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Pendants', 'slug' => 'pendants', 'group' => 'accessories', 'featured' => false, 'nav' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
	);
}

/**
 * Featured categories for homepage (LZJ-style top collections).
 *
 * @return array<int, array<string, mixed>>
 */
function asherava_get_featured_catalog() {
	$featured = array_filter(
		asherava_get_product_catalog(),
		static function ( $item ) {
			return ! empty( $item['featured'] );
		}
	);

	return array_values( $featured );
}

/**
 * Chain categories only (Shop mega menu).
 *
 * @return array<int, array<string, mixed>>
 */
function asherava_get_chain_catalog() {
	return array_values(
		array_filter(
			asherava_get_product_catalog(),
			static function ( $item ) {
				return ( $item['group'] ?? '' ) === 'chains' && false !== ( $item['nav'] ?? true );
			}
		)
	);
}

/**
 * Category archive URL.
 *
 * @param string $slug Category slug.
 */
function asherava_get_category_url( $slug ) {
	if ( function_exists( 'get_term_link' ) ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$link = get_term_link( $term );
			if ( ! is_wp_error( $link ) ) {
				return $link;
			}
		}
	}

	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

	return add_query_arg( 'product_cat', $slug, $shop );
}

/**
 * Create WooCommerce product categories from catalog.
 */
function asherava_sync_product_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	foreach ( asherava_get_product_catalog() as $category ) {
		$existing = term_exists( $category['slug'], 'product_cat' );
		if ( $existing ) {
			continue;
		}

		wp_insert_term(
			$category['title'],
			'product_cat',
			array(
				'slug' => $category['slug'],
			)
		);
	}
}
