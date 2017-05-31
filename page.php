<?php
// =============================================================================
// PAGE.PHP
// -----------------------------------------------------------------------------
// Handles output of individual pages.
// =============================================================================
?>


<?php get_header(); ?>

		<!-- CONTENT -->
		<div id="main" role="main" class="section">
			<div class="container table-container">
			
				<?php get_sidebar('left'); ?>
				
				<div id="main-content" class="row">
				<div class="aps-main-content-wrap <?php main_content_class(); ?>">

                    <?php
                    if ( have_posts() ) :

                        $ops = get_option('aps_op_pages');
                        $show_comments = isset($ops['page_show_comments']) ? $ops['page_show_comments'] : 'no';

                        while ( have_posts() ) :
                            the_post();
                            $has_post_thumbnail = ( has_post_thumbnail() && get_post_meta(get_the_ID(),'show_featured_image',true) != 'no' );
                            $show_title = ( get_post_meta(get_the_ID(),'show_page_title',true) == 'yes' ) ? true : false;
                            $len_content = strlen( get_the_content() )>0;
                            $post_class = ( get_post_meta(get_the_ID(),'show_page_border',true) == 'yes' ) ? 'post-box' : 'post-no-box';

                            if ( $has_post_thumbnail || $show_title || $len_content ) :
                                ?>

                                <article id="post-<?php the_ID(); ?>" <?php post_class($post_class); ?>>

                                    <?php
                                    if( $has_post_thumbnail )
                                    {
                                        echo '<div class="post_media">';
                                        aps_content_featured_image();
                                        echo '</div>';
                                    }
                                    ?>

                                    <?php

                                    if ( $show_title || $len_content )
                                    {
                                        echo '<div class="post_text">';
                                        if ($show_title) { aps_content_entry_title(); }
                                        if ($len_content) { aps_content_entry_content(); }
                                        wp_link_pages( array(
                                            'before' 			=> '<div class="page_links"><span class="page_links_title">' . __( 'Pages:', LANGUAGE_THEME ) . '</span>',
                                            'after'  			=> '</div>',
                                            'link_before' => '<span class="page_link_title">',
                                            'link_after'	=> '</span>'
                                        ) );
                                        echo '</div>';
                                    }

                                    if (comments_open() && $show_comments == 'yes') {
                                        aps_content_single_comments();
                                    }

                                    ?>

                                </article>

                            <?php endif; ?>

                        <?php
                        endwhile;
                    endif;
                    ?>
					
				</div>
				</div>
				
				<?php get_sidebar('right'); ?>	
			
			</div>
		</div>
		

<?php get_footer(); ?>