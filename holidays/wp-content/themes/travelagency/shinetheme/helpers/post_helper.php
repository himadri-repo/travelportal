<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 3/19/15
 * Time: 5:00 PM
 */
if (!function_exists('agency_set_post_view')) {
    function travelagency_set_post_view($post_id = false)
    {
        if (!$post_id) $post_id = get_the_ID();

        $view = (int)get_post_meta($post_id, 'post_views', true);
        $view++;
        update_post_meta($post_id, 'post_views', $view);
    }
}

if (!function_exists('st_get_post_view')) {
    function st_get_post_view($post_id = false)
    {
        if (!$post_id) $post_id = get_the_ID();

        return (int)get_post_meta($post_id, 'post_views', true);
    }
}

//Tags list
if (!function_exists('st_get_list_tags')) {
    function st_get_list_tags()
    {
        global $post;
        $html = '';
        $tags = wp_get_post_tags($post->ID);
        foreach ($tags as $tag) {
            $html .= '<a class="btn-arc-transparent-big btn-main" href="' . get_tag_link($tag->term_id) . '">
                    <span class="text font1">' . $tag->name . '</span>
                    <span class="btnBefore"></span>
                    <span class="btnAfter"></span>
                </a>';
        }
        return $html;
    }
}

// Category or Taxonomy list
if (!function_exists('st_get_list_taxonomy')) {
    function st_get_list_taxonomy($taxonomy)
    {
        $html = '';
        if (!isset($taxonomy) || empty($taxonomy)) $taxonomy = 'category';
        $tags = get_terms($taxonomy);
        foreach ($tags as $tag) {
            $html .= '<a class="btn-arc-transparent-big btn-main" href="' . get_tag_link($tag->term_id) . '">
                    <span class="text font1">' . $tag->name . '</span>
                    <span class="btnBefore"></span>
                    <span class="btnAfter"></span>
                </a>';
        }
        return $html;
    }
}


if(!function_exists('travelagency_kc_list_taxonomy'))
{
    function travelagency_kc_list_taxonomy($taxonomy)
    {
        $list = array('all' => esc_html__('All', 'travelagency'));
        if(!isset($taxonomy) || empty($taxonomy)) $taxonomy = 'category';
        $tags = get_terms($taxonomy);
        foreach ($tags as $tag) {
            $list[$tag->slug] = $tag->name;
        }
        return $list;
    }
}



if (!function_exists('st_remove_w3c')) {
    function st_remove_w3c($embed_code)
    {
        $embed_code = str_replace('webkitallowfullscreen', '', $embed_code);
        $embed_code = str_replace('mozallowfullscreen', '', $embed_code);
        $embed_code = str_replace('frameborder="0"', '', $embed_code);
        $embed_code = str_replace('frameborder="no"', '', $embed_code);
        $embed_code = str_replace('scrolling="no"', '', $embed_code);
        $embed_code = str_replace('&', '&amp;', $embed_code);
        return $embed_code;
    }
}

// MetaBox
if (!function_exists('st_display_metabox')) {
    function st_display_metabox($type = '')
    {
        switch ($type) {
            case 'blog':
                ?>
                <div class="tp-meta">
                    <span class="tp-meta-date"><i
                            class="fa fa-calendar"></i> <?php echo get_the_date('d M Y,'); ?></span>
                    <span class="tp-meta-admin"><i class="fa fa-user"></i> <a
                            href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author(); ?></a> , </span>
                    <span class="tp-meta-comments"><i class="fa fa-comments"></i> [<?php echo get_comments_number(); ?>]
                        <a href="<?php echo esc_url(get_comments_link()); ?>">
                            <?php
                            echo _n('Comment', 'Comments', get_comments_number(), 'travelagency');
                            ?>
                        </a>
                    </span>
                </div>
                <?php
                break;

            case 'single':
                ?>
                <div class="tp-meta">
                    <span class="tp-meta-date"><i
                            class="fa fa-calendar"></i> <?php echo get_the_date('d M Y,'); ?></span>
                    <span class="tp-meta-admin"><i class="fa fa-user"></i> <a
                            href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author(); ?></a> , </span>
                    <span class="tp-meta-comments"><i class="fa fa-comments"></i> [<?php echo get_comments_number(); ?>]
                        <a href="<?php echo esc_url(get_comments_link()); ?>">
                            <?php
                            echo _n('Comment', 'Comments', get_comments_number(), 'travelagency');
                            ?>
                        </a>
                    </span>
                </div>
                <?php
                break;

            default:
                ?>
                <div class="tp-meta">
                    <span class="tp-meta-date"><i
                            class="fa fa-calendar"></i> <?php echo get_the_date('d M Y,'); ?></span>
                    <span class="tp-meta-admin"><i class="fa fa-user"></i> <a
                            href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author(); ?></a> , </span>
                    <span class="tp-meta-comments"><i class="fa fa-comments"></i> [<?php echo get_comments_number(); ?>]
                        <a href="<?php echo esc_url(get_comments_link()); ?>">
                            <?php
                            echo _n('Comment', 'Comments', get_comments_number(), 'travelagency');
                            ?>
                        </a>
                    </span>
                </div>
                <?php
                break;
        }
        ?>
        <?php
    }
}
if (!function_exists('st_paging_nav')) {
    function st_paging_nav()
    {
        // Don't print empty markup if there's only one page.
        if ($GLOBALS['wp_query']->max_num_pages < 2) {
            return;
        }

        $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $query_args = array();
        $url_parts = explode('?', $pagenum_link);

        if (isset($url_parts[1])) {
            wp_parse_str($url_parts[1], $query_args);
        }

        $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
        $pagenum_link = trailingslashit($pagenum_link) . '%_%';

        $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

        // Set up paginated links.
        $links = paginate_links(array(
            'base' => $pagenum_link,
            'format' => $format,
            'total' => $GLOBALS['wp_query']->max_num_pages,
            'current' => $paged,
            'mid_size' => 1,
            'add_args' => array_map('urlencode', $query_args),
            'prev_text' => esc_html__('&larr; Previous', 'travelagency'),
            'next_text' => esc_html__('Next &rarr;', 'travelagency'),
        ));

        if ($links) : ?>
            <nav class="navigation paging-navigation" role="navigation">
                <div class="pagination loop-pagination">
                    <?php echo balanceTags($links); ?>
                </div><!-- .pagination -->
            </nav><!-- .navigation -->
        <?php endif;
    }
}