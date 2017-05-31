<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_list_projects', 'aps_register_widget' ) );


class aps_widget_list_projects extends WP_Widget
{
    public static $widget_defaults = array(
        'title'     	=> '',
        'text'			=> '',
        'select_cats'   => 'all',
        'categories'    => array(),
        'select_tags'   => 'all',
        'tags'          => array(),
        'select_skills' => 'all',
        'skills'        => array(),
        'number'        => '4',
        'orderby'       => 'title',
        'order'         => 'DESC',
        'display'       => 'image_and_text', //text, image, image_and_text ...
        'show_date'     => true
    );

    public static $fields_list = array();

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'List projects', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: List projects', 'widget', LANGUAGE_THEME), $widget_ops);

    }

    function widget( $args, $instance )
    {
        extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $text = $instance['text'];

        $terms_cats = empty($instance['categories']) ? array(0) : (array) $instance['categories'];
        $terms_tags = empty($instance['tags']) ? array(0) : (array) $instance['tags'];
        $terms_skills = empty($instance['skills']) ? array(0) : (array) $instance['skills'];

        $html = '';

        if ($terms_cats)
        {
            $query_data = array(
                'post_type'         => 'aps_project',
                'posts_per_page'    => $instance['number'],
                'orderby'           => $instance['orderby'],
                'order'             => $instance['order'],
                'post_status'       => 'publish',
                'select_cats'       => $instance['select_cats'],
                'categories'        => $terms_cats,
                'select_tags'       => $instance['select_tags'],
                'tags'              => $terms_tags,
                'select_skills'     => $instance['select_skills'],
                'skills'            => $terms_skills,
            );

            //La consulta
            $my_query = $this->aps_query_posts( $query_data );

            //En array
            $data = $this->aps_create_array_data_from_query( $my_query );

            //El template
            $templates = $this->aps_get_templates();
            $template = $templates[ $instance['display'] ];

            //Render data
            $class_html = 'widget-list-posts display-'.$instance['display'];
            $class_html .= $instance['show_date'] ? '' : ' hidden-date';
            $html .= '<div class="'.$class_html.'">';
            $html .= $this->aps_create_html_from_template( $data, $template );
            $html .= '</div>';
        }

        echo $before_widget;
        if ( $title ) echo $before_title . $title . $after_title;
        if ( $text ) echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';
        echo $html;
        echo $after_widget;
    }


    //Realiza la consulta de los posts
    function aps_query_posts( $query_data )
    {
        $query_real = $query_data; //Copia
        unset($query_real['select_cats']);
        unset($query_real['categories']);
        unset($query_real['select_tags']);
        unset($query_real['tags']);
        unset($query_real['select_skills']);
        unset($query_real['skills']);

        //Preparar filtro de project_category and project_tag and project_skill
        $tax_query = array( 'relation' => 'AND' );

        if ($query_data['select_cats'] == 'only')
        {
            $query_cats = array();
            $query_cats['project_category'] = array(
                'taxonomy' => 'project_category',
                'field' => 'id',
                'terms' => $query_data['categories']
            );
            $tax_query = array_merge($tax_query, $query_cats);
        }

        if ($query_data['select_tags'] == 'only')
        {
            $query_tags = array();
            $query_tags['project_tag'] = array(
                'taxonomy' => 'project_tag',
                'field' => 'id',
                'terms' => $query_data['tags']
            );
            $tax_query = array_merge($tax_query, $query_tags);
        }

        if ($query_data['select_skills'] == 'only')
        {
            $query_skills = array();
            $query_skills['project_skill'] = array(
                'taxonomy' => 'project_skill',
                'field' => 'id',
                'terms' => $query_data['skills']
            );
            $tax_query = array_merge($tax_query, $query_skills);
        }

        $query_real['tax_query'] = $tax_query;

        //CONSULTA
        $my_query = new WP_Query( $query_real );
        return $my_query;
    }

    //Obtiene los datos de cada post en forma de array apto para el template
    function aps_create_array_data_from_query( $my_query )
    {
        $data = array();

        if ( $my_query->have_posts() ) {
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

        return $data;
    }


    //A partir del template y del array data genera un template
    function aps_create_html_from_template( $data, $template)
    {
        $html = '<!--';
        foreach($data as $item)
        {
            $html .= '-->'.$this->aps_render_template( $template, $item).'<!--';
            //echo '<pre>'; print_r( $item ); echo '</pre>';
            //echo '<pre>'; print_r( $template ); echo '</pre>';
        }
        $html .= '-->';
        return $html;
    }


    function aps_get_templates()
    {
        return aps_dame_widget_templates();
    }

    function aps_render_template( $tmpl, $data )
    {
        $html = $tmpl;
        foreach($data as $key=>$value){
            $html = str_replace('%'.$key.'%', $value, $html);
        }
        return $html;
    }



    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] 		= strip_tags($new_instance['title']);
        $instance['text'] 		= esc_attr($new_instance['text']);

        $instance['select_cats']= in_array( $new_instance['select_cats'], array('all', 'only') ) ? $new_instance['select_cats'] : 'all';
        $instance['categories'] = (array) $new_instance['categories'];

        $instance['select_tags']= in_array( $new_instance['select_tags'], array('all', 'only') ) ? $new_instance['select_tags'] : 'all';
        $instance['tags']    	= (array) $new_instance['tags'];

        $instance['select_skills']= in_array( $new_instance['select_skills'], array('all', 'only') ) ? $new_instance['select_skills'] : 'all';
        $instance['skills']    	= (array) $new_instance['skills'];

        $instance['number']     = intval($new_instance['number']);
        $instance['order']      = esc_attr($new_instance['order']);
        $instance['orderby']    = esc_attr($new_instance['orderby']);
        $instance['display']    = esc_attr($new_instance['display']);
        $instance['show_date']  = absint($new_instance['show_date']);

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
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_THEME); ?></label>
            <textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>

        <?php

        // Las categorias y los tags
        $terms_cats = get_terms( 'project_category', array(
            'hide_empty'    => 1,
            'hierarchical'  => true
        ) );
        $terms_tags = get_terms( 'project_tag', array(
            'hide_empty'    => 1,
            'hierarchical'  => false
        ) );
        $terms_skills = get_terms( 'project_skill', array(
            'hide_empty'    => 1,
            'hierarchical'  => true
        ) );

        if ( !is_wp_error($terms_cats) ) {
            ?>
            <hr>
            <div class="aps-widget-switch-wrap">
                <span><?php _ex('Select categories:', 'widget',  LANGUAGE_THEME); ?></span>&nbsp;&nbsp;&nbsp;
                <span class="aps-widget-switch">
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_cats' ); ?>" value="all" <?php checked($instance['select_cats'], 'all'); ?> /><?php _ex('All', 'widget', LANGUAGE_THEME); ?></label>
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_cats' ); ?>" value="only" <?php checked($instance['select_cats'], 'only'); ?> /><?php _ex('Only', 'widget', LANGUAGE_THEME); ?></label>
                </span>
                <div class="aps-widget-switch-option" data-show="only">
                    <?php foreach( $terms_cats as $term ): ?>

                        <input id="<?php echo $this->get_field_id($term->term_id); ?>" type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[]" value="<?php echo $term->term_id; ?>" <?php checked( in_array($term->term_id, $instance['categories']) ); ?> />
                        <label for="<?php echo $this->get_field_id($term->term_id); ?>"><?php echo $term->name; ?></label><br />

                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        }


        if ( !is_wp_error($terms_tags) ) {
            ?>
            <hr>
            <div class="aps-widget-switch-wrap">
                <span><?php _ex('Select tags:', 'widget',  LANGUAGE_THEME); ?></span>&nbsp;&nbsp;&nbsp;
                <span class="aps-widget-switch">
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_tags' ); ?>" value="all" <?php checked($instance['select_tags'], 'all'); ?> /><?php _ex('All', 'widget', LANGUAGE_THEME); ?></label>
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_tags' ); ?>" value="only" <?php checked($instance['select_tags'], 'only'); ?> /><?php _ex('Only', 'widget', LANGUAGE_THEME); ?></label>
                </span>
                <div class="aps-widget-switch-option" data-show="only">
                    <?php foreach( $terms_tags as $term ): ?>

                        <input id="<?php echo $this->get_field_id($term->term_id); ?>" type="checkbox" name="<?php echo $this->get_field_name('tags'); ?>[]" value="<?php echo $term->term_id; ?>" <?php checked( in_array($term->term_id, $instance['tags']) ); ?> />
                        <label for="<?php echo $this->get_field_id($term->term_id); ?>"><?php echo $term->name; ?></label><br />

                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        }


        if ( !is_wp_error($terms_skills) ) {
            ?>
            <hr>
            <div class="aps-widget-switch-wrap">
                <span><?php _ex('Select skills:', 'widget',  LANGUAGE_THEME); ?></span>&nbsp;&nbsp;&nbsp;
                <span class="aps-widget-switch">
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_skills' ); ?>" value="all" <?php checked($instance['select_skills'], 'all'); ?> /><?php _ex('All', 'widget', LANGUAGE_THEME); ?></label>
                    <label><input type="radio" name="<?php echo $this->get_field_name( 'select_skills' ); ?>" value="only" <?php checked($instance['select_skills'], 'only'); ?> /><?php _ex('Only', 'widget', LANGUAGE_THEME); ?></label>
                </span>
                <div class="aps-widget-switch-option" data-show="only">
                    <?php foreach( $terms_skills as $term ): ?>

                        <input id="<?php echo $this->get_field_id($term->term_id); ?>" type="checkbox" name="<?php echo $this->get_field_name('skills'); ?>[]" value="<?php echo $term->term_id; ?>" <?php checked( in_array($term->term_id, $instance['skills']) ); ?> />
                        <label for="<?php echo $this->get_field_id($term->term_id); ?>"><?php echo $term->name; ?></label><br />

                    <?php endforeach; ?>
                </div>
            </div>
        <?php
        }

        ?>
        <hr>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _ex('Number of posts:', 'widget', LANGUAGE_THEME); ?></label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo esc_attr($instance['number']); ?>" size="2" maxlength="2" />
        </p>

        <?php

        $list_orderby = array(
            'id'        => _x( 'Order by ID', 'widget', LANGUAGE_THEME ),
            'author'    => _x( 'Order by author', 'widget', LANGUAGE_THEME ),
            'title'     => _x( 'Order by title', 'widget', LANGUAGE_THEME ),
            'date'      => _x( 'Order by date', 'widget', LANGUAGE_THEME ),
            'modified'  => _x( 'Order by modified', 'widget', LANGUAGE_THEME ),
            'rand'      => _x( 'Order by rand', 'widget', LANGUAGE_THEME ),
        );

        $list_display = array(
            'text'             => _x( 'Title only', 'widget', LANGUAGE_THEME ),
            'image'             => _x( 'Square image', 'widget', LANGUAGE_THEME ),
            'image_and_text_nocrop'    => _x( 'Featured image + title', 'widget', LANGUAGE_THEME ),
            'image_and_text_land'    => _x( 'Featured image landscape + title', 'widget', LANGUAGE_THEME ),
            'image_and_text_square'  => _x( 'Featured image square + title', 'widget', LANGUAGE_THEME ),
        );

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _ex('Sort by:', 'widget', LANGUAGE_THEME); ?></label>
            <select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
                <?php foreach( $list_orderby as $value=>$name ): ?>
                    <option value="<?php echo $value; ?>" <?php selected( $instance['orderby'], $value ); ?>><?php echo $name; ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label>
                <input name="<?php echo $this->get_field_name( 'order' ); ?>" value="ASC" type="radio" <?php checked( $instance['order'], 'ASC' ); ?> /><?php _ex('Ascending', 'widget', LANGUAGE_THEME); ?>
            </label>
            <label>
                <input name="<?php echo $this->get_field_name( 'order' ); ?>" value="DESC" type="radio" <?php checked( $instance['order'], 'DESC' ); ?> /><?php _ex('Descending', 'widget', LANGUAGE_THEME); ?>
            </label>
        </p>

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

    function aps_enqueue_styles()
    {

    }

    function aps_enqueue_script()
    {

    }

    public static function aps_sanitize_fields( $fields = array() )
    {

    }

    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

}