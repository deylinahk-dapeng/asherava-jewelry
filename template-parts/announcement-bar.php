<?php
/**
 * Promo / announcement strip.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$placement = isset( $args['placement'] ) ? $args['placement'] : 'top';
$class     = 'av-announcement av-announcement--' . sanitize_html_class( $placement );
?>
<div class="<?php echo esc_attr( $class ); ?>" aria-label="<?php esc_attr_e( 'Promotions', 'asherava-jaxxon' ); ?>">
	<p class="av-announcement__static"><?php esc_html_e( "Luxury Men's Chain Jewelry", 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'Free US Shipping', 'asherava-jaxxon' ); ?></p>
	<div class="av-announcement__track">
		<span><?php esc_html_e( 'UP TO 47% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING ON ALL US ORDERS', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( 'UP TO 47% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING ON ALL US ORDERS', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( 'UP TO 47% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING ON ALL US ORDERS', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( 'UP TO 47% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING ON ALL US ORDERS', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
	</div>
</div>
