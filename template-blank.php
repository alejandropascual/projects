<?php
/* Template Name: BLANK page */
?>

<?php get_template_part('header','global'); ?>

<body <?php body_class( ); ?>>

<div id="page" class="site">

    <div id="main" role="main" class="section">
            <div class="container table-container content-blank-wrap">
                <div id="main-content" class="row content-blank">
                    <div class="aps-main-content-wrap">

                        <?php
                        if ( have_posts() ) :
                            while ( have_posts() ) :
                                the_post();
                        ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-no-box'); ?>>
                        <div class="post_content">
                            <?php the_content(); ?>
                        </div>
                        </article>

                        <?php
                            endwhile;
                        endif;
                        ?>

                    </div>
                </div>
            </div>
    </div>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>