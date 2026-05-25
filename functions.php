<?php
/**
 * Vicksburg Daily News - functions.php
 * Security Check: Prevents direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/////////////////////////////////////
// Suppress only known-noisy deprecated_function notices (e.g. create_wrapper
// from a third-party plugin). Other deprecations still surface when WP_DEBUG.
/////////////////////////////////////

add_filter(
	'deprecated_function_trigger_error',
	static function ( $trigger, $function_name = '', $replacement = '', $version = '' ) {
		unset( $replacement, $version );
		$suppress_functions = array( 'create_wrapper' );
		if ( is_string( $function_name ) && in_array( $function_name, $suppress_functions, true ) ) {
			return false;
		}
		return $trigger;
	},
	10,
	4
);

if ( ! function_exists( 'mvp_home_body_classes' ) ) {
	/**
	 * Body classes for Home template layout tweaks (wider feed, optional 3-column grid).
	 *
	 * @param string[] $classes Body classes.
	 * @return string[]
	 */
	function mvp_home_body_classes( $classes ) {
		if ( is_page_template( 'page-home.php' ) ) {
			$classes[] = 'mvp-home-wider-feed';
			// Theme option: "1" = multi-column card grid (see admin Blog Layout).
			$blog_layout = get_option( 'mvp_blog_layout' );
			if ( (string) $blog_layout === '1' ) {
				$classes[] = 'mvp-home-blog-3cols';
			}
		}
		return $classes;
	}
}
add_filter( 'body_class', 'mvp_home_body_classes' );

if ( ! function_exists( 'zox_news_esc_url_option' ) ) {
	/**
	 * Escape a URL from get_option() without passing null/non-strings to esc_url() (PHP 8.1+).
	 *
	 * @param string $option_name Option name.
	 * @param string $default     Default when option is missing or not a string.
	 * @return string
	 */
	function zox_news_esc_url_option( $option_name, $default = '' ) {
		$value = get_option( $option_name, $default );
		if ( ! is_string( $value ) ) {
			$value = is_string( $default ) ? $default : '';
		}
		return esc_url( $value );
	}
}

if ( ! function_exists( 'vdn_yoast_seo_active' ) ) {
	/**
	 * Whether Yoast SEO is active (Open Graph, Twitter cards, article JSON-LD delegated to Yoast).
	 *
	 * @return bool
	 */
	function vdn_yoast_seo_active() {
		return defined( 'WPSEO_VERSION' ) || class_exists( 'WPSEO_Options', false );
	}
}

/////////////////////////////////////
// Theme Setup
/////////////////////////////////////

if ( ! function_exists( 'mvp_setup' ) ) {
function mvp_setup(){
    // Add theme support for post formats
    add_theme_support( 'post-formats', array( 'gallery', 'video' ) );
    
    // Add theme support for HTML5 markup
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    
    // Add theme support for responsive embeds
    add_theme_support( 'responsive-embeds' );
    
    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    // Add theme support for wide and full width blocks
    add_theme_support( 'align-wide' );
    
    // Add theme support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'css/editor-style.css' );

    // Add theme support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

}
}
add_action('after_setup_theme', 'mvp_setup');

/** Clear cached theme CSS whenever this theme is activated (FTP upload alone does not run this). */
add_action(
	'after_switch_theme',
	static function () {
		delete_transient( 'mvp_dynamic_css' );
	}
);

require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/trust-a11y.php';
require_once get_template_directory() . '/inc/plugin-compat.php';
require_once get_template_directory() . '/inc/home-sidebar-recent-sources.php';
require_once get_template_directory() . '/inc/home-sidebar-recent-more.php';

// Load text domain function - defined early
if ( ! function_exists( 'mvp_load_textdomain' ) ) {
function mvp_load_textdomain(){
    load_theme_textdomain('zox-news', get_template_directory() . '/languages');

    $locale = get_locale();
    $locale_file = get_template_directory() . "/languages/$locale.php";
    if ( is_readable( $locale_file ) )
        require_once( $locale_file );
}
}

// Load text domain on init with priority 10 (standard priority)
// WordPress 6.7.0+ requires translations to load at init or later
// Using priority 10 (default) ensures we don't interfere with plugin initialization
add_action('init', 'mvp_load_textdomain', 10);

/////////////////////////////////////
// Theme Options
/////////////////////////////////////

define('mvp', 'white');
if ( ! defined( 'THEMEPREFIX' ) ) {
    define( 'THEMEPREFIX', 'mvp' );
}

// Initialize theme options on init hook (after text domain is loaded)
// Priority 20 ensures text domain loads first (priority 10)
if ( ! function_exists( 'mvp_init_theme_options' ) ) {
function mvp_init_theme_options() {
    // Only load admin files in admin context
    if ( is_admin() ) {
        require_once get_template_directory() . '/admin/admin-page.php';
        $options = require get_template_directory() . '/admin/options.php';
        if ( ! is_array( $options ) && isset( $GLOBALS['mvp_theme_options'] ) ) {
            $options = $GLOBALS['mvp_theme_options'];
        }
        if ( ! is_array( $options ) ) {
            $options = array();
        }
        $GLOBALS['options_page'] = new WhitelabelOptions( 'Vicksburg Daily News Options', 'zox-news-options', 'mvp', 'themes.php', null, 'edit_theme_options', null, true, false, false, $options );
    }
}
}
add_action('init', 'mvp_init_theme_options', 20);

if ( !function_exists( 'mvp_fonts_url' ) ) {
function mvp_fonts_url() {

$mvp_featured_font = get_option('mvp_featured_font');
$mvp_title_font = get_option('mvp_title_font');
$mvp_heading_font = get_option('mvp_heading_font');
$mvp_content_font = get_option('mvp_content_font');
$mvp_para_font = get_option('mvp_para_font');
$mvp_menu_font = get_option('mvp_menu_font');
$font_url = '';

    if ( 'off' !== _x( 'on', 'Google font: on or off', 'zox-news' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Advent Pro:700&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|Open Sans:700&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|Anton:400&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_featured_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_title_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_heading_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_content_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_para_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese|' .  $mvp_menu_font . ':100,200,300,400,500,600,700,800,900&subset=latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese&display=swap' ), "//fonts.googleapis.com/css" );
    }
    return $font_url;
}
}

// Helper function to sanitize CSS values
if ( !function_exists( 'mvp_sanitize_css_value' ) ) {
function mvp_sanitize_css_value( $value ) {
    if ( empty( $value ) ) {
        return '';
    }
    // Sanitize URL values
    if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
        return esc_url( $value );
    }
    // Sanitize color values (hex, rgb, rgba, named colors)
    if ( preg_match( '/^#[a-f0-9]{3,6}$/i', $value ) ||
         preg_match( '/^rgba?\([^)]+\)$/i', $value ) ||
         preg_match( '/^[a-z]+$/i', $value ) ) {
        return sanitize_text_field( $value );
    }
    // For other values, use sanitize_text_field
    return sanitize_text_field( $value );
}
}

// Generate dynamic CSS (cached version)
if ( !function_exists( 'mvp_generate_dynamic_css' ) ) {
function mvp_generate_dynamic_css() {
    $wallad = mvp_sanitize_css_value( get_option('mvp_wall_ad') );
    $primarycolor = mvp_sanitize_css_value( get_option('mvp_primary_color') );
    $secondcolor = mvp_sanitize_css_value( get_option('mvp_second_color') );
    $topnavbg = mvp_sanitize_css_value( get_option('mvp_top_nav_bg') );
    $topnavtext = mvp_sanitize_css_value( get_option('mvp_top_nav_text') );
    $topnavhover = mvp_sanitize_css_value( get_option('mvp_top_nav_hover') );
    $botnavbg = mvp_sanitize_css_value( get_option('mvp_bot_nav_bg') );
    $botnavtext = mvp_sanitize_css_value( get_option('mvp_bot_nav_text') );
    $botnavhover = mvp_sanitize_css_value( get_option('mvp_bot_nav_hover') );
    $link = mvp_sanitize_css_value( get_option('mvp_link_color') );
    $link2 = mvp_sanitize_css_value( get_option('mvp_link2_color') );
    $featured_font = sanitize_text_field( get_option('mvp_featured_font') );
    $title_font = sanitize_text_field( get_option('mvp_title_font') );
    $heading_font = sanitize_text_field( get_option('mvp_heading_font') );
    $content_font = sanitize_text_field( get_option('mvp_content_font') );
    $para_font = sanitize_text_field( get_option('mvp_para_font') );
    $menu_font = sanitize_text_field( get_option('mvp_menu_font') );
    $mvp_customcss = wp_kses_post( get_option('mvp_customcss') ?? '' );
        $mvp_theme_options = "

#mvp-wallpaper {
    background: url($wallad) no-repeat 50% 0;
    }

#mvp-foot-copy a {
    color: $link;
    }

#mvp-content-main p a,
.mvp-post-add-main p a {
    box-shadow: inset 0 -4px 0 $link;
    }

#mvp-content-main p a:hover,
.mvp-post-add-main p a:hover {
    background: $link;
    }

a,
a:visited,
.post-info-name a,
.woocommerce .woocommerce-breadcrumb a {
    color: $link2;
    }

#mvp-side-wrap a:hover {
    color: $link2;
    }

.mvp-fly-top:hover,
.mvp-vid-box-wrap,
ul.mvp-soc-mob-list li.mvp-soc-mob-com {
    background: $primarycolor;
    }

nav.mvp-fly-nav-menu ul li.menu-item-has-children:after,
.mvp-feat1-left-wrap span.mvp-cd-cat,
.mvp-widget-feat1-top-story span.mvp-cd-cat,
.mvp-widget-feat2-left-cont span.mvp-cd-cat,
.mvp-widget-dark-feat span.mvp-cd-cat,
.mvp-widget-dark-sub span.mvp-cd-cat,
.mvp-vid-wide-text span.mvp-cd-cat,
.mvp-feat2-top-text span.mvp-cd-cat,
.mvp-feat3-main-story span.mvp-cd-cat,
.mvp-feat3-sub-text span.mvp-cd-cat,
.mvp-feat4-main-text span.mvp-cd-cat,
.woocommerce-message:before,
.woocommerce-info:before,
.woocommerce-message:before {
    color: $primarycolor;
    }

#searchform input,
.mvp-authors-name {
    border-bottom: 1px solid $primarycolor;
    }

.mvp-fly-top:hover {
    border-top: 1px solid $primarycolor;
    border-left: 1px solid $primarycolor;
    border-bottom: 1px solid $primarycolor;
    }

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt,
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
    background-color: $primarycolor;
    }

.woocommerce-error,
.woocommerce-info,
.woocommerce-message {
    border-top-color: $primarycolor;
    }

ul.mvp-feat1-list-buts li.active span.mvp-feat1-list-but,
span.mvp-widget-home-title,
span.mvp-post-cat,
span.mvp-feat1-pop-head {
    background: $secondcolor;
    }

.woocommerce span.onsale {
    background-color: $secondcolor;
    }

.mvp-widget-feat2-side-more-but,
.woocommerce .star-rating span:before,
span.mvp-prev-next-label,
.mvp-cat-date-wrap .sticky {
    color: $secondcolor !important;
    }

#mvp-main-nav-top,
#mvp-fly-wrap,
.mvp-soc-mob-right,
#mvp-main-nav-small-cont {
    background: $topnavbg;
    }

#mvp-main-nav-small .mvp-fly-but-wrap span,
#mvp-main-nav-small .mvp-search-but-wrap span,
.mvp-nav-top-left .mvp-fly-but-wrap span,
#mvp-fly-wrap .mvp-fly-but-wrap span {
    background: $topnavtext;
    }

.mvp-nav-top-right .mvp-nav-search-but,
span.mvp-fly-soc-head,
.mvp-soc-mob-right i,
#mvp-main-nav-small span.mvp-nav-search-but,
#mvp-main-nav-small .mvp-nav-menu ul li a  {
    color: $topnavtext;
    }

#mvp-main-nav-small .mvp-nav-menu ul li.menu-item-has-children a:after {
    border-color: $topnavtext transparent transparent transparent;
    }

#mvp-nav-top-wrap span.mvp-nav-search-but:hover,
#mvp-main-nav-small span.mvp-nav-search-but:hover {
    color: $topnavhover;
    }

#mvp-nav-top-wrap .mvp-fly-but-wrap:hover span,
#mvp-main-nav-small .mvp-fly-but-wrap:hover span,
span.mvp-woo-cart-num:hover {
    background: $topnavhover;
    }

#mvp-main-nav-bot-cont {
    background: $botnavbg;
    }

#mvp-nav-bot-wrap .mvp-fly-but-wrap span,
#mvp-nav-bot-wrap .mvp-search-but-wrap span {
    background: $botnavtext;
    }

#mvp-nav-bot-wrap span.mvp-nav-search-but,
#mvp-nav-bot-wrap .mvp-nav-menu ul li a {
    color: $botnavtext;
    }

#mvp-nav-bot-wrap .mvp-nav-menu ul li.menu-item-has-children a:after {
    border-color: $botnavtext transparent transparent transparent;
    }

.mvp-nav-menu ul li:hover a {
    border-bottom: 5px solid $botnavhover;
    }

#mvp-nav-bot-wrap .mvp-fly-but-wrap:hover span {
    background: $botnavhover;
    }

#mvp-nav-bot-wrap span.mvp-nav-search-but:hover {
    color: $botnavhover;
    }

body,
.mvp-feat1-feat-text p,
.mvp-feat2-top-text p,
.mvp-feat3-main-text p,
.mvp-feat3-sub-text p,
#searchform input,
.mvp-author-info-text,
span.mvp-post-excerpt,
.mvp-nav-menu ul li ul.sub-menu li a,
nav.mvp-fly-nav-menu ul li a,
.mvp-ad-label,
span.mvp-feat-caption,
.mvp-post-tags a,
.mvp-post-tags a:visited,
span.mvp-author-box-name a,
#mvp-author-box-text p,
.mvp-post-gallery-text p,
ul.mvp-soc-mob-list li span,
#comments,
h3#reply-title,
h2.comments,
#mvp-foot-copy p,
span.mvp-fly-soc-head,
.mvp-post-tags-header,
span.mvp-prev-next-label,
span.mvp-post-add-link-but,
#mvp-comments-button a,
#mvp-comments-button span.mvp-comment-but-text,
.woocommerce ul.product_list_widget span.product-title,
.woocommerce ul.product_list_widget li a,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta,
.woocommerce div.product p.price,
.woocommerce div.product p.price ins,
.woocommerce div.product p.price del,
.woocommerce ul.products li.product .price del,
.woocommerce ul.products li.product .price ins,
.woocommerce ul.products li.product .price,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button,
.woocommerce .widget_price_filter .price_slider_amount .button,
.woocommerce span.onsale,
.woocommerce-review-link,
#woo-content p.woocommerce-result-count,
.woocommerce div.product .woocommerce-tabs ul.tabs li a,
a.mvp-inf-more-but,
span.mvp-cont-read-but,
span.mvp-cd-cat,
span.mvp-cd-date,
.mvp-feat4-main-text p,
span.mvp-woo-cart-num,
span.mvp-widget-home-title2,
.wp-caption,
#mvp-content-main p.wp-caption-text,
.gallery-caption,
.mvp-post-add-main p.wp-caption-text,
#bbpress-forums,
#bbpress-forums p,
.protected-post-form input,
#mvp-feat6-text p {
    font-family: '$content_font', sans-serif;
    }

.mvp-blog-story-text p,
span.mvp-author-page-desc,
#mvp-404 p,
.mvp-widget-feat1-bot-text p,
.mvp-widget-feat2-left-text p,
.mvp-flex-story-text p,
.mvp-search-text p,
#mvp-content-main p,
.mvp-post-add-main p,
#mvp-content-main ul li,
#mvp-content-main ol li,
.rwp-summary,
.rwp-u-review__comment,
.mvp-feat5-mid-main-text p,
.mvp-feat5-small-main-text p {
    font-family: '$para_font', sans-serif;
    }

.mvp-nav-menu ul li a,
#mvp-foot-menu ul li a {
    font-family: '$menu_font', sans-serif;
    }


.mvp-feat1-sub-text h2,
.mvp-feat1-pop-text h2,
.mvp-feat1-list-text h2,
.mvp-widget-feat1-top-text h2,
.mvp-widget-feat1-bot-text h2,
.mvp-widget-dark-feat-text h2,
.mvp-widget-dark-sub-text h2,
.mvp-widget-feat2-left-text h2,
.mvp-widget-feat2-right-text h2,
.mvp-blog-story-text h2,
.mvp-flex-story-text h2,
.mvp-vid-wide-more-text p,
.mvp-prev-next-text p,
.mvp-related-text,
.mvp-post-more-text p,
h2.mvp-authors-latest a,
.mvp-feat2-bot-text h2,
.mvp-feat3-sub-text h2,
.mvp-feat3-main-text h2,
.mvp-feat4-main-text h2,
.mvp-feat5-text h2,
.mvp-feat5-mid-main-text h2,
.mvp-feat5-small-main-text h2,
.mvp-feat5-mid-sub-text h2,
#mvp-feat6-text h2,
.alp-related-posts-wrapper .alp-related-post .post-title {
    font-family: '$featured_font', sans-serif;
    }

.mvp-feat2-top-text h2,
.mvp-feat1-feat-text h2,
h1.mvp-post-title,
h1.mvp-post-title-wide,
.mvp-drop-nav-title h4,
#mvp-content-main blockquote p,
.mvp-post-add-main blockquote p,
#mvp-404 h1,
#woo-content h1.page-title,
.woocommerce div.product .product_title,
.woocommerce ul.products li.product h3,
.alp-related-posts .current .post-title {
    font-family: '$title_font', sans-serif;
    }

span.mvp-feat1-pop-head,
.mvp-feat1-pop-text:before,
span.mvp-feat1-list-but,
span.mvp-widget-home-title,
.mvp-widget-feat2-side-more,
span.mvp-post-cat,
span.mvp-page-head,
h1.mvp-author-top-head,
.mvp-authors-name,
#mvp-content-main h1,
#mvp-content-main h2,
#mvp-content-main h3,
#mvp-content-main h4,
#mvp-content-main h5,
#mvp-content-main h6,
.woocommerce .related h2,
.woocommerce div.product .woocommerce-tabs .panel h2,
.woocommerce div.product .product_title,
.mvp-feat5-side-list .mvp-feat1-list-img:after {
    font-family: '$heading_font', sans-serif;
    }

    ";

    if(get_option('mvp_wall_ad')) {
    $mvp_wall_ad_css = "
    @media screen and (min-width: 1200px) {
    #mvp-site {
        float: none;
        margin: 0 auto;
        width: 1200px;
        }
    #mvp-leader-wrap {
        left: auto;
        width: 1200px;
        }
    .mvp-main-box {
        width: 1160px;
        }
    #mvp-main-nav-top,
    #mvp-main-nav-bot,
    #mvp-main-nav-small {
        width: 1200px;
        }
    }
        ";
    }

    $mvp_site_skin = get_option('mvp_site_skin');
    if($mvp_site_skin == '1') {
    $mvp_site_skin_css = "
    #mvp-main-nav-top {
        background: #fff;
        padding: 15px 0 0;
        }
    #mvp-fly-wrap,
    .mvp-soc-mob-right,
    #mvp-main-nav-small-cont {
        background: #fff;
        }
    #mvp-main-nav-small .mvp-fly-but-wrap span,
    #mvp-main-nav-small .mvp-search-but-wrap span,
    .mvp-nav-top-left .mvp-fly-but-wrap span,
    #mvp-fly-wrap .mvp-fly-but-wrap span {
        background: #000;
        }
    .mvp-nav-top-right .mvp-nav-search-but,
    span.mvp-fly-soc-head,
    .mvp-soc-mob-right i,
    #mvp-main-nav-small span.mvp-nav-search-but,
    #mvp-main-nav-small .mvp-nav-menu ul li a  {
        color: #000;
        }
    #mvp-main-nav-small .mvp-nav-menu ul li.menu-item-has-children a:after {
        border-color: #000 transparent transparent transparent;
        }
    .mvp-feat1-feat-text h2,
    h1.mvp-post-title,
    .mvp-feat2-top-text h2,
    .mvp-feat3-main-text h2,
    #mvp-content-main blockquote p,
    .mvp-post-add-main blockquote p {
        font-family: 'Anton', sans-serif;
        font-weight: 400;
        letter-spacing: normal;
        }
    .mvp-feat1-feat-text h2,
    .mvp-feat2-top-text h2,
    .mvp-feat3-main-text h2 {
        line-height: 1;
        text-transform: uppercase;
        }
        ";
    }

    $mvp_nav_skin = get_option('mvp_nav_skin');
    $mvp_site_skin = get_option('mvp_site_skin');
    if($mvp_nav_skin == "1" || $mvp_site_skin == "1") {
    $mvp_nav_skin_css = "
    span.mvp-nav-soc-but,
    ul.mvp-fly-soc-list li a,
    span.mvp-woo-cart-num {
        background: rgba(0,0,0,.8);
        }
    span.mvp-woo-cart-icon {
        color: rgba(0,0,0,.8);
        }
    nav.mvp-fly-nav-menu ul li,
    nav.mvp-fly-nav-menu ul li ul.sub-menu {
        border-top: 1px solid rgba(0,0,0,.1);
        }
    nav.mvp-fly-nav-menu ul li a {
        color: #000;
        }
    .mvp-drop-nav-title h4 {
        color: #000;
        }
        ";
    }

    $mvp_nav_layout = get_option('mvp_nav_layout');
    if( $mvp_nav_layout == "1" ) {
    $mvp_nav_layout_css = "
    #mvp-main-body-wrap {
        padding-top: 20px;
        }
    #mvp-feat2-wrap,
    #mvp-feat4-wrap,
    #mvp-post-feat-img-wide,
    #mvp-vid-wide-wrap {
        margin-top: -20px;
        }
    @media screen and (max-width: 479px) {
        #mvp-main-body-wrap {
            padding-top: 15px;
            }
        #mvp-feat2-wrap,
        #mvp-feat4-wrap,
        #mvp-post-feat-img-wide,
        #mvp-vid-wide-wrap {
            margin-top: -15px;
            }
        }
        ";
    }

    $mvp_prime_skin = get_option('mvp_prime_skin');
    if($mvp_prime_skin == "1") {
    $mvp_prime_skin_css = "
    .mvp-vid-box-wrap,
    .mvp-feat1-left-wrap span.mvp-cd-cat,
    .mvp-widget-feat1-top-story span.mvp-cd-cat,
    .mvp-widget-feat2-left-cont span.mvp-cd-cat,
    .mvp-widget-dark-feat span.mvp-cd-cat,
    .mvp-widget-dark-sub span.mvp-cd-cat,
    .mvp-vid-wide-text span.mvp-cd-cat,
    .mvp-feat2-top-text span.mvp-cd-cat,
    .mvp-feat3-main-story span.mvp-cd-cat {
        color: #fff;
        }
        ";
    }

    $mvp_para_lead = get_option('mvp_para_lead');
    if ($mvp_para_lead == "true") {
    if (isset($mvp_para_lead)) { } } else {
    $mvp_para_lead_css = "
    #mvp-leader-wrap {
        position: relative;
        }
    #mvp-site-main {
        margin-top: 0;
        }
    #mvp-leader-wrap {
        top: 0 !important;
        }
        ";
    }

    $mvp_infinite_scroll = get_option('mvp_infinite_scroll');
    if ($mvp_infinite_scroll == "true") {
    if (isset($mvp_infinite_scroll)) {
    $mvp_infinite_scroll_css = "
    .mvp-nav-links {
        display: none;
        }
    .mvp-inf-more-but {
        display: none !important;
        }
    ";
    } }

    $mvp_respond = get_option('mvp_respond');
    if ($mvp_respond == "true") {
    if (isset($mvp_respond)) { } } else {
    $mvp_respond_css = "
    #mvp-site,
    #mvp-main-nav-top,
    #mvp-main-nav-bot {
        min-width: 1240px;
        }
        ";
    }

    $mvp_alp = get_option('mvp_alp');
    if ($mvp_alp !== "true") {
    $mvp_cont_read = get_option('mvp_cont_read');
    if ($mvp_cont_read == "true") {
    if (isset($mvp_cont_read)) {
    $mvp_cont_read_css = "
    @media screen and (max-width: 479px) {
        .single #mvp-content-body-top {
            max-height: 400px;
            }
        .single .mvp-cont-read-but-wrap {
            display: inline;
            }
        }
        ";
    } } }

    $mvp_rtl = get_option('mvp_rtl'); if ($mvp_rtl == "true") {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '1' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '1' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '7' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '7' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '1' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '7' ) || $mvp_post_temp == "temp2" || $mvp_post_temp == "temp8" ) {
    $mvp_post_side_css = "
    .mvp-post-main-out,
    .mvp-post-main-in {
        margin-left: 0 !important;
        }
    #mvp-post-feat-img img {
        width: 100%;
        }
    #mvp-content-wrap,
    #mvp-post-add-box {
        float: none;
        margin: 0 auto;
        max-width: 750px;
        }
        ";
    }
    }
    } else {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '1' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '1' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '7' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '7' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '1' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '7' ) || $mvp_post_temp == "temp2" || $mvp_post_temp == "temp8" ) {
    $mvp_post_side_css = "
    .mvp-post-main-out,
    .mvp-post-main-in {
        margin-right: 0 !important;
        }
    #mvp-post-feat-img img {
        width: 100%;
        }
    #mvp-content-wrap,
    #mvp-post-add-box {
        float: none;
        margin: 0 auto;
        max-width: 750px;
        }
        ";
    }
    }
    }

    $mvp_rtl = get_option('mvp_rtl'); if ($mvp_rtl == "true") {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '3' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '3' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '3' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '5' ) || $mvp_post_temp == "temp4" || $mvp_post_temp == "temp6" ) {
    $mvp_post_side2_css = "
    .mvp-post-main-out,
    .mvp-post-main-in {
        margin-left: 0 !important;
        }
    #mvp-post-feat-img img {
        width: 100%;
        }
    #mvp-post-content,
    #mvp-post-add-box {
        float: none;
        margin: 0 auto;
        max-width: 750px;
        }
        ";
    }
    }
    } else {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '3' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '3' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '3' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '5' ) || $mvp_post_temp == "temp4" || $mvp_post_temp == "temp6" ) {
    $mvp_post_side2_css = "
    .mvp-post-main-out,
    .mvp-post-main-in {
        margin-right: 0 !important;
        }
    #mvp-post-feat-img img {
        width: 100%;
        }
    #mvp-post-content,
    #mvp-post-add-box {
        float: none;
        margin: 0 auto;
        max-width: 750px;
        }
        ";
    }
    }
    }

    if ( is_single() ) {
    $mvp_rtl = get_option('mvp_rtl'); if ($mvp_rtl == "true") {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '4' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '4' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '4' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '5' ) || $mvp_post_temp == "temp5" || $mvp_post_temp == "temp6" ) {
    $mvp_post_side3_css = "
    .mvp-nav-soc-wrap {
        margin-top: -15px;
        height: 30px;
        }
    span.mvp-nav-soc-but {
        font-size: 16px;
        padding-top: 7px;
        width: 30px;
        height: 23px;
        }
    #mvp-main-nav-top {
        padding: 10px 0 !important;
        height: 30px !important;
        z-index: 9999;
        }
    .mvp-nav-top-wrap,
    .mvp-nav-top-mid {
        height: 30px !important;
        }
    .mvp-nav-top-mid img {
        height: 100% !important;
        }
    #mvp-main-nav-bot {
        border-bottom: none;
        display: none;
        height: 0;
        }
    .mvp-nav-top-mid img {
        margin-right: 0;
        }
    .mvp-nav-top-left-out {
        margin-right: -200px;
        }
    .mvp-nav-top-left-in {
        margin-right: 200px;
        }
    .mvp-nav-top-left {
        display: block;
        }
        ";
    }
    }
    } else {
    global $post; if (!empty( $post )) {
    $mvp_post_layout = get_option('mvp_post_layout');
    $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true);
    if( ( empty($mvp_post_temp) && $mvp_post_layout == '4' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '4' ) || ( empty($mvp_post_temp) && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "def" && $mvp_post_layout == '5' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '4' ) || ( $mvp_post_temp == "global" && $mvp_post_layout == '5' ) || $mvp_post_temp == "temp5" || $mvp_post_temp == "temp6" ) {
    $mvp_post_side3_css = "
    .mvp-nav-soc-wrap {
        margin-top: -15px;
        height: 30px;
        }
    span.mvp-nav-soc-but {
        font-size: 16px;
        padding-top: 7px;
        width: 30px;
        height: 23px;
        }
    #mvp-main-nav-top {
        padding: 10px 0 !important;
        height: 30px !important;
        z-index: 9999;
        }
    .mvp-nav-top-wrap,
    .mvp-nav-top-mid {
        height: 30px !important;
        }
    .mvp-nav-top-mid img {
        height: 100% !important;
        }
    #mvp-main-nav-bot {
        border-bottom: none;
        display: none;
        height: 0;
        }
    .mvp-nav-top-mid img {
        margin-left: 0;
        }
    .mvp-nav-top-left-out {
        margin-left: -200px;
        }
    .mvp-nav-top-left-in {
        margin-left: 200px;
        }
    .mvp-nav-top-left {
        display: block;
        }
        ";
    }
    }
    }
    }
    
    $mvp_alp = get_option('mvp_alp');
    $mvp_alp_side = get_option('mvp_alp_side');
    if ($mvp_alp == "true") {
    if (isset($mvp_alp)) {
        if ($mvp_alp_side == "0") {
    $mvp_alp_css = "
    .mvp-auto-post-grid {
        grid-template-columns: 340px minmax(0, auto);
    }
        ";
        } else if ($mvp_alp_side == "1") {
    $mvp_alp_css = "
    .mvp-alp-side {
        display: none;
    }
    .mvp-alp-soc-reg {
        display: block;
    }
    .mvp-auto-post-grid {
        grid-template-columns: minmax(0, auto) 320px;
        grid-column-gap: 60px;
    }
    @media screen and (max-width: 1199px) {
        .mvp-auto-post-grid {
            grid-column-gap: 30px;
        }
    }
        ";
        } else {
    $mvp_alp_css = "
    .mvp-alp-side {
        display: none;
    }
    .mvp-alp-soc-reg {
        display: block;
    }
    .mvp-auto-post-grid {
        grid-template-columns: 100%;
        margin: 30px auto 0;
        max-width: 1000px;
    }
    .mvp-auto-post-main #mvp-content-body {
        float: none;
        margin: 0 auto;
        max-width: 740px;
    }
        ";
        }
    }
    }
    
    $mvp_alp_ad = get_option('mvp_alp_ad');
    if ( ! $mvp_alp_ad) {
    $mvp_alp_side_ad_css = "
    .alp-advert {
        display: none;
    }
    .alp-related-posts-wrapper .alp-related-posts .current {
        margin: 0 0 10px;
    }
        ";
    }

    if ($mvp_customcss) {
    $mvp_customcss_css = "
     $mvp_customcss
        ";
    }

    // Combine all CSS into a single string
    $combined_css = '';
    if (isset($mvp_theme_options)) { $combined_css .= $mvp_theme_options; }
    if (isset($mvp_wall_ad_css)) { $combined_css .= $mvp_wall_ad_css; }
    if (isset($mvp_prime_skin_css)) { $combined_css .= $mvp_prime_skin_css; }
    if (isset($mvp_site_skin_css)) { $combined_css .= $mvp_site_skin_css; }
    if (isset($mvp_nav_skin_css)) { $combined_css .= $mvp_nav_skin_css; }
    if (isset($mvp_nav_layout_css)) { $combined_css .= $mvp_nav_layout_css; }
    if (isset($mvp_para_lead_css)) { $combined_css .= $mvp_para_lead_css; }
    if (isset($mvp_infinite_scroll_css)) { $combined_css .= $mvp_infinite_scroll_css; }
    if (isset($mvp_respond_css)) { $combined_css .= $mvp_respond_css; }
    if (isset($mvp_cont_read_css)) { $combined_css .= $mvp_cont_read_css; }
    if (isset($mvp_post_side_css)) { $combined_css .= $mvp_post_side_css; }
    if (isset($mvp_post_side2_css)) { $combined_css .= $mvp_post_side2_css; }
    if (isset($mvp_post_side3_css)) { $combined_css .= $mvp_post_side3_css; }
    if (isset($mvp_alp_css)) { $combined_css .= $mvp_alp_css; }
    if (isset($mvp_alp_side_ad_css)) { $combined_css .= $mvp_alp_side_ad_css; }
    if (isset($mvp_customcss_css)) { $combined_css .= $mvp_customcss_css; }
    
    return $combined_css;
}
}

// Clear CSS cache when theme options are updated
if ( !function_exists( 'mvp_clear_css_cache' ) ) {
function mvp_clear_css_cache( $option_name ) {
    // Only clear cache for mvp_ options
    if ( strpos( $option_name, 'mvp_' ) === 0 ) {
        delete_transient( 'mvp_dynamic_css' );
    }
}
}
add_action( 'update_option', 'mvp_clear_css_cache', 10, 1 );

// Enqueue styles with cached CSS
if ( !function_exists( 'mvp_styles_method' ) ) {
function mvp_styles_method() {
    wp_enqueue_style(
        'mvp-custom-style',
        get_stylesheet_uri()
    );
    
    // Get cached CSS or generate new
    $cached_css = get_transient( 'mvp_dynamic_css' );
    if ( false === $cached_css ) {
        $cached_css = mvp_generate_dynamic_css();
        // Cache for 12 hours
        set_transient( 'mvp_dynamic_css', $cached_css, 12 * HOUR_IN_SECONDS );
    }
    
    // Add inline CSS if we have any
    if ( !empty( $cached_css ) ) {
        wp_add_inline_style( 'mvp-custom-style', wp_kses_post( $cached_css ?? '' ) );
    }
}
}
add_action( 'wp_enqueue_scripts', 'mvp_styles_method' );

/////////////////////////////////////
// Enqueue Javascript/CSS Files
/////////////////////////////////////

if ( ! function_exists( 'mvp_scripts_method' ) ) {
function mvp_scripts_method() {
    global $wp_styles;
    // Get theme version for cache busting
    $theme_version = wp_get_theme()->get('Version');
    
    wp_enqueue_style( 'mvp-reset', get_template_directory_uri() . '/css/reset.css', array(), $theme_version );
    wp_enqueue_style( 'fontawesome', get_stylesheet_directory_uri() . '/font-awesome/css/font-awesome.css', array(), $theme_version );
    
    // The lines below were commented out to stop PHP Warnings/Notices regarding IE conditional comments and missing dependencies
    // wp_enqueue_style( 'mvp-iecss', get_stylesheet_directory_uri() . '/css/iecss.css', array( 'mvp-style' ), $theme_version );
    // wp_enqueue_style( 'mvp-fonts', mvp_fonts_url(), array(), $theme_version );
    // $wp_styles->add_data( 'mvp-iecss', 'conditional', 'lt IE 10' );

    $mvp_rtl = get_option('mvp_rtl'); if ($mvp_rtl == "true") { if (isset($mvp_rtl)) {
    wp_enqueue_style( 'mvp-rtl', get_template_directory_uri() . '/css/rtl.css', array(), $theme_version );
    } }
    $mvp_respond = get_option('mvp_respond'); if ($mvp_respond == "true") { if (isset($mvp_respond)) {
    $mvp_rtl = get_option('mvp_rtl'); if ($mvp_rtl == "true") { if (isset($mvp_rtl)) {
    wp_enqueue_style( 'mvp-media-queries', get_template_directory_uri() . '/css/media-queries-rtl.css', array(), $theme_version );
    } } else {
    wp_enqueue_style( 'mvp-media-queries', get_template_directory_uri() . '/css/media-queries.css', array(), $theme_version );
    } } }
    // Infinite-scroll init runs as wp_add_inline_script( 'mvp-custom', ... ). The plugin MUST load before that.
    wp_register_script( 'infinitescroll', get_template_directory_uri() . '/js/jquery.infinitescroll.min.js', array( 'jquery' ), $theme_version, true );

    $mvp_infinite_scroll = get_option( 'mvp_infinite_scroll' );
    $mvp_infinite_on     = (
        isset( $mvp_infinite_scroll ) &&
        ( $mvp_infinite_scroll === 'true' || $mvp_infinite_scroll === true || '1' === (string) $mvp_infinite_scroll )
    );

    $mvp_custom_deps = array( 'jquery' );
    if ( $mvp_infinite_on ) {
        $mvp_custom_deps[] = 'infinitescroll';
    }

    wp_register_script( 'mvp-custom', get_template_directory_uri() . '/js/mvpcustom.js', $mvp_custom_deps, $theme_version, true );

    wp_register_script('zoxnews', get_template_directory_uri() . '/js/scripts.js', array('jquery'), $theme_version, true);
    wp_register_script('retina', get_template_directory_uri() . '/js/retina.js', array('jquery'), $theme_version, true);
    wp_register_script('flexslider', get_template_directory_uri() . '/js/flexslider.js', array('jquery'), $theme_version, true);
    wp_register_script('alp', get_template_directory_uri() . '/js/alp.js', array('jquery'), $theme_version, true);

    wp_enqueue_script('mvp-custom');
    wp_enqueue_script('zoxnews');
    wp_enqueue_script('retina');
    if ( is_single() ) wp_enqueue_script( 'flexslider' );
    $mvp_alp = get_option('mvp_alp'); if ($mvp_alp == "true") { if (isset($mvp_alp)) {
    if ( is_single() ) wp_enqueue_script( 'alp' );
    } }

    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
$mvp_nav_layout = get_option('mvp_nav_layout'); if( $mvp_nav_layout == "1" ) {

    $alp_side = get_option('mvp_alp');
    if ( is_single() && $alp_side !== "true" ) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function(){
    var leaderHeight = $("#mvp-leader-wrap").outerHeight();
    var navHeight = $("#mvp-main-head-wrap").outerHeight();
    var headerHeight = navHeight + leaderHeight;
    var previousScroll = 0;
    $(window).scroll(function(event){
            var scroll = $(this).scrollTop();
            if ($(window).scrollTop() > headerHeight){
                $("#mvp-main-nav-small").addClass("mvp-nav-small-fixed");
                $("#mvp-main-body-wrap").css("margin-top", navHeight );
            } else {
                $("#mvp-main-nav-small").removeClass("mvp-nav-small-fixed");
                $("#mvp-main-body-wrap").css("margin-top","0");
            }
            if ($(window).scrollTop() > headerHeight + 50){
                $("#mvp-main-nav-small").addClass("mvp-fixed");
                $("#mvp-main-nav-small").addClass("mvp-fixed-shadow");
                $(".mvp-fly-top").addClass("mvp-to-top");
                if(scroll < previousScroll) {
                    $("#mvp-main-nav-small").removeClass("mvp-soc-mob-up");
                    $("#mvp-soc-mob-wrap").removeClass("mvp-soc-mob-up");
                    $(".mvp-drop-nav-title").removeClass("mvp-nav-small-post");
                    $(".mvp-nav-menu").show();
                } else {
                    $("#mvp-main-nav-small").addClass("mvp-soc-mob-up");
                    $("#mvp-soc-mob-wrap").addClass("mvp-soc-mob-up");
                    $(".mvp-drop-nav-title").addClass("mvp-nav-small-post");
                    $(".mvp-nav-menu").hide();
                }
            } else {
                $("#mvp-main-nav-small").removeClass("mvp-fixed");
                $("#mvp-main-nav-small").removeClass("mvp-fixed-shadow");
                $(".mvp-fly-top").removeClass("mvp-to-top");
            }
            previousScroll = scroll;
    });
    });
    });
    ' );
    } else {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function(){
    var leaderHeight = $("#mvp-leader-wrap").outerHeight();
    var navHeight = $("#mvp-main-head-wrap").outerHeight();
    var headerHeight = navHeight + leaderHeight;
    var previousScroll = 0;
    $(window).scroll(function(event){
            var scroll = $(this).scrollTop();
            if ($(window).scrollTop() > headerHeight){
                $("#mvp-main-nav-small").addClass("mvp-nav-small-fixed");
                $("#mvp-main-body-wrap").css("margin-top", navHeight );
            } else {
                $("#mvp-main-nav-small").removeClass("mvp-nav-small-fixed");
                $("#mvp-main-body-wrap").css("margin-top","0");
            }
            if ($(window).scrollTop() > headerHeight + 50){
                $("#mvp-main-nav-small").addClass("mvp-fixed");
                $("#mvp-main-nav-small").addClass("mvp-fixed-shadow");
                $(".mvp-fly-top").addClass("mvp-to-top");
            } else {
                $("#mvp-main-nav-small").removeClass("mvp-fixed");
                $("#mvp-main-nav-small").removeClass("mvp-fixed-shadow");
                $(".mvp-fly-top").removeClass("mvp-to-top");
            }
            previousScroll = scroll;
    });
    });
    });
    ' );
    }

} else {
    $alp_side = get_option('mvp_alp');
    if ( is_single() && $alp_side !== "true" ) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function(){
    var leaderHeight = $("#mvp-leader-wrap").outerHeight();
    var logoHeight = $("#mvp-main-nav-top").outerHeight();
    var botHeight = $("#mvp-main-nav-bot").outerHeight();
    var navHeight = $("#mvp-main-head-wrap").outerHeight();
    var headerHeight = navHeight + leaderHeight;
    var aboveNav = leaderHeight + logoHeight;
    var totalHeight = logoHeight + botHeight;
    var previousScroll = 0;
    $(window).scroll(function(event){
            var scroll = $(this).scrollTop();
            if ($(window).scrollTop() > aboveNav){
                $("#mvp-main-nav-top").addClass("mvp-nav-small");
                $("#mvp-main-nav-bot").css("margin-top", logoHeight );
            } else {
                $("#mvp-main-nav-top").removeClass("mvp-nav-small");
                $("#mvp-main-nav-bot").css("margin-top","0");
            }
            if ($(window).scrollTop() > headerHeight){
                $("#mvp-main-nav-top").addClass("mvp-fixed");
                $("#mvp-main-nav-bot").addClass("mvp-fixed1");
                $("#mvp-main-body-wrap").css("margin-top", totalHeight );
                $("#mvp-main-nav-top").addClass("mvp-fixed-shadow");
                $(".mvp-nav-top-mid").addClass("mvp-fixed-post");
                $(".mvp-drop-nav-title").show();
                $(".mvp-fly-top").addClass("mvp-to-top");
                if(scroll < previousScroll) {
                    $("#mvp-main-nav-bot").addClass("mvp-fixed2");
                    $("#mvp-main-nav-top").removeClass("mvp-fixed-shadow");
                    $("#mvp-main-nav-top").removeClass("mvp-soc-mob-up");
                    $("#mvp-soc-mob-wrap").removeClass("mvp-soc-mob-up");
                } else {
                    $("#mvp-main-nav-bot").removeClass("mvp-fixed2");
                    $("#mvp-main-nav-top").addClass("mvp-fixed-shadow");
                    $("#mvp-main-nav-top").addClass("mvp-soc-mob-up");
                    $("#mvp-soc-mob-wrap").addClass("mvp-soc-mob-up");
                }
            } else {
                $("#mvp-main-nav-top").removeClass("mvp-fixed");
                $("#mvp-main-nav-bot").removeClass("mvp-fixed1");
                $("#mvp-main-nav-bot").removeClass("mvp-fixed2");
                $("#mvp-main-body-wrap").css("margin-top","0");
                $("#mvp-main-nav-top").removeClass("mvp-fixed-shadow");
                $(".mvp-nav-top-mid").removeClass("mvp-fixed-post");
                $(".mvp-drop-nav-title").hide();
                $(".mvp-fly-top").removeClass("mvp-to-top");
            }
            previousScroll = scroll;
    });
    });
    });
    ' );
    } else {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function(){
    var leaderHeight = $("#mvp-leader-wrap").outerHeight();
    var logoHeight = $("#mvp-main-nav-top").outerHeight();
    var botHeight = $("#mvp-main-nav-bot").outerHeight();
    var navHeight = $("#mvp-main-head-wrap").outerHeight();
    var headerHeight = navHeight + leaderHeight;
    var aboveNav = leaderHeight + logoHeight;
    var totalHeight = logoHeight + botHeight;
    var previousScroll = 0;
    $(window).scroll(function(event){
            var scroll = $(this).scrollTop();
            if ($(window).scrollTop() > aboveNav){
                $("#mvp-main-nav-top").addClass("mvp-nav-small");
                $("#mvp-main-nav-bot").css("margin-top", logoHeight );
            } else {
                $("#mvp-main-nav-top").removeClass("mvp-nav-small");
                $("#mvp-main-nav-bot").css("margin-top","0");
            }
            if ($(window).scrollTop() > headerHeight){
                $("#mvp-main-nav-top").addClass("mvp-fixed");
                $("#mvp-main-nav-bot").addClass("mvp-fixed1");
                $("#mvp-main-body-wrap").css("margin-top", totalHeight );
                $("#mvp-main-nav-top").addClass("mvp-fixed-shadow");
                $(".mvp-fly-top").addClass("mvp-to-top");
                if(scroll < previousScroll) {
                    $("#mvp-main-nav-bot").addClass("mvp-fixed2");
                    $("#mvp-main-nav-top").removeClass("mvp-fixed-shadow");
                } else {
                    $("#mvp-main-nav-bot").removeClass("mvp-fixed2");
                    $("#mvp-main-nav-top").addClass("mvp-fixed-shadow");
                }
            } else {
                $("#mvp-main-nav-top").removeClass("mvp-fixed");
                $("#mvp-main-nav-bot").removeClass("mvp-fixed1");
                $("#mvp-main-nav-bot").removeClass("mvp-fixed2");
                $("#mvp-main-body-wrap").css("margin-top","0");
                $("#mvp-main-nav-top").removeClass("mvp-fixed-shadow");
                $(".mvp-fly-top").removeClass("mvp-to-top");
            }
            previousScroll = scroll;
    });
    
    $(".mvp-alp-side-in").niceScroll({cursorcolor:"#ccc",cursorwidth: 5,cursorborder: 0,zindex:999999});
    
    });
    });
    ' );
    }
}

    $mvp_alp = get_option('mvp_alp');
    if ($mvp_alp !== "true") {
    $mvp_scroll_vid = get_option('mvp_scroll_vid'); if ($mvp_scroll_vid == "true") { if (isset($mvp_scroll_vid)) {
    if ( is_single() ) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    // Video Post Scroll
    $(window).on("scroll.video", function(event){
        var scrollTop     = $(window).scrollTop();
        var elementOffset = $("#mvp-content-wrap").offset().top;
        var distance      = (elementOffset - scrollTop);
        var aboveHeight = $("#mvp-video-embed-wrap").outerHeight();
        if ($(window).scrollTop() > distance + aboveHeight + screen.height){
            $("#mvp-video-embed-cont").addClass("mvp-vid-fixed");
            $("#mvp-video-embed-wrap").addClass("mvp-vid-height");
            $(".mvp-video-close").show();
        } else {
            $("#mvp-video-embed-cont").removeClass("mvp-vid-fixed");
            $("#mvp-video-embed-wrap").removeClass("mvp-vid-height");
            $(".mvp-video-close").hide();
        }
    });

     $(".mvp-video-close").on("click", function(){
        $("iframe").attr("src", $("iframe").attr("src"));
        $("#mvp-video-embed-cont").removeClass("mvp-vid-fixed");
        $("#mvp-video-embed-wrap").removeClass("mvp-vid-height");
        $(".mvp-video-close").hide();
        $(window).off("scroll.video");
      });

    });
      ' );
    }
    } } }

    global $post;
    $socialbox = get_option('mvp_social_box');
    if ($socialbox == "true") {
    if (isset($socialbox)) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    // Mobile Social Buttons More
    $(window).load(function(){
         $(".mvp-soc-mob-right").on("click", function(){
            $("#mvp-soc-mob-wrap").toggleClass("mvp-soc-mob-more");
          });
      });
    });
      ' );
    } }

    $mvp_cont_read = get_option('mvp_cont_read');
    if ($mvp_cont_read == "true") {
    if (isset($mvp_cont_read)) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    // Continue Reading Button
    $(window).load(function(){
         $(".mvp-cont-read-but").on("click", function(){
            $("#mvp-content-body-top").css("max-height","none");
            $("#mvp-content-body-top").css("overflow","visible");
            $(".mvp-cont-read-but-wrap").hide();
          });
      });
    });
      ' );
    } }

    // Parallax Leaderboard
    $mvp_para_lead = get_option('mvp_para_lead'); if ($mvp_para_lead == "true") { if (isset($mvp_para_lead)) {
    if(get_option('mvp_header_leader')) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function(){
        var leaderHeight = $("#mvp-leader-wrap").outerHeight();
        $("#mvp-site-main").css("margin-top", leaderHeight );
      });

    $(window).resize(function(){
        var leaderHeight = $("#mvp-leader-wrap").outerHeight();
        $("#mvp-site-main").css("margin-top", leaderHeight );
    });

    });
      ' );
    }
    } }

    // Main Menu Dropdown Toggle
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(".menu-item-has-children a").click(function(event){
      event.stopPropagation();
      
      });

    $(".menu-item-has-children").click(function(){
          $(this).addClass("toggled");
          if($(".menu-item-has-children").hasClass("toggled"))
          {
          $(this).children("ul").toggle();
      $(".mvp-fly-nav-menu").getNiceScroll().resize();
      }
      $(this).toggleClass("tog-minus");
          return false;
      });

    // Main Menu Scroll
    $(window).load(function(){
      $(".mvp-fly-nav-menu").niceScroll({cursorcolor:"#888",cursorwidth: 7,cursorborder: 0,zindex:999999});
    });
    });
    ' );

    if ( $mvp_infinite_on ) {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(".infinite-content").infinitescroll({
      navSelector: ".mvp-nav-links",
      nextSelector: ".mvp-nav-links a:first",
      itemSelector: ".infinite-post",
      bufferPx: 1800,
      errorCallback: function(){ $(".mvp-inf-more-but").hide(); }
    });
    });
    ' );
    }

    if (
        is_page_template( 'page-home.php' ) &&
        apply_filters( 'mvp_home_sidebar_recent_enable', true )
    ) {
        $mvp_sb_ajax_on = apply_filters( 'mvp_home_sidebar_recent_ajax_enable', true );
        $base_sb_off    = (int) apply_filters( 'mvp_home_sidebar_recent_offset', 0 );
        $init_sb_n      = max( 0, (int) apply_filters( 'mvp_home_sidebar_recent_count', 8 ) );
        wp_localize_script(
            'mvp-custom',
            'mvpHomeSidebarRecent',
            array(
                'ajaxUrl'        => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
                'nonce'          => wp_create_nonce( 'mvp_home_sidebar_recent' ),
                'batchSize'      => (int) mvp_home_sidebar_recent_ajax_batch_size(),
                // Default only: sidebar template overrides via data-next-offset after the diversified pool resolves.
                'nextOffset'     => (int) ( $base_sb_off + $init_sb_n ),
                'streamPosition' => 0,
                'action'         => 'mvp_home_sidebar_more_posts',
                'ajaxEnabled'    => (bool) $mvp_sb_ajax_on,
            )
        );
        wp_add_inline_script(
            'mvp-custom',
            '
	jQuery(document).ready(function ($) {
		var cfg = window.mvpHomeSidebarRecent || null;
		if (!cfg || !cfg.ajaxEnabled || !cfg.ajaxUrl || !window.IntersectionObserver) {
			return;
		}

		var $wrap = $(".mvp-home-sidebar-recent");
		var $sentinel = $wrap.find(".mvp-home-sidebar-recent-sentinel");
		var $moreList = $wrap.find(".mvp-home-sidebar-recent-more");
		if (!$sentinel.length || !$moreList.length) {
			return;
		}

		var streamTokenEl = $wrap.find(".mvp-home-sidebar-stream-token");
		var streamTok = parseInt(streamTokenEl.attr("data-stream"), 10);
		if (!window.isNaN(streamTok)) {
			cfg.streamPosition = streamTok;
		}
		var sidebarNextDom = parseInt(streamTokenEl.attr("data-next-offset"), 10);
		if (!window.isNaN(sidebarNextDom)) {
			cfg.nextOffset = sidebarNextDom;
		}

		var loading = false;
		var done = false;
		var observer = null;
		function finish() {
			done = true;
			if (observer) {
				observer.disconnect();
			}
			$sentinel.hide();
		}

		function loadMore() {
			if (loading || done || typeof cfg.nextOffset === "undefined") {
				return;
			}

			var nextOff = parseInt(cfg.nextOffset, 10);
			if (window.isNaN(nextOff)) {
				nextOff = 0;
			}

			loading = true;
			$sentinel.addClass("is-busy");

			$.ajax({
				type: "POST",
				url: cfg.ajaxUrl,
				dataType: "json",
				data: {
					action: cfg.action,
					nonce: cfg.nonce,
					offset: nextOff,
					streamPosition: parseInt(cfg.streamPosition, 10) || 0
				}
			}).done(function (res) {
				if (!res || !res.success || !res.data) {
					return;
				}
				if (res.data.html && res.data.html.length) {
					$moreList.append(res.data.html);
				}
				if (typeof res.data.nextOffset !== "undefined") {
					cfg.nextOffset = parseInt(res.data.nextOffset, 10);
				}
				if (typeof res.data.streamPosition !== "undefined") {
					cfg.streamPosition = parseInt(res.data.streamPosition, 10);
				}
				if (!res.data.has_more) {
					finish();
				}
			}).fail(function () {
				finish();
			}).always(function () {
				loading = false;
				$sentinel.removeClass("is-busy");
			});
		}

		observer = new IntersectionObserver(
			function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						loadMore();
					}
				});
			},
			{ root: null, rootMargin: "500px", threshold: 0 }
		);

		observer.observe($sentinel.get(0));
	});
'
        );
    }

    if ( is_single() ) {
    global $post; $mvp_show_gallery = get_post_meta($post->ID, "mvp_post_gallery", true);
    if ($mvp_show_gallery == "show") {
    wp_add_inline_script( 'mvp-custom', '
    jQuery(document).ready(function($) {
    $(window).load(function() {
      $(".mvp-post-gallery-bot").flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
        itemWidth: 80,
        itemMargin: 0,
        asNavFor: ".mvp-post-gallery-top"
      });

      $(".mvp-post-gallery-top").flexslider({
        animation: "fade",
        controlNav: false,
        animationLoop: true,
        slideshow: false,
              prevText: "&lt;",
              nextText: "&gt;",
        sync: ".mvp-post-gallery-bot"
      });
    });
    });
    ' );
    }
    }
    
}
}
add_action('wp_enqueue_scripts', 'mvp_scripts_method');

/////////////////////////////////////
// Register Widgets
/////////////////////////////////////

if ( !function_exists( 'mvp_sidebars_init' ) ) {
    function mvp_sidebars_init() {

        register_sidebar(array(
            'id' => 'homepage-widget',
            'name' => esc_html__( 'Homepage Widget Area', 'zox-news' ),
            'description'   => esc_html__( 'The widgetized area in the main content area of the homepage.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-widget-home left relative %2$s"><div class="mvp-main-box">',
            'after_widget' => '</div></section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title">',
            'after_title' => '</span></h4></div>',
        ));

        register_sidebar(array(
            'id' => 'mvp-home-sidebar-widget',
            'name' => esc_html__( 'Homepage Sidebar Widget Area', 'zox-news' ),
            'description'   => esc_html__( 'Widgets above “More stories” (search, tabber, default sidebar content). Ads between story rows do NOT use this area — put those in “Homepage Sidebar — More stories ad slots” below.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-side-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title">',
            'after_title' => '</span></h4></div>',
        ));

        register_sidebar(array(
            'id' => 'mvp-home-sidebar-below-recent',
            'name' => esc_html__( 'Homepage Sidebar — “More stories” ad slots', 'zox-news' ),
            'description'   => esc_html__( 'Repeating ad/HTML markup for between-story rows inside “More stories” (default after every fourth story — filter mvp_home_sidebar_between_stories_interval). Paste [adfusion] or HTML here; the area above (“Homepage Sidebar Widget Area”) is separate and never inserted between stories.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-side-widget mvp-home-sidebar-ad-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title">',
            'after_title' => '</span></h4></div>',
        ));

        register_sidebar(array(
            'id' => 'mvp-sidebar-widget',
            'name' => esc_html__( 'Default Sidebar Widget Area', 'zox-news' ),
            'description'   => esc_html__( 'The default widgetized sidebar.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-side-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title">',
            'after_title' => '</span></h4></div>',
        ));

        register_sidebar(array(
            'id' => 'mvp-sidebar-widget-post',
            'name' => esc_html__( 'Post/Page Sidebar Widget Area', 'zox-news' ),
            'description'   => esc_html__( 'The widgetized sidebar on posts and pages.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-side-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title">',
            'after_title' => '</span></h4></div>',
        ));

        register_sidebar(array(
            'id' => 'mvp-sidebar-woo-widget',
            'name' => esc_html__( 'WooCommerce Sidebar Widget Area', 'zox-news' ),
            'description'   => esc_html__( 'The widgetized sidebar on your WooCommerce pages.', 'zox-news' ),
            'before_widget' => '<section id="%1$s" class="mvp-side-widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="mvp-widget-home-head"><h4 class="mvp-widget-home-title2"><span class="mvp-widget-home-title2">',
            'after_title' => '</span></h4></div>',
        ));

    }
}
add_action( 'widgets_init', 'mvp_sidebars_init' );

include( get_template_directory() . '/widgets/widget-ad.php');
include( get_template_directory() . '/widgets/widget-facebook.php');
include( get_template_directory() . '/widgets/widget-flex.php');
include( get_template_directory() . '/widgets/widget-home-dark.php');
include( get_template_directory() . '/widgets/widget-home-feat1.php');
include( get_template_directory() . '/widgets/widget-home-feat2.php');
include( get_template_directory() . '/widgets/widget-tabber.php');

/////////////////////////////////////
// Register Custom Menus
/////////////////////////////////////

if ( !function_exists( 'register_menus' ) ) {
function register_menus() {
    register_nav_menus(
        array(
            'main-menu' => esc_html__( 'Main Menu', 'zox-news' ),
            'mobile-menu' => esc_html__( 'Fly-Out Menu', 'zox-news' ),
            'footer-menu' => esc_html__( 'Footer Menu', 'zox-news' ))
          );
      }
}
add_action( 'init', 'register_menus' );

/////////////////////////////////////
// Register Mega Menu
/////////////////////////////////////

add_filter( 'walker_nav_menu_start_el', 'mvp_walker_nav_menu_start_el', 10, 4 );

function mvp_walker_nav_menu_start_el( $item_output, $item, $depth, $args ) {
    global $wp_query;
    // The mega dropdown only applies to the main navigation.
    // Your theme location name may be different, "main" is just something I tend to use.
    if ( 'main-menu' !== $args->theme_location )
        return $item_output;

    // The mega dropdown needs to be added to one specific menu item.
    // I like to add a custom CSS class for that menu via the admin area.
    // You could also do an item ID check.
    if ( in_array( 'mvp-mega-dropdown', $item->classes ) ) {
        global $wp_query;
        global $post;
        $subposts = get_posts( 'numberposts=5&cat=' . $item->object_id );
    $item_output .= '<div class="mvp-mega-dropdown"><div class="mvp-main-box"><ul class="mvp-mega-list">';
            foreach( $subposts as $post ) :
                setup_postdata( $post );
        if ( has_post_format( 'video' )) {
                $item_output .= '<li><a href="'. get_permalink( $post->ID ) .'"><div class="mvp-mega-img">';
        $item_output .= get_the_post_thumbnail( $post->ID, 'mvp-mid-thumb' );
        $item_output .= '<div class="mvp-vid-box-wrap mvp-vid-box-small mvp-vid-marg-small"><i class="fa fa-play fa-3"></i></div></div><p>';
        $item_output .= get_the_title( $post->ID );
                $item_output .= '</p></a></li>';
        } else if ( has_post_format( 'gallery' )) {
                $item_output .= '<li><a href="'. get_permalink( $post->ID ) .'"><div class="mvp-mega-img">';
        $item_output .= get_the_post_thumbnail( $post->ID, 'mvp-mid-thumb' );
        $item_output .= '<div class="mvp-vid-box-wrap mvp-vid-box-small mvp-vid-marg-small"><i class="fa fa-camera fa-3"></i></div></div><p>';
        $item_output .= get_the_title( $post->ID );
                $item_output .= '</p></a></li>';
        } else {
                $item_output .= '<li><a href="'. get_permalink( $post->ID ) .'"><div class="mvp-mega-img">';
        $item_output .= get_the_post_thumbnail( $post->ID, 'mvp-mid-thumb' );
        $item_output .= '</div><p>';
        $item_output .= get_the_title( $post->ID );
                $item_output .= '</p></a></li>';
        }
            endforeach; wp_reset_postdata();
    $item_output .= '</ul></div></div>';

    }

    return $item_output;
}

/////////////////////////////////////
// Register Custom Background
/////////////////////////////////////

$custombg = array(
    'default-color' => 'ffffff',
);
add_theme_support( 'custom-background', $custombg );

/////////////////////////////////////
// Register Thumbnails
/////////////////////////////////////

if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 1000, 600, true );
add_image_size( 'mvp-post-thumb', 1000, 600, true );
add_image_size( 'mvp-port-thumb', 560, 600, true );
add_image_size( 'mvp-large-thumb', 590, 354, true );
add_image_size( 'mvp-mid-thumb', 400, 240, true );
add_image_size( 'mvp-small-thumb', 80, 80, true );
}

/////////////////////////////////////
// Title Meta Data
/////////////////////////////////////

add_theme_support( 'title-tag' );

function mvp_filter_home_title(){
if ( ( is_home() && ! is_front_page() ) || ( ! is_home() && is_front_page() ) ) {
    $mvpHomeTitle = get_bloginfo( 'name', 'display' );
    $mvpHomeDesc = get_bloginfo( 'description', 'display' );
    return $mvpHomeTitle . " - " . $mvpHomeDesc;
}
}
add_filter( 'pre_get_document_title', 'mvp_filter_home_title');

/////////////////////////////////////
// Add Custom Meta Box
/////////////////////////////////////

/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'mvp_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'mvp_post_meta_boxes_setup' );

/* Meta box setup function. */
if ( !function_exists( 'mvp_post_meta_boxes_setup' ) ) {
function mvp_post_meta_boxes_setup() {

    /* Add meta boxes on the 'add_meta_boxes' hook. */
    add_action( 'add_meta_boxes', 'mvp_add_post_meta_boxes' );

    /* Save post meta on the 'save_post' hook. */
    add_action( 'save_post', 'mvp_save_video_embed_meta', 10, 2 );
    add_action( 'save_post', 'mvp_save_featured_headline_meta', 10, 2 );
    add_action( 'save_post', 'mvp_save_photo_credit_meta', 10, 2 );
    add_action( 'save_post', 'mvp_save_post_template_meta', 10, 2 );
    add_action( 'save_post', 'mvp_save_featured_image_meta', 10, 2 );
    add_action( 'save_post', 'mvp_save_post_gallery_meta', 10, 2 );
}
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
if ( !function_exists( 'mvp_add_post_meta_boxes' ) ) {
function mvp_add_post_meta_boxes() {

    add_meta_box(
        'mvp-video-embed',            // Unique ID
        esc_html__( 'Video/Audio Embed', 'zox-news' ),        // Title
        'mvp_video_embed_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'normal',                // Context
        'high'                    // Priority
    );

    add_meta_box(
        'mvp-featured-headline',            // Unique ID
        esc_html__( 'Featured Headline', 'zox-news' ),        // Title
        'mvp_featured_headline_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'normal',                // Context
        'high'                    // Priority
    );

    add_meta_box(
        'mvp-photo-credit',            // Unique ID
        esc_html__( 'Featured Image Caption', 'zox-news' ),        // Title
        'mvp_photo_credit_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'normal',                // Context
        'high'                    // Priority
    );

    add_meta_box(
        'mvp-post-template',            // Unique ID
        esc_html__( 'Post Template', 'zox-news' ),        // Title
        'mvp_post_template_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'side',                    // Context
        'core'                    // Priority
    );

    add_meta_box(
        'mvp-featured-image',            // Unique ID
        esc_html__( 'Featured Image Show/Hide', 'zox-news' ),        // Title
        'mvp_featured_image_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'side',                    // Context
        'core'                    // Priority
    );

    add_meta_box(
        'mvp-post-gallery',            // Unique ID
        esc_html__( 'Post Gallery Show/Hide', 'zox-news' ),        // Title
        'mvp_post_gallery_meta_box',        // Callback function
        'post',                    // Admin page (or post type)
        'side',                    // Context
        'core'                    // Priority
    );
}
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_featured_headline_meta_box' ) ) {
function mvp_featured_headline_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_featured_headline_meta', 'mvp_featured_headline_nonce' ); ?>

    <p>
        <label for="mvp-featured-headline"><?php esc_html_e( "Add a custom featured headline that will be displayed in the featured slider.", 'zox-news' ); ?></label>
        <br />
        <input class="widefat" type="text" name="mvp-featured-headline" id="mvp-featured-headline" value="<?php echo esc_html( get_post_meta( $object->ID, 'mvp_featured_headline', true ) ); ?>" size="30" />
    </p>

<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_video_embed_meta_box' ) ) {
function mvp_video_embed_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_video_embed_meta', 'mvp_video_embed_nonce' ); ?>

    <p>
        <label for="mvp-video-embed"><?php esc_html_e( "Enter your video or audio embed code.", 'zox-news' ); ?></label>
        <br />
        <input class="widefat" type="text" name="mvp-video-embed" id="mvp-video-embed" value="<?php echo esc_html( get_post_meta( $object->ID, 'mvp_video_embed', true ) ); ?>" />
    </p>

<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_photo_credit_meta_box' ) ) {
function mvp_photo_credit_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_photo_credit_meta', 'mvp_photo_credit_nonce' ); ?>

    <p>
        <label for="mvp-photo-credit"><?php esc_html_e( "Add a caption and/or photo credit information for the featured image.", 'zox-news' ); ?></label>
        <br />
        <input class="widefat" type="text" name="mvp-photo-credit" id="mvp-photo-credit" value="<?php echo esc_html( get_post_meta( $object->ID, 'mvp_photo_credit', true ) ); ?>" size="30" />
    </p>

<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_post_template_meta_box' ) ) {
function mvp_post_template_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_post_template_meta', 'mvp_post_template_nonce' ); $selected = esc_html( get_post_meta( $object->ID, 'mvp_post_template', true ) ); ?>

    <p>
        <label for="mvp-post-template"><?php esc_html_e( "Select a template for your post.", 'zox-news' ); ?></label>
        <br /><br />
        <select class="widefat" name="mvp-post-template" id="mvp-post-template">
            <option value="global" <?php selected( $selected, 'global' ); ?>>Use Global Setting</option>
            <option value="temp1" <?php selected( $selected, 'temp1' ); ?>>Template 1</option>
            <option value="temp2" <?php selected( $selected, 'temp2' ); ?>>Template 2</option>
            <option value="temp3" <?php selected( $selected, 'temp3' ); ?>>Template 3</option>
            <option value="temp4" <?php selected( $selected, 'temp4' ); ?>>Template 4</option>
            <option value="temp5" <?php selected( $selected, 'temp5' ); ?>>Template 5</option>
            <option value="temp6" <?php selected( $selected, 'temp6' ); ?>>Template 6</option>
            <option value="temp7" <?php selected( $selected, 'temp7' ); ?>>Template 7</option>
            <option value="temp8" <?php selected( $selected, 'temp8' ); ?>>Template 8</option>
            </select>
    </p>
<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_featured_image_meta_box' ) ) {
function mvp_featured_image_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_featured_image_meta', 'mvp_featured_image_nonce' ); $selected = esc_html( get_post_meta( $object->ID, 'mvp_featured_image', true ) ); ?>

    <p>
        <label for="mvp-featured-image"><?php esc_html_e( "Select to show or hide the featured image from automatically displaying in this post.", 'zox-news' ); ?></label>
        <br /><br />
        <select class="widefat" name="mvp-featured-image" id="mvp-featured-image">
                    <option value="show" <?php selected( $selected, 'show' ); ?>>Show</option>
                    <option value="hide" <?php selected( $selected, 'hide' ); ?>>Hide</option>
            </select>
    </p>
<?php }
}

/* Display the post meta box. */
if ( !function_exists( 'mvp_post_gallery_meta_box' ) ) {
function mvp_post_gallery_meta_box( $object, $box ) { ?>

    <?php wp_nonce_field( 'mvp_save_post_gallery_meta', 'mvp_post_gallery_nonce' ); $selected = esc_html( get_post_meta( $object->ID, 'mvp_post_gallery', true ) ); ?>

    <p>
        <label for="mvp-post-gallery"><?php esc_html_e( "Select to show or hide the built-in gallery feature for this post.", 'zox-news' ); ?></label>
        <br /><br />
        <select class="widefat" name="mvp-post-gallery" id="mvp-post-gallery">
                    <option value="hide" <?php selected( $selected, 'hide' ); ?>>Hide</option>
                    <option value="show" <?php selected( $selected, 'show' ); ?>>Show</option>
            </select>
    </p>
<?php }
}

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_video_embed_meta' ) ) {
function mvp_save_video_embed_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_video_embed_nonce'] ) || !wp_verify_nonce( $_POST['mvp_video_embed_nonce'], 'mvp_save_video_embed_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-video-embed'] ) ? wp_kses_post( $_POST['mvp-video-embed'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_video_embed';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_featured_headline_meta' ) ) {
function mvp_save_featured_headline_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_featured_headline_nonce'] ) || !wp_verify_nonce( $_POST['mvp_featured_headline_nonce'], 'mvp_save_featured_headline_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-featured-headline'] ) ? sanitize_text_field( $_POST['mvp-featured-headline'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_featured_headline';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_photo_credit_meta' ) ) {
function mvp_save_photo_credit_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_photo_credit_nonce'] ) || !wp_verify_nonce( $_POST['mvp_photo_credit_nonce'], 'mvp_save_photo_credit_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-photo-credit'] ) ? sanitize_text_field( $_POST['mvp-photo-credit'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_photo_credit';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_post_template_meta' ) ) {
function mvp_save_post_template_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_post_template_nonce'] ) || !wp_verify_nonce( $_POST['mvp_post_template_nonce'], 'mvp_save_post_template_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-post-template'] ) ? sanitize_text_field( $_POST['mvp-post-template'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_post_template';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_featured_image_meta' ) ) {
function mvp_save_featured_image_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_featured_image_nonce'] ) || !wp_verify_nonce( $_POST['mvp_featured_image_nonce'], 'mvp_save_featured_image_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-featured-image'] ) ? sanitize_text_field( $_POST['mvp-featured-image'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_featured_image';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/* Save the meta box's post metadata. */
if ( !function_exists( 'mvp_save_post_gallery_meta' ) ) {
function mvp_save_post_gallery_meta( $post_id, $post ) {

    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['mvp_post_gallery_nonce'] ) || !wp_verify_nonce( $_POST['mvp_post_gallery_nonce'], 'mvp_save_post_gallery_meta' ) )
        return $post_id;

    /* Get the post type object. */
    $post_type = get_post_type_object( $post->post_type );

    /* Check if the current user has permission to edit the post. */
    if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;

    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = ( isset( $_POST['mvp-post-gallery'] ) ? sanitize_text_field( $_POST['mvp-post-gallery'] ) : '' );

    /* Get the meta key. */
    $meta_key = 'mvp_post_gallery';

    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If a new meta value was added and there was no previous value, add it. */
    if ( $new_meta_value && '' == $meta_value )
        add_post_meta( $post_id, $meta_key, $new_meta_value, true );

    /* If the new meta value does not match the old value, update it. */
    elseif ( $new_meta_value && $new_meta_value != $meta_value )
        update_post_meta( $post_id, $meta_key, $new_meta_value );

    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( '' == $new_meta_value && $meta_value )
        delete_post_meta( $post_id, $meta_key, $meta_value );
} }

/////////////////////////////////////
// Comments
/////////////////////////////////////

if ( !function_exists( 'mvp_comment' ) ) {
function mvp_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case '' :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div class="comment-wrapper" id="comment-<?php comment_ID(); ?>">
            <div class="comment-inner">
                <div class="comment-avatar">
                    <?php echo get_avatar( $comment, 46 ); ?>
                </div>
                <div class="commentmeta">
                    <p class="comment-meta-1">
                        <?php printf( esc_html__( '%s ', 'zox-news'), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                    </p>
                    <p class="comment-meta-2">
                        <?php echo get_comment_date(); ?> <?php esc_html_e( 'at', 'zox-news'); ?> <?php echo get_comment_time(); ?>
                        <?php edit_comment_link( esc_html__( 'Edit', 'zox-news'), '(' , ')'); ?>
                    </p>
                </div>
                <div class="text">
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <p class="waiting_approval"><?php esc_html_e( 'Your comment is awaiting moderation.', 'zox-news' ); ?></p>
                    <?php endif; ?>
                    <div class="c">
                        <?php comment_text(); ?>
                    </div>
                </div><!-- .text  -->
                <div class="clear"></div>
                <div class="comment-reply"><span class="reply"><?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span></div>
            </div><!-- comment-inner  -->
        </div><!-- comment-wrapper  -->
    <?php
            break;
        case 'pingback'  :
        case 'trackback' :
    ?>
    <li class="post pingback">
        <p><?php esc_html_e( 'Pingback:', 'zox-news' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'zox-news' ), ' ' ); ?></p>
    <?php
            break;
    endswitch;
}
}

if ( !function_exists( 'mvpClickCommmentButton' ) ) {
function mvpClickCommmentButton($disqus_shortname){
    global $post;
    echo '
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".mvp-com-click-'.$post->ID.'").on("click", function(){
          $(".mvp-com-click-id-'.$post->ID.'").show();
        $(".mvp-disqus-thread-'.$post->ID.'").show();
          $(".mvp-com-but-'.$post->ID.'").hide();
      });
    });
    </script>';
}
}

/////////////////////////////////////
// Related Posts
/////////////////////////////////////

if ( !function_exists( 'mvp_RelatedPosts' ) ) {
function mvp_RelatedPosts() {
    global $post;
    $orig_post = $post;

    $tags = wp_get_post_tags($post->ID);
    if ($tags) {

    $mvp_related_num = esc_html(get_option('mvp_related_num'));
    $slider_exclude = esc_html(get_option('mvp_feat_posts_tags'));
    $tag_exclude_slider = get_term_by('slug', $slider_exclude, 'post_tag');
    if (!empty( $tag_exclude_slider )) {
        $tag_id_exclude_slider =  $tag_exclude_slider->term_id;
               $tag_ids = array();
            foreach($tags as $individual_tag) {
            $excluded_tags = array($tag_id_exclude_slider);
                  if (in_array($individual_tag->term_id,$excluded_tags)) continue;
             $tag_ids[] = $individual_tag->term_id;
        }
    } else {
               $tag_ids = array();
            foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
    }
        $args=array(
            'tag__in' => $tag_ids,
        'order' => 'DESC',
        'orderby' => 'date',
            'post__not_in' => array($post->ID),
            'posts_per_page'=> $mvp_related_num,
            'ignore_sticky_posts'=> 1
        );
        $my_query = new WP_Query( $args );
        if( $my_query->have_posts() ) { ?>
                <ul class="mvp-related-posts-list left related">
                    <?php while( $my_query->have_posts() ) { $my_query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                        <li>
                            <?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
                                <div class="mvp-related-img left relative">
                                    <?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
                                    <?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
                                    <?php if ( has_post_format( 'video' )) { ?>
                                        <div class="mvp-vid-box-wrap mvp-vid-box-mid mvp-vid-marg">
                                            <i class="fa fa-2 fa-play" aria-hidden="true"></i>
                                        </div><!--mvp-vid-box-wrap-->
                                    <?php } else if ( has_post_format( 'gallery' )) { ?>
                                        <div class="mvp-vid-box-wrap mvp-vid-box-mid">
                                            <i class="fa fa-2 fa-camera" aria-hidden="true"></i>
                                        </div><!--mvp-vid-box-wrap-->
                                    <?php } ?>
                                </div><!--mvp-related-img-->
                            <?php } ?>
                            <div class="mvp-related-text left relative">
                                <p><?php the_title(); ?></p>
                            </div><!--mvp-related-text-->
                        </li>
                        </a>
                    <?php }
            echo '</ul>';
        }
    }
    $post = $orig_post;
    wp_reset_query();
}
}

/////////////////////////////////////
// Popular Posts
/////////////////////////////////////

if ( !function_exists( 'getCrunchifyPostViews' ) ) {
function getCrunchifyPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
}

if ( !function_exists( 'setCrunchifyPostViews' ) ) {
function setCrunchifyPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
}

/////////////////////////////////////
// Auto Load Posts
/////////////////////////////////////

$alp_side = get_option('mvp_alp');
if ( $alp_side == "true" && ! function_exists( 'getPostHTML' ) ) {
function getPostHTML($post, $current = false) {
        ob_start();
        ?>
        <div class="alp-related-post post-<?php echo esc_html($post->ID); ?> <?php echo (esc_html($current) ? 'current' : ''); ?>" data-id="<?php echo esc_html($post->ID); ?>" data-document-title="">
        <?php
        $postThumbnailUrl = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        if ( $postThumbnailUrl ) {
            // Thumbnail output placeholder
        }
        ?>
            <div class="post-details">
                <p class="post-meta">
                    <?php
                    $postCategories = get_the_category($post->ID);
                    if($postCategories)
                    {
                        foreach($postCategories as $postCategory)
                        {
                        ?>
                            <a class="post-category" href="<?php echo get_category_link($postCategory->term_id); ?>"><?php echo esc_html($postCategory->name); ?></a>
                        <?php
                        }
                    }
                    ?>
                </p>
                <a class="post-title" href="<?php echo get_permalink($post->ID); ?>"><?php echo html_entity_decode( (string) $post->post_title, ENT_QUOTES | ENT_HTML5, 'UTF-8' ); ?></a>
            </div>
            <?php $socialbox = get_option('mvp_social_box'); if ($socialbox == "true") { ?>
                <div class="mvp-alp-soc-wrap">
                    <ul class="mvp-alp-soc-list">
                        <a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php the_permalink($post->ID);?>&amp;t=<?php the_title_attribute(array('post'=>$post->ID)); ?>', 'facebookShare', 'width=626,height=436'); return false;" title="<?php esc_html_e( 'Share on Facebook', 'zox-news' ); ?>">
                            <li class="mvp-alp-soc-fb"><span class="fa fa-facebook"></span></li>
                        </a>
                        <a href="#" onclick="window.open('http://twitter.com/share?text=<?php the_title_attribute(array('post'=>$post->ID)); ?> &amp;url=<?php the_permalink($post->ID) ?>', 'twitterShare', 'width=626,height=436'); return false;" title="<?php esc_html_e( 'Tweet This Post', 'zox-news' ); ?>">
                            <li class="mvp-alp-soc-twit"><span class="fa fa-twitter"></span></li>
                        </a>
                        <a href="#" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php the_permalink($post->ID);?>&amp;media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mvp-large-thumb' ); echo esc_url( is_array( $thumb ) && ! empty( $thumb[0] ) ? $thumb[0] : '' ); ?>&amp;description=<?php the_title_attribute(array('post'=>$post->ID)); ?>', 'pinterestShare', 'width=750,height=350'); return false;" title="<?php esc_html_e( 'Pin This Post', 'zox-news' ); ?>">
                            <li class="mvp-alp-soc-pin"><span class="fa fa-pinterest-p"></span></li>
                        </a>
                        <a href="mailto:?subject=<?php the_title_attribute(array('post'=>$post->ID)); ?>&amp;BODY=<?php esc_html_e( 'I found this article interesting and thought of sharing it with you. Check it out:', 'zox-news' ); ?> <?php the_permalink($post->ID); ?>">
                            <li class="mvp-alp-soc-com"><span class="fa fa-envelope"></span></li>
                        </a>
                    </ul>
                </div>
            <?php } ?>
        </div>
        <?php
        return ob_get_clean();
}
}

/////////////////////////////////////
// Pagination
/////////////////////////////////////

if ( !function_exists( 'pagination' ) ) {
function pagination($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }

     if(1 != $pages)
     {
         echo "<div class=\"pagination\"><span>".__( 'Page', 'zox-news' )." ".$paged." ".__( 'of', 'zox-news' )." ".$pages."</span>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo; ".__( 'First', 'zox-news' )."</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo; ".__( 'Previous', 'zox-news' )."</a>";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\">".__( 'Next', 'zox-news' )." &rsaquo;</a>";
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>".__( 'Last', 'zox-news' )." &raquo;</a>";
         echo "</div>\n";
     }
}
}

/////////////////////////////////////
// Disqus Comments
/////////////////////////////////////

$disqus_id = get_option('mvp_disqus_id'); if (isset($disqus_id)) {
if ( !function_exists( 'mvp_disqus_embed' ) ) {
function mvp_disqus_embed($disqus_shortname) {
    global $post;
    wp_enqueue_script('disqus_embed','//'.$disqus_shortname.'.disqus.com/embed.js');
    echo '<div id="disqus_thread" class="disqus-thread-'.$post->ID.'"></div>
    <script type="text/javascript">
        var disqus_shortname = "'.$disqus_shortname.'";
        var disqus_title = "'.$post->post_title.'";
        var disqus_url = "'.get_permalink($post->ID).'";
        var disqus_identifier = "'.$disqus_shortname.'-'.$post->ID.'";
    </script>';
}
}
}

/////////////////////////////////////
// Remove Pages From Search Results
/////////////////////////////////////

if ( !is_admin() ) {

function mvp_SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}

add_filter('pre_get_posts','mvp_SearchFilter');

}

/////////////////////////////////////
// Miscellaneous
/////////////////////////////////////

// Place Wordpress Admin Bar Above Main Navigation

if ( is_user_logged_in() ) {
    if ( is_admin_bar_showing() ) {
    function mvp_admin_bar() {
        echo "
            <style type='text/css'>
            #mvp-leader-wrap {top: 32px;}
            .mvp-nav-small, .mvp-fixed1, .mvp-nav-small-fixed {top: -38px !important;}
            </style>
        ";
    }
    add_action( 'wp_head', 'mvp_admin_bar' );
    }
}

// Set Content Width
if ( ! isset( $content_width ) ) $content_width = 740;

// Add RSS links to <head> section
add_theme_support( 'automatic-feed-links' );

// Output buffering removed - WordPress handles this automatically
// If output buffering is needed, use it only where necessary, not globally on init

/////////////////////////////////////
// Google AMP
/////////////////////////////////////

$mvp_enable_amp = get_option('mvp_amp'); if ($mvp_enable_amp == "true") {

add_filter( 'amp_post_template_file', 'mvp_amp_set_custom_template', 10, 3 );
function mvp_amp_set_custom_template( $file, $type, $post ) {
    if ( 'single' === $type ) {
        $file = dirname( __FILE__ ) . '/amp-single.php';
    }
    return $file;
}

add_action( 'amp_post_template_head', 'isa_remove_amp_google_fonts', 2 );
function isa_remove_amp_google_fonts() {
    remove_action( 'amp_post_template_head', 'amp_post_template_add_fonts' );
}

add_action('amp_post_template_head','mvp_amp_google_font');
 function mvp_amp_google_font( $amp_template ) {
    $mvp_featured_font = get_option('mvp_featured_font');
    $mvp_amp_featured_font = preg_replace("/ /","+",$mvp_featured_font);
    $mvp_title_font = get_option('mvp_title_font');
    $mvp_amp_title_font = preg_replace("/ /","+",$mvp_title_font);
    $mvp_heading_font = get_option('mvp_heading_font');
    $mvp_amp_heading_font = preg_replace("/ /","+",$mvp_heading_font);
    $mvp_content_font = get_option('mvp_content_font');
    $mvp_amp_content_font = preg_replace("/ /","+",$mvp_content_font);
    $mvp_para_font = get_option('mvp_para_font');
    $mvp_amp_para_font = preg_replace("/ /","+",$mvp_para_font);
    $mvp_menu_font = get_option('mvp_menu_font');
    $mvp_amp_menu_font = preg_replace("/ /","+",$mvp_menu_font);
 ?>
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Advent Pro:700|Open Sans:700|<?php echo $mvp_amp_featured_font; ?>:100,200,300,400,500,600,700,800,900|<?php echo $mvp_amp_title_font; ?>:100,200,300,400,500,600,700,800,900|<?php echo $mvp_amp_heading_font; ?>:100,200,300,400,500,600,700,800,900|<?php echo $mvp_amp_content_font; ?>:100,200,300,400,500,600,700,800,900|<?php echo $mvp_amp_para_font; ?>:100,200,300,400,500,600,700,800,900|<?php echo $mvp_amp_menu_font; ?>:100,200,300,400,500,600,700,800,900&subset=arabic,latin,latin-ext,cyrillic,cyrillic-ext,greek-ext,greek,vietnamese">
 <?php
 }

add_action( 'amp_post_template_css', 'mvp_amp_my_additional_css_styles' );
function mvp_amp_my_additional_css_styles( $amp_template ) {
    $wallad = get_option('mvp_wall_ad');
    $primarycolor = get_option('mvp_primary_color');
    $secondcolor = get_option('mvp_second_color');
    $topnavbg = get_option('mvp_top_nav_bg');
    $topnavtext = get_option('mvp_top_nav_text');
    $topnavhover = get_option('mvp_top_nav_hover');
    $botnavbg = get_option('mvp_bot_nav_bg');
    $botnavtext = get_option('mvp_bot_nav_text');
    $botnavhover = get_option('mvp_bot_nav_hover');
    $link = get_option('mvp_link_color');
    $link2 = get_option('mvp_link2_color');
    $featured_font = get_option('mvp_featured_font');
    $title_font = get_option('mvp_title_font');
    $heading_font = get_option('mvp_heading_font');
    $content_font = get_option('mvp_content_font');
    $para_font = get_option('mvp_para_font');
    $menu_font = get_option('mvp_menu_font');
    $mvp_customcss = get_option('mvp_customcss');
    ?>
#mvp-foot-copy a {
    color: <?php echo $link; ?>;
    }

#mvp-content-main p a,
.mvp-post-add-main p a {
    box-shadow: inset 0 -4px 0 <?php echo $link; ?>;
    }

#mvp-content-main p a:hover,
.mvp-post-add-main p a:hover {
    background: <?php echo $link; ?>;
    }

a,
a:visited,
.post-info-name a,
.woocommerce .woocommerce-breadcrumb a {
    color: <?php echo $link2; ?>;
    }

#mvp-side-wrap a:hover {
    color: <?php echo $link2; ?>;
    }

.mvp-fly-top:hover,
.mvp-vid-box-wrap,
ul.mvp-soc-mob-list li.mvp-soc-mob-com {
    background: <?php echo $primarycolor; ?>;
    }

nav.mvp-fly-nav-menu ul li.menu-item-has-children:after,
.mvp-feat1-left-wrap span.mvp-cd-cat,
.mvp-widget-feat1-top-story span.mvp-cd-cat,
.mvp-widget-feat2-left-cont span.mvp-cd-cat,
.mvp-widget-dark-feat span.mvp-cd-cat,
.mvp-widget-dark-sub span.mvp-cd-cat,
.mvp-vid-wide-text span.mvp-cd-cat,
.mvp-feat2-top-text span.mvp-cd-cat,
.mvp-feat3-main-story span.mvp-cd-cat,
.mvp-feat3-sub-text span.mvp-cd-cat,
.mvp-feat4-main-text span.mvp-cd-cat,
.woocommerce-message:before,
.woocommerce-info:before,
.woocommerce-message:before {
    color: <?php echo $primarycolor; ?>;
    }

#searchform input,
.mvp-authors-name {
    border-bottom: 1px solid <?php echo $primarycolor; ?>;
    }

.mvp-fly-top:hover {
    border-top: 1px solid <?php echo $primarycolor; ?>;
    border-left: 1px solid <?php echo $primarycolor; ?>;
    border-bottom: 1px solid <?php echo $primarycolor; ?>;
    }

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce #respond input#submit.alt,
.woocommerce a.button.alt,
.woocommerce button.button.alt,
.woocommerce input.button.alt,
.woocommerce #respond input#submit.alt:hover,
.woocommerce a.button.alt:hover,
.woocommerce button.button.alt:hover,
.woocommerce input.button.alt:hover {
    background-color: <?php echo $primarycolor; ?>;
    }

.woocommerce-error,
.woocommerce-info,
.woocommerce-message {
    border-top-color: <?php echo $primarycolor; ?>;
    }

ul.mvp-feat1-list-buts li.active span.mvp-feat1-list-but,
span.mvp-widget-home-title,
span.mvp-post-cat,
span.mvp-feat1-pop-head {
    background: <?php echo $secondcolor; ?>;
    }

.woocommerce span.onsale {
    background-color: <?php echo $secondcolor; ?>;
    }

.mvp-widget-feat2-side-more-but,
.woocommerce .star-rating span:before,
span.mvp-prev-next-label,
.mvp-cat-date-wrap .sticky {
    color: <?php echo $secondcolor; ?>;
    }

#mvp-main-nav-top,
#mvp-fly-wrap,
.mvp-soc-mob-right,
#mvp-main-nav-small-cont {
    background: <?php echo $topnavbg; ?>;
    }

#mvp-main-nav-small .mvp-fly-but-wrap span,
#mvp-main-nav-small .mvp-search-but-wrap span,
.mvp-nav-top-left .mvp-fly-but-wrap span,
#mvp-fly-wrap .mvp-fly-but-wrap span {
    background: <?php echo $topnavtext; ?>;
    }

.mvp-nav-top-right .mvp-nav-search-but,
span.mvp-fly-soc-head,
.mvp-soc-mob-right i,
#mvp-main-nav-small span.mvp-nav-search-but,
#mvp-main-nav-small .mvp-nav-menu ul li a  {
    color: <?php echo $topnavtext; ?>;
    }

#mvp-main-nav-small .mvp-nav-menu ul li.menu-item-has-children a:after {
    border-color: <?php echo $topnavtext; ?> transparent transparent transparent;
    }

#mvp-nav-top-wrap span.mvp-nav-search-but:hover,
#mvp-main-nav-small span.mvp-nav-search-but:hover {
    color: <?php echo $topnavhover; ?>;
    }

#mvp-nav-top-wrap .mvp-fly-but-wrap:hover span,
#mvp-main-nav-small .mvp-fly-but-wrap:hover span,
span.mvp-woo-cart-num:hover {
    background: <?php echo $topnavhover; ?>;
    }

#mvp-main-nav-bot-cont {
    background: <?php echo $botnavbg; ?>;
    }

#mvp-nav-bot-wrap .mvp-fly-but-wrap span,
#mvp-nav-bot-wrap .mvp-search-but-wrap span {
    background: <?php echo $botnavtext; ?>;
    }

#mvp-nav-bot-wrap span.mvp-nav-search-but,
#mvp-nav-bot-wrap .mvp-nav-menu ul li a {
    color: <?php echo $botnavtext; ?>;
    }

#mvp-nav-bot-wrap .mvp-nav-menu ul li.menu-item-has-children a:after {
    border-color: <?php echo $botnavtext; ?> transparent transparent transparent;
    }

.mvp-nav-menu ul li:hover a {
    border-bottom: 5px solid <?php echo $botnavhover; ?>;
    }

#mvp-nav-bot-wrap .mvp-fly-but-wrap:hover span {
    background: <?php echo $botnavhover; ?>;
    }

#mvp-nav-bot-wrap span.mvp-nav-search-but:hover {
    color: <?php echo $botnavhover; ?>;
    }

body,
.mvp-author-info-text,
span.mvp-post-excerpt,
nav.mvp-fly-nav-menu ul li a,
.mvp-ad-label,
span.mvp-feat-caption,
.mvp-post-tags a,
.mvp-post-tags a:visited,
span.mvp-author-box-name a,
#mvp-author-box-text p,
.mvp-post-gallery-text p,
ul.mvp-soc-mob-list li span,
#comments,
h3#reply-title,
h2.comments,
#mvp-foot-copy p,
span.mvp-fly-soc-head,
.mvp-post-tags-header,
.mvp-post-tags a,
span.mvp-prev-next-label,
span.mvp-post-add-link-but,
#mvp-comments-button a,
#mvp-comments-button span.mvp-comment-but-text,
span.mvp-cd-cat,
span.mvp-cd-date,
span.mvp-widget-home-title2,
.wp-caption,
#mvp-content-main p.wp-caption-text,
.gallery-caption,
.mvp-post-add-main p.wp-caption-text,
.protected-post-form input {
    font-family: '<?php echo $content_font; ?>', sans-serif;
    }

.mvp-blog-story-text p,
span.mvp-author-page-desc,
#mvp-404 p,
.mvp-widget-feat1-bot-text p,
.mvp-widget-feat2-left-text p,
.mvp-flex-story-text p,
.mvp-search-text p,
#mvp-content-main p,
.mvp-post-add-main p,
.rwp-summary,
.rwp-u-review__comment,
.mvp-feat5-mid-main-text p,
.mvp-feat5-small-main-text p {
    font-family: '<?php echo $para_font; ?>', sans-serif;
    }

.mvp-nav-menu ul li a,
#mvp-foot-menu ul li a {
    font-family: '<?php echo $menu_font; ?>', sans-serif;
    }

.mvp-related-text p,
.mvp-post-more-text p {
    font-family: '<?php echo $featured_font; ?>', sans-serif;
    }

h1.mvp-post-title,
h1.mvp-post-title-wide,
#mvp-content-main blockquote p,
.mvp-post-add-main blockquote p,
#mvp-404 h1 {
    font-family: '<?php echo $title_font; ?>', sans-serif;
    }

span.mvp-feat1-pop-head,
.mvp-feat1-pop-text:before,
span.mvp-feat1-list-but,
span.mvp-widget-home-title,
.mvp-widget-feat2-side-more,
span.mvp-post-cat,
span.mvp-page-head,
h1.mvp-author-top-head,
.mvp-authors-name,
#mvp-content-main h1,
#mvp-content-main h2,
#mvp-content-main h3,
#mvp-content-main h4,
#mvp-content-main h5,
#mvp-content-main h6 {
    font-family: '<?php echo $heading_font; ?>', sans-serif;
    }
                    
    <?php
}
    
}

/////////////////////////////////////
// WooCommerce
/////////////////////////////////////

add_theme_support( 'woocommerce' );
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

add_action( 'wp_enqueue_scripts', 'agentwp_dequeue_stylesandscripts', 100 );

function agentwp_dequeue_stylesandscripts() {
    if ( class_exists( 'woocommerce' ) ) {
    wp_dequeue_style( 'selectWoo' );
    wp_deregister_style( 'selectWoo' );
    wp_dequeue_script( 'selectWoo');
    wp_deregister_script('selectWoo');
   }
}

/////////////////////////////////////
// Demo Import
/////////////////////////////////////

function ocdi_import_files() {
  return array(
    array(
      'import_file_name'             => 'Zox News Demo Import',
      'local_import_file'            => trailingslashit( get_template_directory() ) . 'import/zox-news.xml',
      'local_import_widget_file'     => trailingslashit( get_template_directory() ) . 'import/zoxnews.wie',
      'import_preview_image_url'     => trailingslashit( get_template_directory() ) . 'screenshot.png',
      'preview_url'                  => 'https://landtechwebdesigns.com',
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'ocdi_import_files' );

function ocdi_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'main-menu' => $main_menu->term_id,
        )
    );

    // Assign front page. Prefer the page that uses the Home template—avoids ambiguity when multiple
    // pages are titled "Home" (common after re-import or manual duplicates; Customizer then shows two "Home").
    $front_page = null;
    $home_template_pages = get_pages(
        array(
            'meta_key'    => '_wp_page_template',
            'meta_value'  => 'page-home.php',
            'number'      => 1,
            'sort_column' => 'ID',
            'sort_order'  => 'ASC',
        )
    );
    if ( ! empty( $home_template_pages ) ) {
        $front_page = $home_template_pages[0];
    } else {
        $front_page = get_page_by_title( 'Home', OBJECT, 'page' );
    }

    if ( $front_page instanceof WP_Post ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $front_page->ID );
    }

}
add_action( 'pt-ocdi/after_import', 'ocdi_after_import_setup' );

/////////////////////////////////////
// Bundled Plugins
/////////////////////////////////////

require_once get_template_directory() . '/plugins/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'zox_register_required_plugins' );

function zox_register_required_plugins() {
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        array(
            'name'               => 'Vicksburg Daily News Social Buttons',
            'slug'               => 'mvp-social-buttons',
            'source'             => get_template_directory() . '/plugins/mvp-social-buttons.zip',
            'required'           => true,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        
        array(
            'name'               => 'Zox News Auto Load Posts',
            'slug'               => 'zox-alp',
            'source'             => get_template_directory() . '/plugins/zox-alp.zip',
            'required'           => true,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'One Click Demo Import',
            'slug'               => 'one-click-demo-import',
            'source'             => get_template_directory() . '/plugins/one-click-demo-import.zip',
            'required'           => false,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),
        
        array(
            'name'               => 'Google AMP',
            'slug'               => 'mvp-google-amp',
            'source'             => get_template_directory() . '/plugins/amp.zip',
            'required'           => false,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'Reviewer Plugin',
            'slug'               => 'reviewer',
            'source'             => get_template_directory() . '/plugins/reviewer.zip',
            'required'           => false,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'Theia Post Slider',
            'slug'               => 'theia-post-slider',
            'source'             => get_template_directory() . '/plugins/theia-post-slider.zip',
            'required'           => false,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),

        array(
            'name'               => 'Theia Sticky Sidebar',
            'slug'               => 'theia-sticky-sidebar',
            'source'             => get_template_directory() . '/plugins/theia-sticky-sidebar.zip',
            'required'           => false,
            'version'            => '',
            'force_activation'   => false,
            'force_deactivation' => false,
        ),


    );

    $config = array(
        'id'           => 'zox-news',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.

        /*
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'zox-news' ),
            'menu_title'                      => __( 'Install Plugins', 'zox-news' ),
            /* translators: %s: plugin name. * /
            'installing'                      => __( 'Installing Plugin: %s', 'zox-news' ),
            /* translators: %s: plugin name. * /
            'updating'                        => __( 'Updating Plugin: %s', 'zox-news' ),
            'oops'                            => __( 'Something went wrong with the plugin API.', 'zox-news' ),
            'notice_can_install_required'     => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'zox-news'
            ),
            'notice_can_install_recommended'  => _n_noop(
                /* translators: 1: plugin name(s). * /
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'zox-news'
            ),
            'notice_ask_to_update'            => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'zox-news'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                /* translators: 1: plugin name(s). * /
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'zox-news'
            ),
            'notice_can_activate_required'    => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'zox-news'
            ),
            'notice_can_activate_recommended' => _n_noop(
                /* translators: 1: plugin name(s). * /
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'zox-news'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'zox-news'
            ),
            'update_link'                       => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'zox-news'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'zox-news'
            ),
            'return'                          => __( 'Return to Required Plugins Installer', 'zox-news' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'zox-news' ),
            'activated_successfully'          => __( 'The following plugin was activated successfully:', 'zox-news' ),
            /* translators: 1: plugin name. * /
            'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'zox-news' ),
            /* translators: 1: plugin name. * /
            'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'zox-news' ),
            /* translators: 1: dashboard link. * /
            'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'zox-news' ),
            'dismiss'                         => __( 'Dismiss this notice', 'zox-news' ),
            'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'zox-news' ),
            'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'zox-news' ),

            'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
        ),
        */
    );

    tgmpa( $plugins, $config );
}
add_filter('the_content','add_my_content');
function add_my_content($content) {
$my_custom_text = ' <strong><em>See a typo? Report it <a href="mailto:info@vicksburgnews.com">here</a>.</em></strong>'; //
if(is_single() && !is_home()) {
$content .= $my_custom_text;
}
return $content;
}

/*
Start  imagify

*/
function pfx_limit_imagify_optimize_count( $count ) {
  return 1;
}
add_filter( 'imagify_count_optimized_attachments', 'pfx_limit_imagify_optimize_count' );
add_filter( 'imagify_count_attachments', 'pfx_limit_imagify_optimize_count' );
add_filter( 'imagify_count_unoptimized_attachments', 'pfx_limit_imagify_optimize_count' );
add_filter( 'imagify_count_saving_data', 'pfx_limit_imagify_optimize_count' );
add_filter( 'imagify_count_error_attachments', 'pfx_limit_imagify_optimize_count' );
