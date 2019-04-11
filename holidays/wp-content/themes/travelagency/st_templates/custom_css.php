<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 3:08 PM
 */
$main_color = travelagency_get_option('main_color');
$style = '';
if(!empty($main_color)) {
    $style .= '.hero-section-caption{ background:'.$main_color.' }
        #navigation > ul > li:hover > a, a:hover,.btn-link:hover{ color:'.$main_color.' }
        .feature-icon{
            border: 2px '.$main_color.' dashed;
            color:'.$main_color.'
        }
        .imghover:hover::before{
             background:'.$main_color.';
             opacity:0.70;
        }
        .location{
            color:'.$main_color.'
        }
        footer>section{
            background:'.$main_color.' !important;
        }
        .wpbooking-loop-order .wb-btn-grid.active, .wpbooking-loop-order .wb-btn-list.active{
                background: '.$main_color.' !important;
                border-color: '.$main_color.' !important;
        }
        .wpbooking-loop-items .wpbooking_service .content-item:hover .service-title a strong{
            color:'.$main_color.';
        }
        .wpbooking-loop-items .wpbooking_service .content-item .wb-hotel-star .wpbooking-icon-star{
             color:'.$main_color.';
        }
        #navigation ul ul li:hover > a, #navigation ul ul li a:hover{
            color:'.$main_color.';
        }
        .contact-icon{
            border: 2px '.$main_color.' dashed;
            color: '.$main_color.';
        }
    
    ';
}
//if(!empty($style)) print $style;