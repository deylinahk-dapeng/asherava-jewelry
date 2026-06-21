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
add_option( 'asherava_show_product_reviews', false, '', false );

asherava_sync_product_categories();

$pages = array(
	'about-us'         => array(
		'title'   => 'About Asherava',
		'content' => '<h2>Built for the long run</h2><p>Asherava is named after Asher and Ava. The brand is built with the intention of creating something honest, durable, and worth leaving behind.</p><p>We focus first on Italian-made 925 sterling silver rope chains, supported by finishing and quality-control relationships in Panyu, Guangzhou.</p><p>By selling directly instead of building around marketplace fees, we aim to keep pricing fair and invest in a business designed to last.</p>',
	),
	'contact-us'       => array(
		'title'   => 'Contact Us',
		'content' => '<h2>Customer support</h2><p>Email us at <a href="mailto:support@asherava.com">support@asherava.com</a>. We usually respond within 1-2 business days.</p><p>For order questions, include your order number and the email address used at checkout so we can help you faster.</p>',
	),
	'shipping-policy'  => array(
		'title'   => 'Shipping Policy',
		'content' => '<p><strong>Last updated: June 21, 2026</strong></p><p>Asherava currently ships to eligible addresses in the United States and Canada.</p><h2>Order processing</h2><p>In-stock orders are normally prepared within 1-3 business days after payment is confirmed. Weekends, public holidays, product launches, and address checks may add processing time.</p><h2>Shipping cost and delivery</h2><p>Available shipping services, charges, and estimated delivery windows are shown at checkout before payment. Delivery estimates begin after an order has been dispatched and are not guarantees.</p><h2>Tracking</h2><p>When tracking is available, a shipping confirmation is sent to the email address used at checkout. Tracking can take up to 48 hours to show its first carrier scan.</p><h2>Address changes</h2><p>Contact <a href="mailto:support@asherava.com">support@asherava.com</a> as soon as possible if an address needs to be corrected. We cannot guarantee changes after an order enters fulfillment.</p><h2>Taxes, duties, and refused deliveries</h2><p>Taxes or import charges collected at checkout will be shown before payment. Any additional duties, brokerage fees, or local charges not collected by Asherava are the recipient\'s responsibility. Refused or unclaimed parcels may have return shipping and carrier fees deducted from the refund.</p><h2>Delivery problems</h2><p>If an order arrives damaged, contains the wrong item, or appears lost, contact us promptly with the order number and relevant photos. We will review the carrier information and work toward an appropriate resolution.</p>',
	),
	'refund-policy'    => array(
		'title'   => 'Refund Policy',
		'content' => '<p><strong>Last updated: June 21, 2026</strong></p><p>Eligible items may be returned within 30 days of delivery.</p><h2>Return eligibility</h2><p>Items must be unworn, unused, undamaged, and returned with their original packaging and any included materials. Items showing wear, alteration, damage after delivery, or missing components may not qualify for a refund.</p><h2>Start a return</h2><p>Email <a href="mailto:support@asherava.com">support@asherava.com</a> with your order number and reason for return before sending anything back. We will provide return instructions and the correct return address. Returns sent without authorization may be delayed or refused.</p><h2>Return shipping</h2><p>Customers are responsible for return shipping unless the item was incorrect, damaged on arrival, or confirmed defective. Use a trackable service and retain the shipping receipt. Original delivery charges are not refundable unless required by law or the return is caused by our error.</p><h2>Refund timing</h2><p>Approved refunds are issued to the original payment method after the returned item is inspected. Allow 5-10 business days after approval for the payment provider to post the refund.</p><h2>Exchanges and order changes</h2><p>For a different length, contact us before returning the item. The fastest option may be to return the original item and place a new order. Cancellation requests are handled when possible, but an order cannot be cancelled after it has shipped.</p>',
	),
	'faq'              => array(
		'title'   => 'FAQ',
		'content' => '<h2>Are Asherava rope chains sterling silver?</h2><p>Our launch collection focuses on 925 sterling silver rope chains. Product-specific material and finish information is listed on each product page.</p><h2>Which widths are available?</h2><p>The launch lineup includes 1.8mm, 3mm, 4mm, 4.5mm, and 5.5mm rope chains.</p><h2>Which lengths are available?</h2><p>Core launch lengths are 18, 20, 22, 24, 26, and 28 inches. Availability may vary by width.</p><h2>How do I choose a chain length?</h2><p>Measure a chain you already own or use a soft measuring tape around the neckline where you want the chain to sit. Wider chains can feel shorter, so consider sizing up if you prefer a looser fit.</p><h2>Will sterling silver tarnish?</h2><p>Sterling silver can naturally tarnish when exposed to moisture, air, cosmetics, or chemicals. Tarnish is not the same as permanent damage and can usually be removed with a silver polishing cloth.</p><h2>How should I care for my chain?</h2><p>Keep it dry when possible, avoid direct contact with perfume and household chemicals, and store it separately in a soft pouch. Clean gently with a silver polishing cloth.</p><h2>When will my order ship?</h2><p>In-stock orders are normally prepared within 1-3 business days. Shipping options and delivery estimates are shown at checkout.</p><h2>Can I return a chain?</h2><p>Eligible unworn items may be returned within 30 days of delivery. Review the Refund Policy and contact support before sending a return.</p><h2>How can I contact Asherava?</h2><p>Email <a href="mailto:support@asherava.com">support@asherava.com</a>. We usually respond within 1-2 business days.</p>',
	),
	'privacy-policy'   => array(
		'title'   => 'Privacy Policy',
		'content' => '<p><strong>Last updated: June 21, 2026</strong></p><p>This Privacy Policy explains how Asherava collects, uses, and shares information when you visit asherava.com, place an order, contact us, or join our email list.</p><h2>Information we collect</h2><p>We may collect contact and order information such as your name, email address, billing and shipping address, phone number, purchased items, and customer-service messages. Payment details are processed by our payment providers and are not stored in full by Asherava.</p><p>We also receive technical information such as IP address, browser type, device information, cookie identifiers, pages viewed, and interactions with the site.</p><h2>How we use information</h2><p>We use information to process and deliver orders, provide customer service, prevent fraud, maintain and improve the store, comply with legal obligations, and send marketing messages when you have consented or where permitted by law.</p><h2>Service providers</h2><p>Information may be shared with providers that help operate the store, including WooCommerce and WordPress hosting, payment processors, shipping carriers, analytics services, fraud-prevention services, and Omnisend for email marketing. These providers receive information only as needed to perform their services.</p><h2>Cookies and analytics</h2><p>The site uses cookies and similar technologies for essential store functions, preferences, performance measurement, and marketing. Browser settings can limit cookies, but some checkout and account features may not work correctly without essential cookies.</p><h2>Marketing choices</h2><p>You can unsubscribe from marketing emails using the link in any marketing message. Transactional messages about orders and accounts may still be sent.</p><h2>Data retention and security</h2><p>We retain information for as long as reasonably needed to provide services, maintain business and tax records, resolve disputes, and meet legal obligations. We use reasonable safeguards, but no online system can guarantee absolute security.</p><h2>Your choices</h2><p>Depending on where you live, you may have rights to request access, correction, or deletion of certain personal information. Contact us to make a request. We may need to verify your identity before responding.</p><h2>Contact</h2><p>Privacy questions can be sent to <a href="mailto:support@asherava.com">support@asherava.com</a>.</p>',
	),
	'terms-of-service' => array(
		'title'   => 'Terms of Service',
		'content' => '<p><strong>Last updated: June 21, 2026</strong></p><p>These Terms govern use of asherava.com and purchases from Asherava. By using the site or placing an order, you agree to these Terms and the policies linked from the site.</p><h2>Store information</h2><p>Product descriptions, images, availability, and prices may be updated without notice. We work to present information accurately, but screen settings and photography can affect how color, scale, and finish appear.</p><h2>Orders and payment</h2><p>An order is an offer to purchase. We may cancel or limit an order because of inventory errors, payment or fraud concerns, pricing mistakes, shipping restrictions, or suspected resale or misuse. Payment must be authorized before fulfillment.</p><h2>Pricing and promotions</h2><p>Prices and the currency charged are shown at checkout. Promotions may have eligibility, usage, expiration, and product restrictions. Unless stated otherwise, offers cannot be combined.</p><h2>Shipping and returns</h2><p>Shipping and delivery are governed by the Shipping Policy. Returns and refunds are governed by the Refund Policy. Delivery estimates are not guarantees, and carrier delays may occur after an order leaves our control.</p><h2>Acceptable use</h2><p>You may not use the site to break the law, interfere with security or operation, collect data without permission, submit false information, or infringe the rights of Asherava or others.</p><h2>Intellectual property</h2><p>Site content, branding, product photography, graphics, and written material are owned by or licensed to Asherava and may not be copied or used commercially without permission.</p><h2>Disclaimer and liability</h2><p>To the extent permitted by law, the site and its content are provided without warranties beyond those expressly stated. Asherava is not responsible for indirect or consequential losses arising from use of the site. Nothing in these Terms limits rights that cannot legally be excluded.</p><h2>Changes</h2><p>We may update these Terms by posting a revised version on this page. Continued use after an update means the revised Terms apply.</p><h2>Contact</h2><p>Questions about these Terms can be sent to <a href="mailto:support@asherava.com">support@asherava.com</a>.</p>',
	),
);

$legacy_page_content = array(
	'about-us'         => '<h2>Built for the long run</h2><p>Asherava is named after Asher and Ava. The brand is built with the intention of creating something honest, durable, and worth leaving behind.</p><p>We focus first on 925 sterling silver rope chains, supported by Italian chain sourcing and finishing relationships in Panyu, Guangzhou.</p>',
	'contact-us'       => '<p>Email: support@asherava.com</p><p>We usually respond within 1-2 business days.</p>',
	'shipping-policy'  => '<p>Asherava launches with shipping coverage for the United States and Canada. Final delivery times and carrier details should be confirmed before public launch.</p>',
	'refund-policy'    => '<p>We offer a 30-day return window on eligible unworn items. Final return address and condition rules should be completed before launch.</p>',
	'faq'              => '<h2>Is it sterling silver?</h2><p>Our launch focus is 925 sterling silver rope chains.</p><h2>Which widths are available?</h2><p>The first lineup is 3mm, 1.8mm, 4.5mm, 5.5mm, and 4mm rope chains.</p>',
	'privacy-policy'   => '<p>This page should be reviewed and finalized with the store privacy settings before launch.</p>',
	'terms-of-service' => '<p>This page should be reviewed and finalized before launch.</p>',
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
		$current_content = trim( (string) $existing->post_content );
		$legacy_content  = isset( $legacy_page_content[ $slug ] ) ? trim( $legacy_page_content[ $slug ] ) : '';

		if ( $current_content && $current_content !== $legacy_content ) {
			echo "Preserved customized page: {$slug}\n";
			continue;
		}

		$postarr['ID'] = $existing->ID;
		wp_update_post( $postarr );
		echo "Updated launch page: {$slug}\n";
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

		if ( $existing ) {
			$product_id = $product->get_id();
			echo "Preserved existing product data: {$item['slug']}\n";
		} else {
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
			$product->set_short_description( '925 sterling silver rope chain. Complete the final length, weight, clasp, and product photography before publishing.' );
			$product->set_description( 'Draft product page for the Asherava launch rope chain lineup. Add final product images, measurements, weight table, clasp details, packaging, and shipping notes before publishing.' );
			$product_id = $product->save();
		}
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
