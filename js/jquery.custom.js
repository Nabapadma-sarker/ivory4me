function isIE8() {
	return jQuery.browser.msie && jQuery.browser.version == '8.0';
}

function isIE10() {
    return jQuery.browser.msie && jQuery.browser.version == '10.0';
}

function isViewportBetween( high, low ) {
    if( low == 'undefinied' )
        { low = 0; }
    
    if( !low )
        { return jQuery( window ).width() < high; }
    else
        { return jQuery( window ).width() < high && jQuery( window ).width() > low; }
}

function isLowResMonitor() {
    return jQuery( window ).width() < 1200;
}

function isTablet() {
    var device = jQuery( 'body' ).hasClass( 'responsive' ) || jQuery( 'body' ).hasClass( 'iPad' ) || jQuery( 'body' ).hasClass( 'Blakberrytablet' ) || jQuery( 'body' ).hasClass( 'isAndroidtablet' ) || jQuery( 'body' ).hasClass( 'isPalm' );
    var size   = jQuery( window ).width() <= 1024 && jQuery( window ).width() >= 768;
    
    return device && size ; 
}

function isPhone() {
    var device = jQuery( 'body' ).hasClass( 'responsive' ) || jQuery( 'body' ).hasClass( 'isIphone' ) || jQuery( 'body' ).hasClass( 'isWindowsphone' ) || jQuery( 'body' ).hasClass( 'isAndroid' ) || jQuery( 'body' ).hasClass( 'isBlackberry' );
    var size   = jQuery( window ).width() <= 480 && jQuery( window ).width() >= 320;
    
    return device && size ;
}

function isMobile() {
    return isTablet() || isPhone();
}

// In case we forget to take out console statements. IE becomes very unhappy when we forget. Let's not make IE unhappy
if(typeof(console) === 'undefined') {
    var console = {}
    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
}

(function($) {          
jQuery( document ).ready( function( $ ) {
	$('body').removeClass('no_js').addClass('yes_js');

    
    if ( isIE8() ) {
        $('*:last-child').addClass('last-child');
    }
    
    if( isIE10() ) {
        $( 'html' ).attr( 'id', 'ie10' ).addClass( 'ie' );
    }
    
    //placeholder support
    $('input[placeholder], textarea[placeholder]').placeholder();
    $('input[placeholder], textarea[placeholder]').each(function(){
    	$(this).focus(function(){
    		$(this).data('placeholder', $(this).attr('placeholder'));
    		$(this).attr('placeholder', '');
    	}).blur(function(){
    		$(this).attr('placeholder', $(this).data('placeholder'));
    	});
    });
	
	//iPad, iPhone hack
	$('.ch-item').bind('hover', function(e){});
    
    //Form fields shadow
    $( 'form input[type="text"], form textarea' ).focus(function() {
        
        //Hide label
        $( this ).parent().find( 'label.hide-label' ).hide(); 
    });
    
    $( 'form input[type="text"], form textarea' ).blur(function() {        
        //Show label
        if( $( this ).val() == '' )
            { $( this ).parent().find( 'label.hide-label' ).show(); }
    });
    
    $( 'form input[type="text"], form textarea' ).each( function() {
        //Show label
        
        if( $( this ).val() != '' && $( this ).parents( 'li' ).find( 'label.hide-label' ).is( ':visible' ) )
            { $( this ).parent().find( 'label.hide-label' ).hide(); }
    } );
    
    //Contact form labels
    $( '.contact-form [type="text"], .contact-form textarea' ).focus( function() {
        //Hide label
        $( this ).parents( 'li' ).find( 'label.hide-label' ).hide(); 
    } );
    
    $( '.contact-form [type="text"], .contact-form textarea' ).blur( function() {
        //Show label
        if( $( this ).val() == '' )
            { $( this ).parents( 'li' ).find( 'label.hide-label' ).show(); }
    } );
    
    
    
//    //Sticky Footer
    if( $( '#footer' ).length )
        { $( '#footer' ).stickyFooter(); }
    else
        { $( '#copyright' ).stickyFooter(); }
    
    //Map handler
    $( '#map-handler a' ).click( function() {
        $( '#map iframe' ).slideToggle( 400, function() {
            if( $( '#map iframe' ).is( ':visible' ) ) {
                $( '#map-handler a' ).text( l10n_handler.map_close );
            } else {
                $( '#map-handler a' ).text( l10n_handler.map_open );
            }
        });
    } );
	
	//social icon
	$('div.fade-socials a, div.fade-socials-small a').hide();
    $('div.fade-socials, div.fade-socials-small').hover(function(){
       $(this).children('a').fadeIn('slow');
    },
    function(){
       $(this).children('a').fadeOut('slow');
    });
	
    var show_dropdown = function()
    {        
        var options;              
             
        containerWidth = $('#header').width();
        marginRight = $('#nav ul.level-1 > li').css('margin-right');
        submenuWidth = $(this).find('ul.sub-menu').outerWidth();
        offsetMenuRight = $(this).position().left + submenuWidth;
        leftPos = -18;
        
        if ( offsetMenuRight > containerWidth )
            options = { left:leftPos - ( offsetMenuRight - containerWidth ) };    
        else
            options = {};
        
        $('ul.sub-menu:not(ul.sub-menu li > ul.sub-menu), ul.children:not(ul.children li > ul.children)', this).css(options).stop(true, true).fadeIn(300);    
    }
    
    var hide_dropdown = function()
    {                               
        $('ul.sub-menu:not(ul.sub-menu li > ul.sub-menu), ul.children:not(ul.children li > ul.children)', this).fadeOut(300);    
    }
        
    $('#nav ul > li').hover( show_dropdown, hide_dropdown );              
    
    $('#nav ul > li').each(function(){
        if( $('ul', this).length > 0 )
            $(this).children('a').append('<span class="sf-sub-indicator"> &raquo;</span>').css({ paddingRight:parseInt($(this).children('a').css('padding-right'))+16 });
    }); 
    
    $('#nav li:not(.megamenu) ul.sub-menu li, #nav li:not(.megamenu) ul.children li').hover(
        function()
        {         
            if ( $(this).closest('.megamenu').length > 0 )
                return; 
                        
            var options;            
            
            containerWidth = $('#header').width();
            containerOffsetRight = $('#header').offset().left + containerWidth;
            submenuWidth = $('ul.sub-menu, ul.children', this).parent().width();
            offsetMenuRight = $(this).offset().left + submenuWidth * 2;
            leftPos = -10;
            
            if ( offsetMenuRight > containerOffsetRight )
                $(this).addClass('left');
                
            $('ul.sub-menu, ul.children', this).stop(true, true).fadeIn(300);
        },
    
        function()
        {
            if ( $(this).closest('.megamenu').length > 0 )
                return; 
                        
            $('ul.sub-menu, ul.children', this).fadeOut(300);
        }
    );
    
    /* megamenu check position */
    $('#nav .megamenu').mouseover(function(){
	
		var main_container_width = $('.container').width();
		var main_container_offset = $('.container').offset();
		var parent = $(this);
		var megamenu = $(this).children('ul.sub-menu');
		var width_megamenu = megamenu.outerWidth();
		var position_megamenu = megamenu.offset();
		var position_parent = parent.position();
		
		var position_right_megamenu = position_parent.left + width_megamenu;
		
		// adjust if the right position of megamenu is out of container
		if ( position_right_megamenu > main_container_width ) {
			megamenu.offset( { top:position_megamenu.top, left:main_container_offset.left + ( main_container_width - width_megamenu ) } );		
		}		
	});          
	
	if ( $('body').hasClass('isMobile') && ! $('body').hasClass('iphone') && ! $('body').hasClass('ipad') ) {
        $('.sf-sub-indicator').parent().click(function(){   
            $(this).parent().toggle( show_dropdown, function(){ document.location = $(this).children('a').attr('href') } )
        });
    }        
	
	// remove margin from the slider, if the page is empty
	if ( $('.slider').length != 0 && $.trim( $('#primary *, #page-meta').text() ) == '' ) {
        $('.slider').attr('style', 'margin-bottom:0 !important;');
        $('#primary').remove();
    }

    // second image in hover in product list
    $(document).on('hover', 'ul.products li .thumbnail-wrapper a.thumb', function(e){
        if(e.type == 'mouseenter') {
            var img = $(this).find('img.image-hover');
            if ( img.length > 0 ) img.css({'display':'block', opacity:0}).animate({opacity:1}, 400);

        } else if ( e.type == 'mouseleave' ) {
            var img = $(this).find('img.image-hover');
            if ( img.length > 0 ) img.animate({opacity:0}, 400);
        }
    });
	
    //play, zoom on image hover
	var yit_lightbox;
	(yit_lightbox = function(){
	    jQuery('a.thumb.video, a.thumb.img, img.thumb.img, img.thumb.project, .work-thumbnail a, .three-columns li a, .onlytitle, .overlay_a img, .nozoom img').hover(
	        function()
	        {
	            jQuery(this).next('.overlay').fadeIn(500);
	            jQuery(this).next('.overlay').children('.lightbox, .details, .lightbox-video').animate({
	                bottom:'40%'
	            }, 300);
	            
	            jQuery(this).next('.overlay').children('.title').animate({
	                top:'30%'
	            }, 300);
	        }
	    );
	    
	    // image lightbox
	    $('a.thumb.video, a.thumb.img, a.thumb.videos, a.thumb.imgs, a.related_detail, a.related_proj, a.related_video, a.related_title, a.project, a.onlytitle').hover(function(){
	        $('<a class="zoom"></a>').appendTo(this).css({
	            dispay:'block', 
	            opacity:0, 
	            height:$(this).children('img').height(), 
	            width:$(this).children('img').width(),
	            'top': $(this).parents('.portfolio-filterable').length ? '-1px' : $(this).css('padding-top'),
	            'left':$(this).parents('.portfolio-filterable').length ? '-1px' : $(this).css('padding-left'),
	            padding:0}).append('<span class="title">' + $(this).attr('title') + '</span>')
	            		   .append('<span class="subtitle">' + $(this).attr('data-subtitle') + '</span>').animate({opacity:0.7}, 500);
	        },        
	        function(){           
	            $('.zoom').fadeOut(500, function(){$(this).remove()});
	        }
	    );
	    
	    $('.zoom').live('click', function(){
	    	if( $.browser.msie ) {
	    		$(this).attr('href', $(this).parent().attr('href'));
	    	}
	    });
	    
	    if ( $.colorbox ) {
    	    if( jQuery( 'body' ).hasClass( 'isMobile' ) ) {
    	        jQuery("a.thumb.img, .overlay_img, .section .related_proj, a.ch-info-lightbox").colorbox({
    	            transition:'elastic',
    	            rel:'lightbox',
    	    		fixed:true,
    	    		maxWidth: '100%',
    	    		maxHeight: '100%',
    	    		opacity : 0.7
    	        });
    	        
    	        jQuery(".section .related_lightbox").colorbox({
    	            transition:'elastic',
    	            rel:'lightbox2',
    	    		fixed:true,
    	    		maxWidth: '100%',
    	    		maxHeight: '100%',
    	    		opacity : 0.7
    	        });
    	    } else {
    	        jQuery("a.thumb.img, .overlay_img, .section.portfolio .related_proj, a.ch-info-lightbox, a.ch-info-lightbox").colorbox({
    	            transition:'elastic',
    	            rel:'lightbox',
    	    		fixed:true,
    	    		maxWidth: '80%',
    	    		maxHeight: '80%',
    	    		opacity : 0.7
    	        });   
    	        
    	        jQuery(".section.portfolio .related_lightbox").colorbox({
    	            transition:'elastic',
    	            rel:'lightbox2',
    	    		fixed:true,
    	    		maxWidth: '80%',
    	    		maxHeight: '80%',
    	    		opacity : 0.7
    	        });   
    	    }
	    
    	    jQuery("a.thumb.video, .overlay_video, .section.portfolio .related_video, a.ch-info-lightbox-video").colorbox({
    	        transition:'elastic',
    	        rel:'lightbox',
    			fixed:true,
    			maxWidth: '60%',
    			maxHeight: '80%',
    	        innerWidth: '60%',
    	        innerHeight: '80%',
    			opacity : 0.7,
    	        iframe: true,
    	        onOpen: function() { $( '#cBoxContent' ).css({ "-webkit-overflow-scrolling": "touch" }) }
    	    });
    	    jQuery(".section.portfolio .lightbox_related_video").colorbox({
    	        transition:'elastic',
    	        rel:'lightbox2',
    			fixed:true,
    			maxWidth: '60%',
    			maxHeight: '80%',
    	        innerWidth: '60%',
    	        innerHeight: '80%',
    			opacity : 0.7,
    	        iframe: true,
    	        onOpen: function() { $( '#cBoxContent' ).css({ "-webkit-overflow-scrolling": "touch" }) }
    	    });   
    	}
	})();      
            
              
    //FILTERABLE
    if( $('.portfolio-filterable').length > 0 ) {
    	$('.gallery-categories-disabled, .portfolio-categories-disabled').addClass('gallery-categories-quicksand');
    }
    
    
    $(".gallery-wrap .internal_page_item .overlay, .section .related_project .overlay").css({opacity:0});
	$(".gallery-wrap .internal_page_item, .section .related_project > div").live( 'mouseover mouseout', function(event){ 
		if ( event.type == 'mouseover' ) $('.overlay', this).show().stop(true,false).animate({ opacity: .7 }, "fast"); 
		if ( event.type == 'mouseout' )  $('.overlay', this).animate({ opacity: 0 }, "fast", function(){ $(this).hide() }); 
	});
	
  	$('.picture_overlay').hover(function(){
  		
	  	var width = $(this).find('.overlay div').innerWidth();
	  	var height =  $(this).find('.overlay div').innerHeight();
	  	var div = $(this).find('.overlay div').css({
	  		'margin-top' : - height / 2,
	  		'margin-left' : - width / 2
	  	});
  		
		if(isIE8()) {
  			$(this).find('.overlay > div').show();
  		}
  	}, function(){
  		
  		if(isIE8()) {
  			$(this).find('.overlay > div').hide();
  		}
  	}).each(function(){
	  	var width = $(this).find('.overlay div').innerWidth();
	  	var height =  $(this).find('.overlay div').innerHeight();
	  	var div = $(this).find('.overlay div').css({
	  		'margin-top' : - height / 2,
	  		'margin-left' : - width / 2
	  	});
	});


	//masonry pinterest blog style
	if ( $.fn.masonry ) {
    	var container = $('#pinterest-container');
    	container.imagesLoaded( function(){
    		container.masonry({
    		  itemSelector: '.post'
    		});
    	});
    	
    	$(window).resize(function(){
    		$('#pinterest-container').masonry({
    		  itemSelector: '.post'
    		});
    	});

        var page_meta_height = $( '#page-meta').outerHeight();
        $( '#pinterest-container .post').each( function( i ) {
            if( i > 2 ) { return; }

            $( this ).css( 'margin-top', page_meta_height + 20 + 'px' ); //20px margin
            i++;
        });
    }

    $( '.yit_toggle_menu ul.menu.open_first > li:first-child, .yit_toggle_menu ul.menu.open_all .dropdown, .yit_toggle_menu ul.menu.open_active > li.current-menu-parent' ).addClass( 'opened' );
    
    //toggle menu widget
    $( '.yit_toggle_menu ul li.dropdown > a' ).click( function( e ) {
        e.preventDefault();
        var dropdown = $( this ).next( 'ul' );
        var dropdown_parent = dropdown.parent( '.dropdown' );
        
        dropdown.width( dropdown_parent.width() );
        dropdown_parent.width( dropdown_parent.parent().width() );
        
        if( dropdown_parent.hasClass( 'opened' ) )
            { dropdown_parent.removeClass( 'opened' ); }
        else
            { dropdown_parent.addClass( 'opened' ); }
        
        dropdown.slideToggle();
    });


    function dropdown_widget_nav() {

        $('.widget.yith-woo-ajax-navigation h3').each(function () {

            var header = $(this);
            var widget = $(this).parent();
            var ul = widget.find('> ul.yith-wcan');

            if (ul.length != 0) {

                header.css('cursor', 'pointer');

                //init widget
                if (!widget.hasClass('closed') && !widget.hasClass('opened')) {
                    widget.addClass('opened');
                }
                if (widget.hasClass('closed')) {
                    ul.hide();
                    header.append('<div class="plus">+</div>');
                }
                if (widget.hasClass('opened')) {
                    header.append('<div class="minus">-</div>');
                }
            }
        });
    }

    $(document).on('yith-wcan-ajax-filtered', dropdown_widget_nav);

    
    var $add_to_cart_clicked = false;
    
    // add to cart click
    $('ul.products li.product').on( 'click', '.add_to_cart_button', function(){
        var $this_product = $(this).parents('li.product');

        if (yit_woocommerce.load_gif != 'undefined') {
            $this_product.filter('.grid').removeClass('added').find('.product-thumbnail').block({message: null, overlayCSS: {background: '#fff url(' + yit_woocommerce.load_gif + ') no-repeat center', opacity: 0.3, cursor:'none'}});
        }  else {
            $this_product.filter('.grid').removeClass('added').find('.product-thumbnail').block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.ajax_loader_url.substring(0, woocommerce_params.ajax_loader_url.length - 7) + '.gif) no-repeat center', opacity: 0.3, cursor:'none'}});
        }

        $add_to_cart_clicked = $(this);
    });
    $('body').on( 'added_to_cart', function(){
        //$('html, body').delay(500).animate({scrollTop:0}, 'slow');
        
        if ( $add_to_cart_clicked != false ) {        
            var $this_product = $add_to_cart_clicked.parents('li.product');

            $this_product.find('.product-thumbnail').unblock();

            $this_product.find('.product-actions .add_to_cart_button').removeClass('added');
            $this_product.addClass('added');
            $this_product.find('.added').fadeIn(500);
        }
    } );
    
    // shop style switcher
    $('#content-shop .list-or-grid a').on( 'click', function(){
        var actual_view = $(this).attr('class').replace( '-view', '' );
        
        if( isIE8() ) {
            actual_view = actual_view.replace( ' last-child', '' );
        }
        
        $('#content-shop ul.products li:not(.category)').removeClass('list grid').addClass( actual_view );
        $(this).parent().find('a').removeClass('active');
        $(this).addClass('active');
        
        $.cookie(yit_shop_view_cookie, actual_view);
        
        return false;
    });

    var carouFredSelOptions_defaults = {
        responsive: false,
        auto: true,
        items: 4,
        circular: true,
        infinite: true,
        debug: false,
        prev: '.es-nav .es-nav-prev',
        next: '.es-nav .es-nav-next',
        swipe: {
            onTouch: false
        },
        scroll : {
            items     : 1,
            pauseOnHover: true
        }
    };
    if( $.fn.carouFredSel ) {
        $('.products-slider-wrapper').each(function(index, current){
            $(this).imagesLoaded(function(){
                current = $(current); // Be sure current is a jquery object
                var items = carouFredSelOptions_defaults.items;

                if( $(this).parents('.border-box').length == 0 ) {
                    var carouFredSel;

                    var prev = current.find('.es-nav-prev').show();
                    var next = current.find('.es-nav-next').show();

                    carouFredSelOptions_defaults.prev = prev;
                    carouFredSelOptions_defaults.next = next;


                    if( $('body').outerWidth() <= 767 ) {
                        current.find('li').each(function(){
                            $(this).width( current.width() );
                        });

                        carouFredSelOptions_defaults.items = 1;
                    } else {
                        current.find('li').each(function(){
                            $(this).attr('style', '');
                        });

                        carouFredSelOptions_defaults.items = items;
                    }

                    carouFredSel = current.find('.products').carouFredSel( carouFredSelOptions_defaults );

                    $(window).resize(function(){
                        carouFredSel.trigger('destroy', false).attr('style','');

                        if( $('body').outerWidth() <= 767 ) {
                            current.find('li').each(function(){
                                $(this).width( current.width() );
                            });

                            carouFredSelOptions_defaults.items = 1;
                        } else {
                            current.find('li').each(function(){
                                $(this).attr('style', '');
                            });

                            carouFredSelOptions_defaults.items = items;
                        }

                        carouFredSel.carouFredSel(carouFredSelOptions_defaults);

                        current.find('.es-nav-prev, .es-nav-next').removeClass('hidden').show();
                    });
                }
            });
        });
        $('.es-nav-prev, .es-nav-next').removeClass('hidden').show();
    }

    // replace type="number" in opera
    $('.opera .quantity input.input-text.qty').replaceWith(function() {
    	$(this).attr('value')
    	return '<input type="text" class="input-text qty text" name="quantity" value="' + $(this).attr('value') + '" />';
    });

    // hide #back-top first
    $("#back-top").hide();

    // fade in #back-top
    $(function () {
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('#back-top').fadeIn();
            } else {
                $('#back-top').fadeOut();
            }
        });

        // scroll body to 0px on click
        $('#back-top a').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });



    // compare button click
    var product_compare, open_window = false;
    $('.product .woo_bt_compare_this').on( 'click', function(){
        open_window = true;
        product_compare = $(this).parents('.product');
        product_compare.block({message: null, overlayCSS: {background: '#fff url(' + woocommerce_params.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6, cursor:'none'}});
    });

    if( typeof woo_comparable_open_compare_type != 'undefined' ) {
        $('body').on( 'woo_update_total_compare_list', function(){
            product_compare.unblock();
            if ( open_window && woo_comparable_open_compare_type == 'window' ) {
                $(".woo_compare_button_go").trigger('click');
            } else if ( woo_comparable_open_compare_type == 'new_page' ) {
                // aprire pagina in una nuova scheda
                var win=window.open(woo_compare_url, '_blank');
                win.focus();
            }
        });
        if ( woo_comparable_open_compare_type == 'new_page' ) {
            $('.woo_compare_button_go a').on('click', function(){
                var win=window.open(woo_compare_url, '_blank');
                win.focus();
            });
        }
    }

    //emulate jquery live to preserve jQuery.live() call
    if( typeof jQuery.fn.live == 'undefined' ) {
        jQuery.fn.live = function( types, data, fn ) {
            jQuery( this.context ).on( types, this.selector, data, fn );
            return this;
        };
    }


    //shop sidebar
    $('#sidebar-shop-sidebar .widget h3').prepend('<div class="minus" />').find('.minus').text('-');
    $('#sidebar-shop-sidebar .widget').on('click', 'h3', function(){
        $(this).parent().find('> *:not(h3)').slideToggle();

        if( $(this).find('div').hasClass('minus') ) {
            $(this).find('div').removeClass('minus').addClass('plus').text('+');
        } else {
            $(this).find('div').removeClass('plus').addClass('minus').text('-');
        }
    });
});

})(jQuery);

// sticky footer plugin
(function($){
    var footer;

    $.fn.extend({
        stickyFooter: function(options) {
            footer = this;

            positionFooter();

            $(window)
                .on('sticky', positionFooter)
                .scroll(positionFooter)
                .resize(positionFooter);

            function positionFooter() {
                var docHeight = $(document.body).height() - $("#sticky-footer-push").height();

                if(docHeight < $(window).height()){
                    var diff = $(window).height() - docHeight;
                    if (!$("#sticky-footer-push").length > 0) {
                        $(footer).before('<div id="sticky-footer-push"></div>');
                    }

                    if( $('#wpadminbar').length > 0 ) {
                        diff -= 28;
                    }
                    $("#sticky-footer-push").height(diff);
                }
            }
        }
    });
})(jQuery);



(function($){
	$("#header-sidebar li.dropdown").hover(
		function(){ $('ul',this).fadeIn(); }, 
		function(){ $('ul',this).fadeOut(); }
	);
})(jQuery);