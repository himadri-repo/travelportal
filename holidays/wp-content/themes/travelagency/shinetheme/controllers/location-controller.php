<?php

if(!function_exists('WPBooking')){
    return;
}

if(!class_exists('TravelAgency_LocationController')){
    class TravelAgency_LocationController{

        static $_inst;

        function __construct()
        {
            /**
             * Add map for location
             *
             * @since 1.0
             */
            add_action( 'wpbooking_location_edit_form_fields', array($this, '_edit_custom_fields'));
            add_action( 'wpbooking_location_add_form_fields', array($this, '_edit_custom_fields'));
            add_action( 'edited_wpbooking_location', array($this, '_save_custom_fields'));
            add_action( 'created_wpbooking_location', array($this, '_save_custom_fields'), 10, 2);

        }

        /* -- #### */
        function count_service_of_location($location_id , $service_type = false){
            $args = array(
                'post_type'     => 'wpbooking_service', //post type, I used 'product'
                'post_status'   => 'publish', // just tried to find all published post
                'posts_per_page' => 1,  //show all
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'wpbooking_location',  //taxonomy name  here, I used 'product_cat'
                        'field' => 'id',
                        'terms' => array( $location_id )
                    )
                )
            );
            if(!empty($service_type)){
                $args['meta_query'] = array(
                    array(
                        'key'     => 'service_type',
                        'value'   => $service_type,
                    ),
                );
            }
            $query = new WP_Query( $args);
            wp_reset_query();
            return $query->found_posts;
        }

        function _save_custom_fields($location_id){
            if(empty($location_id)) return;

            $featured_image = TravelAgency_Input()->post('featured_image');
            $icon_location = TravelAgency_Input()->post('icon_location');
            update_tax_meta($location_id,'featured_image',$featured_image);
            update_tax_meta($location_id,'icon_location',$icon_location);
        }

        function _edit_custom_fields($term_object){
            if(empty($term_object->term_id)) $location_id = 0; else $location_id = $term_object->term_id;

            $wpbooking_featured_image = get_tax_meta($location_id , 'featured_image');
            $thumbnail_url = wp_get_attachment_url( $wpbooking_featured_image );
            ?>
            <tr class="form-field admin_location_img">
                <th scope="row" valign="top">
                    <label><?php echo esc_html__('Location Featured Image', 'travelagency'); ?></label>
                </th>
                <td>
                    <div class="upload-wrapper">
                        <div class="upload-items">
                            <?php
                            if( !empty( $thumbnail_url ) ):
                                ?>
                                <div class="upload-item">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_html__('Featured Thumb','travelagency')?>" class="frontend-image img-responsive">
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" class="save-image-id" name="featured_image" value="<?php echo esc_attr($wpbooking_featured_image); ?>">
                        <button type="button" class="upload-button <?php  if( empty( $thumbnail_url ) ) echo 'no_image'; ?>" data-uploader_title="<?php esc_html_e('Select an image to upload', 'travelagency'); ?>" data-uploader_button_text="<?php esc_html_e('Use this image', 'travelagency'); ?>"><?php echo esc_html__('Upload', 'travelagency'); ?></button>
                        <button type="button" class="delete-button <?php  if( empty( $thumbnail_url ) ) echo 'none'; ?>" data-delete-title="<?php echo esc_html__('Do you want delete this image?','travelagency')?>"><?php echo esc_html__('Delete', 'travelagency'); ?></button>
                    </div>
                </td>
            </tr>
            <?php
        }

        static function inst(){
            if(empty(self::$_inst)){
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }
    TravelAgency_LocationController::inst();
}