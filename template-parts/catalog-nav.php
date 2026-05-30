<?php
/**
 * Desktop + mobile catalog navigation (LZJ-style).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$cart_url   = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$chains     = asherava_get_chain_catalog();
$bracelets  = asherava_get_category_url( 'bracelets' );
$pendants   = asherava_get_category_url( 'pendants' );
$home_url   = home_url( '/' );
$cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
?>

<nav class="av-catalog-nav" aria-label="<?php esc_attr_e( 'Primary catalog', 'asherava-jaxxon' ); ?>">
	<div class="av-catalog-nav__bar">
		<button class="av-catalog-nav__toggle" type="button" aria-expanded="false" aria-controls="av-catalog-drawer">
			<span class="av-catalog-nav__toggle-icon" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'asherava-jaxxon' ); ?></span>
		</button>

		<div class="av-header-utilities">
			<a class="av-header-utilities__link av-header-utilities__search" href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php esc_attr_e( 'Search', 'asherava-jaxxon' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.75"></circle>
					<path d="M20 20L16.5 16.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"></path>
				</svg>
			</a>
			<a class="av-header-utilities__link av-header-utilities__cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'asherava-jaxxon' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<path d="M6 6h15l-1.5 9h-12L6 6Z" stroke="currentColor" stroke-width="1.75" stroke-linejoin="round"></path>
					<path d="M6 6 5 3H2" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"></path>
					<circle cx="9.5" cy="19.5" r="1.25" fill="currentColor"></circle>
					<circle cx="17.5" cy="19.5" r="1.25" fill="currentColor"></circle>
				</svg>
				<?php if ( $cart_count > 0 ) : ?>
					<span class="av-header-utilities__count"><?php echo esc_html( (string) $cart_count ); ?></span>
				<?php endif; ?>
			</a>
		</div>

		<ul class="av-catalog-nav__links av-catalog-nav__links--desktop">
			<li><a href="<?php echo esc_url( $home_url ); ?>"><?php esc_html_e( 'Home', 'asherava-jaxxon' ); ?></a></li>
			<li class="av-catalog-nav__shop">
				<button class="av-catalog-nav__shop-trigger" type="button" aria-expanded="false" aria-controls="av-shop-mega">
					<?php esc_html_e( 'Shop', 'asherava-jaxxon' ); ?>
					<span aria-hidden="true">▾</span>
				</button>
				<div class="av-shop-mega" id="av-shop-mega" hidden>
					<div class="av-shop-mega__inner av-container">
						<div class="av-shop-mega__col">
							<p class="av-shop-mega__label"><?php esc_html_e( 'Chain Styles', 'asherava-jaxxon' ); ?></p>
							<ul class="av-shop-mega__grid">
								<?php foreach ( $chains as $item ) : ?>
									<li><a href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="av-shop-mega__col av-shop-mega__col--side">
							<p class="av-shop-mega__label"><?php esc_html_e( 'More', 'asherava-jaxxon' ); ?></p>
							<ul class="av-shop-mega__side">
								<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
								<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
								<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All', 'asherava-jaxxon' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</li>
			<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
			<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
		</ul>
	</div>

	<div class="av-catalog-drawer" id="av-catalog-drawer" hidden>
		<div class="av-catalog-drawer__panel">
			<div class="av-catalog-drawer__head">
				<strong><?php esc_html_e( 'Menu', 'asherava-jaxxon' ); ?></strong>
				<button class="av-catalog-drawer__close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'asherava-jaxxon' ); ?>">×</button>
			</div>
			<ul class="av-catalog-drawer__list">
				<li><a href="<?php echo esc_url( $home_url ); ?>"><?php esc_html_e( 'Home', 'asherava-jaxxon' ); ?></a></li>
				<li class="av-catalog-drawer__accordion">
					<button class="av-catalog-drawer__accordion-trigger" type="button" aria-expanded="false">
						<?php esc_html_e( 'Shop', 'asherava-jaxxon' ); ?>
						<span aria-hidden="true">+</span>
					</button>
					<ul class="av-catalog-drawer__sub" hidden>
						<?php foreach ( $chains as $item ) : ?>
							<li><a href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
						<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
						<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All', 'asherava-jaxxon' ); ?></a></li>
					</ul>
				</li>
				<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
				<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
			</ul>
		</div>
	</div>
</nav>
