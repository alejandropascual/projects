<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_custom_menu', 'aps_register_widget' ) );


class aps_widget_custom_menu extends WP_Widget {

    public static $widget_defaults = array(
        'title'     	=> '',
        'text'			=> '',
        'menu'          => '',
        'type'          => ''
    );

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'Custom menu', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: Custom menu','widget',LANGUAGE_THEME), $widget_ops);
    }



    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;

        $instance['title'] 	= strip_tags($new_instance['title']);
        $instance['text'] 	= esc_attr($new_instance['text']);
        $instance['menu']   = $new_instance['menu'];
        $instance['type']   = $new_instance['type'];

        return $instance;
    }


    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        $list_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        $list_types = array(
            'custom-1' => _x('Expanded', 'widget', LANGUAGE_THEME),
            'custom-2' => _x('Collapsible closed', 'widget', LANGUAGE_THEME),
            'custom-2 opened' => _x('Collapsible opened', 'widget', LANGUAGE_THEME),
        );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_THEME); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_THEME); ?></label>
            <textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'menu' ); ?>"><?php _ex('Select menu:', 'widget',  LANGUAGE_THEME); ?></label>
            <select id="<?php echo $this->get_field_id( 'menu' ); ?>" name="<?php echo $this->get_field_name( 'menu' ); ?>">
                <?php
                foreach ( $list_menus as $menu ) {
                    echo '<option value="' . $menu->term_id . '"'
                        . selected( $instance['menu'], $menu->term_id, false )
                        . '>'. $menu->name . '</option>';
                }
                ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _ex('Select type:', 'widget',  LANGUAGE_THEME); ?></label>
            <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>">
                <?php
                foreach ( $list_types as $key=>$value ) {
                    echo '<option value="' . $key . '"'
                        . selected( $instance['type'], $key, false )
                        . '>'. $value . '</option>';
                }
                ?>
            </select>
        </p>

        <div style="clear: both;"></div>

    <?php
    }


    function widget( $args, $instance )
    {
        extract($args);

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $title = apply_filters( 'widget_title', $instance['title'] );
        $text = $instance['text'];
        $menu = $instance['menu'];
        $type = $instance['type'];

        echo $before_widget;

        // title
        if ( $title ) echo $before_title . $title . $after_title;

        // content
        if ( $text ) echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';

        wp_nav_menu( array(
            //'theme_location'  => $menu,
            'menu'			  => $menu,
            'container'		  => 'nav',
            'container_class' => 'nav-menu-wrap widget-menu-type-'.$type,
            //'container_id'	  => $id.'-wrap',
            'menu_class'	  => 'widget-menu',
            //'menu_id'		  => $id,
            //'depth'			  => $depth
        ) );

        echo $after_widget;
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