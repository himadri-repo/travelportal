<?php
/**
 * The template for displaying search results pages.
 *
 * @package travelagency
 */

get_header(); ?>
<?php
$sidebar = travelagency_get_sidebar();
$sidebar_pos = $sidebar['position'];
?>
<div class="main-content" id="main-content">
    <div class="banner">
        <?php get_template_part('st_templates/single-content/banner'); ?>
    </div>
    <div class="container content_404">
        <div class="row">
            <?php if ($sidebar_pos == 'left') {
                get_sidebar();
            } ?>
            <div
                class="<?php echo ($sidebar_pos == 'no' ? 'col-sm-12' : 'col-md-9 col-sm-8 padding-' . $sidebar_pos . '-lg'); ?>">

                <?php if (have_posts()) : ?>

                    <header class="page-header">
                        <h1 class="page-title"><?php printf(esc_html__('Search Results for: %s', 'travelagency'), '<span>' . get_search_query() . '</span>'); ?></h1>
                    </header><!-- .page-header -->

                    <?php /* Start the Loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <?php
                        /**
                         * Run the loop for the search to output the results.
                         * If you want to overload this in a child theme then include a file
                         * called content-search.php and that will be used instead.
                         */
                        get_template_part('content', 'search');
                        ?>
                        <?php st_paging_nav(); ?><!-- Display navigation-->
                    <?php endwhile; ?>

                <?php else : ?>

                    <?php get_template_part('content', 'none'); ?>

                <?php endif; ?>
            </div>
            <?php if ($sidebar_pos == 'right') {
                get_sidebar();
            } ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
