<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }





//////////////////////////////////////////
// AFTER SETTINGS SAVE, genera un CSS variable para el tema
//////////////////////////////////////////


add_action('aps_after_settings_skin_saved','aps_generate_stylesheet');

add_action ('aps_after_settings_mobile_saved','aps_generate_css_responsive');




function aps_generate_stylesheet()
{
    //echo '<h3>Guardando opciones SKIN</h3>';
	global $aps_config;

    //Directorio donde se va a almacenar el fichero
    $stylesheet_dir = aps_create_check_folder_for_skin();
    //No se ha podido crear el directorio, avisar al usuario
    //el folder no se puede escribir
    if ($stylesheet_dir === false) { return false; }
	
	//Ok, se ha creado el directorio, puedo seguir
	$stylesheet = trailingslashit( $stylesheet_dir ) . APS_DYNAMIC_CSS_NAME;
	//echo '<p>'.$stylesheet.'</p>';

	//Preparar los estilos
    //include(APS_THEME_DIR.'/includes/php/settings-css-dynamic.php');
    include(APS_THEME_DIR.'/includes/php/settings-less-dynamic.php');
	$styles = $aps_config['style'];

	//Guardar el fichero
    $stylesheet = apply_filters('aps_dynamic_css_dir', $stylesheet);
	$created = aps_backend_create_file($stylesheet, $styles, true);
	if ($created === true) {}
}

function aps_generate_css_responsive()
{
    //echo '<h3>Guardando opciones responsive</h3>';
    global $aps_config;
    //Directorio donde se va a almacenar el fichero
    $stylesheet_dir = aps_create_check_folder_for_skin();
    if ($stylesheet_dir === false) { return false; }

    $stylesheet = trailingslashit( $stylesheet_dir ) . APS_DYNAMIC_CSS_MOBILE_NAME;

    include(APS_THEME_DIR.'/includes/php/settings-less-dynamic-mobile.php');
    $styles = $aps_config['style'];

    $created = aps_backend_create_file($stylesheet, $styles, true);
    if ($created === true) {}
}


function aps_create_check_folder_for_skin()
{
    $wp_upload_dir  = wp_upload_dir();
    $stylesheet_dir = $wp_upload_dir['basedir'].APS_DYNAMIC_CSS_DIR;
    $stylesheet_dir = str_replace('\\', '/', $stylesheet_dir);
    $folder = aps_backend_create_folder($stylesheet_dir);
    if ($folder === false) { return false; }
    return $stylesheet_dir;
}


//Creates a folder
function aps_backend_create_folder(&$folder, $addindex = true)
{	
	if(is_dir($folder) && $addindex == false) return true;
	
	$created = wp_mkdir_p( trailingslashit( $folder ) );
	@chmod( $folder, 0777 );
	
	if($addindex == false) return $created;

    $index_file = trailingslashit( $folder ) . 'index.php';
    if ( file_exists( $index_file ) )
        return $created;

    $handle = @fopen( $index_file, 'w' );
    if ($handle)
    {
        fwrite( $handle, "<?php\r\necho 'Sorry, you cannot browse the directory!';\r\n?>" );
        fclose( $handle );
    }

    return $created;	
}


//Creates a file
function aps_backend_create_file($file, $content = '', $verifycontent = true)
{
	$handle = @fopen( $file, 'w' );
    if($handle)
    {
        $created = fwrite( $handle, $content );
        fclose( $handle );

        if($verifycontent === true)
        {
            $handle = fopen($file, "r");
            $filecontent = fread($handle, filesize($file));
            $created = ($filecontent == $content) ? true : false;
            fclose( $handle );
        }
    }
    else
    {
        $created  = false;
    }

    if($created !== false) $created = true;
    return $created;
}



