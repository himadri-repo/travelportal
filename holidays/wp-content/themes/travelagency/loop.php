<?php if (have_posts()): ?>

    <?php while (have_posts()) :the_post(); ?>

        <?php get_template_part('st_templates/blog-content/items/standard') ?>

    <?php endwhile; ?>

    <?php wp_reset_query(); ?>

    <?php st_paging_nav(); ?><!-- Display navigation-->

<?php else : ?>

    <?php get_template_part('content', 'none'); ?>

<?php endif; ?>