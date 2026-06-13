<?php
/**
 * Post footer shop CTA strip.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id  = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$shop_url = asherava_get_post_shop_cta_url( $post_id );
$shop_url = esc_url( $shop_url );
?>

<section class="av-blog-shop-cta">
	<div class="av-container av-blog-shop-cta__inner">
		<div>
			<p class="av-eyebrow"><?php esc_html_e( 'Shop the guide', 'asherava-jaxxon' ); ?></p>
			<h2><?php esc_html_e( 'Sterling Silver Rope Chains', 'asherava-jaxxon' ); ?></h2>
			<p><?php esc_html_e( 'Solid 925 silver, Italian craft — find your perfect length and width.', 'asherava-jaxxon' ); ?></p>
		</div>
		<a class="av-btn av-btn--primary" href="<?php echo $shop_url; ?>">
			<?php esc_html_e( 'Shop chains', 'asherava-jaxxon' ); ?>
		</a>
	</div>
</section>
