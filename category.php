<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="mvp-main-blog-wrap left relative">
	<div class="mvp-main-box">
		<div class="mvp-main-blog-cont left relative">
			<?php $mvp_feat_cat = get_option('mvp_feat_cat'); if ($mvp_feat_cat == "true") { if ( $paged < 2 ) { ?>
				<?php $mvp_cat_layout = get_option('mvp_cat_layout'); if( $mvp_cat_layout == "1" ) { ?>
					<section id="mvp-feat6-wrap" class="left relative">
						<?php global $do_not_duplicate; global $post; $current_category = single_cat_title("", false); $category_id = get_cat_ID($current_category); $cat_posts = new WP_Query(array( 'cat' => $category_id, 'posts_per_page' => '1', 'ignore_sticky_posts'=> 1 )); while($cat_posts->have_posts()) : $cat_posts->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
							<a href="<?php the_permalink(); ?>" rel="bookmark">
							<div id="mvp-feat6-main" class="left relative">
								<div id="mvp-feat6-img" class="right relative">
									<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
										<?php the_post_thumbnail('mvp-post-thumb', array( 'class' => 'mvp-reg-img' )); ?>
										<?php the_post_thumbnail('mvp-port-thumb', array( 'class' => 'mvp-mob-img' )); ?>
									<?php } ?>
								</div><!--mvp-feat6-img-->
								<div id="mvp-feat6-text">
									<h3 class="mvp-feat1-pop-head"><span class="mvp-feat1-pop-head"><?php single_cat_title(); ?></span></h3>
									<h2><?php the_title(); ?></h2>
									<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
								</div><!--mvp-feat6-text-->
							</div><!--mvp-feat6-main-->
							</a>
						<?php } endwhile; wp_reset_postdata(); ?>
					</section><!--mvp-feat6-wrap-->
				<?php } ?>
			<?php } } ?>
			<div class="mvp-main-blog-out left relative">
				<div class="mvp-main-blog-in">
					<div class="mvp-main-blog-body left relative">
						<?php $mvp_feat_cat = get_option('mvp_feat_cat'); if ($mvp_feat_cat == "true") { if ( $paged < 2 ) { ?>
							<?php $mvp_cat_layout = get_option('mvp_cat_layout'); if( $mvp_cat_layout == "0" ) { ?>
								<div id="mvp-cat-feat-wrap" class="left relative">
									<div class="mvp-widget-feat2-left left relative mvp-widget-feat2-left-alt">
										<?php global $do_not_duplicate; global $post; $current_category = single_cat_title("", false); $category_id = get_cat_ID($current_category); $cat_posts = new WP_Query(array( 'cat' => $category_id, 'posts_per_page' => '1', 'ignore_sticky_posts'=> 1 )); while($cat_posts->have_posts()) : $cat_posts->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
											<a href="<?php the_permalink(); ?>" rel="bookmark">
											<div class="mvp-widget-feat2-left-cont left relative">
												<div class="mvp-feat1-feat-img left relative">
													<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
														<?php the_post_thumbnail('mvp-port-thumb'); ?>
													<?php } ?>
													<?php if ( has_post_format( 'video' )) { ?>
														<div class="mvp-vid-box-wrap mvp-vid-marg">
															<i class="fa fa-2 fa-play" aria-hidden="true"></i>
														</div><!--mvp-vid-box-wrap-->
													<?php } else if ( has_post_format( 'gallery' )) { ?>
														<div class="mvp-vid-box-wrap">
															<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
														</div><!--mvp-vid-box-wrap-->
													<?php } ?>
												</div><!--mvp-feat1-feat-img-->
												<div class="mvp-feat1-feat-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<?php if(get_post_meta($post->ID, "mvp_featured_headline", true)): ?>
														<h2><?php echo esc_html(get_post_meta($post->ID, "mvp_featured_headline", true)); ?></h2>
													<?php else: ?>
														<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<?php endif; ?>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-feat1-feat-text-->
											</div><!--mvp-widget-feat2-left-cont-->
											</a>
										<?php } endwhile; wp_reset_postdata(); ?>
									</div><!--mvp-widget-feat2-left-->
									<div class="mvp-widget-feat2-right left relative">
										<h1 class="mvp-feat1-pop-head"><span class="mvp-feat1-pop-head"><?php single_cat_title(); ?></span></h1>
										<div class="mvp-widget-feat2-right-main left relative">
											<?php global $do_not_duplicate; global $post; $current_category = single_cat_title("", false); $category_id = get_cat_ID($current_category); $cat_posts = new WP_Query(array( 'cat' => $category_id, 'post__not_in'=>$do_not_duplicate, 'posts_per_page' => '2', 'ignore_sticky_posts'=> 1 )); while($cat_posts->have_posts()) : $cat_posts->the_post(); $do_not_duplicate[] = $post->ID; ?>
												<a href="<?php the_permalink(); ?>" rel="bookmark">
												<div class="mvp-widget-feat2-right-cont left relative">
													<div class="mvp-widget-feat2-right-img left relative">
														<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
															<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
															<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
														<?php } ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-box-mid mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-box-mid">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-widget-feat2-right-img-->
													<div class="mvp-widget-feat2-right-text left relative">
														<div class="mvp-cat-date-wrap left relative">
															<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); if (isset($category)) { echo esc_html( $category[0]->cat_name ); } ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
														</div><!--mvp-cat-date-wrap-->
														<h2><?php the_title(); ?></h2>
													</div><!--mvp-widget-feat2-right-text-->
												</div><!--mvp-widget-feat2-right-cont-->
												</a>
											<?php endwhile; wp_reset_postdata(); ?>
										</div><!--mvp-widget-feat2-right-main-->
									</div><!--mvp-widget-feat2-right-->
								</div><!--mvp-cat-feat-wrap-->
							<?php } ?>
						<?php } } ?>
						<?php if(get_option('mvp_arch_layout') == '1' ) { ?>
							<div id="mvp-blog-arch-col-wrap" class="left relative">
							<ul class="mvp-blog-story-list-col left relative infinite-content">
								<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
									<?php if (have_posts()) : while (have_posts()) : the_post(); if (in_array($post->ID, $do_not_duplicate)) continue; ?>
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
									<?php endwhile; endif; ?>
								<?php } else { ?>
									<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
									<?php endwhile; endif; ?>
								<?php } ?>
							</ul>
							</div><!--mvp-blog-arch-col-wrap-->
						<?php } else { ?>
							<ul class="mvp-blog-story-list left relative infinite-content">
								<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
									<?php if (have_posts()) : while (have_posts()) : the_post(); if (in_array($post->ID, $do_not_duplicate)) continue; ?>
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
									<?php endwhile; endif; ?>
								<?php } else { ?>
									<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
									<?php endwhile; endif; ?>
								<?php } ?>
							</ul>
						<?php } ?>
						<div class="mvp-inf-more-wrap left relative">
							<?php $mvp_infinite_scroll = get_option('mvp_infinite_scroll'); if ($mvp_infinite_scroll == "true") { if (isset($mvp_infinite_scroll)) { ?>
								<a href="#" class="mvp-inf-more-but"><?php esc_html_e( 'More Posts', 'zox-news' ); ?></a>
							<?php } } ?>
							<div class="mvp-nav-links">
								<?php if (function_exists("pagination")) { pagination($wp_query->max_num_pages); } ?>
							</div><!--mvp-nav-links-->
						</div><!--mvp-inf-more-wrap-->
					</div><!--mvp-main-blog-body-->
				</div><!--mvp-mvp-main-blog-in-->
				<?php get_sidebar(); ?>
			</div><!--mvp-mvp-main-blog-out-->
		</div><!--mvp-main-blog-cont-->
	</div><!--mvp-main-box-->
</div><!--mvp-main-blog-wrap-->
<?php get_footer(); ?>