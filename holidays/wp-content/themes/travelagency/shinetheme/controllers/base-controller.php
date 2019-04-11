<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 11:53 AM
 */
if (!defined('ABSPATH')) return;

if (!class_exists('TravelAgency_BaseController')) {
    class TravelAgency_BaseController
    {

        static $_inst;

        function __construct()
        {
            //Default Framwork Hooked
            add_filter('wp_title', array($this, '_wp_title'), 10, 2);
            add_action('wp', array($this, '_setup_author'));
            add_action('after_setup_theme', array($this, '_after_setup_theme'), 20);
            add_action('widgets_init', array($this, '_add_sidebars'));

            add_action('wp_enqueue_scripts', array($this, '_add_scripts'));
            add_action('get_footer', array($this, '_enqueue_scripts_footer'), 9999);


//            //Custom hooked
            add_filter('travelagency_get_sidebar', array($this, '_blog_filter_sidebar'));
            add_filter('travelagency_get_sidebar', array($this, '_post_type_filter_sidebar'));

            add_action('wp_head', array($this, '_show_custom_css'), 100);

            add_filter('wp_nav_menu',[$this,'style_menu_item']);

            add_filter( 'script_loader_src', array( $this, '_remove_script_version' ), 50, 1 );
            add_filter( 'style_loader_src', array( $this, '_remove_script_version' ), 50, 1 );

            add_filter('style_loader_tag', array( $this,'myplugin_remove_type_attr'), 1, 2);
            add_filter('script_loader_tag', array( $this,'myplugin_remove_type_attr'), 1, 2);

            if(function_exists('WPBooking')) {
                add_filter( 'login_url' , array( $this , '_redirect_login_url' ) , 10 , 3 );
            }

        }

        function _remove_script_version( $src )
        {
            $parts = explode( '?ver', $src );

            return $parts[ 0 ];
        }

        function myplugin_remove_type_attr($tag, $handle) {
            return preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $tag );
        }

        function _redirect_login_url($login_url, $redirect){
            $account_page = wpbooking_get_option('myaccount-page');
            $account_page = apply_filters('wpbooking_set_page_login', $account_page);
            if(!empty($account_page)) {
                $redir = get_permalink($account_page);
                if(!empty($redirect)) {
                    $redir = add_query_arg('redirect_to', $redirect, $redir);
                }
                return esc_url($redir);
            }else {
                return $login_url;
            }
        }

        function style_menu_item($menuclass){
            $style = travelagency_get_option_with_meta('st_menu_color','');
            $menu_hover = travelagency_get_option_with_meta('st_menu_color_hover','');
            if(!empty($menu_hover)){
                $menu_hover = TravelAgency_Assets()->build_css('color:'.$menu_hover.' !important',':hover');
            }
            if(!empty($style)){
                $style = TravelAgency_Assets()->build_css('font-size: '.$style['font-size'].' !important;color: '.$style['font-color'].' !important;font-family:'.$style['font-family'].' !important;'.$style['font-style'].' !important;font-weight:'.$style['font-weight'].' !important;letter-spacing:'.$style['letter-spacing'].' !important;line-height:'.$style['line-height'].';text-decoration:'.$style['text-decoration'].' !important;text-transform:'.$style['text-transform'].' !important');
            }
            echo preg_replace('/<a /', '<a class="normal-class '.esc_attr($style).' '.esc_attr($menu_hover).'" ', $menuclass);
        }

        function _show_custom_css()
        {
            $style = TravelAgency_Template()->load_view('custom_css');

            ?>
            <style id="st_cutom_css">
                <?php echo ($style);?>
            </style>
            <?php
            echo "\n";

        }

        function _blog_filter_sidebar($sidebar)
        {
            if (is_home()) {
                $pos = travelagency_get_option('st_sidebar_position_blog');
                $sidebar_id = travelagency_get_option('st_sidebar_blog');
            } else {
                if (is_single()) {
                    $pos = travelagency_get_option('st_sidebar_position_post');
                    $sidebar_id = travelagency_get_option('st_sidebar_post');
                } else {
                    $pos = travelagency_get_option('st_sidebar_position_page');
                    $sidebar_id = travelagency_get_option('st_sidebar_page');
                }
            }
            $id = get_the_ID();
            if (is_404()) $id = travelagency_get_option('st_404_page');
            if (is_front_page()) $id = (int)get_option('page_on_front');

            $sidebar_pos = get_post_meta($id, 'st_sidebar_position', true);
            $id_side_post = get_post_meta($id, 'st_select_sidebar', true);
            if (!empty($sidebar_pos)) {
                $pos = $sidebar_pos;
                $sidebar_id = $id_side_post;
            }
            if ($sidebar_id) {
                $sidebar['id'] = $sidebar_id;
            }

            if ($pos) {
                $sidebar['position'] = $pos;
            }
            if (TravelAgency_Input()->get('sidebar_pos')) {
                $sidebar['position'] = TravelAgency_Input()->get('sidebar_pos');
            }

            return $sidebar;
        }


        function _post_type_filter_sidebar($sidebar)
        {
            return $sidebar;
        }

        function studio_googlemap_url()
        {
            $key = travelagency_get_option('google_api_key', 'AIzaSyAwXoW3vyBK0C5k2G-0l1D3n10UJ3LwZ3k');
            if (is_ssl()) {
                $url = add_query_arg('key', $key, "https://maps.googleapis.com/maps/api/js");
            } else {
                $url = add_query_arg('key', $key, "//maps.googleapis.com/maps/api/js");
            }
            return $url;
        }

        function studio_fonts_url()
        {
            $font_url = '';

            /*
            Translators: If there are characters in your language that are not supported
            by chosen font(s), translate this to 'off'. Do not translate into your own language.
             */
            if ('off' !== _x('on', 'Google font: on or off', 'travelagency')) {
                $font_url = add_query_arg('family', ('Lato:300,300i,400,400i,700,700i,900,900i|Open+Sans:100,400,600,700'), "//fonts.googleapis.com/css");
            }
            return $font_url;
        }

        function _add_scripts()
        {
            /*
             * Javascript
             * */
            wp_enqueue_script('carousel', TravelAgency_Assets()->url('js/owl.carousel.min.js'), array('jquery'), null, true);

            wp_register_script('bootstrap', TravelAgency_Assets()->url('bs/js/bootstrap.min.js'), array('jquery'), null, true);
            wp_enqueue_script('bootstrap');
            // CSS
            wp_register_style('bootstrap', TravelAgency_Assets()->url('bs/css/bootstrap.min.css'));
            wp_register_style('agency-style', get_stylesheet_uri());
            wp_enqueue_style('bootstrap');
            wp_enqueue_style('agency-style');

            /*  Begin Custom for theme */
            //Script

            if (!wp_script_is('wpbooking-google-map-js', 'enqueued')) {
                wp_enqueue_script('js-google-map', $this->studio_googlemap_url(), array('jquery'), null, true);
            }

            // Enqueue Script
            if (is_singular()) wp_enqueue_script("comment-reply");

            // CSS
            wp_enqueue_style('google-fonts', self::studio_fonts_url());

            if (!wp_style_is('wpbooking-font-awesome', 'enqueued')) {
                wp_enqueue_style('font-awesome-css', TravelAgency_Assets()->url('css/lib/font-awesome/css/fontawesome.min.css'));
            }
            wp_enqueue_style('carousel-style', TravelAgency_Assets()->url('css/owl.carousel.min.css'));

            wp_enqueue_style('st-style', TravelAgency_Assets()->url('css/style.css'));

            wp_enqueue_style('st-custom-style', TravelAgency_Assets()->url('css/custom.css'));
            wp_enqueue_style('st-custom-style2', TravelAgency_Assets()->url('css/custom2.css'));
        }

        function _enqueue_scripts_footer()
        {

            wp_enqueue_script('custom-js', TravelAgency_Assets()->url('js/custom.js'), array('jquery'), null, true);
            wp_enqueue_script('menumaker', TravelAgency_Assets()->url('js/menumaker.js'), array('jquery'), null, true);
            wp_enqueue_script('sticky-menu', TravelAgency_Assets()->url('js/jquery.sticky.js'), array('jquery'), null, true);
            wp_localize_script('js-sc-custom-theme', 'ajax_process', array('ajaxurl' => admin_url('admin-ajax.php')));

        }

        // -----------------------------------------------------
        // Default Hooked, Do not edit
        /**
         * Hook setup theme
         * */
        function _after_setup_theme()
        {
            /*
             * Make theme available for translation.
             * Translations can be filed in the /languages/ directory.
             * If you're building a theme based on agency, use a find and replace
             * to change $'travelagency' to the name of your theme in all the template files
             */

            // This theme uses wp_nav_menu() in one location.
            $menus = TravelAgency_Config::inst()->get('nav_menus');
            if (is_array($menus) and !empty($menus)) {
                register_nav_menus($menus);
            }

            add_theme_support("title-tag");
            add_theme_support('automatic-feed-links');
            add_theme_support('post-thumbnails');
            add_theme_support('html5', array(
                'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
            ));
            add_theme_support('post-formats', array(
                'image', 'video', 'gallery', 'audio', 'quote'
            ));
            add_theme_support('custom-header');
            add_theme_support('custom-background');

        }

        /**
         * Add default sidebar to website
         *
         *
         * */
        function _add_sidebars()
        {
            // From config file
            $sidebars = TravelAgency_Config::inst()->get('sidebars');
            if (is_array($sidebars) and !empty($sidebars)) {
                foreach ($sidebars as $value) {
                    register_sidebar($value);
                }
            }
            $add_sidebars = travelagency_get_option('st_add_sidebar');
            if (is_array($add_sidebars) and !empty($add_sidebars)) {
                foreach ($add_sidebars as $sidebar) {
                    if (!empty($sidebar['title'])) {
                        $id = strtolower(str_replace(' ', '-', $sidebar['title']));
                        $custom_add_sidebar = array(
                            'name' => $sidebar['title'],
                            'id' => $id,
                            'description' => esc_html__('SideBar created by add sidebar in theme options.', 'travelagency'),
                            'before_title' => '<' . $sidebar['widget_title_heading'] . ' class="widget-title">',
                            'after_title' => '</' . $sidebar['widget_title_heading'] . '>',
                            'before_widget' => '<div id="%1$s" class="sidebar-widget widget %2$s">',
                            'after_widget' => '</div>',
                        );
                        register_sidebar($custom_add_sidebar);
                        unset($custom_add_sidebar);
                    }
                }
            }

        }


        /**
         * Set up author data
         *
         * */
        function _setup_author()
        {
            global $wp_query;

            if ($wp_query->is_author() && isset($wp_query->post)) {
                $GLOBALS['authordata'] = get_userdata($wp_query->post->post_author);
            }
        }

        static function inst()
        {
            if (empty(self::$_inst)) {
                self::$_inst = new self();
            }

            return self::$_inst;
        }

    }

    TravelAgency_BaseController::inst();
}
