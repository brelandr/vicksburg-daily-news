<?php
/**
 * Plugin Name: Side Tabber Widget
 */

add_action( 'widgets_init', 'mvp_tabber_load_widgets' );

function mvp_tabber_load_widgets() {
	register_widget( 'mvp_tabber_widget' );
}

class mvp_tabber_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_tabber_widget', 'description' => esc_html__('A widget that displays your latest posts, popular posts, and video posts in a tabber widget.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_tabber_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_tabber_widget', esc_html__('Vicksburg Daily News: Side Tabber Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		global $post;
		$first_title = $instance['first_title'];
		$first = $instance['first'];
		$second_title = $instance['second_title'];
		$second = $instance['second'];
		$third_title = $instance['third_title'];
		$third = $instance['third'];
		$popular_days = $instance['popular_days'];
		$number = $instance['number'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		?>

			<div class="mvp-widget-tab-wrap left relative">
				<div class="mvp-feat1-list-wrap left relative">
					<div class="mvp-feat1-list-head-wrap left relative">
						<ul class="mvp-feat1-list-buts left relative">
							<li class="mvp-feat-col-tab"><a href="#mvp-tab-col1"><span class="mvp-feat1-list-but"><?php echo esc_html( $first_title ); ?></span></a></li>
							<?php if ($second_title && $second !== 'none') { ?>
								<li><a href="#mvp-tab-col2"><span class="mvp-feat1-list-but"><?php echo esc_html( $second_title ); ?></span></a></li>
							<?php } ?>
							<?php if ($third_title && $third !== 'none') { ?>
								<li><a href="#mvp-tab-col3"><span class="mvp-feat1-list-but"><?php echo esc_html( $third_title ); ?></span></a></li>
							<?php } ?>
						</ul>
					</div><!--mvp-feat1-list-head-wrap-->
					<div id="mvp-tab-col1" class="mvp-feat1-list left relative mvp-tab-col-cont">
						<?php if ($first == 'latest') { ?>
							<?php $tab1_latest = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab1_latest->have_posts()) : while ($tab1_latest->have_posts()) : $tab1_latest->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($first == 'popular') { ?>
							<?php $popular_days_ago = "$popular_days days ago"; $recent = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1, 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'post_views_count', 'date_query' => array( array( 'after' => $popular_days_ago )) )); while($recent->have_posts()) : $recent->the_post(); ?>
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
						<?php } else if ($first == 'video') { ?>
							<?php $tab1_video = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab1_video->have_posts()) : while ($tab1_video->have_posts()) : $tab1_video->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($first == 'gallery') { ?>
							<?php $tab1_gallery = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab1_gallery->have_posts()) : while ($tab1_gallery->have_posts()) : $tab1_gallery->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } ?>
					</div><!--mvp-tab-col1-->
					<?php if ($second !== 'none') { ?>
					<div id="mvp-tab-col2" class="mvp-feat1-list left relative mvp-tab-col-cont">
						<?php if ($second == 'latest') { ?>
							<?php $tab2_latest = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab2_latest->have_posts()) : while ($tab2_latest->have_posts()) : $tab2_latest->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($second == 'popular') { ?>
							<?php $popular_days_ago = "$popular_days days ago"; $recent = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1, 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'post_views_count', 'date_query' => array( array( 'after' => $popular_days_ago )) )); while($recent->have_posts()) : $recent->the_post(); ?>
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
						<?php } else if ($second == 'video') { ?>
							<?php $tab2_video = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab2_video->have_posts()) : while ($tab2_video->have_posts()) : $tab2_video->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($second == 'gallery') { ?>
							<?php $tab2_gallery = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab2_gallery->have_posts()) : while ($tab2_gallery->have_posts()) : $tab2_gallery->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } ?>
					</div><!--mvp-tab-col2-->
					<?php } ?>
					<?php if ($third !== 'none') { ?>
					<div id="mvp-tab-col3" class="mvp-feat1-list left relative mvp-tab-col-cont">
						<?php if ($third == 'latest') { ?>
							<?php $tab3_latest = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab3_latest->have_posts()) : while ($tab3_latest->have_posts()) : $tab3_latest->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($third == 'popular') { ?>
							<?php $popular_days_ago = "$popular_days days ago"; $recent = new WP_Query(array( 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1, 'orderby' => 'meta_value_num', 'order' => 'DESC', 'meta_key' => 'post_views_count', 'date_query' => array( array( 'after' => $popular_days_ago )) )); while($recent->have_posts()) : $recent->the_post(); ?>
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
						<?php } else if ($third == 'video') { ?>
							<?php $tab3_video = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-video' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab3_video->have_posts()) : while ($tab3_video->have_posts()) : $tab3_video->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } else if ($third == 'gallery') { ?>
							<?php $tab3_gallery = new WP_Query(array( 'tax_query' => array( array( 'taxonomy' => 'post_format', 'field' => 'slug', 'terms' => 'post-format-gallery' )), 'posts_per_page' => $number, 'ignore_sticky_posts'=> 1 )); if ($tab3_gallery->have_posts()) : while ($tab3_gallery->have_posts()) : $tab3_gallery->the_post(); ?>
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
							<?php endwhile; endif; wp_reset_postdata(); ?>
						<?php } ?>
					</div><!--mvp-tab-col3-->
					<?php } ?>
				</div><!--mvp-feat1-list-wrap-->
			</div><!--mvp-widget-tab-wrap-->

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
		$instance['first_title'] = strip_tags( $new_instance['first_title'] );
		$instance['first'] = strip_tags( $new_instance['first'] );
		$instance['second_title'] = strip_tags( $new_instance['second_title'] );
		$instance['second'] = strip_tags( $new_instance['second'] );
		$instance['third_title'] = strip_tags( $new_instance['third_title'] );
		$instance['third'] = strip_tags( $new_instance['third'] );
		$instance['popular_days'] = strip_tags( $new_instance['popular_days'] );
		$instance['number'] = strip_tags( $new_instance['number'] );

		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'number' => 5, 'popular_days' => 30, 'first' => 'latest', 'second' => 'popular', 'third' => 'video', 'first_title' => 'Latest', 'second_title' => 'Popular', 'third_title' => 'Videos' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- First Title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'first_title' ); ?>">First Column Title:</label>
			<input id="<?php echo $this->get_field_id( 'first_title' ); ?>" name="<?php echo $this->get_field_name( 'first_title' ); ?>" value="<?php echo $instance['first_title']; ?>" style="width:90%;" />
		</p>

		<!-- First Column -->
		<p>
			<label for="<?php echo $this->get_field_id('first'); ?>">Display In First Column:</label>
			<select id="<?php echo $this->get_field_id('first'); ?>" name="<?php echo $this->get_field_name('first'); ?>" style="width:100%;">
				<option value='latest' <?php if ('latest' == $instance['first']) echo 'selected="selected"'; ?>>Latest</option>
				<option value='popular' <?php if ('popular' == $instance['first']) echo 'selected="selected"'; ?>>Popular</option>
				<option value='video' <?php if ('video' == $instance['first']) echo 'selected="selected"'; ?>>Videos</option>
				<option value='gallery' <?php if ('gallery' == $instance['first']) echo 'selected="selected"'; ?>>Galleries</option>
			</select>
		</p>

		<!-- Second Title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'second_title' ); ?>">Second Column Title:</label>
			<input id="<?php echo $this->get_field_id( 'second_title' ); ?>" name="<?php echo $this->get_field_name( 'second_title' ); ?>" value="<?php echo $instance['second_title']; ?>" style="width:90%;" />
		</p>

		<!-- Second Column -->
		<p>
			<label for="<?php echo $this->get_field_id('second'); ?>">Display In Second Column:</label>
			<select id="<?php echo $this->get_field_id('second'); ?>" name="<?php echo $this->get_field_name('second'); ?>" style="width:100%;">
				<option value='latest' <?php if ('latest' == $instance['second']) echo 'selected="selected"'; ?>>Latest</option>
				<option value='popular' <?php if ('popular' == $instance['second']) echo 'selected="selected"'; ?>>Popular</option>
				<option value='video' <?php if ('video' == $instance['second']) echo 'selected="selected"'; ?>>Videos</option>
				<option value='gallery' <?php if ('gallery' == $instance['second']) echo 'selected="selected"'; ?>>Galleries</option>
				<option value='none' <?php if ('none' == $instance['second']) echo 'selected="selected"'; ?>>None</option>
			</select>
		</p>

		<!-- Third Title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'third_title' ); ?>">Third Column Title:</label>
			<input id="<?php echo $this->get_field_id( 'third_title' ); ?>" name="<?php echo $this->get_field_name( 'third_title' ); ?>" value="<?php echo $instance['third_title']; ?>" style="width:90%;" />
		</p>

		<!-- Third Column -->
		<p>
			<label for="<?php echo $this->get_field_id('third'); ?>">Display In Second Column:</label>
			<select id="<?php echo $this->get_field_id('third'); ?>" name="<?php echo $this->get_field_name('third'); ?>" style="width:100%;">
				<option value='latest' <?php if ('latest' == $instance['third']) echo 'selected="selected"'; ?>>Latest</option>
				<option value='popular' <?php if ('popular' == $instance['third']) echo 'selected="selected"'; ?>>Popular</option>
				<option value='video' <?php if ('video' == $instance['third']) echo 'selected="selected"'; ?>>Videos</option>
				<option value='gallery' <?php if ('gallery' == $instance['third']) echo 'selected="selected"'; ?>>Galleries</option>
				<option value='none' <?php if ('none' == $instance['third']) echo 'selected="selected"'; ?>>None</option>
			</select>
		</p>

		<!-- Number of days -->
		<p>
			<label for="<?php echo $this->get_field_id( 'popular_days' ); ?>">Number of days to use for Popular Posts:</label>
			<input id="<?php echo $this->get_field_id( 'popular_days' ); ?>" name="<?php echo $this->get_field_name( 'popular_days' ); ?>" value="<?php echo $instance['popular_days']; ?>" size="3" />
		</p>

		<!-- Number of posts -->
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>">Number of posts to display:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" size="3" />
		</p>

	<?php
	}
}

?>