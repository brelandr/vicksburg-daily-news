<?php
/**
 * Plugin Name: Ad Widget
 */

add_action( 'widgets_init', 'mvp_ad_load_widgets' );

function mvp_ad_load_widgets() {
	register_widget( 'mvp_ad_widget' );
}

class mvp_ad_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'mvp_ad_widget', 'description' => esc_html__('A widget that displays an ad of any size.', 'zox-news') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'mvp_ad_widget' );

		/* Create the widget. */
		parent::__construct( 'mvp_ad_widget', esc_html__('Vicksburg Daily News: Ad Widget', 'zox-news'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$code = $instance['code'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		?>
			<div class="mvp-widget-ad left relative">
				<span class="mvp-ad-label"><?php esc_html_e( 'Advertisement', 'zox-news' ); ?></span>
				<?php echo html_entity_decode($code); ?>
			</div><!--mvp-widget-ad-->
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
		$instance['code'] = $new_instance['code'];


		return $instance;
	}


	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'code' => 'Enter ad code here');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Ad code -->
		<p>
			<label for="<?php echo $this->get_field_id( 'code' ); ?>">Ad code:</label>
			<textarea id="<?php echo $this->get_field_id( 'code' ); ?>" name="<?php echo $this->get_field_name( 'code' ); ?>" style="width:96%;" rows="6"><?php echo $instance['code']; ?></textarea>
		</p>


	<?php
	}
}

?>