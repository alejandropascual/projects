<?php

// Tag Cloud change style, all the same size
// =============================================================================

if ( ! function_exists( 'aps_projects_tag_cloud_widget' ) ) :
    function aps_projects_tag_cloud_widget( $tag_string )
    {
        return preg_replace( "/style='font-size:.+pt;'/", '', $tag_string );
    }
    add_filter( 'wp_generate_tag_cloud', 'aps_projects_tag_cloud_widget' );
endif;



// Custom Title for SEO
// =============================================================================

if ( ! function_exists( 'aps_wp_title' ) ) :
    function aps_wp_title( $title ) {

        if ( is_front_page() ) {
            return get_bloginfo( 'name' ) . ' | ' . get_bloginfo( 'description' );
        } else {
            return trim( $title ) . ' | ' . get_bloginfo( 'name' );
        }

        return $title;

    }
    add_filter( 'wp_title', 'aps_wp_title' );
endif;