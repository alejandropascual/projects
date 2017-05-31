<?php

// =============================================================================
// CONTENT-NONE.PHP
// -----------------------------------------------------------------------------
// Called from 404.php and search.php
// =============================================================================

?>

<article id="post-error" class="post post-error">


    <div class="post_text">
        <div class="post_content">


            <h1 class="text_404"><?php _e('Oops',LANGUAGE_THEME); ?></h1>
            <h3 ><?php echo __('We can\'t find what you\'ve requested!', LANGUAGE_THEME); ?></h3>

            <div class="error_404_section">
                <p><?php echo __('Try searching using the form below:', LANGUAGE_THEME); ?></p>
                <form role="search" id="searchform" method="get" action="<?php echo site_url(); ?>">
                    <div class="search-wrap">
                        <input class="button-style-2 as_input" type="text" value="" name="s" id="s">
                        <input class="fa fa-search button-style-1" type="submit" id="searchsubmit" value="">
                    </div>
                </form>
            </div>


            <?php
            $menu_id = aps_get_option('menu_page_404');
            if ($menu_id && $menu_id != '')
            {
                echo '<div class="error_404_section">';
                echo '<p>'. __('Or use these useful links:', LANGUAGE_THEME).'</p>';
                $menu_id = str_replace('menu-','',$menu_id);
                wp_nav_menu( array(
                        'menu' => $menu_id,
                        'depth' => 1,
                        'container' => 'nav',
                        'container_id' => 'nav-404',
                        'container_class' => 'nav-404',
                        'menu_id' => 'menu-404-list',
                        'menu_class' => 'list-icon circle-yes list-icon-arrow'
                    )
                );
                echo '</div>';
            }
            ?>

        </div>
    </div>


</article>