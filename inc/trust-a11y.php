<?php
/**
 * Editorial transparency, footer trust links, accessibility assets, reading progress.
 *
 * @package zox-news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prepend “Last updated” to post body when the modified time is later than publish (with small tolerance).
 *
 * @param string $content Post content.
 * @return string
 */
function vdn_prepend_last_updated_to_content( $content ) {
	static $vdn_last_updated_done = false;
	if ( $vdn_last_updated_done || ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	if ( get_option( 'mvp_last_updated', 'true' ) !== 'true' ) {
		return $content;
	}
	global $post;
	if ( ! $post instanceof WP_Post ) {
		return $content;
	}
	$published = (int) get_post_time( 'U', true, $post );
	$modified  = (int) get_post_modified_time( 'U', true, $post );
	if ( $modified <= $published + 60 ) {
		return $content;
	}
	$vdn_last_updated_done = true;
	$df      = get_option( 'date_format' );
	$mod_str = get_post_modified_time( $df, true, $post );
	$line    = '<p class="mvp-last-updated"><span class="mvp-last-updated-label">' . esc_html__( 'Last updated:', 'zox-news' ) . '</span> <time class="mvp-last-updated-time" datetime="' . esc_attr( get_post_modified_time( 'c', true, $post ) ) . '">' . esc_html( $mod_str ) . '</time></p>';

	return $line . $content;
}

add_filter( 'the_content', 'vdn_prepend_last_updated_to_content', 4 );

/**
 * Enqueue accessibility CSS and optional reading progress script on singles.
 */
function vdn_enqueue_trust_a11y_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_enqueue_style(
		'vdn-accessibility',
		get_template_directory_uri() . '/css/accessibility.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
	if ( is_singular( 'post' ) && get_option( 'mvp_reading_progress', 'false' ) === 'true' ) {
		wp_enqueue_script(
			'vdn-read-progress',
			get_template_directory_uri() . '/js/read-progress.js',
			array(),
			wp_get_theme()->get( 'Version' ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'vdn_enqueue_trust_a11y_assets', 20 );
