<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


global $aps_config;

function aps_array_map_styles($num)
{
	$list = array();
	for($i=1; $i<=$num; $i++)
		$list[$i] = 'Map '.$i;
	return $list;
}

$aps_config['option_pages'] = array(
	array(
		'page_title'	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_title_in' => __('Install', LANGUAGE_THEME),
		'title'			=> __('Install', LANGUAGE_THEME),
		'id'			=> 'aps_op_general',
		//'icon'			=> get_template_directory_uri().'/includes/stylesheets/images/icons/icon-layout.png',
        'icon'          => 'dashicons-admin-generic',
		'position'		=> 64
	),
    //SEO
    array(
        'page_title'	=> 'seo',
        'menu_title'	=> 'SEO',
        'title'			=> __('SEO', LANGUAGE_THEME),
        'id'			=> 'aps_op_seo',
    ),
    //Responsive
    array(
        'page_title'	=> 'Mobile',
        'menu_title'	=> 'Mobile',
        'title'			=> __('Mobile options', LANGUAGE_THEME),
        'id'			=> 'aps_op_mobile',
    ),
	//Brand
	array(
		'page_title'	=> 'Brand',
		'menu_title'	=> 'Brand',
		'title'			=> __('Brand options', LANGUAGE_THEME),
		'id'			=> 'aps_op_brand',
	),
	//Pagina especial de colores del tema
	array(
		'page_title'	=> 'Styling',
		'menu_title'	=> 'Styling',
		'title'			=> __('Style theme', LANGUAGE_THEME),
		'id'			=> 'aps_op_theme_style',
		'callback'		=> 'aps_mostrar_opcion_theme_colors'
	),
	//Brand
	array(
		'page_title'	=> 'Extra code',
		'menu_title'	=> 'Extra code',
		'title'			=> __('Extra code', LANGUAGE_THEME),
		'id'			=> 'aps_op_extra',
	),
	/*array(
		'page_title'	=> 'Styles',
		'menu_title'	=> 'Styles',
		'title'			=> __('STYLES', LANGUAGE_THEME),
		'id'			=> 'aps_op_style'
	),*/
	array(
		'page_title'	=> 'Style Maps',
		'menu_title'	=> 'Style Maps',
		'title'			=> __('STYLE MAPS', LANGUAGE_THEME),
		'id'			=> 'aps_op_maps'
	),
	array(
		'page_title'	=> 'Contact data',
		'menu_title'	=> 'Contact data',
		'title'			=> __('CONTACT DATA', LANGUAGE_THEME),
		'id'			=> 'aps_op_contact'
	),
	array(
		'page_title'	=> 'Social menu',
		'menu_title'	=> 'Social menu',
		'title'			=> __('Social links', LANGUAGE_THEME),
		'id'			=> 'aps_op_social'
	),
	array(
		'page_title'	=> 'Share post',
		'menu_title'	=> 'Share post',
		'title'			=> __('Share post', LANGUAGE_THEME),
		'id'			=> 'aps_op_share'
	),
	array(
		'page_title'	=> 'Blog',
		'menu_title'	=> 'Blog',
		'menu_title'	=> 'Blog',
		'title'			=> __('Blog options', LANGUAGE_THEME),
		'id'			=> 'aps_op_blog'
	),
    array(
        'page_title'	=> 'Blog archive',
        'menu_title'	=> 'Blog archive',
        'title'			=> __('Blog archive', LANGUAGE_THEME),
        'id'			=> 'aps_op_blog_archive'
    ),
    array(
        'page_title'	=> 'Blog post',
        'menu_title'	=> 'Blog post',
        'title'			=> __('Blog single post', LANGUAGE_THEME),
        'id'			=> 'aps_op_post'
    ),
    array(
        'page_title'	=> 'Pages',
        'menu_title'	=> 'Pages',
        'title'			=> __('Pages', LANGUAGE_THEME),
        'id'			=> 'aps_op_pages'
    ),
    array(
        'page_title'	=> 'Projects list',
        'menu_title'	=> 'Projects list',
        'title'			=> __('Projects list', LANGUAGE_THEME),
        'id'			=> 'aps_op_project_list'
    ),
    array(
        'page_title'	=> 'Projects archive',
        'menu_title'	=> 'Projects archive',
        'title'			=> __('Projects archive', LANGUAGE_THEME),
        'id'			=> 'aps_op_project_archive'
    ),
    array(
        'page_title'	=> 'Project post',
        'menu_title'	=> 'Project post',
        'title'			=> __('Project post', LANGUAGE_THEME),
        'id'			=> 'aps_op_project_post'
    ),
    array(
        'page_title'	=> 'Backup options',
        'menu_title'	=> 'Backup options',
        'title'			=> __('Backup options', LANGUAGE_THEME),
        'id'			=> 'aps_op_backup'
    ),
);

do_action('theme_option_pages');


function urlst($name,$path)
{
    return '<a href="'.admin_url('admin.php?page='.$path).'">'.$name.'</a>';
}

// SECCIONES
//Puede ser una o varias secciones por pagina
$aps_config['option_sections'] = array(
	array(
		'id'		=> 'sec_opciones_generales',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'      => __('', LANGUAGE_THEME),
		//'desc'		=> __('You can define default layout for:<ul><li>'.urlst('Blog','aps_op_blog').'<li>'.urlst('Blog archive','aps_op_blog_archive').'<li>'.urlst('Single post','aps_op_post').'<li>'.urlst('Pages','aps_op_pages').'<li>'.urlst('Projects list','aps_op_project_list').'<li>'.urlst('Projects archive','aps_op_project_archive').'<li>'.urlst('Project post','aps_op_project_post').'</ul><br>The content of the layout can be defined in <a href="'.admin_url().'edit.php?post_type=aps_layout">Layouts</a>', LANGUAGE_THEME),
		'page'		=> 'aps_op_general'
	),
    array(
        'id'		=> 'sec_opciones_seo',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'page'		=> 'aps_op_seo'
    ),
	array(
		'id'		=> 'sec_opciones_mobile',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'page'		=> 'aps_op_mobile'
	),
	array(
		'id'		=> 'sec_opciones_brand',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'page'		=> 'aps_op_brand'
	),
	array(
		'id'		=> 'sec_opciones_extra',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'page'		=> 'aps_op_extra'
	),
	array(
		'id'		=> 'sec_opciones_maps',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'page'		=> 'aps_op_maps'
	),
	array(
		'id'		=> 'sec_opciones_contact',
		'title'		=> __('Section contact data', LANGUAGE_THEME),
		'desc'		=> __('Description section contact data', LANGUAGE_THEME),
		'page'		=> 'aps_op_contact'
	),
	array(
		'id'		=> 'sec_opciones_social',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('Place the links. To remove a link leave it blank.', LANGUAGE_THEME),
		'page'		=> 'aps_op_social'
	),
	array(
		'id'		=> 'sec_opciones_share',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('Mark the share buttons you want in blog posts', LANGUAGE_THEME),
		'page'		=> 'aps_op_share'
	),
	array(
		'id'		=> 'sec_opciones_blog',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('Default layout for the blog page', LANGUAGE_THEME),
		'page'		=> 'aps_op_blog'
	),
    array(
        'id'		=> 'sec_opciones_blog_archive',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Default layout for the blog archive page (category, tag, author, date)', LANGUAGE_THEME),
        'page'		=> 'aps_op_blog_archive'
    ),
    array(
        'id'		=> 'sec_opciones_post',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Options for the single post page', LANGUAGE_THEME),
        'page'		=> 'aps_op_post'
    ),
    array(
        'id'		=> 'sec_opciones_pages',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Options for pages', LANGUAGE_THEME),
        'page'		=> 'aps_op_pages'
    ),
    array(
        'id'		=> 'sec_opciones_project_list',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Options for the <a target="_black" href="'.site_url('/project').'">Projects List Page</a><br>( Listing of latest projects like in a blog)', LANGUAGE_THEME),
        'page'		=> 'aps_op_project_list'
    ),
    array(
        'id'		=> 'sec_opciones_project_archive',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Options for the Projects-Taxonomy Archive Page <br>( Archive of projects by some Category, Skill or Tag)', LANGUAGE_THEME),
        'page'		=> 'aps_op_project_archive'
    ),
    array(
        'id'		=> 'sec_opciones_project_post',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Options for single project page', LANGUAGE_THEME),
        'page'		=> 'aps_op_project_post'
    ),

    array(
        'id'		=> 'sec_opciones_backup',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('Backup options', LANGUAGE_THEME),
        'page'		=> 'aps_op_backup'
    ),

	//Necesito tener la seccion definida para que guarde bien los datos
	array(
		'id'		=> 'sec_opciones_theme_style',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'page'		=> 'aps_op_theme_style',
	),

);

do_action('theme_option_sections');



// FIELDS
//Vienen asociados a la seccion y a la pagina
//la pagina es la misma que la de la seccion correspondiente
$aps_config['option_fields'] = array(

    //SECCION INFO
    array(
        //'section'	=> 'sec_opciones_generales',
        'section'	=> 'sec_opciones_generales',
        'id'		=> 'button_import',
        'title'		=> __('WEB EXAMPLE', LANGUAGE_THEME),
        'desc'		=> __('This will replace your current theme options, widgets and sliders.
        <br>It will import posts, pages, projects, layouts and images.
        <br>It is recommended to do it with a fresh install only once.
        <br>Otherwise it ill duplicate content and linking between content could be broken.

        ', LANGUAGE_THEME),
        'type' 		=> 'button_type',
        'button_link' => admin_url('admin.php?page=aps_op_general&import_data_projects=true'),
        'button_name' => __('IMPORT EXAMPLE CONTENT', LANGUAGE_THEME),
        'button_message' => __('WARNING: Are you sure to import content? Be patient, it can take a few minutes to complete... it has to import all the images from the example. Update Settings->Permalinks after example has been imported.',LANGUAGE_THEME),
        'button_preloader' => 'yes'
    ),

	//SELECT-LAYOUT
    array(
        //'section'	=> 'sec_opciones_generales',
        'section'	=> 'sec_opciones_post',
        'id'		=> 'layout_default_post',
        'title'		=> __('Default theme-layout for SINGLE-PAGE-POST', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        //'section'	=> 'sec_opciones_generales',
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'layout_default_blog',
        'title'		=> __('Default theme-layout for BLOG-PAGE', LANGUAGE_THEME),
        'desc'		=> __('Valid only with this option en Reading <a href="'.admin_url('options-reading.php').'">Settings</a><br>Front page displays -> Your latest posts', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        //'section'	=> 'sec_opciones_generales',
        'section'   => 'sec_opciones_blog_archive',
        'id'		=> 'layout_default_archive',
        'title'		=> __('Default theme-layout for ARCHIVE-PAGE (category,tag,author,date)', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_pages',
        'id'		=> 'layout_default_page',
        'title'		=> __('Default theme-layout for PAGES', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_pages',
        'id'		=> 'page_show_comments',
        'title'		=> __('Show comments in pages when active ?', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no'
    ),
    array(
        'section'	=> 'sec_opciones_pages',
        'id'		=> 'layout_default_page_404',
        'title'		=> __('Layout for 404 PAGE', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_pages',
        'id'		=> 'menu_page_404',
        'title'		=> __('Menu list for 404-Error page', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_menu'
    ),
    array(
        'section'	=> 'sec_opciones_pages',
        'id'		=> 'layout_default_page_search',
        'title'		=> __('Layout forSEARCH PAGE', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_project_post',
        'id'		=> 'layout_default_aps_project',
        'title'		=> __('Default theme-layout for SINGLE-PROJECT-PAGE', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'layout_default_aps_project_list',
        'title'		=> __('Default theme-layout for LIST-PROJECT-PAGE', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'layout_default_aps_project_archive',
        'title'		=> __('Default theme-layout for TAXONOMY-PROJECT-PAGE', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select_layout'
    ),

    //FIXED HEADER
    array(
        'section'	=> 'sec_opciones_mobile',
        'id'			=> 'fixed_header',
        'title'		=> __('FIXED HEADER', LANGUAGE_THEME),
        'desc'		=> __('The header will be fixed at the top of the page when scrolling window.', LANGUAGE_THEME),
        'type' 		=> 'yes_no'
    ),


    array(
        'section'	=> 'sec_opciones_mobile',
        'id'			=> 'menu_collapse_width',
        'title'		=> __('MENU BUTTON FOR WIDTH', LANGUAGE_THEME),
        'desc'		=> __('The menu can change to one button when the screen width is small to see all the menu.<br>You can define the width when this happens.', LANGUAGE_THEME),
        'desc_image' => APS_THEME_URI.'/includes/stylesheets/images/layouts/responsive_menu.png',
        'type' 		=> 'select',
        'value'     => '768',
        'options'   =>   array(
            '320px'   => '320px (vertical iPhone)',
            '480px'     => '480px (vertical Galaxy II)',
            '600px' 	=> '600px (vertical Galaxy Tab 7 & Kindle)',
            '640px' 	=> '640px (vertical iPhone 4)',
            '720px' 	=> '720px (vertical Galaxy III)',
            '768px'   => '768px (vertical iPad)',
            '800px'   => '800px (vertical Galaxy Tab 10)',
            '100px'   => 'Never'
        ),
    ),

    array(
        'section'	=> 'sec_opciones_mobile',
        'id'			=> 'sidebars_collapse_width',
        'title'		=> __('SIDEBARS RESPONSIVE FOR WIDTH', LANGUAGE_THEME),
        'desc'		=> __('The sidebars can change the position when the screen width is small.<br>You can define the width when this happens.', LANGUAGE_THEME),
        'desc_image' => APS_THEME_URI.'/includes/stylesheets/images/layouts/responsive_sidebars.png',
        'type' 		=> 'select',
        'value'     => '768',
        'options'   =>   array(
            '320px'   => '320px (vertical iPhone)',
            '480px'     => '480px (vertical Galaxy II)',
            '600px' 	=> '600px (vertical Galaxy Tab 7 & Kindle)',
            '640px' 	=> '640px (vertical iPhone 4)',
            '720px' 	=> '720px (vertical Galaxy III)',
            '768px'   => '768px (vertical iPad)',
            '808px'   => '800px (vertical Galaxy Tab 10)',
            '100px'   => 'Never'
        ),
    ),

	array(
		'section'	=> 'sec_opciones_mobile',
		'id'		=> 'display_responsive',
		'title'		=> __('TYPE OF RESPONSIVE SIDEBARS', LANGUAGE_THEME),
		'desc'		=> __('Sidebars can change the position to up and down or to lateral sliding.', LANGUAGE_THEME),
		'type' 		=> 'radio_image',
		'args'		=>  array(''),
		'options'	=>  array(
						'responsive-sidebars-both' 		=> 'Up and Down',
						'responsive-sidebars-left' 	 	=> 'Only Left',
						'responsive-sidebars-right' 	=> 'Only Right',
						'responsive-sidebars-none' 	 	=> 'Hide both',
						//'responsive-sidebars-nostack'   => 'no stack',
                        'responsive-sidebars-floated'   => 'Lateral sliding'
						),
		'images'	=> array(
							'layouts/sidebars_up_down.png',
							'layouts/sidebars_up.png',
							'layouts/sidebars_down.png',
							'layouts/sidebars_none.png',
							//'layouts/layout-responsive-0.png',
                            'layouts/sidebars_lateral.png',
						),
		'class'	=> 'aps-radioimage-small',
        'required' => array('sidebars_collapse_width','321px,481px,601px,641px,721px,769px,801px'),
	),

	
	
	
	//TRACKING CODE
	array(
		'section'	=> 'sec_opciones_extra',
		'id'		=> 'tracking_code',
		'title'		=> __('Tracking code (e.g. Google analytics)', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'textarea'
	),
	//EXTRA STYLE
	array(
		'section'	=> 'sec_opciones_extra',
		'id'		=> 'extra_style',
		'title'		=> __('Add here your extra style CSS', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'textarea'
	),
	
	
	//LOGOS
	array(
		'section'	=> 'sec_opciones_brand',
		'id'		=> 'logo_top',
		'title'		=> __('LOGO', LANGUAGE_THEME),
		//'desc'		=> __('height 90px, variable width.<br><small>If layout has no header it will show in left sidebar</small>', LANGUAGE_THEME),
		'desc'		=> __('height 90px, width variable', LANGUAGE_THEME),
		'type' 		=> 'image'
	),

	array(
		'section'	=> 'sec_opciones_brand',
		'id'		=> 'favicon',
		'title'		=> __('FAVICON 140x140 en PNG', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'image'
	),

	array(
		'section'	=> 'sec_opciones_brand',
		'id'			=> 'site',
		'title'		=> __('Site title', LANGUAGE_THEME),
		'desc'		=> __('Site title and Tagline can be modified in <a href="'.site_url().'/wp-admin/options-general.php">Settings</a>', LANGUAGE_THEME),
		'type' 		=> 'desc'
	),


	
	//STYLE MAPS
	array(
		'section'	=> 'sec_opciones_maps',
		'id'		=> 'map_style',
		'title'		=> __('Maps style', LANGUAGE_THEME),
		'desc'		=> __('This style will be used for all maps in the web', LANGUAGE_THEME),
		'type' 		=> 'map_style',
		'options_'	=> array(
			'1'=>'Mapa 1',
			'2'=>'Mapa 2',
			'3'=>'Mapa 3',
			'4'=>'Mapa 4'
		),
		//'options' => aps_array_map_styles(25)
        'options' => array(
            'Default Google' => 'Default Google',
            'Pale Dawn' => 'Pale Dawn',
            'Subtle Grayscale' => 'Subtle Grayscale',
            'Blue Water' => 'Blue Water',
            'Midnight Commander' => 'Midnight Commander',
            'Retro' => 'Retro',
            'Shades of Gray' => 'Shades of Gray',
            'Light Monochrome' => 'Ligh Monochrome',
            'Grayscale' => 'Grayscale',
            'Subtle' => 'Subtle',
            'Paper' => 'Paper',
            'Neutral Blue' => 'Neutral Blue',
            'Apple Maps-esque' => 'Apple Maps-esque',
            'Shift Worker' => 'Shift Worker',
            'Pink & Blue' => 'Pink & Blue',
            'Night vision' => 'Night vision',
            'Light Green' => 'Light Green',
            'Hints of Gold' => 'Hints of Gold'
        )
	),
    array(
        'section'	=> 'sec_opciones_maps',
        'id'		=> 'map_icons_color',
        'title'		=> __('Color icons', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
    ),
    array(
        'section'	=> 'sec_opciones_maps',
        'id'		=> 'map_icons_type',
        'title'		=> __('Project icon type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'icon-map-square-70'=>'Square with image',
            'icon-map-circle-70'=>'Circle with image',
            'icon-map-square-40'=>'Square',
            'icon-map-circle-40'=>'Circle',
        ),
    ),

	
	//CONTACT DATA AND COPYRIGHT
	array(
		'section'	=> 'sec_opciones_contact',
		'id'		=> 'address',
		'title'		=> __('Contact data - location', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input'
	),
	array(
		'section'	=> 'sec_opciones_contact',
		'id'		=> 'telephone',
		'title'		=> __('Contact data - telephone', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input'
	),
	array(
		'section'	=> 'sec_opciones_contact',
		'id'		=> 'email',
		'title'		=> __('Contact data - email', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input'
	),
	array(
		'section'	=> 'sec_opciones_contact',
		'id'		=> 'schedule',
		'title'		=> __('Contact data - schedule', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input'
	),
	array(
		'section'	=> 'sec_opciones_contact',
		'id'		=> 'copyright',
		'title'		=> __('Copyright text', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'textarea'
	),
	
	
	
	//SOCIAL LINKS
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_open',
		'title'		=> __('Social Links open in a new window ?', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),

    //Lo sustituyo por una funcion
    /*
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_facebook',
		'title'		=> __('<span class="admin-social-icon fa fa-facebook"></span><span class="admin-social-text">Facebook</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_twitter',
		'title'		=> __('<span class="admin-social-icon fa fa-twitter"></span> </span><span class="admin-social-text">Twitter</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_rss',
		'title'		=> __('<span class="admin-social-icon fa fa-rss"></span> </span><span class="admin-social-text">RSS</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_linkedin',
		'title'		=> __('<span class="admin-social-icon fa fa-linkedin"></span> </span><span class="admin-social-text">Linkedin</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_skype',
		'title'		=> __('<span class="admin-social-icon fa fa-skype"></span> </span><span class="admin-social-text">Skype</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_tumblr',
		'title'		=> __('<span class="admin-social-icon fa fa-tumblr"></span> </span><span class="admin-social-text">Tumblr</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_google',
		'title'		=> __('<span class="admin-social-icon fa fa-google-plus"></span></span><span class="admin-social-text">Google Plus</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_pinterest',
		'title'		=> __('<span class="admin-social-icon fa fa-pinterest"></span> </span><span class="admin-social-text">Pinterest</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_dribbble',
		'title'		=> __('<span class="admin-social-icon fa fa-dribbble"></span> </span><span class="admin-social-text">Dribbble</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_behance',
		'title'		=> __('<span class="admin-social-icon fa fa-behance"></span> </span><span class="admin-social-text">Behance</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_flickr',
		'title'		=> __('<span class="admin-social-icon fa fa-flickr"></span> </span><span class="admin-social-text">Flickr</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_youtube',
		'title'		=> __('<span class="admin-social-icon fa fa-youtube-play"></span></span><span class="admin-social-text">Youtube</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_vimeo',
		'title'		=> __('<span class="admin-social-icon fa fa-vimeo-square"></span></span><span class="admin-social-text">Vimeo</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_instagram',
		'title'		=> __('<span class="admin-social-icon fa fa-instagram"></span></span><span class="admin-social-text">Instagram</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_picasa',
		'title'		=> __('<span class="admin-social-icon entypo-social-picasa"></span></span><span class="admin-social-text">Picasa</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	array(
		'section'	=> 'sec_opciones_social',
		'id'		=> 'social_github',
		'title'		=> __('<span class="admin-social-icon fa fa-github"></span></span><span class="admin-social-text">Github</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input_social'
	),
	//behance,picasa,digg,blogger,forrst,devianart,myspace,yahoo,reddit
	*/








	
	//SOCIAL SHARE
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_facebook',
		'title'		=> __('<span class="admin-social-icon fa fa-facebook""></span> <span class="admin-social-text">Facebook</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_twitter',
		'title'		=> __('<span class="admin-social-icon fa fa-twitter""></span> <span class="admin-social-text">Twitter</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),

	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_reddit',
		'title'		=> __('<span class="admin-social-icon fa fa-reddit""></span> <span class="admin-social-text">Reddit</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),

	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_linkedin',
		'title'		=> __('<span class="admin-social-icon fa fa-linkedin""></span> <span class="admin-social-text">Linkedin</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_google_plus',
		'title'		=> __('<span class="admin-social-icon fa fa-google-plus""></span> <span class="admin-social-text">Google Plus</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_tumblr',
		'title'		=> __('<span class="admin-social-icon fa fa-tumblr""></span> <span class="admin-social-text">Tumblr</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_pinterest',
		'title'		=> __('<span class="admin-social-icon fa fa-pinterest""></span> <span class="admin-social-text">Pinterest</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_email',
		'title'		=> __('<span class="admin-social-icon fa fa-envelope""></span> <span class="admin-social-text">Email</span>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_share',
		'id'		=> 'share_nofollow',
		'title'		=> __('nofollow', LANGUAGE_THEME),
		'desc'		=> __('Add rel="nofollow" attribute to social links', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	
	
	
	//BLOG OPTIONS
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_title',
		'title'		=> __('Blog Page Title', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'input'
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_layout_type',
		'title'		=> __('Blog Layout', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'select',
		'options' => array(
			'list' 		=> 'LIST',
			'masonry' 	=> 'MASONRY',
			'grid' 		=> 'GRID',
		)
	),

	
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_layout_list',
		'title'		=> __('Blog List type', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'radio_image',
		'options' => array(
			'list_b1' 	=> 'Big images + text full width',
			'list_b2' 	=> 'Big images + text medium width',
			'list_b2a' 	=> 'Big images + text medium width + alternate',
			'list_b3' 	=> 'Big images + text small width',
			'list_b3a' 	=> 'Big images + text small width + alternate',		
			'list_m1' 	=> 'Medium images + text right',
			'list_m2' 	=> 'Medium images + text left',
			'list_m3' 	=> 'Medium images + text alternate',
		),
		'required' => array('blog_layout_type','list'),
		'images'=> array(
			'blog/blog_list_b1.png',
			'blog/blog_list_b2.png',
			'blog/blog_list_b2a.png',
			'blog/blog_list_b3.png',
			'blog/blog_list_b3a.png',
			'blog/blog_list_m1.png',
			'blog/blog_list_m2.png',
			'blog/blog_list_m3.png',
		),
		'class'	=> 'aps-radioimage-blog'
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_layout_masonry',
		'title'		=> __('Blog Masonry Type', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'radio_image',
		'options' => array(
			'masonry_1' => 'Masonry style 1',
			'masonry_2' => 'Masonry style 2',
			'masonry_3' => 'Masonry style 3',
			'masonry_4' => 'Masonry style 4',
			'masonry_5' => 'Masonry style 5',
			'masonry_6' => 'Masonry style 6',
		),
		'images'=> array(
			'blog/blog_masonry_1.png',
			'blog/blog_masonry_2.png',
			'blog/blog_masonry_3.png',
			'blog/blog_masonry_4.png',
			'blog/blog_masonry_5.png',
			'blog/blog_masonry_6.png',
		),
		'required' => array('blog_layout_type','masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_layout_grid',
		'title'		=> __('Blog Grid Type', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'radio_image',
		'options' => array(
			'grid_1' => 'Image square',
			'grid_2' => 'Image horizontal',
            'grid_3' => 'Image vertical'
		),
        'images'=> array(
            'blog/blog_grid_1.png',
            'blog/blog_grid_2.png',
            'blog/blog_grid_3.png'
        ),
		'required' => array('blog_layout_type','grid')
	),
	
	
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'masonry_width',
		'title'		=> __('Masonry Element Width', LANGUAGE_THEME),
		'desc'		=> __('This is the width of the box for the post.', LANGUAGE_THEME),
		'type' 		=> 'select',
		'options'	=> array(
			'width-250' => '250 px',
			'width-275' => '275 px',
			'width-300' => '300 px',
			'width-325' => '325 px',
			'width-350' => '350 px',
			'width-375' => '375 px',
			'width-400' => '400 px'
		),
		'required'	=> array('blog_layout_type','masonry')
	),
	
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'masonry_margin',
		'title'		=> __('Masonry Element margin', LANGUAGE_THEME),
		'desc'		=> __('This is the separation between elements', LANGUAGE_THEME),
		'type' 		=> 'select',
		'options'	=> array(
				'margin-0' => '0 px',
				'margin-1' => '2 px',
				'margin-2' => '4 px',
				'margin-4' => '8 px',
				'margin-8' => '16 px',
				'margin-10' => '20 px',
				'margin-15' => '30 px',
				'margin-20' => '40 px',
				'margin-25'	=> '50 px',
				'margin-30' => '60 px'
			),
		'required'	=> array('blog_layout_type','masonry')
	),

    //grid options
    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'grid_width',
        'title'		=> __('Number of Columns', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'width-2cols' => '2 Columns',
            'width-3cols' => '3 Columns',
            'width-4cols' => '4 Columns',
            'width-5cols' => '5 Columns',
            'width-6cols' => '6 Columns',
        ),
        'required'	=> array('blog_layout_type','grid')
    ),

    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'grid_margin',
        'title'		=> __('Grid Element margin', LANGUAGE_THEME),
        'desc'		=> __('This is the separation between grid elements', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'margin-0' => '0 px',
            'margin-1' => '2 px',
            'margin-2' => '4 px',
            'margin-4' => '8 px',
            'margin-8' => '16 px',
            'margin-10' => '20 px',
            'margin-15' => '30 px',
            'margin-20' => '40 px',
            'margin-25'	=> '50 px',
            'margin-30' => '60 px'
        ),
        'required'	=> array('blog_layout_type','grid')
    ),

    //BLOG ARCHIVE
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_layout_type',
        'title'		=> __('Blog Layout', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options' => array(
            'list' 		=> 'LIST',
            'masonry' 	=> 'MASONRY',
            'grid' 		=> 'GRID',
        )
    ),

    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_layout_list',
        'title'		=> __('Blog List type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'radio_image',
        'options' => array(
            'list_b1' 	=> 'Big images + text full width',
            'list_b2' 	=> 'Big images + text medium width',
            'list_b2a' 	=> 'Big images + text medium width + alternate',
            'list_b3' 	=> 'Big images + text small width',
            'list_b3a' 	=> 'Big images + text small width + alternate',
            'list_m1' 	=> 'Medium images + text right',
            'list_m2' 	=> 'Medium images + text left',
            'list_m3' 	=> 'Medium images + text alternate',
        ),
        'required' => array('blog_layout_type','list'),
        'images'=> array(
            'blog/blog_list_b1.png',
            'blog/blog_list_b2.png',
            'blog/blog_list_b2a.png',
            'blog/blog_list_b3.png',
            'blog/blog_list_b3a.png',
            'blog/blog_list_m1.png',
            'blog/blog_list_m2.png',
            'blog/blog_list_m3.png',
        ),
        'class'	=> 'aps-radioimage-blog'
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_layout_masonry',
        'title'		=> __('Blog Masonry Type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'radio_image',
        'options' => array(
            'masonry_1' => 'Masonry style 1',
            'masonry_2' => 'Masonry style 2',
            'masonry_3' => 'Masonry style 3',
            'masonry_4' => 'Masonry style 4',
            'masonry_5' => 'Masonry style 5',
            'masonry_6' => 'Masonry style 6',
        ),
        'images'=> array(
            'blog/blog_masonry_1.png',
            'blog/blog_masonry_2.png',
            'blog/blog_masonry_3.png',
            'blog/blog_masonry_4.png',
            'blog/blog_masonry_5.png',
            'blog/blog_masonry_6.png',
        ),
        'required' => array('blog_layout_type','masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_layout_grid',
        'title'		=> __('Blog Grid Type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'radio_image',
        'options' => array(
            'grid_1' => 'Image square',
            'grid_2' => 'Image horizontal',
            'grid_3' => 'Image vertical'
        ),
        'images'=> array(
            'blog/blog_grid_1.png',
            'blog/blog_grid_2.png',
            'blog/blog_grid_3.png'
        ),
        'required' => array('blog_layout_type','grid')
    ),


    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'masonry_width',
        'title'		=> __('Masonry Element Width', LANGUAGE_THEME),
        'desc'		=> __('This is the width of the box for the post.', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'width-250' => '250 px',
            'width-275' => '275 px',
            'width-300' => '300 px',
            'width-325' => '325 px',
            'width-350' => '350 px',
            'width-375' => '375 px',
            'width-400' => '400 px'
        ),
        'required'	=> array('blog_layout_type','masonry')
    ),

    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'masonry_margin',
        'title'		=> __('Masonry Element margin', LANGUAGE_THEME),
        'desc'		=> __('This is the separation between elements', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'margin-0' => '0 px',
            'margin-1' => '2 px',
            'margin-2' => '4 px',
            'margin-4' => '8 px',
            'margin-8' => '16 px',
            'margin-10' => '20 px',
            'margin-15' => '30 px',
            'margin-20' => '40 px',
            'margin-25'	=> '50 px',
            'margin-30' => '60 px'
        ),
        'required'	=> array('blog_layout_type','masonry')
    ),

//grid options
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'grid_width',
        'title'		=> __('Number of Columns', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'width-2cols' => '2 Columns',
            'width-3cols' => '3 Columns',
            'width-4cols' => '4 Columns',
            'width-5cols' => '5 Columns',
            'width-6cols' => '6 Columns',
        ),
        'required'	=> array('blog_layout_type','grid')
    ),

    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'grid_margin',
        'title'		=> __('Grid Element margin', LANGUAGE_THEME),
        'desc'		=> __('This is the separation between grid elements', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array(
            'margin-0' => '0 px',
            'margin-1' => '2 px',
            'margin-2' => '4 px',
            'margin-4' => '8 px',
            'margin-8' => '16 px',
            'margin-10' => '20 px',
            'margin-15' => '30 px',
            'margin-20' => '40 px',
            'margin-25'	=> '50 px',
            'margin-30' => '60 px'
        ),
        'required'	=> array('blog_layout_type','grid')
    ),


	
	// BLOG OPTIONS
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'subsection1',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('You can override here the elements you want to display in each post', LANGUAGE_THEME),
		'type' 		=> 'subsection',
		'title_sub' => 'BLOG DISPLAY OPTIONS',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_image',
		'title'		=> __('Display Featured Image', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
    /*array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'blog_show_curtain',
        'title'		=> __('Display hover on image', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        'required'  => array('blog_layout_type','grid')
    ),*/
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_date',
		'title'		=> __('Display Date', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'blog_date_format',
        'title'		=> __('Date format', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'   => array(
            'dmy' => __('DAY month year', LANGUAGE_THEME),
            'myd' => __('month year DAY', LANGUAGE_THEME),
            'mdy' => __('month DAY year', LANGUAGE_THEME),
            'default' => __('Wordpress Settings->Reading',LANGUAGE_THEME),
        )
    ),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_title',
		'title'		=> __('Display Title', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_meta',
		'title'		=> __('Display Meta', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_author',
		'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Author</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
		'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_cats',
		'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Categories</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
		'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_comments',
		'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Comments number</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
		'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_content',
		'title'		=> __('Display Excerpt content', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_social',
		'title'		=> __('Display Social buttons', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_more',
		'title'		=> __('Display More button', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
	array(
		'section'	=> 'sec_opciones_blog',
		'id'		=> 'blog_show_border',
		'title'		=> __('Display Border Post', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
	),
    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'content_readmore',
        'title'		=> __('Read more link', LANGUAGE_THEME),
        'desc'		=> __('Display a read more link at the end of the excerpt text', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'options'	=> array('true'=>'Yes','false'=>'No'),
        //'required'	=> array('blog_layout_type','grid')
    ),
    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'content_words',
        'title'		=> __('Excerpt number of words', LANGUAGE_THEME),
        'desc'		=> __('Select the number of words for the excerpt text', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array('10'=>'10','20'=>'20','30'=>'30','40'=>'40','55'=>'Normal 55'),
        //'required'	=> array('blog_layout_type','grid')
    ),
    array(
        'section'	=> 'sec_opciones_blog',
        'id'		=> 'blog_show_pagination',
        'title'		=> __('Pagination type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'   => array(
            'numbers' => 'Numbers',
            'load_more' => 'Load more button',
            'scroll' => 'Infinite scroll'
        )
        //'required'  => array('blog_layout_type','list,masonry')
    ),







    // BLOG ARCHIVE OPTIONS
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'subsection1',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('You can override here the elements you want to display in each post.', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'ARCHIVE DISPLAY OPTIONS',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_image',
        'title'		=> __('Display Featured Image', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no'
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_date',
        'title'		=> __('Display Date', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_title',
        'title'		=> __('Display Title', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_meta',
        'title'		=> __('Display Meta', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_author',
        'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Author</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_cats',
        'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Categories</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_comments',
        'title'		=> __('<small>&nbsp;&nbsp;&nbsp;&nbsp;meta: Comments number</small><br><small style="font-size:10px;">&nbsp;&nbsp;&nbsp;&nbsp;(when Display Meta = YES)</small>', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        'required'  => array('blog_show_meta','yes'),
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_content',
        'title'		=> __('Display Excerpt content', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_social',
        'title'		=> __('Display Social buttons', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_more',
        'title'		=> __('Display More button', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_border',
        'title'		=> __('Display Border Post', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'required'  => array('blog_layout_type','list,masonry')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'content_readmore',
        'title'		=> __('Read more link', LANGUAGE_THEME),
        'desc'		=> __('Display a read more link at the end of the excerpt text', LANGUAGE_THEME),
        'type' 		=> 'yes_no',
        //'options'	=> array('true'=>'Yes','false'=>'No'),
        //'required'	=> array('blog_layout_type','grid')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'content_words',
        'title'		=> __('Grid text words', LANGUAGE_THEME),
        'desc'		=> __('Select the number of words for the excerpt text', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'	=> array('10'=>'10','20'=>'20','30'=>'30','40'=>'40','55'=>'Normal 55'),
        //'required'	=> array('blog_layout_type','grid')
    ),
    array(
        'section'	=> 'sec_opciones_blog_archive',
        'id'		=> 'blog_show_pagination',
        'title'		=> __('Pagination type', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'options'   => array(
            'numbers' => 'Numbers',
            'load_more' => 'Load more button',
            'scroll' => 'Infinite scroll'
        )
        //'required'  => array('blog_layout_type','list,masonry')
    ),



	
	
	// SINGLE OPTIONS
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_image',
		'title'		=> __('Display Featured Image', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_date',
		'title'		=> __('Display Date', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_title',
		'title'		=> __('Display Title', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_cats',
		'title'		=> __('Display Categories', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_tags',
		'title'		=> __('Display Tags', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_social',
		'title'		=> __('Display Social buttons', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_border',
		'title'		=> __('Display Border', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),

    //division
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'line0',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'line'
    ),
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'single_show_author',
        'title'		=> __('Display About the author', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'yes_no'
    ),

    //division
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'line1',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'line'
    ),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_next',
		'title'		=> __('Display Previous/Next post', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
    //Division
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'line2',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'line'
    ),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_related',
		'title'		=> __('Display Related posts', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'single_related_filter',
        'title'		=> __('Filter related posts with', LANGUAGE_THEME),
        'desc'		=> __('(posts that match some of the categories / tags selected in the post)', LANGUAGE_THEME),
        'class'		=> '',
        'type' 		=> 'select',
        'options'	=> array(
            'categories'            => 'Same categories',
            'tags'                  => 'Same tags',
            'categories_and_tags'   => 'Same categories and tags',
        ),
        'required'	=> array('single_show_related','yes')
    ),
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'single_related_type',
        'title'		=> __('Type of carousel', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'class'		=> '',
        'type' 		=> 'select',
        'options' => array(
            'photo-slider'      => 'Only images',
            'image-slider'      => 'Images with top title',
            'featured-slider'   => 'Images with bottom title and excerpt',
        ),
        'required'	=> array('single_show_related','yes')
    ),
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'single_related_size',
        'title'		=> __('Image size for carousel', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'class'		=> '',
        'type' 		=> 'select',
        'options' => array(
            'large'       => 'Large image',
            'medium'      => 'Medium image',
            'thumbnail'   => 'Small square image',
            'thumbnail-v' => 'Small vertical image'
        ),
        'required'	=> array('single_show_related','yes')
    ),

	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_related_number',
		'title'		=> __('Number of related posts', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		//'class'		=> 'aps-ancho-yes_no',
		'type' 		=> 'select',
		'options'	=> array('3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '-1'=>'All'),
		'required'	=> array('single_show_related','yes')
	),
    array(
        'section'	=> 'sec_opciones_post',
        'id' 	    => 'single_related_orderby',
        'title'		=> __('Order related posts By', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 	    => 'select',
        'value'     => 'date',
        'options'   => array(
            'none'          => 'None',
            'title'         => __('Title', LANGUAGE_THEME),
            'author'        => 'Author',
            'date'          => 'Date',
            'comment_count' => 'Popularity',
            'rand'          => 'Random'
        ),
        'required'	=> array('single_show_related','yes')
    ),
    array(
        'section'	=> 'sec_opciones_post',
        'id' 	    => 'single_related_order',
        'title'	=> __('Order of posts', LANGUAGE_THEME),
        'desc' 	=> __('', LANGUAGE_THEME),
        'type' 	=> 'select',
        'value' => 'ASC',
        'options' => array(
            'ASC' => 'Ascending',
            'DESC' => __('Descending', LANGUAGE_THEME)
        ),
        'required'	=> array('single_show_related','yes')
    ),
    //Division
    array(
        'section'	=> 'sec_opciones_post',
        'id'		=> 'line3',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'line'
    ),
	array(
		'section'	=> 'sec_opciones_post',
		'id'		=> 'single_show_comments',
		'title'		=> __('Display Comments', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'yes_no'
	),
	
	//COLORS OF THE THEME se muestra aparte



    /////////////////////////////////////////////////
    // Para PROJECTS LIST
    /////////////////////////////////////////////////

    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Page title', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'page_title',
        'type'  => 'input',
        'value' => '',
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'subsection1',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'GALLERY',
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'type',
        'title'		=> __('Type of gallery', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'value' => 'masonry_image',
        'options' => array(
            'masonry_image'                     => 'Masonry with image',
            'masonry_image_and_text'            => 'Masonry with image and text',
            'grid_image'                        => 'Grid with image',
            'grid_image_and_text'               => 'Grid with image and text',
            'justified_grid_image'              => 'Justified grid image',
            'list_image_and_text'               => 'List',
            'list_image_and_text_alternate'     => 'List alternate',
            'gallery_image'                     => 'Slider',
            'gallery_image_and_text'            => 'Slider with text',
            'fullwidth_image'                   => 'Fullwidth image'
        ),
    ),

    //Masonry
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Masonry: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'masonry_width',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','masonry_image,masonry_image_and_text')
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Masonry: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 2', LANGUAGE_THEME),
        'id'    => 'masonry_margin',
        'type'  => 'input',
        'value' => 2,
        'required' => array('type','masonry_image,masonry_image_and_text')
    ),

    //Grid
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Grid: number of columns', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'grid_cols',
        'type'  => 'select',
        'value' => '4',
        'required' => array('type','grid_image,grid_image_and_text'),
        'options' => array('2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6')
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Grid: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'grid_width',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Grid: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 2', LANGUAGE_THEME),
        'id'    => 'grid_padding',
        'type'  => 'input',
        'value' => 2,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Grid: ratio image vertical/horizontal', LANGUAGE_THEME),
        'desc'  => __('examples: 1 (square)<br>0.5 (landscape)<br>2 (vertical)', LANGUAGE_THEME),
        'id'    => 'grid_ratio',
        'type'  => 'input',
        'value' => 0.75,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),

    //Justified grid
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Justified grid: column height en pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'jgrid_height',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','justified_grid_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Justified grid: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 1', LANGUAGE_THEME),
        'id'    => 'jgrid_padding',
        'type'  => 'input',
        'value' => 1,
        'required' => array('type','justified_grid_image'),
    ),

    //List
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('List: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 400', LANGUAGE_THEME),
        'id'    => 'list_width',
        'type'  => 'input',
        'value' => 400,
        'required' => array('type', 'list_image_and_text,list_image_and_text_alternate'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('List: ratio image vertical/horizontal', LANGUAGE_THEME),
        'desc'  => __('examples: 1 (square)<br>0.5 (landscape)<br>2 (vertical)', LANGUAGE_THEME),
        'id'    => 'list_ratio',
        'type'  => 'input',
        'value' => 0.75,
        'required' => array('type', 'list_image_and_text,list_image_and_text_alternate'),
    ),

    //Slider
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Slider: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 800', LANGUAGE_THEME),
        'id'    => 'gallery_width',
        'type'  => 'input',
        'value' => 800,
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
    ),
    /*
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Slider full-screen', LANGUAGE_THEME),
        'desc'  => __('This will force the height of the slider to the height of the screen (minus header height)', LANGUAGE_THEME),
        'id'    => 'gallery_fullscreen',
        'type'  => 'yes_no',
        'value' => 'no',
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
    ),
    */
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Slider: image height in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 500', LANGUAGE_THEME),
        'id'    => 'gallery_height',
        'type'  => 'input',
        'value' => 500,
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
    ),


    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Slider: image mode', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'gallery_mode',
        'type'  => 'select',
        'value' => 'fill',
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
        'options' => array(
            'fill' => 'Fill: image will expand to fill the slider container',
            'fit' => 'Fit: image will fit inside the slider container'
        )
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Slider: display post excerpt', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'gallery_text_desc',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'gallery_image_and_text'),
        'options' => array(
            'yes' => 'Yes, display below the title',
            'no' => 'No'
        )
    ),
    //Fullwidth image
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 800', LANGUAGE_THEME),
        'id'    => 'fullwidth_width',
        'type'  => 'input',
        'value' => 800,
        'required' => array('type', 'fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Image height in pixels', LANGUAGE_THEME),
        'desc'  => __('Example: 400 or <br>Leave it blank if you do not want to crop the image.', LANGUAGE_THEME),
        'id'    => 'fullwidth_height',
        'type'  => 'input',
        'value' => 400,
        'required' => array('type', 'fullwidth_image'),
    ),

    //Projects list Paging
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'subsection2',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'PAGING',
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Pagination type', LANGUAGE_THEME),
        //'class' => 'aps-title-grande',
        'desc'  => __('Not valid for Slider gallery<br>The number of posts in page is defined in <a href="'.admin_url().'/options-reading.php">Settings</a> as with the blog', LANGUAGE_THEME),
        'id'    => 'paging_type',
        'type'  => 'select',
        'value' => 'no',
        'required' => array('use_gallery_of_post', 'no'),
        'options' => array(
            //'none'      => 'No pagination. Show all elements',
            'numbers'   => 'Pagination with Numbers',
            'ajax'      => 'Pagination with Load-More button',
            'scroll'      => 'Infinite scroll'
        )
    ),
    /*array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Pagination: posts per page', LANGUAGE_THEME),
        'desc'  => __('Valid only when you are using pagination', LANGUAGE_THEME),
        'id'    => 'posts_per_page',
        'type'  => 'input',
        'required' => array('paging_type', 'numbers,ajax'),
        'value' => 10
    ),
    */

    //Order
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'subsection3',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'ORDER',
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title'	=> __('Order posts By', LANGUAGE_THEME),
        //'class' => 'aps-title-grande',
        'desc' 	=> __('', LANGUAGE_THEME),
        'id' 	=> 'orderby',
        'type' 	=> 'select',
        'value' => 'date',
        'options' => array(
            //'none'          => 'None',
            'title'         => __('Title', LANGUAGE_THEME),
            'author'        => 'Author',
            'date'          => 'Date',
            //'comment_count' => 'Popularity',
            'rand'          => 'Random'
        )
    ),

    array(
        'section'	=> 'sec_opciones_project_list',
        'title'	=> __('Order of posts', LANGUAGE_THEME),
        'desc' 	=> __('', LANGUAGE_THEME),
        'id' 	=> 'order',
        'type' 	=> 'select',
        'value' => 'ASC',
        'options' => array(
            'ASC' => 'Ascending',
            'DESC' => __('Descending', LANGUAGE_THEME)
        )
    ),

    //DISPLAY
    array(
        'section'	=> 'sec_opciones_project_list',
        'id'		=> 'subsection4',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'DISPLAY',
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Display border', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'with_border',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Hover: display link to post', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_post',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Hover: display link to lightbox', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_lightbox',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),

    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Hover: display link to external page', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_external',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_list',
        'title' => __('Hover: display content text', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_curtain_text',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),



    /////////////////////////////////////////////////
    // Para PROJECTS ARCHIVE
    /////////////////////////////////////////////////

    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Page title', LANGUAGE_THEME),
        'desc'  => __('example: Projects Archive: %term%', LANGUAGE_THEME),
        'id'    => 'page_title',
        'type'  => 'input',
        'value' => 'Projects Archive: %term%',
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'subsection1',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'GALLERY',
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'type',
        'title'		=> __('Type of gallery', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'value' => 'masonry_image',
        'options' => array(
            'masonry_image'                     => 'Masonry with image',
            'masonry_image_and_text'            => 'Masonry with image and text',
            'grid_image'                        => 'Grid with image',
            'grid_image_and_text'               => 'Grid with image and text',
            'justified_grid_image'              => 'Justified grid image',
            'list_image_and_text'               => 'List',
            'list_image_and_text_alternate'     => 'List alternate',
            'gallery_image'                     => 'Slider',
            'gallery_image_and_text'            => 'Slider with text',
            'fullwidth_image'                   => 'Fullwidth image'
        ),
    ),

    //Masonry
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Masonry: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'masonry_width',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','masonry_image,masonry_image_and_text')
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Masonry: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 2', LANGUAGE_THEME),
        'id'    => 'masonry_margin',
        'type'  => 'input',
        'value' => 2,
        'required' => array('type','masonry_image,masonry_image_and_text')
    ),

    //Grid
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Grid: number of columns', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'grid_cols',
        'type'  => 'select',
        'value' => '4',
        'required' => array('type','grid_image,grid_image_and_text'),
        'options' => array('2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6')
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Grid: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'grid_width',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Grid: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 2', LANGUAGE_THEME),
        'id'    => 'grid_padding',
        'type'  => 'input',
        'value' => 2,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Grid: ratio image vertical/horizontal', LANGUAGE_THEME),
        'desc'  => __('examples: 1 (square)<br>0.5 (landscape)<br>2 (vertical)', LANGUAGE_THEME),
        'id'    => 'grid_ratio',
        'type'  => 'input',
        'value' => 0.75,
        'required' => array('type','grid_image,grid_image_and_text'),
    ),

    //Justified grid
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Justified grid: column height en pixels', LANGUAGE_THEME),
        'desc'  => __('example: 300', LANGUAGE_THEME),
        'id'    => 'jgrid_height',
        'type'  => 'input',
        'value' => 300,
        'required' => array('type','justified_grid_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Justified grid: separation between elements in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 1', LANGUAGE_THEME),
        'id'    => 'jgrid_padding',
        'type'  => 'input',
        'value' => 1,
        'required' => array('type','justified_grid_image'),
    ),

    //List
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('List: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 400', LANGUAGE_THEME),
        'id'    => 'list_width',
        'type'  => 'input',
        'value' => 400,
        'required' => array('type', 'list_image_and_text,list_image_and_text_alternate'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('List: ratio image vertical/horizontal', LANGUAGE_THEME),
        'desc'  => __('examples: 1 (square)<br>0.5 (landscape)<br>2 (vertical)', LANGUAGE_THEME),
        'id'    => 'list_ratio',
        'type'  => 'input',
        'value' => 0.75,
        'required' => array('type', 'list_image_and_text,list_image_and_text_alternate'),
    ),

    //Slider
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Slider: image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 800', LANGUAGE_THEME),
        'id'    => 'gallery_width',
        'type'  => 'input',
        'value' => 800,
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Slider: image height in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 500', LANGUAGE_THEME),
        'id'    => 'gallery_height',
        'type'  => 'input',
        'value' => 500,
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Slider: image mode', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'gallery_mode',
        'type'  => 'select',
        'value' => 'fill',
        'required' => array('type', 'gallery_image,gallery_image_and_text'),
        'options' => array(
            'fill' => 'Fill: image will expand to fill the slider container',
            'fit' => 'Fit: image will fit inside the slider container'
        )
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Slider: display post excerpt', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'gallery_text_desc',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'gallery_image_and_text'),
        'options' => array(
            'yes' => 'Yes, display below the title',
            'no' => 'No'
        )
    ),
    //Fullwidth image
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Image width in pixels', LANGUAGE_THEME),
        'desc'  => __('example: 800', LANGUAGE_THEME),
        'id'    => 'fullwidth_width',
        'type'  => 'input',
        'value' => 800,
        'required' => array('type', 'fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Image height in pixels', LANGUAGE_THEME),
        'desc'  => __('Example: 400 or <br>Leave it blank if you do not want to crop the image.', LANGUAGE_THEME),
        'id'    => 'fullwidth_height',
        'type'  => 'input',
        'value' => 400,
        'required' => array('type', 'fullwidth_image'),
    ),



    //Projects archive Paging
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'subsection2',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'PAGING',
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Pagination type', LANGUAGE_THEME),
        //'class' => 'aps-title-grande',
        'desc'  => __('Not valid for Slider gallery<br>The number of posts in page is defined in <a href="'.admin_url().'/options-reading.php">Settings</a> as with the blog', LANGUAGE_THEME),
        'id'    => 'paging_type',
        'type'  => 'select',
        'value' => 'no',
        'required' => array('use_gallery_of_post', 'no'),
        'options' => array(
            //'none'      => 'No pagination. Show all elements',
            'numbers'   => 'Pagination with Numbers',
            'ajax'      => 'Pagination with Load-More button',
            'scroll'      => 'Infinite scroll'
        )
    ),
    /*array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Pagination: posts per page', LANGUAGE_THEME),
        'desc'  => __('Valid only when you are using pagination', LANGUAGE_THEME),
        'id'    => 'posts_per_page',
        'type'  => 'input',
        'required' => array('paging_type', 'numbers,ajax'),
        'value' => 10
    ),
    */

    //Order
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'subsection3',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'ORDER',
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title'	=> __('Order posts By', LANGUAGE_THEME),
        //'class' => 'aps-title-grande',
        'desc' 	=> __('', LANGUAGE_THEME),
        'id' 	=> 'orderby',
        'type' 	=> 'select',
        'value' => 'date',
        'options' => array(
            //'none'          => 'None',
            'title'         => __('Title', LANGUAGE_THEME),
            'author'        => 'Author',
            'date'          => 'Date',
            //'comment_count' => 'Popularity',
            'rand'          => 'Random'
        )
    ),

    array(
        'section'	=> 'sec_opciones_project_archive',
        'title'	=> __('Order of posts', LANGUAGE_THEME),
        'desc' 	=> __('', LANGUAGE_THEME),
        'id' 	=> 'order',
        'type' 	=> 'select',
        'value' => 'ASC',
        'options' => array(
            'ASC' => 'Ascending',
            'DESC' => __('Descending', LANGUAGE_THEME)
        )
    ),

    //DISPLAY
    array(
        'section'	=> 'sec_opciones_project_archive',
        'id'		=> 'subsection4',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'subsection',
        'title_sub' => 'DISPLAY',
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Display border', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'with_border',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Hover: display link to post', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_post',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Hover: display link to lightbox', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_lightbox',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),

    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Hover: display link to external page', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_link_external',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),
    array(
        'section'	=> 'sec_opciones_project_archive',
        'title' => __('Hover: display content text', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'display_curtain_text',
        'type'  => 'yes_no',
        'value' => 'yes',
        'required' => array('type', 'masonry_image,masonry_image_and_text,grid_image,grid_image_and_text,list_image_and_text,list_image_and_text_alternate,justified_grid_image,fullwidth_image'),
    ),


    //Backup options aps_op_backup
    array(
        'section'	=> 'sec_opciones_backup',
        'title' => __('Backup Options', LANGUAGE_THEME),
        'desc'  => __('This will generate a text with all the options and save it.<br>You can also copy the options from the textarea and save them in a text file.', LANGUAGE_THEME),
        'id'    => 'backup_button_1',
        'type'  => 'ajax_button',
        'button' => __('BACKUP OPTIONS', LANGUAGE_THEME),
        'action' => 'backup_options',
        'value' => '',
    ),
    array(
        'section'	=> 'sec_opciones_backup',
        'title' => __('Restore Options', LANGUAGE_THEME),
        'desc'  => __('This will restore options with the data inside the textarea', LANGUAGE_THEME),
        'id'    => 'backup_button_2',
        'type'  => 'ajax_button',
        'button' => __('RESTORE OPTIONS', LANGUAGE_THEME),
        'action' => 'restore_options',
        'value' => '',
    ),
    array(
        'section'	=> 'sec_opciones_backup',
        'title' => __('Options', LANGUAGE_THEME),
        'desc'  => __('Copy the text file of your options inside the textarea and press - RESTORE OPTIONS -', LANGUAGE_THEME),
        'id'    => 'textarea_options',
        'type'  => 'textarea',
        'class' => 'widefat',
        'value' => '',
    ),
    array(
        'section'	=> 'sec_opciones_seo',
        'title' => __('Description', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'seo_description',
        'type'  => 'textarea'
    ),
    array(
        'section'	=> 'sec_opciones_seo',
        'title' => __('Keywords', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'seo_keywords',
        'type'  => 'textarea'
    ),
    array(
        'section'	=> 'sec_opciones_seo',
        'title' => __('Author', LANGUAGE_THEME),
        'desc'  => __('', LANGUAGE_THEME),
        'id'    => 'seo_author',
        'type'  => 'input'
    ),

);











/*******************************************************/
//Campos que define el estilo de colores del theme
/*******************************************************/

$aps_config['theme_fields'] = array(

	//TABS
	array(
		'id'		=> 'layout_tabs',
		'title'		=> __('', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pills',
		'class'		=> 'theme-style-tabs',
		'tabs'		=>  array(
						'tab_general' => 'General',
						'tab_header' => 'Header',
						'tab_left' => 'Left sidebar',
						'tab_main' => 'Main content',
						'tab_right' => 'Right sidebar',
						'tab_footer' => 'Footer',
						'tab_socket' => 'Socket',
						)
	),
	
	
	//GENERAL
	array(
		'id'		=> 'general_backcolor',
		'title'		=> __('Background-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_general'),
		'data-target' => '.rough-tpl-wrap',
		'data-css' 	  => 'background-color'
	),
    //pattern
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_general'),
    ),
    array(
        'id'		=> 'general_pattern',
        'title'		=> __('Back pattern', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'pattern',
        'tab'	=>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl-wrap',
    ),
    array(
        'id'		=> 'general_pattern_scroll',
        'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'pattern_scroll',
        'required'	=>  array('general_pattern','{true}'),
        'tab'	=>  array('layout_tabs','tab_general'),
        //'data-target' => '.rough-tpl-wrap',
        'data-css'	=> 'background-attachment'
    ),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_general'),
	),

	
    /*array(
        'id'		=> 'font_googlefonts',
        'title'		=> __('', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'googlefonts',
        'tab'	=>  array('layout_tabs','tab_general')
    ),*/
	array(
		'id'		=> 'heading_font',
		'title'		=> __('Heading Font', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'select_font_real',
		'tab'	=>  array('layout_tabs','tab_general')
	),
	array(
		'id'		=> 'body_font',
		'title'		=> __('Body Font', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'select_font_real',
		'tab'	=>  array('layout_tabs','tab_general')
	),
	array(
		'id'		=> 'blog_title_font',
		'title'		=> __('BLOG Title Font', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'select_font_real',
		'tab'	=>  array('layout_tabs','tab_general')
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_general'),
	),
    array(
        'id'		    => 'font_size_h1',
        'title'		    => __('H1 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h1',
		'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'font_size_h2',
        'title'		    => __('H2 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h2',
        'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'font_size_h3',
        'title'		    => __('H3 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h3',
        'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'font_size_h4',
        'title'		    => __('H4 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h4',
        'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'font_size_h5',
        'title'		    => __('H5 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h5',
        'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'font_size_h6',
        'title'		    => __('H6 font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl h6',
        'data-css' 	  => 'font-size'
    ),
    /*array(
        'id'		    => 'font_size_body',
        'title'		    => __('Body font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_general'),
        'data-target' => '.rough-tpl,.rough-tpl div,.rough-tpl p',
        'data-css' 	  => 'font-size'
    ),*/


	
	
	
	//HEADER TOP
    array(
        'id'		    => 'header_top_font_size',
        'title'		    => __('TOP: Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_header'),
        'data-target' => '.rough-tpl-header-top span,.rough-tpl-header-top a',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_header'),
    ),
	array(
		'id'		=> 'header_top_backcolor',
		'title'		=> __('TOP: Back Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
        //'class'     => 'with-transparent',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'header_top_color',
		'title'		=> __('TOP: Text Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top',
		'data-css' 	  => 'color'
	),


	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_top_menu_hover_color',
		'title'		=> __('TOP: Menu Hover Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top .text.hover',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'header_top_link_color',
		'title'		=> __('TOP: Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'header_top_hoverlink_color',
		'title'		=> __('TOP: Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_top_division_color',
		'title'		=> __('TOP: Division color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top .text',
		'data-css' 	  => 'border-color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_top_pattern',
		'title'		=> __('TOP: Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-top',
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_header'),
    ),
	
	//HEADER BOTTOM
    array(
        'id'		    => 'header_bottom_font_size',
        'title'		    => __('BOTTOM: Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_header'),
        'data-target' => '.rough-tpl-header-center span,.rough-tpl-header-center a,.rough-tpl-header-bottom span,.rough-tpl-header-bottom a',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_header'),
    ),
	array(
		'id'		=> 'header_bottom_backcolor',
		'title'		=> __('BOTTOM: Back Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
        //'class'     => 'with-transparent',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center,.rough-tpl-header-bottom',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'header_bottom_color',
		'title'		=> __('BOTTOM: Text Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center,.rough-tpl-header-bottom',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_bottom_menu_hover_color',
		'title'		=> __('BOTTOM: Menu Hover Color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center .text.hover,.rough-tpl-header-bottom .text.hover',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'header_bottom_link_color',
		'title'		=> __('BOTTOM: Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center .link,.rough-tpl-header-bottom .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'header_bottom_hoverlink_color',
		'title'		=> __('BOTTOM: Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center .hoverlink,.rough-tpl-header-bottom .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_bottom_division_color',
		'title'		=> __('BOTTOM: Division color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center .text,.rough-tpl-header-bottom .text,.rough-tpl-header-center .icon',
		'data-css' 	  => 'border-color'
	),
	array(
		'id'		=> 'header_bottom_border_color',
		'title'		=> __('BOTTOM: Border color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center,.rough-tpl-header-bottom',
		'data-css' 	  => 'border-color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_header'),
	),
	array(
		'id'		=> 'header_bottom_pattern',
		'title'		=> __('BOTTOM: Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_header'),
		'data-target' => '.rough-tpl-header-center,.rough-tpl-header-bottom',
	),

	
	//LEFT
    array(
        'id'		    => 'left_font_size',
        'title'		    => __('Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_left'),
        'data-target' => '.rough-tpl-wrap .rough-tpl.rough-tpl-left',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_left'),
    ),
	array(
		'id'		=> 'left_backcolor',
		'title'		=> __('Background-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'left_heading_color',
		'title'		=> __('Heading color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left .heading',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'left_color',
		'title'		=> __('Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left .text,.rough-tpl-left .select,.rough-tpl-left li',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_left'),
	),
    array(
        'id'		=> 'left_border_color_line',
        'title'		=> __('Sidebar right border', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_left'),
        'data-target' => '.rough-tpl-left',
        'data-css' 	  => 'border-color'
    ),
    array(
        'id'		=> 'left_border_color',
        'title'		=> __('Widgets division line', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_left'),
        'data-target' => '.rough-tpl-left .r-widget',
        'data-css' 	  => 'border-color'
    ),
    array(
        'id'		=> 'left_widget_backcolor',
        'title'		=> __('Widgets background', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'class'     => 'with-transparent',
        'tab'	=>  array('layout_tabs','tab_left'),
        'data-target' => '.rough-tpl-left .r-widget',
        'data-css' 	  => 'background-color'
    ),
    array(
        'id'		=> 'left_widget_margin',
        'title'		=> __('Widgets margin', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'tab'	=>  array('layout_tabs','tab_left'),
        'options'   => array(
            '0px'=>'0px',
            '1px'=>'1px',
            '2px'=>'2px',
            '5px' =>'5px',
            '10px' =>'10px',
            '15px' =>'15px',
            '20px' =>'20px',
            '30px' =>'30px',
            '40px' =>'40px',
            '50px' =>'50px',
            '60px' =>'60px',
            '70px' =>'70px',
            '80px' =>'80px',
            '90px' =>'90px',
            '100px' =>'100px',

        ),
        'data-target' => '.rough-tpl-left .r-widget',
        'data-css' 	  => 'margin-bottom'
    ),



	array(
		'id'		=> 'left_border_color_menu',
		'title'		=> __('Items border', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left ul li,.rough-tpl-left .select',
		'data-css' 	  => 'border-color'
	),


	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_left'),
	),
	array(
		'id'		=> 'left_link_color',
		'title'		=> __('Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'left_hoverlink_color',
		'title'		=> __('Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_left'),
	),
	//pattern
	array(
		'id'		=> 'left_pattern',
		'title'		=> __('Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_left'),
		'data-target' => '.rough-tpl-left',
	),
	array(
		'id'		=> 'left_pattern_scroll',
		'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern_scroll',
		'tab'	=>  array('layout_tabs','tab_left'),
		'required'	=>  array('left_pattern','{true}'),
		'data-target' => '.rough-tpl-left',
		'data-css'	=> 'background-attachment'
	),
	
	
	
	//MAIN
    array(
        'id'		    => 'main_font_size',
        'title'		    => __('Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_main'),
        'data-target' => '.rough-tpl.rough-tpl-main .body',
        'data-css' 	  => 'font-size'
    ),
    array(
        'id'		    => 'main_meta_font_size',
        'title'		    => __('Meta Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_main'),
        'data-target' => '.rough-tpl.rough-tpl-main div.metadata',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_main'),
    ),
	array(
		'id'		=> 'main_backcolor',
		'title'		=> __('Back-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'main_heading_color',
		'title'		=> __('Heading color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .heading',
		'data-css' 	  => 'color',
        'data-target-css' => array('.theme-heading'=>'background-color')
	),
	array(
		'id'		=> 'main_color',
		'title'		=> __('Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .text,.rough-tpl-main .select',
		'data-css' 	  => 'color',
        'data-target-css' => array('.theme-text'=>'background-color')
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_main'),
	),
	
	array(
		'id'		=> 'main_hig_backcolor',
		'title'		=> __('Highlight-Back-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .highlightbox',
		'data-css' 	  => 'background-color',
        'data-target-css' => array('.theme-title'=>'background-color')
        //'data-target-css' => array('.r-button.b3'=>'background-color','.body.r-button.b4'=>'border-color','.r-button.b4'=>'color')
	),
	array(
		'id'		=> 'main_hig_color',
		'title'		=> __('Highlight Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .highlightbox',
		'data-css' 	  => 'color',
        'data-target-css' => array('.theme-title'=>'color')
        //'data-target-css' => array('.r-button.b3'=>'color')
	),
    array(
        'id'		=> 'main_hig_hover_color',
        'title'		=> __('Highlight Hover Text color', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_main'),
        'data-target' => '.rough-tpl-main .highlightbox span',
        'data-css' 	  => 'color'
    ),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_main'),
	),
	
	array(
		'id'		=> 'main_alt_backcolor',
		'title'		=> __('Alternate-Back-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .alternate',
		'data-css' 	  => 'background-color',
        'data-target-css' => array('.theme-heading'=>'color','.theme-text'=>'color', '.theme-link'=>'color', '.theme-hoverlink'=>'color')
	),
	array(
		'id'		=> 'main_alt_color',
		'title'		=> __('Alternate Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .alternate .alt-text',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_main'),
	),
	
	array(
		'id'		=> 'main_window_border_color',
		'title'		=> __('Window border color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .ventana',
		'data-css' 	  => 'border-color'
	),
	array(
		'id'		=> 'main_border_color',
		'title'		=> __('Other borders color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .select',
		'data-css' 	  => 'border-color'
	),

	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_main'),
	),
	
	array(
		'id'		=> 'main_link_color',
		'title'		=> __('Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .link',
		'data-css' 	  => 'color',
        'data-target-css' => array('.theme-link'=>'background-color'),

        /*'data-target-css' => array( '.rough-tpl-main .link, .r-button.b2'=>'color',
                                    '.r-button.b1' => 'background-color',
                                    '.r-button.b2' => 'border-color',
                                    '.ventana .metadata' => 'color'
                                    )*/
	),
	array(
		'id'		=> 'main_hoverlink_color',
		'title'		=> __('Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .hoverlink',
		'data-css' 	  => 'color',
        'data-target-css' => array('.theme-hoverlink'=>'background-color')
	),
	array(
		'id'		=> 'main_link_back_color',
		'title'		=> __('Link Back color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_main'),
		'data-target' => '.rough-tpl-main .ventana .icono',
		'data-css' 	  => 'background-color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_main'),
	),


    //pattern
    array(
        'id'		=> 'main_pattern',
        'title'		=> __('Back pattern', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'pattern',
        'tab'	=>  array('layout_tabs','tab_main'),
        'data-target' => '.rough-tpl-main',
    ),
    array(
        'id'		=> 'main_pattern_scroll',
        'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'pattern_scroll',
        'tab'	=>  array('layout_tabs','tab_main'),
        'required'	=>  array('main_pattern','{true}'),
        'data-target' => '.rough-tpl-main',
        'data-css'	=> 'background-attachment'
    ),
	
	
	//RIGHT
    array(
        'id'		    => 'right_font_size',
        'title'		    => __('Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_right'),
        'data-target' => '.rough-tpl-wrap .rough-tpl.rough-tpl-right',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_right'),
    ),
	array(
		'id'		=> 'right_backcolor',
		'title'		=> __('Background-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'right_heading_color',
		'title'		=> __('Heading color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right .heading',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'right_color',
		'title'		=> __('Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right .text,.rough-tpl-right .select,.rough-tpl-right li',
		'data-css' 	  => 'color'
	),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_right'),
    ),
    array(
        'id'		=> 'right_border_color_line',
        'title'		=> __('Sidebar left border', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_right'),
        'data-target' => '.rough-tpl-right',
        'data-css' 	  => 'border-color'
    ),
    array(
        'id'		=> 'right_border_color',
        'title'		=> __('Widgets division line', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_right'),
        'data-target' => ',.rough-tpl-right .r-widget',
        'data-css' 	  => 'border-color'
    ),
    array(
        'id'		=> 'right_widget_backcolor',
        'title'		=> __('Widgets background', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'class'     => 'with-transparent',
        'tab'	=>  array('layout_tabs','tab_right'),
        'data-target' => '.rough-tpl-right .r-widget',
        'data-css' 	  => 'background-color'
    ),
    array(
        'id'		=> 'right_widget_margin',
        'title'		=> __('Widgets margin', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'select',
        'tab'	=>  array('layout_tabs','tab_right'),
        'options'   => array(
            '0px'=>'0px',
            '1px'=>'1px',
            '2px'=>'2px',
            '5px' =>'5px',
            '10px' =>'10px',
            '15px' =>'15px',
            '20px' =>'20px',
            '30px' =>'30px',
            '40px' =>'40px',
            '50px' =>'50px',
            '60px' =>'60px',
            '70px' =>'70px',
            '80px' =>'80px',
            '90px' =>'90px',
            '100px' =>'100px',
        ),
        'data-target' => '.rough-tpl-right .r-widget',
        'data-css' 	  => 'margin-bottom'
    ),



    array(
        'id'		=> 'right_border_color_menu',
        'title'		=> __('Menu lines', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_right'),
        'data-target' => '.rough-tpl-right ul li,.rough-tpl-right .select',
        'data-css' 	  => 'border-color'
    ),

	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_right'),
	),
	array(
		'id'		=> 'right_link_color',
		'title'		=> __('Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'right_hoverlink_color',
		'title'		=> __('Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_right'),
	),
	//pattern
	array(
		'id'		=> 'right_pattern',
		'title'		=> __('Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_right'),
		'data-target' => '.rough-tpl-right',
	),
	array(
		'id'		=> 'right_pattern_scroll',
		'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern_scroll',
		'tab'	=>  array('layout_tabs','tab_right'),
		'required'	=>  array('right_pattern','{true}'),
		'data-target' => '.rough-tpl-right',
		'data-css'	=> 'background-attachment'
	),
	
	
	
	//FOOTER
    array(
        'id'		    => 'footer_font_size',
        'title'		    => __('Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_footer'),
        'data-target' => '.rough-tpl-footer span, .rough-tpl-footer a, .rough-tpl-footer div, .rough-tpl-footer ',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_footer'),
    ),
	array(
		'id'		=> 'footer_backcolor',
		'title'		=> __('Background-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'footer_heading_color',
		'title'		=> __('Heading color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer .heading',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'footer_color',
		'title'		=> __('Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer .text,.rough-tpl-footer .select,.rough-tpl-footer li',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_footer'),
	),
    array(
        'id'		=> 'footer_border_color_line',
        'title'		=> __('Footer top division', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_footer'),
        'data-target' => '.rough-tpl-footer',
        'data-css' 	  => 'border-color'
    ),
	array(
		'id'		=> 'footer_border_color',
		'title'		=> __('Widgets division', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer .widget-division',
		'data-css' 	  => 'border-color'
	),
    array(
        'id'		=> 'footer_border_color_menu',
        'title'		=> __('Menu lines', LANGUAGE_THEME),
        'desc'		=> __('', LANGUAGE_THEME),
        'type' 		=> 'color',
        'tab'	=>  array('layout_tabs','tab_footer'),
        'data-target' => '.rough-tpl-footer ul li,.rough-tpl-footer .select',
        'data-css' 	  => 'border-color'
    ),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_footer'),
	),
	array(
		'id'		=> 'footer_link_color',
		'title'		=> __('Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'footer_hoverlink_color',
		'title'		=> __('Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_footer'),
	),
	//pattern
	array(
		'id'		=> 'footer_pattern',
		'title'		=> __('Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'data-target' => '.rough-tpl-footer',
	),
	array(
		'id'		=> 'footer_pattern_scroll',
		'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern_scroll',
		'tab'	=>  array('layout_tabs','tab_footer'),
		'required'	=>  array('footer_pattern','{true}'),
		'data-target' => '.rough-tpl-footer',
		'data-css'	=> 'background-attachment'
	),
	
	
	
	//SOCKET
    array(
        'id'		    => 'socket_font_size',
        'title'		    => __('Font size', LANGUAGE_THEME),
        'desc'		    => __('', LANGUAGE_THEME),
        'type' 		    => 'select_font_size',
        'tab'	        =>  array('layout_tabs','tab_socket'),
        'data-target' => '.rough-tpl-socket span, .rough-tpl-socket a',
        'data-css' 	  => 'font-size'
    ),
    array(
        'type' 		=> 'line',
        'tab'	=>  array('layout_tabs','tab_socket'),
    ),
	array(
		'id'		=> 'socket_backcolor',
		'title'		=> __('Background-color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket',
		'data-css' 	  => 'background-color'
	),
	array(
		'id'		=> 'socket_color',
		'title'		=> __('Text color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket .text',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_socket'),
	),
	array(
		'id'		=> 'socket_division_color',
		'title'		=> __('Division color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket .text,.rough-tpl-socket .icon',
		'data-css' 	  => 'border-color'
	),
	array(
		'id'		=> 'socket_border_color',
		'title'		=> __('Border color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket',
		'data-css' 	  => 'border-color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_socket'),
	),
	array(
		'id'		=> 'socket_link_color',
		'title'		=> __('Link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket .link',
		'data-css' 	  => 'color'
	),
	array(
		'id'		=> 'socket_hoverlink_color',
		'title'		=> __('Hovered link color', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'color',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket .hoverlink',
		'data-css' 	  => 'color'
	),
	array(
		'type' 		=> 'line',
		'tab'	=>  array('layout_tabs','tab_socket'),
	),
	//pattern
	array(
		'id'		=> 'socket_pattern',
		'title'		=> __('Back pattern', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'data-target' => '.rough-tpl-socket',
	),
	array(
		'id'		=> 'socket_pattern_scroll',
		'title'		=> __('Pattern fixed/scroll', LANGUAGE_THEME),
		'desc'		=> __('', LANGUAGE_THEME),
		'type' 		=> 'pattern_scroll',
		'tab'	=>  array('layout_tabs','tab_socket'),
		'required'	=>  array('socket_pattern','{true}'),
		'data-target' => '.rough-tpl-socket',
		'data-css'	=> 'background-attachment'
	),
);

$aps_config['option_fields'] = array_merge( $aps_config['option_fields'], aps_get_options_social_icons() );
do_action('theme_option_fields');
