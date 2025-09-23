<?php
/**
 * Hot News Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hot_News
 */

if (!defined('HOT_NEWS_VERSION')) {
    define('HOT_NEWS_VERSION', '1.0.8');
}

/**
 * Define theme constants
 */
define('HOT_NEWS_DIR', get_template_directory());
define('HOT_NEWS_URI', get_template_directory_uri());
define('HOT_NEWS_INC_DIR', HOT_NEWS_DIR . '/inc');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function hot_news_setup()
{
    // Make theme available for translation
    load_theme_textdomain('hot-news', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Set default thumbnail sizes
    set_post_thumbnail_size(825, 525, true); // Main news image
    add_image_size('news-full', 'auto', 'auto', true); // Main news image
    add_image_size('news-large', 450, 350, true); // Large news thumbnail
    add_image_size('news-medium', 350, 200, true); // Medium news thumbnail
    add_image_size('news-small', 150, 100, true); // Small news thumbnail

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'hot-news'),
        'top-menu' => esc_html__('Top Bar Menu', 'hot-news'),
        'footer-menu' => esc_html__('Footer Menu', 'hot-news'),
    ));

    // Switch default core markup for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Add support for wide alignment
    add_theme_support('align-wide');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat'
    ));
}
add_action('after_setup_theme', 'hot_news_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function hot_news_content_width()
{
    $GLOBALS['content_width'] = apply_filters('hot_news_content_width', 825);
}
add_action('after_setup_theme', 'hot_news_content_width', 0);

/**
 * Register widget area.
 */
function hot_news_widgets_init()
{
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'hot-news'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'hot-news'),
        'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="sw-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 1', 'hot-news'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here.', 'hot-news'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 2', 'hot-news'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here.', 'hot-news'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 3', 'hot-news'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here.', 'hot-news'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Widget 4', 'hot-news'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here.', 'hot-news'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'hot_news_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function hot_news_scripts()
{
    // Enqueue Google Fonts
    wp_enqueue_style('hot-news-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap', array(), null);

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', array(), '4.4.1');

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');

    // Enqueue Slick Slider CSS
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/lib/slick/slick.css', array(), HOT_NEWS_VERSION);
    wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/assets/lib/slick/slick-theme.css', array(), HOT_NEWS_VERSION);

    // Enqueue main theme stylesheet
    wp_enqueue_style('hot-news-style', get_stylesheet_uri(), array(), HOT_NEWS_VERSION);
    
    // Add inline CSS for section title links
    $custom_css = '
        .section-title-link {
            color: inherit !important;
            text-decoration: none !important;
            transition: all 0.3s ease;
        }
        .section-title-link:hover {
            color: #007bff !important;
            text-decoration: none !important;
        }
        .section-title-link:hover .null {
            transform: translateX(5px);
        }
        .section-title-link .null {
            transition: transform 0.3s ease;
        }
        .archive-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 30px;
            border-radius: 10px;
            border-left: 5px solid #007bff;
        }
        .archive-title {
            margin-bottom: 10px;
            font-weight: 600;
        }
        .popular-rank {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #333 !important;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
    ';
    wp_add_inline_style('hot-news-style', $custom_css);

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js', array('jquery'), '4.4.1', true);

    // Enqueue Easing JS
    wp_enqueue_script('easing', get_template_directory_uri() . '/assets/lib/easing/easing.min.js', array('jquery'), HOT_NEWS_VERSION, true);

    // Enqueue Slick Slider JS
    wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/lib/slick/slick.min.js', array('jquery'), HOT_NEWS_VERSION, true);

    // Enqueue main theme script
    wp_enqueue_script('hot-news-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap', 'slick-js'), HOT_NEWS_VERSION, true);

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'hot_news_scripts');

/**
 * Custom template tags for this theme.
 */
require HOT_NEWS_INC_DIR . '/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require HOT_NEWS_INC_DIR . '/template-functions.php';

/**
 * Customizer additions.
 */
require HOT_NEWS_INC_DIR . '/customizer.php';

/**
 * Theme Options Page.
 */
require HOT_NEWS_INC_DIR . '/admin/theme-options.php';

/**
 * WordPress Admin Styling Customization.
 */
require HOT_NEWS_INC_DIR . '/admin/admin-styling.php';

/**
 * Load view template with arguments
 *
 * @param string $template_path Path to template file
 * @param array $args Arguments to pass to template
 */
function loadView($template_path, $args = array()) {
    if (file_exists($template_path)) {
        // Make args available as variables in template
        extract($args);
        include $template_path;
    }
}

/**
 * Custom Walker for Bootstrap Navigation
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Get featured posts for homepage
 */
function hot_news_get_featured_posts($limit = 5)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'meta_query' => array(
            array(
                'key' => '_featured_post',
                'value' => '1',
                'compare' => '='
            )
        )
    );

    $featured_posts = get_posts($args);

    // If no featured posts, get latest posts
    if (empty($featured_posts)) {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $featured_posts = get_posts($args);
    }

    return $featured_posts;
}

/**
 * Get posts by category for homepage sections
 */
function hot_news_get_category_posts($category_slug, $limit = 3)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'category_name' => $category_slug,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    return get_posts($args);
}

/**
 * Get popular posts (by views)
 */
function hot_news_get_popular_posts($limit = 5)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'meta_key' => '_post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    $posts = get_posts($args);

    // Fallback to comment count if no posts with views
    if (empty($posts)) {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'orderby' => 'comment_count',
            'order' => 'DESC'
        );
        $posts = get_posts($args);
    }

    return $posts;
}

/**
 * Get most liked posts
 */
function hot_news_get_most_liked_posts($limit = 5)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'meta_key' => '_post_likes',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    $posts = get_posts($args);

    // Fallback to recent posts if no posts with likes
    if (empty($posts)) {
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $posts = get_posts($args);
    }

    return $posts;
}

/**
 * Get most disliked posts
 */
function hot_news_get_most_disliked_posts($limit = 5)
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'meta_key' => '_post_dislikes',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    return get_posts($args);
}

/**
 * Get most viewed posts
 */
function hot_news_get_most_viewed_posts($limit = 5)
{
    return hot_news_get_popular_posts($limit); // Same as popular posts
}

/**
 * Get hot news posts
 */
function hot_news_get_hot_posts($limit = 5)
{
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'meta_value' => '1',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    return get_posts($args);
}

/**
 * Track post views
 */
function hot_news_track_post_views($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id || !is_single()) {
        return;
    }

    // Don't track views for admin users
    if (current_user_can('manage_options')) {
        return;
    }

    // Get current views
    $views = get_post_meta($post_id, '_post_views', true);
    $views = $views ? intval($views) : 0;

    // Increment views
    $views++;

    // Update views
    update_post_meta($post_id, '_post_views', $views);
}

// Removed duplicate tracking function - now handled by hot_news_init_tracking()

/**
 * Custom excerpt length
 */
function hot_news_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', 'hot_news_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function hot_news_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'hot_news_excerpt_more');

/**
 * Add custom meta boxes for posts
 */
function hot_news_add_meta_boxes()
{
    add_meta_box(
        'hot_news_featured_post',
        __('Bài viết nổi bật', 'hot-news'),
        'hot_news_featured_post_callback',
        'post',
        'side',
        'default'
    );

    add_meta_box(
        'hot_news_post_stats',
        __('Thống kê bài viết', 'hot-news'),
        'hot_news_post_stats_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'hot_news_add_meta_boxes');

/**
 * Featured post meta box callback
 */
function hot_news_featured_post_callback($post)
{
    wp_nonce_field('hot_news_save_featured_post', 'hot_news_featured_post_nonce');
    $featured = get_post_meta($post->ID, '_featured_post', true);
    $hot_news = get_post_meta($post->ID, '_hot_news', true);
    ?>
    <p>
        <label for="hot_news_featured_post">
            <input type="checkbox" id="hot_news_featured_post" name="hot_news_featured_post" value="1" <?php checked($featured, '1'); ?> />
            <?php _e('Đánh dấu là bài viết nổi bật', 'hot-news'); ?>
        </label>
    </p>
    <p>
        <label for="hot_news_hot_post">
            <input type="checkbox" id="hot_news_hot_post" name="hot_news_hot_post" value="1" <?php checked($hot_news, '1'); ?> />
            <strong><?php _e('🔥 Đánh dấu là tin HOT', 'hot-news'); ?></strong>
        </label>
    </p>
    <?php
}

/**
 * Post stats meta box callback
 */
function hot_news_post_stats_callback($post)
{
    wp_nonce_field('hot_news_save_post_stats', 'hot_news_post_stats_nonce');

    $views = get_post_meta($post->ID, '_post_views', true) ?: 0;
    $likes = get_post_meta($post->ID, '_post_likes', true) ?: 0;
    $dislikes = get_post_meta($post->ID, '_post_dislikes', true) ?: 0;
    ?>
    <div style="margin-bottom: 15px;">
        <label for="hot_news_post_views"><strong><?php _e('Lượt xem:', 'hot-news'); ?></strong></label><br>
        <input type="number" id="hot_news_post_views" name="hot_news_post_views" value="<?php echo esc_attr($views); ?>" min="0" style="width: 100%;" />
        <small><?php _e('Số lượt xem bài viết này', 'hot-news'); ?></small>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="hot_news_post_likes"><strong><?php _e('Lượt thích:', 'hot-news'); ?></strong></label><br>
        <input type="number" id="hot_news_post_likes" name="hot_news_post_likes" value="<?php echo esc_attr($likes); ?>" min="0" style="width: 100%;" />
        <small><?php _e('Số lượt thích bài viết này', 'hot-news'); ?></small>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="hot_news_post_dislikes"><strong><?php _e('Lượt không thích:', 'hot-news'); ?></strong></label><br>
        <input type="number" id="hot_news_post_dislikes" name="hot_news_post_dislikes" value="<?php echo esc_attr($dislikes); ?>" min="0" style="width: 100%;" />
        <small><?php _e('Số lượt không thích bài viết này', 'hot-news'); ?></small>
    </div>
    
    <div style="padding: 10px; background: #f9f9f9; border-left: 4px solid #0073aa;">
        <strong><?php _e('Tổng quan:', 'hot-news'); ?></strong><br>
        <small>
            <?php printf(__('Tỷ lệ thích: %s%%', 'hot-news'), $likes + $dislikes > 0 ? round(($likes / ($likes + $dislikes)) * 100, 1) : 0); ?><br>
            <?php printf(__('Tương tác: %s lượt', 'hot-news'), $likes + $dislikes); ?>
        </small>
    </div>
    <?php
}

/**
 * Save featured post meta
 */
function hot_news_save_featured_post($post_id)
{
    if (!isset($_POST['hot_news_featured_post_nonce']) || !wp_verify_nonce($_POST['hot_news_featured_post_nonce'], 'hot_news_save_featured_post')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['hot_news_featured_post'])) {
        update_post_meta($post_id, '_featured_post', '1');
    } else {
        delete_post_meta($post_id, '_featured_post');
    }

    // Save hot news field
    if (isset($_POST['hot_news_hot_post'])) {
        update_post_meta($post_id, '_hot_news', '1');
    } else {
        delete_post_meta($post_id, '_hot_news');
    }
}
add_action('save_post', 'hot_news_save_featured_post');

/**
 * Save post stats meta
 */
function hot_news_save_post_stats($post_id)
{
    if (!isset($_POST['hot_news_post_stats_nonce']) || !wp_verify_nonce($_POST['hot_news_post_stats_nonce'], 'hot_news_save_post_stats')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save views
    if (isset($_POST['hot_news_post_views'])) {
        $views = absint($_POST['hot_news_post_views']);
        update_post_meta($post_id, '_post_views', $views);
    }

    // Save likes
    if (isset($_POST['hot_news_post_likes'])) {
        $likes = absint($_POST['hot_news_post_likes']);
        update_post_meta($post_id, '_post_likes', $likes);
    }

    // Save dislikes
    if (isset($_POST['hot_news_post_dislikes'])) {
        $dislikes = absint($_POST['hot_news_post_dislikes']);
        update_post_meta($post_id, '_post_dislikes', $dislikes);
    }
}
add_action('save_post', 'hot_news_save_post_stats');

/**
 * Include sample data
 */
require get_template_directory() . '/sample-data.php';

/**
 * Include demo setup
 */
require get_template_directory() . '/setup-demo.php';

/**
 * Include analytics
 */
require get_template_directory() . '/analytics.php';

/**
 * Include Google Ads Manager
 */
require get_template_directory() . '/inc/admin/google-ads-manager.php';

/**
 * Include Affiliate Manager
 */
require get_template_directory() . '/inc/admin/affiliate-manager.php';

/**
 * Add custom rewrite rules for archive pages
 */
function hot_news_add_custom_rewrite_rules()
{
    add_rewrite_rule('^tin-moi/?$', 'index.php?hot_news_archive=newest', 'top');
    add_rewrite_rule('^tin-doc-nhieu/?$', 'index.php?hot_news_archive=popular', 'top');
}
add_action('init', 'hot_news_add_custom_rewrite_rules');

/**
 * Add custom query vars
 */
function hot_news_add_query_vars($vars)
{
    $vars[] = 'hot_news_archive';
    return $vars;
}
add_filter('query_vars', 'hot_news_add_query_vars');

/**
 * Handle custom archive templates
 */
function hot_news_template_include($template)
{
    $archive_type = get_query_var('hot_news_archive');
    
    if ($archive_type) {
        $custom_template = '';
        
        if ($archive_type === 'newest') {
            $custom_template = locate_template('archive-newest.php');
        } elseif ($archive_type === 'popular') {
            $custom_template = locate_template('archive-popular.php');
        }
        
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'hot_news_template_include');

/**
 * Flush rewrite rules on theme activation
 */
function hot_news_flush_rewrite_rules()
{
    hot_news_add_custom_rewrite_rules();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'hot_news_flush_rewrite_rules');

/**
 * Add breaking news functionality
 */
function hot_news_add_breaking_news_meta_box()
{
    add_meta_box(
        'hot_news_breaking_news',
        __('Breaking News', 'hot-news'),
        'hot_news_breaking_news_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'hot_news_add_breaking_news_meta_box');

/**
 * Breaking news meta box callback
 */
function hot_news_breaking_news_callback($post)
{
    wp_nonce_field('hot_news_save_breaking_news', 'hot_news_breaking_news_nonce');
    $breaking = get_post_meta($post->ID, '_breaking_news', true);
    ?>
    <label for="hot_news_breaking_news">
        <input type="checkbox" id="hot_news_breaking_news" name="hot_news_breaking_news" value="1" <?php checked($breaking, '1'); ?> />
        <?php _e('Mark as breaking news', 'hot-news'); ?>
    </label>
    <?php
}

/**
 * Save breaking news meta
 */
function hot_news_save_breaking_news($post_id)
{
    if (!isset($_POST['hot_news_breaking_news_nonce']) || !wp_verify_nonce($_POST['hot_news_breaking_news_nonce'], 'hot_news_save_breaking_news')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['hot_news_breaking_news'])) {
        update_post_meta($post_id, '_breaking_news', '1');
    } else {
        delete_post_meta($post_id, '_breaking_news');
    }
}
add_action('save_post', 'hot_news_save_breaking_news');

/**
 * Display breaking news banner
 */
function hot_news_display_breaking_news()
{
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => '_breaking_news',
                'value' => '1',
                'compare' => '='
            )
        ),
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $breaking_posts = get_posts($args);

    if (!empty($breaking_posts)) {
        $post = $breaking_posts[0];
        echo '<div class="breaking-news">';
        echo '<a href="' . get_permalink($post->ID) . '" style="color: white; text-decoration: none;">';
        echo esc_html($post->post_title);
        echo '</a>';
        echo '</div>';
    }
}

/**
 * Handle newsletter signup
 */
function hot_news_handle_newsletter_signup()
{
    if (!isset($_POST['newsletter_nonce']) || !wp_verify_nonce($_POST['newsletter_nonce'], 'hot_news_advertisement_nonce')) {
        wp_die(__('Security check failed', 'hot-news'));
    }

    $email = sanitize_email($_POST['newsletter_email']);

    if (!is_email($email)) {
        wp_redirect(add_query_arg('newsletter', 'invalid', wp_get_referer()));
        exit;
    }

    // Here you can add code to save the email to database or send to email service
    // For now, we'll just redirect with success message

    wp_redirect(add_query_arg('newsletter', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_hot_news_newsletter_signup', 'hot_news_handle_newsletter_signup');
add_action('admin_post_nopriv_hot_news_newsletter_signup', 'hot_news_handle_newsletter_signup');

/**
 * Handle contact form submission
 */
function hot_news_handle_contact_form()
{
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'hot_news_contact_nonce')) {
        wp_die(__('Security check failed', 'hot-news'));
    }

    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    if (!is_email($email)) {
        wp_redirect(add_query_arg('contact', 'invalid', wp_get_referer()));
        exit;
    }

    // Here you can add code to send the email
    // For now, we'll just redirect with success message

    wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_hot_news_contact_form', 'hot_news_handle_contact_form');
add_action('admin_post_nopriv_hot_news_contact_form', 'hot_news_handle_contact_form');

/**
 * Display admin notices for form submissions
 */
function hot_news_display_form_notices()
{
    if (isset($_GET['newsletter'])) {
        if ($_GET['newsletter'] === 'success') {
            echo '<div class="alert alert-success" role="alert">' . esc_html__('Thank you for subscribing to our newsletter!', 'hot-news') . '</div>';
        } elseif ($_GET['newsletter'] === 'invalid') {
            echo '<div class="alert alert-danger" role="alert">' . esc_html__('Please enter a valid email address.', 'hot-news') . '</div>';
        }
    }

    if (isset($_GET['contact'])) {
        if ($_GET['contact'] === 'success') {
            echo '<div class="alert alert-success" role="alert">' . esc_html__('Thank you for your message! We will get back to you soon.', 'hot-news') . '</div>';
        } elseif ($_GET['contact'] === 'invalid') {
            echo '<div class="alert alert-danger" role="alert">' . esc_html__('Please enter a valid email address.', 'hot-news') . '</div>';
        }
    }
}
add_action('wp_footer', 'hot_news_display_form_notices');

/**
 * Display like/dislike buttons
 */
function hot_news_display_like_dislike_buttons($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id) {
        return;
    }

    $likes = get_post_meta($post_id, '_post_likes', true) ?: 0;
    $dislikes = get_post_meta($post_id, '_post_dislikes', true) ?: 0;
    $views = get_post_meta($post_id, '_post_views', true) ?: 0;

    ?>
    <div class="post-reactions" style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px;">
        <div class="row">
            <div class="col-md-4">
                <div class="reaction-item text-center">
                    <i class="fas fa-eye" style="color: #6c757d;"></i>
                    <span class="reaction-count"><?php echo number_format($views); ?></span>
                    <small class="d-block">lượt xem</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reaction-item text-center">
                    <button class="btn btn-sm btn-outline-success like-btn" data-post-id="<?php echo $post_id; ?>" data-action="like">
                        <i class="fas fa-thumbs-up"></i>
                        <span class="reaction-count"><?php echo number_format($likes); ?></span>
                    </button>
                    <small class="d-block">thích</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="reaction-item text-center">
                    <button class="btn btn-sm btn-outline-danger dislike-btn" data-post-id="<?php echo $post_id; ?>" data-action="dislike">
                        <i class="fas fa-thumbs-down"></i>
                        <span class="reaction-count"><?php echo number_format($dislikes); ?></span>
                    </button>
                    <small class="d-block">không thích</small>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Handle AJAX like/dislike with rate limiting
 */
function hot_news_handle_like_dislike()
{
    // Rate limiting: max 10 reactions per minute per IP
    if (!hot_news_check_rate_limit('reaction', 10)) {
        wp_send_json_error('Bạn đang thực hiện quá nhanh. Vui lòng chờ một chút!');
        return;
    }

    if (!isset($_POST['post_id']) || !isset($_POST['action_type'])) {
        wp_send_json_error('Dữ liệu không hợp lệ');
        return;
    }

    $post_id = intval($_POST['post_id']);
    $action_type = sanitize_text_field($_POST['action_type']);

    if (!in_array($action_type, ['like', 'dislike'])) {
        wp_send_json_error('Hành động không hợp lệ');
        return;
    }

    // Check if user already reacted (using cookies for simplicity)
    $cookie_name = 'hot_news_reaction_' . $post_id;
    if (isset($_COOKIE[$cookie_name])) {
        wp_send_json_error('Bạn đã đánh giá bài viết này rồi!');
        return;
    }

    // Verify post exists
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error('Bài viết không tồn tại');
        return;
    }

    // Get current count
    $meta_key = $action_type === 'like' ? '_post_likes' : '_post_dislikes';
    $current_count = get_post_meta($post_id, $meta_key, true) ?: 0;
    $new_count = intval($current_count) + 1;

    // Update count
    update_post_meta($post_id, $meta_key, $new_count);

    // Set cookie to prevent multiple reactions (expires in 30 days)
    setcookie($cookie_name, $action_type, time() + (30 * 24 * 60 * 60), '/');

    // Track interaction
    global $wpdb;
    $visitor_id = hot_news_get_visitor_id();
    $table_interactions = $wpdb->prefix . 'hot_news_interactions';

    $wpdb->insert(
        $table_interactions,
        array(
            'visitor_id' => $visitor_id,
            'post_id' => $post_id,
            'interaction_type' => $action_type,
            'interaction_value' => '1',
            'interaction_time' => current_time('mysql')
        )
    );

    wp_send_json_success(array(
        'message' => $action_type === 'like' ? 'Cảm ơn bạn đã thích bài viết!' : 'Cảm ơn phản hồi của bạn!',
        'new_count' => $new_count
    ));
}
add_action('wp_ajax_hot_news_like_dislike', 'hot_news_handle_like_dislike');
add_action('wp_ajax_nopriv_hot_news_like_dislike', 'hot_news_handle_like_dislike');

/**
 * Enqueue analytics script
 */
function hot_news_enqueue_analytics_script()
{
    wp_localize_script('hot-news-main', 'hot_news_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('hot_news_analytics_nonce'),
        'load_more_nonce' => wp_create_nonce('hot_news_load_more_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'hot_news_enqueue_analytics_script');

/**
 * Handle interaction tracking via AJAX with rate limiting
 */
function hot_news_handle_track_interaction()
{
    // Rate limiting: max 100 interactions per minute per IP
    if (!hot_news_check_rate_limit('interaction', 100)) {
        wp_send_json_error('Rate limit exceeded');
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hot_news_analytics_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    global $wpdb;
    $visitor_id = hot_news_get_visitor_id();
    $table_interactions = $wpdb->prefix . 'hot_news_interactions';
    $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';

    // Handle time on page and scroll depth
    if (isset($_POST['time_on_page']) && isset($_POST['scroll_depth'])) {
        $time_on_page = intval($_POST['time_on_page']);
        $scroll_depth = intval($_POST['scroll_depth']);

        // Validate values
        if ($time_on_page < 0 || $time_on_page > 86400) { // Max 24 hours
            wp_send_json_error('Invalid time on page');
            return;
        }

        if ($scroll_depth < 0 || $scroll_depth > 100) {
            wp_send_json_error('Invalid scroll depth');
            return;
        }

        // Update the latest pageview record
        $wpdb->query($wpdb->prepare(
            "UPDATE $table_pageviews 
             SET time_on_page = %d, scroll_depth = %d 
             WHERE visitor_id = %s 
             ORDER BY visit_time DESC 
             LIMIT 1",
            $time_on_page,
            $scroll_depth,
            $visitor_id
        ));
    }

    // Handle click interactions
    if (isset($_POST['interaction_type']) && $_POST['interaction_type'] === 'click') {
        $interaction_value = sanitize_text_field($_POST['interaction_value']);
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        // Limit interaction value length
        $interaction_value = substr($interaction_value, 0, 100);

        $wpdb->insert(
            $table_interactions,
            array(
                'visitor_id' => $visitor_id,
                'post_id' => $post_id,
                'interaction_type' => 'click',
                'interaction_value' => $interaction_value,
                'interaction_time' => current_time('mysql')
            )
        );
    }

    wp_send_json_success('Tracked successfully');
}
add_action('wp_ajax_hot_news_track_interaction', 'hot_news_handle_track_interaction');
add_action('wp_ajax_nopriv_hot_news_track_interaction', 'hot_news_handle_track_interaction');

/**
 * Create analytics tables on theme activation
 */
function hot_news_create_analytics_tables()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Table for visitor tracking
    $table_visitors = $wpdb->prefix . 'hot_news_visitors';
    $sql_visitors = "CREATE TABLE $table_visitors (
        id int(11) NOT NULL AUTO_INCREMENT,
        visitor_id varchar(32) NOT NULL,
        ip_address varchar(45) NOT NULL,
        user_agent text,
        country varchar(100),
        city varchar(100),
        first_visit datetime DEFAULT CURRENT_TIMESTAMP,
        last_visit datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        total_visits int(11) DEFAULT 1,
        PRIMARY KEY (id),
        UNIQUE KEY visitor_id (visitor_id),
        KEY ip_address (ip_address),
        KEY first_visit (first_visit)
    ) $charset_collate;";

    // Table for page views
    $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';
    $sql_pageviews = "CREATE TABLE $table_pageviews (
        id int(11) NOT NULL AUTO_INCREMENT,
        visitor_id varchar(32) NOT NULL,
        post_id int(11),
        page_type varchar(50) NOT NULL,
        page_url varchar(500) NOT NULL,
        page_title varchar(500),
        referrer varchar(500),
        visit_time datetime DEFAULT CURRENT_TIMESTAMP,
        time_on_page int(11) DEFAULT 0,
        scroll_depth int(3) DEFAULT 0,
        PRIMARY KEY (id),
        KEY visitor_id (visitor_id),
        KEY post_id (post_id),
        KEY page_type (page_type),
        KEY visit_time (visit_time)
    ) $charset_collate;";

    // Table for user interactions
    $table_interactions = $wpdb->prefix . 'hot_news_interactions';
    $sql_interactions = "CREATE TABLE $table_interactions (
        id int(11) NOT NULL AUTO_INCREMENT,
        visitor_id varchar(32) NOT NULL,
        post_id int(11),
        interaction_type varchar(50) NOT NULL,
        interaction_value varchar(100),
        interaction_time datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY visitor_id (visitor_id),
        KEY post_id (post_id),
        KEY interaction_type (interaction_type),
        KEY interaction_time (interaction_time)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_visitors);
    dbDelta($sql_pageviews);
    dbDelta($sql_interactions);

    // Set version
    update_option('hot_news_analytics_db_version', '1.0');
}

/**
 * Initialize analytics on theme setup
 */
function hot_news_init_analytics()
{
    $db_version = get_option('hot_news_analytics_db_version');
    if ($db_version !== '1.0') {
        hot_news_create_analytics_tables();
    }
}
add_action('after_setup_theme', 'hot_news_init_analytics');

/**
 * Get or create visitor ID
 */
function hot_news_get_visitor_id()
{
    // Check if visitor ID exists in cookie
    if (isset($_COOKIE['hot_news_visitor_id'])) {
        return sanitize_text_field($_COOKIE['hot_news_visitor_id']);
    }

    // Generate new visitor ID
    $visitor_id = md5(uniqid(rand(), true));

    // Set cookie for 1 year
    setcookie('hot_news_visitor_id', $visitor_id, time() + (365 * 24 * 60 * 60), '/');

    return $visitor_id;
}

/**
 * Track visitor information
 */
function hot_news_track_visitor()
{
    global $wpdb;

    $visitor_id = hot_news_get_visitor_id();
    $ip_address = hot_news_get_client_ip();
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';

    $table_visitors = $wpdb->prefix . 'hot_news_visitors';

    // Check if visitor exists
    $existing_visitor = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_visitors WHERE visitor_id = %s",
        $visitor_id
    ));

    if ($existing_visitor) {
        // Update existing visitor
        $wpdb->update(
            $table_visitors,
            array(
                'last_visit' => current_time('mysql'),
                'total_visits' => $existing_visitor->total_visits + 1,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent
            ),
            array('visitor_id' => $visitor_id)
        );
    } else {
        // Insert new visitor
        $wpdb->insert(
            $table_visitors,
            array(
                'visitor_id' => $visitor_id,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'first_visit' => current_time('mysql'),
                'last_visit' => current_time('mysql'),
                'total_visits' => 1
            )
        );
    }

    return $visitor_id;
}

/**
 * Get client IP address
 */
function hot_news_get_client_ip()
{
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');

    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                }
            }
        }
    }

    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
}

/**
 * Check rate limiting
 */
function hot_news_check_rate_limit($action = 'pageview', $limit = 60)
{
    $ip = hot_news_get_client_ip();
    $cache_key = 'hot_news_rate_limit_' . $action . '_' . md5($ip);

    // Get current count
    $current_count = get_transient($cache_key);

    if ($current_count === false) {
        // First request in this minute
        set_transient($cache_key, 1, 60); // 60 seconds
        return true;
    } elseif ($current_count >= $limit) {
        // Rate limit exceeded
        return false;
    } else {
        // Increment counter
        set_transient($cache_key, $current_count + 1, 60);
        return true;
    }
}

/**
 * Track page view with rate limiting
 */
function hot_news_track_pageview()
{
    global $wpdb;

    // Don't track admin users
    if (current_user_can('manage_options')) {
        return;
    }

    // Rate limiting: max 30 pageviews per minute per IP
    if (!hot_news_check_rate_limit('pageview', 30)) {
        return;
    }

    $visitor_id = hot_news_get_visitor_id();
    $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';
    $post_id = is_single() || is_page() ? get_the_ID() : 0;

    // Check if this exact page was already tracked in the last 60 seconds
    $page_url = home_url(add_query_arg(array(), $GLOBALS['wp']->request));
    $recent_view = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_pageviews 
         WHERE visitor_id = %s AND page_url = %s 
         AND visit_time > DATE_SUB(NOW(), INTERVAL 60 SECOND)
         LIMIT 1",
        $visitor_id,
        $page_url
    ));

    // Don't track if same page was viewed recently
    if ($recent_view) {
        return;
    }

    $visitor_id = hot_news_track_visitor();

    // Get page information
    $page_type = hot_news_get_page_type();
    $page_title = wp_get_document_title();
    $referrer = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';

    // Insert page view
    $wpdb->insert(
        $table_pageviews,
        array(
            'visitor_id' => $visitor_id,
            'post_id' => $post_id,
            'page_type' => $page_type,
            'page_url' => $page_url,
            'page_title' => $page_title,
            'referrer' => $referrer,
            'visit_time' => current_time('mysql')
        )
    );

    // Update post views if it's a single post
    if (is_single() && $post_id) {
        $current_views = get_post_meta($post_id, '_post_views', true) ?: 0;
        update_post_meta($post_id, '_post_views', intval($current_views) + 1);
    }
}

/**
 * Get current page type
 */
function hot_news_get_page_type()
{
    if (is_home() || is_front_page()) {
        return 'home';
    } elseif (is_single()) {
        return 'single';
    } elseif (is_page()) {
        return 'page';
    } elseif (is_category()) {
        return 'category';
    } elseif (is_tag()) {
        return 'tag';
    } elseif (is_archive()) {
        return 'archive';
    } elseif (is_search()) {
        return 'search';
    } elseif (is_404()) {
        return '404';
    }
    return 'other';
}

/**
 * Track page view - single hook with duplicate prevention
 */
function hot_news_init_tracking()
{
    // Don't track admin pages
    if (is_admin()) {
        return;
    }

    // Prevent duplicate tracking in same request
    static $tracked = false;
    if ($tracked) {
        return;
    }
    $tracked = true;

    hot_news_track_pageview();
}
add_action('wp_head', 'hot_news_init_tracking');

/**
 * Add dashboard widgets
 */
function hot_news_add_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'hot_news_analytics_widget',
        __('Thống kê website - Hot News', 'hot-news'),
        'hot_news_analytics_dashboard_widget'
    );
}
add_action('wp_dashboard_setup', 'hot_news_add_dashboard_widgets');

/**
 * Redirect admin dashboard to analytics page
 */
function hot_news_redirect_dashboard_to_analytics()
{
    global $pagenow;

    // Only redirect for admin users on dashboard
    if ($pagenow === 'index.php' && current_user_can('manage_options') && !isset($_GET['redirect_disabled'])) {
        wp_redirect(admin_url('admin.php?page=hot-news-analytics'));
        exit;
    }
}
add_action('admin_init', 'hot_news_redirect_dashboard_to_analytics');

/**
 * Debug tracking function (remove in production)
 */
function hot_news_debug_tracking()
{
    if (isset($_GET['debug_tracking']) && current_user_can('manage_options')) {
        echo '<div style="position: fixed; top: 50px; right: 20px; background: #fff; border: 2px solid #0073aa; padding: 15px; z-index: 9999; max-width: 300px;">';
        echo '<h4>🐛 Debug Tracking</h4>';
        echo '<p><strong>Is Admin:</strong> ' . (is_admin() ? 'Yes' : 'No') . '</p>';
        echo '<p><strong>Can Manage:</strong> ' . (current_user_can('manage_options') ? 'Yes' : 'No') . '</p>';
        echo '<p><strong>Page Type:</strong> ' . hot_news_get_page_type() . '</p>';
        echo '<p><strong>Post ID:</strong> ' . (is_single() ? get_the_ID() : 'N/A') . '</p>';
        echo '<p><strong>Visitor ID:</strong> ' . substr(hot_news_get_visitor_id(), 0, 8) . '...</p>';

        global $wpdb;
        $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';
        $recent_views = $wpdb->get_var("SELECT COUNT(*) FROM $table_pageviews WHERE visit_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        echo '<p><strong>Views (1h):</strong> ' . $recent_views . '</p>';

        // Check for recent duplicate
        $visitor_id = hot_news_get_visitor_id();
        $page_url = home_url(add_query_arg(array('debug_tracking' => ''), $GLOBALS['wp']->request));
        $page_url = remove_query_arg('debug_tracking', $page_url);
        $recent_duplicate = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_pageviews 
             WHERE visitor_id = %s AND page_url = %s 
             AND visit_time > DATE_SUB(NOW(), INTERVAL 60 SECOND)",
            $visitor_id,
            $page_url
        ));
        echo '<p><strong>Recent duplicates:</strong> ' . $recent_duplicate . '</p>';

        echo '<p><a href="' . remove_query_arg('debug_tracking') . '">❌ Close</a></p>';
        echo '</div>';
    }
}
add_action('wp_footer', 'hot_news_debug_tracking');

/**
 * AJAX handler for loading more posts (infinite scroll)
 */
function hot_news_load_more_posts()
{
    // Rate limiting: max 20 requests per minute per IP
    if (!hot_news_check_rate_limit('load_more', 20)) {
        wp_send_json_error('Bạn đang tải quá nhanh. Vui lòng chờ một chút!');
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'hot_news_load_more_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }

    $page = intval($_POST['page']);
    $posts_per_page = 5; // Load 5 posts each time
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
    $author = isset($_POST['author']) ? intval($_POST['author']) : 0;
    $year = isset($_POST['year']) ? intval($_POST['year']) : 0;
    $month = isset($_POST['month']) ? intval($_POST['month']) : 0;
    $day = isset($_POST['day']) ? intval($_POST['day']) : 0;
    $archive_type = isset($_POST['archive_type']) ? sanitize_text_field($_POST['archive_type']) : '';

    // Validate page number
    if ($page < 1 || $page > 100) { // Limit to 100 pages max
        wp_send_json_error('Invalid page number');
        return;
    }

    // Build query args based on archive type
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    // Add specific query parameters based on archive type
    if (!empty($archive_type)) {
        if ($archive_type === 'newest') {
            // Already set to order by date DESC, no additional parameters needed
        } elseif ($archive_type === 'popular') {
            $args['meta_key'] = '_post_views';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
        }
    } elseif (!empty($category)) {
        $args['category_name'] = $category;
    } elseif (!empty($tag)) {
        $args['tag'] = $tag;
    } elseif ($author > 0) {
        $args['author'] = $author;
    } elseif ($year > 0) {
        $args['year'] = $year;
        if ($month > 0) {
            $args['monthnum'] = $month;
            if ($day > 0) {
                $args['day'] = $day;
            }
        }
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        wp_send_json_error('Không còn bài viết nào để tải');
        return;
    }

    $posts_html = '';
    $post_count = ($page - 1) * $posts_per_page;

    while ($query->have_posts()) {
        $query->the_post();
        $post_count++;

        ob_start();
        ?>
        <div class="news-feed-item" data-post-id="<?php echo get_the_ID(); ?>">
            <div class="news-item-card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="news-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('news-medium', array('class' => 'img-fluid')); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . (($post_count % 5) + 1) . '.jpg'); ?>" 
                                         alt="<?php the_title_attribute(); ?>" class="img-fluid">
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="news-content">
                            <?php hot_news_display_badge(); ?>
                            <h3 class="news-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <?php hot_news_display_meta(); ?>
                            <div class="news-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="news-actions">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                                    Đọc tiếp <i class="fas fa-arrow-right"></i>
                                </a>
                                <div class="news-stats">
                                    <?php
                                    $views = get_post_meta(get_the_ID(), '_post_views', true) ?: 0;
        $likes = get_post_meta(get_the_ID(), '_post_likes', true) ?: 0;
        ?>
                                    <span class="stat-item">
                                        <i class="fas fa-eye"></i> <?php echo number_format($views); ?>
                                    </span>
                                    <span class="stat-item">
                                        <i class="fas fa-thumbs-up"></i> <?php echo number_format($likes); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $posts_html .= ob_get_clean();
    }

    wp_reset_postdata();

    // Check if there are more posts
    $has_more = $query->max_num_pages > $page;

    wp_send_json_success(array(
        'posts_html' => $posts_html,
        'has_more' => $has_more,
        'current_page' => $page,
        'max_pages' => $query->max_num_pages
    ));
}
add_action('wp_ajax_hot_news_load_more_posts', 'hot_news_load_more_posts');
add_action('wp_ajax_nopriv_hot_news_load_more_posts', 'hot_news_load_more_posts');

/**
 * Analytics dashboard widget content
 */
function hot_news_analytics_dashboard_widget()
{
    global $wpdb;

    $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';

    // Get today's stats
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));

    $today_views = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_pageviews WHERE DATE(visit_time) = %s",
        $today
    ));

    $yesterday_views = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_pageviews WHERE DATE(visit_time) = %s",
        $yesterday
    ));

    $today_visitors = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(DISTINCT visitor_id) FROM $table_pageviews WHERE DATE(visit_time) = %s",
        $today
    ));

    // Get this week's stats
    $week_start = date('Y-m-d', strtotime('monday this week'));
    $week_views = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_pageviews WHERE DATE(visit_time) >= %s",
        $week_start
    ));

    // Get most viewed post today
    $top_post = $wpdb->get_row($wpdb->prepare(
        "SELECT p.post_title, COUNT(*) as views 
         FROM $table_pageviews pv 
         JOIN {$wpdb->posts} p ON pv.post_id = p.ID 
         WHERE DATE(pv.visit_time) = %s AND pv.post_id > 0 
         GROUP BY pv.post_id 
         ORDER BY views DESC 
         LIMIT 1",
        $today
    ));

    $change_percent = $yesterday_views > 0 ? round((($today_views - $yesterday_views) / $yesterday_views) * 100, 1) : 0;
    $change_class = $change_percent > 0 ? 'up' : ($change_percent < 0 ? 'down' : 'same');
    $change_icon = $change_percent > 0 ? '↗' : ($change_percent < 0 ? '↘' : '→');

    ?>
    <div class="hot-news-analytics-widget">
        <style>
        .hot-news-analytics-widget {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .analytics-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .analytics-stat {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #2271b1;
        }
        .analytics-stat.visitors {
            border-left-color: #00a32a;
        }
        .analytics-stat.week {
            border-left-color: #f56e28;
        }
        .analytics-stat.top {
            border-left-color: #d63638;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #1d2327;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            color: #646970;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-change {
            font-size: 11px;
            margin-top: 5px;
        }
        .stat-change.up { color: #00a32a; }
        .stat-change.down { color: #d63638; }
        .stat-change.same { color: #646970; }
        .top-post {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 6px;
            border-left: 4px solid #d63638;
            margin-bottom: 15px;
        }
        .widget-actions {
            text-align: center;
        }
        .widget-actions .button {
            margin: 0 5px;
        }
        </style>
        
        <div class="analytics-grid">
            <div class="analytics-stat">
                <div class="stat-number"><?php echo number_format($today_views); ?></div>
                <div class="stat-label">Lượt xem hôm nay</div>
                <div class="stat-change <?php echo $change_class; ?>">
                    <?php echo $change_icon; ?> <?php echo abs($change_percent); ?>% so với hôm qua
                </div>
            </div>
            
            <div class="analytics-stat visitors">
                <div class="stat-number"><?php echo number_format($today_visitors); ?></div>
                <div class="stat-label">Khách truy cập hôm nay</div>
            </div>
        </div>
        
        <div class="analytics-grid">
            <div class="analytics-stat week">
                <div class="stat-number"><?php echo number_format($week_views); ?></div>
                <div class="stat-label">Lượt xem tuần này</div>
            </div>
            
            <div class="analytics-stat top">
                <div class="stat-number"><?php echo $top_post ? $top_post->views : 0; ?></div>
                <div class="stat-label">Bài viết hot nhất</div>
            </div>
        </div>
        
        <?php if ($top_post): ?>
        <div class="top-post">
            <strong>🔥 Bài viết được xem nhiều nhất hôm nay:</strong><br>
            <small><?php echo esc_html($top_post->post_title); ?></small>
        </div>
        <?php endif; ?>
        
        <div class="widget-actions">
            <a href="<?php echo admin_url('admin.php?page=hot-news-analytics'); ?>" class="button button-primary">
                📊 Xem báo cáo chi tiết
            </a>
            <a href="<?php echo admin_url('edit.php'); ?>" class="button">
                📝 Quản lý bài viết
            </a>
        </div>
    </div>
    <?php
}

add_filter('mejs_settings', function($settings) {
    $settings['stretching'] = 'responsive'; // Tối ưu responsive trên mobile
    $settings['pluginPath'] = 'https://cdn.jsdelivr.net/npm/mediaelement@latest/build/'; // Sử dụng CDN mới
    return $settings;
});
