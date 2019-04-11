<?php
/**
 * Created by ShineTheme.
 * User: Sejinichi
 * Version: 1.0
 */

if (class_exists('STPluginsImporter') and !class_exists('TravelAgency_Importer')) {
    class TravelAgency_Importer extends STPluginsImporter
    {
        static function init()
        {
            add_action('admin_init', array(__CLASS__, '_do_import'));
            add_action('admin_enqueue_scripts', array(__CLASS__, '_add_import_js'));

        }

        static function _do_import()
        {
            if (isset($_REQUEST['stp_do_import']) and $_REQUEST['stp_do_import']) {

                //Check Permission
                if (!current_user_can('manage_options')) {
                    echo json_encode(array('status' => 0, 'message' => esc_html__('You do not have permission to do this', 'travelagency')));
                    die;
                }

                self::load_lib();

                //Check Importer Plugins was installed
                if (!class_exists('WP_Importer') or !class_exists('WP_Import')) {
                    echo json_encode(array('status' => 0, 'message' => esc_html__('Importer Class Was Not Installed', 'travelagency')));
                    die;
                }
                $st_import_config = array(
                    'homepage_default' => '',
                    'blogpage_default' => '',
                    'menu_locations' => array()
                );

                //Load import config
                $import_config = self::$_import_path . '/config.php';
                if (file_exists($import_config)) {
                    include_once $import_config;
                }

                extract($st_import_config);


                $step = isset($_REQUEST['step']) ? $_REQUEST['step'] : 1;
                $index = isset($_REQUEST['index']) ? $_REQUEST['index'] : 1;

                $data_dir = self::$_import_path . '/' . self::$_package;
                $data_url = self::$_import_url . '/' . self::$_package;

                $package = self::$_package;

                if ($step == 1) {
                    if (!class_exists('WPBooking_Admin_Setup')) {
                        echo json_encode(array(
                                'status' => 0,
                                'messenger' => wp_kses(__("<span class='red'>Plugin WPBooking not found. Stop working!</span>", 'travelagency'), array('span' => array('class' => array()))),
                                'next_url' => ''
                            )
                        );
                        die();
                    }
                    // Setup Plugin
                    global $wp_rewrite;
                    $wp_rewrite->set_permalink_structure('/%postname%/');

                    $user_data = [
                        'ID' => get_current_user_id(),
                        'first_name' => 'Jont',
                        'last_name' => 'Henrry',
                        'display_name' => 'Jont Henrry',
                    ];
                    $user_id = wp_update_user($user_data);
                    update_user_meta(get_current_user_id(), 'phone', '12345678');
                    update_user_meta(get_current_user_id(), 'address', 'LA, Califolia');
                    update_user_meta(get_current_user_id(), 'description', 'Aenean id ullamcorper libero. Vestibulum imperdiet nibh vel magna lacinia ultrices. Sed id interdum urna. onsectetur adipiscing elit. faucibus risus, a euismod lorem hendrerit ac nisi Lorem ipsum dolor ');
                    update_user_meta(get_current_user_id(), 'facebook', '#');
                    update_user_meta(get_current_user_id(), 'google', '#');
                    update_user_meta(get_current_user_id(), 'twitter', '#');
                    update_user_meta(get_current_user_id(), 'instagram', '#');
                    update_user_meta(get_current_user_id(), 'linkedin', '#');
                    update_user_meta(get_current_user_id(), 'youtube', '#');

                    // SET OPTION
                    update_option('wpbooking_currency', array(
                        'currency' => 'USD',
                        'symbol' => '$',
                        'position' => 'left',
                        'thousand_sep' => ',',
                        'decimal_sep' => '.',
                        'decimal' => '2',
                    ));

                    // SET EMAIL
                    update_option("wpbooking_email_from", esc_html__("travelagency", "travelagency"));
                    update_option("wpbooking_email_from_address", get_option("admin_email"));
                    update_option("wpbooking_system_email", get_option("admin_email"));
                    update_option("wpbooking_on_booking_email_customer", 1);
                    update_option("wpbooking_on_booking_email_admin", 1);
                    update_option("wpbooking_on_registration_email_customer", 1);
                    update_option("wpbooking_on_registration_email_admin", 1);
                    update_option("wpbooking_email_header", WPBooking_Admin_Setup::inst()->_get_template_default("email_header"));
                    update_option("wpbooking_email_footer", WPBooking_Admin_Setup::inst()->_get_template_default("email_footer"));
                    update_option("wpbooking_email_stylesheet", '
                            .color_black{
                                color:black;
                            }
                            .font_italic{
                                font-style:italic;
                            }
                            .completed{
                                border: 1px solid #669966;
                                border-radius: 20px;
                                color: #669966;
                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-left: 10px;
                                padding: 5px 20px;
                            }
                            .failed{
                                border: 1px solid red;
                                color: red;
                                border-radius: 20px;

                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-left: 10px;
                                padding: 5px 20px;
                            }
                            .on_hold{
                                border: 1px solid #6684f2;
                                color: #6684f2;
                                border-radius: 20px;
                                display: inline-block;
                                font-size: 12px;
                                font-weight: bold;
                                margin-left: 10px;
                                padding: 5px 20px;
                            }
                            .col-10{
                                float:left;
                                width:100%;
                            }
                            .col-5{
                                float:left;
                                width:50%;
                            }
                            .col-7{
                                float:left;
                                width:70%;
                            }
                            .col-3{
                                float:left;
                                width:30%;
                            }
                            .col-2{
                                float:left;
                                width:20%;
                            }
                            .float-right{
                                float:right;
                            }
                            .bold{
                                font-weight:bold;
                            }
                            .head-info{
                                margin-bottom:20px;
                            }
                            .head-info-content-hl{
                                 color: #6684f2;
                            }
                            .head-info-total-price{
                                text-align:right;

                            }
                            .head-info-total-price .head-info-title{
                                 color: #666666;
                                display: block;
                                font-size: 15px;
                                text-transform: uppercase;
                                font-weight:bold;
                            }
                            .head-info-total-price .head-info-content{
                                color: #6684f2;
                                display: block;
                                font-size: 24px;
                                font-weight: bold;
                            }
                            .content-row {
                                overflow:hidden;
                            }
                            .content-row img{
                                max-width:150px;
                            }
                            table{
                                width:100%;
                                font-size: 13px;
                            }
                            h3{
                                margin:0px;
                                padding:0px 0px 10px 0px;
                            }
                            h3 a{
                                text-decoration: none;
                                color: #6684f2;
                            }
                            h4{
                                margin:0px;
                                padding:0px 0px 10px 0px;
                            }
                            .room-image img{
                                width:50px;
                            }
                            .text-center{
                                text-align:center;
                            }
                            .service_info td{
                                vertical-align: top;
                            }
                            table {
                                border-collapse: collapse;
                            }

                            table, th, td {
                                border: 1px solid #ccc;
                            }
                            .extra-service{
                                margin-top:10px;
                            }
                            .btn_detail_checkout {
                                color: #6684f2;
                                font-size: 13px;
                                font-style: italic;
                                font-weight: normal;
                                margin-top: 20px;
                            }
                            .customer{
                                margin-top:30px;
                            }
                            .customer label{
                                font-weight: bold;
                            }
                            .btn_history{
                               background: #6684f2 none repeat scroll 0 0;
                                border: 1px solid #6684f2;
                                border-radius: 20px;
                                color: white;
                                display: inline-block;
                                font-size: 12px;
                                font-weight: normal;
                                margin-bottom: 20px;
                                margin-top: 20px;
                                padding: 10px 25px;
                                text-decoration: none;
                                text-transform: uppercase;
                            }
                            .color{
                                color:#6684f2;
                            }
                            .content-total{
                                width: 50%; float: right;
                            }
                            .total-title,.total-amount{
                                display: inline-block; width: 50%;
                                margin-bottom: 20px;
                            }
                            .total-amount{
                                float:right;
                            }
                            .total-title{
                                text-align:left;
                            }









                            .template {
                                background: #F1F1F1;
                                font-family: tahoma;
                                padding: 50px 0px;
                            }

                            .email-template {
                                width: 100%;
                                text-align: center;
                                background: #f2f2f2;
                            }

                            .email-header {
                                padding-top: 25px;
                                padding-bottom: 25px;
                            }

                            .email-header h2 {
                                font-size: 30px;
                            }

                            .email-footer {
                                padding-top: 50px;
                                padding-bottom: 60px;
                                font-size: 15px;
                                font-style: italic;
                                line-height: 25px;
                            }

                            .email-footer a {
                                margin-left: 10px;
                                margin-right: 10px;
                            }

                            .email-footer img {
                                width: 22px;
                                height: 22px;
                            }

                            .content {
                                background: white;
                                width: 600px;
                                margin: 0px auto;
                                border-radius: 4px;
                                padding: 20px;
                                padding: 20px 70px 50px;
                                border-radius: 0;
                                color: #000;
                                font-size: 15px;
                                text-align: left;
                                overflow:auto;
                            }

                            .header {
                                margin: -20px;
                                background: #0073aa;
                                padding: 15px 25px;
                                margin-bottom: 40px;
                            }

                            .header h1 {
                                color: red;
                                text-transform: uppercase;
                                font-size: 30px;
                            }

                            .footer {
                                margin: -20px;
                                background: #ECECEC;
                                padding: 5px 10px;
                                margin-top: 40px;
                                color: #737373;
                            }

                            table, tr {
                                border-top: 1px solid #ccc;
                                border-left: 1px solid #ccc;
                                background: white;
                            }

                            td, th {
                                border-right: 1px solid #ccc;
                                border-bottom: 1px solid #ccc;

                                padding: 8px;
                            }

                            .review-cart-total:before,
                            .review-cart-total:after {
                                display: table;

                            }

                            .review-cart-total:after {
                                clear: both;
                            }

                            .review-cart-total .total-title {
                                clear: both;
                                font-size: 14px;
                                float: left;
                                margin-top: 10px;
                            }

                            .review-cart-total .total-amount {
                                font-size: 14px;
                                color: #666666;
                                float: right;
                                margin-top: 10px;
                            }

                            .review-cart-total .total-amount.big {
                                font-size: 20px;
                                margin-top: 5px;
                            }

                            .review-cart-total .total-line {
                                clear: both;
                                height: 1px;
                                width: 100%;
                                background: #333;
                                margin: 10px 0px;
                                display: block;
                                margin-top: 15px;
                            }

                            .label {
                                display: inline;
                                padding: .2em .6em .3em;
                                font-size: 75%;
                                font-weight: bold;
                                line-height: 1;
                                color: #fff;
                                text-align: center;
                                white-space: nowrap;
                                vertical-align: baseline;
                                border-radius: .25em;
                            }

                            a.label:hover, a.label:focus {
                                color: #fff;
                                text-decoration: none;
                                cursor: pointer;
                            }

                            .label:empty {
                                display: none;
                            }

                            .btn .label {
                                position: relative;
                                top: -1px;
                            }

                            .label-default {
                                background-color: #777;
                            }

                            .label-default[href]:hover, .label-default[href]:focus {
                                background-color: #5e5e5e;
                            }

                            .label-primary {
                                background-color: #337ab7;
                            }

                            .label-primary[href]:hover, .label-primary[href]:focus {
                                background-color: #286090;
                            }

                            .label-success {
                                background-color: #5cb85c;
                            }

                            .label-success[href]:hover, .label-success[href]:focus {
                                background-color: #449d44;
                            }

                            .label-info {
                                background-color: #5bc0de;
                            }

                            .label-info[href]:hover, .label-info[href]:focus {
                                background-color: #31b0d5;
                            }

                            .label-warning {
                                background-color: #6684f2;
                            }

                            .label-warning[href]:hover, .label-warning[href]:focus {
                                background-color: #ec971f;
                            }

                            .label-danger {
                                background-color: #d9534f;
                            }

                            .label-danger[href]:hover, .label-danger[href]:focus {
                                background-color: #c9302c;
                            }

                            /*email comment*/
                            .title {
                                font-size: 28px;
                                margin-bottom: 17px;
                            }

                            .title-approved {
                                color: #6aa84f;
                                font-size: 28px;
                                margin-bottom: 17px;
                            }

                            .title-disapproved {
                                color: #cc4125;
                                font-size: 28px;
                                margin-bottom: 17px;
                            }

                            .content-header {
                                margin-bottom: 40px;
                                text-align: center;
                            }

                            .content-header p {
                                line-height: 25px;
                                font-style: italic;
                            }

                            .content-center {
                                background: #fafafa;
                                padding: 20px 15px;
                                text-align: center;
                                font-style: italic;
                            }

                            .content .content-center a {
                                color: #6684f2;
                            }

                            .content-center .icon {
                                font-size: 45px;
                                line-height: 1;
                            }

                            .content-center .comment {
                                margin-top: 0px;
                                margin-bottom: 22px;
                                font-style: italic;
                            }

                            .content-center .review {
                                font-style: italic;
                            }

                            .review-score {
                                display: table;
                                width: 50%;
                                list-style: none;
                                text-align: left;
                                margin: 0 auto;
                            }

                            .review-score li {
                                display: table-row;
                                line-height: 2;
                            }

                            .review-score li span {
                                display: table-cell;
                            }

                            .review-score li .score {
                                color: #6684f2;
                            }

                            .content-footer {
                                margin: 30px 30px 0;
                                text-align: center;
                            }

                            .content-footer .btn.btn-default {
                                padding: 15px;
                                background: #6684f2;
                                color: #FFF;
                                text-decoration: none;
                                display: inline-block;
                            }

                            .content-footer .comment_link {
                                display: block;
                                margin-top: 15px;
                                font-style: italic;
                            }

                            .content-footer .comment_link a {
                                color: #6684f2;
                            }

                                ');
                    update_option("wpbooking_email_to_customer", WPBooking_Admin_Setup::inst()->_get_template_default("booking_email_customer"));
                    update_option("wpbooking_email_to_admin", WPBooking_Admin_Setup::inst()->_get_template_default("booking_email_admin"));
                    update_option("wpbooking_registration_email_customer", WPBooking_Admin_Setup::inst()->_get_template_default("registration_email_customer"));
                    update_option("wpbooking_registration_email_admin", WPBooking_Admin_Setup::inst()->_get_template_default("registration_email_admin"));

                    $currency_list = 'a:2:{i:0;a:8:{s:5:"title";s:3:"USD";s:8:"currency";s:3:"USD";s:6:"symbol";s:1:"$";s:8:"position";s:4:"left";s:12:"thousand_sep";s:0:"";s:11:"decimal_sep";s:1:".";s:7:"decimal";s:1:"2";s:4:"rate";s:1:"1";}i:1;a:8:{s:5:"title";s:3:"EUR";s:8:"currency";s:3:"EUR";s:6:"symbol";s:3:"&euro;";s:8:"position";s:4:"left";s:12:"thousand_sep";s:0:"";s:11:"decimal_sep";s:1:".";s:7:"decimal";s:1:"2";s:4:"rate";s:4:"0.84";}}';

                    update_option("wpbooking_currency_list", unserialize($currency_list));

                    update_option("wpbooking_google_api_key", 'AIzaSyDklmtkvMX8BkEQkOCllQf6QsCpOwkJABQ');
                    update_option("wpbooking_service_type_accommodation_thumb_size", '120,120,off');
				

                    $gateway = WPBooking_Payment_Gateways::inst();
                    $all = $gateway->get_gateways();
                    if (!empty($all)) {
                        foreach ($all as $key => $value) {
                            if ($key == 'submit_form') {
                                update_option("wpbooking_gateway_" . $key . "_enable", 1);
                                update_option("wpbooking_gateway_" . $key . "_title", esc_html__('Submit Form', 'travelagency'));
                                update_option("wpbooking_gateway_" . $key . "_desc", esc_html__('Submit Form', 'travelagency'));
                            } else {
                                update_option("wpbooking_gateway_" . $key . "_enable", 0);
                            }
                            if ($key == 'paypal') {
                                update_option("wpbooking_gateway_" . $key . "_title", esc_html__('PayPal', 'travelagency'));
                                update_option("wpbooking_gateway_" . $key . "_desc", esc_html__('You will be redirect to paypal website to finish the payment process', 'travelagency'));
                            }
                        }
                    }

                    //Delete Pages Of WPBooking
                    $page_names = [
                        'Wpbooking Archive',
                        'Wpbooking Checkout',
                        'Wpbooking My Account',
                        'Wpbooking Term & Condition',
                    ];
                    foreach ($page_names as $key => $val) {
                        $page_obj = get_page_by_title($val);
                        if (!empty($page_obj->ID)) {
                            wp_delete_post($page_obj->ID, true);
                        }
                    }

                    update_option("wpbooking_setup_demo", "false");

                    echo json_encode(array(
                            'status' => "ok",
                            'messenger' => wp_kses(__("Set options Plugin Wpbooking ... <span>DONE!</span><br>", 'travelagency'), array('span' => array(), 'br' => array())),
                            'next_url' => admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step + 1) . '&index=' . ($index + 1)),
                            'step' => $index
                        )
                    );
                    die();
                }

                if ($step == 2) {
                    //Update theme_options
                    $data_json = $data_url . '/theme_options.json';
                    $data_res = wp_remote_get($data_json);
                    if (!is_wp_error($data_res)) {
                        $data_body = $data_res['body'];
                        $options = unserialize(ot_decode($data_body)); // unserialize

                        if (!empty($options)) {

                            if (!function_exists('ot_options_id')) {
                                echo json_encode(array(
                                        'status' => 0,
                                        'messenger' => wp_kses(__("<span class='red'>Plugin: Option Tree must be installed first. Stop working!</span>", 'travelagency'), array('span' => array('class' => array()))),
                                        'next_url' => ''
                                    )
                                );
                                die;
                            }

                            update_option(ot_options_id(), $options); // and overwrite the current theme-options
                            echo json_encode(array(
                                    'status' => "ok",
                                    'messenger' => wp_kses(__("Importing the demo theme options... <span>DONE!</span><br>", 'travelagency'), array('span' => array(), 'br' => array())),
                                    'next_url' => admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step + 1) . '&index=' . ($index + 1)),
                                    'step' => $index
                                )
                            );
                        } else {
                            echo json_encode(array(
                                    'status' => 0,
                                    'messenger' => wp_kses(__("<span class='red'>File: theme_options.json contain NULL content. Stop working!</span>", 'travelagency'), array('span' => array('class' => array()))),
                                    'next_url' => ''
                                )
                            );
                        }

                    } else {
                        echo json_encode(array(
                                'status' => 0,
                                'messenger' => sprintf(__("<span class='red'>Can not read theme_options.json<br>File:%s<br>. Stop working!</span>", 'travelagency'), $data_json),
                                'next_url' => ''
                            )
                        );
                        die;
                    }

                }

                //Update Widgets
                if ($step == 3) {

                    // Add data to widgets
                    $json_file = $data_url . '/widget.json'; // widgets data file
                    $widgets_json = wp_remote_get($json_file);
                    $widget_data = $widgets_json['body'];
                    $data_object = json_decode($widget_data);

                    $import_widgets = self::wie_import_data($data_object);
                    echo json_encode(array(
                            'status' => 1,
                            'messenger' => wp_kses(__("Importing the demo widgets... <span>DONE!</span>.<br>", 'travelagency'), array('span' => array(), 'br' => array())),

                            'next_url' => admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step + 1) . '&index=' . ($index + 1)),
                            'step' => $index
                        )
                    );
                }

                //Import XML

                if ($step == 4) {

                    $stt_file = isset($_REQUEST['file_number']) ? $_REQUEST['file_number'] : 0;
                    $ds_file = array_filter(glob($data_dir . '/data/*'), 'is_file');

                    $file_name = isset($ds_file[$stt_file]) ? $ds_file[$stt_file] : false;

                    if (!$file_name) {
                        echo json_encode(array(
                                'status' => 0,
                                'messenger' => wp_kses(__("<span class='red'>File Not Found. Stop working!</span>", 'travelagency'), array('span' => array('class' => array()))),
                                'next_url' => ''
                            )
                        );
                        die;
                    }

                    $nexturl = admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step) . '&file_number=' . ($stt_file + 1) . '&index=' . ($index + 1));

                    if ($stt_file >= count($ds_file) - 1) {
                        $nexturl = admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step + 1) . '&index=' . ($index + 1));
                    }

                    ob_start();
                    $importer = new WP_Import();
                    $theme_xml = $file_name;
                    $importer->fetch_attachments = true;
                    $importer->import($theme_xml);
                    @ob_clean();
                    echo json_encode(array(
                            'status' => 1,
                            'messenger' => sprintf(__("Importing data: %s %d of %d ... <span>DONE!</span><br>", 'travelagency'), basename($file_name), $stt_file + 1, count($ds_file)),
                            'next_url' => $nexturl,
                            'file' => $ds_file,
                            'step' => $index
                        )
                    );
                }

                // Set Up Menu Theme Location

                if ($step == 5) {
                    //  Set imported menus to registered theme locations
                    $locations = get_theme_mod('nav_menu_locations'); // registered menu locations in theme
                    $menus = wp_get_nav_menus(); // registered menus
                    if ($menus) {
                        foreach ($menus as $menu) { // assign menus to theme locations
                            if (!empty($menu_locations))
                                foreach ($menu_locations as $key => $st_over_menu) {
                                    if ($menu->name == $key) {
                                        $locations[$st_over_menu] = $menu->term_id;
                                    }
                                }
                        }
                    }
                    set_theme_mod('nav_menu_locations', $locations); // set menus to locations

                    $nexturl = admin_url(self::$_import_page . "&stp_do_import=1&package={$package}&step=" . ($step + 1) . '&index=' . ($index + 1));
                    do_action('travelagency_after_step_setup_menu');
                    echo json_encode(array(
                            'status' => 1,
                            'messenger' => wp_kses(__("Importing menu settings ... <span>DONE!</span><br>", 'travelagency'), array('span' => array(), 'br' => array())),
                            'next_url' => $nexturl,
                            'step' => $index
                        )
                    );

                }
                // Set reading options
                if ($step == 6) {
                    // UPDATE ATTR TAXONOMY
                    $attr = array(
                        'germany'=>'a:4:{s:23:"min_price_accommodation";s:1:"0";s:14:"min_price_tour";s:3:"199";s:14:"featured_image";s:3:"153";s:13:"icon_location";b:0;}',
                        'italy'=>'a:4:{s:23:"min_price_accommodation";s:1:"0";s:14:"min_price_tour";s:3:"199";s:14:"featured_image";s:3:"156";s:13:"icon_location";b:0;}',
                        'paris'=>'a:4:{s:23:"min_price_accommodation";s:1:"0";s:14:"min_price_tour";s:3:"199";s:14:"featured_image";s:3:"156";s:13:"icon_location";b:0;}',
                        'russia'=>'a:4:{s:23:"min_price_accommodation";s:1:"0";s:14:"min_price_tour";s:3:"199";s:14:"featured_image";s:3:"155";s:13:"icon_location";b:0;}',
                        'united-states'=>'a:4:{s:23:"min_price_accommodation";s:1:"0";s:14:"min_price_tour";s:3:"199";s:14:"featured_image";s:3:"155";s:13:"icon_location";b:0;}',
					);
                    foreach ($attr as $k => $v) {
                        $term = get_term_by('slug', $k, 'wpbooking_location');
                        if (!empty($term->term_id)) {
                            if (!empty($v)) {
                                $data = unserialize($v);
                                foreach ($data as $key => $value) {
                                    update_tax_meta($term->term_id, $key, $value);
                                }
                            }
                        }
                    }

                    // Set reading options
                    if (!empty($homepage_default)) {
                        $homepage = get_page_by_title($homepage_default);
                        if ($homepage->ID) {
                            update_option('show_on_front', 'page');
                            update_option('page_on_front', $homepage->ID); // Front Page
                        }
                    }
                    if (!empty($blogpage_default)) {
                        $homepage = get_page_by_title($blogpage_default);
                        if ($homepage->ID) {
                            update_option('show_on_front', 'page');
                            update_option('page_for_posts', $homepage->ID); // Blog Page
                        }
                    }
                    self::update_contact_form_default();
                    do_action('travelagency_after_setup_reading_page');

                    //SET PAGE
                    $page_arr = [
                        'wpbooking_archive-page' => 'Wpbooking Archive',
                        'wpbooking_term-page' => 'Wpbooking Term & Condition',
                        'wpbooking_myaccount-page' => 'Wpbooking My Account',
                        'wpbooking_checkout_page' => 'Wpbooking Checkout',
                    ];
                    foreach ($page_arr as $key => $val) {
                        $page_obj = get_page_by_title($val);
                        if (!empty($page_obj->ID)) {
                            update_option($key, $page_obj->ID);
                        }
                    }

                    echo json_encode(array(
                            'status' => "ok",
                            'messenger' => wp_kses(__("Setting reading options... <span>DONE!</span><br/><span>All Done! Have Fun</span>", 'travelagency'), array('span' => array(), 'br' => array())),
                            'next_url' => '',
                            'step' => $index
                        )
                    );
                }
                die;
            }

        }

        static function update_contact_form_default()
        {
            $contactform = get_page_by_title('Travel Agency', OBJECT, 'wpcf7_contact_form');
            if (!empty($contactform->ID)) {
                update_option('wpcf7', $contactform->ID);
            }
        }
    }

    TravelAgency_Importer::init();
}
