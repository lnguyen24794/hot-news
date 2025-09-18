<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hot_News
 */
?>

    <!-- Footer Start -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php else : ?>
                    <div class="col-lg-6 col-md-6">
                        <div class="footer-widget">
                            <h3 class="title"><?php esc_html_e('TIN HOT', 'hot-news'); ?></h3>
                            <div class="contact-info">
                                <p><i class="fa fa-envelope"></i><?php echo esc_html(hot_news_get_contact_info('email')); ?></p>
                                <p><i class="fa fa-phone"></i><?php echo esc_html(hot_news_get_contact_info('phone')); ?></p>
                                <div class="social">
                                    <?php
                                    // Get social networks from theme options (only filled ones)
                                    $social_networks = hot_news_get_social_networks();
                                    
                                    foreach ($social_networks as $network => $data) {
                                        if (!empty($data['url'])) {
                                            echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener" title="' . esc_attr($data['name']) . '">';
                                            echo '<i class="' . esc_attr($data['icon']) . '"></i>';
                                            echo '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h3 class="title"><?php esc_html_e('Danh mục', 'hot-news'); ?></h3>
                            <ul>
                                <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order'   => 'DESC',
                            'number'  => 5,
                        ));

                    foreach ($categories as $category) {
                        echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                    }
                    ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (is_active_sidebar('footer-4')) : ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                <?php else : ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <?php
                            $sample_newsletter = hot_news_get_sample_data('newsletter');
                    ?>
                            <h3 class="title"><?php echo esc_html(get_theme_mod('hot_news_newsletter_title', $sample_newsletter['title'])); ?></h3>
                            <div class="newsletter">
                                <p><?php echo esc_html(get_theme_mod('hot_news_newsletter_description', $sample_newsletter['description'])); ?></p>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <input type="hidden" name="action" value="hot_news_advertisement_signup">
                                    <?php wp_nonce_field('hot_news_advertisement_nonce', 'advertisement_nonce'); ?>
                                    <input class="form-control" type="email" name="newsletter_email" placeholder="<?php echo esc_attr($sample_newsletter['placeholder']); ?>" required>
                                    <button style="width: 40%;" class="btn" type="submit"><?php echo esc_html($sample_newsletter['button_text']); ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    
    <!-- Footer Menu Start -->
    <div class="footer-menu">
        <div class="container">
            <div class="f-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'menu_id'        => 'footer-bottom-menu',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ));

// Fallback menu if no menu is assigned
if (!has_nav_menu('footer-menu')) :
    $sample_footer = hot_news_get_sample_data('footer');
    foreach ($sample_footer['footer_menu'] as $title => $url) : ?>
        <a href="<?php echo esc_url($url == '#' ? home_url('/') : $url); ?>"><?php echo esc_html($title); ?></a>
    <?php endforeach;
endif; ?>
            </div>
        </div>
    </div>
    <!-- Footer Menu End -->

    <!-- Footer Bottom Start -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 copyright">
                    <?php 
                    $copyright_text = hot_news_get_option('copyright_text');
                    if (!empty($copyright_text)) {
                        echo '<p>' . esc_html($copyright_text) . '</p>';
                    } else {
                        echo '<p>&copy; ' . date('Y') . ' <a href="' . esc_url(home_url()) . '">' . esc_html(get_bloginfo('name')) . '</a>. All Rights Reserved.</p>';
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <?php 
                    $site_description = hot_news_get_option('site_description');
                    if (!empty($site_description)) {
                        echo '<p class="text-muted small text-right">' . esc_html(wp_trim_words($site_description, 10)) . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer Bottom End -->

    <!-- Back to Top -->
    <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
