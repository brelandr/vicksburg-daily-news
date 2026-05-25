<!DOCTYPE html>
<html ⚡ <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>" >
		<link rel="amphtml" href="<?php echo get_permalink(); ?>amp/" />
    	<meta name="viewport" id="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
		<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { if(get_option('mvp_favicon')) { ?><link rel="shortcut icon" href="<?php echo zox_news_esc_url_option('mvp_favicon'); ?>" /><?php } } ?>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<?php if ( ! vdn_yoast_seo_active() ) : ?>
		<meta property="og:type" content="article" />
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
				<?php global $post; $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mvp-post-thumb' ); ?>
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
		<?php endif; ?>
		<script async custom-element="amp-social-share" src="https://cdn.ampproject.org/v0/amp-social-share-0.1.js"></script>
		<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
		<?php do_action( 'amp_post_template_head', $this ); ?>
		<style amp-custom>
			<?php echo file_get_contents( get_template_directory() . '/css/amp-style.css' ); ?>
			<?php echo file_get_contents( get_template_directory() . '/css/amp-media-queries.css' ); ?>
			<?php do_action( 'amp_post_template_css', $this ); ?>
		</style>
	</head>
	<body <?php body_class(''); ?>>
		<?php get_template_part('amp-fly-menu'); ?>
		<div id="mvp-site" class="left relative">
			<div id="mvp-site-wall" class="left relative">
				<div id="mvp-site-main" class="left relative">
					<header id="mvp-main-head-wrap">
						<nav id="mvp-main-nav-wrap" class="left relative">
							<div id="mvp-main-nav-small" class="left relative">
								<div id="mvp-main-nav-small-cont" class="left">
									<div class="mvp-main-box">
										<div id="mvp-nav-small-wrap">
											<div class="mvp-nav-small-cont">
												<div id="mvp-nav-small-left">
													<div class="mvp-fly-but-wrap left relative ampstart-btn caps m2" on="tap:sidebar.toggle" role="button" tabindex="0">
														<span></span>
														<span></span>
														<span></span>
														<span></span>
													</div><!--mvp-fly-but-wrap-->
												</div><!--mvp-nav-small-left-->
												<div class="mvp-nav-small-mid">
													<div class="mvp-nav-small-logo">
														<?php if(get_option('mvp_logo_nav')) { ?>
															<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><amp-img src="<?php echo zox_news_esc_url_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" width="<?php echo esc_html(get_option('mvp_amp_logo')); ?>" height="30" layout="fixed"></amp-img></a>
														<?php } else { ?>
															<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><amp-img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" width="<?php echo esc_html(get_option('mvp_amp_logo')); ?>" height="30" layout="fixed"></amp-img></a>
														<?php } ?>
														<h2 class="mvp-logo-title"><?php bloginfo( 'name' ); ?></h2>
													</div><!--mvp-nav-small-logo-->
												</div><!--mvp-nav-small-mid-->
											</div><!--mvp-nav-small-cont-->
										</div><!--mvp-nav-small-wrap-->
									</div><!--mvp-main-box-->
								</div><!--mvp-main-nav-small-cont-->
							</div><!--mvp-main-nav-small-->
						</nav><!--mvp-main-nav-wrap-->
						<div id="mvp-soc-mob-wrap" class="left relative">
							<div class="mvp-main-box">
								<div class="mvp-soc-mob-cont">
									<amp-social-share class="rounded" type="email" width="36" height="36"></amp-social-share>
									<?php $mvp_amp_fb = get_option('mvp_amp_fb'); if ($mvp_amp_fb) { ?>
										<amp-social-share class="rounded" type="facebook" width="36" height="36" data-param-app_id="<?php echo esc_html($mvp_amp_fb); ?>"></amp-social-share>
									<?php } ?>
									<amp-social-share class="rounded" type="twitter" width="36" height="36"></amp-social-share>
									<amp-social-share class="rounded" type="pinterest" data-param-media="<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'mvp-post-thumb' ); echo esc_url( is_array( $thumb ) && ! empty( $thumb[0] ) ? $thumb[0] : '' ); ?>" width="36" height="36"></amp-social-share>
									<amp-social-share class="rounded" type="whatsapp" width="36" height="36"></amp-social-share>
								</div><!--mvp-soc-mob-cont-->
							</div><!--mvp-main-box-->
						</div><!--mvp-soc-mob-wrap-->
					</header><!--mvp-main-head-wrap-->
					<div id="mvp-main-body-wrap" class="left relative">
						<?php global $author; $userdata = get_userdata($author); ?>
						<article id="mvp-article-wrap">
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
								<div id="mvp-article-cont" class="left relative">
									<div class="mvp-main-box">
										<div id="mvp-post-main" class="left relative">
											<header id="mvp-post-head" class="left relative">
												<h3 class="mvp-post-cat left relative"><a class="mvp-post-cat-link" href="<?php $category = get_the_category(); $category_id = get_cat_ID( $category[0]->cat_name ); $category_link = get_category_link( $category_id ); echo esc_url( $category_link ); ?>"><span class="mvp-post-cat left"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span></a></h3>
												<h1 class="mvp-post-title left entry-title"><?php the_title(); ?></h1>
												<?php if ( has_excerpt() ) { ?>
													<span class="mvp-post-excerpt left"><?php the_excerpt(); ?></span>
												<?php } ?>
												<?php $author_info = get_option('mvp_author_info'); if ($author_info == "true") { ?>
													<div class="mvp-author-info-wrap left relative">
														<div class="mvp-author-info-thumb left relative">
															<amp-img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'email' ) ) ?: '' ); ?>" width="46" height="46"  layout="responsive"></amp-img>
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
														<?php global $post; $mvp_featured_img = get_option('mvp_featured_img'); $mvp_show_hide = get_post_meta($post->ID, "mvp_featured_image", true); if ($mvp_featured_img == "true") { if ($mvp_show_hide !== "hide") { ?>
																<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
																	<div id="mvp-post-feat-img" class="left relative mvp-post-feat-img-wide2">
																		<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-post-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
																		<amp-img src="<?php echo esc_url($mvp_thumb_url) ?>" width="<?php echo esc_html($mvp_thumb_width) ?>" height="<?php echo esc_html($mvp_thumb_height) ?>" layout="responsive"></amp-img>
																	</div><!--mvp-post-feat-img-->
																	<?php global $post; if(get_post_meta($post->ID, "mvp_photo_credit", true)): ?>
																		<span class="mvp-feat-caption"><?php echo wp_kses_post( get_post_meta( $post->ID, 'mvp_photo_credit', true ) ?? '' ); ?></span>
																	<?php endif; ?>
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
																		<div id="mvp-content-main" class="left relative">
																			<?php echo $this->get( 'post_amp_content' ); ?>
																			<?php wp_link_pages(); ?>
																		</div><!--mvp-content-main-->
																		<div id="mvp-content-bot" class="left">
																			<div class="mvp-post-tags">
																				<span class="mvp-post-tags-header"><?php esc_html_e( 'Related Topics:', 'zox-news' ); ?></span><span><?php the_tags('','','') ?></span>
																		</div><!--mvp-post-tags-->
																		<div class="mvp-org-wrap">
																			<div class="mvp-org-logo">
																				<?php if(get_option('mvp_logo_nav')) { ?>
																					<amp-img src="<?php echo zox_news_esc_url_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" width="<?php echo esc_html(get_option('mvp_amp_logo')); ?>" height="30" layout="fixed"></amp-img>
																				<?php } else { ?>
																					<amp-img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" width="<?php echo esc_html(get_option('mvp_amp_logo')); ?>" height="30" layout="fixed"></amp-img>
																				<?php } ?>
																			</div><!--mvp-org-logo-->
																		</div><!--mvp-org-wrap-->
																	</div><!--mvp-content-bot-->
																</div><!--mvp-content-body-top-->

															</div><!--mvp-content-body-->
												</div><!--mvp-content-wrap-->
													<?php if ( comments_open() ) { ?>
														<?php $disqus_id = get_option('mvp_disqus_id'); if ($disqus_id) { if (isset($disqus_id)) {  ?>
															<a href="<?php the_permalink() ?>">
															<div id="mvp-comments-button" class="left relative">
																<span class="mvp-comment-but-text"><?php comments_number(__( 'Comments', 'zox-news'), esc_html__('Comments', 'zox-news'), esc_html__('Comments', 'zox-news')); ?></span>
															</div><!--mvp-comments-button-->
															</a>
														<?php } } else { ?>
															<a href="<?php the_permalink() ?>">
															<div id="mvp-comments-button" class="left relative">
																<span class="mvp-comment-but-text"><?php comments_number(__( 'Click to comment', 'zox-news'), esc_html__('1 Comment', 'zox-news'), esc_html__('% Comments', 'zox-news'));?></span>
															</div><!--mvp-comments-button-->
															</a>
														<?php } ?>
													<?php } ?>
											</div><!--mvp-post-content-->

								</div><!--mvp-post-main-->
									<?php $mvp_trend_posts = get_option('mvp_trend_posts'); if ($mvp_trend_posts == "true") { ?>
										<div id="mvp-post-more-wrap" class="left relative">
											<h4 class="mvp-widget-home-title">
												<span class="mvp-widget-home-title"><?php echo esc_html(get_option('mvp_pop_head')); ?></span>
											</h4>
											<ul class="mvp-post-more-list left relative">
												<?php global $post; $pop_days = esc_html(get_option('mvp_pop_days')); $popular_days_ago = "$pop_days days ago"; $recent = new WP_Query(array('posts_per_page' => '6', 'ignore_sticky_posts'=> 1, 'post__not_in' => array( $post->ID ), 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'post_views_count', 'date_query' => array( array( 'after' => $popular_days_ago )) )); while($recent->have_posts()) : $recent->the_post(); ?>
							
													<li>
														<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
															<div class="mvp-post-more-img left relative">
																<a href="<?php the_permalink(); ?>" rel="bookmark">
																<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-mid-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
																<amp-img class="mvp-reg-img" src="<?php echo esc_url($mvp_thumb_url) ?>" width="<?php echo esc_html($mvp_thumb_width) ?>" height="<?php echo esc_html($mvp_thumb_height) ?>" layout="responsive"></amp-img>
																<?php $thumb_id = get_post_thumbnail_id(); $mvp_thumb_array = wp_get_attachment_image_src($thumb_id, 'mvp-small-thumb', true); $mvp_thumb_url = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[0] ) ) ? $mvp_thumb_array[0] : ''; $mvp_thumb_width = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[1] ) ) ? $mvp_thumb_array[1] : ''; $mvp_thumb_height = ( is_array( $mvp_thumb_array ) && isset( $mvp_thumb_array[2] ) ) ? $mvp_thumb_array[2] : ''; ?>
																<amp-img class="mvp-mob-img" src="<?php echo esc_url($mvp_thumb_url) ?>" width="<?php echo esc_html($mvp_thumb_width) ?>" height="<?php echo esc_html($mvp_thumb_height) ?>" layout="responsive"></amp-img>
																</a>
															</div><!--mvp-post-more-img-->
														<?php } ?>
														<div class="mvp-post-more-text left relative">
															<a href="<?php the_permalink(); ?>" rel="bookmark">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<p><?php the_title(); ?></p>
															</a>
														</div><!--mvp-post-more-text-->
													</li>
												<?php endwhile; wp_reset_postdata(); ?>
											</ul>
										</div><!--mvp-post-more-wrap-->
									<?php } ?>
								</div><!--mvp-main-box-->
							</div><!--mvp-article-cont-->
							<?php if ( function_exists( 'setCrunchifyPostViews' ) ) { setCrunchifyPostViews( get_the_ID() ); } ?>
						<?php endwhile; endif; ?>
					</article><!--mvp-article-wrap-->	
				</div><!--mvp-main-body-wrap-->
				<footer id="mvp-foot-wrap" class="left relative">
					<div id="mvp-foot-bot" class="left relative">
						<div class="mvp-main-box">
							<div id="mvp-foot-copy" class="left relative">
								<p><?php echo wp_kses_post( get_option('mvp_copyright') ?? '' ); ?></p>
							</div><!--mvp-foot-copy-->
						</div><!--mvp-main-box-->
					</div><!--mvp-foot-bot-->
				</footer>
			</div><!--mvp-site-main-->
		</div><!--mvp-site-wall-->
	</div><!--mvp-site-->
	<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>