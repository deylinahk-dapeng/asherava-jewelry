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
	<p class="av-announcement__static"><?php esc_html_e( '10% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'Free Shipping in North America', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-Day Returns', 'asherava-jaxxon' ); ?></p>
	<div class="av-announcement__track">
		<span><?php esc_html_e( '10% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING IN NORTH AMERICA', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( '10% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING IN NORTH AMERICA', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( '10% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING IN NORTH AMERICA', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
		<span><?php esc_html_e( '10% OFF', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( 'FREE SHIPPING IN NORTH AMERICA', 'asherava-jaxxon' ); ?> &bull; <?php esc_html_e( '30-DAY RETURNS', 'asherava-jaxxon' ); ?></span>
	</div>
</div>
