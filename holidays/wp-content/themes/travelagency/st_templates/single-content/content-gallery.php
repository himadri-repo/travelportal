<?php
$data = '';
$gallery = get_post_meta(get_the_ID(), 'format_gallery', true);
if (!empty($gallery)) {
    $array = explode(',', $gallery);
    if (is_array($array) && !empty($array)) {
        $data .= '<div id="owl-example" class="owl-carousel st-owl">';
        foreach ($array as $key => $item) {
            $thumbnail_url = wp_get_attachment_url($item);
            $data .= '<img src="' . $thumbnail_url . '" alt="project">';
        }
        $data .= '</div>';
    }
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