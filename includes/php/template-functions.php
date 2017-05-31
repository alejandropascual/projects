<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


function aps_get_related_posts($post_id, $number_posts = -1)
{
	$query = new WP_Query();
    $args = '';

	if($number_posts == 0) {
		return $query;
	}

	$args = wp_parse_args($args, array(
		'posts_per_page' => $number_posts,
		'post__not_in' => array($post_id),
		'ignore_sticky_posts' => 0,
        'meta_key' => '_thumbnail_id',
        'category__in' => wp_get_post_categories($post_id)
	));

	$query = new WP_Query($args);

  	return $query;
}
