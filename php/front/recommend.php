<?php

/**
 * トップページ：おすすめ物件セクション
 *
 * 呼び出し元: front-page.php
 *   get_template_part( 'php/front/recommend' );
 */

/* ---------------------------------------------------------
 * クエリ
 *
 * 【今】カスタム投稿タイプ・ACFが未作成のため、通常の投稿を新しい順に3件取得。
 * 【あとで】下の「本番用」に差し替える。
 * --------------------------------------------------------- */
$recommend_query = new WP_Query([
    'post_type'      => 'post', // ← 本番では 'property'
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',

    // 【本番用】ACFの is_recommend（true/false）がONの物件だけ取得
    // 'meta_query'  => [
    //   [
    //     'key'   => 'is_recommend',
    //     'value' => '1',
    //   ],
    // ],
]);

// 該当する物件がなければ、セクションごと出さない
if (! $recommend_query->have_posts()) {
    wp_reset_postdata();
    return;
}
?>

<section class="recommend">
    <div class="wrapper">
            <?php
            get_template_part('php/components/section-head', null, [
                'title' => 'おすすめの物件',
                'lead' => 'ご家族の暮らしに合わせて厳選した、今おすすめの住まいです。',
            ]);
            ?>

        <ul class="recommend__list">
            <?php while ($recommend_query->have_posts()) : $recommend_query->the_post(); ?>
                <li class="recommend__item">
                    <?php
                    get_template_part('php/components/property-card', null, [
                        'variant' => 'simple',
                    ]);
                    ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</section>

<?php wp_reset_postdata(); ?>
