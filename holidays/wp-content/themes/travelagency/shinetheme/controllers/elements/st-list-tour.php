<?php

if(!function_exists('WPBooking')){
    return;
}
if(!function_exists('agency_kc_list_tour')){
    function agency_kc_list_tour($atts){
        $html = $style = $order = $order_by = $custom_id = $tour_ids = $post_per_page = '';
        $data = shortcode_atts(array(
            'style' => 'style-1',
            'order' => '',
            'order_by' => '',
            'tour_ids' => '',
            'custom_id' => '',
            'post_per_page' => 3,
            'tour_type' => '',
            'item_per_line' => '4',
            'view_all' => '',
        ),$atts);
        extract($data);
        $args = array(
            'post_type' => 'wpbooking_service',
            'order' => $order,
            'orderby' => $order_by,
            'posts_per_page' => $post_per_page,
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'enable_property',
                    'value' => 'on',
                ),
                array(
                    'key' => 'service_type',
                    'value' => 'tour',
                )
            ),
        );

        $post_id_array = array();
        $post_id = array();
        if($custom_id == 'yes'){
            if(!empty($tour_ids)){
                $value = explode(',',$tour_ids);
                foreach($value as $k => $v){
                    $post_id_array[] =  explode(':',$v);
                }
                foreach($post_id_array as $k => $v){
                    $post_id[] = $v[0];
                }
            }
            $args = array(
                'post_type' => 'wpbooking_service',
                'order' => $order,
                'orderby' => 'post__in',
                'posts_per_page' => $post_per_page,
                'post__in' => $post_id,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'enable_property',
                        'value' => 'on',
                    ),
                    array(
                        'key' => 'service_type',
                        'value' => 'tour',
                    )
                ),
            );
        }

        $tour_query = new WP_Query($args);
        if($tour_query->have_posts()){
            $html .='<div class="st-list-tour st-row row">';
                $html .= TravelAgency_Template()->load_view('elements/st-list-tour/'.$style,false,array(
                    'data' => $data,
                    'tour_query' => $tour_query,
            ));
            $html .='</div>';
        }
        return $html;
    }
}
stp_reg_shortcode('agency_list_tour','agency_kc_list_tour');
kc_add_map(
    array(
        'agency_list_tour' => array(
            'name' => esc_html__('Travel Agency List Tour','travelagency'),
            'description' => esc_html__('List Your Tour','travelagency'),
            'icon' => 'icon-st',
            'category' => 'Shinetheme',
            'params' => array(
                array(
                    'name' => 'order_by',
                    'label' => esc_html__('Order by','travelagency'),
                    'type' => 'select',
                    'options' => travelagency_get_order_list(),
                ),
                array(
                    'name' => 'order',
                    'label' => esc_html__('Order','travelagency'),
                    'type' => 'select',
                    'options' => array(
                        'ASC' => esc_html__('ASC','travelagency'),
                        'DESC' => esc_html__('DESC','travelagency'),
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
                    'name' => 'custom_id',
                    'label' => esc_html__('Use Custom List Tour','travelagency'),
                    'type' => 'checkbox',
                    'options' => array(
                        'yes' => esc_html__('Yes','travelagency'),
                    )
                ),
                array(
                    'name' => 'tour_ids',
                    'label' => esc_html__('List Your Tour','travelagency'),
                    'type' => 'autocomplete',
                    'options' => array(
                        'multiple' => true,
                        'post_type' => 'wpbooking_service'
                    ),
                    'relation' => array(
                        'parent' => 'custom_id',
                        'show_when' => 'yes',
                    )
                ),
                array(
                    'name' => 'tour_type',
                    'label' => esc_html__('Link Tour Type','travelagency'),
                    'description' => esc_html__('Enter Tour Type add to link','travelagency'),
                    'type' => 'autocomplete',
                    'options' => array(
                        'multiple'      => true,
                        'post_type'     => 'wpbooking_service',
                        'taxonomy'      => 'wb_tour_type',
                    ),
                    'relation' => array(
                        'parent' => 'link_custom',
                        'hide_when' => 'yes',
                    )
                ),
                array(
                    'name' => 'view_all',
                    'label' => esc_html__('Show View all button?','travelagency'),
                    'type' => 'checkbox',
                    'description' => esc_html__('Choose to show button View all','travelagency'),
                    'options' => array(
                        'yes' => esc_html__('Yes','travelagency'),
                    )
                ),
            )
        )
    )
);