<?php

// =============================================================================
// 404.PHP
// -----------------------------------------------------------------------------
// Handles errors when pages do not exist.
// =============================================================================


get_header(); ?>

		<!-- CONTENT -->
		<div id="main" role="main" class="section">
			<div class="container table-container">

                <?php get_sidebar('left'); ?>

				<div id="main-content" class="row">
				<div class="aps-main-content-wrap hidden-border <?php main_content_class(); ?>">

                    <?php get_template_part( 'content', 'none' ); ?>

				</div>
				</div>

                <?php get_sidebar('right'); ?>
				
			</div>
		</div>

<?php get_footer(); ?>