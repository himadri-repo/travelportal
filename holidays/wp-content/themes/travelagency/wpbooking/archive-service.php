<?php
global $wp_query;
get_header();
echo wpbooking_load_view('wrap/start');
?>
<?php  echo  TravelAgency_Template()->load_view('single-content/banner-tax'); ?>
    <div class="container wb-archive-wrapper hentry">
        <div class="row">
            <div class="col-md-12">
                <?php echo wpbooking_load_view('archive/header')?>
                <div class="wpbooking-loop-wrap">
                    <?php echo wpbooking_load_view('archive/loop')?>
                    <?php echo wpbooking_load_view('archive/pagination')?>
                </div>
            </div>
        </div>
    </div>
<?php
echo wpbooking_load_view('wrap/end');
get_footer();