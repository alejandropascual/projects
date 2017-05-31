
// COLOR PICKER

;jQuery(document).ready(function($){

    "use strict";

    function bind_wpColorPicker()
    {
        //http://www.paulund.co.uk/adding-a-new-color-picker-with-wordpress-3-5
        var myOptions = {

            change: function(event,ui){
                $(this).trigger('color_changed');
            },
            hide: function(event,ui){
                $(this).trigger('color_changed');
            },
            clear: function(event,ui){
                $(this).trigger('color_changed');
            }

        };
        $('.aps-color-field').wpColorPicker(myOptions);
        //jQuery('.aps-color-field').iris();
    }

    bind_wpColorPicker();

    //Para poder poner color transparente
    $('.aps-section-field.with-transparent .wp-picker-container').append('<label><input type="checkbox" class="btn-color-transparent">Transparent</input></label>');

    //Poner al principio los transparentes
    $('.aps-section-field.with-transparent').each(function(index,item){
        //console.log('Comprobando color:');
        //console.log(item);
        //console.log('Al principio tiene el color '+color);
        var $picker = $(this).find('.wp-picker-container');
        var $input = $picker.find('.wp-picker-input-wrap input.aps-color-field');
        var color = $input.val();
        if (color == 'transparent') {
            $picker.find('.btn-color-transparent').prop('checked',true);
            $picker.find('.wp-color-result').fadeOut();
        }

    });

    //Click transparentes
    $('.rough-theme-wrap').on('click','.btn-color-transparent', function(){

        var $self = $(this);
        var value = $self.prop('checked');
        //console.log(value);
        var $picker = $self.closest('.wp-picker-container');

        if (value==true) {
            //console.log('Poniendo transparente');
            $picker.find('.wp-color-result').css({'background-color':'transparent'});
            $picker.find('.wp-picker-input-wrap input.aps-color-field').val('transparent');
            $picker.find('.wp-picker-input-wrap input.aps-color-field').trigger('color_changed');
            $picker.find('.wp-color-result').fadeOut();
        } else {
            //console.log('Poniendo blanco');
            $picker.find('.wp-color-result').css({'background-color':'#aaaaaa'});
            $picker.find('.wp-picker-input-wrap input.aps-color-field').val('#aaaaaa');
            $picker.find('.wp-picker-input-wrap input.aps-color-field').trigger('color_changed');
            $picker.find('.wp-color-result').fadeIn();
        }

    });

});






///////////////////////////////////////////
// MEDIA UPLOAD IMAGES in opciones y metaboxes
///////////////////////////////////////////

(function($){

    "use strict";
	
	var aps_media = {
	
		
		bind_click: function(){
			
			$('.aps_upload_image').live('click', function(event){
				
				var $el = $(this);
				event.preventDefault();
				
				var frame = wp.media({
					title: $el.data('modal-title'),
					library:{
						type: 'image'
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $el.data('button-title'),
						// Tell the button not to close the modal, since we're
						// going to refresh the page when the image is selected.
						close: true
					}
				});
				
				frame.on('select', function(){
					var attachment = frame.state().get('selection').first();
					$el.closest('.aps-select-image').find('input').val(attachment.id);
					
					$.post(ajaxurl,{
						'action': 'aps_get_image_src',
						'size':	'medium',
						'image_id': attachment.id
					}, function(response){
						//console.log(response);
						if (response==0){
							console.log('ERROR aps_get_image_src');
						} else {
							var $parent = $el.closest('.aps-select-image');
							$parent.find('img').attr('src',response);
							$parent.find('.aps-preview-image').slideDown(400);
						}
					});
				});
				
				frame.open();
				
				return false;
			});
			
		},
		
		bind_remove: function(){
			$('.aps_remove_image').live('click', function(event){
				
				var $el = $(this);
				event.preventDefault();
				var $parent = $el.closest('.aps-select-image');
				
				$parent.find('input').val('');
				$parent.find('.aps-preview-image').slideUp(400,function(){
					$parent.find('img').attr('src','');
				});
			});
		}
			
	};
	
	
	
	$(function(){
		aps_media.bind_click();
		aps_media.bind_remove();
	});
	
	
})(jQuery);




///////////////////////////////////////////
// GALLERY IN METABOXES
///////////////////////////////////////////


(function($){

    "use strict";

	$(window).load(function(){
		
		wp.media.EditApsGallery = {
			
			frame: function() {
				if ( this._frame )
					return this._frame;
				
				var selection = this.select();
				
				this._frame = wp.media({
					displaySettings:    false,
					id:                 'editapsgallery-frame',
					title:              'apsgallery',
					filterable:         'uploaded',
					frame:              'post',
					state:              'gallery-edit',
					library:            { type : 'image' },
					multiple:           true,
					editing:            true,
					selection:          selection
				});
				
				this._frame.on( 'update',
					function() {
						var controller = wp.media.EditApsGallery._frame.states.get('gallery-edit');
						var library = controller.get('library');
						
						var ids = library.pluck('id');

						//console.log(ids);

						$('#gallery_images').val( ids.join(',') );

						// update the galllery_preview
						aps_gallery_ajax_preview();

						return false;
					});

				return this._frame;
			},
			
			init: function() {
				
				$('.aps-upload-gallery').click( function(e){
					//console.log(this);
					e.preventDefault();
					wp.media.EditApsGallery.frame().open();
				});
			},
			
			select: function(){
				var gallery_ids = $('#gallery_images').val(),
					shortcode = wp.shortcode.next('gallery', '[gallery ids="'+gallery_ids+'"]'),
					defaultPostId = wp.media.gallery.defaults.id,
					attachments, selection;
				
				if (!shortcode)
					return;
				
				// Ignore the rest of the match object.
				shortcode = shortcode.shortcode;
				//console.log(shortcode);

				if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
					shortcode.set( 'id', defaultPostId );

				attachments = wp.media.gallery.attachments( shortcode );
				selection = new wp.media.model.Selection( attachments.models, {
					props:    attachments.props.toJSON(),
					multiple: true
				});

				selection.gallery = attachments.gallery;

				// Fetch the query's attachments, and then break ties from the
				// query to allow for sorting.
				selection.more().done( function() {
					// Break ties with the query.
					selection.props.set({ query: false });
					selection.unmirror();
					selection.props.unset('orderby');
				});

				//console.log(selection);
				return selection;
				
			}
			
		}
		
		aps_gallery_ajax_preview();
		$( wp.media.EditApsGallery.init );
		
	});
	
	
	var aps_gallery_ajax_preview = function() {

		var $gallery = $('#gallery_images');
		if( $gallery.length==0 ) return;
		
		var ids = '';
		ids = $gallery.val();
		
		$.ajax({
			type: 'post',
			url: locals.ajax_url,
			data: {
				action: 'aps_gallery_preview',
				attachments_ids: ids
			},
			beforeSend: function() {
				
			},
			complete: function() {
				
			},
			success: function( response ) {
				var result = JSON.parse(response);
				if (result.success){
					$('#aps-upload-gallery')
						.parent().find('ul').html(result.output);
				}
			}
		});
		
	};
	
	
})(jQuery);


///////////////////////////////////////////
// METABOXES
///////////////////////////////////////////

(function($){


	var metaboxes = {
		
		bind_date_picker : function()
		{
			$('.datepicker').datepicker({
				dateFormat: 'dd-mm-yy', 
				dayNames: ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'],
				dayNamesShort:['Lun','Mar','Mie','Jue','View','Sab','Dom']	
			});
			
			//Al cambiar la fecha se almacenan los segundos en el campo oculto id_sec
			$('.datepicker').on('change',function()
			{
				var id_sec = $(this).attr('name')+'_sec';
				//console.log('Datepicker changed '+id_sec);
				var campos = $(this).val();
				campos = campos.split('-');
				//console.log(campos);
				//console.log(campos.length);
				
				if (campos.length>1) campos[1] -= 1; //Corregir el mes, el primero es el 0
				
				if (campos.length==1){
					var d = Date.UTC(campos[0],01,01);
					var fecha = new Date(campos[0]);
				}else if (campos.length==2){
					var d = Date.UTC(campos[1],campos[0],01);
					var fecha = new Date(campos[1], campos[0]);
				}else if (campos.length==3){
					var d = Date.UTC(campos[2],campos[1],campos[0]);
					var fecha = new Date(campos[2], campos[1], campos[0]);
				}
				//console.log(fecha);
				//console.log(fecha.valueOf());
				$('#'+id_sec).val(d/1000);
			});	
		},
		
		bind_map : function()
		{
			$('.map_canvas').each(function()
			{
				//console.log(this);
				var $mapcanvas = $(this);
				var $marker = undefined;
				var $input_coord = $mapcanvas.closest('.postbox').find('input.coordenadas');
				var $input_direccion = $mapcanvas.closest('.postbox').find('input.direccion');
				var $input_zoom = $mapcanvas.closest('.postbox').find('input.zoom');
				
				//Si existen las coordenadas guardadas creo el marker
				var coordenadas = $input_coord.val();
				
				//Tenqo que comprobar si existe esta variable para que no me de problemas
				if (coordenadas!=undefined && coordenadas.length>1){
				  //console.log(coordenadas);
				  var ll = coordenadas.split(',');
				  var latLng = new google.maps.LatLng(ll[1], ll[0]);
				  //console.log(latLng);
				  //$mapcanvas.gmap({'center': latLng});
				  //map_move(latLng);
				  map_move(latLng, $input_zoom.val());
				   
				  //marker_crear(latLng);//Me cambia la direccion
				  
				  $mapcanvas
					  .gmap('addMarker',{
						'position': latLng,
						'draggable': true,
						'bounds': false
						}, function(map, marker){
						  	$marker = marker;
						  	//Para que no me cambie la direccion almacenada
						  	//marker_update_inputs();
						})
					  .dragend(function(event, marker){
					  	marker_update_inputs();
					  })
					  .click(function(event, marker){
						marker_update_inputs();
					  });
				}
				
				
				//Para cuando se haga click se crea un nuevo marker
				$mapcanvas.gmap().bind('init', function(event, map)
				{
				 $(map).click(function(event){
					//console.log('Map clicked');
					if($marker===undefined){
						marker_crear(event.latLng);
					} else {
						//console.log('Mover el marker');
						marker_mover(event.latLng);
					}
				 })
				 .addEventListener('zoom_changed', function(event){
					 $input_zoom.val(this.zoom);
				 });
				 
				 
				 
				});//$mapcanvas				
				
				
				function marker_crear(latLng){
				  $mapcanvas
					  .gmap('addMarker',{
						'position': latLng,
						'draggable': true,
						'bounds': false  
						}, function(map, marker){
						  	//console.log('Marker created');
						  	//console.log(marker);
						  	$marker = marker;
						  	marker_update_inputs();
						})
					  .dragend(function(event, marker){
					  	//console.log('marker dragend');
					  	//console.log($marker);
					  	marker_update_inputs();
					  })
					  .click(function(event, marker){
						//console.log('marker click');
						//console.log($marker);
						marker_update_inputs();
					  });
				}
				
				function marker_mover(latLng){
					//console.log(latLng);
					$marker.setPosition(latLng);
					marker_update_inputs();
					//Centra el mapa en el marker
					//map_move(latLng); //LO HE ANULADO, HACE UN EFECTO RARO
				}
				
				function map_move(latLng, zoom){
					//console.log('Moviendo el mapa');
					//console.log(latLng);
					//$mapcanvas.gmap('center',latLng);
					//$mapcanvas.gmap({'center': latLng});
					//$mapcanvas.gmap('get','map').setOptions({'center':latLng});
			
					var map = $mapcanvas.gmap('get','map');
					map.panTo(latLng);  		
					var z = parseInt(zoom);
					if (z%1 != 0) z=10;
					map.setZoom( z );
					//$mapcanvas.gmap('zoom',14);
				}
				
				
				function marker_update_inputs(){
					
					//Escribe las coordenadas
					//console.log(marker.getPosition());
					var location = $marker.getPosition();
					var ll = location.lng().toFixed(6) + ',' + location.lat().toFixed(6) + ',0.000000';
					//console.log(position);	  	
					$input_coord.val(ll);
					
					//Escribe la dirección
					$mapcanvas.gmap('search', {'location': location}, function(results, status){
						if (status==='OK'){
							var address = results[0]['formatted_address'];
							//console.log(address);
							$input_direccion.val(address);
						} else {
							$input_direccion.val('');
						}
					});
				
				}
				
				//Para refrescar el mapa cuando se abre el metabox
				$mapcanvas.closest('.postbox').click(function(){
					$mapcanvas.gmap('refresh');	  
				});			

				
			});//each
				
				
		},//bind_map


        bind_chosen_select : function()
        {
            if ( $.isFunction($.fn.chosen) ) {
                $('.chosen-select').chosen('.chosen-select').change(function(e){
                    //console.log('Chosen has changed ');
                    $('.chosen-select').trigger("chosen:updated");
                });
            }

        }
		
	};//metaboxes


	$(function(){
		metaboxes.bind_date_picker();
		metaboxes.bind_map();
        metaboxes.bind_chosen_select();
	});
	
	
})(jQuery);










///////////////////////////////////////////
// PANEL DE OPCIONES
///////////////////////////////////////////

// Panel de opciones. Opciones se abren / cierran en funcion de algun select


(function($){

    "use strict";

	var panel_opciones = {
		
		//Opciones abren / cierran en funcion de select
		bind_change_select: function(){
			$('.aps-section-field select').live('change',function(){
				var id = $(this).attr('id');
				var val = $(this).val();
				panel_opciones.comprobar_required(id,val);
			});
		},
		
		bind_change_tab: function(){
			$('.aps-section-field input[type="radio"]').live('change',function(){
				var id = $(this).data('id');
				var val = $(this).val();
				panel_opciones.comprobar_required(id,val);
				
				//Trigger selet para que oculte dependencias
				//pero solo de los que estan dentro del tab
				//que son los que estan visibles
				var required_tab = id+'::'+val;
				$('.aps-section-field select').each(function(){
					var $required = $(this).closest('.aps-section-field').find('.required-tab');
					if ($required && $required.val()==required_tab)
					{
						$(this).trigger('change');
					}
						
				});
			});
		},
		
		
		comprobar_required: function(id, val)
		{
			
			$('.aps-field-required').each(function(index,el)
			{
				var el_required = $(el).val();
				var res = el_required.split("::");

				//Coincide la id y por tanto
				//esta condicionado
				if (res[0] == id)
				{
					//Mi campo
					var $parent = $(el).closest('.aps-section-field');
					
					//La fila de la table en options
					var $row = $parent.closest('tr');
					
					//Para metaboxes es otros
					if ($row.length == 0)
						$row = $parent;
						
					//Por si uso titulos que no tienen nada les asigni espacios
					if (res[1] === '{true}' && val!== '' && val!== ' ' && val!== '  ' && val!== '   '){
						$row.show();
					}
					else
					{
						var valores = res[1].split(",");
						var coincide = false;
						$.each(valores,function(index,item){
							if (item == val)
								coincide = true;
						});
						
						if (coincide==true){
							$row.show();
						}
						else{
							$row.hide();	
						}
					}
				}
			});
				
		},
		
		
		bind_radio_image: function(){
			$('.input-radio-image').live('change', function(event){
				//console.log(this);
				$(this).closest('.aps-section-field')
					   .find('.aps-radio-image')
					   .removeClass('aps-radio-selected');
			    $(this).closest('.aps-radio-image')
			    	   .addClass('aps-radio-selected');
			});
		},
		
		bind_option_tab: function(){
			$('.aps-option-tab').live('click', function(event){
				//console.log(this);
				$(this).closest('.aps-section-field')
					   .find('label').removeClass('selected');
			    $(this).closest('.aps-option-tab').addClass('selected');
			});
		},
		
		
		bind_change_layout: function(){
			var self = this;
			$('.aps-section-field.aps-layout input[type="radio"]').live('change',function(){
				var id = $(this).data('id');
				var val = $(this).val();
				//console.log('Radio changed to :'+id+' = '+val);
				self.update_layout();
			});
		},
		
		//En custom post aps_layout
		update_layout: function(){
			//console.log('Updating-layout');
			var compose = '';
			var first = true;
			
			//Busco los layout radio solamente
			$('.aps-section-field.aps-layout input[type="radio"]:checked').each(function(index,item){
				var id = $(item).data('id');
				var val = $(item).val();
				if (first)
					compose += val;
				else 
					compose += ','+val;
				first = false;
			});
			//console.log(compose);
			$.post(ajaxurl,{
				'action': 'aps_dame_layout',
				'compose': compose
			},function(response){
				if (response==0){
					console.log('ERROR aps_dame_layout');
				} else {
					$('.aps-layout-composition').html(response);
				}
			});
		},
		
		bind_page_change_select_layout: function(){
			$('.type-select_layout select').live('change',function(){

                var $self = $(this);

				var id = $(this).attr('id');
				var val = $(this).val();
				//console.log('Changed to layout: '+id+' = '+val);
				
				var layout_id = val.replace('id-','');
				//console.log('Layout '+layout_id);
				
				$.post(ajaxurl,{
					//'action': 'aps_dame_layout_from_id',
                    'action': 'aps_dame_layout_y_responsive_from_id',
					'id': layout_id
				},function(response){
					if (response==0){
						console.log('ERROR aps_dame_layout_from_id');
					} else {
						$self.parent().parent().find('.aps-layout-selected').html(response);
					}
				});
			});
		},
		
		bind_map_style: function(){
			var $map = $('#map_canvas_style').first();
			if ($map.length==0) return;
			
			//css
			$map.css({
				'width':'100%',
				'height':'400px',
				'display':'block',
				'background-color':'#ddd'
			});

			//Crear mapa directamente
			//ver ejemplo de fmangado jquery.elapmap.js
			var MY_MAPTYPE_ID = 'mymap';
			var mapOptions = {
				zoom: 9,
				center: new google.maps.LatLng(40.730608, -74.025879),
				disableDefaultUI: true,
				mapTypeControlOptions: { mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID] },
				mapTypeId: MY_MAPTYPE_ID
			};
			
			var map = new google.maps.Map($map[0], mapOptions);
			
			//Estilar el mapa
			var styledMapOptions = {name: 'Custom Style'};
			var val = $('#map_style').val();
			var customMapType = new google.maps.StyledMapType( mapstyles[val], styledMapOptions );
			map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
			
			//select change
			$('#map_style').change(function(ev){
				//console.log('Map changed');
				var value = $(this).val();
				var customMapType = new google.maps.StyledMapType( mapstyles[value], styledMapOptions );
				map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
			});


            //Añadirle 4 tipo de iconos de ejemplo

            var coord_ejemplo = new google.maps.LatLng(40.716558,-73.990173);
            var puntos = [];
            puntos['icon-map-square-70'] = {
                coord: coord_ejemplo,
                img: '<img src="http://placehold.it/70x70/&text=Image">',
                anchor: RichMarkerPosition.BOTTOM_LEFT
            };
            puntos['icon-map-square-40'] = {
                coord: coord_ejemplo,
                img: '',
                anchor: RichMarkerPosition.BOTTOM_LEFT
            };
            puntos['icon-map-circle-70'] = {
                coord: coord_ejemplo,
                img: '<img src="http://placehold.it/70x70&text=Image">',
                anchor: RichMarkerPosition.BOTTOM_CENTER
            };
            puntos['icon-map-circle-40'] = {
                coord: coord_ejemplo,
                img: '',
                anchor: RichMarkerPosition.BOTTOM_CENTER
            };

            var richmarker = null;

            //Cuando cambia el tipo de icono
            $('#map_icons_type').on('change', function(){

                var val = $(this).val();
                var punto = puntos[val];

                if (richmarker!=null) {
                    richmarker.setMap(null);
                }
                var color = $('.wp-color-picker').first().val();

                richmarker = new RichMarker({
                    position: punto.coord,
                    map: map,
                    draggable: false,
                    content: '<span class="mapa-icono ' + val + '" style="border-color:'+color+';"><div class="inner">' + punto.img + '</div></span>',
                    shadow: 'none',
                    anchor: punto.anchor
                });

            });


            //Cuando cambia el color del icono
            $('.wp-color-picker').on('color_changed',function(){
                var that = this;
                //Para que de tiempo a actualizarse el input
                setTimeout(function(){
                    var color = $(that).val();
                    $('.mapa-icono').css('border-color',color);

                }, 50);
            });

            //Espero a que se haya cargado el mapa para cargar el color inicial
            setTimeout(function()
            {
                $('#map_icons_type').trigger('change');
                //$('.wp-color-picker').trigger('color_changed');
            }, 400);

		}
		
	};

	$(function(){
		panel_opciones.bind_change_select();
		//console.log('TRIGGERING SELECT');
		$('.aps-section-field select').trigger('change');
		
		panel_opciones.bind_radio_image();
		panel_opciones.bind_option_tab();
		panel_opciones.bind_change_tab();
		//console.log('TRIGGERING TABS');
		$('.aps-section-field input[type="radio"]:checked').trigger('change');
		
		panel_opciones.bind_change_layout();
		panel_opciones.bind_page_change_select_layout();
		$('.type-select_layout select').trigger('change');
		
		panel_opciones.bind_map_style();
		
	});


})(jQuery);






///////////////////////////////////////////
// ROUGH THEME STYLES
///////////////////////////////////////////

// Panel de opciones. Opciones se abren / cierran en funcion de algun select


(function($){

    "use strict";

	var panel_style_theme = {
	
		bind_colors: function(){
			
			/* Ya no me hace falta
			$('.wp-picker-container a').click(function(){ 
				var that = $(this).closest('.wp-picker-container').find('.wp-color-picker');
				//panel_style_theme.change_css_value(that);
			});
			*/
			
			$('.wp-color-picker').on('color_changed',function(){
				
				var that = this;
				//Para que de tiempo a actualizarse el input
				setTimeout(function(){
					panel_style_theme.change_css_value(that);
				}, 50);
				
			});
			
			
		},

        bind_change_font_size: function(){

            $('.aps-section-field.type-select_font_size select').on('change', function(){
                //console.log('Font size changed');
                panel_style_theme.change_css_value(this);
            });
        },

        bind_change_select_field: function(){
            $('.aps-section-field.type-select select').on('change', function(){
                panel_style_theme.change_css_value(this);
            });
        },
		
		change_css_value: function(input){
			
			var self = input;

            //console.log(input.id);
            var $self = $(self);

			var val = $self.val();
			//console.log(val);

            //Aplicar desde dos campos
			var data_target = $self.closest('.aps-section-field').data('target');
			var data_css = $self.closest('.aps-section-field').data('css');
			if (undefined!=data_target && undefined!=data_css)
			{
				//console.log(data_target + ' . ' + data_css);
				var items = data_target.split(",");
				$.each(items,function(index,item){
					$(item).css(data_css,val);
				});
			}

            //Aplicar pero desde un array de datos
            var data_target_css = $self.closest('.aps-section-field').data('target-css');
            if ( data_target_css != undefined ) {
                //console.log(data_target_css);
                for (var data_target in data_target_css){
                    var data_css = data_target_css[data_target];
                    var items = data_target.split(",");
                    $.each(items,function(index,item){
                        $(item).css(data_css,val);
                    });
                }
            }

            //Para cambiar todos a la vez
            //var campo = input.id;
            //var campos = ['header_top_','header_bottom_','left_','main_','right_','footer_','socket_'];


		},
		
		//EN preparacion
		bind_click_save_scheme: function(){
			$('#save-scheme').click(function(event){
				event.preventDefault();
				//console.log('Saving-scheme');
				
				//Como array
				var codigo = new Array();
				
				//Como objecto
				var codigo_st = {};

                //Las tres fuentes de google
                $('.type-select_font_real').each(function(){
                    var id = $(this).find('.dd-container').attr('id');
                    var val = $(this).find('input.dd-selected-value').val();
                    codigo.push( id+':'+val);
                    codigo_st[id] = val;
                });

				//Todas las opciones,colores,fuentes,patrones
				$('input.aps-color-field,select.aps-option-field').each(function(index,item){
					//console.log($(item).val());
					//codigo.push( $(item).attr('id')+':'+$(item).val() );
					var id = $(item).attr('id');
					var val = $(item).val();
					codigo.push( id+':'+val);
					codigo_st[id] = val;
				});


				
				//Los patrones
				
				//console.log(codigo);
				console.log(codigo_st);
				console.log(JSON.stringify(codigo_st));
			});
		},
		
		bind_google_fonts: function(){

			$('.type-select_font select').on('change',function(e){
				//console.log('Font changed to : ' + $(this).val());
				
				var value = $(this).val();
				
				//Los titulos no
				if (value=='websafe' || value=='google')
					return;
				
				var parent = $(this).parents('.aps-section-field:eq(0)');
				var html = "";
				
				//Fuente web
				if (value.indexOf("-websafe") != -1)
				{
					value = value.replace(/-websafe/g, "", value);
					value = value.replace(/-/g, "", value);
					html += '<style type="text/css">#rough-template .web_font_'+this.id+'{font-family:'+value+';}</style>';
				}
				//Fuente google
				else
				{
					var font_val = value.replace(/ /g, "+", value);
					html = '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+font_val+'">';
					html += '<style type="text/css">#rough-template .web_font_'+this.id+'{font-family:'+value+';}</style>';
				}
				
				var insert = $('<div class="my_font_'+this.id+'">'+html+'</div>');
				insert.appendTo(parent);
			});
		},
		
		//Para las fonts
		bind_ddslick: function(){

			$('.ddslick').each(function(){
               //Genero un campo oculto para guardar el valor del ddslick
                //y que wordpress pueda recogerlo al guardarlo

                var name = $(this).attr('name');
                var value = $(this).val();
                var this_id = this.id;

                //El input oculto
                var $input = $('<input type="hidden" name="'+name+'" value="'+value+'">');
                $(this).before($input);

                var parent = $(this).parents('.aps-section-field:eq(0)');

                //Ahora genero el ddslick y le asigno el valor inicial
                $(this).ddslick({
                   selectText: value,
                   onSelected: function(data)
                   {
                       var value = data.selectedData.value;
                       //Para guardar el valor
                       $input.val(value);

                       var html = "";
                       //Fuente web
                       if (value.indexOf("-websafe") != -1)
                       {
                           value = value.replace(/-websafe/g, "", value);
                           value = value.replace(/-/g, "", value);
                           html += '<style type="text/css">#rough-template .web_font_'+this_id+'{font-family:'+value+';}</style>';
                       }
                       //Fuente google
                       else
                       {
                           var font_val = value.replace(/ /g, "+", value);
                           //html = '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='+font_val+'">';
                           html += '<style type="text/css">#rough-template .web_font_'+this_id+'{font-family:'+value+';}</style>';
                       }

                       var insert = $('<div class="my_font_'+this_id+'">'+html+'</div>');
                       insert.appendTo(parent);
                   }
                });
            });

            /*$('.ddslick').ddslick({
				onSelected: function(data){
				
					console.log(data);
					console.log(data.selectedData.value);
					
					var value = data.selectedData.value;
					
					//Los titulos no
					if (value=='websafe' || value=='google')
						return;
					
					//console.log(this);
					console.log(data.selectedItem);
					var parent = $(data.selectedItem).parents('.aps-section-field:eq(0)');
					console.log(parent);
					
					//Todavia no esta listo
				}
			});*/
			
		},



		bind_back_patterns: function(){
		
			$('.type-pattern select').on('change', function(){
				var value = $(this).val();
				
				//variable desde php
				if (undefined == url_patterns) return;
				var pattern = url_patterns+value+'.png';

				if (value=='') pattern = '';
				if (value=='none') pattern = '';
				if (value=='none1') pattern = '';
				if (value=='none2') pattern = '';
				//console.log(pattern);
				
				var css = 'background-image: url('+pattern+'); background-attachment: scroll; background-position: 0% 0%; background-repeat: repeat repeat;';
				
				var data_target = $(this).closest('.aps-section-field').data('target');
				
				var items = data_target.split(",");
				$.each(items,function(index,item){
					$(item).css({
						'background-image': 'url('+pattern+')',
						//'background-attachment': 'fixed', //scroll,fixed
						'background-position':'0% 0%',
						'background-repeat':'repeat repeat'
					});
				});
			});
			
			$('.type-pattern_scroll select').on('change', function(){
				panel_style_theme.change_css_value(this);
			});
		},
		
		//Al pulsar radio-button de los esquemas guardados
		bind_change_scheme_color: function(){
		
			$('input[name=scheme_colors]').change(function(){
		
				$('label.scheme-option').removeClass('selected');
				$(this).closest('label.scheme-option').addClass('selected');
		
				//return;
		
				//Cojo los datos de los parametros
				var json = $(this).data('json');
				console.log(json);

                //return;

				//Es una estructura con toda la configuracion
				for (var name in json){
					if(json.hasOwnProperty(name))
					{
						//Le pongo el valor
						var $name = $('#'+name);
						$name.val(json[name]);
						
						//Actualizo el color iris
						if (name.indexOf("color") != -1){
							$name.iris('color',json[name]);
						}
					}
				}

                //Para las google fonts tengo que cambiarlo en el ddslick
                //y en el campo oculto que me sirve para el wordpress, que
                //se actuaiza solo al cambiar el ddslick
                var ids = ['heading_font','body_font','blog_title_font'];
                $.each(ids, function(index, name){
                   //console.log('Buscando ddslick '+name+ ' = '+json[name]);
                   if ( json[name] != undefined ) {
                        //Cambiar el ddslick
                        //console.log('Cambiando el ddslick '+name);
                        var $ddslick = $('#'+name);

                        //Buscar el numero de la opcion
                        var dd_options = $ddslick.find('.dd-option-value');
                        var index_to_select = 0;
                        for (var i=0; i<dd_options.length; i++){
                            //console.log(dd_options[i]);
                            if ($(dd_options[i]).val()==json[name]){
                                index_to_select = i;
                                //console.log('Encontrado en el index '+index_to_select);
                                break;
                            }
                        }

                        //Selecciono el ddslick
                        $ddslick.ddslick('select', {index: index_to_select });
                        //$ddslick.ddslick('select', {index: 1 });
                   } ;
                });
				
				//Actualizar template
				panel_style_theme.update_visible_controls();
				
			});	
		},



		//Boton abrir scheme colors popup
		bind_open_popup_schemes: function(){
			
			$('#open-popup-schemes').click(function(event){
				event.preventDefault();
				$('#popup-schemes').slideToggle(200);
			})	
		},

		update_visible_controls: function(){
			$('.type-select_font select').trigger('change');
            $('.type-select_font_size select').trigger('change');
			$('.type-pattern select').trigger('change');
			$('.type-pattern_scroll select').trigger('change');
			$('.wp-color-picker').trigger('color_changed');
			$('.aps-tab-radio:checked').trigger('change');
            $('.aps-section-field select').trigger('change'); //widget margin
		},

		trigger_all_options: function(){
			$('.rough-tpl-wrap').fadeOut(10);
			
			//console.log('TRIGGER COLORS');
			//$('.wp-color-picker').trigger('color_changed');
			
			//console.log('TRIGGER FONT');
			//$('.type-select_font select').trigger('change');
			
			//console.log('TRIGGER SELECT PATTERN');
			//$('.type-pattern select').trigger('change');
			
			//console.log('TRIGGER SELECT PATTERN SCROLL');
			//$('.type-pattern_scroll select').trigger('change');
			
			//console.log('TRIGGER TABS');
			//$('.aps-tab-radio:checked').trigger('change');
			//console.log('END TRIGGER TABS');
			
			panel_style_theme.update_visible_controls();
			
			$('.rough-tpl-wrap').fadeIn(300);
		}
	
	};

	$(function(){
		
		//Si existe el panel
		if ($('.rough-tpl-wrap').length)
		{
			panel_style_theme.bind_colors();
            panel_style_theme.bind_change_font_size();
            panel_style_theme.bind_change_select_field();
			panel_style_theme.bind_google_fonts();
			panel_style_theme.bind_ddslick();
			panel_style_theme.bind_back_patterns();
			
			//Actualizar template
			panel_style_theme.trigger_all_options();
			
			//Para saber el codigo del esquema
			panel_style_theme.bind_click_save_scheme();
			
			//Para cambiar el esquema
			panel_style_theme.bind_change_scheme_color();
			panel_style_theme.bind_open_popup_schemes();
		};
		
	});
	

})(jQuery);













///////////////////////////////////////////
// Page templates
///////////////////////////////////////////


(function($){

    $(function(){

        $('#page_template').change(function(){

            //Estoy usando un mismo shortcode para crear los 4 tipos de page templates
            //por lo que tengo que seleccionar y ocultar sobre la marcha al
            //seleccionar un determinado tipo de template

            var val = $(this).val();

            var select_post_type = $('#page_extra_content select[name="content_post_type"]');
            var select_use_gallery = $('#page_extra_content select[name="use_gallery_of_post"]');

            var extra_content = false;
            var post_type = false;
            var use_gallery = false;

            if ( val=='template-list-posts.php' )
            {
                extra_content = true;
                post_type = 'post';
                use_gallery = 'no';
            }
            else if ( val=='template-list-projects.php' )
            {
                extra_content = true;
                post_type = 'aps_project';
                use_gallery = 'no';
            }
            else if ( val=='template-gallery-posts.php' )
            {
                extra_content = true;
                post_type = 'post';
                use_gallery = 'yes';
            }
            else if ( val=='template-gallery-projects.php' )
            {
                extra_content = true;
                post_type = 'aps_project';
                use_gallery = 'yes';
            }
            else if ( val=='template-blog.php' )
            {
                extra_content = false;
                $('#page_extra_content_below').show();
            }

            if ( val=='template-blank.php')
            {
                extra_content = false;
                $('#page_extra_content_below').hide();
                $('#box_layout_page').hide();
                $('#box_page_options').hide();
                $('#postimagediv').hide();
            }
            else
            {
                $('#box_layout_page').show();
                $('#box_page_options').show();
                $('#postimagediv').show();
            }

            if (extra_content)
            {
                $('#page_extra_content').show();
                $('#page_extra_content_below').hide();

                //Seleccionar post a post_type
                select_post_type.val(post_type);
                select_post_type.trigger('change');
                select_post_type.parent().hide();

                //Selelcionar galeria o featured image
                select_use_gallery.val(use_gallery);
                select_use_gallery.trigger('change');
                select_use_gallery.parent().hide();

                //Borrar el titulo este que viene de page-metabox-data.php en aps-pagebuilder
                $('.title-featured-image-gallery').hide();
            }
            else
            {
                //Cerrar el container
                $('#page_extra_content').hide();
            }

            if (val == 'default') {
                $('#page_extra_content').hide();
                $('#page_extra_content_below').hide();
                $('#box_layout_page').show();
                $('#box_page_options').show();
                $('#postimagediv').show();
            }

        }).trigger('change');


    });

})(jQuery);



///////////////////////////////////////////
// Widgets
///////////////////////////////////////////

jQuery(document).ready(function($){

    //Comprobar los widget switch, p.ej para categorias all only
    function widgetShowHideFromSwitcher( )
    {

        $('.aps-widget-switch input:checked').each(function(){
            var val = $(this).val();
            $(this).closest('.aps-widget-switch-wrap').find('.aps-widget-switch-option').hide();
            $(this).closest('.aps-widget-switch-wrap').find(".aps-widget-switch-option[data-show='" + val + "']").show();
        });
    }

    //Mantener vivos
    $('#wpbody').on('click','.aps-widget-switch input', function(){
        widgetShowHideFromSwitcher();
    });

    //Al arrancar
    widgetShowHideFromSwitcher();

    //When widget save
    $(document).ajaxSuccess(function(e, xhr, settings){

        if ( settings.data.search( 'action=save-widget' ) != -1 ) {

            widgetShowHideFromSwitcher();
        };
    });

});


///////////////////////////////////////////
// Mega menu
///////////////////////////////////////////


jQuery(document).ready(function($){

    var timeout_recalcular = false;

    var aps_mega_menu = {

        bind_click: function()
        {
            $('#menu-to-edit').on('change', '.menu-item-aps_megamenu input', function(){

                console.log('Clicked');

                var $checkbox = $(this);
                var parent = $(this).parents('.menu-item:eq(0)');

                //console.log($checkbox.is(':checked'));

                if($checkbox.is(':checked'))
                {
                    parent.addClass('megamenu_active');
                }
                else
                {
                    parent.removeClass('megamenu_active');
                }

                aps_mega_menu.recalcular_megamenu();
            });
        },

        recalcular_megamenu: function()
        {

            //console.log('Recalculando todos los menu-item');

            var $menuItems = $('#menu-to-edit .menu-item');

            $menuItems.each(function(i)
            {
                var $item = $(this),
                    $itemCheckbox = $('.menu-item-aps_megamenu input', this);

                if(!$item.is('.menu-item-depth-0'))
                {
                    //Busco el menu-item superior
                    var $checkSuper = $menuItems.filter(':eq('+(i-1)+')');
                    if($checkSuper.is('.megamenu_active'))
                    {
                        $item.addClass('megamenu_active');
                        $itemCheckbox.attr('checked','checked');
                    }
                    else
                    {
                        $item.removeClass('megamenu_active');
                        $itemCheckbox.attr('checked','');
                    }
                }
            });



            //$('.megamenu_active .menu-item-depth-1 .description-title-attr').addClass('description-wide');


        },

        bind_menuitembar: function()
        {
            $(document).on('mouseup', '.menu-item-bar', function(event, ui)
            {
                if(!$(event.target).is('a'))
                {
                    clearTimeout(aps_mega_menu.timeout_recalcular);
                    aps_mega_menu.timeout_recalcular = setTimeout(aps_mega_menu.recalcular_megamenu, 400);
                }
            });
        }

    };

    aps_mega_menu.bind_click();
    aps_mega_menu.bind_menuitembar();
    aps_mega_menu.recalcular_megamenu();
    setTimeout(function(){
        var $items = $('.menu-item-depth-0 .menu-item-aps_megamenu input');

        //console.log('Trigger mega menu');
        //console.log($items);

        $items.trigger('change');
    }, 500);


});

///////////////////////////////////////////
// Cambiar posicion select layout a page attributes
///////////////////////////////////////////
/*
(function($){

	$(function(){
	
		$boxlayout = $('#box_layout_page');
		$pageparent = $('#pageparentdiv');
		//console.log($pageparent.length+' / '+$boxlayout.length);
		
		if ($pageparent.length && $boxlayout.length){
			//console.log('Moviendo layout');
			$layout = $boxlayout.find('.inside');
			$pageparent.find('.inside').prepend($layout);
			$boxlayout.remove();
			$('label[for=box_layout_page-hide]').remove();
		}
	
	});

})(jQuery);
*/




/******************************/
/*METABOXES IDIOMAS TABS */
/******************************/
/*
(function($){

	$(document).ready(function () 
	{
		$('.aps-idiomas-tab').click(function(e){
			 e.preventDefault();
			 var $box = $(this).closest('.aps-idiomas-box'); 
			 $box.find('.aps-idiomas-tab').removeClass('selected');
			 $(this).addClass('selected');
			 
			 var ln = $(this).data('idioma');
			 console.log(ln);
			 $box.find('.aps-idiomas-content').hide();
			 $box.find('.aps-idiomas-content.content'+ln).show();
			 
		});
		
		//Arranque
		$('.aps-idiomas-tab.selected').trigger('click');
		
	});

})(jQuery);
*/




// richmarker-compiled.js
// http://google-maps-utility-library-v3.googlecode.com/svn/trunk/richmarker/docs/reference.html

(function(){var b=true,f=false;function g(a){var c=a||{};this.d=this.c=f;if(a.visible==undefined)a.visible=b;if(a.shadow==undefined)a.shadow="7px -3px 5px rgba(88,88,88,0.7)";if(a.anchor==undefined)a.anchor=i.BOTTOM;this.setValues(c)}g.prototype=new google.maps.OverlayView;window.RichMarker=g;g.prototype.getVisible=function(){return this.get("visible")};g.prototype.getVisible=g.prototype.getVisible;g.prototype.setVisible=function(a){this.set("visible",a)};g.prototype.setVisible=g.prototype.setVisible;
    g.prototype.s=function(){if(this.c){this.a.style.display=this.getVisible()?"":"none";this.draw()}};g.prototype.visible_changed=g.prototype.s;g.prototype.setFlat=function(a){this.set("flat",!!a)};g.prototype.setFlat=g.prototype.setFlat;g.prototype.getFlat=function(){return this.get("flat")};g.prototype.getFlat=g.prototype.getFlat;g.prototype.p=function(){return this.get("width")};g.prototype.getWidth=g.prototype.p;g.prototype.o=function(){return this.get("height")};g.prototype.getHeight=g.prototype.o;
    g.prototype.setShadow=function(a){this.set("shadow",a);this.g()};g.prototype.setShadow=g.prototype.setShadow;g.prototype.getShadow=function(){return this.get("shadow")};g.prototype.getShadow=g.prototype.getShadow;g.prototype.g=function(){if(this.c)this.a.style.boxShadow=this.a.style.webkitBoxShadow=this.a.style.MozBoxShadow=this.getFlat()?"":this.getShadow()};g.prototype.flat_changed=g.prototype.g;g.prototype.setZIndex=function(a){this.set("zIndex",a)};g.prototype.setZIndex=g.prototype.setZIndex;
    g.prototype.getZIndex=function(){return this.get("zIndex")};g.prototype.getZIndex=g.prototype.getZIndex;g.prototype.t=function(){if(this.getZIndex()&&this.c)this.a.style.zIndex=this.getZIndex()};g.prototype.zIndex_changed=g.prototype.t;g.prototype.getDraggable=function(){return this.get("draggable")};g.prototype.getDraggable=g.prototype.getDraggable;g.prototype.setDraggable=function(a){this.set("draggable",!!a)};g.prototype.setDraggable=g.prototype.setDraggable;
    g.prototype.k=function(){if(this.c)this.getDraggable()?j(this,this.a):k(this)};g.prototype.draggable_changed=g.prototype.k;g.prototype.getPosition=function(){return this.get("position")};g.prototype.getPosition=g.prototype.getPosition;g.prototype.setPosition=function(a){this.set("position",a)};g.prototype.setPosition=g.prototype.setPosition;g.prototype.q=function(){this.draw()};g.prototype.position_changed=g.prototype.q;g.prototype.l=function(){return this.get("anchor")};g.prototype.getAnchor=g.prototype.l;
    g.prototype.r=function(a){this.set("anchor",a)};g.prototype.setAnchor=g.prototype.r;g.prototype.n=function(){this.draw()};g.prototype.anchor_changed=g.prototype.n;function l(a,c){var d=document.createElement("DIV");d.innerHTML=c;if(d.childNodes.length==1)return d.removeChild(d.firstChild);else{for(var e=document.createDocumentFragment();d.firstChild;)e.appendChild(d.firstChild);return e}}function m(a,c){if(c)for(var d;d=c.firstChild;)c.removeChild(d)}
    g.prototype.setContent=function(a){this.set("content",a)};g.prototype.setContent=g.prototype.setContent;g.prototype.getContent=function(){return this.get("content")};g.prototype.getContent=g.prototype.getContent;
    g.prototype.j=function(){if(this.b){m(this,this.b);var a=this.getContent();if(a){if(typeof a=="string"){a=a.replace(/^\s*([\S\s]*)\b\s*$/,"$1");a=l(this,a)}this.b.appendChild(a);var c=this;a=this.b.getElementsByTagName("IMG");for(var d=0,e;e=a[d];d++){google.maps.event.addDomListener(e,"mousedown",function(h){if(c.getDraggable()){h.preventDefault&&h.preventDefault();h.returnValue=f}});google.maps.event.addDomListener(e,"load",function(){c.draw()})}google.maps.event.trigger(this,"domready")}this.c&&
    this.draw()}};g.prototype.content_changed=g.prototype.j;function n(a,c){if(a.c){var d="";if(navigator.userAgent.indexOf("Gecko/")!==-1){if(c=="dragging")d="-moz-grabbing";if(c=="dragready")d="-moz-grab"}else if(c=="dragging"||c=="dragready")d="move";if(c=="draggable")d="pointer";if(a.a.style.cursor!=d)a.a.style.cursor=d}}
    function o(a,c){if(a.getDraggable())if(!a.d){a.d=b;var d=a.getMap();a.m=d.get("draggable");d.set("draggable",f);a.h=c.clientX;a.i=c.clientY;n(a,"dragready");a.a.style.MozUserSelect="none";a.a.style.KhtmlUserSelect="none";a.a.style.WebkitUserSelect="none";a.a.unselectable="on";a.a.onselectstart=function(){return f};p(a);google.maps.event.trigger(a,"dragstart")}}
    function q(a){if(a.getDraggable())if(a.d){a.d=f;a.getMap().set("draggable",a.m);a.h=a.i=a.m=null;a.a.style.MozUserSelect="";a.a.style.KhtmlUserSelect="";a.a.style.WebkitUserSelect="";a.a.unselectable="off";a.a.onselectstart=function(){};r(a);n(a,"draggable");google.maps.event.trigger(a,"dragend");a.draw()}}
    function s(a,c){if(!a.getDraggable()||!a.d)q(a);else{var d=a.h-c.clientX,e=a.i-c.clientY;a.h=c.clientX;a.i=c.clientY;d=parseInt(a.a.style.left,10)-d;e=parseInt(a.a.style.top,10)-e;a.a.style.left=d+"px";a.a.style.top=e+"px";var h=t(a);a.setPosition(a.getProjection().fromDivPixelToLatLng(new google.maps.Point(d-h.width,e-h.height)));n(a,"dragging");google.maps.event.trigger(a,"drag")}}function k(a){if(a.f){google.maps.event.removeListener(a.f);delete a.f}n(a,"")}
    function j(a,c){if(c){a.f=google.maps.event.addDomListener(c,"mousedown",function(d){o(a,d)});n(a,"draggable")}}function p(a){if(a.a.setCapture){a.a.setCapture(b);a.e=[google.maps.event.addDomListener(a.a,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(a.a,"mouseup",function(){q(a);a.a.releaseCapture()},b)]}else a.e=[google.maps.event.addDomListener(window,"mousemove",function(c){s(a,c)},b),google.maps.event.addDomListener(window,"mouseup",function(){q(a)},b)]}
    function r(a){if(a.e){for(var c=0,d;d=a.e[c];c++)google.maps.event.removeListener(d);a.e.length=0}}
    function t(a){var c=a.l();if(typeof c=="object")return c;var d=new google.maps.Size(0,0);if(!a.b)return d;var e=a.b.offsetWidth;a=a.b.offsetHeight;switch(c){case i.TOP:d.width=-e/2;break;case i.TOP_RIGHT:d.width=-e;break;case i.LEFT:d.height=-a/2;break;case i.MIDDLE:d.width=-e/2;d.height=-a/2;break;case i.RIGHT:d.width=-e;d.height=-a/2;break;case i.BOTTOM_LEFT:d.height=-a;break;case i.BOTTOM:d.width=-e/2;d.height=-a;break;case i.BOTTOM_RIGHT:d.width=-e;d.height=-a}return d}
    g.prototype.onAdd=function(){if(!this.a){this.a=document.createElement("DIV");this.a.style.position="absolute"}if(this.getZIndex())this.a.style.zIndex=this.getZIndex();this.a.style.display=this.getVisible()?"":"none";if(!this.b){this.b=document.createElement("DIV");this.a.appendChild(this.b);var a=this;google.maps.event.addDomListener(this.b,"click",function(){google.maps.event.trigger(a,"click")});google.maps.event.addDomListener(this.b,"mouseover",function(){google.maps.event.trigger(a,"mouseover")});
        google.maps.event.addDomListener(this.b,"mouseout",function(){google.maps.event.trigger(a,"mouseout")})}this.c=b;this.j();this.g();this.k();var c=this.getPanes();c&&c.overlayImage.appendChild(this.a);google.maps.event.trigger(this,"ready")};g.prototype.onAdd=g.prototype.onAdd;
    g.prototype.draw=function(){if(!(!this.c||this.d)){var a=this.getProjection();if(a){var c=this.get("position");a=a.fromLatLngToDivPixel(c);c=t(this);this.a.style.top=a.y+c.height+"px";this.a.style.left=a.x+c.width+"px";a=this.b.offsetHeight;c=this.b.offsetWidth;c!=this.get("width")&&this.set("width",c);a!=this.get("height")&&this.set("height",a)}}};g.prototype.draw=g.prototype.draw;g.prototype.onRemove=function(){this.a&&this.a.parentNode&&this.a.parentNode.removeChild(this.a);k(this)};
    g.prototype.onRemove=g.prototype.onRemove;var i={TOP_LEFT:1,TOP:2,TOP_RIGHT:3,LEFT:4,MIDDLE:5,RIGHT:6,BOTTOM_LEFT:7,BOTTOM:8,BOTTOM_RIGHT:9};window.RichMarkerPosition=i;
})();



//IMPORTANTE PORQUE NO SE PUEDE SELECCIONAR EL INDEX 0
//HE CAMBIADO UNA LINEA
//ddslick
//Title: Custom DropDown plugin by PC
//Documentation: http://designwithpc.com/Plugins/ddslick
//Author: PC
//Website: http://designwithpc.com
//Twitter: http://twitter.com/chaudharyp

(function ($) {

    $.fn.ddslick = function (method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exists.');
        }
    };

    var methods = {},

    //Set defauls for the control
        defaults = {
            data: [],
            keepJSONItemsOnTop: false,
            width: 260,
            height: null,
            background: "#eee",
            selectText: "",
            defaultSelectedIndex: null,
            truncateDescription: true,
            imagePosition: "left",
            showSelectedHTML: true,
            clickOffToClose: true,
            onSelected: function () { }
        },

        ddSelectHtml = '<div class="dd-select"><input class="dd-selected-value" type="hidden" /><a class="dd-selected"></a><span class="dd-pointer dd-pointer-down"></span></div>',
        ddOptionsHtml = '<ul class="dd-options"></ul>',

    //CSS for ddSlick
        ddslickCSS = '<style id="css-ddslick" type="text/css">' +
            '.dd-select{ border-radius:2px; border:solid 1px #ccc; position:relative; cursor:pointer;}' +
            '.dd-desc { color:#aaa; display:block; overflow: hidden; font-weight:normal; line-height: 1.4em; }' +
            '.dd-selected{ overflow:hidden; display:block; padding:10px; font-weight:bold;}' +
            '.dd-pointer{ width:0; height:0; position:absolute; right:10px; top:50%; margin-top:-3px;}' +
            '.dd-pointer-down{ border:solid 5px transparent; border-top:solid 5px #000; }' +
            '.dd-pointer-up{border:solid 5px transparent !important; border-bottom:solid 5px #000 !important; margin-top:-8px;}' +
            '.dd-options{ border:solid 1px #ccc; border-top:none; list-style:none; box-shadow:0px 1px 5px #ddd; display:none; position:absolute; z-index:2000; margin:0; padding:0;background:#fff; overflow:auto;}' +
            '.dd-option{ padding:10px; display:block; border-bottom:solid 1px #ddd; overflow:hidden; text-decoration:none; color:#333; cursor:pointer;-webkit-transition: all 0.25s ease-in-out; -moz-transition: all 0.25s ease-in-out;-o-transition: all 0.25s ease-in-out;-ms-transition: all 0.25s ease-in-out; }' +
            '.dd-options > li:last-child > .dd-option{ border-bottom:none;}' +
            '.dd-option:hover{ background:#f3f3f3; color:#000;}' +
            '.dd-selected-description-truncated { text-overflow: ellipsis; white-space:nowrap; }' +
            '.dd-option-selected { background:#f6f6f6; }' +
            '.dd-option-image, .dd-selected-image { vertical-align:middle; float:left; margin-right:5px; max-width:64px;}' +
            '.dd-image-right { float:right; margin-right:15px; margin-left:5px;}' +
            '.dd-container{ position:relative;}​ .dd-selected-text { font-weight:bold}​</style>';

    //CSS styles are only added once.
    if ($('#css-ddslick').length <= 0) {
        $(ddslickCSS).appendTo('head');
    }

    //Public methods
    methods.init = function (options) {
        //Preserve the original defaults by passing an empty object as the target
        var options = $.extend({}, defaults, options);

        //Apply on all selected elements
        return this.each(function () {
            var obj = $(this),
                data = obj.data('ddslick');
            //If the plugin has not been initialized yet
            if (!data) {

                var ddSelect = [], ddJson = options.data;

                //Get data from HTML select options
                obj.find('option').each(function () {
                    var $this = $(this), thisData = $this.data();
                    ddSelect.push({
                        text: $.trim($this.text()),
                        value: $this.val(),
                        selected: $this.is(':selected'),
                        description: thisData.description,
                        imageSrc: thisData.imagesrc //keep it lowercase for HTML5 data-attributes
                    });
                });

                //Update Plugin data merging both HTML select data and JSON data for the dropdown
                if (options.keepJSONItemsOnTop)
                    $.merge(options.data, ddSelect);
                else options.data = $.merge(ddSelect, options.data);

                //Replace HTML select with empty placeholder, keep the original
                var original = obj, placeholder = $('<div id="' + obj.attr('id') + '"></div>');
                obj.replaceWith(placeholder);
                obj = placeholder;

                //Add classes and append ddSelectHtml & ddOptionsHtml to the container
                obj.addClass('dd-container').append(ddSelectHtml).append(ddOptionsHtml);

                //Get newly created ddOptions and ddSelect to manipulate
                var ddSelect = obj.find('.dd-select'),
                    ddOptions = obj.find('.dd-options');

                //Set widths
                ddOptions.css({ width: options.width });
                ddSelect.css({ width: options.width, background: options.background });
                obj.css({ width: options.width });

                //Set height
                if (options.height != null)
                    ddOptions.css({ height: options.height, overflow: 'auto' });

                //Add ddOptions to the container. Replace with template engine later.
                $.each(options.data, function (index, item) {
                    if (item.selected) options.defaultSelectedIndex = index;
                    ddOptions.append('<li>' +
                    '<a class="dd-option">' +
                    (item.value ? ' <input class="dd-option-value" type="hidden" value="' + item.value + '" />' : '') +
                    (item.imageSrc ? ' <img class="dd-option-image' + (options.imagePosition == "right" ? ' dd-image-right' : '') + '" src="' + item.imageSrc + '" />' : '') +
                    (item.text ? ' <label class="dd-option-text">' + item.text + '</label>' : '') +
                    (item.description ? ' <small class="dd-option-description dd-desc">' + item.description + '</small>' : '') +
                    '</a>' +
                    '</li>');
                });

                //Save plugin data.
                var pluginData = {
                    settings: options,
                    original: original,
                    selectedIndex: -1,
                    selectedItem: null,
                    selectedData: null
                }
                obj.data('ddslick', pluginData);

                //Check if needs to show the select text, otherwise show selected or default selection
                if (options.selectText.length > 0 && options.defaultSelectedIndex == null) {
                    obj.find('.dd-selected').html(options.selectText);
                }
                else {
                    var index = (options.defaultSelectedIndex != null && options.defaultSelectedIndex >= 0 && options.defaultSelectedIndex < options.data.length)
                        ? options.defaultSelectedIndex
                        : 0;
                    selectIndex(obj, index);
                }

                //EVENTS
                //Displaying options
                obj.find('.dd-select').on('click.ddslick', function () {
                    open(obj);
                });

                //Selecting an option
                obj.find('.dd-option').on('click.ddslick', function () {
                    selectIndex(obj, $(this).closest('li').index());
                });

                //Click anywhere to close
                if (options.clickOffToClose) {
                    ddOptions.addClass('dd-click-off-close');
                    obj.on('click.ddslick', function (e) { e.stopPropagation(); });
                    $('body').on('click', function () {
                        $('.dd-click-off-close').slideUp(50).siblings('.dd-select').find('.dd-pointer').removeClass('dd-pointer-up');
                    });
                }
            }
        });
    };

    //Public method to select an option by its index
    methods.select = function (options) {
        return this.each(function () {
            //if (options.index) //IMPORTANTE PORQUE NO SE PUEDE SELECCIONAR EL INDEX 0 *************************//
                selectIndex($(this), options.index);
        });
    }

    //Public method to open drop down
    methods.open = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if plugin is initialized
            if (pluginData)
                open($this);
        });
    };

    //Public method to close drop down
    methods.close = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if plugin is initialized
            if (pluginData)
                close($this);
        });
    };

    //Public method to destroy. Unbind all events and restore the original Html select/options
    methods.destroy = function () {
        return this.each(function () {
            var $this = $(this),
                pluginData = $this.data('ddslick');

            //Check if already destroyed
            if (pluginData) {
                var originalElement = pluginData.original;
                $this.removeData('ddslick').unbind('.ddslick').replaceWith(originalElement);
            }
        });
    }

    //Private: Select index
    function selectIndex(obj, index) {


        //console.log('PRIVATE SELECTINDEX '+index);

        //Get plugin data
        var pluginData = obj.data('ddslick');

        //Get required elements
        var ddSelected = obj.find('.dd-selected'),
            ddSelectedValue = ddSelected.siblings('.dd-selected-value'),
            ddOptions = obj.find('.dd-options'),
            ddPointer = ddSelected.siblings('.dd-pointer'),
            selectedOption = obj.find('.dd-option').eq(index),
            selectedLiItem = selectedOption.closest('li'),
            settings = pluginData.settings,
            selectedData = pluginData.settings.data[index];

        //Highlight selected option
        obj.find('.dd-option').removeClass('dd-option-selected');
        selectedOption.addClass('dd-option-selected');

        //Update or Set plugin data with new selection
        pluginData.selectedIndex = index;
        pluginData.selectedItem = selectedLiItem;
        pluginData.selectedData = selectedData;

        //If set to display to full html, add html
        if (settings.showSelectedHTML) {
            ddSelected.html(
                (selectedData.imageSrc ? '<img class="dd-selected-image' + (settings.imagePosition == "right" ? ' dd-image-right' : '') + '" src="' + selectedData.imageSrc + '" />' : '') +
                (selectedData.text ? '<label class="dd-selected-text">' + selectedData.text + '</label>' : '') +
                (selectedData.description ? '<small class="dd-selected-description dd-desc' + (settings.truncateDescription ? ' dd-selected-description-truncated' : '') + '" >' + selectedData.description + '</small>' : '')
            );

        }
        //Else only display text as selection
        else ddSelected.html(selectedData.text);

        //Updating selected option value
        ddSelectedValue.val(selectedData.value);

        //BONUS! Update the original element attribute with the new selection
        pluginData.original.val(selectedData.value);
        obj.data('ddslick', pluginData);

        //Close options on selection
        close(obj);

        //Adjust appearence for selected option
        adjustSelectedHeight(obj);

        //Callback function on selection
        if (typeof settings.onSelected == 'function') {
            settings.onSelected.call(this, pluginData);
        }
    }

    //Private: Close the drop down options
    function open(obj) {

        var $this = obj.find('.dd-select'),
            ddOptions = $this.siblings('.dd-options'),
            ddPointer = $this.find('.dd-pointer'),
            wasOpen = ddOptions.is(':visible');

        //Close all open options (multiple plugins) on the page
        $('.dd-click-off-close').not(ddOptions).slideUp(50);
        $('.dd-pointer').removeClass('dd-pointer-up');

        if (wasOpen) {
            ddOptions.slideUp('fast');
            ddPointer.removeClass('dd-pointer-up');
        }
        else {
            ddOptions.slideDown('fast');
            ddPointer.addClass('dd-pointer-up');
        }

        //Fix text height (i.e. display title in center), if there is no description
        adjustOptionsHeight(obj);
    }

    //Private: Close the drop down options
    function close(obj) {
        //Close drop down and adjust pointer direction
        obj.find('.dd-options').slideUp(50);
        obj.find('.dd-pointer').removeClass('dd-pointer-up').removeClass('dd-pointer-up');
    }

    //Private: Adjust appearence for selected option (move title to middle), when no desripction
    function adjustSelectedHeight(obj) {

        //Get height of dd-selected
        var lSHeight = obj.find('.dd-select').css('height');

        //Check if there is selected description
        var descriptionSelected = obj.find('.dd-selected-description');
        var imgSelected = obj.find('.dd-selected-image');
        if (descriptionSelected.length <= 0 && imgSelected.length > 0) {
            obj.find('.dd-selected-text').css('lineHeight', lSHeight);
        }
    }

    //Private: Adjust appearence for drop down options (move title to middle), when no desripction
    function adjustOptionsHeight(obj) {
        obj.find('.dd-option').each(function () {
            var $this = $(this);
            var lOHeight = $this.css('height');
            var descriptionOption = $this.find('.dd-option-description');
            var imgOption = obj.find('.dd-option-image');
            if (descriptionOption.length <= 0 && imgOption.length > 0) {
                $this.find('.dd-option-text').css('lineHeight', lOHeight);
            }
        });
    }

})(jQuery);





//Button import dummy data
jQuery(document).ready(function($) {

    "use strict";

    $('#button_import').click(function(e){

        e.preventDefault();

        var $button = $(e.target);
        var message = $button.data('message');
        var answer = confirm(message);

        //Redirigir url para proceder a importar
        if (answer) {
            var link = $button.attr('href');
            $button.parent().find('.button-preloader').addClass('active');
            window.location.href = link;
        };
    });

});


//Backup settings
jQuery(document).ready(function($){

    "use strict";

    $('.options_ajax_button').click(function(e){
        e.preventDefault();

        var $self = $(e.target);

        //Preguntar si esta seguro
        var action = $self.data('action');
        var message = '';
        if (action == 'backup_options') {
            message = "Click OK to BACKUP options.";
        } else {
            message = "Click OK to RESTORE options.";
        }
        var answer = confirm(message);
        if (!answer) return;


        //Realizar accion ajax
        var nonce = $('#security_nonce').val();

        var data = {
            action: 'options_ajax_action',
            type: action,
            security_nonce: nonce
        };

        //Envio lo que hay en el textarea para restaurar options
        if (action=='restore_options') {
            data.options = $('#textarea_options').val();
        }

        $.post(ajaxurl, data, function(response){

            //failed
            if (response == -1) {
                alert('Response failed');
            }

            else {
                if (action=='backup_options') {
                    $('#textarea_options').val(response);
                    //alert('Your Options have been saved');
                    $('#submit').trigger('click');
                }
                else {
                    alert(response);
                }

            }

        });

    });

});
