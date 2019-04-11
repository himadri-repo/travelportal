<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 2/28/15
 * Time: 7:01 PM
 */

/**
 *
 * List all theme options fields
 *
 * @see     STOptiontreeConfig::_add_themeoptions();
 *
 *
 * */
$config['theme_options'] = array(
    'sections' => array(
        array(
            'id' => 'option_general',
            'title' => '<i class="fa fa-cog"></i>' . esc_html__('General Settings', 'travelagency')
        ),
        array(
            'id' => 'option_logo',
            'title' => '<i class="fa fa-image"></i>' . esc_html__('Logo Settings', 'travelagency')
        ),
        array(
            'id' => 'option_header',
            'title' => '<i class="fa fa-header"></i>' . esc_html__('Heading Settings', 'travelagency')
        ),
        array(
            'id' => 'option_layout',
            'title' => '<i class="fa fa-columns"></i>' . esc_html__('Layout Settings', 'travelagency')
        ),
        array(
            'id' => 'option_typography',
            'title' => '<i class="fa fa-font"></i>' . esc_html__('Typography', 'travelagency')
        )
    ),
    'settings' => array(
        /*----------------Begin General --------------------*/
        array(
            'id' => 'st_footer_page',
            'label' => esc_html__('Footer Page', 'travelagency'),
            'desc' => esc_html__('Include page to Footer', 'travelagency'),
            'type' => 'page-select',
            'section' => 'option_general'
        ),
        array(
            'id' => 'main_color',
            'label' => esc_html__('Main color', 'travelagency'),
            'type' => 'colorpicker',
            'section' => 'option_general',
        ),
        array(
            'id' => 'google_api_key',
            'label' => esc_html__('Google Map API Key', 'travelagency'),
            'desc' => esc_html__('Get google api key in ', 'travelagency') . '<a target="_blank" href="' . esc_url('https://developers.google.com/maps/documentation/javascript/get-api-key') . '">' . esc_html__("here", 'travelagency') . '</a>',
            'type' => 'text',
            'section' => 'option_general',
        ),
        array(
            'id' => 'st_favicon',
            'label' => esc_html__('Favicon', 'travelagency'),
            'desc' => esc_html__('Icon size should be: 44*44', 'travelagency'),
            'type' => 'upload',
            'section' => 'option_general'
        ),

        /*----------------End General ----------------------*/

        /*----------------Begin Logo --------------------*/
        array(
            'id' => 'logo',
            'label' => esc_html__('Logo', 'travelagency'),
            'desc' => esc_html__('This allow you to change logo', 'travelagency'),
            'type' => 'upload',
            'section' => 'option_logo',
        ),
        array(
            'id' => 'logo_retina',
            'label' => esc_html__('Logo Retina', 'travelagency'),
            'desc' => wp_kses(__('Note: You MUST re-name Logo Retina to logo-name@2x.ext-name. Example:<br>
                                    Logo is: <em>my-logo.jpg</em><br>Logo Retina must be: <em>my-logo@2x.jpg</em>  ', 'travelagency'), array('em' => array(), 'br' => array())),
            'type' => 'upload',
            'section' => 'option_logo',
        ),
        /*----------------End Logo ----------------------*/

        /*----------------Begin Topbar --------------------*/
        array(
            'id' => 'st_topbar',
            'label' => esc_html__('Top bar','travelagency'),
            'type' => 'tab',
            'section' => 'option_header'
        ),
        array(
            'id' => 'topbar_on_off',
            'label' => esc_html__('On or Off Top Bar','travelagency'),
            'section' => 'option_header',
            'type' => 'on-off',
            'std' => 'on',
        ),
        array(
            'id' => 'topbar_contact',
            'label' => esc_html__('Contact infomation','travelagency'),
            'type' => 'text',
            'desc' => esc_html__('Enter Your Contact','travelagency'),
            'section' => 'option_header',
            'condition' => 'topbar_on_off:not(off)',
        ),
        array(
            'id' => 'topbar_social',
            'label' => esc_html__('Socials','travelagency'),
            'type' => 'list-item',
            'desc' => esc_html__('Add Socials','travelagency'),
            'section' => 'option_header',
            'condition' => 'topbar_on_off:not(off)',
            'settings' => array(
                array(
                    'id' => 'top_bar_social_icon',
                    'type' => 'text',
                    'label' => esc_html__('Icon', 'travelagency'),
                    'class' => 'st_icon_awesome'
                ),
                array(
                    'id' => 'top_bar_social_link',
                    'type' => 'text',
                    'label' => esc_html__('Link','travelagency'),
                )
            )
        ),
        array(
            'id' => 'bg_topbar',
            'label' => esc_html__('Background','travelagency'),
            'type' => 'colorpicker',
            'section' => 'option_header',
            'condition' => 'topbar_on_off:not(off)',
        ),
        /*----------------End Topbar --------------------*/
        /*----------------Begin Menu --------------------*/
        array(
            'id' => 'st_menu_header',
            'type' => 'tab',
            'label' => esc_html__('Menu','travelagency'),
            'section' => 'option_header',
        ),
        array(
            'id' => 'st_menu_fixed',
            'label' => esc_html__('Menu Fixed', 'travelagency'),
            'desc' => esc_html__('Menu change to fixed when scroll','travelagency'),
            'type' => 'on-off',
            'section' => 'option_header',
        ),
        array(
            'id' => 'st_bg_menu',
            'label' => esc_html__('Background for menu','travelagency'),
            'type' => 'colorpicker',
            'section' => 'option_header',
        ),
        array(
            'id' => 'st_menu_color',
            'label' => esc_html__('Menu style', 'travelagency'),
            'type' => 'typography',
            'section' => 'option_header',
        ),
        array(
            'id' => 'st_menu_color_hover',
            'label' => esc_html__('Hover color', 'travelagency'),
            'desc' => esc_html__('Choose color', 'travelagency'),
            'type' => 'colorpicker',
            'section' => 'option_header',
        ),
        array(
            'id' => 'st_menu_color_active',
            'label' => esc_html__('Active color', 'travelagency'),
            'desc' => esc_html__('Choose color', 'travelagency'),
            'type' => 'colorpicker',
            'section' => 'option_header',
        ),
        /*----------------End Menu ----------------------*/

        /*----------------Begin Layout --------------------*/
        array(
            'id' => 'st_layout_general',
            'type' => 'tab',
            'label' => esc_html__('General','travelagency'),
            'section' => 'option_layout',
        ),
        array(
            'id' => 'st_sidebar_position_blog',
            'label' => esc_html__('Sidebar Blog', 'travelagency'),
            'type' => 'select',
            'section' => 'option_layout',
            'choices' => array(
                array(
                    'value' => 'no',
                    'label' => esc_html__('No Sidebar', 'travelagency'),
                ),
                array(
                    'value' => 'left',
                    'label' => esc_html__('Left', 'travelagency'),
                ),
                array(
                    'value' => 'right',
                    'label' => esc_html__('Right', 'travelagency'),
                )
            )
        ),
        array(
            'id' => 'st_sidebar_blog',
            'label' => esc_html__('Sidebar select display in blog', 'travelagency'),
            'type' => 'sidebar-select',
            'section' => 'option_layout',
            'condition' => 'st_sidebar_position_blog:not(no)',
        ),

        /****end blog****/
        array(
            'id' => 'st_sidebar_position_page',
            'label' => esc_html__('Sidebar Page', 'travelagency'),
            'type' => 'select',
            'section' => 'option_layout',
            'choices' => array(
                array(
                    'value' => 'no',
                    'label' => esc_html__('No Sidebar', 'travelagency'),
                ),
                array(
                    'value' => 'left',
                    'label' => esc_html__('Left', 'travelagency'),
                ),
                array(
                    'value' => 'right',
                    'label' => esc_html__('Right', 'travelagency'),
                )
            )
        ),
        array(
            'id' => 'st_sidebar_page',
            'label' => esc_html__('Sidebar select display in page', 'travelagency'),
            'type' => 'sidebar-select',
            'section' => 'option_layout',
            'condition' => 'st_sidebar_position_page:not(no)',
        ),
        /****end page****/
        array(
            'id' => 'st_sidebar_position_post',
            'label' => esc_html__('Sidebar Single Post', 'travelagency'),
            'type' => 'select',
            'section' => 'option_layout',
            'choices' => array(
                array(
                    'value' => 'no',
                    'label' => esc_html__('No Sidebar', 'travelagency'),
                ),
                array(
                    'value' => 'left',
                    'label' => esc_html__('Left', 'travelagency'),
                ),
                array(
                    'value' => 'right',
                    'label' => esc_html__('Right', 'travelagency'),
                )
            )
        ),
        array(
            'id' => 'st_sidebar_post',
            'label' => esc_html__('Sidebar select display in single post', 'travelagency'),
            'type' => 'sidebar-select',
            'section' => 'option_layout',
            'condition' => 'st_sidebar_position_post:not(no)',
        ),
        array(
            'id' => 'st_add_sidebar',
            'label' => esc_html__('Add SideBar', 'travelagency'),
            'type' => 'list-item',
            'section' => 'option_layout',
            'settings' => array(
                array(
                    'id' => 'widget_title_heading',
                    'label' => esc_html__('Choose heading title widget', 'travelagency'),
                    'type' => 'select',
                    'std' => 'h3',
                    'choices' => array(
                        array(
                            'value' => 'h1',
                            'label' => esc_html__('H1', 'travelagency'),
                        ),
                        array(
                            'value' => 'h2',
                            'label' => esc_html__('H2', 'travelagency'),
                        ),
                        array(
                            'value' => 'h3',
                            'label' => esc_html__('H3', 'travelagency'),
                        ),
                        array(
                            'value' => 'h4',
                            'label' => esc_html__('H4', 'travelagency'),
                        ),
                        array(
                            'value' => 'h5',
                            'label' => esc_html__('H5', 'travelagency'),
                        ),
                        array(
                            'value' => 'h6',
                            'label' => esc_html__('H6', 'travelagency'),
                        ),
                    )
                ),
            ),
        ),

        // single page, blog
        array(
            'id' => 'st_layout_single',
            'type' => 'tab',
            'label' => esc_html__('Single','travelagency'),
            'section' => 'option_layout',
        ),
        array(
            'id' => 'st_on_off_banner',
            'type' => 'on-off',
            'label' => esc_html__('Show or Hide Banner','travelagency'),
            'section' => 'option_layout',
            'std' => 'on',
        ),
        array(
            'id' => 'st_banner_single',
            'label' => esc_html__('Select Image for Banner', 'travelagency'),
            'type' => 'upload',
            'section' => 'option_layout',
            'condition' => 'st_on_off_banner:is(on)',
        ),
        array(
            'id' => 'st_on_off_bread',
            'type' => 'on-off',
            'label' => esc_html__('Breadcrumb','travelagency'),
            'desc' => esc_html__('Turn on or off Breadcrumb','travelagency'),
            'section' => 'option_layout',
            'std' => 'on',
        ),
        /*----------------End Layout ----------------------*/

        /*----------------Begin Typography ----------------------*/
        array(
            'id' => 'st_custom_typography',
            'label' => esc_html__('Add Settings', 'travelagency'),
            'type' => 'list-item',
            'section' => 'option_typography',
            'settings' => array(
                array(
                    'id' => 'typo_area',
                    'label' => esc_html__('Choose Area to style', 'travelagency'),
                    'type' => 'select',
                    'std' => 'main',
                    'choices' => array(
                        array(
                            'value' => 'header',
                            'label' => esc_html__('Header', 'travelagency'),
                        ),
                        array(
                            'value' => 'main',
                            'label' => esc_html__('Main Content', 'travelagency'),
                        ),
                        array(
                            'value' => 'widget',
                            'label' => esc_html__('Widget', 'travelagency'),
                        ),
                        array(
                            'value' => 'footer',
                            'label' => esc_html__('Footer', 'travelagency'),
                        ),
                    )
                ),
                array(
                    'id' => 'typo_heading',
                    'label' => esc_html__('Choose heading Area', 'travelagency'),
                    'type' => 'select',
                    'std' => 'h3',
                    'choices' => array(
                        array(
                            'value' => 'h1',
                            'label' => esc_html__('H1', 'travelagency'),
                        ),
                        array(
                            'value' => 'h2',
                            'label' => esc_html__('H2', 'travelagency'),
                        ),
                        array(
                            'value' => 'h3',
                            'label' => esc_html__('H3', 'travelagency'),
                        ),
                        array(
                            'value' => 'h4',
                            'label' => esc_html__('H4', 'travelagency'),
                        ),
                        array(
                            'value' => 'h5',
                            'label' => esc_html__('H5', 'travelagency'),
                        ),
                        array(
                            'value' => 'h6',
                            'label' => esc_html__('H6', 'travelagency'),
                        ),
                        array(
                            'value' => 'a',
                            'label' => esc_html__('a', 'travelagency'),
                        ),
                        array(
                            'value' => 'p',
                            'label' => esc_html__('p', 'travelagency'),
                        ),
                    )
                ),
                array(
                    'id' => 'typography_style',
                    'label' => esc_html__('Add Style', 'travelagency'),
                    'type' => 'typography',
                    'section' => 'option_typography',
                ),
            ),
        ),
        array(
            'id' => 'google_fonts',
            'label' => esc_html__('Add Google Fonts', 'travelagency'),
            'type' => 'google-fonts',
            'section' => 'option_typography',
        )
        /*----------------End Typography ----------------------*/
    )
);

