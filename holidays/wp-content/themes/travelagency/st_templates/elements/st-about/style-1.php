<?php
extract($data);
?>
<div class="feature-block">
    <div class="feature-icon"><i class="<?php if(!empty($icon)){ echo esc_attr($icon); } ?>"></i></div>
    <div class="feature-content">
        <?php if(!empty($title)){ ?><h3 class="feature-title"><?php echo esc_html($title); ?></h3> <?php } ?>
        <?php if(!empty($des)){ ?><p><?php echo esc_html($des); ?></p> <?php } ?>
    </div>
</div>