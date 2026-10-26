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

    /*=================================================
    スクロール時の物件画像フェード表示
    ===================================================*/
    const $cards = $('.property-card');

    function fadeInCards() {
        const scroll = $(window).scrollTop();
        const windowHeight = $(window).height();

        $cards.not('.is-visible').each(function () {
            const target = $(this).offset().top;
            if(scroll > target - windowHeight + 200) {
                $(this).addClass('is-visible');
            }
        });
    }

    $(window).on('scroll', fadeInCards);
    $(window).on('load', fadeInCards);
});
