<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 2/28/15
 * Time: 9:12 PM
 */

if (!class_exists('TravelAgency_Assets')) {
    class TravelAgency_Assets
    {

        static $_inst;
        public $asset_url;

        public $inline_css;
        public $current_css_id;
        public $prefix_class = "st_";

        function __construct()
        {
            $this->current_css_id = time();
            add_action('wp_footer', array($this, '_action_footer_css'));

            $this->asset_url = TravelAgency_Config::inst()->get('asset_url');
        }

        function url($file = false)
        {
            return $this->asset_url . '/' . $file;
        }

        function build_css($string = false, $effect = false)
        {
            $this->current_css_id++;
            $this->inline_css .= "
                ." . $this->prefix_class . $this->current_css_id . $effect . "{
                    {$string}
                }
        ";
            return $this->prefix_class . $this->current_css_id;
        }

        function add_css($string = false)
        {
            $this->inline_css .= $string;

        }

        function _action_footer_css()
        {
            ?>
            <style id="stassets_footer_css">
                <?php echo ($this->inline_css); ?>
            </style>
            <?php
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }

    }

    TravelAgency_Assets::inst();

    if (!function_exists('TravelAgency_Assets')) {
        function TravelAgency_Assets()
        {
            return TravelAgency_Assets::inst();
        }
    }


}