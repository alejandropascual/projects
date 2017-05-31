<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

//Responsive mobile
$mobile_options = get_option('aps_op_mobile');

$menu_collapse_width = $mobile_options['menu_collapse_width'];
if ( !$menu_collapse_width ) $menu_collapse_width = '769px';
//echo '<pre>'; print_r( $mobile_options ); echo '</pre>';
//echo '<pre>'; print_r( $menu_collapse_width); echo '</pre>';

$sidebars_collapse_width = $mobile_options['sidebars_collapse_width'];
if ( !$sidebars_collapse_width ) $sidebars_collapse_width = '769px';


// LESS COMPILER
require_once( APS_THEME_DIR . '/includes/vendors/lessphp/lessc.inc.php' );
$less = new lessc;

$file1 = '
    @menu_collapse_width:           '.$menu_collapse_width.';
    @sidebars_collapse_width:       '.$sidebars_collapse_width.';
';
$file2 = file_get_contents(APS_THEME_DIR.'/includes/stylesheets/less/layout-style-compile-mobile.less');

global $aps_config;
$style_complete = $less->compile($file1.$file2);
$aps_config['style'] = $style_complete;