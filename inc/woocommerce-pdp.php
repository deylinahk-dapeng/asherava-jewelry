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
		'1-8mm-rope-chain-sterling-silver',
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
		'3mm-rope-sterling-silver-chain'   => __( '3mm Rope Chain', 'asherava-jaxxon' ),
		'3mm-rope-chain-sterling-silver'   => __( '3mm Rope Chain', 'asherava-jaxxon' ),
		'1-8mm-rope-chain-sterling-silver' => __( '1.8mm Rope Chain', 'asherava-jaxxon' ),
		'4mm-rope-chain-sterling-silver'   => __( '4mm Rope Chain', 'asherava-jaxxon' ),
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

add_action( 'wp', 'asherava_pdp_setup_hooks' );
function asherava_pdp_setup_hooks() {
	if ( ! is_product() ) {
		return;
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
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_size_guide_link', 31 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_render_description', 32 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_render_trust_blocks', 36 );
	add_action( 'woocommerce_single_product_summary', 'asherava_pdp_render_accordions', 40 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_open_below', 1 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_render_seo_description', 8 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_close_below', 99 );
	add_action( 'woocommerce_after_single_product', 'asherava_pdp_close_wrapper', 99 );

	add_filter( 'woocommerce_product_description_heading', 'asherava_pdp_description_heading' );
	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'asherava_pdp_variation_buttons', 20, 2 );
	add_filter( 'woocommerce_product_tabs', 'asherava_pdp_remove_tabs', 99 );
	add_filter( 'woocommerce_product_is_visible', 'asherava_pdp_visible_during_coming_soon', 10, 2 );
	add_filter( 'generate_show_breadcrumb', 'asherava_pdp_hide_theme_breadcrumb' );
	add_filter( 'woocommerce_product_get_name', 'asherava_pdp_display_name', 10, 2 );
	add_filter( 'the_title', 'asherava_pdp_the_title', 10, 2 );
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
	$material = get_post_meta( get_the_ID(), '_asherava_material_label', true );
	if ( ! $material ) {
		$material = __( 'Sterling Silver 925', 'asherava-jaxxon' );
	}

	echo '<p class="av-pdp__material av-type-label">' . esc_html( $material ) . '</p>';
}

function asherava_pdp_shipping_note() {
	global $product;

	if ( ! $product ) {
		return;
	}

	echo '<p class="av-pdp__shipping-note">' . esc_html__( 'Shipping calculated at checkout.', 'asherava-jaxxon' ) . '</p>';
}

function asherava_pdp_size_guide_link() {
	$url = asherava_resolve_menu_url(
		array( 'mens-rope-chain-size-guide', 'rope-chain-size-guide', 'size-guide' ),
		'/size-guide/'
	);

	if ( ! $url ) {
		return;
	}

	echo '<p class="av-pdp__size-guide"><a href="' . esc_url( $url ) . '">' . esc_html__( "Men's Rope Chain Size Guide", 'asherava-jaxxon' ) . '</a></p>';
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
function asherava_pdp_render_description() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$body = $product->get_short_description();
	if ( ! $body ) {
		list( $body, ) = asherava_pdp_split_description( $product->get_description() );
	}

	$body = trim( (string) $body );
	if ( '' === $body ) {
		return;
	}

	echo '<section class="av-pdp__description av-pdp__description--buybox">';
	echo '<h2 class="av-pdp__description-heading">' . esc_html( $product->get_name() ) . '</h2>';
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
 * Whether a variation attribute is chain length / size.
 *
 * @param string $attribute Raw attribute name.
 * @param string $label     Attribute label.
 */
function asherava_pdp_is_size_attribute( $attribute, $label ) {
	$slug = sanitize_title( $attribute );

	return false !== strpos( $slug, 'size' )
		|| false !== strpos( $slug, 'length' )
		|| false !== stripos( $label, 'size' )
		|| false !== stripos( $label, 'length' );
}

/**
 * T-shirt ↔ chain length hint above size swatches (JAXXON-style).
 */
function asherava_pdp_render_size_fit_hint( $extra_options = array() ) {
	$custom = get_post_meta( get_the_ID(), '_asherava_size_fit_hint', true );
	if ( 'hide' === $custom ) {
		return;
	}

	echo '<p class="av-pdp__size-hint">';

	if ( $custom && 'hide' !== $custom ) {
		echo esc_html( $custom );
		echo '</p>';
		return;
	}

	echo esc_html__( 'Start with your usual t-shirt size.', 'asherava-jaxxon' );
	echo ' <span class="av-pdp__size-hint-map" aria-hidden="true">';
	echo '<span class="av-pdp__size-hint-item"><abbr title="' . esc_attr__( 'Small', 'asherava-jaxxon' ) . '">S</abbr> (18&Prime;)</span>';
	echo ' <span class="av-pdp__size-hint-item"><abbr title="' . esc_attr__( 'Medium', 'asherava-jaxxon' ) . '">M</abbr> (20&Prime;)</span>';
	echo ' <span class="av-pdp__size-hint-item"><abbr title="' . esc_attr__( 'Large', 'asherava-jaxxon' ) . '">L</abbr> (22&Prime;)</span>';
	echo ' <span class="av-pdp__size-hint-item"><abbr title="' . esc_attr__( 'Extra large', 'asherava-jaxxon' ) . '">XL</abbr> (24&Prime;)</span>';
	echo '</span>';
	echo '</p>';

	$has_long = false;
	foreach ( $extra_options as $option ) {
		if ( preg_match( '/\b(2[6-9]|3[0-2])\b/', (string) $option ) ) {
			$has_long = true;
			break;
		}
	}

	if ( $has_long ) {
		echo '<p class="av-pdp__size-hint av-pdp__size-hint--long">';
		echo esc_html__( 'Need more length? 26″–32″ sizes are listed below.', 'asherava-jaxxon' );
		echo '</p>';
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

	$is_size = asherava_pdp_is_size_attribute( $attribute, $label );

	ob_start();
	?>
	<div class="av-pdp__option" data-attribute="<?php echo esc_attr( $name ); ?>">
		<p class="av-pdp__option-label av-type-label"><?php echo esc_html( sprintf( /* translators: %s: attribute label */ __( 'Select %s', 'asherava-jaxxon' ), $label ) ); ?></p>
		<?php
		if ( $is_size ) {
			asherava_pdp_render_size_fit_hint( $args['options'] );
		}
		?>
		<div class="av-pdp__swatches" role="group" aria-label="<?php echo esc_attr( $label ); ?>">
			<?php
			foreach ( $args['options'] as $option ) :
				$value  = esc_attr( $option );
				$text   = esc_html( apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product ) );
				$active = selected( $selected, $option, false ) ? ' is-selected' : '';
				?>
				<button type="button" class="av-pdp__swatch<?php echo esc_attr( $active ); ?>" data-value="<?php echo $value; ?>">
					<?php echo $text; ?>
				</button>
			<?php endforeach; ?>
		</div>
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
	if ( ! $product || ! $product->is_in_stock() ) {
		return $html;
	}

	return '<p class="av-pdp__stock av-pdp__stock--in">' . esc_html__( 'In stock', 'asherava-jaxxon' ) . '</p>';
}
