<?php
/**
 * Related blog posts.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$related = asherava_get_related_posts( $post_id, 3 );

if ( empty( $related ) ) {
	return;
}
?>

<section class="av-blog-related av-section av-section--muted">
	<div class="av-container">
		<div class="av-section__head">
			<h2><?php esc_html_e( 'Related articles', 'asherava-jaxxon' ); ?></h2>
		</div>
		<div class="av-blog-grid av-blog-grid--compact">
			<?php foreach ( $related as $rel_post ) : ?>
				<?php
				get_template_part(
					'template-parts/blog/card',
					null,
					array( 'post' => $rel_post )
				);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
