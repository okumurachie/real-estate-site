<?php
function res_register_post_types() {
    register_post_type('property', [
        'labels' => [
            'name'          => '物件',
            'singular_name' => '物件',
        ],
        'public'       => true,
        'has_archive'  => true,          // 一覧ページ(アーカイブ)を有効にする
        'rewrite'      => ['slug' => 'property'],
        'menu_icon'    => 'dashicons-admin-home',
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'res_register_post_types');
