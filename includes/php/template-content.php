<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


////////////////////////////////////////
// TEMPLATE TAGS
////////////////////////////////////////
/*

Son para el contenido de los posts

*/


//Blog style class
// =============================================================================

//aprovecho aqui para saber las opciones de estilo del blog

if ( ! function_exists( 'aps_blog_style_class' ) ) :
    function aps_calcular_blog_layout( $is_archive = null)
    {
        global $aps_config;

        //Para los ejemplos del tema se puede sobreescribir el layout
        //definiendo query var type=masonry / type=grid / type=list
        //el resto de parametros los coje de las opciones


        ///////////////////////////////////////////////////////////
        // *** SUPER IMPORTANTE PARA EL RESTO DE CONTENIDO ***** //
        ///////////////////////////////////////////////////////////
        //Guardo todos los datos para mostrar el blog o archive
        if ($is_archive == 'yes' || is_archive()) {
            $aps_config['aps_op_blog'] = get_option('aps_op_blog_archive');
        } else {
            $aps_config['aps_op_blog'] = get_option('aps_op_blog');
        }



        //Ahora decido la clase y tipo de blog
        $data = $aps_config['aps_op_blog'];
        $blog_type = $data['blog_layout_type'];

        $blog_type = $data['blog_layout_type'];
        $blog_layout = 'list_b1'; // por defecto

        //Dentro de list,masonry,grid cojo el tipo
        if ($blog_type == 'masonry') {
            $blog_layout = $data['blog_layout_masonry'];
        } else if ($blog_type == 'grid') {
            $blog_layout = $data['blog_layout_grid'];
        } else {
            $blog_layout = $data['blog_layout_list'];
        }
        $aps_config['blog_type'] = $blog_type;
        $aps_config['blog_layout'] = $blog_layout;

        do_action('aps_change_blog_type_from_query_var');
    }
endif;


if ( ! function_exists( 'aps_blog_style_class' ) ) :
  function aps_blog_style_class($echo=true) {
  	
  		//Guardo todas las opciones para lo que me haga falta
  		global $aps_config;

		//Layout del blog
        aps_calcular_blog_layout();//Aqui esta lo importante ****************

        $blog_type = $aps_config['blog_type'];
        $blog_layout = $aps_config['blog_layout'];
        $data = $aps_config['aps_op_blog'];

		//Clase
        $class = '';
		if ($blog_layout != ''){
		  $class = 'posts-'.$blog_layout;
		}
		
		//Es masonry o grid?
		//if (preg_match('/masonry/', $blog_layout)) {
		//if ( strstr($blog_layout, 'masonry') ) {
		if ($blog_type == 'masonry') {
			$class .= ' isotope '.$data['masonry_width'].' '.$data['masonry_margin'];
		} else if ($blog_type == 'grid') {
            $class .= ' isotope grid '.$data['grid_width'].' '.$data['grid_margin'];
        }

		
		//Opciones elementos particulares para list y masonry
        $values = array();
        if ($blog_type=='masonry' || $blog_type=='list' || $blog_type=='grid') {
            $values = array('image','date', 'title', 'meta', 'author', 'cats', 'comments', 'content', 'social', 'more', 'border');
        }
        //else if ($blog_type=='grid') {
        //    $values = array('border');
        //}
      //para grid solo afecta el border
        foreach ($values as $value) {
            if ( isset( $data['blog_show_' . $value] ) && $data['blog_show_' . $value] == 'no') {
                $class .= ' hidden-' . $value;
            }
        }
		
		//Son alternate posts
		global $aps_config;
		$aps_config['use_alternate_post'] = false;
		
		//Que listados van alternados
		if ($blog_layout=='list_m3' || $blog_layout=='list_b2a' || $blog_layout=='list_b3a'){
			$aps_config['use_alternate_post'] = true;
		}


		if ($echo){
			echo ' '.$class;
		} else {
			return $class;
		}
  }
endif;



function aps_blog_template( $blog_layout = null, $blog_type = null)
{
    global $aps_config;

    //Saber el template que voy a usar
    if ($blog_layout == null && $blog_type == null)
    {
        $blog_layout = $aps_config['blog_layout'];
        $blog_type = $aps_config['blog_type'];
    }


    if ( $blog_type == 'list' || $blog_type == 'grid' ){
        $blog_template = $blog_type;
    } else {
        $blog_template = $blog_layout;
    }
    return $blog_template;
}



//Single post wrapper class
// =============================================================================

if ( ! function_exists( 'aps_post_wrapper_style_class' ) ) :
  function aps_post_wrapper_style_class( ) {
 
	//Opciones elementos particulares
	$values = array('border');
	foreach($values as $value){
		if (aps_get_option('single_show_'.$value)=='no'){
			echo ' hidden-'.$value;
		}
	}
  }
endif;


//Single post class
// =============================================================================


//aprovecho aqui para saber las opciones de estilo del single post page
if ( ! function_exists( 'aps_post_style_class' ) ) :
  function aps_post_style_class( $classes ) {
 
	  
	global $post;
	if ( FALSE === strpos( $post->post_content, '<!--more-->' ) ) {
		array_push( $classes, 'has-no-more-link' );
	} 
 
	return $classes;
  }
endif;


add_filter( 'post_class', 'aps_post_style_class' );


//Page title
// =============================================================================

if ( ! function_exists( 'aps_content_page_title' ) ) :
    function aps_content_page_title() {


        //Blog
        //Sin page especificada
        $show_title = 'yes';
        $title = '';

        if (is_home() && is_front_page())
        {
            $title = aps_get_option('blog_title');

        //Page especificada
        } else if (is_home()) {
            $page_for_posts = get_option('page_for_posts');
            $title = get_the_title( $page_for_posts );
            $show_title = get_post_meta($page_for_posts,'show_page_title',true);
        }
        else if (is_category()) {
            $title = single_cat_title('',false);
        }
        else if (is_tag()) {
            $title = single_tag_title('',false);
        }
        else if (is_author()) {
            global $author;
            $userdata = get_userdata($author);
            $title = sprintf( __( 'Author: %s', LANGUAGE_THEME ), $userdata->display_name );
        }
        else if ( is_day() ) {
            $title = sprintf(__('Daily Archives: %s',LANGUAGE_THEME), get_the_date());
        }
        else if ( is_month() ) {
            $title = sprintf( __( 'Monthly Archives: %s', LANGUAGE_THEME ), get_the_date( _x( 'F Y', 'monthly archives date format', LANGUAGE_THEME ) ) );
        }
        else if ( is_year() ) {
            $title = sprintf( __( 'Yearly Archives: %s', LANGUAGE_THEME ), get_the_date( _x( 'Y', 'yearly archives date format', LANGUAGE_THEME ) ) );
        }


        if ( $show_title == 'yes' && $title != '' ) {
            echo  '<h2 class="page_title">'.$title.'</h2>';
        }


    }
endif;


//Post title
// =============================================================================

if ( ! function_exists( 'aps_content_entry_title' ) ) :
  function aps_content_entry_title( $h = 'h2', $class="post_title") {
  
  	if ( is_singular() ) {
		the_title( '<'.$h.' class="'.$class.'">', '</'.$h.'>' );
	} else {
		$tit = esc_attr(sprintf(__('Permalink to: &#39;%s&#39;',LANGUAGE_THEME), the_title_attribute('echo=0')));
		the_title( '<'.$h.' class="'.$class.'"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" title="'.$tit.'">', '</a></'.$h.'>' );
	}
	
  }
endif;



//Icono more sobre imagen
// =============================================================================

if ( ! function_exists( 'aps_content_entry_more' ) ) :
  function aps_content_entry_more() {
	echo '<a class="post_more" href="'.esc_url( get_permalink() ).'">+</a>';  
  }
endif;



//Post media: image, gallery, video, audio, quote, link
// =============================================================================

if ( ! function_exists( 'aps_content_entry_media' ) ) :
    function aps_content_entry_media( ) {

        //Para un post sencillo
        if (is_single())
        {
            //Comprobar si tengo que sobreescribir la featured image con un shortcode
            $featured_use_sc = get_post_meta( get_the_ID(), 'post_featured_as_shortcode', true);
            if ( $featured_use_sc && $featured_use_sc == 'yes' ) {
                $sc = get_post_meta( get_the_ID(), 'post_featured_shortcode', true);
                echo do_shortcode($sc);
            } else {
                aps_content_entry_media_normal();
            }

        }

        //Para el blog
        //puede ser imagen de proporcion natural, o imagen recortada, depende del tipo de blog
        //Algunos tipos de blogs requieren la imagen de proporcion natural
        //y otros una imagen recortada (list_m1, list_m2, list_m3, grid_1, grid_2, grid_3)
        else
        {
            global $aps_config;
            $tipo_blog = $aps_config['blog_layout'];

            //Tamaños predefinidos segun tipo de blog
            //Para el grid deberia variar el tamaño segun el num de cols
            $blogs_recortar = array(
                'list_m1' => array('w'=>590,'h'=>295),
                'list_m2' => array('w'=>590,'h'=>295),
                'list_m3' => array('w'=>590,'h'=>300),
                'grid_1' => array('w'=>440,'h'=>440),
                'grid_2' => array('w'=>440,'h'=>290),
                'grid_3' => array('w'=>290,'h'=>490),
            );

            //echo '<h1>Blog tipo: '.$tipo_blog.'</h1>';

            //Imagen para recortar
            if ( in_array( $tipo_blog, array_keys($blogs_recortar)) )
            {
                //echo '<h2>Imagen recortada</h2>';
                $width = $blogs_recortar[$tipo_blog]['w'];
                $height = $blogs_recortar[$tipo_blog]['h'];
                aps_content_entry_media_resized( $width, $height );
            }

            //Imagen sin recortar
            else
            {
                //echo '<h2>Imagen sinn recortar</h2>';
                aps_content_entry_media_normal();
            }
        }

    }
endif;



//Esto vale para imagen de proporcion natural
//el quote y link tambien mantiene una dimension autoajustable
//segun el texto que lleve
function aps_content_entry_media_normal()
{
    $post_format = get_post_format();

    switch ($post_format)
    {
        case 'image':
            aps_content_featured_image();
            break;
        case 'gallery':
            aps_content_gallery();
            break;
        case 'video':
            aps_content_video();
            break;
        case 'audio':
            aps_content_audio();
            break;
        case 'quote':
            aps_content_quote();
            break;
        case 'link':
            aps_content_link();
            break;
        default:
            aps_content_featured_image();
            break;
    }
}


function aps_content_entry_media_resized( $width=600, $height=400)
{

    $post_format = get_post_format();
    switch ($post_format)
    {
        case 'image':
            aps_content_featured_image( $width, $height );
            break;
        case 'gallery':
            aps_content_gallery( $width, $height );
            break;
        case 'video':
            aps_content_video( $width, $height );
            break;
        case 'audio':
            aps_content_audio( $width, $height );
            break;
        case 'quote':
            aps_content_quote( $width, $height );
            break;
        case 'link':
            aps_content_link( $width, $height );
            break;
        default:
            aps_content_featured_image( $width, $height);
            break;
    }

}


//Devuelve el tamaño de la imagen necesaria segun el tipo de blog
//Esto vale para las imaganes que mantienen la proporcion solamente
//si tengo que recortarla ya o me vale
function aps_dame_image_size_segun_single_blog()
{
    if ( is_single() || is_singular() ) {
        return 'full';
    }

    //El tipo de blog puede ser list, masonry, grid
    //devuelve diferente segun el tipo de blog
    $size_image = 'medium';

    global $aps_config;
    $tipo_blog = $aps_config['blog_layout'];


    if (strpos($tipo_blog,'list') !== false)
    {
        $size_image = 'large';
    }
    else if (strpos($tipo_blog,'masonry') !== false)
    {
        //Depende del ancho de la imagen devuelvo un tamaño u otro+
        $size_image = 'medium';
        $masonry_width = $aps_config['aps_op_blog']['masonry_width'];
        $masonry_width =  intval( str_replace('width-','',$masonry_width) );


        if ($masonry_width>300) {
            $size_image = 'large';
        }

    }
    else if (strpos($tipo_blog,'grid') !== false)
    {
        $size_image = 'medium';
    }


    return $size_image;
}



//Post date
// =============================================================================

if ( ! function_exists( 'aps_content_entry_date' ) ) :
  function aps_content_entry_date() {

      $option = aps_get_option('blog_date_format');

      $day 	= esc_html(get_the_date('d'));
      $month 	= esc_html(get_the_date('F'));
      $year 	= esc_html(get_the_date('Y'));

      $html  = '<div class="post_date">';
      $html .= '<div class="date_holder">';


      if ($option == 'dmy') {
          $html .= '<h3 class="date">'.$day.'</h3>';
          $html .= '<h5 class="month">'.$month.'</h5>';
          $html .= '<h5 class="year">'.$year.'</h5>';
      } else if ($option == 'myd') {
          $html .= '<h5 class="month">'.$month.'</h5>';
          $html .= '<h5 class="year">'.$year.'</h5>';
          $html .= '<h3 class="date">'.$day.'</h3>';
      } else if ($option == 'mdy') {
          $html .= '<h5 class="month">'.$month.'</h5>';
          $html .= '<h3 class="date">'.$day.'</h3>';
          $html .= '<h5 class="year">'.$year.'</h5>';
      } else {
          $html .= aps_content_entry_date_simple('','<h5 class="date">','</h5>',false);
      }
      $html .= '</div>';
      $html .= '</div>';

      echo $html;
  }
endif;

//Post date simple, cambiada de la the_date()
// =============================================================================
if ( ! function_exists( 'aps_content_entry_date_simple' ) ) :
    function aps_content_entry_date_simple( $d = '', $before = '', $after = '', $echo = true ) {
        global $currentday, $previousday;
        $the_date = '';

        //if ( $currentday != $previousday ) {
            $the_date .= $before;
            $the_date .= get_the_date( $d );
            $the_date .= $after;
            $previousday = $currentday;

            $the_date = apply_filters('the_date', $the_date, $d, $before, $after);

            if ( $echo )
                echo $the_date;
            else
                return $the_date;
        //}

        //return null;
    }
endif;



//Post single: categories
// =============================================================================

if ( ! function_exists( 'aps_content_entry_categories' ) ) :
  function aps_content_entry_categories() {

	//$separator = '';
	$separator = '&nbsp;|&nbsp;';
	
  	//Categories
	$list_categories = '';
	$cats = get_the_category();
    foreach ($cats as $cat) {
    	$c_href = get_category_link($cat->term_id);
    	$c_title = esc_attr( sprintf( __("View all posts in: &#39;%s&#39;",LANGUAGE_THEME), $cat->name ));
    	$c_name = $cat->name;
	    $list_categories .= '<a class="post_cat" href="'.$c_href.'" title="'.$c_title.'">'.$c_name.'</a>'.$separator;
    }
    $list_categories = sprintf('%s', trim($list_categories, $separator));
    ?>
    <div class="post_meta">
		<div class="meta_holder">
			<?php echo $list_categories; ?>
		</div>
	</div>
	<?php
  }
endif;

//Post meta: author, categories, comments
// =============================================================================

if ( ! function_exists( 'aps_content_entry_meta' ) ) :
  function aps_content_entry_meta() {

    //En la search page pasa por aqui con posts y projects
    if ( get_post_type() == 'aps_project' )
    {
        $sep = '&nbsp;&nbsp;|&nbsp;&nbsp;';
        $list_categories = get_the_term_list(get_the_ID(), 'project_category', '', $sep, '');
        $list_tags = get_the_term_list(get_the_ID(), 'project_tag', '', $sep, '');
        $list_skills = get_the_term_list(get_the_ID(), 'project_skill', '', $sep, '');
        $list = $list_skills;
        if ($list_categories != '') {
            $list = ($list = !'' ? $list . $sep . $list_categories : $list_categories);
        }
        if ($list_tags != '') {
            $list = ($list = !'' ? $list . $sep . $list_tags : $list_tags);
        }

        ?>
        <div class="post_meta">
            <div class="meta_holder">
                <?php echo $list; ?>
            </div>
        </div>
        <?php
        return;
    }


  	global $aps_config;
  	
  	$separator = '&nbsp;&nbsp;|&nbsp;&nbsp;';
  
  	//Autor
  	if ( isset($aps_config['aps_op_blog']['blog_show_author']) && $aps_config['aps_op_blog']['blog_show_author']=='no' ) {
  		$author = '';
  	} else {
	  	$author_href = get_author_posts_url( get_the_author_meta( 'ID' ) );
	  	$author_name = get_the_author();
	  	$author_title = esc_attr( sprintf( __('All posts by: &#39;%s&#39;',LANGUAGE_THEME), $author_name ) );
	  	$author = sprintf( '<span class="post_author_by">%1$s</span><a class="post_author" href="%2$s" title="%3$s">%4$s</a>', __('By ',LANGUAGE_THEME),$author_href, $author_title, $author_name);
	  	$author .= $separator;	
  	} 
  	
  
  
  	//Categories
  	if ( isset($aps_config['aps_op_blog']['blog_show_cats']) && $aps_config['aps_op_blog']['blog_show_cats']=='no' ) {
  		$list_categories = '';
  	} else {
	  	$list_categories = '';
		$cats = get_the_category();
	    foreach ($cats as $cat) {
	    	$c_href = get_category_link($cat->term_id);
	    	$c_title = esc_attr( sprintf( __("View all posts in: &#39;%s&#39;",LANGUAGE_THEME), $cat->name ));
	    	$c_name = $cat->name;
		    $list_categories .= '<a class="post_cat" href="'.$c_href.'" title="'.$c_title.'">'.$c_name.'</a>'.$separator;
	    }
	    $list_categories = sprintf('%s', trim($list_categories, $separator));
	    $list_categories .= $separator;
  	}
	
    
    
    //Comments
    if ( isset($aps_config['aps_op_blog']['blog_show_comments']) && $aps_config['aps_op_blog']['blog_show_comments']=='no' ) {
  		$comments = '';
  	} else {
	    $comments_number = get_comments_number();
	    $comments_link = esc_url(get_comments_link());
	    
	    if ($comments_number==0) {
		     $comments_title = __('Leave a comment on: &#39;%s&#39;', LANGUAGE_THEME);
		     $comments_msg = 'Leave a comment';
		     
	    } else if ($comments_number==1) {
		    $comments_title = __('Leave a comment on: &#39;%s&#39;', LANGUAGE_THEME);
		    $comments_msg = '1 Comment';
		    
	    } else {
		    $comments_title = __('View all comments on: &#39;%s&#39;', LANGUAGE_THEME);
		    $comments_msg = $comments_number . ' Comments';
	    }

        //En vez del mensaje pongo un icono
        $comments_msg = ($comments_number>0 ? $comments_number.'&nbsp;' : '') . '<i class="fa fa-comment"></i>';

	    $comments = sprintf('<a href="%1$s" title="%2$s" class="post_comments">%3$s</a>',
	    		$comments_link,
	    		esc_attr( sprintf( $comments_title, get_the_title() ) ),
	    		$comments_msg);

    }	
    						
  
  	?>
	<div class="post_meta">
		<div class="meta_holder">
			<?php echo $author; ?>
			<?php echo $list_categories; ?>
			<?php echo $comments; ?>
		</div>
	</div>
	<?php
  
  }
endif;



//Post Tags
// =============================================================================

if ( ! function_exists( 'aps_content_entry_tags' ) ) :
  function aps_content_entry_tags() {
  	
  	?>
  	<div class="post_meta">
		<div class="meta_holder">
  		<?php the_tags('', '&nbsp;&nbsp;|&nbsp;&nbsp;', ''); ?>
  		</div>
	</div>	
  	<?php
  }
endif;


//Post Content
// =============================================================================

if ( ! function_exists( 'aps_content_entry_content' ) ) :
    function aps_content_entry_content_limited()
    {
        global $aps_config;
        //Es el mismo nombre de opcion para aps_op_blog y aps_op_blog_archive
        //echo '<pre>'; print_r( $aps_config['aps_op_blog'] ); echo '</pre>';

        $max_words = $aps_config['aps_op_blog']['content_words'];
        $read_more = $aps_config['aps_op_blog']['content_readmore'];

        aps_content_entry_content($max_words,false,$read_more);
    }
endif;


function aps_excerpt_length($length){
    //return 20;
    global $aps_config;
    return $aps_config['excerpt_length'];
}



if ( ! function_exists( 'aps_content_entry_content' ) ) :
    function aps_content_entry_content($max_words = -1, $strip_tags = false, $read_more = 'no')
    {
        if (is_singular()){
            echo '<div class="post_content">';
            the_content();
            echo '</div>';
            return true;
        }


        if ($max_words=='-1')
        {
            remove_filter( 'excerpt_length', 'aps_excerpt_length', 999 );
        }
        else
        {
            $max_words = intval( $max_words );
            $limit = $max_words+1;
            global $aps_config;
            $aps_config['excerpt_length'] = $limit;
            add_filter( 'excerpt_length', 'aps_excerpt_length', 999 );
        }



        echo '<div class="post_content">';
        the_excerpt();
        if ($read_more=='yes')
        {
            $readmore = ' <a class="read-more" href="'.get_permalink( get_the_ID() ).'">'.__('Read More',LANGUAGE_THEME).'</a>';
            echo $readmore;
        }
        echo '</div>';
        return true;

    }
endif;



//Post Share
// =============================================================================

if ( ! function_exists( 'aps_content_entry_social' ) ) :
  function aps_content_entry_social() {
  
  	//Este link puede ser relativo
  	$link = esc_url( get_permalink() );
  	$title = the_title_attribute('echo=0');
  	$nofollow = '';
	if(aps_get_option('share_nofollow')=='yes') {
		$nofollow = ' rel="nofollow"';
	}
	
	$pinterest_image = '';
	if (has_post_thumbnail()){
		$image = wp_get_attachment_image_src(get_post_thumbnail_id(),'large',null);
		$pinterest_image = $image[0];
	}
	
	$description = get_the_excerpt();
	$tooltip = 'data-toggle="tooltip" data-placement="top"';
	
  	$html  = '<div class="post_social">';
  	$html .= '<span class="social_share_holder">';
  	
  	//Facebook
  	if (aps_get_option('share_facebook') == 'yes'){
	  	$html .= '<a href="http://www.facebook.com/sharer.php?s=100&p&#91;url&#93;='.$link.'&p&#91;images&#93;&#91;0&#93;=http://www.gravatar.com/avatar/2f8ec4a9ad7a39534f764d749e001046.png&p&#91;title&#93;='.$title.'" target="_blank"'.$nofollow.'><i class="fa fa-facebook aps-tooltip" '.$tooltip.' title="Facebook"></i></a>';
  	}
  		
  	//Twitter
  	if (aps_get_option('share_twitter') == 'yes'){
  		$html .= '<a href="http://twitter.com/home?status='.$title.' '.$link.'" target="_blank"'.$nofollow.'><i class="fa fa-twitter aps-tooltip" '.$tooltip.' title="Twitter"></i></a>';
  	}
  	
  	//Linkedin
  	if (aps_get_option('share_linkedin') == 'yes'){
  		$html .= '<a href="http://linkedin.com/shareArticle?mini=true&amp;url='.$link.'&amp;title='.$title.'" target="_blank"'.$nofollow.'><i class="fa fa-linkedin aps-tooltip" '.$tooltip.' title="linkedin"></i></a>';
  	}
  	
  	//Reddit
  	if (aps_get_option('share_reddit') == 'yes'){
        $html .= '<a href="http://reddit.com/submit?url='.$link.'&amp;title='.$title.'" target="_blank"'.$nofollow.'><i class="fa fa-reddit aps-tooltip" '.$tooltip.' title="Reddit"></i></a>';
  	}
  	
  	//Tumblr
  	if (aps_get_option('share_tumblr') == 'yes'){
  		$html .= '<a href="http://www.tumblr.com/share/link?url='.urlencode($link).'&amp;name='.urlencode($title).'&amp;description='.urlencode($description).'" target="_blank"'.$nofollow.'><i class="fa fa-tumblr aps-tooltip" '.$tooltip.' title="Tumblr"></i></a>';
  	}
  	
  	//Google
  	if (aps_get_option('share_google_plus') == 'yes'){
  		$html .= '<a href="https://plus.google.com/share?url='.$link.'" onclick="javascript:window.open(this.href,
  \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" target="_blank"'.$nofollow.'><i class="fa fa-google-plus aps-tooltip" '.$tooltip.' title="Google Plus"></i></a>';
  	}
  	
  	//Pinterest
  	if (aps_get_option('share_pinterest') == 'yes' && $pinterest_image != ''){
  		$html .= '<a href="http://pinterest.com/pin/create/button/?url='.urlencode($link).'&amp;description='.urlencode($title).'&amp;media='.urlencode($pinterest_image).'" target="_blank"'.$nofollow.'><i class="fa fa-pinterest aps-tooltip" '.$tooltip.' title="Pinterest"></i></a>';
  	}
  	
  	//Email
  	if (aps_get_option('share_email') == 'yes'){
  		$html .= '<a href="mailto:?subject='.$title.'&amp;body='.$link.'"><i class="fa fa-envelope aps-tooltip" '.$tooltip.' title="Email"></i></a>';
  	}
  	
  	$html .= '</span>';
  	$html .= '</div>';
  	echo $html;

  }
endif;


//Post meta: author, date, comments, categories
// =============================================================================

if ( ! function_exists( 'aps_content_entry_meta_old' ) ) :
  function aps_content_entry_meta_old() {
  	
  	//Autor
  	$author_href = get_author_posts_url( get_the_author_meta( 'ID' ) );
  	$author_name = get_the_author();
  	$author_title = esc_attr( sprintf( __('All posts by: &#39;%s&#39;',LANGUAGE_THEME), $author_name ) );
  	$author = sprintf( '%3$s<a class="entry-author" href="%1$s" title="%2$s">%4$s</a>', $author_href, $author_title, __('By ',LANGUAGE_THEME),$author_name);
  	
  	//Fecha
  	$date = sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>',
      esc_attr( get_the_date( 'c' ) ),
      //esc_html( get_the_date( 'm.Y' ) )
      esc_html( get_the_date() )
    );
    
    //List categories
    $separator = ', ';
	  $list_categories = '';
	  
    if (get_post_type()=='aps-portfolio') {
	    $cats = get_the_terms( get_the_ID(), 'aps-portfolio');
	    foreach ($cats as $cat) {
	    	$c_href = get_term_link( $cat->slug, 'aps-portfolio');
	    	$c_title = esc_attr( sprintf( __("View all posts in: &#39;%s&#39;",LANGUAGE_THEME), $cat->name ));
	    	$c_name = $cat->name;
		    $list_categories .= '<a href="'.$c_href.'" title="'.$c_title.'">'.$c_name.'</a>'.$separator;
	    }
    } else {
	    $cats = get_the_category();
	    foreach ($cats as $cat) {
	    	$c_href = get_category_link($cat->term_id);
	    	$c_title = esc_attr( sprintf( __("View all posts in: &#39;%s&#39;",LANGUAGE_THEME), $cat->name ));
	    	$c_name = $cat->name;
		    $list_categories .= '<a href="'.$c_href.'" title="'.$c_title.'">'.$c_name.'</a>'.$separator;
	    }
    }
		$list_categories = sprintf('%s', trim($list_categories, $separator));
    
    
    //Comments
    $comments_number = get_comments_number();
    $comments_link = esc_url(get_comments_link());
    
    if ($comments_number==0) {
	     $comments_title = 'Leave a comment on: &#39;%s&#39;';
	     $comments_msg = 'Leave a comment';
	     
    } else if ($comments_number==1) {
	    $comments_title = 'Leave a comment on: &#39;%s&#39;';
	    $comments_msg = '1 Comment';
	    
    } else {
	    $comments_title = 'View all comments on: &#39;%s&#39;';
	    $comments_msg = $comments_number . ' Comments';
    }
    $comments = sprintf('<a href="%1$s" title="%2$s" class="meta-comments">%3$s</a>',
    						$comments_link,
    						esc_attr( sprintf( __($comments_title,'_aps'), get_the_title() ) ),
    						$comments_msg);
    
    //echo
    printf('<p class="entry-meta-p">%1$s%2$s%3$s%4$s</p>',
    	'<span>'.$date.'</span>',
    	'<span>'.$author.'</span>',
    	'<span>'.$list_categories.'</span>',
    	'<span>'.$comments.'</span>'
    	);
    
  }
endif;








//Post featured image
// =============================================================================

if ( ! function_exists( 'aps_content_featured_image' ) ) :
    function aps_content_featured_image( $width=false, $height=false, $apply_filters = true )
    {

        $thumbnail = '';

        if ( has_post_thumbnail() )
        {
            //Imagen sin recortar
            if ( $width === false && $height === false )
            {
                $size_image = aps_dame_image_size_segun_single_blog();
                $thumbnail = get_the_post_thumbnail( null, $size_image, null); // PENDIENTE SIZE PROPIA
            }
            else
            {
                $thumb_id = get_post_thumbnail_id( null );
                $image_resized = aps_get_image_resized_for_id( $thumb_id, $width, $height, 0, 'no');
                if ( isset($image_resized['resized']))
                {
                    $thumbnail = $image_resized['resized']['img'];
                }
                else
                {
                    $thumbnail = aps_placeholder_image($width,$height,'error resized image');
                    $thumbnail = $thumbnail['img'];
                }

            }
        }
        else
        {
            return false; //Nada que mostrar
            //$thumbnail = aps_placeholder_image($width,$height,'no featured image')['img'];
        }


        $image_html = sprintf('<div class="entry-thumb">%s</div>',$thumbnail);

        //Con o sin link
        if (is_singular())
        {
            $html = $image_html;
        }
        else
        {

            $html = sprintf('<a href="%1$s" class="entry-thumb-anchor" title="%2$s">%3$s</a>',
                esc_url( get_permalink() ),
                esc_attr( sprintf( __( 'Permalink to: &#39;%s&#39;', LANGUAGE_THEME ), the_title_attribute( 'echo=0' ) ) ),
                $image_html);
        }

        //echo $html;
        //Wrap la imagen que se posiciona en absolute para poder arrancar el isotope antes
        if ($apply_filters){
            echo apply_filters('aps_filter_featured_image',$html);
        } else {
            echo $html;
        }

    }
endif;



//Lo que hace es poner el contenedor de la imagen con un padding
//y pasar la imagen a position:absolute
//para que se quede el espacio hasta que se cargue la imagen
//De esta forma puedo cargar el isotope aunque la imagen no este lista
function aps_filter_featured_image( $html )
{

    //Extraer ancho y alto de la imagen final
    $width = null;
    $height = null;
    if (preg_match('/width=\"(\d+)?\"/',$html,$match)){
        //echo '<pre>'; print_r( $match ); echo '</pre>';
        $width = $match[1];
    };
    if (preg_match('/height=\"(\d+)?\"/',$html,$match)){
        //echo '<pre>'; print_r( $match ); echo '</pre>';
        $height = $match[1];
    };

    //Tengo que tener las dimensionas para poder aplicar el estilo
    if ($width==null & $height==null) { return $html; }


    //Para pruebas añadir un DELAY a la imagen *****
    if (defined('DEELAY_ME')) {
        $html = preg_replace('~<img.+?src=\"http:\/\/~', '<img $1 src="http://deelay.me/'.DEELAY_ME.'/http://', $html);
    }


    //Preparar los estilo
    $ratio = 100.0 * ( floatval($height) / floatval($width) );

    //El estilo del wrapper
    //$style_entry_thumb = 'position:relative;overflow:hidden;padding-top:'.$ratio.'%;';
    //$html = str_replace('class="entry-thumb"','class="entry-thumb" style="'.$style_entry_thumb.'"', $html);

    //El estilo de la imagen
    //$html = str_replace('<img','<img style="position:absolute;top:0px;width:100%;"', $html);

    //Con css directamente
    $style_entry_thumb = 'padding-top:'.$ratio.'%;';
    $html = str_replace('class="entry-thumb"','class="entry-thumb absolute-image" style="'.$style_entry_thumb.'"', $html);

    return $html;
}

add_filter('aps_filter_featured_image', 'aps_filter_featured_image',10,1);








//Placeholder image
// =============================================================================


function aps_placeholder_image( $width=400, $height=400, $text='no featured image')
{
    $text = str_replace(' ','+',$text);
    $name = 'http://placehold.it/'.$width.'x'.$height.'&text='.$text;
    return array(
        'img' => '<img src="'.$name.'" width="'.$width.'" height="'.$height.'" alt="placeholder">',
        'url' => $name
    );
}



//Post format GALLERY, desde imagenes del post format, meta 
// =============================================================================

if ( ! function_exists( 'aps_content_gallery' ) ) :
    function aps_content_gallery( $width=false, $height=false  ) {
		
        //Obtener imagenes asociadas al post
        $images_id = aps_gallery_images_id_for_post( get_the_ID() );

        //Tengo imagenes para formar el slider
        if ( !empty( $images_id ) )
        {
            //Array para luego montar el slider
            $data_images = array();

            //Imagenes sin recortar
            if ( $width === false && $height === false )
            {
                $size_image = aps_dame_image_size_segun_single_blog();
                foreach ( $images_id as $image_id ) {
                    $image_html = wp_get_attachment_image( $image_id, $size_image, false, false );
                    $data_images[] = $image_html;
                }
            }

            //Con imagenes recortadas
            else
            {
                foreach ( $images_id as $image_id ) {
                    $image_resized = aps_get_image_resized_for_id( $image_id, $width, $height, 0, 'no');
                    if ( isset($image_resized['resized']) ){
                        $data_images[] = $image_resized['resized']['img'];
                    } else {
                        $dat = aps_placeholder_image($width,$height,'error resized image '.$image_id);
                        $data_images[] = $dat['img'];

                    }
                }

            }

            //Ahora genero el slider con las imagenes html, flexslider
            //aps_flexslider_for_images_html( $data_images );
            aps_royalslider_for_images_html( $data_images );

        }
        //No tengo slider, pongo un placeholder
        else
        {
            //Al llamar a esta funcion, como no tengo thumbnail me va a colocar un placeholder
            aps_content_featured_image( $width, $height);
        }

    }
endif;


function aps_royalslider_for_images_html( $data_images )
{
    //La altura del slider lo determina la primera imagen

    //Dimensiones estandar
    $gallery_width  = 300;
    $gallery_height = 200;
    $gallery_mode = 'fill';

    //Obtener las dimensiones de la primera imagen
    $width = null;
    $height = null;
    $html_first_image = $data_images[0];
    if (preg_match('/width=\"(\d+)?\"/',$html_first_image,$match)) { $width = $match[1]; };
    if (preg_match('/height=\"(\d+)?\"/',$html_first_image,$match)) { $height = $match[1]; };
    if ($width!=null && $height!=null) {
        $gallery_width  = $width;
        $gallery_height = $height;
    }

    $data_wrapper  = 'data-rs-width="'.$gallery_width.'" data-rs-height="'.$gallery_height.'" data-rs-mode="'.$gallery_mode.'" data-fullscreen="no"';
    $data_wrapper .= ' data-rs-transition="move" data-rs-autoplay="yes" data-rs-show_buttons="yes" data-rs-show_points="yes"';

    $html = '<div class="aps_animated aps_fadeInUp" data-adelay="500">';
    $html .= '<div class="sliderContainer hidden-border" '.$data_wrapper.'>';
    $html .= '<div class="shortcode-royalSlider royalSlider heroSlider rsMinW">';
    foreach( $data_images as $html_image) {
        $html_image = str_replace( '<img', '<img class="rsImg"', $html_image);
        $html .= '<div class="rsContent">'.$html_image.'</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    echo $html;
}


function aps_flexslider_for_images_html( $data_images )
{

    $html = '<div class="flexslider"><ul class="slides">';
    foreach( $data_images as $image_html) {
        $html .= '<li class="slide">'.$image_html.'</li>';
    }
    $html .= '</ul></div>';
    echo $html;
}


//Variante para poner las imagenes en blanco
//Esta tecnica no funciona bien con el flexslider porque fija la altura
//para la primera imagen con el padding
//y luego no funciona si el resto son de mayor altura
//Mejor usar Royalslider
function aps_flexslider_for_images_html_pruebas( $data_images )
{
    $html = '<div class="flexslider"><ul class="slides">';
    foreach( $data_images as $image_html)
    {
        //Obtener el tamaño de la imagen
        $width = null;
        $height = null;
        if (preg_match('/width=\"(\d+)?\"/',$image_html,$match)){
            //echo '<pre>'; print_r( $match ); echo '</pre>';
            $width = $match[1];
        };
        if (preg_match('/height=\"(\d+)?\"/',$image_html,$match)){
            //echo '<pre>'; print_r( $match ); echo '</pre>';
            $height = $match[1];
        };

        if ($width==null && $height==null)
        {
            //echo '<p>NO TENGO DIMENSIONES</p>';
            $html .= '<li class="slide">'.$image_html.'</li>';

        } else {

            //Para pruebas añadir un delay a la imagen *****
            //$image_html = preg_replace('~<img(.+)?src=\"http:\/\/~','<img $1 src="http://deelay.me/4000/http://' ,$image_html);

            $ratio = 100.0 * ( floatval($height) / floatval($width) );
            $image_html = str_replace('<img','<img style="position:absolute;top:0px;width:100%;"', $image_html);

            $html .= '<li class="slide" style="position:relative;overflow:hidden;padding-top:'.$ratio.'%;">'.$image_html.'</li>';
        }

    }
    $html .= '</ul></div>';
    echo $html;
}



add_filter('aps_filter_flexslider_images', 'aps_filter_flexslider_images', 10,1);

function aps_gallery_images_id_for_post( $post_id )
{
    $images_id = array();

    //Primero la thumbnail
    if (has_post_thumbnail()) {
        $images_id[] = get_post_thumbnail_id( $post_id );
    }

    //Ahora la galeria
    $option = get_post_meta($post_id, 'gallery_images_src', true);

    //Las asociadas al post
    if ($option == 'images_attach')
    {
        $args = array(
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_parent'    => get_the_ID(),
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => null,
            'numberposts'    => -1,
        );
        $attachments = get_posts( $args );
        foreach ($attachments as $attach) {
            $image_id[] = $attach->ID;
        }
    }
    //Las que he introducido en el metabox
    else
    {
        $images_id_more = get_post_meta(get_the_ID(),'gallery_images',true);
        $images_id_more = preg_split('/,/', $images_id_more);
        $images_id = array_merge($images_id,$images_id_more);
    }

    return $images_id;
}


//Post format VIDEO
// =============================================================================

if ( ! function_exists( 'aps_content_video' ) ) :
    function aps_content_video( $width=false, $height=false  ) {

        $ratio = get_post_meta( get_the_ID(), 'pf_video_ratio', true);

        $atts = array(
            'ratio' => $ratio,
            'ratio_custom' => '',
            //'frame' => get_post_meta( get_the_ID(), 'pf_video_frame', true),
            'frame'	=> 'no',
            'skin'	=> get_post_meta( get_the_ID(), 'pf_video_skin', true),
            'autoplay' => get_post_meta( get_the_ID(), 'pf_video_autoplay', true),
            //'m4v'			=> get_post_meta( get_the_ID(), 'pf_video_m4v', true),
            //'ogv'			=> get_post_meta( get_the_ID(), 'pf_video_ogv', true),
            //'webm'		=> get_post_meta( get_the_ID(), 'pf_video_webm', true),
            //'poster'	=> 'http://www.jplayer.org/video/poster/Big_Buck_Bunny_Trailer_480x270.png'
        );


        //Si viene con un tamaño especifico tengo que ajustar esta proporcion
        $ratio_custom = false;
        if ( $width !== false && $height !== false )
        {
            $ratio_custom = round( floatval($height) / floatval($width) , 3);
            $atts['ratio_custom'] = $ratio_custom;
        }


        //Preparar la imagen poster para el video
        //Preparo la imagen con un tamaño segun proporcion del video
        if ( $ratio_custom !== false )
        {
            $width_image = $width;
            $height_image = $height;
        }
        else
        {
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
        }

        if ( has_post_thumbnail() )
        {
            $image_resized = aps_get_image_resized_for_post_id( get_the_ID(), $width_image, $height_image, 0, 'no');

            if ( isset($image_resized['resized']['url']) )
            {
                $atts['poster'] = $image_resized['resized']['url'];
            }
            else
            {
                $atts['poster'] = aps_placeholder_image($width_image,$height_image,'error resize image')['url'];
            }
        }
        else
        {
            $atts['poster'] = aps_placeholder_image($width_image,$height_image,'no featured image')['url'];
        }

        //Para pruebas
        if (defined('DEELAY_ME')) {
            $atts['poster'] = 'http://deelay.me/'.DEELAY_ME.$atts['poster'];
        }


        //m4v
        $m4v = get_post_meta( get_the_ID(), 'pf_video_m4v', true);
        if (isset($m4v) && strlen($m4v)>10){
          $atts['m4v'] = $m4v;
        }

        //ogv
        $ogv = get_post_meta( get_the_ID(), 'pf_video_ogv', true);
        if (isset($ogv) && strlen($ogv)>10){
          $atts['ogv'] = $ogv;
        }

        //webm
        $webm = get_post_meta( get_the_ID(), 'pf_video_webm', true);
        if (isset($webm) && strlen($webm)>10){
          $atts['webm'] = $webm;
        }

        //echo '<pre>'; print_r($atts); echo '</pre>';

        //Video self/hosted or embed
        $self_hosted = get_post_meta( get_the_ID(), 'pf_video_hosted', true);

        //El embed
        $embed = get_post_meta( get_the_ID(), 'pf_video_embed', true);

        //Compruebo si tengo el pagebuilder
        global $aps_config;
        if (isset($aps_config['pagebuilder']))
        {
          $pagebuilder = $aps_config['pagebuilder'];
          //echo '<h1>PAGE BUILDER SI ESTA ACTIVO PARA EL VIDEO</h1>';

          if ($self_hosted == 'yes') {
              $atts['type'] = 'hosted';
          } else {
              $atts['type'] = 'embed_iframe';
              $atts['iframe'] = $embed;
          }

          /*
          $shortcode = '[aps_video';
          foreach($atts as $key=>$value) {
              $shortcode .= " $key='".$value."'";
          }
          $shortcode .= ']';
          echo do_shortcode($shortcode);
          */
          echo $pagebuilder->shortcode_classes['aps_video']->shortcode_handler($atts);
          //echo '<pre>'; print_r( $atts ); echo '</pre>';

        } else {
          echo '<h3>The Plugin aps-spagebuilder is required. Please install it.</h3>';
        }


        //Con aps-shortcodes//
        /*
        if ($self_hosted=='no' && isset($embed) && strlen($embed)>10)
        {
          if (function_exists('aps_shortcode_video_embed')){
                echo aps_shortcode_video_embed($atts, $embed);
            } else {
                echo '<h3>The Plugin aps-shortcodes is required. Please install it.</h3>';
            }
        }
        else
        {
            //Video self hosted
            if (function_exists('aps_shortcode_video_hosted')){
                echo aps_shortcode_video_hosted($atts,'');
            } else {
                echo '<h3>The Plugin aps-shortcodes is required. Please install it.</h3>';
            }
        }
        */


    }
endif;


//Post format AUDIO
// =============================================================================

if ( ! function_exists( 'aps_content_audio' ) ) :
    function aps_content_audio( $width=false, $height=false  ) {

        //echo '<h1>AQUI VA EL AUDIO</h1>';
        $atts = array(
        //'frame' => get_post_meta( get_the_ID(), 'pf_audio_frame', true),
        'frame' => 'no',
        'skin'	=> get_post_meta( get_the_ID(), 'pf_audio_skin', true),
        'autoplay' => get_post_meta( get_the_ID(), 'pf_audio_autoplay', true),
        );

        //La imagen debajo del video

        //Necesito preparar width y height para despues
        if ( has_post_thumbnail() )
        {
            if ( $width !== false &&  $height !== false )
            {
                $thumb_id = get_post_thumbnail_id( null );
                $image_resized = aps_get_image_resized_for_id( $thumb_id, $width, $height, 0, 'no');
                if (isset($image_resized['resized'])){
                    $atts['poster'] = $image_resized['resized']['url'];
                } else {
                    $atts['poster'] = aps_placeholder_image($width,$height,'error resizing image')['url'];
                }
            }
            else
            {
                $size_image = aps_dame_image_size_segun_single_blog();
                $poster = wp_get_attachment_image_src( get_post_thumbnail_id(), $size_image, false, false);
                $atts['poster'] = $poster[0];
                $width = $poster[1];
                $height = $poster[2];
            }
        }
        else
        {
            if ( $width !== false &&  $height !== false ) {
                $atts['poster'] = aps_placeholder_image($width,$height,'no featured image')['url'];
            } else {
                $atts['poster'] = APS_THEME_URI . '/includes/stylesheets/images/postformat/dashicon-audio.png';
                $width = 800;
                $height = 400;
            }
        }

        //Para pruebas
        if (defined('DEELAY_ME')) {
            $atts['poster'] = 'http://deelay.me/'.DEELAY_ME.$atts['poster'];
        }

        //mp3
        $mp3 = get_post_meta( get_the_ID(), 'pf_audio_mp3', true);
        if (isset($mp3) && strlen($mp3)>10){
          $atts['mp3'] = $mp3;
        }

        //oga
        $oga = get_post_meta( get_the_ID(), 'pf_audio_oga', true);
        if (isset($oga) && strlen($oga)>10){
          $atts['oga'] = $oga;
        }

        //m4a
        $m4a = get_post_meta( get_the_ID(), 'pf_audio_m4a', true);
        if (isset($m4a) && strlen($m4a)>10){
          $atts['m4a'] = $m4a;
        }

        $self_hosted = get_post_meta( get_the_ID(), 'pf_audio_hosted', true);
        $embed = get_post_meta( get_the_ID(), 'pf_audio_embed', true);


        //Con aps-pagebuilder
        //Compruebo si tengo el pagebuilder
        global $aps_config;

        if (!isset($aps_config['pagebuilder'])){
            echo '<h3>The Plugin aps-spagebuilder is required. Please install it.</h3>';
            return false;
        }


        $pagebuilder = $aps_config['pagebuilder'];
        if ($self_hosted == 'yes') {
            $atts['type'] = 'hosted';
        } else {
            $atts['type'] = 'embed_iframe';
            $atts['iframe'] = $embed;
        }

        //Añadir el ratio
        $ratio = floatval($height) / floatval($width) ;
        $atts['poster_ratio'] = $ratio;


        //El shortcode
        $html_sc = $pagebuilder->shortcode_classes['aps_audio']->shortcode_handler($atts);
        //echo '<pre>'; print_r( $atts ); echo '</pre>';

        //Para mantenerla posicion mientras carga la imagen
        //echo '<h1>width='.$width.' height='.$height.'</h1>';

        //echo '<div style="position:relative;width:100%;overflow:hidden;padding-top:'.(100.0*$ratio).'%;">';
        //echo '<div style="position:absolute;top:0px;width:100%;">';
        echo $html_sc;
        //echo '</div>';
        //echo '</div>';




        /* Con aps-shortcodes
        if ($self_hosted=='no' && isset($embed) && strlen($embed)>10)
        {
          if (function_exists('aps_shortcode_audio_embed')){
                echo aps_shortcode_audio_embed($atts, $embed);
            } else {
                echo '<h3>The Plugin aps-shortcodes is required. Please install it.</h3>';
            }
        }
        else
        {
          if (function_exists('aps_shortcode_audio_hosted')){
                echo aps_shortcode_audio_hosted($atts,'');
            } else {
                echo '<h3>The Plugin aps-shortcodes is required. Please install it.</h3>';
            }
        }*/
    }
endif;


//Post format QUOTE
// =============================================================================

if ( ! function_exists( 'aps_content_quote' ) ) :
    function aps_content_quote( $width=false, $height=false ) {

        $the_id = get_the_ID();
        $cite  = get_post_meta( $the_id, 'pf_quote_cite', true);
        $quote = get_post_meta( $the_id, 'pf_quote', true);
        $align = get_post_meta( $the_id, 'pf_quote_align', true);

        $class = '';
        $style = '';

        //Mantener una proporcion determinada
        if ( $width != false && $height != false )
        {
            $class = 'with-ratio';
            $ratio = ( 100.00 * floatval($height) / floatval($width) );
            $style = 'padding-bottom:'.$ratio.'%;';
        }

        //El fondo puede ser la featured image, el pattern, o un color
        //la altura se corresponde con lo que ocupe el texto
        $back_op = get_post_meta( $the_id, 'pf_quote_background', true);

        //Usar featured image como fondo
        if ($back_op == 'featured')
        {
            if ( has_post_thumbnail() )
            {
                //Imagen normal
                if ($width===false && $height===false ) {

                    $size_image = aps_dame_image_size_segun_single_blog();
                    $poster = wp_get_attachment_image_src( get_post_thumbnail_id(), $size_image, false, false);
                    $image_url = $poster[0];
                }
                //Imagen resized
                else {

                    $image_resized = aps_get_image_resized_for_post_id( $the_id, $width, $height, 0, 'no');
                    if (isset($image_resized['resized'])) {
                        $image_url = $image_resized['resized']['url'];
                    } else {
                        $image_url = aps_placeholder_image($width,$height,'error resized image')['url'];
                    }
                }

            }
            else
            {
                if ($width===false && $height===false ) {
                    $image_url = aps_placeholder_image($width,$height,'no featured image')['url'];
                } else {
                    $image_url = aps_placeholder_image(600,400,'no featured image')['url'];
                }
            }

            //Para pruebas
            if (defined('DEELAY_ME')) {
                $image_url = 'http://deelay.me/'.DEELAY_ME.$image_url;
            }

            $style .= "background-image: url('".$image_url."');";
            $class .= ' back_pattern no-repeat';
        }

        //Usar pattern como fondo
        else if ($back_op == 'pattern')
        {
            $class .= ' back_pattern';
        }

        //Usar color de fondo
        else if ($back_op == 'backcolor' )
        {
            $back_color = get_post_meta( $the_id, 'pf_quote_back_color', true);
            $style .= "background-color:".$back_color.";";
        }

        //Usar color de texto
        $use_text_color = get_post_meta( $the_id, 'pf_quote_override_color', true);

        if ($use_text_color == 'yes')
        {
            $text_color = get_post_meta( $the_id, 'pf_quote_color', true);
            $style .= 'color:'.$text_color.';';
        }

        //Echar
        $html = '';
        if (!is_singular()) { $html .= '<a href="'.esc_url(get_permalink()).'">'; }
        $html .= '<div class="aps-quote '.$class.'" style="'.$style.'">';
        $html .= '<blockquote style="text-align:'.$align.';"><span class="fa fa-quote-left"></span> '.$quote.' <span class="fa fa-quote-right"></span><br><cite>'.$cite.'</cite></blockquote>';
        $html .= '</div>';
        if (!is_singular()) { $html .= '</a>'; }
        echo $html;


        //Forma antigua
        /*$atts = array(
        'cite' => get_post_meta( get_the_ID(), 'pf_quote_cite', true),
        'align'	=> get_post_meta( get_the_ID(), 'pf_quote_align', true)
        );
        $content = get_post_meta( get_the_ID(), 'pf_quote', true);
        */


        //Con aps-pagebuilder
        //Compruebo si tengo el pagebuilder
        /*global $aps_config;
        if (isset($aps_config['pagebuilder']))
        {
        $pagebuilder = $aps_config['pagebuilder'];

        echo $pagebuilder->shortcode_classes['aps_blockquote']->shortcode_handler($atts, $content);

        } else {
        echo '<h3>The Plugin aps-spagebuilder is required. Please install it.</h3>';
        }
        */

        // Con aps-shortcodes
        /*
        if (function_exists('aps_shortcode_blockquote')){
        echo aps_shortcode_blockquote($atts, $content);
        } else {
        echo '<h3>The Plugin aps-shortcodes is required. Please install it.</h3>';
        }*/

    }
endif;




//Post format LINK
// =============================================================================

if ( ! function_exists( 'aps_content_link' ) ) :
    function aps_content_link( $width=false, $height=false )
    {
        $the_id = get_the_ID();
        $link = get_post_meta( $the_id, 'pf_link', true);
        $link_text = get_post_meta( $the_id, 'pf_link_text', true);
        if (strlen($link_text)<5) $link_text = $link;

        $new_window = get_post_meta( $the_id, 'pf_link_open', true);
        $new_window = $new_window == 'no' ? '_self' : '_blank';

        $class = '';
        $style = '';
        $style_anchor = '';

        //Mantener una proporcion determinada
        if ( $width != false && $height != false )
        {
          $class = 'with-ratio';
          $ratio = ( 100.00 * floatval($height) / floatval($width) );
          $style = 'padding-bottom:'.$ratio.'%;';
        }

        //El fondo puede ser la featured image, el pattern, o un color
        //la altura se corresponde con lo que ocupe el texto
        $back_op = get_post_meta( $the_id, 'pf_link_background', true);

        //Usar featured image como fondo
        if ($back_op == 'featured')
        {
            if ( has_post_thumbnail() )
            {
                //Imagen normal
                if ($width===false && $height===false ) {

                    $size_image = aps_dame_image_size_segun_single_blog();
                    $poster = wp_get_attachment_image_src( get_post_thumbnail_id(), $size_image, false, false);
                    $image_url = $poster[0];
                }
                //Imagen resized
                else {

                    $image_resized = aps_get_image_resized_for_post_id( $the_id, $width, $height, 0, 'no');
                    if (isset($image_resized['resized'])) {
                        $image_url = $image_resized['resized']['url'];
                    } else {
                        $image_url = aps_placeholder_image($width,$height,'error resized image')['url'];
                    }
                }

            }
            else
            {
                if ($width===false && $height===false ) {
                    $image_url = aps_placeholder_image($width,$height,'no featured image')['url'];
                } else {
                    $image_url = aps_placeholder_image(600,400,'no featured image')['url'];
                }
            }

            //Para pruebas
            if (defined('DEELAY_ME')) {
                $image_url = 'http://deelay.me/'.DEELAY_ME.$image_url;
            }

            $style .= "background-image: url('".$image_url."');";
            $class .= ' back_pattern no-repeat';
        }

        //Usar pattern como fondo
        else if ($back_op == 'pattern')
        {
            $class .= ' back_pattern';
        }

        //Usar color de fondo
        else if ($back_op == 'backcolor' )
        {
            $back_color = get_post_meta( $the_id, 'pf_link_back_color', true);
            $style .= "background-color:".$back_color.";";
        }

        //Usar color de texto
        $use_text_color = get_post_meta( $the_id, 'pf_link_override_color', true);

        if ($use_text_color == 'yes')
        {
            $text_color = get_post_meta( $the_id, 'pf_link_color', true);
            $style .= 'color:'.$text_color.';';
            $style_anchor .= 'color:'.$text_color.';';
        }


        //Echar
        $html = '<div class="aps-link '.$class.'" style="'.$style.'">';
        $html .= '<div class="aps-link-text"><span class="fa fa-chain"></span>&nbsp;&nbsp;<a style="'.$style_anchor.'" target="'.$new_window.'" href="'.$link.'">'.$link_text.'</a></div>';
        $html .= '</div>';
        echo $html;
    }
endif;


//About the author
// =============================================================================

if ( ! function_exists( 'aps_content_about_the_author' ) ) :
  function aps_content_about_the_author() {
  
  	?>
	<div class="about-author post-box">
		<div class="title"><h3><?php echo __('About the Author:', LANGUAGE_THEME); ?> <?php the_author_posts_link(); ?></h3><div class="title-sep-container"><div class="title-sep"></div></div></div>
		<div class="about-author-container clearfix">
			<div class="avatar">
				<?php echo get_avatar(get_the_author_meta('email'), '72'); ?>
			</div>
			<div class="description">
				<?php the_author_meta("description"); ?>
			</div>
		</div>
	</div>
	<?php	
	  
  }
endif;



//Related posts author
// =============================================================================

if ( ! function_exists( 'aps_content_related' ) ) :
  function aps_content_related() {


        global $post;
        $type    = aps_get_option('single_related_type');
        $size   = aps_get_option('single_related_size');
        $number  = aps_get_option('single_related_number');
        $orderby = aps_get_option('single_related_orderby');
        $order   = aps_get_option('single_related_order');

        $filter = aps_get_option('single_related_filter');
        $sc_cats = '';
        $sc_tags = '';

        if ( $filter == 'categories' || $filter == 'categories_and_tags' ) {

            $cats = get_the_category();
            if ($cats){
                $sc_cats = 'categories_post="';
                foreach ($cats as $cat) {
                    $sc_cats .= 'category::'.$cat->slug.',';
                }
                $sc_cats =  substr($sc_cats,0,-1);
                $sc_cats .= '"';
            }

        }

        if ( $filter == 'tags' || $filter == 'categories_and_tags' ) {

            $tags = get_the_tags();
            if ($tags){
                $sc_tags = 'tags_post="';
                foreach ($tags as $tag) {
                    $sc_tags .= 'post_tag::'.$tag->slug.',';
                }
                $sc_tags =  substr($sc_tags,0,-1);
                $sc_tags .= '"';
            }

        }

        $sc = "[aps_posts_carousel type='$type' size='$size' source='post' $sc_cats $sc_tags limit='$number' orderby='$orderby' order='$order']";
        //echo '<pre>'; print_r( $sc ); echo '</pre>';
        $html_shortcode = do_shortcode($sc);

        //Solo se muestra si tiene relacionados
        if ($html_shortcode)
        {
            ?>
            <div class="related-posts post-box">
                <div class="title">
                    <h3><?php echo __('Related posts:', LANGUAGE_THEME); ?></h3>
                </div>
                <?php  echo $html_shortcode; ?>
            </div>
        <?php
        }

  }
endif;

//Single post comments
// =============================================================================

if ( ! function_exists( 'aps_content_single_comments' ) ) :
  function aps_content_single_comments() {
						
	?>
	<div id="comments" class="post-comments post-box">
		<div class="title"><h3><?php echo __('Comments:', LANGUAGE_THEME); ?></h3></div>
		<div class="post-comments-container">
		<?php wp_reset_query(); ?>
		<?php comments_template( ); ?>
		</div>
	</div>
	<?php
  }
endif;


//Single post comments
// =============================================================================


if ( ! function_exists( 'aps_comment' ) ) :
  function aps_comment($comment, $args, $depth) {
	  
	  
	  ?>
	  <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	  
	  	<div class="the-comment">
		  	
		  	<div class="avatar">
				<?php echo get_avatar($comment, 54); ?>
			</div>
			
			<div class="comment-box">
			
				<div class="comment-author meta">
					<strong><?php echo get_comment_author_link() ?></strong>
					<?php printf(__('%1$s at %2$s', LANGUAGE_THEME),
						get_comment_date(),  
						get_comment_time()) ?>
						
						<?php edit_comment_link(__(' - Edit', LANGUAGE_THEME),'  ','') ?>
						<?php comment_reply_link(array_merge( $args, 
							array('reply_text' 	=> __(' - Reply', LANGUAGE_THEME),
								  'add_below' 	=> 'comment',
								  'depth' 		=> $depth,
								  'max_depth' 	=> $args['max_depth']))) ?>
				</div>
				
				<div class="comment-text">
					<?php if ($comment->comment_approved == '0') : ?>
					<em><?php echo __('Your comment is awaiting moderation.', LANGUAGE_THEME) ?></em>
					<br>
					<?php endif; ?>
					<?php comment_text() ?>
				</div>
			
			</div>
		  
		</div>
		
	  
	  <?php
  }
endif;



//Get all metadata for post
// =============================================================================


if ( ! function_exists( 'aps_get_all_metadata' ) ) :
    function aps_get_all_metadata($post_id)
    {
        static $result = [];

        if (isset($result[$post_id])) { return $result[$post_id]; }

        global $wpdb;
        $data = array();
        $wpdb->query("
                        SELECT meta_key, meta_value
                        FROM $wpdb->postmeta
                        WHERE post_id = $post_id
                    ");
        foreach($wpdb->last_result as $k => $v){
            $data[$v->meta_key] =   $v->meta_value;
        };
        $result[$post_id] = $data;
        return $result[$post_id];
    }
endif;





//Pages the_extra_content()
// =============================================================================

if ( ! function_exists( 'aps_content_extra_content' ) ) :
    function aps_content_extra_content()
    {
        $post_id = get_the_ID();
        //$meta = aps_get_all_metadata( get_the_ID() );
        //echo '<pre>'; print_r( $meta ); echo '</pre>';
        //if ( isset($meta['add_extra_content'])  ) { return ''; }
        if ( get_post_meta( $post_id, 'add_extra_content', true) == 'no' ) {
            //echo '<div class="post_content">';
            //the_content();
            //echo '</div>';
            return ;
        }

        //Formo el shortcode con el extra content
        $sc = "[aps_gallery_template ";
        $sc .= ' post_type="'.get_post_meta($post_id, 'content_post_type', true).'"';
        $campos = 'select_by,query_relation,post_ids,categories_post,tags_post,categories_aps_project,tags_aps_project,use_gallery_of_post,page_number,paging_type,orderby,order,posts_per_page,type,masonry_width,masonry_margin,grid_cols,grid_width,grid_padding,grid_ratio,jgrid_height,jgrid_padding,gallery_width,gallery_height,gallery_fullscreen,gallery_mode,gallery_text_desc,list_width,list_ratio,fullwidth_width,fullwidth_height,with_border,display_link_post,display_link_lightbox,display_link_external,display_curtain_text';
        $campos = explode(',',$campos);
        foreach($campos as $campo){
            $data = get_post_meta($post_id, $campo, true);
            //Si es un campo vacio no quiero que aparezca en el shortcode porque me da problemas
            //por ejemplo page_number=  vacio me da problemas
            if ($data){
                if ( is_array($data) ) {
                    $data = join(',',$data);
                }
                $sc .= ' '.$campo.'="'.$data.'"';
            }
        }
        $sc .= ']';

        //echo $sc;

        echo '<div class="post_content_extra">';
        //echo '<pre>'; print_r( $sc ); echo '</pre>';
        //the_content();
        echo do_shortcode($sc);
        /*if (get_post_meta($post_id,'display_position',true)=='above') {
            echo do_shortcode($sc);
            the_content();
        } else {
            the_content();
            echo do_shortcode($sc);
        }*/
        echo '</div>';
    }
endif;



//Template-blog+content
// =============================================================================

if ( ! function_exists( 'aps_content_template_blogpluscontent' ) ) :
    function aps_content_template_blogpluscontent( $content = 'above' ) //below
    {
        if ( is_home() && !is_front_page())
        {
            $page_for_posts = get_option('page_for_posts');

            $page_content = '';
            if ($content=='above') {
                $page_content = get_post_field('post_content', $page_for_posts);
            } else if ($content=='below') {
                $page_content = get_post_meta($page_for_posts,'content_below',true);
            }
            echo apply_filters('the_content',  do_shortcode($page_content) );
        }
    }
endif;