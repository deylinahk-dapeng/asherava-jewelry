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
$cart_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$account_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$search_url   = home_url( '/?s=&post_type=product' );
$chains     = asherava_get_chain_catalog();
$bracelets  = asherava_get_category_url( 'bracelets' );
$pendants   = asherava_get_category_url( 'pendants' );
$blog_url   = asherava_get_blog_url();
$show_blog  = function_exists( 'asherava_should_show_blog_nav' ) ? asherava_should_show_blog_nav() : false;
$show_accessories = (bool) apply_filters( 'asherava_show_accessory_nav', get_option( 'asherava_show_accessory_nav', false ) );
$about_url  = asherava_resolve_menu_url( array( 'about-us', 'about' ), '/about-us/' );
$home_url   = home_url( '/' );
$cart_count   = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$is_home             = is_front_page();
$is_shop_page        = function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() );
$drawer_primary_extra = function_exists( 'asherava_get_drawer_primary_links' ) ? asherava_get_drawer_primary_links() : array();
$drawer_secondary     = function_exists( 'asherava_get_drawer_secondary_links' ) ? asherava_get_drawer_secondary_links() : array();
?>

<nav class="av-catalog-nav" aria-label="<?php esc_attr_e( 'Primary catalog', 'asherava-jaxxon' ); ?>">
	<div class="av-catalog-nav__bar">
		<button class="av-catalog-nav__toggle" type="button" aria-expanded="false" aria-controls="av-catalog-drawer">
			<span class="av-catalog-nav__toggle-icon" aria-hidden="true"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'asherava-jaxxon' ); ?></span>
		</button>

		<div class="av-header-utilities">
			<?php get_template_part( 'template-parts/header', 'locale', array( 'modifier' => 'header' ) ); ?>
			<a class="av-header-utilities__link av-header-utilities__search" href="<?php echo esc_url( $search_url ); ?>" aria-label="<?php esc_attr_e( 'Search', 'asherava-jaxxon' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.75"></circle>
					<path d="M20 20L16.5 16.5" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"></path>
				</svg>
				<span class="av-header-utilities__label"><?php esc_html_e( 'Search', 'asherava-jaxxon' ); ?></span>
			</a>
			<a class="av-header-utilities__link av-header-utilities__account" href="<?php echo esc_url( $account_url ); ?>" aria-label="<?php esc_attr_e( 'Account', 'asherava-jaxxon' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
					<circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.75"></circle>
					<path d="M5 20c0-3.866 3.134-7 7-7s7 3.134 7 7" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"></path>
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
			</li>
			<?php if ( $show_accessories ) : ?>
				<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
				<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
			<?php endif; ?>
			<?php if ( $show_blog ) : ?>
				<li><a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Blog', 'asherava-jaxxon' ); ?></a></li>
			<?php endif; ?>
			<li><a href="<?php echo esc_url( $about_url ); ?>"><?php esc_html_e( 'About Us', 'asherava-jaxxon' ); ?></a></li>
		</ul>
	</div>

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
				<p class="av-shop-mega__label"><?php esc_html_e( 'Launch Focus', 'asherava-jaxxon' ); ?></p>
				<ul class="av-shop-mega__side">
					<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All Rope Chains', 'asherava-jaxxon' ); ?></a></li>
					<?php if ( $show_accessories ) : ?>
						<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
						<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="av-catalog-drawer" id="av-catalog-drawer" hidden>
		<div class="av-catalog-drawer__panel">
			<div class="av-catalog-drawer__head">
				<div class="av-catalog-drawer__brand">
					<?php echo asherava_get_logo_html( 'white' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<button class="av-catalog-drawer__close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'asherava-jaxxon' ); ?>">
					<?php esc_html_e( 'Close', 'asherava-jaxxon' ); ?>
				</button>
			</div>
			<ul class="av-catalog-drawer__list">
				<li><a class="<?php echo $is_home ? 'is-current' : ''; ?>" href="<?php echo esc_url( $home_url ); ?>"><?php esc_html_e( 'Home', 'asherava-jaxxon' ); ?></a></li>
				<li class="av-catalog-drawer__accordion<?php echo $is_shop_page ? ' is-current' : ''; ?>">
					<button class="av-catalog-drawer__accordion-trigger" type="button" aria-expanded="false">
						<?php esc_html_e( 'Shop', 'asherava-jaxxon' ); ?>
						<span class="av-catalog-drawer__chevron" aria-hidden="true"></span>
					</button>
					<ul class="av-catalog-drawer__sub" hidden>
						<?php foreach ( $chains as $item ) : ?>
							<li><a href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
						<?php endforeach; ?>
						<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All Rope Chains', 'asherava-jaxxon' ); ?></a></li>
						<?php if ( $show_accessories ) : ?>
							<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
							<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
						<?php endif; ?>
					</ul>
				</li>
				<?php foreach ( $drawer_primary_extra as $link ) : ?>
					<?php
					$link_classes = array();
					if ( asherava_is_current_menu_link( $link['url'] ) ) {
						$link_classes[] = 'is-current';
					}
					if ( false !== strpos( $link['label'], '&' ) || strlen( $link['label'] ) > 18 ) {
						$link_classes[] = 'av-catalog-drawer__link--long';
					}
					?>
					<li>
						<a class="<?php echo esc_attr( implode( ' ', $link_classes ) ); ?>" href="<?php echo esc_url( $link['url'] ); ?>">
							<?php echo esc_html( $link['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="av-catalog-drawer__locale">
				<p class="av-catalog-drawer__locale-label"><?php esc_html_e( 'Ship to', 'asherava-jaxxon' ); ?></p>
				<?php get_template_part( 'template-parts/header', 'locale', array( 'modifier' => 'drawer' ) ); ?>
			</div>
			<?php if ( ! empty( $drawer_secondary ) ) : ?>
				<ul class="av-catalog-drawer__list av-catalog-drawer__list--secondary">
					<?php foreach ( $drawer_secondary as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo ! empty( $link['icon'] ) ? ' class="av-catalog-drawer__link--login"' : ''; ?>>
								<?php if ( ! empty( $link['icon'] ) && 'login' === $link['icon'] ) : ?>
									<svg class="av-catalog-drawer__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
										<circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.75"></circle>
										<path d="M5 20c0-3.866 3.134-7 7-7s7 3.134 7 7" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"></path>
									</svg>
								<?php endif; ?>
								<span><?php echo esc_html( $link['label'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</nav>
