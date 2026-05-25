<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" >
<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { if(get_option('mvp_favicon')) { ?><link rel="shortcut icon" href="<?php echo zox_news_esc_url_option('mvp_favicon'); ?>" /><?php } } ?>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if ( ! vdn_yoast_seo_active() ) : ?>
<?php if ( is_single() ) { ?>
<meta property="og:type" content="article" />
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
		<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mvp-post-thumb' ); ?>
		<meta property="og:image" content="<?php echo esc_url( is_array( $thumb ) && ! empty( $thumb[0] ) ? $thumb[0] : '' ); ?>" />
		<meta name="twitter:image" content="<?php echo esc_url( is_array( $thumb ) && ! empty( $thumb[0] ) ? $thumb[0] : '' ); ?>" />
	<?php } ?>
<meta property="og:url" content="<?php the_permalink() ?>" />
<meta property="og:title" content="<?php the_title_attribute(); ?>" />
<meta property="og:description" content="<?php echo strip_tags(get_the_excerpt()); ?>" />
<meta name="twitter:card" content="summary">
<meta name="twitter:url" content="<?php the_permalink() ?>">
<meta name="twitter:title" content="<?php the_title_attribute(); ?>">
<meta name="twitter:description" content="<?php echo strip_tags(get_the_excerpt()); ?>">
<?php endwhile; endif; ?>
<?php } else { ?>
<meta property="og:description" content="<?php bloginfo('description'); ?>" />
<?php } ?>
<?php endif; ?>
<?php wp_head(); ?>
<script data-ad-client="ca-pub-3727402376742652" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
</head>
<body <?php body_class(''); ?>>
	<?php if ( is_singular( 'post' ) && get_option( 'mvp_reading_progress', 'false' ) === 'true' ) : ?>
	<div class="mvp-read-progress-track" aria-hidden="true"><span class="mvp-read-progress-bar"></span></div>
	<?php endif; ?>
	<a class="mvp-skip-link screen-reader-text" href="#mvp-main-body-wrap"><?php esc_html_e( 'Skip to content', 'zox-news' ); ?></a>
	<?php get_template_part('fly-menu'); ?>
	<div id="mvp-site" class="left relative">
		<div id="mvp-search-wrap">
			<div id="mvp-search-box">
				<?php get_search_form(); ?>
			</div><!--mvp-search-box-->
			<div class="mvp-search-but-wrap mvp-search-click">
				<span></span>
				<span></span>
			</div><!--mvp-search-but-wrap-->
		</div><!--mvp-search-wrap-->
		<?php if(get_option('mvp_wall_ad')) { ?>
			<div id="mvp-wallpaper">
				<?php if(get_option('mvp_wall_url')) { ?>
					<a href="<?php echo zox_news_esc_url_option('mvp_wall_url'); ?>" class="mvp-wall-link" target="_blank"></a>
				<?php } ?>
			</div><!--mvp-wallpaper-->
		<?php } ?>
		<div id="mvp-site-wall" class="left relative">
			<?php if(get_option('mvp_header_leader')) { ?>
				<?php global $post; $mvp_post_layout = get_option('mvp_post_layout'); $mvp_post_temp = get_post_meta($post->ID, "mvp_post_template", true); if ( ( ! $mvp_post_temp && $mvp_post_layout == 'Template 5' && is_single() ) || ( ! $mvp_post_temp && $mvp_post_layout == 'Template 6' && is_single() ) || ( $mvp_post_temp == "global" && $mvp_post_layout == 'Template 5' && is_single() ) || ( $mvp_post_temp == "global" && $mvp_post_layout == 'Template 6' && is_single() ) || ( $mvp_post_temp == "temp5" && is_single() ) || ( $mvp_post_temp == "temp6" && is_single() ) ) { } else { ?>
				<div id="mvp-leader-wrap">
					<?php if( function_exists('the_ad_placement') ) { the_ad_placement('manual-placement'); } ?>
					<?php $leader_ad = get_option('mvp_header_leader'); if ($leader_ad && ! is_404()) { echo html_entity_decode($leader_ad); } ?>
				</div><!--mvp-leader-wrap-->
				<?php } ?>
			<?php } ?>
			<div id="mvp-site-main" class="left relative">
			<header id="mvp-main-head-wrap" class="left relative">
				<?php $mvp_nav_layout = get_option('mvp_nav_layout'); if( $mvp_nav_layout == "1" ) { ?>
					<nav id="mvp-main-nav-wrap" class="left relative">
						<div id="mvp-main-nav-small" class="left relative">
							<div id="mvp-main-nav-small-cont" class="left">
								<div class="mvp-main-box">
									<div id="mvp-nav-small-wrap">
										<div class="mvp-nav-small-right-out left">
											<div class="mvp-nav-small-right-in">
												<div class="mvp-nav-small-cont left">
													<div class="mvp-nav-small-left-out right">
														<div id="mvp-nav-small-left" class="left relative">
															<div class="mvp-fly-but-wrap mvp-fly-but-click left relative">
																<span></span>
																<span></span>
																<span></span>
																<span></span>
															</div><!--mvp-fly-but-wrap-->
														</div><!--mvp-nav-small-left-->
														<div class="mvp-nav-small-left-in">
															<div class="mvp-nav-small-mid left">
																<div class="mvp-nav-small-logo left relative">
																	<?php if(get_option('mvp_logo_nav')) { ?>
																		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zox_news_esc_url_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
																	<?php } else { ?>
																		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
																	<?php } ?>
																	<?php if ( is_home() || is_front_page() ) { ?>
																		<h1 class="mvp-logo-title"><?php bloginfo( 'name' ); ?></h1>
																	<?php } else { ?>
																		<h2 class="mvp-logo-title"><?php bloginfo( 'name' ); ?></h2>
																	<?php } ?>
																</div><!--mvp-nav-small-logo-->
																<div class="mvp-nav-small-mid-right left">
																	<?php if (is_single()) { ?>
																		<div class="mvp-drop-nav-title left">
																			<h4><?php the_title(); ?></h4>
																		</div><!--mvp-drop-nav-title-->
																	<?php } ?>
																	<div class="mvp-nav-menu left">
																		<?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
																	</div><!--mvp-nav-menu-->
																</div><!--mvp-nav-small-mid-right-->
															</div><!--mvp-nav-small-mid-->
														</div><!--mvp-nav-small-left-in-->
													</div><!--mvp-nav-small-left-out-->
												</div><!--mvp-nav-small-cont-->
											</div><!--mvp-nav-small-right-in-->
											<div id="mvp-nav-small-right" class="right relative">
												<span class="mvp-nav-search-but fa fa-search fa-2 mvp-search-click"></span>
											</div><!--mvp-nav-small-right-->
										</div><!--mvp-nav-small-right-out-->
									</div><!--mvp-nav-small-wrap-->
								</div><!--mvp-main-box-->
							</div><!--mvp-main-nav-small-cont-->
						</div><!--mvp-main-nav-small-->
					</nav><!--mvp-main-nav-wrap-->
				<?php } else { ?>
					<nav id="mvp-main-nav-wrap" class="left relative">
						<div id="mvp-main-nav-top" class="left relative">
							<div class="mvp-main-box">
								<div id="mvp-nav-top-wrap" class="left relative">
									<div class="mvp-nav-top-right-out left relative">
										<div class="mvp-nav-top-right-in">
											<div class="mvp-nav-top-cont left relative">
												<div class="mvp-nav-top-left-out relative">
													<div class="mvp-nav-top-left">
														<div class="mvp-nav-soc-wrap">
															<?php if(get_option('mvp_facebook')) { ?>
																<a href="<?php echo esc_html(get_option('mvp_facebook')); ?>" target="_blank"><span class="mvp-nav-soc-but fa fa-facebook fa-2"></span></a>
															<?php } ?>
															<?php if(get_option('mvp_twitter')) { ?>
																<a href="<?php echo esc_html(get_option('mvp_twitter')); ?>" target="_blank"><span class="mvp-nav-soc-but fa fa-twitter fa-2"></span></a>
															<?php } ?>
															<?php if(get_option('mvp_instagram')) { ?>
																<a href="<?php echo esc_html(get_option('mvp_instagram')); ?>" target="_blank"><span class="mvp-nav-soc-but fa fa-instagram fa-2"></span></a>
															<?php } ?>
															<?php if(get_option('mvp_youtube')) { ?>
																<a href="<?php echo esc_html(get_option('mvp_youtube')); ?>" target="_blank"><span class="mvp-nav-soc-but fa fa-youtube-play fa-2"></span></a>
															<?php } ?>
														</div><!--mvp-nav-soc-wrap-->
														<div class="mvp-fly-but-wrap mvp-fly-but-click left relative">
															<span></span>
															<span></span>
															<span></span>
															<span></span>
														</div><!--mvp-fly-but-wrap-->
													</div><!--mvp-nav-top-left-->
													<div class="mvp-nav-top-left-in">
														<div class="mvp-nav-top-mid left relative">
															<?php if(get_option('mvp_logo')) { ?>
																<a class="mvp-nav-logo-reg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zox_news_esc_url_option('mvp_logo'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
															<?php } else { ?>
																<a class="mvp-nav-logo-reg" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-large.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
															<?php } ?>
															<?php if(get_option('mvp_logo_nav')) { ?>
																<a class="mvp-nav-logo-small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zox_news_esc_url_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
															<?php } else { ?>
																<a class="mvp-nav-logo-small" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
															<?php } ?>
															<?php if ( is_home() || is_front_page() ) { ?>
																<h1 class="mvp-logo-title"><?php bloginfo( 'name' ); ?></h1>
															<?php } else { ?>
																<h2 class="mvp-logo-title"><?php bloginfo( 'name' ); ?></h2>
															<?php } ?>
															<?php if (is_single()) { ?>
																<div class="mvp-drop-nav-title left">
																	<h4><?php the_title(); ?></h4>
																</div><!--mvp-drop-nav-title-->
															<?php } ?>
														</div><!--mvp-nav-top-mid-->
													</div><!--mvp-nav-top-left-in-->
												</div><!--mvp-nav-top-left-out-->
											</div><!--mvp-nav-top-cont-->
										</div><!--mvp-nav-top-right-in-->
										<div class="mvp-nav-top-right">
											<?php if ( class_exists( 'WooCommerce' ) ) { ?>
												<div class="mvp-woo-cart-wrap">
													<a class="mvp-woo-cart" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_html_e( 'View your shopping cart', 'zox-news' ); ?>"><span class="mvp-woo-cart-num"><?php echo WC()->cart->get_cart_contents_count(); ?></span></a><span class="mvp-woo-cart-icon fa fa-shopping-cart" aria-hidden="true"></span>
												</div><!--mvp-woo-cart-wrap-->
											<?php } ?>
											<span class="mvp-nav-search-but fa fa-search fa-2 mvp-search-click"></span>
										</div><!--mvp-nav-top-right-->
									</div><!--mvp-nav-top-right-out-->
								</div><!--mvp-nav-top-wrap-->
							</div><!--mvp-main-box-->
						</div><!--mvp-main-nav-top-->
						<div id="mvp-main-nav-bot" class="left relative">
							<div id="mvp-main-nav-bot-cont" class="left">
								<div class="mvp-main-box">
									<div id="mvp-nav-bot-wrap" class="left">
										<div class="mvp-nav-bot-right-out left">
											<div class="mvp-nav-bot-right-in">
												<div class="mvp-nav-bot-cont left">
													<div class="mvp-nav-bot-left-out">
														<div class="mvp-nav-bot-left left relative">
															<div class="mvp-fly-but-wrap mvp-fly-but-click left relative">
																<span></span>
																<span></span>
																<span></span>
																<span></span>
															</div><!--mvp-fly-but-wrap-->
														</div><!--mvp-nav-bot-left-->
														<div class="mvp-nav-bot-left-in">
															<div class="mvp-nav-menu left">
																<?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
															</div><!--mvp-nav-menu-->
														</div><!--mvp-nav-bot-left-in-->
													</div><!--mvp-nav-bot-left-out-->
												</div><!--mvp-nav-bot-cont-->
											</div><!--mvp-nav-bot-right-in-->
											<div class="mvp-nav-bot-right left relative">
												<span class="mvp-nav-search-but fa fa-search fa-2 mvp-search-click"></span>
											</div><!--mvp-nav-bot-right-->
										</div><!--mvp-nav-bot-right-out-->
									</div><!--mvp-nav-bot-wrap-->
								</div><!--mvp-main-nav-bot-cont-->
							</div><!--mvp-main-box-->
						</div><!--mvp-main-nav-bot-->
					</nav><!--mvp-main-nav-wrap-->
				<?php } ?>
			</header><!--mvp-main-head-wrap-->
			<div id="mvp-main-body-wrap" class="left relative" tabindex="-1">