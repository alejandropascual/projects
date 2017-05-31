
<?php
// =============================================================================
// TEMPLATE-LIST-GALLERY.PHP
// -----------------------------------------------------------------------------
// Handles 4 templates:
//    template-list-posts.php
//    template-list-projects.php
//    template-gallery-posts.php
//    template-gallery-projects.php
// =============================================================================
?>


<?php get_header(); ?>


    <!-- CONTENT -->
    <div id="main" role="main" class="section">
        <div class="container table-container">

            <?php get_sidebar('left'); ?>

            <div id="main-content" class="row">
                <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php
                    if ( have_posts() ) :
                        while ( have_posts() ) :
                            the_post();

                            $display_position = get_post_meta(get_the_ID(),'display_position',true);
                            $has_post_thumbnail = has_post_thumbnail();
                            $show_title = ( get_post_meta(get_the_ID(),'show_page_title',true) == 'yes' ) ? true : false;
                            $len_content = strlen( get_the_content() )>0;
                            $post_class = ( get_post_meta(get_the_ID(),'show_page_border',true) == 'yes' ) ? 'post-box' : 'post-no-box';

                            if ($display_position == 'above')
                            {
                                aps_content_extra_content();
                            }


                            if ( $has_post_thumbnail || $show_title || $len_content ) :
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>
                                <?php
                                if( $has_post_thumbnail )
                                {
                                    echo '<div class="post_media">';
                                    aps_content_featured_image();
                                    echo '</div>';
                                }
                                ?>

                                <?php

                                if ( $show_title || $len_content )
                                {
                                    echo '<div class="post_text">';
                                        if ($show_title) { aps_content_entry_title(); }
                                        if ($len_content) { aps_content_entry_content(); }
                                        //No pagination, only for the template
                                    echo '</div>';
                                }
                                ?>
                            </article>
                            <?php endif;

                            if ($display_position != 'above')
                            {
                                aps_content_extra_content();
                            }

                        endwhile;
                    endif;
                    ?>

                </div>
            </div>

            <?php get_sidebar('right'); ?>

        </div>
    </div>


<?php get_footer(); ?>