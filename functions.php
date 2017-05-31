<?php


// No direct access
if (!defined('ABSPATH')) {exit;}


// CONSTANTS
//====================================================================

if ( ! defined( 'APS_PROJECTS_VERSION' ) ) {
    define( 'APS_PROJECTS_VERSION', '1.0' );
}

if ( !defined( 'APS_THEME_DIR' ) ) {
	define( 'APS_THEME_DIR', get_template_directory() );
}

if ( !defined( 'APS_THEME_URI' ) ) {
	define( 'APS_THEME_URI', get_template_directory_uri() );
}

if ( !defined( 'APS_CHILD_THEME_DIR' ) ) {
	define( 'APS_CHILD_THEME_DIR', get_stylesheet_directory() );
}

if ( !defined( 'APS_CHILD_THEME_URI' ) ) {
	define( 'APS_CHILD_THEME_URI', get_stylesheet_directory_uri() );
}

if ( !defined( 'APS_DIR' ) ) {
	define( 'APS_DIR', trailingslashit( APS_THEME_DIR ) . basename( dirname( __FILE__ ) ) );
}

if ( !defined( 'APS_URI' ) ) {
	define( 'APS_URI', trailingslashit( APS_THEME_URI ) . basename( dirname( __FILE__ ) ) );
}

if ( !defined( 'APS_DYNAMIC_CSS_DIR' ) ) {
	define( 'APS_DYNAMIC_CSS_DIR', '/dynamic_theme');
}

if ( !defined( 'APS_DYNAMIC_CSS_NAME' ) ) {
	define( 'APS_DYNAMIC_CSS_NAME', 'base_theme.css');
}

if ( !defined( 'APS_DYNAMIC_CSS_MOBILE_NAME' ) ) {
    define( 'APS_DYNAMIC_CSS_MOBILE_NAME', 'base_theme_mobile.css');
}



// LANGUAGE DOMAIN
//====================================================================


/* Set languige zone */
if ( !defined( 'LANGUAGE_THEME' ) ) {
    define( 'LANGUAGE_THEME', 'projects' );
}

load_theme_textdomain( 'projects', get_template_directory() . '/languages' );


// GLOBALS
//====================================================================

// Variables globales
global $aps_config;



// AFTER SETUP THEME
//====================================================================

//add_action( 'after_setup_theme', 'aps_load_theme_domain', 15 );

function aps_theme_setup()
{

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

    // Allow shortcodes in widget text
    add_filter('widget_text', 'do_shortcode');

    // create upload dir
    wp_upload_dir();

    // Content Width
    if (!isset( $content_width )) $content_width = '600px';
	
	// THUMBNAILS
	add_theme_support( 'post-thumbnails' );

	// SIDEBARS
	require( APS_THEME_DIR . '/includes/php/sidebars.php' );

    // AJAX
    require( APS_THEME_DIR . '/includes/php/ajax-theme.php' );
    require( APS_THEME_DIR . '/includes/php/ajax-options.php' );

    // TAGS
    require( APS_THEME_DIR . '/includes/php/template-content.php' );
    require( APS_THEME_DIR . '/includes/php/template-navigation.php' );
    require( APS_THEME_DIR . '/includes/php/template-layout-tags.php' );
    require( APS_THEME_DIR . '/includes/php/template-tags.php' );
    require( APS_THEME_DIR . '/includes/php/template-functions.php' );
    require( APS_THEME_DIR . '/includes/php/template-tags-project.php' );

    // HOOKS
    require( APS_THEME_DIR . '/includes/php/header-hooks.php' );

    // IMAGES
    require( APS_THEME_DIR . '/includes/php/aq_resizer.php' );
    require( APS_THEME_DIR . '/includes/php/core-functions.php' );

    // UTILS functions
    require( APS_THEME_DIR . '/includes/php/various.php' );

	// SETTINGS
    require( APS_THEME_DIR . '/includes/php/class-htmlhelper.php' );
	require( APS_THEME_DIR . '/includes/php/settings-data.php' );
	require( APS_THEME_DIR . '/includes/php/settings-helpers.php' );
	require( APS_THEME_DIR . '/includes/php/settings-hooks.php' );
    require( APS_THEME_DIR . '/includes/php/settings-setup-defaults.php' );

	// PAGE METABOX
	require( APS_THEME_DIR . '/includes/php/class-metabox.php' );
	new APSMetaBox( APS_THEME_DIR.'/includes/php/page-metabox.php' );
	
	// ENQUEUE SCRIPTS
	require( APS_THEME_DIR . '/includes/php/enqueue-scripts.php' );

    // WALKER MEGA MENU
    require( APS_THEME_DIR . '/includes/php/class-mega-menu.php' );
    new ApsMegaMenu();


    if (is_admin())
    {
        // IMPORTER
        require( APS_THEME_DIR . '/includes/plugins/importer/projects-importer.php' );

        // PLUGIN ACTIVATION
        require( APS_THEME_DIR . '/includes/php/activate-plugins.php' );

        // WELCOME
        //require( APS_THEME_DIR . '/includes/php/welcome.php' );

        // ACCIONES BULK VARIAS
        //require( APS_THEME_DIR . '/includes/php/acciones-bulk-varias.php' );
    }
}

add_action( 'after_setup_theme', 'aps_theme_setup', 15 );
