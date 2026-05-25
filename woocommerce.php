<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div id="mvp-article-wrap">
	<div id="mvp-article-cont" class="left relative">
		<div class="mvp-main-box">
			<div id="mvp-post-main" class="left relative">
				<?php if(is_single()) { if (have_posts()) : while (have_posts()) : the_post(); ?>
					<?php woocommerce_breadcrumb(); ?>
				<?php endwhile; endif; } else { ?>
					<?php woocommerce_breadcrumb(); ?>
				<?php } ?>
				<div class="mvp-woo-side-out right relative">
					<?php get_sidebar('woo'); ?>
					<div class="mvp-woo-side-in">
						<div id="mvp-post-content" class="left relative">
							<div id="mvp-content-wrap" class="left relative">
								<div id="mvp-content-body" class="left-relative">
									<div id="woo-content" class="left relative">
										<?php woocommerce_content(); ?>
										<?php wp_link_pages(); ?>
									</div><!--woo-content-->
								</div><!--mvp-content-body-->
							</div><!--mvp-content-wrap-->
						</div><!--mvp-post-content-->
					</div><!--mvp-woo-side-in-->
				</div><!--mvp-woo-side-out-->
			</div><!--mvp-post-main-->
		</div><!--mvp-main-box-->
	</div><!--mvp-article-cont-->
</div><!--mvp-article-wrap-->
<?php get_footer(); ?>