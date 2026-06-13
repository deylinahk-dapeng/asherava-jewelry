<?php
/**
 * Blog index newsletter / promo CTA.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>

<section class="av-blog-newsletter av-section av-section--cta">
	<div class="av-container av-blog-newsletter__inner">
		<div>
			<p class="av-eyebrow"><?php esc_html_e( 'Newsletter', 'asherava-jaxxon' ); ?></p>
			<h2><?php esc_html_e( 'Get 10% off your first order', 'asherava-jaxxon' ); ?></h2>
			<p><?php esc_html_e( 'Chain sizing tips, new drops, and exclusive offers for men’s silver jewelry.', 'asherava-jaxxon' ); ?></p>
		</div>
		<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">
			<?php esc_html_e( 'Shop with WELCOME10', 'asherava-jaxxon' ); ?>
		</a>
	</div>
</section>
