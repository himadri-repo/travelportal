<?php 
$data ='';
if (wp_get_attachment_url(get_post_thumbnail_id($post->ID))) {
    $thumbnail_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    $data .= '<img alt="blog-img" title="blog-img" class="img-responsive" src="' . $thumbnail_url . '"/>';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if(!empty($data)) echo balanceTags($data);?>
    <header class="entry-header">
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        <div class="entry-meta">
            <?php st_display_metabox();?>
        </div>
    </header>

    <div class="entry-content">
        <?php the_excerpt();
        ?>
        <?php
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'travelagency' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <footer class="entry-footer">
    </footer>
</article>