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
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_render_reviews', 20 );
	add_action( 'woocommerce_after_single_product_summary', 'asherava_pdp_close_below', 99 );
	add_action( 'woocommerce_after_single_product', 'asherava_pdp_close_wrapper', 99 );

	add_filter( 'woocommerce_product_description_heading', 'asherava_pdp_description_heading' );
	add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'asherava_pdp_variation_buttons', 20, 2 );
	add_filter( 'woocommerce_product_tabs', 'asherava_pdp_remove_tabs', 99 );
	add_filter( 'woocommerce_product_is_visible', 'asherava_pdp_visible_during_coming_soon', 10, 2 );
	add_filter( 'generate_show_breadcrumb', 'asherava_pdp_hide_theme_breadcrumb' );
	add_filter( 'woocommerce_product_get_name', 'asherava_pdp_display_name', 10, 2 );
	add_filter( 'the_title', 'asherava_pdp_the_title', 10, 2 );

	add_action( 'woocommerce_after_add_to_cart_quantity', 'asherava_pdp_render_stock_badge_after_qty', 12 );
	add_action( 'wp_enqueue_scripts', 'asherava_pdp_enqueue_stock_script', 25 );
	add_action( 'wp_enqueue_scripts', 'asherava_pdp_enqueue_gallery_video_script', 26 );
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

	echo '<p class="av-pdp__shipping-note">' . esc_html__( 'Free shipping in the US & Canada.', 'asherava-jaxxon' ) . '</p>';
}

function asherava_pdp_size_guide_link() {
	global $product;

	if ( $product && in_array( $product->get_slug(), asherava_pdp_rope_slugs(), true ) ) {
		return;
	}

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
 * 3mm rope chain weight rows for buybox size guide.
 *
 * @return array<string, string> Length label => grams.
 */
function asherava_pdp_rope_3mm_weight_rows() {
	return array(
		'18 Inches' => '16.5',
		'20 Inches' => '18',
		'22 Inches' => '19.5',
		'24 Inches' => '22',
		'26 Inches' => '24',
		'28 Inches' => '26',
		'30 Inches' => '27',
		'32 Inches' => '29',
	);
}

/**
 * Buybox product copy for rope PDPs (LZJ layout, Asherava typography).
 *
 * @param string $slug Product slug.
 */
function asherava_pdp_rope_buybox_description_html( $slug ) {
	if ( ! in_array( $slug, asherava_pdp_rope_slugs(), true ) ) {
		return '';
	}

	ob_start();
	?>
	<p class="av-pdp__description-intro">
		<?php
		esc_html_e(
			'This 3mm Rope Sterling Silver chain is made in Italy and crafted with high precision and attention to detail. Featuring a diamond-cut rope design, it is sure to add a touch of sparkle and sophistication to any outfit. The 3mm Rope chain is made of 925 sterling silver for superior quality and durability.',
			'asherava-jaxxon'
		);
		?>
	</p>
	<h3 class="av-pdp__spec-heading"><?php esc_html_e( "Men's Rope Chain Size Guide", 'asherava-jaxxon' ); ?></h3>
	<ul class="av-pdp__spec-list">
		<?php foreach ( asherava_pdp_rope_3mm_weight_rows() as $length => $grams ) : ?>
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
	<p class="av-pdp__spec-note">
		<?php esc_html_e( 'All chain weights are an approximate gram weight. Final chain weight may vary.', 'asherava-jaxxon' ); ?>
	</p>
	<p class="av-pdp__spec-final"><?php esc_html_e( 'All prices are final.', 'asherava-jaxxon' ); ?></p>
	<?php
	return ob_get_clean();
}

/**
 * Buybox description (below Add to cart, inside right column).
 */
function asherava_pdp_render_description() {
	global $product;

	if ( ! $product ) {
		return;
	}

	$slug = $product->get_slug();
	$body = asherava_pdp_rope_buybox_description_html( $slug );

	if ( '' === $body ) {
		$body = $product->get_short_description();
		if ( ! $body ) {
			list( $body, ) = asherava_pdp_split_description( $product->get_description() );
		}
		$body = trim( (string) $body );
	}

	if ( '' === $body ) {
		return;
	}

	$title = get_post_meta( $product->get_id(), '_asherava_display_name', true );
	if ( ! $title ) {
		$title = asherava_pdp_short_name_for_slug( $slug );
	}
	if ( ! $title ) {
		$title = $product->get_name();
	}

	echo '<section class="av-pdp__description av-pdp__description--buybox">';
	echo '<h2 class="av-pdp__description-heading">' . esc_html( $title ) . '</h2>';
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

/**
 * Customer reviews (WooCommerce comments) below the PDP grid.
 */
function asherava_pdp_render_reviews() {
	global $product;

	if ( ! $product || ! wc_review_ratings_enabled() || ! $product->get_review_count() ) {
		return;
	}

	if ( ! comments_open( $product->get_id() ) ) {
		return;
	}

	echo '<section class="av-pdp__reviews" id="reviews">';
	comments_template();
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
 * Format size swatch label (LZJ: "18 INCHES").
 *
 * @param string $text    Option label or slug.
 * @param bool   $is_size Whether attribute is chain length.
 */
function asherava_pdp_format_swatch_label( $text, $is_size ) {
	if ( ! $is_size ) {
		return $text;
	}

	$text = trim( str_replace( array( '-', '_' ), ' ', (string) $text ) );

	if ( preg_match( '/^(\d+)\s*inches?$/i', $text, $m ) ) {
		return $m[1] . '-inch';
	}

	return strtolower( str_replace( ' ', '-', $text ) );
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

	static $size_stock_badge_rendered = false;

	ob_start();
	?>
	<div class="av-pdp__option" data-attribute="<?php echo esc_attr( $name ); ?>">
		<p class="av-pdp__option-label av-type-label">
			<?php
			if ( $is_size ) {
				esc_html_e( 'Select size', 'asherava-jaxxon' );
			} else {
				echo esc_html( sprintf( /* translators: %s: attribute label */ __( 'Select %s', 'asherava-jaxxon' ), $label ) );
			}
			?>
		</p>
		<?php
		$size_option_in_stock = array();
		if ( $is_size && $product->is_type( 'variable' ) ) {
			$stock_map = asherava_pdp_variation_stock_map( $product );
			foreach ( $product->get_children() as $variation_id ) {
				$variation = wc_get_product( $variation_id );
				if ( ! $variation ) {
					continue;
				}
				$attrs = $variation->get_attributes();
				if ( empty( $attrs['pa_size'] ) ) {
					continue;
				}
				$stock = isset( $stock_map[ (int) $variation_id ] ) ? $stock_map[ (int) $variation_id ] : null;
				$size_option_in_stock[ $attrs['pa_size'] ] = $stock && ! empty( $stock['in_stock'] );
			}
		}
		?>
		<div class="av-pdp__swatches<?php echo $is_size ? ' av-pdp__swatches--size' : ''; ?>" role="group" aria-label="<?php echo esc_attr( $label ); ?>">
			<?php
			foreach ( $args['options'] as $option ) :
				$value      = esc_attr( $option );
				$raw_text   = apply_filters( 'woocommerce_variation_option_name', $option, null, $attribute, $product );
				$text       = esc_html( asherava_pdp_format_swatch_label( $raw_text, $is_size ) );
				$active     = selected( $selected, $option, false ) ? ' is-selected' : '';
				$disabled   = '';
				$aria_label = $text;

				if ( $is_size && $product->is_type( 'variable' ) && isset( $size_option_in_stock[ $option ] ) && ! $size_option_in_stock[ $option ] ) {
					$disabled   = ' is-disabled';
					$aria_label = $text . ' (' . __( 'Out of stock', 'asherava-jaxxon' ) . ')';
				}
				?>
				<button type="button" class="av-pdp__swatch<?php echo esc_attr( $active . $disabled ); ?>" data-value="<?php echo $value; ?>"<?php echo $disabled ? ' disabled' : ''; ?> aria-label="<?php echo esc_attr( $aria_label ); ?>">
					<?php echo $text; ?>
				</button>
			<?php endforeach; ?>
		</div>
		<?php
		if ( $is_size && ! $size_stock_badge_rendered && $product->is_type( 'variable' ) ) {
			asherava_pdp_render_stock_badge();
			$size_stock_badge_rendered = true;
		}
		?>
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
/**
 * Suppress default WC stock HTML on PDP (custom per-SKU badge instead).
 *
 * @param string       $html    Stock HTML.
 * @param WC_Product   $product Product.
 */
function asherava_pdp_stock_badge( $html, $product ) {
	if ( is_product() ) {
		return '';
	}

	return $html;
}

/**
 * Whether the IN STOCK badge should show for a product/variation.
 *
 * @param WC_Product $product Product or variation.
 */
function asherava_pdp_should_show_stock_badge( $product ) {
	if ( ! $product || ! $product->is_in_stock() ) {
		return false;
	}

	if ( ! $product->managing_stock() ) {
		return true;
	}

	$qty = (int) $product->get_stock_quantity();

	return $qty > 0;
}

/**
 * Stock map for variable product variations (variation_id => qty meta).
 *
 * @param WC_Product_Variable $product Variable product.
 * @return array<int, array{in_stock: bool, qty: int|null}>
 */
function asherava_pdp_variation_stock_map( $product ) {
	$map = array();

	if ( ! $product->is_type( 'variable' ) ) {
		return $map;
	}

	foreach ( $product->get_children() as $variation_id ) {
		$variation = wc_get_product( $variation_id );
		if ( ! $variation ) {
			continue;
		}

		$map[ (int) $variation_id ] = array(
			'in_stock' => $variation->is_in_stock(),
			'qty'      => $variation->managing_stock() ? (int) $variation->get_stock_quantity() : null,
		);
	}

	return $map;
}

/**
 * Low-stock badge below size swatches (LZJ: "ONLY 5 LEFT!").
 */
function asherava_pdp_render_stock_badge() {
	printf(
		'<div class="av-pdp__stock-badge" id="av-pdp-stock-badge" hidden aria-live="polite"><span class="av-pdp__stock av-pdp__stock--low"></span></div>'
	);
}

/**
 * Simple products: stock badge after quantity (same low-stock rules).
 */
function asherava_pdp_render_stock_badge_after_qty() {
	global $product;

	if ( ! $product || ! is_product() || $product->is_type( 'variable' ) ) {
		return;
	}

	asherava_pdp_render_stock_badge();
}

/**
 * Default low-stock threshold for "ONLY X LEFT!" badge.
 */
function asherava_pdp_low_stock_threshold() {
	return (int) apply_filters( 'asherava_pdp_low_stock_threshold', 10 );
}

/**
 * Whether the low-stock badge should show.
 *
 * @param array{in_stock: bool, qty: int|null} $stock Stock row.
 */
function asherava_pdp_should_show_low_stock_badge( $stock ) {
	if ( empty( $stock['in_stock'] ) ) {
		return false;
	}

	if ( null === $stock['qty'] ) {
		return false;
	}

	$qty = (int) $stock['qty'];

	return $qty > 0 && $qty <= asherava_pdp_low_stock_threshold();
}

/**
 * Pass variation stock + simple product stock to PDP script.
 */
function asherava_pdp_enqueue_stock_script() {
	if ( ! is_product() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_queried_object_id() );
	}

	if ( ! $product ) {
		return;
	}

	wp_enqueue_script( 'wc-add-to-cart-variation' );

	$simple_stock = array(
		'in_stock' => $product->is_in_stock(),
		'qty'      => $product->managing_stock() ? (int) $product->get_stock_quantity() : null,
	);

	$payload = array(
		'i18nOnlyLeft'        => __( 'Only %d left', 'asherava-jaxxon' ),
		'lowStockThreshold'   => asherava_pdp_low_stock_threshold(),
		'isVariable'          => $product->is_type( 'variable' ),
		'variations'          => array(),
		'simple'              => array_merge(
			$simple_stock,
			array( 'show' => asherava_pdp_should_show_low_stock_badge( $simple_stock ) )
		),
	);

	if ( $product->is_type( 'variable' ) ) {
		$payload['variations'] = asherava_pdp_variation_stock_map( $product );
	}

	wp_localize_script( 'asherava-jaxxon', 'asheravaPdpStock', $payload );
}

/**
 * Optional PDP gallery video URL (last thumbnail = play video).
 *
 * Product meta: _asherava_gallery_video_url
 * Supports self-hosted .mp4/.webm or YouTube / Vimeo links.
 *
 * @param int $product_id Product ID.
 */
function asherava_pdp_get_gallery_video_url( $product_id ) {
	$url = trim( (string) get_post_meta( $product_id, '_asherava_gallery_video_url', true ) );

	if ( $url ) {
		return esc_url_raw( $url );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return '';
	}

	$bundled = get_stylesheet_directory() . '/assets/videos/3mm-rope-product.mp4';
	if ( in_array( $product->get_slug(), asherava_pdp_rope_slugs(), true ) && file_exists( $bundled ) ) {
		return esc_url_raw( get_stylesheet_directory_uri() . '/assets/videos/3mm-rope-product.mp4' );
	}

	return '';
}

/**
 * Pass gallery video config to PDP script.
 */
function asherava_pdp_enqueue_gallery_video_script() {
	if ( ! is_product() ) {
		return;
	}

	global $product;

	if ( ! $product instanceof WC_Product ) {
		$product = wc_get_product( get_queried_object_id() );
	}

	if ( ! $product ) {
		return;
	}

	$video_url = asherava_pdp_get_gallery_video_url( $product->get_id() );
	if ( ! $video_url ) {
		return;
	}

	$poster = get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_gallery_thumbnail' );
	if ( ! $poster ) {
		$poster = get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_single' );
	}

	wp_localize_script(
		'asherava-jaxxon',
		'asheravaPdpGalleryVideo',
		array(
			'url'    => $video_url,
			'poster' => $poster ? esc_url_raw( $poster ) : '',
			'i18nPlay' => __( 'Play product video', 'asherava-jaxxon' ),
		)
	);
}
