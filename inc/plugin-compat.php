<?php
/**
 * Third-party plugin compatibility (Yoast, Planit, AdFusion).
 *
 * @package Vicksburg_Daily_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * When Yoast SEO is active, defer Planit (TWEC) event head output so Yoast owns JSON-LD / social for events.
 * When Yoast is inactive, Planit keeps its default behavior.
 *
 * @param bool    $allow Whether Planit may output event meta.
 * @param WP_Post $post  Event post object.
 * @return bool
 */
function vdn_compat_planit_event_meta_allow( $allow, $post ) {
	unset( $post );
	if ( function_exists( 'vdn_yoast_seo_active' ) && vdn_yoast_seo_active() ) {
		return false;
	}
	return (bool) $allow;
}

add_filter( 'twec_seo_output_event_meta', 'vdn_compat_planit_event_meta_allow', 10, 2 );

/**
 * Gate AdFusion injection on AMP: custom HTML ads often violate AMP or break validation.
 * Disable via: add_filter( 'vdn_disable_adfusion_on_amp', '__return_false' );
 *
 * @param bool        $allow   Whether the ad may render.
 * @param int|null    $ad_id   Ad post ID (context varies by call site).
 * @param mixed       $context Optional context (AdFusion Premium).
 * @return bool
 */
function vdn_compat_adfusion_allow_render( $allow, $ad_id = null, $context = null ) {
	unset( $ad_id, $context );
	if ( ! $allow ) {
		return false;
	}
	if ( apply_filters( 'vdn_disable_adfusion_on_amp', true ) && function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		return false;
	}
	return true;
}

add_filter( 'adfusion_allow_ad_render', 'vdn_compat_adfusion_allow_render', 100, 3 );

/**
 * Run AdFusion shortcodes in widget placements where Core does not (by default).
 * Without this, [adfusion group="sidebar-only"] pasted into a Custom HTML widget
 * or block HTML widget is output verbatim and appears “blank”.
 */
function vdn_compat_widget_maybe_do_shortcodes( $content ) {
	if ( '' === $content ) {
		return $content;
	}
	if ( ! preg_match( '/\[\s*adfusion\b/i', $content ) ) {
		return $content;
	}
	return do_shortcode( $content );
}
add_filter( 'widget_custom_html_content', 'vdn_compat_widget_maybe_do_shortcodes', 999 );

/** Classic Text widget (block-based or legacy): run [adfusion] when present */
add_filter( 'widget_text_content', 'vdn_compat_widget_maybe_do_shortcodes', 998 );

/**
 * Widget block HTML (block editor → Custom HTML): process [adfusion] only when present.
 *
 * @param string               $block_content Block HTML.
 * @param array<string, mixed> $block         Parsed block.
 * @return string
 */
function vdn_compat_render_block_html_adfusion_shortcodes( $block_content, $block ) {
	if ( ! is_array( $block ) || empty( $block['blockName'] ) || 'core/html' !== $block['blockName'] ) {
		return $block_content;
	}
	return vdn_compat_widget_maybe_do_shortcodes( $block_content );
}
add_filter( 'render_block', 'vdn_compat_render_block_html_adfusion_shortcodes', 20, 2 );
