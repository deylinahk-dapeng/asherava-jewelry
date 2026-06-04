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
		'title' => __( 'Real Silver', 'asherava-jaxxon' ),
		'text'  => __( 'Experience the elegance and beauty of our precious metal silver jewelry, crafted to perfection for those who appreciate the finer things in life.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'silver-chain-guides', 'guides' ), '/silver-chain-guides/' ),
	),
	array(
		'title' => __( 'Jump Rings Closed', 'asherava-jaxxon' ),
		'text'  => __( 'Protect your jewelry pieces from accidental opening with strong closed jump rings.', 'asherava-jaxxon' ),
		'url'   => asherava_resolve_menu_url( array( 'faq' ), '/faq/' ),
	),
	array(
		'title' => __( '1,000+ 5 Star Reviews', 'asherava-jaxxon' ),
		'text'  => __( 'Discover why thousands of customers trust Asherava for men\'s sterling silver chains.', 'asherava-jaxxon' ),
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
