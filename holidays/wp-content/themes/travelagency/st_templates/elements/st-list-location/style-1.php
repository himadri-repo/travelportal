<?php
extract($data);
if(!empty($overlay)){
    $overlay = TravelAgency_Assets()->build_css('background:'.$overlay.' !important',':before');
}
$size = array(360,300);
if($item_per_line == '6'){
    $size = array(525,437);
}
if(!empty($locations)){
    foreach($locations as $v){
        $id = get_tax_meta($v->term_id,'featured_image');
        $url = TravelAgency_Assets()->url('img/defalut-location.png');
        if(!empty($id)){
            $url = wp_get_attachment_image_url($id,$size);
        }
        $link = get_term_link($v->term_id);
    ?>
    <div class="item col-lg-<?php echo esc_attr($item_per_line); ?> col-md-<?php echo esc_attr($item_per_line); ?> col-sm-6 col-xs-12">
        <div class="destination-img">
            <a href="<?php echo esc_url($link); ?>" class="imghover <?php echo esc_attr($overlay); ?>"><img src="<?php echo esc_url($url); ?>" alt=""></a>
        </div>
        <div class="destination-content">
            <h3><a href="<?php echo esc_url($link); ?>" class="destination-title"><?php echo esc_html($v->name); ?></a></h3>
        </div>
    </div>
    <?php } }?>

