jQuery(function ($) {

    $('[data-toggle="tooltip"]').tooltip();

    $('.albumColumn').each(function () {
        $(this).find('i').click(function () {
            $(this).parent().find('.editSubmitButton').fadeIn();
        });
    });

    //Accordian
    $('.accordianItemActive').find('.accordianItemContents').show();
    $('.accordianItem, .accordianItemActive').each(function () {
        $(this).find('.accordianItemHeader').click(function () {
            $(this).parent().toggleClass('accordianItemActive');
            $(this).next().slideToggle();

            $('.articlePosts').isotope({
                itemSelector: '.articlePost'
            });
        });
    });

    //Isotop
    $(window).load(function () {
//        $('.articlePosts').isotope({
//            itemSelector: '.articlePost'
//        });
    });

    //dashboard Sub Menu
    $('.dashboardTabMenu li').each(function () {
        $(this).find('ul').prev('a').click(function () {
            $(this).next('ul.tabSubMenu').stop().slideToggle('fast');
        });
    });
    $('.dashboardTabMenu li').each(function () {
        $(this).click(function () {
            $('ul.tabSubMenu li').removeClass('active');
        });
    });
    $('ul.tabSubMenu li').each(function () {
        $(this).click(function () {
            $('.dashboardTabMenu li').removeClass('active');
        });
    });

    //Mobile Tab
    $('.manageButton').click(function () {
        $(this).find('i').toggleClass('fa-angle-down fa-angle-up');
        $('.dashboardTabMenu').stop().slideToggle('fast');
    });

});