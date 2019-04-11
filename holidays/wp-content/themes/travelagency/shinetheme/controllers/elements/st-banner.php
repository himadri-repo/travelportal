<?php
if(!function_exists('travelagency_kc_banner')){
    function travelagency_kc_banner($atts){
        $html = $first_text_field = '';
        $data = shortcode_atts(array(
            'bg_image' => '',
            'show_breadcrumb' => 'yes',
        ),$atts);
        $html .= '<div class="st-banner">';
            $html .= TravelAgency_Template()->load_view('elements/st-banner/style-1',false,array(
                'data' => $data,
            ));
        $html .= '</div>';
        return $html;
    }

}
stp_reg_shortcode('st_banner', 'travelagency_kc_banner');
global $kc;
$kc->add_map(
    array(
        'st_banner' =>
            array(
                'name' => esc_html__('Travel Agency Banner','travelagency'),
                'description' => esc_html__('Create Banner','travelagency'),
                'icon' => 'icon-st',
                'category' => 'Shinetheme',
                'params' => array(
                    array(
                        'name' => 'bg_image',
                        'label' => esc_html__('Background Images','travelagency'),
                        'type' => 'attach_image',
                        'description' => esc_html__('Select a image for background of banner','travelagency'),
                    ),
                    [
                        'name' => 'show_breadcrumb',
                        'label' => esc_html__('Show Breadcrumb', 'travelagency'),
                        'type' => 'toggle',
                        'value' => 'yes',
                    ]

                )
            ),
    ));