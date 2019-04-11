<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package agency
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php $favicon = travelagency_get_option('st_favicon'); if(!empty($favicon)){ ?>
        <link rel="shortcut icon" type="image/png" href="<?php echo esc_url($favicon); ?>"/>
        <link rel="shortcut icon" type="image/png" href="<?php echo esc_url($favicon); ?>"/>
    <?php } ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="site_wrapper">
    <header id="header" class="site-header" role="banner">
        <?php
        $topbar_contact = travelagency_get_option('topbar_contact');
        $topbar_social = travelagency_get_option('topbar_social');
        $bg_topbar = travelagency_get_option_with_meta('bg_topbar', '');
        if (!empty($bg_topbar)) {
            $bg_topbar = TravelAgency_Assets()->build_css('background-color:' . $bg_topbar . '');
        }
        $top_bar = travelagency_get_option_with_meta('topbar_on_off', 'on');
        if ($top_bar == 'on' && class_exists('ShinethemeCore')) {
            if (!empty($topbar_contact) || !empty($topbar_social)) {
                ?>
                <div class="top-bar <?php echo esc_attr($bg_topbar); ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-sm-8">
                                <?php if (!empty($topbar_contact)) { ?>
                                    <div class="call-info">
                                        <p class="call-text">
                                            <?php echo do_shortcode($topbar_contact); ?>
                                        </p>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-8 col-sm-4">
                                <div class="topbar-right-content">
                                    <?php if (!empty($topbar_social)) { ?>
                                        <div class="social">
                                            <ul>
                                                <?php
                                                foreach ($topbar_social as $v) { ?>
                                                    <li>
                                                        <a href="<?php echo esc_url($v['top_bar_social_link']); ?>"><?php
                                                            ?><i
                                                                class="fa <?php echo esc_html($v['top_bar_social_icon']) ?>"></i></a>
                                                    </li>
                                                <?php }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                    <?php

                                    if ( function_exists( 'icl_get_languages' ) ) {
                                        $langs = icl_get_languages( 'skip_missing=0' );
                                        ?>
                                        <?php
                                            if(class_exists('WB_Multi_Currency')){
                                                echo do_shortcode('[wb_change_curency]');
                                            }
                                            ?>
                                        <div class="language-switch">
                                            <div class="dropdown">
                                                <?php foreach ( $langs as $key => $value ) {  if ( $value[ 'active' ] == 1 ) {  ?>
                                                    <a class="dropdown-toggle" data-toggle="dropdown" title="<?php echo esc_html($value['native_name']) ?>">
                                                        <div class="current_language">
                                                            <img src="<?php echo esc_url($value['country_flag_url']) ?>" alt ="<?php echo esc_html($value['native_name']) ?>" />
                                                        </div>
                                                    </a>
                                                <?php } } ?>
                                                <?php if ( count( $langs ) >= 2 ):
                                                    ?>
                                                    <ul class="dropdown-menu">
                                                        <?php foreach ( $langs as $key => $value ) { ?>
                                                            <li>
                                                                <a title="<?php echo esc_html($value['native_name']) ?>" href="<?php echo esc_url( $value[ 'url' ] ) ?>" alt="<?php echo esc_html($value['native_name']) ?>">
                                                                    <img src="<?php echo esc_url($value['country_flag_url']) ?>" alt ="<?php echo esc_html($value['native_name']) ?>" />
                                                                    <?php echo esc_html($value['language_code']) ?>
                                                                </a>
                                                            </li>
                                                        <?php  } ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        } ?>
        <?php
        $logo = travelagency_get_option_with_meta('logo', '');
        $menu_fix = travelagency_get_option_with_meta('st_menu_fixed', 'on');
        $sticky_menu = '';
        if ($menu_fix == 'on') {
            $sticky_menu = 'header-fixed';
        }
        $bg_menu = travelagency_get_option_with_meta('st_bg_menu', '');
        if (!empty($bg_menu)) {
            $bg_menu = TravelAgency_Assets()->build_css('background-color:' . $bg_menu . '');
        }
        $menu_color = travelagency_get_option_with_meta('st_menu_color', '');
        ?>

        <div class="header-wrapper <?php echo esc_attr($sticky_menu); ?>">
            <div class="header <?php echo esc_attr($bg_menu) ?>">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-2 col-sm-12 col-xs-12">
                            <a href="<?php echo home_url('/'); ?>" class="">
                                <?php if (!empty($logo)) { ?> <img src="<?php echo esc_url($logo); ?>"
                                                                   alt="<?php echo esc_html__('Logo', 'travelagency') ?>"> <?php } ?>
                            </a>
                        </div>
                        <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                            <div class="navigation">
                                <div id="navigation">
                                    <?php

                                    if (has_nav_menu('primary')) {
                                        wp_nav_menu(array(
                                            'container' => '',
                                            'container_class' => 'main_menu',
                                            'menu' => 'primary',
                                            'theme_location' => 'primary',
                                            'depth' => 10,
                                            'menu_class' => 'st-nav st-navbar-nav navbar-nav',
                                            'menu_id' => 'main_menu',
                                            'walker' => new Agency_Custom_Navwalker(),
                                        ));
                                    } else {
                                        echo '<ul class="st-nav st-navbar-nav nav navbar-nav navbar-center"><li>';
                                        echo '<a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Make your menu at Appearance => Menus ', 'travelagency') . '</a>';
                                        echo "</li></ul>";
                                    }

                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </header><!-- #masthead -->
