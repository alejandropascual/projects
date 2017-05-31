<?php
// =============================================================================
// MASONRY_3.PHP
// -----------------------------------------------------------------------------
// Handles blog style masonry_3.
// =============================================================================
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
    <?php
    echo '<div class="post_text">';
    aps_content_entry_title();
    aps_content_entry_content_limited();
    aps_content_entry_meta();
    aps_content_entry_social();
    aps_content_entry_date();
    echo '</div>';
    echo '<div class="post_media">';
    aps_content_entry_media();
    aps_content_entry_more();
    echo '</div>';
    ?>
</article>