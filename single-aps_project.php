<?php
// =============================================================================
// SINGLE-APS_PROJECT.PHP
// -----------------------------------------------------------------------------
// Handles single post for aps_project custom post.
// =============================================================================
?>

<?php get_header(); ?>

    <div id="main" role="main" class="section">
        <div class="container table-container">

            <?php get_sidebar('left'); ?>

            <div id="main-content" class="row">
                <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php
                    if ( have_posts() ) :
                    while (have_posts()) : the_post();
                        $data = aps_get_all_metadata(get_the_ID());
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-single post-box'); ?>>

                            <?php
                            $lay_type = $data['project_layout_type'];
                            switch ($lay_type)
                            {
                                case 'lay_1':
                                    the_project_gallery();
                                    the_project_text(true,true);
                                    the_project_video();
                                    break;

                                case 'lay_2':
                                    the_project_title();
                                    the_project_gallery();
                                    the_project_text(false,true);
                                    the_project_video();
                                    break;

                                case 'lay_3':
                                    the_project_video();
                                    the_project_title();
                                    the_project_text(false,true,false);
                                    the_project_gallery();
                                    break;

                                case 'lay_4':
                                    the_project_gallery();
                                    the_project_title();
                                    the_project_map();
                                    the_project_text_1_column();
                                    break;

                                case 'lay_5':
                                    the_project_title();
                                    the_project_gallery();
                                    the_project_map();
                                    the_project_text_1_column();
                                    break;

                                case 'lay_6':
                                    the_project_thumbnail();
                                    the_project_text(true,true,false);
                                    the_project_gallery();
                                    the_project_video();
                                    break;

                                case 'lay_7':
                                    the_project_title();
                                    the_project_thumbnail();
                                    the_project_text(false,true,false);
                                    the_project_gallery();
                                    the_project_video();
                                    break;

                                case 'lay_8':
                                    the_project_title();
                                    the_project_thumbnail();
                                    the_project_text(false,false,false);
                                    the_project_map();
                                    the_project_gallery();
                                    the_project_video();
                                    break;

                                default:
                                    the_project_gallery();
                                    the_project_text(true,true);
                                    the_project_video();
                                    break;
                            }
                            ?>

                        </article>

                        <?php the_project_related(); ?>

                        <?php
                        if (comments_open()) {
                            aps_content_single_comments();
                        }
                        ?>

                        <?php aps_pagenav(); ?>

                        <?php

                    endwhile;
                    endif;
                    ?>

                </div>
            </div>

            <?php get_sidebar('right'); ?>

        </div>
    </div>

<?php get_footer(); ?>