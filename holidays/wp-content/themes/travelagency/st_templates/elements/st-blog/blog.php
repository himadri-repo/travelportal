<?php
/**
 * Created by travelagency.
 * Developer: nasanji
 * Date: 3/22/2018
 * Version: 1.0
 */
extract($atts);

$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;
if (is_front_page()) {
    $paged = (get_query_var('page')) ? absint(get_query_var('page')) : 1;
}

$args = array(
    'orderby' => $orderby,
    'order' => $order,
    'posts_per_page' => (int)$number_post,
    'paged' => $paged,
);

if (!empty($categories)) {
    $list_cat = explode(",", $categories);
    if(!in_array('all', $list_cat)){
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field' => 'slug',
            'terms' => $list_cat,
        );
    }
}else{
    $args['tax_query'][] = array(
        'taxonomy' => 'category',
        'field' => 'slug',
        'terms' => '##',
    );
}

$blog_query = new WP_Query($args);
?>
<div class="st-blog-element">
    <?php
    if($blog_query->have_posts()){
        while($blog_query->have_posts()){
            $blog_query->the_post();
            echo TravelAgency_Template()->load_view('blog-content/items/standard', false, null);
        }
    }
    ?>
</div>

<?php
$html_pagination = '';
if($show_pagi == 'yes') {
$html_pagination .= '<div class="st-pagination">';
    $big = 999999999;
    $sj_link = paginate_links(array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '&page=%#%',
    'total' => $blog_query->max_num_pages,
    'current' => $paged,
    'mid_size' => 1,
    'type' => 'list',
    'prev_text' => '<i class="fa fa-long-arrow-left"></i>',
    'next_text' => '<i class="fa fa-long-arrow-right"></i>',
    )
    );
    $html_pagination .= $sj_link;
    $html_pagination .= '</div>';
}

echo do_shortcode($html_pagination);