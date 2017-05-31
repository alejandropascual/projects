<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if( ! class_exists( 'ApsMegaMenu' ) )
{
    class ApsMegaMenu
    {
        function ApsMegaMenu()
        {
            // add css and js for the menu page
            add_action('admin_menu', array($this, 'add_css_js_menu_header'));

            // replace arguments nav menu, use front end walker
            add_filter('wp_nav_menu_args', array($this,'nav_menu_arguments'), 100);

            // save data menu item
            add_action('wp_update_nav_menu_item',array($this,'save_custom_nav_fields'),10,3);

            // walker menu edit
            add_filter('wp_edit_nav_menu_walker',array($this,'edit_menu_walker'),10,2);
        }

        function add_css_js_menu_header()
        {
            if(basename( $_SERVER['PHP_SELF']) == "nav-menus.php" )
            {
                //wp_enqueue_style(  );
                //wp_enqueue_script(  );
            }
        }

        function nav_menu_arguments( $arguments )
        {
            $arguments['walker'] 				= new ApsMegaMenuShow();
            return $arguments;
        }

        function save_custom_nav_fields($menu_id, $menu_item_db_id, $args)
        {
            $keys = array('aps_megamenu','aps_newrow','aps_checkbox_desc', 'aps_desc');

            foreach( $keys as $key )
            {
                if( !isset($_POST['menu-item-'.$key][$menu_item_db_id]) )
                {
                    $_POST['menu-item-'.$key][$menu_item_db_id] = "";
                }
                $value = $_POST['menu-item-'.$key][$menu_item_db_id];
                update_post_meta( $menu_item_db_id, '_menu-item-'.$key, $value );
            }
        }

        function edit_menu_walker($walker, $menu_id)
        {
            return 'ApsMegaMenuEdit';
        }

    }
}


// Modified version of  ../includes/nav-menu.php

if( ! class_exists( 'ApsMegaMenuEdit' ) )
{
    class ApsMegaMenuEdit extends Walker_Nav_Menu
    {
        function start_lvl( &$output, $depth = 0, $args = array() ) {}

        function end_lvl( &$output, $depth = 0, $args = array() ) {}

        function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            global $_wp_nav_menu_max_depth;
            $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

            ob_start();
            $item_id = esc_attr( $item->ID );
            $removed_args = array(
                'action',
                'customlink-tab',
                'edit-menu-item',
                'menu-item',
                'page-tab',
                '_wpnonce',
            );

            $original_title = '';
            if ( 'taxonomy' == $item->type ) {
                $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
                if ( is_wp_error( $original_title ) )
                    $original_title = false;
            } elseif ( 'post_type' == $item->type ) {
                $original_object = get_post( $item->object_id );
                $original_title = get_the_title( $original_object->ID );
            }

            $classes = array(
                'menu-item menu-item-depth-' . $depth,
                'menu-item-' . esc_attr( $item->object ),
                'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
            );

            $title = $item->title;

            if ( ! empty( $item->_invalid ) ) {
                $classes[] = 'menu-item-invalid';
                /* translators: %s: title of menu item which is invalid */
                $title = sprintf( __( '%s (Invalid)' ), $item->title );
            } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
                $classes[] = 'pending';
                /* translators: %s: title of menu item in draft status */
                $title = sprintf( __('%s (Pending)'), $item->title );
            }

            $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

            $submenu_text = '';
            if ( 0 == $depth )
                $submenu_text = 'style="display: none;"';

            ?>
        <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
            <dl class="menu-item-bar">
                <dt class="menu-item-handle">
                    <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item' ); ?></span></span>
					<span class="item-controls">
						<span class="item-type item-type-normal"><?php echo esc_html( $item->type_label ); ?></span>
                        <span class="item-type item-type-column"><?php _e('COLUMN', LANGUAGE_THEME); ?></span>
                        <span class="item-type item-type-megamenu"><?php _e('MEGA-MENU', LANGUAGE_THEME); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
                            echo wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'action' => 'move-up-menu-item',
                                        'menu-item' => $item_id,
                                    ),
                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                ),
                                'move-menu_item'
                            );
                            ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
                            echo wp_nonce_url(
                                add_query_arg(
                                    array(
                                        'action' => 'move-down-menu-item',
                                        'menu-item' => $item_id,
                                    ),
                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                ),
                                'move-menu_item'
                            );
                            ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                        ?>"><?php _e( 'Edit Menu Item' ); ?></a>
					</span>
                </dt>
            </dl>

            <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
                <?php if( 'custom' == $item->type ) : ?>
                    <p class="field-url description description-wide">
                        <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                            <?php _e( 'URL' ); ?><br />
                            <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                        </label>
                    </p>
                <?php endif; ?>
                <p class="description description-thin description-nav-label">
                    <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                        <span class="item-type-normal"><?php _e( 'Navigation Label' ); ?></span><span class="item-type-column"><?php _e( 'Column name' ); ?></span><br />
                        <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                    </label>
                </p>
                <p class="description description-thin description-title-attr">
                    <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                        <?php _e( 'Title Attribute' ); ?><br />
                        <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                    </label>
                </p>
                <p class="description description-wide aps_mega_menu_title_column">
                    <?php _e( 'The Navigation Label is the Title of this column.' ); ?>
                    <br />
                    <span style="font-size: 11px;"><?php _e( 'If you dont want a title write a single dash "-".' ); ?></span>
                </p>

                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
                        <?php _e( 'Open link in a new window/tab' ); ?>
                    </label>
                </p>
                <p class="field-css-classes description description-thin">
                    <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                        <?php _e( 'CSS Classes (optional)' ); ?><br />
                        <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                    </label>
                </p>
                <p class="field-xfn description description-thin">
                    <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                        <?php _e( 'Link Relationship (XFN)' ); ?><br />
                        <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                    </label>
                </p>
                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                        <?php _e( 'Description' ); ?><br />
                        <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                        <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.'); ?></span>
                    </label>
                </p>


                <!-- Mega menu options begin -->

                <!-- field aps_megamenu -->

                <?php
                $key = 'menu-item-aps_megamenu';
                $label = __('MEGA-MENU: Use as Mega Menu ', LANGUAGE_THEME);
                $value = get_post_meta( $item_id, '_'.$key, true);
                if($value != "") $value = "checked='checked'";
                ?>

                <p class="field-custom description description-wide aps_mega_menu <?php echo $key; ?>">
                    <label for="edit-<?php echo $key.'-'.$item_id; ?>">
                        <?php _e( $label ); ?>
                        <input type="checkbox" value="active" id="edit-<?php echo $key.'-'.$item_id; ?>" class=" <?php echo $key; ?>" name="<?php echo $key . "[". $item_id ."]";?>" <?php echo $value; ?> />
                    </label>
                </p>


                <!-- field aps_newrow -->

                <p class="field-custom description description-wide aps_mega_menu menu-item-aviso_title">
                <?php _e('If you do not want to appear the Column name just write a single dash "-"',LANGUAGE_THEME); ?>
                </p>

                <?php
                $key = "menu-item-aps_newrow";
                $label = __('MEGA-MENU: Start new row of columns from this item ', LANGUAGE_THEME);
                $value = get_post_meta( $item->ID, '_'.$key, true);
                if($value != "") $value = "checked='checked'";

                ?>

                <p class="field-custom description description-wide aps_mega_menu <?php echo $key; ?>">
                    <label for="edit-<?php echo $key.'-'.$item_id; ?>">
                        <input type="checkbox" value="active" id="edit-<?php echo $key.'-'.$item_id; ?>" class=" <?php echo $key; ?>" name="<?php echo $key . "[". $item_id ."]";?>" <?php echo $value; ?> />
                        <?php _e( $label ); ?>
                    </label>
                </p>


                <!-- field aps_checkbox_desc -->

                <?php
                $key = "menu-item-aps_checkbox_desc";
                $label = __('MEGA-MENU: Use text block instead of link<br><small>(Dont remove the Navigation Label or Wordpress will delete this item.)</small>', LANGUAGE_THEME);
                $value = get_post_meta( $item->ID, '_'.$key, true);
                if($value != "") $value = "checked='checked'";
                ?>

                <p class="field-custom description description-wide aps_mega_menu <?php echo $key; ?>">
                    <label for="edit-<?php echo $key.'-'.$item_id; ?>">
                        <input type="checkbox" value="active" id="edit-<?php echo $key.'-'.$item_id; ?>" class=" <?php echo $key; ?>" name="<?php echo $key . "[". $item_id ."]";?>" <?php echo $value; ?> />
                        <?php _e( $label ); ?>
                    </label>
                </p>


                <!-- field aps_desc -->

                <?php
                $key = "menu-item-aps_desc";
                $label = __('MEGA-MENU: Text Block ', LANGUAGE_THEME);
                $value = get_post_meta( $item->ID, '_'.$key, true);
                ?>

                <p class="field-custom description description-wide aps_mega_menu <?php echo $key; ?>">
                    <label for="edit-<?php echo $key.'-'.$item_id; ?>">
                        <?php _e( $label ); ?>
                        <textarea rows="6" id="edit-<?php echo $key.'-'.$item_id; ?>" class=" <?php echo $key; ?>" name="<?php echo $key . "[". $item_id ."]";?>"><?php echo $value; ?></textarea>
                    </label>
                </p>




                <!-- Mega menu options end -->



                <p class="field-move hide-if-no-js description description-wide">
                    <label>
                        <span><?php _e( 'Move' ); ?></span>
                        <a href="#" class="menus-move-up"><?php _e( 'Up one' ); ?></a>
                        <a href="#" class="menus-move-down"><?php _e( 'Down one' ); ?></a>
                        <a href="#" class="menus-move-left"></a>
                        <a href="#" class="menus-move-right"></a>
                        <a href="#" class="menus-move-top"><?php _e( 'To the top' ); ?></a>
                    </label>
                </p>


                <div class="menu-item-actions description-wide submitbox">
                    <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
                        <p class="link-to-original">
                            <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                        </p>
                    <?php endif; ?>
                    <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                    echo wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'delete-menu-item',
                                'menu-item' => $item_id,
                            ),
                            admin_url( 'nav-menus.php' )
                        ),
                        'delete-menu_item_' . $item_id
                    ); ?>"><?php _e( 'Remove' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel'); ?></a>
                </div>

                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
                <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
                <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
            </div><!-- .menu-item-settings-->
            <ul class="menu-item-transport"></ul>
            <?php
            $output .= ob_get_clean();
        }
    }
}


//wp-includes/nav-menu-template.php
if( ! class_exists( 'ApsMegaMenuShow' ) )
{
    class ApsMegaMenuShow extends Walker
    {
        var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

        var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );



        function start_lvl( &$output, $depth = 0, $args = array() )
        {
            //echo '<h3>start_lvl '.$depth.'</h3>';
            $indent = str_repeat("\t", $depth);

            //Para wrap el megamenu con un div
            //if ( $depth === 0 ) $output .= '%mega_menu_wrap%';
            //$output .= "\n$indent<ul class=\"sub-menu \">\n";
            if ( $depth === 0 ){
                $output .= "\n$indent<ul class=\"sub-menu%class_megamenu%\">\n";
            } else {
                if ($this->megamenu_active)
                {
                    //Dentro del submenu necesito que se quede visible
                    //para que el superfish no lo oculte y no puedo usar un ul directamente
                    //$output .= "\n$indent<div class=\"sub_sub_megamenu\"><ul>\n";
                    $output .= "\n$indent<div class=\"sub_sub_megamenu\">\n";
                }
                else
                {
                    $output .= "\n$indent<ul class=\"sub-menu\">\n";
                }

            }

        }



        function end_lvl( &$output, $depth = 0, $args = array() )
        {
            //echo '<h3>end_lvl '.$depth.'</h3>';
            $indent = str_repeat("\t", $depth);
            //$output .= "$indent</ul>\n";

            if ($depth !== 0 && $this->megamenu_active) {
                //$output .= "$indent</ul></div>\n";
                $output .= "$indent</div>\n";
            }
            else {
                $output .= "$indent</ul>\n";
            }

            if ( $depth === 0 )
            {
                //Activado ?
                if ($this->megamenu_active)
                {
                    $output = str_replace('%class_megamenu%',' submenu_megamenu max-columns-'.$this->max_columns, $output);

                    //Las ultimas columnas comono tienen un salto de row hago lasustitucion aqui
                    $output = str_replace('%column%','mega-column-'.($this->max_columns),$output);
                }
                else
                {
                    $output = str_replace('%class_megamenu%','',$output);
                }
            }
        }

        // PENDIENTE DE ESTUDIAR MEJOR
        function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
        {
            //echo '<p>start_el '.$depth.'</p>';
            /*
                Campos a tener en cuenta:
                _menu-item-aps_megamenu
                _menu-item-aps_newrow
                _menu-item-aps_checkbox_desc
                _menu-item-aps_desc
            */

            //Maximas columnas
            $max_columns = 5;

            $item_output = '';
            $class_mega_menu = '';
            $class_mega_column = '';
            $class_mega_desc = '';

            //Saber si esta activo
            if ( $depth === 0 )
            {
                //Marcador para sabe si es un megamenu
                $this->megamenu_active = get_post_meta( $item->ID, '_menu-item-aps_megamenu', true );
                if ($this->megamenu_active) {
                    $class_mega_menu = 'item_is_megamenu';
                } else {
                    $class_mega_menu = 'item_is_not_megamenu';
                }

                $this->columns = 0;
                $this->max_columns = 0;
            }

            //Es una fila de elementos nueva
            if ( $depth === 1 && $this->megamenu_active )
            {
                //Una nueva columna
                $this->columns ++;
                $this->max_columns = $this->columns;

                //Hay que empezar otra linea porque me he pasado o porque quiere el usuario ?
                if ($this->columns > $max_columns || get_post_meta( $item->ID, '_menu-item-aps_newrow', true) )
                {
                    $output .= "\n<hr class='megamenu_newrow' />\n";
                    //Tengo que indicarle cuantas columnas llevo
                    $output = str_replace('%column%','mega-column-'.($this->max_columns - 1),$output);
                    $this->columns = 1;
                    $this->max_columns = 1; //Reiniciar la cuenta
                }

                //El titulo del elemento
                if ($item->title != '-') {
                    $title = apply_filters('the_title', $item->title, $item->ID);
                    $item_output .= "<h4 class='column-title'>" . $title . "</h4>";
                }

                $class_mega_column = 'megamenu_column %column%';
            }


            //Es un texto que puede llevar shortcode
            else if ( $depth === 2 && $this->megamenu_active && get_post_meta( $item->ID, '_menu-item-aps_checkbox_desc', true))
            {
                $class_mega_desc = 'megamenu_textblock';
                $item_output .= '<p>'.do_shortcode( get_post_meta( $item->ID, '_menu-item-aps_desc', true) ).'</p>';
            }


            //Elemento de link normal
            else
            {
                $atts = array();
                $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
                $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
                $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
                $atts['href']   = ! empty( $item->url )        ? $item->url        : '';
                $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

                $attributes = '';
                foreach ( $atts as $attr => $value ) {
                    if ( ! empty( $value ) ) {
                        $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }

                $item_output .= $args->before;
                $item_output .= '<a'. $attributes .'>';
                /** This filter is documented in wp-includes/post-template.php */
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .= '</a>';
                $item_output .= $args->after;
            }


            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $class_names = $value = '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;


            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
            //$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
            $class_names = ' class="' . esc_attr( $class_names ) . ' ' . $class_mega_menu . ' ' . $class_mega_column . ' ' . $class_mega_desc . '"';


            $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            //$output .= $indent . '<li' . $id . $value . $class_names .'>';
            if ( $depth !== 0 && $this->megamenu_active) {
                $output .= $indent . '<div' . $id . $value . $class_names .'>';
            } else {
                $output .= $indent . '<li' . $id . $value . $class_names .'>';
            }

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }


        function end_el( &$output, $item, $depth = 0, $args = array() )
        {
            if ( $depth !== 0 && $this->megamenu_active) {
                $output .= "</div>\n";
            } else {
                $output .= "</li>\n";
            }
        }
    }
}