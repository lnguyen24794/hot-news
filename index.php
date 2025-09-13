<?php
/**
 * The main template file
 *
 * Template Name: Home Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Hot_News
 */

get_header();

?>

<main id="primary" class="site-main">

    <?php if (is_home() && !is_paged()) : ?>
        <!-- 3 Column Layout: Hot News (7) | Popular News (2) | Ads (3) -->
        <div class="homepage-layout">
            <div class="container">
                <div class="row mt-5">
                    <!-- Left Column: Hot News (7/12) -->
                    <div class="col-lg-6">
                        <div class="hot-news-section">
                            
                            <!-- Featured Hot News -->
                            <div class="featured-hot-news mb-4">
                                <?php
                                    $hot_posts = hot_news_get_hot_posts(6);
                                    if (empty($hot_posts)) {
                                        $hot_posts = get_posts(array(
                                            'posts_per_page' => 1,
                                            'post_status' => 'publish',
                                        ));
                                    }
                                    if (!empty($hot_posts)) :
                                        $post = $hot_posts[0];
                                        setup_postdata($post);
                                        ?>
                                    <?php loadView(get_template_directory() . '/home/vertical-item.php', ['is_main' => true]); ?>
                                <?php
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>

                            <!-- More Hot News -->
                            <div class="more-hot-news">
                                <div class="row">
                                    <?php
                                    
                                    foreach ($hot_posts as $key => $post) :
                                        if ($key == 0) {
                                            continue;
                                        }
                                        setup_postdata($post);
                                        ?>
                                        <div class="col-md-6 mb-3">
                                            <?php loadView(get_template_directory() . '/home/vertical-item.php', ['is_main' => false]); ?>
                                        </div>
                                    <?php endforeach;
                                        wp_reset_postdata(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Middle Column: Popular News (3/12) -->
                    <div class="col-lg-3">
                        <div class="popular-news-section">
                            <div>
                                <?php
                                    $popular_posts = hot_news_get_popular_posts(8);
                                    if (empty($popular_posts)) {
                                        $popular_posts = get_posts(array(
                                            'posts_per_page' => 8,
                                            'post_status' => 'publish',
                                            'meta_key' => '_post_views',
                                            'orderby' => 'meta_value_num',
                                            'order' => 'DESC'
                                        ));
                                    }
                                    if (!empty($popular_posts)) :
                                        $post = $popular_posts[0];
                                        setup_postdata($post);
                                        ?>
                                    <?php loadView(get_template_directory() . '/home/vertical-item.php', ['is_main' => false]); ?>
                                <?php
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </div>
                            <div class="popular-news-list">
                                <?php
                                foreach ($popular_posts as $key => $post) :
                                    if ($key == 0) {
                                        continue;
                                    }
                                    setup_postdata($post);
                                    ?>
                                      <?php loadView(get_template_directory() . '/home/horizontal-item.php', ['is_main' => false]); ?>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Advertisements (3/12) -->
                    <div class="col-lg-3">
                        <div class="ads-section">
                            <!-- Ad Banner 1 -->
                            <div class="ad-banner mb-4">
                                <div class="ad-label">Quảng cáo</div>
                                <?php
                                    hot_news_display_ad(
                                        'homepage_ad_1',
                                        '<a href="#" target="_blank" rel="noopener">
                                                                            <img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-1.jpg') . '" 
                                                                                alt="Quảng cáo 1" class="img-fluid">
                                                                        </a>'
                                    );
                                    ?>
                            </div>

                            <!-- Ad Banner 2 -->
                            <div class="ad-banner mb-4">
                                <div class="ad-label">Quảng cáo</div>
                                <?php
                                hot_news_display_ad(
                                    'homepage_ad_2',
                                    '<a href="#" target="_blank" rel="noopener">
                                                                        <img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-2.jpg') . '" 
                                                                            alt="Quảng cáo 2" class="img-fluid">
                                                                    </a>'
                                );
                                ?>
                            </div>

                            <!-- Newsletter Signup -->
                            <div class="newsletter-widget">
                                <div class="widget-card">
                                    <h5 class="widget-title">
                                        <i class="fas fa-envelope"></i> Đăng ký nhận tin
                                    </h5>
                                    <p>Nhận tin tức mới nhất</p>
                                    <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                                        <input type="hidden" name="action" value="hot_news_newsletter_signup">
                                        <?php wp_nonce_field('hot_news_advertisement_nonce', 'newsletter_nonce'); ?>
                                        <div class="form-group">
                                            <input type="email" name="newsletter_email" class="form-control" 
                                                   placeholder="Email của bạn..." required>
                                        </div>
                                        <button class="btn btn-primary btn-block" type="submit">
                                            Đăng ký <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- Main News End-->

    <!-- Additional News Section for Non-Homepage -->
    <?php if (!is_home() || is_paged()) : ?>
    <div class="news-feed-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <header class="page-header">
                        <h1 class="archive-title">
                            <?php esc_html_e('Tất cả tin tức', 'hot-news'); ?>
                        </h1>
                        <div class="archive-description">
                            <?php esc_html_e('Khám phá những tin tức mới nhất và nổi bật từ chúng tôi', 'hot-news'); ?>
                        </div>
                    </header>

                    <div class="news-feed-items">
                        <?php
                        if (have_posts()) :
                            $post_count = 0;
                            while (have_posts()) :
                                the_post();
                                $post_count++;
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
    endif;
?>
                                                </div>
                                                
                                                <div class="load-more-section text-center">
                                                    <?php
// Pagination with modern styling
the_posts_pagination(array(
    'mid_size'  => 2,
    'prev_text' => '<i class="fas fa-chevron-left"></i> Trước',
    'next_text' => 'Tiếp <i class="fas fa-chevron-right"></i>',
));
?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="advertisement-sidebar">
                        <div class="sticky-ads">
                            <!-- Google AdSense Sidebar Ad -->
                            <div class="ad-banner mb-4">
                                <div class="ad-label">Quảng cáo</div>
                                <?php
    hot_news_display_ad(
        'sidebar_ad_code',
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
                            
                            <!-- Categories Widget -->
                            <div class="categories-widget">
                                <div class="widget-card">
                                    <h4 class="widget-title">
                                        <i class="fas fa-folder"></i> Danh mục
                                    </h4>
                                    <div class="categories-list">
                                        <?php
    $categories = get_categories(array(
        'orderby' => 'count',
        'order'   => 'DESC',
        'number'  => 8,
    ));

foreach ($categories as $category) {
    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">';
    echo '<span class="category-name">' . esc_html($category->name) . '</span>';
    echo '<span class="category-count">' . $category->count . '</span>';
    echo '</a>';
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
    <?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
