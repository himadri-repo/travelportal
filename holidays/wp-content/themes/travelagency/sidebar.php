<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package travelagency
 */
?>
<?php
$sidebar = travelagency_get_sidebar();
if (is_active_sidebar($sidebar['id'])):?>
    <div class="col-md-3 col-sm-4 sidebar margin-top-up">
        <?php dynamic_sidebar($sidebar['id']); ?>
    </div>
<?php endif; ?>