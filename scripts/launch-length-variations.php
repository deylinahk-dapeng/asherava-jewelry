<?php
/**
 * Add launch length variations to rope chain products.
 *
 * Run from the WordPress root:
 * wp eval-file wp-content/themes/asherava-jaxxon/scripts/launch-length-variations.php
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Product_Variable' ) || ! class_exists( 'WC_Product_Variation' ) ) {
	echo "WooCommerce is not available.\n";
	return;
}

$length_options = array(
	'18 inch',
	'20 inch',
	'22 inch',
	'24 inch',
	'26 inch',
	'28 inch',
);

$attribute_slug = 'length';
$attribute_name = 'Length';
$taxonomy       = wc_attribute_taxonomy_name( $attribute_slug );

$target_slugs = array(
	'3mm-rope-sterling-silver-chain',
	'3mm-rope-chain-sterling-silver',
	'3mm-rope-chain-sterling-silver-diamond-cut',
	'1-8mm-rope-chain-sterling-silver',
	'4-5mm-rope-chain-sterling-silver',
	'5-5mm-rope-chain-sterling-silver',
	'4mm-rope-chain-sterling-silver',
);

$fallback_prices = array(
	'3mm-rope-sterling-silver-chain'             => '149',
	'3mm-rope-chain-sterling-silver'             => '79',
	'3mm-rope-chain-sterling-silver-diamond-cut' => '149',
	'1-8mm-rope-chain-sterling-silver'           => '59',
	'4-5mm-rope-chain-sterling-silver'           => '109',
	'5-5mm-rope-chain-sterling-silver'           => '139',
	'4mm-rope-chain-sterling-silver'             => '99',
);

if ( ! taxonomy_exists( $taxonomy ) ) {
	$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_slug );

	if ( ! $attribute_id && function_exists( 'wc_create_attribute' ) ) {
		$attribute_id = wc_create_attribute(
			array(
				'name'         => $attribute_name,
				'slug'         => $attribute_slug,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);

		delete_transient( 'wc_attribute_taxonomies' );
	}

	if ( ! taxonomy_exists( $taxonomy ) ) {
		register_taxonomy(
			$taxonomy,
			array( 'product' ),
			array(
				'hierarchical' => false,
				'label'        => $attribute_name,
				'query_var'    => true,
				'rewrite'      => false,
				'show_ui'      => false,
			)
		);
	}
}

$length_terms = array();
foreach ( $length_options as $index => $length ) {
	$term = term_exists( $length, $taxonomy );

	if ( ! $term ) {
		$term = wp_insert_term(
			$length,
			$taxonomy,
			array(
				'slug' => sanitize_title( $length ),
			)
		);
	}

	if ( is_wp_error( $term ) ) {
		echo "Skipped length term: {$length} ({$term->get_error_message()})\n";
		continue;
	}

	$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
	wp_update_term(
		$term_id,
		$taxonomy,
		array(
			'menu_order' => $index,
		)
	);

	$length_terms[ $length ] = get_term( $term_id, $taxonomy );
}

$allowed_term_slugs = array_map(
	static function ( $term ) {
		return $term instanceof WP_Term ? $term->slug : '';
	},
	$length_terms
);
$allowed_term_slugs = array_values( array_filter( $allowed_term_slugs ) );

/**
 * Build a launch variation SKU.
 *
 * @param string $base_sku Base product SKU.
 * @param string $length   Length option label.
 * @param int    $product_id Product ID fallback.
 * @return string
 */
if ( ! function_exists( 'asherava_launch_length_sku' ) ) {
	function asherava_launch_length_sku( $base_sku, $length, $product_id ) {
		$number = preg_replace( '/\D+/', '', $length );
		$base   = $base_sku ? $base_sku : 'ASH-P' . (int) $product_id;

		return $base . '-' . $number . 'IN';
	}
}

/**
 * Remove old custom-length and duplicate variations.
 *
 * @param WC_Product_Variable $product Product object.
 * @param string              $taxonomy Length taxonomy.
 * @param string[]            $allowed_term_slugs Allowed pa_length term slugs.
 * @return int Deleted variation count.
 */
if ( ! function_exists( 'asherava_cleanup_length_variations' ) ) {
	function asherava_cleanup_length_variations( $product, $taxonomy, $allowed_term_slugs ) {
		$seen    = array();
		$deleted = 0;

		foreach ( $product->get_children() as $child_id ) {
			$variation = wc_get_product( $child_id );
			if ( ! $variation instanceof WC_Product_Variation ) {
				continue;
			}

			$attributes = $variation->get_attributes();
			$term_slug  = isset( $attributes[ $taxonomy ] ) ? (string) $attributes[ $taxonomy ] : '';

			if ( ! $term_slug || ! in_array( $term_slug, $allowed_term_slugs, true ) || isset( $seen[ $term_slug ] ) ) {
				wp_delete_post( $child_id, true );
				++$deleted;
				continue;
			}

			$seen[ $term_slug ] = (int) $child_id;
		}

		return $deleted;
	}
}

/**
 * Find an existing variation for a Length value.
 *
 * @param WC_Product_Variable $product Product object.
 * @param string              $taxonomy Attribute taxonomy.
 * @param string              $term_slug Attribute term slug.
 * @param string              $length  Length option label.
 * @return int
 */
if ( ! function_exists( 'asherava_find_length_variation_id' ) ) {
	function asherava_find_length_variation_id( $product, $taxonomy, $term_slug, $length ) {
		foreach ( $product->get_children() as $child_id ) {
			$variation = wc_get_product( $child_id );
			if ( ! $variation instanceof WC_Product_Variation ) {
				continue;
			}

			$attributes = $variation->get_attributes();
			if ( isset( $attributes[ $taxonomy ] ) && $term_slug === $attributes[ $taxonomy ] ) {
				return (int) $child_id;
			}

			if ( isset( $attributes['length'] ) && ( $length === $attributes['length'] || $term_slug === sanitize_title( $attributes['length'] ) ) ) {
				return (int) $child_id;
			}
		}

		return 0;
	}
}

foreach ( $target_slugs as $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'product' );

	if ( ! $post ) {
		echo "Skipped missing product: {$slug}\n";
		continue;
	}

	$base_product = wc_get_product( $post->ID );
	if ( ! $base_product ) {
		echo "Skipped invalid product: {$slug}\n";
		continue;
	}

	$base_name = $base_product->get_name();
	$base_sku  = $base_product->get_sku();
	$price     = $base_product->get_regular_price();

	if ( '' === $price ) {
		$price = $base_product->get_price();
	}

	if ( '' === $price && isset( $fallback_prices[ $slug ] ) ) {
		$price = $fallback_prices[ $slug ];
	}

	wp_set_object_terms( $post->ID, 'variable', 'product_type' );

	$product = new WC_Product_Variable( $post->ID );
	$product->set_name( $base_name );
	$product->set_slug( $slug );

	$attribute = new WC_Product_Attribute();
	$attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_slug ) );
	$attribute->set_name( $taxonomy );
	$attribute->set_options(
		array_map(
			static function ( $term ) {
				return (int) $term->term_id;
			},
			$length_terms
		)
	);
	$attribute->set_position( 0 );
	$attribute->set_visible( true );
	$attribute->set_variation( true );

	$attributes = $product->get_attributes();
	unset( $attributes['length'] );
	$attributes[ $taxonomy ] = $attribute;

	$product->set_attributes( $attributes );
	$product->set_default_attributes(
		array(
			$taxonomy => isset( $length_terms['20 inch'] ) ? $length_terms['20 inch']->slug : '20-inch',
		)
	);
	$product->save();
	wp_set_object_terms(
		$post->ID,
		array_map(
			static function ( $term ) {
				return (int) $term->term_id;
			},
			$length_terms
		),
		$taxonomy,
		false
	);

	$deleted_old_variations = asherava_cleanup_length_variations( $product, $taxonomy, $allowed_term_slugs );
	$product                = new WC_Product_Variable( $post->ID );

	foreach ( $length_options as $length ) {
		if ( empty( $length_terms[ $length ] ) || is_wp_error( $length_terms[ $length ] ) ) {
			continue;
		}

		$term         = $length_terms[ $length ];
		$variation_id = asherava_find_length_variation_id( $product, $taxonomy, $term->slug, $length );
		$variation    = $variation_id ? wc_get_product( $variation_id ) : new WC_Product_Variation();

		if ( ! $variation instanceof WC_Product_Variation ) {
			$variation = new WC_Product_Variation();
		}

		$variation->set_parent_id( $post->ID );
		$variation->set_status( 'publish' );
		$variation->set_attributes(
			array(
				$taxonomy => $term->slug,
			)
		);

		if ( '' !== $price ) {
			$variation->set_regular_price( $price );
		}

		$variation->set_manage_stock( false );
		$variation->set_stock_status( 'instock' );

		try {
			$variation->set_sku( asherava_launch_length_sku( $base_sku, $length, $post->ID ) );
		} catch ( Exception $exception ) {
			// Keep existing SKU if a merchant already created one manually.
		}

		$variation->save();
	}

	WC_Product_Variable::sync( $post->ID );
	wc_delete_product_transients( $post->ID );
	clean_post_cache( $post->ID );

	$product = wc_get_product( $post->ID );
	echo sprintf(
		"Updated length variations: %s | type=%s | variations=%d | cleaned=%d\n",
		$slug,
		$product ? $product->get_type() : 'unknown',
		$product instanceof WC_Product_Variable ? count( $product->get_children() ) : 0,
		$deleted_old_variations
	);
}

echo "Asherava launch length variations complete.\n";
