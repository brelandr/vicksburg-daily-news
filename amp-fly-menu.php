<amp-sidebar id="sidebar" layout="nodisplay" side="left">
<div id="mvp-fly-wrap">
	<div id="mvp-fly-menu-top" class="left relative">
		<div class="mvp-fly-but-wrap mvp-fly-but-menu ampstart-btn caps m2" on="tap:sidebar.toggle" role="button" tabindex="0">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div><!--mvp-fly-but-wrap-->
	</div><!--mvp-fly-menu-top-->
	<div id="mvp-fly-menu-wrap">
		<nav class="mvp-fly-nav-menu left relative">
			<?php wp_nav_menu(array('theme_location' => 'mobile-menu')); ?>
		</nav>
	</div><!--mvp-fly-menu-wrap-->
</div><!--mvp-fly-wrap-->
</amp-sidebar>