<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_ads_2_2', 'aps_register_widget' ) );


class aps_widget_ads_2_2 extends WP_Widget
{
    public static $widget_defaults = array(
        //'title'     	=> __('', LANGUAGE_THEME),
        //'text'			=> '',
        'images'    => array('','','',''),
        'links'     => array('','','','')
    );

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'ADS 2x2', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: ADS 2x2', 'widget', LANGUAGE_THEME), $widget_ops);
    }

    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['images'] = isset($new_instance['images']) ? $new_instance['images'] : array('','','','');
        $instance['links'] = isset($new_instance['links']) ? $new_instance['links'] : array('','','','');
        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $images = empty( $instance['images'] ) ? array('','','','') : $instance['images'];
        $links = empty( $instance['links'] ) ? array('','','','') : $instance['links'];
        ?>

        <p>
            <?php
            for ( $i = 0; $i < count($images); $i++ ) :
                $index = $i+1;
                $val_im = $images[$i];
                $val_li = $links[$i];
                ?>
                <label><?php echo _x( 'Image', 'widget', LANGUAGE_THEME ).$index.' :'; ?>
                    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'images' ) . '[' . $i . ']'; ?>" value="<?php echo $val_im; ?>" />
                </label>
                <label><?php echo _x( 'Link Image', 'widget', LANGUAGE_THEME ).$index.' :'; ?>
                    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'links' ) . '[' . $i . ']'; ?>" value="<?php echo $val_li; ?>" />
                </label>
            <?php endfor; ?>
        </p>


        <?php
    }

    function widget( $args, $instance )
    {
        extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );
        $images = empty( $instance['images'] ) ? array() : $instance['images'];
        $links = empty( $instance['links'] ) ? array() : $instance['links'];

        $html = '';
        if ( !empty($images) && !empty($links) )
        {
            for ( $i = 0; $i < count($images); $i++ ) {
                $index = $i+1;
                $src = $images[$i];
                $href = $links[$i];
                $html .= '<a class="wd-image-ad" target="_blank" href="'.$href.'"><img src="'.$src.'"></a>';
            }
        }

        echo $before_widget;
        echo $html;
        echo $after_widget;
    }
}