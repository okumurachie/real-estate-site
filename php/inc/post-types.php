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
        'property_area'    => ['label' => 'エリア',       'hierarchical' => true,  'slug' => 'area'],
        'property_type'    => ['label' => '物件種別',     'hierarchical' => true,  'slug' => 'type'],
        'property_feature' => ['label' => 'こだわり条件', 'hierarchical' => false, 'slug' => 'feature'],
        'property_label'   => ['label' => 'ラベル',       'hierarchical' => false, 'slug' => 'label'],
    ];
    foreach ($taxonomies as $taxonomy_name => $settings) {
        register_taxonomy($taxonomy_name, 'property', [
            'label'        => $settings['label'],
            'hierarchical' => $settings['hierarchical'],
            'public'       => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'rewrite'      => ['slug' => $settings['slug']],
        ]);
    }
}
add_action('init', 'res_register_post_types');
