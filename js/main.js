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

    /*=================================================
    お気に入りボタンのon/off切り替え
    ===================================================*/
    $(document).on('click', '.js-fav', function () {
        const $btn = $(this);
        const isPressed = $btn.attr('aria-pressed') === 'true';
        if (isPressed) {
            $btn.attr('aria-pressed', 'false');
        } else {
            $btn.attr('aria-pressed', 'true');
        }
    });
});
