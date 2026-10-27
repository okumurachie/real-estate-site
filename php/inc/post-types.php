<?php
function res_register_post_types()
{
    register_post_type('property', [
        'labels' => [
            'name'          => '物件',
            'singular_name' => '物件',
            'add_new_item'  => '物件を追加',
            'edit_item'     => '物件を編集',
            'all_items'     => '物件一覧',
        ],
        'public'       => true,
        'has_archive'  => true,          // 一覧ページ(アーカイブ)を有効にする
        'rewrite'      => ['slug' => 'property'],
        'menu_icon'    => 'dashicons-admin-home',
        'menu_position' => 5,
        'supports'     => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    $taxonomies = [
        'property_area'    => 'エリア',
        'property_type'    => '物件種別',
        'property_feature' => 'こだわり条件',
        'property_label'   => 'ラベル',
    ];
    foreach ($taxonomies as $slug => $label) {
        register_taxonomy($slug, 'property', [
            'label'        => $label,
            'hierarchical' => false,
            'public'       => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'rewrite'      => ['slug' => $slug],
        ]);
    }
}
add_action('init', 'res_register_post_types');
