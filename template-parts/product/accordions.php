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
	$material = __( 'Italian-made 925 sterling silver rope chain with a diamond-cut finish for a brighter surface reflection. Available lengths and approximate gram weights are listed with each product when configured.', 'asherava-jaxxon' );
}

$fit = get_post_meta( get_the_ID(), '_asherava_fit_detail', true );
if ( ! $fit ) {
	$fit = __( '3mm is a balanced daily-wear width: visible enough to stand on its own, restrained enough for a clean everyday look, and easy to layer with pendants or finer chains. For most men, 22″–24″ is the safest everyday range; 18″–20″ wears closer, while 26″+ sits lower.', 'asherava-jaxxon' );
}

$care = get_post_meta( get_the_ID(), '_asherava_care_detail', true );
if ( ! $care ) {
	$care_url = asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' );
	$care     = __( 'Sterling silver naturally responds to moisture, sweat, and chemicals. Keep the chain dry when possible, avoid perfumes and chlorine, store it separately in a soft pouch, and use a silver polishing cloth when the shine needs a refresh.', 'asherava-jaxxon' );
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
