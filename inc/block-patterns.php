<?php
/**
 * Optional block patterns (editor only until inserted into content).
 *
 * @package zox-news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize markup to Core serialization so the editor does not report invalid blocks.
 *
 * @param string $markup Raw block markup (parseable by parse_blocks).
 * @return string
 */
function vdn_prepare_pattern_content( $markup ) {
	if ( ! function_exists( 'parse_blocks' ) || ! function_exists( 'serialize_blocks' ) ) {
		return $markup;
	}
	$markup = trim( (string) $markup );
	$blocks = parse_blocks( $markup );
	if ( empty( $blocks ) ) {
		return $markup;
	}
	return serialize_blocks( $blocks );
}

/**
 * Register pattern category and patterns for Vicksburg Daily News.
 */
function vdn_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	if ( class_exists( 'WP_Block_Pattern_Categories_Registry', false ) && ! WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( 'vicksburg-daily-news' ) ) {
		register_block_pattern_category(
			'vicksburg-daily-news',
			array(
				'label' => __( 'Vicksburg Daily News', 'zox-news' ),
			)
		);
	} elseif ( ! class_exists( 'WP_Block_Pattern_Categories_Registry', false ) ) {
		register_block_pattern_category(
			'vicksburg-daily-news',
			array(
				'label' => __( 'Vicksburg Daily News', 'zox-news' ),
			)
		);
	}

	$callout_body = '<p><strong>' . esc_html__( 'Callout:', 'zox-news' ) . '</strong> ' . esc_html__( 'Replace this text with a short highlight.', 'zox-news' ) . '</p>';

	// Paragraph only — core/group saved markup differs too much by WP version and causes "invalid content" for many sites.
	// Editors can select the paragraph and choose "Group" to add a box, border, or background.
	$article_callout_raw = '<!-- wp:paragraph -->
' . $callout_body . '
<!-- /wp:paragraph -->';

	$link1 = esc_html__( 'First related article (edit link)', 'zox-news' );
	$link2 = esc_html__( 'Second related article (edit link)', 'zox-news' );
	$h_rel = esc_html__( 'Related', 'zox-news' );

	// List block removed — nested list-item markup often fails validation. Use plain links; add bullets in UI if desired.
	$related_raw = '<!-- wp:heading {"level":3} -->
<h3>' . $h_rel . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><a href="#">' . $link1 . '</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="#">' . $link2 . '</a></p>
<!-- /wp:paragraph -->';

	$quote_text = esc_html__( 'Add a memorable quote from the story or source.', 'zox-news' );

	// Quote: include saved wrapper so PHP/JS parsers agree (comment-only quote often invalidates).
	$quote_raw = '<!-- wp:quote -->
<blockquote class="wp-block-quote"><!-- wp:paragraph -->
<p>' . $quote_text . '</p>
<!-- /wp:paragraph --></blockquote>
<!-- /wp:quote -->';

	$correction_raw = '<!-- wp:paragraph -->
<p><strong>' . esc_html__( 'Correction:', 'zox-news' ) . '</strong> ' . esc_html__( 'Describe what was wrong and how it has been corrected.', 'zox-news' ) . '</p>
<!-- /wp:paragraph -->';

	$h_dates = esc_html__( 'Key dates', 'zox-news' );
	$timeline_raw = '<!-- wp:heading {"level":3} -->
<h3>' . $h_dates . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><strong>' . esc_html__( 'Month 0, 0000 —', 'zox-news' ) . '</strong> ' . esc_html__( 'First milestone or event.', 'zox-news' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>' . esc_html__( 'Month 0, 0000 —', 'zox-news' ) . '</strong> ' . esc_html__( 'Second milestone or event.', 'zox-news' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>' . esc_html__( 'Month 0, 0000 —', 'zox-news' ) . '</strong> ' . esc_html__( 'Third milestone or event.', 'zox-news' ) . '</p>
<!-- /wp:paragraph -->';

	$h_staff = esc_html__( 'About this coverage', 'zox-news' );
	$staff_raw = '<!-- wp:heading {"level":3} -->
<h3>' . $h_staff . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__( 'Add reporter byline notes, contact info for tips, or how this story was reported.', 'zox-news' ) . '</p>
<!-- /wp:paragraph -->';

	register_block_pattern(
		'vicksburg-daily-news/article-callout',
		array(
			'title'       => __( 'Article callout', 'zox-news' ),
			'description' => __( 'Bold callout paragraph. Optional: select it and convert to a Group to add a border or background.', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $article_callout_raw ),
		)
	);

	register_block_pattern(
		'vicksburg-daily-news/related-links',
		array(
			'title'       => __( 'Related links', 'zox-news' ),
			'description' => __( 'Heading plus two linked paragraphs (convert to a List block in the editor if you prefer).', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $related_raw ),
		)
	);

	register_block_pattern(
		'vicksburg-daily-news/pull-quote-inline',
		array(
			'title'       => __( 'Pull quote', 'zox-news' ),
			'description' => __( 'Quote block with placeholder text; use block settings for citation if needed.', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $quote_raw ),
		)
	);

	register_block_pattern(
		'vicksburg-daily-news/correction-notice',
		array(
			'title'       => __( 'Correction notice', 'zox-news' ),
			'description' => __( 'Short correction paragraph for the top or bottom of an updated story.', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $correction_raw ),
		)
	);

	register_block_pattern(
		'vicksburg-daily-news/key-dates',
		array(
			'title'       => __( 'Key dates / timeline', 'zox-news' ),
			'description' => __( 'Heading plus dated lines for timelines or running stories.', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $timeline_raw ),
		)
	);

	register_block_pattern(
		'vicksburg-daily-news/staff-box',
		array(
			'title'       => __( 'Staff / reporting box', 'zox-news' ),
			'description' => __( 'Small text block for bylines, tip lines, or reporting methodology.', 'zox-news' ),
			'categories'  => array( 'vicksburg-daily-news' ),
			'content'     => vdn_prepare_pattern_content( $staff_raw ),
		)
	);
}

add_action( 'init', 'vdn_register_block_patterns', 11 );
