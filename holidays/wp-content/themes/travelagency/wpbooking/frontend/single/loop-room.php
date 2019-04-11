<?php
$list_extra = array();
$list_extra = get_post_meta(get_the_ID(), 'extra_services', true);
$hotel_id = wp_get_post_parent_id(get_the_ID());
$service_room = new WB_Service(get_the_ID());

$check_in = WPBooking_Input::request('checkin_y') . "-" . WPBooking_Input::request('checkin_m') . "-" . WPBooking_Input::request('checkin_d');
$check_out = WPBooking_Input::request('checkout_y') . "-" . WPBooking_Input::request('checkout_m') . "-" . WPBooking_Input::request('checkout_d');
if ($check_in == '--') $check_in = '';
if ($check_out == '--') $check_out = '';

$person = (int) WPBooking_Input::request('adults') + (int) WPBooking_Input::request('children');

$diff = strtotime($check_out) - strtotime($check_in);
$diff = $diff / (60 * 60 * 24);
if ($diff < 0) $diff = 0;
?>
<div class="loop-room post-<?php the_ID() ?>">
    <div class="room-image">
        <?php
        $featured_id = $service_room->get_featured_image_room('feature_image_id');
        if (!empty($featured_id)) {
            echo wp_get_attachment_image(intval($featured_id), [270, 200], false);
        }
        ?>

    </div>
    <div class="content-room-wrap">
        <div class="room-content">
            <div class="room-title">
                <h3><?php the_title() ?></h3>
            </div>
            <div class="room-info">
                <div class="control left">
                    <?php
                    $max_guests = get_post_meta(get_the_ID(), 'max_guests', true);
                    if (!empty($max_guests)) {
                        ?>
                        <i class="ion-ios-people"></i>
                        <?php echo esc_attr($max_guests); ?>
                    <?php } ?>
                </div>
                <div class="control">
                    <?php
                    $room_size = get_post_meta(get_the_ID(), 'room_size', true);
                    if (!empty($room_size)) {
                        ?>
                        <i class="ion-arrow-expand"></i>
                        <?php
                        echo esc_attr($room_size);
                        $room_measunit = get_post_meta($hotel_id, 'room_measunit', true);
                        if ($room_measunit == 'feet')
                            echo ' ft<sup>2</sup>';
                        else echo ' m<sup>2</sup>';
                    }
                    ?>
                </div>
            </div>
            <div class="room-facilities">
                <?php $facilities = get_post_meta(get_the_ID(), 'taxonomy_room', true); ?>
                <?php if (!empty($facilities)) { ?>
                    <div class="facilities">
                        <?php
                        $html = '';
                        foreach ($facilities as $taxonomy => $term_ids) {
                            $rental_features = get_taxonomy($taxonomy);
                            if (!empty($term_ids) and !empty($rental_features->labels->name)) {
                                echo '<div class="title">' . esc_html($rental_features->labels->name) . ': </div>';
                                foreach ($term_ids as $key => $value) {
                                    if ($key <= 6) {
                                        $term = get_term($value, $taxonomy);
                                        if (!empty($term->name)) {
                                            $html .= $term->name . ", ";
                                        }

                                    }
                                }
                            }
                        }
                        $html = substr($html, 0, -2);
                        echo do_shortcode($html);
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="room-book">
            <?php
            $price = get_post_meta(get_the_ID(), 'base_price', true);
            $check_in = WPBooking_Input::request('checkin_y') . "-" . WPBooking_Input::request('checkin_m') . "-" . WPBooking_Input::request('checkin_d');
            $check_out = WPBooking_Input::request('checkout_y') . "-" . WPBooking_Input::request('checkout_m') . "-" . WPBooking_Input::request('checkout_d');
            if ($check_in == '--') $check_in = '';
            if ($check_out == '--') $check_out = '';

            $is_minimum_stay = true;
            if ($check_in and $check_out) {
                $service = new WB_Service(WPBooking_Input::request('hotel_id'));
                $check_in_timestamp = strtotime($check_in);
                $check_out_timestamp = strtotime($check_out);
                $minimum_stay = $service->get_minimum_stay();
                $dDiff = wpbooking_timestamp_diff_day($check_in_timestamp, $check_out_timestamp);
                if ($dDiff < $minimum_stay) {
                    $is_minimum_stay = false;
                }
            }

            if (!empty($check_in) and !empty($check_out) and $is_minimum_stay) {
                ?>
                <div class="room-total-price">
                    <?php
                    $price = WPBooking_Accommodation_Service_Type::inst()->_get_price_room_with_date(get_the_ID(), $check_in, $check_out);
                    echo WPBooking_Currency::format_money($price);
                    ?>
                    <br>
                    <span class="small">
                        <?php
                        if ($diff > 1) {
                            echo sprintf(esc_html__("/ %s nights", "travelagency"), $diff);
                        } else {
                            echo sprintf(esc_html__("/ %s night", "travelagency"), $diff);
                        }
                        ?>
                    </span>
                </div>
                <div class="room-number">
                    <select class="option_number_room st-select" name="wpbooking_room[<?php the_ID() ?>][number_room]"
                            data-price-base="<?php echo esc_attr($price) ?>">
                        <?php
                        $max_room = get_post_meta(get_the_ID(), 'room_number', true);
                        if (empty($max_room)) $max_room = 20;
                        for ($i = 0; $i <= $max_room; $i++) {
                            echo "<option value='{$i}'>{$i}</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php } else { ?>
                <button onclick="return false"
                        class="wb-btn wb-btn-default wb-btn-sm button_show_price is_single_search_result"><?php esc_html_e("Show Price", "travelagency") ?></button>
            <?php } ?>
        </div>
        <div class="room-extra">
            <?php if (!empty($list_extra)) { ?>
                <span class="btn_extra"><?php esc_html_e("Extra services", "travelagency") ?></span>
            <?php } ?>
        </div>
        <?php $number_night = $diff; ?>
        <div class="more-extra" data-diff="<?php echo esc_attr( $number_night ); ?>" data-person="<?php echo esc_attr($person); ?>">
            <?php if (!empty($list_extra)) {
                ?>
                <table>
                    <thead>
                    <tr>
                        <td class="et-action">

                        </td>
                        <td class="service-name">
                            <?php esc_html_e("Service name", 'travelagency') ?>
                        </td>
                        <td class="text-center">
                            <?php esc_html_e("Quantity", 'travelagency') ?>
                        </td>
                        <td class="text-center">
                            <?php
                            echo sprintf(esc_html__("Price (%s)", 'travelagency'), WPBooking_Currency::get_current_currency('currency'))
                            ?>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($list_extra as $k => $v) { ?>
                        <tr>
                            <td class="text-center">
                                <input class="option_is_extra" type="checkbox"
                                       value="<?php echo esc_attr($v['is_selected']) ?>" <?php if ($v['require'] == 'yes') echo 'checked onclick="return false"'; ?>
                                       name="wpbooking_room[<?php the_ID() ?>][extra_service][<?php echo esc_attr($k) ?>][is_check]">
                            </td>
                            <td>
                                <span class="title"><?php echo esc_html($v['is_selected']) ?></span>
                                <span class="desc"><?php echo (!empty($v['desc'])) ? esc_html($v['desc']) : '' ?></span>
                            </td>
                            <td>
                                <select class="form-control option_extra_quantity"
                                        name="wpbooking_room[<?php the_ID() ?>][extra_service][<?php echo esc_attr($k) ?>][quantity]"
                                        data-price-extra="<?php echo esc_attr($v['money']) ?>">
                                    <?php
                                    $start = 0;
                                    if ($v['require'] == 'yes') $start = 1;
                                    for ($i = $start; $i <= $v['quantity']; $i++) {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="text-center text-color">
                                <?php echo WPBooking_Currency::format_money($v['money']); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
    <div class="modal">
        <div class="modal-content">
            <span class="close"><i class="ion-close-round"></i></span>
            <div class="title col-7">
                <?php the_title() ?>
            </div>
            <?php
            if (!empty($diff)) {
                ?>
                <div class="price col-3 text-right">
                    <?php echo WPBooking_Currency::format_money($price); ?>
                    <span class="small">
                        <?php
                        if ($diff > 0) {
                            echo sprintf(esc_html__("/ %s nights", "travelagency"), $diff);
                        } else {
                            echo sprintf(esc_html__("/ %s night", "travelagency"), $diff);
                        }
                        ?>
                    </span>
                </div>
                <?php
            }
            ?>
            <div class="gallery col-6">
                <div class="service-gallery-single">
                    <div class="fotorama_room" data-allowfullscreen="true" data-nav="thumbs">
                        <?php
                        $gallery = get_post_meta(get_the_ID(), 'gallery_room', true);
                        if (!empty($gallery) and is_array($gallery)) {
                            foreach ($gallery as $k => $v) {
                                echo wp_get_attachment_image($v, 'full');
                            }
                        } else {
                            $featured = $service_room->get_featured_image_room('thumb300');
                            echo do_shortcode($featured);
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="info col-4">
                <div class="item ">
                    <?php
                    $max_guests = get_post_meta(get_the_ID(), 'max_guests', true);
                    if (!empty($max_guests)) {
                        ?>
                        <i class="ion-ios-people"></i>
                        <span><?php esc_html_e("Max Guest:", "travelagency") ?><?php echo esc_attr($max_guests); ?><?php if ($max_guests > 1) esc_html_e("guests", "travelagency"); else esc_html_e("guest", "travelagency"); ?></span>
                    <?php } ?>
                </div>
                <div class="item space">
                    <?php
                    $room_size = get_post_meta(get_the_ID(), 'room_size', true);
                    if (!empty($room_size)) {
                    ?>
                    <i class="ion-arrow-expand"></i>
                    <span>
                             <?php esc_html_e("Room Size:", "travelagency") ?>
                             <?php
                             echo esc_attr($room_size);
                             $room_measunit = get_post_meta($hotel_id, 'room_measunit', true);
                             if ($room_measunit == 'feet')
                                 echo ' ft<sup>2</sup>';
                             else echo ' m<sup>2</sup>';
                             }
                             ?>
                        </span>
                </div>
                <div class="room-facilities">
                    <?php $facilities = get_post_meta(get_the_ID(), 'taxonomy_room', true); ?>
                    <?php if (!empty($facilities)) { ?>
                        <div class="facilities">
                            <?php
                            $html = '';
                            foreach ($facilities as $taxonomy => $term_ids) {
                                $rental_features = get_taxonomy($taxonomy);
                                if (!empty($term_ids) and !empty($rental_features->labels->name)) {
                                    echo '<div class="faci-title">' . esc_html($rental_features->labels->name) . ': </div>';
                                    foreach ($term_ids as $key => $value) {
                                        if ($key <= 6) {
                                            $term = get_term($value, $taxonomy);
                                            if (!empty($term->name)) {
                                                $html .= $term->name . ", ";
                                            }

                                        }
                                    }
                                }
                            }
                            $html = substr($html, 0, -2);
                            echo do_shortcode($html);
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="item"><b><?php esc_html_e("Bath rooms", "travelagency") ?>
                        : </b><?php echo get_post_meta(get_the_ID(), 'bath_rooms', true) ?> <?php esc_html_e('room(s)', 'travelagency') ?>
                </div>
                <div class="item"><b><?php esc_html_e("Living rooms", "travelagency") ?>
                        : </b><?php echo get_post_meta(get_the_ID(), 'living_rooms', true) ?> <?php esc_html_e('room(s)', 'travelagency') ?>
                </div>
                <div class="item"><b><?php esc_html_e("Bed rooms", "travelagency") ?>
                        : </b><?php echo get_post_meta(get_the_ID(), 'bed_rooms', true) ?> <?php esc_html_e('room(s)', 'travelagency') ?>
                </div>
            </div>

        </div>

    </div>
</div>