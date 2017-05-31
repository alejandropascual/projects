<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

//Este es el estilo que voy a usar
global $style;
$style = get_option('aps_op_theme_style');
$style = apply_filters('theme_style_to_calculate', $style);


if ( !function_exists('get_style_option') ):
	function get_style_option($option)
	{
		global $style;
		if (isset($style[$option]) && $style[$option]!='')
			return $style[$option];
		else
			return '0'; //Para que no de problemas el compilador less
	}
endif;

if ( !function_exists('get_style_patron') ):
    function get_style_patron($option)
    {
        $pattern_path = get_template_directory_uri().'/includes/stylesheets/images/patterns/';
        $value = get_style_option($option);
        if ($value == '0') return 'none';
        return 'url("'.$pattern_path.$value.'.png")';
    }
endif;



//Fuentes
$heading_font = get_style_option('heading_font');
$heading_font = preg_replace('/-websafe/', '', $heading_font);
$heading_font = preg_replace('/websafe/', '', $heading_font);
$heading_font = ($heading_font=='') ? 'Helvetica, sans-serif' : $heading_font;

$body_font = get_style_option('body_font');
$body_font = preg_replace('/-websafe/', '', $body_font);
$body_font = preg_replace('/websafe/', '', $body_font);
$body_font = ($heading_font=='') ? 'Helvetica, sans-serif' : $body_font;

$blog_title_font = get_style_option('blog_title_font');
$blog_title_font = preg_replace('/-websafe/', '', $blog_title_font);
$blog_title_font = preg_replace('/websafe/', '', $blog_title_font);
$blog_title_font = ($blog_title_font=='') ? 'Helvetica, sans-serif' : $blog_title_font;



// LESS COMPILER
require_once( APS_THEME_DIR . '/includes/vendors/lessphp/lessc.inc.php' );
$less = new lessc;

$file1 = '
	@heading_font: 					'.$heading_font.';
	@body_font:						'.$body_font.';
	@blog_title_font:				'.$blog_title_font.';
	
	@general_backcolor:				'.get_style_option("general_backcolor").';
	@general_pattern:				'.get_style_patron("general_pattern").';
	@general_pattern_scroll:		'.get_style_option("general_pattern_scroll").';

	@font_size_h1:                  '.get_style_option("font_size_h1").';
	@font_size_h2:                  '.get_style_option("font_size_h2").';
	@font_size_h3:                  '.get_style_option("font_size_h3").';
	@font_size_h4:                  '.get_style_option("font_size_h4").';
	@font_size_h5:                  '.get_style_option("font_size_h5").';
	@font_size_h6:                  '.get_style_option("font_size_h6").';

	@header_top_font_size:			'.get_style_option("header_top_font_size").';
	@header_top_color:				'.get_style_option("header_top_color").';
	@header_top_menu_hover_color:	'.get_style_option("header_top_menu_hover_color").';
	@header_top_backcolor:			'.get_style_option("header_top_backcolor").';
	@header_top_link_color:			'.get_style_option("header_top_link_color").';
	@header_top_hoverlink_color:	'.get_style_option("header_top_hoverlink_color").';
	@header_top_division_color:		'.get_style_option("header_top_division_color").';
	@header_top_pattern:			'.get_style_patron("header_top_pattern").';

	@header_bottom_font_size:		'.get_style_option("header_bottom_font_size").';
	@header_bottom_color:			'.get_style_option("header_bottom_color").';
	@header_bottom_menu_hover_color:'.get_style_option("header_bottom_menu_hover_color").';
	@header_bottom_backcolor:		'.get_style_option("header_bottom_backcolor").';
	@header_bottom_link_color:		'.get_style_option("header_bottom_link_color").';
	@header_bottom_hoverlink_color:	'.get_style_option("header_bottom_hoverlink_color").';
	@header_bottom_division_color:	'.get_style_option("header_bottom_division_color").';
	@header_bottom_border_color:	'.get_style_option("header_bottom_border_color").';
	@header_bottom_pattern:			'.get_style_patron("header_bottom_pattern").';

	@left_font_size:   				'.get_style_option("left_font_size").';
	@left_backcolor:				'.get_style_option("left_backcolor").';
	@left_heading_color:			'.get_style_option("left_heading_color").';
	@left_color:					'.get_style_option("left_color").';
	@left_border_color:				'.get_style_option("left_border_color").';
	@left_border_color_line:		'.get_style_option("left_border_color_line").';
	@left_border_color_menu:		'.get_style_option("left_border_color_menu").';
	@left_widget_backcolor:         '.get_style_option("left_widget_backcolor").';
	@left_widget_margin:            '.get_style_option("left_widget_margin").';
	@left_link_color:				'.get_style_option("left_link_color").';
	@left_hoverlink_color:			'.get_style_option("left_hoverlink_color").';
	@left_pattern:					'.get_style_patron("left_pattern").';
	@left_pattern_scroll:			'.get_style_option("left_pattern_scroll").';

    @main_font_size:   		        '.get_style_option("main_font_size").';
    @main_meta_font_size:   		'.get_style_option("main_meta_font_size").';
	@main_backcolor:				'.get_style_option("main_backcolor").';
	@main_heading_color:			'.get_style_option("main_heading_color").';
	@main_color:					'.get_style_option("main_color").';
	@main_hig_backcolor:			'.get_style_option("main_hig_backcolor").';
	@main_hig_color:				'.get_style_option("main_hig_color").';
	@main_hig_hover_color:			'.get_style_option("main_hig_hover_color").';
	@main_alt_backcolor:			'.get_style_option("main_alt_backcolor").';
	@main_alt_color:				'.get_style_option("main_alt_color").';
	@main_window_border_color:		'.get_style_option("main_window_border_color").';
	@main_border_color:				'.get_style_option("main_border_color").';
	@main_link_color:				'.get_style_option("main_link_color").';
	@main_hoverlink_color:			'.get_style_option("main_hoverlink_color").';
	@main_link_back_color:			'.get_style_option("main_link_back_color").';
	@main_pattern:					'.get_style_patron("main_pattern").';
	@main_pattern_scroll:			'.get_style_option("main_pattern_scroll").';

	@right_font_size:				'.get_style_option("right_font_size").';
	@right_backcolor:				'.get_style_option("right_backcolor").';
	@right_heading_color:			'.get_style_option("right_heading_color").';
	@right_color:					'.get_style_option("right_color").';
	@right_border_color:			'.get_style_option("right_border_color").';
	@right_border_color_line:		'.get_style_option("right_border_color_line").';
	@right_border_color_menu:		'.get_style_option("right_border_color_menu").';
	@right_widget_backcolor:        '.get_style_option("right_widget_backcolor").';
	@right_widget_margin:           '.get_style_option("right_widget_margin").';
	@right_link_color:				'.get_style_option("right_link_color").';
	@right_hoverlink_color:			'.get_style_option("right_hoverlink_color").';
	@right_pattern:					'.get_style_patron("right_pattern").';
	@right_pattern_scroll:			'.get_style_option("right_pattern_scroll").';
	
	@footer_font_size:				'.get_style_option("footer_font_size").';
	@footer_backcolor:				'.get_style_option("footer_backcolor").';
	@footer_heading_color:			'.get_style_option("footer_heading_color").';
	@footer_color:					'.get_style_option("footer_color").';
	@footer_border_color:			'.get_style_option("footer_border_color").';
	@footer_border_color_line:		'.get_style_option("footer_border_color_line").';
	@footer_border_color_menu:		'.get_style_option("footer_border_color_menu").';
	@footer_link_color:				'.get_style_option("footer_link_color").';
	@footer_hoverlink_color:		'.get_style_option("footer_hoverlink_color").';
	@footer_pattern:				'.get_style_patron("footer_pattern").';
	@footer_pattern_scroll:			'.get_style_option("footer_pattern_scroll").';

	@socket_font_size:				'.get_style_option("socket_font_size").';
	@socket_backcolor:				'.get_style_option("socket_backcolor").';
	@socket_color:					'.get_style_option("socket_color").';
	@socket_division_color:			'.get_style_option("socket_division_color").';
	@socket_border_color:			'.get_style_option("socket_border_color").';
	@socket_link_color:				'.get_style_option("socket_link_color").';
	@socket_hoverlink_color:		'.get_style_option("socket_hoverlink_color").';
	@socket_pattern:				'.get_style_patron("socket_pattern").';
	@socket_pattern_scroll:			'.get_style_option("socket_pattern_scroll").';
	
';
$file2 = file_get_contents(APS_THEME_DIR.'/includes/stylesheets/less/layout-style-compile.less');

//echo '<pre>'; print_r($file1); echo '</pre>';
global $aps_config;
$style_complete = $less->compile($file1.$file2);
$aps_config['style'] = $style_complete;
//$aps_config['style'] = '{}';