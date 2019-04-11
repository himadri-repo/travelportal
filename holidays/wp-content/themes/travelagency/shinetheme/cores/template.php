<?php
/**
 * Created by travelagency.
 * Developer: nasanji
 * Date: 9/26/2017
 * Version: 1.0
 */

if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_Template')) {
    class TravelAgency_Template
    {

        static $_inst;

        public $_template_dir;

        public $_message_session_key;

        function __construct()
        {
            // Init some environment
            $this->_template_dir = apply_filters('st_template_dir', 'st_templates');
            $this->_message_session_key = apply_filters('st_message_session_key', 'st_message');

        }

        function load_view($view_name, $slug = false, $data = NULL, $echo = FALSE)
        {
            if ($data) extract($data);

            if ($slug) {
                $template = locate_template($this->_template_dir . '/' . $view_name . '-' . $slug . '.php');
                if (!$template) {
                    $template = locate_template($this->_template_dir . '/' . $view_name . '.php');
                }
            } else {
                $template = locate_template($this->_template_dir . '/' . $view_name . '.php');
            }

            //Allow Template be filter
            $template = apply_filters('st_load_view_' . $template, $template, $view_name, $slug);

            if (file_exists($template)) {

                if (!$echo) {
                    ob_start();
                    include $template;

                    return @ob_get_clean();
                } else

                    include $template;
            }
        }

        function set_message($message, $type = 'info', $clear = false)
        {
            if ($clear) {
                $this->clear_messages();
            }


            $messages = $this->get_messages();

            if (!is_array($message)) {
                $messages = array(
                    array(
                        'message' => $message,
                        'type' => $type
                    )
                );
            } else {

                $messages[] = array(
                    'message' => $message,
                    'type' => $type
                );
            }

            TravelAgency_Session()->set($this->_message_session_key, $messages);
        }

        function get_messages()
        {
            return TravelAgency_Session()->get($this->_message_session_key, array());
        }

        function get_message($first = false)
        {
            $messages = $this->get_messages();
            if (!$first) return array_pop($messages);
            else return array_shift($messages);
        }

        function clear_messages()
        {
            TravelAgency_Session()->destroy($this->_message_session_key);
        }

        function message($first = false)
        {
            $message = $this->get_message($first);
            return $this->_message_to_string($message);
        }

        function messages()
        {
            $all = $this->get_messages();

            if (!empty($all)) {
                $html = '';

                foreach ($all as $key => $value) {
                    $html .= $this->_message_to_string($value);
                }

                return $html;
            }


        }

        function _message_to_string($message)
        {

            TravelAgency_Config::inst()->load('template');
            $layout = TravelAgency_Config::inst()->get('message_layout');

            $html = sprintf($layout, $message['type'], $message['message']);

            return apply_filters('st_messagge_to_string', $html, $message);
        }

        public function get_vc_pagecontent($page_id = false)
        {
            if ($page_id) {
                $page = get_post($page_id);

                if ($page) {
                    $content = apply_filters('the_content', $page->post_content);

                    $content = str_replace(']]>', ']]&gt;', $content);


                    $shortcodes_custom_css = get_post_meta($page_id, '_wpb_shortcodes_custom_css', true);

                    TravelAgency_Assets()->add_css($shortcodes_custom_css);

                    wp_reset_postdata();

                    return $content;
                }
            }
        }

        function remove_wpautop($content, $autop = false)
        {

            if ($autop) {
                $content = wpautop(preg_replace('/<\/?p\>/', "\n", $content) . "\n");
            }
            return do_shortcode(shortcode_unautop($content));
        }

        static function inst()
        {

            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }
    }

    TravelAgency_Template::inst();

    if (!function_exists('TravelAgency_Template')) {
        function TravelAgency_Template()
        {
            return TravelAgency_Template::inst();
        }
    }
}