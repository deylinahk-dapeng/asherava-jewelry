<?php
/**
 * LZJ-style single product (PDP) layout.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product slugs that use LZJ short titles and coming-soon visibility.
 *
 * @return string[]
 */
function asherava_pdp_rope_slugs() {
	return array(
		'3mm-rope-sterling-silver-chain',
		'3mm-rope-chain-sterling-silver',
		'3mm-rope-chain-sterling-silver-diamond-cut',
		'1-8mm-rope-chain-sterling-silver',
		'4-5mm-rope-chain-sterling-silver',
		'5-5mm-rope-chain-sterling-silver',
		'4mm-rope-chain-sterling-silver',
	);
}

/**
 * Short buybox title by slug.
 *
 * @param string $slug Product slug.
 */
function asherava_pdp_short_name_for_slug( $slug ) {
	$map = array(
		'3mm-rope-sterling-silver-chain'             => __( '3mm Rope Chain', 'asherava-jaxxon' ),
		'3mm-rope-chain-sterling-silver'             => __( '3mm Rope Chain', 'asherava-jaxxon' ),
		'3mm-rope-chain-sterling-silver-diamond-cut' => __( '3mm Rope Chain', 'asherava-jaxxon' ),
		'1-8mm-rope-chain-sterling-silver'           => __( '1.8mm Rope Chain', 'asherava-jaxxon' ),
		'4-5mm-rope-chain-sterling-silver'           => __( '4.5mm Rope Chain', 'asherava-jaxxon' ),
		'5-5mm-rope-chain-sterling-silver'           => __( '5.5mm Rope Chain', 'asherava-jaxxon' ),
		'4mm-rope-chain-sterling-silver'             => __( '4mm Rope Chain', 'asherava-jaxxon' ),
	);

	return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/**
 * Split long SEO description: buybox copy vs FAQ/extra block below the grid.
 *
 * @param string $html Full product description HTML.
 * @return array{0: string, 1: string} [buybox_html, seo_extra_html]
 */
function asherava_pdp_split_description( $html ) {
	$html = trim( (string) $html );
	if ( '' === $html ) {
		return array( '', '' );
	}

	if ( preg_match( '/<h2[^>]*>\s*(?:FAQ|Frequently Asked)/i', $html, $m, PREG_OFFSET_CAPTURE ) ) {
		$pos = (int) $m[0][1];
		return array(
			trim( substr( $html, 0, $pos ) ),
			trim( substr( $html, $pos ) ),
		);
	}

	$plain_len = strlen( wp_strip_all_tags( $html ) );
	if ( $plain_len > 900 ) {
		if ( preg_match( '/^(.*?<\/ul>)/is', $html, $m ) ) {
			$buybox = trim( $m[1] );
			$extra  = trim( substr( $html, strlen( $m[1] ) ) );
			if ( $extra ) {
				return array( $buybox, $extra );
			}
		}
		if ( preg_match( '/^(.*?<\/p>\s*<p>.*?<\/p>)/is', $html, $m ) ) {
			$buybox = trim( $m[1] );
			$extra  = trim( substr( $html, strlen( $m[1] ) ) );
			if ( $extra ) {
				return array( $buybox, $extra );
			}
		}
	}

	return array( $html, '' );
}

/**
 * Whether customer reviews are approved for public display.
 */
function asherava_product_reviews_enabled() {
	return (bool) apply_filters(
		'asherava_show_product_reviews',
		get_option( 'asherava_show_product_reviews', false )
	);
}

add_action( 'wp', 'asherava_pdp_configure_review_visibility', 5 );
function asherava_pdp_configure_review_visibility() {
	if ( asherava_product_reviews_enabled() ) {
		return;
	}

	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
}

add_action( 'wp', 'asherava_pdp_setup_hooks' );
function asherava_pdp_setup_hooks() {
	if ( ! is_product() ) {
		return;
	}

	if ( ! asherava_product_reviews_enabled() ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_filter( 'woocommerce_structured_data_product', 'asherava_pdp_remove_unverified_rating_data', 20, 2 );
	}

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

	add_action( 'woocommerce_before_single_product', 'asherava_pdp_open_wrapper', 4 );
	add_action( 'woocommerce_before_single_product', 'asherava_pdp_render_breadcrumbs', 6 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_material_badge', 4 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_shipping_note', 11 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_post_atc_note', 31 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_render_description', 32 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_render_accordions', 40 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_open_below', 1 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_render_seo_description', 8 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_close_below', 99 );
	add_action( 'woocommerce_after_single_product', 'asherava_pdp_close_wrapper', 99 );

	add_filter( 'woocommerce_product_description_heading', 'asherava_pdp_description_heading' );
	add_filter( 'woocommerce_product_tabs', 'asherava_pdp_remove_tabs', 99 );
	add_filter( 'woocommerce_product_is_visible', 'asherava_pdp_visible_during_coming_soon', 10, 2 );
	add_filter( 'generate_show_breadcrumb', 'asherava_pdp_hide_theme_breadcrumb' );
	add_filter( 'woocommerce_product_get_name', 'asherava_pdp_display_name', 10, 2 );
	add_filter( 'the_title', 'asherava_pdp_the_title', 10, 2 );
	add_filter( 'woocommerce_variation_option_name', 'asherava_pdp_length_option_name', 20, 4 );
	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'asherava_pdp_variation_buttons', 20, 2 );
	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'asherava_pdp_hide_duplicate_size_dropdown', 100, 2 );
	add_filter( 'wp_get_attachment_image_attributes', 'asherava_pdp_product_image_alt', 20, 3 );
}

/**
 * Keep unverified ratings out of search markup until reviews are enabled.
 *
 * @param array        $markup  Product structured data.
 * @param WC_Product   $product Product object.
 * @return array
 */
function asherava_pdp_remove_unverified_rating_data( $markup, $product ) {
	unset( $markup['aggregateRating'], $markup['review'] );

	return $markup;
}

/**
 * Use the product name when a product image has no meaningful alternative text.
 *
 * @param array        $attr       Image attributes.
 * @param WP_Post      $attachment Attachment post.
 * @param string|array $size       Requested image size.
 * @return array
 */
function asherava_pdp_product_image_alt( $attr, $attachment, $size ) {
	if ( ! is_product() || ! $attachment instanceof WP_Post ) {
		return $attr;
	}

	$current_alt = isset( $attr['alt'] ) ? trim( (string) $attr['alt'] ) : '';
	$filename_alt = sanitize_title( $current_alt );
	$attachment_slug = sanitize_title( $attachment->post_name );

	if ( $current_alt && $filename_alt !== $attachment_slug ) {
		return $attr;
	}

	$product = wc_get_product( get_queried_object_id() );
	if ( $product ) {
		$attr['alt'] = sprintf(
			/* translators: %s: product name. */
			__( '%s product image', 'asherava-jaxxon' ),
			$product->get_name()
		);
	}

	return $attr;
}

function asherava_pdp_hide_theme_breadcrumb( $show ) {
	if ( is_product() ) {
		return false;
	}

	return $show;
}

/**
 * LZJ buybox uses short product title (e.g. "3mm Rope Chain").
 *
 * @param string     $name    Product name.
 * @param WC_Product $product Product object.
 */
function asherava_pdp_display_name( $name, $product ) {
	if ( is_admin() || wp_doing_ajax() || ! $product || ! is_product() ) {
		return $name;
	}

	$short = get_post_meta( $product->get_id(), '_asherava_display_name', true );
	if ( $short ) {
		return $short;
	}

	$mapped = asherava_pdp_short_name_for_slug( $product->get_slug() );
	if ( $mapped ) {
		return $mapped;
	}

	return $name;
}

/**
 * Short title in buybox (WC uses the_title(), not get_name()).
 *
 * @param string $title Post title.
 * @param int    $id    Post ID.
 */
function asherava_pdp_the_title( $title, $id = 0 ) {
	if ( is_admin() || ! is_product() || ! $id || 'product' !== get_post_type( $id ) ) {
		return $title;
	}

	if ( (int) get_queried_object_id() !== (int) $id ) {
		return $title;
	}

	$short = get_post_meta( $id, '_asherava_display_name', true );
	if ( $short ) {
		return $short;
	}

	$post = get_post( $id );
	if ( $post ) {
		$mapped = asherava_pdp_short_name_for_slug( $post->post_name );
		if ( $mapped ) {
			return $mapped;
		}
	}

	return $title;
}

/**
 * Allow rope PDPs to render for guests while WooCommerce Coming Soon is on.
 *
 * @param bool $visible Default visibility.
 * @param int  $id      Product ID.
 */
function asherava_pdp_visible_during_coming_soon( $visible, $id ) {
	if ( $visible || ! $id ) {
		return $visible;
	}

	$slug = get_post_field( 'post_name', $id );
	if ( $slug && in_array( $slug, asherava_pdp_rope_slugs(), true ) ) {
		return true;
	}

	return $visible;
}

add_filter( 'woocommerce_coming_soon_exclude', 'asherava_pdp_exclude_from_coming_soon' );
function asherava_pdp_exclude_from_coming_soon( $exclude ) {
	if ( $exclude ) {
		return true;
	}

	if ( is_singular( 'product' ) ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post && in_array( $post->post_name, asherava_pdp_rope_slugs(), true ) ) {
			return true;
		}
	}

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$uri = wp_unslash( $_SERVER['REQUEST_URI'] );
		foreach ( asherava_pdp_rope_slugs() as $slug ) {
			if ( false !== strpos( $uri, '/product/' . $slug ) ) {
				return true;
			}
		}
	}

	return $exclude;
}

function asherava_pdp_description_heading( $heading ) {
	return '';
}

function asherava_pdp_remove_tabs( $tabs ) {
	return array();
}

function asherava_pdp_open_wrapper() {
	echo '<div class="av-pdp">';
}

function asherava_pdp_render_breadcrumbs() {
	if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
		return;
	}

	echo '<div class="av-pdp__breadcrumbs av-container">';
	woocommerce_breadcrumb(
		array(
			'delimiter'   => '<span class="av-pdp__crumb-sep" aria-hidden="true">›</span>',
			'wrap_before' => '<nav class="av-pdp__crumb-trail" aria-label="' . esc_attr__( 'Breadcrumb', 'asherava-jaxxon' ) . '">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
		)
	);
	echo '</div>';
}

function asherava_pdp_open_below() {
	echo '<div class="av-pdp__below av-container">';
}

function asherava_pdp_close_below() {
	echo '</div>';
}

function asherava_pdp_close_wrapper() {
	echo '</div><!-- .av-pdp -->';
}

function asherava_pdp_material_badge() {
	$slug     = get_post_field( 'post_name', get_the_ID() );
	$material = in_array( $slug, asherava_pdp_rope_slugs(), true ) ? '' : get_post_meta( get_the_ID(), '_asherava_material_label', true );
	if ( ! $material ) {
		$material = __( 'Sterling silver 925', 'asherava-jaxxon' );
	}

	echo '<p class="av-pdp__material av-type-label">' . esc_html( $material ) . '</p>';
}

function asherava_pdp_shipping_note() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<p class="av-pdp__shipping-note">' . esc_html__( 'Free shipping in the US & Canada.', 'asherava-jaxxon' ) . '</p>';
}

function asherava_pdp_size_guide_url() {
	$url = asherava_resolve_menu_url(
		array( 'mens-rope-chain-size-guide', 'rope-chain-size-guide', 'size-guide' ),
		'/size-guide/'
	);

	return $url;
}

function asherava_pdp_post_atc_note() {
	echo '<p class="av-pdp__post-atc-note">' . esc_html__( '30-day returns. Most men wear 22″.', 'asherava-jaxxon' ) . '</p>';
}

add_filter( 'woocommerce_get_price_html', 'asherava_pdp_price_html', 20, 2 );
function asherava_pdp_price_html( $price, $product ) {
	if ( ! is_product() || ! $product ) {
		return $price;
	}

	if ( $product->is_type( 'variable' ) ) {
		$min = $product->get_variation_price( 'min', true );
		if ( $min ) {
			return '<span class="av-pdp__price-display">' . wc_price( $min ) . '</span>';
		}
	}

	return '<span class="av-pdp__price-display">' . $price . '</span>';
}

/**
 * Buybox description (LZJ: below Add to cart, inside right column).
 */
function asherava_pdp_rope_buybox_description_html( $slug ) {
	$three_mm_slugs = array(
		'3mm-rope-sterling-silver-chain',
		'3mm-rope-chain-sterling-silver',
		'3mm-rope-chain-sterling-silver-diamond-cut',
	);

	if ( ! in_array( $slug, $three_mm_slugs, true ) ) {
		return '';
	}

	$weights = array(
		'18″' => '16.5',
		'20″' => '18',
		'22″' => '19.5',
		'24″' => '22',
		'26″' => '24',
		'28″' => '26',
		'30″' => '27',
		'32″' => '29',
	);

	ob_start();
	?>
	<p class="av-pdp__description-intro"><?php esc_html_e( '3mm rope in 925 sterling silver. 22″ is the everyday length for most men.', 'asherava-jaxxon' ); ?></p>
	<h3 class="av-pdp__spec-heading"><?php esc_html_e( "Men's Rope Chain Size Guide", 'asherava-jaxxon' ); ?></h3>
	<ul class="av-pdp__spec-list">
		<?php foreach ( $weights as $length => $grams ) : ?>
			<li>
				<span class="av-pdp__spec-length"><?php echo esc_html( $length ); ?></span>
				<span class="av-pdp__spec-weight">
					<?php
					printf(
						/* translators: %s: approximate gram weight */
						esc_html__( 'weights approx. %s grams', 'asherava-jaxxon' ),
						esc_html( $grams )
					);
					?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
	<p class="av-pdp__spec-note"><?php esc_html_e( 'All chain weights are approximate. Final chain weight may vary.', 'asherava-jaxxon' ); ?></p>
	<p class="av-pdp__spec-note"><?php esc_html_e( 'Plated: rhodium over 925. Bare silver: unplated 925.', 'asherava-jaxxon' ); ?></p>
	<?php
	return ob_get_clean();
}

function asherava_pdp_render_description() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$body = asherava_pdp_rope_buybox_description_html( $product->get_slug() );
	if ( ! $body ) {
		$body = $product->get_short_description();
		if ( ! $body ) {
			list( $body, ) = asherava_pdp_split_description( $product->get_description() );
		}
	}

	$body = trim( (string) $body );
	if ( '' === $body ) {
		return;
	}

	echo '<section class="av-pdp__description av-pdp__description--buybox">';
	echo '<div class="av-pdp__description-body">' . wp_kses_post( $body ) . '</div>';
	echo '</section>';
}

/**
 * FAQ / extra SEO copy below the two-column grid.
 */
function asherava_pdp_render_seo_description() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$long  = $product->get_description();
	$short = $product->get_short_description();

	list( $buybox_from_long, $seo_extra ) = asherava_pdp_split_description( $long );

	$html = $seo_extra;
	if ( '' === $html && $short && $long && trim( wp_strip_all_tags( $long ) ) !== trim( wp_strip_all_tags( $short ) ) ) {
		$html = $long;
		if ( $buybox_from_long && false !== strpos( $html, $buybox_from_long ) ) {
			$html = trim( str_replace( $buybox_from_long, '', $html ) );
		}
	}

	$html = trim( (string) $html );
	if ( '' === $html ) {
		return;
	}

	echo '<section class="av-pdp__seo">';
	echo '<div class="av-pdp__seo-body">' . wp_kses_post( $html ) . '</div>';
	echo '</section>';
}

function asherava_pdp_render_trust_blocks() {
	get_template_part( 'template-parts/product/trust', 'blocks' );
}

function asherava_pdp_render_accordions() {
	get_template_part( 'template-parts/product/accordions' );
}

/**
 * Whether a product belongs to the launch rope chain set.
 *
 * @param WC_Product|null $product Product object.
 */
function asherava_pdp_is_rope_product( $product ) {
	return $product instanceof WC_Product && in_array( $product->get_slug(), asherava_pdp_rope_slugs(), true );
}

/**
 * Whether a variation attribute is chain length.
 *
 * @param string $attribute Raw attribute name.
 * @param string $label     Attribute label.
 */
function asherava_pdp_is_length_attribute( $attribute, $label ) {
	$slug = sanitize_title( $attribute );

	return false !== strpos( $slug, 'length' )
		|| false !== stripos( $label, 'length' );
}

/**
 * Whether a variation attribute is chain size.
 *
 * @param string $attribute Raw attribute name.
 * @param string $label     Attribute label.
 */
function asherava_pdp_is_size_attribute( $attribute, $label ) {
	$slug = sanitize_title( $attribute );

	return false !== strpos( $slug, 'size' )
		|| false !== stripos( $label, 'size' );
}

/**
 * Whether the product already has a Length variation attribute.
 *
 * @param WC_Product|null $product Product object.
 */
function asherava_pdp_product_has_length_attribute( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return false;
	}

	if ( $product->is_type( 'variable' ) && method_exists( $product, 'get_variation_attributes' ) ) {
		foreach ( array_keys( (array) $product->get_variation_attributes() ) as $attribute_key ) {
			if ( false !== strpos( sanitize_title( $attribute_key ), 'length' ) ) {
				return true;
			}
		}
	}

	foreach ( $product->get_attributes() as $attribute_key => $attribute_object ) {
		if ( false !== strpos( sanitize_title( $attribute_key ), 'length' ) ) {
			return true;
		}

		if (
			is_object( $attribute_object )
			&& method_exists( $attribute_object, 'get_name' )
			&& false !== strpos( sanitize_title( $attribute_object->get_name() ), 'length' )
		) {
			return true;
		}
	}

	return false;
}

/**
 * Rope products should show Length only when legacy Size duplicates it.
 *
 * @param string          $attribute Raw attribute name.
 * @param string          $label     Attribute label.
 * @param WC_Product|null $product   Product object.
 */
function asherava_pdp_should_hide_attribute( $attribute, $label, $product ) {
	if ( ! asherava_pdp_is_size_attribute( $attribute, $label ) || ! asherava_pdp_product_has_length_attribute( $product ) ) {
		return false;
	}

	if ( asherava_pdp_is_rope_product( $product ) ) {
		return true;
	}

	return $product instanceof WC_Product
		&& false !== strpos( sanitize_title( $product->get_slug() ), 'rope' );
}

/**
 * Format launch chain length terms as compact one-line labels.
 *
 * @param string          $name      Term display name.
 * @param WP_Term|null    $term      Term object.
 * @param string          $attribute Raw attribute name.
 * @param WC_Product|null $product   Product object.
 */
function asherava_pdp_length_option_name( $name, $term = null, $attribute = '', $product = null ) {
	$label = wc_attribute_label( $attribute, $product );

	if ( ! asherava_pdp_is_length_attribute( $attribute, $label ) ) {
		return $name;
	}

	if ( preg_match( '/^\s*(\d+(?:\.\d+)?)\s*(?:inch|inches|in|")?\s*$/i', wp_strip_all_tags( (string) $name ), $match ) ) {
		return $match[1] . '"';
	}

	return $name;
}

/**
 * Keep the duplicate Size dropdown present for WooCommerce matching, but hide it visually.
 *
 * @param string $html Default variation dropdown HTML.
 * @param array  $args Attribute args.
 */
function asherava_pdp_hide_duplicate_size_dropdown( $html, $args ) {
	if ( empty( $args['attribute'] ) ) {
		return $html;
	}

	$product = isset( $args['product'] ) ? $args['product'] : null;
	$label   = wc_attribute_label( $args['attribute'], $product );

	if ( ! asherava_pdp_should_hide_attribute( $args['attribute'], $label, $product ) ) {
		return $html;
	}

	return '<span class="av-pdp__hide-variation-row" data-av-hide-variation-row hidden></span><span class="av-pdp__select-hidden av-pdp__select-hidden--legacy-size" hidden aria-hidden="true">' . $html . '</span>';
}

function asherava_pdp_is_finish_attribute( $attribute, $label ) {
	$slug = sanitize_title( $attribute );

	return false !== strpos( $slug, 'finish' )
		|| false !== strpos( $slug, 'plating' )
		|| false !== stripos( $label, 'finish' )
		|| false !== stripos( $label, 'plating' );
}

function asherava_pdp_render_finish_hint() {
	echo '<p class="av-pdp__size-hint">' . esc_html__( 'Plated: rhodium over 925, brighter, slower to tarnish. Bare silver: unplated 925.', 'asherava-jaxxon' ) . '</p>';
}

function asherava_pdp_option_number( $option ) {
	if ( preg_match( '/(\d+(?:\.\d+)?)/', (string) $option, $match ) ) {
		return (float) $match[1];
	}

	return null;
}

function asherava_pdp_format_swatch_label( $text, $is_length ) {
	$key = strtolower( trim( str_replace( array( '-', '_' ), ' ', (string) $text ) ) );
	$finish_map = array(
		'rhodium plated' => __( 'Plated', 'asherava-jaxxon' ),
		'plated'         => __( 'Plated', 'asherava-jaxxon' ),
		'bare sterling'  => __( 'Bare silver', 'asherava-jaxxon' ),
		'bare silver'    => __( 'Bare silver', 'asherava-jaxxon' ),
		'unplated'       => __( 'Bare silver', 'asherava-jaxxon' ),
	);

	if ( isset( $finish_map[ $key ] ) ) {
		return $finish_map[ $key ];
	}

	if ( $is_length && preg_match( '/(\d+(?:\.\d+)?)/', (string) $text, $match ) ) {
		return $match[1] . '″';
	}

	return $text;
}

function asherava_pdp_render_swatch_buttons( $options, $selected, $attribute, $product, $is_length ) {
	foreach ( $options as $option ) {
		$raw_text = apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product );
		$text     = asherava_pdp_format_swatch_label( $raw_text, $is_length );
		$active   = selected( $selected, $option, false ) ? ' is-selected' : '';
		$number   = asherava_pdp_option_number( $option );
		?>
		<button type="button" class="av-pdp__swatch<?php echo esc_attr( $active ); ?>" data-value="<?php echo esc_attr( $option ); ?>">
			<span class="av-pdp__swatch-text"><?php echo esc_html( $text ); ?></span>
			<?php if ( $is_length && 22.0 === $number ) : ?>
				<span class="av-pdp__swatch-note"><?php esc_html_e( 'Top Pick', 'asherava-jaxxon' ); ?></span>
			<?php endif; ?>
		</button>
		<?php
	}
}

/**
 * Render attribute options as LZJ-style button grid (hidden select for WC).
 *
 * @param string $html Default dropdown HTML.
 * @param array  $args Attribute args.
 */
function asherava_pdp_variation_buttons( $html, $args ) {
	if ( empty( $args['options'] ) ) {
		return $html;
	}

	$product   = $args['product'];
	$attribute = $args['attribute'];
	$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
	$selected  = $args['selected'] ? $args['selected'] : '';
	$label     = wc_attribute_label( $attribute, $product );

	if ( asherava_pdp_should_hide_attribute( $attribute, $label, $product ) ) {
		return '<span class="av-pdp__hide-variation-row" data-av-hide-variation-row hidden></span><span class="av-pdp__select-hidden av-pdp__select-hidden--legacy-size" hidden aria-hidden="true">' . $html . '</span>';
	}

	$is_size   = asherava_pdp_is_size_attribute( $attribute, $label );
	$is_length = asherava_pdp_is_length_attribute( $attribute, $label );
	$is_finish = asherava_pdp_is_finish_attribute( $attribute, $label );
	$is_length = $is_length || $is_size;
	$primary   = array();
	$more      = array();

	if ( $is_length ) {
		foreach ( $args['options'] as $option ) {
			if ( in_array( asherava_pdp_option_number( $option ), array( 20.0, 22.0, 24.0 ), true ) ) {
				$primary[] = $option;
			} else {
				$more[] = $option;
			}
		}
	} else {
		$primary = $args['options'];
	}

	ob_start();
	?>
	<div class="av-pdp__option" data-attribute="<?php echo esc_attr( $name ); ?>">
		<p class="av-pdp__option-label<?php echo $is_length ? ' av-pdp__option-label--length' : ''; ?>">
			<span><?php echo esc_html( $is_length ? __( 'Length', 'asherava-jaxxon' ) : ( $is_finish ? __( 'Finish', 'asherava-jaxxon' ) : $label ) ); ?></span>
			<?php if ( $is_length && asherava_pdp_size_guide_url() ) : ?>
				<a class="av-pdp__size-guide" href="<?php echo esc_url( asherava_pdp_size_guide_url() ); ?>"><?php esc_html_e( 'Size guide', 'asherava-jaxxon' ); ?></a>
			<?php endif; ?>
		</p>
		<div class="av-pdp__swatches<?php echo $is_length ? ' av-pdp__swatches--size' : ( $is_finish ? ' av-pdp__swatches--finish' : '' ); ?>" role="group" aria-label="<?php echo esc_attr( $label ); ?>">
			<?php asherava_pdp_render_swatch_buttons( $primary, $selected, $attribute, $product, $is_length ); ?>
		</div>
		<?php if ( $is_length && $more ) : ?>
			<details class="av-pdp__more-lengths"<?php echo in_array( $selected, $more, true ) ? ' open' : ''; ?>>
				<summary><?php esc_html_e( 'More lengths', 'asherava-jaxxon' ); ?></summary>
				<div class="av-pdp__swatches av-pdp__swatches--size" role="group" aria-label="<?php esc_attr_e( 'More lengths', 'asherava-jaxxon' ); ?>">
					<?php asherava_pdp_render_swatch_buttons( $more, $selected, $attribute, $product, true ); ?>
				</div>
			</details>
		<?php endif; ?>
		<?php if ( $is_finish ) : ?>
			<?php asherava_pdp_render_finish_hint(); ?>
		<?php endif; ?>
		<div class="av-pdp__select-hidden">
			<?php
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WC core template.
			echo $html;
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_filter( 'woocommerce_product_get_default_attributes', 'asherava_pdp_default_rope_length', 10, 2 );
function asherava_pdp_default_rope_length( $defaults, $product ) {
	if ( ! asherava_pdp_is_rope_product( $product ) || ! method_exists( $product, 'get_variation_attributes' ) ) {
		return $defaults;
	}

	foreach ( (array) $product->get_variation_attributes() as $attribute => $options ) {
		$attribute_label = wc_attribute_label( $attribute, $product );
		if ( ! asherava_pdp_is_length_attribute( $attribute, $attribute_label ) && ! asherava_pdp_is_size_attribute( $attribute, $attribute_label ) ) {
			continue;
		}

		foreach ( (array) $options as $option ) {
			if ( 22.0 === asherava_pdp_option_number( $option ) ) {
				$defaults[ $attribute ] = $option;
				break 2;
			}
		}
	}

	return $defaults;
}

add_filter( 'generate_sidebar_layout', 'asherava_pdp_sidebar_layout' );
function asherava_pdp_sidebar_layout( $layout ) {
	if ( is_product() ) {
		return 'no-sidebar';
	}

	return $layout;
}

add_filter( 'body_class', 'asherava_pdp_body_class' );
function asherava_pdp_body_class( $classes ) {
	if ( is_product() ) {
		$classes[] = 'av-single-product';
	}

	return $classes;
}

add_filter( 'woocommerce_product_single_add_to_cart_text', 'asherava_pdp_add_to_cart_text' );
function asherava_pdp_add_to_cart_text( $text ) {
	return __( 'Add to cart', 'asherava-jaxxon' );
}

add_filter( 'woocommerce_get_stock_html', 'asherava_pdp_stock_badge', 10, 2 );
function asherava_pdp_stock_badge( $html, $product ) {
	if ( is_product() ) {
		return '';
	}

	if ( ! $product || ! $product->is_in_stock() ) {
		return $html;
	}

	return '<p class="av-pdp__stock av-pdp__stock--in">' . esc_html__( 'In stock', 'asherava-jaxxon' ) . '</p>';
}
