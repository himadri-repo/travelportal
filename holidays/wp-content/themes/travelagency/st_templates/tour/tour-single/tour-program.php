<?php
$itinerary = get_post_meta(get_the_ID(), 'tour_program', true);
?>
<?php
if (!empty($itinerary) && is_array($itinerary) && count($itinerary) > 0) {
    $i = 1;
    ?>
    <h3 class="title-program"><?php echo esc_html__('Tour program', 'travelagency'); ?></h3>
    <div class="st-tour-program--wrap">
        <?php
        foreach ($itinerary as $key => $val) {
            if (!empty($val['title'])) {
                ?>
                <div class="st-tour-program--row <?php echo($i % 2 == 0 ? 'even-item' : ''); ?>">
                    <div class="content-left">
                        <?php
                        if (!empty($val['program_image'])) {
                            echo '<img src="' . esc_url($val['program_image']) . '" alt="program" />';
                        }
                        ?>
                    </div>
                    <div class="content-right">
                        <h3 class="title"><?php echo do_shortcode($val['title']); ?></h3>
                        <?php
                        if(!empty($val['desc'])){
                            echo '<p>'.do_shortcode($val['desc']).'</p>';
                        }
                        ?>
                    </div>
                </div>
                <?php
                $i++;
            }
        }
        ?>
    </div>
<?php } ?>
