<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 3/22/2018
 * Version: 1.0
 */
extract($data);
$bg_class = '';
if(!empty($bg_image)){
    $img_full = wp_get_attachment_image_url($bg_image, 'full', false);
    $bg_class = TravelAgency_Assets::inst()->build_css('background: url('.$img_full.')');
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
<?php
if($show_breadcrumb == 'yes'){
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