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
?>

<main id="primary" class="av-home">
	<section class="av-hero">
		<?php asherava_render_announcement_bar( 'hero' ); ?>
		<div class="av-hero__media" style="background-image:url('https://images.unsplash.com/photo-1617032216428-9e23d1bb0ca5?auto=format&fit=crop&w=1800&q=80');"></div>
		<div class="av-hero__overlay"></div>
		<div class="av-container av-hero__content">
			<p class="av-eyebrow">Sterling Silver Chain Studio</p>
			<h1 class="av-hero__title">925 Sterling Silver Chains</h1>
			<p class="av-hero__subtitle">Italian-crafted rope and Cuban chains for everyday wear.</p>
			<div class="av-hero__actions">
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">Shop Chains</a>
				<a class="av-btn av-btn--ghost" href="<?php echo esc_url( $shop_url ); ?>">Best Sellers</a>
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
				<h2>Best Sellers</h2>
				<p>Launch-ready sterling silver rope chains. Product photos can be refined before publishing.</p>
			</div>
			<div class="av-product-grid">
				<?php if ( function_exists( 'wc_get_products' ) ) : ?>
					<?php
					$products = wc_get_products(
						array(
							'limit'   => 6,
							'status'  => 'publish',
							'orderby' => 'date',
							'order'   => 'DESC',
						)
					);
					?>
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
						<div class="av-empty-products">
							<h3><?php esc_html_e( 'Rope chain collection is being prepared.', 'asherava-jaxxon' ); ?></h3>
							<p><?php esc_html_e( 'We are setting up the core 3mm, 1.8mm, 4.5mm, 5.5mm, and 4mm sterling silver rope chains before launch.', 'asherava-jaxxon' ); ?></p>
							<a class="av-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Visit shop', 'asherava-jaxxon' ); ?></a>
						</div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="av-section av-section--muted">
		<div class="av-container">
			<div class="av-section__head av-section__head--center">
				<div>
					<h2>Shop Rope Chains by Width</h2>
					<p>Start with our core sterling silver rope chain lineup: 3mm, 1.8mm, 4.5mm, 5.5mm, and 4mm.</p>
				</div>
				<a class="av-link" href="<?php echo esc_url( asherava_get_category_url( 'rope-chains' ) ); ?>">Shop all rope chains</a>
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

	<section class="av-section av-story">
		<div class="av-container av-story__inner">
			<div>
				<p class="av-eyebrow">About Asherava</p>
				<h2>Built for the long run.</h2>
			</div>
			<div>
				<p>Asherava is named after Asher and Ava, my two children. I want this brand to grow patiently, stay honest, and become something worth leaving behind.</p>
				<p>Our jewelry background gives us access to Italian sterling silver chain supply and finishing support in Panyu, Guangzhou. Instead of building around marketplace fees, we keep the model direct and aim for fair long-term pricing.</p>
			</div>
		</div>
	</section>

	<section class="av-section av-section--cta av-confidence">
		<div class="av-container">
			<div class="av-section__head av-section__head--center">
				<div>
					<h2>Customer Confidence</h2>
					<p>Simple policies and product details for first-time buyers.</p>
				</div>
				<a class="av-link" href="<?php echo esc_url( asherava_resolve_menu_url( array( 'faq' ), '/faq/' ) ); ?>">Read FAQ</a>
			</div>
			<div class="av-confidence__grid">
				<div>
					<strong>Secure Checkout</strong>
					<span>Protected online payment flow.</span>
				</div>
				<div>
					<strong>Shipping</strong>
					<span>US and Canada launch coverage.</span>
				</div>
				<div>
					<strong>Returns</strong>
					<span>30-day return window.</span>
				</div>
				<div>
					<strong>Material</strong>
					<span>925 sterling silver product focus.</span>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
