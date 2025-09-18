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
    <!-- 3 Column Layout: Hot News (7) | Popular News (2) | Ads (3) -->
    <div class="homepage-layout">
        <div class="container">
            <div class="row mt-5">
                <!-- Left Column: Hot News (7/12) -->
                <div class="col-lg-6">
                    <div class="hot-news-section">
                        <div id='nz-div'>
                            <h3 class="tde">			
                            <span class="null">Tin mới</span>	
                            </h3>
                        </div>
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
                        <div id='nz-div'>
                            <h3 class="tde">			
                            <span class="null">Tin đọc nhiều</span>	
                            </h3>
                        </div>
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
    <!-- Main News End-->

</main><!-- #main -->

<?php
get_footer();
