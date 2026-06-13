<?php
/**
 * Featured blog post (index hero card).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_obj = isset( $args['post'] ) ? $args['post'] : null;
if ( ! $post_obj instanceof WP_Post ) {
	return;
}

$permalink = get_permalink( $post_obj );
$title     = get_the_title( $post_obj );
$excerpt   = has_excerpt( $post_obj ) ? get_the_excerpt( $post_obj ) : wp_trim_words( wp_strip_all_tags( $post_obj->post_content ), 32, '…' );
$category  = asherava_get_post_primary_category( $post_obj->ID );
$thumb     = get_the_post_thumbnail( $post_obj, 'large', array( 'loading' => 'eager' ) );
?>

<section class="av-blog-featured">
	<div class="av-container">
		<div class="av-blog-featured__card">
			<a class="av-blog-featured__media" href="<?php echo esc_url( $permalink ); ?>">
				<?php if ( $thumb ) : ?>
					<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<div class="av-blog-card__placeholder" aria-hidden="true"></div>
				<?php endif; ?>
			</a>
			<div class="av-blog-featured__body">
				<p class="av-blog-featured__label"><?php esc_html_e( 'Featured', 'asherava-jaxxon' ); ?></p>
				<?php if ( $category ) : ?>
					<a class="av-blog-card__category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endif; ?>
				<h2 class="av-blog-featured__title">
					<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
				</h2>
				<p class="av-blog-featured__excerpt"><?php echo esc_html( $excerpt ); ?></p>
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $permalink ); ?>">
					<?php esc_html_e( 'Read now', 'asherava-jaxxon' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
