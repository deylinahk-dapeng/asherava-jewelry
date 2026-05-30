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

$placeholders = array(
	array( 'name' => 'Cuban Link Chain 10mm', 'price' => '$149', 'badge' => 'Best Seller' ),
	array( 'name' => 'Cuban Link Chain 8mm', 'price' => '$119', 'badge' => 'Best Seller' ),
	array( 'name' => 'Cuban Link Chain 5mm', 'price' => '$89', 'badge' => '' ),
	array( 'name' => 'Rope Chain 5mm', 'price' => '$79', 'badge' => '' ),
	array( 'name' => 'Cuban Link Bracelet 8mm', 'price' => '$99', 'badge' => 'New' ),
	array( 'name' => 'Cuban Link Chain 3mm', 'price' => '$69', 'badge' => '' ),
);
?>

<main id="primary" class="av-home">
	<section class="av-hero">
		<div class="av-hero__media" style="background-image:url('https://images.unsplash.com/photo-1617032216428-9e23d1bb0ca5?auto=format&fit=crop&w=1800&q=80');"></div>
		<div class="av-hero__overlay"></div>
		<div class="av-container av-hero__content">
			<p class="av-eyebrow">Most Trusted Men's Chains</p>
			<h1 class="av-hero__title">Bold Chains.<br>Built to Last.</h1>
			<p class="av-hero__subtitle">Premium Cuban, rope, and tennis chains crafted for everyday confidence.</p>
			<div class="av-hero__actions">
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">Shop Chains</a>
				<a class="av-btn av-btn--ghost" href="<?php echo esc_url( $shop_url ); ?>">Best Sellers</a>
			</div>
		</div>
	</section>

	<section class="av-trust">
		<div class="av-container av-trust__grid">
			<div class="av-trust__item">
				<strong>100,000+</strong>
				<span>5-Star Reviews</span>
			</div>
			<div class="av-trust__item">
				<strong>Free Shipping</strong>
				<span>On all US orders</span>
			</div>
			<div class="av-trust__item">
				<strong>30-Day Returns</strong>
				<span>Hassle-free exchanges</span>
			</div>
			<div class="av-trust__item">
				<strong>24/7 Support</strong>
				<span>Dedicated customer care</span>
			</div>
		</div>
	</section>

	<section class="av-section av-section--dark">
		<div class="av-container">
			<div class="av-section__head">
				<h2>Flash Sale</h2>
				<p>This week's top deals are live — shop before they're gone.</p>
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
						<?php foreach ( $placeholders as $item ) : ?>
							<a class="av-product-card" href="<?php echo esc_url( $shop_url ); ?>">
								<div class="av-product-card__image av-product-card__image--placeholder"></div>
								<?php if ( ! empty( $item['badge'] ) ) : ?>
									<span class="av-badge"><?php echo esc_html( $item['badge'] ); ?></span>
								<?php endif; ?>
								<h3><?php echo esc_html( $item['name'] ); ?></h3>
								<p class="av-product-card__price"><?php echo esc_html( $item['price'] ); ?></p>
							</a>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="av-section">
		<div class="av-container">
			<div class="av-section__head">
				<h2>Best Sellers</h2>
				<a class="av-link" href="<?php echo esc_url( $shop_url ); ?>">View all</a>
			</div>
			<div class="av-product-grid av-product-grid--light">
				<?php foreach ( array_slice( $placeholders, 0, 4 ) as $item ) : ?>
					<a class="av-product-card av-product-card--light" href="<?php echo esc_url( $shop_url ); ?>">
						<div class="av-product-card__image av-product-card__image--placeholder"></div>
						<h3><?php echo esc_html( $item['name'] ); ?></h3>
						<p class="av-product-card__price"><?php echo esc_html( $item['price'] ); ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="av-section av-section--muted">
		<div class="av-container">
			<div class="av-section__head av-section__head--center">
				<div>
					<h2>Most Popular Collections</h2>
					<p>Shop our best-selling chain styles — rope, Cuban, Franco, curb, and more.</p>
				</div>
				<a class="av-link" href="<?php echo esc_url( $shop_url ); ?>">Shop all chains</a>
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

	<section class="av-section av-quiz">
		<div class="av-container av-quiz__inner">
			<div>
				<p class="av-eyebrow">Need Help Deciding?</p>
				<h2>Find your perfect chain.</h2>
				<p>For myself, I'm looking for a classic chain in gold.</p>
			</div>
			<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">Shop Gold Chains</a>
		</div>
	</section>

	<section class="av-section av-section--cta">
		<div class="av-container av-cta">
			<h2>2026 Top Trending</h2>
			<p>Discover this year's most-wanted chains and sets.</p>
			<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">Shop Trending</a>
		</div>
	</section>
</main>

<?php
get_footer();
