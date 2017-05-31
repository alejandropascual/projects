<?php
// =============================================================================
// SEARCH.PHP
// -----------------------------------------------------------------------------
// Handles search queries.
// =============================================================================
?>

<?php get_header(); ?>


<div id="main" role="main" class="section">
    <div class="container table-container">

        <?php get_sidebar('left'); ?>

        <div id="main-content" class="row">

            <div class="aps-main-content-wrap <?php main_content_class(); ?>">

                <!-- search -->
                <div class="search_page_new_search">
                    <h2><?php _e('Search results for:',LANGUAGE_THEME); echo get_search_query(); ?></h2>

                    <?php if ( have_posts() ) : ?>

                        <div class="new_search">
                            <p><?php echo __('Need a new search?', LANGUAGE_THEME); ?></p>
                            <form role="search" id="searchform" method="get" action="<?php echo site_url(); ?>">
                                <div class="search-wrap">
                                    <input class="button-style-2 as_input" type="text" value="" name="s" id="s">
                                    <input class="fa fa-search button-style-1" type="submit" id="searchsubmit" value="">
                                </div>
                            </form>
                        </div>

                    <?php endif; ?>

                </div>


                <?php
                if ( have_posts() ) :

                    ?><div class="search-result selector"><?php

                    while ( have_posts() ) :
                        the_post();
                        get_template_part('layout/search/search_list');
                    endwhile;

                    ?></div><?php

                    aps_page_nav_numbers();

                else :

                    get_template_part( 'content', 'none' );

                endif;
                ?>


            </div>

        </div>

        <?php get_sidebar('right'); ?>

    </div>
</div>



<?php get_footer(); ?>