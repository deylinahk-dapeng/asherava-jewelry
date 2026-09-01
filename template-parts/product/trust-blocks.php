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
		'text'  => __( 'Clear material details and sterling silver positioning are shown plainly so buyers can check what they are getting.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' ),
	),
	array(
		'title' => __( 'Fit & Returns', 'asherava-jaxxon' ),
		'text'  => __( 'Length guidance and a practical 30-day return window help first-time buyers check fit, feel, and finish.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'mens-rope-chain-size-guide', 'rope-chain-size-guide', 'size-guide' ), '/size-guide/' ),
	),
	array(
		'title' => __( 'Fair Direct Pricing', 'asherava-jaxxon' ),
		'text'  => __( 'Built for buyers who want a bright, durable silver chain without inflated mall-jewelry markups.', 'asherava-jaxxon' ),
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
