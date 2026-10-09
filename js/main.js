$(function () {
    /*=================================================
    ハンバーガーメニュー
    ===================================================*/
    const $header = $('header');

    $('.hamburger').on('click', function () {
        $header.addClass('is-animating').toggleClass('open');
    });

    $('#global-nav a').on('click', function () {
        $header.removeClass('open is-animating');
    });

    $('.header-navi ul').on('transitionend', function (e) {
        if (e.target === this && e.originalEvent.propertyName === 'transform') {
            $header.removeClass('is-animating');
        }
    });
});
