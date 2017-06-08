jQuery(document).ready(function ($) {

    if ($('body').hasClass('single-product')) {
        setTimeout(function () {

            if ($.trim($('.usermessagea').html()) != '' || $.trim($('.contact-form li div.msg-error').text()) != '') {
                $('div.woocommerce-tabs .tabs li').removeClass('active');
                $('div.woocommerce-tabs .panel').css('display', 'none');
                $('div.woocommerce-tabs .tabs li.info_tab').addClass('active');
                $('#tab-info').css('display', 'block');
            }
        }, 200);

    }

    if(parseFloat(yit_woocommerce.version) >= 3.0) {
        var flexViewport = $('.flex-viewport');
        if( $('body').hasClass('single-product') && ( flexViewport.length != 0 ) ){
            var wcProductGalleryWrapper = $('.product').find('.woocommerce-product-gallery__wrapper');
            var onSaleBadge = wcProductGalleryWrapper.find('.on_sale_wrap');
            onSaleBadge.remove();
            flexViewport.append(onSaleBadge);
        }

    }

});

