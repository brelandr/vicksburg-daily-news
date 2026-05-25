<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="mvp-side-wrap" class="left relative theiaStickySidebar">
	<?php if ( is_single() || is_page() ) { ?>
		<?php if ( is_active_sidebar( 'mvp-sidebar-widget-post' ) ) { ?>
			<?php dynamic_sidebar( 'mvp-sidebar-widget-post' ); ?>
		<?php } ?>
	<?php } else { ?>
		<?php if ( is_active_sidebar( 'mvp-sidebar-widget' ) ) { ?>
			<?php dynamic_sidebar( 'mvp-sidebar-widget' ); ?>
		<?php } ?>
	<?php } ?>
</div><!--mvp-side-wrap-->