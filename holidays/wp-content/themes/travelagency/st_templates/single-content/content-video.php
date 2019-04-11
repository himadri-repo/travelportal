<?php
$data = '';
if (get_post_meta(get_the_ID(), 'format_media', true)) {
    $media_url = get_post_meta(get_the_ID(), 'format_media', true);
    $data .= st_remove_w3c(wp_oembed_get($media_url));
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (!empty($data)) echo balanceTags($data); ?>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        <div class="entry-meta">
            <?php st_display_metabox(); ?>
        </div>
    </header>

    <div class="entry-content">
        <?php the_content();
        the_tags();
        the_category();
        ?>
        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'travelagency'),
            'after' => '</div>',
        ));
        ?>
    </div>

    <footer class="entry-footer">
    </footer>
</article>