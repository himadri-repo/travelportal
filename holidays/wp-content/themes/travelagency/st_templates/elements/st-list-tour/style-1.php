<?php extract($data); ?>
<?php
    $slugs_arr = array();
    $slug = array();
    if(!empty($tour_type)){
        $tour_type = explode(',',$tour_type);
        foreach($tour_type as $v){
            $slugs_arr[] = explode(':',$v);
        }
    }
    $link_service = get_post_type_archive_link('wpbooking_service');
    if(!empty($link_service)){
        $link_service = add_query_arg(array(
            'service_type' => 'tour'
        ), $link_service);
    }
    if($tour_query->have_posts()){
        while($tour_query->have_posts()){
            $tour_query->the_post(); ?>
            <div class="item col-lg-<?php echo esc_attr($item_per_line); ?> col-md-<?php echo esc_attr($item_per_line); ?> col-sm-6 col-xs-12">
                <?php echo TravelAgency_Template()->load_view('tour/tour-item/grid',false,array(
                    'tour_type' => $tour_type,
                    'slugs_arr' => $slugs_arr,
                    'item_per_line' => $item_per_line,
                )) ?>
            </div>
        <?php }
        wp_reset_postdata();
    } ?>
<?php if($view_all == 'yes') { ?>
<div class="btn_view_all">
    <a class="btn-primary btn" href="<?php echo esc_url($link_service); ?>"> <?php echo esc_html__('View all','travelagency') ?></a>
</div>
<?php } ?>

