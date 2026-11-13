<?php

/**
 * 物件一覧：検索設定 / 検索パラメータ / メインクエリの絞り込み
 * functions.php 等に追記:
 *   require_once get_template_directory() . '/php/inc/property-archive-query.php';
 *
 * ※ 投稿タイプ / タクソノミー / フィールド名は、実際の登録名に合わせて下の配列を直してください。
 */

/** 検索まわりの設定 */
function res_property_search_config(): array
{
    return [
        'post_type' => 'property',
        'per_page'  => 6,
        'taxonomies'       => [
            'area'    => 'property_area',
            'type'    => 'property_type',
            'feature' => 'property_feature',
        ],
        // price は「万円」の数値で保存している想定
        'meta_keys'      => [
            'price' => 'price',
            'plan'  => 'layout',
            'building_area'   => 'building_area',
            'land_area'  => 'land_area',
            'floor_area' => 'floor_area',
        ],
        'plan_options'     => ['2LDK', '3LDK', '4LDK', '5LDK~'],
        'price_options'    => [1000, 1500, 2000, 2500, 3000, 3500, 4000, 5000],
    ];
}

/** GET の配列パラメータを安全に取得 */
function res_get_query_array(string $key): array
{


    return isset($_GET[$key]) ? array_map('sanitize_text_field', (array) wp_unslash($_GET[$key])) : [];
}

/**
 * URLの検索条件をまとめて返す（クエリ側・表示側の両方で使う）
 *
 * @return array{area:string[],type:string[],plan:string[],feature:string[],price_min:int,price_max:int,sort_by:string,favorites_only:bool,favorite_ids:int[]}
 */
function res_get_property_search_params(): array
{
    $favorites_text = isset($_GET['favorites']) ? sanitize_text_field(wp_unslash($_GET['favorites'])) : null;
    $favorite_ids = [];
    if ($favorites_text) {
        $favorite_ids = array_values(array_filter(array_map('absint', explode(',', $favorites_text))));
    }

    return [
        'area'      => res_get_query_array('area'),
        'type'      => res_get_query_array('type'),
        'plan'      => res_get_query_array('plan'),
        'feature'   => res_get_query_array('feature'),
        'price_min' => absint($_GET['price_min'] ?? 0),
        'price_max' => absint($_GET['price_max'] ?? 0),
        'sort_by'      => sanitize_key($_GET['orderby'] ?? 'new'),
        'favorites_only'    => null !== $favorites_text,
        'favorite_ids'   => $favorite_ids,
    ];
}

/** 公開中の物件数 */
function res_get_property_total(): int
{
    $config = res_property_search_config();
    return (int) wp_count_posts($config['post_type'])->publish;
}

/** 本日更新された物件の件数 */
function res_get_today_updated_count(): int
{
    $config = res_property_search_config();
    $today_query = new WP_Query([
        'post_type'      => $config['post_type'],
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'date_query'     => [['after' => 'today', 'column' => 'post_modified']],
    ]);
    return (int) $today_query->found_posts;
}

/** メインクエリ（物件アーカイブ）に検索条件を反映 */
add_action('pre_get_posts', function ($query) {
    $config = res_property_search_config();
    if (is_admin() || ! $query->is_main_query() || ! is_post_type_archive($config['post_type'])) {
        return;
    }

    $search = res_get_property_search_params();
    $query->set('posts_per_page', $config['per_page']);

    // タクソノミー
    $tax_query = ['relation' => 'AND'];
    foreach (['area', 'type'] as $search_key) {
        if ($search[$search_key]) {
            $tax_query[] = [
                'taxonomy' => $config['taxonomies'][$search_key],
                'field' => 'slug',
                'terms' => $search[$search_key],
            ];
        }
    }
    if ($search['feature']) {
        $tax_query[] = [
            'taxonomy' => $config['taxonomies']['feature'],
            'field' => 'slug',
            'terms' => $search['feature'],
            'operator' => 'AND',
        ];
    }
    if (count($tax_query) > 1) {
        $query->set('tax_query', $tax_query);
    }

    // カスタムフィールド
    $meta_query = ['relation' => 'AND'];
    $price_key = $config['meta_keys']['price'];

    if ($search['price_min']) $meta_query[] = ['key' => $config['meta']['price'], 'value' => $search['price_min'], 'compare' => '>=', 'type' => 'NUMERIC'];
    if ($search['price_max']) $meta_query[] = ['key' => $config['meta']['price'], 'value' => $search['price_max'], 'compare' => '<=', 'type' => 'NUMERIC'];
    if ($search['plan']) {
        $plan_values = [];
        foreach ($search['plan'] as $selected_plan) {
            $plan_values = array_merge(
                $plan_values,
                '5LDK~' === $selected_plan ? ['5LDK', '6LDK', '7LDK'] : [$selected_plan]);
        }
        $meta_query[] = ['key' => $config['meta_keys']['plan'], 'value' => $plan_values, 'compare' => 'IN'];
    }
    if (count($meta_query) > 1) {
        $query->set('meta_query', $meta_query);
    }

    // お気に入りのみ
    if ($search['favorites_only']) {
        $query->set('post__in', $search['favorite_ids'] ?: [0]);
    }

    // 並び替え
    switch ($search['sort_by']) {
        case 'low':
            $query->set('meta_key', $price_key);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'ASC');
            break;
        case 'high':
            $query->set('meta_key', $price_key);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
            break;
        case 'area':
            $query->set('meta_key', $config['meta_keys']['building_area']);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', 'DESC');
            break;
        default:
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
    }
});
