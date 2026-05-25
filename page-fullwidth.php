<?php
	/* Template Name: Full Width */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<article id="mvp-article-wrap" <?php post_class(); ?>>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="mvp-article-cont" class="left relative">
			<div class="mvp-main-box">
				<div id="mvp-post-main" class="left relative">
					<header id="mvp-post-head" class="left relative">
						<h1 class="mvp-post-title left entry-title"><?php the_title(); ?></h1>
					</header>
					<div id="mvp-post-content" class="left relative">
						<div id="mvp-content-wrap" class="left relative">
							<div class="mvp-post-soc-out right relative">
								<?php $socialbox = get_option('mvp_social_box'); if ($socialbox == "true") { ?>
									<div class="mvp-post-soc-wrap left relative">
										<ul class="mvp-post-soc-list left relative">
											<a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&amp;t=<?php the_title_attribute(); ?>', 'facebookShare', 'width=626,height=436'); return false;" title="<?php esc_html_e( 'Share on Facebook', 'zox-news' ); ?>">
											<li class="mvp-post-soc-fb">
												<i class="fa fa-2 fa-facebook" aria-hidden="true"></i>
											</li>
											</a>
											<a href="#" onclick="window.open('http://twitter.com/share?text=<?php the_title_attribute(); ?> -&amp;url=<?php the_permalink() ?>', 'twitterShare', 'width=626,height=436'); return false;" title="<?php esc_html_e( 'Tweet This Post', 'zox-news' ); ?>">
											<li class="mvp-post-soc-twit">
												<i class="fa fa-2 fa-twitter" aria-hidden="true"></i>
											</li>
											</a>
											<a href="#" onclick="window.open('http://pinterest.com/pin/create/button/?url=<?php the_permalink();?>&amp;media=<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mvp-post-thumb' ); echo esc_url( is_array( $thumb ) && ! empty( $thumb[0] ) ? $thumb[0] : '' ); ?>&amp;description=<?php the_title_attribute(); ?>', 'pinterestShare', 'width=750,height=350'); return false;" title="<?php esc_html_e( 'Pin This Post', 'zox-news' ); ?>">
											<li class="mvp-post-soc-pin">
												<i class="fa fa-2 fa-pinterest-p" aria-hidden="true"></i>
											</li>
											</a>
											<a href="mailto:?subject=<?php the_title_attribute(); ?>&amp;BODY=<?php esc_html_e( 'I found this article interesting and thought of sharing it with you. Check it out:', 'zox-news' ); ?> <?php the_permalink(); ?>">
											<li class="mvp-post-soc-email">
												<i class="fa fa-2 fa-envelope" aria-hidden="true"></i>
											</li>
											</a>
											<?php if ( comments_open() ) { ?>
												<?php $disqus_id = get_option('mvp_disqus_id'); if ($disqus_id) { if (isset($disqus_id)) {  ?>
													<a href="#disqus_thread">
													<li class="mvp-post-soc-com mvp-com-click">
														<i class="fa fa-2 fa-commenting" aria-hidden="true"></i>
													</li>
													</a>
												<?php } } else { ?>
													<a href="<?php comments_link(); ?>">
													<li class="mvp-post-soc-com mvp-com-click">
														<i class="fa fa-2 fa-commenting" aria-hidden="true"></i>
													</li>
													</a>
												<?php } ?>
											<?php } ?>
										</ul>
									</div><!--mvp-post-soc-wrap-->
								<?php } ?>
								<div class="mvp-post-soc-in">
									<div id="mvp-content-body" class="left-relative">
										<div id="mvp-content-main" class="left relative">
											<?php the_content(); ?>
											<?php wp_link_pages(); ?>
										</div><!--mvp-content-main-->
										<div id="mvp-content-bot" class="left relative">
											<div class="mvp-org-wrap">
												<div class="mvp-org-logo">
													<?php if(get_option('mvp_logo')) { ?>
														<img src="<?php echo zox_news_esc_url_option('mvp_logo'); ?>"/>
													<?php } else { ?>
														<img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" />
													<?php } ?>
												</div><!--mvp-org-logo-->
											</div><!--mvp-org-wrap-->
											<?php if ( comments_open() ) { ?>
												<?php $disqus_id = get_option('mvp_disqus_id'); if ($disqus_id) { if (isset($disqus_id)) {  ?>
													<div id="mvp-comments-button" class="left relative mvp-com-click">
														<span class="mvp-comment-but-text"><?php comments_number(__( 'Comments', 'zox-news'), esc_html__('Comments', 'zox-news'), esc_html__('Comments', 'zox-news')); ?></span>
													</div><!--mvp-comments-button-->
													<?php $disqus_id2 = esc_html($disqus_id); mvp_disqus_embed($disqus_id2); ?>
												<?php } } else { ?>
													<?php $mvp_click_id = get_the_ID(); ?>
													<div id="mvp-comments-button" class="left relative mvp-com-click">
														<span class="mvp-comment-but-text"><?php comments_number(__( 'Click to comment', 'zox-news'), esc_html__('1 Comment', 'zox-news'), esc_html__('% Comments', 'zox-news'));?></span>
													</div><!--mvp-comments-button-->
													<?php comments_template(); ?>
												<?php } ?>
											<?php } ?>
										</div><!--mvp-content-bot-->
									</div><!--mvp-content-body-->
								</div><!--mvp-post-soc-in-->
							</div><!--mvp-post-soc-out-->
						</div><!--mvp-content-wrap-->
					</div><!--mvp-post-content-->
				</div><!--mvp-post-main-->
			</div><!--mvp-main-box-->
		</div><!--mvp-article-cont-->
	<?php endwhile; endif; ?>
</article><!--mvp-article-wrap-->
<?php get_footer(); ?>