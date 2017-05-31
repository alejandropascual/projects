<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_tabs_posts', 'aps_register_widget' ) );


class aps_widget_tabs_posts extends WP_Widget
{
    public static $widget_defaults = array(
        'title'      => '',
        'tax_tab1'   => 'all',
        'tax_tab2'   => 'all',
        'tax_tab3'   => 'all',
        'number'     => '5',
        'display'    => 'image',
        'show_date'  => true
    );

    public static $fields_list = array();

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'Tabs Posts', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: Tabs Posts', 'widget', LANGUAGE_THEME), $widget_ops);
    }

    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] 		= strip_tags($new_instance['title']);
        $instance['number']     = intval($new_instance['number']);
        $instance['display']    = $new_instance['display'];
        $instance['tax_tab1']   = $new_instance['tax_tab1'];
        $instance['tax_tab2']   = $new_instance['tax_tab2'];
        $instance['tax_tab3']   = $new_instance['tax_tab3'];
        $instance['show_date'] = absint($new_instance['show_date']);
        return $instance;
    }


    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_THEME); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _ex('Number of items for each Tab:', 'widget', LANGUAGE_THEME); ?></label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo esc_attr($instance['number']); ?>" size="2" maxlength="2" />
        </p>

        <?php

        // Las categorias y los tags
        $terms_cats = get_terms( 'category', array(
            'hide_empty'    => 1,
            'hierarchical'  => true
        ) );
        $terms_tags = get_terms( 'tag', array(
            'hide_empty'    => 1,
            'hierarchical'  => false
        ) );

        $all_terms = array();
        if (is_array($terms_cats)) {
            $all_terms = array_merge( $all_terms, $terms_cats );
        }
        if (is_array($terms_tags)) {
            $all_terms = array_merge( $all_terms, $terms_tags );
        }


        $list_display = array(
            'text'             => _x( 'Title only', 'widget', LANGUAGE_THEME ),
            'image'             => _x( 'Square image', 'widget', LANGUAGE_THEME ),
            'image_and_text_nocrop'    => _x( 'Featured image + title', 'widget', LANGUAGE_THEME ),
            'image_and_text_land'    => _x( 'Featured image landscape + title', 'widget', LANGUAGE_THEME ),
            'image_and_text_square'  => _x( 'Featured image square + title', 'widget', LANGUAGE_THEME ),
        );

        $campos = array('tax_tab1','tax_tab2','tax_tab3');
        $index = 1;
        foreach($campos as $campo) {
            ?>
            <p>
                <label
                    for="<?php echo $this->get_field_id($campo); ?>"><?php _ex('Category/Tag fot TAB '.$index++.':', 'widget', LANGUAGE_THEME); ?></label>
                <select id="<?php echo $this->get_field_id($campo); ?>"
                        name="<?php echo $this->get_field_name($campo); ?>">
                    <?php
                    foreach ($all_terms as $term)
                    {
                        $term_text = $term->taxonomy.':'.$term->slug;
                        echo '<option value="'.$term_text. '"'
                            . selected($instance[$campo], $term_text, false)
                            . '>' . $term->name . '</option>';
                    }
                    ?>
                </select>
            </p>
        <?php } ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _ex('Display:', 'widget', LANGUAGE_THEME); ?></label>
            <select id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ); ?>">
                <?php foreach( $list_display as $value=>$name ): ?>
                    <option value="<?php echo $value; ?>" <?php selected( $instance['display'], $value ); ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php _ex('Show date', 'widget', LANGUAGE_THEME); ?>
                <input type="checkbox" name="<?php echo $this->get_field_name( 'show_date' ); ?>" value="1" <?php checked($instance['show_date']); ?> />
        </p>


        <div style="clear: both;"></div>

    <?php


    }


    function widget( $args, $instance )
    {
        extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $number = $instance['number'];
        $display = $instance['display'];

        $tax_tabs = array();
        $tax_tabs[] = $instance['tax_tab1'];
        $tax_tabs[] = $instance['tax_tab2'];
        $tax_tabs[] = $instance['tax_tab3'];
        $tmpl_name = $display;

        $html  = '';
        //$html .= '<p>'.$tax_tab1.' / '.$tax_tab2.' / '.$tax_tab3.'</p>';

        //Los tabs
        $html .= '<div class="widget-tabs-wrap">';
        $index = 0;
        foreach($tax_tabs as $tax_tab)
        {
            $term_explode = explode(':',$tax_tab);
            $taxonomy = $term_explode[0];
            $term_slug = $term_explode[1];
            $term = get_term_by('slug',$term_slug,$taxonomy);
            $class_active = ($index==0) ? ' active' : '';
            $html .= '<a class="widget-tab'.$class_active.'" data-tab="'.$index++.'">'.$term->name.'</a>';
        }
        $html .= '</div>';

        //Contenido de cada tab
        $html .= '<div class="widget-tab-content-wrap">';
        $html .= '<div class="widget-tab-content active" data-tab="0">'.$this->html_for_taxonomy($tax_tabs[0], $number,$tmpl_name).'</div>';
        $html .= '<div class="widget-tab-content" data-tab="1">'.$this->html_for_taxonomy($tax_tabs[1], $number,$tmpl_name).'</div>';
        $html .= '<div class="widget-tab-content" data-tab="2">'.$this->html_for_taxonomy($tax_tabs[2], $number,$tmpl_name).'</div>';
        $html .= '</div>';

        //Class wrapper html
        $class_html = 'display-'.$tmpl_name;
        $class_html .= $instance['show_date'] ? '' : ' hidden-date';

        //El widget
        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;
        echo '<div class="'.$class_html.'">'.$html.'</div>';
        echo $after_widget;
    }


    function html_for_taxonomy($term_text, $number_of_posts, $template_name)
    {
        $term_explode = explode(':',$term_text);
        $taxonomy = $term_explode[0];
        $term_slug = $term_explode[1];

        $args = array(
            'post_type'         => 'post',
            'posts_per_page'    => $number_of_posts,
            'tax_query'         => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => array($term_slug)
                )
            )
        );

        $my_query = new WP_Query( $args );

        //Prepara un array de datos de cada post
        $data = array();

        if ($my_query->have_posts()) {
            while ( $my_query->have_posts() ) {
                $my_query->the_post();

                $data_post = array();
                $data_post['title'] = get_the_title();
                $data_post['link'] = esc_url( get_permalink() );
                $data_post['date'] = get_the_date('F d Y');

                $image =  aps_get_image_resized_for_post_id( get_the_ID(), 120, 120, 0, 'no');
                $data_post['image_square_url'] = $image['resized']['url'];

                $image =  aps_get_image_resized_for_post_id( get_the_ID(), 290, 80, 0, 'no');
                $data_post['image_land_url'] = $image['resized']['url'];

                $image =  aps_get_image_resized_for_post_id( get_the_ID(), 290, 0, 0, 'no');
                $data_post['image_nocrop_url'] = $image['resized']['url'];

                $data[] = $data_post;
            }
        }
        wp_reset_postdata();

        //Template
        $templates = $this->aps_get_templates();
        $template = $templates[$template_name];
        return $this->aps_create_html_from_template( $data, $template);
    }

    //A partir del template y del array data genera un template
    function aps_create_html_from_template( $data, $template)
    {
        $html = '<!--';
        foreach($data as $item)
        {
            $html .= '-->'.$this->aps_render_template( $template, $item).'<!--';
        }
        $html .= '-->';
        return $html;
    }

    function aps_render_template( $tmpl, $data )
    {
        $html = $tmpl;
        foreach($data as $key=>$value){
            $html = str_replace('%'.$key.'%', $value, $html);
        }
        return $html;
    }

    function aps_get_templates()
    {
        return aps_dame_widget_templates();
    }

}