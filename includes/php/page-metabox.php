<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

$boxes = array(
	array(
		'id' 		=> 'box_page_options',
		'title' 	=> __('Options','LANGUAGE_THEME'),
		'page' 		=> array('page'),
		'context' 	=> 'side',
		'priority'	=> 'high'
	)
);
$fields = array(
	array(
		'box_id' => 'box_page_options',
		'id'		=> 'show_page_title',
		'title' => __('Show page title','LANGUAGE_THEME'),
		'desc'	=> __('','LANGUAGE_THEME'),
		'type'	=> 'select',
		'options' => array('yes'=>'YES','no'=>'NO'),
		'value'	=> 'yes'
	),
    array(
        'box_id' => 'box_page_options',
        'id'		=> 'show_page_border',
        'title' => __('With bordered box','LANGUAGE_THEME'),
        'desc'	=> __('','LANGUAGE_THEME'),
        'type'	=> 'select',
        'options' => array('yes'=>'YES','no'=>'NO'),
        'value'	=> 'yes'
    ),
    array(
        'box_id' => 'box_page_options',
        'id'		=> 'show_featured_image',
        'title' => __('Show featured image','LANGUAGE_THEME'),
        'desc'	=> __('ex: You can hide featured image when display the page but show it in for search results page ','LANGUAGE_THEME'),
        'type'	=> 'select',
        'options' => array('yes'=>'YES','no'=>'NO'),
        'value'	=> 'yes'
    ),
    array(
        'box_id' => 'box_page_options',
        'id'		=> 'use_preloader',
        'title' => __('Use Preloader','LANGUAGE_THEME'),
        'desc'	=> __('This will load all the images before the page is displayed','LANGUAGE_THEME'),
        'type'	=> 'select',
        'options' => array('no'=>'NO','yes'=>'YES'),
        'value'	=> 'no'
    )
);
