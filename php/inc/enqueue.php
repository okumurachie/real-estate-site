<?php
function res_add_favicon()
{
    echo '<link rel="shortcut icon" href="' . esc_url(get_template_directory_uri() . '/img/favicon.ico') . '">' . "\n";
}
add_action('wp_head', 'res_add_favicon');

function res_enqueue_styles()
{
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_register_style(
        'reset_style',
        'https://unpkg.com/ress/dist/ress.min.css',
        array(),
        '1.0'
    );

    wp_register_style(
        'google_fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Noto+Serif+JP:wght@400;500;700&display=swap',
        array(),
        null
    );

    // main.cssを最後に実行
    wp_enqueue_style(
        'main_style',
        $uri . '/css/main.css',
        array('reset_style', 'google_fonts'),
        filemtime($dir . '/css/main.css')
    );
}
add_action('wp_enqueue_scripts', 'res_enqueue_styles');

function res_enqueue_scripts()
{
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_deregister_script('jquery');
    wp_register_script(
        'jquery',
        'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js',
        array(),
        '3.6.0'
    );

    wp_enqueue_script(
        'main_script',
        $uri . '/js/main.js',
        array('jquery'),
        filemtime($dir . '/js/main.js'),
        true
    );

    if (is_front_page()) {
        wp_enqueue_script(
            'front_hero_script',
            $uri . '/js/front-hero.js',
            array('jquery'),
            filemtime($dir . '/js/front-hero.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'res_enqueue_scripts');
