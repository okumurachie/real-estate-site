<section class="property-archive__head">
    <div class="wrapper">
        <div class="property-archive__head-inner">
            <p class="property-archive__en">PROPERTIES</p>
            <div class="property-archive__head-row">
                <?php
                get_template_part('php/components/section-head', null, [
                    'title' => '物件を探す',
                    'lead' => '地域を知るスタッフが厳選した、家族に寄り添う住まいをご紹介します。',
                ]);
                ?>
                <p class="property-archive__new"><span class="property-archive__new-dot"></span>本日更新：<b><?php echo esc_html(res_get_today_updated_count()); ?></b>件の新着情報</p>
            </div>
        </div>
    </div>
</section>
