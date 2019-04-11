<?php
/**
 * The template for displaying all single posts.
 *
 * @package travelagency
 */

get_header();
$sidebar = travelagency_get_sidebar();
$sidebar_pos = $sidebar['position'];
?>
    <div id="main-content" class="main-content">
        <div class="container">
            <div class="row">
                <?php if ($sidebar_pos == 'left') {
                    get_sidebar();
                } ?>
                <div class="<?php echo ($sidebar_pos == 'no' ? 'col-sm-12' : 'col-sm-9'); ?>">
                    <?php
                    while (have_posts()) : the_post();

                        /*
                        * Include the post format-specific template for the content. If you want to
                        * use this in a child theme, then include a file called called content-___.php
                        * (where ___ is the post format) and that will be used instead.
                        */
                        get_template_part('content', 'page');

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;

                        // End the loop.
                    endwhile; ?>
                </div>
                <?php if ($sidebar_pos == 'right') {
                    get_sidebar();
                } ?>
            </div>

        </div>

    </div>
<?php
get_footer();