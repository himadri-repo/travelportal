<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 3:11 PM
 */

if (!function_exists('st_shortcode_button') and function_exists('stp_reg_shortcode')) {
    function st_shortcode_button($arg, $content = false)
    {
        $disabled = $type = $type_color = $size = $block = $badge = '';
        $default = array(
            'type' => 'button',
            'type_color' => 'default',
            'size' => 's',
            'block' => '',
            'badge' => '',
            'disabled' => ''
        );
        extract(wp_parse_args($arg, $default));
        if (!empty($badge)) {
            $badge = '<span class="badge">' . $badge . '</span>';
        }
        return '<button ' . $disabled . ' type="' . $type . '" class="btn btn-' . $type_color . ' btn-' . $size . ' btn-' . $block . ' ">' . $content . $badge . '</button>';
    }

    stp_reg_shortcode('button', 'st_shortcode_button');
}