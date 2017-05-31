<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


add_action( 'admin_init', 'aps_theme_importer');

function aps_theme_importer() {
    global $wpdb;

    //Datos importados
    if ( current_user_can( 'manage_options' ) && isset( $_GET['imported_data'] ) &&  $_GET['imported_data']=='success' ) {
        add_settings_error('general', 'settings_updated', __('EXAMPLE CONTENT HAS BEEN IMPORTED ;)', LANGUAGE_THEME), 'updated');
        return true;
    }

    //Se van a importar datos
    if ( !current_user_can( 'manage_options' ) ||  !isset( $_GET['import_data_projects'] ) ) {
        return false;
    }

    // We are importing
    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    // If WP importer doen not exist
    //Ya se encarga el siguiente
    /*if ( ! class_exists( 'WP_Importer' ) ) {
        $wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        include $wp_importer;
    }*/

    // If WP importer does not exist
    if ( ! class_exists('WP_Import') ) {
        $wp_import = get_template_directory() . '/includes/plugins/importer/wordpress-importer.php';
        include $wp_import;
    }

    // Comprobacion
    if ( !class_exists( 'WP_Importer' ) || !class_exists( 'WP_Import' ) ) {
        return false;
    }


    //1- Importar xml - correcto
    //Importa posts, pages, projects, images, menus
    //Los menus no tienen localizacion sino que están indicados en los layouts
    //sino habria que registrarlos en sus localizaciones
    $importer = new WP_Import();
    $theme_xml = get_template_directory() . '/includes/plugins/importer/data/projects-example.xml';
    $importer->fetch_attachments = true;
    ob_start();
    $importer->import($theme_xml);
    ob_end_clean();

    //2- Opciones - correcto
    $options_txt = get_template_directory_uri() . '/includes/plugins/importer/data/projects-example-options.txt';
    $options_txt = wp_remote_get( $options_txt );
    $result = aps_restore_options( $options_txt['body'] );

    //3- Widgets data - correcto
    $widget_data = get_template_directory_uri() . '/includes/plugins/importer/data/widget_data.json';
    $widget_data = wp_remote_get( $widget_data );
    //No los genero aqui sino despues de los layouts ya que se han preparado los menus correctamente
    //aps_projects_import_widget_data( $widget_data['body'] );

    //4- Arreglar los menus, los terms no los crea con los mismo ids y hay que reasignar los ids de los menus dentro de los layouts
    // Importo el json con los datos exportados desde mi plugin
    // Arreglo tambien el widget Custom menu que tiene un campo 'menu' con el id del menu asociado
    $layouts_data = get_template_directory_uri() . '/includes/plugins/importer/data/layouts_data.json';
    $layouts_data = wp_remote_get( $layouts_data );
    aps_projects_import_layouts_data( $layouts_data['body'], $widget_data['body'] );


    //5- Revolution Slider - correcto, no estoy metiendo sliders por ahora porque no se exportan bien
    /*
    if( class_exists('UniteFunctionsRev') ) { // plugin activated

        $rev_directory = get_template_directory() . '/includes/plugins/importer/data/revsliders/';

        foreach( glob( $rev_directory . '*.zip' ) as $filename ) { // get all files from revsliders data dir
            $filename = basename($filename);
            $rev_files[] = get_template_directory() . '/includes/plugins/importer/data/revsliders/' . $filename ;
        }

        foreach( $rev_files as $rev_file ) {
            aps_import_revslider_zip_file( $rev_file );
        }

    }
    */

    //6- Royal Slider
    // No tiene import/export todavia el plugin, dejarlo para mas adelante


    //7- Set reading options - probarlo al final
    $homepage = get_page_by_title( 'HOME - shapedesigner' );
    $posts_page = get_page_by_title( 'BlogPlusContent' );
    if($homepage->ID && $posts_page->ID) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $homepage->ID); // Front Page
        update_option('page_for_posts', $posts_page->ID); // Blog Page
        update_option('posts_per_page', 20);
    }

    //8- Permalinks to %postname%
    $permalink_structure = get_option('permalink_structure');
    if ($permalink_structure != '/%postname%/') {
        update_option('permalink_structure', '/%postname%/');
    }
    flush_rewrite_rules( true );

    //9- Al final redireccionar
    wp_redirect ( admin_url('admin.php?page=aps_op_general&imported_data=success') );

}

// RevSlider plugin -> importSliderFromPost()
function aps_import_revslider_zip_file( $rev_file )
{
    global $wpdb;

    $filepath = $rev_file;

    //check if zip file or fallback to old, if zip, check if all files exist
    $zip = new ZipArchive;
    $importZip = $zip->open($filepath, ZIPARCHIVE::CREATE);

    if($importZip === true){ //true or integer. If integer, its not a correct zip file

        //check if files all exist in zip
        $slider_export = $zip->getStream('slider_export.txt');
        $custom_animations = $zip->getStream('custom_animations.txt');
        $dynamic_captions = $zip->getStream('dynamic-captions.css');
        $static_captions = $zip->getStream('static-captions.css');

        $content = '';
        $animations = '';
        $dynamic = '';
        $static = '';

        while (!feof($slider_export)) $content .= fread($slider_export, 1024);
        if($custom_animations){ while (!feof($custom_animations)) $animations .= fread($custom_animations, 1024); }
        if($dynamic_captions){ while (!feof($dynamic_captions)) $dynamic .= fread($dynamic_captions, 1024); }
        if($static_captions){ while (!feof($static_captions)) $static .= fread($static_captions, 1024); }

        fclose($slider_export);
        if($custom_animations){ fclose($custom_animations); }
        if($dynamic_captions){ fclose($dynamic_captions); }
        if($static_captions){ fclose($static_captions); }

        //check for images!

    }else{ //check if fallback
        //get content array
        $content = @file_get_contents($filepath);
    }

    if($importZip === true) { //we have a zip

        $db = new UniteDBRev(); //RevSlider class

        //update/insert custom animations
        $animations = @unserialize($animations);
        if (!empty($animations)) {
            foreach ($animations as $key => $animation) { //$animation['id'], $animation['handle'], $animation['params']
                $exist = $db->fetch(GlobalsRevSlider::$table_layer_anims, "handle = '" . $animation['handle'] . "'");
                if (!empty($exist)) { //update the animation, get the ID
                    if ($updateAnim == "true") { //overwrite animation if exists
                        $arrUpdate = array();
                        $arrUpdate['params'] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));
                        $db->update(GlobalsRevSlider::$table_layer_anims, $arrUpdate, array('handle' => $animation['handle']));

                        $id = $exist['0']['id'];
                    } else { //insert with new handle
                        $arrInsert = array();
                        $arrInsert["handle"] = 'copy_' . $animation['handle'];
                        $arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

                        $id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
                    }
                } else { //insert the animation, get the ID
                    $arrInsert = array();
                    $arrInsert["handle"] = $animation['handle'];
                    $arrInsert["params"] = stripslashes(json_encode(str_replace("'", '"', $animation['params'])));

                    $id = $db->insert(GlobalsRevSlider::$table_layer_anims, $arrInsert);
                }

                //and set the current customin-oldID and customout-oldID in slider params to new ID from $id
                $content = str_replace(array('customin-' . $animation['id'], 'customout-' . $animation['id']), array('customin-' . $id, 'customout-' . $id), $content);
            }
            //dmp(__("animations imported!",REVSLIDER_TEXTDOMAIN));
        } else {
            //dmp(__("no custom animations found, if slider uses custom animations, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
        }

        //overwrite/append static-captions.css
        if (!empty($static)) {
            //if ($updateStatic == "true") { //overwrite file
            //    RevOperations::updateStaticCss($static);
            //} else { //append
            $static_cur = RevOperations::getStaticCss();
            $static = $static_cur . "\n" . $static;
            RevOperations::updateStaticCss($static);
            //}
        }

        //overwrite/create dynamic-captions.css
        //parse css to classes
        $dynamicCss = UniteCssParserRev::parseCssToArray($dynamic);

        if (is_array($dynamicCss) && $dynamicCss !== false && count($dynamicCss) > 0) {
            foreach ($dynamicCss as $class => $styles) {
                //check if static style or dynamic style
                $class = trim($class);

                if ((strpos($class, ':hover') === false && strpos($class, ':') !== false) || //before, after
                    strpos($class, " ") !== false || // .tp-caption.imageclass img or .tp-caption .imageclass or .tp-caption.imageclass .img
                    strpos($class, ".tp-caption") === false || // everything that is not tp-caption
                    (strpos($class, ".") === false || strpos($class, "#") !== false) || // no class -> #ID or img
                    strpos($class, ">") !== false
                ) { //.tp-caption>.imageclass or .tp-caption.imageclass>img or .tp-caption.imageclass .img
                    continue;
                }

                //is a dynamic style
                if (strpos($class, ':hover') !== false) {
                    $class = trim(str_replace(':hover', '', $class));
                    $arrInsert = array();
                    $arrInsert["hover"] = json_encode($styles);
                    $arrInsert["settings"] = json_encode(array('hover' => 'true'));
                } else {
                    $arrInsert = array();
                    $arrInsert["params"] = json_encode($styles);
                }
                //check if class exists
                $result = $db->fetch(GlobalsRevSlider::$table_css, "handle = '" . $class . "'");

                if (!empty($result)) { //update
                    $db->update(GlobalsRevSlider::$table_css, $arrInsert, array('handle' => $class));
                } else { //insert
                    $arrInsert["handle"] = $class;
                    $db->insert(GlobalsRevSlider::$table_css, $arrInsert);
                }
            }
            //dmp(__("dynamic styles imported!",REVSLIDER_TEXTDOMAIN));
        } else {
            //dmp(__("no dynamic styles found, if slider uses dynamic styles, the provided export may be broken...",REVSLIDER_TEXTDOMAIN));
        }

    }

    //Al activar esta linea me da un problema en php5.5
    //Deprecated: preg_replace(): The /e modifier is deprecated, use preg_replace_callback instead ...
    //$content = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $content); //clear errors in string

    $arrSlider = @unserialize($content);
    //if(empty($arrSlider))
    //  UniteFunctionsRev::throwError("Wrong export slider file format! This could be caused because the ZipArchive extension is not enabled.");

    //update slider params
    $sliderParams = $arrSlider["params"];

    //if($sliderExists){
    //    $sliderParams["title"] = $this->arrParams["title"];
    //    $sliderParams["alias"] = $this->arrParams["alias"];
    //    $sliderParams["shortcode"] = $this->arrParams["shortcode"];
    //}

    if(isset($sliderParams["background_image"]))
        $sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath($sliderParams["background_image"]);

    $json_params = json_encode($sliderParams);

    //update slider or craete new
    //if($sliderExists){
    //    $arrUpdate = array("params"=>$json_params);
    //    $this->db->update(GlobalsRevSlider::$table_sliders,$arrUpdate,array("id"=>$sliderID));
    //}
    //else{	//new slider
    $arrInsert = array();
    $arrInsert["params"] = $json_params;
    $arrInsert["title"] = UniteFunctionsRev::getVal($sliderParams, "title","Slider1");
    $arrInsert["alias"] = UniteFunctionsRev::getVal($sliderParams, "alias","slider1");
    //$sliderID = $this->db->insert(GlobalsRevSlider::$table_sliders,$arrInsert);
    $sliderID = $wpdb->insert(GlobalsRevSlider::$table_sliders,$arrInsert);
    $sliderID = $wpdb->insert_id;
    //}

    //-------- Slides Handle -----------

    //delete current slides
    //if($sliderExists)
    //    $this->deleteAllSlides();

    //create all slides
    $arrSlides = $arrSlider["slides"];

    $alreadyImported = array();

    foreach($arrSlides as $slide) {

        $params = $slide["params"];
        $layers = $slide["layers"];

        //convert params images:
        if (isset($params["image"])) {
            //import if exists in zip folder
            if (trim($params["image"]) !== '') {
                if ($importZip === true) { //we have a zip, check if exists
                    $image = $zip->getStream('images/' . $params["image"]);
                    if (!$image) {
                        echo $params["image"] . ' not found!<br>';
                    } else {
                        if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]])) {
                            $importImage = UniteFunctionsWPRev::import_media('zip://' . $filepath . "#" . 'images/' . $params["image"], $sliderParams["alias"] . '/');

                            if ($importImage !== false) {
                                $alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]] = $importImage['path'];

                                $params["image"] = $importImage['path'];
                            }
                        } else {
                            $params["image"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $params["image"]];
                        }
                    }
                }
            }
            $params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
        }

        //convert layers images:
        foreach ($layers as $key => $layer) {
            if (isset($layer["image_url"])) {
                //import if exists in zip folder
                if (trim($layer["image_url"]) !== '') {
                    if ($importZip === true) { //we have a zip, check if exists
                        $image_url = $zip->getStream('images/' . $layer["image_url"]);
                        if (!$image_url) {
                            echo $layer["image_url"] . ' not found!<br>';
                        } else {
                            if (!isset($alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]])) {
                                $importImage = UniteFunctionsWPRev::import_media('zip://' . $filepath . "#" . 'images/' . $layer["image_url"], $sliderParams["alias"] . '/');

                                if ($importImage !== false) {
                                    $alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]] = $importImage['path'];

                                    $layer["image_url"] = $importImage['path'];
                                }
                            } else {
                                $layer["image_url"] = $alreadyImported['zip://' . $filepath . "#" . 'images/' . $layer["image_url"]];
                            }
                        }
                    }
                }
                $layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
                $layers[$key] = $layer;
            }
        }

        //create new slide
        $arrCreate = array();
        $arrCreate["slider_id"] = $sliderID;
        $arrCreate["slide_order"] = $slide["slide_order"];
        $arrCreate["layers"] = json_encode($layers);
        $arrCreate["params"] = json_encode($params);

        //$this->db->insert(GlobalsRevSlider::$table_slides,$arrCreate);
        $wpdb->insert(GlobalsRevSlider::$table_slides, $arrCreate);
    }
}


function aps_borrar_content_widgets() {
    $current_sidebars = get_option( 'sidebars_widgets' );
}



// Parsing Widgets Functions
// Thanks to http://wordpress.org/plugins/widget-settings-importexport/
function aps_projects_import_widget_data_prepare_data( $widget_data ) {
    $json_data = $widget_data;
    $json_data = json_decode( $json_data, true );

    $sidebar_data = $json_data[0];
    $widget_data = $json_data[1];

    foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
        $widgets[ $widget_data_title ] = '';
        foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
            if( is_int( $widget_data_key ) ) {
                $widgets[$widget_data_title][$widget_data_key] = 'on';
            }
        }
    }
    unset($widgets[""]);

    foreach ( $sidebar_data as $title => $sidebar ) {
        $count = count( $sidebar );
        for ( $i = 0; $i < $count; $i++ ) {
            $widget = array( );
            $widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
            $widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
            if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
                unset( $sidebar_data[$title][$i] );
            }
        }
        $sidebar_data[$title] = array_values( $sidebar_data[$title] );
    }

    foreach ( $widgets as $widget_title => $widget_value ) {
        foreach ( $widget_value as $widget_key => $widget_value ) {
            $widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
        }
    }

    $sidebar_data = array( array_filter( $sidebar_data ), $widgets );

    return $sidebar_data;
    //aps_projects_parse_import_data( $sidebar_data );
}


function aps_projects_import_widget_data( $widget_data )
{
    $sidebar_data = aps_projects_import_widget_data_prepare_data($widget_data);
    aps_projects_parse_import_data_widgets( $sidebar_data );
}

function aps_projects_parse_import_data_widgets( $import_array ) {
    global $wp_registered_sidebars;
    $sidebars_data = $import_array[0];
    $widget_data = $import_array[1];
    $current_sidebars = get_option( 'sidebars_widgets' );
    $new_widgets = array( );

    foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

        foreach ( $import_widgets as $import_widget ) :
            //if the sidebar exists
            if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) :
                $title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
                $index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
                $current_widget_data = get_option( 'widget_' . $title );
                $new_widget_name = aps_projects_get_new_widget_name( $title, $index );
                $new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

                if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
                    while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
                        $new_index++;
                    }
                }
                $current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
                if ( array_key_exists( $title, $new_widgets ) ) {
                    $new_widgets[$title][$new_index] = $widget_data[$title][$index];
                    $multiwidget = $new_widgets[$title]['_multiwidget'];
                    unset( $new_widgets[$title]['_multiwidget'] );
                    $new_widgets[$title]['_multiwidget'] = $multiwidget;
                } else {
                    $current_widget_data[$new_index] = $widget_data[$title][$index];
                    $current_multiwidget = $current_widget_data['_multiwidget'];
                    $new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
                    $multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
                    unset( $current_widget_data['_multiwidget'] );
                    $current_widget_data['_multiwidget'] = $multiwidget;
                    $new_widgets[$title] = $current_widget_data;
                }

            endif;
        endforeach;
    endforeach;

    if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
        update_option( 'sidebars_widgets', $current_sidebars );

        foreach ( $new_widgets as $title => $content )
            update_option( 'widget_' . $title, $content );

        return true;
    }

    return false;
}

function aps_projects_get_new_widget_name( $widget_name, $widget_index ) {
    $current_sidebars = get_option( 'sidebars_widgets' );
    $all_widget_array = array( );
    foreach ( $current_sidebars as $sidebar => $widgets ) {
        if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
            foreach ( $widgets as $widget ) {
                $all_widget_array[] = $widget;
            }
        }
    }
    while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
        $widget_index++;
    }
    $new_widget_name = $widget_name . '-' . $widget_index;
    return $new_widget_name;
}



function aps_projects_import_layouts_data( $layouts_data, $widget_data)
{
    //echo '<pre>'; print_r( $layouts_data ); echo '</pre>';
    $layouts_data = (array)json_decode($layouts_data);
    //echo '<pre>'; print_r( $layouts_data ); echo '</pre>';

    //Los datos de los menus importados
    $menus = $layouts_data['menus'];
    //echo '<pre>MENUS: '; print_r( $menus ); echo '</pre>';

    //Los datos de los layouts importados
    $layouts = $layouts_data['layouts'];
    //echo '<pre>LAYOUTS: '; print_r( $layouts ); echo '</pre>';

    //Obtengo los menus disponibles
    $menus_now = wp_get_nav_menus();
    //echo '<pre>MENUS NOW: '; print_r( $menus_now ); echo '</pre>';

    //Genero una correspondencia entre los menus importados y los existentes
    //A cada id de menu importado le busco el id real que se ha creado
    $map_menus = array();
    foreach($menus as $menu){
        //Es como se llamara el menu en el dato del layout
        //se identifica como menu-?
        $menu_slug = $menu->slug;
        foreach($menus_now as $menu_now){
            if ($menu_slug == $menu_now->slug){
                $map_menus['menu-'.$menu->term_id] = 'menu-'.$menu_now->term_id;
            }
        }
    }

    //echo '<pre>MAP MENUS: '; print_r( $map_menus ); echo '</pre>';
    //Ahora busco dentro de los metadata de los layouts que dato tengo que cambiar
    //echo '<pre>LAYOUTS: '; print_r( $layouts ); echo '</pre>';
    foreach($layouts as $layout)
    {
        $layout_title = $layout->title;
        $layout_id = $layout->id;

        //Podria coger el ID para buscar el layout actual pero por si acaso cojo el title
        $layout_now = get_page_by_title( $layout_title, 'OBJECT', 'aps_layout');
        if ($layout_now!=NULL){
            $layout_now_id = $layout_now->ID;
            //echo '<pre>LAYOUT ENCONTRADO: '; print_r( $layout_now ); echo '</pre>';
            //Le cambio los metada que tengan el dato del menu-? asignado
            foreach( $layout->metadata as $key=>$value)
            {
                $pos = strpos($value,'menu-');
                if ( $pos !== false && $pos === 0) {
                    //echo '<p>Tengo que cambiar: '.$key.' -> '.$value.' por '.$map_menus[$value].'</p>';
                    if (isset($map_menus[$value])){

                        //IMPORTANTE, se actualiza el menu en el layout
                        update_post_meta($layout_now_id, $key, $map_menus[$value]);
                        //echo '<p>Valor cambiado</p>';
                    }
                }
            }
        }
    }

    //Cambio tambien los datos de menu en los widgets 'Custom Menu'
    //busco dentro de los widgets el campo aps_custom_menu
    //le cambio su valor de id por el nuevo id del menu
    //ex: widgets_6 -> aps_custom_menu -> menu -> 17

    $sidebar_data = aps_projects_import_widget_data_prepare_data($widget_data);
    //echo '<pre>ANTES: '; print_r( $sidebar_data ); echo '</pre>';

    //Filtro los datos de los menus antes de generar los widgets
    if (isset($sidebar_data[1]['aps_custom_menu'])) {
        foreach($sidebar_data[1]['aps_custom_menu'] as $key=>$arr_data)
        {
            //Obtengo el numero del menu que trae
            $menu_id =  $sidebar_data[1]['aps_custom_menu'][$key]['menu'];
            //Busco el nuevo id de menu
            if (isset($map_menus['menu-'.$menu_id])) {
                $value = $map_menus['menu-'.$menu_id];
                $value = str_replace('menu-','',$value);
                $sidebar_data[1]['aps_custom_menu'][$key]['menu'] = $value;
            }
        }
    }
    //echo '<pre>DESPUES: '; print_r( $sidebar_data ); echo '</pre>';

    //Genero los widgets
    aps_projects_parse_import_data_widgets( $sidebar_data );

}