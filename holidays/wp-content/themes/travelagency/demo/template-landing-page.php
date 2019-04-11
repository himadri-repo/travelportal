<?php
/**
 * Template Name: Landing Page
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <?php if(defined('ST_TRAVELER_VERSION')){?>  <meta name="traveler" content="<?php echo esc_attr(ST_TRAVELER_VERSION) ?>"/>  <?php };?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php if(!function_exists('_wp_render_title_tag')):?>
        <title><?php wp_title('|',true,'right') ?></title>
    <?php endif;?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
$preload = travelagency_get_option('gen_enable_preload','on');
$st_preload_type = travelagency_get_option('st_preload_type','dark');
$st_preload_desc = travelagency_get_option('st_preload_desc','');
if($st_preload_type == 'light'){
    $logo = travelagency_get_option('logo_dark','');
}else{
    $logo = travelagency_get_option('logo_light','');
}
if($preload == 'on') {
    ?>
	
    <div class="helios-loader <?php echo esc_attr($st_preload_type)?>">
        <div class="text-center">
            <span class="icon"><i class="fa fa-map-signs"></i></span>
                <span class="logo">
                    <?php
                    if(!empty($logo)){
                        ?>
                        <img src="<?php echo esc_url($logo) ?>" alt="<?php esc_html_e("logo",'travelagency') ?>"  />
                    <?php }else{ ?>
                        <p><?php echo get_bloginfo('name'); ?></p>
                    <?php }
                    ?>
                </span>
            <p class="desc"><?php echo esc_attr($st_preload_desc); ?></p>
        </div>
    </div>
    <?php
}
?>
    <div class="landing-wrapper">
        <header class="ld-site-header">
            <div class="ld-logo">
                <a href="<?php echo esc_url(home_url('/'))?>">
                    <?php
                    $logo = travelagency_get_option('logo_light','');
                    if(!empty($logo)){
                    ?>
                    <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>" class="logo-image">
                    <?php }else{
                        bloginfo('name');
                    } ?>
                </a>
            </div>
            <nav class="ld-nav">
                <div class="icon-mobile"><i class="fa fa-bars"></i></div>
                <ul class="ld-menu">
                    <li class="menu-item">
                        <a href="#demos">DEMOS</a>
                    </li>
                    <li class="menu-item">
                        <a href="#features">FEATURES</a>
                    </li>
                </ul>
            </nav>
            <div class="ld-buy-now">
                <a href="#" target="_blank" class="buy-now">Buy Now | $89</a>
            </div>
        </header>
        <div class="ld-banner">
            <div class="container">
                <div class="ld-banner-content">
                    <h2 class="banner-title">#1 BOOKING WORDPRESS THEME</h2>
                    <p class="desc">Traveler based on deep research on the most popular travel booking websites such as booking.com, tripadvisor, yahoo travel, expedia, priceline, hotels.com, travelocity, kayak, orbitz, etc. This guys can't be wrong. You should definitely give it a shot <img draggable="false" class="emoji" alt="d" src="https://s.w.org/images/core/emoji/2.2.1/svg/1f642.svg"></p>
                    <a class="ld-explore" href="#demos">Discover All Demos</a>
                </div>
            </div>
        </div>
        <div class="ld-main-content">
            <section class="ld-demos-home" id="demos">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-sm-10 col-xs-12 col-md-offset-2 col-sm-offset-1 text-center lb-demos-title-desc">
                            <h2 class="demos-title">Pick From <span class="ld-highlight">Our Demos</span></h2>
                            <p class="desc">Profuse demos mean just as many possibilities. Offering over 10 options, this versatile and fabulous theme is designed to fit comfortably to your need.</p>
                        </div>
                    </div>
                </div>
                <div class="ld-demos">

<!--                    Add New/Edit Home Demo-->

                    <?php
                    $arr = array(
                        array(
                            'title' => 'Home 1',
                            'img' => get_template_directory_uri().'/demo/img/home/home-1.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-1'
                        ),
                        array(
                            'title' => 'Home 2',
                            'img' => get_template_directory_uri().'/demo/img/home/home-2.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-2'
                        ),
                        array(
                            'title' => 'Home 3',
                            'img' => get_template_directory_uri().'/demo/img/home/home-3.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-3'
                        ),
                        array(
                            'title' => 'Home 4',
                            'img' => get_template_directory_uri().'/demo/img/home/home-4.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-4'
                        ),
                        array(
                            'title' => 'Home 5',
                            'img' => get_template_directory_uri().'/demo/img/home/home-5.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-5'
                        ),
                        array(
                            'title' => 'Home 6',
                            'img' => get_template_directory_uri().'/demo/img/home/home-6.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-61'
                        ),
                        array(
                            'title' => 'Home 7',
                            'img' => get_template_directory_uri().'/demo/img/home/home-7.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-7'
                        ),
                        array(
                            'title' => 'Home 8',
                            'img' => get_template_directory_uri().'/demo/img/home/home-8.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-8'
                        ),
                        array(
                            'title' => 'Home 9',
                            'img' => get_template_directory_uri().'/demo/img/home/home-9.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-9'
                        ),
                        array(
                            'title' => 'Home 10',
                            'img' => get_template_directory_uri().'/demo/img/home/home-10.jpg',
                            'link' => 'http://shinetheme.com/demosd/helios/home-10'
                        ),
                    )
                    ?>
                    <div class="ld-container">
                        <div class="ld-row">
                            <?php
                            foreach($arr as $key => $val){
                            ?>
                            <div class="item">
                                <div class="thumb">
                                    <?php
                                    if(!empty($val['new'])){
                                        echo '<div class="ld_featured">New</div>';
                                    }
                                    ?>
                                    <a class="link-home" href="<?php echo esc_url($val['link'])?>" target="_blank">
                                        <img src="<?php echo esc_url($val['img'])?>" alt="hotel full map">
                                    </a>
                                </div>
                                <h3 class="title"><a href="<?php echo esc_url($val['link'])?>" target="_blank"><?php echo esc_attr($val['title'])?></a></h3>
                            </div>
                                <?php
                                } ?>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ld-featured" id="features">
                <div class="container ft-container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="ld-featured-title">Main <span class="ld-highlight">Features</span></h2>
                        </div>
                        <div class="col-md-12">
                            <div class="features-list">

                                <!--                    Add New/Edit Feature-->

                                <?php

                                $features = array(
                                    array(
                                        'icon' => 'fa-paypal',
                                        'title' => 'Paypal Integrated',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-language',
                                        'title' => 'WPML Supporter',
                                        'desc' => 'Your online business can get even more convenient and productive with plugin designed especially for online shopping experience.',
                                    ),
                                    array(
                                        'icon' => 'fa-arrows',
                                        'title' => 'Fully Responsive',
                                        'desc' => 'Traveler displaying itself at its best on every screensizes or platforms.',
                                    ),
                                    array(
                                        'icon' => 'fa-pagelines',
                                        'title' => 'Visual Composer',
                                        'desc' => 'Visual Composer with extra advanced functionalities and organised clean skin.',
                                    ),
                                    array(
                                        'icon' => 'fa-hand-pointer-o',
                                        'title' => 'One Click Install',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-search',
                                        'title' => 'Smart Search',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-desktop',
                                        'title' => 'Home Page Demos',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-flag',
                                        'title' => 'Font Awesome Icons',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-user',
                                        'title' => 'Dashboard User Page',
                                        'desc' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s',
                                    ),
                                    array(
                                        'icon' => 'fa-envelope',
                                        'title' => 'Mailchimps',
                                        'desc' => 'Online email marketing solution to manage subscribers, send emails, and track results. Offers integrations with other programs.',
                                    ),
                                    array(
                                        'icon' => 'fa-envelope-o',
                                        'title' => 'Contact Form 7',
                                        'desc' => 'Just another contact form plugin. Simple but flexible.',
                                    ),
                                    array(
                                        'icon' => 'fa-cogs',
                                        'title' => 'Traveler Core System',
                                        'desc' => 'With so much of our experience we make Traveler Core System to our each theme for help client more easy to use our product and optimize for SEO.',
                                    ),
                                );
                                foreach($features as $key => $val){
                                    ?>
                                    <div class="feature-item">
                                        <span class="icon"><i class="fa <?php echo esc_attr($val['icon']); ?>"></i></span>
                                        <div class="title-desc">
                                            <h4 class="title"><?php echo esc_attr($val['title'])?></h4>
                                            <p class="desc"><?php echo esc_attr($val['desc'])?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
            <section class="ld-show-case ld-none" id="cases">
                <div class="container ft-container">
                    <div class="row">
                        <div class="col-md-12 text-center title-showcase">
                            <h2 class="show-case-title">Showcase</h2>
                            <p class="desc">See what's our customers <span class="ld-highlight">can do</span> with Traveler</p>
                        </div>
                    </div>
                    <div class="row">

                        <!--         Add New/Edit showcase-->

                        <?php
                        $arr_case = array(
                            array(
                                'img' => get_template_directory_uri().'/demo/img/case/suncar.jpg',
                                'link' => 'suncarsgroup.com'
                            ),
                            array(
                                'img' => get_template_directory_uri().'/demo/img/case/thermeeurope.jpg',
                                'link' => 'thermaeurope.com'
                            ),
                            array(
                                'img' => get_template_directory_uri().'/demo/img/case/beeroam.jpg',
                                'link' => 'beeroam.com'
                            ),
                            array(
                                'img' => get_template_directory_uri().'/demo/img/case/itraveland.jpg',
                                'link' => 'itraveland.com'
                            ),
                        );

                        foreach($arr_case as $key => $val){
                        ?>
                        <div class="sc-item">
                            <div class="item">
                                <img src="<?php echo esc_url($val['img']) ?>">
                                <h3 class="title"><a href="<?php echo esc_url($val['link']) ?>" target="_blank"><?php echo esc_attr($val['link']); ?></a></h3>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <div class="col-md-8 col-sm-12 col-md-offset-2">
                            <div class="more-case">And more than 2000+ sites use Traveler</div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ld-install-theme">
                <div class="div-table">
                    <div class="table-cell">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="icon popup-youtube" href="https://www.youtube.com/watch?v=5fJ4cIapwHw" ><i class="fa fa-play"></i></a>
                                    <h3 class="install-title">Check out the tutorial now to see how to install the Traveler theme!</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="ld-testimonials ld-none" id="testimonials">
                <div class="container ft-container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h2 class="t-title"><span class="ld-highlight">5 stars</span> customer service</h2>
                            <div class="row">
                                <div class="col-md-8 col-sm-12 col-md-offset-2">
                                    <p class="t-desc">Traveler - Having been established since 2015, we understand that customer service is the key to success for online business. You will be making a smart decision to go with Traveler Management Booking System for Wordpress for your travel booking website, and you won't be disappointed with our customer service - GURRANTEED!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row t-row">
                        <?php
                        $arr_testimonials = array(
                            array(
                                'user' => 'astronindigo',
                                'reasion' => 'Good Code',
                                'content' => 'Very good theme :)
                                    The support answers all my questions just awesome !
                                    The documentation is excellent !
                                    If you can add a restaurant category with the menus (even if there is no reservation at first would be awesome) as well as cruises and improve Seo.
                                    In any case, to continue like that you are at the top, 5 stars are widely deserved and notices to the buyers which looks at comments negative, him result certainly from nobody which do not know how to read a documentation or even less install a simple one wordpress.
                                    I look forward to seeing some more of novelties:)'
                            ),
                            array(
                                'user' => 'astronindigo',
                                'reasion' => 'Good Code',
                                'content' => 'Very good theme :)
                                    The support answers all my questions just awesome !
                                    The documentation is excellent !'
                            ),
                            array(
                                'user' => 'Sejuani37',
                                'reasion' => 'Good Code',
                                'content' => 'Very good theme :)
                                    The support answers all my questions just awesome !
                                    The documentation is excellent !'
                            ),
                            array(
                                'user' => 'astronindigo',
                                'reasion' => 'Good Code',
                                'content' => 'Very good theme :)'
                            ),
                            array(
                                'user' => 'multilabel',
                                'reasion' => 'Feature Availability',
                                'content' => 'Amazing Features packed in a great design theme. Need some deep knoledge to make it work. Not for newbie but for experts of Wordpresss and the technology behind.'
                            ),
                        );
                        foreach($arr_testimonials as $val){
                        ?>
<!--                        begin item-->

                        <div class="item">
                            <div class="t-item">
                                <div class="t-head">
                                    <div class="stars-re">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        &nbsp;for &nbsp;<span><?php echo esc_attr($val['reasion'])?></span>
                                    </div>
                                    <div class="t-by"><?php echo esc_attr($val['user'])?></div>
                                </div>
                                <div class="t-content">
                                    <?php echo wpautop($val['content']); ?>
                                </div>
                            </div>
                        </div>
<!--                        end item-->
                        <?php } ?>

                    </div>
                </div>
            </section>
            <section class="lb-thanks">
                <div class="div-table">
                    <div class="table-cell">
                        <h2 class="thank-title">Thanks you for your time!</h2>
                        <p class="thanks-desc">Start creating your new travel booking website today</p>
                        <a href="#" class="buy-now" target="_blank">Buy Now | $89</a>
                    </div>
                </div>
                <div class="thanks-support">
                    <div class="item">
                        <a href="https://shinehelp.ticksy.com/" target="_blank">
                            <i class="fa fa-life-ring"></i>
                            <h4 class="title">SUPPORT</h4>
                        </a>
                    </div>
                    <div class="item">
                        <a href="http://shinetheme.com/documentation/helios/" target="_blank">
                            <i class="fa fa-book"></i>
                            <h4 class="title">DOCUMENTS</h4>
                        </a>
                    </div>
                    <div class="item">
                        <a href="https://www.youtube.com/watch?v=hghfObbp-v0&list=PLKwVkOFkT-MYozhDeR8PKarhL7To_9npN" target="_blank">
                            <i class="fa fa-video-camera"></i>
                            <h4 class="title">VIDEO TUTORIALS</h4>
                        </a>
                    </div>
                </div>
            </section>
            <footer class="ld-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <ul class="ld-social">
                                <li><a href="<?php echo esc_url('https://www.facebook.com/shinethemetoday/') ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="<?php echo esc_url('https://twitter.com/ShineTheme') ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="<?php echo esc_url('https://plus.google.com/u/0/112223222835197628595') ?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <script>
                (function($) {
                    $(document).ready(function() {
                        $('a[href*=#]:not([href=#])').click(function() {
                            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                                var target = $(this.hash);
                                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                                if (target.length) {
                                    $('html,body').animate({
                                        scrollTop: target.offset().top
                                    }, 500);
                                    return false;
                                }
                            }
                        });
                        $('.popup-youtube').magnificPopup({
                            disableOn: 100,
                            type: 'iframe',
                            mainClass: 'mfp-fade',
                            removalDelay: 160,
                            preloader: false,

                            fixedContentPos: false
                        });

                        $('.icon-mobile').click(function(){
                            var p = $(this).parent();
                            p.find($('.ld-menu')).toggleClass('active');
                        });

                        $(window).scroll(function(){
                            var head = $('.ld-site-header'),
                                scroll = $(window).scrollTop();

                            if (scroll >= 100) head.addClass('fixed');
                            else head.removeClass('fixed');
                        });
                    });
                })(jQuery);
            </script>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>