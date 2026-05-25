<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div id="mvp-article-wrap">
	<div id="mvp-article-cont" class="left relative">
		<div class="mvp-main-box">
			<section id="mvp-404">
				<h1><?php esc_html_e( 'Error', 'zox-news' ); ?> 404!</h1>
				<p><?php esc_html_e( 'The page you requested does not exist or has moved.', 'zox-news' ); ?></p>
			</section><!--mvp-404-->
		</div><!--mvp-main-box-->
	</div><!--mvp-article-cont-->
</div><!--mvp-article-wrap-->
<?php get_footer(); ?>