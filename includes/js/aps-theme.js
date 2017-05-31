

(function($,window,undefined) {

    "use strict";

    /******************************/
    /* SMART RESIZE */
    /******************************/
    //Smart resize de Paul Irish
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/

    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            };

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    }

    jQuery.fn['smartResize'] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };


    /******************************/
    /* PRELOADER */
    /******************************/



    $(document).ready(function(){

        var num = document.images.length;
        //console.log('Numero de imagenes = '+num);

        function showprogress() {

            if (document.images.length == 0) {
                return false;
            }
            var loaded = 0;
            for (var i=0; i<document.images.length; i++) {
                if (document.images[i].complete) {
                    loaded++;
                }
            }
            var percentage = Math.round(100 * loaded / document.images.length);

            console.log(percentage+'%');
            //document.getElementById("progress").innerHTML = percentage + "% loaded";

            if (percentage == 100) {
                window.clearInterval(ID);
            }
        }

        //var ID = setInterval( showprogress, 200);

    });





    /******************************/
    /* SEARCHFORM Icono en el MENU */
    /******************************/

    $(document).ready(function(){
        $('#menu-header,#socket').on('click','.search-toggle',function(e){
            var parent = $(this).closest('.search-nav');
            $(parent).toggleClass('close');
            e.preventDefault();
        });

    });

    /******************************/
    /* FIXED HEADER */
    /******************************/


    /* SCROLL */
    // Solo si la opcion esta activada
    $(document).ready(function(){
        if ( $('body').hasClass('fixed-header-yes') && $('#menu-header').length){
            usar_header_fixed();
        }
    });

    function usar_header_fixed() {

        $(window).scroll(function() {

            var scrollTop = $(window).scrollTop();
            var offsetTop = $('#menu-header .head-center').offset().top;
            var offsetRelleno = $('#header-relleno').offset().top;
            var dif = offsetTop - scrollTop;
            var difRelleno = offsetRelleno - scrollTop;

            //console.log(difRelleno);

            if ($('body').hasClass('header-fixed')) {

                //Si paso el umbral de donde deberia estar lo desfijo
                //if (scrollTop<5) {
                if (difRelleno>-5) {
                    $('body').removeClass('header-fixed');
                }

            } else {

                //El head-center lo he sobrepasado entonces lo fijo
                if (dif<0) {
                    $('body').addClass('header-fixed');
                } else {
                    $('body').removeClass('header-fixed');
                }
            }

        });

    }






    /******************************/
    /* MENU MOBILE */
    /******************************/
    // Antes de arrancar el superfish menu


    //Segunda version de menu mobile
    $(document).ready(function(){

        //Crear zona pare el menu mobile que se extiende dentro del head-center

        //$('#menu-header .head-center').append('<div id="menu-mobile-list"><a id="menu-mobile-btn-close" class="fa fa-circle-o" href="#"></a><div class="wrap"></div></div>');
        $('#menu-header .head-center').append('<div id="menu-mobile-list"><div class="container wrap"></div></div>');

        //Crear boton para menu mobile
        $('#menu-header .head-center').prepend('<div id="menu-mobile-btn"></div>');

        //Agregar los menus tal cual
        $('#menu-header .nav-menu-wrap').each(function() {

            var $html = $('<div class="head-mob mob-main"><nav></nav></div>');
            var $menu = $html.appendTo('#menu-mobile-list .wrap');
            var $menu_nav = $menu.find('nav');

            $(this).clone().appendTo($menu_nav);
        });

        // Quito sf-menu a lo menus mobiles
        $('#menu-mobile-list .sf-menu').removeClass('sf-menu');

        //Convertir los menus en lista de elementos
        $('#menu-mobile-list nav').each(function(){
            //convert_menu_to_list(this);
        });

        $('#menu-mobile-btn').click(function()
        {
            $(this).toggleClass('close');

            $('#menu-mobile-list').toggleClass('open');

            $('body').toggleClass('menu-mobile-open');

            if ( $('body').hasClass('menu-mobile-open') )
            {
                $('body').addClass('header-fixed');
            }
            else
            {
                if ( $('body').hasClass('fixed-header-no') )
                {
                    $('body').removeClass('header-fixed');
                }
            }
        });

        // Cuando pulso un menu mobile si esta dentro tengoq eu ocultar el menu
        $('#menu-mobile-list a').click(function(){
            $('#menu-mobile-btn').trigger('click');
        });

    });


    // Convertir un menu de ul en un listado tipo select
    // Latest version: https://github.com/toddmotto/selectnav

    function convert_menu_to_list( ul ) {

        //console.log(ul);

        var select = document.createElement('div');

        var loadLinks = function(element, hyphen, level) {

            var e = element;
            var children = e.children;

            for(var i = 0; i < e.children.length; ++i) {

                var currentLink = children[i];

                switch(currentLink.nodeName) {
                    case 'A':
                        var option = document.createElement('a');
                        //option.innerHTML = (level++ < 1 ? '' : hyphen) + currentLink.innerHTML;
                        option.innerHTML = currentLink.innerHTML;
                        if (level++ < 1) {
                            option.setAttribute('class','btn-menu level');
                        } else {
                            option.setAttribute('class','btn-menu level'+hyphen);
                        }
                        option.href = currentLink.href;
                        select.appendChild(option);
                        break;
                    default:
                        if(currentLink.nodeName === 'UL') {
                            (level < 2) || (hyphen += hyphen);
                        }
                        loadLinks(currentLink, hyphen, level);
                        break;
                }
            }
        } //loadLinks

        loadLinks(ul, '-sub', 0);

        //console.log(select);
        $(ul).html(select);

    }



    /******************************/
    /* SUPERFISH MENU */
    /******************************/
    $(document).ready(function(){
        $('ul.sf-menu').superfish();
    });


    /******************************/
    /* WIDGET MENU desplegable */
    /******************************/

    $(document).ready(function(){

        function addIconToItems(){

            //Le añado un icono a la derecha
            $('.widget-menu-type-custom-2').each(function(){

                $(this).find('li.menu-item-has-children >a').after('<span class="icon-open-sub"></span>');

            });

            $('.widget-menu-type-custom-2').on('click', '.icon-open-sub', function(){
                //console.log('Pulsado abrir submenu');
                $(this).toggleClass('open');
                $(this).siblings('ul.sub-menu').toggleClass('open');
            });

            //Abrir todos al principio si tiene la clase opened
            $('.widget-menu-type-custom-2.opened').each(function(){
                $(this).find('.icon-open-sub').trigger('click');
            });
        }

        setTimeout(addIconToItems, 500);
        //addIconToItems();


    });



    /******************************/
    /* SMOOTH SCROLL */
    /******************************/
    // http://css-tricks.com/snippets/jquery/smooth-scrolling/
    $(document).ready(function(){

        //Salvar el menu flotante
        var altura_menu = 0;
        if ( $('body').hasClass('fixed-header-yes') && $('#menu-header').length )
        {
            altura_menu = $('#menu-header .head-center').height();
        }
        //console.log('ALTURA MENU '+altura_menu);

        //$('a.hash').click(function(e) {
        //Solo los que empiezan por move
        $('a[href*=#move-]:not([href=#])').click(function(e) {

            //Evitar el acordeon
            //if ($(this).hasClass('aps-panel-toggle')) return;

            //e.preventDefault();
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                    //console.log('AUTO SCROLLING FRAGMENT');
                    $('html,body').animate({
                        scrollTop: target.offset().top - altura_menu
                    }, 1000);
                    return false;
                }
            }
        });

    });


    //Al inicio comprueba el hash cuando viene de otra pagina
    //y lo desplazo la altura del menu
    $(document).ready(function(){


        function comprobarAbrirToggle(){
            //console.log(location);
            //console.log(location.hash);
            //console.log(location.search);
            var hash = location.hash;
            var search = location.search.replace('?','');
            //console.log(search);
            var arrSearch = search.split('&');
            //console.log(arrSearch);
            //console.log($.inArray('open',arrSearch));
            if ($.inArray('open', arrSearch)!=-1) {
                //console.log('Abrir el toggle');
                //alert('Abrir el toggle');
                //Sibling con class=hash
                var a = location.hash.replace('#','');
                if (a.length>2) {
                    //$('#'+a+' + .'+a).trigger('click');
                    var toggle = $('#'+a+' + .'+a+' a.aps-panel-toggle');
                    toggle.trigger('click');
                    //console.log(toggle);
                }
            }
        }


        //console.log(location);
        function comprobar_hash(){
            //console.log('Buscando desplazamiento hash');
            //console.log('ScrollTop= '+document.body.scrollTop);

            if (location.hash != ""){
                var altura_menu = $('#menu-header .head-center').height();
                var top = document.body.scrollTop - altura_menu;
                //console.log('TOP '+top);
                $('body').animate({
                    scrollTop: top
                }, 300,comprobarAbrirToggle);
            }
        }
        setTimeout(comprobar_hash, 1000);
    });



    //jquery selector anchos variables de un div
    $(document).ready(function( $ ) {
        $('.selector').selectorQuery();
    });












/******************************/
/* ISOTOPE con el shortcode gallery_template.php en masonry y en grid */
/******************************/

jQuery(document).ready(function($) {


    //Isotope
    //Permite centrar  en el container

    //masonry
    $.Isotope.prototype._getCenteredMasonryColumns = function() {

        this.width = this.element.width();
        var parentWidth = this.element.parent().width();
        var colW = this.options.masonry && this.options.masonry.columnWidth || // i.e. options.masonry && options.masonry.columnWidth
            this.$filteredAtoms.outerWidth(true) || // or use the size of the first item
            parentWidth; // if there's no items, use size of container
        var cols = Math.floor(parentWidth / colW);
        cols = Math.max(cols, 1);
        this.masonry.cols = cols; // i.e. this.masonry.cols = ....
        this.masonry.columnWidth = colW; // i.e. this.masonry.columnWidth = ...
    };

    $.Isotope.prototype._masonryReset = function() {

        this.masonry = {}; // layout-specific props
        this._getCenteredMasonryColumns(); // FIXME shouldn't have to call this again
        var i = this.masonry.cols;
        this.masonry.colYs = [];
        while (i--) {
            this.masonry.colYs.push(0);
        }
    };

    $.Isotope.prototype._masonryResizeChanged = function() {

        var prevColCount = this.masonry.cols;
        this._getCenteredMasonryColumns(); // get updated colCount
        return (this.masonry.cols !== prevColCount);
    };

    $.Isotope.prototype._masonryGetContainerSize = function() {

        var unusedCols = 0,
            i = this.masonry.cols;
        while (--i) { // count unused columns
            if (this.masonry.colYs[i] !== 0) {
                break;
            }
            unusedCols++;
        }
        return {
            height: Math.max.apply(Math, this.masonry.colYs),
            width: (this.masonry.cols - unusedCols) * this.masonry.columnWidth // fit container to columns that have been used;
        };
    };


    //Mi modo para el grid
    $.extend( $.Isotope.prototype, {
        _apsModeReset : function() {
            this.apsMode = {
                x:0,
                y:0,
                height:0
            }
        },
        _apsModeLayout : function( $elems ) {
            var instance = this,
                containerWidth = this.element.width(),
                props = this.apsMode;

            $elems.each( function() {
                var $this = $(this),
                    atomW = $this.outerWidth(true),
                    atomH = $this.outerHeight(true);

                //Defino 5 pixeles de error por los porcentajes exactos
                if ( props.x !== 0 && atomW + props.x > containerWidth+5 ) {
                    // if this element cannot fit in the current row
                    props.x = 0;
                    props.y = props.height;
                }

                // position the atom
                instance._pushPosition( $this, props.x, props.y );
                //console.log('Posicionando : ' + props.x + ' , ' + props.y);

                props.height = Math.max( props.y + atomH, props.height );
                props.x += atomW;

            });
        },

        _apsModeGetContainerSize : function() {
            return { height : this.apsMode.height };
        },

        _apsModeResizeChanged : function() {
            return true;
        }
    });



    //http://stackoverflow.com/questions/15008383/is-an-isotope-layout-combining-centered-fitrows-possible
    // modified Isotope methods for gutters in fitRows


    //Isotope arrancar
    //Obtener los datos de ancho y margin del isotope
    $('.isotope').each(function(){

        var $self = $(this);
        var layoutMode = 'masonry';
        if ($self.hasClass('grid')) {
            layoutMode = 'apsMode';
        }

        //Espero un poco para que aplique el css anterior y pueda
        //funcionar bien el isotope
        //setTimeout(function(){

        //}, 600);

        function arrancarIsotope()
        {
            $self.isotope({
                resizable: true,
                //layoutMode : 'masonry',
                //layoutMode: 'fitRows',
                layoutMode: layoutMode,
                containerClass : 'isotope',
                itemClass : 'post',
                hiddenClass : 'isotope-hidden',
                hiddenStyle: { opacity: 0, scale: 0.001 },
                visibleStyle: { opacity: 1, scale: 1 },
                containerStyle: {
                    position: 'relative',
                    overflow: 'hidden'
                },
                animationEngine: 'best-available',
                animationOptions: {
                    queue: false,
                    duration: 800
                },
                resizesContainer: true,
                sortBy : 'original-order',
                sortAscending : true,
                transformsEnabled: true,
                itemPositionDataEnabled: false
            });
        }

        //Puedo arrancar al principio porque
        //las imagenes ahora dejan hueco
        //imagesLoaded( '.isotope', arrancarIsotope);
        arrancarIsotope();
        $self.addClass('aps_onScreen');
        setTimeout( arrancarIsotope, 400); //Para corregir errores

        //$self.imagesLoaded( function(){
        //arrancarIsotope();
        //$self.addClass('aps_onScreen');
        //} );


        var $wrapper = $self.closest('.aps-gallery-template');
        //console.log($wrapper);

        //console.log($wrapper);
        //Pulsar los filtros
        $wrapper.find('.isotope-filters a').click(function(e){
            console.log('Filtro pulsado');
            e.preventDefault();
            var selector = $(this).attr('data-filter');
            $self.isotope({ filter: selector });
            $wrapper.find('.isotope-filters a').removeClass('active');
            $(this).addClass('active');
            return false;
        });


        //Cargar ajax si existe
        var page_current = 1;
        var $boton_ajax = $self.parent().parent().find('.gallery-the-paging.ajax a');

        function relayoutAfterResponse(){
            $self.isotope('reLayout');
        }

        if ($boton_ajax)
        {



            var sc_atts = $boton_ajax.attr('data-sc_atts');
            sc_atts = $.parseJSON(sc_atts);
            var max_num_pages = $boton_ajax.data('max_num_pages');



            $boton_ajax.click(function(e){

                e.preventDefault();

                page_current ++;
                sc_atts['page_number'] = page_current;
                //console.log('ENVIANDO');
                //console.log(sc_atts);
                //console.log(max_num_pages);

                $boton_ajax.parent().addClass('loading');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data:
                    {
                        action: 'aps_gallery_template_ajax',
                        sc_atts: sc_atts
                    },
                    error: function()
                    {

                    },
                    success: function(response)
                    {
                        if (response.success)
                        {
                            //console.log(response.html);
                            var $newItems = $(response.html);
                            $self.append($newItems);
                            //$self.isotope('appended', $newItems);
                            $self.isotope('appended', $newItems);
                            setTimeout(relayoutAfterResponse,300);

                            //Quitar aviso loading
                            $boton_ajax.parent().removeClass('loading');

                            //Comprobar si es la ultima
                            //console.log('PAGE CURRENT '+page_current);
                            if (page_current == max_num_pages) {
                                $boton_ajax.parent().remove();
                            }
                        }



                    },
                    complete: function(response)
                    {

                    }
                });

            });
        }



    });
});


/******************************/
/* EVERSLIDER */
/******************************/

jQuery(document).ready(function($) {

    $('.everslider.image-slider').each(function(){

        var $el = $(this);

        $el.everslider({
            mode: 'carousel',
            moveSlides: 'auto',
            itemKeepRatio: false,
            navigation: true,
            mouseWheel: false,
            itemWidth: $el.data('slide_width'),
            itemHeight: $el.data('slide_height')
            //mode: 'normal',
            //maxVisible: 2,
            //slideEasing: 'easeInOutQuart',
            //slideDuration: 500
            //maxVisible: 1,
            //slideEasing: 'easeInOutQuart',
            //slideDuration: 500
        });

    });


    $('.everslider.featured-slider').each(function(){

        var $el = $(this);

        $el.everslider({
            mode: 'carousel',
            itemKeepRatio: false,
            navigation: true,
            mouseWheel: false,
            itemWidth: $el.data('slide_width'),
            itemHeight: $el.data('slide_height'),
            moveSlides: 'auto'
        });
    });

});





//Boxer ya no lo uso
/*
 jQuery(document).ready(function($) {
 $('.theme-boxer').boxer();
 });
 */

//Prettyphoto ya no lo uso
/*
 jQuery(document).ready(function($) {
 $("[rel^='prettyPhoto']").prettyPhoto({
 animation_speed:'normal',
 //theme:'light_square',
 slideshow:3000,
 autoplay_slideshow: false
 });
 });
 */


/******************************/
/* MAGNIFICPOPUP */
/******************************/

jQuery(document).ready(function($) {

    $('.popup-gallery').magnificPopup({
        delegate: 'a.popup',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title') + '<small>'+item.el.attr('data-author')+'</small>';
            }
        }
    });

    $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false
    });
});




/******************************/
/* JUSTIFIED GRID con el shortcode gallery_template.php */
/******************************/

jQuery(document).ready(function($) {


    //Mostrar imagenes a medida que se van cargando, pero vale solo al principio
    //no con ajax, por lo que mejor no usarlo por ahora
    //en css la img tiene que tener opacity:0 al principio
    function mostrar_imagenes_tras_cargar() {
        $('.gallery-the-elements-inner.justified-grid img').each(function () {
            $(this).imagesLoaded(function () {
                var $img = $(this.elements[0]);
                $img.css({
                    "visibility": "visible"
                }).animate({
                    "opacity": 1
                }, 300);
            });
        });
    }
    //mostrar_imagenes_tras_cargar();


    $('.gallery-the-elements-inner.justified-grid').each(function () {

        var $self = $(this);
        var $wrapper = $self.closest('.aps-gallery-template');
        var padding = parseInt( $self.attr('data-padding') );
        var height = parseInt( $self.attr('data-target_height') );
        $self.removeWhitespace();

        function collage() {
            //return;
            var width = parseInt( $self.width() );
            $self.collagePlus({
                'albumWidth': width,
                'targetHeight': height,
                'padding': padding,
                'allowPartialLastRow': true,
                'fadeSpeed': 2000,
                'effect': 'effect-1',
                'direction': 'vertical'
            });
        }


        $self.imagesLoaded( function(){
            $self.addClass('aps_onScreen');
            collage();
            //$self.addClass('aps_onScreen');
            //Solo bind despues de la primera vez que hago collage
            var resizeTimer = null;
            $(window).bind('resize', function() {
                if (resizeTimer)  { clearTimeout(resizeTimer); }
                resizeTimer = setTimeout(collage, 200);
            });
        } );



        //Filtros, no esta preparado
        //para ocultar creo que voy a tener que mover los elementos
        //ocultos fuera del grid
        /*
         $wrapper.find('.isotope-filters a').click(function(){
         var selector = $(this).attr('data-filter');

         $para_ocultar = $self.find('article.isotope-item').not(selector);
         $para_mostrar = $self.find(selector);

         $para_ocultar.addClass('isotope-hidden').remove();
         $para_mostrar.removeClass('isotope-hidden').show();
         setTimeout(collage,3000);

         $wrapper.find('.isotope-filters a').removeClass('active');
         $(this).addClass('active');
         return false;
         });
         */


        //Boton load more ajax
        var $load_more = $self.closest('.aps-gallery-template.type-justified_grid_image').find('.gallery-the-paging.ajax a');
        $load_more.each(function(index, item)
        {
            var $gallery = $self;

            var $boton_ajax = $(this);
            var sc_atts = $boton_ajax.attr('data-sc_atts');
            sc_atts = $.parseJSON(sc_atts);
            var max_num_pages = $boton_ajax.data('max_num_pages');
            var page_current = 1;

            $boton_ajax.click(function(e){
                e.preventDefault();
                page_current ++;
                sc_atts['page_number'] = page_current;
                $boton_ajax.parent().addClass('loading');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data:
                    {
                        action: 'aps_gallery_template_ajax',
                        sc_atts: sc_atts
                    },
                    error: function()
                    {

                    },
                    success: function(response)
                    {
                        if (response.success)
                        {
                            //console.log(response.html);
                            $gallery.append(response.html);

                            $boton_ajax.parent().removeClass('loading');
                            if (page_current == max_num_pages) {
                                $boton_ajax.parent().remove();
                            }

                            //Para que le de tiempo a cargar las imagenes
                            //mostrar_imagenes_tras_cargar();
                            $self.imagesLoaded( function(){
                                collage();
                            } );
                        }
                    },
                    complete: function(response)
                    {

                    }
                });
            });
        });

    });

});



/******************************/
/* LIST OF ELEMENTS en gallery_template.php shortcode */
/******************************/

jQuery(document).ready(function($) {

    $('.type-list_image_and_text,.type-list_image_and_text_alternate').each(function(){

        var $wrapper = $(this);
        var $gallery = $wrapper.find('.gallery-the-elements-inner');

        $wrapper.find('.isotope-filters a').click(function(){
            var selector = $(this).attr('data-filter');

            var $para_ocultar = $wrapper.find('article.isotope-item').not(selector);
            var $para_mostrar = $wrapper.find(selector);

            $para_ocultar.addClass('isotope-hidden').slideUp(200);
            $para_mostrar.removeClass('isotope-hidden').slideDown(200);

            $wrapper.find('.isotope-filters a').removeClass('active');
            $(this).addClass('active');

            return false;
        });

        //LOAD MORE
        //Boton load more ajax
        var $load_more = $wrapper.find('.gallery-the-paging.ajax a');
        $load_more.each(function(index, item)
        {

            var $boton_ajax = $(this);
            var sc_atts = $boton_ajax.attr('data-sc_atts');
            sc_atts = $.parseJSON(sc_atts);
            var max_num_pages = $boton_ajax.data('max_num_pages');
            var page_current = 1;

            $boton_ajax.click(function(e){
                e.preventDefault();
                page_current ++;
                sc_atts['page_number'] = page_current;
                $boton_ajax.parent().addClass('loading');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data:
                    {
                        action: 'aps_gallery_template_ajax',
                        sc_atts: sc_atts
                    },
                    error: function()
                    {

                    },
                    success: function(response)
                    {
                        if (response.success)
                        {
                            //console.log(response.html);
                            $gallery.append(response.html);

                            $boton_ajax.parent().removeClass('loading');
                            if (page_current == max_num_pages) {
                                $boton_ajax.parent().remove();
                            }

                        }
                    },
                    complete: function(response)
                    {

                    }
                });
            });
        });
    });

});


/******************************/
/* gallery_template FULLWIDTH IMAGE - LOAD MORE y SCROLL */
/******************************/

// Este tipo no tiene filters

jQuery(document).ready(function($) {

    $('.type-fullwidth_image').each(function(){

        var $wrapper = $(this);
        var $gallery = $wrapper.find('.gallery-the-elements');

        //LOAD MORE
        //Boton load more ajax
        var $load_more = $wrapper.find('.gallery-the-paging.ajax a');
        $load_more.each(function(index, item)
        {

            var $boton_ajax = $(this);
            var sc_atts = $boton_ajax.attr('data-sc_atts');
            sc_atts = $.parseJSON(sc_atts);
            var max_num_pages = $boton_ajax.data('max_num_pages');
            var page_current = 1;

            $boton_ajax.click(function(e){
                e.preventDefault();
                page_current ++;
                sc_atts['page_number'] = page_current;
                $boton_ajax.parent().addClass('loading');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data:
                    {
                        action: 'aps_gallery_template_ajax',
                        sc_atts: sc_atts
                    },
                    error: function()
                    {

                    },
                    success: function(response)
                    {
                        if (response.success)
                        {
                            //console.log(response.html);
                            $gallery.append(response.html);

                            $boton_ajax.parent().removeClass('loading');
                            if (page_current == max_num_pages) {
                                $boton_ajax.parent().remove();
                            }

                        }
                    },
                    complete: function(response)
                    {

                    }
                });
            });
        });

    });

});



/******************************/
/* ROYALSLIDER */
/******************************/
jQuery(document).ready(function($) {

    function arrancarRoyalslider()
    {
        $('.shortcode-royalSlider').each(function(){

            //console.log('Royalslider ancho real = ' + $(this).parent().width());

            var $self = $(this),
                $parent = $self.parent(),
                rsWidth = parseInt( $parent.attr('data-rs-width')),
                rsHeight = parseInt( $parent.attr('data-rs-height')),
                rsMode =  $parent.attr('data-rs-mode'),
                fullScreen = $parent.attr('data-fullscreen'),
                transition = $parent.attr('data-rs-transition'),
                autoplay = ($parent.attr('data-rs-autoplay') == 'yes'),
                show_buttons = ($parent.attr('data-rs-show_buttons') == 'yes'),
                show_points = $parent.attr('data-rs-show_points');

            var controlNavigation = 'none';
            if (show_points == 'yes') {
                controlNavigation = 'bullets';
            }

            if (fullScreen == 'yes') {
                var alturaWindow = $(window).height();
                var alturaHeader = $('#menu-header').height();
                var alturaVisible = alturaWindow - alturaHeader;
                var anchoReal = $(this).width();

                //console.log('Ancho inicial = '+rsWidth + ' Real = '+anchoReal);
                //console.log('Altura real = '+alturaWindow+' - '+alturaHeader+' = '+alturaVisible);

                rsWidth = anchoReal;
                rsHeight = alturaVisible;

            }



            //console.log(rsWidth + ' / ' + rsHeight + ' / ' + rsMode);

            $self.royalSlider({
                arrowsNav: show_buttons,
                loop: true,
                keyboardNavEnabled: true,
                controlsInside: false,
                imageScaleMode: rsMode,
                imageScalePadding: 0,
                arrowsNavAutoHide: false,
                autoScaleSlider: true,
                autoScaleSliderWidth: rsWidth,
                autoScaleSliderHeight: rsHeight,
                controlNavigation: controlNavigation,//bullets
                thumbsFitInViewport: false,
                navigateByClick: true,
                startSlideId: 0,
                autoPlay: {
                    enabled: autoplay,
                    pauseOnHover: false,
                    stopAtAction: true,
                    delay: 3000
                },
                transitionType:transition,
                globalCaption: false,
                deeplinking: {
                    enabled: true,
                    change: false
                }
                /* size of all images http://help.dimsemenov.com/kb/royalslider-jquery-plugin-faq/adding-width-and-height-properties-to-images */
                //imgWidth: 1400,
                //imgHeight: 680
            });

        });
    }

    arrancarRoyalslider();

});


/******************************/
/* BLOG LOAD MORE BUTTON */
/******************************/

jQuery(document).ready(function($) {

    $('.blog-pagination-ajax a').each(function(){

        var $boton_ajax = $(this);
        var max_num_pages = $boton_ajax.data('max_num_pages');
        var data_query = $boton_ajax.data('query');
        var is_archive = $boton_ajax.data('is_archive');
        var page_current = 1;
        var $blog_articles = $boton_ajax.parent().prev('.blog-articles');

        //console.log(data_query);
        //console.log(is_archive);

        $boton_ajax.click(function(e){

            e.preventDefault();
            $boton_ajax.parent().addClass('loading');
            page_current++;

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'html',
                data:
                {
                    action: 'aps_blog_pagination',
                    page_number: page_current,
                    data_query: data_query,
                    is_archive: is_archive
                },
                error: function()
                {

                },
                success: function(response)
                {

                    //console.log(response.html);
                    var $newItems = $(response);

                    //Añadir los elementos
                    $blog_articles.append($newItems);

                    $boton_ajax.parent().removeClass('loading');
                    if (page_current == max_num_pages) {
                        $boton_ajax.parent().remove();
                    }


                    //Es in isotope, entonces reajustar
                    if ($blog_articles.hasClass('isotope'))
                    {
                        //console.log('Tengo que recalcular el isotope');
                        $blog_articles.isotope('appended', $newItems);
                        setTimeout(function(){
                            $blog_articles.isotope('reLayout');
                        },400);
                    }

                    //Para el caso de gallery no se carga automaticamente el flexslider y hay que cargarlo aqui

                    //Flexslider
                    /*$newItems.find('.flexslider').flexslider({
                     controlNav: false,
                     animation: "fade",
                     easing: "easeInOutExpo",
                     //animation: "slide", // set animation to slide, esto falla
                     smoothHeight: false // auto-adjust to fit the height of images
                     });
                     */


                    //Royalslider
                    $newItems.find('.shortcode-royalSlider').each(function(){

                        var $self = $(this),
                            $parent = $self.parent(),
                            rsWidth = parseInt( $parent.attr('data-rs-width')),
                            rsHeight = parseInt( $parent.attr('data-rs-height')),
                            rsMode =  $parent.attr('data-rs-mode');

                        $self.royalSlider({
                            arrowsNav: true,
                            loop: true,
                            keyboardNavEnabled: true,
                            controlsInside: false,
                            imageScaleMode: rsMode,
                            imageScalePadding: 0,
                            arrowsNavAutoHide: false,
                            autoScaleSlider: true,
                            autoScaleSliderWidth: rsWidth,
                            autoScaleSliderHeight: rsHeight,
                            controlNavigation: 'none',
                            thumbsFitInViewport: false,
                            navigateByClick: true,
                            startSlideId: 0,
                            autoPlay: true,
                            transitionType:'move',
                            globalCaption: false,
                            deeplinking: {
                                enabled: true,
                                change: false
                            }
                        });

                    });



                },
                complete: function(response)
                {

                }

            });

        });

    });

});

/******************************/
/* INFINITE SCROLL */
/******************************/

jQuery(document).ready(function($) {

    //Para los blogs
    if ($('.blog-pagination-ajax.infinite-scroll').length == 1)
    {
        $(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {

                //$('.blog-pagination-ajax a').trigger('click');
                $('.blog-pagination-ajax a').each(function(){
                    if ( !$(this).parent().hasClass('loading') ) {
                        $(this).trigger('click');
                    }
                });

            }
        });
    }

    //Para el shortcode
    if ($('.gallery-the-paging.infinite-scroll').length == 1)
    {
        $(window).scroll(function () {
            if ($(window).scrollTop() == $(document).height() - $(window).height()) {

                //$('.gallery-the-paging a').trigger('click');
                $('.gallery-the-paging a').each(function(){
                    if ( !$(this).parent().hasClass('loading') ) {
                        $(this).trigger('click');
                    }
                });

            }
        });
    }

});




/******************************/
/* ANIMATION CSS */
/******************************/

jQuery(document).ready(function($) {


    $(window).scroll(function(){
        apsScrollAnimations();
    });



    function apsScrollAnimations() {

        $('.aps_animated').each(function(){

            //console.log(this);

            //Si no esta en pantalla
            if (!$(this).hasClass('aps_onScreen') && apsIsScrolledIntoView(this)) {

                //console.log('Acaba de entrar a la vista este elemento');

                //$(this).addClass('aps_onScreen');

                //Por grupos ?
                if (typeof $(this).attr('data-agroup') != 'undefined') {

                    //Los elementos con el mismo grupo
                    var agroup = $(this).attr('data-agroup');
                    $('[data-agroup=' + agroup + ']').each(function(){

                        //Tiene retraso
                        if ( typeof $(this).attr('data-adelay') != 'undefined' && parseInt($(this).attr('data-adelay')) != 0) {

                            //Comprobar si no se ha lanzado ya el timer
                            if ( !$(this).hasClass('aps_onScreenDelay')) {
                                $(this).addClass('aps_onScreenDelay');
                                var $this = $(this);
                                setTimeout(function(){
                                    $this.addClass('aps_onScreen');
                                }, parseInt($(this).attr('data-adelay')));
                            }

                        } else {
                            $(this).addClass('aps_onScreen');
                        }

                    });



                    //No tiene grupos
                } else {

                    //Tiene retraso
                    if ( typeof $(this).attr('data-adelay') != 'undefined' && parseInt($(this).attr('data-adelay')) != 0) {

                        //Comprobar si no se ha lanzado ya el timer
                        if ( !$(this).hasClass('aps_onScreenDelay')) {
                            $(this).addClass('aps_onScreenDelay');
                            var $this = $(this);
                            setTimeout(function(){
                                $this.addClass('aps_onScreen');
                            }, parseInt($(this).attr('data-adelay')));
                        }

                    } else {
                        $(this).addClass('aps_onScreen');

                    }


                }

            }

        });


    }


    function apsIsScrolledIntoView(elem){

        var docViewTop = $(window).scrollTop();
        var docViewLimit = docViewTop + $(window).height()/1.4; // 1.7 es demasiado

        var elemTop = $(elem).offset().top;
        var elemBottom = elemTop + $(elem).height();

        //console.log('docViewTop:' + docViewTop);
        //console.log('docViewLimit:' + docViewLimit);
        //console.log('elemTop:' + elemTop);

        //console.log(docViewTop + ' , '+docViewLimit + ' : '+elemTop+', '+elemBottom);

        return ((elemTop <= docViewLimit) && (elemTop >= docViewTop));

    }

    //Arrancar los primeros
    apsScrollAnimations();

    setTimeout(apsScrollAnimations, 600);

});




    /******************************/
    /* SIDEBAR FLOATED */
    /******************************/

    jQuery(document).ready(function($){

        $('.responsive-sidebars-floated #left-sidebar-open').click(function(){
            $('#left-sidebar-open').toggleClass('open');
            $('#left-sidebar').toggleClass('open');
        });

        $('.responsive-sidebars-floated #right-sidebar-open').click(function(){
            $('#right-sidebar-open').toggleClass('open');
            $('#right-sidebar').toggleClass('open');
        });

    });


    /******************************/
    /* WIDGET TABS */
    /******************************/

    jQuery(document).ready(function($){
        $('.widget .widget-tab').click(function(e){
            e.preventDefault();
            var $this = $(this);
            var ntab = $this.data('tab');

            var $parent = $this.closest('.widget');
            $parent.find('.widget-tab').removeClass('active');
            $this.addClass('active');

            $parent.find('.widget-tab-content').removeClass('active');
            $parent.find('.widget-tab-content[data-tab="'+ntab+'"]').addClass('active');
        })
    });


    /******************************/
    /* DELAY CUBE ROTATE TITLES */
    /******************************/

    jQuery(document).ready(function($){

        function arrancarAnimacion(){
            $(this).addClass('active')
        }

        $('.cube-effect').each(function(){
            var $self = $(this);
            var delay = $self.data('delay');
            if (delay && delay>0) {
                setTimeout(function() { $self.find('.cube-inner').addClass('active'); }, delay);
            } else {
                $self.find('.cube-inner').addClass('active');
            }
        });

    });


    /******************************/
    /* FITTEXT */
    /******************************/


    jQuery(document).ready(function($){

        $('.aps-fittext').each(function(){
            var coef = $(this).data('fit');
            var min_font = $(this).data('min_font');
            var max_font = $(this).data('max_font');

            //console.log(coef);
            if (coef && min_font && max_font) {
                $(this).fitText(coef, { minFontSize: min_font, maxFontSize: max_font });
            } else if (coef) {
                $(this).fitText(coef);
            } else {
                $(this).fitText();
            }


        });


    });



//FINAL stric mode
})(jQuery, window);




// PLUGINS PRIMERO

/******************************/
/* JQUERY SELECTOR */
/******************************/



/*!
 * jquery.selectorquery.js 1.0
 *
 * Copyright 2013, Benjamin Intal http://gambit.ph @bfintal
 * Released under the GPL v2 License
 *
 * Date: Aug 31, 2013
 */(function(e){"use strict";e.fn.selectorQuery=function(t){var n=e.extend({widthStops:[320,480,640,960,1024,1280],classPrefix:"max-"},t);n.allClasses=n.classPrefix+n.widthStops.join(" "+n.classPrefix);return this.each(function(){var t=e(this),r=n,i=function(){clearTimeout(t.t);t.t=setTimeout(function(){t.removeClass(r.allClasses);for(var e in r.widthStops)t.addClass(t.width()+parseFloat(t.css("paddingLeft"))+parseFloat(t.css("paddingRight"))<=r.widthStops[e]&&r.classPrefix+r.widthStops[e])},100)};i();e(window).resize(function(){i()})})}})(jQuery);





/******************************/
/* FITTEXT.js */
/******************************/

/*global jQuery */
/*!
 * FitText.js 1.2
 *
 * Copyright 2011, Dave Rupert http://daverupert.com
 * Released under the WTFPL license
 * http://sam.zoy.org/wtfpl/
 *
 * Date: Thu May 05 14:23:00 2011 -0600
 */

(function( $ ){

    $.fn.fitText = function( kompressor, options ) {

        // Setup options
        var compressor = kompressor || 1,
            settings = $.extend({
                'minFontSize' : Number.NEGATIVE_INFINITY,
                'maxFontSize' : Number.POSITIVE_INFINITY
            }, options);

        return this.each(function(){

            // Store the object
            var $this = $(this);

            // Resizer() resizes items based on the object width divided by the compressor * 10
            var resizer = function () {
                $this.css('font-size', Math.max(Math.min($this.width() / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
            };

            // Call once to set.
            resizer();

            // Call on resize. Opera debounces their resize by default.
            $(window).on('resize.fittext orientationchange.fittext', resizer);

        });

    };

})( jQuery );
