<?php
/**
 * Page template: slug `blog` — blog index shell (fallback when Reading settings differ).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$wp_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => (int) get_option( 'posts_per_page', 10 ),
		'paged'          => $paged,
	)
);

get_header();
?>

<main id="primary" class="av-blog av-blog-index">
	<?php
	get_template_part(
		'template-parts/blog/hero',
		null,
		array(
			'title'    => __( 'Blog', 'asherava-jaxxon' ),
			'subtitle' => __( 'Expert tips on men’s sterling silver chains — sizing, care, and style.', 'asherava-jaxxon' ),
		)
	);
	get_template_part( 'template-parts/blog/category', 'rail' );
	get_template_part(
		'template-parts/blog/loop',
		null,
		array( 'show_featured' => true )
	);
	get_template_part( 'template-parts/blog/newsletter', 'cta' );
	?>
</main>

<?php
get_footer();
wp_reset_postdata();
