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
$show_accessory_nav = (bool) apply_filters( 'asherava_show_accessory_nav', get_option( 'asherava_show_accessory_nav', false ) );
$home_url   = home_url( '/' );
$cart_count   = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$logo_url     = get_stylesheet_directory_uri() . '/assets/images/asherava-logo-white.svg';
$is_home             = is_front_page();
$is_shop_page        = function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() );
$drawer_primary_extra = function_exists( 'asherava_get_drawer_primary_links' ) ? asherava_get_drawer_primary_links() : array();
$drawer_secondary     = function_exists( 'asherava_get_drawer_secondary_links' ) ? asherava_get_drawer_secondary_links() : array();
?>

<nav class="av-catalog-nav" aria-label="<?php esc_attr_e( 'Primary catalog', 'asherava-jaxxon' ); ?>">
	<div class="av-catalog-nav__bar">
		<button class="av-catalog-nav__toggle" type="button" aria-expanded="false" aria-controls="av-catalog-drawer">
			<?php echo asherava_icon( 'menu' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'asherava-jaxxon' ); ?></span>
		</button>

		<div class="av-header-utilities">
			<a class="av-header-utilities__link av-header-utilities__search" href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php esc_attr_e( 'Search', 'asherava-jaxxon' ); ?>">
				<?php echo asherava_icon( 'search' ); ?>
			</a>
			<a class="av-header-utilities__link av-header-utilities__cart" href="<?php echo esc_url( $cart_url ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'asherava-jaxxon' ); ?>">
				<?php echo asherava_icon( 'bag' ); ?>
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
					<?php echo asherava_icon( 'chevron-down', 'av-catalog-nav__shop-icon' ); ?>
				</button>
			</li>
			<?php if ( $show_accessory_nav ) : ?>
				<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
				<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
			<?php endif; ?>
		</ul>
	</div>

	<div class="av-shop-mega" id="av-shop-mega" hidden>
		<div class="av-shop-mega__inner av-container">
			<div class="av-shop-mega__col">
				<p class="av-shop-mega__label"><?php esc_html_e( 'Rope Chain Launch', 'asherava-jaxxon' ); ?></p>
				<ul class="av-shop-mega__grid">
					<?php foreach ( $chains as $item ) : ?>
						<li><a href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="av-shop-mega__col av-shop-mega__col--side">
				<p class="av-shop-mega__label"><?php esc_html_e( 'Launch Focus', 'asherava-jaxxon' ); ?></p>
				<ul class="av-shop-mega__side">
					<li><a href="<?php echo esc_url( asherava_get_category_url( 'rope-chains' ) ); ?>"><?php esc_html_e( 'Shop All Rope Chains', 'asherava-jaxxon' ); ?></a></li>
					<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'All Products', 'asherava-jaxxon' ); ?></a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="av-catalog-drawer" id="av-catalog-drawer" hidden>
		<div class="av-catalog-drawer__panel">
			<div class="av-catalog-drawer__head">
				<a class="av-catalog-drawer__brand" href="<?php echo esc_url( $home_url ); ?>" rel="home">
					<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="160" height="24" decoding="async" />
				</a>
				<button class="av-catalog-drawer__close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'asherava-jaxxon' ); ?>">
					<?php echo asherava_icon( 'close' ); ?>
				</button>
			</div>
			<ul class="av-catalog-drawer__list">
				<li><a class="<?php echo $is_home ? 'is-current' : ''; ?>" href="<?php echo esc_url( $home_url ); ?>"><?php esc_html_e( 'Home', 'asherava-jaxxon' ); ?></a></li>
				<li class="av-catalog-drawer__accordion<?php echo $is_shop_page ? ' is-current' : ''; ?>">
					<button class="av-catalog-drawer__accordion-trigger" type="button" aria-expanded="false">
						<?php esc_html_e( 'Shop', 'asherava-jaxxon' ); ?>
						<?php echo asherava_icon( 'chevron-down', 'av-catalog-drawer__chevron' ); ?>
					</button>
					<ul class="av-catalog-drawer__sub" hidden>
						<?php foreach ( $chains as $item ) : ?>
							<li><a href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
						<?php endforeach; ?>
						<?php if ( $show_accessory_nav ) : ?>
							<li><a href="<?php echo esc_url( $bracelets ); ?>"><?php esc_html_e( 'Bracelets', 'asherava-jaxxon' ); ?></a></li>
							<li><a href="<?php echo esc_url( $pendants ); ?>"><?php esc_html_e( 'Pendants', 'asherava-jaxxon' ); ?></a></li>
						<?php endif; ?>
						<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop All', 'asherava-jaxxon' ); ?></a></li>
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
			<?php if ( ! empty( $drawer_secondary ) ) : ?>
				<ul class="av-catalog-drawer__list av-catalog-drawer__list--secondary">
					<?php foreach ( $drawer_secondary as $link ) : ?>
						<li>
							<a href="<?php echo esc_url( $link['url'] ); ?>"<?php echo ! empty( $link['icon'] ) ? ' class="av-catalog-drawer__link--login"' : ''; ?>>
								<?php if ( ! empty( $link['icon'] ) && 'login' === $link['icon'] ) : ?>
									<?php echo asherava_icon( 'user', 'av-catalog-drawer__icon' ); ?>
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
