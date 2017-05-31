<?php
// =============================================================================
// search_list.PHP
// -----------------------------------------------------------------------------
// Handles list item en search.php template.
// =============================================================================
?>
<?php

$post_type = get_post_type();
$types = array(
    'post' => __('[POST]', LANGUAGE_THEME),
    'page' => __('[PAGE]',LANGUAGE_THEME),
    'aps_project' => __('[PROJECT]',LANGUAGE_THEME)
);
if ( isset( $types[$post_type]) ) {
    $type = $types[$post_type];
} else {
    $type = '';
}

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('search-post post'); ?>>

    <div class="search-box-image">
    <?php
    aps_content_featured_image(120,120,false);
    ?>
    </div>

    <div class="search-box-text">
    <?php
    aps_content_entry_title('h3','title');
    aps_content_entry_date_simple( '', '<div class="date"><span>', $after = '</span><span class="post_type">'.$type.'</span>', true );
    the_excerpt();
    the_date();
    ?>
    </div>

</article>
