<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }


add_action( 'widgets_init', array( 'aps_widget_logo_title', 'aps_register_widget' ) );


class aps_widget_logo_title extends WP_Widget
{
    public static $widget_defaults = array(
        //'title'     	=> __('', LANGUAGE_THEME),
        //'text'			=> '',
        'show_logo'     => '',
        'show_title'    => '',
        'show_tagline'  => '',
        'logo_width'    => '100%'
    );

    function __construct()
    {
        $widget_ops = array( 'description' => _x( 'Logo / Title', 'widget', LANGUAGE_THEME ) );
        parent::WP_Widget(false, _x('ELAP: Logo / Title', 'widget', LANGUAGE_THEME), $widget_ops);
    }

    public static function aps_register_widget()
    {
        register_widget( get_class() );
    }

    function update( $new_instance, $old_instance )
    {
        //$instance['title'] 	      = strip_tags($new_instance['title']);
        //$instance['text'] 	      = esc_attr($new_instance['text']);
        $instance['show_logo']    = absint($new_instance['show_logo']);
        $instance['show_title']   = absint($new_instance['show_title']);
        $instance['show_tagline'] = absint($new_instance['show_tagline']);
        $instance['logo_width']   = $new_instance['logo_width'];
        $instance['logo_float']   = $new_instance['logo_float'];

        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        $logo_float = array(
            'left' => 'Left',
            'center' => 'Center',
            'right' => 'Right'
        );

        ?>
        <?php /*
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _ex('Title:', 'widget',  LANGUAGE_THEME); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _ex('Text:', 'widget',  LANGUAGE_THEME); ?></label>
            <textarea id="<?php echo $this->get_field_id( 'text' ); ?>" rows="10" class="widefat" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>
        */ ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'show_logo' ); ?>"><?php _ex('Show LOGO', 'widget', LANGUAGE_THEME); ?>
            <input type="checkbox" name="<?php echo $this->get_field_name( 'show_logo' ); ?>" value="1" <?php checked($instance['show_logo']); ?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'logo_width' ); ?>"><?php _ex('Logo width in %:', 'widget',  LANGUAGE_THEME); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'logo_width' ); ?>" class="" name="<?php echo $this->get_field_name( 'logo_width' ); ?>" value="<?php echo esc_attr($instance['logo_width']); ?>" />
        </p>

        <label
            for="<?php echo $this->get_field_id('logo_float'); ?>"><?php _ex('Logo position:', 'widget', LANGUAGE_THEME); ?></label>
            <select id="<?php echo $this->get_field_id('logo_float'); ?>"
                name="<?php echo $this->get_field_name('logo_float'); ?>">
            <?php
            foreach ($logo_float as $key=>$value)
            {
                echo '<option value="'.$key. '"'
                    . selected($instance['logo_float'], $key, false)
                    . '>' . $value . '</option>';
            }
            ?>
        </select>

        <p>
            <label for="<?php echo $this->get_field_id( 'show_title' ); ?>"><?php _ex('Show TITLE', 'widget', LANGUAGE_THEME); ?>
            <input type="checkbox" name="<?php echo $this->get_field_name( 'show_title' ); ?>" value="1" <?php checked($instance['show_title']); ?> />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'show_tagline' ); ?>"><?php _ex('Show TAGLINE', 'widget', LANGUAGE_THEME); ?>
            <input type="checkbox" name="<?php echo $this->get_field_name( 'show_tagline' ); ?>" value="1" <?php checked($instance['show_tagline']); ?> />
        </p>

        <div style="clear: both;"></div>

        <?php
    }

    function widget( $args, $instance )
    {
        extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

        //$title = apply_filters( 'widget_title', $instance['title'] );
        //$text = $instance['text'];

        $html = '';
        if ($instance['show_logo'])
        {
            $src = aps_image_src(aps_get_option('logo_top'), 'full');
            $style = 'width:'.$instance['logo_width'].';';
            $float = $instance['logo_float'];
            if ($float == 'center') {
                $style .= 'margin:0 auto;';
            } else if ($float == 'right') {
                $style .= 'float:right;';
            }
            $html .= '<a class="wd-image-logo-wrap" href="'.site_url().'"><div class="wd-image-logo" style="'.$style.'"><img src="'.$src.'"></div></a>';
        }
        if ($instance['show_title'])
        {
            $title = get_bloginfo('name');
            $html .= '<h1 class="wd-site-title"><a href="'.site_url().'">'.$title.'</a></h1>';
        }
        if ($instance['show_tagline'])
        {
            $tagline = get_bloginfo('description');
            $html .= '<h3 class="wd-site-tagline">'.$tagline.'</h3>';
        }

        echo $before_widget;
        //if ( $title ) echo $before_title . $title . $after_title;
        //if ( $text ) echo '<div class="widget-info">' . apply_filters('get_the_excerpt', $text) . '</div>';
        echo $html;
        echo $after_widget;
    }
}