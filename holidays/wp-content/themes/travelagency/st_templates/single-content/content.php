<div class="post-block">
    <div class="post-img">
        <?php if(has_post_thumbnail()){ ?><img alt="blog-img" title="blog-img" class="img-responsive" src="<?php the_post_thumbnail_url(array(1140,500)); ?>"/> <?php } ?>
    </div>
    <div class="post-sticky"> <i class="fa fa-thumb-tack"></i> </div>
    <div class="post-content">
        <div>
            <h1 class="post-title"><?php the_title(); ?></h1>
        </div>
        <div class="meta"> <div class="meta-date"><?php st_display_metabox(); ?></div></div>
    </div>
</div>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>  >

    <div class="entry-content">
        <?php the_content();
        ?>
        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'travelagency'),
            'after' => '</div>',
        ));
        ?>
    </div>
</article>