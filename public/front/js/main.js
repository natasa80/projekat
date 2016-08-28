jQuery(document).ready(function ($) {

    /*
     // Password toggle class - Log In page - NE RADI IZ NEKOG RAZLOGA
     $('a.aPassword').click(function () {
     $(this).toggleClass(' fa-eye fa-eye-slash ');
     
     if ($("a.aPassword").attr("class") == "fa-eye-slash") {   
     $("input.passwordField").attr("type", "text");
     } else {
     $("input.passwordField").attr("type", "password");
     }
     
     });
     
     */



// Password toggle class - Log In page
    $('a.aPassword').click(function () {
        var change = $("input.passwordField").attr('type');
        if (change == "password") {
            $("input.passwordField").attr("type", "text");
        } else {
            $("input.passwordField").attr("type", "password");
        }
        $('a.aPassword').toggleClass("fa-eye-slash");
    });

    // article LIKE / UNLIKE heart
    $('a span.fa-heart-o').click(function () {
        $(this).toggleClass(' fa-heart-o fa-heart ');
    });

    $('li.likeDetails a.fa-heart-o').click(function () {
        $(this).toggleClass(' fa-heart-o fa-heart ');
    });





    //SHOW / HIDE BASKET

    $('.shoppingCart').click(function () {
        $('.shoppingCartContent').toggle('slide');
    });

    $('.remove').click(function () {
        $(this).parent().parent().hide();

    });

    //range slider - Shop page
    $('.nstSlider').nstSlider({
        "left_grip_selector": ".leftGrip",
        "right_grip_selector": ".rightGrip",
        "value_bar_selector": ".bar",
        "value_changed_callback": function (cause, leftValue, rightValue) {
            $(this).parent().find('.leftLabel').text(leftValue);
            $(this).parent().find('.rightLabel').text(rightValue);
        }
    });

    // home page carousel slider    
    $("#testimonial-slider").owlCarousel({
        items: 1,
        itemsDesktop: [1199, 1],
        itemsDesktopSmall: [1000, 1],
        itemsTablet: [767, 1],
        pagination: false,
        navigation: true,
        navigationText: ["", ""],
        slideSpeed: 1000,
        autoPlay: false
    });


    /*  Validacija Forme - Contact page   */
    $('#mainForm').validator();

   $('.company a').click(function (){
        setTimeout(function(){ $('#menu2').validator(); }, 10);
        
    });



});