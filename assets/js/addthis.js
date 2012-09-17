// Define Shop Your Way as a custom social media channel
var addthis_config = {

    'services_custom': {
        name: 'Shop Your Way',
        url: 'http://www.shopyourway.com/sharer/share?title={{title}}&link={{url}}&sourceSiteUrl={{url}}&sourceSiteAlias={{title}}',
        icon: '../img/shopyourway_large.png'
    }
};

// $(function() {
    // var delay = 200;
// 
    // function hideMenu() {
        // if (!$('.share_button').data('in') && !$('.share_menu_links').data('in') && !$('.share_menu_links').data('hidden')) {
            // $('.share_menu_links').fadeOut('fast');
            // $('.share_button').removeClass('active');
            // $('.share_menu_links').data('hidden', true);
        // }
    // }
// 
    // $('.share_button, .share_menu_links').mouseenter(function() {
        // $('.share_menu_links').fadeIn('fast');
        // $('.share_button').addClass('active');
        // $(this).data('in', true);
        // $('.share_menu_links').data('hidden', false);
    // }).mouseleave(function() {
        // $(this).data('in', false);
        // setTimeout(hideMenu, delay);
    // });
// 
// });