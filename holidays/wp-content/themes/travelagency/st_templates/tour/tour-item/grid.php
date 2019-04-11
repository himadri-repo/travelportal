<?php $size = array(460,356);
    if($item_per_line == '6'){
        $size = array(460,356);
    }
?>

<div class="service-block <?php if(!has_post_thumbnail()){ echo esc_attr('no-thum'); } ?>">
    <div class="service-img">
        <?php if(has_post_thumbnail()){ ?><a href="<?php the_permalink(); ?>"><img src="<?php the_post_thumbnail_url($size) ?>" alt="<?php  ?>"></a> <?php } ?>
    </div>
    <div class="service-content">
        <h3><a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a></h3>
        <p><?php echo wp_trim_words(get_the_excerpt(get_the_ID()), 14, ''); ?></p>
        <?php foreach($slugs_arr as $k => $v){
            $url = get_term_link($v[0],'wb_tour_type'); ?>
            <div class="service-btn-link"><a href="<?php echo esc_url($url); ?>" class="btn-link"><?php echo esc_html($v[1]); ?></a></div>
        <?php } ?>
    </div>
</div>