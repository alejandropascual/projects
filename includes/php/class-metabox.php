<?php


// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


if ( !class_exists( 'APSMetaBox' ) ) {

    class APSMetaBox
    {
        var $path_file;
        var $boxes;
        var $fields;

        function __construct($path_file)
        {
            $this->path_file = $path_file;
            add_action('load-post.php', array($this, 'setup'));
            add_action('load-post-new.php', array($this, 'setup'));
            //$this->setUp();
        }

        function setup()
        {
            $this->get_data();
            $this->add_actions();
        }

        function add_actions()
        {
            add_action('add_meta_boxes',array($this, 'init_metaboxes'));
            add_action('save_post', array($this, 'save_post'),10,2);
            //add_action('wp_print_scripts', array($this, 'add_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
        }

        function get_data()
        {
            require ($this->path_file);
            if (isset($boxes)) $this->boxes = $boxes;
            if (isset($fields)) $this->fields = $fields;
        }


        //Scripts para el admin
        function add_scripts()
        {
        }

        function init_metaboxes()
        {
            if ( !empty($this->boxes) && !empty($this->fields))
            {
                //Loop all the boxes
                foreach($this->boxes as $box)
                {
                    //Loop post-types
                    foreach($box['page'] as $page)
                    {
                        add_meta_box(
                            $box['id'],
                            $box['title'],
                            array($this,'show_metabox'),
                            $page,
                            $box['context'],
                            $box['priority'],
                            array('aps_actual_box'=>$box)
                        );

                    }
                }
            }
        }

        function show_metabox($current_post, $data)
        {
            global $post;
            if(!is_object($post)) return;

            //Coger el metabox pasado
            $box = $data['args']['aps_actual_box'];

            //Contenido
            $html = '';

            //Idiomas ??
            global $idiomas;
            if (isset($idiomas) && isset($box['idiomas']) && $box['idiomas'])
            {
                //echo '<h2>PONER IDIOMAS AQUI</h2>';
                //echo '<pre>'; print_r($idiomas); echo '</pre>';
                global $languageCodes;
                //Los tabs de idiomas
                $html .= '<div class="aps-idiomas-box">';
                $html .= '<ul class="aps-idiomas-tabs">';
                $first = true;
                foreach($idiomas['pro_idiomas'] as $ln){
                    $class_first = $first ? ' selected' : '';
                    $html .= "<li><a data-idioma=\"{$ln}\" class=\"aps-idiomas-tab{$class_first}\" href=\"#\">{$languageCodes[$ln]}</a></li>";
                    $first = false;
                }
                $html .= '</ul>';

                //El contenido
                $html .= '<div class="aps-idiomas-contents">';

                foreach($idiomas['pro_idiomas'] as $ln)
                {
                    $html .= '<div class="aps-idiomas-content content'.$ln.'">';
                    foreach($this->fields as $field) {
                        if ($field['box_id'] === $box['id']){
                            $field['post_id'] = $current_post->ID;
                            $new_field = $field;
                            if (isset($field['id'])){
                                $new_field['id'] = $field['id'].$ln;
                            }
                            if (method_exists('APSHtmlHelper', 'render_'.$new_field['type']))
                                $html .= APSHtmlHelper::render_metabox($new_field);
                        }
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }
            else
            {
                //Sin idiomas
                //Busco los fields dentro de este box
                foreach($this->fields as $field)
                {
                    //Lo necesito para obtener el dato meta
                    $field['post_id'] = $current_post->ID;
                    $content = '';
                    if ($field['box_id'] === $box['id']) {
                        if (method_exists('APSHtmlHelper', 'render_'.$field['type'])) {
                            $content = APSHtmlHelper::render_metabox($field);
                        }
                    }
                    $html .= $content;
                }
            }

            echo $html;
        }



        function can_save($post_id)
        {

            //Check nonce
            //if(!isset($_POST['custom_meta_box_nonce'])) return false;

            //Post array ?
            if (empty($_POST) || empty($_POST['post_ID']))
                return false;

            //Autosave ?
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
                return false;

            //Revision no save
            //if ( $post_object->post_type == 'revision' )
            //    return false;

            //Is post type for metabox ?
            foreach($this->boxes as $box){
                if (in_array($_POST['post_type'], $box['page']))
                    $has_to_save = true;
            }
            if (empty($has_to_save)) return false;

            //Permision
            if (!current_user_can('edit_page', $post_id))  return false;
            elseif (!current_user_can('edit_post', $post_id)) return false;

            return true;
        }


        function save_post($post_id, $post_object)
        {
            if (!$this->can_save($post_id)) return;

            //Recorro cada box
            foreach($this->fields as $field)
            {
                if ($field['type']=='subfields') {
                    foreach( $field['subfields'] as $subfield) {
                        $this->save_field($post_id, $subfield);
                    }
                } else {
                    $this->save_field($post_id, $field);
                }

            }
        }


        function save_field( $post_id, $field)
        {
            //El checkbox no recibe nada si no esta checked
            if ($field['type']==='checkbox'){
                if (isset($_POST[$field['id']])) {
                    update_post_meta($post_id, $field['id'], $_POST[$field['id']]);
                } else {
                    update_post_meta($post_id, $field['id'], '');
                }

            }

            // Como averiguar cuando select multiple viene vacio
            //categories_post, tags_post, categories_aps_project, tags_aps_project
            else if ($field['type']==='select_custom_categories') {

                foreach($field['include_custom_posts'] as $name){
                    $campo_id = $field['id'].'_'.$name;
                    if (!isset($_POST[$campo_id])) {
                        update_post_meta($post_id, $campo_id , []);
                    } else {
                        update_post_meta($post_id, $campo_id , $_POST[$campo_id]);
                    }
                }

            }

            else {
                foreach ($_POST as $key => $value) {
                    //echo '<p>Guardando '.$key.' = '.$value.'</p>';
                    //Si existe el campo recibido
                    if (isset($field['id'])) {
                        //Y que coincide
                        if (strpos($key, $field['id']) !== false) {
                            update_post_meta($post_id, $key, $_POST[$key]);
                        }

                        //A veces puede tener otros ids con el mismo campo
                        if (isset($_POST[$key . '_1'])) {
                            update_post_meta($post_id, $key . '_1', $_POST[$key . '_1']);
                        }
                        if (isset($_POST[$key . '_2'])) {
                            update_post_meta($post_id, $key . '_2', $_POST[$key . '_2']);
                        }

                    }
                }
            }
        }

    }
}