<?php get_header(); ?>

<main class="property-archive">
    <?php get_template_part('php/components/breadcrumb'); ?>

    <?php get_template_part('php/archive/head'); ?>

    <div class="property-archive__grid">
        <?php get_template_part('php/archive/filter'); ?>
        <?php get_template_part('php/archive/list'); ?>
    </div>

    <?php get_template_part('php/archive/themes'); ?>
    <?php get_template_part('php/archive/cta-request'); ?>
</main>

<?php get_footer(); ?>
