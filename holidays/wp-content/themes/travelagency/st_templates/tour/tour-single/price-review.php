<?php $service = wpbooking_get_service(); ?>
    <div class="wb-price">
        <?php echo travelagency_get_price_service(); ?>
    </div>
<div class="wb-hotel-star">
    <?php
    $service->get_star_rating_html();
    ?>
</div>