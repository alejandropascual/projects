<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


/*
aps_get_favicon
aps_image_src
aps_get_image_src
aps_short_text
*/

//====================================================================
// Header meta
//====================================================================

if ( ! function_exists( 'aps_header_meta' ) ) {

    function aps_header_meta() {

        $description = aps_get_option('seo_description');
        $keywords = aps_get_option('seo_keywords');
        $author = aps_get_option('seo_author');


        if ( !empty($description) ) {
            echo '<meta name="description" content="' . $description . '">
            ';
        }

        if ( !empty($keywords) ) {
            echo '<meta name="keywords" content="' . $keywords . '">
            ';
        }

        if ( !empty($author) ) {
            echo '<meta name="author" content="' . $author . '">
            ';
        }
    }

}

// FAVICON
//====================================================================

function aps_show_favicon()
{
	$output = '';
	$icon = aps_image_src( aps_get_option('favicon') ,'small');
	$ext = pathinfo($icon,PATHINFO_EXTENSION);

	switch ( $ext )
	{
		case 'png':
			$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_PNG ) );
			break;
		case 'gif':
			$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_GIF ) );
			break;
		case 'jpg':
		case 'jpeg':
			$icon_type = esc_attr( image_type_to_mime_type( IMAGETYPE_JPEG ) );
			break;
		case 'ico':
			$icon_type = esc_attr( 'image/icon' );
			break;
		default:
			return '';
	}
	
	//$output .= '<!-- icon -->' . "\n";
    //$output .= '<link rel="icon" href="' . $icon . '" type="' . $icon_type . '" />' . "\n";
    //$output .= '<link rel="shortcut icon" href="' . $icon . '" type="' . $icon_type . '" />' . "\n";

    // Sacado de http://kamalyon.com
    $output .= '
<!-- Favicons ================================================== -->
<!-- Default Favicon -->
<link href="' . $icon . '" rel="icon" type="image/x-icon">
<!-- For third-generation iPad with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="' . $icon . '">
<!-- For iPhone with high-resolution Retina display: -->
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="' . $icon . '">
<!-- For first- and second-generation iPad: -->
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="' . $icon . '">
<!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
<link rel="apple-touch-icon-precomposed" href="' . $icon . '">
    ';
	
	echo  $output;
}



// UTILES
//====================================================================


function aps_image_src($id, $size)
{
	$src = wp_get_attachment_image_src( $id, $size);
	if (empty($src)) return null;
	return $src[0];
}




//Devuelve la imagen con el size solicitado
//si no existe ese size regenera los sizes y
//lo vuelve a buscar
//puede que no lo encuentre porque el tamaño no se puede crear porque sea mayor que el
//tamaño original de la imagen
function aps_get_image_src($id, $size)
{
	$result = wp_get_attachment_image_src($id, $size);
	
	//Es la adecuada o la original ?
	if ($result[3]) return $result;
	
	//Path de la imagen
	$fullsizepath = get_attached_file($id);
	if (false === $fullsizepath || !file_exists($fullsizepath)) {
		//ERROR, devuelve la original
		return $result;
	}
	
	//Resize all sizes
	include( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $id, $fullsizepath );
	wp_update_attachment_metadata( $id,  $attach_data );
    
   //Devolver el tamaño
   return wp_get_attachment_image_src($id, $size);
}




function aps_short_text($text, $cut_length, $limit){
    $text = (strlen($text) > $limit) ? substr($text,0,$cut_length).'...' : $text;
    echo $text;
}
