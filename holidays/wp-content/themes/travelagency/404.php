<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package travelagency
 */

get_header(); ?>
<?php
$sidebar = travelagency_get_sidebar();
$sidebar_pos = $sidebar['position'];
?>
<div class="main-wrapper">
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
                    <section class="error-404 not-found">
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'travelagency'); ?></h1>
                        </header><!-- .page-header -->

                        <div class="page-content">
                            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'travelagency'); ?></p>

                            <?php get_search_form(); ?>

                        </div><!-- .page-content -->
                    </section><!-- .error-404 -->
            </div>
            <?php if ($sidebar_pos == 'right') {
                get_sidebar();
            } ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
