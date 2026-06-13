<?php
/**
 * Blog single breadcrumbs.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$blog_url = asherava_get_blog_index_url();
$title    = get_the_title();
?>

<nav class="av-blog-breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'asherava-jaxxon' ); ?>">
	<div class="av-container">
		<ol class="av-blog-breadcrumbs__list">
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'asherava-jaxxon' ); ?></a></li>
			<li><a href="<?php echo esc_url( $blog_url ); ?>"><?php esc_html_e( 'Blog', 'asherava-jaxxon' ); ?></a></li>
			<li aria-current="page"><?php echo esc_html( $title ); ?></li>
		</ol>
	</div>
</nav>
