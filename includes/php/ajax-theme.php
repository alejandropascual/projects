<?php

add_action('wp_ajax_aps_get_image_src','aps_get_image_src_ajax');


if (!function_exists('aps_get_image_src_ajax')):
	function aps_get_image_src_ajax()
	{
		$image_id = $_REQUEST['image_id'];
		$size = $_REQUEST['size'];
		$src = wp_get_attachment_image_src($image_id, $size);
		
		if (!empty($src))
			echo $src[0];
		else
			echo 0;
			
		die();
	}
endif;


add_action('wp_ajax_aps_gallery_preview', 'aps_gallery_preview');

if (!function_exists('aps_gallery_preview')):
	function aps_gallery_preview()
	{
		$result = array('success' => false, 'output' => '');
		
		$ids = $_REQUEST['attachments_ids'];
		if (empty($ids)){
			echo json_encode( $result );
			exit;
		}
		
		$ids = explode( ',', $ids );

		foreach ( $ids as $id ) {
			$attach = wp_get_attachment_image_src( $id, 'thumbnail', false);
	
			$link_edit = admin_url('post.php?post='.$id.'&action=edit');
			$result["output"] .= '<li><a target="_blank" href="'.$link_edit.'"><img src="'.$attach[0] .'" /></a></li>';
	
		}
		$result["success"] = true;
		echo json_encode( $result );
		exit;
	}
endif;



add_action('wp_ajax_aps_blog_pagination', 'aps_blog_pagination');
add_action('wp_ajax_nopriv_aps_blog_pagination', 'aps_blog_pagination');

if (!function_exists('aps_blog_pagination')):
    function aps_blog_pagination()
    {
        $page_number = $_REQUEST['page_number'];
        if (isset($_REQUEST['data_query'])) {
            $data_query = $_REQUEST['data_query'];
        } else {
            $data_query = array();
        }
        $is_archive = $_REQUEST['is_archive'];

        //Prepara los datos forzando si es archive o no
        aps_calcular_blog_layout($is_archive); //Layout diferente para blog y archive
        $blog_template = aps_blog_template();

        //La consulta
        query_posts(  array_merge( $data_query,  array('paged' => $page_number) ) );

        if ( have_posts() ) :
            while ( have_posts() ) :

                the_post();
                //get_template_part('content', 'blog');
                get_template_part('layout/blog/'.$blog_template);

            endwhile;
        endif;


        exit;
    }
endif;