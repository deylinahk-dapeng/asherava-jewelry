<?php
/**
 * Blog helpers, categories, and layout utilities.
 *
 * @package Asherava_Jaxxon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default blog category definitions.
 *
 * @return array<int, array{slug: string, title: string, description: string}>
 */
function asherava_get_blog_category_defs() {
	return array(
		array(
			'slug'        => 'rope-chain-guides',
			'title'       => __( 'Rope Chain Guides', 'asherava-jaxxon' ),
			'description' => __( 'Sizing, styling, and buying guides for rope chains.', 'asherava-jaxxon' ),
		),
		array(
			'slug'        => 'chain-care',
			'title'       => __( 'Chain Care', 'asherava-jaxxon' ),
			'description' => __( 'Cleaning, storage, and tarnish prevention.', 'asherava-jaxxon' ),
		),
		array(
			'slug'        => 'sizing-fit',
			'title'       => __( 'Sizing & Fit', 'asherava-jaxxon' ),
			'description' => __( 'Length and width guides for men’s chains.', 'asherava-jaxxon' ),
		),
		array(
			'slug'        => 'chain-comparisons',
			'title'       => __( 'Comparisons', 'asherava-jaxxon' ),
			'description' => __( 'Rope vs Cuban, Figaro, curb, and more.', 'asherava-jaxxon' ),
		),
		array(
			'slug'        => 'brand-quality',
			'title'       => __( 'Brand & Quality', 'asherava-jaxxon' ),
			'description' => __( '925 sterling silver, Italian craft, and trust.', 'asherava-jaxxon' ),
		),
	);
}

/**
 * Create blog categories if missing.
 */
function asherava_sync_blog_categories() {
	foreach ( asherava_get_blog_category_defs() as $cat ) {
		if ( term_exists( $cat['slug'], 'category' ) ) {
			continue;
		}

		wp_insert_term(
			$cat['title'],
			'category',
			array(
				'slug'        => $cat['slug'],
				'description' => $cat['description'],
			)
		);
	}
}

/**
 * Blog index URL.
 */
function asherava_get_blog_index_url() {
	return asherava_get_blog_url();
}

/**
 * Estimated reading time in minutes.
 *
 * @param int $post_id Post ID.
 */
function asherava_get_reading_time( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( (string) $content ) );
	$mins    = max( 1, (int) ceil( $words / 220 ) );

	return sprintf(
		/* translators: %d: minute count */
		_n( '%d min read', '%d min read', $mins, 'asherava-jaxxon' ),
		$mins
	);
}

/**
 * Optional shop product slug for post footer CTA.
 *
 * @param int $post_id Post ID.
 */
function asherava_get_post_shop_product_slug( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$slug    = get_post_meta( $post_id, '_asherava_shop_product_slug', true );

	return is_string( $slug ) ? trim( $slug ) : '';
}

/**
 * Resolve shop CTA URL for a post.
 *
 * @param int $post_id Post ID.
 */
function asherava_get_post_shop_cta_url( $post_id = 0 ) {
	$slug = asherava_get_post_shop_product_slug( $post_id );

	if ( $slug ) {
		$matches = get_posts(
			array(
				'name'           => $slug,
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( ! empty( $matches[0] ) ) {
			return get_permalink( (int) $matches[0] );
		}
	}

	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}

/**
 * Featured post for blog index (sticky first, else latest).
 */
function asherava_get_blog_featured_post() {
	$sticky = get_option( 'sticky_posts' );
	$sticky = is_array( $sticky ) ? array_filter( array_map( 'intval', $sticky ) ) : array();

	if ( ! empty( $sticky ) ) {
		$posts = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'post__in'       => $sticky,
				'posts_per_page' => 1,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		if ( ! empty( $posts ) ) {
			return $posts[0];
		}
	}

	$posts = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	return ! empty( $posts ) ? $posts[0] : null;
}

/**
 * Related posts in the same primary category.
 *
 * @param int $post_id Post ID.
 * @param int $limit   Max posts.
 * @return WP_Post[]
 */
function asherava_get_related_posts( $post_id, $limit = 3 ) {
	$cats = wp_get_post_categories( $post_id );
	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $limit,
		'post__not_in'        => array( $post_id ),
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
	);

	if ( ! empty( $cats ) ) {
		$args['category__in'] = array( (int) $cats[0] );
	}

	return get_posts( $args );
}

/**
 * Primary category for a post.
 *
 * @param int $post_id Post ID.
 */
function asherava_get_post_primary_category( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$categories = get_the_category( $post_id );

	if ( empty( $categories ) ) {
		return null;
	}

	return $categories[0];
}
