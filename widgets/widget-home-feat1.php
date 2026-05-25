<?php
/**
 * Plugin Name: Home Featured 1 Widget
 */

add_action( 'widgets_init', 'mvp_home_feat1_load_widgets' );

function mvp_home_feat1_load_widgets() {
	register_widget( 'mvp_home_feat1_widget' );
}

class mvp_home_feat1_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_home_feat1_widget', 'description' => esc_html__('A widget that allows you to either display 2 large posts in a row, 4 smaller posts in a row, or both at the same time.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_home_feat1_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_home_feat1_widget', esc_html__('Vicksburg Daily News: Home Featured 1 Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		global $post;
		$title = apply_filters('widget_title', $instance['title'] );
		$layout = $instance['layout'];
		$tagcat = $instance['tagcat'];
		$enterslug = $instance['enterslug'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>

			<div class="mvp-widget-feat1-wrap left relative">
				<?php if ($layout == 'both' || $layout == 'large') { ?>
					<div class="mvp-widget-feat1-cont left relative">
						<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
							<?php if ($tagcat == 'tag') { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-top-story left relative">
										<div class="mvp-widget-feat1-top-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-large-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
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
										</div><!--mvp-widget-feat1-top-img-->
										<div class="mvp-widget-feat1-top-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-top-text-->
									</div><!--mvp-widget-feat1-top-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-top-story left relative">
										<div class="mvp-widget-feat1-top-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-large-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
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
										</div><!--mvp-widget-feat1-top-img-->
										<div class="mvp-widget-feat1-top-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-top-text-->
									</div><!--mvp-widget-feat1-top-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } else { ?>
							<?php if ($tagcat == 'tag') { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-top-story left relative">
										<div class="mvp-widget-feat1-top-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-large-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
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
										</div><!--mvp-widget-feat1-top-img-->
										<div class="mvp-widget-feat1-top-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-top-text-->
									</div><!--mvp-widget-feat1-top-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '2', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-top-story left relative">
										<div class="mvp-widget-feat1-top-img left relative">
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<?php the_post_thumbnail('mvp-large-thumb', array( 'class' => 'mvp-reg-img lazy' )); ?>
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
										</div><!--mvp-widget-feat1-top-img-->
										<div class="mvp-widget-feat1-top-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-top-text-->
									</div><!--mvp-widget-feat1-top-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } ?>
					</div><!--mvp-widget-feat1-cont-->
				<?php } ?>
				<?php if ($layout == 'both' || $layout == 'small') { ?>
					<div class="mvp-widget-feat1-cont left relative">
						<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
							<?php if ($tagcat == 'tag') { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-bot-story left relative">
										<div class="mvp-widget-feat1-bot-img left relative">
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
										</div><!--mvp-widget-feat1-bot-img-->
										<div class="mvp-widget-feat1-bot-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
											<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
										</div><!--mvp-widget-feat1-bot-text-->
									</div><!--mvp-widget-feat1-bot-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-bot-story left relative">
										<div class="mvp-widget-feat1-bot-img left relative">
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
										</div><!--mvp-widget-feat1-bot-img-->
										<div class="mvp-widget-feat1-bot-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-bot-text-->
									</div><!--mvp-widget-feat1-bot-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } else { ?>
							<?php if ($tagcat == 'tag') { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-bot-story left relative">
										<div class="mvp-widget-feat1-bot-img left relative">
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
										</div><!--mvp-widget-feat1-bot-img-->
										<div class="mvp-widget-feat1-bot-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
											<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
										</div><!--mvp-widget-feat1-bot-text-->
									</div><!--mvp-widget-feat1-bot-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } else { ?>
								<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => '4', 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
									<a href="<?php the_permalink(); ?>" rel="bookmark">
									<div class="mvp-widget-feat1-bot-story left relative">
										<div class="mvp-widget-feat1-bot-img left relative">
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
										</div><!--mvp-widget-feat1-bot-img-->
										<div class="mvp-widget-feat1-bot-text left relative">
											<div class="mvp-cat-date-wrap left relative">
												<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
											</div><!--mvp-cat-date-wrap-->
											<h2><?php the_title(); ?></h2>
										</div><!--mvp-widget-feat1-bot-text-->
									</div><!--mvp-widget-feat1-bot-story-->
									</a>
								<?php } endwhile; wp_reset_postdata(); ?>
							<?php } ?>
						<?php } ?>
					</div><!--mvp-widget-feat1-cont-->
				<?php } ?>
			</div><!--mvp-widget-feat1-wrap-->

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
		$instance['layout'] = strip_tags( $new_instance['layout'] );
		$instance['tagcat'] = strip_tags( $new_instance['tagcat'] );
		$instance['enterslug'] = strip_tags( $new_instance['enterslug'] );

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

		<!-- Layout -->
		<p>
			<label for="<?php echo $this->get_field_id('layout'); ?>">Display Large, Small, or Both:</label>
			<select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>" style="width:100%;">
				<option value='both' <?php if ('both' == $instance['layout']) echo 'selected="selected"'; ?>>Large and Small</option>
				<option value='large' <?php if ('large' == $instance['layout']) echo 'selected="selected"'; ?>>Large</option>
				<option value='small' <?php if ('small' == $instance['layout']) echo 'selected="selected"'; ?>>Small</option>
			</select>
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

	<?php
	}
}

?>