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
                                <?php
                                $sample_contact = hot_news_get_sample_data('contact');
                    ?>
                                <p><i class="fa fa-map-marker"></i><?php echo esc_html(get_theme_mod('hot_news_contact_address', $sample_contact['address'])); ?></p>
                                <p><i class="fa fa-envelope"></i><?php echo esc_html(get_theme_mod('hot_news_contact_email', $sample_contact['email'])); ?></p>
                                <p><i class="fa fa-phone"></i><?php echo esc_html(get_theme_mod('hot_news_contact_phone', $sample_contact['phone'])); ?></p>
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
                    <p><?php echo wp_kses_post(get_theme_mod('hot_news_footer_copyright', sprintf(__('Copyright &copy; %s <a href="%s">%s</a>. All Rights Reserved', 'hot-news'), date('Y'), esc_url(home_url('/')), get_bloginfo('name')))); ?></p>
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
