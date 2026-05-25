<?php
/**
 * Homepage sidebar — “More stories” markup + AJAX load-more.
 *
 * @package zox-news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mvp_home_sidebar_ad_interval_every' ) ) {
	/**
	 * Insert sidebar interstitial markup after every N stories.
	 *
	 * @return int
	 */
	function mvp_home_sidebar_ad_interval_every() {
		return max( 1, min( 20, absint( (int) apply_filters( 'mvp_home_sidebar_between_stories_interval', 4 ) ) ) );
	}
}

if ( ! function_exists( 'mvp_home_sidebar_finalize_interstitial_markup' ) ) {
	/**
	 * Run stray AdFusion shortcodes if widget output escaped processing (ordering / caching edge cases).
	 *
	 * @param string $html Raw sidebar/widget HTML.
	 * @return string
	 */
function mvp_home_sidebar_finalize_interstitial_markup( $html ) {
		$html = is_string( $html ) ? trim( $html ) : '';
		if ( '' === $html ) {
			return $html;
		}
		// Catch [adfusion …] regardless of casing; avoids “blank slot” when shortcodes weren’t expanded yet.
		if ( preg_match( '/\[\s*adfusion\b/i', $html ) ) {
			return do_shortcode( $html );
		}
		return $html;
	}
}


if ( ! function_exists( 'mvp_home_sidebar_get_interstitial_ad_html' ) ) {
	/**
	 * Interstitial markup for one list row between stories.
	 *
	 * Re-renders the widget placement on each call so ad scripts (rotating inventories, duplicate-ID guards)
	 * get distinct instances at every insertion point.
	 *
	 * Sidebar defaults to registered id `mvp-home-sidebar-below-recent` — override with filter `mvp_home_sidebar_interstitial_sidebar_id`.
	 *
	 * To reinstate former single-render reuse (performance, not compatible with many ad stacks):
	 * `add_filter( 'mvp_home_sidebar_interstitial_share_cached_markup', '__return_true' );`
	 *
	 * @return string
	 */
	function mvp_home_sidebar_get_interstitial_ad_html() {
		static $cached_single = '';
		static $had_cached    = false;

		if ( apply_filters( 'mvp_home_sidebar_interstitial_share_cached_markup', false ) ) {
			if ( $had_cached ) {
				return $cached_single;
			}
		}

		if ( ! apply_filters( 'mvp_home_sidebar_interstitial_ads_enable', true ) ) {
			if ( apply_filters( 'mvp_home_sidebar_interstitial_share_cached_markup', false ) ) {
				$had_cached     = true;
				$cached_single = '';
			}
			return '';
		}

		/**
		 * Return custom markup (including empty string). Return null or false for widget area fallback.
		 *
		 * @param string|null $custom_html Markup when string; null|false for fallback.
		 */
		$custom = apply_filters( 'mvp_home_sidebar_interstitial_ad_html', null );

		if ( is_string( $custom ) ) {
			$html = mvp_home_sidebar_finalize_interstitial_markup( $custom );
			if ( apply_filters( 'mvp_home_sidebar_interstitial_share_cached_markup', false ) ) {
				$had_cached     = true;
				$cached_single = $html;
			}
			return $html;
		}

		if ( false === $custom ) {
			if ( apply_filters( 'mvp_home_sidebar_interstitial_share_cached_markup', false ) ) {
				$had_cached     = true;
				$cached_single = '';
			}
			return '';
		}

		$raw = '';

		$sidebar_id = apply_filters( 'mvp_home_sidebar_interstitial_sidebar_id', 'mvp-home-sidebar-below-recent' );
		if ( ! is_string( $sidebar_id ) || '' === $sidebar_id ) {
			$sidebar_id = 'mvp-home-sidebar-below-recent';
		}

		if (
			is_active_sidebar( $sidebar_id ) &&
			apply_filters( 'mvp_home_sidebar_interstitial_widgets_enable', true )
		) {
			ob_start();
			dynamic_sidebar( $sidebar_id );
			$raw_ob = ob_get_clean();
			$raw    = is_string( $raw_ob ) ? $raw_ob : '';
		}

		$html = mvp_home_sidebar_finalize_interstitial_markup( $raw );

		if ( apply_filters( 'mvp_home_sidebar_interstitial_share_cached_markup', false ) ) {
			$had_cached     = true;
			$cached_single = $html;
		}

		return $html;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_maybe_output_interstitial_li' ) ) {
	/**
	 * Output wrapped ad markup as a single list row when interval reached.
	 *
	 * @param int $story_index Running count of posts output (starts at 1 for first).
	 */
	function mvp_home_sidebar_maybe_output_interstitial_li( $story_index ) {
		$every = mvp_home_sidebar_ad_interval_every();
		if ( $story_index <= 0 || ( $story_index % $every ) !== 0 ) {
			return;
		}

		$slot = mvp_home_sidebar_get_interstitial_ad_html();
		if ( '' === trim( $slot ) ) {
			return;
		}

		echo '<li class="mvp-home-sidebar-recent-item mvp-home-sidebar-recent-ad-slot" role="presentation">';
		echo '<div class="mvp-home-sidebar-recent-ad-inner">';
		// Trusted admin/widget output (matches dynamic_sidebar semantics).
		echo $slot;
		echo '</div></li>';
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_ajax_batch_size' ) ) {
	/**
	 * Posts per AJAX request for sidebar “More stories”.
	 *
	 * @return int
	 */
	function mvp_home_sidebar_recent_ajax_batch_size() {
		return max( 1, min( 24, (int) apply_filters( 'mvp_home_sidebar_recent_ajax_batch', 8 ) ) );
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_render_list_progressive' ) ) {
	/**
	 * Emit story `<li>` items and optional interstitial `<li>` rows.
	 *
	 * @param WP_Query $query         Query with posts remaining to loop.
	 * @param int      $story_position Running count across page + AJAX loads (advanced per story row only).
	 */
	function mvp_home_sidebar_recent_render_list_progressive( WP_Query $query, &$story_position ) {
		while ( $query->have_posts() ) :
			$query->the_post();

			$story_position++;

			?>
			<li class="mvp-home-sidebar-recent-item">
				<a class="mvp-home-sidebar-recent-thumb" href="<?php the_permalink(); ?>" rel="bookmark">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'mvp-small-thumb', array( 'class' => 'mvp-reg-img', 'loading' => 'lazy' ) ); ?>
					<?php endif; ?>
				</a>
				<div class="mvp-home-sidebar-recent-text">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</div>
			</li>
			<?php

			mvp_home_sidebar_maybe_output_interstitial_li( $story_position );

		endwhile;
	}
}

if ( ! function_exists( 'mvp_ajax_home_sidebar_more_posts' ) ) {
	/**
	 * AJAX handler: next chunk of sidebar stories.
	 */
	function mvp_ajax_home_sidebar_more_posts() {
		check_ajax_referer( 'mvp_home_sidebar_recent', 'nonce' );

		$has_more       = false;
		$next_offset_raw = 0;

		if ( ! apply_filters( 'mvp_home_sidebar_recent_enable', true ) ) {
			wp_send_json_success(
				array(
					'html'           => '',
					'nextOffset'     => 0,
					'streamPosition' => 0,
					'has_more'       => false,
				)
			);
		}

		$offset         = isset( $_POST['offset'] ) ? absint( wp_unslash( $_POST['offset'] ) ) : 0;
		$story_position = isset( $_POST['streamPosition'] ) ? absint( wp_unslash( $_POST['streamPosition'] ) ) : 0;
		$batch          = mvp_home_sidebar_recent_ajax_batch_size();

		$max_off = absint(
			apply_filters(
				'mvp_home_sidebar_recent_ajax_max_offset',
				50000
			)
		);
		if ( $max_off > 0 ) {
			$offset = min( $max_off, $offset );
		}

		if ( function_exists( 'mvp_home_sidebar_recent_take_batch' ) ) {
			$bundle = mvp_home_sidebar_recent_take_batch( $batch, $offset );
			$query  = isset( $bundle['query'] ) ? $bundle['query'] : null;
			if ( ! $query instanceof WP_Query ) {
				$query = new WP_Query(
					array(
						'post__in'            => array( 0 ),
						'posts_per_page'      => 1,
						'ignore_sticky_posts' => true,
					)
				);
			}
			$next_offset_raw = isset( $bundle['next_offset'] ) ? (int) $bundle['next_offset'] : $offset;

			if ( array_key_exists( 'has_more', $bundle ) ) {
				$has_more = (bool) $bundle['has_more'];
			} else {
				$pool_total = isset( $bundle['pool_total'] ) ? (int) $bundle['pool_total'] : 0;
				$slice_cnt  = isset( $bundle['slice_count'] ) ? (int) $bundle['slice_count'] : 0;
				$has_more   = (
					$slice_cnt > 0
					&& $next_offset_raw > $offset
					&& $next_offset_raw < $pool_total
				);
			}
		} else {
			$query = new WP_Query(
				array(
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'posts_per_page'      => $batch,
					'offset'              => $offset,
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
					'orderby'             => 'date',
					'order'               => 'DESC',
				)
			);
			$next_offset_raw = $offset + min( $batch, (int) $query->post_count );
			$has_more       = (
				min( $batch, (int) $query->post_count ) > 0
				&& min( $batch, (int) $query->post_count ) >= $batch
			);
		}

		ob_start();
		if ( $query->have_posts() ) {
			mvp_home_sidebar_recent_render_list_progressive( $query, $story_position );
		}
		$html = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'html'           => $html,
				'nextOffset'     => (int) $next_offset_raw,
				'streamPosition' => (int) $story_position,
				'has_more'       => $has_more,
			)
		);
	}
}
add_action( 'wp_ajax_mvp_home_sidebar_more_posts', 'mvp_ajax_home_sidebar_more_posts' );
add_action( 'wp_ajax_nopriv_mvp_home_sidebar_more_posts', 'mvp_ajax_home_sidebar_more_posts' );
