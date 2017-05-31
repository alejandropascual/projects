<?php
// =============================================================================
// GRID.PHP
// -----------------------------------------------------------------------------
// Handles output for grid style elements in blog.
// =============================================================================
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post post-grid'); ?>>

    <div class="post-item-wrap">

        <div class="item-image post_media">
            <?php aps_content_entry_media(); ?>
            <?php aps_content_entry_more(); ?>
        </div>

        <div class="item-content post_text">
            <?php
                aps_content_entry_date();
                aps_content_entry_title();
                aps_content_entry_meta();
                aps_content_entry_content_limited();
                aps_content_entry_social();
            ?>
        </div>

    </div>

</article>