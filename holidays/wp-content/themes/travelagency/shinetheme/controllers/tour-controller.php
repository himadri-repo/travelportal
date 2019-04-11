<?php
if(!function_exists('WPBooking')){
    return;
}
if(!class_exists('TravelAgency_TourController')){
    class TravelAgency_TourController{

        static $_inst;

        function __construct() {
            add_filter('wpbooking_metabox_service_tour', [$this, '_add_tour_custom_metabox']);
        }

        function _add_tour_custom_metabox($metabox){
            $metabox['itinerary_tab'] = array(
                'label'  => esc_html__('5. Tour Programs', 'travelagency'),
                'fields' => array(
                    array('type' => 'open_section'),
                    array(
                        'label' => esc_html__('Tour Programs', 'travelagency')
                    ),
                    array(
                        'label' => esc_html__("Tour Programs", 'travelagency'),
                        'id'    => 'tour_program',
                        'type'  => 'list-item',
                        'desc'  => esc_html__('Program of the tour', 'travelagency'),
                        'value' => [
                            [
                                'label' => esc_html__("Description", 'travelagency'),
                                'id'    => 'desc',
                                'type'  => 'textarea',
                            ],
                            [
                                'label' => esc_html__("Image", 'travelagency'),
                                'id'    => 'program_image',
                                'type'  => 'image',
                            ],
                        ]
                    ),
                    array('type' => 'close_section'),
                    array(
                        'type' => 'section_navigation',
                    ),
                )
            );

            return $metabox;
        }

        static function inst(){

            if(empty(self::$_inst)){
                self::$_inst = new self();
            }

            return self::$_inst;
        }

    }
    TravelAgency_TourController::inst();
}