<?php
if(!empty($cart['rooms'])){
    $booking=WPBooking_Checkout_Controller::inst();
    ?>
    <div class="checkout-room-detail">
        <h4 class="st-title"><?php echo esc_html__('Room Details','travelagency'); ?></h4>
        <?php foreach($cart['rooms'] as $k=>$v){
            $service_room=new WB_Service($k);
            $featured=$service_room->get_featured_image_room();
            $price_room = WPBooking_Accommodation_Service_Type::inst()->_get_price_room_in_cart($cart,$k);
            $price_total_room = WPBooking_Accommodation_Service_Type::inst()->_get_total_price_room_in_cart($cart,$k);
            ?>
            <div class="st-room">
                <a class="delete-cart-item tooltip_desc" onclick="return confirm('<?php esc_html_e('Do you want to delete it?','travelagency') ?>')" href="<?php echo esc_url(add_query_arg(array('delete_item_hotel_room'=>$k),$booking->get_checkout_url())) ?>">
                    <i class="fa fa-trash-o"></i>
                    <span class="tooltip_content"><?php esc_html_e("Remove this room",'travelagency') ?></span>
                </a>
                <div class="head room_row">
                    <div class="col">
                        <h5 class="st-title"><?php echo get_the_title($k) ?></h5>
                        <?php if($max = $service_room->get_meta('max_guests')){ ?>
                            <div class="sub-title"><?php esc_html_e("Max","travelagency") ?> <?php echo esc_attr($max) ?> <?php esc_html_e("people","travelagency") ?></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="content">
                    <div class="price room_row">
                        <div class="col title"><?php esc_html_e('Price','travelagency') ?> (<?php echo WPBooking_Currency::get_current_currency('currency') ?>)</div>
                        <div class="col right"> <?php echo WPBooking_Currency::format_money($price_room); ?></div>
                    </div>
                    <div class="number room_row">
                        <div class="col title"><?php esc_html_e('Number','travelagency') ?></div>
                        <div class="col right"><?php echo esc_attr($v['number']) ?></div>
                    </div>
                    <div class="total room_row">
                        <div class="col title"><?php esc_html_e('Total','travelagency') ?> (<?php echo WPBooking_Currency::get_current_currency('currency') ?>)</div>
                        <div class="col right"><?php echo WPBooking_Currency::format_money($price_total_room); ?></div>
                    </div>
                    <div class="more_detail">
                        <?php if(!empty($v['list_date_price'])){ ?>
                            <span class="btn_detail_checkout"><?php esc_html_e("Details","travelagency") ?> <i class="fa fa-caret-down" aria-hidden="true"></i></span>
                        <?php } ?>
                        <div class="content_details">
                            <?php
                            if(!empty($v['list_date_price'])){ ?>
                                <div class="extra-service">
                                    <div class="title"><?php esc_html_e("Price by Night","travelagency") ?></div>
                                    <div class="extra-item">
                                        <table>
                                            <thead>
                                            <tr>
                                                <th width="60%">
                                                    <?php esc_html_e("Night","travelagency") ?>
                                                </th>
                                                <th class="text-center">
                                                    <?php esc_html_e("Price","travelagency") ?>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $i=1; foreach( $v['list_date_price'] as $k_list_date => $v_list_date){ ?>
                                                <tr>
                                                    <td>
                                                        <?php esc_html_e("Night","travelagency") ?> <?php echo esc_html($i) ?>
                                                        <br>
                                                        <span class="desc">(<?php echo date(get_option('date_format') , $k_list_date) ?>)</span>
                                                    </td>
                                                    <td class="text-center main_color">
                                                        <?php echo WPBooking_Currency::format_money($v_list_date) ?>
                                                    </td>
                                                </tr>
                                                <?php $i++;} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php
                            if(!empty($v['extra_fees'])){ ?>
                                <?php
                                foreach($v['extra_fees'] as $extra_service){
                                    if(!empty($extra_service['data'])){
                                        ?>
                                        <div class="extra-service">
                                            <div class="title"><?php echo esc_html($extra_service['title']) ?></div>
                                            <div class="extra-item">
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <th width="60%" >
                                                            <?php esc_html_e("Service name",'travelagency') ?>
                                                        </th>
                                                        <th class="text-center">
                                                            <?php esc_html_e("Price",'travelagency') ?>
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach($extra_service['data'] as $value){
                                                        ?>
                                                        <tr>
                                                            <td >
                                                                <?php echo esc_html($value['title']) ?><br>
                                                                x  <span class="desc"><?php echo esc_html($value['quantity']) ?></span>
                                                            </td>
                                                            <td class="text-center main_color">
                                                                <?php echo WPBooking_Currency::format_money($value['price']) ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php } ?>
    </div>
<?php } ?>
