<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/6/15
 * Time: 3:16 PM
 */
$link=get_template_directory_uri().'/';
?>
<div class="demo_changer" id="demo_changer">
    <div class="demo-icon setting">
        <i class="fa-spin fa fa-cog"></i>
        <span class="hover"><span>Quick Options</span></span>
    </div>
    <a href="http://shinetheme.com/documentation/traveler/" class="demo-icon doc" target="_blank">
        <i class="fa fa-book"></i>
        <span class="hover"><span>Documentation</span></span>
    </a>
    <a href="https://shinehelp.shinetheme.com/" class="demo-icon support" target="_blank">
        <i class="fa fa-life-ring"></i>
        <span class="hover"><span>Support</span></span>
    </a>
<!--    <a href="https://www.youtube.com/watch?v=hghfObbp-v0&list=PLKwVkOFkT-MYozhDeR8PKarhL7To_9npN" class="demo-icon video" target="_blank">-->
<!--        <i class="fa fa-video-camera"></i>-->
<!--        <span class="hover"><span>Video Tutorials</span></span>-->
<!--    </a>-->
    <a href="#" class="demo-icon buy" target="_blank">
        <i class="fa fa-download"></i>
        <span class="hover"><span>Purchase Now</span></span>
    </a>
    <div class="form_holder">
        <div class="purchase">
            <a class="demo-buy-now" href="#" target="_blank">Purchase Now</a>
        </div>
        <div class="line"></div>
        <p class="title">Color Scheme</p>
        <div class="predefined_styles" id="styleswitch_area">
            <a class="styleswitch" href="#" style="background:#4d8638;"></a>
            <a class="styleswitch" href="#" data-src="bright-turquoise" style="background:#0EBCF2;"></a>
            <a class="styleswitch" href="#" data-src="turkish-rose" style="background:#B66672;"></a>
            <a class="styleswitch" href="#" data-src="salem" style="background:#12A641;"></a>
            <a class="styleswitch" href="#" data-src="hippie-blue" style="background:#4F96B6;"></a>
            <a class="styleswitch" href="#" data-src="mandy" style="background:#E45E66;"></a>
            <a class="styleswitch" href="#" data-src="green-smoke" style="background:#96AA66;"></a>
            <a class="styleswitch" href="#" data-src="horizon" style="background:#5B84AA;"></a>
            <a class="styleswitch" href="#" data-src="cerise" style="background:#CA2AC6;"></a>
            <a class="styleswitch" href="#" data-src="brick-red" style="background:#cf315a;"></a>
            <a class="styleswitch" href="#" data-src="de-york" style="background:#74C683;"></a>
            <a class="styleswitch" href="#" data-src="shamrock" style="background:#30BBB1;"></a>
            <a class="styleswitch" href="#" data-src="studio" style="background:#7646B8;"></a>
            <a class="styleswitch" href="#" data-src="leather" style="background:#966650;"></a>
            <a class="styleswitch" href="#" data-src="denim" style="background:#3366cc;"></a>
            <a class="styleswitch" href="#" data-src="scarlet" style="background:#FF1D13;"></a>
        </div>
        <div class="line"></div>
        <p class="title">Homepages Demo</p>
        <div class="home_demo">
            <div class="demo-row">
            <?php
            $arr = array(
                array(
                    'title' => 'Home',
                    'img' => get_template_directory_uri().'/demo/img/home/home.png',
                    'link' => 'http://shinetheme.com/demosd/travelagency'
                ),
            );
            foreach($arr as $key => $val){
                ?>
                <div class="item">
                    <a href="<?php echo esc_url($val['link']); ?>">
                        <img src="<?php echo esc_url($val['img']); ?>" alt="<?php echo esc_attr($val['title']); ?>">
                        <span class="name"><?php echo esc_html($val['title']); ?></span>
                    </a>
                </div>
            <?php
            }
            ?>
            </div>
        </div>
    </div>
</div>