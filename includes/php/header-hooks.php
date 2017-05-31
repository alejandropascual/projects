<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

add_action('wp_head', 'aps_projects_header');

function aps_projects_header()
{
    if ( !is_page_template('template-blank.php') ) {
        aps_get_current_layout();
    }
}