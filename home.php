<?php
/**
 * Blog posts index (page_for_posts).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
