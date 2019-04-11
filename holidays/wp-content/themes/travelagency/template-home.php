<?php
/**
 * Created by wpbooking.
 * Developer: nasanji
 * Date: 3/22/2018
 * Version: 1.0
 * Template name: Home
 */
get_header(); ?>
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    while (have_posts()) : the_post();
                        the_content();
                    endwhile; ?>
                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>