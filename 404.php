<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Hot_News
 */

get_header();
?>

<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'hot-news'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php esc_html_e('404 Error', 'hot-news'); ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<main id="primary" class="site-main">
    <!-- 404 Error Start-->
    <div class="main-news">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-404" style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 10px; margin: 40px 0;">
                        
                        <div class="error-number" style="font-size: 8rem; font-weight: bold; color: #FF6F61; line-height: 1; margin-bottom: 20px;">
                            404
                        </div>
                        
                        <h1 class="error-title" style="font-size: 2.5rem; color: #000; margin-bottom: 20px;">
                            <?php esc_html_e('Oops! Page Not Found', 'hot-news'); ?>
                        </h1>
                        
                        <p class="error-description" style="font-size: 1.2rem; color: #666; margin-bottom: 30px; max-width: 500px; margin-left: auto; margin-right: auto;">
                            <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'hot-news'); ?>
                        </p>
                        
                        <div class="error-search" style="max-width: 400px; margin: 30px auto;">
                            <h3 style="margin-bottom: 15px; color: #000;"><?php esc_html_e('Try searching for what you need:', 'hot-news'); ?></h3>
                            <?php get_search_form(); ?>
                        </div>
                        
                        <div class="error-actions" style="margin-top: 40px;">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn" style="display: inline-block; padding: 12px 30px; background: #FF6F61; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; font-weight: 600;">
                                <i class="fas fa-home"></i> <?php esc_html_e('Go to Homepage', 'hot-news'); ?>
                            </a>
                            <a href="javascript:history.back()" class="btn" style="display: inline-block; padding: 12px 30px; background: #000; color: white; text-decoration: none; border-radius: 5px; font-weight: 600;">
                                <i class="fas fa-arrow-left"></i> <?php esc_html_e('Go Back', 'hot-news'); ?>
                            </a>
                        </div>
                        
                        <div class="error-suggestions" style="margin-top: 50px;">
                            <h3 style="margin-bottom: 20px; color: #000;"><?php esc_html_e('You might be interested in:', 'hot-news'); ?></h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 style="color: #FF6F61; margin-bottom: 15px;"><?php esc_html_e('Popular Categories', 'hot-news'); ?></h4>
                                    <ul style="list-style: none; padding: 0; text-align: left;">
                                        <?php
                                        $categories = get_categories(array(
                                            'orderby' => 'count',
                                            'order'   => 'DESC',
                                            'number'  => 5,
                                        ));

foreach ($categories as $category) {
    echo '<li style="margin-bottom: 8px;"><a href="' . esc_url(get_category_link($category->term_id)) . '" style="color: #666; text-decoration: none;"><i class="fas fa-folder" style="color: #FF6F61; margin-right: 8px;"></i>' . esc_html($category->name) . ' (' . $category->count . ')</a></li>';
}
?>
                                    </ul>
                                </div>
                                
                                <div class="col-md-6">
                                    <h4 style="color: #FF6F61; margin-bottom: 15px;"><?php esc_html_e('Recent Posts', 'hot-news'); ?></h4>
                                    <ul style="list-style: none; padding: 0; text-align: left;">
                                        <?php
$recent_posts = get_posts(array(
    'posts_per_page' => 5,
    'post_status' => 'publish'
));

foreach ($recent_posts as $post) {
    setup_postdata($post);
    echo '<li style="margin-bottom: 8px;"><a href="' . esc_url(get_permalink()) . '" style="color: #666; text-decoration: none; font-size: 0.9rem;"><i class="fas fa-newspaper" style="color: #FF6F61; margin-right: 8px;"></i>' . esc_html(get_the_title()) . '</a></li>';
}
wp_reset_postdata();
?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="error-contact" style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #ddd;">
                            <p style="color: #666; margin-bottom: 15px;">
                                <?php esc_html_e('Still can\'t find what you\'re looking for?', 'hot-news'); ?>
                            </p>
                            <a href="<?php echo esc_url(home_url('/contact')); ?>" style="color: #FF6F61; text-decoration: none; font-weight: 600;">
                                <i class="fas fa-envelope"></i> <?php esc_html_e('Contact Us', 'hot-news'); ?>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 Error End-->
</main><!-- #main -->

<?php
get_footer();
