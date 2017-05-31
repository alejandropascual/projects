<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

////////////////////////////////////////
// FRONT END
////////////////////////////////////////

// ENQUEUE SCRIPTS

if ( ! function_exists( 'aps_theme_enqueue_scripts' ) ) :
  function aps_theme_enqueue_scripts() {

        // MODERNIZR
        wp_enqueue_script( 'vendor-modernizr', get_template_directory_uri() . '/includes/vendors/modernizr.js', NULL ,NULL,false);

        if ( is_singular() ) { wp_enqueue_script( 'comment-reply' ); }

        // SUPERFISH
        wp_enqueue_script( 'vendor-hoverintent', APS_THEME_URI . '/includes/vendors/superfish/hoverIntent.js', array( 'jquery' ),NULL,true);
        wp_enqueue_script( 'vendor-superfish', APS_THEME_URI . '/includes/vendors/superfish/superfish.min.js', array( 'jquery', 'vendor-hoverintent' ),NULL,true);

        //IMAGES LOADED
        wp_enqueue_script( 'vendor-imagesloaded', APS_THEME_URI . '/includes/vendors/imagesloaded/imagesloaded.js', array( 'jquery' ),NULL,true);

        // ISOTOPE
        wp_enqueue_script( 'vendor-isotope', APS_THEME_URI . '/includes/vendors/isotope/isotope.js', array( 'jquery','vendor-imagesloaded' ),NULL,true);

        // COLLAGE-PLUS
        wp_enqueue_script( 'vendor-removewhitespace', APS_THEME_URI . '/includes/vendors/collageplus/jquery.removeWhitespace.min.js', array( 'jquery' ),NULL,true);
        wp_enqueue_script( 'vendor-collageplus', APS_THEME_URI . '/includes/vendors/collageplus/jquery.collagePlus.min.js', array( 'jquery' ),NULL,true);

        //MAGNIFIC POPUP
        wp_enqueue_script( 'vendor-magnificpopup', APS_THEME_URI . '/includes/vendors/magnificpopup/jquery.magnific-popup.min.js', array( 'jquery' ),NULL,true);

        // EVERSLIDER
        wp_enqueue_script('vendor-easing', APS_THEME_URI . '/includes/vendors/everslider/js/jquery.easing.min.js', array('jquery'),NULL,true);
        wp_enqueue_script('vendor-mousewheel', APS_THEME_URI . '/includes/vendors/everslider/js/jquery.mousewheel.js', array('jquery'),NULL,true);
        wp_enqueue_script('vendor-everslider', APS_THEME_URI . '/includes/vendors/everslider/js/jquery.everslider.min.js', array('jquery','vendor-easing','vendor-mousewheel'),NULL,true);

        //ROYALSLIDER, lo llamo igual que el del plugin y lo echo por si el plugin no esta activado
        wp_enqueue_script( 'new-royalslider-main-js', APS_THEME_URI . '/includes/vendors/royalslider/jquery.royalslider.min.js', array( 'jquery' ),NULL,true);
        //wp_register_script( 'new-royalslider-main-js', NEW_ROYALSLIDER_PLUGIN_URL . 'lib/royalslider/jquery.royalslider.min.js', array('jquery'), NEW_ROYALSLIDER_WP_VERSION, true);
        //wp_enqueue_script( 'new-royalslider-main-js'); //El del plugin

        //MAP
        wp_register_script( 'googlemap', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false',NULL,NULL,true);
        wp_register_script( 'googlemap-styles', APS_THEME_URI . '/includes/js/map-styles.js', NULL, NULL,true);
        wp_register_script( 'aps-maps', get_template_directory_uri() . '/includes/js/aps-maps.js', array( 'jquery', 'googlemap', 'googlemap-styles' ),NULL,true);

        // MIS SCRIPTS
        wp_enqueue_script( 'aps-theme', get_template_directory_uri() . '/includes/js/aps-theme.js', array( 'jquery', 'vendor-isotope', 'vendor-superfish' ),NULL,true);

  }
endif;

add_action( 'wp_enqueue_scripts', 'aps_theme_enqueue_scripts' );


// ENQUEUE STYLES

if ( ! function_exists( 'aps_theme_enqueue_styles' ) ) :
  function aps_theme_enqueue_styles()
  {
		//BOOTSTRAP ADAPTADO
		wp_enqueue_style( 'aps-base', get_template_directory_uri() . '/includes/stylesheets/css/bootstrap.css' );

		// EL DYNAMIC CSS
		$upload_url = wp_upload_dir();
		$upload_url = $upload_url['baseurl'];
		wp_enqueue_style( 'aps-frontend-dynamic', $upload_url . APS_DYNAMIC_CSS_DIR.'/'.APS_DYNAMIC_CSS_NAME );
        wp_enqueue_style( 'aps-frontend-dynamic-mobile', $upload_url . APS_DYNAMIC_CSS_DIR.'/'.APS_DYNAMIC_CSS_MOBILE_NAME );

        // MAGNIFIC POPUP
        wp_enqueue_style( 'vendor-magnificpopup', get_template_directory_uri() . '/includes/vendors/magnificpopup/magnific-popup.css' );

        //ROYALSLIDER
        wp_register_style( 'new-royalslider-core-css', get_template_directory_uri() . '/includes/vendors/royalslider/royalslider.css' );
        wp_register_style( 'rsMinW-css', get_template_directory_uri() . '/includes/vendors/royalslider/skins/minimal-white/rs-minimal-white.css', array('new-royalslider-core-css'));
	    wp_enqueue_style( 'rsMinW-css');

		// Dashicons and Font awesome
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style( 'aps-font-awesome', get_template_directory_uri() . '/includes/stylesheets/css/font-awesome.min.css' );
  }
endif;

add_action( 'wp_enqueue_scripts', 'aps_theme_enqueue_styles' );




// PRELOADER

function aps_first_enqueue_scripts()
{
    if (is_page())
    {
        $page_id = get_queried_object_id();
        $use_preloader = get_post_meta( $page_id, 'use_preloader', true);
        if ($use_preloader == 'yes') {
            wp_enqueue_style( 'vendor-pace', get_template_directory_uri() . '/includes/vendors/pace/pace.css' );
            wp_enqueue_script( 'vendor-pace', get_template_directory_uri() . '/includes/vendors/pace/pace.js', NULL ,NULL,true);
        }
    }
}

add_filter('wp_enqueue_scripts', 'aps_first_enqueue_scripts', 0);



function aps_dame_googlefonts()
{
    $styles = array(
        "Advent Pro" => "300,400,600",
        "Alef" => "400,700",
        "Alice" => "",
        "Allerta" => "",
        "Arvo" => "400,700",
        "Anaheim" => "",
        "Antic" => "",
        "Bangers" => "",
        "Bitter" => "400,700",
        "Cabin" => "400,700",
        "Cardo" => "400,700",
        "Carme" => "",
        "Coda" => "400,800",
        "Coustard" => "400,900",
        "Codystar" => "300,400",
        "Gruppo" => "",
        "Damion" => "",
        "Dancing Script" => "",
        "Droid Sans" => "400,700",
        "Droid Serif" => "400,700",
        "EB Garamond" => "",
        "Exo" => "100,300,500,800",
        "Fjalla One" => "",
        "Fjord One" => "",
        "Gafata" => "",
        "Gudea" => "400,700",
        "Inconsolata" => "400,700",
        "Josefin Sans" => "100,300,400,700",
        "Josefin Slab" => "",
        "Kameron" => "",
        "Kreon" => "",
        "Lato" => "",
        "Lobster" => "",
        "League Script" => "",
        "Mate SC" => "",
        "Mako" => "",
        "Merriweather" => "",
        "Metrophobic" => "",
        "Molengo" => "",
        "Muli" => "",
        "Nobile" => "",
        "News Cycle" => "",
        "Open Sans" => "300,400,600",
        "Orbitron" => "",
        "Orienta" => "",
        "Oxygen Mono" => "",
        "Oswald" => "",
        "Pacifico" => "",
        "Pathway Gothic One" => "",
        "Poly" => "",
        "Podkova" => "",
        "PT Sans" => "",
        "PT Sans Narrow" => "",
        "Quattrocento" => "",
        "Questrial" => "",
        "Quicksand" => "",
        "Raleway" => "",
        "Rationale" => "",
        "Ruda" => "",
        "Salsa" => "",
        "Share Tech Mono" => "",
        "Sintony" => "",
        "Sunshiney" => "",
        "Signika Negative" => "",
        "Tangerine" => "",
        "Terminal Dosis" => "",
        "Tenor Sans" => "",
        "Text Me One" => "",
        "Titillium Web" => "",
        "Varela Round" => "",
        "Voltaire" => "",
        "Wire One" => "",
        "Yellowtail" => ""
    );
    return $styles;
}


function googlefont_with_styles( $font )
{
    $styles = aps_dame_googlefonts();

    if ( isset($styles[$font]) && $styles[$font] != '' ){
        $font = $font.':'.$styles[$font];
    }
    $font = str_replace(' ','+',$font);
    return $font;
}



//Para imprimir las fuentes que hacen falta
function aps_print_other_scripts()
{

	//FUENTES
	$style = get_option('aps_op_theme_style');
	
	$heading_font = $style['heading_font'];
	$body_font = $style['body_font'];
	$title_blog_font = $style['blog_title_font'];

    //Todas las fuentes a la misma vez
    $g_fonts = '';

	//Es google font
	if (!preg_match('/websafe/', $heading_font)){
        $g_fonts = googlefont_with_styles($heading_font);
	}
	
	//Es google font
	if (!preg_match('/websafe/', $body_font)) {
        $font = googlefont_with_styles($body_font);
        $g_fonts .= (($g_fonts != '') ? '|' : '') . $font;
    }
		
	//Es google font
	if (!preg_match('/websafe/', $title_blog_font)) {
        $font = googlefont_with_styles($title_blog_font);
        $g_fonts .= (($g_fonts != '') ? '|' : '') . $font;
    }
    if ($g_fonts != '') {
        echo '<link id="style_google_fonts" rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=' . $g_fonts . '">';
    }
	//TRACKING CODE
	echo aps_get_option('tracking_code');
	
	//CUSTOM STYLE
	echo '<style>'.aps_get_option('extra_style').'</style>';
	
}


add_action ( 'wp_enqueue_scripts' , 'aps_print_other_scripts');





////////////////////////////////////////
// BACK END
////////////////////////////////////////

// ENQUEUE SCRIPTS - STYLES

function aps_admin_enqueue_scripts()
{
	//Color picker
	wp_enqueue_style( 'wp-color-picker' );
	
	//Media library
	wp_enqueue_media();
	wp_enqueue_script('custom-header');
	

	//Para que se carguen siempre en cualquier parte
	wp_enqueue_script ('jquery');
	wp_enqueue_script ('jquery-ui-core');
	wp_enqueue_script ('jquery-ui-widget');
	wp_enqueue_script ('jquery-ui-sortable');
	wp_enqueue_script ('jquery-ui-draggable');
	wp_enqueue_script ('jquery-ui-resizable');
	wp_enqueue_script ('jquery-ui-button');
	wp_enqueue_script ('jquery-ui-datepicker');
	
	//jQuery ui general
	wp_enqueue_style('jquery-ui-css', APS_THEME_URI.'/includes/vendors/jquery-ui/jquery-ui.css');
	
	//jquery ui para datepicker
	wp_enqueue_style('jquery-ui-custom-css', APS_THEME_URI.'/includes/vendors/jquery-ui/jquery-ui-custom.css');
	
	//Googlemap
	wp_enqueue_script('googlemap', 'http://maps.google.com/maps/api/js?sensor=true', array('jquery') );
	wp_enqueue_script( 'jquery-googlemap', APS_THEME_URI.'/includes/vendors/jquery-googlemap/jquery.ui.map.full.min.js', array('googlemap','jquery') );

	//Admin all
	wp_register_script('aps-backend', APS_THEME_URI.'/includes/js/backend.js', array('jquery','wp-color-picker','custom-header'));
	wp_enqueue_script('aps-backend');
	
	wp_localize_script( 'aps-backend', 'locals', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
	//Style
	wp_register_style('aps-backend', APS_THEME_URI.'/includes/stylesheets/css/backend.css');
	wp_enqueue_style('aps-backend');
	
	//Font awesome
	//wp_register_style( 'aps-font-awesome', 'http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
	//wp_enqueue_style( 'aps-font-awesome' );
}

add_action( 'admin_enqueue_scripts', 'aps_admin_enqueue_scripts', 15 );