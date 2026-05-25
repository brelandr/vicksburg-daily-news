<?php
/**
 * Homepage “More stories” sidebar sources: avoid main-feed overlap; prefer sections + popular backfill.
 *
 * @package zox-news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'mvp_home_sidebar_resolve_featured_exclude_ids' ) ) {
	/**
	 * Post IDs excluded from “More News” when featured posts are enabled (matches featured.php tagging).
	 *
	 * Falls back to a tag-based ID scan during AJAX where $do_not_duplicate is not populated.
	 *
	 * @return int[]
	 */
	function mvp_home_sidebar_resolve_featured_exclude_ids() {
		global $do_not_duplicate;
		if ( ! empty( $do_not_duplicate ) && is_array( $do_not_duplicate ) ) {
			return array_values( array_unique( array_map( 'absint', $do_not_duplicate ) ) );
		}

		$featured_on = get_option( 'mvp_feat_posts' );
		if ( ! isset( $featured_on ) || 'true' !== (string) $featured_on ) {
			return array();
		}

		$tag = get_option( 'mvp_feat_posts_tags' );
		if ( empty( $tag ) ) {
			return array();
		}

		static $memo = null;
		if ( is_array( $memo ) ) {
			return $memo;
		}

		$query = new WP_Query(
			array(
				'post_type'              => 'post',
				'post_status'            => 'publish',
				'tag'                    => $tag,
				'posts_per_page'         => 48,
				'fields'                 => 'ids',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'suppress_filters'       => false,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		wp_reset_postdata();
		$memo = array_map( 'absint', is_array( $query->posts ) ? $query->posts : array() );

		return $memo;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_get_main_feed_first_page_ids' ) ) {
	/**
	 * Copy of the first-page “More News” query — ids only — so the sidebar does not repeat that row.
	 *
	 * Works on full page renders and AJAX (does not rely on is_page_template()).
	 *
	 * @return int[]
	 */
	function mvp_home_sidebar_get_main_feed_first_page_ids() {
		static $memo = null;
		if ( is_array( $memo ) ) {
			return $memo;
		}

		// If this template does not render the main blog stream, skip.
		$home_layout = get_option( 'mvp_home_layout' );
		if ( false !== $home_layout && '0' !== (string) $home_layout && '2' !== (string) $home_layout ) {
			$memo = array();
			return $memo;
		}

		$mvp_posts_num = absint( get_option( 'mvp_posts_num' ) );
		if ( $mvp_posts_num < 1 ) {
			$mvp_posts_num = 10;
		}

		$exclude_featured = mvp_home_sidebar_resolve_featured_exclude_ids();

		$args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => $mvp_posts_num,
			'paged'                  => 1,
			'fields'                 => 'ids',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		if ( ! empty( $exclude_featured ) ) {
			$args['post__not_in'] = array_values( array_unique( array_map( 'absint', $exclude_featured ) ) );
		}

		$query = new WP_Query( $args );
		wp_reset_postdata();
		$memo = array_map( 'absint', is_array( $query->posts ) ? $query->posts : array() );

		return $memo;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_collect_exclude_post_ids' ) ) {
	/**
	 * Sidebar should not duplicate featured-area posts or first-page More News IDs.
	 *
	 * Additional IDs may be appended via filter `mvp_home_sidebar_exclude_post_ids`.
	 *
	 * @return int[]
	 */
	function mvp_home_sidebar_collect_exclude_post_ids() {
		$featured   = mvp_home_sidebar_resolve_featured_exclude_ids();
		$main_first = mvp_home_sidebar_get_main_feed_first_page_ids();

		$merged = array_values(
			array_unique(
				array_merge(
					array_filter( array_map( 'absint', $featured ) ),
					array_filter( array_map( 'absint', $main_first ) )
				)
			)
		);

		/**
		 * Extra post IDs excluded from sidebar “More stories”.
		 *
		 * @param int[] $merged Default merge of featured exclusions + More News page 1.
		 */
		return array_values( array_unique( array_filter( array_map( 'absint', (array) apply_filters( 'mvp_home_sidebar_exclude_post_ids', $merged ) ) ) ) );
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_build_ordered_pool_ids' ) ) {
	/**
	 * Ordered diversified pool after exclusions (category mix → popular meta → newest fallback).
	 *
	 * Cached per-request by exclusion set + sizing hint so the initial sidebar list and AJAX setup can reuse one build.
	 *
	 * @param int[] $base_exclude Sidebar + main + featured exclusions plus any caller extras.
	 * @param int   $recent_count Hint for fetch depth (minimum pool size guidance).
	 * @return int[]
	 */
	function mvp_home_sidebar_recent_build_ordered_pool_ids( array $base_exclude, $recent_count = 8 ) {
		$recent_count = max( 1, absint( $recent_count ) );
		$base_exclude = array_values( array_unique( array_filter( array_map( 'absint', $base_exclude ) ) ) );
		sort( $base_exclude, SORT_NUMERIC );

		static $pool_memo = array();
		$memo_k           = md5( implode( ',', $base_exclude ) . '|z' . (string) $recent_count );

		if ( array_key_exists( $memo_k, $pool_memo ) ) {
			return $pool_memo[ $memo_k ];
		}
		$prefer_slugs       = apply_filters(
			'mvp_home_sidebar_prefer_category_slugs',
			array(
				'obituaries',
				'sports',
			)
		);
		$prefer_slugs       = array_values(
			array_filter(
				array_map(
					function ( $slug ) {
						return sanitize_title( (string) $slug );
					},
					(array) $prefer_slugs
				)
			)
		);

		$popular_meta_key = apply_filters( 'mvp_home_sidebar_popular_meta_key', 'post_views_count' );
		$popular_meta_key = is_string( $popular_meta_key ) ? $popular_meta_key : 'post_views_count';

		$need = min( $recent_count * 6, max( $recent_count * 4, 48 ) );
		if ( apply_filters( 'mvp_home_sidebar_broaden_recent_pool', false ) ) {
			$need *= 3;
		}

		$pool_ids = array();

		// Prefer section categories (mix of niche content before general home feed).
		if ( ! empty( $prefer_slugs ) ) {
			$preferred = new WP_Query(
				apply_filters(
					'mvp_home_sidebar_recent_preferred_query_args',
					array(
						'post_type'              => 'post',
						'post_status'            => 'publish',
						'posts_per_page'         => $recent_count * 8,
						'offset'                 => 0,
						'orderby'                => 'date',
						'order'                  => 'DESC',
						'post__not_in'           => $base_exclude,
						'ignore_sticky_posts'    => true,
						'no_found_rows'          => true,
						'fields'                 => 'ids',
						'tax_query'              => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'slug',
								'terms'    => $prefer_slugs,
							),
						),
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
					)
				)
			);
			wp_reset_postdata();
			$preferred_ids = array_map( 'absint', is_array( $preferred->posts ) ? $preferred->posts : array() );
			foreach ( $preferred_ids as $pid ) {
				if ( count( $pool_ids ) >= $need ) {
					break;
				}
				if ( ! in_array( $pid, $pool_ids, true ) ) {
					$pool_ids[] = $pid;
				}
			}
		}

		// Popular archives (older strong performers vs headline stream duplicates).
		$popular_exclude = array_values(
			array_unique(
				array_merge(
					$base_exclude,
					$pool_ids
				)
			)
		);
		$popular_args    = array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => max( $recent_count * 10, $need ),
			'offset'              => 0,
			'orderby'             => 'meta_value_num',
			'order'               => 'DESC',
			'meta_key'            => $popular_meta_key,
			'post__not_in'        => $popular_exclude,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'meta_query'          => array(
				array(
					'key'     => $popular_meta_key,
					'compare' => 'EXISTS',
					'type'    => 'NUMERIC',
				),
			),
		);

		/**
		 * Optional date window for sidebar “popular”, e.g. array( array( 'after' => '90 days ago' ) ).
		 *
		 * @param array|null $date_query Passed to WP_Query date_query when non-empty array.
		 * @param string     $meta_key   Views meta key.
		 */
		$popular_date = apply_filters( 'mvp_home_sidebar_popular_date_query', null, $popular_meta_key );
		if ( is_array( $popular_date ) && ! empty( $popular_date ) ) {
			$popular_args['date_query'] = $popular_date;
		}

		$popular = new WP_Query(
			apply_filters( 'mvp_home_sidebar_recent_popular_query_args', $popular_args )
		);
		wp_reset_postdata();
		foreach ( array_map( 'absint', is_array( $popular->posts ) ? $popular->posts : array() ) as $pid ) {
			if ( count( $pool_ids ) >= $need ) {
				break;
			}
			if ( ! in_array( $pid, $pool_ids, true ) ) {
				$pool_ids[] = $pid;
			}
		}

		// Fallback: chronological recent excluding everything above.
		$recent_exclude = array_values(
			array_unique(
				array_merge(
					$base_exclude,
					$pool_ids
				)
			)
		);
		if ( count( $pool_ids ) < $need ) {
			$recent = new WP_Query(
				apply_filters(
					'mvp_home_sidebar_recent_fallback_query_args',
					array(
						'post_type'              => 'post',
						'post_status'            => 'publish',
						'posts_per_page'         => max( $need - count( $pool_ids ), $recent_count * 8 ),
						'offset'                 => 0,
						'orderby'                => 'date',
						'order'                  => 'DESC',
						'post__not_in'           => $recent_exclude,
						'ignore_sticky_posts'    => true,
						'no_found_rows'          => true,
						'fields'                 => 'ids',
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
					)
				)
			);
			wp_reset_postdata();
			foreach ( array_map( 'absint', is_array( $recent->posts ) ? $recent->posts : array() ) as $pid ) {
				if ( count( $pool_ids ) >= $need ) {
					break;
				}
				if ( ! in_array( $pid, $pool_ids, true ) ) {
					$pool_ids[] = $pid;
				}
			}
		}

		$combined_ids = apply_filters(
			'mvp_home_sidebar_recent_post_id_pool',
			$pool_ids,
			array(
				'exclusion_ids'  => $base_exclude,
				'recent_count_hint' => $recent_count,
			)
		);

		if ( ! is_array( $combined_ids ) ) {
			$combined_ids = array();
		}

		$result          = array_values( array_unique( array_filter( array_map( 'absint', $combined_ids ) ) ) );
		$pool_memo[ $memo_k ] = $result;

		return $result;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_wp_query_from_pool_slice' ) ) {
	/**
	 * Materialize WP_Query preserving pool order for the given slice.
	 *
	 * @param int[] $ordered_ids Ordered ID list from mvp_home_sidebar_recent_build_ordered_pool_ids().
	 * @param int   $offset       Offset inside the ordered pool.
	 * @param int   $posts_per_page Batch size.
	 * @return WP_Query Empty query yields no posts via post__in 0 sentinel.
	 */
	function mvp_home_sidebar_recent_wp_query_from_pool_slice( array $ordered_ids, $offset, $posts_per_page ) {
		$posts_per_page = max( 1, absint( $posts_per_page ) );
		$offset         = max( 0, absint( $offset ) );
		$slice_ids      = array_slice( $ordered_ids, $offset, $posts_per_page );

		if ( empty( $slice_ids ) ) {
			return new WP_Query(
				array(
					'post_type'              => 'post',
					'post_status'            => 'publish',
					'post__in'               => array( 0 ),
					'posts_per_page'         => 1,
					'ignore_sticky_posts'    => true,
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				)
			);
		}

		$query = new WP_Query(
			apply_filters(
				'mvp_home_sidebar_recent_final_query_args',
				array(
					'post_type'              => 'post',
					'post_status'            => 'publish',
					'post__in'               => array_values( $slice_ids ),
					'orderby'                => 'post__in',
					'posts_per_page'         => count( $slice_ids ),
					'ignore_sticky_posts'    => true,
					'suppress_filters'       => false,
					'no_found_rows'          => true,
					'update_post_term_cache' => true,
				)
			)
		);

		return $query;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_tail_post_ids' ) ) {
	/**
	 * IDs excluded from chronological sidebar continuation (everything already shown via curated pool + main exclusions).
	 *
	 * Large lists are clipped to stay within practical post__not_in limits.
	 *
	 * @param int[] $merged_exclude Featured + first-page main IDs.
	 * @param int[] $pool_ids       Curated pool IDs already served (or duplicated in exclude).
	 * @return int[]
	 */
	function mvp_home_sidebar_recent_tail_post_ids( array $merged_exclude, array $pool_ids ) {
		$combined = array_values(
			array_unique(
				array_filter(
					array_map(
						'absint',
						array_merge(
							$merged_exclude,
							$pool_ids
						)
					)
				)
			)
		);
		/** @var int $max_wp_not_in Typical safe upper bound for post__not_in size. */
		$cap = absint(
			apply_filters(
				'mvp_home_sidebar_recent_tail_exclude_cap',
				400
			)
		);
		if ( $cap > 0 && count( $combined ) > $cap ) {
			$combined = array_slice( $combined, 0, $cap );
		}

		return $combined;
	}
}

if ( ! function_exists( 'mvp_home_sidebar_recent_take_batch' ) ) {
	/**
	 * One batch of sidebar stories plus pool metadata.
	 *
	 * After the diversified pool is exhausted, continues with chronological posts (excluding pool + exclusions)
	 * so infinite scroll keeps loading ads + stories indefinitely until the catalogue ends.
	 *
	 * @param int $posts_per_page Batch size / initial list length (also deepens curated pool sizing).
	 * @param int $offset Monotonic scroll offset: indexes into curated pool first, then into chronological tail
	 *                    (`offset >= pool_total` → tail begins at `$offset - $pool_total`).
	 *
	 * @return array<string,mixed> query, slice_count, pool_total, next_offset, has_more.
	 */
	function mvp_home_sidebar_recent_take_batch( $posts_per_page, $offset = 0 ) {
		$posts_per_page = max( 1, absint( $posts_per_page ) );
		$offset         = max( 0, absint( $offset ) );

		$merged_exclude = mvp_home_sidebar_collect_exclude_post_ids();
		$pool_ids       = mvp_home_sidebar_recent_build_ordered_pool_ids(
			$merged_exclude,
			$posts_per_page
		);
		$pool_total     = count( $pool_ids );

		/*
		 * Curated diversification window (preferred categories → popular meta → fallback).
		 */
		if ( $offset < $pool_total ) {
			$slice_ids = array_slice( $pool_ids, $offset, $posts_per_page );

			if ( empty( $slice_ids ) ) {
				$query       = new WP_Query(
					array(
						'post_type'              => 'post',
						'post_status'            => 'publish',
						'post__in'               => array( 0 ),
						'posts_per_page'         => 1,
						'ignore_sticky_posts'    => true,
						'no_found_rows'          => true,
						'update_post_meta_cache' => false,
						'update_post_term_cache' => false,
					)
				);
				$slice_count = 0;
			} else {
				$query       = mvp_home_sidebar_recent_wp_query_from_pool_slice(
					$pool_ids,
					$offset,
					$posts_per_page
				);
				$slice_count = count( $slice_ids );
			}

			$next_offset = $offset + $slice_count;
			/*
			 * More curated rows remain, or this batch drained the diversified pool → load chronological tail next.
			 */
			$has_more = ( $slice_count > 0 ) && (
				$next_offset <= $pool_total
			);

			return array(
				'query'       => $query,
				'slice_count' => $slice_count,
				'pool_total'  => $pool_total,
				'next_offset' => $next_offset,
				'has_more'    => $has_more,
				'in_tail'     => false,
			);
		}

		/*
		 * Chronological continuation after curated pool IDs (same exclusions; no repeats from primary pool ordering).
		 */
		$tail_off    = max( 0, $offset - $pool_total );
		$tail_not_in = mvp_home_sidebar_recent_tail_post_ids( $merged_exclude, $pool_ids );

		$tail_args = array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'posts_per_page'         => $posts_per_page,
			'offset'                 => $tail_off,
			'orderby'                => 'date',
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => false,
			'update_post_meta_cache' => false,
			'suppress_filters'       => false,
		);
		if ( ! empty( $tail_not_in ) ) {
			$tail_args['post__not_in'] = array_values(
				array_unique(
					array_map( 'absint', $tail_not_in )
				)
			);
		}

		$query       = new WP_Query(
			apply_filters(
				'mvp_home_sidebar_recent_tail_query_args',
				$tail_args,
				array(
					'request_offset' => $offset,
					'tail_sql_offset'=> $tail_off,
					'pool_total'     => $pool_total,
				)
			)
		);
		$slice_count = (int) $query->post_count;
		$next_offset = $offset + max( $slice_count, 0 );
		$found       = isset( $query->found_posts ) ? (int) $query->found_posts : 0;

		$has_more = (
			$slice_count > 0
			&& ( ( $tail_off + $slice_count ) < $found )
		);

		return array(
			'query'       => $query,
			'slice_count' => $slice_count,
			'pool_total'  => $pool_total,
			'next_offset' => $next_offset,
			'has_more'    => $has_more,
			'in_tail'     => true,
		);
	}
}
