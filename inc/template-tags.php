<?php
/**
 * Custom template tags for this theme
 *
 * @package Hot_News
 */

if (!function_exists('hot_news_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function hot_news_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf($time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'hot-news'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
    }
endif;

if (!function_exists('hot_news_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function hot_news_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'hot-news'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
    }
endif;

if (!function_exists('hot_news_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function hot_news_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'hot-news'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'hot-news') . '</span>', $categories_list); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'hot-news'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'hot-news') . '</span>', $tags_list); // WPCS: XSS OK.
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'hot-news'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'hot-news'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('hot_news_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     */
    function hot_news_post_thumbnail($size = 'post-thumbnail') {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail($size); ?>
            </div><!-- .post-thumbnail -->
        <?php else : ?>
            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail($size, array(
                    'alt' => the_title_attribute(array(
                        'echo' => false,
                    )),
                ));
                ?>
            </a>
        <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('wp_body_open')) :
    /**
     * Shim for sites older than 5.2.
     */
    function wp_body_open() {
        do_action('wp_body_open');
    }
endif;

/**
 * Display news meta information
 */
function hot_news_display_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    echo '<div class="news-meta">';
    echo '<span class="author">' . get_the_author_meta('display_name', get_post_field('post_author', $post_id)) . '</span>';
    echo '<span class="date">' . get_the_date('M j, Y', $post_id) . '</span>';
    
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        echo '<span class="category">' . esc_html($categories[0]->name) . '</span>';
    }
    echo '</div>';
}

/**
 * Display hot news badge
 */
function hot_news_display_badge($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $featured = get_post_meta($post_id, '_featured_post', true);
    $breaking = get_post_meta($post_id, '_breaking_news', true);
    
    if ($breaking) {
        echo '<span class="hot-news-badge" style="background: #dc3545;">BREAKING</span>';
    } elseif ($featured) {
        echo '<span class="hot-news-badge">HOT</span>';
    }
}

/**
 * Get related posts
 */
function hot_news_get_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return array();
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'category__in' => $categories,
        'orderby' => 'rand'
    );
    
    return get_posts($args);
}

/**
 * Display social share buttons
 */
function hot_news_social_share($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    
    echo '<div class="social-share">';
    echo '<h5>' . __('Share this article:', 'hot-news') . '</h5>';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url) . '" target="_blank" class="share-facebook"><i class="fab fa-facebook-f"></i></a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title) . '" target="_blank" class="share-twitter"><i class="fab fa-twitter"></i></a>';
    echo '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url) . '" target="_blank" class="share-linkedin"><i class="fab fa-linkedin-in"></i></a>';
    echo '<a href="mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($post_url) . '" class="share-email"><i class="fas fa-envelope"></i></a>';
    echo '</div>';
}
