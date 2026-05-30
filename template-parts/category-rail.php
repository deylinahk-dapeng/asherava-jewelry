<?php
/**
 * Horizontal category filter rail (shop + archive pages).
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$current  = is_product_category() ? get_queried_object()->slug : '';
$items    = asherava_get_product_catalog();
?>

<div class="av-category-rail" data-av-category-rail>
	<div class="av-category-rail__track">
		<a class="av-category-chip<?php echo $current ? '' : ' is-active'; ?>" href="<?php echo esc_url( $shop_url ); ?>">
			<?php esc_html_e( 'All', 'asherava-jaxxon' ); ?>
		</a>
		<?php foreach ( $items as $item ) : ?>
			<a
				class="av-category-chip<?php echo ( $current === $item['slug'] ) ? ' is-active' : ''; ?>"
				href="<?php echo esc_url( asherava_get_category_url( $item['slug'] ) ); ?>"
			>
				<?php echo esc_html( $item['title'] ); ?>
			</a>
		<?php endforeach; ?>
	</div>
</div>
