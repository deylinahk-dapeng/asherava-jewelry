<?php
/**
 * Product catalog categories (Luke Zion Jewelry structure).
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
		array( 'title' => 'Rope Chains', 'slug' => 'rope-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => 'Byzantine Chains', 'slug' => 'byzantine-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Figaro Chains', 'slug' => 'figaro-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => 'Ice Link Chains', 'slug' => 'ice-link-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1515562141207-7a88fb7ce338' ) ),
		array( 'title' => 'Franco Chains', 'slug' => 'franco-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => 'Forzentina Chains', 'slug' => 'forzentina-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1602751584552-8b4b7d0e8c3a' ) ),
		array( 'title' => 'Dollar Chains', 'slug' => 'dollar-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Cuban Chains', 'slug' => 'cuban-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Snake Chains', 'slug' => 'snake-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => 'Barrel Chains', 'slug' => 'barrel-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => 'Rock Chains', 'slug' => 'rock-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => 'Box Chains', 'slug' => 'box-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1515562141207-7a88fb7ce338' ) ),
		array( 'title' => 'Figaro Mariner Hybrid Chains', 'slug' => 'figaro-mariner-hybrid-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => 'Cuban Figaro Hybrid Chains', 'slug' => 'cuban-figaro-hybrid-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Valentino Chains', 'slug' => 'valentino-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => 'Herringbone Chains', 'slug' => 'herringbone-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => 'Figarope Chains', 'slug' => 'figarope-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => 'Curb Chains', 'slug' => 'curb-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Master Link Chains', 'slug' => 'master-link-chains', 'group' => 'chains', 'featured' => true, 'image' => $image( '1515562141207-7a88fb7ce338' ) ),
		array( 'title' => 'Square Greek Box Chains', 'slug' => 'square-greek-box-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
		array( 'title' => 'Mariner Chains', 'slug' => 'mariner-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1605100804763-247f67b3557e' ) ),
		array( 'title' => 'Puff Link Chains', 'slug' => 'puff-link-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1617032216428-9e23d1bb0ca5' ) ),
		array( 'title' => 'Moon Cut Chains', 'slug' => 'moon-cut-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Kids Chains', 'slug' => 'kids-chains', 'group' => 'chains', 'featured' => false, 'image' => $image( '1515562141207-7a88fb7ce338' ) ),
		array( 'title' => 'Bracelets', 'slug' => 'bracelets', 'group' => 'accessories', 'featured' => false, 'image' => $image( '1611591437281-460bfbe1220a' ) ),
		array( 'title' => 'Pendants', 'slug' => 'pendants', 'group' => 'accessories', 'featured' => false, 'image' => $image( '1599643478518-a784e5dc4c8f' ) ),
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
				return ( $item['group'] ?? '' ) === 'chains';
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
