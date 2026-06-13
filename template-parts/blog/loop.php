<?php
/**
 * Blog posts grid + pagination.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_featured = ! empty( $args['show_featured'] );
$featured      = $show_featured ? asherava_get_blog_featured_post() : null;
$featured_id   = $featured instanceof WP_Post ? $featured->ID : 0;

if ( $featured && $show_featured ) {
	get_template_part(
		'template-parts/blog/featured',
		null,
		array( 'post' => $featured )
	);
}
?>

<section class="av-blog-list av-section">
	<div class="av-container">
		<?php if ( have_posts() ) : ?>
			<div class="av-blog-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					if ( $featured_id && (int) get_the_ID() === $featured_id && $show_featured ) {
						continue;
					}
					get_template_part(
						'template-parts/blog/card',
						null,
						array( 'post' => get_post() )
					);
				endwhile;
				?>
			</div>

			<nav class="av-blog-pagination" aria-label="<?php esc_attr_e( 'Blog pages', 'asherava-jaxxon' ); ?>">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => __( '← Previous', 'asherava-jaxxon' ),
						'next_text' => __( 'Next →', 'asherava-jaxxon' ),
					)
				);
				?>
			</nav>
		<?php else : ?>
			<div class="av-blog-empty">
				<h2><?php esc_html_e( 'Articles coming soon', 'asherava-jaxxon' ); ?></h2>
				<p><?php esc_html_e( 'We’re preparing sterling silver chain guides on sizing, care, and style. Check back shortly.', 'asherava-jaxxon' ); ?></p>
				<?php
				$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
				?>
				<a class="av-btn av-btn--primary" href="<?php echo esc_url( $shop_url ); ?>">
					<?php esc_html_e( 'Shop chains', 'asherava-jaxxon' ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section>
