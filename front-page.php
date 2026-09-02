<?php
/**
 * Asherava storefront homepage.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$shop_url            = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$featured_categories = asherava_get_featured_catalog();
$size_guide_url       = asherava_resolve_menu_url( array( 'mens-chain-size-guide', 'chain-size-guide', 'size-guide' ), '/mens-chain-size-guide/' );
$featured_product     = false;

if ( function_exists( 'wc_get_product' ) ) {
	$featured_post = get_page_by_path( '3mm-rope-sterling-silver-chain', OBJECT, 'product' );

	if ( $featured_post instanceof WP_Post ) {
		$featured_product = wc_get_product( $featured_post->ID );
	}

	if ( ! $featured_product && function_exists( 'wc_get_products' ) ) {
		$products = wc_get_products(
			array(
				'limit'   => 1,
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
		$featured_product = ! empty( $products ) ? $products[0] : false;
	}
}

$product_url   = $featured_product ? $featured_product->get_permalink() : $shop_url;
$product_name  = $featured_product ? $featured_product->get_name() : __( '3mm Rope Chain', 'asherava-jaxxon' );
$product_price = $featured_product ? $featured_product->get_price_html() : '';
$product_image = $featured_product ? $featured_product->get_image( 'woocommerce_single' ) : '';
?>

<main id="primary" class="av-home av-home--store">
	<section class="av-store-hero">
		<div class="av-store-hero__media" aria-hidden="true"></div>
		<div class="av-store-hero__overlay" aria-hidden="true"></div>
		<div class="av-container av-store-hero__content">
			<p class="av-eyebrow"><?php esc_html_e( '925 Sterling Silver', 'asherava-jaxxon' ); ?></p>
			<h1><?php esc_html_e( '3mm Rope Chain', 'asherava-jaxxon' ); ?></h1>
			<p><?php esc_html_e( 'A balanced everyday profile with your choice of bare silver or plated finish.', 'asherava-jaxxon' ); ?></p>
			<div class="av-store-hero__actions">
				<a class="av-btn av-btn--primary av-btn--light" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'Shop the chain', 'asherava-jaxxon' ); ?></a>
				<a class="av-btn av-btn--ghost" href="<?php echo esc_url( $size_guide_url ); ?>"><?php esc_html_e( 'Find your length', 'asherava-jaxxon' ); ?></a>
			</div>
		</div>
	</section>

	<section class="av-store-trust" aria-label="<?php esc_attr_e( 'Store benefits', 'asherava-jaxxon' ); ?>">
		<div class="av-container av-store-trust__grid">
			<div><strong><?php esc_html_e( '925 Sterling Silver', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Clear material details', 'asherava-jaxxon' ); ?></span></div>
			<div><strong><?php esc_html_e( '18–32 Inch Lengths', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Find your preferred fit', 'asherava-jaxxon' ); ?></span></div>
			<div><strong><?php esc_html_e( 'Two Finishes', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Bare silver or plated', 'asherava-jaxxon' ); ?></span></div>
			<div><strong><?php esc_html_e( '30-Day Returns', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Shop with confidence', 'asherava-jaxxon' ); ?></span></div>
		</div>
	</section>

	<section class="av-section av-store-collections">
		<div class="av-container">
			<div class="av-section__head">
				<div>
					<p class="av-eyebrow"><?php esc_html_e( 'Browse the collection', 'asherava-jaxxon' ); ?></p>
					<h2><?php esc_html_e( 'Shop rope chains by width', 'asherava-jaxxon' ); ?></h2>
					<p><?php esc_html_e( 'Start with the profile that fits how you plan to wear it.', 'asherava-jaxxon' ); ?></p>
				</div>
				<a class="av-link" href="<?php echo esc_url( asherava_get_category_url( 'rope-chains' ) ); ?>"><?php esc_html_e( 'View all chains', 'asherava-jaxxon' ); ?></a>
			</div>
			<nav class="av-store-filters" aria-label="<?php esc_attr_e( 'Chain widths', 'asherava-jaxxon' ); ?>">
				<a class="is-active" href="<?php echo esc_url( asherava_get_category_url( 'rope-chains' ) ); ?>"><?php esc_html_e( 'All', 'asherava-jaxxon' ); ?></a>
				<?php foreach ( $featured_categories as $category ) : ?>
					<a href="<?php echo esc_url( asherava_get_category_url( $category['slug'] ) ); ?>"><?php echo esc_html( preg_replace( '/\s+Rope Chains$/', '', $category['title'] ) ); ?></a>
				<?php endforeach; ?>
			</nav>
			<div class="av-store-collection-grid">
				<?php foreach ( $featured_categories as $category ) : ?>
					<a class="av-store-collection-card" href="<?php echo esc_url( asherava_get_category_url( $category['slug'] ) ); ?>">
						<figure><img src="<?php echo esc_url( $category['image'] ); ?>" alt="<?php echo esc_attr( $category['title'] ); ?>" loading="lazy"></figure>
						<div><h3><?php echo esc_html( $category['title'] ); ?></h3><span><?php esc_html_e( 'Explore the profile', 'asherava-jaxxon' ); ?></span></div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="av-section av-store-featured">
		<div class="av-container">
			<div class="av-section__head">
				<div><p class="av-eyebrow"><?php esc_html_e( 'Current favorite', 'asherava-jaxxon' ); ?></p><h2><?php esc_html_e( 'A closer look at 3mm', 'asherava-jaxxon' ); ?></h2></div>
				<a class="av-link" href="<?php echo esc_url( $product_url ); ?>"><?php esc_html_e( 'Open product page', 'asherava-jaxxon' ); ?></a>
			</div>
			<a class="av-store-featured__product" href="<?php echo esc_url( $product_url ); ?>">
				<div class="av-store-featured__media">
					<?php if ( $product_image ) : ?>
						<?php echo wp_kses_post( $product_image ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/images/products/3mm-rope-chain-white.png' ); ?>" alt="<?php echo esc_attr( $product_name ); ?>">
					<?php endif; ?>
				</div>
				<div class="av-store-featured__details">
					<p class="av-eyebrow"><?php esc_html_e( 'Sterling Silver 925', 'asherava-jaxxon' ); ?></p>
					<h2><?php echo esc_html( asherava_pdp_short_name_for_slug( $featured_product ? $featured_product->get_slug() : '' ) ?: $product_name ); ?></h2>
					<?php if ( $product_price ) : ?><p class="av-store-featured__price"><?php echo wp_kses_post( $product_price ); ?></p><?php endif; ?>
					<p><?php esc_html_e( 'Choose from bare silver or plated finish, with lengths from 18 to 32 inches.', 'asherava-jaxxon' ); ?></p>
					<div class="av-store-featured__spec"><span><?php esc_html_e( 'Finish', 'asherava-jaxxon' ); ?></span><strong><?php esc_html_e( 'Bare silver / Plated', 'asherava-jaxxon' ); ?></strong></div>
					<div class="av-store-featured__spec"><span><?php esc_html_e( 'Length', 'asherava-jaxxon' ); ?></span><strong><?php esc_html_e( '18″–32″', 'asherava-jaxxon' ); ?></strong></div>
					<span class="av-btn av-btn--primary"><?php esc_html_e( 'View product', 'asherava-jaxxon' ); ?></span>
					<small><?php esc_html_e( '30-day returns · Secure checkout', 'asherava-jaxxon' ); ?></small>
				</div>
			</a>
		</div>
	</section>

	<section class="av-section av-store-guide">
		<div class="av-container av-store-guide__grid">
			<div>
				<p class="av-eyebrow"><?php esc_html_e( 'Length guide', 'asherava-jaxxon' ); ?></p>
				<h2><?php esc_html_e( 'Choose the way it sits.', 'asherava-jaxxon' ); ?></h2>
				<p><?php esc_html_e( 'A quick comparison makes sizing easier before you reach the product page.', 'asherava-jaxxon' ); ?></p>
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $size_guide_url ); ?>"><?php esc_html_e( 'Open size guide', 'asherava-jaxxon' ); ?></a>
			</div>
			<div class="av-store-guide__lengths">
				<div><strong><?php esc_html_e( '18–20″', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Closer to the collarbone', 'asherava-jaxxon' ); ?></span></div>
				<div><strong><?php esc_html_e( '22″', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Everyday length and top pick', 'asherava-jaxxon' ); ?></span></div>
				<div><strong><?php esc_html_e( '24–26″', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Lower on the chest', 'asherava-jaxxon' ); ?></span></div>
				<div><strong><?php esc_html_e( '28–32″', 'asherava-jaxxon' ); ?></strong><span><?php esc_html_e( 'Longer, layered look', 'asherava-jaxxon' ); ?></span></div>
			</div>
		</div>
	</section>

	<section class="av-section av-store-help">
		<div class="av-container">
			<div class="av-section__head"><div><p class="av-eyebrow"><?php esc_html_e( 'Before you buy', 'asherava-jaxxon' ); ?></p><h2><?php esc_html_e( 'Clear answers, close to the product.', 'asherava-jaxxon' ); ?></h2></div></div>
			<div class="av-store-help__grid">
				<a href="<?php echo esc_url( $product_url ); ?>"><span>01</span><h3><?php esc_html_e( 'Material & finish', 'asherava-jaxxon' ); ?></h3><p><?php esc_html_e( 'Understand bare silver and plated options before choosing.', 'asherava-jaxxon' ); ?></p></a>
				<a href="<?php echo esc_url( $size_guide_url ); ?>"><span>02</span><h3><?php esc_html_e( 'Chain size guide', 'asherava-jaxxon' ); ?></h3><p><?php esc_html_e( 'Compare widths and lengths without leaving the shopping flow.', 'asherava-jaxxon' ); ?></p></a>
				<a href="<?php echo esc_url( asherava_resolve_menu_url( array( 'shipping-policy' ), '/shipping-policy/' ) ); ?>"><span>03</span><h3><?php esc_html_e( 'Shipping & returns', 'asherava-jaxxon' ); ?></h3><p><?php esc_html_e( 'Review delivery coverage and the 30-day return policy.', 'asherava-jaxxon' ); ?></p></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
