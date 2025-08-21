<?php
/**
 * Hot News Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Hot_News
 */

if (!defined('HOT_NEWS_VERSION')) {
    define('HOT_NEWS_VERSION', '1.0.0');
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
function hot_news_setup() {
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
    add_image_size('news-large', 450, 350, true); // Large news thumbnail
    add_image_size('news-medium', 350, 223, true); // Medium news thumbnail
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
function hot_news_content_width() {
    $GLOBALS['content_width'] = apply_filters('hot_news_content_width', 825);
}
add_action('after_setup_theme', 'hot_news_content_width', 0);

/**
 * Register widget area.
 */
function hot_news_widgets_init() {
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
function hot_news_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('hot-news-google-fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap', array(), null);

    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', array(), '4.4.1');

    // Enqueue Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css', array(), '5.10.0');

    // Enqueue Slick Slider CSS
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/lib/slick/slick.css', array(), HOT_NEWS_VERSION);
    wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/assets/lib/slick/slick-theme.css', array(), HOT_NEWS_VERSION);

    // Enqueue main theme stylesheet
    wp_enqueue_style('hot-news-style', get_stylesheet_uri(), array(), HOT_NEWS_VERSION);

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
 * Custom Walker for Bootstrap Navigation
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Get featured posts for homepage
 */
function hot_news_get_featured_posts($limit = 5) {
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
function hot_news_get_category_posts($category_slug, $limit = 3) {
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
 * Get popular posts (by comment count)
 */
function hot_news_get_popular_posts($limit = 5) {
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'orderby' => 'comment_count',
        'order' => 'DESC'
    );
    
    return get_posts($args);
}

/**
 * Custom excerpt length
 */
function hot_news_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'hot_news_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function hot_news_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'hot_news_excerpt_more');

/**
 * Add custom meta box for featured posts
 */
function hot_news_add_meta_boxes() {
    add_meta_box(
        'hot_news_featured_post',
        __('Featured Post', 'hot-news'),
        'hot_news_featured_post_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'hot_news_add_meta_boxes');

/**
 * Featured post meta box callback
 */
function hot_news_featured_post_callback($post) {
    wp_nonce_field('hot_news_save_featured_post', 'hot_news_featured_post_nonce');
    $featured = get_post_meta($post->ID, '_featured_post', true);
    ?>
    <label for="hot_news_featured_post">
        <input type="checkbox" id="hot_news_featured_post" name="hot_news_featured_post" value="1" <?php checked($featured, '1'); ?> />
        <?php _e('Mark as featured post', 'hot-news'); ?>
    </label>
    <?php
}

/**
 * Save featured post meta
 */
function hot_news_save_featured_post($post_id) {
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
}
add_action('save_post', 'hot_news_save_featured_post');

/**
 * Add breaking news functionality
 */
function hot_news_add_breaking_news_meta_box() {
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
function hot_news_breaking_news_callback($post) {
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
function hot_news_save_breaking_news($post_id) {
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
function hot_news_display_breaking_news() {
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
