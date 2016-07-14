function c(par){
    return console.log(par);
}

jQuery(document).ready(function() {
    /*show small cart*/
    if (jQuery('.cart_pos').html() > 0) {
        jQuery('.small-cart').show();
    };
    /*size-span add bootstrap class*/
    jQuery('.size-span').hover(
        function(){
            jQuery(this).addClass('bg-primary');
        },
        function(){
            jQuery(this).removeClass('bg-primary');
        }
    );
    /*admin*/
    if (jQuery('#current_cat').html() > 0) {
        var cat = jQuery('#current_cat').text();
        c(cat);
        jQuery('#'+cat).addClass('bg-primary');
    };
    /*lightgallery*/
    jQuery('#lightgallery').lightGallery({
        thumbnail:true,
        animateThumb: true,
        showThumbByDefault: false,
        selector: '.selector'
    });
    /*scrolltop*/
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $('.scrollup').click(function(){
        $("html, body").animate({ scrollTop: 0 }, 600);
        return false;
    });

});

    function AddSizeToField(elem){
        var div = jQuery(elem).parent().parent().find('.size-class');
        div.append('<div class="size-row"><div class="size-label">'+jQuery(elem).html()+'</div><input class="size-inp" type="number" name="amount" id="amount" value="1"> <span class="glyphicon glyphicon-remove size-icon" aria-hidden="true" id="remove" onclick="RemoveSizeInput(this)"></span></div>');
        div.find('.size-inp').select();
    }

    function RemoveSizeInput(elm){
        jQuery(elm).parent().remove();
    }