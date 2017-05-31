<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


////////////////////////////////////////////////////
//SIDEBARS - widgets area
////////////////////////////////////////////////////

global $aps_config;
$aps_config['sidebars'] = array();

if (!function_exists('aps_register_sidebars')) :

function aps_register_sidebars()
{

	global $aps_config;
	
	//Sidebars fijos siempre
	for ($i=1; $i<=10; $i++)
	{

        $aps_config['sidebars'][] = array(
			'name'          => __( 'Widget Area ', LANGUAGE_THEME ).$i,
			'id'            => 'widgets_'.$i,
			'description'   => __( 'You can use this widget area in the sidebar or in the footer. Just select in the LAYOUT where you want this to appear.', LANGUAGE_THEME ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>'
		);
	}

	//Registrarlos
	foreach($aps_config['sidebars'] as $sidebar)
	{
		register_sidebar($sidebar);
	}


}
endif;

add_action( 'widgets_init', 'aps_register_sidebars' );


// Allow shortcodes en widgets
add_filter( 'widget_text', 'do_shortcode' );

//Widgets
$aps_widgets = array(
    'contact-info',
    'list-posts',
    'list-projects',
    'custom-menu',
    'tabs-projects',
    'tabs-posts',
    'logo-title',
    'ads_2_2',
    'ads_1_1'
);
require_once( APS_THEME_DIR.'/includes/widgets/widgets-utils.php');
foreach($aps_widgets as $aps_widget){
    include_once( APS_THEME_DIR.'/includes/widgets/'.$aps_widget.'.php');
}