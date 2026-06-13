<?php
/**
 * Jaxxon-style homepage template.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

$featured_categories = asherava_get_featured_catalog();
$hero_image          = function_exists( 'asherava_jaxxon_get_hero_image_url' ) ? asherava_jaxxon_get_hero_image_url() : get_stylesheet_directory_uri() . '/assets/images/products/3mm-rope-chain-black.png';
$products            = function_exists( 'wc_get_products' ) ? wc_get_products(
	array(
		'limit'   => 6,
		'status'  => 'publish',
		'orderby' => 'date',
		'order'   => 'DESC',
	)
) : array();
?>

<main id="primary" class="av-home">
	<section class="av-hero av-hero--fullbleed" aria-label="<?php esc_attr_e( 'Featured collection', 'asherava-jaxxon' ); ?>">
		<?php asherava_render_announcement_bar( 'hero' ); ?>
		<div class="av-hero__media av-hero__media--product" style="background-image:url('<?php echo esc_url( $hero_image ); ?>');"></div>
		<div class="av-hero__overlay av-hero__overlay--fullbleed"></div>
		<div class="av-hero__content av-hero__content--center">
			<p class="av-eyebrow"><?php esc_html_e( 'AsherAva Fine Silver', 'asherava-jaxxon' ); ?></p>
			<h1 class="av-hero__title"><?php esc_html_e( '925 Sterling Silver Chains', 'asherava-jaxxon' ); ?></h1>
			<p class="av-hero__subtitle"><?php esc_html_e( 'Italian-crafted rope and Cuban chains for everyday wear.', 'asherava-jaxxon' ); ?></p>
			<div class="av-hero__actions">
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop Chains', 'asherava-jaxxon' ); ?></a>
				<a class="av-btn av-btn--ghost" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Best Sellers', 'asherava-jaxxon' ); ?></a>
			</div>
		</div>
	</section>

	<section class="av-trust">
		<div class="av-container av-trust__grid">
			<div class="av-trust__item">
				<strong>925 Silver</strong>
				<span>Sterling silver chains</span>
			</div>
			<div class="av-trust__item">
				<strong>Free Shipping</strong>
				<span>US &amp; Canada</span>
			</div>
			<div class="av-trust__item">
				<strong>30-Day Returns</strong>
				<span>Easy exchanges</span>
			</div>
			<div class="av-trust__item">
				<strong>Fair Pricing</strong>
				<span>Direct sourcing model</span>
			</div>
		</div>
	</section>

	<section class="av-section av-section--dark">
		<div class="av-container">
			<div class="av-section__head">
				<h2><?php esc_html_e( 'New Arrivals', 'asherava-jaxxon' ); ?></h2>
				<p><?php esc_html_e( 'Fresh sterling silver chains from our launch collection.', 'asherava-jaxxon' ); ?></p>
			</div>
			<div class="av-product-grid">
				<?php if ( ! empty( $products ) ) : ?>
					<?php foreach ( $products as $product ) : ?>
						<a class="av-product-card" href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<div class="av-product-card__image">
								<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							<h3><?php echo esc_html( $product->get_name() ); ?></h3>
							<p class="av-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="av-product-empty">
						<p class="av-eyebrow"><?php esc_html_e( 'Launch Collection', 'asherava-jaxxon' ); ?></p>
						<h3><?php esc_html_e( 'Sterling silver chains are being prepared.', 'asherava-jaxxon' ); ?></h3>
						<p><?php esc_html_e( 'Our first rope and Cuban chain listings are in progress. Join the list or check back soon for launch inventory.', 'asherava-jaxxon' ); ?></p>
						<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View Shop', 'asherava-jaxxon' ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php if ( ! empty( $products ) ) : ?>
	<section class="av-section">
		<div class="av-container">
			<div class="av-section__head">
				<h2><?php esc_html_e( 'Best Sellers', 'asherava-jaxxon' ); ?></h2>
				<a class="av-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View all', 'asherava-jaxxon' ); ?></a>
			</div>
			<div class="av-product-grid av-product-grid--light">
				<?php foreach ( array_slice( $products, 0, 3 ) as $product ) : ?>
					<a class="av-product-card av-product-card--light" href="<?php echo esc_url( $shop_url ); ?>">
						<div class="av-product-card__image">
							<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<h3><?php echo esc_html( $product->get_name() ); ?></h3>
						<p class="av-product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<section class="av-section av-section--story">
		<div class="av-container av-story">
			<div>
				<p class="av-eyebrow"><?php esc_html_e( 'Our Story', 'asherava-jaxxon' ); ?></p>
				<h2><?php esc_html_e( 'Built for the long run.', 'asherava-jaxxon' ); ?></h2>
			</div>
			<div class="av-story__copy">
				<p><?php esc_html_e( 'Asherava is named after my two children, Asher and Ava. This brand was built with a simple idea: create lasting sterling silver jewelry, price it fairly, and grow it patiently.', 'asherava-jaxxon' ); ?></p>
				<p><?php esc_html_e( 'With years in the jewelry business, direct access to Italian sterling silver chains, and finishing work through trusted partners in Panyu, Guangzhou, we keep the process close and the pricing honest.', 'asherava-jaxxon' ); ?></p>
			</div>
		</div>
	</section>

	<section class="av-section av-section--muted">
		<div class="av-container">
			<div class="av-section__head av-section__head--center">
				<div>
					<h2><?php esc_html_e( 'Shop Rope Chains by Width', 'asherava-jaxxon' ); ?></h2>
					<p><?php esc_html_e( 'Start with our core sterling silver rope chain lineup: 3mm, 1.8mm, 4.5mm, 5.5mm, and 4mm.', 'asherava-jaxxon' ); ?></p>
				</div>
				<a class="av-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop all rope chains', 'asherava-jaxxon' ); ?></a>
			</div>
			<div class="av-featured-collections" data-av-featured-collections>
				<?php foreach ( $featured_categories as $category ) : ?>
					<a class="av-category-card" href="<?php echo esc_url( asherava_get_category_url( $category['slug'] ) ); ?>">
						<img src="<?php echo esc_url( $category['image'] ); ?>" alt="<?php echo esc_attr( $category['title'] ); ?>" loading="lazy" />
						<div class="av-category-card__label">
							<span><?php echo esc_html( $category['title'] ); ?></span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="av-section">
		<div class="av-container">
			<div class="av-section__head av-section__head--center">
				<div>
					<h2><?php esc_html_e( 'Customer Confidence', 'asherava-jaxxon' ); ?></h2>
					<p><?php esc_html_e( 'Simple promises for a cleaner buying experience.', 'asherava-jaxxon' ); ?></p>
				</div>
			</div>
			<div class="av-confidence">
				<div class="av-confidence__item">
					<h3><?php esc_html_e( 'Sterling Silver 925', 'asherava-jaxxon' ); ?></h3>
					<p><?php esc_html_e( 'Focused on silver chains with clear material information and wearable everyday proportions.', 'asherava-jaxxon' ); ?></p>
				</div>
				<div class="av-confidence__item">
					<h3><?php esc_html_e( 'Direct Value', 'asherava-jaxxon' ); ?></h3>
					<p><?php esc_html_e( 'We sell directly instead of building our pricing around high marketplace fees.', 'asherava-jaxxon' ); ?></p>
				</div>
				<div class="av-confidence__item">
					<h3><?php esc_html_e( 'Global Shipping', 'asherava-jaxxon' ); ?></h3>
					<p><?php esc_html_e( 'Prepared for worldwide customers with clear shipping, return, and support policies.', 'asherava-jaxxon' ); ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="av-section av-quiz">
		<div class="av-container av-quiz__inner">
			<div>
				<p class="av-eyebrow">Need Help Deciding?</p>
				<h2>Find your perfect chain.</h2>
				<p><?php esc_html_e( 'For myself, I want a classic sterling silver chain that fits every day.', 'asherava-jaxxon' ); ?></p>
			</div>
			<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop Silver Chains', 'asherava-jaxxon' ); ?></a>
		</div>
	</section>

	<section class="av-section av-section--cta">
		<div class="av-container av-cta">
			<h2><?php esc_html_e( 'Join the launch list', 'asherava-jaxxon' ); ?></h2>
			<p><?php esc_html_e( 'Get first access to new chain drops, sizing guides, and launch offers.', 'asherava-jaxxon' ); ?></p>
			<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop Chains', 'asherava-jaxxon' ); ?></a>
		</div>
	</section>
</main>

<?php
get_footer();
