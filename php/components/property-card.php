<?php

/**
 * 物件カード（共通パーツ）
 *
 * 使い方:
 *   get_template_part( 'php/components/property-card', null, [
 *     'variant' => 'simple', // simple（トップ）/ standard（詳細の近隣物件）/ full（一覧）
 *   ] );
 *
 * ※ WP_Query のループ内で呼ぶ想定。ループ外で使う場合は 'post_id' を渡す。
 */
/* ※ データの整形は php/inc/property-data.php の res_get_property_data() が担当。
 */



/* ---------------------------------------------------------
 * 引数
 * --------------------------------------------------------- */
$args = wp_parse_args($args ?? [], [
    'variant' => 'standard',
    'post_id' => get_the_ID(),
]);

$variant = in_array($args['variant'], ['simple', 'standard', 'full'], true)
    ? $args['variant']
    : 'standard';

$post_id = (int) $args['post_id'];
if (! $post_id) {
    return;
}

$is_simple   = ('simple' === $variant);
$is_standard = ('standard' === $variant);
$is_full     = ('full' === $variant);


/* ---------------------------------------------------------
 * 表示データ
 * --------------------------------------------------------- */
$p = res_get_property_data($post_id);

// 画像
$noimage_url = get_template_directory_uri() . '/img/noimage.jpeg';
?>

<article class="property-card property-card--<?php echo esc_attr($variant); ?>" data-property-id="<?php echo esc_attr($p['id']); ?>">

    <figure class="property-card__thumb">
        <?php if (has_post_thumbnail($post_id)) : ?>
            <?php
            echo get_the_post_thumbnail($post_id, 'medium_large', [
                'class'   => 'property-card__img',
                'loading' => 'lazy',
            ]);
            ?>
        <?php else : ?>
            <img class="property-card__img" src="<?php echo esc_url($noimage_url); ?>" alt="" loading="lazy">
        <?php endif; ?>

        <?php if (! $is_simple) : ?>
            <?php if ($p['labels']) : ?>
                <ul class="property-card__labels">
                    <?php foreach ($p['labels'] as $label) : ?>
                        <li class="property-card__label"><?php echo esc_html($label); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <button type="button" class="property-card__fav js-fav" data-property-id="<?php echo esc_attr($p['id']); ?>" aria-pressed="false" aria-label="お気に入り">
                <i class="bi bi-heart-fill property-card__fav-icon" aria-hidden="true"></i>
            </button>
        <?php endif; ?>
    </figure>

    <section class="property-card__body">

        <?php if ('' !== $p['price_text']) : ?>
            <p class="property-card__price">
                <span class="property-card__price-num"><?php echo esc_html($p['price_text']); ?></span><span class="property-card__price-unit">万円</span>
            </p>
        <?php endif; ?>

        <?php /* カード全体クリック用リンク（::after で全面に広げる）。simple では見出しは視覚的に隠す */ ?>
        <h3 class="property-card__title">
            <a class="property-card__link" href="<?php echo esc_url($p['permalink']); ?>"><?php echo esc_html($p['title']); ?></a>
        </h3>

        <?php if ($p['address']) : ?>
            <p class="property-card__address"><?php echo esc_html($p['address']); ?></p>
        <?php endif; ?>

        <?php if ($is_full && $p['address_text']) : ?>
            <p class="property-card__access property-card__access--inline"><?php echo esc_html($p['address_text']); ?></p>
        <?php endif; ?>

        <?php if (! $is_full && $p['spec_line']) : ?>
            <p class="property-card__spec"><?php echo esc_html($p['spec_line']); ?></p>
        <?php endif; ?>

        <?php if ($is_full) : ?>
            <dl class="property-card__table">
                <div class="property-card__table-item">
                    <dt>間取り</dt>
                    <dd><?php echo esc_html($p['layout'] ?: '-'); ?></dd>
                </div>
                <div class="property-card__table-item">
                    <dt>建物面積</dt>
                    <dd><?php echo esc_html($p['main_area_txt'] ?: '-'); ?></dd>
                </div>
                <div class="property-card__table-item">
                    <dt>土地面積</dt>
                    <dd><?php echo esc_html($p['land_area_txt'] ?: '-'); ?></dd>
                </div>
            </dl>

            <?php if ($p['catch_text']) : ?>
                <p class="property-card__catch"><?php echo esc_html($p['catch_text']); ?></p>
            <?php endif; ?>

            <ul class="property-card__actions">
                <li><span class="property-card__btn property-card__btn--detail">詳細を見る</span></li>
                <li><a class="property-card__btn property-card__btn--reserve" href="<?php echo esc_url($p['reserve_url']); ?>">見学予約</a></li>
            </ul>
        <?php endif; ?>

        <?php if ($is_standard) : ?>
            <div class="property-card__foot">
                <?php if ($p['access_text']) : ?>
                    <p class="property-card__access"><?php echo esc_html($p['access_text']); ?></p>
                <?php endif; ?>
                <span class="property-card__more">詳細を見る →</span>
            </div>
        <?php endif; ?>
    </section>
</article>
