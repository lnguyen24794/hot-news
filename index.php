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
        <!-- Top News Start-->
        <div class="top-news">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 tn-left">
                        <div class="row tn-slider">
                            <?php
                            $featured_posts = hot_news_get_featured_posts(2);
        foreach ($featured_posts as $post) :
            setup_postdata($post);
            ?>
                                <div class="col-md-6">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-large'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-450x350-1.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="col-md-6 tn-right">
                        <div class="row">
                            <?php
$hot_posts = hot_news_get_hot_posts(4);
if (empty($hot_posts)) {
    $hot_posts = get_posts(array(
        'posts_per_page' => 4,
        'post_status' => 'publish'
    ));
}
foreach ($hot_posts as $post) :
    setup_postdata($post);
    ?>
                                <div class="col-md-6">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-medium'); ?>
                                        <?php else : ?>
                                            <img  style="max-height: 200px; height: 200px;" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $hot_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top News End-->

        <!-- Category News Start-->
        <?php
        // Get top categories with posts
        $categories = get_categories(array(
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 4,
            'hide_empty' => true
        ));

if ($categories) :
    $category_count = 0;
    foreach ($categories as $category) :
        $category_posts = get_posts(array(
            'category' => $category->term_id,
            'posts_per_page' => 3,
            'post_status' => 'publish'
        ));

        if (empty($category_posts)) {
            continue;
        }

        if ($category_count % 2 == 0) {
            echo '<div class="cat-news"><div class="container"><div class="row">';
        }
        ?>
                
                <div class="col-md-6">
                    <h2><?php echo esc_html($category->name); ?></h2>
                    <div class="row cn-slider">
                        <?php foreach ($category_posts as $post) :
                            setup_postdata($post); ?>
                            <div class="col-md-6">
                                <div class="cn-img">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('news-medium'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $category_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                    <div class="cn-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
        wp_reset_postdata(); ?>
                    </div>
                </div>
                
                <?php
                $category_count++;
        if ($category_count % 2 == 0) {
            echo '</div></div></div>';
        }

        // Limit to 4 categories for homepage
        if ($category_count >= 4) {
            break;
        }
    endforeach;

// Close the last row if odd number of categories
if ($category_count % 2 == 1) {
    echo '</div></div></div>';
}
endif;
?>
        <!-- Category News End-->
        
        <!-- Tab News Start-->
        <div class="tab-news">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#featured">Tin Nổi Bật</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#popular">Phổ Biến</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#latest">Mới Nhất</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="featured" class="container tab-pane active">
                                <?php
                        $featured_posts = hot_news_get_featured_posts(3);
foreach ($featured_posts as $post) :
    setup_postdata($post);
    ?>
                                <div class="tn-news">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-medium'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-1.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="tn-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                                <?php endforeach;
wp_reset_postdata(); ?>
                            </div>
                            <div id="popular" class="container tab-pane fade">
                                <?php
$popular_posts = hot_news_get_popular_posts(3);
foreach ($popular_posts as $post) :
    setup_postdata($post);
    ?>
                                <div class="tn-news">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-medium'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-2.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="tn-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                                <?php endforeach;
wp_reset_postdata(); ?>
                            </div>
                            <div id="latest" class="container tab-pane fade">
                                <?php
$latest_posts = get_posts(array('posts_per_page' => 3));
foreach ($latest_posts as $post) :
    setup_postdata($post);
    ?>
                                <div class="tn-news">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-medium'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-3.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="tn-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                                <?php endforeach;
wp_reset_postdata(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                         <!-- Google AdSense Tab News Ad -->
                         <div class="ad-banner mb-4">
                            <div class="ad-label">Quảng cáo</div>
                            <?php
                            hot_news_display_ad(
                                'tab_news_ad_code',
                                '<a href="#" target="_blank" rel="noopener">
                                    <img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-1.jpg') . '" 
                                            alt="Quảng cáo 1" class="img-fluid">
                                </a>'
                            );
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab News End-->

    <?php endif; ?>

    <!-- Main News Start-->
    <?php if (is_home() && !is_paged()) : ?>
    <div class="main-news">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <?php
                        $main_posts = get_posts(array(
'posts_per_page' => 9,
'post_status' => 'publish',
'meta_query' => array(
'relation' => 'OR',
array(
    'key' => '_featured_post',
    'compare' => 'NOT EXISTS'
),
array(
    'key' => '_featured_post',
    'value' => '1',
    'compare' => '!='
)
)
                        ));

        foreach ($main_posts as $post) :
            setup_postdata($post);
            ?>
                            <div class="col-md-4">
                                <div class="mn-img">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('news-medium'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $main_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                    <div class="mn-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
wp_reset_postdata(); ?>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="mn-list">
                        <h2>Đọc Thêm</h2>
                        <ul>
                            <?php
    $read_more_posts = get_posts(array(
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'orderby' => 'rand'
    ));

foreach ($read_more_posts as $post) :
    setup_postdata($post);
    ?>
                                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php endforeach;
wp_reset_postdata(); ?>
                        </ul>
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
