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
$message   = __( '925 Sterling Silver Chains', 'asherava-jaxxon' ) . ' &bull; ' . __( '10% Welcome Offer', 'asherava-jaxxon' ) . ' &bull; ' . __( 'Fair Direct Pricing', 'asherava-jaxxon' ) . ' &bull; ' . __( '30-Day Returns', 'asherava-jaxxon' );
?>
<div class="<?php echo esc_attr( $class ); ?>" aria-label="<?php esc_attr_e( 'Promotions', 'asherava-jaxxon' ); ?>">
	<p class="av-announcement__static"><?php echo wp_kses_post( $message ); ?></p>
	<div class="av-announcement__track">
		<span><?php echo wp_kses_post( $message ); ?></span>
		<span><?php echo wp_kses_post( $message ); ?></span>
		<span><?php echo wp_kses_post( $message ); ?></span>
		<span><?php echo wp_kses_post( $message ); ?></span>
	</div>
</div>
