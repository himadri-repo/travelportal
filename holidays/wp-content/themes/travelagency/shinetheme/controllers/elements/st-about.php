<?php
if(!function_exists('WPBooking')){
    return;
}
if(!function_exists('travelagency_kc_about')) {
    function travelagency_kc_about( $atts )
    {
        $html = $title = $des = $icon = '';

        $data = shortcode_atts( array(
            'title' => '',
            'des' => '',
            'icon' => '',
        ),$atts );
        extract($data);
            $html .= '<div class="st-abour">';
                $html .= TravelAgency_Template()->load_view('elements/st-about/style-1',false,array(
                  'data' => $data,
                ));
            $html .= '</div>';
        return $html;
    }
}
stp_reg_shortcode('travelagency_about','travelagency_kc_about');
kc_add_map(
    array(
        'travelagency_about' => array(
            'name' => esc_html__('Travel Agency About','travelagency'),
            'description' => esc_html__('Create About','travelagency'),
            'icon' => 'icon-st',
            'category' => 'Shinetheme',
            'params' => array(
                array(
                    'name' => 'title',
                    'type' => 'textfield',
                    'label' => esc_html__('Title','travelagency'),
                ),
                array(
                    'name' => 'des',
                    'type' => 'textarea',
                    'label' => esc_html__('Description','travelagency'),
                ),
                array(
                    'name' => 'icon',
                    'type' => 'icon_picker',
                    'label' => esc_html__('Icon','travelagency'),
                ),
            )
        )
    )
);