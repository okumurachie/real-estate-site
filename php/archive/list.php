<!-- これはサンプルコード -->
<!-- php/archive/list.php -->
<section class="property-list" aria-labelledby="property-list-title">
    <h2 id="property-list-title" class="screen-reader-text">物件一覧</h2>
    <ul class="property-list__items">
        <?php while (have_posts()) : the_post(); ?>
            <li class="property-list__item">
                <article>
                    <!-- 物件カード -->
                </article>
            </li>
        <?php endwhile; ?>
    </ul>
</section>
