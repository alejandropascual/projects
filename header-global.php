<?php

// =============================================================================
// HEADER-GLOBAL.PHP
// -----------------------------------------------------------------------------
// Called from header.php and template-blank.php
// =============================================================================

?><!DOCTYPE html>
<!--[if IE 8]><html class="no-js ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 9]><html class="no-js ie9" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php wp_title( '|', true, 'right' ); ?></title>

    <?php //aps_header_meta(); no usar si estoy usando el PLUGIN YOAST SEO?>
    <?php aps_show_favicon(); ?>

    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php wp_head(); ?>
</head>
