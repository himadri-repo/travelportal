<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package travelagency
 */

?>
<footer id="colophon" class="site-footer" role="contentinfo">
    <?php
    $page_id = travelagency_get_option('st_footer_page');
    if (!empty($page_id)) echo travelagency_get_pagecontent($page_id);
    else { ?>
        <div class="site-info">
            <a href="<?php echo esc_url('http://wordpress.org/'); ?>"><?php printf(esc_html__('Proudly powered by %s', 'travelagency'), 'WordPress'); ?></a>
            <span class="sep"> | </span>
            <?php printf(esc_html__('Theme: %1$s by %2$s.', 'travelagency'), 'travelagency', '<a href="'.esc_url('http://shinetheme.com').'">Shinetheme</a>'); ?>
        </div><!-- .site-info -->
    <?php }
    ?>
</footer>
<?php wp_footer(); ?>
</div>
</body>
</html>
