<div class="mvp-main-box">
<div class="mvp-auto-post-grid">
	<div class="mvp-alp-side">
		<div class="mvp-alp-side-in">
			<?php get_template_part( 'alp/alp', 'side' ); ?>
		</div><!--mvp-alp-side-in-->
	</div><!--mvp-alp-side-->
	<div class="mvp-auto-post-main">
<article id="post-<?php echo get_the_ID(); ?>" class="mvp-article-wrap">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php global $author; $userdata = get_userdata($author); ?>
		<div id="mvp-article-cont" class="left relative">
				<div id="mvp-post-main" class="left relative">
					<?php if ( function_exists( 'the_ad_group' ) ) { the_ad_group(597); } ?>
					<header id="mvp-post-head" class="left relative">
						<h3 class="mvp-post-cat left relative"><a class="mvp-post-cat-link" href="<?php $category = get_the_category(); $category_id = get_cat_ID( $category[0]->cat_name ); $category_link = get_category_link( $category_id ); echo esc_url( $category_link ); ?>"><span class="mvp-post-cat left"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span></a></h3>
						<h1 class="mvp-post-title left entry-title"><?php the_title(); ?></h1>
						<?php if ( has_excerpt() ) { ?>
							<span class="mvp-post-excerpt left"><?php the_excerpt(); ?></span>
						<?php } ?>
						<?php $author_info = get_option('mvp_author_info'); if ($author_info == "true") { ?>
							<div class="mvp-author-info-wrap left relative">
								<div class="mvp-author-info-thumb left relative">
									<?php echo get_avatar( get_the_author_meta('email'), '46' ); ?>
								</div><!--mvp-author-info-thumb-->
								<div class="mvp-author-info-text left relative">
									<div class="mvp-author-info-date left relative">
										<p><?php esc_html_e( 'Published', 'zox-news' ); ?></p> <span class="mvp-post-date"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span> <p><?php esc_html_e( 'on', 'zox-news' ); ?></p> <span class="mvp-post-date updated"><time class="post-date updated" datetime="<?php the_time('Y-m-d'); ?>"><?php the_time(get_option('date_format')); ?></time></span>
									</div><!--mvp-author-info-date-->
									<div class="mvp-author-info-name left relative">
										<p><?php esc_html_e( 'By', 'zox-news' ); ?></p> <span class="author-name vcard fn author"><?php the_author_posts_link(); ?></span> <?php $authortwit = get_the_author_meta('twitter'); if ( ! empty ( $authortwit ) ) { ?><a href="<?php echo esc_url( $authortwit ); ?>" class="mvp-twit-but" target="_blank"><span class="mvp-author-info-twit-but"><i class="fa fa-twitter fa-2"></i></span></a><?php } ?>
									</div><!--mvp-author-info-name-->
								</div><!--mvp-author-info-text-->
							</div><!--mvp-author-info-wrap-->
						<?php } ?>
					</header>
							<div id="mvp-post-content" class="left relative">
								<?php $mvp_featured_img = get_option('mvp_featured_img'); $mvp_show_hide = get_post_meta($post->ID, "mvp_featured_image", true); if ($mvp_featured_img == "true") { if ($mvp_show_hide !== "hide") { ?>
									<?php if(get_post_meta($post->ID, "mvp_video_embed", true)) { ?>
										<div id="mvp-video-embed-wrap" class="left relative">
											<div id="mvp-video-embed-cont" class="left relative">
												<span class="mvp-video-close fa fa-times" aria-hidden="true"></span>
												<div id="mvp-video-embed" class="left relative">
													<?php echo html_entity_decode( (string) get_post_meta($post->ID, "mvp_video_embed", true), ENT_QUOTES | ENT_HTML5, 'UTF-8' ); ?>
												</div><!--mvp-video-embed-->
											</div><!--mvp-video-embed-cont-->
										</div><!--mvp-video-embed-wrap-->
										<div class="mvp-post-img-hide">
											<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-post-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
										</div><!--mvp-post-img-hide-->
									<?php } else { ?>
										<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
											<div id="mvp-post-feat-img" class="left relative mvp-post-feat-img-wide2">
												<?php the_post_thumbnail(''); ?>
												<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-post-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
											</div><!--mvp-post-feat-img-->
											<?php global $post; if(get_post_meta($post->ID, "mvp_photo_credit", true)): ?>
												<span class="mvp-feat-caption"><?php echo wp_kses_post( get_post_meta( $post->ID, 'mvp_photo_credit', true ) ?? '' ); ?></span>
											<?php endif; ?>
										<?php } ?>
									<?php } ?>
								<?php } else { ?>
									<div class="mvp-post-img-hide">
										<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-post-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
									</div><!--mvp-post-img-hide-->
								<?php } ?>
								<?php } else { ?>
									<div class="mvp-post-img-hide">
										<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-post-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
									</div><!--mvp-post-img-hide-->
								<?php } ?>
								<div id="mvp-content-wrap" class="left relative">
											<div id="mvp-content-body" class="left relative">
												<div id="mvp-content-body-top" class="left relative">
													<?php global $post; $alp = get_option('mvp_alp'); if ($alp == "true") { ?>
														<?php $socialbox = get_option('mvp_social_box'); if ($socialbox == "true") { ?>
															<?php if ( function_exists( 'mvp_SocialALP' ) ) { ?>
																<?php mvp_SocialALP(); ?>
															<?php } ?>
														<?php } ?>
													<?php } ?>
													<div id="mvp-content-main" class="left relative">
														<?php the_content(); ?>
														<?php wp_link_pages(); ?>
													</div><!--mvp-content-main-->
													<?php global $post; $alp = get_option('mvp_alp'); if ($alp == "true") { ?>
														<?php $socialbox = get_option('mvp_social_box'); if ($socialbox == "true") { ?>
															<?php if ( function_exists( 'mvp_SocialALP' ) ) { ?>
																<?php mvp_SocialALP(); ?>
															<?php } ?>
														<?php } ?>
													<?php } ?>
													<div id="mvp-content-bot" class="left">
														<div class="mvp-post-tags">
															<span class="mvp-post-tags-header"><?php esc_html_e( 'Related Topics:', 'zox-news' ); ?></span><span><?php the_tags('','','') ?></span>
														</div><!--mvp-post-tags-->
														<div class="posts-nav-link">
															<?php posts_nav_link(); ?>
														</div><!--posts-nav-link-->
														<?php $mvp_prev_next = get_option('mvp_prev_next'); if ($mvp_prev_next == "true") { ?>
															<div id="mvp-prev-next-wrap" class="left relative">
																<?php $nextPost = get_next_post(TRUE, ''); if($nextPost) { $args = array( 'posts_per_page' => 1, 'include' => $nextPost->ID ); $nextPost = get_posts($args); foreach ($nextPost as $post) { setup_postdata($post); ?>
																	<div class="mvp-next-post-wrap right relative">
																		<a href="<?php the_permalink(); ?>" rel="bookmark">
																		<div class="mvp-prev-next-cont left relative">
																			<div class="mvp-next-cont-out left relative">
																				<div class="mvp-next-cont-in">
																					<div class="mvp-prev-next-text left relative">
																						<span class="mvp-prev-next-label left relative"><?php esc_html_e( "Up Next", 'zox-news' ); ?></span>
																						<p><?php the_title(); ?></p>
																					</div><!--mvp-prev-next-text-->
																				</div><!--mvp-next-cont-in-->
																				<span class="mvp-next-arr fa fa-chevron-right right"></span>
																			</div><!--mvp-prev-next-out-->
																		</div><!--mvp-prev-next-cont-->
																		</a>
																	</div><!--mvp-next-post-wrap-->
																<?php wp_reset_postdata(); } } ?>
																<?php $prevPost = get_previous_post(TRUE, ''); if($prevPost) { $args = array( 'posts_per_page' => 1, 'include' => $prevPost->ID ); $prevPost = get_posts($args); foreach ($prevPost as $post) { setup_postdata($post); ?>
																	<div class="mvp-prev-post-wrap left relative">
																		<a href="<?php the_permalink(); ?>" rel="bookmark">
																		<div class="mvp-prev-next-cont left relative">
																			<div class="mvp-prev-cont-out right relative">
																				<span class="mvp-prev-arr fa fa-chevron-left left"></span>
																				<div class="mvp-prev-cont-in">
																					<div class="mvp-prev-next-text left relative">
																						<span class="mvp-prev-next-label left relative"><?php esc_html_e( "Don't Miss", 'zox-news' ); ?></span>
																						<p><?php the_title(); ?></p>
																					</div><!--mvp-prev-next-text-->
																				</div><!--mvp-prev-cont-in-->
																			</div><!--mvp-prev-cont-out-->
																		</div><!--mvp-prev-next-cont-->
																		</a>
																	</div><!--mvp-prev-post-wrap-->
																<?php wp_reset_postdata(); } } ?>
															</div><!--mvp-prev-next-wrap-->
														<?php } ?>
														<?php $author = get_option('mvp_author_box'); if ($author == "true") { ?>
															<div id="mvp-author-box-wrap" class="left relative">
																<div class="mvp-author-box-out right relative">
																	<div id="mvp-author-box-img" class="left relative">
																		<?php echo get_avatar( get_the_author_meta('email'), '60' ); ?>
																	</div><!--mvp-author-box-img-->
																	<div class="mvp-author-box-in">
																		<div id="mvp-author-box-head" class="left relative">
																			<span class="mvp-author-box-name left relative"><?php the_author_posts_link(); ?></span>
																			<div id="mvp-author-box-soc-wrap" class="left relative">
																				<?php $mvp_email = get_option('mvp_author_email'); if ($mvp_email == "true") { ?>
																					<a href="mailto:<?php the_author_meta('email'); ?>"><span class="mvp-author-box-soc fa fa-envelope-square fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'facebook' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php the_author_meta('facebook'); ?>" alt="Facebook" target="_blank"><span class="mvp-author-box-soc fa fa-facebook-square fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'twitter' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php echo esc_url( get_the_author_meta( 'twitter' ) ); ?>" alt="Twitter" target="_blank"><span class="mvp-author-box-soc fa fa-twitter-square fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'pinterest' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php the_author_meta('pinterest'); ?>" alt="Pinterest" target="_blank"><span class="mvp-author-box-soc fa fa-pinterest-square fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'googleplus' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php the_author_meta('googleplus'); ?>" alt="Google Plus" target="_blank"><span class="mvp-author-box-soc fa fa-google-plus-square fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'instagram' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php the_author_meta('instagram'); ?>" alt="Instagram" target="_blank"><span class="mvp-author-box-soc fa fa-instagram fa-2"></span></a>
																				<?php } ?>
																				<?php $authordesc = get_the_author_meta( 'linkedin' ); if ( ! empty ( $authordesc ) ) { ?>
																					<a href="<?php the_author_meta('linkedin'); ?>" alt="LinkedIn" target="_blank"><span class="mvp-author-box-soc fa fa-linkedin-square fa-2"></span></a>
																				<?php } ?>
																			</div><!--mvp-author-box-soc-wrap-->
																		</div><!--mvp-author-box-head-->
																	</div><!--mvp-author-box-in-->
																</div><!--mvp-author-box-out-->
																<div id="mvp-author-box-text" class="left relative">
																	<p><?php the_author_meta('description'); ?></p>
																</div><!--mvp-author-box-text-->
															</div><!--mvp-author-box-wrap-->
														<?php } ?>
														<div class="mvp-org-wrap">
															<div class="mvp-org-logo">
																<?php if(get_option('mvp_logo')) { ?>
																	<img src="<?php echo zox_news_esc_url_option('mvp_logo'); ?>"/>
																<?php } else { ?>
																	<img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" />
																<?php } ?>
															</div><!--mvp-org-logo-->
														</div><!--mvp-org-wrap-->
													</div><!--mvp-content-bot-->
												</div><!--mvp-content-body-top-->
												<div class="mvp-cont-read-wrap">
													<?php $mvp_cont_read = get_option('mvp_cont_read'); if ($mvp_cont_read == "true") { ?>
														<div class="mvp-cont-read-but-wrap left relative">
															<span class="mvp-cont-read-but"><?php esc_html_e( 'Continue Reading', 'zox-news' ); ?></span>
														</div><!--mvp-cont-read-but-wrap-->
													<?php } ?>
													<?php $mvp_post_ad = get_option('mvp_post_ad'); if ($mvp_post_ad) { ?>
														<div id="mvp-post-bot-ad" class="left relative">
															<span class="mvp-ad-label"><?php esc_html_e( 'Advertisement', 'zox-news' ); ?></span>
															<?php $mvp_post_ad = get_option('mvp_post_ad'); if ($mvp_post_ad) { echo html_entity_decode($mvp_post_ad); } ?>
														</div><!--mvp-post-bot-ad-->
													<?php } ?>
													<?php $mvp_related = get_option('mvp_related_posts'); if ($mvp_related == "true") { ?>
														<div id="mvp-related-posts" class="left relative">
															<h4 class="mvp-widget-home-title">
																<span class="mvp-widget-home-title"><?php esc_html_e( 'You may like', 'zox-news' ); ?></span>
															</h4>
															<?php if ( function_exists( 'mvp_RelatedPosts' ) ) { mvp_RelatedPosts(); } ?>
														</div><!--mvp-related-posts-->
													<?php } ?>
													<?php if ( comments_open() ) { ?>
														<?php $disqus_id = get_option('mvp_disqus_id'); if ($disqus_id) { if (isset($disqus_id)) {  ?>
															<?php $mvp_click_id = get_the_ID(); ?>
															<div id="mvp-comments-button" class="mvp-disqus-comm-first left relative mvp-com-click-<?php echo esc_html($mvp_click_id); ?> mvp-com-but-<?php echo esc_html($mvp_click_id); ?>">
																<span class="mvp-comment-but-text"><?php comments_number(__( 'Comments', 'zox-news'), esc_html__('Comments', 'zox-news'), esc_html__('Comments', 'zox-news')); ?></span>
															</div><!--mvp-comments-button-->
															<?php $disqus_id2 = esc_html($disqus_id); mvp_disqus_embed($disqus_id2); ?>
															<div class="mvp-disqus-comm-wrap">
																<a href="<?php the_permalink(); ?>#mvp-comments-button" target="_blank">
																	<div id="mvp-comments-button" class="left relative">
																		<span class="mvp-comment-but-text"><i class="fas fa-comment"></i> <?php comments_number(__( 'Comments', 'jawn'), esc_html__('Comments', 'jawn'), esc_html__('Comments', 'jawn')); ?></span>
																	</div><!--mvp-comments-button-->
																</a>
															</div><!--mvp-diqus-comm-wrap-->
														<?php } } else { ?>
															<?php $mvp_click_id = get_the_ID(); ?>
															<div id="mvp-comments-button" class="left relative mvp-com-click-<?php echo esc_html($mvp_click_id); ?> mvp-com-but-<?php echo esc_html($mvp_click_id); ?>">
																<span class="mvp-comment-but-text"><?php comments_number(__( 'Click to comment', 'zox-news'), esc_html__('1 Comment', 'zox-news'), esc_html__('% Comments', 'zox-news'));?></span>
															</div><!--mvp-comments-button-->
															<?php comments_template(); ?>
														<?php } ?>
													<?php } ?>
												</div><!--mvp-cont-read-wrap-->
											</div><!--mvp-content-body-->
								</div><!--mvp-content-wrap-->
							</div><!--mvp-post-content-->
				</div><!--mvp-post-main-->
		</div><!--mvp-article-cont-->
		<?php if ( function_exists( 'setCrunchifyPostViews' ) ) { setCrunchifyPostViews( get_the_ID() ); } ?>
		<?php $disqus_id3 = get_option( 'mvp_disqus_id' ); if ( function_exists( 'mvpClickCommmentButton' ) ) { mvpClickCommmentButton( $disqus_id3 ); } ?>
	<?php endwhile; endif; ?>
</article><!--mvp-article-wrap-->
	</div><!--mvp-auto-post-main-->
	<?php $alp_side = get_option('mvp_alp_side'); if ($alp_side == "1") { ?>
		<?php get_sidebar(); ?>
	<?php } ?>
</div><!--mvp-auto-post-grid-->
</div><!--mvp-main-box-->