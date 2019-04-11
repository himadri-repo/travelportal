<?php
$service      = wpbooking_get_service();
$service_type = $service->get_type();
$hotel_id     = get_the_ID();
$tour         = WPBooking_Tour_Service_Type::inst();
$start_month  = $tour->get_first_month_has_tour();
if ( !$start_month ) {
    $start_month = date( 'm' );
}

$start_month  = sprintf( "%02d", $start_month );
$start_date   = $start_month . '-01-' . date( 'Y' );
$pricing_type = $service->get_meta( 'pricing_type' );
$age_options  = $service->get_meta( 'age_options' );

wp_enqueue_script( 'wpbooking-daterangepicker-js' );
wp_enqueue_style( 'wpbooking-daterangepicker' );
$service = wpbooking_get_service();
$address = $service->get_address();
$duration = get_post_meta(get_the_ID(), 'duration', true);

?>
<?php
    echo TravelAgency_Template()->load_view('single-content/banner');
?>
<div class="single-service">
    <div class="container">
        <div class="row head">
            <div class="col-md-8">
                <h1 class="wb-service-title" itemprop="name"><?php the_title(); ?></h1>
                <ul class="tour_infor_base">
                    <?php if ($address) { ?>
                        <li>
                            <div class="service-address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                <i class="fa fa-map-marker"></i> <?php echo esc_html($address) ?>
                            </div>
                        </li>
                    <?php } ?>
                    <?php
                    if(!empty($duration)){ ?>
                        <li>
                            <i class="fa fa-clock-o"></i> <?php echo esc_html($duration); ?>
                        </li>
                    <?php }
                    ?>
                    <?php
                    $tour_type = get_post_meta(get_the_ID(),'tour_type', true);
                    if(!empty($tour_type)) {
                        echo '<li><i class="fa fa-tag"></i>'.travelagency_get_term_by('id', (int)$tour_type, 'wb_tour_type', 'name').'</li>';
                    }?>
                </ul>
            </div>
            <div class="col-md-4 text-right">
                <?php
                echo TravelAgency_Template()->load_view('tour/tour-single/price-review');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div itemscope itemtype="http://schema.org/Place" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <meta itemprop="url" content="<?php the_permalink(); ?>"/>
                    <div class="row-service-gallery-contact">
                        <div class="col-service-gallery">
                            <div class="wb-tabs-gallery-map">
                                <?php
                                $size_img_full = array(1170, 620);
                                $gallery_tour = get_post_meta(get_the_ID(),'tour_gallery',true);
                                $thumb_size = [160,70];

                                if(!empty($gallery_tour)){
                                    $id_img = explode(',',$gallery_tour);
                                    echo '<div class="fotorama tour-gallery" data-allowfullscreen="true" data-nav="thumbs" data-thumbwidth="120" data-thumbheight="70">';
                                    foreach( $id_img as $k => $v) {
                                        $img_full = wp_get_attachment_image_url($v,$size_img_full,false);
                                        $img_thumbnail = wp_get_attachment_image_url($v,$thumb_size,false);
                                        echo '<a href="'.$img_full.'" data-thumb="'.$img_thumbnail.'"></a>';
                                    }
                                    echo '</div>';
                                }
                                else{
                                    echo '<div class="fotorama hotel-gallery" data-allowfullscreen="true" data-nave="thumbs">';
                                    echo '<a hef="'.wpbooking_assets_url('images/default.png').'" ><img src="'.wpbooking_assets_url('images/default.png').'" alt="default image" ><a>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="overview_tour">
                        <div class="p-mobile">
                            <h3 class="tour-overview"><?php echo esc_html__('Tour Overview', 'travelagency'); ?></h3>
                            <?php
                            while (have_posts()) {
                                the_post();
                                the_content();
                            }
                            ?>
                            <?php echo TravelAgency_Template()->load_view('tour/tour-single/tour-program'); ?>
                        </div>
                    </div>
                    <div class="wb-tour-meta">
                        <ul>
                            <?php
                                $tour_type     = get_post_meta( get_the_ID(), 'tour_type', true );
                                $tax_tour_type = get_term_by( 'id', (int)$tour_type, 'wb_tour_type' );

                                echo '<li>
                                        <i class="fa fa-tag"></i> 
                                        <span class="title">'.esc_html__('Price','travelagency').'</span>
                                        <span class="name">'.travelagency_get_price_service(get_the_ID()).'</span>
                                       </li>';
                                if(!empty($tax_tour_type->name)){ ?>
                                    <li class="tour_type">
                                        <i class="fa fa-flag"></i>
                                        <span class="title"><?php echo esc_html__('Trip','travelagency'); ?></span>
                                        <span class="name"> <?php echo esc_html($tax_tour_type->name); ?> </span>
                                    </li>
                                <?php }

                                if ( $duration = get_post_meta( get_the_ID(), 'duration', true ) ) {
                                    echo '<li class="duration" itemprop="duration" >
                                            <i class="fa fa-clock-o"></i>
                                            <span class="title">'.esc_html__('Duration','travelagency').'</span>
                                            <span class="name"> '. $duration . '</span></li>';
                                }
                                if ( $max_people = get_post_meta( get_the_ID(), 'max_guests', true ) ) {
                                    echo '<li class="max_people">
                                            <i class="fa fa-users"></i> 
                                            <span class="title">'.esc_html__('Group size','travelagency').'</span>
                                             <span class="name"> '. $max_people . esc_html__( ' people', 'travelagency' ) . '</span> </li>';
                                }
                                ?>

                        </ul>
                    </div>

                    <div class="container-fluid wpbooking-single-content entry-header">
                        <?php do_action( 'wpbooking_after_service_description' ) ?>
                        <div class="service-content-section">
                            <h5 class="service-info-title"><?php esc_html_e( 'Payment Policies', 'travelagency' ) ?></h5>
                            <div class="service-details row">
                                <?php
                                $array                = [
                                    'deposit_payment_status' => '',
                                    'deposit_payment_amount' => wp_kses( esc_html__( 'Deposit: %s &nbsp;&nbsp; required', 'travelagency' ), [ 'span' => [ 'class' => [] ] ] ),
                                    'allow_cancel'           => esc_html__( 'Allowed Cancellation: Yes', 'travelagency' ),
                                    'cancel_free_days_prior' => esc_html__( 'Time allowed to free: %s', 'travelagency' ),
                                    'cancel_guest_payment'   => esc_html__( 'Fee cancel for booking: %s', 'travelagency' ),
                                ];
                                $cancel_guest_payment = [
                                    'first_night' => esc_html__( '100&#37; of the first night', 'travelagency' ),
                                    'full_stay'   => esc_html__( '100&#37; of the full stay', 'travelagency' ),
                                ];
                                $deposit_html         = [];
                                $allow_deposit        = '';
                                foreach ( $array as $key => $val ) {
                                    $meta = get_post_meta( get_the_ID(), $key, true );
                                    if ( $key == 'deposit_payment_status' ) {
                                        $allow_deposit = $meta;
                                        continue;
                                    }
                                    if ( !empty( $meta ) ) {
                                        if ( $key == 'deposit_payment_amount' ) {
                                            if ( empty( $allow_deposit ) ) {
                                                $deposit_html[] = '';
                                            } elseif ( $allow_deposit == 'amount' ) {
                                                $deposit_html[] = sprintf( $val, WPBooking_Currency::format_money( $meta ) );
                                            } else {
                                                $deposit_html[] = sprintf( $val, $meta . '%' );
                                            }
                                            continue;
                                        }
                                        if ( $key == 'cancel_guest_payment' ) {
                                            $deposit_html[] = sprintf( $val, $cancel_guest_payment[ $meta ] );
                                            continue;
                                        }
                                        if ( $key == 'cancel_free_days_prior' ) {
                                            if ( $meta == 'day_of_arrival' )
                                                $deposit_html[] = sprintf( $val, esc_html__( 'Day of arrival (6 pm)', 'travelagency' ) );
                                            else
                                                $deposit_html[] = sprintf( $val, $meta . esc_html__( ' day', 'travelagency' ) );

                                            continue;
                                        }

                                    }
                                    if ( $key == 'allow_cancel' ) {
                                        $deposit_html[] = $val;
                                        continue;
                                    }
                                }

                                if ( !empty( $deposit_html ) ) {
                                    ?>
                                    <div class="service-detail-item">
                                        <div class="service-detail-title"><?php esc_html_e( 'Prepayment / Cancellation', 'travelagency' ) ?></div>
                                        <div class="service-detail-content">
                                            <ul>
                                                <?php
                                                foreach ( $deposit_html as $value ) {
                                                    if ( !empty( $value ) ) echo '<li>'.( $value ).'</li>';
                                                }
                                                ?>
                                                </ul>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php
                                $tax_html         = [];
                                $array            = [
                                    'vat_excluded'     => '',
                                    'vat_unit'         => '',
                                    'vat_amount'       => esc_html__( 'V.A.T: %s &nbsp;&nbsp;', 'travelagency' ),
                                    'citytax_excluded' => '',
                                    'citytax_unit'     => '',
                                    'citytax_amount'   => esc_html__( 'City tax: %s', 'travelagency' ),
                                ];
                                $citytax_unit     = [
                                    'stay'             => esc_html__( ' /stay', 'travelagency' ),
                                    'person_per_stay'  => esc_html__( ' /person per stay', 'travelagency' ),
                                    'night'            => esc_html__( ' /night', 'travelagency' ),
                                    'percent'          => esc_html__( '%', 'travelagency' ),
                                    'person_per_night' => esc_html__( ' /person per night', 'travelagency' ),
                                ];
                                $vat_excluded     = '';
                                $citytax_excluded = '';
                                $ct_unit          = '';
                                foreach ( $array as $key => $val ) {
                                    $value = get_post_meta( get_the_ID(), $key, true );
                                    if ( !empty( $value ) ) {
                                        switch ( $key ) {
                                            case 'vat_excluded':
                                                $vat_excluded = $value;
                                                break;
                                            case 'vat_unit':
                                                $ct_unit = $value;
                                                break;
                                            case 'vat_amount':
                                                $amount = '';
                                                if ( !empty( $ct_unit ) ) {
                                                    if ( $ct_unit == 'percent' ) {
                                                        $amount = $value . '%';
                                                    } else {
                                                        $amount = WPBooking_Currency::format_money( $value );
                                                    }
                                                }

                                                if ( $vat_excluded == 'yes_included' ) {
                                                    $tax_html[] = sprintf( $val, $amount . ' &nbsp;&nbsp;' . wp_kses( '<span class="enforced_red">' . esc_html__( 'included', 'travelagency' ) . '</span>', [ 'span' => [ 'class' => [] ] ] ) );
                                                } elseif ( $vat_excluded != '' ) {
                                                    $tax_html[] = sprintf( $val, $amount );
                                                }
                                                break;
                                            case 'citytax_excluded':
                                                $citytax_excluded = $value;
                                                break;
                                            case 'citytax_unit':
                                                $ct_unit = $value;
                                                break;
                                            case 'citytax_amount':
                                                if ( !empty( $ct_unit ) ) {
                                                    if ( $ct_unit == 'percent' ) {
                                                        $str_citytax = sprintf( $val, $value ) . $citytax_unit[ $ct_unit ];
                                                    } else {
                                                        $str_citytax = sprintf( $val, WPBooking_Currency::format_money( $value ) ) . $citytax_unit[ $ct_unit ];
                                                    }
                                                }
                                                if ( $citytax_excluded != '' ) {
                                                    if ( $citytax_excluded == 'yes_included' ) {
                                                        $tax_html[] = $str_citytax . '&nbsp;&nbsp; <span class="enforced_red">' . esc_html__( 'included', 'travelagency' ) . '</span>';
                                                    } else {
                                                        $tax_html[] = $str_citytax;
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                }

                                if ( !empty( $tax_html ) ) {
                                    ?>
                                    <div class="service-detail-item">
                                        <div
                                            class="service-detail-title"><?php esc_html_e( 'Tax', 'travelagency' ) ?></div>
                                        <div class="service-detail-content">
                                            <ul>
                                                <?php foreach ( $tax_html as $value ) {
                                                    echo '<li>'.( $value ) . '</li>';
                                                } ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php
                                if ( $terms_conditions = get_post_meta( get_the_ID(), 'terms_conditions', true ) ) { ?>
                                    <div class="service-detail-item">
                                        <div class="service-detail-title"><?php esc_html_e( 'Term & Condition', 'travelagency' ) ?></div>
                                        <div class="service-detail-content">
                                            <?php echo( $terms_conditions ); ?>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>

                    </div>
                    <div class="booking-request row">
                        <div class="col-md-6">
                            <div class="form-booking">
                                <h3 class="title"><?php echo esc_html__('Request to book','travelagency') ?></h3>
                                <?php echo TravelAgency_Template()->load_view('tour/tour-single/request-book',false,array(
                                    'service' => $service,
                                    'start_date' => $start_date,
                                    'pricing_type' => $pricing_type,
                                    'age_options' => $age_options,
                                )) ?>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact">
                                <h3 class="title"> <?php echo esc_html__('Contact us','travelagency'); ?></h3>
                                <ul>
                                    <?php
                                    $location_id = get_post_meta(get_the_ID(),'location_id',true);
                                    $location = get_term_by('id',$location_id,'wpbooking_location');
                                    if($address = get_post_meta(get_the_ID(),'address',true)){
                                        echo '<li><i class="fa fa-building-o"></i> '.esc_attr($address); if(!empty($location->name)){
                                            echo ', '.esc_html($location->name);
                                        }'</li>';
                                    }
                                    if($mail = get_post_meta(get_the_ID(),'contact_email',true)){
                                        echo '<li><i class="fa fa-envelope"></i><a href="mailto:'.esc_url($mail).'"> '.esc_attr($mail).'</a></li>';
                                    }
                                    if($phone = get_post_meta(get_the_ID(),'contact_number',true)){
                                        echo '<li><i class="fa fa-phone"></i><a href="tel:'.esc_url($phone).'"> '.esc_attr($phone).'</a></li>';
                                    }
                                    ?>


                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <?php
            $lat = get_post_meta(get_the_ID(),'map_lat',true);
            $ln = get_post_meta(get_the_ID(),'map_long',true);

        if(!empty($lat) && (!empty($ln))){
        ?>
        <div class="map">
            <iframe src="https://maps.google.com/maps?q=<?php if(!empty($lat)){ echo esc_attr($lat); } ?>,<?php if(!empty($ln)){ echo esc_attr($ln);} ?>&hl=es;z=14&amp;output=embed">
            </iframe>
        </div>
    <?php } ?>
    </div>
