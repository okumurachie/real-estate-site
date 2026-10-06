<main>
    <h2>物件一覧</h2>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php get_template_part('php/parts/property-card'); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>物件が見つかりませんでした。</p>
    <?php endif; ?>
</main>
