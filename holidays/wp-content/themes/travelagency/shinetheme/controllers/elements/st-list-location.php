<?php
if(!function_exists('WPBooking')){
    return;
}
if(!function_exists('agency_kc_list_location')) {
    function agency_kc_list_location( $atts ) {
        $html = $custom_id = $location = $post_per_page = $order_by = $order = $style = '';

        $data = shortcode_atts(array(
            'style' => 'style-1',
            'order_by' => '',
            'order' => '',
            'location' => '',
            'custom_id' => '',
            'post_per_page' => '',
            'item_per_line' => '',
            'overlay' => '',
        ),$atts);
        extract($data);
        $agrs = array(
            'taxonomy' => 'wpbooking_location',
            'hide_empty' => false,
            'number' => $post_per_page,
            'orderby' => $order_by,
            'order' => $order,
        );
        if($custom_id == 'yes' && !empty($location)){
            $slug_location = array();
            $location_arr = explode(',',$location);
            foreach($location_arr as $k => $v){
                $slug_location[] = explode(':',$v);
            }
            $slugs = array();
            foreach($slug_location as $slug){
                $slugs[] = $slug[0];
            }
            $agrs = array(
                'taxonomy' => 'wpbooking_location',
                'hide_empty' => false,
                'slug' => $slugs,
                'orderby' => $order_by,
                'order' => $order,
            );
        }

        $locations = get_terms($agrs);

        $html .='<div class="st-list-location st-row row">';
            $html .= TravelAgency_Template()->load_view('elements/st-list-location/'.$style,false,array(
                'data' => $data,
                'locations' => $locations,

            ));
        $html .='</div>';

        return $html;

    }
}
stp_reg_shortcode('agency_list_location','agency_kc_list_location');
kc_add_map(
    array(
        'agency_list_location' => array(
            'name' => esc_html__('Travel Agency List Location','travelagency'),
            'description' => esc_html__('List Your Location','travelagency'),
            'icon' => 'icon-st',
            'category' => 'Shinetheme',
            'params' => array(
                array(
                    'name' => 'style',
                    'type' => 'select',
                    'label' => esc_html__('Style','travelagency'),
                    'options' => array(
                        'style-1' => esc_html__('Grid','travelagency'),
                        'style-2' => esc_html__('Slide Show','travelagency'),
                    )
                ),
                array(
                    'name' => 'order_by',
                    'type' => 'select',
                    'label' => esc_html__('Order By','travelagency'),
                    'options' => travelagency_get_order_list(),
                ),
                array(
                    'name' => 'order',
                    'type' => 'select',
                    'label' => esc_html__('Order','travelagency'),
                    'options' => array(
                        'ASC' => esc_html__('ASC','travelagency'),
                        'DESC' => esc_html__('DESC','travelagency'),
                    )
                ),
                array(
                    'name' => 'custom_id',
                    'label' => esc_html__('Use Custom List Location','travelagency'),
                    'type' => 'checkbox',
                    'options' => array(
                        'yes' => esc_html__('Yes','travelagency'),
                    )
                ),
                array(
                    'name' => 'location',
                    'label' => esc_html__('Custom Location','travelagency'),
                    'description' => esc_html__('Enter your location','travelagency'),
                    'type' => 'autocomplete',
                    'options' => array(
                        'multiple'      => true,
                        'post_type'     => 'wpbooking_service',
                        'taxonomy'      => 'wpbooking_location',
                    ),
                    'relation' => array(
                        'parent' => 'custom_id',
                        'show_when' => 'yes',
                    )
                ),
                array(
                    'name' => 'post_per_page',
                    'label' => esc_html__('Number Item','travelagency'),
                    'type' => 'number_slider',
                    'value' => 3,
                    'unit' => 'int',
                    'options' => array(
                        'min' => 2,
                        'max' => 12,
                        'show_input' => true
                    ),
                    'relation' => array(
                        'parent' => 'custom_id',
                        'hide_when' => 'yes'
                    )
                ),
                array(
                    'name' => 'item_per_line',
                    'label' => esc_html__('Item per line','travelagency'),
                    'type' => 'select',
                    'options' => array(
                        '6' => esc_html__('2 Items','travelagency'),
                        '4' => esc_html__('3 Items','travelagency'),
                        '3' => esc_html__('4 Items','travelagency'),
                    ),
                    'value' => '4',
                ),
                array(
                    'name' => 'overlay',
                    'label' => esc_html__('Overlay Item','travelagency'),
                    'type' => 'color_picker',
                ),
            )
        )
    )
);