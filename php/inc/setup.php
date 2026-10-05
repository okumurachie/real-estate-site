<?php
function res_theme_setup() {
    // サムネイル設定を有効化
    add_theme_support('post-thumbnails');

    // タイトルタグを有効化
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'res_theme_setup');
