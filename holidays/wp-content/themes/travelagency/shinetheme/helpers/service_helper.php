<?php
 if(!function_exists('travelagency_get_order_list')){
     function travelagency_get_order_list($current = false, $extra = array(), $return = 'array'){
         $default = array(
             'none' => esc_html__('None', 'travelagency'),
             'ID' => esc_html__('Post ID', 'travelagency'),
             'author' => esc_html__('Author', 'travelagency'),
             'title' => esc_html__('Post Title', 'travelagency'),
             'name' => esc_html__('Post Name', 'travelagency'),
             'date' => esc_html__('Post Date', 'travelagency'),
             'modified' => esc_html__('Last Modified Date', 'travelagency'),
             'parent' => esc_html__('Post Parent', 'travelagency'),
             'rand' => esc_html__('Random', 'travelagency'),
             'comment_count' => esc_html__('Comment Count', 'travelagency'),
         );
         if (!empty($extra) and is_array($extra)) {
             $default = array_merge($default, $extra);
         }

         if ($return == "array") {
             return $default;
         } elseif ($return == 'option') {
             $html = '';
             if (!empty($default)) {
                 foreach ($default as $key => $value) {
                     $selected = selected($key, $current, false);
                     $html .= "<option {$selected} value='{$key}'>{$value}</option>";
                 }
             }
             return $html;
         }
     }
 }
if(!function_exists('travelagency_get_price_service')){
    function travelagency_get_price_service($service_id = false){

        if(empty($service_id)) $service_id = get_the_ID();
        if(!function_exists('wpbooking_service_price')) return false;

        $price = wpbooking_service_price($service_id);
        $price_html = WPBooking_Currency::format_money($price);

        return $price_html;

    }
}

if(!function_exists('travelagency_get_term_by')){
    function travelagency_get_term_by($field, $value, $tax, $need){
        $tax_object = get_term_by($field, $value, $tax);
        if(!empty($tax_object->name)){
            switch($need){
                case 'name':
                    return $tax_object->name;
                    break;
                case 'slug':
                    return $tax_object->slug;
                    break;
                case 'count':
                    return $tax_object->count;
                    break;
                case 'link':
                    $tax_link = get_term_link($tax_object,'wpbooking_location');
                    return $tax_link;
                    break;
            }
        }
        return '';
    }
}

