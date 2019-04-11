<?php
if(!class_exists('agency_page_controller')){
    class agency_page_controller{
        static $_inst;
        function __construct() {
            add_action('init',[$this,'_add_meta_box']);
        }

        function _add_meta_box(){
            $page_meta_box = array(
                'id'        => 'page_option',
                'title'     => esc_html__(  'Page Options' , 'travelagency' ),
                'desc'      => '',
                'pages'     => array( 'page' ),
                'context'   => 'normal',
                'priority'  => 'high',
                'fields'    => array(
                    array(
                        'id'                => 'tab_header',
                        'label'             => esc_html__('Header Settings', 'travelagency'),
                        'type'              => 'tab',
                    ),
                    array(
                        'id' => 'logo',
                        'label' => esc_html__('Logo', 'travelagency'),
                        'desc' => esc_html__('This allow you to change logo', 'travelagency'),
                        'type' => 'upload',
                    ),
                    array(
                        'type' => 'select',
                        'id' => 'topbar_on_off',
                        'label' => esc_html__('Enable Top Bar', 'travelagency'),
                        'desc' => esc_html__('Enable show top bar', 'travelagency'),
                        'choices' => array(
                            array(
                                'label' => esc_html__('---Default---', 'travelagency'),
                                'value' => ''
                            ),
                            array(
                                'label'=>esc_html__('Yes', 'travelagency'),
                                'value'=>'on'
                            ),
                            array(
                                'label'=>esc_html__('No', 'travelagency'),
                                'value'=>'off'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'id' => 'st_menu_fixed',
                        'label' => esc_html__('Menu Fixed', 'travelagency'),
                        'desc' => esc_html__('Menu change to fixed when scroll', 'travelagency'),
                        'choices' => array(
                            array(
                                'label' => esc_html__('---Default---', 'travelagency'),
                                'value' => ''
                            ),
                            array(
                                'label'=>esc_html__('Yes', 'travelagency'),
                                'value'=>'on'
                            ),
                            array(
                                'label'=>esc_html__('No', 'travelagency'),
                                'value'=>'off'
                            ),
                        ),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'id' => 'bg_topbar',
                        'label' => esc_html__('Background Top Bar','travelagency'),
                    ),
                    array(
                        'type' => 'colorpicker',
                        'id' => 'st_bg_menu',
                        'label' => esc_html__('Background for menu','travelagency'),
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

                )
            );
            if ( function_exists( 'ot_register_meta_box' ) ) {
                ot_register_meta_box( $page_meta_box );
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
    agency_page_controller::inst();
}