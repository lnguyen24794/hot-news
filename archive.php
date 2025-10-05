<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Trang chủ', 'hot-news'); ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(__('Author: %s', 'hot-news'), '<span class="vcard">' . get_the_author() . '</span>');
                    } elseif (is_day()) {
                        printf(__('Day: %s', 'hot-news'), '<span>' . get_the_date() . '</span>');
                    } elseif (is_month()) {
                        printf(__('Month: %s', 'hot-news'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'hot-news')) . '</span>');
                    } elseif (is_year()) {
                        printf(__('Year: %s', 'hot-news'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'hot-news')) . '</span>');
                    } else {
                        esc_html_e('Archives', 'hot-news');
                    }
?>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<main id="primary" class="site-main">
    <!-- News Feed Start-->
    <div class="news-feed-container">
        <div class="container">
            <div class="row">
                <!-- News Feed Column (8/12) -->
                <div class="col-lg-8">
                    <?php if (have_posts()) : ?>
                        
                        <!-- News Feed Items Container -->
                        <div id="news-feed-container" class="news-feed-items">
                            <?php
                            $post_count = 0;
                        $posts_shown = 0;
                        while (have_posts() && $posts_shown < 5) : // Only show first 5 posts initially
                            the_post();
                            $post_count++;
                            $posts_shown++;
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
                        endwhile;
?>
                        </div>
                        
                        <!-- Load More Button and Loading Indicator -->
                        <div class="load-more-section text-center">
                            <?php if ($wp_query->max_num_pages > 1) : ?>
                                <button id="load-more-btn" class="btn btn-outline-primary btn-lg" 
                                        data-page="2" 
                                        data-max-pages="<?php echo $wp_query->max_num_pages; ?>"
                                        data-category="<?php echo is_category() ? get_queried_object()->slug : ''; ?>"
                                        data-tag="<?php echo is_tag() ? get_queried_object()->slug : ''; ?>"
                                        data-author="<?php echo is_author() ? get_queried_object()->ID : 0; ?>"
                                        data-year="<?php echo is_year() ? get_query_var('year') : 0; ?>"
                                        data-month="<?php echo is_month() ? get_query_var('monthnum') : 0; ?>"
                                        data-day="<?php echo is_day() ? get_query_var('day') : 0; ?>">
                                    <i class="fas fa-plus"></i> Tải thêm tin tức
                                </button>
                            <?php endif; ?>
                            
                            <div id="loading-indicator" class="loading-indicator" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Đang tải...</span>
                                </div>
                                <p class="mt-2">Đang tải thêm tin tức...</p>
                            </div>
                            
                            <div id="no-more-posts" class="no-more-posts" style="display: none;">
                                <p class="text-muted">
                                    <i class="fas fa-check-circle"></i> Bạn đã xem hết tất cả tin tức
                                </p>
                            </div>
                        </div>
                        
                    <?php else : ?>
                        
                        <div class="no-results">
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h2><?php esc_html_e('Không tìm thấy tin tức', 'hot-news'); ?></h2>
                                <p class="text-muted"><?php esc_html_e('Không có tin tức nào trong danh mục này. Hãy thử tìm kiếm hoặc xem các danh mục khác.', 'hot-news'); ?></p>
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                </div>

                <!-- Advertisement Column (4/12) -->
                <div class="col-lg-4">
                    <div class="advertisement-sidebar">
                        <div class="sticky-ads">
                            <!-- Google AdSense Archive Sidebar Banner 1 -->
                            <div class="ad-banner mb-4">
                                <div class="ad-label">Quảng cáo</div>
                                <?php
    hot_news_display_ad(
        'archive_sidebar_ad_1',
        '<a href="#" target="_blank" rel="noopener">
                                        <img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-1.jpg') . '" 
                                             alt="Quảng cáo 1" class="img-fluid">
                                    </a>'
    );
?>
                            </div>
                            
                            <!-- Newsletter Signup -->
                            <div class="newsletter-widget mb-4">
                                <div class="widget-card">
                                    <h4 class="widget-title">
                                        <i class="fas fa-envelope"></i> Đăng ký nhận tin
                                    </h4>
                                    <p>Nhận tin tức mới nhất qua email của bạn</p>
                                    <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                        <input type="hidden" name="action" value="hot_news_newsletter_signup">
                                        <?php wp_nonce_field('hot_news_advertisement_nonce', 'newsletter_nonce'); ?>
                                        <div class="input-group">
                                            <input type="email" name="newsletter_email" class="form-control" 
                                                   placeholder="Email của bạn..." required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Google AdSense Archive Sidebar Banner 2 -->
                            <div class="ad-banner mb-4">
                                <div class="ad-label">Quảng cáo</div>
                                <?php
hot_news_display_ad(
    'archive_sidebar_ad_2',
    '<a href="#" target="_blank" rel="noopener">
                                        <img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-2.jpg') . '" 
                                             alt="Quảng cáo 2" class="img-fluid">
                                    </a>'
);
?>
                            </div>
                            
                            <!-- Popular Posts Widget -->
                            <div class="popular-posts-widget mb-4">
                                <div class="widget-card">
                                    <h4 class="widget-title">
                                        <i class="fas fa-fire"></i> Tin nổi bật
                                    </h4>
                                    <?php
    $popular_posts = hot_news_get_popular_posts(5);
if (!empty($popular_posts)) :
    ?>
                                        <div class="popular-posts-list">
                                            <?php foreach ($popular_posts as $popular_post) : ?>
                                                <div class="popular-post-item">
                                                    <div class="row no-gutters">
                                                        <div class="col-4">
                                                            <?php if (has_post_thumbnail($popular_post->ID)) : ?>
                                                                <a href="<?php echo get_permalink($popular_post->ID); ?>">
                                                                    <?php echo get_the_post_thumbnail($popular_post->ID, 'news-small', array('class' => 'img-fluid')); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="popular-post-content">
                                                                <h6 class="popular-post-title">
                                                                    <a href="<?php echo get_permalink($popular_post->ID); ?>">
                                                                        <?php echo wp_trim_words($popular_post->post_title, 8); ?>
                                                                    </a>
                                                                </h6>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-eye"></i> 
                                                                    <?php echo number_format(get_post_meta($popular_post->ID, '_post_views', true) ?: 0); ?>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php
endif;
?>
                                </div>
                            </div>
                            
                            <!-- Social Media Widget -->
                            <div class="social-media-widget">
                                <div class="widget-card">
                                    <h4 class="widget-title">
                                        <i class="fas fa-share-alt"></i> Theo dõi chúng tôi
                                    </h4>
                                    <div class="social-links">
                                        <?php
                                        // Get social networks from theme options (only filled ones)
                                        $social_networks = hot_news_get_social_networks();

foreach ($social_networks as $network => $data) {
    if (!empty($data['url'])) {
        echo '<a class="social-link ' . esc_attr($network) . '" href="' . esc_url($data['url']) . '" target="_blank" rel="noopener" title="' . esc_attr($data['name']) . '">';
        echo '<i class="' . esc_attr($data['icon']) . '"></i>';
        echo '</a>';
    }
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News Feed End-->
</main><!-- #main -->

<?php
get_footer();
