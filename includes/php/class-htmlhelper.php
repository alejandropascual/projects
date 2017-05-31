<?php


// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


if ( !class_exists( 'APSHtmlHelper' ) ) {

    class APSHtmlHelper
    {

        static $postmeta = array();

        static function render_metabox($field)
        {

            $post_id = $field['post_id'];
            //echo '<h3>Render post_id='.$post_id.' field: '.$field['id'].'<h3>';

            $name_method = 'render_'.$field['type'];

            $field['value'] = get_post_meta( $post_id, $field['id'], true );
            //echo '<pre>'; print_r( $field['value'] ); echo '</pre>';

            return self::$name_method($field);

        }


        static function render_metabox_old($field)
        {
            $name_method = 'render_'.$field['type'];

            //Guardo los meta data del post
            if (!isset($postmeta[$field['post_id']]))
            {
                self::$postmeta[$field['post_id']] = get_post_custom($field['post_id']);
            }

            //Obtengo el metadata
            if (isset($field['id'])){
                if (isset(self::$postmeta[$field['post_id']][$field['id']]))
                {
                    $field['value'] = self::$postmeta[$field['post_id']][$field['id']][0];
                }
            }

            //ob_start();
            //echo '<pre>'; print_r(self::$postmeta); echo '</pre>';
            //$html = ob_get_clean();
            //return $html . self::$name_method($field);
            return self::$name_method($field);
        }


        static function hr()
        {
            return '<hr class="aps-metabox-hr">';
        }

        //CABECERA
        static function render_header($field)
        {
            $class_field = (isset($field['class'])) ? $field['class']:'';

            if (isset($field['required']))
            {
                $html  = '<div class="aps-section-field '.$class_field.' type-'.$field['type'].' aps-required">';
                $html .= '<input class="aps-field-required" type="hidden" value="'.implode('::',$field['required']).'">';
            }
            else
            {
                $html = '<div class="aps-section-field '.$class_field.' type-'.$field['type'].'">';
            }
            return $html;
        }

        //label_desc para todos
        static function render_label_desc($field)
        {
            $html = '';
            $html .= '<strong class="meta-field-title">'.$field['title'].'</strong><br>';
            $html .= '<div class="meta-field-description">'.$field['desc'].'</div>';
            return $html;
        }

        //TITLE
        static function render_title($field)
        {
            $html = '';
            $class = isset($field['class']) ? $field['class'] : '';
            $html .= self::render_header($field);
            $html .= '<div class="type-title-title">'.$field['title'].'</div>';
            $html .= '<div class="'.$class.'">'.$field['desc'].'</div>';
            $html .= '</div>';
            return $html;
        }

        //DESC ok
        static function render_desc($field)
        {
            $html = '';
            $class = isset($field['class']) ? $field['class'] : '';
            $html .= self::render_header($field);
            $html .= '<strong>'.$field['title'].'</strong><br>';
            $html .= '<div class="'.$class.'">'.$field['desc'].'</div>';
            $html .= self::hr().'</div>';
            return $html;
        }


        //INPUT ok
        static function render_input($field)
        {
            $html = '';

            $html .= self::render_header($field);
            $value = nl2br($field['value']);
            $class = isset($field['class']) ? $field['class'] : '';

            $html .= self::render_label_desc($field);
            $html .= '<input type="text" class="'.$class.'" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'">';
            $html .= '<br><br>';
            $html .= self::hr().'</div>';
            return $html;
        }

        //CHECKBOX ok
        static function render_checkbox($field)
        {
            $html = '';
            $html .= self::render_header($field);

            $checked = '';
            if ($field['value'] === 'on') {
                $checked = 'checked="checked"';
            }
            $class = isset($field['class']) ? $field['class'] : '';

            $html .= '<input type="checkbox" class="'.$class.'" '.$checked.' id="'.$field['id'].'" name="'.$field['id'].'" value="on">';
            $html .= '<label for="'.$field['id'].'"><strong>&nbsp;&nbsp;'.$field['title'].'</strong></label>';
            $html .= '<div>'.$field['desc'].'</div>';
            $html .= '<br>';

            $html .= self::hr().'</div>';
            return $html;
        }

        //YES_NO ok
        static function render_yes_no($field)
        {
            if ( !isset($field['value']) || $field['value']!='yes' ){
                $field['value']='no';
            }
            $field['options'] = array('yes' => 'YES','no' => 'NO');
            return self::render_tabs_pills($field).'<div class="aps-field-separar"></div>';//.self::hr();
        }

        //TABS ok
        static function render_tabs($field)
        {
            return self::render_tabs_pills($field,'aps-tabs');
        }

        //PILLS ok
        static function render_pills($field)
        {
            return self::render_tabs_pills($field,'aps-tabs-pills');
        }

        //Lo uso para tabs, pills, yes_no
        static function render_tabs_pills($field, $type="aps-tabs-pills")
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            $html .= '<div class="'.$type.'">';

            $current_key = $field['value'];
            $first = true;
            foreach($field['options'] as $key=>$value)
            {
                $checked = ($key == $current_key) ? 'checked="checked"' : '';
                if ($current_key == null && $first) $checked = 'checked="checked"';
                $selected = ($checked=='') ? '' : 'selected';

                $html .= '<label class="aps-option-tab '.$selected.'">';
                $html .= '<input type="radio" class="aps-tab-radio" data-id="'.$field['id'].'" name="'.$field['id'].'" value="'.$key.'" '.$checked.'>';
                $html .= '<span> '.$value.'</span>';
                $html .= '</label>';
            }

            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }

        //TEXTAREA
        static function render_textarea($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            $class = isset($field['class']) ? $field['class'] : '';
            $html .= '<textarea rows="5" cols="30" class="'.$class.'" id="'.$field['id'].'" name="'.$field['id'].'">';
            $html .= esc_textarea($field['value']);
            $html .= '</textarea>';
            $html .= '<br><br>';
            $html .= self::hr().'</div>';
            return $html;
        }

        //RADIO ok
        static function render_radio($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $current_key = $field['value'];
            $first = true;
            foreach($field['options'] as $key=>$value)
            {
                $checked = ($key == $current_key) ? 'checked="checked"' : '';
                if ($current_key == null && $first) $checked = 'checked="checked"';

                $html .= '<label>';
                $html .= '<input type="radio" data-id="'.$field['id'].'" name="'.$field['id'].'" value="'.$key.'" '.$checked.'>';
                $html .= '<span> '.$value.'</span>';
                $html .= '</label><br>';
            }

            $html .= self::hr().'</div>';
            return $html;
        }

        //RADIO-IMAGE ok
        static function render_radio_image($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $current_key = $field['value'];
            $i = 0;
            $first = true;
            foreach($field['options'] as $key=>$value)
            {
                $checked = ($key == $current_key) ? 'checked="checked"' : '';
                if ($current_key == null && $first) $checked = 'checked="checked"';
                $selected = ($checked=='') ? '':'aps-radio-selected';

                $html .= '<label class="aps-radio-image '.$selected.'">';

                $image = $field['images'][$i++];
                if (strpos($image,'http://') === false)
                    $image = 'http://'.$image;
                //$image = get_template_directory_uri().'/includes/stylesheets/images/'.$image;

                $html .= '<img src="'.$image.'">';
                $html .= '<input class="input-radio-image" type="radio" data-id="'.$field['id'].'" name="'.$field['id'].'" value="'.$key.'" '.$checked.'>';
                $html .= '<div> '.$value.'</div>';
                $html .= '</label>';
                $first = false;
            }
            $html .= self::hr().'</div>';
            return $html;
        }


        //SELECT ok
        static function render_select($field, $header=true)
        {
            //echo '<pre>'; print_r( $field ); echo '</pre>';

            $html = '';
            if ($header)
            {
                $html .= self::render_header($field);
                $html .= self::render_label_desc($field);
            }
            $class = isset($field['class']) ? $field['class'] : '';

            $html .= '<select style="min-width:200px;" class="'.$class.'" id="'.$field['id'].'" name="'.$field['id'].'">';

            foreach($field['options'] as $key=>$value)
            {
                $selected = ($field['value'] == $key) ? 'selected="selected"' : '' ;
                $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
            $html .= '</select>';

            if ($header)
            {
                $html .= '<br><br>';
                $html .= self::hr().'</div>';
            }
            return $html;
        }


        //LAYOUT ok
        static function render_layout($field)
        {
            global $post;
            //echo '<pre>'; print_r($post->ID); echo '</pre>';

            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            $class = isset($field['class']) ? $field['class'] : '';

            $html .= '<div style="width:60%;float:right;">'.$field['comments'].'</div>';

            //Composicion
            $html .= '<div class="aps-layout-composition">';
            $compose = '';
            $first = true;
            foreach($field['layouts'] as $layout)
            {
                $value = get_post_meta($post->ID,$layout,true);
                if ($value)
                {
                    $compose .= $first ? $value : ','.$value;
                    $first = false;
                }
            }
            if ($compose=='')
                $compose = $field['value'];
            //$html .= '<p>'.$compose.'</p>';
            $html .= aps_dame_layout($compose);

            $html .= '</div>';
            $html .= self::hr().'</div>';
            return $html;
        }

        //SELECT-SIDEBAR
        static function render_select_sidebar($field)
        {
            $options = array();
            $options[''] = 'None';
            foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar )
            {
                //echo '<pre>'; print_r($sidebar); echo '</pre>';
                $options[$sidebar['id']] = $sidebar['name'];
            }
            $field['options'] = $options;
            return self::render_select($field);
        }

        //SELECT-MENU
        static function render_select_menu($field)
        {
            $options = array();
            $options[''] = 'None';
            $options = array_merge($options,get_registered_nav_menus());
            $field['options'] = $options;
            return self::render_select($field);
        }

        //SELECT-SLIDER
        static function render_select_slider($field)
        {
            $options = array();
            $options['slider1'] = 'Slider 1';
            $options['slider2'] = 'Slider 2';

            $active_layerslider = aps_is_active_plugin_layerslider();
            if ($active_layerslider)
                $options['layerslider'] = 'Layerslider';

            $options = array_merge($options,aps_get_list_layerslider());

            $field['options'] = $options;
            return self::render_select($field);
        }

        //IMAGE
        static function render_image($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            $value = $field['value'];

            $html .= '<div class="aps-select-image">';

            $style = "max-width:150px;";
            $html .= '<input style="'.$style.'" type="text" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" class="regular-text code">';
            $html .= '<a href="#" class="button aps_upload_image" data-modal-title="Select Image" data-button-title="Selecciona la imagen">Select</a>';

            $src = wp_get_attachment_image_src($value, 'medium');
            if (!empty($src)){
                $src = $src[0];
                $hidden = '';
            } else {
                $src = '';
                $hidden = 'hidden';
            }

            $html .= '<div class="aps-preview-image" '.$hidden.'>';
            $html .= '<img src="'.$src.'">';
            $html .= '<br><a href="#" class="aps_remove_image button">Remove</a>';
            $html .= '</div>';

            $html .= '</div>';//aps-select-image

            $html .= self::hr().'</div>';//header
            return $html;
        }

        //IMAGE WITH SIZE
        static function render_image_with_size($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            $value = $field['value'];

            //Select image
            $html .= '<div class="aps-select-image" style="float:left; margin-right: 20px;">';

            $style = "max-width:150px;";
            $html .= '<input style="'.$style.'" type="text" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" class="regular-text code">';
            $html .= '<a href="#" class="button aps_upload_image" data-modal-title="Select Image" data-button-title="Selecciona la imagen">Select</a>';

            $src = wp_get_attachment_image_src($value, 'medium');
            if (!empty($src)){
                $src = $src[0];
                $hidden = '';
            } else {
                $src = '';
                $hidden = 'hidden';
            }

            $html .= '<div class="aps-preview-image" '.$hidden.'>';
            $html .= '<img src="'.$src.'">';
            $html .= '<br><a href="#" class="aps_remove_image button" style="width:100%;">Remove</a>';

            $html .= '</div>';
            $html .= '</div>';//aps-select-image


            //Width and height
            $value_width = get_post_meta(get_the_ID(), $field['id'].'_width', true);
            if (!$value_width) {
                $value_width = '1200';
            }
            $field_width = array(
                //'box_id'	=> 'box_layout_options',
                'id'		=> $field['id'].'_width',
                'title' => __('Filter ', LANGUAGE_THEME),
                'desc'		=> __('', LANGUAGE_THEME),
                'type' 		=> 'input',
                'value'     => $value_width
            );
            $html .= self::render_input($field_width);

            $value_height = get_post_meta(get_the_ID(), $field['id'].'_height', true);
            if (!$value_height) {
                $value_height = '800';
            }
            $field_height = array(
                //'box_id'	=> 'box_layout_options',
                'id'		=> $field['id'].'_height',
                'title' => __('Filter ', LANGUAGE_THEME),
                'desc'		=> __('', LANGUAGE_THEME),
                'type' 		=> 'input',
                'value'     => $value_height
            );
            $html .= self::render_input($field_height);



            $html .= self::hr().'</div>';//header
            return $html;
        }




        //SELECT-LAYOUT
        static function render_select_layout($field)
        {
            global $post;
            $options = array();
            //$options['id-default'] = 'Default';
            //$options = array_merge($options,aps_get_list_layouts());
            $options = aps_get_list_layouts();
            $field['options'] = $options;
            $html = self::render_select($field);
            $html .= '<div class="aps-layout-selected"></div>';
            return $html;

            //Layout imagen
            //value es de la forma id-3456
            //$layout_id = preg_replace('/id-/', '', $field['value']);
            //$html .= '<div class="aps-layout-selected">';
            //$html .= aps_html_layout_with_link_for_id($layout_id);
            //$html .= '</div>';
            //return $html;
        }


        //NUMBER
        static function render_number($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $value = $field['value'];
            $class = isset($field['class']) ? $field['class'] : '';

            $html .= '<input type="number" class="'.$class.'" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" min="'.$field['options'][0].'" max="'.$field['options'][1].'">';
            $html .= '<br><br>';

            $html .= self::hr().'</div>';
            return $html;
        }


        static function render_header_box_2el($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            //Obtener los dos valores
            /*if (isset(self::$postmeta[$field['post_id']][$field['id'].'_1']))
                $value1 = self::$postmeta[$field['post_id']][$field['id'].'_1'][0];
            else
                $value1 = '';
            if (isset(self::$postmeta[$field['post_id']][$field['id'].'_2']))
                $value2 = self::$postmeta[$field['post_id']][$field['id'].'_2'][0];
            else
                $value2 = '';
            */
            //Mas facil asi
            $value1 = get_post_meta($field['post_id'], $field['id'].'_1', true);
            $value2 = get_post_meta($field['post_id'], $field['id'].'_2', true);

            $class_select = isset($field['class_select']) ? $field['class_select'] : '';

            $html .= '<div class="'.$class_select.'">';

            if (count($field['options'][0]) == 1)
            {
                $html .= '<span class="campo-fijo-left">'.$field['options'][0]['-'].'</span>';
            }
            else
            {
                $html .= '<select class="" name="'.$field['id'].'_1" id="'.$field['id'].'_1">';
                foreach($field['options'][0] as $key=>$text)
                {
                    $selected = ($value1 == $key) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$key.'" '.$selected.'>'.$text.'</option>';
                }
                $html .= '</select>';
            }

            if (count($field['options'][1]) == 1)
            {
                $html .= '<span class="campo-fijo-right">'.$field['options'][1]['-'].'</span>';
            }
            else
            {
                $html .= '<select class="" name="'.$field['id'].'_2" id="'.$field['id'].'_2">';
                foreach($field['options'][1] as $key=>$text)
                {
                    $selected = ($value2 == $key) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$key.'" '.$selected.'>'.$text.'</option>';
                }
                $html .= '</select>';
            }




            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }


        static function render_socket_box_widgets($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $n = $field['n_widgets'];

            $html .= '<div class="select-footer-box-widgets n_widgets-'.$n.'">';
            $html .= '<div class="w-wrap">';

            for ($i=0; $i<$n; $i++)
            {
                $id = $field['id'].'_'.($i+1);
                $value = get_post_meta($field['post_id'], $id, true);
                $html .= '<select name="'.$id.'" id="'.$id.'">';
                foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar )
                {
                    $selected = ($value == $sidebar['id']) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$sidebar['id'].'" '.$selected.'>'.$sidebar['name'].'</option>';
                }
                $html .= '</select>';
            }
            $html .= '</div>';
            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }


        static function render_content_box_widgets($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $n = $field['n_widgets'];

            $html .= '<div class="'.$n.' content-wrap">';

            //Left
            if ($n=='content-2' || $n=='content-3')
            {
                $id = $field['id'].'_left';
                $value = get_post_meta($field['post_id'], $id, true);

                $html .= '<div class="left">';

                //$html .= '<div class="aps-section-field aps-required left-logo-mainmenu">';
                //$html .= '<input class="aps-field-required" type="hidden" value="layout_header::">';
                //$html .= '<div class="left-logo"><small>LOGO</small></div>';
                //$html .= '<div class="left-mainmenu"><small>MAIN MENU</small></div>';
                //$html .= '</div>';

                //Select widget
                $html .= '<select name="'.$id.'" id="'.$id.'">';
                foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar )
                {
                    $selected = ($value == $sidebar['id']) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$sidebar['id'].'" '.$selected.'>'.$sidebar['name'].'</option>';
                }
                $html .= '</select>';

                //Sidebar ancho estrecho

                $id_cols = $id.'_cols';
                $html .= '<select class="select_width" name="'.$id_cols.'" id="'.$id_cols.'">';
                $options = array('normal'=>'sidebar NORMAL (250px)','narrow'=>'sidebar NARROW (200px)','wide'=>'sidebar WIDE (300px)');
                $value_cols = get_post_meta($field['post_id'], $id_cols, true);
                foreach($options as $key=>$value)
                {
                    $selected = ($value_cols == $key) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                }
                $html .= '</select>';


                $html .= '</div>';
            }

            //Main
            $id_wrap = $field['id'].'_wrap';
            $html .= '<div class="main">';
            $html .= '<div>Main content area</div>';
            //select margin del content
            $options = array(
                'main-padding-6'	=> 'Normal: 6%',
                'main-padding-0'	=> 'Full Width: 0%',
                'main-padding-2'	=> '2%',
                'main-padding-4'	=> '4%',
                'main-padding-10' => '10%',
                'main-padding-15' => '15%',
                'main-padding-20' => '20%',
                'main-padding-25' => '25%'
            );
            $value_wrap = get_post_meta($field['post_id'], $id_wrap, true);
            $html .= '<p>Select Left and Right margin for the Main content</p>';
            $html .= '<select style="min-width:200px;" class="select_width" name="'.$id_wrap.'" id="'.$id_wrap.'">';
            foreach($options as $key=>$value)
            {
                $selected = ($value_wrap == $key) ? 'selected="selected"' : '' ;
                $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
            $html .= '</select>';
            $html .= '</div>';


            //Right
            if ($n=='content-2' || $n=='content-4')
            {
                $id = $field['id'].'_right';
                $value = get_post_meta($field['post_id'], $id, true);

                $html .= '<div class="right">';

                //Select widget
                $html .= '<select name="'.$id.'" id="'.$id.'">';
                foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar )
                {
                    $selected = ($value == $sidebar['id']) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$sidebar['id'].'" '.$selected.'>'.$sidebar['name'].'</option>';
                }
                $html .= '</select>';

                //Sidebar ancho estrecho

                $id_cols = $id.'_cols';
                $html .= '<select class="select_width" name="'.$id_cols.'" id="'.$id_cols.'">';
                $options = array('normal'=>'sidebar NORMAL (250px)','narrow'=>'sidebar NARROW (200px)','wide'=>'sidebar WIDE (300px)');
                $value_cols = get_post_meta($field['post_id'], $id_cols, true);
                foreach($options as $key=>$value)
                {
                    $selected = ($value_cols == $key) ? 'selected="selected"' : '' ;
                    $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                }
                $html .= '</select>';


                $html .= '</div>';
            }
            $html .= '</div>';


            $html .= '</div>';
            return $html;
        }

        static function render_select_slider_box($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            //Obtengo todos los sliders posibles
            $options = array();
            $options['none'] = 'None';
            $options['shortcode'] = 'Shortcode';
            //$options['slider2'] = 'Slider 2';




            // Royal slider
            $active_royalslider = aps_is_active_plugin_royalslider();
            if ($active_royalslider) {
                //$options['royalslider'] = '== Royal Slider ==';
                $options = array_merge($options, aps_get_list_royalslider());
            }

            // Ever slider
            $active_everslider = aps_is_active_plugin_everslider();
            if ($active_everslider) {
                //$options['everslider'] = '== Ever Slider ==';
                $options = array_merge($options, aps_get_list_everslider());
            }

            // Layerslider
            $active_layerslider = aps_is_active_plugin_layerslider();
            if ($active_layerslider) {
                //$options['layerslider'] = '== Layer Slider ==';
                $options = array_merge($options, aps_get_list_layerslider());
            }

            // Revolution slider
            $active_revslider = aps_is_active_plugin_revolution_slider();
            if ($active_revslider) {
                //$options['revslider'] = '== Revolution Slider ==';
                $options = array_merge($options, aps_get_list_revslider());
            }

            // Las opciones
            $field['options'] = $options;

            $html .= '<div class="slider-box">';
            $html .= '<div class="slider-box-inner">';

            $html .= APSHtmlHelper::render_select($field,false);
            //Para el shortcode
            $field_id_sc = $field['id'].'_sc';
            $field_value = get_post_meta(get_the_ID(),$field_id_sc,true);
            $html .= '<div class="meta-field-description" style="color:white; padding-top:10px;">'.__('In case of shortcode paste it here',LANGUAGE_THEME).'</div>';
            //$html .= '<input type="text" class="" id="'.$field['id'].'" name="'.$field_id_sc.'" value="'.esc_attr($field_value).'">';
            $html .= '<textarea rows="4" class="" id="'.$field['id'].'" name="'.$field_id_sc.'">';
            $html .= esc_textarea($field_value);
            $html .= '</textarea>';
            //$html .= ''
            $html .= '</div>';

            $html .= '</div>';
            $html .= '</div>';
            return $html;
        }

        static function render_select_image_box($field)
        {
            $html = '';
            $html .= self::render_header($field);
            //$html .= self::render_label_desc($field);

            $html .= '<div class="image-box">';

            //Almaceno los tres campos en un array
            //para ponerlos despues dentro de una table
            $html_arr = array();
            foreach($field['fields'] as $sub_field)
            {
                $name = 'render_'.$sub_field['type'];
                $sub_field['value'] = get_post_meta($field['post_id'], $sub_field['id'], true);
                $html_arr[] = self::$name($sub_field);
            }

            //La table
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td>'.$html_arr[0].'</td>';
            if (isset($html_arr[1]))
                $html .= '<td>'.$html_arr[1].'</td>';//Esto era del ratio que ya no uso
            $html .= '</tr>';
            $html .= '</table>';


            $html .= '</div>';

            $html .= '</div>';
            return $html;
        }



        static function render_datepicker($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $class = isset($field['class']) ? $field['class'] : '';
            $id = $field['id'];


            $html .= '<div class="datepicker-box">';

            $html .= '<input class="campo datepicker '.$class.'" type="text" id="'.$id.'" name="'.$id.'" value="'.$field['value'].'">';

            //Campo extra donde se ponen los segundos de manera oculta por si me hace falta reordenar los posts por esta fecha
            $id_sec = $id.'_sec';
            $value_sec = get_post_meta($field['post_id'],$id_sec,true);
            $html .= '<input type="hidden" class="" id="'.$id_sec.'" name="'.$id_sec.'" value="'.$value_sec.'" readonly>';

            $html .= '</div>';


            $html .= '</div>';
            return $html;
        }

        static function render_map($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $class = isset($field['class']) ? $field['class'] : '';
            $html .= '<div class="'.$class.' map_canvas" id="'.$field['id'].'">MAP CANVAS en layout</div>';

            $html .= '</div>';
            return $html;
        }


        static function render_gallery($field)
        {

            //wp_enqueue_media();
            //wp_enqueue_script( 'admin-all' );
            //wp_localize_script( 'admin-all', 'locals', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $class = isset($field['class']) ? $field['class'] : '';
            $html .= '<a class="button aps-upload-gallery" id="aps-upload-gallery" rel="tooltip" data-original-title="My tooltip" data-placement="top">IMAGES</a>';
            $html .= '<input class="hidden '.$class.'" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$field['value'].'">';
            $html .= '<ul class="aps-gallery-images"></ul>';



            $html .= '</div>';
            return $html;
        }

        static function render_division($field)
        {
            return '<div class="aps-division"></div>';
        }

        //pendiente de revisar
        static function render_tiny_mce($field)
        {
            $html = '';

            //ids tiny-mce in the range od [a-z] only, me lo estropea
            //$field['id'] = preg_replace('/[^a-zA-Z_]/', '', $field['id']);

            //Only for ajax elements needs this
            $user_id = get_current_user_id();
            if (get_user_meta($user_id, 'rich_editing', true) == "true" && isset($field['ajax']))
            {
                //replace new lines with brs
                $field['value'] = str_replace("\n", "<br>", $field['value']);
            }

            $html .= self::render_label_desc($field);

            if (!isset($field['buttons'])){
                $field['buttons'] = array(
                    //'formatselect',
                    //'forecolor',
                    //'|',
                    'bold',
                    'italic',
                    'underline',
                    'strikethrough',
                    '|',
                    'bullist',
                    'numlist',
                    //'blockquote',
                    '|',
                    'justifyleft',
                    'justifycenter',
                    'justifyright',
                    'justifyfull',
                    '|',
                    'image',
                    'link',
                    'unlink',
                    'spellchecker',
                    'charmap',
                    'wp_fullscreen',
                    'wp_adv',
                    //'fontselect',
                    //'fontsizeselect',
                );

            }
            $buttons = implode(',', $field['buttons']);

            ob_start();
            wp_editor(
                $field['value'],
                $field['id']
            /*array(
                'textarea_name' => $field['id'],
                'editor_class' => '',
                'media_buttons' => true,
                'textarea_rows' => 10,
                //'remove_linebreaks'=>false,
                //'convert_newlines_to_brs'=>true,
                //'remove_redundant_brs'=>false,
                //'wpautop'=>true,
                'tinymce' => array('theme_advanced_buttons1' => $buttons)
            )*/
            );
            $html .= ob_get_clean();

            $html .= '<br><br>';

            //Esconder en js el campo content del post 1 sola vez
            static $count = 0; $count++;
            if ($count==1 && isset($field['replace_post_content']) && $field['replace_post_content']==true){
                $html .= "<script>jQuery('#postdivrich').hide();</script>";
            }

            return $html;
        }

        static function render_select_multiple_chosen($field, $header = false)
        {
            $html = '';
            if ($header)
            {
                $html .= self::render_header($field);
                $html .= self::render_label_desc($field);
            }

            $class = isset($field['class']) ? $field['class'] : '';
            $html .= '<select style="min-width:400px;" multiple="multiple" size="5" data-placeholder="Select..." class="chosen-select '.$class.'" id="'.$field['id'].'" name="'.$field['id'].'[]">';

            //Devuelve un array de valores
            $meta_values = get_post_meta( get_the_ID(), $field['id'],false);
            if ($meta_values){
                $meta_values = $meta_values[0];
            }
            //echo '<pre>'; print_r( $meta_values ); echo '</pre>';

            foreach($field['options'] as $key=>$value)
            {
                $selected = ( in_array($key, $meta_values) ) ? 'selected="selected"' : '' ;
                $html .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
            }
            $html .= '</select>';


            if ($header)
            {
                $html .= '<br><br>';
                $html .= self::hr().'</div>';
            }
            return $html;
        }

        static function render_select_custom_categories($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            //Extraer las categorias de los post type incluidos
            //para un multiple select

            $post_types = get_post_types(array('public' => true), 'objects');
            foreach( $post_types as $post_type)
            {
                if (isset($field['exclude_custom_posts']) && in_array($post_type->name, $field['exclude_custom_posts'])) continue;
                if ( isset($field['include_custom_posts']) && !in_array($post_type->name, $field['include_custom_posts']) ) continue;

                //Voy a generar un field para cada custom post
                $options = [];
                $taxonomies = get_object_taxonomies($post_type->name);
                foreach ($taxonomies as $taxonomy)
                {
                    if ( ($field['type-hierarchy']=='yes' && is_taxonomy_hierarchical($taxonomy)) ||
                        ($field['type-hierarchy']=='no' && !is_taxonomy_hierarchical($taxonomy)) ) {
                        $terms = get_terms($taxonomy);
                        foreach ($terms as $term) {
                            $value = $taxonomy . '::' . $term->slug;
                            $options[$value] = $term->name;
                        }
                    }
                }
                $cat_name = $field['type-hierarchy']=='yes' ? 'CATEGORIES' : 'TAGS';
                $subfield = array(
                    'title' => __('Filter ', LANGUAGE_THEME).$cat_name,
                    'desc' => __('Select multiple or leave it blank for all.', LANGUAGE_THEME),
                    'id'=> $field['id'].'_'.$post_type->name,
                    'type' => 'select_multiple_chosen',
                    'options' => $options,
                    'values' => ''//los obtiene el render
                );
                $html .= self::render_select_multiple_chosen($subfield, true);
                //echo '<h2>RENDERIZAONDO'.$post_type->name.'</h2>';
                //echo '<pre>'; print_r( $options ); echo '</pre>';
            }

            $html .= '</div>';
            return $html;
        }

        static function render_subfields($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);
            //echo '<pre>'; print_r( $field ); echo '</pre>';

            foreach($field['subfields'] as $subfield)
            {
                $subfield['post_id'] = $field['post_id'];
                $html .= self::render_metabox($subfield);
            }

            $html .= '</div>';
            return $html;
        }


        static function render_color($field)
        {
            $html = '';
            $html .= self::render_header($field);
            $html .= self::render_label_desc($field);

            $value = $field['value'];
            $class = $field['class'];

            $html .= '<input type="text" class="aps-color-field '.$class.'" id="'.$field['id'].'" name="'.$field['id'].'" value="'.$value.'" data-default-color="#ffffff">';

            $html .= self::hr().'</div>';
            return $html;
        }

    } //class APSHtmlHelper
} //if (!defined...