<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

// UTILIDADES PARA EL FRONT END, PARA FORMAR EL LAYOUT


// LOG datos pagina, solo pruebas
//====================================================================

function aps_log_datos_pagina()
{

	$lista = '404,page,single,archive,attachment,front_page,home,search,category,tag,tax';
	$lista = preg_split('/,/', $lista);
	
	echo '<!-- ';
	foreach($lista as $el)
	{
		$function = 'is_'.$el;
		echo ' is_'.$el.'['; print_r($function()); echo '] ';
	}
    echo '<br>';
    echo 'is_post_type_archive['.is_post_type_archive('aps_project').']';
    echo 'is_tax_project_category['.is_tax('project_category').']';
    echo 'is_tax_project_skill['.is_tax('project_skill').']';
    echo 'is_tax_project_tag['.is_tax('project_tag').']';
	
	if (is_page() || is_single()){
		global $post;
		echo '<p>'.$post->post_title.'</p>';
	}
	
	global $aps_config;
	echo '<pre>'; print_r($aps_config['layout']); echo '</pre>';	
	echo ' -->';
}
if (WP_DEBUG)
{
	add_action('wp_head','aps_log_datos_pagina');
}



// Saber el layout actual desde el header.php
//====================================================================

function aps_get_current_layout()
{
	global $post;

    //Blog es el front page
    if ( is_front_page() && is_home() )
    {
        $layout = 'layout_default_blog';
    }
    //Pagina front page
	else if (is_front_page())
    {
		$frontpage_id = get_option('page_on_front');
		$layout = get_post_meta($frontpage_id,'layout_id',true);
		
	}
    //pagina blog especifica
    else if (is_home())
    {
		$page_for_posts = get_option('page_for_posts');
		$layout = get_post_meta($page_for_posts,'layout_id',true);
			//is_post_type_archive()
	}
    //List of projects
    else if (is_post_type_archive('aps_project'))
    {
        $layout =  'layout_default_aps_project_list';
    }
    //Archive projects-taxonomy
    else if (is_tax('project_category') || is_tax('project_skill') || is_tax('project_tag'))
    {
        $layout =  'layout_default_aps_project_archive';
    }
    //pagina o single post
    else if (is_page() || is_single())
    {
		$layout = get_post_meta($post->ID,'layout_id',true);

        if (empty($layout))
        {
            if (is_single()) {
                if ( get_post_type()=='aps_project') {
                    $layout = 'layout_default_aps_project';
                } else {
                    $layout = 'layout_default_post';
                }

            }
            else if (is_page()) {
                $layout = 'layout_default_page';
            }
        }
	}
    else if (is_archive())
    {
        $layout = 'layout_default_archive';
        //$layout_id = aps_get_option($layout);
    }
    else if (is_404())
    {
        $layout = 'layout_default_page_404';
    }
    else if (is_search())
    {
        $layout = 'layout_default_page_search';
    }
    else
    {
		$layout = 'layout_default_page';
	}

    //echo '<pre>'; print_r( $layout ); echo '</pre>';

    //Definida en aps-layoutbuilder layout-ajax.php
    $layout_id = apply_filters('aps_layout', $layout);

    //Para cambiarlo con la url
    $layout_id = apply_filters('aps_change_page_layout_from_query_var', $layout_id);


	global $aps_config;
	$aps_config['layout'] = array();
	$aps_config['layout']['layout'] = $layout;
	$aps_config['layout']['id'] = $layout_id;
	$aps_config['layout']['layout_id'] = 'layout-'.$layout_id;
	$aps_config['layout']['slider1'] = get_post_meta($layout_id,'layout_slider1',true);
	$aps_config['layout']['header'] = get_post_meta($layout_id,'layout_header',true);
	$aps_config['layout']['slider2'] = get_post_meta($layout_id,'layout_slider2',true);
	//$aps_config['layout']['title'] = get_post_meta($layout_id,'layout_title',true);
	$aps_config['layout']['content'] = get_post_meta($layout_id,'layout_content',true);
	$aps_config['layout']['footer'] = get_post_meta($layout_id,'layout_footer',true);
	$aps_config['layout']['socket'] = get_post_meta($layout_id,'layout_socket',true);
    $aps_config['layout']['responsive'] = get_post_meta($layout_id,'layout_responsive',true);
    $aps_config['layout']['use_background'] = get_post_meta($layout_id,'layout_use_background',true);
    $aps_config['layout']['layout_back_image'] = get_post_meta($layout_id,'layout_back_image',true);
    $aps_config['layout']['layout_content_transparent'] = get_post_meta($layout_id,'layout_content_transparent',true);
	//echo '<pre>'; print_r($aps_config['layout']); echo '</pre>';
}


// 	LAYOUT CLASS
//====================================================================


//Classes para añadir al body
function layout_class(){

	global $aps_config;
	
	//Estilo del layout
	$campos = array('layout_id','slider1','header','slider2','content','footer','socket');
	$class=array();
	foreach($campos as $campo){
		$class[] = 'lay-'.$campo.'-'.$aps_config['layout'][$campo];
	}
	if(aps_has_header()){
		$class[] = 'has-header';
	} else {
		$class[] = 'no-header';
	}

    //Full width and extend del layout particular
    //$class[] = aps_get_option('display'); //Antiguo desde settings general
    //$class[] = aps_get_option('display_extend');
    $class_responsive = $aps_config['layout']['responsive'];
    $class_responsive = apply_filters('body_class_responsive',$class_responsive);
    $class[] = $class_responsive;

	
	//Sidebars
	if (aps_has_left_sidebar()){
		$class[] = 'has-left-sidebar';
	} else {
		$class[] = 'no-left-sidebar';
	}
	if (aps_has_right_sidebar()) {
		$class[] = 'has-right-sidebar';
	} else {
		$class[] = 'no-right-sidebar';
	}
		
	//Header fixed ?
	$class[] = 'fixed-header-'.aps_get_option('fixed_header');
	
	//Responsive sidebars
	$sidebar_responsive = aps_get_option('display_responsive');
	if ($sidebar_responsive=='responsive-sidebars-nostack') {
        $class[] = 'responsive-sidebars-no';
    }
    else if ($sidebar_responsive=='responsive-sidebars-floated') {
        $class[] = 'responsive-sidebars-floated';
    }
    else {
        $class[] = 'responsive-sidebars-yes';
    }
	$class[] = $sidebar_responsive;

    $class[] = 'main-content-transparent-'.$aps_config['layout']['layout_content_transparent'];
	
	//MEnu mobile
	//$class[] = 'menu-mobile-main-'.aps_get_option('menu_mobile_main');
	//$class[] = 'menu-mobile-aux-'.aps_get_option('menu_mobile_auxiliar');

    //Content transparent

    //Preloader
    $class[] = aps_preloader_class();
	
	return $class;
}


function aps_preloader_class()
{
    if (is_page())
    {
        $page_id = get_queried_object_id();
        $use_preloader = get_post_meta( $page_id, 'use_preloader', true);
        if ($use_preloader == 'yes') {
            return 'has-preloader';
        }
    }
    return '';
}


// LAYOUT has_?

function aps_has_slider_above()
{
	global $aps_config;
	return $aps_config['layout']['slider1'];	
}
function aps_has_header()
{
	global $aps_config;
	
	if ($aps_config['layout']['header'] != '') {
        return $aps_config['layout']['header'];
    }
	
	return false;
}
function aps_has_slider_below()
{
	global $aps_config;
	return $aps_config['layout']['slider2'];	
}
/*function aps_has_title()
{
	global $aps_config;
	if ($aps_config['layout']['title'] == 'title-1')
		return true;
	return false;
}*/
function aps_has_left_sidebar()
{
	global $aps_config;
	$d = $aps_config['layout']['content'];
	if ($d == 'content-2' || $d == 'content-3') {
        return $d;
    }
	return false;
}
function aps_has_right_sidebar()
{
	global $aps_config;
	$d = $aps_config['layout']['content'];
	if ($d == 'content-2' || $d == 'content-4') {
        return $d;
    }

	return false;
}
function aps_has_footer()
{
	global $aps_config;
	if ($aps_config['layout']['footer'] == 'footer-1' ||
		$aps_config['layout']['footer'] == 'footer-2' ||
		$aps_config['layout']['footer'] == 'footer-3' ||
		$aps_config['layout']['footer'] == 'footer-4') {
        return $aps_config['layout']['footer'];
    }
	return false;
}
function aps_has_socket()
{
	global $aps_config;
	if ($aps_config['layout']['socket'] == 'socket-1') {
        return true;
    }
	return false;
}




// 	CLASS MAIN CONTENT
// es para controlar el margin del content
//====================================================================

function main_content_class($layout_id=null, $echo=true){
	global $aps_config;
	if ($layout_id==null) {
        $layout_id = $aps_config['layout']['id'];
    }
	
	$content = $aps_config['layout']['content'];
	$n = preg_replace('/content-/', '', $content);
	
	//Segun sea content-1 content-2 content-3 content-4
	$name = 'layout_content_widgets'.$n.'_wrap';
	$class = get_post_meta($layout_id, $name, true);
	//echo 'Buscando '.$name;
		
	if ($echo==true)
		echo ' '.$class;
	else
	return $class;
}





// 	LAYOUT SLIDER OR IMAGE
//====================================================================

//El $number corresponde a 1 o 2 segun slider above o below
function aps_slider_image($number=1, $size='large', $layout_id=null)
{
	global $aps_config;
	if ($layout_id==null) {
        $layout_id = $aps_config['layout']['id'];
    }

    $meta_name = 'layout_slider'.$number.'_image';
	$image_id = get_post_meta($layout_id,$meta_name,true);

	if (empty($image_id)) { return false; }

    $image_width = get_post_meta($layout_id, $meta_name.'_width',true);
    $image_height = get_post_meta($layout_id, $meta_name.'_height',true);

    //echo '<h1>Buscando imagen para datos</h1>';
    //echo '<p>ID='.$image_id.' width='.$image_width.' height='.$image_height.'</p>';
    //$resized = aps_get_image_resized_for_id($image_id, $image_width, $image_height, 0, 'no');
    //echo '<pre>'; print_r( $resized ); echo '</pre>';

    return aps_get_image_resized_url_for_id( $image_id, $image_width, $image_height,0,'no');

	//return aps_image_src($image, $size);
}

function aps_slider_image_ratio($number=1, $layout_id=null)
{
	//Ya no lo uso, devuelvo 1 para que no de error
	return 1.0;
	
	global $aps_config;
	if ($layout_id==null) {
        $layout_id = $aps_config['layout']['id'];
    }
	$ratio = get_post_meta($layout_id,'layout_slider'.$number.'_ratio',true);
	$val = preg_replace('/ratio-/', '', $ratio);
	
	return 1.0/floatval($val);
	
	/*
	$height = get_post_meta($layout_id,'layout_image_height',true);
	$width = get_post_meta($layout_id,'layout_image_width',true);
	
	$height = empty($height) ? '300' : $height;
	$width  = empty($width)  ? '600' : $width;
	
	return floatval($height) / floatval($width);
	*/
	
}



// 	LAYOUT SLIDER
//====================================================================

//El $number corresponde a 1 o 2 segun slider above o below
function aps_layout_slider_html($number=1, $layout_id=null)
{
	global $aps_config;
	if ($layout_id==null) {
        $layout_id = $aps_config['layout']['id'];
    }

	$slider = get_post_meta($layout_id,'layout_slider'.$number.'_input',true);
	if (empty($slider)) return '';

	if ($slider=='none'){
        return '';
    }

    else if ($slider=='shortcode'){
        $meta_sc = get_post_meta($layout_id,'layout_slider'.$number.'_input_sc',true);
        return do_shortcode($meta_sc);
    }

    else if ( preg_match('/^royalslider-(\d+)/', $slider, $matches) )
    {
        if (!isset($matches[1]))
            return '';
        $id = $matches[1];
        $content = '[new_royalslider id="'.$id.'"]';
        $html = do_shortcode($content);
        return $html;
    }

    else if ( preg_match('/^everslider-(.+)/', $slider, $matches) )
    {
        if (!isset($matches[1]))
            return '';
        $id = $matches[1];
        $content = '[everslider id="'.$id.'"]';
        $html = do_shortcode($content);
        return $html;
    }

	// Layerslider
	else if ( preg_match('/^layerslider-(\d+)/', $slider, $matches) )
	{
		if (!isset($matches[1]))
			return '';
		$id = $matches[1];
		$content = '[layerslider id='.$id.']';
		$html = do_shortcode($content);
		return $html;
	}
	
	
	// Revslider
	else if ( preg_match('/^revslider-(.+)/', $slider, $matches) )
	{
		if (!isset($matches[1]))
			return '';
		$alias = $matches[1];
		//return '<h1>'.$alias.'</h1>';
		$content = '[rev_slider '.$alias.']';
		$html = do_shortcode($content);
		return $html;
	}
	
	return '<h1>ERROR: '.$slider.'</h1>';
	
	//De relleno
	//$html = '<img src="http://placekitten.com/1400/600" style="width:100%;">';
	//return $html;
}


// 	LAYOUT WIDGETS
//====================================================================


function aps_get_option_widget($option)
{
	global $aps_config;
	$layout_id = $aps_config['layout']['id'];
	$widget = get_post_meta($layout_id, $option, true);
	
	if (is_active_sidebar($widget))
	{
		ob_start();
		dynamic_sidebar( $widget );
		return ob_get_clean();
	}
	return null;
}



function aps_sidebar_class($side = 'left', $layout_id=null)
{
	global $aps_config;
	if ($layout_id==null) {
        $layout_id = $aps_config['layout']['id'];
    }
    $aps_config['layout']['content'] = get_post_meta($layout_id,'layout_content',true);

    if ($aps_config['layout']['content']=='content-2'){
        $name = 'layout_content_widgets2_';
    } else if ($aps_config['layout']['content']=='content-3') {
        $name = 'layout_content_widgets3_';
    } else {
        $name = 'layout_content_widgets4_';
    }

	$option = get_post_meta($layout_id,$name.$side.'_cols',true);
	echo $option;
}


// 	LAYOUT FOOTER
//====================================================================


function aps_footer_num_of_areas()
{
	global $aps_config;
	
	$option = $aps_config['layout']['footer'];
	if (empty($option)) return null;
	$n = preg_replace('/footer-/', '', $option);
	return intval($n);
}

function aps_get_option_footer_widget($footer, $column)
{
	$n = preg_replace('/footer-/', '', $footer);
	$option = 'layout_footer_widgets'.$n.'_'.$column;
	
	return aps_get_option_widget($option);
}

// 	LAYOUT SOCKET
//====================================================================




function aps_get_menu_socket()
{
	$html = '';
	ob_start();
	wp_nav_menu( array( 'theme_location'  => 'menu_3',
		'container'		  => 'nav',
		'container_class' => 'socket-menu-wrap',
		'container_id'	  => 'socket-menu-wrap',
		'menu_class'	  => 'socket-menu',
		'menu_id'		  => 'socket-menu',
		'depth'			  => 1
	 ) );
	 $html .= ob_get_clean();
	 return $html;
}



// 	OPTION DATA para menus y socket
//====================================================================

function aps_get_option_data($option_head)
{
	//Obtengo que option tengo que coger de los settings
	//y las clases por si pide un menu
	switch($option_head){
		case 'head-top:left':
			$option = 'layout_header_box_top_1';
			$sf_class="sf-left sf-no-border sf-banda2";
			break;
		case 'head-top:right':
			$option = 'layout_header_box_top_2';
			$sf_class="sf-right sf-no-border sf-banda2";
			break;
		case 'head-center:left':
			$option = 'layout_header_box_center_1';
			$sf_class="sf-left sf-no-border sf-banda1";
			break;
		case 'head-center:right':
			$option = 'layout_header_box_center_2';
			$sf_class="sf-right sf-no-border sf-banda1";
			break;
		case 'head-bottom:left':
			$option = 'layout_header_box_bottom_1';
			$sf_class="sf-left sf-no-border sf-banda2";
			break;
		case 'head-bottom:right':
			$option = 'layout_header_box_bottom_2';
			$sf_class="sf-right sf-no-border sf-banda2";
			break;
		case 'socket:left':
			$option = 'layout_socket_box_1';
			$sf_class="sf-right sf-no-border sf-banda2";
			break;
		case 'socket:right':
			$option = 'layout_socket_box_2';
			$sf_class="sf-right sf-no-border sf-banda2";
			break;	
		default:
			$option = '';
			break;
	}
	
	if ($option=='') return '';
	
	global $aps_config;
	$layout_id = $aps_config['layout']['id'];
	$value = get_post_meta($layout_id, $option, true);
	
	
	//Primero compruebo si es un menu-id
	if (preg_match('/menu-(\d+)/', $value,$match)){
			$menu_id = $match[1];
			$name = 'menu-aps';//menu-auxiliar de antes
			$depth = 0;
			
			/*
			if (strpos($option_head, 'head-center')){
				$name = 'menu-main';
			}
			//En el socket solo un nivel
			if (preg_match('/socket:/', $option_head)) {
				$name = 'menu-socket';
				$depth=1;
			}
			*/
			aps_get_menu_superfish($menu_id, $name.' '.$sf_class, $name, $depth);
	}
	else {
	
		Switch ($value)
		{
			case 'logo':
				aps_get_site_logo();
				break;
			
			case 'site_title':
				aps_get_site_title();
				break;	
			
			case 'site_tagline':
				aps_get_site_tagline();
				break;
			
			case 'site_title_tagline':
				aps_get_site_title_tagline();
				break;
			
			case 'contact':
				aps_get_contact_data();
				break;
			
			case 'social':
				aps_get_social_icons();
				break;
			
			case 'copyright':
				aps_get_copyright_data();
				break;
			
			case 'search':
				aps_get_search_form();
				break;
			
			//case 'main-menu':
			//case 'auxiliar-menu':
			//case 'socket-menu':
			//	include(APS_THEME_DIR.'/layout/menu-ejemplo.php');
			//	break;
			
			/*
			case 'main-menu':
				aps_get_menu_superfish('menu_1', 'menu-main '.$sf_class, 'menu-main');
				break;
				
			case 'auxiliar-menu':
				aps_get_menu_superfish('menu_2', 'menu-auxiliar '.$sf_class, 'menu-auxiliar');
				break;
				
			case 'socket-menu':
				aps_get_menu_superfish('menu_3', 'menu-socket '.$sf_class, 'menu-socket', 1);
				break;
			*/
			
			default:
				return '';
				break;	
		}
	
	}	
}


// Contact data
function aps_get_contact_data($echo=true, $class='')
{
	$address = aps_get_option('address');
	$tlf = aps_get_option('telephone');
	$email = aps_get_option('email');
	$schedule = aps_get_option('schedule');
	
	$html  = '';
	$html .= '<div class="contact-data listado-inline '.$class.'">';
	$html .= '<ul>';
	
	if (!empty($address))
	{
		$html .= '<li class="contact-address">';
		$html .= '<span class="fa fa-location-arrow"></span>'.$address.'</li>';
	}
	
	if (!empty($tlf))
	{
		$html .= '<li class="contact-telephone">';
		$html .= '<span class="fa fa-mobile"></span>'.$tlf.'</li>';
	}
	
	if (!empty($email))
	{
		$html .= '<li class="contact-email">';
		$html .= '<a class="email" href="mailto:'.$email.'">';
		$html .= '<span class="fa fa-envelope"></span>'.$email.'</a></li>';
	}
	
	if (!empty($schedule))
	{
		$html .= '<li class="contact-schedule">';
		$html .= '<span class="fa fa-calendar"></span>'.$schedule.'</li>';
	}
	
	$html .= '</ul>';
	$html .= '</div>';
	
	if ($echo)
		echo $html;
	else
		return $html;
}




//Social icons
function aps_get_list_of_social_icons()
{
    // Con fontawesome
    static $list = array(
        'social_email'          => 'envelope',
        'social_facebook' 	    => 'facebook',
        'social_twitter' 		=> 'twitter',
        'social_rss' 			=> 'rss',
        'social_linkedin' 	    => 'linkedin',
        'social_skype' 			=> 'skype',
        'social_tumblr' 		=> 'tumblr',
        'social_google' 		=> 'google-plus',
        'social_pinterest' 	    => 'pinterest',
        'social_dribbble' 	    => 'dribbble',
        'social_behance' 		=> 'behance',
        'social_flickr' 		=> 'flickr',
        'social_vimeo' 			=> 'vimeo-square',
        'social_instagram' 	    => 'instagram',
        //'social_picasa' 		=> 'picasa',
        'social_github' 		=> 'github',
        'social_digg' 		    => 'digg',
        'social_reddit' 		=> 'reddit',
        'social_foursquare' 	=> 'foursquare',
        //'social_blogger' 		=> 'blogger',
        'social_deviantart'     => 'deviantart',
        //'social_forrst' 		=> 'forrst',
        'social_vine' 		    => 'vine',
        //'social_myspace' 		=> 'myspace',
        'social_yahoo' 		    => 'yahoo',
        'social_weibo' 		    => 'weibo',
        'social_xing' 		    => 'xing',
    );
    return $list;
}

function aps_get_options_social_icons()
{
    $list = aps_get_list_of_social_icons();

    $options = array();
    foreach( $list as $key=>$value) {
        $name = str_replace('social_','',$key);
        $options[] = array(
            'section'	=> 'sec_opciones_social',
            'id'		=> $key,
            'title'		=> '<span class="admin-social-icon fa fa-'.$value.'"></span><span class="admin-social-text">'.strtoupper($name).'</span>',
            'desc'		=> __('', LANGUAGE_THEME),
            'type' 		=> 'input_social'
        );
    }
    return $options;
}


function aps_get_social_icons($echo=true, $class='')
{
	$open = aps_get_option('social_open');
	$target = $open == 'yes' ? 'target="_blank"' : '';
	
	/* con entypo
	$list = array(
		'social_facebook' 	=> 'entypo-social-facebook',
		'social_twitter' 		=> 'entypo-social-twitter',
		'social_rss' 				=> 'entypo-rss',
		'social_linkedin' 	=> 'entypo-social-linkedin',
		'social_skype' 			=> 'entypo-social-skype',
		'social_tumblr' 		=> 'entypo-social-tumblr',
		'social_google' 		=> 'entypo-social-google',
		'social_pinterest' 	=> 'entypo-social-pinterest',
		'social_dribbble' 	=> 'entypo-social-dribbble',
		'social_behance' 		=> 'entypo-social-behance',
		'social_flickr' 		=> 'entypo-social-flickr',
		'social_vimeo' 			=> 'entypo-social-vimeo',
		'social_instagram' 	=> 'entypo-social-instagram',
		'social_picasa' 		=> 'entypo-social-picasa',
		'social_github' 		=> 'entypo-social-github'
	);
	*/
	
	$list = aps_get_list_of_social_icons();
	
	$html = '';
	$html .= '<div class="social-icons listado-inline '.$class.'"><ul>';
	
	foreach($list as $key=>$icon)
	{
		$url = aps_get_option($key);

		if (strlen($url) > 5) {

            if ($key=='social_email') { $url = 'mailto:'.$url; }

			$html .= '<li><a '.$target.' href="'.esc_url($url).'" aria-hidden="true">';
			//$html .= '<span class="entypo-icon '.$icon.'"></span></a></li>';
			$html .= '<span class="fa fa-'.$icon.'"></span></a></li>';
		}
	}
	
	$html .= '</ul></div>';
	
	if ($echo)
		echo $html;
	else
		return $html;
}



//Copyright
function aps_get_copyright_data( $echo=true, $class='')
{
	$cp = aps_get_option('copyright');
	
	$html  = '';
	$html .= '<div class="copyright-data '.$class.'">';
	$html .= $cp;
	$html .= '</div>';
	if ($echo)
		echo $html;
	else
		return $html;
}

//Site Title
function aps_get_site_title( $echo=true )
{
	$title = get_bloginfo('name');
	$html = '<a class="site-title" href="'.site_url().'">'.$title.'</a>';	
	if ($echo)
		echo $html;
	else
		return $html;
}


//Site Tagline
function aps_get_site_tagline( $echo=true )
{
	$tagline = get_bloginfo('description');
	$html = '<span class="site-tagline">'.$tagline.'</span>';
	if ($echo)
		echo $html;
	else
		return $html;
}

//Site Title+Tagline
function aps_get_site_title_tagline( $echo=true )
{
	$title = get_bloginfo('name');
	$tagline = get_bloginfo('description');
	$html  = '<span class="site-title-tagline">';
	$html .= '<a class="site-title" href="'.site_url().'">'.$title.'</a>';
	$html .= '<span class="site-tagline">'.$tagline.'</span>';	
	$html .= '</span>';
	if ($echo)
		echo $html;
	else
		return $html;
}

//Logo solo
function aps_get_site_logo( $echo=true )
{
	$src = aps_image_src(aps_get_option('logo_top'), 'full');
	
	$site_url = apply_filters('aps_site_url',site_url());
	
	$html = '<a class="image-logo-wrap" href="'.$site_url.'"><div class="image-logo"><img src="'.$src.'"></div></a>';
	if ($echo)
		echo $html;
	else
		return $html;
}

//Search form
function aps_get_search_form( $echo=true )
{
	$html = '<div class="search-nav close">';
	$html .= '<a href="#" class="search-toggle fa fa-search"></a>';
	$html .= '<form role="search" id="searchform" method="get" action="'.site_url().'">';
	$html .= '<div class="search-wrap">';
	$html .= '<input type="text" value="" name="s" id="s">';
	$html .= '<input type="submit" id="searchsubmit" value="">';
	$html .= '</div>';
	if ( defined( 'ICL_LANGUAGE_CODE' ) ){
		$html .= '<input type="hidden" name="lang" value="'.ICL_LANGUAGE_CODE.'">';
	} 
	$html .= '</form>';
	
	$html .= '</div>';
	if ($echo)
		echo $html;
	else
		return $html;
}


function aps_get_menu($menu, $class='header-menu', $id='header-menu', $depth=0)
{
	$html = '';
	ob_start();
	wp_nav_menu( array( 'theme_location'  => $menu,
		'container'		  => 'nav',
		'container_class' => $class.'-wrap',
		'container_id'	  => $id.'-wrap',
		'menu_class'	  => $class,
		'menu_id'		  => $id,
		'depth'			  => $depth
	 ) );
	 $html .= ob_get_clean();
	 echo $html;
}

function aps_get_menu_superfish($menu_id, $class='', $id='', $depth=0)
{
	$class = 'sf-menu '.$class;
	$html = '';
	ob_start();
	wp_nav_menu( array(
		//'theme_location'  => $menu,
		'menu'					=> $menu_id,
		'container'		  => 'nav',
		'container_class' => 'nav-menu-wrap',
		'container_id'	  => $id.'-wrap',
		'menu_class'	  => $class,
		'menu_id'		  => $id,
		'depth'			  => $depth
	 ) );
	 $html .= ob_get_clean();
	 echo $html;
}


//Background image in layout
function aps_body_style_layout( $echo=true )
{
    global $aps_config;

    $responsive = $aps_config['layout']['responsive'];

    //if ( $responsive == 'fullwidth' ) { return false; }

    $use_background = $aps_config['layout']['use_background'];
    $image_id = $aps_config['layout']['layout_back_image'];

    //echo 'data-image="'.$image_id.'"';

    if ($use_background != 'yes') { return ''; }
    if (!$image_id) { return false; }

    $src = wp_get_attachment_image_src($image_id, 'full');
    if ( !$src ) { return false; }

    $src = $src[0];
    //echo '<pre>'; print_r( $src ); echo '</pre>';

    $style = "background-image: url('{$src}');
background-attachment: fixed;background-size: cover;background-position: 50% 50%;background-repeat: no-repeat no-repeat;";
    $html = ' style="'.$style.'"';

    if ($echo) { echo $html; }
    else { return $html; }

}