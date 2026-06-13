<?php
/**
 * Template for policy, FAQ, and contact pages.
 *
 * Template Name: Policy Page
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="av-policy-page-wrap">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'av-policy-page' ); ?>>
			<header class="av-policy-page__header">
				<h1 class="av-policy-page__title"><?php the_title(); ?></h1>
			</header>
			<div class="av-policy-page__content entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</div>

<?php
get_footer();
