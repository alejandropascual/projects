<?php
// =============================================================================
// SINGLE.PHP
// -----------------------------------------------------------------------------
// Handles single post content.
// =============================================================================
?>

<?php get_header(); ?>

    <div id="main" role="main" class="section">
        <div class="container table-container">

            <?php get_sidebar('left'); ?>

            <div id="main-content" class="row">
            <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                <?php
                global $aps_config;
                $aps_config['aps_op_post'] = get_option('aps_op_post');
                $single = $aps_config['aps_op_post'];

                ?>
                <div class="post-single-wrapper <?php aps_post_wrapper_style_class(); ?>">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();

                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-single post-box'); ?>>

                            <?php

                            //Layout single post
                            $post_layout_override = get_post_meta( get_the_ID(), 'post_layout_override',true);

                            if ($post_layout_override && $post_layout_override == 'yes')
                            {
                                $layout_type = get_post_meta( get_the_ID(), 'post_layout_type',true);
                                get_template_part('layout/single/single_'.$layout_type);
                            }
                            else
                            {
                                get_template_part('layout/single/single_lay_1');
                            }

                            ?>

                        </article>
                        <?php

                    endwhile;

                    // ABOUT THE AUTHOR
                    if (isset($single['single_show_author']) && $single['single_show_author']=='yes') {
                        aps_content_about_the_author();
                    }

                    // RELATED POSTS
                    if (isset($single['single_show_related']) && $single['single_show_related']=='yes') {
                        aps_content_related();
                    }

                    // POST NAVIGATION
                    if (isset($single['single_show_next']) && $single['single_show_next']=='yes') {
                        echo '<div class="pagination-posts">';
                        previous_post_link('<span class="nav-pre-post"> %link</span>');
                        next_post_link('<span class="nav-next-post">%link </span>');
                        echo '</div>';
                    }

                    // COMMENTS
                    if (comments_open() && isset($single['single_show_comments']) && $single['single_show_comments']=='yes') {
                        aps_content_single_comments();
                    }

                echo '</div>';

                endif;
                ?>

            </div>
            </div>

            <?php get_sidebar('right'); ?>

        </div>
    </div>
		

<?php get_footer(); ?>