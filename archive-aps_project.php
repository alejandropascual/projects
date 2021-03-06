<?php

// =============================================================================
// ARCHIVE-APS_PROJECT.php
// -----------------------------------------------------------------------------
// Handles projects archive.
//
// Es una version simplificada del de layout/project/taxonomy.php
// que usa para las taxonomias de proyectos, pero yo no necesito las categorias ni los tags
// =============================================================================


?>
<?php get_header(); ?>


    <!-- CONTENT -->
    <div id="main" role="main" class="section">
        <div class="container table-container">

            <?php get_sidebar('left'); ?>

            <div id="main-content" class="row">
                <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php the_project_page_title(); ?>

                    <?php

                    //No uso el loop sino un shortcode con los datos de las opciones

                    //Las opciones para el shortcode
                    $options = get_option('aps_op_project_list');

                    //NUmero de posts viene del wordpress
                    $posts_per_page = get_option('posts_per_page');

                    //Montar el shortcode
                    $shortcode = '[aps_gallery_template post_type="aps_project"';
                    $shortcode .= ' posts_per_page="'.$posts_per_page.'"';
                    $shortcode .= ' current_url="yes"';

                    foreach($options as $key=>$value)
                    {
                        if ($value!='') {
                            $shortcode .= ' '.$key.'="'.$value.'"';
                        }
                    }
                    $shortcode .= ']';

                    //Echar el shortcode
                    echo do_shortcode($shortcode);
                    ?>

                </div>
            </div>

            <?php get_sidebar('right'); ?>

        </div>
    </div>


<?php get_footer(); ?>