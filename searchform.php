<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input type="text" name="s" id="s" placeholder="<?php esc_attr_e( 'Search', 'zox-news' ); ?>" value="<?php echo get_search_query(); ?>" />
	<input type="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'zox-news' ); ?>" />
</form>