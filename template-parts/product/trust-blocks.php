<?php
/**
 * PDP trust icon row (LZJ-style).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blocks = array(
	array(
		'title' => __( '925 Sterling Silver', 'asherava-jaxxon' ),
		'text'  => __( 'Focused on solid sterling silver chains with clear material details and a clean everyday shine.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' ),
	),
	array(
		'title' => __( 'Fair Direct Pricing', 'asherava-jaxxon' ),
		'text'  => __( 'We sell direct and keep pricing built around long-term value instead of marketplace fees.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'faq' ), '/faq/' ),
	),
	array(
		'title' => __( 'Everyday Wear', 'asherava-jaxxon' ),
		'text'  => __( 'Designed around wearable widths, practical lengths, and simple care for daily rotation.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'about-us', 'about' ), '/about-us/' ),
	),
);
?>
<ul class="av-pdp__trust">
	<?php foreach ( $blocks as $block ) : ?>
		<li class="av-pdp__trust-item">
			<h3 class="av-pdp__trust-title av-type-value"><?php echo esc_html( $block['title'] ); ?></h3>
			<p class="av-pdp__trust-text"><?php echo esc_html( $block['text'] ); ?></p>
			<?php if ( ! empty( $block['url'] ) ) : ?>
				<a class="av-pdp__trust-link" href="<?php echo esc_url( $block['url'] ); ?>"><?php esc_html_e( 'Learn More', 'asherava-jaxxon' ); ?></a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
