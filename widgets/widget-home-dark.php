<?php
/**
 * Plugin Name: Home Dark Widget
 */

add_action( 'widgets_init', 'mvp_home_dark_load_widgets' );

function mvp_home_dark_load_widgets() {
	register_widget( 'mvp_home_dark_widget' );
}

class mvp_home_dark_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_home_dark_widget', 'description' => esc_html__('A widget that displays a list of either gallery or video posts with a dark background.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_home_dark_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_home_dark_widget', esc_html__('Vicksburg Daily News: Home Dark Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		global $post;
		$title = apply_filters('widget_title', $instance['title'] );
		$vidgall = $instance['vidgall'];

		?>

	<section class="mvp-widget-home left relative">
		<div class="mvp-widget-dark-wrap left relative">
			<div class="mvp-main-box">
				<div class="mvp-widget-home-head">
					<h4 class="mvp-widget-home-title"><span class="mvp-widget-home-title"><?php echo esc_html($title); ?></span></h4>
				</div><!--mvp-widget-home-head-->
				<div class="mvp-widget-dark-main left relative">
					<div class="mvp-widget-dark-left left relative">
						<?php if ($vidgall == 'videos') { ?>
							<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
								<?php global $post; $video_query = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => '1', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); if ($video_query->have_posts()) : while ($video_query->have_posts()) : $video_query->the_post(); $do_not_duplicate[] = $post->ID; ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-dark-feat left relative">
										<div class="mvp-widget-dark-feat-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-post-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
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
										</div><!--mvp-widget-dark-feat-img-->
										<div class="mvp-widget-dark-feat-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-feat-text-->
									</div><!--mvp-widget-dark-feat-->
									</a>
								<?php endwhile; endif; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $do_not_duplicate; global $post; $video_query2 = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => '1', 'ignore_sticky_posts'=> 1 )); if ($video_query2->have_posts()) : while ($video_query2->have_posts()) : $video_query2->the_post(); $do_not_duplicate[] = $post->ID; ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-dark-feat left relative">
										<div class="mvp-widget-dark-feat-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-post-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
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
										</div><!--mvp-widget-dark-feat-img-->
										<div class="mvp-widget-dark-feat-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-feat-text-->
									</div><!--mvp-widget-dark-feat-->
									</a>
								<?php endwhile; endif; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } else { ?>
							<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
								<?php global $post; $gallery_query = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => '1', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); if ($gallery_query->have_posts()) : while ($gallery_query->have_posts()) : $gallery_query->the_post(); $do_not_duplicate[] = $post->ID; ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-dark-feat left relative">
										<div class="mvp-widget-dark-feat-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-post-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
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
										</div><!--mvp-widget-dark-feat-img-->
										<div class="mvp-widget-dark-feat-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-feat-text-->
									</div><!--mvp-widget-dark-feat-->
									</a>
								<?php endwhile; endif; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $post; $gallery_query2 = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => '1', 'ignore_sticky_posts'=> 1 )); if ($gallery_query2->have_posts()) : while ($gallery_query2->have_posts()) : $gallery_query2->the_post(); $do_not_duplicate[] = $post->ID; ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-dark-feat left relative">
										<div class="mvp-widget-dark-feat-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-post-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
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
										</div><!--mvp-widget-dark-feat-img-->
										<div class="mvp-widget-dark-feat-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-feat-text-->
									</div><!--mvp-widget-dark-feat-->
									</a>
								<?php endwhile; endif; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } ?>
					</div><!--mvp-widget-dark-left-->
					<div class="mvp-widget-dark-right left relative">
						<?php if ($vidgall == 'videos') { ?>
							<?php global $do_not_duplicate; $video_list_query = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); if ($video_list_query->have_posts()) : while ($video_list_query->have_posts()) : $video_list_query->the_post(); ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark">
								<div class="mvp-widget-dark-sub left relative">
									<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
										<div class="mvp-widget-dark-sub-out right relative">
											<div class="mvp-widget-dark-sub-img left relative">
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
												<?php if ( has_post_format( 'video' )) { ?>
													<div class="mvp-vid-box-wrap mvp-vid-box-small mvp-vid-marg-small">
														<i class="fa fa-2 fa-play" aria-hidden="true"></i>
													</div><!--mvp-vid-box-wrap-->
												<?php } else if ( has_post_format( 'gallery' )) { ?>
													<div class="mvp-vid-box-wrap mvp-vid-box-small">
														<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
													</div><!--mvp-vid-box-wrap-->
												<?php } ?>
											</div><!--mvp-widget-dark-sub-img-->
											<div class="mvp-widget-dark-sub-in">
												<div class="mvp-widget-dark-sub-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2><?php the_title(); ?></h2>
												</div><!--mvp-widget-dark-sub-text-->
											</div><!--mvp-widget-dark-sub-in-->
										</div><!--mvp-widget-dark-sub-out-->
									<?php } else { ?>
										<div class="mvp-widget-dark-sub-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-sub-text-->
									<?php } ?>
								</div><!--mvp-widget-dark-sub-->
								</a>
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else { ?>
							<?php global $do_not_duplicate; $gallery_list_query = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); if ($gallery_list_query->have_posts()) : while ($gallery_list_query->have_posts()) : $gallery_list_query->the_post(); ?>
								<a href="<?php the_permalink(); ?>" rel="bookmark">
								<div class="mvp-widget-dark-sub left relative">
									<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
										<div class="mvp-widget-dark-sub-out right relative">
											<div class="mvp-widget-dark-sub-img left relative">
												<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
												<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img lazy' )); ?>
												<?php if ( has_post_format( 'video' )) { ?>
													<div class="mvp-vid-box-wrap mvp-vid-box-small mvp-vid-marg-small">
														<i class="fa fa-2 fa-play" aria-hidden="true"></i>
													</div><!--mvp-vid-box-wrap-->
												<?php } else if ( has_post_format( 'gallery' )) { ?>
													<div class="mvp-vid-box-wrap mvp-vid-box-small">
														<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
													</div><!--mvp-vid-box-wrap-->
												<?php } ?>
											</div><!--mvp-widget-dark-sub-img-->
											<div class="mvp-widget-dark-sub-in">
												<div class="mvp-widget-dark-sub-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2><?php the_title(); ?></h2>
												</div><!--mvp-widget-dark-sub-text-->
											</div><!--mvp-widget-dark-sub-in-->
										</div><!--mvp-widget-dark-sub-out-->
									<?php } else { ?>
										<div class="mvp-widget-dark-sub-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-dark-sub-text-->
									<?php } ?>
								</div><!--mvp-widget-dark-sub-->
								</a>
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } ?>
					</div><!--mvp-widget-dark-right-->
				</div><!--mvp-widget-dark-main-->
			</div><!--mvp-main-box-->
		</div><!--mvp-widget-dark-wrap-->
	</section><!--mvp-widget-home-->

		<?php

	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['vidgall'] = strip_tags( $new_instance['vidgall'] );

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

		<!-- Video/Gallery -->
		<p>
			<label for="<?php echo $this->get_field_id('vidgall'); ?>">Display Videos or Galleries:</label>
			<select id="<?php echo $this->get_field_id('vidgall'); ?>" name="<?php echo $this->get_field_name('vidgall'); ?>" style="width:100%;">
				<option value='videos' <?php if ('videos' == $instance['vidgall']) echo 'selected="selected"'; ?>>Videos</option>
				<option value='galleries' <?php if ('galleries' == $instance['vidgall']) echo 'selected="selected"'; ?>>Galleries</option>
			</select>
		</p>

	<?php
	}
}

?>