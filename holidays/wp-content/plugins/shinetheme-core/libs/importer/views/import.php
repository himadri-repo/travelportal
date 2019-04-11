<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 2/4/15
 * Time: 12:01 AM
 */
?>
<div class="wrap">
    <h2><?php _e('One-click Install Demo Content',STP_TEXTDOMAIN) ?></h2>
</div>
<div id="message" class="updated">
    <p>
    The Demo content is a replication of the Live Content. By importing it, you could get several sliders, sliders,
    pages, posts, theme options, widgets, sidebars and other settings.<br>
    To be able to get them, make sure that you have installed and activated these plugins:  Contact form 7 , Option Tree and Visual Composer<br> <span style="color:#f0ad4e">
WARNING: By clicking Import Demo Content button, your current theme options, sliders and widgets will be replaced. It can also take a minute to complete. <br><span style="color:red"><b>Please back up your database before  it.</b></span>
    </p>
</div>

<br>
<div class="oneclick-demo">
    <div class="oneclick-demo-wrap">
        <div class="screenshot">
            <img src="<?php echo esc_url(get_template_directory_uri().'/screenshot.png'); ?>" alt="creenshot">
        </div>
        <div class="oneclick-button">
            <a href="#" onclick="return false" data-url="<?php echo admin_url('?stp_do_import=1') ?>" class="btn_stp_do_import"><i class="dashicons-before dashicons-download"></i><?php _e('Import Demo',STP_TEXTDOMAIN)?></a>
        </div>
    </div>
</div>
<!--<div id="import_debug">-->
<!--</div> -->
<div class="modal fade st_modal" id="myModal" tabindex="-1" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" data-step="14">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">Import Demo</h3>
            </div>
            <div class="progress-wrap">
                <span class="percent">0%</span>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active import_progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="14" style="width:0%">
                    </div>
                </div>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>