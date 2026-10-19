<?php

/**
 * 物件データの取得・整形
 *
 * property-card.php / 物件詳細 / お気に入り一覧 など、
 * 物件情報を表示する箇所で共通して使う。
 *
 * 使い方:
 *   $p = res_get_property_data( get_the_ID() );
 *   echo esc_html( $p['price_text'] );
 *
 * ※ 戻り値は「表示用に整えた値」。出力時は必ずエスケープすること。
 */

/**
 * ACF未作成の間のダミー値を出すかどうか。
 * WP_DEBUG が true のときだけ有効。ACF作成後、見た目確認が不要になったら
 * res_property_use_dummy() ごと削除してOK。
 */
function res_property_use_dummy(): bool
{
    return defined('WP_DEBUG') && WP_DEBUG;
}

/**
 * ACFの値を取得する。値が空ならダミー値（デバッグ時のみ）を返す。
 *
 * @param string $key   ACFフィールド名
 * @param int    $post_id
 * @param mixed  $dummy ダミー値
 * @return mixed
 */
function res_get_property_field(string $key, int $post_id, $dummy = '')
{
    if (function_exists('get_field')) {
        $value = get_field($key, $post_id);
        if (null !== $value && '' !== $value && false !== $value) {
            return $value;
        }
    }
    return res_property_use_dummy() ? $dummy : '';
}

/**
 * タクソノミーのターム名を配列で取得する。空ならダミー（デバッグ時のみ）。
 *
 * @return string[]
 */
function res_get_property_terms(string $taxonomy, int $post_id, array $dummy = []): array
{
    $terms = get_the_terms($post_id, $taxonomy);
    if (is_array($terms) && $terms) {
        return wp_list_pluck($terms, 'name');
    }
    return res_property_use_dummy() ? $dummy : [];
}

/**
 * 物件1件分の表示用データを返す。
 *
 * @return array<string, mixed>
 */
function res_get_property_data(int $post_id): array
{

    // 価格（万円単位の数値で保存 → 表示時にカンマ区切り）
    $price      = res_get_property_field('price', $post_id, 3480);
    $price_text = ('' !== $price) ? number_format((float) $price) : '';

    // 間取り
    $layout = res_get_property_field('layout', $post_id, '3LDK');

    // 面積（戸建ては建物面積、マンションは専有面積を優先）
    $building_area = res_get_property_field('building_area', $post_id, 92);
    $floor_area    = res_get_property_field('floor_area', $post_id, '');
    $land_area     = res_get_property_field('land_area', $post_id, 120);
    $main_area     = $building_area ?: $floor_area;
    $main_area_txt = $main_area ? $main_area . '㎡' : '';
    $land_area_txt = $land_area ? $land_area . '㎡' : '';

    // 築年数（built_year = 西暦）
    $built_year = res_get_property_field('built_year', $post_id, (int) wp_date('Y') - 8);
    $built_text = '';
    if ($built_year) {
        $age        = max(0, (int) wp_date('Y') - (int) $built_year);
        $built_text = $age ? '築' . $age . '年' : '新築';
    }

    // タクソノミー
    $labels        = res_get_property_terms('property_label', $post_id, ['新築物件', '駅チカ物件']);
    $features      = res_get_property_terms('property_feature', $post_id, ['日当たり良好']);
    $feature_first = $features[0] ?? '';

    // スペック1行（空の項目は自動で飛ばす）  例: 日当たり良好 ・ 3LDK ・ 築8年 ・ 92㎡
    $spec_line = implode(' ・ ', array_filter([$feature_first, $layout, $built_text, $main_area_txt]));

    // アクセス  例: JR南草津駅 徒歩8分
    $station     = res_get_property_field('station', $post_id, 'JR南草津駅');
    $access_type = res_get_property_field('access_type', $post_id, 'walk'); // walk / bus
    $access_min  = res_get_property_field('access_min', $post_id, 8);
    $access_text = '';
    if ($station) {
        $access_text = $station;
        if ($access_min) {
            $access_text .= ' ' . ('bus' === $access_type ? 'バス' : '徒歩') . $access_min . '分';
        }
    }

    return [
        'id'            => $post_id,
        'title'         => get_the_title($post_id),
        'permalink'     => get_permalink($post_id),
        'price_text'    => $price_text,
        'address'       => res_get_property_field('address', $post_id, '草津市野路3丁目'),
        'labels'        => $labels,
        'layout'        => $layout,
        'main_area_txt' => $main_area_txt,
        'land_area_txt' => $land_area_txt,
        'built_text'    => $built_text,
        'spec_line'     => $spec_line,
        'access_text'   => $access_text,
        'catch_text'    => res_get_property_field(
            'catch_text',
            $post_id,
            '南向きバルコニーからの陽当たり抜群。小学校まで徒歩5分、スーパー近隣で子育て世帯に良い住環境です。'
        ),
        // 見学予約URL（予約フォームのURLは仮。実際のスラッグに合わせて変更）
        'reserve_url'   => add_query_arg('property_id', $post_id, home_url('/contact/')),
    ];
}
