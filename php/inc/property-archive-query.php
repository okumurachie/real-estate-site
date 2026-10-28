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
        'tax'       => [
            'area'    => 'property_area',
            'type'    => 'property_type',
            'feature' => 'property_feature',
        ],
        // price は「万円」の数値で保存している想定
        'meta'      => [
            'price' => 'price',
            'plan'  => 'layout',
            'bld'   => 'building_area',
            'land'  => 'land_area',
        ],
        'plans'     => ['2LDK', '3LDK', '4LDK', '5LDK~'],
        'prices'    => [1000, 1500, 2000, 2500, 3000, 3500, 4000, 5000],
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
 * @return array{area:string[],type:string[],plan:string[],feature:string[],price_min:int,price_max:int,sort:string,is_fav:bool,fav_ids:int[]}
 */
function res_get_property_search_params(): array
{
    $favs_raw = isset($_GET['favs']) ? sanitize_text_field(wp_unslash($_GET['favs'])) : null;

    return [
        'area'      => res_get_query_array('area'),
        'type'      => res_get_query_array('type'),
        'plan'      => res_get_query_array('plan'),
        'feature'   => res_get_query_array('feature'),
        'price_min' => absint($_GET['price_min'] ?? 0),
        'price_max' => absint($_GET['price_max'] ?? 0),
        'sort'      => sanitize_key($_GET['orderby'] ?? 'new'),
        'is_fav'    => null !== $favs_raw,
        'fav_ids'   => $favs_raw ? array_values(array_filter(array_map('absint', explode(',', $favs_raw)))) : [],
    ];
}

/** 公開中の物件数 */
function res_get_property_total(): int
{
    $cfg = res_property_search_config();
    return (int) wp_count_posts($cfg['post_type'])->publish;
}

/** 本日更新された物件の件数 */
function res_get_today_updated_count(): int
{
    $cfg = res_property_search_config();
    $q = new WP_Query([
        'post_type'      => $cfg['post_type'],
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'date_query'     => [['after' => 'today', 'column' => 'post_modified']],
    ]);
    return (int) $q->found_posts;
}

/** メインクエリ（物件アーカイブ）に検索条件を反映 */
add_action('pre_get_posts', function ($q) {
    $cfg = res_property_search_config();
    if (is_admin() || ! $q->is_main_query() || ! is_post_type_archive($cfg['post_type'])) {
        return;
    }

    $s = res_get_property_search_params();
    $q->set('posts_per_page', $cfg['per_page']);

    // タクソノミー
    $tax_query = ['relation' => 'AND'];
    foreach (['area', 'type'] as $k) {
        if ($s[$k]) {
            $tax_query[] = ['taxonomy' => $cfg['tax'][$k], 'field' => 'slug', 'terms' => $s[$k]];
        }
    }
    if ($s['feature']) {
        $tax_query[] = ['taxonomy' => $cfg['tax']['feature'], 'field' => 'slug', 'terms' => $s['feature'], 'operator' => 'AND'];
    }
    if (count($tax_query) > 1) {
        $q->set('tax_query', $tax_query);
    }

    // カスタムフィールド
    $meta_query = ['relation' => 'AND'];
    if ($s['price_min']) $meta_query[] = ['key' => $cfg['meta']['price'], 'value' => $s['price_min'], 'compare' => '>=', 'type' => 'NUMERIC'];
    if ($s['price_max']) $meta_query[] = ['key' => $cfg['meta']['price'], 'value' => $s['price_max'], 'compare' => '<=', 'type' => 'NUMERIC'];
    if ($s['plan']) {
        $vals = [];
        foreach ($s['plan'] as $p) {
            $vals = array_merge($vals, '5LDK~' === $p ? ['5LDK', '6LDK', '7LDK'] : [$p]);
        }
        $meta_query[] = ['key' => $cfg['meta']['plan'], 'value' => $vals, 'compare' => 'IN'];
    }
    if (count($meta_query) > 1) {
        $q->set('meta_query', $meta_query);
    }

    // お気に入りのみ
    if ($s['is_fav']) {
        $q->set('post__in', $s['fav_ids'] ?: [0]);
    }

    // 並び替え
    switch ($s['sort']) {
        case 'low':
            $q->set('meta_key', $cfg['meta']['price']);
            $q->set('orderby', 'meta_value_num');
            $q->set('order', 'ASC');
            break;
        case 'high':
            $q->set('meta_key', $cfg['meta']['price']);
            $q->set('orderby', 'meta_value_num');
            $q->set('order', 'DESC');
            break;
        case 'area':
            $q->set('meta_key', $cfg['meta']['bld']);
            $q->set('orderby', 'meta_value_num');
            $q->set('order', 'DESC');
            break;
        default:
            $q->set('orderby', 'date');
            $q->set('order', 'DESC');
    }
});
