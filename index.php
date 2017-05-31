<?php
// =============================================================================
// INDEX.PHP
// -----------------------------------------------------------------------------
// Default template. Must be present for theme to function properly.
// =============================================================================
?>

<?php get_header(); ?>

		<div id="main" role="main" class="section">
			<div class="container table-container">
			
				<?php get_sidebar('left'); ?>

				<div id="main-content" class="row">

					<div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php aps_content_page_title(); ?>

                        <?php  aps_content_template_blogpluscontent('above'); ?>

                        <!-- blog -->
                        <div class="blog-articles <?php aps_blog_style_class(); ?> selector aps_animated aps_fadeInUp" data-adelay="500">

                        <?php

                        $blog_template = aps_blog_template();

                        if ( have_posts() ) :
                            while ( have_posts() ) :
                                the_post();
                                get_template_part('layout/blog/'.$blog_template);
                            endwhile;
                        else :
                            get_template_part( 'content', 'none' );
                        endif;
                        ?>

                        </div>
                        <!-- close blog -->

                        <?php aps_pagenav(); ?>

                        <?php  aps_content_template_blogpluscontent('below'); ?>

					</div>
					
				</div>
				
				<?php get_sidebar('right'); ?>	
			
			</div>
		</div>
		
		

<?php get_footer(); ?>