<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !function_exists('aps_font_url_visual_editor') ) :
	function aps_font_url_visual_editor()
	{
		$font_url = add_query_arg( 'family', urlencode( 'Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' ), "//fonts.googleapis.com/css" );
		
		return $font_url;
	}
endif;

// PENDIENTE. Poner el basico de bootstrap
// This theme styles the visual editor to resemble the theme style.
add_editor_style( array( 'editor-style.css', aps_font_url_visual_editor() ) );