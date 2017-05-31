<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

/* Para resize imagenes sobre la marcha
    prop = height/width
    Las imagenes se les da un coeficiente de seguridad cuando $ampliar = true
    width*1.5
    height*1.3
*/

function aps_get_image_resized_for_url( $url, $width = 0, $height = 0, $prop = 0, $ampliar = 'yes', $alt='' )
{
    //Por si acaso
    $width = intval($width);
    $height = intval($height);
    $prop = intval($prop);

    //Coeficiente de seguridad
    $c_w = 1.0;
    $c_h = 1.0;
    if ( $ampliar === 'yes' || $ampliar === true || $ampliar === 'true')
    {
        $c_w = 1.5;
        $c_h = 1.3;
    }

    $resize = array();

    //echo 'Calculando image con: '.$width.' / '.$height.' / '.$prop.' / '.$ampliar.' ('.$c_w.'-'.$c_h.')<br>';

    //Quiero la imagen con un ancho y mantener la proporcion natural
    if ($width != 0 && $height == 0 && $prop == 0) {
        $width = aps_redondear_medida ($width, $c_w); //Aumento el ancho por escalones
        $resize = aq_resize($url, $width, null,false,false); //Devuelve url, width y height
    }

    //Quiero la imagen con un alto y mantener la proporcion natural
    else if ($width == 0 && $height != 0 && $prop == 0) {
        $height = aps_redondear_medida ($height, $c_h);
        $resize = aq_resize($url, null, $height,false,false);
    }

    //Quiero la imagen con un ancho y una proporcion determinada
    else if ($width != 0 && $height == 0 && $prop != 0) {
        $width = aps_redondear_medida ($width, $c_w);
        $height = intval($width * $prop);
        //echo 'OK calculando con '.$width.' x '.$height.'<br>';
        $resize = aq_resize($url, $width, $height,true,false);
    }

    //Quiero la imagen con un alto y una proporcion determinada
    else if ($width == 0 && $height != 0 && $prop != 0) {
        $height = aps_redondear_medida ($height, $c_h);
        $width = intval($height / $prop);
        $resize = aq_resize($url, $width, $height,true,false);
    }

    //Quiero la imagen con unas medidas determinadas
    else if ($width != 0 && $height != 0) {
        $ratio = $height / $width;
        $width = aps_redondear_medida ($width, $c_w);
        $height = intval($width * $ratio);
        $resize = aq_resize($url, $width, $height,true,false);
    }
    //echo 'Calculando image resized: '.$width.' / '.$height.' / '.$prop.' / '.$ampliar.'<br>';
    //echo '<pre>'; print_r( $resize ); echo '</pre>';

    if (empty($resize)) return false;

    $result = array(
        'resized'   => array(
            'url' => $resize[0],
            'width' => $resize[1],
            'height' => $resize[2],
            'img' => '<img src="'.$resize[0].'" width="'.$resize[1].'" height="'.$resize[2].'" alt="'.$alt.'">'
        ),
    );

    return $result;
}







//Funcion simplificada, paso la id de la imagen

function aps_get_image_resized_for_id( $attach_id, $width = 0, $height = 0, $prop = 0, $ampliar = 'yes'  )
{
    $attach_url = wp_get_attachment_url($attach_id,'full');
    if ($attach_url)
    {
        $alt = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        $result = aps_get_image_resized_for_url($attach_url, $width, $height, $prop, $ampliar, $alt);

        //Añado un array con los datos de la original
        $result['full'] =  wp_get_attachment_metadata($attach_id);
        $result['full']['url'] = $attach_url;
        $result['full']['img'] = '<img src="'.$result['full']['url'].'" alt="'.$alt.'" width="'.$result['full']['width'].'" height="'.$result['full']['height'].'">';
        return $result;
    }
    return false;
}

function aps_get_image_resized_url_for_id( $attach_id, $width = 0, $height = 0, $prop = 0, $ampliar = 'yes'  )
{
    $resized_image = aps_get_image_resized_for_id( $attach_id, $width, $height, $prop, $ampliar);
    if (isset($resized_image['resized']['url'])) {
        return $resized_image['resized']['url'];
    } else {
        return $resized_image['full']['url'];
    }
}



//Funcion simplificada, paso la id del post para extraer su thumbnail

function aps_get_image_resized_for_post_id( $post_id, $width = 0, $height = 0, $prop = 0, $ampliar = 'yes'  )
{
    $attach_id = get_post_thumbnail_id( $post_id );
    if ( !$attach_id ) return false;
    return aps_get_image_resized_for_id( $attach_id, $width, $height, $prop, $ampliar);
}







function aps_redondear_medida( $medida = 0, $coef = 1.0 )
{
    if ($medida == 0 || !is_numeric($medida)) return;

    //Amplio antes de redondear
    $medida = $medida * $coef;

    //Redondeo a 50
    $result =   50 * intval( ($medida*1.0 +50.0 ) / 50.0 );
    return intval($result);
}




function aps_get_image_sizes_array()
{
    global $_wp_additional_image_sizes;
    $sizes = array();
    foreach( get_intermediate_image_sizes() as $s ){
        $sizes[ $s ] = array( 0, 0 );
        if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
            $sizes[ $s ][0] = get_option( $s . '_size_w' );
            $sizes[ $s ][1] = get_option( $s . '_size_h' );
        }else{
            if( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) )
                $sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'], );
        }
    }
    return $sizes;
}

function aps_get_image_sizes_options( $with_both_dim = false )
{
    $sizes = aps_get_image_sizes_array();
    $sizes2 = [];
    foreach($sizes as $size=>$dim){
        if ($with_both_dim==true) {
            if ($dim[1]!=0){
                $sizes2[$size] = $size.' ('.$dim[0].' x '.$dim[1].')';
            }
        } else {
            if ($dim[1]==0) $dim[1]='variable';
            $sizes2[$size] = $size.' ('.$dim[0].' x '.$dim[1].')';
        }
    }
    return $sizes2;
}




// LAYERSLIDER

//Saber que plugins estan activos
function aps_is_active_plugin_layerslider()
{
    if (function_exists('layerslider_activation_scripts'))
        return true;
    return false;
}

function aps_get_list_layerslider()
{
    global $wpdb;
    $db = $wpdb->prefix.'layerslider';
    $query = "SELECT id,name FROm $db";
    $results = $wpdb->get_results($query);
    //echo '<pre>'; print_r($results); echo '</pre>';

    $options = array();
    foreach($results as $result)
    {
        $options['layerslider-'.$result->id] = 'Layerslider::'.$result->name;
    }
    return $options;
}



// REVOLUTION SLIDER

function aps_is_active_plugin_revolution_slider()
{
    if ( class_exists('RevSlide')){
        return true;
    }
    return false;
}

function aps_get_list_revslider()
{
    global $wpdb;
    $db = $wpdb->prefix.'revslider_sliders';
    $query = "SELECT alias,title FROm $db";
    $results = $wpdb->get_results($query);

    $options = array();
    foreach($results as $result)
    {
        $options['revslider-'.$result->alias] = 'Revslider::'.$result->title;
    }
    return $options;
}



// ROYAL SLIDER
function aps_is_active_plugin_royalslider()
{
    if ( class_exists('NewRoyalSliderMain')){
        return true;
    }
    return false;
}

function aps_get_list_royalslider()
{
    global $wpdb;
    $db = $wpdb->prefix.'new_royalsliders';
    $query = "SELECT id,name FROm $db";
    $results = $wpdb->get_results($query);

    $options = array();
    foreach($results as $result)
    {
        $options['royalslider-'.$result->id] = 'Royalslider::'.$result->name;
    }
    return $options;
}

// EVER SLIDER
function aps_is_active_plugin_everslider()
{
    if (function_exists('everslider_load_textdomain'))
        return true;
    return false;
}

function aps_get_list_everslider()
{
    global $wpdb;
    $eversliders = get_option('everslider_sliders');
    //echo '<pre>'; print_r( $eversliders ); echo '</pre>';

    $options = array();
    if ($eversliders){
        foreach( $eversliders as $key=>$value)
        {
            $es_options = get_option($value);
            if ($es_options){
                $options['everslider-'.$key] = 'Everslider::'.$es_options['name'];
            }

        }
    }

    return $options;
}
