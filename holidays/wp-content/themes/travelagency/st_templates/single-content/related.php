<?php
$post_id = get_the_ID();
$data_tmp = get_the_category($post_id);
if (!empty($data_tmp)) {
    $ids = array();
    foreach ($data_tmp as $k => $v) {
        array_push($ids, $v->term_id);
    }
    $arg = array(
        'post_type' => 'post',
        'category__in' => $ids,
        'post__not_in' => array($post_id),
        'posts_per_page' => '3'
    );
    query_posts($arg);
    if (have_posts()) {
        ?>
        <div class="col-md-12"> <h2><?php echo esc_html__('Related Post','travelagency'); ?></h2></div>
            <?php
            while(have_posts()){
                the_post();
                ?>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 item">
                        <?php if(has_post_thumbnail()){ ?>
                            <div class="related-img">
                                <a href="<?php the_permalink(); ?>" class="imghover">
                                    <img src="<?php the_post_thumbnail_url(400,200); ?> " alt="<?php echo travelagency_get_alt_image(get_post_thumbnail_id(get_the_ID()))?>" >
                                </a>
                            </div>
                        <?php } ?>
                        <div class="related-post-content">
                            <h2><a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a></h2>
                            <span class="meta-categories"><?php echo esc_html__('In','travelagency'); ?>"<?php the_category(', '); ?>"</span>
                        </div>
                    </div>
                <?php
            }
            ?>
        <?php
    } else{

    }
}
wp_reset_postdata();
?>