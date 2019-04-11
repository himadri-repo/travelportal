<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 3/22/2018
 * Version: 1.0
 */
global $kc;
$kc->add_map(
    array(
        'st_blog' => array(
            'name' => esc_html__('Travel Agency Blog', 'travelagency'),
            'icon' => 'icon-st',
            'category' => 'Shinetheme',
            'params' => array(
                array(
                    'type' => 'number_slider',
                    'label' => esc_html__('Number Post', 'travelagency'),
                    'name' => 'number_post',
                    'options' => array(
                        'min' => '1',
                        'max' => '30',
                        'unit' => esc_html__('post','travelagency'),
                        'show_input' => true
                    ),
                    'value' => '12',
                    'description' => esc_html__('Number post in page','travelagency')
                ),
                array(
                    'type' => 'checkbox',
                    'label' => esc_html__('Select Categories','travelagency'),
                    'name' => 'categories',
                    'options' => travelagency_kc_list_taxonomy('category'),
                    'value' => 'all',
                    'description' => esc_html__('Select categories', 'travelagency')
                ),
                array(
                    'type' => 'select',
                    'label' => esc_html__('Order By', 'travelagency'),
                    'name' => 'orderby',
                    'options' => travelagency_get_order_list(),
                    'value' => 'ID'
                ),
                array(
                    'type' => 'select',
                    'label' => esc_html__('Order', 'travelagency'),
                    'name' => 'order',
                    'options' => array(
                        'ASC' => esc_html__('Ascending', 'travelagency'),
                        'DESC' => esc_html__('Descending', 'travelagency')
                    ),
                    'value' => 'DESC'
                ),
                array(
                    'type' => 'toggle',
                    'label' => esc_html__('Show Pagination', 'travelagency'),
                    'name' => 'show_pagi',
                    'value' => 'yes',
                    'description' => esc_html__('Show pagination on page', 'travelagency')
                )
            )
        )
    )
);

stp_reg_shortcode('st_blog', 'travelagency_kc_blog');

if(!function_exists('travelagency_kc_blog')){
    function travelagency_kc_blog($atts, $content = false){
        $output = '';

        $atts = shortcode_atts(array(
            'number_post' => '12',
            'categories' => '',
            'orderby' => 'ID',
            'order' => 'DESC',
            'show_pagi' => 'yes'
        ), $atts);

        $output .= TravelAgency_Template::inst()->load_view('elements/st-blog/blog',false , array(
            'atts' => $atts
        ));

        return $output;
    }
}