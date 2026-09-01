<?php
/**
 * Configure Length and Finish variations for the launch 3mm rope chain.
 *
 * Run from the WordPress root:
 * wp eval-file wp-content/themes/asherava-jaxxon/scripts/update-3mm-rope-options.php
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

$product_slug = '3mm-rope-sterling-silver-chain';
$lengths      = array( '18 inch', '20 inch', '22 inch', '24 inch', '26 inch', '28 inch', '30 inch', '32 inch' );
$finishes     = array( 'Bare Silver', 'Plated' );

/**
 * Ensure a global product attribute and its taxonomy are available.
 *
 * @param string $slug Attribute slug.
 * @param string $name Attribute label.
 * @return string
 */
function asherava_3mm_ensure_attribute_taxonomy( $slug, $name ) {
	$taxonomy    = wc_attribute_taxonomy_name( $slug );
	$attribute_id = wc_attribute_taxonomy_id_by_name( $slug );

	if ( ! $attribute_id && function_exists( 'wc_create_attribute' ) ) {
		$attribute_id = wc_create_attribute(
			array(
				'name'         => $name,
				'slug'         => $slug,
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
				'label'        => $name,
				'query_var'    => true,
				'rewrite'      => false,
				'show_ui'      => false,
			)
		);
	}

	return $taxonomy;
}

/**
 * Ensure ordered terms exist for an attribute taxonomy.
 *
 * @param string   $taxonomy Taxonomy name.
 * @param string[] $labels Term labels.
 * @return WP_Term[]
 */
function asherava_3mm_ensure_terms( $taxonomy, $labels ) {
	$terms = array();

	foreach ( $labels as $index => $label ) {
		$term = term_exists( $label, $taxonomy );

		if ( ! $term ) {
			$term = wp_insert_term( $label, $taxonomy, array( 'slug' => sanitize_title( $label ) ) );
		}

		if ( is_wp_error( $term ) ) {
			throw new RuntimeException( $term->get_error_message() );
		}

		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		wp_update_term( $term_id, $taxonomy, array( 'menu_order' => $index ) );
		$terms[ $label ] = get_term( $term_id, $taxonomy );
	}

	return $terms;
}

/**
 * Build a taxonomy-backed variation attribute.
 *
 * @param string    $attribute_slug Attribute slug.
 * @param string    $taxonomy Taxonomy name.
 * @param WP_Term[] $terms Attribute terms.
 * @param int       $position Display order.
 * @return WC_Product_Attribute
 */
function asherava_3mm_build_attribute( $attribute_slug, $taxonomy, $terms, $position ) {
	$attribute = new WC_Product_Attribute();
	$attribute->set_id( wc_attribute_taxonomy_id_by_name( $attribute_slug ) );
	$attribute->set_name( $taxonomy );
	$attribute->set_options(
		array_map(
			static function ( $term ) {
				return (int) $term->term_id;
			},
			$terms
		)
	);
	$attribute->set_position( $position );
	$attribute->set_visible( true );
	$attribute->set_variation( true );

	return $attribute;
}

$post = get_page_by_path( $product_slug, OBJECT, 'product' );
if ( ! $post ) {
	echo "Missing product: {$product_slug}\n";
	return;
}

$existing_product = wc_get_product( $post->ID );
if ( ! $existing_product ) {
	echo "Invalid product: {$product_slug}\n";
	return;
}

$price = $existing_product->get_variation_price( 'min', false );
if ( '' === $price ) {
	$price = $existing_product->get_price();
}
if ( '' === $price ) {
	$price = '149';
}

try {
	$length_taxonomy = asherava_3mm_ensure_attribute_taxonomy( 'length', 'Length' );
	$finish_taxonomy = asherava_3mm_ensure_attribute_taxonomy( 'finish', 'Finish' );
	$length_terms    = asherava_3mm_ensure_terms( $length_taxonomy, $lengths );
	$finish_terms    = asherava_3mm_ensure_terms( $finish_taxonomy, $finishes );
} catch ( RuntimeException $exception ) {
	echo 'Attribute setup failed: ' . $exception->getMessage() . "\n";
	return;
}

wp_set_object_terms( $post->ID, wp_list_pluck( $length_terms, 'term_id' ), $length_taxonomy, false );
wp_set_object_terms( $post->ID, wp_list_pluck( $finish_terms, 'term_id' ), $finish_taxonomy, false );
wp_set_object_terms( $post->ID, 'variable', 'product_type' );

$product    = new WC_Product_Variable( $post->ID );
$attributes = $product->get_attributes();
unset( $attributes['size'], $attributes['pa_size'], $attributes['length'], $attributes['finish'] );
$attributes[ $length_taxonomy ] = asherava_3mm_build_attribute( 'length', $length_taxonomy, $length_terms, 0 );
$attributes[ $finish_taxonomy ] = asherava_3mm_build_attribute( 'finish', $finish_taxonomy, $finish_terms, 1 );
$product->set_attributes( $attributes );
$product->set_default_attributes(
	array(
		$length_taxonomy => $length_terms['20 inch']->slug,
		$finish_taxonomy => $finish_terms['Bare Silver']->slug,
	)
);
$product->save();

$existing_variations = array();
foreach ( $product->get_children() as $variation_id ) {
	$variation = wc_get_product( $variation_id );
	if ( ! $variation instanceof WC_Product_Variation ) {
		continue;
	}

	$variation_attributes = $variation->get_attributes();
	$length_slug          = isset( $variation_attributes[ $length_taxonomy ] ) ? $variation_attributes[ $length_taxonomy ] : '';
	$finish_slug          = isset( $variation_attributes[ $finish_taxonomy ] ) ? $variation_attributes[ $finish_taxonomy ] : '';

	if ( $length_slug && ! $finish_slug ) {
		$finish_slug = $finish_terms['Bare Silver']->slug;
	}

	if ( $length_slug && $finish_slug ) {
		$existing_variations[ $length_slug . '|' . $finish_slug ] = $variation_id;
	}
}

$created = 0;
$updated = 0;
foreach ( $lengths as $length ) {
	foreach ( $finishes as $finish ) {
		$length_slug = $length_terms[ $length ]->slug;
		$finish_slug = $finish_terms[ $finish ]->slug;
		$key         = $length_slug . '|' . $finish_slug;
		$variation   = isset( $existing_variations[ $key ] ) ? wc_get_product( $existing_variations[ $key ] ) : new WC_Product_Variation();
		$is_new      = ! $variation->get_id();

		$variation->set_parent_id( $post->ID );
		$variation->set_status( 'publish' );
		$variation->set_attributes(
			array(
				$length_taxonomy => $length_slug,
				$finish_taxonomy => $finish_slug,
			)
		);
		$variation->set_regular_price( $price );
		$variation->set_manage_stock( false );
		$variation->set_stock_status( 'instock' );

		$length_number = preg_replace( '/\D+/', '', $length );
		$finish_code   = 'Bare Silver' === $finish ? 'BARE' : 'PLATED';
		$sku           = $existing_product->get_sku() . '-' . $length_number . 'IN-' . $finish_code;
		try {
			$variation->set_sku( $sku );
		} catch ( Exception $exception ) {
			// Preserve a merchant-created SKU if the generated value is already used.
		}

		$variation->save();
		delete_post_meta( $variation->get_id(), 'attribute_pa_size' );

		if ( $is_new ) {
			++$created;
		} else {
			++$updated;
		}
	}
}

WC_Product_Variable::sync( $post->ID );
wc_delete_product_transients( $post->ID );
clean_post_cache( $post->ID );

$product = wc_get_product( $post->ID );
echo sprintf(
	"Updated 3mm options: variations=%d | created=%d | updated=%d | price=%s\n",
	$product instanceof WC_Product_Variable ? count( $product->get_children() ) : 0,
	$created,
	$updated,
	$price
);
