<?php
function res_enqueue_assets() {
    $dir = get_template_directory();
    $uri = get_template_directory_uri();

    wp_enqueue_style('res-main', $uri . '/css/main.css', [], filemtime($dir . '/css/main.css'));
    wp_enqueue_script('res-main', $uri . '/js/main.js', [], filemtime($dir . '/js/main.js'), true);
}
add_action('wp_enqueue_scripts', 'res_enqueue_assets');
