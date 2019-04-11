<?php
if(!function_exists('WPBooking')){
    return;
}
if(!function_exists('agency_kc_slider')){
    function agency_kc_slider($atts){
        $html = $first_text_field = '';
        $data = shortcode_atts(array(
            'first_text_field' => '',
            'images' => '',
            'height' => '510px',
            'title' => '',
            'link_custom' => '',
            'link' => '',
            'bg_box' => '',
            'tour_type' => '',
            'nav' => '',
            'dots' => '',
            'overlay' => '',
        ),$atts);
        $html .= '<div class="st-slider">';
            $html .= TravelAgency_Template()->load_view('elements/st-slider/style-1',false,array(
                'data' => $data,
            ));
        $html .= '</div>';
        return $html;
    }

}
stp_reg_shortcode('agency_slider', 'agency_kc_slider');
kc_add_map(
    array(
        'agency_slider' =>
            array(
                'name' => esc_html__('Travel Agency Slider','travelagency'),
                'description' => esc_html__('Create Slider','travelagency'),
                'icon' => 'icon-st',
                'category' => 'Shinetheme',
                'params' => array(
                    array(
                        'name' => 'images',
                        'label' => esc_html__('Images','travelagency'),
                        'type' => 'attach_images',
                        'description' => esc_html__('Select Images( Select Multiple Images with the CTRL )','travelagency'),
                    ),
                    array(
                        'name' => 'overlay',
                        'label' => esc_html__('Overlay slider','travelagency'),
                        'type' => 'color_picker',
                    ),
                    array(
                        'name' => 'height',
                        'label' => esc_html__('Height of Slider','travelagency'),
                        'type' => 'number_slider',
                        'value' => '510px',
                        'options' =>array(
                            'min' => 200,
                            'max' => 800,
                            'unit' => 'px',
                            'show_input' => true
                        ),
                    ),
                    array(
                        'name' => 'title',
                        'label' => esc_html__('Title of box','travelagency'),
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'bg_box',
                        'label' => esc_html__('Background of box','travelagency'),
                        'type' => 'color_picker',
                    ),
                    array(
                        'name' => 'tour_type',
                        'label' => esc_html__('Link Tour Type','travelagency'),
                        'description' => esc_html__('Enter Tour Type add to link','travelagency'),
                        'type' => 'autocomplete',
                        'options' => array(
                            'multiple'      => false,
                            'post_type'     => 'wpbooking_service',
                            'taxonomy'      => 'wb_tour_type',
                        ),
                        'relation' => array(
                            'parent' => 'link_custom',
                            'hide_when' => 'yes',
                        )
                    ),
                    array(
                        'name' => 'link_custom',
                        'label' => esc_html__('Use custom link','travelagency'),
                        'type' => 'checkbox',
                        'options' => array(
                           'yes' => esc_html__('Yes','travelagency'),
                        ),
                    ),
                    array(
                        'name' => 'link',
                        'label' => esc_html__('Enter your link','travelagency'),
                        'type' => 'link',
                        'relation' => array(
                            'parent' => 'link_custom',
                            'show_when' => 'yes',
                        )
                    ),
                    array(
                        'name' => 'nav',
                        'label' => esc_html__('Show Navigation slider','travelagency'),
                        'type' => 'checkbox',
                        'options' => array(
                            'yes' => esc_html__('Yes','travelagency'),
                        )
                    ),
                    array(
                        'name' => 'dots',
                        'label' => esc_html__('Show dots slider','travelagency'),
                        'type' => 'checkbox',
                        'options' => array(
                            'yes' => esc_html__('Yes','travelagency'),
                        )
                    ),
                )
            ),
    ));