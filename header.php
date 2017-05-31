<?php

// =============================================================================
// HEADER.PHP
// -----------------------------------------------------------------------------
// The site header
// =============================================================================

?>

<?php get_template_part('header','global'); ?>

<body <?php body_class( layout_class() ); aps_body_style_layout(); ?>>

	<div id="page" class="site">

        <?php get_template_part('layout/layout','slider-above'); ?>
        <?php get_template_part('layout/layout','menu'); ?>
        <?php get_template_part('layout/layout','slider-below'); ?>