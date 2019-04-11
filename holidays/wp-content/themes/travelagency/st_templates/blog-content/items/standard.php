<?php
/**
 * Created by travelagency.
 * Developer: nasanji
 * Date: 3/22/2018
 * Version: 1.0
 */
?>
<div class="post-block">
    <div class="post-img">
        <a href="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>" class="imghover">
            <?php
            the_post_thumbnail([1140, 500]);
            ?>
        </a>
    </div>
    <?php
    if(is_sticky(get_the_ID())){
    ?>
    <div class="post-sticky"> <i class="fa fa-thumb-tack hidden-xs"></i> </div>
    <?php } ?>
    <div class="post-content">
        <div>
            <h1><a href="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>" class="post-title"><?php the_title(); ?></a></h1>
        </div>
        <div class="meta">
            <span class="meta-date"><?php echo esc_html__('Posted on', 'travelagency'); ?> <?php the_date(get_option('date_format'))?> </span>
            <span class="meta-author"><?php echo esc_html__('by', 'travelagency')?> <a
                    href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author(); ?></a> </span>
        </div>
    </div>
</div>
