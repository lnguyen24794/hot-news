<?php
/**
 * Template for displaying contact page
 * Template Name: Contact Page
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
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
                <li class="breadcrumb-item active" aria-current="page"><?php esc_html_e('Liên hệ', 'hot-news'); ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Contact Start -->
<div class="news-feed-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <header class="page-header">
                    <h1 class="archive-title">
                        <?php esc_html_e('Liên hệ với chúng tôi', 'hot-news'); ?>
                    </h1>
                    <div class="archive-description">
                        <?php esc_html_e('Hãy để lại thông tin để chúng tôi có thể hỗ trợ bạn tốt nhất', 'hot-news'); ?>
                    </div>
                </header>

                <!-- Contact Form Card -->
                <div class="contact-form-card">
                    <div class="widget-card">
                        <h3 class="widget-title">
                            <i class="fas fa-paper-plane"></i> Gửi tin nhắn
                        </h3>
                        
                        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="contact-form">
                            <input type="hidden" name="action" value="hot_news_contact_form">
                            <?php wp_nonce_field('hot_news_contact_nonce', 'contact_nonce'); ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_name">
                                            <i class="fas fa-user"></i> Họ và tên *
                                        </label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" 
                                               placeholder="<?php esc_attr_e('Nhập họ và tên của bạn', 'hot-news'); ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_email">
                                            <i class="fas fa-envelope"></i> Email *
                                        </label>
                                        <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                               placeholder="<?php esc_attr_e('Nhập email của bạn', 'hot-news'); ?>" required />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_subject">
                                    <i class="fas fa-tag"></i> Tiêu đề *
                                </label>
                                <input type="text" class="form-control" id="contact_subject" name="contact_subject" 
                                       placeholder="<?php esc_attr_e('Nhập tiêu đề tin nhắn', 'hot-news'); ?>" required />
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_message">
                                    <i class="fas fa-comment"></i> Nội dung *
                                </label>
                                <textarea class="form-control" id="contact_message" rows="6" name="contact_message" 
                                          placeholder="<?php esc_attr_e('Nhập nội dung tin nhắn của bạn...', 'hot-news'); ?>" required></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button class="btn btn-primary btn-lg" type="submit">
                                    <i class="fas fa-paper-plane"></i> <?php esc_html_e('Gửi tin nhắn', 'hot-news'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Page Content -->
                <?php
                while (have_posts()) :
                    the_post();
                    if (get_the_content()) :
                ?>
                    <div class="page-content-card">
                        <div class="widget-card">
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </div>
                <?php 
                    endif;
                endwhile;
                ?>
            </div>
            
            <div class="col-lg-4">
                <div class="advertisement-sidebar">
                    <div class="sticky-ads">
                        <!-- Contact Info Card -->
                        <div class="contact-info-widget mb-4">
                            <div class="widget-card">
                                <h4 class="widget-title">
                                    <i class="fas fa-info-circle"></i> Thông tin liên hệ
                                </h4>
                                
                                <div class="contact-info-list">
                                    <div class="contact-info-item">
                                        <div class="contact-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="contact-details">
                                            <h6>Địa chỉ</h6>
                                            <p><?php echo esc_html(hot_news_get_contact_info('address')); ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div class="contact-details">
                                            <h6>Email</h6>
                                            <p>
                                                <a href="mailto:<?php echo esc_attr(hot_news_get_contact_info('email')); ?>">
                                                    <?php echo esc_html(hot_news_get_contact_info('email')); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="contact-details">
                                            <h6>Điện thoại</h6>
                                            <p>
                                                <a href="tel:<?php echo esc_attr(hot_news_get_contact_info('phone')); ?>">
                                                    <?php echo esc_html(hot_news_get_contact_info('phone')); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <?php $business_hours = hot_news_get_contact_info('business_hours'); ?>
                                    <?php if (!empty($business_hours)) : ?>
                                    <div class="contact-info-item">
                                        <div class="contact-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="contact-details">
                                            <h6>Giờ làm việc</h6>
                                            <p><?php echo esc_html($business_hours); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media Widget -->
                        <div class="social-media-widget mb-4">
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
                                            echo '<a href="' . esc_url($data['url']) . '" class="social-link ' . esc_attr($network) . '" target="_blank" rel="noopener">';
                                            echo '<i class="' . esc_attr($data['icon']) . '"></i> ' . esc_html($data['name']);
                                            echo '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Advertisement Banner -->
                        <div class="ad-banner mb-4">
                            <div class="ad-label">Quảng cáo</div>
                            <a href="#" target="_blank" rel="noopener">
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ads-1.jpg'); ?>" 
                                     alt="Quảng cáo" class="img-fluid">
                            </a>
                        </div>
                        
                        <!-- Popular Posts Widget -->
                        <div class="popular-posts-widget">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->

<?php
get_footer();
