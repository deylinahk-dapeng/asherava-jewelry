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
	$material = __( '925 sterling silver (92.5% silver). Two finishes: <strong>Plated</strong> — rhodium over 925. <strong>Bare silver</strong> — unplated 925.', 'asherava-jaxxon' );
}

$fit = get_post_meta( get_the_ID(), '_asherava_fit_detail', true );
if ( ! $fit ) {
	$fit = __( '20″ sits near the collarbone. 22″ sits just below it and is the everyday choice for most men. 24″ sits lower on the chest.', 'asherava-jaxxon' );
}

$care = get_post_meta( get_the_ID(), '_asherava_care_detail', true );
if ( ! $care ) {
	$care_url = asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' );
	$care     = __( 'Sterling silver naturally responds to moisture, sweat, and chemicals. Bare silver can oxidize over time; this is normal. Keep the chain dry when possible, avoid perfumes and chlorine, store it separately in a soft pouch, and use a silver polishing cloth when the shine needs a refresh.', 'asherava-jaxxon' );
	if ( $care_url ) {
		$care .= ' <a href="' . esc_url( $care_url ) . '">' . esc_html__( 'Learn More', 'asherava-jaxxon' ) . '</a>';
	}
}
?>
<div class="av-pdp__accordions">
	<details class="av-pdp__accordion">
		<summary><?php esc_html_e( 'Material', 'asherava-jaxxon' ); ?></summary>
		<div class="av-pdp__accordion-body">
			<?php echo wp_kses_post( wpautop( $material ) ); ?>
		</div>
	</details>
	<details class="av-pdp__accordion">
		<summary><?php esc_html_e( 'Fit', 'asherava-jaxxon' ); ?></summary>
		<div class="av-pdp__accordion-body">
			<?php echo wp_kses_post( wpautop( $fit ) ); ?>
		</div>
	</details>
	<details class="av-pdp__accordion">
		<summary><?php esc_html_e( 'Care', 'asherava-jaxxon' ); ?></summary>
		<div class="av-pdp__accordion-body">
			<?php echo wp_kses_post( wpautop( $care ) ); ?>
		</div>
	</details>
</div>
