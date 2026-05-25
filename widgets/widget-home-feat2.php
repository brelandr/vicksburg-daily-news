<?php
/**
 * Plugin Name: Home Featured 2 Widget
 */

add_action( 'widgets_init', 'mvp_home_feat2_load_widgets' );

function mvp_home_feat2_load_widgets() {
	register_widget( 'mvp_home_feat2_widget' );
}

class mvp_home_feat2_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_home_feat2_widget', 'description' => esc_html__('A widget that displays one large featured post, two smaller featured posts, and a sidebar with more posts and an optional 300x250 ad.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_home_feat2_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_home_feat2_widget', esc_html__('Vicksburg Daily News: Home Featured 2 Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		global $post;
		$title = apply_filters('widget_title', $instance['title'] );
		$tagcat = $instance['tagcat'];
		$enterslug = $instance['enterslug'];
		$mainpos = $instance['mainpos'];
		$code = $instance['code'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>

			<div class="mvp-widget-feat2-wrap left relative">
				<div class="mvp-widget-feat2-out left relative">
					<div class="mvp-widget-feat2-in">
						<div class="mvp-widget-feat2-main left relative">
							<?php if ($mainpos == 'middle') { ?>
								<div class="mvp-widget-feat2-left left relative mvp-widget-feat2-left-alt">
							<?php } else { ?>
								<div class="mvp-widget-feat2-left left relative">
							<?php } ?>
								<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
									<?php if ($tagcat == 'tag') { ?>
										<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '1', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
									<?php } else { ?>
										<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '1', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
									<?php } ?>
								<?php } else { ?>
									<?php if ($tagcat == 'tag') { ?>
										<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '1', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
									<?php } else { ?>
										<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '1', 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
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
									<?php } ?>
								<?php } ?>
							</div><!--mvp-widget-feat2-left-->
							<div class="mvp-widget-feat2-right left relative">
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
													<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
												</div><!--mvp-cat-date-wrap-->
												<h2><?php the_title(); ?></h2>
											</div><!--mvp-widget-feat2-right-text-->
										</div><!--mvp-widget-feat2-right-cont-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
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
													<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
												</div><!--mvp-cat-date-wrap-->
												<h2><?php the_title(); ?></h2>
											</div><!--mvp-widget-feat2-right-text-->
										</div><!--mvp-widget-feat2-right-cont-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							</div><!--mvp-widget-feat2-right-->
						</div><!--mvp-widget-feat2-main-->
					</div><!--mvp-widget-feat2-in-->
					<div class="mvp-widget-feat2-side left relative">
						<?php if ($code) { ?>
							<div class="mvp-widget-feat2-side-ad left relative">
								<span class="mvp-ad-label"><?php esc_html_e( 'Advertisement', 'zox-news' ); ?></span>
								<?php echo html_entity_decode($code); ?>
							</div><!--mvp-widget-feat2-side-ad-->
						<?php } ?>
						<div class="mvp-widget-feat2-side-list left relative">
							<div class="mvp-feat1-list left relative">
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; if($code) { $mvp_post_num = 3; } else { $mvp_post_num = 6; }; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => $mvp_post_num, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<div class="mvp-feat1-list-cont left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-feat1-list-out relative">
													<div class="mvp-feat1-list-img left relative">
														<?php the_post_thumbnail('mvp-small-thumb'); ?>
													</div><!--mvp-feat1-list-img-->
													<div class="mvp-feat1-list-in">
														<div class="mvp-feat1-list-text">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2><?php the_title(); ?></h2>
														</div><!--mvp-feat1-list-text-->
													</div><!--mvp-feat1-list-in-->
												</div><!--mvp-feat1-list-out-->
											<?php } else { ?>
												<div class="mvp-feat1-list-text">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2><?php the_title(); ?></h2>
												</div><!--mvp-feat1-list-text-->
											<?php } ?>
										</div><!--mvp-feat1-list-cont-->
										</a>
									<?php endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; if($code) { $mvp_post_num = 3; } else { $mvp_post_num = 6; }; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => $mvp_post_num, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<div class="mvp-feat1-list-cont left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-feat1-list-out relative">
													<div class="mvp-feat1-list-img left relative">
														<?php the_post_thumbnail('mvp-small-thumb'); ?>
													</div><!--mvp-feat1-list-img-->
													<div class="mvp-feat1-list-in">
														<div class="mvp-feat1-list-text">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2><?php the_title(); ?></h2>
														</div><!--mvp-feat1-list-text-->
													</div><!--mvp-feat1-list-in-->
												</div><!--mvp-feat1-list-out-->
											<?php } else { ?>
												<div class="mvp-feat1-list-text">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2><?php the_title(); ?></h2>
												</div><!--mvp-feat1-list-text-->
											<?php } ?>
										</div><!--mvp-feat1-list-cont-->
										</a>
									<?php endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							</div><!--mvp-feat1-list-->
							<?php if ($tagcat == 'tag') { ?>
								<a href="<?php $tag_id = get_term_by('slug', $enterslug, 'post_tag'); echo get_tag_link($tag_id); ?>">
								<div class="mvp-widget-feat2-side-more-but left relative">
									<span class="mvp-widget-feat2-side-more"><?php esc_html_e( 'More', 'zox-news' ); ?> <?php echo esc_html( $title ); ?></span><i class="fa fa-long-arrow-right" aria-hidden="true"></i>
								</div><!--mvp-widget-feat2-side-more-but-->
								</a>
							<?php } else { ?>
								<a href="<?php $cat_id = get_term_by('slug', $enterslug, 'category'); echo get_category_link($cat_id); ?>">
								<div class="mvp-widget-feat2-side-more-but left relative">
									<span class="mvp-widget-feat2-side-more"><?php esc_html_e( 'More', 'zox-news' ); ?> <?php echo esc_html( $title ); ?></span><i class="fa fa-long-arrow-right" aria-hidden="true"></i>
								</div><!--mvp-widget-feat2-side-more-but-->
								</a>
							<?php } ?>
						</div><!--mvp-widget-feat2-side-list-->
					</div><!--mvp-widget-feat2-side-->
				</div><!--mvp-widget-feat2-out-->
			</div><!--mvp-widget-feat2-wrap-->

		<?php

		/* After widget (defined by themes). */
		echo $after_widget;

	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['tagcat'] = strip_tags( $new_instance['tagcat'] );
		$instance['enterslug'] = strip_tags( $new_instance['enterslug'] );
		$instance['mainpos'] = strip_tags( $new_instance['mainpos'] );
		$instance['code'] = $new_instance['code'];

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Title' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
		</p>

		<!-- Cat/Tag -->
		<p>
			<label for="<?php echo $this->get_field_id('tagcat'); ?>">Display Posts By Category Or Tag:</label>
			<select id="<?php echo $this->get_field_id('tagcat'); ?>" name="<?php echo $this->get_field_name('tagcat'); ?>" style="width:100%;">
				<option value='category' <?php if ('category' == $instance['tagcat']) echo 'selected="selected"'; ?>>Category</option>
				<option value='tag' <?php if ('tag' == $instance['tagcat']) echo 'selected="selected"'; ?>>Tag</option>
			</select>
		</p>

		<!-- Enter Cat/Tag -->
		<p>
			<label for="<?php echo $this->get_field_id( 'enterslug' ); ?>">Enter Category/Tag Slug Name:</label>
			<input id="<?php echo $this->get_field_id( 'enterslug' ); ?>" name="<?php echo $this->get_field_name( 'enterslug' ); ?>" value="<?php echo $instance['enterslug']; ?>" style="width:90%;" />
		</p>

		<!-- Main Position -->
		<p>
			<label for="<?php echo $this->get_field_id('mainpos'); ?>">Position of Main Post:</label>
			<select id="<?php echo $this->get_field_id('mainpos'); ?>" name="<?php echo $this->get_field_name('mainpos'); ?>" style="width:100%;">
				<option value='left' <?php if ('left' == $instance['mainpos']) echo 'selected="selected"'; ?>>Left</option>
				<option value='middle' <?php if ('middle' == $instance['mainpos']) echo 'selected="selected"'; ?>>Middle</option>
			</select>
		</p>

		<!-- Ad code -->
		<p>
			<label for="<?php echo $this->get_field_id( 'code' ); ?>">Optional 300x250 Ad Code:</label>
			<textarea id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>" style="width:96%;" rows="6"><?php echo $instance['code']; ?></textarea>
		</p>

	<?php
	}
}

?>