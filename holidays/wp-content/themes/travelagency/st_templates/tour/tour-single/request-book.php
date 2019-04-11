<div class="col-service-reviews-meta">
    <div class="wb-service-reviews-meta">
        <form class="wb-tour-booking-form" method="post" action="<?php echo esc_url(get_the_permalink(get_the_ID())); ?>">
            <input type="hidden" name="post_id" value="<?php the_ID() ?>">
            <div class="wb-tour-form-wrap">
                <div class="wpbooking-form-group">
                    <div class="departure-date-group clearfix" data-post_id="<?php echo get_the_ID(); ?>"
                         data-start-month="<?php echo esc_attr( $start_date ); ?>">
                        <label class="title"
                               for="departure-date-field"><?php echo esc_html__( 'Departure Date', 'travelagency' ); ?></label>
                        <div class="item-search datepicker-field">
                            <div class="item-search-content">
                                <i class="fa fa-calendar"></i>
                                <input type="hidden" class="checkin_d" name="checkin_d"
                                       value="<?php echo esc_attr( WPBooking_Input::get( 'checkin_d' ) ); ?>"/>
                                <input type="hidden" class="checkin_m" name="checkin_m"
                                       value="<?php echo esc_attr( WPBooking_Input::get( 'checkin_m' ) ); ?>"/>
                                <input type="hidden" class="checkin_y" name="checkin_y"
                                       value="<?php echo esc_attr( WPBooking_Input::get( 'checkin_y' ) ) ?>"/>
                                <input
                                    class="wpbooking-date-start wb-required"
                                    readonly type="text" placeholder="Date">
                                <input class="wpbooking-check-in-out" type="text" name="check_in_out">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wpbooking-form-control select_control">
                    <label class="wpbooking-form-control "><?php esc_html_e( 'Adults', 'travelagency' );
                        if ( !$pricing_type or $pricing_type == 'per_person' ) {
                            if ( !empty( $age_options[ 'adult' ][ 'minimum' ] ) or !empty( $age_options[ 'adult' ][ 'maximum' ] ) ) {
                                printf( ' (%s - %s)', $age_options[ 'adult' ][ 'minimum' ], $age_options[ 'adult' ][ 'maximum' ] );
                            }
                        }

                        ?>

                    </label>
                    <div class="controls">
                        <i class="fa fa-angle-down"></i>
                        <select class="wpbooking-form-control" name="adult_number">
                            <?php for ( $i = 0; $i <= 20; $i++ ) {
                                printf( '<option value="%s">%s</option>', $i, $i );
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="wpbooking-form-control select_control">
                    <label class="wpbooking-form-control"><?php esc_html_e( 'Children', 'travelagency' );
                        if ( !$pricing_type or $pricing_type == 'per_person' ) {
                            if ( !empty( $age_options[ 'child' ][ 'minimum' ] ) or !empty( $age_options[ 'child' ][ 'maximum' ] ) ) {
                                printf( ' (%s - %s)', $age_options[ 'child' ][ 'minimum' ], $age_options[ 'child' ][ 'maximum' ] );
                            }
                        }
                        ?></label>
                    <div class="controls">
                        <i class="fa fa-angle-down"></i>
                        <select class="wpbooking-form-control" name="children_number">
                            <?php for ( $i = 0; $i <= 20; $i++ ) {
                                printf( '<option value="%s">%s</option>', $i, $i );
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="wpbooking-form-control select_control">
                    <label class="wpbooking-form-control"><?php esc_html_e( 'Infant', 'travelagency' );
                        if ( !$pricing_type or $pricing_type == 'per_person' ) {
                            if ( !empty( $age_options[ 'infant' ][ 'minimum' ] ) or !empty( $age_options[ 'infant' ][ 'maximum' ] ) ) {
                                printf( ' (%s - %s)', $age_options[ 'infant' ][ 'minimum' ], $age_options[ 'infant' ][ 'maximum' ] );
                            }
                        }
                        ?></label>
                    <div class="controls">
                        <i class="fa fa-angle-down"></i>
                        <select class="wpbooking-form-control" name="infant_number">
                            <?php for ( $i = 0; $i <= 20; $i++ ) {
                                printf( '<option value="%s">%s</option>', $i, $i );
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="booking-message"></div>
                <button type="submit"
                        class="wb-button wb-order-button"><?php esc_html_e( 'Book Now', 'travelagency' ) ?>
                    <i class="fa fa-spinner fa-pulse "></i></button>

                <div class="extrac-service">
                    <?php
                    $extra_service=get_post_meta(get_the_ID(),'extra_services',true);
                    if(!empty($extra_service)){
                    echo '<span class="btn_extra">'.esc_html__('Extra services','travelagency').'</span>';
                    echo '<div class="more-extra">';
                    echo '<table>';
                    ?>
                    <thead>
                    <tr>
                        <td><?php echo esc_html__('Service Name','travelagency')?></td>
                        <td><?php echo esc_html__('Quantity','travelagency')?></td>
                        <td><?php echo esc_html__('Price(ALL)','travelagency')?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($extra_service as $k=>$v) {
                        $name = sanitize_title($v['is_selected']);
                        ?>
                        <tr>
                            <td>
                                <label><?php echo esc_html( $v[ 'is_selected' ] ) ?></label>
                            </td>
                            <td>
                                <div class="extra_select_quantity">
                                    <i class="fa fa-angle-down"></i>
                                    <select class="option_extra_quantity"
                                            name="wpbooking_extra_service[<?php echo esc_attr( $name ) ?>][quantity]"
                                            data-price-extra="<?php echo esc_attr( $v[ 'money' ] ) ?>">
                                        <?php
                                        $start = 0;
                                        if($v[ 'require' ] == 'yes')
                                            $start = 1;
                                        for( $i = $start ; $i <= $v[ 'quantity' ] ; $i++ ) {
                                            echo "<option value='{$i}'>{$i}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <span class="price"><?php echo WPBooking_Currency::format_money( $v[ 'money' ] ); ?></span>
                            </td>
                        </tr>
                        <?php
                    }
                    echo '</tbody></table></div>';
                    }
                    ?>
                </div>
            </div>
        </form>
        <?php
        do_action( 'wpbooking_after_booking_form' );
        ?>
    </div>
</div>