<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 12/1/2017
 * Version: 1.0
 */

if(!class_exists('ShinethemeCore_Controller')){
    class ShinethemeCore_Controller{

        static $_inst;
        function __construct()
        {
            add_filter('user_contactmethods', [$this, '_add_user_contactmethods']);
        }

        function _add_user_contactmethods($user_contactmethods)
        {
            $extra_fields = [
                ['facebook', esc_html__('Facebook', 'vivavivu'), true],
                ['twitter', esc_html__('Twitter', 'vivavivu'), true],
                ['google', esc_html__('Google+', 'vivavivu'), true],
                ['instagram', esc_html__('Instagram', 'vivavivu'), false],
            ];

            foreach ($extra_fields as $field) {
                if (!isset($contactmethods[$field[0]]))
                    $user_contactmethods[$field[0]] = $field[1];
            }
            return $user_contactmethods;
        }

        static function inst(){
            if(empty(self::$_inst))
                self::$_inst = new self();

            return self::$_inst;
        }
    }

    ShinethemeCore_Controller::inst();
}