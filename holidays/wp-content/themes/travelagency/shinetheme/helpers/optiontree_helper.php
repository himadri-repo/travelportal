<?php
/**
 * Created by ShineTheme.
 * User: Sejinichi
 * Date: 9/26/2017
 * Version: 1.0
 */
if (!function_exists('travelagency_get_option')) {
    function travelagency_get_option($key, $default = NULL)
    {
        if (function_exists('ot_get_option')) {
            return ot_get_option($key, $default);
        }

        return $default;
    }
}