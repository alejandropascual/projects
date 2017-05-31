(function($,window,undefined) {

    "use strict";


    /******************************/
    // MAPA ADDRESS
    /******************************/

    $(document).ready(function(){

        $('.map_canvas_address').each(function(el) {

            // Para obtener coordenadas
            var geocoder = new google.maps.Geocoder();

            //Para centrar todas las direcciones
            var bounds = new google.maps.LatLngBounds();

            var infowindow = new google.maps.InfoWindow({
                content: 'Address'
            });

            var numberAddress = 0;
            var zoom = 12;

            function codeAddress( index, address, map, mak ) {

                geocoder.geocode( { 'address': address}, function(results, status)
                {
                    if (status == google.maps.GeocoderStatus.OK) {

                        //console.log( 'Coord encontradas '+index + ' de ' + numberAddress );
                        //console.log( results[0].geometry.location );

                        var marker = new RichMarker({
                            position: results[0].geometry.location,
                            map: map,
                            draggable: false,
                            content: '<span class="' + mak.icon_class + '" style="border-color:' + mak.icon_color + ';"><div class="inner">' + mak.img + '</div></span>',
                            shadow: 'none',
                            anchor: mak.anchor,
                        });

                        if (numberAddress > 1)
                        {
                            bounds.extend(marker.position);
                            //console.log('Encajando el plano');
                            map.fitBounds(bounds);
                            map.setZoom(zoom);

                        } else {
                            //console.log('Centrando el mapa');
                            //console.log(results[0].geometry.location);
                            map.setCenter(results[0].geometry.location);
                        }

                        google.maps.event.addListener(marker, 'click', function(){
                            infowindow.content = address;
                            infowindow.open(map, marker);
                            setTimeout( function(){
                                infowindow.close();
                            }, 3000);
                        });

                    } else {
                        console.log('Geocode was not successful for the following reason: ' + status);
                    }
                });

            }



            //Generar el mapa
            var $self = $(this);

            var address = $self.attr('data-address'),
                icon_class = $self.attr('data-icon-class'),
                icon_color = $self.attr('data-color'),
                mapstyle = $self.attr('data-mapstyle'),
                coord = $self.attr('data-coord');

            //Zoom global
            zoom = parseInt( $self.attr('data-zoom'));

            var ll = coord.split(',');
            var center = new google.maps.LatLng(ll[1], ll[0]);

            //Crear mapa
            var MY_MAPTYPE_ID = 'custom_style';

            var mapOptions = {
                zoom: zoom,
                center: center,
                disableDefaultUI: true,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
                },
                mapTypeId: MY_MAPTYPE_ID
            };
            var map = new google.maps.Map($self[0], mapOptions);

            var featureOpts = mapstyles[mapstyle];

            var styledMapOptions = {
                name: 'Custom Style'
            };

            var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
            map.mapTypes.set(MY_MAPTYPE_ID, customMapType);

            //El icono
            var anchor = RichMarkerPosition.BOTTOM_LEFT;
            if (icon_class == 'icon-map-circle-40') {
                anchor = RichMarkerPosition.BOTTOM_CENTER;
            }


            //Ahora las direcciones
            var adds = address.split("|");
            //console.log(adds);
            numberAddress = adds.length;

            $.each(adds, function(index, value){

                var marker = {
                    anchor: anchor,
                    icon_color: icon_color,
                    icon_class: icon_class,
                    img: ''
                };
                codeAddress( index+1, value, map, marker);

            });

        });

    });




    /******************************/
    // MAPA PROYECTO
    /******************************/

    $(document).ready(function(){

        $('.map_canvas_project').each(function(el){

            var $self = $(this);

            var coord = $self.attr('data-coord'),
                address = $self.attr('data-address'),
                zoom = parseInt( $self.attr('data-zoom')),
                img_src = $self.attr('data-img-src'),
                icon_class = $self.attr('data-icon-class'),
                icon_color = $self.attr('data-color'),
                mapstyle = $self.attr('data-mapstyle');

            var img = '';
            if (img_src) {
                img = '<img src="'+img_src+'">';
            }

            //console.log('Arrancando el mapa');
            //console.log( coord + ' / ' + address + ' / ' + zoom );

            var ll = coord.split(',');
            var center = new google.maps.LatLng(ll[1], ll[0]);

            //Crear mapa
            var MY_MAPTYPE_ID = 'custom_style';

            var mapOptions = {
                zoom: zoom,
                center: center,
                disableDefaultUI: true,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
                },
                mapTypeId: MY_MAPTYPE_ID
            };
            var map = new google.maps.Map($self[0], mapOptions);

            var featureOpts = mapstyles[mapstyle];

            var styledMapOptions = {
                name: 'Custom Style'
            };

            var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
            map.mapTypes.set(MY_MAPTYPE_ID, customMapType);

            //Marker
            /*var marker = new google.maps.Marker({
                position: center,
                map: map
            });*/

            var anchor = RichMarkerPosition.BOTTOM_LEFT;
            if ( icon_class == 'icon-map-circle-70' || icon_class == 'icon-map-circle-40') {
                anchor = RichMarkerPosition.BOTTOM_CENTER;
            }
            //Estos iconos no llevan imagen
            if ( icon_class == 'icon-map-circle-40' || icon_class == 'icon-map-square-40' ) {
                img = '';
            }

            var rr = new RichMarker({
                position: center,
                map: map,
                draggable: false,
                content: '<span class="' + icon_class + '" style="border-color:' + icon_color + ';"><div class="inner">' + img + '</div></span>',
                shadow: 'none',
                anchor: anchor
            });
            //$(rr.a).css('box-shadow','none');

        });

    });




    /******************************/
    // MAPA VARIOS PROYECTOS
    /******************************/

    $(document).ready(function() {


        $('.map_canvas_projects').each(function (el) {

            var $self = $(this);
            var $wrapper = $(this).closest('.map_projects');
            var $win_project = $wrapper.find('.map_popup');

            var projects_data = $self.data('projects_data');
            //console.log( projects_data );

            var map_options = $self.data('map_options');
            var map_style = map_options.map_style;
            var icon_class = map_options.icon_type;
            var icon_color = map_options.icon_color;
            //console.log( map_options );

            //Crear mapa
            var MY_MAPTYPE_ID = 'custom_style';

            var mapOptions = {
                zoom: 2,
                center: new google.maps.LatLng(42, 72),
                disableDefaultUI: true,
                mapTypeControlOptions: {
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
                },
                mapTypeId: MY_MAPTYPE_ID
            };

            var map = new google.maps.Map($self[0], mapOptions);
            var featureOpts = mapstyles[map_style];
            var styledMapOptions = {
                name: 'Custom Style'
            };
            var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
            map.mapTypes.set(MY_MAPTYPE_ID, customMapType);


            //crear cada marker
            var anchor = RichMarkerPosition.BOTTOM_LEFT;

            if (icon_class == 'icon-map-circle-70' || icon_class == 'icon-map-circle-40') {
                anchor = RichMarkerPosition.BOTTOM_CENTER;
            }

            var bounds = new google.maps.LatLngBounds();


            function render_info_project( info ){
                var html = '<a href="'+info.permalink+'"><img class="popup-img" src="'+info.image_resized_url+'"></a>\
                    <div class="popup-text">\
                        <a href="'+info.permalink+'" class="popup-text-title">'+info.title+'</a>\
                        <div class="popup-text-desc">'+info.excerpt+'</div>\
                    </div>';
                return html;
            }

            //Le paso el marker como contexto this
            function mostrarProyectoMarker( )
            {
                $win_project.addClass('open');
                $win_project.find('.popup-info').html( render_info_project(this.info) );
            }

            $.each(projects_data, function (index, project)
            {
                var img = '<img src="' + project.thumbnail_url + '">';
                if (icon_class == 'icon-map-circle-40' || icon_class == 'icon-map-square-40') {
                    img = '';
                }

                var ll = project.map_coord.split(',');
                var center = new google.maps.LatLng(ll[1], ll[0]);

                var marker = new RichMarker({
                    position: center,
                    map: map,
                    draggable: false,
                    content: '<span class="richmarker ' + icon_class + '" style="border-color:' + icon_color + ';"><div class="inner">' + img + '</div></span>',
                    shadow: 'none',
                    anchor: anchor,
                    info: project
                });

                bounds.extend(marker.position);

                google.maps.event.addListener(marker, 'mouseover', function(){
                    mostrarProyectoMarker.call(this);
                });
                google.maps.event.addListener(marker, 'click', function(){
                    mostrarProyectoMarker.call(this);
                });

            });

            //now fit the map to the newly inclusive bounds
            map.fitBounds(bounds);

            //Para cerrar la ventana del proyecto
            function cerrarPopup(){
                $win_project.removeClass('open');
            }
            google.maps.event.addListener(map, 'drag', cerrarPopup );
            $win_project.find('.popup-close').click( cerrarPopup );

        });

    });



})(jQuery, window);


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