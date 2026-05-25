<div id="mvp-fly-wrap">
	<div id="mvp-fly-menu-top" class="left relative">
		<div class="mvp-fly-top-out left relative">
			<div class="mvp-fly-top-in">
				<div id="mvp-fly-logo" class="left relative">
					<?php if(get_option('mvp_logo_nav')) { ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zox_news_esc_url_option('mvp_logo_nav'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
					<?php } else { ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-nav.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
					<?php } ?>
				</div><!--mvp-fly-logo-->
			</div><!--mvp-fly-top-in-->
			<div class="mvp-fly-but-wrap mvp-fly-but-menu mvp-fly-but-click">
				<span></span>
				<span></span>
				<span></span>
				<span></span>
			</div><!--mvp-fly-but-wrap-->
		</div><!--mvp-fly-top-out-->
	</div><!--mvp-fly-menu-top-->
	<div id="mvp-fly-menu-wrap">
		<nav class="mvp-fly-nav-menu left relative">
			<?php wp_nav_menu(array('theme_location' => 'mobile-menu')); ?>
		</nav>
	</div><!--mvp-fly-menu-wrap-->
	<div id="mvp-fly-soc-wrap">
		<span class="mvp-fly-soc-head"><?php esc_html_e( 'Connect with us', 'zox-news' ); ?></span>
		<ul class="mvp-fly-soc-list left relative">
			<?php if(get_option('mvp_facebook')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_facebook'); ?>" target="_blank" class="fa fa-facebook fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_twitter')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_twitter'); ?>" target="_blank" class="fa fa-twitter fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_pinterest')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_pinterest'); ?>" target="_blank" class="fa fa-pinterest-p fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_instagram')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_instagram'); ?>" target="_blank" class="fa fa-instagram fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_google')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_google'); ?>" target="_blank" class="fa fa-google-plus fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_youtube')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_youtube'); ?>" target="_blank" class="fa fa-youtube-play fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_linkedin')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_linkedin'); ?>" target="_blank" class="fa fa-linkedin fa-2"></a></li>
			<?php } ?>
			<?php if(get_option('mvp_tumblr')) { ?>
				<li><a href="<?php echo zox_news_esc_url_option('mvp_tumblr'); ?>" target="_blank" class="fa fa-tumblr fa-2"></a></li>
			<?php } ?>
		</ul>
	</div><!--mvp-fly-soc-wrap-->
</div><!--mvp-fly-wrap-->