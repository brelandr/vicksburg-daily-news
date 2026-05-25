<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
			</div><!--mvp-main-body-wrap-->
			<footer id="mvp-foot-wrap" class="left relative">
				<div id="mvp-foot-top" class="left relative">
					<div class="mvp-main-box">
						<div id="mvp-foot-logo" class="left relative">
							<?php if(get_option('mvp_logo_foot')) { ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zox_news_esc_url_option('mvp_logo_foot'); ?>" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
							<?php } else { ?>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logos/logo-large.png" alt="<?php bloginfo( 'name' ); ?>" data-rjs="2" /></a>
							<?php } ?>
						</div><!--mvp-foot-logo-->
						<div id="mvp-foot-soc" class="left relative">
							<ul class="mvp-foot-soc-list left relative">
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
						</div><!--mvp-foot-soc-->
						<div id="mvp-foot-menu-wrap" class="left relative">
							<div id="mvp-foot-menu" class="left relative">
								<?php wp_nav_menu(array('theme_location' => 'footer-menu', 'fallback_cb' => 'false')); ?>
							</div><!--mvp-foot-menu-->
						</div><!--mvp-foot-menu-wrap-->
					</div><!--mvp-main-box-->
				</div><!--mvp-foot-top-->
				<div id="mvp-foot-bot" class="left relative">
					<div class="mvp-main-box">
						<div id="mvp-foot-copy" class="left relative">
							<p><?php echo wp_kses_post( get_option('mvp_copyright') ?? '' ); ?></p>
							<?php
							$vdn_corr = get_option( 'mvp_footer_corrections_url' );
							$vdn_eth  = get_option( 'mvp_footer_ethics_url' );
							$vdn_corr = is_string( $vdn_corr ) ? trim( $vdn_corr ) : '';
							$vdn_eth  = is_string( $vdn_eth ) ? trim( $vdn_eth ) : '';
							if ( $vdn_corr !== '' || $vdn_eth !== '' ) :
								?>
							<p class="mvp-foot-trust">
								<?php if ( $vdn_corr !== '' ) : ?>
									<a href="<?php echo esc_url( $vdn_corr ); ?>"><?php esc_html_e( 'Corrections policy', 'zox-news' ); ?></a>
								<?php endif; ?>
								<?php if ( $vdn_corr !== '' && $vdn_eth !== '' ) : ?>
									<span class="mvp-foot-trust-sep" aria-hidden="true">|</span>
								<?php endif; ?>
								<?php if ( $vdn_eth !== '' ) : ?>
									<a href="<?php echo esc_url( $vdn_eth ); ?>"><?php esc_html_e( 'Ethics and standards', 'zox-news' ); ?></a>
								<?php endif; ?>
							</p>
							<?php endif; ?>
						</div><!--mvp-foot-copy-->
					</div><!--mvp-main-box-->
				</div><!--mvp-foot-bot-->
			</footer>
		</div><!--mvp-site-main-->
	</div><!--mvp-site-wall-->
</div><!--mvp-site-->
<div class="mvp-fly-top back-to-top">
	<i class="fa fa-angle-up fa-3"></i>
</div><!--mvp-fly-top-->
<div class="mvp-fly-fade mvp-fly-but-click">
</div><!--mvp-fly-fade-->
<?php wp_footer(); ?>
</body>
</html>