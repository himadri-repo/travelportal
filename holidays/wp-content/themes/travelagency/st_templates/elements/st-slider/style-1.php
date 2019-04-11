<?php
    extract($data);
if(!empty($images)){
    $img_ids = explode(',',$images);
}
if(!empty($height)){
    $height = TravelAgency_Assets()->build_css('height:'.$height.' !important');
}
if(!empty($overlay)){
    $overlay = TravelAgency_Assets()->build_css('background:'.$overlay.'', ':after');
}

$default = [
    'target' => 'target',
    '_self' => '_self',
    'title' => esc_html__( 'View tour', 'travelagency' ),
    'url' => 'url'
];
if(!empty($link )&& $link_custom =='yes'){
    $link = kc_parse_link($link);
}
$link = wp_parse_args( $link, $default );

if(!empty($bg_box)){
    $bg_box = TravelAgency_Assets()->build_css('background-color:'.$bg_box.'');
}
$slug_tour = '';
if(!empty($tour_type) && empty($link_custom)){
    $tour_type = explode(':',$tour_type);
    $slug_tour = $tour_type[0];
    $slug_tour =get_term_link( $slug_tour,'wb_tour_type');
}
if($nav == 'yes'){
    $nav = 'true';
}
if($dots == 'yes'){
    $dots = 'true';
}
?>
<div class="slider-cotent <?php echo esc_attr($height); ?>">
    <div class="st-slider-carousel owl-carousel owl-theme" data-nav="<?php echo esc_attr($nav); ?>" data-dots="<?php echo esc_attr($dots); ?>">
        <?php
        if(!empty($img_ids)){
            foreach($img_ids as $v){
                $img_url = wp_get_attachment_image_url($v,array(1920,510));
                $bg = TravelAgency_Assets()->build_css('background:url('.$img_url.')');
             ?>
            <div class="item <?php echo esc_attr($bg).' '.esc_attr($height).' '.esc_attr($overlay); ?>" >
            </div>
        <?php } } ?>
    </div>
    <div class="slider-content container">
        <div class="hero-section-caption pinside40 col-lg-5 col-md-6 col-sm-6 col-xs-12 <?php echo esc_attr($bg_box); ?>">
           <?php if(!empty($title)){ ?> <h1 class="hero-title"><?php echo esc_html($title); ?></h1> <?php } ?>
            <a href="<?php if(!empty($tour_type)){ echo esc_url($slug_tour); } else {  echo esc_url($link['url']); } ?>" target="<?php if(empty($tour_type)){ echo esc_attr($link['target']); } else { echo esc_attr('_self'); } ?>" class="btn btn-primary "><?php if(!empty($tour_type)){ echo esc_html__('view tours','travelagency'); } else { echo esc_html($link['title']); } ?></a>
        </div>
    </div>
</div>