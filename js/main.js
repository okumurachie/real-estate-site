$(function () {
    /*=================================================
    ハンバーガーメニュー
    ===================================================*/
    $('.hamburger').on('click', function () {
        $('header').toggleClass('open');
    });

    $('#layer').on('click', function () {
        $('header').removeClass('open');
    });

    $('#global-nav a').on('click', function () {
        $('header').removeClass('open');
    });
});
