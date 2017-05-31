<?php
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }


//////////////////////////////////////////
// DATOS QUE NECESITO backend y frontend
//////////////////////////////////////////

//Mapeo los fields para poder obtener el dato directamente
//aps_get_option('page_backcolor')
//en vez de tener que pasar: aps_op_style['page_backcolor']

$aps_config['map_section_page'] = array();
$aps_config['map_field_page'] = array();

foreach($aps_config['option_sections'] as $section)
	$aps_config['map_section_page'][$section['id']] = $section['page'];

foreach($aps_config['option_fields'] as $field)
	$aps_config['map_field_page'][$field['id']] = $aps_config['map_section_page'][$field['section']];



//Obtener el valor del setting directamente sin sabe la pagina a la que pertenece
function aps_get_option($key)
{
	global $aps_config;
    if ( isset($aps_config['map_field_page'][$key]) )
    {
        $page = $aps_config['map_field_page'][$key];
        $options = get_option($page);
        if (isset($options[$key])) { return $options[$key]; }
        return false;
    }
    else
    {
        return false;
    }

}


function aps_get_option_page($key,$page = 'aps_op_theme_style')
{
	global $aps_config;
	
	//cache
	if (isset($aps_config[$page])){
		$options = $aps_config[$page];
	} else {
		$options = get_option($page);
		$aps_config[$page] = $options;
	}
	
	if (isset($options[$key]))
		return $options[$key];
	return null;
}





//////////////////////////////////////////
// CREACION DEL MENU DE OPCIONES
//////////////////////////////////////////

function aps_create_options_page()
{
	global $aps_config;
	$option_pages = $aps_config['option_pages'];

	$first = true;
	$first_page = $option_pages[0]['id'];
	$index_page = 0;
	
	foreach($option_pages as $page)
	{
		if ($first)
		{
			$id = $page['id'];
			$main_page = add_menu_page(
				$page['page_title'],
				$page['menu_title'],
				'edit_theme_options',
				$page['id'],
				'aps_mostrar_pagina_opciones',
				isset($page['icon']) ? $page['icon'] : '',
				$page['position']
			);
			$first = false;	
			
			// Adds actions to hook in the required css and javascript
			add_action( 'admin_print_styles-' . $main_page,'aps_options_load_styles' );
			add_action( 'admin_print_scripts-' . $main_page, 'aps_options_load_scripts' );
			add_action( 'admin_print_scripts-' . $main_page, 'aps_options_media_scripts' );
		}
		else
		{
			$subpage = add_submenu_page(
				$first_page,
				$page['page_title'],
				$page['menu_title'],
				'edit_theme_options',
				$page['id'],
				'aps_mostrar_pagina_opciones'
			);
			
			// Adds actions to hook in the required css and javascript
			add_action( 'admin_print_styles-' . $subpage,'aps_options_load_styles' );
			add_action( 'admin_print_scripts-' . $subpage, 'aps_options_load_scripts' );
			add_action( 'admin_print_scripts-' . $subpage, 'aps_options_media_scripts' );
		}
		$index_page++;
	}
	
	//Change menu name for main page
	global $submenu;
	if (isset($submenu['aps_op_general'][0][0])){
		$submenu[$option_pages[0]['id']][0][0] = $option_pages['0']['menu_title_in'];
	}
}
add_action('admin_menu', 'aps_create_options_page');



//por ahora estoy usando aps_admin_enqueue_scripts en enqueue_scripts.php
//ya veremos como lo cambio


function aps_options_load_styles()
{

}

function aps_options_load_scripts()
{
	$page = $_REQUEST['page'];
	if ($page=='aps_op_maps'){
		wp_enqueue_script( 'map-styles', APS_THEME_URI.'/includes/js/map-styles.js');	
	}
	
}

function aps_options_media_scripts()
{
	
}




function aps_mostrar_pagina_opciones()
{
	global $aps_config;
	
	$page_id = $_REQUEST['page'];
	if (empty($page_id)) return;
	
	$page = null;
	foreach($aps_config['option_pages'] as $menu_page)
	{
		if ($menu_page['id'] == $page_id)
		{
			$page = $menu_page;
			$title = $menu_page['title'];
			break;
		}	
	}
	
	if (isset( $_REQUEST['settings-updated']) && $_REQUEST['settings-updated']=='true')
	{

        if ( $page_id=='aps_op_theme_style' ) {
            //Para generar el dynamic style css
            do_action('aps_after_settings_skin_saved');
        }
        else if ( $page_id=='aps_op_mobile' )
        {
            //Para generar las opciones responsive
            do_action('aps_after_settings_mobile_saved');
        }

        do_action('aps_after_settings_saved');
	}


	?>
	<div class="wrap aps-options-page">
		<div id="icon-options-general" class="icon32"></div>
		<h2><?php echo $title; ?></h2>
		<?php settings_errors(); ?>
		<form method="post" action="options.php">
			<?php
			settings_fields( $page_id );
			//Si tiene una funcion especial
			if (isset($page['callback']))
				$page['callback']();
			else
				do_settings_sections( $page_id );
			
			submit_button();
			?>
		</form>
	</div>
	<?php	
}

//Pendiente
function aps_do_settings_sections($id)
{
	
}



//////////////////////////////////////////
// REGISTRAR SECTIONS Y SETTINGS
//////////////////////////////////////////

function aps_registrar_las_opciones()
{
	global $aps_config;
	$sections = $aps_config['option_sections'];
	$fields = $aps_config['option_fields'];
	
	$index=0;
	foreach($sections as $section)
	{
		add_settings_section(
			$section['id'],		
			$section['title'],		
			'aps_mostrar_seccion',
			$section['page']		
		);
		$index++;
	}
	
	foreach($fields as $field)
	{
		//A los fields les asigno la pagina a la que pertenece la seccion
		$page = '';
		foreach($sections as $sec){
			if ($field['section'] == $sec['id']){
				$page = $sec['page'];
				$field['page']=$sec['page'];
				break;
			}
		}
		add_settings_field(	
			$field['id'],
			$field['title'],		
			'aps_mostrar_opcion_'.$field['type'],	
			$field['page'],	
			$field['section'],			
			$field
		);
	}
	
	foreach($sections as $section)
	{
		register_setting($section['page'],$section['page']);
	}
}



add_action( 'admin_init', 'aps_registrar_las_opciones' );





//////////////////////////////////////////
// MOSTRAR SECTIONS Y SETTINGS
// Tambien lo puedo usar para los metaboxes ?
//////////////////////////////////////////

// DESCRIPCION DE LA SECCION

function aps_mostrar_seccion()
{
	global $aps_config;
	$id = $_REQUEST['page'];
	$index = 0;
	foreach($aps_config['option_sections'] as $section)
	{
		if (!isset($section['echo']) && $section['page'] == $id)
		{
			//Indico que ya lo he usado para no repetirlo
			//por si hay mas de una seccion en la misma pagina
			$aps_config['option_sections'][$index]['echo'] = true;
			echo '<p>'.$section['desc'].'</p>';
			echo '<hr>';	
			break;
		}	
		$index++;
	}
}


// El nombre de la funcion se forma con aps_mostrar_opcion_(type)

//CABECERA
function aps_mostrar_opcion_header($field)
{
	$class_field = (isset($field['class'])) ? $field['class']:'';
	
	//Para el rough template
	$data = '';
	if (isset($field['data-target'])) {
		$data .= ' data-target="'.$field['data-target'].'" ';
    }
		
	if (isset($field['data-css'])) {
		$data .= ' data-css="'.$field['data-css'].'" ';
    }

    if ( isset($field['data-target-css']) &&  is_array( $field['data-target-css'] ) ) {
        $data .= ' data-target-css="'.esc_attr( json_encode($field['data-target-css']) ).'"' ;
    }

	
	if (isset($field['required']) || isset($field['tab']))
	{
		$html  = '<div class="aps-section-field '.$class_field.' type-'.$field['type'].' aps-required" '.$data.'>';
		
		if (isset($field['required'])){
			$html .= '<input class="aps-field-required" type="hidden" value="'.implode('::',$field['required']).'">';
		}
		
		if (isset($field['tab'])){
			$html .= '<input class="aps-field-required required-tab" type="hidden" value="'.implode('::',$field['tab']).'">';
		}
		
	}
	else
	{
		$html = '<div class="aps-section-field '.$class_field.' type-'.$field['type'].'" '.$data.'>';
	}
	
	//Campos especiales rough template
	if (isset($field['title_']))
		$html .= '<div><strong>'.$field['title_'].'</strong></div>';
	
	if (isset($field['desc_']))
		$html .= '<div><small>'.$field['desc_'].'</small></div>';
	
	return $html;
}


function aps_get_option_value($field)
{
	$options = get_option($field['page']);
	if (isset($options[$field['id']]))
		return $options[$field['id']];
	return '';
}


// LINE de separacion
function aps_mostrar_opcion_line($field)
{
	$html = aps_mostrar_opcion_header($field);
	$html .= '<hr></div>';
	echo $html;	
}



// DESCRIPION informativo solo
function aps_mostrar_opcion_desc($field)
{
	$html = aps_mostrar_opcion_header($field);
	$html .= '<div class="description">'.$field['desc'].'</div>';
	$html .= '</div>';
	echo $html;	
}

function aps_mostrar_opcion_subsection($field)
{
	$html = aps_mostrar_opcion_header($field);
	$html .= '<h2>'.$field['title_sub'].'</h2>';
	$html .= '<div class="description"><small>'.$field['desc'].'<small></div>';
	$html .= '<hr>';
	$html .= '</div>';
	echo $html;	
}


// CHECKBOX
function aps_mostrar_opcion_checkbox($field)
{
	$value = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	$html .= '<input type="checkbox" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="1" ' . checked( 1, ($value != '') ? $value : 0, false ) . '/>'; 
	$html .= '<label for="'.$field['id'].'">&nbsp;'  . $field['args'][0] . '</label>'; 
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;		
}

// INPUT 
function aps_mostrar_opcion_input($field)
{
	$value = aps_get_option_value($field);

	$html  = aps_mostrar_opcion_header($field);

	$name = $field['page'].'['.$field['id'].']';
	
	$html .= '<input type="text" id="'.$field['id'].'" name="'.$name.'" value="'.$value.'" class="regular-text code">';
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}

// INPUT SOCIAL
function aps_mostrar_opcion_input_social($field)
{
	$value = aps_get_option_value($field);

	$html  = aps_mostrar_opcion_header($field);

	$name = $field['page'].'['.$field['id'].']';
	
	//$html .= '<input type="checkbox" name="'.$name.'_check">&nbsp;&nbsp;';
	$html .= '<input type="text" id="'.$field['id'].'" name="'.$name.'" value="'.$value.'" class="regular-text code">';
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}



// TEXTAREA
function aps_mostrar_opcion_textarea($field)
{
	$value = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	$html .= '<textarea rows="10" cols="50" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" class="large-text code">'.$value.'</textarea>';
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}

// SELECT
function aps_mostrar_opcion_select($field)
{
	$current_key = aps_get_option_value($field);
	
	$style = "width:250px;height:30px;max-width:250px;";
	
	$html  = aps_mostrar_opcion_header($field);
	
	
	$class_select = isset($field['class_select']) ? $field['class_select'] : '';
	
	$html .= '<select style="'.$style.'" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" class="aps-option-field '.$class_select.'">';
	foreach($field['options'] as $key=>$value){
		$selected = ($key == $current_key) ? 'selected="selected"' : '';
		$html .= '<option value="'.esc_attr($key).'" '.$selected.'>'.$value.'</option>';
	}
	$html .= '</select>';
	$html .= '<p class="description">'.$field['desc'].'</p>';
    if (isset($field['desc_image'])){
        $html .= '<img src="'.$field['desc_image'].'">';
    }
	
	$html .= '</div>';
	echo $html;
}

// RADIO
function aps_mostrar_opcion_radio($field)
{
	$current_key = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	$first = true;
	foreach($field['options'] as $key=>$value)
	{
		$checked = ($key == $current_key) ? 'checked="checked"' : '';
		if ($current_key == null && $first) $checked = 'checked="checked"';
		
		$html .= '<label><input type="radio" data-id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="'.$key.'" '.$checked.'><span> '.$value.'</span></label><br>';
		$first = false;
	}
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}

// RADIO-IMAGE
function aps_mostrar_opcion_radio_image($field)
{
	$current_key = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	
	$html .= '<p class="description">'.$field['desc'].'</p>';
	
	$i = 0;
	$first = true;
	foreach($field['options'] as $key=>$value)
	{
		$checked = ($key == $current_key) ? 'checked="checked"' : '';
		if ($current_key == null && $first) $checked = 'checked="checked"';
		$selected = ($checked=='') ? '':'aps-radio-selected';
		
		$html .= '<label class="aps-radio-image '.$selected.'">';
		
		$image = $field['images'][$i++];
		if (strpos($image,'http://') === false)
			$image = get_template_directory_uri().'/includes/stylesheets/images/'.$image;
		
		$html .= '<img src="'.$image.'">';
		$html .= '<input class="input-radio-image" type="radio" data-id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="'.$key.'" '.$checked.'>';
		$html .= '<div> '.$value.'</div>';
		$html .= '</label>';
		$first = false;
	}
	//$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}

// COLOR
function aps_mostrar_opcion_color($field)
{
	$current_val = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	$style = "";
	$html .= '<input style="'.$style.'" class="aps-option-field aps-color-field" type="text" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="'.$current_val.'" data-default-color="#000">';	
	$html .= '</div>';
	echo $html;
}

// NUMBER
function aps_mostrar_opcion_number($field)
{
	$value = aps_get_option_value($field);
	$min = $field['args']['min'];
	$max = $field['args']['max'];
	$style = "font-size:20px;";
	
	$html  = aps_mostrar_opcion_header($field);
	$html .= '<input style="'.$style.'" type="number" id="'.$field['id'].'" min="'.$min.'" max="'.$max.'" name="'.$field['page'].'['.$field['id'].']" value="'.$value.'" class="">';
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '</div>';
	echo $html;
}

// IMAGE
function aps_mostrar_opcion_image($field)
{
	$value = aps_get_option_value($field);
		
	$style = "max-width:150px;";
	
	$html  = aps_mostrar_opcion_header($field);
	$html .= '<div class="aps-select-image">';
	
	$html .= '<input style="'.$style.'" type="text" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="'.$value.'" class="regular-text code">';
	$html .= '<a href="#" class="button aps_upload_image" data-modal-title="Select Image" data-button-title="Selecciona la imagen">Select</a>';
	
	
	$src = wp_get_attachment_image_src($value, 'medium');
	if (!empty($src)){
		$src = $src[0];
		$hidden = '';	
	} else {
		$src = '';
		$hidden = 'hidden';
	}
	
	$html .= '<p class="description">'.$field['desc'].'</p>';
	
	$html .= '<div class="aps-preview-image" '.$hidden.'>';
	$html .= '<img src="'.$src.'">';
	$html .= '<br><a href="#" class="aps_remove_image button">Remove</a>';
	$html .= '</div>';
	
	$html .= '</div>';
	
	$html .= '</div>';
	
	echo $html;
}

//TABS
function aps_mostrar_opcion_tabs($field)
{
	aps_mostrar_opcion_tabs_pills($field, 'aps-tabs');
}
//PILLS
function aps_mostrar_opcion_pills($field)
{
	aps_mostrar_opcion_tabs_pills($field, 'aps-tabs-pills');
}

function aps_mostrar_opcion_tabs_pills($field, $type="aps-tabs-pills")
{
	$current_key = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	
	$html .= '<p class="description">'.$field['desc'].'</p>';
	
	$class = (isset($field['class'])) ? $field['class'] : '';
	
	$html .= '<div class="'.$type.' '.$class.'">';
	$first = true;
	foreach($field['tabs'] as $key=>$value)
	{
		$checked = ($key == $current_key) ? 'checked="checked"' : '';
		if ($current_key == null && $first) $checked = 'checked="checked"';
		$selected = ($checked=='') ? '' : 'selected';
		
		$html .= '<label class="aps-option-tab '.$selected.'"><input type="radio" class="aps-tab-radio" data-id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" value="'.$key.'" '.$checked.'><span> '.$value.'</span></label>';
		$first = false;
	}
	$html .= '</div>';

	$html .= '</div>';
	echo $html;
}

function aps_mostrar_opcion_yes_no($field)
{
	$field['tabs'] = array('yes' => 'YES','no' => 'NO');
	aps_mostrar_opcion_tabs_pills($field, "aps-tabs-pills");
}


function aps_mostrar_opcion_layout($field)
{
	$current_key = aps_get_option_value($field);
	
	$html  = aps_mostrar_opcion_header($field);
	$html .= '<p class="description">'.$field['desc'].'</p>';
	$html .= '<div class="aps-layout-composition">';
	
	$compose = '';
	$first = true;
	$options = get_option($field['page']);
	foreach($field['layouts'] as $layout)
	{
		$value = isset($options[$layout]) ? $options[$layout] : null;
		if ($value)
		{
			$compose .= $first ? $value : ','.$value;
			$first = false;
		}
		
	}
	//$html .= aps_dame_layout('image-1,header-1,slider-0,content-1,footer-1,socket-1');
	$html .= aps_dame_layout($compose);
	//$html .= $compose;
	$html .= '</div>';
	$html .= '</div>';
	
	echo $html;
}

function aps_mostrar_opcion_select_layout($field)
{
	$options = aps_get_list_layouts();
	$field['options'] = $options;
	aps_mostrar_opcion_select($field);
	echo '<div class="aps-layout-selected"></div>';

}



function aps_dame_options_fonts()
{

    $styles = aps_dame_googlefonts();
    $fonts = array();
    foreach( $styles as $key=>$value){ $fonts[$key] = $key; }

	
	//Websave fonts
	$options = array(
		//'websafe' 							=> '- Web save font -',
		'Arial-websafe' 					=> 'Arial',
		'Georgia-websafe'					=> 'Georgia',
		'Verdana-websafe' 					=> 'Verdana',
		'Helvetica-websafe' 				=> 'Helvetica',
		'HelveticaNeue,Helvetica-websafe' 	=> 'Helvetica Neue',
		'"LucidaSans","LucidaGrande","LucidaSansUnicode"-websafe' => 'Lucida',
		//'google' 								=> '- Google Fonts -'
	);
	
	//Todas
	$options = array_merge($options,$fonts);
	return $options;
}



function aps_mostrar_opcion_googlefonts($field)
{
	//echo '<h3>GOOGLEFONTS</h3>';
	$options = aps_dame_options_fonts();
	$html = '';
	foreach($options as $key=>$value){
		if (strpos($key, 'websafe')===false) {
			$html .= "<link href='http://fonts.googleapis.com/css?family={$key}' rel='stylesheet' type='text/css'>";
		}
	}
	echo $html;
}

//Usa un campo oculto input para poner la fuente y que la guarde el wordpress
function aps_mostrar_opcion_select_font_real($field)
{

    static $count = 0;
    $count++;
    //La primera vez necesito echar los estilos
    if ($count==1) {
        aps_mostrar_opcion_googlefonts($field);
    }

	$options = aps_dame_options_fonts();
	
	foreach($options as $key=>$value) {
		if (strpos($key, 'websafe')===false) {
			$options[$key] = htmlspecialchars("<div style=\"font-family:{$key};\">{$key}</div>");
		} else {
			$options[$key] = htmlspecialchars("<div style=\"font-family:{$value};\">{$value}</div>");
		}
	}
	
	$field['options'] = $options;
	$field['class_select'] = 'ddslick';
	aps_mostrar_opcion_select($field);
}

function aps_mostrar_opcion_select_font_old($field)
{
	$field['options'] = aps_dame_options_fonts();
	aps_mostrar_opcion_select($field);
}


function aps_mostrar_opcion_select_font_size($field)
{
    $array = [];
    for($i=6; $i<100; $i++){
        $array[$i.'px'] = $i.'px';
    }
    $field['options'] = $array;

    aps_mostrar_opcion_select($field);
}


function aps_give_me_theme_patterns()
{
    $options = array();
    $options[''] = ':: None ::';

    $path_images = APS_THEME_DIR.'/includes/stylesheets/images/patterns/';

    //Imagenes que terminan en _t.png son transparentes
    $options2 = array();
    foreach(glob($path_images.'*_t.png') as $file)
    {
        $name = basename($file);
        //if (preg_match('/_@2X/', $name)) continue;
        //if (!preg_match('/_t.png/', $name)) continue;
        $name = preg_replace('/\.png/','' , $name);
        $name_title = ucfirst(preg_replace('/_t/', '', $name));
        $name_title = preg_replace('/_/', ' ', $name_title);
        $options2[$name] = $name_title;
    }
    ksort($options2);

    //Imagenes que terminan en _o.png son opacas
    $options3 = array();
    foreach(glob($path_images.'*_o.png') as $file)
    {
        $name = basename($file);
        $name = preg_replace('/\.png/','' , $name);
        $name_title = ucfirst(preg_replace('/_o/', '', $name));
        $name_title = preg_replace('/_/', ' ', $name_title);
        $options3[$name] = $name_title;
    }
    ksort($options3);

    //Juntar
    $options = array_merge($options,$options2);

    return $options;
}


function aps_mostrar_opcion_pattern($field)
{

	$field['options'] = aps_give_me_theme_patterns();
	aps_mostrar_opcion_select($field);
	
	//Url patterns
	$url_images = APS_THEME_URI.'/includes/stylesheets/images/patterns/';
	echo '<script>var url_patterns="'.$url_images.'";</script>';
}

function aps_mostrar_opcion_pattern_scroll($field)
{
	$options = array(
		'scroll' => 'Scroll',
		'fixed' => 'Fixed'
	);
	$field['options'] = $options;
	aps_mostrar_opcion_select($field);
}



function aps_mostrar_opcion_map_style($field)
{
	$current_key = aps_get_option_value($field);
	$html  = aps_mostrar_opcion_header($field);
	$name = $field['page'].'['.$field['id'].']';
	
	//$html .= '<input type="text" id="'.$field['id'].'" name="'.$name.'" value="'.$value.'" class="regular-text code">';
	$html .= '<p class="description">'.$field['desc'].'</p>';
	
	$style = "width:250px;height:30px;max-width:250px;";
	$html .= '<select style="'.$style.'" id="'.$field['id'].'" name="'.$field['page'].'['.$field['id'].']" class="aps-option-field">';
	foreach($field['options'] as $key=>$value){
		$selected = ($key == $current_key) ? 'selected="selected"' : '';
		$html .= '<option value="'.esc_attr($key).'" '.$selected.'>'.$value.'</option>';
	}
	$html .= '</select><br><br>';
	
	$html .= '<div id="map_canvas_style">MAP CANVAS</div>';
	
	$html .= '</div>';
	echo $html;
}


//UTIL listado nav menus
if ( !function_exists('aps_get_list_menus')):
    function aps_get_list_menus()
    {
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

        $options = array();
        foreach($menus as $menu){
            //echo '<pre>'; print_r($menu); echo '</pre>';
            $options['menu-'.$menu->term_id] = 'MENU: '.$menu->name;
        }
        return $options;
    }
endif;



function aps_mostrar_opcion_select_menu($field)
{
    $options = array();
    $options[''] = 'None';
    $options = array_merge($options, aps_get_list_menus()  );
    $field['options'] = $options;
    aps_mostrar_opcion_select( $field );
}

function aps_mostrar_opcion_ajax_button($field)
{
    static $counter = 0;
    $counter++;

    if ($counter==1) {
        wp_nonce_field('options_ajax_action','security_nonce');
    }
    $html = '';
    $html .= '<a href="#" class="options_ajax_button button-primary" data-action="'.$field['action'].'">'.$field['button'].'</a>';
    $html .= '<p class="description"><small>'.$field['desc'].'</small></p>';
    echo $html;
}

function aps_mostrar_opcion_button_type($field)
{
    $html = '<a class="button-primary" id="'.$field['id'].'" href="'.$field['button_link'].'" data-message="'.esc_attr($field['button_message']).'">'.$field['button_name'].'</a>';
    if (isset($field['button_preloader']) && $field['button_preloader']=='yes') {
        $html .= '<span class="button-preloader"><img src="'.APS_THEME_URI.'/includes/stylesheets/images/preloaders/boxes2.gif"></span>';
    }
    $html .= '<p class="description"><small>'.$field['desc'].'</small></p>';
    echo $html;
}
















// THEME COLORS


function aps_mostrar_opcion_theme_colors()
{
	//Para el name de las opciones
	$page_id = $_REQUEST['page'];
	
	global $aps_config;
	$fields = $aps_config['theme_fields'];
	
	//Wrap global
	echo '<div class="box-theme-style">';
	
	//Boton PRIVADO
	//echo '<button id="save-scheme">Show me the code of this scheme</button>';
	//echo '<br>';
	
	//Boton select template
	aps_rough_template_schemes();
	
	echo '<div class="rough-theme-wrap">';
	
	//Fields
	echo '<div class="rough-theme-left">';
	echo '<div class="rough-theme-left-inner">';
	foreach($fields as $field)
	{
		$field['page'] = 'aps_op_theme_style';
		$field['section'] = 'aps_op_theme_style';
		$field['title_'] = isset($field['title']) ? $field['title'] : '';
		$field['desc_'] = isset($field['desc']) ? $field['desc'] : '';
		$field['title'] = '';
		$field['desc'] = '';
		$function = 'aps_mostrar_opcion_'.$field['type'];
		echo $function($field);
	}
	echo '</div>';
	echo '</div>';
	
	
	//Rough template
	echo '<div class="rough-theme-right">';
	require(APS_THEME_DIR.'/includes/php/rough-template.php');
	echo '</div>';
	
	echo '</div>';
	
	
	echo '</div>';
}




function escapeJsonString($data)
{
	//$result = str_replace('\\', '', $data);
	$result = str_replace('"', '&quot;', $data);
	return $result;
}

function toJsonEscape($data)
{
	$result = json_encode($data);
	$result = str_replace('"', '&quot;', $result);
	return $result;
}

function aps_rough_template_schemes()
{
	//Me da $schemes
	require(APS_THEME_DIR.'/includes/php/rough-template-schemes.php');
	
	$arr_schemes = array();
	foreach($schemes as $key=>$scheme)
	{
		$arr_schemes[$key] = (array)json_decode($scheme);
	}
	
	//Lo transformo en un array valido para montar
	//el esquema de color del template
	$schemes_colors = array();
	foreach($arr_schemes as $key=>$d)
	{
		$schemes_colors[$key] = array(
			'general'	=> array($d['general_backcolor']),
			'header-top'=> array($d['header_top_backcolor'],$d['header_top_color'],'border-right:'.$d['header_top_division_color']),
			'header' 	=> array($d['header_bottom_backcolor'],$d['header_bottom_color'],'border-right:'.$d['header_bottom_division_color']),
			'left' 		=> array($d['left_backcolor'],$d['left_color'],'border-right:'.$d['left_border_color_line']),
			'main' 		=> array($d['main_backcolor'],$d['main_color']),
			'right' 	=> array($d['main_backcolor'],$d['right_color'],'border-left:'.$d['right_border_color_line']),
			'footer' 	=> array($d['footer_backcolor'],$d['footer_color'],'border-left:'.$d['footer_border_color_line']),
			'socket' 	=> array($d['socket_backcolor'],$d['socket_color'],'border-left:'.$d['socket_border_color'])
		);
	}

	
	echo '<div id="popup-schemes-wrap">';
	
	echo '<div class="">';
	echo '<button id="open-popup-schemes">SELECT STYLE&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-down"></i></button>';
	echo '<span style="padding-left:20px;color:#cdcdcd;">This is a rough template of the theme</span>';
	echo '</div>';
	
	echo '<div id="popup-schemes">';
	
	foreach($schemes_colors as $key_scheme=>$colors)
	{
		$json = toJsonEscape($arr_schemes[$key_scheme]);
		?>
		
		
		<label class="scheme-option">
		<input type="radio" name="scheme_colors" value="<?php echo esc_attr($key_scheme); ?>" data-json="<?php echo $json; ?>">
			<div class="scheme-name"><?php echo $key_scheme; ?></div>
			
			<!-- el json del codigo para javascript -->
			<!--script>var pepe = <?php echo esc_js(($schemes[$key_scheme])); ?></script-->
			
			
			<div class="scheme-colors" style="background-color:<?php echo $colors['general'][0]; ?>;">
				<?php
					$html = '';
					foreach($colors as $key=>$color)
					{
						if ($key=='general') continue;
						
						$border = '';
						if (isset($color[2]))
						{
							$border = $color[2];
							$border = preg_split('/:/',$border);
							$border = $border[0].': 1px solid '.$border[1];
						}
						$html .= '<div class="scheme-box scheme-'.$key.'" style="background-color:'.$color[0].';'.$border.'"><div class="scheme-point" style="background-color:'.$color[1].';"></div></div>';
					}
					echo $html;
				?>
			</div>	
		</label>
		<?php
	};
	
	echo '</div></div>';
}









