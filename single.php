<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<?php $alp = get_option('mvp_alp'); if ($alp == "true") { ?>
	<?php get_template_part( 'alp/alp', 'single' ); ?>
<?php } else { ?>
	<?php get_template_part( 'parts/post', 'single' ); ?>
<?php } ?>
<?php get_footer(); ?>