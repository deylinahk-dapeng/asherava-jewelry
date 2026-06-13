<?php
/**
 * Single blog post.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	$category = asherava_get_post_primary_category();
	?>
<main id="primary" class="av-blog av-blog-single">
	<?php get_template_part( 'template-parts/blog/breadcrumbs' ); ?>

	<article <?php post_class( 'av-blog-article' ); ?>>
		<header class="av-blog-article__header">
			<div class="av-container av-blog-article__header-inner">
				<?php if ( $category ) : ?>
					<a class="av-blog-card__category" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endif; ?>
				<h1 class="av-blog-article__title"><?php the_title(); ?></h1>
				<p class="av-blog-article__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<span aria-hidden="true">·</span>
					<span><?php echo esc_html( asherava_get_reading_time() ); ?></span>
				</p>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="av-blog-article__featured">
				<div class="av-container">
					<figure class="av-blog-article__figure">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
					</figure>
				</div>
			</div>
		<?php endif; ?>

		<div class="av-container">
			<div class="av-blog-article__content entry-content">
				<?php the_content(); ?>
			</div>
		</div>
	</article>

	<?php
	get_template_part(
		'template-parts/blog/shop',
		'cta',
		array( 'post_id' => get_the_ID() )
	);
	get_template_part(
		'template-parts/blog/related',
		null,
		array( 'post_id' => get_the_ID() )
	);
	?>
</main>
	<?php
endwhile;

get_footer();
