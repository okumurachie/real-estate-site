<?php

/**
 * パンくずリスト（共通パーツ）
 * 使い方: get_template_part('php/components/breadcrumb');
 * 先頭は常に「ホーム」。最後の項目は現在ページ（リンクなし）。
 */

/* ---------------------------------------------------------
 * ① パンくずの項目を配列に集める（先頭は「ホーム」）
 * --------------------------------------------------------- */
$items = [
    ['label' => 'ホーム', 'url' => home_url('/'), 'icon' => 'bi-house'],
];

/* ---------------------------------------------------------
 * ② ページの種類ごとに、「ホーム」のあとに続く項目を足す
 * --------------------------------------------------------- */
if (is_post_type_archive()) {
    // 投稿タイプの一覧ページ（例: 物件一覧）
    $items[] = [
        'label' => is_post_type_archive('property') ? '物件を探す' : post_type_archive_title('', false),
    ];
} elseif (is_singular() && ! is_page()) {
    // 投稿の詳細ページ（例: 物件詳細）
    $post_type_object = get_post_type_object(get_post_type());

    // 一覧ページがある投稿タイプなら、「一覧」を途中に入れる
    if ($post_type_object && $post_type_object->has_archive) {
        $archive_label = ('property' === $post_type_object->name)
            ? '物件を探す'
            : $post_type_object->labels->name;

        $items[] = [
            'label' => $archive_label,
            'url'   => get_post_type_archive_link($post_type_object->name),
        ];
    }
    $items[] = ['label' => get_the_title()];
} elseif (is_page()) {
    // 固定ページ：親ページがあれば、親（祖先）を上から順に入れる
    $ancestor_ids = array_reverse(get_post_ancestors(get_the_ID()));
    foreach ($ancestor_ids as $ancestor_id) {
        $items[] = ['label' => get_the_title($ancestor_id), 'url' => get_permalink($ancestor_id)];
    }
    $items[] = ['label' => get_the_title()];
} elseif (is_tax() || is_category() || is_tag()) {
    // タクソノミー・カテゴリー・タグの一覧
    $items[] = ['label' => single_term_title('', false)];
} elseif (is_search()) {
    $items[] = ['label' => '検索結果'];
} elseif (is_404()) {
    $items[] = ['label' => 'ページが見つかりません'];
}

/* ---------------------------------------------------------
 * ③ 表示
 * --------------------------------------------------------- */
$last_index = array_key_last($items);
?>
<div class="wrapper">
    <nav class="page-breadcrumb" aria-label="パンくずリスト">
        <ol class="page-breadcrumb__list">
            <?php foreach ($items as $index => $item) : ?>
                <?php $is_last = ($index === $last_index); ?>
                <li class="page-breadcrumb__item" <?php echo $is_last ? 'aria-current="page"' : ''; ?>>
                    <?php if (! $is_last && ! empty($item['url'])) : ?>
                        <a class="page-breadcrumb__link" href="<?php echo esc_url($item['url']); ?>">
                            <?php if (! empty($item['icon'])) : ?>
                                <i class="bi <?php echo esc_attr($item['icon']); ?> page-breadcrumb__icon" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?php echo esc_html($item['label']); ?>
                        </a>
                    <?php else : ?>
                        <?php echo esc_html($item['label']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
</div>
