<?php
/**
 * Hot News Theme Customizer
 *
 * @package Hot_News
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function hot_news_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector'        => '.site-title a',
            'render_callback' => 'hot_news_customize_partial_blogname',
        ));
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector'        => '.site-description',
            'render_callback' => 'hot_news_customize_partial_blogdescription',
        ));
    }

    // Add Hot News Settings Panel
    $wp_customize->add_panel('hot_news_settings', array(
        'title'    => __('Hot News Settings', 'hot-news'),
        'priority' => 30,
    ));

    // Header Settings Section
    $wp_customize->add_section('hot_news_header', array(
        'title'    => __('Header Settings', 'hot-news'),
        'panel'    => 'hot_news_settings',
        'priority' => 10,
    ));

    // Top Bar Contact Info
    $wp_customize->add_setting('hot_news_contact_email', array(
        'default'           => 'info@example.com',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('hot_news_contact_email', array(
        'label'   => __('Contact Email', 'hot-news'),
        'section' => 'hot_news_header',
        'type'    => 'email',
    ));

    $wp_customize->add_setting('hot_news_contact_phone', array(
        'default'           => '+123 456 7890',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_contact_phone', array(
        'label'   => __('Contact Phone', 'hot-news'),
        'section' => 'hot_news_header',
        'type'    => 'text',
    ));

    // Header Advertisement
    $wp_customize->add_setting('hot_news_header_ad_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hot_news_header_ad_image', array(
        'label'   => __('Header Advertisement Image', 'hot-news'),
        'section' => 'hot_news_header',
    )));

    $wp_customize->add_setting('hot_news_header_ad_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hot_news_header_ad_url', array(
        'label'   => __('Header Advertisement URL', 'hot-news'),
        'section' => 'hot_news_header',
        'type'    => 'url',
    ));

    // Social Media Section
    $wp_customize->add_section('hot_news_social', array(
        'title'    => __('Social Media', 'hot-news'),
        'panel'    => 'hot_news_settings',
        'priority' => 20,
    ));

    $social_networks = array(
        'facebook'  => 'Facebook',
        'twitter'   => 'Twitter',
        'instagram' => 'Instagram',
        'linkedin'  => 'LinkedIn',
        'youtube'   => 'YouTube',
    );

    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting('hot_news_social_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('hot_news_social_' . $network, array(
            'label'   => $label . ' ' . __('URL', 'hot-news'),
            'section' => 'hot_news_social',
            'type'    => 'url',
        ));
    }

    // Homepage Settings Section
    $wp_customize->add_section('hot_news_homepage', array(
        'title'    => __('Homepage Settings', 'hot-news'),
        'panel'    => 'hot_news_settings',
        'priority' => 30,
    ));

    // Featured Categories
    $categories = get_categories();
    $category_choices = array('' => __('Select Category', 'hot-news'));
    foreach ($categories as $category) {
        $category_choices[$category->slug] = $category->name;
    }

    $wp_customize->add_setting('hot_news_featured_category_1', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_featured_category_1', array(
        'label'   => __('Featured Category 1', 'hot-news'),
        'section' => 'hot_news_homepage',
        'type'    => 'select',
        'choices' => $category_choices,
    ));

    $wp_customize->add_setting('hot_news_featured_category_2', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_featured_category_2', array(
        'label'   => __('Featured Category 2', 'hot-news'),
        'section' => 'hot_news_homepage',
        'type'    => 'select',
        'choices' => $category_choices,
    ));

    $wp_customize->add_setting('hot_news_featured_category_3', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_featured_category_3', array(
        'label'   => __('Featured Category 3', 'hot-news'),
        'section' => 'hot_news_homepage',
        'type'    => 'select',
        'choices' => $category_choices,
    ));

    $wp_customize->add_setting('hot_news_featured_category_4', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_featured_category_4', array(
        'label'   => __('Featured Category 4', 'hot-news'),
        'section' => 'hot_news_homepage',
        'type'    => 'select',
        'choices' => $category_choices,
    ));

    // Sidebar Advertisement
    $wp_customize->add_setting('hot_news_sidebar_ad_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hot_news_sidebar_ad_image', array(
        'label'   => __('Sidebar Advertisement Image', 'hot-news'),
        'section' => 'hot_news_homepage',
    )));

    $wp_customize->add_setting('hot_news_sidebar_ad_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hot_news_sidebar_ad_url', array(
        'label'   => __('Sidebar Advertisement URL', 'hot-news'),
        'section' => 'hot_news_homepage',
        'type'    => 'url',
    ));

    // Footer Settings Section
    $wp_customize->add_section('hot_news_footer', array(
        'title'    => __('Footer Settings', 'hot-news'),
        'panel'    => 'hot_news_settings',
        'priority' => 40,
    ));

    $wp_customize->add_setting('hot_news_footer_copyright', array(
        'default'           => sprintf(__('Copyright &copy; %s. All Rights Reserved', 'hot-news'), date('Y')),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('hot_news_footer_copyright', array(
        'label'   => __('Footer Copyright Text', 'hot-news'),
        'section' => 'hot_news_footer',
        'type'    => 'textarea',
    ));

    // Newsletter Section
    $wp_customize->add_setting('hot_news_newsletter_title', array(
        'default'           => __('Newsletter', 'hot-news'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hot_news_newsletter_title', array(
        'label'   => __('Newsletter Title', 'hot-news'),
        'section' => 'hot_news_footer',
        'type'    => 'text',
    ));

    $wp_customize->add_setting('hot_news_newsletter_description', array(
        'default'           => __('Subscribe to our newsletter to get the latest news and updates.', 'hot-news'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('hot_news_newsletter_description', array(
        'label'   => __('Newsletter Description', 'hot-news'),
        'section' => 'hot_news_footer',
        'type'    => 'textarea',
    ));

    // Color Settings Section
    $wp_customize->add_section('hot_news_colors', array(
        'title'    => __('Color Settings', 'hot-news'),
        'panel'    => 'hot_news_settings',
        'priority' => 50,
    ));

    $wp_customize->add_setting('hot_news_primary_color', array(
        'default'           => '#FF6F61',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hot_news_primary_color', array(
        'label'   => __('Primary Color', 'hot-news'),
        'section' => 'hot_news_colors',
    )));

    $wp_customize->add_setting('hot_news_secondary_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'hot_news_secondary_color', array(
        'label'   => __('Secondary Color', 'hot-news'),
        'section' => 'hot_news_colors',
    )));
}
add_action('customize_register', 'hot_news_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function hot_news_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function hot_news_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function hot_news_customize_preview_js() {
    wp_enqueue_script('hot-news-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), HOT_NEWS_VERSION, true);
}
add_action('customize_preview_init', 'hot_news_customize_preview_js');

/**
 * Output custom CSS based on customizer settings
 */
function hot_news_customizer_css() {
    $primary_color = get_theme_mod('hot_news_primary_color', '#FF6F61');
    $secondary_color = get_theme_mod('hot_news_secondary_color', '#000000');
    
    if ($primary_color !== '#FF6F61' || $secondary_color !== '#000000') {
        ?>
        <style type="text/css">
            :root {
                --primary-color: <?php echo esc_attr($primary_color); ?>;
                --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            }
            
            a { color: <?php echo esc_attr($primary_color); ?>; }
            .nav-bar, .nav-bar .navbar { background: <?php echo esc_attr($primary_color); ?> !important; }
            .back-to-top { background: <?php echo esc_attr($primary_color); ?>; }
            .back-to-top:hover { background: <?php echo esc_attr($secondary_color); ?>; }
            .top-news .tn-title a:hover,
            .cat-news .cn-title a:hover,
            .main-news .mn-title a:hover,
            .single-news .sn-slider .sn-title a:hover { color: <?php echo esc_attr($primary_color); ?>; }
            .tab-news .nav.nav-pills .nav-link:hover,
            .tab-news .nav.nav-pills .nav-link.active { background: <?php echo esc_attr($primary_color); ?>; }
            .hot-news-badge { background: linear-gradient(45deg, <?php echo esc_attr($primary_color); ?>, <?php echo esc_attr($primary_color); ?>80); }
            .page-numbers:hover,
            .page-numbers.current { background: <?php echo esc_attr($primary_color); ?>; border-color: <?php echo esc_attr($primary_color); ?>; }
        </style>
        <?php
    }
}
add_action('wp_head', 'hot_news_customizer_css');
