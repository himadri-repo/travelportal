<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package travelagency
 */
$sidebar = travelagency_get_sidebar();
$sidebar_pos = $sidebar['position'];
get_header(); ?>

<div id="main-content" class="main-content">
        <?php
            get_template_part('st_templates/single-content/banner');
        ?>
        <?php do_action('agency_before_main_content') ?>
        <div class="container archive-blog">
                <?php the_archive_title('<h1>', '</h1>'); ?>
                <div class="row">
                    <?php if ($sidebar_pos == 'left') {
                        get_sidebar();
                    } ?>
                    <div
                        class="<?php echo ($sidebar_pos == 'no' ? 'col-sm-12' : 'col-md-9 col-sm-8 padding-' . $sidebar_pos . '-lg'); ?>">
                        <?php get_template_part('loop') ?>
                    </div>
                    <?php if ($sidebar_pos == 'right') {
                        get_sidebar();
                    } ?>
                </div>
        </div>

        <?php do_action('agency_before_main_content') ?>
</div>
<?php get_footer(); ?>
