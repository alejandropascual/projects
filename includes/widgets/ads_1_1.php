<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_ads_1_1', 'aps_register_widget' ) );


class aps_widget_ads_1_1 extends WP_Widget
{
    public static $widget_defaults = array(
        //'title'     	=> __('', LANGUAGE_THEME),
        //'text'			=> '',
        'image'    => '',
        'link'     => ''
    );

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'ADS 1x1', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: ADS 1x1', 'widget', LANGUAGE_THEME), $widget_ops);
    }

    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['image'] = isset($new_instance['image']) ? $new_instance['image'] : '';
        $instance['link'] = isset($new_instance['link']) ? $new_instance['link'] : '';
        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $image = empty( $instance['image'] ) ? '' : $instance['image'];
        $link = empty( $instance['link'] ) ? '' : $instance['link'];
        ?>

        <p>

                <label><?php echo _x( 'Image', 'widget', LANGUAGE_THEME ).' :'; ?>
                    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php echo $image; ?>" />
                </label>
                <label><?php echo _x( 'Link Image', 'widget', LANGUAGE_THEME ).' :'; ?>
                    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $link; ?>" />
                </label>
        </p>


    <?php
    }

    function widget( $args, $instance )
    {
        extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $image = empty( $instance['image'] ) ? '' : $instance['image'];
        $link = empty( $instance['link'] ) ? '' : $instance['link'];

        $html = '';
        if ($image != '' && $link != '') {
            $html .= '<a class="wd-image-ad-full" target="_blank" href="'.$link.'"><img src="'.$image.'"></a>';
        }


        echo $before_widget;
        echo $html;
        echo $after_widget;
    }
}