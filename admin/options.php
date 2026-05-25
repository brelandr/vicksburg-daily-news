<?php
	
//Theme Shortname
$shortname = "mvp";

if ( is_admin() ) {

$site_skin = array("Standard","Entertainment");
$arch_layout = array("Row","Column");
$prime_shade = array("Highlight","Standard");
$nav_color = array("Dark","Light");
$nav_layout = array("Large","Small");
$home_layout = array("Blog","Widgets","Widgets and Blog");
$post_layout = array("Template 1","Template 2","Template 3","Template 4","Template 5","Template 6","Template 7","Template 8");
$feat_layout = array("Featured 1","Featured 2","Featured 3","Featured 4","Featured 5","Featured 6");
$cat_layout = array("Featured 1","Featured 2");

}
	

/*	Start Admin Options 
	================================================= */
	$options = array();

/* General Settings */
$options[] = array( "name" => esc_html__('General','zox-news'),
			"type" => "section");


if (isset($site_skin)) {
$options[] = array( "name" => esc_html__('Site Skin','zox-news'),
			"desc" => esc_html__('Select between the Standard skin, or an Entertainment skin, with more bold headlines and a few other small changes.','zox-news'),
			"id" => $shortname."_site_skin",
			"class" => "medium first",
			"std" => "Standard",
			"type" => "select",
			"options" => $site_skin);
}

if (isset($nav_layout)) {
$options[] = array( "name" => esc_html__('Navigation Layout','zox-news'),
			"desc" => esc_html__('Select between a Large or Small navigation layout. The Large navigation will have two rows and a larger space for the logo and menu items.','zox-news'),
			"id" => $shortname."_nav_layout",
			"class" => "medium last",
			"std" => "Dark",
			"type" => "select",
			"options" => $nav_layout);
}

$options[] = array( "name" => esc_html__('Logo','zox-news'),
			"desc" => esc_html__('Upload a logo file that will appear in your header. The recommended maximum dimensions for this logo are 600px wide, but it can be any height.','zox-news'),
			"id" => $shortname."_logo",
			"class" => "medium first",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => esc_html__('Logo in Navigation','zox-news'),
			"desc" => esc_html__('Upload a logo file that will appear in your navigation. The recommended maximum dimensions for this logo are 200x30.','zox-news'),
			"id" => $shortname."_logo_nav",
			"class" => "medium last",
			"std" => "",
			"type" => "upload");
	
$options[] = array( "name" => esc_html__('Logo in Navigation Width','zox-news'),
			"desc" => "If you are utilizing the Google AMP feature, you will need to make sure your logo height is 30px and then enter the width of your logo file here. Default is 92.",
			"id" => $shortname."_amp_logo",
			"class" => "medium first",
			"std" => "92",
			"type" => "text");

$options[] = array( "name" => __('Logo in Footer','zox-news'),
			"desc" => esc_html__('Upload a logo file that will appear in your footer. There are no maximum recommended dimensions for this logo size.','zox-news'),
			"id" => $shortname."_logo_foot",
			"class" => "medium last",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => esc_html__('Custom Favicon','zox-news'),
			"desc" => esc_html__('Upload a 16x16px PNG/GIF image that will represent your website\'s favicon.','zox-news'),
			"id" => $shortname."_favicon",
			"class" => "medium first",
			"std" => "",
			"type" => "upload");

$options[] = array( "name" => esc_html__('Custom CSS','zox-news'),
			"desc" => "Enter your custom CSS here. You will not lose any of the CSS you enter here if you update the theme to a new version.",
			"id" => $shortname."_customcss",
			"class" => "medium last",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => esc_html__('Toggle Responsiveness','zox-news'),
			"desc" => "Toggle the responsiveness of the theme.",
			"id" => $shortname."_respond",
			"class" => "tiny first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Toggle Infinite Scroll','zox-news'),
			"desc" => "Toggle the Infinite Scroll feature.",
			"id" => $shortname."_infinite_scroll",
			"class" => "tiny",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Enable RTL','zox-news'),
			"desc" => "Toggle this box if you would like to enable the RTL stylesheet.",
			"id" => $shortname."_rtl",
			"class" => "tiny last",
			"std" => "false",
			"type" => "switch");


/* Theme Color Settings */
$options[] = array( "name" => esc_html__('Colors','zox-news'),
			"type" => "section");

if (isset($prime_shade)) {
$options[] = array( "name" => esc_html__('Primary Color Skin','zox-news'),
			"desc" => esc_html__('Select between a highlight and standard skin for the primary color. This will affect the color of the video/gallery icons and a few other color elements. If you are using a bright, highlight color like the default green that you see in the demo, choose highlight. If you are using a more standard color, choose standard.','zox-news'),
			"id" => $shortname."_prime_skin",
			"class" => "medium first",
			"std" => "Highlight",
			"type" => "select",
			"options" => $prime_shade);
}

if (isset($nav_color)) {
$options[] = array( "name" => esc_html__('Top Navigation Skin','zox-news'),
			"desc" => esc_html__('Select between a dark and light top navigation skin. This will affect the color of the social media buttons and fly-out menu text color.','zox-news'),
			"id" => $shortname."_nav_skin",
			"class" => "medium last",
			"std" => "Dark",
			"type" => "select",
			"options" => $nav_color);
}

$options[] = array( "name" => esc_html__('Primary Theme Color','zox-news'),
			"desc" => esc_html__('Primary color for the site.','zox-news'),
			"id" => $shortname."_primary_color",
			"class" => "tiny first",
			"std" => "#0be6af",
			"type" => "color");

$options[] = array( "name" => esc_html__('Secondary Color','zox-news'),
			"desc" => esc_html__('Secondary color for the site.','zox-news'),
			"id" => $shortname."_second_color",
			"class" => "tiny",
			"std" => "#ff005b",
			"type" => "color");

$options[] = array( "name" => esc_html__('Top Navigation Background Color','zox-news'),
			"desc" => esc_html__('The background color of the top navigation.','zox-news'),
			"id" => $shortname."_top_nav_bg",
			"class" => "tiny",
			"std" => "#000000",
			"type" => "color");

$options[] = array( "name" => esc_html__('Top Navigation Text Color','zox-news'),
			"desc" => esc_html__('The text color of the top navigation.','zox-news'),
			"id" => $shortname."_top_nav_text",
			"class" => "tiny last",
			"std" => "#555555",
			"type" => "color");

$options[] = array( "name" => esc_html__('Top Navigation Text Hover Color','zox-news'),
			"desc" => esc_html__('The text color when you mouse over the top navigation.','zox-news'),
			"id" => $shortname."_top_nav_hover",
			"class" => "tiny first",
			"std" => "#0be6af",
			"type" => "color");

$options[] = array( "name" => esc_html__('Bottom Navigation Background Color','zox-news'),
			"desc" => esc_html__('The background color of the top navigation.','zox-news'),
			"id" => $shortname."_bot_nav_bg",
			"class" => "tiny",
			"std" => "#ffffff",
			"type" => "color");

$options[] = array( "name" => esc_html__('Bottom Navigation Text Color','zox-news'),
			"desc" => esc_html__('The text color of the top navigation.','zox-news'),
			"id" => $shortname."_bot_nav_text",
			"class" => "tiny",
			"std" => "#000000",
			"type" => "color");

$options[] = array( "name" => esc_html__('Bottom Navigation Text Hover Color','zox-news'),
			"desc" => esc_html__('The text color when you mouse over the top navigation.','zox-news'),
			"id" => $shortname."_bot_nav_hover",
			"class" => "tiny last",
			"std" => "#0be6af",
			"type" => "color");

$options[] = array( "name" => esc_html__('Primary Link Color','zox-news'),
			"desc" => esc_html__('Primary link underline color for links within posts and pages.','zox-news'),
			"id" => $shortname."_link_color",
			"class" => "tiny first",
			"std" => "#0be6af",
			"type" => "color");

$options[] = array( "name" => esc_html__('Unstyled Link Color','zox-news'),
			"desc" => esc_html__('Unstyled default link color for links throughout the site.','zox-news'),
			"id" => $shortname."_link2_color",
			"class" => "tiny",
			"std" => "#ff005b",
			"type" => "color");


/* Font Settings */
$options[] = array( "name" => esc_html__('Fonts','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('General Content Font','zox-news'),
			"desc" => esc_html__('Enter the font name for the general font for the content on all pages.','zox-news'),
			"id" => $shortname."_content_font",
			"class" => "medium first",
			"std" => "Roboto",
			"type" => "text");

$options[] = array( "name" => esc_html__('Paragraph Font','zox-news'),
			"desc" => esc_html__('Enter the font name for the paragraph font for all pages.','zox-news'),
			"id" => $shortname."_para_font",
			"class" => "medium last",
			"std" => "PT Serif",
			"type" => "text");

$options[] = array( "name" => esc_html__('Top Navigation Font','zox-news'),
			"desc" => "Enter the font name for the top navigation menu.",
			"id" => $shortname."_menu_font",
			"class" => "medium first",
			"std" => "Oswald",
			"type" => "text");

$options[] = array( "name" => esc_html__('Featured Posts/Article Headline Font','zox-news'),
			"desc" => "Enter the font name the font for the headlines in the Featured Posts section and other headlines throughout the site.",
			"id" => $shortname."_featured_font",
			"class" => "medium last",
			"std" => "Oswald",
			"type" => "text");

$options[] = array( "name" => esc_html__('Article Title Font','zox-news'),
			"desc" => "Enter the font name the font for the title of posts on article pages.",
			"id" => $shortname."_title_font",
			"class" => "medium first",
			"std" => "Oswald",
			"type" => "text");

$options[] = array( "name" => esc_html__('General Heading Font','zox-news'),
			"desc" => "Enter the font name the font for the general headings that appear at the top of the different sections around the site.",
			"id" => $shortname."_heading_font",
			"class" => "medium last",
			"std" => "Roboto",
			"type" => "text");


/* Homepage Settings */
$options[] = array( "name" => esc_html__('Homepage Settings','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Attention','zox-news'),
			"desc" => "",
			"id" => $shortname."_attention_home_slider",
			"std" => "In order to utilize these functions, you will have to set up your homepage as a static page. Please refer to the Installing Demo Data section of the documentation for more information.",
			"type" => "message");

$options[] = array( "name" => esc_html__('Show Featured Posts?','zox-news'),
			"desc" => "Toggle the Featured Posts section from the homepage.",
			"id" => $shortname."_feat_posts",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

if (isset($feat_layout)) {
$options[] = array( "name" => esc_html__('Featured Posts Layout','zox-news'),
			"desc" => esc_html__('Select the layout of your Featured Posts section on the homepage.','zox-news'),
			"id" => $shortname."_feat_layout",
			"class" => "medium last",
			"std" => "Featured 1",
			"type" => "select",
			"options" => $feat_layout);
}

$options[] = array( "name" => esc_html__('Featured Posts Tag Slug','zox-news'),
			"desc" => esc_html__('Enter the Tag Slug of the Tag you want associated with the Featured Posts section. Posts with this Tag will be displayed in the Featured Slider at the top of the homepage.','zox-news'),
			"id" => $shortname."_feat_posts_tags",
			"class" => "medium first",
			"std" => "featured",
			"type" => "text");

$options[] = array( "name" => esc_html__('Featured Posts Ad Code','zox-news'),
			"desc" => esc_html__('If you are using Featured Posts #1, you can insert a 300x250 ad that will appear at the top of the right column. Enter your ad code (Eg. Google Adsense) for that area.','zox-news'),
			"id" => $shortname."_feat_ad",
			"class" => "medium last",
			"std" => "",
			"type" => "textarea");

if (isset($home_layout)) {
$options[] = array( "name" => __('Homepage Body Layout','zox-news'),
			"desc" => __('Select your layout for the body of the homepage that will appear in the main content area of the homepage.','zox-news'),
			"id" => $shortname."_home_layout",
			"class" => "medium first",
			"std" => "Widgets and Blog",
			"type" => "select",
			"options" => $home_layout);
}

if (isset($arch_layout)) {
$options[] = array( "name" => esc_html__('Homepage Blog Layout','zox-news'),
			"desc" => __('Select the layout for the blog section of the homepage.','zox-news'),
			"id" => $shortname."_blog_layout",
			"class" => "medium last",
			"std" => "Row",
			"type" => "select",
			"options" => $arch_layout);
}

$options[] = array( "name" => esc_html__('Number of posts per page','zox-news'),
			"desc" => "Set the number of posts per page that you want displayed on the Homepage Blog and the Latest News Template.",
			"id" => $shortname."_posts_num",
			"class" => "medium first",
			"std" => "10",
			"type" => "text");


/* Trending Posts Settings */
$options[] = array( "name" => esc_html__('Trending Posts Settings','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Trending Posts Heading','zox-news'),
			"desc" => esc_html__('Enter the heading of the Trending Posts section that will appear in the Featured Posts sections that utilize the Trending Posts feature.','zox-news'),
			"id" => $shortname."_pop_head",
			"class" => "medium first",
			"std" => "Trending",
			"type" => "text");

$options[] = array( "name" => esc_html__('Trending Posts Days','zox-news'),
			"desc" => "Number of days to use for Trending Posts. Only posts published within this length of time will be displayed in the Trending Posts section.",
			"id" => $shortname."_pop_days",
			"class" => "medium last",
			"std" => "9999",
			"type" => "text");


/* Article Settings */
$options[] = array( "name" => esc_html__('Article Settings','zox-news'),
			"type" => "section");

if (isset($post_layout)) {
$options[] = array( "name" => esc_html__('Default Post Template','zox-news'),
			"desc" => esc_html__('Select the default Post Template layout for your articles.','zox-news'),
			"id" => $shortname."_post_layout",
			"class" => "medium first",
			"std" => "Template 1",
			"type" => "select",
			"options" => $post_layout);
}

$options[] = array( "name" => esc_html__('Show Featured Image In Posts?','zox-news'),
			"desc" => esc_html__('Toggle the featured image thumbnail from all posts.','zox-news'),
			"id" => $shortname."_featured_img",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Show Social Sharing Buttons?','zox-news'),
			"desc" => "Toggle the social sharing buttons from all posts.",
			"id" => $shortname."_social_box",
			"class" => "medium last",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Show Post Info?','zox-news'),
			"desc" => "Toggle the author/post info from the top of posts.",
			"id" => $shortname."_author_info",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Show Author Box?','zox-news'),
			"desc" => "Toggle the author box from the bottom of your posts.",
			"id" => $shortname."_author_box",
			"class" => "medium last",
			"std" => "false",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Show Related Posts?','zox-news'),
			"desc" => "Toggle the Related Posts from the bottom of your posts.",
			"id" => $shortname."_related_posts",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Number of Related Posts','zox-news'),
			"desc" => "Set the number of posts that you want displayed in the Related Posts section on your posts.",
			"id" => $shortname."_related_num",
			"class" => "medium last",
			"std" => "6",
			"type" => "text");

$options[] = array( "name" => esc_html__('Show Teaser Posts?','zox-news'),
			"desc" => "Toggle the Teaser Posts from the bottom of your posts.",
			"id" => $shortname."_more_posts",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Number of Teaser Posts','zox-news'),
			"desc" => "Set the number of posts that you want displayed in the Teaser Posts section on your posts.",
			"id" => $shortname."_more_num",
			"class" => "medium last",
			"std" => "3",
			"type" => "text");

$options[] = array( "name" => esc_html__('Show Trending Posts?','zox-news'),
			"desc" => "Toggle the Trending Posts from the bottom of your posts.",
			"id" => $shortname."_trend_posts",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Number of Trending Posts','zox-news'),
			"desc" => "Set the number of posts that you want displayed in the Trending Posts section on your posts.",
			"id" => $shortname."_trend_post_num",
			"class" => "medium last",
			"std" => "8",
			"type" => "text");

$options[] = array( "name" => esc_html__('Show Previous/Next Post Links?','zox-news'),
			"desc" => "Toggle the links to the previous/next posts arrows in the margins of each article.",
			"id" => $shortname."_prev_next",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Toggle Scrolling Video','zox-news'),
			"desc" => "Toggle the scrolling fixed video function on video posts.",
			"id" => $shortname."_scroll_vid",
			"class" => "medium last",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Use Continue Reading Button On Mobile?','zox-news'),
			"desc" => "Toggle the Continue Reading button feature on mobile devices.",
			"id" => $shortname."_cont_read",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Disqus Forum Shortname','zox-news'),
			"desc" => "If you want to use Disqus as your commenting system, enter your Disqus Forum Shortname in order to activate Disqus on your site. This is the unique identifier for your website in Disqus (i.e. yourforumshortname.disqus.com)",
			"id" => $shortname."_disqus_id",
			"class" => "medium last",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Article Ad Code','zox-news'),
			"desc" => "Enter your ad code (Eg. Google Adsense) for the ad area below the article. The maximum width of this area is 740px, but it can be any height.",
			"id" => $shortname."_post_ad",
			"class" => "medium first",
			"std" => "",
			"type" => "textarea");


/*	Auto Load Posts Settings
	================================================= */

	$options[] = array( "name" => esc_html__('Auto Load Posts Settings',"zox-news"),
						"type" => "section");


	$options[] = array( "name" => esc_html__("Toggle Auto Load Posts","zox-news"),
				"desc" => esc_html__("Toggle on/off Auto Load Posts feature.","zox-news"),
				"id" => $shortname."_alp",
				"class" => "medium first",
				"std" => 'false',
				"type" => "switch");

	$options[] = array( "name" => esc_html__("Auto Load Posts Sidebar Layout","zox-news"),
				"desc" => esc_html__("Select the sidebar layout for Auto Load Posts pages.","zox-news"),
				"id" => $shortname."_alp_side",
				"std" => 0,
				"type" => "buttons",
				"options" => array(
					0 => "Latest",
					1 => "Widgets",
					2 => "No Sidebar"
				));

	$options[] = array( "name" => esc_html__("Number of Auto Load Posts","zox-news"),
				"desc" => esc_html__("Set the maximum number of Auto Load Posts. This number of posts will be loaded in the sidebar when the page loads.","zox-news"),
				"id" => $shortname."_alp_num",
				"std" => 10,
				"min" => 2,
				"max" => 100,
				"suffix" => null,
				"type" => "number");

	$options[] = array( "name" => esc_html__("Auto Load Posts Sidebar Ad Code","zox-news"),
				"desc" => esc_html__("Enter your ad code (Eg. Google Adsense) for the ad area in the Auto Load Posts sidebar. The maximum width of this area is 330px, but it can be any height. The area was designed to ideally display 300x250 or 300x500 ads.","zox-news"),
				"id" => $shortname."_alp_ad",
				"type" => "code");
	
	
/* Google AMP Settings */
$options[] = array( "name" => esc_html__('Google AMP Settings','zox-news'),
			"type" => "section");
	
$options[] = array( "name" => esc_html__('Enable Google AMP on mobile devices','zox-news'),
			"desc" => "Check this box if you would like to enable the Google AMP compatibility with the theme. You will also need to install and activate the AMP plugin that comes with the theme.",
			"id" => $shortname."_amp",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");
	
$options[] = array( "name" => esc_html__('Facebook App ID','zox-news'),
			"desc" => "In order to utilize the Google AMP Facebook share button, you must provide a valid Facebook App ID.",
			"id" => $shortname."_amp_fb",
			"class" => "medium last",
			"std" => "",
			"type" => "text");


/* Category Settings */
$options[] = array( "name" => esc_html__('Category Pages','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Attention','zox-news'),
			"desc" => "",
			"id" => $shortname."_attention_ad",
			"std" => "To set the number of posts that are displayed on category pages, go to Settings > Reading and change the 'Blog page show at most' number.",
			"type" => "message");

$options[] = array( "name" => esc_html__('Show Featured Posts','zox-news'),
			"desc" => "Toggle the Featured Posts section from the category pages.",
			"id" => $shortname."_feat_cat",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

if (isset($cat_layout)) {
$options[] = array( "name" => esc_html__('Featured Posts Layout','zox-news'),
			"desc" => esc_html__('Select the layout of your Featured Posts section on the category pages.','zox-news'),
			"id" => $shortname."_cat_layout",
			"class" => "medium last",
			"std" => "Featured 1",
			"type" => "select",
			"options" => $cat_layout);
}

if (isset($arch_layout)) {
$options[] = array( "name" => esc_html__('Archive Blog Layout','zox-news'),
			"desc" => __('Select the layout for the blog section of your category and archive pages.','zox-news'),
			"id" => $shortname."_arch_layout",
			"class" => "medium first",
			"std" => "Row",
			"type" => "select",
			"options" => $arch_layout);
}


/* Social Media Settings */
$options[] = array( "name" => esc_html__('Social Media','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Facebook','zox-news'),
			"desc" => "Enter your Facebook Page URL here.",
			"id" => $shortname."_facebook",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Twitter','zox-news'),
			"desc" => "Enter your Twitter URL here.",
			"id" => $shortname."_twitter",
			"class" => "medium last",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Pinterest','zox-news'),
			"desc" => "Enter your Pinterest URL here.",
			"id" => $shortname."_pinterest",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Instagram','zox-news'),
			"desc" => "Enter your Instagram URL here.",
			"id" => $shortname."_instagram",
			"class" => "medium last",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Google Plus','zox-news'),
			"desc" => "Enter your Google Plus URL here.",
			"id" => $shortname."_google",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Youtube','zox-news'),
			"desc" => "Enter your Youtube URL here.",
			"id" => $shortname."_youtube",
			"class" => "medium last",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Linkedin','zox-news'),
			"desc" => "Enter your Linkedin URL here.",
			"id" => $shortname."_linkedin",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Tumblr','zox-news'),
			"desc" => "Enter your Tumblr URL here.",
			"id" => $shortname."_tumblr",
			"class" => "medium last",
			"std" => "",
			"type" => "text");


/* Ad Management Settings */
$options[] = array( "name" => esc_html__('Ad Management','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Attention','zox-news'),
			"desc" => "",
			"id" => $shortname."_attention_ad",
			"std" => "The 300x250 ads are controlled via a Widget.",
			"type" => "message");

$options[] = array( "name" => esc_html__('Toggle Parallax Leaderboard','zox-news'),
			"desc" => "Toggle the parallax leaderboard feature and display a static leaderboard ad.",
			"id" => $shortname."_para_lead",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Header Leaderboard Ad Code','zox-news'),
			"desc" => "Enter your ad code (Eg. Google Adsense) for the 970x90 ad area. You can also place a 728x90 ad in this spot.",
			"id" => $shortname."_header_leader",
			"class" => "medium last",
			"std" => "",
			"type" => "textarea");

$options[] = array( "name" => esc_html__('Wallpaper Ad Image URL','zox-news'),
			"desc" => "Enter the URL for your wallpaper ad image. Wallpaper ad code should be a minimum of 1280px wide. Please see the theme documentation for more on wallpaper ad specifications.",
			"id" => $shortname."_wall_ad",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Wallpaper Ad Click-Through URL','zox-news'),
			"desc" => "Enter the URL for your wallpaper ad click-through.",
			"id" => $shortname."_wall_url",
			"class" => "medium last",
			"std" => "",
			"type" => "text");


/* Editorial & accessibility */
$options[] = array( "name" => esc_html__('Editorial & Accessibility','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Show “Last updated” on posts','zox-news'),
			"desc" => esc_html__('When enabled, a line is shown above the article body if the post was modified after publication (helps readers and transparency).','zox-news'),
			"id" => $shortname."_last_updated",
			"class" => "medium first",
			"std" => "true",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Reading progress bar on posts','zox-news'),
			"desc" => esc_html__('Optional thin bar at the top of the screen on single posts showing scroll progress.','zox-news'),
			"id" => $shortname."_reading_progress",
			"class" => "medium last",
			"std" => "false",
			"type" => "switch");

$options[] = array( "name" => esc_html__('Corrections policy URL','zox-news'),
			"desc" => esc_html__('If set, a link appears in the footer (e.g. a WordPress page about corrections).','zox-news'),
			"id" => $shortname."_footer_corrections_url",
			"class" => "medium first",
			"std" => "",
			"type" => "text");

$options[] = array( "name" => esc_html__('Ethics / standards URL','zox-news'),
			"desc" => esc_html__('Optional second footer link (e.g. ethics, masthead, or advertising standards).','zox-news'),
			"id" => $shortname."_footer_ethics_url",
			"class" => "medium last",
			"std" => "",
			"type" => "text");


/* Footer Settings */
$options[] = array( "name" => esc_html__('Footer Info','zox-news'),
			"type" => "section");

$options[] = array( "name" => esc_html__('Copyright Text','zox-news'),
			"desc" => "Here you can enter any text you want (eg. copyright text)",
			"id" => $shortname."_copyright",
			"std" => "Copyright &copy; " . date('Y') . " Vicksburg Daily News. Theme by Randy Breland, Land Tech Web Designs, Corp, powered by WordPress.",
			"type" => "textarea");
																																			
$GLOBALS['mvp_theme_options'] = $options;

return $options;