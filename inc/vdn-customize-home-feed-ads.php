<?php
/**
 * Home template: optionally inject Google AdSense after every N stories (Customizer).
 *
 * Depends on AdFusion core for publisher ID (adf_google_adsense_id) and script enqueue.
 *
 * Infinite scroll: `js/vdn-home-feed-ads-infinite.js` mirrors PHP inserts when new `<li>` posts
 * are appended (MutationObserver on `ul.infinite-content`).
 *
 * @package Vicksburg_Daily_News
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether we are rendering the child “Home” page template (“More News” context).
 *
 * @return bool
 */
function vdn_home_feed_is_home_feed_page() {
	if ( function_exists( 'is_page_template' ) && is_page_template( 'page-home.php' ) ) {
		return true;
	}
	if ( ! is_front_page() || ! is_page() ) {
		return false;
	}
	$pid = get_queried_object_id();
	if ( $pid < 1 ) {
		return false;
	}
	$slug = get_page_template_slug( $pid );
	return ( 'page-home.php' === $slug );
}

/**
 * Reset story counter once per `<ul infinite-content>` feed (call before loops).
 *
 * @return void
 */
function vdn_home_feed_reset_story_counter() {
	$GLOBALS['vdn_home_feed_story_count'] = 0;
}

/**
 * @param mixed $checked Value from Customizer.
 * @return bool
 */
function vdn_home_feed_sanitize_enable( $checked ) {
	return ( isset( $checked ) && ( true === $checked || '1' === $checked || 1 === (int) $checked ) );
}

/**
 * @param mixed $every Raw interval.
 * @return int Clamp 1–100.
 */
function vdn_home_feed_sanitize_every( $every ) {
	$n = absint( $every );
	if ( $n < 1 ) {
		return 10;
	}
	if ( $n > 100 ) {
		return 100;
	}
	return $n;
}

/**
 * @param mixed $slot Ad slot meta value.
 * @return string
 */
function vdn_home_feed_sanitize_slot( $slot ) {
	return sanitize_text_field( (string) $slot );
}

/**
 * Sanitize Customizer textarea for slot list (digits + commas/newlines in parse step).
 *
 * @param mixed $value Raw theme mod value.
 * @return string
 */
function vdn_home_feed_sanitize_slots_textarea( $value ) {
	return sanitize_textarea_field( (string) $value );
}

/**
 * Parse one or more AdSense slot IDs from Customizer text (comma or whitespace separated).
 *
 * @param string $raw Raw string.
 * @return string[] Non-empty numeric IDs in order (no duplicates removed).
 */
function vdn_home_feed_parse_slots_from_theme_string( $raw ) {
	$raw = vdn_home_feed_sanitize_slot( $raw );
	if ( '' === $raw ) {
		return array();
	}
	$parts = preg_split( '/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY );
	$out   = array();
	foreach ( (array) $parts as $part ) {
		$s = vdn_home_feed_sanitize_slot( $part );
		if ( preg_match( '/^\d+$/', $s ) ) {
			$out[] = $s;
		}
	}
	return $out;
}

/**
 * Fallback slots from wp_options keys (single value expanded to one-element array).
 *
 * @return string[]
 */
function vdn_home_feed_get_fallback_slots_from_options() {
	$option_keys = apply_filters(
		'vdn_home_feed_adfusion_default_slot_option_keys',
		array(
			'adf_google_default_slot',
			'adf_google_default_ad_slot',
			'adf_google_adsense_default_slot',
			'adf_default_google_ad_slot',
			'adf_global_google_ad_slot',
			'adf_google_fallback_ad_slot',
		)
	);
	foreach ( (array) $option_keys as $key ) {
		$key = is_string( $key ) ? trim( $key ) : '';
		if ( '' === $key || ! preg_match( '/^[a-z0-9_\-]+$/', $key ) ) {
			continue;
		}
		$val = get_option( $key, '' );
		if ( ! is_string( $val ) ) {
			continue;
		}
		$list = vdn_home_feed_parse_slots_from_theme_string( $val );
		if ( array() !== $list ) {
			return $list;
		}
		$one = vdn_home_feed_sanitize_slot( $val );
		if ( preg_match( '/^\d+$/', $one ) ) {
			return array( $one );
		}
	}
	return array();
}

/**
 * All configured feed slot IDs for rotation (Customizer list, option fallback, then filter legacy string).
 *
 * @return string[]
 */
function vdn_home_feed_get_adsense_slots() {
	static $vdn_home_feed_slots_memo = null;

	if ( null !== $vdn_home_feed_slots_memo ) {
		return $vdn_home_feed_slots_memo;
	}

	$from_theme = vdn_home_feed_parse_slots_from_theme_string( get_theme_mod( 'vdn_home_feed_ad_slot', '' ) );
	if ( array() !== $from_theme ) {
		/** @var string[] $slots */
		$slots = apply_filters( 'vdn_home_feed_adsense_slots', $from_theme );
		$vdn_home_feed_slots_memo = is_array( $slots ) ? $slots : array();
		return $vdn_home_feed_slots_memo;
	}

	$from_opts = vdn_home_feed_get_fallback_slots_from_options();
	if ( array() !== $from_opts ) {
		/** @var string[] $slots */
		$slots                    = apply_filters( 'vdn_home_feed_adsense_slots', $from_opts );
		$vdn_home_feed_slots_memo = is_array( $slots ) ? $slots : array();
		return $vdn_home_feed_slots_memo;
	}

	$legacy_single = vdn_home_feed_sanitize_slot( (string) apply_filters( 'vdn_home_feed_adsense_slot', '' ) );
	$combined      = ( '' !== $legacy_single && preg_match( '/^\d+$/', $legacy_single ) ) ? array( $legacy_single ) : array();
	/** @var string[] $slots */
	$slots                    = apply_filters( 'vdn_home_feed_adsense_slots', $combined );
	$vdn_home_feed_slots_memo = is_array( $slots ) ? $slots : array();
	return $vdn_home_feed_slots_memo;
}

/**
 * Pick rotating slot id for nth ad placement (1 = first placement after stories N, 2 = second …).
 *
 * @param int     $placement_index 1-based (first ad placement = 1).
 * @param string[]     $slots            Slot IDs from vdn_home_feed_get_adsense_slots().
 * @return string Empty if none.
 */
function vdn_home_feed_get_slot_for_placement( $placement_index, array $slots ) {
	if ( array() === $slots ) {
		return '';
	}
	$n = (int) $placement_index;
	if ( $n < 1 ) {
		$n = 1;
	}
	$slots = array_values( $slots );
	return (string) $slots[ ( $n - 1 ) % count( $slots ) ];
}

/**
 * Register Customizer settings (Appearance > Customize > VDN Home feed ads).
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 * @return void
 */
function vdn_home_feed_ads_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'vdn_home_feed_ads_section',
		array(
			'title'       => __( 'VDN Home feed ads', 'zox-news' ),
			'description' => __( 'Insert Google AdSense after every N stories on the Home page template only. Publisher ID uses AdFusion (adf_google_adsense_id). Requires the Zox home layout that shows the “More News” list (not widgets-only: in Zox options, home layout must include the blog list).', 'zox-news' ),
			'priority'    => 160,
		)
	);

	$wp_customize->add_setting(
		'vdn_home_feed_ad_enable',
		array(
			'default'           => false,
			'sanitize_callback' => 'vdn_home_feed_sanitize_enable',
		)
	);
	$wp_customize->add_control(
		'vdn_home_feed_ad_enable_ctrl',
		array(
			'type'        => 'checkbox',
			'section'     => 'vdn_home_feed_ads_section',
			'label'       => __( 'Enable AdSense inserts in More News feed', 'zox-news' ),
			'description' => __( 'When disabled, no ad rows are output.', 'zox-news' ),
			'settings'    => 'vdn_home_feed_ad_enable',
		)
	);

	$wp_customize->add_setting(
		'vdn_home_feed_ad_every',
		array(
			'default'           => 10,
			'sanitize_callback' => 'vdn_home_feed_sanitize_every',
		)
	);
	$wp_customize->add_control(
		'vdn_home_feed_ad_every_ctrl',
		array(
			'type'        => 'number',
			'section'     => 'vdn_home_feed_ads_section',
			'label'       => __( 'Show an ad after every N stories', 'zox-news' ),
			'description' => __( 'Example: 10 inserts an ad row after posts 10, 20, 30… within the visible list.', 'zox-news' ),
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 100,
				'step' => 1,
			),
			'settings'    => 'vdn_home_feed_ad_every',
		)
	);

	$wp_customize->add_setting(
		'vdn_home_feed_ad_slot',
		array(
			'default'           => '',
			'sanitize_callback' => 'vdn_home_feed_sanitize_slots_textarea',
		)
	);
	$wp_customize->add_control(
		'vdn_home_feed_ad_slot_ctrl',
		array(
			'type'        => 'textarea',
			'section'     => 'vdn_home_feed_ads_section',
			'label'       => __( 'AdSense slot IDs (one or many)', 'zox-news' ),
			'description' => __( 'Use one numeric data-ad-slot per line or comma-separated. To reduce “same ad every interval,” create multiple Display units in AdSense (each has its own slot) and paste at least two different IDs—the theme rotates placement 1, 2, 3…. If left empty, the theme uses site options (vdn_home_feed_adfusion_default_slot_option_keys) or the vdn_home_feed_adsense_slot filter.', 'zox-news' ),
			'input_attrs' => array(
				'rows'        => 3,
				'placeholder' => __( '9771735772, 2233445566, 3344556677', 'zox-news' ),
			),
			'settings'    => 'vdn_home_feed_ad_slot',
		)
	);
}
add_action( 'customize_register', 'vdn_home_feed_ads_customize_register' );

/**
 * Frontend stylesheet when a feed row can render (Customizer + publisher + slot + guards).
 *
 * @return void
 */
function vdn_home_feed_ads_maybe_enqueue_assets() {
	// Only load feed-ad CSS when a row might actually render (same gates as markup + script fallback).
	if ( ! vdn_home_feed_should_render_adsense_rows() ) {
		return;
	}
	$href = get_stylesheet_directory_uri() . '/css/vdn-home-feed-ads.css';
	$path = get_stylesheet_directory() . '/css/vdn-home-feed-ads.css';
	$ver  = '1.1.0';
	if ( is_readable( $path ) ) {
		$mtime = filemtime( $path );
		if ( false !== $mtime ) {
			$ver = (string) $mtime;
		}
	}
	wp_enqueue_style(
		'vdn-home-feed-ads',
		$href,
		array(),
		$ver
	);
}
add_action( 'wp_enqueue_scripts', 'vdn_home_feed_ads_maybe_enqueue_assets', 20 );

/**
 * Front-end: keep AdSense rows in sync when infinite scroll appends posts.
 *
 * @return void
 */
function vdn_home_feed_ads_enqueue_infinite_script() {
	if ( ! vdn_home_feed_should_render_adsense_rows() ) {
		return;
	}
	$handle = 'vdn-home-feed-ads-infinite';
	$src    = get_stylesheet_directory_uri() . '/js/vdn-home-feed-ads-infinite.js';
	$path   = get_stylesheet_directory() . '/js/vdn-home-feed-ads-infinite.js';
	$ver    = '1.0.0';
	if ( is_readable( $path ) ) {
		$mtime = filemtime( $path );
		if ( false !== $mtime ) {
			$ver = (string) $mtime;
		}
	}
	wp_enqueue_script( $handle, $src, array(), $ver, true );
	$blog_layout = get_option( 'mvp_blog_layout', '' );
	$layout      = ( '1' === (string) $blog_layout ) ? 'col' : 'row';
	$slots = vdn_home_feed_get_adsense_slots();
	wp_localize_script(
		$handle,
		'vdnHomeFeedAds',
		array(
			'every'   => vdn_home_feed_sanitize_every( get_theme_mod( 'vdn_home_feed_ad_every', 10 ) ),
			'client'  => vdn_home_feed_get_adsense_client_id(),
			'slots'   => array_map( 'strval', $slots ),
			'slot'    => vdn_home_feed_get_slot_for_placement( 1, $slots ),
			'layout'  => $layout,
			'label'   => esc_html__( 'Advertisement', 'zox-news' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'vdn_home_feed_ads_enqueue_infinite_script', 25 );

/**
 * Resolve AdSense client id consistent with core AdFusion helpers when available.
 *
 * @return string
 */
function vdn_home_feed_get_adsense_client_id() {
	$raw = get_option( 'adf_google_adsense_id', '' );
	if ( ! is_string( $raw ) ) {
		return '';
	}
	$raw = trim( $raw );
	if ( function_exists( 'adf_normalize_adsense_client_id' ) ) {
		return trim( adf_normalize_adsense_client_id( $raw ) );
	}
	// Loose fallback when helper not loaded.
	return sanitize_text_field( $raw );
}

/**
 * First resolved slot (Customizer / options / legacy filter).
 *
 * @return string First slot ID or empty.
 */
function vdn_home_feed_get_resolved_adsense_slot() {
	$slots = vdn_home_feed_get_adsense_slots();
	return vdn_home_feed_get_slot_for_placement( 1, $slots );
}

/**
 * Whether home-feed AdSense rows are configured on this load (Customizer + Pub + Slot + guards).
 *
 * @return bool
 */
function vdn_home_feed_should_render_adsense_rows() {
	if ( ! vdn_home_feed_is_home_feed_page() ) {
		return false;
	}
	if ( ! vdn_home_feed_sanitize_enable( get_theme_mod( 'vdn_home_feed_ad_enable', false ) ) ) {
		return false;
	}
	if ( function_exists( 'adfusion_premium' ) && adfusion_premium()->is_user_ad_free() ) {
		return false;
	}
	if ( ! apply_filters( 'adfusion_allow_google_ads', true ) ) {
		return false;
	}
	$pub = vdn_home_feed_get_adsense_client_id();
	if ( '' === $pub ) {
		return false;
	}
	return array() !== vdn_home_feed_get_adsense_slots();
}

/**
 * Echo `adsbygoogle.js` when the home-feed inject is on but Core AdFusion would not load the script.
 *
 * @return void
 */
function vdn_home_feed_maybe_echo_adsense_script() {
	if ( ! vdn_home_feed_should_render_adsense_rows() ) {
		return;
	}
	$adf_enabled  = get_option( 'adf_google_ads_enabled' );
	$adf_fallback = get_option( 'adf_google_fallback_enabled' );
	// Core AdFusion_Public already prints this tag when Ads or Fallback is on — avoid duplicate loaders.
	if ( '1' === $adf_enabled || '1' === $adf_fallback ) {
		return;
	}
	$pub_for_js = vdn_home_feed_get_adsense_client_id();
	if ( '' === $pub_for_js ) {
		return;
	}
	$adsense_js = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . rawurlencode( $pub_for_js );
	// Same pattern as AdFusion_Public::maybe_enqueue_adsense_script().
	// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Theme-only AdSense; mirrors AdFusion.
	echo '<script async src="' . esc_url( $adsense_js ) . '" crossorigin="anonymous"></script>' . "\n";
}
add_action( 'wp_head', 'vdn_home_feed_maybe_echo_adsense_script', 2 );

/**
 * Increment story index and optionally echo `<li>` with one AdSense block.
 *
 * @param string $layout Grid style: `col` (narrow feed) vs `row` (wide strip).
 * @return void
 */
function vdn_home_feed_maybe_render_ad_li( $layout ) {
	if ( ! vdn_home_feed_is_home_feed_page() ) {
		return;
	}
	if ( ! isset( $GLOBALS['vdn_home_feed_story_count'] ) ) {
		$GLOBALS['vdn_home_feed_story_count'] = 0;
	}

	++$GLOBALS['vdn_home_feed_story_count'];

	if ( ! vdn_home_feed_sanitize_enable( get_theme_mod( 'vdn_home_feed_ad_enable', false ) ) ) {
		return;
	}
	if ( function_exists( 'adfusion_premium' ) && adfusion_premium()->is_user_ad_free() ) {
		return;
	}
	if ( ! apply_filters( 'adfusion_allow_google_ads', true ) ) {
		return;
	}

	$every  = vdn_home_feed_sanitize_every( get_theme_mod( 'vdn_home_feed_ad_every', 10 ) );
	$slots  = vdn_home_feed_get_adsense_slots();
	$pub    = vdn_home_feed_get_adsense_client_id();

	if ( array() === $slots || '' === $pub ) {
		return;
	}

	$counter = (int) $GLOBALS['vdn_home_feed_story_count'];
	if ( $every < 1 || 0 !== ( $counter % $every ) ) {
		return;
	}

	$placement = (int) ( $counter / $every );
	if ( $placement < 1 ) {
		$placement = 1;
	}
	$slot = vdn_home_feed_get_slot_for_placement( $placement, $slots );
	if ( '' === $slot ) {
		return;
	}

	if ( ! in_array( $layout, array( 'col', 'row' ), true ) ) {
		$layout = 'col';
	}
	// Avoid `mvp-blog-story-col`/`left`/float/grid column classes on ad `<li>`s — they constrain width on small screens below AdSense minimums (blank slots).
	$item_class = ( 'col' === $layout )
		? 'relative vdn-home-feed-ad vdn-home-feed-ad--col'
		: 'relative vdn-home-feed-ad vdn-home-feed-ad--row';

	// Match AdFusion_Public markup. Feed units are filled by `vdn-home-feed-ads-infinite.js` only.
	// `data-adf-triggered="0"` excludes these from AdFusion’s global `ins.adsbygoogle:not([data-adf-triggered])`
	// scan (attribute present → skipped), which avoids a race/empty push on narrow mobile viewports.
	$inner = '<div class="vdn-home-feed-adfusion-google vdn-google-fallback-ad" style="text-align:center;margin:15px 0;clear:both;">';
	$inner .= '<ins class="adsbygoogle"
				 style="display:block"
				 data-vdn-home-feed="1"
				 data-adf-triggered="0"
				 data-ad-client="' . esc_attr( $pub ) . '"
				 data-ad-slot="' . esc_attr( $slot ) . '"
				 data-ad-format="auto"
				 data-full-width-responsive="true"></ins>';
	$inner .= '</div>';

	printf(
		'<li class="%s" role="presentation" aria-hidden="true"><span class="screen-reader-text">%s</span>%s</li>',
		esc_attr( $item_class ),
		esc_html__( 'Advertisement', 'zox-news' ),
		$inner // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_attr() parts.
	);
}
