<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

// Solo ocurre una vez si el tema no tiene nada preparado al inicio
// Dos comprobaciones
// 1- Layoutbuilder instalado y sin ningun layout creado
// 2- Settings vacios





//if ( function_exists('aps_layoutbuilder_load_lang') && )

// Proceso
// Genera layouts por defecto para blog, projects, post y single project
// Restore settings default
// Datos por defecto del blog, como en import
// Asignar layouts creados a los settings

add_action('admin_init', 'aps_comprobar_default_settings');
function aps_comprobar_default_settings()
{
    //Existe plugin instalado layout builder, si no volver
    if ( !function_exists('aps_layoutbuilder_load_lang') ) return false;

    //Buscar si hay layouts creados, si los hay volver
    global $wpdb;
    $query = "SELECT ID FROM $wpdb->posts WHERE post_type='aps_layout'";
    $results = $wpdb->get_results($query);
    if ($wpdb->num_rows > 0) return false;

    //Tiene opciones generadas ya ?, si las tiene volver
    $option = get_option('aps_op_theme_style');
    if ( $option != false ) return false;


    //OK, ahora generar un layout y crear opciones por defecto asociadas a ese layout
    $args = array(
        'post_title' => 'Example layout',
        'post_type' => 'aps_layout',
        'post_status' => 'publish',
        'post_date'	=> date("Y:m:d H:i:s")
    );
    $layout_id = wp_insert_post($args);

    //Ahora genero unos metadata para este post
    $metadata = array(
        'layout_slider1' => '',
        'layout_slider1_image' => '',
        'layout_slider1_image_width' => '1200',
        'layout_slider1_image_height' => '800',
        'layout_slider1_input' => 'none',
        'layout_slider1_input_sc' => '',
        'layout_header' => 'header-2',
        'layout_header_box_top_1' => 'contact',
        'layout_header_box_top_2' => 'social',
        'layout_header_box_center_1' => 'site_title_tagline',
        'layout_header_box_center_2' => '',
        'layout_header_box_bottom_1' => '',
        'layout_header_box_bottom_2' => '',
        'layout_slider2' => '',
        'layout_slider2_image' => '',
        'layout_slider2_image_width' => '1200',
        'layout_slider2_image_height' => '800',
        'layout_slider2_input' => 'none',
        'layout_slider2_input_sc' => '',
        'layout_content' => 'content-1',
        'layout_content_widgets1_wrap' => 'main-padding-6',
        'layout_content_widgets2_left' => 'widgets_1',
        'layout_content_widgets2_left_cols' => 'normal',
        'layout_content_widgets2_wrap' => 'main-padding-6',
        'layout_content_widgets2_right' => 'widgets_1',
        'layout_content_widgets2_right_cols' => 'normal',
        'layout_content_widgets3_left' => 'widgets_1',
        'layout_content_widgets3_left_cols' => 'normal',
        'layout_content_widgets3_wrap' => 'main-padding-6',
        'layout_content_widgets4_wrap' => 'main-padding-6',
        'layout_content_widgets4_right' => 'widgets_1',
        'layout_content_widgets4_right_cols' => 'normal',
        'layout_footer' => '',
        'layout_footer_widgets1_1' => 'widgets_1',
        'layout_footer_widgets2_1' => 'widgets_1',
        'layout_footer_widgets2_2' => 'widgets_1',
        'layout_footer_widgets3_1' => 'widgets_4',
        'layout_footer_widgets3_2' => 'widgets_5',
        'layout_footer_widgets3_3' => 'widgets_6',
        'layout_footer_widgets4_1' => 'widgets_4',
        'layout_footer_widgets4_2' => 'widgets_4',
        'layout_footer_widgets4_3' => 'widgets_4',
        'layout_footer_widgets4_4' => 'widgets_4',
        'layout_socket' => 'socket-1',
        'layout_socket_box_1' => 'copyright',
        'layout_socket_box_2' => 'contact',
        'layout_responsive' => 'boxed display-extend-no',
        'layout_use_background' => 'no',
        'layout_back_image' => '',
        'layout_content_transparent' => 'no'
    );
    foreach($metadata as $key=>$value) {
        update_post_meta($layout_id, $key, $value);
    }

    //Importo las opciones por defecto del tema de ejemplo
    $options_txt = get_template_directory_uri() . '/includes/plugins/importer/data/projects-example-options.txt';
    $options_txt = wp_remote_get( $options_txt );
    $result = aps_restore_options( $options_txt['body'] );

    //Ahora asigno los layouts por defecto a las opciones
    $data = array(
        'aps_op_blog' => array('layout_default_blog'),
        'aps_op_blog_archive' => array('layout_default_archive'),
        'aps_op_post' => array('layout_default_post'),
        'aps_op_pages' => array('layout_default_page','layout_default_page_404','layout_default_page_search'),
        'aps_op_project_list' => array('layout_default_aps_project_list'),
        'aps_op_project_archive' => array('layout_default_aps_project_archive'),
        'aps_op_project_post' => array('layout_default_aps_project')
    );
    //Actualizo los datos
    foreach($data as $key=>$subkeys) {
        $option = get_option($key);
        foreach($subkeys as $subkey){
            $option[$subkey] = $layout_id;
        }
        update_option($key, $option);
    }

}