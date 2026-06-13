<?php
/**
 * Blog archive hero band.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title    = isset( $args['title'] ) ? $args['title'] : __( 'Blog', 'asherava-jaxxon' );
$subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : __( 'Expert tips on men’s sterling silver chains — sizing, care, and style.', 'asherava-jaxxon' );
?>
<section class="av-blog-hero">
	<div class="av-container av-blog-hero__inner">
		<p class="av-eyebrow av-blog-hero__eyebrow"><?php esc_html_e( 'Sterling Silver Guides', 'asherava-jaxxon' ); ?></p>
		<h1 class="av-blog-hero__title"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $subtitle ) : ?>
			<p class="av-blog-hero__subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>
	</div>
</section>
