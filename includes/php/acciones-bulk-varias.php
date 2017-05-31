<?php

function actualizar_meta_de_proyectos() {

    $args = array(
        'orderby'          => 'post_title',
        'order'            => 'DESC',
        'post_type'        => 'aps_project',
        'posts_per_page'   => -1
    );
    $posts_array = get_posts($args);

    if ($posts_array){
        foreach ($posts_array as $post){
            $id = $post->ID;
            //echo '<h1>'.$id.'-'.$post->post_title.'</h1>';
            $meta = array(
                'project_show_skills' => 'no',
                'project_show_tags' => 'no',
                'project_show_categories' => 'no',
                'project_show_social' => 'no',
                'project_show_related' => 'no'
                //'ping_status' => 'open',
                //'comment_status' => 'open'
            );

            foreach( $meta as $key=>$value) {
                //echo '<div>'.$key.': '.get_post_meta( $id, $key, true ).'</div>';
                update_post_meta( $id, $key, $value );
            }
        }
    }

}

if ( is_admin() ) {
    //actualizar_meta_de_proyectos();
}