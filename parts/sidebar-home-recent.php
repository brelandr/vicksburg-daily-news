<?php
/**
 * Homepage sidebar: “More stories” + interstitial ads every N stories + infinite scroll continuation.
 *
 * @package zox-news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mvp_home_sidebar_recent_render_list_progressive' ) ) {
	return;
}

$recent_count = (int) apply_filters( 'mvp_home_sidebar_recent_count', 8 );
if ( $recent_count < 1 || ! apply_filters( 'mvp_home_sidebar_recent_enable', true ) ) {
	return;
}

$recent_sidebar_offset = (int) apply_filters( 'mvp_home_sidebar_recent_offset', 0 );
$recent_batch          = null;

if ( function_exists( 'mvp_home_sidebar_recent_take_batch' ) ) {
	$recent_batch = mvp_home_sidebar_recent_take_batch( $recent_count, $recent_sidebar_offset );
	$recent_q      = isset( $recent_batch['query'] ) ? $recent_batch['query'] : null;
	if ( ! ( $recent_q instanceof WP_Query ) ) {
		return;
	}
} else {
	$recent_q = new WP_Query(
		array(
			'posts_per_page'      => $recent_count,
			'offset'              => $recent_sidebar_offset,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $recent_q->have_posts() ) {
	return;
}

// Count stories from 1 for the first visible item so ads fall after rows 4, 8, 12, ...
// (AJAX appends continue the same rhythm via data-stream).
$mvp_sidebar_story_count = 0;

$mvp_sidebar_ajax_next_offset = $recent_sidebar_offset + $recent_count;
if ( is_array( $recent_batch ) && isset( $recent_batch['next_offset'] ) ) {
	$mvp_sidebar_ajax_next_offset = (int) $recent_batch['next_offset'];
}

?>
<div class="mvp-side-widget mvp-home-sidebar-recent">
	<h4 class="mvp-home-sidebar-recent-title"><?php esc_html_e( 'More stories', 'zox-news' ); ?></h4>
	<ul class="mvp-home-sidebar-recent-list">
		<?php mvp_home_sidebar_recent_render_list_progressive( $recent_q, $mvp_sidebar_story_count ); ?>
	</ul>
	<?php wp_reset_postdata(); ?>
	<span class="mvp-home-sidebar-stream-token" aria-hidden="true"
		data-stream="<?php echo esc_attr( (string) $mvp_sidebar_story_count ); ?>"
		data-next-offset="<?php echo esc_attr( (string) max( 0, $mvp_sidebar_ajax_next_offset ) ); ?>"></span>

	<?php if ( apply_filters( 'mvp_home_sidebar_recent_ajax_enable', true ) ) : ?>
		<ul class="mvp-home-sidebar-recent-list mvp-home-sidebar-recent-more" aria-live="polite"></ul>
		<div class="mvp-home-sidebar-recent-sentinel" aria-hidden="true">
			<span class="mvp-home-sidebar-recent-loading"><?php esc_html_e( 'Loading…', 'zox-news' ); ?></span>
		</div>
	<?php endif; ?>
</div>
