<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Hot_News
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function hot_news_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'hot_news_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function hot_news_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'hot_news_pingback_header');

/**
 * Add custom CSS for admin area
 */
function hot_news_admin_styles() {
    echo '<style>
        .hot-news-meta-box label {
            display: block;
            margin: 10px 0;
            font-weight: 600;
        }
        .hot-news-meta-box input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>';
}
add_action('admin_head', 'hot_news_admin_styles');

/**
 * Modify main query for homepage
 */
function hot_news_modify_main_query($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_home()) {
            $query->set('posts_per_page', 9);
        }
    }
}
add_action('pre_get_posts', 'hot_news_modify_main_query');

/**
 * Add custom post classes
 */
function hot_news_post_classes($classes, $class, $post_id) {
    if (get_post_meta($post_id, '_featured_post', true)) {
        $classes[] = 'featured-post';
    }
    
    if (get_post_meta($post_id, '_breaking_news', true)) {
        $classes[] = 'breaking-news-post';
    }
    
    return $classes;
}
add_filter('post_class', 'hot_news_post_classes', 10, 3);

/**
 * Add custom image sizes to media library
 */
function hot_news_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'news-large' => __('News Large (450x350)', 'hot-news'),
        'news-medium' => __('News Medium (350x223)', 'hot-news'),
        'news-small' => __('News Small (150x100)', 'hot-news'),
    ));
}
add_filter('image_size_names_choose', 'hot_news_custom_image_sizes');

/**
 * Customize comment form
 */
function hot_news_comment_form_defaults($defaults) {
    $defaults['comment_notes_before'] = '';
    $defaults['comment_notes_after'] = '';
    $defaults['title_reply'] = __('Leave a Comment', 'hot-news');
    $defaults['title_reply_to'] = __('Reply to %s', 'hot-news');
    $defaults['cancel_reply_link'] = __('Cancel Reply', 'hot-news');
    $defaults['label_submit'] = __('Post Comment', 'hot-news');
    
    return $defaults;
}
add_filter('comment_form_defaults', 'hot_news_comment_form_defaults');

/**
 * Add custom fields to comment form
 */
function hot_news_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    
    $fields['author'] = '<div class="row"><div class="col-md-6"><div class="form-group">' .
                       '<input id="author" name="author" type="text" class="form-control" placeholder="' . __('Your Name *', 'hot-news') . '" value="' . esc_attr($commenter['comment_author']) . '" required /></div></div>';
    
    $fields['email'] = '<div class="col-md-6"><div class="form-group">' .
                      '<input id="email" name="email" type="email" class="form-control" placeholder="' . __('Your Email *', 'hot-news') . '" value="' . esc_attr($commenter['comment_author_email']) . '" required /></div></div></div>';
    
    $fields['url'] = '<div class="form-group">' .
                    '<input id="url" name="url" type="url" class="form-control" placeholder="' . __('Your Website', 'hot-news') . '" value="' . esc_attr($commenter['comment_author_url']) . '" /></div>';
    
    return $fields;
}
add_filter('comment_form_default_fields', 'hot_news_comment_form_fields');

/**
 * Customize comment form textarea
 */
function hot_news_comment_form_textarea($comment_field) {
    $comment_field = '<div class="form-group">' .
                    '<textarea id="comment" name="comment" class="form-control" rows="5" placeholder="' . __('Your Comment *', 'hot-news') . '" required></textarea></div>';
    
    return $comment_field;
}
add_filter('comment_form_field_comment', 'hot_news_comment_form_textarea');

/**
 * Add custom walker for comments
 */
class Hot_News_Walker_Comment extends Walker_Comment {
    
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $GLOBALS['comment_depth'] = $depth + 1;
        $output .= '<div class="comment-replies">';
    }
    
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $GLOBALS['comment_depth'] = $depth + 1;
        $output .= '</div>';
    }
    
    public function start_el(&$output, $comment, $depth = 0, $args = null, $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        
        if (!empty($args['callback'])) {
            ob_start();
            call_user_func($args['callback'], $comment, $args, $depth);
            $output .= ob_get_clean();
            return;
        }
        
        $tag = 'div';
        $add_below = 'comment';
        
        $output .= '<' . $tag . ' ';
        comment_class('comment-item', $comment, null, $output);
        $output .= ' id="comment-' . get_comment_ID() . '">';
        
        if ('div' != $tag) {
            $output .= ' id="div-comment-' . get_comment_ID() . '">';
        }
        
        $output .= '<div class="comment-content">';
        $output .= '<div class="comment-author">' . get_comment_author_link() . '</div>';
        $output .= '<div class="comment-meta">' . get_comment_date() . ' at ' . get_comment_time() . '</div>';
        
        if ($comment->comment_approved == '0') {
            $output .= '<em class="comment-awaiting-moderation">' . __('Your comment is awaiting moderation.', 'hot-news') . '</em><br />';
        }
        
        comment_text(get_comment_ID(), $output);
        
        $reply_link = get_comment_reply_link(array_merge($args, array(
            'add_below' => $add_below,
            'depth' => $depth,
            'max_depth' => $args['max_depth']
        )));
        
        if ($reply_link) {
            $output .= '<div class="reply">' . $reply_link . '</div>';
        }
        
        $output .= '</div>';
    }
    
    public function end_el(&$output, $comment, $depth = 0, $args = null) {
        $output .= "</div>\n";
    }
}

/**
 * Add schema markup for articles
 */
function hot_news_add_schema_markup() {
    if (is_single() && 'post' == get_post_type()) {
        echo '<script type="application/ld+json">';
        echo json_encode(array(
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => get_the_title(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                )
            ),
            'description' => get_the_excerpt()
        ));
        echo '</script>';
    }
}
add_action('wp_head', 'hot_news_add_schema_markup');

/**
 * Add Open Graph meta tags
 */
function hot_news_add_og_meta() {
    if (is_single()) {
        global $post;
        
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />';
        echo '<meta property="og:description" content="' . esc_attr(get_the_excerpt()) . '" />';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />';
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')) . '" />';
        }
        
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />';
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />';
        echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '" />';
        
        $categories = get_the_category();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />';
            }
        }
        
        $tags = get_the_tags();
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />';
            }
        }
    }
}
add_action('wp_head', 'hot_news_add_og_meta');
