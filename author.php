<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<?php global $author; $userdata = get_userdata($author); ?>
<div class="mvp-main-blog-wrap left relative">
	<div class="mvp-main-box">
		<div class="mvp-main-blog-cont left relative">
			<div id="mvp-author-page-top" class="left relative">
				<div class="mvp-author-top-out right relative">
					<div id="mvp-author-top-left" class="left relative">
						<?php echo get_avatar( $userdata->user_email, '200' ); ?>
					</div><!--mvp-author-top-left-->
					<div class="mvp-author-top-in">
						<div id="mvp-author-top-right" class="left relative">
							<h1 class="mvp-author-top-head left"><?php echo esc_html( $userdata->display_name ); ?></h1>
							<span class="mvp-author-page-desc left relative"><?php echo wp_kses_post( $userdata->description ?? '' ); ?></span>
							<ul class="mvp-author-page-list left relative">
								<?php $mvp_email = get_option('mvp_author_email'); if ($mvp_email == "true") { ?>
									<a href="mailto:<?php echo esc_html($userdata->user_email); ?>"><li class="fa fa-envelope-o fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->facebook; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->facebook); ?>" alt="Facebook" target="_blank"><li class="fa fa-facebook fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->twitter; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->twitter); ?>" alt="Twitter" target="_blank"><li class="fa fa-twitter fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->pinterest; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->pinterest); ?>" alt="Pinterest" target="_blank"><li class="fa fa-pinterest-p fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->instagram; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->instagram); ?>" alt="Instagram" target="_blank"><li class="fa fa-instagram fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->googleplus; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->googleplus); ?>" alt="Google Plus" target="_blank"><li class="fa fa-google-plus fa-2"></li></a>
								<?php } ?>
								<?php $authordesc = $userdata->linkedin; if ( ! empty ( $authordesc ) ) { ?>
									<a href="<?php echo esc_url( $userdata->linkedin); ?>" alt="LinkedIn" target="_blank"><li class="fa fa-linkedin fa-2"></li></a>
								<?php } ?>
							</ul>
						</div><!--mvp-author-top-right-->
					</div><!--mvp-author-top-in-->
				</div><!--mvp-author-top-out-->
			</div><!--mvp-author-page-top-->
			<div class="mvp-main-blog-out left relative">
				<div class="mvp-main-blog-in">
					<div class="mvp-main-blog-body left relative">
						<div class="mvp-widget-home-head">
							<h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title"><?php esc_html_e( 'Stories By', 'zox-news' ); ?> <?php echo esc_html( $userdata->display_name ); ?></span></h4>
						</div><!--mvp-widget-home-head-->
						<?php if(get_option('mvp_arch_layout') == '1' ) { ?>
							<ul class="mvp-blog-story-list-col left relative infinite-content">
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
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													<?php } ?>
												</div><!--mvp-cat-date-wrap-->
												<h2><?php the_title(); ?></h2>
												<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
											</div><!--mvp-blog-story-text-->
										<?php } ?>
										</a>
									</li><!--mvp-blog-story-wrap-->
								<?php endwhile; endif; ?>
							</ul>
						<?php } else { ?>
							<ul class="mvp-blog-story-list left relative infinite-content">
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
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													<?php } ?>
												</div><!--mvp-cat-date-wrap-->
												<h2><?php the_title(); ?></h2>
												<p><?php echo wp_trim_words( get_the_excerpt(), 26, '...' ); ?></p>
											</div><!--mvp-blog-story-text-->
										<?php } ?>
										</a>
									</li><!--mvp-blog-story-wrap-->
								<?php endwhile; endif; ?>
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