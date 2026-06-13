<?php
/**
 * Blog category archive.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$term     = get_queried_object();
$subtitle = $term instanceof WP_Term && $term->description ? $term->description : __( 'Articles in this category.', 'asherava-jaxxon' );
?>

<main id="primary" class="av-blog av-blog-category">
	<?php
	get_template_part(
		'template-parts/blog/hero',
		null,
		array(
			'title'    => single_cat_title( '', false ),
			'subtitle' => $subtitle,
		)
	);
	get_template_part( 'template-parts/blog/category', 'rail' );
	get_template_part(
		'template-parts/blog/loop',
		null,
		array( 'show_featured' => false )
	);
	get_template_part( 'template-parts/blog/newsletter', 'cta' );
	?>
</main>

<?php
get_footer();
