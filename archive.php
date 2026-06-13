<?php
/**
 * Generic archives (fallback for blog).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="av-blog av-blog-archive">
	<?php
	get_template_part(
		'template-parts/blog/hero',
		null,
		array(
			'title'    => get_the_archive_title(),
			'subtitle' => get_the_archive_description(),
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
