<?php

/**
 * Class Name: Agency_Custom_Navwalker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
if(!class_exists('Agency_Custom_Navwalker')) {
    class Agency_Custom_Navwalker extends Walker_Nav_Menu
    {

        /**
         * @see Walker::start_lvl()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int $depth Depth of page. Used for padding.
         */
        public function start_lvl(&$output, $depth = 0, $args = array())
        {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul role=\"menu\" class=\" \"  >\n";
        }

        /**
         * @see Walker::start_el()
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param int $current_page Menu item ID.
         * @param object $args
         */
        public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
        {
            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            /**
             * Dividers, Headers or Disabled
             * =============================
             * Determine whether the item is a Divider, Header, Disabled or regular
             * menu item. To prevent errors we use the strcasecmp() function to so a
             * comparison that is not case sensitive. The strcasecmp() function returns
             * a 0 if the strings are equal.
             */
            if (strcasecmp($item->attr_title, 'divider') == 0 && $depth === 1) {
                $output .= $indent . '<li role="presentation" class="divider">';
            } else if (strcasecmp($item->title, 'divider') == 0 && $depth === 1) {
                $output .= $indent . '<li role="presentation" class="divider">';
            } else if (strcasecmp($item->attr_title, 'dropdown-header') == 0 && $depth === 1) {
                $output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr($item->title);
            } else if (strcasecmp($item->attr_title, 'disabled') == 0) {
                $output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr($item->title) . '</a>';
            } else {

                $page_mega = get_post_meta($item->ID,'list_page_menu',true);

                $class_names = $value = '';

                $classes = empty($item->classes) ? array() : (array)$item->classes;
                $classes[] = 'menu-item-' . $item->ID;

                $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

                if ($args->has_children) {
                    $class_names .= ' dropdown';

                }
                if($depth === 1 && !empty($page_mega)){
                    $class_names .= ' has_mega_menu mega_menu';
                }

                /* menu active #### */

                $menu_active = travelagency_get_option_with_meta('st_menu_color_active','');
                if(!empty($menu_active)){
                    $menu_active = TravelAgency_Assets()->build_css('color:'.$menu_active.' !important','>a');
                }
                if (in_array('current-menu-item', $classes))
                    $class_names .= ''.$menu_active.' active';

                $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' "' : '';

                $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
                $id = $id ? ' id="' . esc_attr($id) . '"' : '';

                $output .= $indent . '<li' . $id . $value . $class_names . '>';

                $atts = array();
                $atts['title'] = !empty($item->title) ? $item->title : '';
                $atts['target'] = !empty($item->target) ? $item->target : '';
                $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';

                // If item has_children add atts to a.
                if ($args->has_children && $depth === 0) {
                    $atts['href'] = !empty($item->url) ? $item->url : '';
//					$atts['data-toggle'] = 'dropdown';
                    $atts['aria-haspopup'] = 'true';
                } else {
                    $atts['href'] = !empty($item->url) ? $item->url : '';
                }

                $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

                $attributes = '';
                foreach ($atts as $attr => $value) {
                    //echo "<pre>";print_r($attr);echo "</pre>";
                    if (!empty($value)) {
                        $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                        $attributes .= ' ' . $attr . '="' . $value . '"';
                    }
                }

                $item_output = $args->before;

                /*
                 * Glyphicons
                 * ===========
                 * Since the the menu item is NOT a Divider or Header we check the see
                 * if there is a value in the attr_title property. If the attr_title
                 * property is NOT null we apply it as the class name for the glyphicon.
                 */

                if (!empty($item->attr_title))
                    $item_output .= '<a ' . $attributes . '><span class="glyphicon ' . esc_attr($item->attr_title) . '"></span>&nbsp;';
                else
                    $item_output .= '<a ' . $attributes . '>';


                $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
                if($args->has_children) {
                    $item_output .= '<i class=""></i></a>';
                }else{
                    $item_output .= '</a>';
                }


                $item_output .= $args->after;

                $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
            }
        }

        /**
         * Traverse elements to create list from elements.
         *
         * Display one element if the element doesn't have any children otherwise,
         * display the element and its children. Will only traverse up to the max
         * depth and no ignore elements under that depth.
         *
         * This method shouldn't be called directly, use the walk() method instead.
         *
         * @see Walker::start_el()
         * @since 2.5.0
         *
         * @param object $element Data object
         * @param array $children_elements List of elements to continue traversing.
         * @param int $max_depth Max depth to traverse.
         * @param int $depth Depth of current element.
         * @param array $args
         * @param string $output Passed by reference. Used to append additional content.
         * @return null Null on failure with no changes to parameters.
         */
        public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
        {
            if (!$element)
                return;

            $id_field = $this->db_fields['id'];

            // Display this element.
            if (is_object($args[0]))
                $args[0]->has_children = !empty($children_elements[$element->$id_field]);

            parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }

        /**
         * Menu Fallback
         * =============
         * If this function is assigned to the wp_nav_menu's fallback_cb variable
         * and a manu has not been assigned to the theme location in the WordPress
         * menu manager the function with display nothing to a non-logged in user,
         * and will add a link to the WordPress menu manager if logged in as an admin.
         *
         * @param array $args passed from the wp_nav_menu function.
         *
         */
        public static function fallback($args)
        {
            if (current_user_can('manage_options')) {

                extract($args);

                $fb_output = null;

                if ($container) {
                    $fb_output = '<' . $container;

                    if ($container_id)
                        $fb_output .= ' id="' . $container_id . '"';

                    if ($container_class)
                        $fb_output .= ' class="' . $container_class . '"';

                    $fb_output .= '>';
                }

                $fb_output .= '<ul';

                if ($menu_id)
                    $fb_output .= ' id="' . $menu_id . '"';

                if ($menu_class)
                    $fb_output .= ' class="' . $menu_class . '"';

                $fb_output .= ' data-in="fadeInDown" data-out="fadeOut"';

                $fb_output .= '>';
                $fb_output .= '<li><a href="' . admin_url('nav-menus.php') . '">Add a menu</a></li>';
                $fb_output .= '</ul>';

                if ($container)
                    $fb_output .= '</' . $container . '>';

                echo do_shortcode($fb_output);
            }
        }
    }
}


if(!class_exists('Agency_Dl_Menu_Walker'))
{
    class Agency_Dl_Menu_Walker extends Walker_Nav_Menu
    {
        function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
            $id_field = $this->db_fields['id'];

            if ( is_object( $args[0] ) ) {
                $args[0]->has_children = !empty( $children_elements[$element->$id_field] );
            }
            return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
        }
        public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
            $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . esc_attr($item->ID);

            /**
             * Filter the CSS class(es) applied to a menu item's list item element.
             *
             * @since 3.0.0
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
             * @param object $item    The current menu item.
             * @param array  $args    An array of {@see wp_nav_menu()} arguments.
             * @param int    $depth   Depth of menu item. Used for padding.
             */
            $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
            $class_names = $class_names ? ' class="dropdown ' . esc_attr( $class_names ) . '"' : '';

            /**
             * Filter the ID applied to a menu item's list item element.
             *
             * @since 3.0.1
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
             * @param object $item    The current menu item.
             * @param array  $args    An array of {@see wp_nav_menu()} arguments.
             * @param int    $depth   Depth of menu item. Used for padding.
             */
            $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
            $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

            $output .= $indent . '<li' . $id . $class_names .'>';

            $atts = array();
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
            $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
            $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

            /**
             * Filter the HTML attributes applied to a menu item's anchor element.
             *
             * @since 3.6.0
             * @since 4.1.0 The `$depth` parameter was added.
             *
             * @param array $atts {
             *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
             *
             *     @type string $title  Title attribute.
             *     @type string $target Target attribute.
             *     @type string $rel    The rel attribute.
             *     @type string $href   The href attribute.
             * }
             * @param object $item  The current menu item.
             * @param array  $args  An array of {@see wp_nav_menu()} arguments.
             * @param int    $depth Depth of menu item. Used for padding.
             */
            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                    $attributes .= ' ' . $attr . '="' . $value . '"';
                }
            }

            $item_output = $args->before;
            if ( $args->has_children ) {
                $item_output .= '<a'. $attributes .' >';
            }else{
                $item_output .= '<a'. $attributes .'>';
            }
            /** This filter is documented in wp-includes/post-template.php */
            $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

            if ( $args->has_children ) {
                $item_output .= ' ';
            }

            $item_output .= '</a>';
            $item_output .= $args->after;

            /**
             * Filter a menu item's starting output.
             *
             * The menu item's starting output only includes `$args->before`, the opening `<a>`,
             * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
             * no filter for modifying the opening and closing `<li>` for a menu item.
             *
             * @since 3.0.0
             *
             * @param string $item_output The menu item's starting HTML output.
             * @param object $item        Menu item data object.
             * @param int    $depth       Depth of menu item. Used for padding.
             * @param array  $args        An array of {@see wp_nav_menu()} arguments.
             */
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }

        /**
         * Ends the element output, if needed.
         *
         * @see Walker::end_el()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param object $item   Page data object. Not used.
         * @param int    $depth  Depth of page. Not Used.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         */
        public function end_el( &$output, $item, $depth = 0, $args = array() ) {
            $output.= "</li>";
        }

        /**
         * Starts the list before the elements are added.
         *
         * @see Walker::start_lvl()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         */
        public function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"dl-submenu\">\n";
        }

        /**
         * Ends the list of after the elements are added.
         *
         * @see Walker::end_lvl()
         *
         * @since 3.0.0
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of menu item. Used for padding.
         * @param array  $args   An array of arguments. @see wp_nav_menu()
         */
        public function end_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat("\t", $depth);
            $output .= "$indent</ul>\n";
        }

    }
}