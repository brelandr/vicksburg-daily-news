<?php

/**
* Whitelabel Theme & Plugin Options
* Version 1.3
* Author: Curly Themes
*
*
* @package Whitelabel Theme & Plugin Options
* @version 1.2
* @author Curly Themes
*
*/

/**
* Whitelabel Options
* Set-up a new options page based o the class ini parameters.
*
* @since Whitelabel Theme & Plugin Options 1.2
*
* @param string $name Options page name. Default: 'Options Page'.
* @param string $slug Options page slug. Use only alpha-numeric characters and separate words by dash or underscore. Default: 'options-page'.
* @param string $prefix Options unique prefix. Default: 'wl'.
* @param string $parent Options page parent. Default: null.
* @param string $icon Options page icon. Default: null.
* @param string $role Options page user roles. Default: 'read'.
* @param string $order Options page position. Default: null
* @param boolean $style Set to FALSE to keep the standard WordPress style of the options page. Default: TRUE
* @param boolean $title Set to TRUE to place a title on your options page. Default: FALSE
* @param boolean $sidebar Set to TRUE to activate the sidebar generator. Default: FALSE
* @param array $options Options array. Default: null.
* @param string $url Base folder URI of the options page. This paramenter needs
* to be set for plugins, according with the plugins name. This parameter is optional for themes.
* @param string $folder Folder name, relative to the theme or plugin root folder.
*
* For more information about parents, icons and user roles:
* @link http://codex.wordpress.org/Function_Reference/add_theme_page
* @link http://melchoyce.github.io/dashicons/
* @link http://codex.wordpress.org/Roles_and_Capabilities
*
* @return void
*
*/

if ( ! class_exists('WhitelabelOptions') ) {
	class WhitelabelOptions{

		public $_parent;
		public $_icon;
		public $_role;
		public $_style;
		public $_title;
		public $_name;
		public $_slug;
		public $_order;
		public $_options;
		public $_prefix;
		public $_url;
		public $_folder;

		public function __construct(
			$name 		= 'Options Page',
			$slug 		= 'options-page',
			$prefix		= 'wl',
			$parent 	= null,
			$icon		= null,
			$role 		= 'read',
			$order 		= null,
			$style 		= true,
			$title		= false,
			$sidebar	= false,
			$options	= null,
			$url 		= null,
			$folder 	= 'admin') {

			$this->_parent 	= $parent;
			$this->_icon	= $icon;
			$this->_role	= $role;
			$this->_style 	= $style;
			$this->_title	= $title;
			$this->_name	= $name;
			$this->_slug	= $slug;
			$this->_order	= $order;
			$this->_options	= $options;
			$this->_prefix	= $prefix;
			$this->_url		= ( $url ) ? $url : get_template_directory_uri();
			$this->_folder	= $folder;

			if ( $sidebar === true && WhitelabelSidebars::$_count < 1) {
				$sidebar = new WhitelabelSidebars( $this->_url, $this->_folder );
			}

			/** Only do in Admin */
			if ( is_admin() ) {

				add_action('admin_enqueue_scripts', array($this, 'load_scripts') );

				/** Create Options Page */
				if ( $parent ) {
					add_action('admin_menu', array( $this, 'submenu_page' ) );
				} else {
					add_action('admin_menu', array( $this, 'menu_page' ) );
				}

				/** Save Options */
				add_action( 'wp_ajax_'.$this->_prefix.'_save_options', array( $this, 'save_options') );

				/** Import Options */
				add_action( 'wp_ajax_'.$this->_prefix.'_import_options', array( $this, 'import_options' ) );

				/** Backup Options */
				add_action( 'wp_ajax_'.$this->_prefix.'_backup_options', array( $this, 'backup_options' ) );

				/** Reset Options */
				add_action( 'wp_ajax_'.$this->_prefix.'_reset_options', array( $this, 'reset_options' ) );

				/** Restore Options */
				add_action( 'wp_ajax_'.$this->_prefix.'_restore_options', array( $this, 'restore_options' ) );

				/** Admin Bar */
				add_action( 'wp_before_admin_bar_render', array( $this, 'top_navigation' ) );

			}
		}

		/** Save Options Hook */
		function save_options() {

			if ( ! isset( $_POST['theme_options_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['theme_options_nonce'], 'theme_options_nonce_field' ) ) {
				return;
			}

			$data = $_POST['data'];

			if ( isset( $data ) ) {
				foreach ($data as $key => $value) {
					update_option( $data[$key][0], stripcslashes( $data[$key][1] ) );
				}
			}

			die();
		}

		/** Import Options Hook */
		function import_options() {
			$data = $_POST['data'];
			$data = json_decode( $data, true );

			if ( isset( $data ) ) {
				foreach ( $data as $key => $value ) {
					update_option( $key, stripcslashes( $value) );
				}
			}

			die();
		}

		/** Backup Options Hook */
		function backup_options() {

			foreach ( $_POST['data'] as $key => $value ) {
				$now = time();
				if ( $_POST['data'][$key][0] ==  $this->_prefix.'_theme_options_backup_list' ) {
					$current_list = get_option( $this->_prefix.'_theme_options_backup_list' );
					update_option( $this->_prefix.'_theme_options_backup_list', $current_list.$now.' ' );

				} else {
					update_option( $_POST['data'][$key][0].$now, stripcslashes( $_POST['data'][$key][1] ) );
				}
			}

			die();
		}

		/** Reset Options Hook */
		function reset_options() {
			$data = $_POST['data'];

			if (isset($data)) {
				foreach ( $data as $key => $value ) {
					if ( strpos( $data[$key][0], '_theme_options_backup_') !== false ) {
						$old = get_option( $this->_prefix.'_theme_options_backup_list' );
						$new = str_replace( $data[$key][1], ' ', $old );
						update_option( $this->_prefix.'_theme_options_backup_list', $new );
					}
					delete_option( $data[$key][0] );
				}
			}

			die();
		}

		/** Restore Options Hook */
		function restore_options() {
			$data = get_option($_POST['data'][0][0]);
			$data = json_decode( $data, true );

			foreach ( $data as $key => $value ) {
				update_option( $value['option'],  stripcslashes( $value['value'] ) );
			}

			die();
		}

		/** Enqueue Scripts & Styles */
		function load_scripts() {
			if ( ! function_exists( 'get_current_screen' ) ) {
				return;
			}

			$screen = get_current_screen();
			if ( ! $screen || false === strpos( $screen->id, $this->_slug ) ) {
				return;
			}

			global $_wp_admin_css_colors;
			$admin_colors = $_wp_admin_css_colors;
			$color_scheme = $admin_colors[get_user_option('admin_color')]->colors;

			if ( $this->_style !== false ) {
				wp_enqueue_style( 'curly-google-font-roboto', 'http://fonts.googleapis.com/css?family=Roboto:400,300,700,900', true );
			}

			wp_enqueue_style('curly-whitelabel-select', $this->_url . '/'.$this->_folder.'/css/selectric.css', true);
			wp_enqueue_style('curly-whitelabel-chosen', $this->_url.'/'.$this->_folder.'/css/chosen.css', true);
			wp_enqueue_style('curly-whitelabel-main', $this->_url.'/'.$this->_folder.'/css/main.css', true);
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-position');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-button');
			wp_enqueue_media();
			wp_enqueue_script('curly-whitelabel-selectric', $this->_url.'/'.$this->_folder.'/js/jquery.selectric.min.js' , 'jquery', null, true);
			wp_enqueue_script('curly-whitelabel-chosen', $this->_url.'/'.$this->_folder.'/js/jquery.chosen.min.js' , 'jquery', null, true);
			wp_enqueue_script('curly-whitelabel-main', $this->_url.'/'.$this->_folder.'/js/main.js' , 'jquery', null, true);

			wp_localize_script('curly-whitelabel-main', 'js_options_data', array(
				1 => $this->_url,
				2 => __('Saving', 'zox-news'),
				3 => __('You are about to leave this page without saving. All changes will be lost.', 'zox-news'),
				4 => __('WARNING: You are about to delete all your settings! Please confirm this action.', 'zox-news'),
				5 => $this->_prefix,
				6 => __('WARNING: You are about to restore your backup. This will overwrite all your settings! Please confirm this action.', 'zox-news'),
				7 => __('WARNING: You are about to delete your backup. All unsaved options will be lost. We recommend that you save your options before deleting a backup. Please confirm this action.', 'zox-news'),
				8 => __('WARNING: You are about to create a backup. All unsaved options will be lost. We recommend that you save your options before deleting a backup. Please confirm this action.', 'zox-news'),
				9 => __('Delete','zox-news'),
				10=> $this->_prefix
			));

			if ( $this->_style !== false ) {
				$typography = '
					#theme-options-wrapper{
						font-family: "Roboto", sans-serif;
					}
					#theme-options-wrapper h2{
						font-weight: 300;
						font-size: 24px;
					}
					#theme-options .form-control label.name{
						font-size: 16px;
						line-height: 22px;
					}
					#theme-options .form-control input[type=text],
					#theme-options .buttons label,
					#theme-options .wp-color-result::after,
					#theme-options .wp-picker-clear,
					#tab-list a{
						font-size: 13px;
					}
					#theme-options .wp-color-picker,
					#theme-options p,
					#theme-options .message h3{
						font-size: 14px !important;
					}
				';
			} else {
				$typography = null;
			}

			$color_scheme = '
				#tab-list li.ui-state-active a{
					color: '.$color_scheme[3].';
					border-color: '.$color_scheme[3].';
				}
				#save-options-top, #save-options-bottom,
				#theme-options .buttons label.ui-state-active,
				#theme-options .slider.ui-slider .ui-slider-handle,
				#theme-options .buttons.buttons-images label.ui-state-active::after,
				#theme-options .selectricItems li:hover,
				#theme-options .chosen-container .chosen-results li.highlighted,
				#theme-options .switch.on,
				#theme-options .btn,
				#options-saved{
					background: '.$color_scheme[3].';
					color: #fff;
				}
				#options-error{
					background: '.$color_scheme[2].';
					color: #fff;
				}
				#theme-options .btn:hover,
				#save-options-top:hover, #save-options-bottom:hover,
				#theme-options .slider.ui-slider .ui-slider-handle:hover,
				#theme-options .slider.ui-slider .ui-slider-handle:active{
					background: '.$color_scheme[2].';
					color: #fff;
				}
				#theme-options .buttons.buttons-images label.ui-state-active,
				#theme-options .switch.on{
					border-color: '.$color_scheme[3].';
				}
				#tab-list a:hover{
					color: '.$color_scheme[3].';
				}';

			$css = $typography.$color_scheme;

			wp_add_inline_style('curly-whitelabel-main', $css);
		}

		/** Top Navigation Hook */
		function top_navigation() {

			global $wp_admin_bar;

			if ( $this->_parent ) {
				$link_base = admin_url().$this->_parent.'?page='.$this->_slug;
			} else {
				$link_base = admin_url().'admin.php?page='.$this->_slug;
			}

			$wp_admin_bar->add_menu( array(
				'parent'	=> false,
				'id'		=> $this->_slug,
				'title'		=> $this->_name,
				'href'		=> $link_base,
				'meta' 		=> array( 'title' => $this->_name )
			) );

			foreach ( $this->_options as $key => $value) {
				if ($value['type'] == 'section') {
					$wp_admin_bar->add_menu( array(
						'parent'	=> $this->_slug,
						'id'		=> 'curly'.$key,
						'title'		=> $value['name'],
						'href'		=> $link_base.'#'.$key,
						'meta' 		=> array( 'title' => $value['name'] )
					) );
				}
			}
		}

		/** Add Subpage */
		function submenu_page(){
		     add_submenu_page( $this->_parent, $this->_name, $this->_name, $this->_role, $this->_slug, array($this, 'create_page') );
		}

		/** Add Page */
		function menu_page() {
			add_theme_page( $this->_name, $this->_name, $this->_role, $this->_slug, array($this, 'create_page'), $this->_icon, $this->_order );
		}

		/** Create Page Hook */
		function create_page() {
			$list_items = null;
			$div_contents = null;

			if ( ! is_array( $this->_options ) || empty( $this->_options ) ) {
				$div_contents = '<div class="no-tab">' . __('No options are available for this page.', 'zox-news') . '</div>';
			} else {
				$tab_groups = array();
				$tabs = array();
				foreach ( $this->_options as $key => $value ) {
					if ( $value['type'] == 'section' ) {
						$tabs[] = array('id' => $key, 'name' => $value['name']);
						$parent = $key;
					} else {
						if ( ! isset( $parent ) ) {
							$parent = 0;
						}
						if ( isset( $value['id'] ) ) {
							$tab_groups[ $parent ][ $key ]['id'] = $value['id'];
						}
						if ( isset( $value['type'] ) ) {
							$tab_groups[ $parent ][ $key ]['type'] = $value['type'];
						}
						if ( isset( $value['name'] ) ) {
							$tab_groups[ $parent ][ $key ]['name'] = $value['name'];
						}
						if ( isset( $value['desc'] ) ) {
							$tab_groups[ $parent ][ $key ]['desc'] = $value['desc'];
						}
						if ( isset( $value['std'] ) ){
							$tab_groups[ $parent ][ $key ]['std'] = $value['std'];
						} else {
							$tab_groups[ $parent ][ $key ]['std'] = null;
						}
						if ( isset( $value['options'] ) ) {
							$tab_groups[ $parent ][ $key ]['options'] = $value['options'];
						}
						if ( isset( $value['class'] ) ) {
							$tab_groups[ $parent ][ $key ]['class'] = $value['class'];
						}
						if ( isset( $value['prefix'] ) ) {
							$tab_groups[ $parent ][ $key ]['prefix'] = $value['prefix'];
						}
						if ( isset( $value['suffix'] ) ) {
							$tab_groups[ $parent ][ $key ]['suffix'] = $value['suffix'];
						}
						if ( isset( $value['min'] ) ) {
							$tab_groups[ $parent ][ $key ]['min'] = $value['min'];
						}
						if ( isset( $value['max'] ) ) {
							$tab_groups[ $parent ][ $key ]['max'] = $value['max'];
						}
						if ( isset( $value['increment'] ) ) {
							$tab_groups[ $parent ][ $key ]['increment'] = $value['increment'];
						}
						if ( isset( $value['alert'] ) ) {
							$tab_groups[ $parent ][ $key ]['alert'] = $value['alert'];
						}
						if ( isset( $value['source'] ) ) {
							$tab_groups[ $parent ][ $key ]['source'] = $value['source'];
						}
						if ( isset( $value['placeholder'] ) ) {
							$tab_groups[ $parent ][ $key ]['placeholder'] = $value['placeholder'];
						}
						if ( isset( $value['editor_settings'] ) ) {
							$tab_groups[ $parent ][ $key ]['editor_settings'] = $value['editor_settings'];
						}
						if ( isset( $value['height'] ) ) {
							$tab_groups[ $parent ][ $key ]['height'] = $value['height'];
						}
					}
				}

				if ( ! empty( $tabs ) ) {

					$list_items = '<ul id="tab-list">';
					$div_contents = null;

					foreach ( $tabs as $tab ) {

						$list_items 	.= '<li><a href="#'.$tab['id'].'">'.$tab['name'].'</a></li>';
						$div_contents 	.= '<div id="'.$tab['id'].'" class="tab">';

						if ( ! empty( $tab_groups[ $tab['id'] ] ) ) {
							foreach ( $tab_groups[ $tab['id'] ] as $tab_content ) {
								$option = new WhitelabelOptionsGenerator( $tab_content, $this->_prefix );
								$div_contents .= $option;
							}
						} else {
							$div_contents .= __('There are no options defined for this tab.', 'zox-news');
						}

						$div_contents	.= '</div>';
					}
					$list_items .= '</ul>';

				} else {

					$no_tab = true;
					$div_contents = '<div class="no-tab">';

					if ( ! empty( $tab_groups[0] ) ) {
						foreach ( $tab_groups[0] as $tab_content ) {
							$option = new WhitelabelOptionsGenerator( $tab_content );
							$div_contents .= $option;
						}
					} else {
						$div_contents .= __('There are no options defined for this page.', 'zox-news');
					}

					$div_contents .= '</div>';

				}
			}

			$html = '<div id="theme-options-wrapper">';
				$html .= ( $this->_title === true ) ? '<h1>'.$this->_name.'</h1>' : null;
				$html .= '<div id="theme-options">';
					$html .= ( isset( $list_items ) ) ? $list_items : null;
					$html .= ( $div_contents ) ? $div_contents : null;
				$html .= '</div>';
				$html .= wp_nonce_field('theme_options_nonce_field', 'theme_options_nonce', true, false);
				$html .= '<a href="#" id="save-options-bottom" class="'.( ( isset($no_tab) && $no_tab === true ) ? 'no-tab' : null ).'" title="'.__('Save Options','zox-news').'">'.__('Save Options','zox-news').'</a>';
			$html .= '</div>';
			$html .= '<a href="#" id="save-options-top" title="'.__('Quick Save','zox-news').'">'.__('Quick Save','zox-news').'</a>';
			$html .= '<div id="options-saved"><div class="fa fa-save fa-large fa-5x"></div><strong>'.__('Saved','zox-news').'</strong></div>';
			$html .= '<div id="options-error"><div class="fa fa-warning fa-large fa-5x"></div><strong>'.__('Error','zox-news').'</strong></div>';

			echo html_entity_decode($html);
		}
	}
}

/**
* Whitelabel Options Generator
* Used to create each option based on type.
*
* @since Whitelabel Theme & Plugin Options 1.2
*
*/

if ( ! class_exists( 'WhitelabelOptionsGenerator' ) ) {
	class WhitelabelOptionsGenerator {

	public $_data;
	public $_id;
	public $_type;
	public $_default;
	public $_value;
	public $_class;
	public $_desc;
	public $_increment;
	public $_name;
	public $_options;
	public $_min;
	public $_max;
	public $_prefix;
	public $_suffix;
	public $_source;
	public $_placeholder;
	public $_editor;
	public $_height;
	public $_upload_title;
	public $_upload_button;
	public $_options_prefix;

	public function __construct( $data = null, $prefix = 'white' ) {
		if ( ! is_array( $data ) ) {
			$data = array(
				'type' => 'message',
				'name' => '',
				'desc' => '',
			);
		}
		$this->_data 			= $data;
		$this->_type			= isset( $data['type'] ) ? $data['type'] : 'message';
		$this->_id				= ( isset( $data['id'] ) ) ? $data['id'] : null;
		$this->_default 		= ( isset( $data['std'] ) ) ? $data['std'] : null;
		$this->_value 			= ( null !== get_option( $this->_id, null ) ) ? esc_html( get_option( $this->_id ) ) : $this->_default;
		$this->_class 			= ( isset( $data['class'] ) ) ? $data['class'] : null;
		$this->_desc 			= ( isset( $data['desc'] ) ) ? '<span class="description">'.$data['desc'].'</span>' : null;
		$this->_desc 		   .= ( isset( $data['alert'] ) ) ? '<span class="description alert">'.$data['alert'].'</span>' : null;
		$this->_increment 		= ( isset( $data['increment'] ) ) ? $data['increment'] : 1;
		$this->_name 			= ( isset( $data['name'] ) ) ? esc_html($data['name']) : null;
		$this->_options 		= ( isset( $data['options'] ) ) ? $data['options'] : null;
		$this->_min 			= ( isset( $data['min'] ) ) ? $data['min'] : null;
		$this->_max 			= ( isset( $data['max'] ) ) ? $data['max'] : null;
		$this->_prefix 			= ( isset( $data['prefix'] ) ) ? $data['prefix'] : null;
		$this->_suffix 			= ( isset( $data['suffix'] ) ) ? $data['suffix'] : null;
		$this->_source 			= ( isset( $data['source'] ) ) ? $data['source'] : null;
		$this->_placeholder 	= ( isset( $data['placeholder'] ) ) ? $data['placeholder'] : null;
		$this->_editor 			= ( isset( $data['editor'] ) ) ? $data['editor_settings'] : null;
		$this->_height 			= ( isset( $data['height'] ) ) ? $data['height'] : null;
		$this->_upload_title 	= __('Insert ', 'zox-news') . $this->_name;
		$this->_upload_button	= __('Choose as ', 'zox-news') . $this->_name;
		$this->_options_prefix	= $prefix;
	}

	public function __toString() {
		switch ( $this->_type ) {
			case 'title' : {
				return $this->title();
			} break;
			case 'message' : {
				return $this->message();
			} break;
			case 'html' : {
				return $this->html();
			} break;
			case 'iframe' : {
				return $this->iframe();
			} break;
			case 'divider' : {
				return $this->divider();
			} break;
			case 'text' : {
				return $this->text();
			} break;
			case 'textarea' : {
				return $this->textarea();
			} break;
			case 'switch' : {
				return $this->switcher();
			} break;
			case 'checkbox' : {
				return $this->checkbox();
			} break;
			case 'checkboxes' : {
				return $this->checkboxes();
			} break;
			case 'radio' : {
				return $this->radio();
			} break;
			case 'select' : {
				return $this->select();
			} break;
			case 'select_search' : {
				return $this->select_search();
			} break;
			case 'select_multiple' : {
				return $this->select_multiple();
			} break;
			case 'color' : {
				return $this->color();
			} break;
			case 'upload' : {
				return $this->image();
			} break;
			case 'upload_min' : {
				return $this->image_mini();
			} break;
			case 'images' : {
				return $this->images();
			} break;
			case 'number' : {
				return $this->number();
			} break;
			case 'buttons' : {
				return $this->buttons();
			} break;
			case 'editor_settings' : {
				return $this->editor();
			} break;
			case 'font' : {
				return $this->font();
			} break;
			case 'google_font' : {
				return $this->font_google();
			} break;
			case 'backup' : {
				return $this->backup_button();
			} break;
			case 'reset' : {
				return $this->reset_button();
			} break;
			case 'export' : {
				return $this->export();
			} break;
			case 'import' : {
				return $this->import();
			} break;
			case 'code' : {
				return $this->code();
			} break;
			default	: {
				return '';
			}
		}
	}

	/** Title Option */
	function title() {
		$output = '<div class="form-control '.$this->_class.' info-title">';
			$output .= '<h2>'.$this->_name.'</h2>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Message Option */
	function message() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<div class="message">';
			$output .= ( $this->_name ) ? '<h3>'.$this->_name.'</h3>' : null;
			$output .= $this->_default;
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** HTML Option */
	function html() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label><br>';
			$output .= $this->_default;
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** iFrame Option */
	function iframe() {
		$output = '<div class="form-control">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<div class="content-frame"><iframe src="'.$this->_source.'" height="'.$this->_height.'"></iframe></div>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Divider Option **/
	function divider() {
		$output = '<hr>';

		return $output;
	}

	/** Text Field **/
	function text() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<input type="text" placeholder="'.$this->_placeholder.'" id="'.$this->_id.'" name="'.$this->_name.'" value="'.$this->_value.'">';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Textarea Field */
	function textarea() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<textarea placeholder="'.$this->_placeholder.'" id="'.$this->_id.'" name="'.$this->_id.'">'.wp_kses_post( $this->_value ).'</textarea>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Switch Option */
	function switcher() {
		$output = '<div class="form-control '.$this->_class.' switch-control">';
			$output .= '<input type="checkbox" class="js-switch" id="'.$this->_id.'" name="'.$this->_id.'" '.checked( $this->_value, 'true', false ).'><label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Checkbox Option */
	function checkbox() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">';
			$output .= '<input type="checkbox" id="'.$this->_id.'" name="'.$this->_id.'"'.checked( $this->_value, 'true', false ).'>'.$this->_name;
			$output .= '</label>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Checkboxes Option */
	function checkboxes() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			foreach ( $this->_options as $key => $option ) {
				$output .= '<label class="checkbox"><input type="checkbox" id="'.$this->_id.'_'.$key.'" value="'.$key.'"'.checked( get_option( $this->_id.'_'.$key, $this->_default[$key] ), 'true', false).'>'.$option.'</label>';
			}
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Radio Option */
	function radio() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			foreach ( $this->_options as $key => $option ) {
				$output .= '<label class="checkbox"><input type="radio" id="'.$this->_id.'_'.$key.'" value="'.$key.'" name="'.$this->_id.'" '.checked( $this->_value, $key, false).'>'.$option.'</label>';
			}
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Select Option */
	function select() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<select class="select-style" id="'.$this->_id.'" name="'.$this->_id.'">';
				foreach ( $this->_options as $key => $option ) {
					$output .= '<option value="'.$key.'" '.selected( $this->_value, $key, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Select Option with Search */
	function select_search() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<select class="select-chosen" id="'.$this->_id.'" name="'.$this->_id.'">';
				foreach ( $this->_options as $key => $option ) {
					$output .= '<option value="'.$key.'" '.selected( $this->_value, $key, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Search Multiple Option */
	function select_multiple() {
		$value = get_option( $this->_id, $this->_default );
		if ( !is_array($value) ) {
			$value = explode(",", $value);
		}
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<select class="select-chosen" multiple id="'.$this->_id.'" name="'.$this->_id.'">';
				foreach ( $this->_options as $key => $option ) {
					$output .= '<option value="'.$key.'" '.selected( in_array( $key, $value ) ? $key : null, $key, false).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Color Option */
	function color() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<input type="text" id="'.$this->_id.'" name="'.$this->_id.'" class="color-picker" value="'.$this->_value.'">';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Image Option */
	function image() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<input type="text" id="'.$this->_id.'" name="'.$this->_id.'" value="'.get_option( $this->_id ).'">';
			$output .= '<input type="hidden" id="'.$this->_id.'_id" name="'.$this->_id.'_id" value="'.get_option( $this->_id.'_id' ).'">';
			$output .= '<a href="#" class="image-upload-button btn" data-upload-title="'.$this->_upload_title.'" data-upload-button="'.$this->_upload_button.'">'.__('Upload','zox-news').'</a>';
			$output .= '<a href="#" class="image-clear-button btn" style="display:'.( ( $this->_value ) ? 'inline-block' : 'none').'">'.__('Clear','zox-news').'</a>';
			$output .= ( $this->_value ) ? '<img src="'.$this->_value.'" class="image-preview">' : null;
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Image Option without Preview */
	function image_mini() {
		$output = '<div class="form-control '.$this->_class.' upload_file">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<input type="text" id="'.$this->_id.'" name="'.$this->_id.'" value="'.get_option( $this->_id ).'">';
			$output .= '<a href="#" class="image-upload-button btn" data-upload-title="'.$this->_upload_title.'" data-upload-button="'.$this->_upload_button.'">'.__('Upload','zox-news').'</a>';
			$output .= '<a href="#" class="image-clear-button btn" style="display:'.( ( $this->_value ) ? 'inline-block' : 'none').'">'.__('Clear','zox-news').'</a>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Images Select Option */
	function images() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<div class="buttons buttons-images">';
				foreach ( $this->_options as $key => $option ) {
					$output .= '<input type="radio" id="'.$this->_id.'_'.$key.'" value="'.$key.'" name="'.$this->_id.'" '.checked( $this->_value, $key, false ).'>';
					$output .= '<label for="'.$this->_id.'_'.$key.'"><img src="'.$option.'"></label>';
				}
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Number Option */
	function number() {
		$output = '<div class="form-control '.$this->_class.'" style="position:relative">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<input type="hidden" id="'.$this->_id.'" name="'.$this->_id.'" value="'.$this->_value.'">';
			$output .= '<div class="slider" id="'.$this->_id.'_slider"></div>';
			$output .= '<div class="slider_value">'.$this->_prefix.$this->_value.$this->_suffix.'</div>';
			$output .= $this->_desc;
		$output .= '</div>';
		$output .= '<script type="text/javascript">jQuery(function() { jQuery( "#'.$this->_id.'_slider" ).slider({ value: '.$this->_value.' , step: '.$this->_increment.' , min:'.$this->_min.' , max:'.$this->_max.' , slide: function( event, ui ) { jQuery(this).siblings(".slider_value").text( "'.$this->_prefix.'" + ui.value + "'.$this->_suffix.'" ); jQuery(this).siblings("input[type=hidden]").val(ui.value); }}); });</script>';

		return $output;
	}

	/** Buttons Option */
	function buttons() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<div class="buttons">';
				foreach ( $this->_options as $key => $option ) {
					$output .= '<input type="radio" id="'.$this->_id.'_'.$key.'" value="'.$key.'" name="'.$this->_id.'" '.checked( $this->_value, $key, false ).'>';
					$output .= '<label for="'.$this->_id.'_'.$key.'">'.$option.'</label>';
				}
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Editor Option */
	function editor() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			ob_start(); wp_editor( get_option( $this->_id, $this->_default ), $this->_id, $this->_editor);
			$output .= ob_get_clean();
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Font Option */
	function font() {
		$font_style = array(
			__('Light', 'zox-news'),
			__('Light Italic', 'zox-news'),
			__('Normal', 'zox-news'),
			__('Bold', 'zox-news'),
			__('Italic', 'zox-news'),
			__('Bold Italic', 'zox-news')
		);
		$font_variant = array(
			__('Normal', 'zox-news'),
			__('Capitalize', 'zox-news'),
			__('Uppercase', 'zox-news'),
			__('Small Caps', 'zox-news')
		);
		$value = get_option( $this->_id, $this->_default[0] );
		$value_size = ( get_option( $this->_id.'_size', null) ) ? get_option( $this->_id.'_size') : $this->_default[1];
		$value_style = ( get_option( $this->_id.'_style', null) ) ? get_option( $this->_id.'_style') : $this->_default[2];
		$value_variant = ( get_option( $this->_id.'_variant', null) ) ? get_option( $this->_id.'_variant') : $this->_default[3];

		$output = '<div class="form-control typography">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<div class="font-chooser">';
				$output .= '<select class="select-chosen" id="'.$this->_id.'" name="'.$this->_id.'">';
					foreach ( $this->_options as $key => $option ) {
						$output .= '<option value="'.$option.'" '.selected( $value, $option, false ).'>'.$option.'</option>';
					}
				$output .= '</select>';
			$output .= '</div>';
			$output .= '<select class="select-style" id="'.$this->_id.'_style" name="'.$this->_id.'_style">';
				foreach ( $font_style as $key => $option ) {
					$output .= '<option value="'.$key.'" '.selected( $value_style, $key, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= '<select class="select-style font-variant" id="'.$this->_id.'_variant" name="'.$this->_id.'_variant">';
				foreach ( $font_variant as $key => $option ) {
					$output .= '<option value="'.$key.'" '.selected( $value_variant, $key, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= '<div class="font-size">';
				$output .= '<input type="hidden" id="'.$this->_id.'_size" name="'.$this->_id.'_size" value="'.$value_size.'">';
				$output .= '<div class="slider" id="'.$this->_id.'_size_slider"></div>';
				$output .= '<div class="slider_value">'.$value_size.$this->_suffix.'</div>';
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';
		$output .= '<script type="text/javascript">jQuery(function() { jQuery( "#'.$this->_id.'_size_slider" ).slider({ value: '.$value_size.' ,  min:'.$this->_min.' , max:'.$this->_max.' , step: '.$this->_increment.' , slide: function( event, ui ) { jQuery(this).siblings(".slider_value").text( ui.value + "'.$this->_suffix.'" ); jQuery(this).siblings("input[type=hidden]").val(ui.value); }}); });</script>';

		return $output;
	}

	/** Google Font Option */
	function font_google() {
		$font_style = array(
			__('100', 'zox-news'),
			__('200', 'zox-news'),
			__('300', 'zox-news'),
			__('400', 'zox-news'),
			__('500', 'zox-news'),
			__('600', 'zox-news'),
			__('700', 'zox-news'),
			__('800', 'zox-news'),
			__('900', 'zox-news')
		);
		$font_variant = array(
			__('None', 'zox-news'),
			__('Capitalize', 'zox-news'),
			__('Uppercase', 'zox-news'),
			__('Lowercase', 'zox-news')
		);
		$value = get_option( $this->_id, $this->_default[0] );
		$value_size = ( get_option( $this->_id.'_size', null) ) ? get_option( $this->_id.'_size' ) : $this->_default[1];
		$value_style = ( get_option( $this->_id.'_style', null) ) ? get_option( $this->_id.'_style' ) : $this->_default[2];
		$value_variant = ( get_option( $this->_id.'_variant', null) ) ? get_option( $this->_id.'_variant' ) : $this->_default[3];

		$output = '<div class="form-control typography">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<div class="font-chooser">';
				$output .= '<select class="select-chosen" id="'.$this->_id.'" name="'.$this->_id.'">';
					foreach ( $this->_options as $key => $option ) {
						$output .= '<option value="'.$option['family'].'" '.selected( $value, $option['family'], false ).'>'.$option['family'].'</option>';
					}
				$output .= '</select>';
			$output .= '</div>';
			$output .= '<select class="select-style" id="'.$this->_id.'_style" name="'.$this->_id.'_style">';
				foreach ( $font_style as $key => $option ) {
					$output .= '<option value="'.$option.'" '.selected( $value_style, $option, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= '<select class="select-style font-variant" id="'.$this->_id.'_variant" name="'.$this->_id.'_variant">';
				foreach ( $font_variant as $key => $option ) {
					$output .= '<option value="'.$option.'" '.selected( $value_variant, $option, false ).'>'.$option.'</option>';
				}
			$output .= '</select>';
			$output .= '<div class="font-size">';
				$output .= '<input type="hidden" id="'.$this->_id.'_size" name="'.$this->_id.'_size" value="'.$value_size.'">';
				$output .= '<div class="slider" id="'.$this->_id.'_size_slider"></div>';
				$output .= '<div class="slider_value">'.$value_size.$this->_suffix.'</div>';
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';
		$output .= '<script type="text/javascript">jQuery(function() { jQuery( "#'.$this->_id.'_size_slider" ).slider({ value: '.$value_size.' ,  min:'.$this->_min.' , max:'.$this->_max.' , step: '.$this->_increment.' , slide: function( event, ui ) { jQuery(this).siblings(".slider_value").text( ui.value + "'.$this->_suffix.'" ); jQuery(this).siblings("input[type=hidden]").val(ui.value); }}); });</script>';

		return $output;
	}

	/** Back-up Option */
	function backup_button() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';

			// Variables
			$current_list = get_option( $this->_options_prefix . '_theme_options_backup_list' );
			$current_list = explode( ' ', $current_list );
			$current_list = array_filter( $current_list, 'strlen' );

			$css = ( count( $current_list ) > 0 ) ? 'with-list' :  'no-list';

			$output .= '<div class="message '.$css.'">';
				$output .= '<h3 class="even">'.__('Back-up available','zox-news').'</h3>';
				$output .= '<p class="even">'.__('You options have been backed up. You can always restore your options by clicking the <strong>Restore</strong> button below:','zox-news').'</p>';
				$output .= '<ul class="backup-list even">';
				foreach ( $current_list as $backup ) {

					$output .= '<li>'.date( "M d, Y H:i", $backup ).'<a href="#" class="delete-backup" data-backup="'.$backup.'">'.__('Delete','zox-news').'</a><a href="#" class="restore-backup" data-backup="'.$backup.'">'.__('Restore','zox-news').'</a></li>';
				}
				$output .= '</ul>';
				$output .= '<p class="odd">'.__('You currently have not backups. You can create a backup by clicking the <strong>Backup Now</strong> link below:','zox-news').'</p>';
				$output .= '<a href="#" id="backup">'.__('Backup Now','zox-news').'</a>';
			$output .= '</div>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Reset Option */
	function reset_button() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= $this->_desc;
			$output .= '<a href="#" id="reset-options-bottom" title="'.__('Reset Options','zox-news').'">'.__('Reset Options','zox-news').'</a>';
		$output .= '</div>';

		return $output;
	}

	/** Export Option */
	function export() {
		$all_options = wp_load_alloptions();
		$options_data = array();
		foreach( $all_options as $option_name => $this->_value ) {
			if ( substr( $option_name, 0, strlen( $this->_options_prefix ) ) === $this->_options_prefix ) $options_data[$option_name] = $this->_value;
		}

		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="export_field">'.$this->_name.'</label>';
			$output .= '<textarea readonly id="export_field" name="export_field">'.json_encode( $options_data ).'</textarea>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	/** Import Option */
	function import() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="import_field">'.$this->_name.'</label>';
			$output .= '<textarea placeholder="'.$this->_placeholder.'" id="import_field" name="import_field"></textarea>';
			$output .= $this->_desc;
			$output .= '<a href="#" id="import-options" title="'.__('Import Options','zox-news').'">'.__('Import Options','zox-news').'</a>';
		$output .= '</div>';

		return $output;
	}

	/** Code Option */
	function code() {
		$output = '<div class="form-control '.$this->_class.'">';
			$output .= '<label class="name" for="'.$this->_id.'">'.$this->_name.'</label>';
			$output .= '<textarea class="code" placeholder="'.$this->_placeholder.'" id="'.$this->_id.'" name="'.$this->_id.'">'.wp_kses_post( $this->_value ).'</textarea>';
			$output .= $this->_desc;
		$output .= '</div>';

		return $output;
	}

	}
}

/**
* Whitelabel Sidebar Generator
* Used to create dynamic sidebars for your theme or plugin.
*
* @since Whitelabel Theme & Plugin Options 1.1
*
* @param string $url Base folder URI of the options page. This paramenter needs
* to be set for plugins, according with the plugins name. This parameter is
* optional for themes.
*
* @return void
*
*/

if ( ! class_exists( 'WhitelabelSidebars' ) ) {
	class WhitelabelSidebars {

	static $_count = 0;
	public static $_prefix = THEMEPREFIX;
	public $_url;
	public $_folder;

	public function __construct( $url = null, $folder = 'admin' ) {

		$this->_url = ( $url ) ? $url : get_template_directory_uri();
		$this->_folder = $folder;

		WhitelabelSidebars::$_count++;

		if ( WhitelabelSidebars::$_count == 1 ) {

			if ( is_admin() ) {
				add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
				add_action('admin_menu', array($this, 'add_theme_page'));
				add_action('wp_ajax_update_sidebars', array($this, 'update_sidebars'));
				add_action('add_meta_boxes', array($this, 'meta_box'));
				add_action('save_post', array($this, 'save_meta_box_data'));
			}
			add_action('widgets_init', array($this, 'create_sidebars'));
		}

	}

	function load_scripts() {

		global $_wp_admin_css_colors;
		$admin_colors = $_wp_admin_css_colors;
		$color_scheme = $admin_colors[get_user_option('admin_color')]->colors;

		if ( get_current_screen()->id == 'appearance_page_sidebars' ) {

			wp_register_style('curly-google-font-roboto', 'http://fonts.googleapis.com/css?family=Roboto:400,300,700,900', true);
			wp_register_style('curly-sidebars', $this->_url.'/'.$this->_folder.'/css/sidebars.css', null,  null, null);
			wp_register_script('curly-sidebars', $this->_url.'/'.$this->_folder.'/js/sidebars.js', array('jquery'), null, true);

			if ( ! wp_script_is( 'curly-google-font-roboto', 'enqueued' ) ) {
				wp_enqueue_style( 'curly-google-font-roboto' );
			}

			if ( ! wp_script_is( 'curly-google-font-roboto', 'enqueued' ) ) {
				wp_enqueue_style( 'curly-sidebars' );
			}

			if ( ! wp_script_is( 'curly-google-font-roboto', 'enqueued' ) ) {
				wp_enqueue_script( 'curly-sidebars' );
			}

			$js_data = array(
				__('Remove','zox-news'),
				__('Are you sure you want to delete this sidebar?','zox-news'),
				__('Sidebar name cannot be empty. Please provide a valid name for your sidebar.','zox-news'),
				__('You already have a sidebar with that name. Please provide a valid name for your sidebar.','zox-news'),
				__('Your sidebar has been succesfully created.','zox-news'),
				__('You currently have no sidebars created. <br>Use the form above to create your first sidebar.','zox-news')
			);

			wp_localize_script('curly-sidebars', 'js_data', $js_data);

			$color_scheme = '
				#sidebars-wrapper input[type=submit],
				#sidebar-list li a:hover{
					background-color: '.$color_scheme[3].';
					color: #fff;
				}';

			wp_add_inline_style('curly-sidebars', $color_scheme);
		}
	}

	function update_sidebars() {

		$name 	= sanitize_text_field( $_POST['name'] );
		$id 	= sanitize_text_field( $_POST['id'] );
		$method = sanitize_text_field( $_POST['method'] );

		$sidebars 	= $this->get_sidebars();
		$count 		= $this->get_sidebars_count() + 1;

		if ( $method == 'update' ) {

			if ( !empty($name) ) {

				if ( !$sidebars ) {

					$sidebars = array( $count => $name );
					$sidebars = json_encode($sidebars);
					update_option( self::$_prefix . '_sidebars_list' , $sidebars );
					update_option( self::$_prefix . '_sidebars_list_count' , $count );

					echo json_encode( array( $count, $name ) );

				} else {

					if ( !in_array( $name , $sidebars ) ) {

						$sidebars[$count] = $name ;
						$sidebars = json_encode($sidebars);
						update_option( self::$_prefix . '_sidebars_list' , $sidebars );
						update_option( self::$_prefix . '_sidebars_list_count' , $count );

						echo json_encode( array( $count, $name ) );

					} else {
						echo 'duplicate';
					}
				}

			} else {
				echo 'empty';
			}

		}

		if ( $method == 'delete' ) {
			unset( $sidebars[$id] );
			$sidebars = json_encode($sidebars);
			update_option( self::$_prefix . '_sidebars_list' , $sidebars );
			echo 'success';
		}

		die();
	}

	function add_theme_page(){
	     add_theme_page( 'themes.php', __('Sidebars', 'zox-news'), __('Sidebars', 'zox-news'), 'manage_options', 'sidebars', array($this, 'add_theme_page_cb'));
	}

	function add_theme_page_cb( $html = null ) {

		$sidebars = $this->get_sidebars();

		$html .= '<div id="sidebars-wrapper">';
			$html .= '<h1>'.__('Sidebars', 'zox-news').'</h1>';
			$html .= '<form method="post" id="add-sidebar" action="">';
				$html .= '<input type="text" id="add-sidebar-field" placeholder="'.__('Enter new sidebar name','zox-news').'">';
				$html .= '<input type="submit" id="add-sidebar-button" value="'.__('Add Sidebar','zox-news').'">';
			$html .= '</form>';
			$html .= '<div id="messages"></div>';
			$html .= '<h3>'.__('Sidebar List','zox-news').'</h3>';
			$html .= '<ul id="sidebar-list">';

			if ( $sidebars ) {

				foreach ($sidebars as $id => $name) {
					$html .= '<li>'.$name.' <code>[dynamic-sidebar id="'.$id.'"]</code><a href="#" data-sidebar-id="'.$id.'">'.__('Remove','zox-news').'</a></li>';
				}

			} else {
				$html .= '<li id="no-sidebar">'.__('You currently have no sidebars created. <br>Use the form above to create your first sidebar.','zox-news').'</li>';
			}

			$html .= '</ul>';
		$html .= '</div>';

		echo html_entity_decode($html);
	}

	function get_sidebars() {
		$sidebars = get_option( self::$_prefix . '_sidebars_list' );
		$sidebars = json_decode( $sidebars , true);

		return $sidebars;
	}

	function get_sidebars_count() {
		$count = get_option( self::$_prefix . '_sidebars_list_count', 0 );

		return $count;
	}

	function create_sidebars() {
		$sidebars = $this->get_sidebars();
		if ( $sidebars ) {
			foreach ($sidebars as $id => $name) {
				register_sidebar( array(
				    'name'         => $name,
				    'id'           => 'dynamic-sidebar-'.$id,
				    'before_widget'=> '<aside id="%1$s" class="dynamic-sidebar-widget widget %2$s">',
				    'after_widget' => '</aside>'
				) );
			}
		}

	}

	public static function sidebar( $default = null, $logic = false ) {

		global $post;

		$sidebar = get_post_meta( $post->ID, self::$_prefix . '_dynamic_sidebar', true);

		if ( $logic === true ) {
			if ( $sidebar && is_active_sidebar( $sidebar ) ) {
				dynamic_sidebar( $sidebar );
			} elseif( is_active_sidebar( $default ) ) {
				dynamic_sidebar( $default );
			} else {
				return;
			}
		} else {
			if ( $sidebar ) {
				dynamic_sidebar( $sidebar );
			} else {
				dynamic_sidebar( $default );
			}
		}
	}

	function sidebar_shortcode( $atts ) {

		ob_start();
		dynamic_sidebar( 'sidebar_'.$atts['id'] );
		$sidebar = ob_get_contents();
		ob_end_clean();

		return $sidebar;
	}

	public function meta_box() {
		$screens = array( 'post', 'page' );

			foreach ( $screens as $screen ) {
				add_meta_box('sidebar_metabox', __( 'Sidebar', 'zox-news' ), array($this, 'meta_box_callback'), $screen, 'side');
			}

	}

	public function meta_box_callback( $post ) {

		wp_nonce_field( 'sidebar_meta_box', 'sidebar_meta_box_nonce' );

		$default_sidebar = get_post_meta( $post->ID, self::$_prefix . '_dynamic_sidebar', true );

		global $wp_registered_sidebars;

		echo '<p><strong><label>'.__('Choose Sidebar:','zox-news').'</label></strong></p>';
		echo '<select name="sidebar" id="sidebar">';
		echo '<option>'.__('Choose Sidebar','zox-news').'</option>';
		foreach ( $wp_registered_sidebars as $value ) {
			echo '<option value="'.$value['id'].'" '.selected($default_sidebar, $value['id']).'>'.$value['name'].'</option>';
		}
		echo '</select>';
		echo '<p>'.__('Choose a custom sidebar for this page','zox-news').'</p>';
	}

	public function save_meta_box_data( $post_id ) {

		if ( ! isset( $_POST['sidebar_meta_box_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['sidebar_meta_box_nonce'], 'sidebar_meta_box' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['sidebar'] ) ) {
			return;
		}

		$data = sanitize_text_field( $_POST['sidebar'] );
		update_post_meta( $post_id, self::$_prefix . '_dynamic_sidebar', $data );
	}

}
}
?>