<?php
/**
 * Blog category filter chips.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_url       = asherava_get_blog_index_url();
$posts_page_id  = (int) get_option( 'page_for_posts' );
$is_blog_index  = is_home() || is_page( 'blog' ) || ( $posts_page_id && is_page( $posts_page_id ) );
$current        = is_category() ? get_queried_object()->slug : '';
$terms    = get_categories(
	array(
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<div class="av-blog-rail av-category-rail" data-av-category-rail>
	<div class="av-container">
		<div class="av-category-rail__track">
			<a class="av-category-chip<?php echo $is_blog_index && ! $current ? ' is-active' : ''; ?>" href="<?php echo esc_url( $blog_url ); ?>">
				<?php esc_html_e( 'All', 'asherava-jaxxon' ); ?>
			</a>
			<?php foreach ( $terms as $term ) : ?>
				<?php if ( 'uncategorized' === $term->slug ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<a
					class="av-category-chip<?php echo ( $current === $term->slug ) ? ' is-active' : ''; ?>"
					href="<?php echo esc_url( get_category_link( $term->term_id ) ); ?>"
				>
					<?php echo esc_html( $term->name ); ?>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>
