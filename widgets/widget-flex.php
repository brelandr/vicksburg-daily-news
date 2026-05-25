<?php
/**
 * Plugin Name: Flexible Posts Widget
 */

add_action( 'widgets_init', 'mvp_flex_load_widgets' );

function mvp_flex_load_widgets() {
	register_widget( 'mvp_flex_widget' );
}

class mvp_flex_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_flex_widget', 'description' => esc_html__('A widget that displays any number of posts within any widget area.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_flex_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_flex_widget', esc_html__('Vicksburg Daily News: Flexible Posts Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		global $post;
		$title = apply_filters('widget_title', $instance['title'] );
		$rowcol = $instance['rowcol'];
		$number = $instance['number'];
		$tagcat = $instance['tagcat'];
		$enterslug = $instance['enterslug'];
		$code = $instance['code'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		?>

		<div class="mvp-widget-flex-wrap left relative">
			<?php if ($code) { ?>
				<div class="mvp-flex-side-out left relative">
					<div class="mvp-flex-side-in">
						<div class="mvp-flex-story-wrap left relative">
							<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => $number, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => $number, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							<?php } else { ?>
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							<?php } ?>
						</div><!--mvp-flex-story-wrap-->
					</div><!--mvp-flex-side-in-->
					<div class="mvp-flex-side-wrap left relative">
						<div class="mvp-flex-ad left relative">
							<span class="mvp-ad-label"><?php esc_html_e( 'Advertisement', 'zox-news' ); ?></span>
							<?php echo html_entity_decode($code); ?>
						</div><!--mvp-flex-ad-->
					</div><!--mvp-flex-side-wrap-->
				</div><!--mvp-flex-side-out-->
			<?php } else { ?>
						<div class="mvp-flex-story-wrap left relative">
							<?php global $do_not_duplicate; if (isset($do_not_duplicate)) { ?>
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => $number, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col mvp-flex-col-noad">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => $number, 'post__not_in'=>$do_not_duplicate, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col mvp-flex-col-noad">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							<?php } else { ?>
								<?php if ($tagcat == 'tag') { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'tag' => $enterslug, 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col mvp-flex-col-noad">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } else { ?>
									<?php global $do_not_duplicate; global $post; $recent = new WP_Query(array( 'category_name' => $enterslug, 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; if (isset($do_not_duplicate)) { ?>
										<a href="<?php the_permalink(); ?>" rel="bookmark">
										<?php if ($rowcol == 'col') { ?>
											<div class="mvp-flex-story left relative mvp-flex-col mvp-flex-col-noad">
										<?php } else { ?>
											<div class="mvp-flex-story left relative mvp-flex-row">
										<?php } ?>
											<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
												<div class="mvp-flex-story-out right relative">
													<div class="mvp-flex-story-img left relative">
														<?php the_post_thumbnail('mvp-mid-thumb', array( 'class' => 'mvp-reg-img' )); ?>
														<?php the_post_thumbnail('mvp-small-thumb', array( 'class' => 'mvp-mob-img' )); ?>
														<?php if ( has_post_format( 'video' )) { ?>
															<div class="mvp-vid-box-wrap mvp-vid-marg">
																<i class="fa fa-2 fa-play" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } else if ( has_post_format( 'gallery' )) { ?>
															<div class="mvp-vid-box-wrap">
																<i class="fa fa-2 fa-camera" aria-hidden="true"></i>
															</div><!--mvp-vid-box-wrap-->
														<?php } ?>
													</div><!--mvp-flex-story-img--->
													<div class="mvp-flex-story-in">
														<div class="mvp-flex-story-text left relative">
															<div class="mvp-cat-date-wrap left relative">
																<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
															</div><!--mvp-cat-date-wrap-->
															<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
															<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
														</div><!--mvp-flex-story-text--->
													</div><!--mvp-flex-story-in-->
												</div><!--mvp-flex-story-out-->
											<?php } else { ?>
												<div class="mvp-flex-story-text left relative">
													<div class="mvp-cat-date-wrap left relative">
														<span class="mvp-cd-cat left relative"><?php $category = get_the_category(); echo esc_html( $category[0]->cat_name ); ?></span><span class="mvp-cd-date left relative"><?php printf( esc_html__( '%s ago', 'zox-news' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></span>
													</div><!--mvp-cat-date-wrap-->
													<h2 class="mvp-stand-title"><?php the_title(); ?></h2>
													<p><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
												</div><!--mvp-flex-story-text--->
											<?php } ?>
										</div><!--mvp-flex-story-->
										</a>
									<?php } endwhile; wp_reset_postdata(); ?>
								<?php } ?>
							<?php } ?>
						</div><!--mvp-flex-story-wrap-->
			<?php } ?>
		</div><!--mvp-widget-flex-wrap-->

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
		$instance['rowcol'] = strip_tags( $new_instance['rowcol'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['tagcat'] = strip_tags( $new_instance['tagcat'] );
		$instance['enterslug'] = strip_tags( $new_instance['enterslug'] );
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

		<!-- Row/Column -->
		<p>
			<label for="<?php echo $this->get_field_id('rowcol'); ?>">Display Posts In Rows or Columns:</label>
			<select id="<?php echo $this->get_field_id('rowcol'); ?>" name="<?php echo $this->get_field_name('rowcol'); ?>" style="width:100%;">
				<option value='row' <?php if ('row' == $instance['rowcol']) echo 'selected="selected"'; ?>>Rows</option>
				<option value='col' <?php if ('col' == $instance['rowcol']) echo 'selected="selected"'; ?>>Columns</option>
			</select>
		</p>

		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to display:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3" />
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

		<!-- Ad code -->
		<p>
			<label for="<?php echo $this->get_field_id( 'code' ); ?>">Optional 300px Width Ad Code:</label>
			<textarea id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>" style="width:96%;" rows="6"><?php echo $instance['code']; ?></textarea>
		</p>

	<?php
	}
}

?>