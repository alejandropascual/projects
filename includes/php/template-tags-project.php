<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

// PROJECT GALLERY
if ( ! function_exists( 'the_project_gallery' ) ) :
    function the_project_gallery()
    {
        //Obtener imagenes asociadas al post
        $has_gallery = get_post_meta(get_the_ID(),'project_has_gallery',true);

        if ( $has_gallery == 'yes' ) {
            //Creo el shortcode
            $data = aps_get_all_metadata(get_the_ID());
            if (!isset($data['type'])) {
                return '';
            }

            switch ($data['type']) {
                case 'masonry_image':
                    $campos = 'masonry_width,masonry_margin';
                    break;
                case 'grid_image':
                    $campos = 'grid_cols,grid_width,grid_padding,grid_ratio';
                    break;
                case 'justified_grid_image':
                    $campos = 'jgrid_height,jgrid_padding';
                    break;
                case 'gallery_image':
                    $campos = 'gallery_width,gallery_height,gallery_mode';
                    break;
                default:
                    return '';
                    break;
            }

            //Crear shortcode
            $shortcode = "[aps_gallery_template type={$data['type']} post_type=aps_project use_gallery_of_post=yes select_by=by_ids post_ids='" . get_the_ID() . "'";
            $campos = explode(',', $campos);
            foreach ($campos as $campo) {
                $valor = $data[$campo];
                $shortcode .= " {$campo}='{$valor}'";
            }
            $shortcode .= " with_border=no display_link_post=no display_link_external=no display_link_lightbox=yes display_curtain_text=no image_direct_link=yes]";


            echo '<div class="post_media">';
            echo do_shortcode($shortcode);
            echo '</div>';
        }
    }
endif;


// PROJECT THUMBNAIL
if ( ! function_exists( 'the_project_thumbnail' ) ) :
    function the_project_thumbnail($size='large')
    {
        echo '<div class="post_media">';
        the_post_thumbnail($size);
        echo '</div>';
    }
endif;


// PROJECT TITLE
if ( ! function_exists( 'the_project_title' ) ) :
    function the_project_title()
    {
        aps_content_entry_title();
    }
endif;



// PROJECT PAGE TITLE - para listado y archives
//http://stackoverflow.com/questions/2805879/wordpress-taxonomy-title-output
if ( ! function_exists( 'the_project_page_title' ) ) :
    function the_project_page_title()
    {
        global $wp_query;
        $query = $wp_query->query;

        if (is_post_type_archive('aps_project')) {
            $ops = get_option('aps_op_project_list');
            $title = $ops['page_title'];

        } else if (is_tax()) {

            $ops = get_option('aps_op_project_archive');
            $page_title = $ops['page_title'];

            //Solo hay un termino
            foreach ($query as $key => $value) {
                $term = get_term_by('slug', $value, $key);
                $term_name = $term->name;
            }
            //$title = __('Projects Archive: ', LANGUAGE_THEME).$title;
            $title = str_replace('%term%', $term_name, $page_title);

        }
        /* else if (is_tax('project_category')) {
                    $title = 'PROJECT CATEGORY';

                } else if (is_tax('project_skill')) {
                    $title = 'PROJECT SKILL';

                } else if (is_tax('project_tag')) {
                    $title = 'PROJECT TAG';
                }*/

        if ($title != '') {
            echo '<h2 class="page_title">' . $title . '</h2>';
        }

    }
endif;



// PROJECT TEXT en dos columnas
if ( ! function_exists( 'the_project_text' ) ) :
    function the_project_text( $with_title = false, $with_map = false , $with_thumbnail = false)
    {
        global $post;
        $data = aps_get_all_metadata(get_the_ID());
        ?>
        <div class="post_text clearfix">
        <?php if ($with_title) { aps_content_entry_title(); } ?>

        <div class="project-data row">

            <div class="project-description col-sm-8 col-xs-12">
                <h3><?php echo __('Project Description', LANGUAGE_THEME) ?></h3>
                <?php aps_content_entry_content(); ?>
            </div>

            <div class="project-info col-sm-4 col-xs-12">
                <h3><?php echo __('Project Details', LANGUAGE_THEME) ?></h3>

                <?php if ($with_map && $data['project_has_map'] && $data['project_has_map']=='yes'): ?>
                    <div class="project-info-box">
                        <?php the_project_map(); ?>
                        <?php echo '<div><small>'.$data['project_address'].'</small></div>'; ?>
                    </div>
                <?php endif; ?>


                <?php if ($with_thumbnail): ?>
                    <div class="project-info-box">
                        <?php the_project_thumbnail('medium'); ?>
                    </div>
                <?php endif; ?>


                <?php if ($data['project_show_skills'] && $data['project_show_skills']=='yes'): ?>
                <?php if(get_the_term_list($post->ID, 'project_skill', '', '<br />', '')): ?>
                    <div class="project-info-box">
                        <h4><?php echo __('Skills Needed', LANGUAGE_THEME) ?>:</h4>
                        <div class="project-terms">
                            <?php echo get_the_term_list($post->ID, 'project_skill', '', '<br />', ''); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ($data['project_show_categories'] && $data['project_show_categories']=='yes'): ?>
                <?php if(get_the_term_list($post->ID, 'project_category', '', '<br />', '')): ?>
                    <div class="project-info-box">
                        <h4><?php echo __('Categories', LANGUAGE_THEME) ?>:</h4>
                        <div class="project-terms">
                            <?php echo get_the_term_list($post->ID, 'project_category', '', '<br />', ''); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ($data['project_show_tags'] && $data['project_show_tags']=='yes'): ?>
                <?php if(get_the_term_list($post->ID, 'project_tag', '', '<br />', '')): ?>
                    <div class="project-info-box">
                        <h4><?php echo __('Tags', LANGUAGE_THEME) ?>:</h4 >
                        <div class="project-terms">
                            <?php echo get_the_term_list($post->ID, 'project_tag', '', '<br />', ''); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ($data['project_has_link'] && $data['project_has_link']=='yes'): ?>
                    <div class="project-info-box">
                        <h4><?php echo __('Project link', LANGUAGE_THEME) ?>:</h4>
                        <div class="project-terms">
                            <span><a href="<?php echo $data['project_link']; ?>" target="<?php echo $data['project_target']; ?>"><?php echo $data['project_caption']; ?><i class="fa fa-arrow-circle-o-right"></i></a></span>
                        </div>
                    </div>
                <?php endif; ?>



            </div>

        </div>

        <?php if ($data['project_show_social'] && $data['project_show_social']=='yes'): ?>
            <div class="project-info-box">
                <div class="project-social">
                    <?php the_project_social(); ?>
                </div>
            </div>
        <?php endif; ?>

        </div>
        <?php
    }
endif;


// PROJECT TEXT en una columna
if ( ! function_exists( 'the_project_text_1_column' ) ) :
    function the_project_text_1_column()
    {
        global $post;
        $data = aps_get_all_metadata(get_the_ID());
        ?>
        <div class="post_text clearfix">
        <div class="project-data row project-data-1col">

            <div class="project-info col-sm-12">

                <div class="project-info-box info-box-terms">
                <div class="project-terms project-terms-small">

                    <?php
                    $list1 = '';
                    $list2 = '';
                    $list3 = '';
                    if ($data['project_show_skills'] && $data['project_show_skills']=='yes'):
                         if(get_the_term_list($post->ID, 'project_skill')):
                            $list1 = get_the_term_list($post->ID, 'project_skill','',' | ','');
                         endif;
                    endif;

                    if ($data['project_show_categories'] && $data['project_show_categories']=='yes'):
                        if(get_the_term_list($post->ID, 'project_category')):
                            $list2 = get_the_term_list($post->ID, 'project_category','',' | ','');
                        endif;
                    endif;

                    if ($data['project_show_tags'] && $data['project_show_tags']=='yes'):
                        if(get_the_term_list($post->ID, 'project_tag')):
                            $list3 = get_the_term_list($post->ID, 'project_tag','',' | ','');
                        endif;
                    endif;

                    $list = $list1;
                    if ($list2!=''){
                        $list = ( $list != '' ? $list.' | ' : '' ) . $list2;
                    }
                    if ($list3!=''){
                        $list = ( $list != '' ? $list.' | ' : '' ) . $list3;
                    }
                    echo $list;
                    ?>


                </div>
                </div>

                <?php if ($data['project_has_link'] && $data['project_has_link']=='yes'): ?>
                    <div class="project-info-box">
                        <h4><?php echo __('Project link', LANGUAGE_THEME) ?>:</h4>
                        <div class="project-terms">
                            <span><a href="<?php echo $data['project_link']; ?>" target="<?php echo $data['project_target']; ?>"><?php echo $data['project_caption']; ?><i class="fa fa-arrow-circle-o-right"></i></a></span>
                        </div>
                    </div>
                <?php endif; ?>

                <h3 class="title-project-desc"><?php echo __('Project Description', LANGUAGE_THEME) ?></h3>
                <?php aps_content_entry_content(); ?>

            </div>

        </div>
        </div>
        <?php
    }
endif;


// PROJECT VIDEO
if ( ! function_exists( 'the_project_video' ) ) :
    function the_project_video()
    {
        $data = aps_get_all_metadata(get_the_ID());
        if ($data['project_has_video'] == 'no') { return false; }

        $poster_image = '';
        if ($data['project_video_use_poster'] == 'featured') {
            if (has_post_thumbnail())
            {
                //Recorto la imagen segun proporcion del video
                $ratio = $data['project_video_ratio'];
                $width_image = 300;
                if (is_single()){ $width_image = 1024; }
                $sizes = array(
                    'video-16_9' => 0.5625,
                    'video-5_3' => 0.6,
                    'video-5_4' => 0.8,
                    'video-4_3' => 0.75,
                    'video-3_2' => 0.67,
                );
                $height_image = $width_image * $sizes[$ratio];

                $image_resized = aps_get_image_resized_for_post_id( get_the_ID(), $width_image, $height_image, 0, 'no');

                if ( isset($image_resized['resized']['url']) )
                {
                    $poster_image = $image_resized['resized']['url'];
                }
                else
                {
                    $poster_image = aps_placeholder_image($width_image,$height_image,'error resize image')['url'];
                }

            }
        } else {
            $poster_image = $data['project_video_poster'];
        }

        $shortcode = "[aps_video type='hosted' ratio='{$data['project_video_ratio']}' frame='no' m4v='{$data['project_video_m4v']}' ogv='{$data['project_video_ogv']}' webm='{$data['project_video_webm']}' poster='{$poster_image}' autoplay='{$data['project_video_autoplay']}' skin='{$data['project_video_skin']}']";
        echo do_shortcode($shortcode);
    }
endif;


// PROJECT MAP
if ( ! function_exists( 'the_project_map' ) ) :
    function the_project_map()
    {

        $data = aps_get_all_metadata(get_the_ID());
        if ($data['project_has_map'] == 'no') { return false; }

        $html = '<div class="map_canvas_project"';
        $html .= ' data-address="'.esc_attr($data['project_address']).'"';
        $html .= ' data-coord="'.esc_attr($data['project_coord']).'"';
        $html .= ' data-zoom="'.esc_attr($data['project_zoom']).'"';
        $html .= ' data-icon-class="'.aps_get_option('map_icons_type').'"'; //icon-map-circle-70
        $html .= ' data-color="'.aps_get_option('map_icons_color').'"';
        $html .= ' data-mapstyle="'.aps_get_option('map_style').'"';

        if ( has_post_thumbnail() ) {
            $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'thumbnail');
            $html .= ' data-img-src="'.$thumbnail_url[0].'"';
        }
        $html .= '></div>';

        echo $html;
        //data-address="'.esc_attr($data['project_address']).'">MAP CANVAS</div>';
        wp_enqueue_script( 'aps-maps' );
    }
endif;


// PROJECT RELATED
if ( ! function_exists( 'the_project_related' ) ) :
    function the_project_related()
    {
        $data = aps_get_all_metadata(get_the_ID());
        if (isset($data['project_show_related']) && $data['project_show_related']=='yes')
        {

            $filter     = $data['project_related_filter'];
            $type       = $data['project_related_type'];
            $size       = $data['project_related_size'];
            $number     = $data['project_related_limit'];
            $orderby    = $data['project_related_orderby'];
            $order      = $data['project_related_order'];

            $sc_cats = '';
            $sc_tags = '';

            if ( $filter == 'categories' || $filter == 'all' ) {
                $terms = wp_get_object_terms( get_the_ID(), 'project_category');
                foreach($terms as $term) {
                    $sc_cats .= 'project_category::'.$term->slug.',';
                }
            }

            if ( $filter == 'skills' || $filter == 'all' ) {
                $terms = wp_get_object_terms( get_the_ID(), 'project_skill');
                foreach($terms as $term) {
                    $sc_cats .= 'project_skill::'.$term->slug.',';
                }
            }

            if ( $filter == 'tags' || $filter == 'categories_and_tags' ) {
                $terms = wp_get_object_terms( get_the_ID(), 'project_tag');
                foreach($terms as $term) {
                    $sc_tags .= 'project_tag::'.$term->slug.',';
                }
            }
            if (strlen($sc_cats)>0) {
                $sc_cats = 'categories_aps_project="'.substr($sc_cats,0,-1).'"';
            }
            if (strlen($sc_tags)>0) {
                $sc_tags = 'tags_aps_project="'.substr($sc_tags,0,-1).'"';
            }


            $sc = "[aps_posts_carousel type='$type' size='$size' source='aps_project' $sc_cats $sc_tags limit='$number' orderby='$orderby' order='$order']";
            $html_shortcode = do_shortcode($sc);

            //echo '<pre>'; print_r( $sc ); echo '</pre>';

            //Solo se muestra si tiene relacionados
            if ($html_shortcode)
            {
                ?>
                <div class="related-posts post-box">
                    <div class="title">
                        <h3><?php echo __('Related projects:', LANGUAGE_THEME); ?></h3>
                    </div>
                    <?php  echo $html_shortcode; ?>
                </div>
            <?php
            }

        }
    }
endif;

if ( ! function_exists( 'the_project_social' ) ) :
    function the_project_social() {
        aps_content_entry_social();
    }
endif;
