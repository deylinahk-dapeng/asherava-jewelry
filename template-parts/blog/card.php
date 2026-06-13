<?php
/**
 * Blog post card.
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
$excerpt   = has_excerpt( $post_obj ) ? get_the_excerpt( $post_obj ) : wp_trim_words( wp_strip_all_tags( $post_obj->post_content ), 24, '…' );
$category  = asherava_get_post_primary_category( $post_obj->ID );
$thumb     = get_the_post_thumbnail( $post_obj, 'large', array( 'loading' => 'lazy' ) );
?>

<article class="av-blog-card">
	<a class="av-blog-card__media" href="<?php echo esc_url( $permalink ); ?>">
		<?php if ( $thumb ) : ?>
			<?php echo $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<div class="av-blog-card__placeholder" aria-hidden="true"></div>
		<?php endif; ?>
	</a>
	<div class="av-blog-card__body">
		<?php if ( $category ) : ?>
			<a class="av-blog-card__category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php endif; ?>
		<h2 class="av-blog-card__title">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
		</h2>
		<p class="av-blog-card__excerpt"><?php echo esc_html( $excerpt ); ?></p>
		<p class="av-blog-card__meta">
			<time datetime="<?php echo esc_attr( get_the_date( 'c', $post_obj ) ); ?>"><?php echo esc_html( get_the_date( '', $post_obj ) ); ?></time>
			<span aria-hidden="true">·</span>
			<span><?php echo esc_html( asherava_get_reading_time( $post_obj->ID ) ); ?></span>
		</p>
		<a class="av-link av-blog-card__read" href="<?php echo esc_url( $permalink ); ?>">
			<?php esc_html_e( 'Read article', 'asherava-jaxxon' ); ?>
		</a>
	</div>
</article>
