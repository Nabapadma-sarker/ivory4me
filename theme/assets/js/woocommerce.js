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

});

