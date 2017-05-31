<?php



//Backup settings

// Save options ajax
add_action('wp_ajax_options_ajax_action','aps_options_ajax_action');

function aps_options_ajax_action()
{
    $nonce = $_REQUEST['security_nonce'];
    if ( !wp_verify_nonce($nonce, 'options_ajax_action') ) die('-1');

    $type = $_REQUEST['type'];

    if ($type == 'backup_options') {

        $data_options = array();
        global $aps_config;

        //Recorrer cada seccion para obtener los valores
        $op_pages = $aps_config['option_pages'];
        foreach($op_pages as $op_page)
        {
            if ($op_page['id'] == 'aps_op_backup') continue;
            $data_saved = get_option($op_page['id']);
            if ($data_saved != '') {
                $data_options[$op_page['id']] = $data_saved;
            }
        }

        $data_encoded = base64_encode( serialize( $data_options ) );
        die( $data_encoded );
    }
    else if ($type == 'restore_options') {

        $data_encoded = $_REQUEST['options'];

        if (aps_restore_options( $data_encoded ) ) {
            die(__('OK. OPTIONS HAVE BEEN RESTORED.',LANGUAGE_THEME));
        } else {
            die(__('ERROR. Text options is not correct.',LANGUAGE_THEME));
        }

    }

}



function aps_restore_options( $data_encoded )
{

    $data_decoded = unserialize( base64_decode( $data_encoded ) );

    if (is_array($data_decoded))
    {
        foreach ($data_decoded as $key => $value) {
            update_option($key, $value);
        }
        //Ahora tengo que regenerar el css-theme y css-mobile
        aps_generate_stylesheet();
        aps_generate_css_responsive();

        return true;

    } else {
        return false;
    }
}

