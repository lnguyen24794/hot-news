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
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h3 class="title"><?php esc_html_e('Get in Touch', 'hot-news'); ?></h3>
                            <div class="contact-info">
                                <p><i class="fa fa-map-marker"></i><?php esc_html_e('123 News Street, NY, USA', 'hot-news'); ?></p>
                                <p><i class="fa fa-envelope"></i><?php echo esc_html(get_theme_mod('hot_news_contact_email', 'info@example.com')); ?></p>
                                <p><i class="fa fa-phone"></i><?php echo esc_html(get_theme_mod('hot_news_contact_phone', '+123-456-7890')); ?></p>
                                <div class="social">
                                    <?php
                                    $social_networks = array(
                                        'twitter'   => 'fab fa-twitter',
                                        'facebook'  => 'fab fa-facebook-f',
                                        'linkedin'  => 'fab fa-linkedin-in',
                                        'instagram' => 'fab fa-instagram',
                                        'youtube'   => 'fab fa-youtube',
                                    );

                    foreach ($social_networks as $network => $icon_class) {
                        $social_url = get_theme_mod('hot_news_social_' . $network);
                        if ($social_url) {
                            echo '<a href="' . esc_url($social_url) . '" target="_blank" rel="noopener"><i class="' . esc_attr($icon_class) . '"></i></a>';
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
                <?php else : ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h3 class="title"><?php esc_html_e('Useful Links', 'hot-news'); ?></h3>
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'menu_class'     => '',
                                'items_wrap'     => '<ul>%3$s</ul>',
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ));

                    // Fallback menu if no menu is assigned
                    if (!has_nav_menu('footer-menu')) : ?>
                                <ul>
                                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'hot-news'); ?></a></li>
                                    <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'hot-news'); ?></a></li>
                                    <li><a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy Policy', 'hot-news'); ?></a></li>
                                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact Us', 'hot-news'); ?></a></li>
                                    <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php esc_html_e('About Us', 'hot-news'); ?></a></li>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="col-lg-3 col-md-6">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php else : ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h3 class="title"><?php esc_html_e('Categories', 'hot-news'); ?></h3>
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
                            <h3 class="title"><?php echo esc_html(get_theme_mod('hot_news_newsletter_title', __('Newsletter', 'hot-news'))); ?></h3>
                            <div class="newsletter">
                                <p><?php echo esc_html(get_theme_mod('hot_news_newsletter_description', __('Subscribe to our newsletter to get the latest news and updates.', 'hot-news'))); ?></p>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <input type="hidden" name="action" value="hot_news_newsletter_signup">
                                    <?php wp_nonce_field('hot_news_newsletter_nonce', 'newsletter_nonce'); ?>
                                    <input class="form-control" type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Your email here', 'hot-news'); ?>" required>
                                    <button class="btn" type="submit"><?php esc_html_e('Submit', 'hot-news'); ?></button>
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
if (!has_nav_menu('footer-menu')) : ?>
                    <a href="<?php echo esc_url(home_url('/terms-of-use')); ?>"><?php esc_html_e('Terms of use', 'hot-news'); ?></a>
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy policy', 'hot-news'); ?></a>
                    <a href="<?php echo esc_url(home_url('/cookies')); ?>"><?php esc_html_e('Cookies', 'hot-news'); ?></a>
                    <a href="<?php echo esc_url(home_url('/accessibility')); ?>"><?php esc_html_e('Accessibility help', 'hot-news'); ?></a>
                    <a href="<?php echo esc_url(home_url('/advertise')); ?>"><?php esc_html_e('Advertise with us', 'hot-news'); ?></a>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact us', 'hot-news'); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Footer Menu End -->

    <!-- Footer Bottom Start -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 copyright">
                    <p><?php echo wp_kses_post(get_theme_mod('hot_news_footer_copyright', sprintf(__('Copyright &copy; %s <a href="%s">%s</a>. All Rights Reserved', 'hot-news'), date('Y'), esc_url(home_url('/')), get_bloginfo('name')))); ?></p>
                </div>

                <div class="col-md-6 template-by">
                    <p><?php printf(__('Powered by <a href="%s">WordPress</a> | Theme: Hot News', 'hot-news'), 'https://wordpress.org/'); ?></p>
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
