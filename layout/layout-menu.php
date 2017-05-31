<?php
// =============================================================================
// LAYOUT-MENU.PHP
// -----------------------------------------------------------------------------
// Handles top menu with three strips: top / center / bottom
// =============================================================================
?>

<?php

$header = aps_has_header();
if ($header=='') return;

?>

<div id="header-relleno"></div>

<header id="menu-header" class="layout-menu section" role="banner">

    <div class="top-wrapper">
        <div class="container">

            <!-- HEADER-TOP -->
            <?php if ( $header=='header-2' || $header=='header-4' ) : ?>
                <div class="head-top clearfix">
                    <span class="head-left">
                        <?php aps_get_option_data('head-top:left'); ?>
                    </span>
                    <span class="head-right">
                        <?php aps_get_option_data('head-top:right'); ?>
                    </span>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="center-wrapper">
        <div class="container">

            <!-- HEADER-CENTER -->
            <div class="head-center clearfix">
                <span class="head-left">
                    <?php aps_get_option_data('head-center:left'); ?>
                </span>
                <span class="head-right">
                    <?php aps_get_option_data('head-center:right'); ?>
                </span>
            </div>


        </div>
    </div>

    <div class="bottom-wrapper">
        <div class="container">

            <!-- HEADER-BOTTOM -->
            <?php if ( $header=='header-3' || $header=='header-4' ) : ?>
                <div class="head-bottom clearfix">
                    <span class="head-left">
                        <?php aps_get_option_data('head-bottom:left'); ?>
                    </span>
                    <span class="head-right">
                        <?php //echo aps_get_contact_data(); ?>
                        <?php aps_get_option_data('head-bottom:right'); ?>
                    </span>
                </div>
            <?php endif; ?>

        </div>
    </div>

</header>