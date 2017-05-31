<?php
// =============================================================================
// TAXONOMY.PHP
// -----------------------------------------------------------------------------
// Handles taxonomies archive for projects.
// =============================================================================
?>
<?php get_header(); ?>

    <div id="main" role="main" class="section">
        <div class="container table-container">

            <?php get_sidebar('left'); ?>

            <div id="main-content" class="row">
                <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php the_project_page_title(); ?>

                    <?php

                    //No uso el loop sino un shortcode con los datos de las opciones

                    //Preparar las taxonomias usadas
                    global $wp_query;
                    $query = $wp_query->query;
                    $project_cat = '';
                    $project_tag = '';

                    if (isset($query['project_category'])) {
                        $project_cat = 'project_category::'.$query['project_category'];

                    } else if (isset($query['project_skill'])) {
                        $project_cat = 'project_skill::'.$query['project_skill'];

                    } else if (isset($query['project_tag'])) {
                        $project_tag = 'project_tag::'.$query['project_tag'];
                    }

                    // Las opciones para el shortcode
                    $options = get_option('aps_op_project_archive');

                    // Numero de posts viene del wordpress
                    $posts_per_page = get_option('posts_per_page');

                    // Montar el shortcode
                    $shortcode = '[aps_gallery_template post_type="aps_project"';
                    $shortcode .= ' categories_aps_project="'.$project_cat.'"';
                    $shortcode .= ' tags_aps_project="'.$project_tag.'"';
                    $shortcode .= ' posts_per_page="'.$posts_per_page.'"';
                    $shortcode .= ' current_url="yes"';

                    foreach($options as $key=>$value)
                    {
                        if ($value!='') {
                            $shortcode .= ' '.$key.'="'.$value.'"';
                        }
                    }
                    $shortcode .= ']';

                    //  Echar el shortcode
                    echo do_shortcode($shortcode);

                    ?>

                </div>
            </div>

            <?php get_sidebar('right'); ?>

        </div>
    </div>


<?php get_footer(); ?>