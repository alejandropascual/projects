<?php
// =============================================================================
// SINGLE_LAY_1.PHP
// -----------------------------------------------------------------------------
// Layout 1 for single post.
// =============================================================================
?>
<?php

global $aps_config;
$single = $aps_config['aps_op_post'];

echo '<div class="post_media">';

    if (isset($single['single_show_image']) && $single['single_show_image']=='yes') {
        aps_content_entry_media();
    }

echo '</div>';

echo '<div class="post_text">';

    if (isset($single['single_show_date']) && $single['single_show_date']=='yes') {
        aps_content_entry_date();
    }

    if (isset($single['single_show_cats']) && $single['single_show_cats']=='yes') {
        aps_content_entry_categories();
    }

    if (isset($single['single_show_title']) && $single['single_show_title']=='yes') {
        aps_content_entry_title();
    }

    aps_content_entry_content();
    aps_link_pages();

    if (isset($single['single_show_tags']) && $single['single_show_tags']=='yes') {
        aps_content_entry_tags();
    }

    if (isset($single['single_show_social']) && $single['single_show_social']=='yes') {
        aps_content_entry_social();
    }

echo '</div>';