<?php
$banner_option = travelagency_get_option_with_meta('st_on_off_banner','on');
$bg_banner = travelagency_get_option_with_meta('st_banner_single','');
$show_breadcrumb = travelagency_get_option_with_meta('st_on_off_bread','on');
$bg_class = '';
if($banner_option == 'on'){
    if(!empty($bg_banner)){
        $img_full = wp_get_attachment_image_url($bg_banner, 'full', false);
        $bg_class = TravelAgency_Assets::inst()->build_css('background-image: url('.$img_full.')');
    }

?>
    <div class="page-header <?php echo esc_attr($bg_class); ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <div class="page-section">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
if($show_breadcrumb == 'on' && !is_front_page()){
    ?>
    <div class="page-breadcrumb">
        <div class="container">
            <div class="row">
                <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="breadcrumb">
                        <?php
                        travelagency_display_breadcrumbs(true);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>