<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

/*
Funciones para navegacion del contenido

aps_link_pages (before after post)
aps_pagenav ( paginacion )
aps_breadcrumbs


*/

//wp link pages
// =============================================================================

if ( ! function_exists( 'aps_link_pages' ) ) :
  function aps_link_pages() {
      if (!is_singular()) return false;

        wp_link_pages( array(
          'before' 			=> '<div class="page_links"><span class="page_links_title">' . __( 'Pages:', LANGUAGE_THEME ) . '</span>',
          'after'  			=> '</div>',
          'link_before' => '<span class="page_link_title">',
          'link_after'	=> '</span>'
        ) );
      }
endif;


//page navigation
// =============================================================================

if ( ! function_exists( 'aps_pagenav' ) ) :
  function aps_pagenav($before = '', $after = '') {
  	
  	//Next Prev post
  	if (is_single()){
  	
  		echo '<div class="pagination-posts">';
	  	previous_post_link('<span class="nav-pre-post"> %link</span>');
	  	next_post_link('<span class="nav-next-post">%link </span>');
	  	echo '</div>';

  	} else {

        //Vale para blog y blog-archive
        global $aps_config;
        $pagination = $aps_config['aps_op_blog']['blog_show_pagination'];

        //Es el blog
	  	if (is_home())
        {
            switch ($pagination){
                case 'numbers':
                    aps_page_nav_numbers();
                    break;
                case 'load_more':
                    aps_page_nav_load_more();
                    break;
                case 'scroll':
                    aps_page_nav_scroll();
                    break;
                default:
                    aps_page_nav_numbers();
                    break;
            }
        }
        //Para category, tag, author, date
        else if (is_archive() || is_search())
        {
            switch ($pagination){
                case 'numbers':
                    aps_page_nav_numbers();
                    break;
                case 'load_more':
                    aps_page_nav_load_more();
                    break;
                case 'scroll':
                    aps_page_nav_scroll();
                    break;
                default:
                    aps_page_nav_numbers();
                    break;
            }
        }
	  	
  	}
  	
}
endif;


function aps_page_nav_numbers()
{
    //Pagination posts
    //posts_nav_link();
    global $paged;
    if(get_query_var('paged')) {
        $paged = get_query_var('paged');
    } else if (get_query_var('page')) {
        $paged = get_query_var('page');
    } else {
        $paged = 1;
    }
    //echo '<h1>'.$paged.'</h1>';

    global $wp_query;
    $max_pages = $wp_query->max_num_pages;
    if ($max_pages==1) return false;

    $html = '<div class="pagination-numbers">';
    $html .= '<ul class="">';
    if ($paged > 1) {
        $html .= '<li><a class="button-style-1 nav-first" href="' . esc_url(get_pagenum_link(1)) . '"></a></li>';
    }
    for ($i=1; $i<=$max_pages; $i++)
    {
        if ($i==$paged){
            $html .= '<li><span class="nav-current button-style-1 active">'.$i.'</span></li>';
        } else if( ($i<=$paged+2 && $i>=$paged-2) || $i%10 == 0 ){
            $html .= '<li><a class="button-style-1 nav-page" href="'.esc_url( get_pagenum_link( $i ) ).'">'.$i.'</a></li>';
        }
    }
    if ($paged<$max_pages) {
        $html .= '<li><a class="button-style-1 nav-last" href="' . esc_url(get_pagenum_link($max_pages)) . '"></a></li>';
    }
    $html .= '</ul></div>';


    $html .= sprintf('<div class="pagination-text">'.__('Page %1$s of %2$s',LANGUAGE_THEME).'</div>',$paged, $max_pages);
    echo $html;
}

function aps_page_nav_load_more()
{
    aps_page_nav_ajax( __('LOAD MORE', LANGUAGE_THEME), '');
}

function aps_page_nav_scroll()
{
    aps_page_nav_ajax( __('SCROLL TO LOAD MORE', LANGUAGE_THEME), 'infinite-scroll');
}

function aps_page_nav_ajax( $text='LOAD MORE', $class='' )
{
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    $data_query = $wp_query->query;
    unset($data_query['paged']); //NO quiero que pase la paged puesto que debe ser la 1 cuando echa este ajax
    $is_archive = is_archive() ? 'yes' : 'no';

    if ($pages ==1 ) return false;

    $html = '<div class="blog-pagination-ajax ajax pagination-ajax '.$class.'">';
    $html .= '<a class="button-style-1" href="javascript: void(0);"  data-max_num_pages="' . $pages . '" data-query="'.esc_attr(json_encode($data_query)).'" data-is_archive="'.$is_archive.'">';
    $html .= $text;
    $html .= '</a>';
    $html .= '<span><img src="' . APS_THEME_URI . '/includes/stylesheets/images/preloaders/boxes2.gif' . '"></span>';
    $html .= '<script>var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
    $html .= '</div>';
    echo $html;
}


function aps_post_pagination_link($link)
{
	$url =  preg_replace('!">$!','',_wp_link_page($link));
	$url =  preg_replace('!^<a href="!','',$url);
	return $url;
}

//breadcrumbs
// =============================================================================


if ( ! function_exists( 'aps_breadcrumbs' ) ) :
  function aps_breadcrumbs() {
  	
  	echo '<h1>BREADCRUMBS</h1>';
  	
  	global $post;
  	
  	$home = '<span class="home"><i class="fa fa-home"> Home</i></span>';
  	$home_url = home_url();
  	$delimiter = '<span class="delimiter"><i class="fa fa-angle-right"></i></span>';
  	$before = '<span class="current">';
  	$after = '</span>';
  	$page_title = get_the_title();
    $blog_title = get_the_title( get_option( 'page_for_posts', true ) );
  	$show_current = 1;  
  	
  	$html = '<div class="aps-breadcrumbs">';
  	
  	// FRONT PAGE
  	if ( is_front_page() ) {
  	
  		$html .= $before.$home.$after;
  	
  	// HOME
  	} elseif ( is_home() ) {
  	
  		$html .= '<a href="'.$home_url.'">'.$home.'</a>'.$delimiter.' '.$before.$blog_title.$after;
  	
  	// THE REST
  	} else {
  	
  		$html .= '<a href="'.$home_url.'">'.$home.'</a>'.$delimiter;
  		
  		// CATEGORY
  		if ( is_category() ) {
  		
  			$the_cat = get_category( get_query_var( 'cat' ), false );
  			if ($the_cat->parent != 0)
  				$html .= get_category_parents( $the_cat->parent, TRUE, ' ' . $delimiter . ' ' );
  			$html .= $before . __( 'Category ', LANGUAGE_THEME ) . '&ldquo;' . single_cat_title( '', false ) . '&rdquo;' . $after;
  			
  		// SEARCH
  		} elseif ( is_search() ) {
	  		
	  		$html .= $before . __( 'Search Results for ', LANGUAGE_THEME ) . '&ldquo;' . get_search_query() . '&rdquo;' . $after;
	  	
	  	// SINGULAR
  		} elseif ( is_singular( 'post' ) ) {
  		
  			//La pagina de los posts
  			if ( get_option( 'page_for_posts' ) == is_front_page() ) {
  				$html .= ' ' . $before . $page_title . $after;
  			} else {
	  			$html .= '<a href="' . get_permalink( get_option( 'page_for_posts' ) ) . '" title="' . esc_attr( __( 'See All Posts', LANGUAGE_THEME ) ) . '">' . $blog_title . '</a> ' . $delimiter . ' ' . $before . $page_title . $after;
  			}
  		
  		// PAGE without parent
  		} elseif ( is_page() && !$post->post_parent ) {
  		
  			if ( $show_current == 1 ) $html .= $before . $page_title . $after;
  			
  		// PAGE with parent
  		} elseif ( is_page() && $post->post_parent ) {
  			
  			$parent_id   = $post->post_parent;
  			$breadcrumbs = array();
  			while ( $parent_id ) {
  				$pag = get_page( $parent_id );
  				$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
  				$parent_id     = $page->post_parent;
  			}
  			$breadcrumbs = array_reverse( $breadcrumbs );
  			for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
	  			$html .= $breadcrumbs[$i];
	  			if ( $i != count( $breadcrumbs ) -1 )
	  				$html .= ' ' . $delimiter . ' ';
  			}
  			if ( $show_current == 1 )
  				$html .= ' ' . $delimiter . ' ' . $before . $page_title . $after;
  		
  		// TAG
  		} elseif ( is_tag() ) {
  		
  			$html .= $before . __( 'Posts Tagged as ', LANGUAGE_THEME) . '&ldquo;' . single_tag_title( '', false ) . '&rdquo;' . $after;
  		
  		// AUTHOR
  		} elseif ( is_author() ) {
  		
  			global $author;
  			$userdata = get_userdata( $author );
  			$html .= $before . __( 'Posts by ', LANGUAGE_THEME ) . '&ldquo;' . $userdata->display_name . $after . '&rdquo;';
  		
  		// $=$
  		} elseif ( is_404() ) {
  		
  			$html .= $before . __( '404 (Page Not Found)', LANGUAGE_THEME ) . $after;
  		
  		// ARCHIVE
  		} elseif ( is_archive() ) {
  			
  			$html .= $before . __( 'Archives ', LANGUAGE_THEME ) . $after;
  			
  		}
  		
  		// PAGED
  		if ( get_query_var( 'paged' ) ) {
  		
  			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $html .= ' (';
  			
        $html .= '<span class="current" style="white-space: nowrap;">' . __( 'Page', LANGUAGE_THEME ) . ' ' . get_query_var( 'paged' ) . '</span>';
        
        if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
          
  		}

  	}
  	
  	$html .= '</div>';
  	
  	echo $html;
  }
endif;


