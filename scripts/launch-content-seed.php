<?php
/**
 * Seed launch-ready draft products, guide posts, and policy pages.
 *
 * Run on server:
 * wp eval-file wp-content/themes/asherava-jaxxon/scripts/launch-content-seed.php
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function asherava_seed_upsert_page( $slug, $title, $content, $status = 'publish', $template = 'page-policy.php' ) {
	$existing = get_page_by_path( $slug, OBJECT, 'page' );

	$postarr = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_status'  => $status,
		'post_type'    => 'page',
	);

	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		$post_id       = wp_update_post( $postarr, true );
	} else {
		$post_id = wp_insert_post( $postarr, true );
	}

	if ( ! is_wp_error( $post_id ) && $template ) {
		update_post_meta( $post_id, '_wp_page_template', $template );
	}

	return $post_id;
}

function asherava_seed_upsert_post( $slug, $title, $content, $excerpt = '', $status = 'draft' ) {
	$existing = get_page_by_path( $slug, OBJECT, 'post' );

	$postarr = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => $excerpt,
		'post_status'  => $status,
		'post_type'    => 'post',
	);

	if ( $existing ) {
		$postarr['ID'] = $existing->ID;
		return wp_update_post( $postarr, true );
	}

	return wp_insert_post( $postarr, true );
}

function asherava_seed_upsert_product( $slug, $title, $short_description, $description, $price = '', $regular_price = '' ) {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return new WP_Error( 'woocommerce_missing', 'WooCommerce is not active.' );
	}

	$existing = get_page_by_path( $slug, OBJECT, 'product' );
	$product  = $existing ? wc_get_product( $existing->ID ) : new WC_Product_Simple();

	if ( ! $product ) {
		$product = new WC_Product_Simple();
	}

	$product->set_name( $title );
	$product->set_slug( $slug );
	$product->set_status( 'draft' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_short_description( $short_description );
	$product->set_description( $description );
	$product->set_regular_price( $regular_price ? $regular_price : $price );
	$product->set_price( $price ? $price : $regular_price );
	$product->set_manage_stock( false );
	$product->set_stock_status( 'instock' );

	$product_id = $product->save();

	update_post_meta( $product_id, '_asherava_material_label', 'Sterling Silver 925' );
	update_post_meta( $product_id, '_asherava_material_detail', 'Crafted from 925 sterling silver. Confirm product-specific origin, width, clasp, and approximate gram weight before publishing.' );
	update_post_meta( $product_id, '_asherava_care_detail', 'Store dry, avoid harsh chemicals, and polish gently with a soft silver cloth when needed.' );

	wp_set_object_terms( $product_id, 'simple', 'product_type' );

	return $product_id;
}

$policy_pages = array(
	array(
		'about-us',
		'About Asherava',
		'<div class="av-policy"><h2>Built for the long run</h2><p>Asherava is named after my two children, Asher and Ava. This brand was built with a simple idea: create lasting sterling silver jewelry, price it fairly, and grow it patiently.</p><p>With years in the jewelry business, direct access to Italian sterling silver chains, and finishing work through trusted partners in Panyu, Guangzhou, we keep the process close and the pricing honest.</p><p>Instead of relying on marketplaces with heavy fees, we sell directly and keep our margins modest, so customers can get better jewelry at a fairer price.</p></div>',
	),
	array(
		'contact-us',
		'Contact Us',
		'<div class="av-policy"><p>Need help with sizing, shipping, or an order? Contact our support team and we will reply as soon as possible during business hours.</p><p>Email: <a href="mailto:support@asherava.com">support@asherava.com</a></p><p>Business hours: Monday-Friday, 9:00-17:00.</p></div>',
	),
	array(
		'shipping-policy',
		'Shipping Policy',
		'<div class="av-policy"><p>We prepare orders with care and provide tracking when available. Final delivery times depend on destination, carrier service, and customs processing.</p><h2>Processing</h2><p>Most ready-to-ship items are prepared within 1-3 business days. Custom or backordered items may require more time.</p><h2>International orders</h2><p>Import duties, taxes, or customs fees may be charged by the destination country and are the customer\'s responsibility unless stated otherwise at checkout.</p></div>',
	),
	array(
		'refund-policy',
		'Refund Policy',
		'<div class="av-policy"><p>We want every customer to feel confident about their purchase. If your item arrives damaged or incorrect, contact us promptly with your order number and photos.</p><h2>Returns</h2><p>Eligible unused items may be returned within 30 days of delivery. Items must be unworn, undamaged, and returned with original packaging.</p><h2>Final sale</h2><p>Custom, engraved, or heavily discounted final-sale items may not be eligible for return.</p></div>',
	),
	array(
		'faq',
		'FAQ',
		'<div class="av-policy"><h2>Is your jewelry sterling silver?</h2><p>Our silver chain listings focus on 925 sterling silver. Always review the material details on each product page before purchasing.</p><h2>Will sterling silver tarnish?</h2><p>Sterling silver can naturally oxidize over time. Keep it dry, avoid harsh chemicals, and polish with a soft silver cloth.</p><h2>How do I choose a chain length?</h2><p>Most men choose 20-24 inches for daily wear. Shorter lengths sit higher, while longer lengths create a more relaxed look.</p></div>',
	),
	array(
		'privacy-policy',
		'Privacy Policy',
		'<div class="av-policy"><p>This page explains how Asherava collects and uses customer information for orders, support, marketing, analytics, and site security. Update this draft with your final legal/privacy details before advertising at scale.</p></div>',
	),
	array(
		'terms-of-service',
		'Terms of Service',
		'<div class="av-policy"><p>By using this website and placing an order, customers agree to Asherava\'s store policies, product information, pricing, payment, shipping, return, and support terms. Update this draft with your final legal terms before advertising at scale.</p></div>',
	),
);

$guide_posts = array(
	array(
		'mens-chain-length-guide',
		"Men's Chain Length Guide",
		'<h2>How to choose a chain length</h2><p>Chain length changes how a piece sits on the body. For most men, 20 inches sits near the collarbone, 22 inches sits slightly lower, and 24 inches creates a relaxed everyday look.</p><h2>Everyday recommendation</h2><p>If you are unsure, start with 22 inches for a balanced fit. Pair wider chains with slightly longer lengths for a cleaner drape.</p><p><a href="/shop/">Shop sterling silver chains</a></p>',
		'Choose the right men\'s chain length for daily wear.',
	),
	array(
		'rope-chain-vs-cuban-chain',
		'Rope Chain vs Cuban Chain',
		'<h2>Rope chains</h2><p>Rope chains have a twisted texture that catches light from many angles. They are a strong choice for customers who want shine without a flat link profile.</p><h2>Cuban chains</h2><p>Cuban chains have broader interlocking links and a heavier visual presence. Choose Cuban if you want a bolder statement chain.</p><p><a href="/shop/">Compare chain styles</a></p>',
		'Compare rope chains and Cuban chains before choosing a style.',
	),
	array(
		'what-is-925-sterling-silver',
		'What Is 925 Sterling Silver?',
		'<h2>925 sterling silver explained</h2><p>925 sterling silver means the metal contains 92.5% silver, usually blended with other metals for strength. It is widely used in jewelry because it balances beauty, durability, and value.</p><h2>Care basics</h2><p>Keep sterling silver dry when possible and polish gently when oxidation appears.</p><p><a href="/shop/">Shop 925 sterling silver chains</a></p>',
		'Learn what 925 sterling silver means and how to care for it.',
	),
	array(
		'how-to-clean-sterling-silver-chains',
		'How to Clean Sterling Silver Chains',
		'<h2>Simple care routine</h2><p>Use a soft silver polishing cloth for regular maintenance. Avoid bleach, chlorine, and harsh cleaners.</p><h2>Storage</h2><p>Store your chain dry in a pouch or box. Keeping silver away from moisture slows oxidation.</p><p><a href="/shop/">Explore sterling silver chains</a></p>',
		'Care instructions for keeping sterling silver chains clean.',
	),
);

function asherava_seed_rope_description( $width ) {
	return '<h2>' . esc_html( $width ) . ' Rope Chain</h2><p>A focused 925 sterling silver rope chain draft for the launch lineup. Confirm final product images, exact gram weights, clasp detail, available lengths, and inventory before publishing.</p><h2>Details to verify before publishing</h2><ul><li>925 sterling silver</li><li>' . esc_html( $width ) . ' width</li><li>Available lengths</li><li>Approximate gram weights by length</li><li>Clasp type</li><li>Origin and finishing details</li></ul><h2>FAQ</h2><h3>Can I wear it daily?</h3><p>Yes, sterling silver rope chains are suitable for daily wear with proper care.</p><h3>Will it tarnish?</h3><p>Sterling silver can oxidize naturally. Store dry and polish gently when needed.</p>';
}

$draft_products = array(
	array(
		'3mm-rope-chain-sterling-silver',
		'3mm Rope Chain',
		'Italian-crafted 925 sterling silver rope chain for everyday wear.',
		asherava_seed_rope_description( '3mm' ),
		'79',
		'79',
	),
	array(
		'1-8mm-rope-chain-sterling-silver',
		'1.8mm Rope Chain',
		'Lightweight 925 sterling silver rope chain for a subtle everyday look.',
		asherava_seed_rope_description( '1.8mm' ),
		'59',
		'59',
	),
	array(
		'4-5mm-rope-chain-sterling-silver',
		'4.5mm Rope Chain',
		'Balanced 925 sterling silver rope chain with more presence.',
		asherava_seed_rope_description( '4.5mm' ),
		'109',
		'109',
	),
	array(
		'5-5mm-rope-chain-sterling-silver',
		'5.5mm Rope Chain',
		'Bolder 925 sterling silver rope chain for a heavier everyday look.',
		asherava_seed_rope_description( '5.5mm' ),
		'139',
		'139',
	),
	array(
		'4mm-rope-chain-sterling-silver',
		'4mm Rope Chain',
		'Classic 925 sterling silver rope chain with balanced everyday weight.',
		asherava_seed_rope_description( '4mm' ),
		'99',
		'99',
	),
);

foreach ( $policy_pages as $page ) {
	asherava_seed_upsert_page( $page[0], $page[1], $page[2] );
}

foreach ( $guide_posts as $post ) {
	asherava_seed_upsert_post( $post[0], $post[1], $post[2], $post[3], 'draft' );
}

foreach ( $draft_products as $product ) {
	asherava_seed_upsert_product( $product[0], $product[1], $product[2], $product[3], $product[4], $product[5] );
}

update_option( 'asherava_store_email', 'support@asherava.com', false );
update_option( 'asherava_show_blog_nav', false, false );
update_option( 'asherava_show_accessory_nav', false, false );

echo "Asherava launch content seed complete.\n";
