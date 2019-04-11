<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 2:36 PM
 */

if (!function_exists('travelagency_get_sidebar_id')) {
    function travelagency_get_sidebar_id($for_optiontree = false)
    {
        global $wp_registered_sidebars;
        $r = array();
        $r[] = esc_html__('--Select--', 'travelagency');
        if (!empty($wp_registered_sidebars)) {
            foreach ($wp_registered_sidebars as $key => $value) {

                if ($for_optiontree) {
                    $r[] = array(
                        'value' => $value['id'],
                        'label' => $value['name']
                    );
                } else {
                    $r[$value['id']] = $value['name'];
                }
            }
        }
        return $r;
    }
}

if (!function_exists('st_custom_field_types')) {
    function st_custom_field_types()
    {
        $types = array(
            array(
                'value' => 'text',
                'label' => esc_html__('Text', 'travelagency')
            ),
            array(
                'value' => 'textarea',
                'label' => esc_html__('Textarea', 'travelagency')
            ),
            array(
                'value' => 'date',
                'label' => esc_html__('Date', 'travelagency')
            ),
            array(
                'value' => 'color',
                'label' => esc_html__('Colorpicker', 'travelagency')
            ),
            array(
                'value' => 'on-off',
                'label' => esc_html__('On/Off', 'travelagency')
            ),
        );

        return $types;
    }
}

if (!function_exists('st_get_order_list')) {
    function st_get_order_list($current = false, $extra = array(), $return = 'array')
    {
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

if (!function_exists('travelagency_get_sidebar')) {
    function travelagency_get_sidebar()
    {
        $default = array(
            'position' => 'right',
            'id' => 'blog-sidebar'
        );

        return apply_filters('travelagency_get_sidebar', $default);
    }
}

//Custom Menu
if (!function_exists('st_custom_menu')) {
    function st_custom_menu()
    {
        $args = array(
            'theme_location' => 'primary',
            'menu' => '',
            'container' => '',
            'container_class' => '',
            'container_id' => '',
            'menu_class' => 'list-inline',
            'menu_id' => '',
            'echo' => true,
            'fallback_cb' => 'wp_page_menu',
            'before' => '',
            'after' => '',
            'link_before' => '',
            'link_after' => '',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'depth' => 0,
            'walker' => '',
        );
        if (has_nav_menu('primary')) {
            wp_nav_menu($args);
        }
    }
}

//Contact Form list
if (!function_exists('st_get_list_ContactForm')) {
    function st_get_list_ContactForm()
    {
        $contact = array();
        if (defined('WPCF7_VERSION')) {
            $querycontact = get_posts(array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1));
            $contact['-- Select --'] = "";
            if (is_array($querycontact)) foreach ($querycontact as $post_ct) {
                $contact[$post_ct->post_title] = $post_ct->ID;
            }
        }
        return $contact;
    }
}

//Fill class
if (!function_exists('st_fill_class')) {
    function st_fill_class($class)
    {
        $string = '';
        if (!empty($class)) $string = 'class="' . $class . '"';
        return $string;
    }
}

//Fill css background
if (!function_exists('st_fill_css_background')) {
    function st_fill_css_background($data)
    {
        $string = '';
        if (!empty($data['background-color'])) $string .= 'background-color:' . $data['background-color'] . ';' . "\n";
        if (!empty($data['background-repeat'])) $string .= 'background-repeat:' . $data['background-repeat'] . ';' . "\n";
        if (!empty($data['background-attachment'])) $string .= 'background-attachment:' . $data['background-attachment'] . ';' . "\n";
        if (!empty($data['background-position'])) $string .= 'background-position:' . $data['background-position'] . ';' . "\n";
        if (!empty($data['background-size'])) $string .= 'background-size:' . $data['background-size'] . ';' . "\n";
        if (!empty($data['background-image'])) $string .= 'background-image:url("' . $data['background-image'] . '");' . "\n";
        if (!empty($string)) return TravelAgency_Assets()->build_css($string);
        else return false;
    }
}

if (!function_exists('st_list_menu_name')) {
    function st_list_menu_name()
    {
        $menu_nav = wp_get_nav_menus();
        $menu_list = array('Default' => '');
        if (is_array($menu_nav) && !empty($menu_nav)) {
            foreach ($menu_nav as $item) {
                if (is_object($item)) {
                    $menu_list[$item->name] = $item->slug;
                }
            }
        }
        return $menu_list;
    }
}

if(!function_exists('travelagency_get_option_with_meta')){
    function travelagency_get_option_with_meta($key, $default){
        if (empty($key))
            return '';
        $res = get_post_meta(get_the_ID(), $key, true);
        if (empty($res))
            $res = travelagency_get_option($key, $default);

        return $res;
    }
}

//Display BreadCrumb
if (!function_exists('travelagency_display_breadcrumbs')) {
    function travelagency_display_breadcrumbs($echo = false)
    {
        ob_start();
        $delimiter = ' / ';
        $name = esc_html__('Home', 'travelagency');
        $currentBefore = '<span class="current">';
        $currentAfter = '</span>';
        if (!is_home() && !is_front_page() || is_paged()) {
            global $post;
            echo '<a href="' . esc_url(home_url('/')) . '">' . $name . '</a> ' . $delimiter . ' ';
            if (is_tax()) {
                $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                echo do_shortcode($currentBefore . $term->name . $currentAfter);
            } elseif (is_category()) {
                global $wp_query;
                $cat_obj = $wp_query->get_queried_object();
                $thisCat = $cat_obj->term_id;
                $thisCat = get_category($thisCat);
                $parentCat = get_category($thisCat->parent);
                if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
                echo do_shortcode($currentBefore . '');
                single_cat_title();
                echo '' . $currentAfter;
            } elseif (is_day()) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
                echo do_shortcode($currentBefore . get_the_time('d') . $currentAfter);
            } elseif (is_month()) {
                echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
                echo do_shortcode($currentBefore . get_the_time('F') . $currentAfter);
            } elseif (is_year()) {
                echo do_shortcode($currentBefore . get_the_time('Y') . $currentAfter);
            } elseif (is_single()) {
                $postType = get_post_type();
                if ($postType == 'post') {
                    the_category(', ');
                    echo do_shortcode($delimiter);
                }
                echo do_shortcode($currentBefore);
                the_title();
                echo do_shortcode($currentAfter);
            } elseif (is_page() && !$post->post_parent) {
                echo do_shortcode($currentBefore);
                the_title();
                echo do_shortcode($currentAfter);
            } elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                foreach ($breadcrumbs as $crumb) echo do_shortcode($crumb . ' ' . $delimiter . ' ');
                echo do_shortcode($currentBefore);
                the_title();
                echo do_shortcode($currentAfter);
            } elseif (is_search()) {
                echo do_shortcode($currentBefore) . esc_html__('Search Results for:', 'travelagency') . ' &quot;' . get_search_query() . '&quot;' . $currentAfter;
            } elseif (is_tag()) {
                echo do_shortcode($currentBefore) . esc_html__('Post Tagged with:', 'travelagency') . ' &quot;';
                single_tag_title();
                echo '&quot;' . $currentAfter;
            } elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);
                echo do_shortcode($currentBefore) . esc_html__('Author Archive', 'travelagency') . $currentAfter;
            } elseif (is_404()) {
                echo do_shortcode($currentBefore) . esc_html__('Page Not Found', 'travelagency') . $currentAfter;
            } elseif (is_post_type_archive('wpbooking_service') or is_tax(get_object_taxonomies('wpbooking_service'))) {
                $service_type = TravelAgency_Input()->get('service_type', '');
                $services = WPBooking_Service_Controller::inst()->get_service_type($service_type);

                if (!empty($services)) {
                    echo do_shortcode($currentBefore . $services->get_info('label') . $currentAfter);
                } else {
                    echo do_shortcode($currentBefore . esc_html__('Services', 'travelagency') . $currentAfter);
                }

            }
            if (get_query_var('paged')) {
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ' (';
                echo ' ' . $delimiter . ' ' . esc_html__('Page', 'travelagency') . ' ' . get_query_var('paged');
                if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) echo ')';
            }
        }
        $bc = ob_get_clean();
        if ($echo) {
            echo do_shortcode($bc);
        } else {
            return $bc;
        }
    }
}

if (!function_exists('travelagency_get_alt_image')) {
    function travelagency_get_alt_image($image_id)
    {
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        if (!$image_alt) {
            $image_alt = get_bloginfo('description');
        }
        return $image_alt;
    }
}


if(!function_exists('travelagency_get_pagecontent')) {
    function travelagency_get_pagecontent($page_id = false)
    {
        if ($page_id) {
            $page = get_post($page_id);
            if ($page) {
                $content = '';
                global $kc;
                if (isset($kc)) {
                    if (isset($page->post_content_filtered) && !empty($page->post_content_filtered)) {
                        $content = kc_do_shortcode($page->post_content_filtered);
                    } else {
                        $content = kc_do_shortcode($page->post_content);
                    }
                } else {
                    echo do_shortcode($page->post_content);
                }

                return $content;
            }
        }

        return false;
    }
}