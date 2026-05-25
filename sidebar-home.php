<div id="mvp-side-wrap" class="left relative theiaStickySidebar">

	<?php if ( is_active_sidebar( 'mvp-home-sidebar-widget' ) ) { ?>
		<?php dynamic_sidebar( 'mvp-home-sidebar-widget' ); ?>
	<?php } ?>
	<?php get_template_part( 'parts/sidebar-home-recent' ); ?>
</div><!--mvp-side-wrap-->

