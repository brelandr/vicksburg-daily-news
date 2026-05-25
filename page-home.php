<?php
	/* Template Name: Home */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<?php $mvp_feat_posts = get_option('mvp_feat_posts'); if ($mvp_feat_posts == "true") { ?>
	<?php get_template_part('featured'); ?>
<?php } ?>
<?php if(get_option('mvp_home_layout') == '1' || get_option('mvp_home_layout') == '2') { ?>
	<div id="mvp-home-widget-wrap" class="left relative">
		<?php if ( is_active_sidebar( 'homepage-widget' ) ) { ?>
			<?php dynamic_sidebar( 'homepage-widget' ); ?>
		<?php } ?>
	</div><!--mvp-home-widget-wrap-->
<?php } ?>
<?php if(get_option('mvp_home_layout') == '0' || get_option('mvp_home_layout') == '2') { ?>
	<div class="mvp-main-blog-wrap left relative">
		<div class="mvp-main-box">
			<div class="mvp-main-blog-cont left relative">
				<div class="mvp-widget-home-head">
					<h4 class="mvp-widget-home-title">
						<span class="mvp-widget-home-title"><?php esc_html_e( 'More News', 'zox-news' ); ?></span>
					</h4>
				</div><!--mvp-widget-home-head-->
				<div class="mvp-main-blog-out left relative">
					<div class="mvp-main-blog-in">
						<div class="mvp-main-blog-body left relative">
							<?php
							// Pagination / infinite-scroll must use the same query as the list below. When featured
							// posts populate $do_not_duplicate, loops use WP_Query and global $wp_query stays the
							// static front page—so $wp_query->max_num_pages is wrong (often 1) and no nav links render.
							$mvp_home_blog_max_pages = null;
							// VDN home-feed AdSense inserts: inc/vdn-customize-home-feed-ads.php (Customizer).
							// Theme infinite-scroll AJAX appends posts without re-running PHP here; ad rows follow
							// the first server-rendered batch only unless extended in JS later.
							?>
							<?php if(get_option('mvp_blog_layout') == '1' ) { ?>
								<ul class="mvp-blog-story-list-col left relative infinite-content">
									<?php
									if ( function_exists( 'vdn_home_feed_reset_story_counter' ) ) {
										vdn_home_feed_reset_story_counter();
									}
									?>
									<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
										<?php global $post; $mvp_posts_num = esc_html(get_option('mvp_posts_num')); $paged = (get_query_var('page')) ? get_query_var('page') : 1; $home_col_query1 = new WP_Query(array( 'posts_per_page' => $mvp_posts_num, 'post__not_in'=>$do_not_duplicate, 'paged' =>$paged )); if ($home_col_query1->have_posts()) : while ($home_col_query1->have_posts()) : $home_col_query1->the_post(); ?>
											<li class="mvp-blog-story-col left relative infinite-post">
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
													<div class="mvp-blog-story-out relative">
														<div class="mvp-blog-story-img left relative">
															<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
															<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
															<?php if ( has_post_format( 'video' )) { ?>
																<div class="mvp-vid-box-wrap mvp-vid-marg">
																	<i class="fa fa-2 fa-play" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } else if ( has_post_format( 'gallery' )) { ?>
																<div class="mvp-vid-box-wrap">
																	<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } ?>
														</div><!--mvp-blog-story-img-->
														<div class="mvp-blog-story-in">
															<div class="mvp-blog-story-text left relative">
																<div class="mvp-cat-date-wrap left relative">
																	<?php if ( is_sticky() ) { ?>
																		<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } else { ?>
																		<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } ?>
																</div><!--mvp-cat-date-wrap-->
																<h2><?php the_title(); ?></h2>
																<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
															</div><!--mvp-blog-story-text-->
														</div><!--mvp-blog-story-in-->
													</div><!--mvp-blog-story-out-->
												<?php } else { ?>
													<div class="mvp-blog-story-text left relative">
														<div class="mvp-cat-date-wrap left relative">
															<?php if ( is_sticky() ) { ?>
																<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } else { ?>
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } ?>
														</div><!--mvp-cat-date-wrap-->
														<h2><?php the_title(); ?></h2>
														<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
													</div><!--mvp-blog-story-text-->
												<?php } ?>
												</a>
											</li><!--mvp-blog-story-wrap-->
											<?php if ( function_exists( 'vdn_home_feed_maybe_render_ad_li' ) ) { vdn_home_feed_maybe_render_ad_li( 'col' ); } ?>
										<?php endwhile; endif; $mvp_home_blog_max_pages = $home_col_query1->max_num_pages; wp_reset_postdata(); ?>
									<?php } else { ?>
										<?php $mvp_posts_num = esc_html(get_option('mvp_posts_num')); $paged = (get_query_var('page')) ? get_query_var('page') : 1; query_posts(array( 'posts_per_page' => $mvp_posts_num, 'paged' =>$paged )); if (have_posts()) : while (have_posts()) : the_post(); ?>
											<li class="mvp-blog-story-col left relative infinite-post">
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
													<div class="mvp-blog-story-out relative">
														<div class="mvp-blog-story-img left relative">
															<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
															<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
															<?php if ( has_post_format( 'video' )) { ?>
																<div class="mvp-vid-box-wrap mvp-vid-marg">
																	<i class="fa fa-2 fa-play" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } else if ( has_post_format( 'gallery' )) { ?>
																<div class="mvp-vid-box-wrap">
																	<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } ?>
														</div><!--mvp-blog-story-img-->
														<div class="mvp-blog-story-in">
															<div class="mvp-blog-story-text left relative">
																<div class="mvp-cat-date-wrap left relative">
																	<?php if ( is_sticky() ) { ?>
																		<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } else { ?>
																		<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } ?>
																</div><!--mvp-cat-date-wrap-->
																<h2><?php the_title(); ?></h2>
																<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
															</div><!--mvp-blog-story-text-->
														</div><!--mvp-blog-story-in-->
													</div><!--mvp-blog-story-out-->
												<?php } else { ?>
													<div class="mvp-blog-story-text left relative">
														<div class="mvp-cat-date-wrap left relative">
															<?php if ( is_sticky() ) { ?>
																<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } else { ?>
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } ?>
														</div><!--mvp-cat-date-wrap-->
														<h2><?php the_title(); ?></h2>
														<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
													</div><!--mvp-blog-story-text-->
												<?php } ?>
												</a>
											</li><!--mvp-blog-story-wrap-->
											<?php if ( function_exists( 'vdn_home_feed_maybe_render_ad_li' ) ) { vdn_home_feed_maybe_render_ad_li( 'col' ); } ?>
										<?php endwhile; endif; $mvp_home_blog_max_pages = $wp_query->max_num_pages; wp_reset_query(); ?>
									<?php } ?>
								</ul>
							<?php } else { ?>
								<ul class="mvp-blog-story-list left relative infinite-content">
								<?php
								if ( function_exists( 'vdn_home_feed_reset_story_counter' ) ) {
									vdn_home_feed_reset_story_counter();
								}
								?>
								<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
									<?php $mvp_posts_num = esc_html(get_option('mvp_posts_num')); $paged = (get_query_var('page')) ? get_query_var('page') : 1; $home_row_query1 = new WP_Query(array( 'posts_per_page' => $mvp_posts_num, 'post__not_in'=>$do_not_duplicate, 'paged' =>$paged )); if ($home_row_query1->have_posts()) : while ($home_row_query1->have_posts()) : $home_row_query1->the_post(); ?>
											<li class="mvp-blog-story-wrap left relative infinite-post">
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
													<div class="mvp-blog-story-out relative">
														<div class="mvp-blog-story-img left relative">
															<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
															<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
															<?php if ( has_post_format( 'video' )) { ?>
																<div class="mvp-vid-box-wrap mvp-vid-marg">
																	<i class="fa fa-2 fa-play" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } else if ( has_post_format( 'gallery' )) { ?>
																<div class="mvp-vid-box-wrap">
																	<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } ?>
														</div><!--mvp-blog-story-img-->
														<div class="mvp-blog-story-in">
															<div class="mvp-blog-story-text left relative">
																<div class="mvp-cat-date-wrap left relative">
																	<?php if ( is_sticky() ) { ?>
																		<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } else { ?>
																		<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } ?>
																</div><!--mvp-cat-date-wrap-->
																<h2><?php the_title(); ?></h2>
																<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
															</div><!--mvp-blog-story-text-->
														</div><!--mvp-blog-story-in-->
													</div><!--mvp-blog-story-out-->
												<?php } else { ?>
													<div class="mvp-blog-story-text left relative">
														<div class="mvp-cat-date-wrap left relative">
															<?php if ( is_sticky() ) { ?>
																<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } else { ?>
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } ?>
														</div><!--mvp-cat-date-wrap-->
														<h2><?php the_title(); ?></h2>
														<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
													</div><!--mvp-blog-story-text-->
												<?php } ?>
												</a>
											</li><!--mvp-blog-story-wrap-->
											<?php if ( function_exists( 'vdn_home_feed_maybe_render_ad_li' ) ) { vdn_home_feed_maybe_render_ad_li( 'row' ); } ?>
										<?php endwhile; endif; $mvp_home_blog_max_pages = $home_row_query1->max_num_pages; wp_reset_postdata(); ?>
									<?php } else { ?>
										<?php $mvp_posts_num = esc_html(get_option('mvp_posts_num')); $paged = (get_query_var('page')) ? get_query_var('page') : 1; query_posts(array( 'posts_per_page' => $mvp_posts_num, 'paged' =>$paged )); if (have_posts()) : while (have_posts()) : the_post(); ?>
											<li class="mvp-blog-story-wrap left relative infinite-post">
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
													<div class="mvp-blog-story-out relative">
														<div class="mvp-blog-story-img left relative">
															<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
															<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
															<?php if ( has_post_format( 'video' )) { ?>
																<div class="mvp-vid-box-wrap mvp-vid-marg">
																	<i class="fa fa-2 fa-play" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } else if ( has_post_format( 'gallery' )) { ?>
																<div class="mvp-vid-box-wrap">
																	<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
																</div><!--mvp-vid-box-wrap-->
															<?php } ?>
														</div><!--mvp-blog-story-img-->
														<div class="mvp-blog-story-in">
															<div class="mvp-blog-story-text left relative">
																<div class="mvp-cat-date-wrap left relative">
																	<?php if ( is_sticky() ) { ?>
																		<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } else { ?>
																		<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
																	<?php } ?>
																</div><!--mvp-cat-date-wrap-->
																<h2><?php the_title(); ?></h2>
																<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
															</div><!--mvp-blog-story-text-->
														</div><!--mvp-blog-story-in-->
													</div><!--mvp-blog-story-out-->
												<?php } else { ?>
													<div class="mvp-blog-story-text left relative">
														<div class="mvp-cat-date-wrap left relative">
															<?php if ( is_sticky() ) { ?>
																<span class="mvp-cd-cat left relative sticky"><?php esc_html_e( 'Sticky Post', 'zox-news' ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } else { ?>
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															<?php } ?>
														</div><!--mvp-cat-date-wrap-->
														<h2><?php the_title(); ?></h2>
														<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
													</div><!--mvp-blog-story-text-->
												<?php } ?>
												</a>
											</li><!--mvp-blog-story-wrap-->
											<?php if ( function_exists( 'vdn_home_feed_maybe_render_ad_li' ) ) { vdn_home_feed_maybe_render_ad_li( 'row' ); } ?>
										<?php endwhile; endif; $mvp_home_blog_max_pages = $wp_query->max_num_pages; wp_reset_query(); ?>
									<?php } ?>
								</ul>
							<?php } ?>
							<div class="mvp-inf-more-wrap left relative">
								<?php $mvp_infinite_scroll = get_option('mvp_infinite_scroll'); if ($mvp_infinite_scroll == "true") { if (isset($mvp_infinite_scroll)) { ?>
									<a href="#" class="mvp-inf-more-but"><?php esc_html_e( 'More Posts', 'zox-news' ); ?></a>
								<?php } } ?>
								<div class="mvp-nav-links">
									<?php if (function_exists("pagination")) { pagination( isset( $mvp_home_blog_max_pages ) ? $mvp_home_blog_max_pages : $wp_query->max_num_pages ); } ?>
								</div><!--mvp-nav-links-->
							</div><!--mvp-inf-more-wrap-->
						</div><!--mvp-main-blog-body-->
					</div><!--mvp-main-blog-in-->
					<?php get_sidebar('home'); ?>
				</div><!--mvp-mmain-blog-out-->
			</div><!--mvp-main-blog-cont-->
		</div><!--mvp-main-box-->
	</div><!--mvp-main-blog-wrap-->
<?php } ?>
<?php get_footer(); ?>