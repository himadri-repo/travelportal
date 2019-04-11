<?php
if(!function_exists('WPBooking')){
    return;
}
if(!class_exists('TravelAgency_ServiceController')){
    class TravelAgency_ServiceController{

        static $_inst;

        function __construct() {

            add_filter('wpbooking_default_layout_archive',[$this,'change_default_layout_archive']);
            add_filter('wpbooking_single_loop_image_size',[$this,'change_default_gallery'],10,1);
            add_filter('wpbooking_metabox_field_html_image', [$this, '_set_image_field_html_sv'], 10, 3);

            add_action('travelagency_after_step_setup_menu',array($this,'_update_price_service_after_import'));

            if(function_exists('WPBooking')){
                remove_filter('the_content', array(WPBooking_Order::inst(), '_show_order_information'));
            }
        }

        function change_default_layout_archive($default){
            $default = 'grid';
            return $default;
        }

        function _set_image_field_html_sv($field_html, $field, $post_id){

            $field_html = TravelAgency_Template()->load_view('admin/wb-meta-field/image', false, [
                'data' => $field,
                'post_id' => $post_id
            ]);
            return $field_html;
        }

        function change_default_gallery($size){
            $size = array(1170,620);
            return $size;
        }

        function _update_price_service_after_import(){
            global $wpdb;
            $query = "SELECT {$wpdb->prefix}posts.ID
                    FROM
                        {$wpdb->prefix}posts
                    INNER JOIN {$wpdb->prefix}postmeta ON (
                        {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id
                    )
                    WHERE
                        1 = 1
                    AND (
                            {$wpdb->prefix}postmeta.meta_key = 'service_type' AND {$wpdb->prefix}postmeta.meta_value LIKE '%tour%'
                    )
                    AND {$wpdb->prefix}posts.post_type = 'wpbooking_service'
                    AND {$wpdb->prefix}posts.post_status = 'publish'
                    GROUP BY
                        {$wpdb->prefix}posts.ID";
            $rs = $wpdb->get_results($query,ARRAY_A);
            if(!empty($rs)){
                foreach($rs as $k=>$v){
                    $post_id = $v['ID'];
                    $Calendar = WPBooking_Calendar_Metabox::inst();
                    $price = rand(10,500);
                    $check_in = strtotime( '+1 day' ) ;
                    $check_out = strtotime( '+3 day' );
                    $status = 'available';
                    $group_day = TravelAgency_Input()->post('group_day','');
                    $can_check_in=TravelAgency_Input()->post('can_check_in');
                    $can_check_out=TravelAgency_Input()->post('can_check_out');
                    $weekly=TravelAgency_Input()->post('weekly');
                    $monthly=TravelAgency_Input()->post('monthly');
                    $calendar_minimum=0;
                    $calendar_maximum=0;
                    $calendar_price=$price;
                    $calendar_adult_minimum=0;
                    $calendar_adult_price=$price;
                    $calendar_child_minimum=0;
                    $calendar_child_price=$price;
                    $calendar_infant_minimum=0;
                    $calendar_infant_price=$price;
                    $base_id = (int) wpbooking_origin_id( $post_id);
                    $result = $Calendar->get_availability( $base_id, $check_in, $check_out );
                    $split = $Calendar->split_availability( $result, $check_in, $check_out );
                    if( isset( $split['delete'] ) && !empty( $split['delete'] ) ){
                        foreach( $split['delete'] as $item ){
                            $Calendar->wpbooking_delete_availability( $item['id'] );
                        }
                    }
                    if( isset( $split['insert'] ) && !empty( $split['insert'] ) ){
                        foreach( $split['insert'] as $item ){
                            $Calendar->wpbooking_insert_availability( $item['post_id'], $item['base_id'], $item['start'], $item['end'], $item['price'], $item['status'], $item['group_day'],$weekly,$monthly,$can_check_in,$can_check_out,$item['calendar_minimum'],$item['calendar_maximum'],$item['calendar_price'],$item['adult_minimum'],$item['adult_price'],$item['child_minimum'],$item['child_price'],$item['infant_minimum'],$item['infant_price'] );
                        }
                    }
                    $new_item = $Calendar->wpbooking_insert_availability( $post_id, $base_id, $check_in, $check_out, $price, $status, $group_day,$weekly,$monthly,$can_check_in,$can_check_out,$calendar_minimum,$calendar_maximum,$calendar_price,$calendar_adult_minimum,$calendar_adult_price,$calendar_child_minimum,$calendar_child_price,$calendar_infant_minimum,$calendar_infant_price );
                    do_action('wpbooking_after_add_availability',$post_id);

                    $metabox2 = 'Domestic Tour';
                    //update_post_meta($post_id,'tour_type',unserialize($metabox2));
                    update_term_meta($post_id, 'tour_type', $metabox2);

                }
            }
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }
    TravelAgency_ServiceController::inst();

}