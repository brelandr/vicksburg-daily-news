<?php
	/* Template Name: Authors List */
?>
<?php global $author; $userdata = get_userdata($author); ?>
<?php get_header(); ?>
<div id="mvp-article-wrap">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="mvp-article-cont" class="left relative">
			<div class="mvp-main-box">
				<div id="mvp-post-main" class="left relative">
					<div class="mvp-post-main-out left relative">
						<div class="mvp-post-main-in">
							<div id="mvp-post-content" class="left relative">
									<div class="mvp-widget-home-head">
										<h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title"><?php the_title(); ?></span></h4>
									</div><!--mvp-widget-home-head-->
								<div class="mvp-authors-wrap left relative">
									<?php $mvp_users = get_users('orderby=post_count&order=DESC'); foreach($mvp_users as $user) { $post_count = count_user_posts( $user->ID ); if($post_count < 1) continue; ?>
										<section class="mvp-authors-list-wrap left relative">
											<div class="mvp-authors-list-img left relative">
												<?php echo get_avatar( $user->user_email, '150' ); ?>
											</div><!--mvp-authors-list-img-->
											<span class="mvp-authors-name"><a href="<?php echo get_author_posts_url( $user->ID ); ?>"><?php echo esc_html( $user->display_name ); ?></a></span>
											<?php wp_get_current_user(); $author_query = array('posts_per_page' => '1','author' => $user->ID); $author_posts = new WP_Query($author_query); while($author_posts->have_posts()) : $author_posts->the_post(); ?>
												<h2 class="mvp-authors-latest"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
											<?php endwhile; wp_reset_postdata(); ?>
										</section><!--mvp-authors-page-list-wrap-->
									<?php } ?>
								</div><!--mvp-authors-wrap-->
							</div><!--mvp-post-content-->
						</div><!--mvp-post-main-in-->
						<?php get_sidebar(); ?>
					</div><!--mvp-post-main-out-->
				</div><!--mvp-post-main-->
			</div><!--mvp-main-box-->
		</div><!--mvp-article-cont-->
	<?php endwhile; endif; ?>
</div><!--mvp-article-wrap-->
<?php get_footer(); ?>