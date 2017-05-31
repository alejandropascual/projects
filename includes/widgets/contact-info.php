<?php


if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_contact_info', 'aps_register_widget' ) );


class aps_widget_contact_info extends WP_Widget {

    public static $widget_defaults = array (
        'title'     	=> '',
        'text'			=> '',
        'links'     => array()
    );

    public static $social_icons = array();

    function aps_widget_contact_info()
    {
        $widget_ops = array( 'description' => _x( 'Contact info', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: Contact info','widget',LANGUAGE_THEME), $widget_ops);

        self::$social_icons = aps_get_list_of_social_icons();

    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $links = empty( $instance['links'] ) ? array() : $instance['links'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_THEME); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_THEME); ?></label>
            <textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>

        <h4><?php _ex('Social links:', 'widget', LANGUAGE_THEME); ?></h4>

        <p>
            <?php foreach ( self::$social_icons as $slug=>$title ) :
                $val = isset($links[ $slug ]) ? esc_attr($links[ $slug ]) : '';
                $title2 =  strtoupper( str_replace('social_','',$slug) );
                ?>

                <label><?php echo $title2 . ':'; ?>
                    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'links' ) . '[' . esc_attr($slug) . ']'; ?>" value="<?php echo $val; ?>" />
                </label>
                </br>
            <?php endforeach; ?>
        </p>

        <div style="clear: both;"></div>

        <?php
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['text'] = $new_instance['text'];
        $instance['links'] = isset($new_instance['links']) ? $new_instance['links'] : array();
        return $instance;
    }

    function widget( $args, $instance )
    {
        extract( $args );

        $list = aps_get_list_of_social_icons();

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        $title = apply_filters( 'widget_title', $instance['title'] );
        $text = $instance['text'];
        $links = $instance['links'];


        echo $before_widget ;

        // title
        if ( $title ) echo $before_title . $title . $after_title;

        // content
        if ( $text ) echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';

        // links social
        if ( !empty($links) )
        {
            echo '<div class="aps-social-icons">';
            foreach( $links as $name=>$link)
            {
                if ( !$link ) continue;
                $class_icon = 'fa fa-'.$list[$name];
                if ($name == 'social_email') {
                    $link = 'mailto:'.$link;
                }

                printf( '<a class="%1$s" href="%2$s" target="_blank" title="%3$s"><span class="icon-text">%3$s</span></a>',
                    $class_icon,
                    esc_attr($link),
                    isset(self::$social_icons[ $name ]) ? esc_attr(self::$social_icons[ $name ]) : ''
                );
            }
            echo '</div>';
        }

        echo $after_widget;
    }


    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

}