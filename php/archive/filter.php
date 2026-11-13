<!-- これはサンプルコード -->
<!-- php/archive/filter.php -->
<aside class="property-filter" aria-label="物件の絞り込み">
    <form method="get" action="<?php echo esc_url(get_post_type_archive_link('property')); ?>">
        <!-- 検索項目 -->
        <button type="submit">検索する</button>
    </form>
</aside>
