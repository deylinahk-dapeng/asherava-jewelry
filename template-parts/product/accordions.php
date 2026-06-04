<?php
/**
 * PDP Material / Care accordions.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$material = get_post_meta( get_the_ID(), '_asherava_material_detail', true );
if ( ! $material ) {
	$material = __( 'Authentic Sterling Silver 925', 'asherava-jaxxon' );
}

$care = get_post_meta( get_the_ID(), '_asherava_care_detail', true );
if ( ! $care ) {
	$care_url = asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' );
	$care     = __( 'How to care for sterling silver 925', 'asherava-jaxxon' );
	if ( $care_url ) {
		$care .= ' <a href="' . esc_url( $care_url ) . '">' . esc_html__( 'Learn More', 'asherava-jaxxon' ) . '</a>';
	}
}
?>
<div class="av-pdp__accordions">
	<details class="av-pdp__accordion" open>
		<summary><?php esc_html_e( 'Material', 'asherava-jaxxon' ); ?></summary>
		<div class="av-pdp__accordion-body">
			<?php echo wp_kses_post( wpautop( $material ) ); ?>
		</div>
	</details>
	<details class="av-pdp__accordion">
		<summary><?php esc_html_e( 'Care', 'asherava-jaxxon' ); ?></summary>
		<div class="av-pdp__accordion-body">
			<?php echo wp_kses_post( wpautop( $care ) ); ?>
		</div>
	</details>
</div>
