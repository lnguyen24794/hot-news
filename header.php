<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hot_News
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'hot-news'); ?></a>

    <!-- Top Bar Start -->
    <div class="top-bar">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="tb-contact">
                        <?php
                        $contact_email = get_theme_mod('hot_news_contact_email', 'info@example.com');
$contact_phone = get_theme_mod('hot_news_contact_phone', '+123 456 7890');

if ($contact_email) : ?>
                            <p><i class="fas fa-envelope"></i><?php echo esc_html($contact_email); ?></p>
                        <?php endif;

if ($contact_phone) : ?>
                            <p><i class="fas fa-phone-alt"></i><?php echo esc_html($contact_phone); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tb-menu">
                        <?php
wp_nav_menu(array(
    'theme_location' => 'top-menu',
    'menu_id'        => 'top-menu',
    'container'      => false,
    'items_wrap'     => '%3$s',
    'depth'          => 1,
    'fallback_cb'    => false,
));

// Fallback menu if no menu is assigned
if (!has_nav_menu('top-menu')) : ?>
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'hot-news'); ?></a>
                            <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy', 'hot-news'); ?></a>
                            <a href="<?php echo esc_url(home_url('/contact')); ?>"><?php esc_html_e('Contact', 'hot-news'); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Top Bar End -->

    <!-- Breaking News Display -->
    <?php hot_news_display_breaking_news(); ?>
    
    <!-- Brand Start -->
    <div class="brand">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4">
                    <div class="b-logo">
                        <?php
if (has_custom_logo()) {
    the_custom_logo();
} else {
    ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <h1 class="site-title"><?php bloginfo('name'); ?></h1>
                            </a>
                            <?php
    $description = get_bloginfo('description', 'display');
    if ($description || is_customize_preview()) :
        ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif;
}
?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-4">
                    <div class="b-ads">
                        <?php
$header_ad_image = get_theme_mod('hot_news_header_ad_image');
$header_ad_url = get_theme_mod('hot_news_header_ad_url');

if ($header_ad_image) :
    if ($header_ad_url) : ?>
                                <a href="<?php echo esc_url($header_ad_url); ?>" target="_blank" rel="noopener">
                                    <img src="<?php echo esc_url($header_ad_image); ?>" alt="<?php esc_attr_e('Advertisement', 'hot-news'); ?>">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url($header_ad_image); ?>" alt="<?php esc_attr_e('Advertisement', 'hot-news'); ?>">
                            <?php endif;
else : ?>
                            <!-- Default advertisement placeholder -->
                            <div class="ad-placeholder" style="background: #f5f5f5; padding: 20px; text-align: center; color: #666;">
                                <p><?php esc_html_e('Advertisement Space', 'hot-news'); ?></p>
                                <small><?php esc_html_e('Configure in Customizer > Hot News Settings > Header Settings', 'hot-news'); ?></small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="b-search">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Brand End -->

    <!-- Nav Bar Start -->
    <div class="nav-bar">
        <div class="container">
            <nav class="navbar navbar-expand-md bg-dark navbar-dark">
                <a href="#" class="navbar-brand"><?php esc_html_e('MENU', 'hot-news'); ?></a>
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav mr-auto">
                        <?php
wp_nav_menu(array(
    'theme_location'  => 'primary',
    'menu_id'         => 'primary-menu',
    'container'       => false,
    'items_wrap'      => '%3$s',
    'walker'          => new WP_Bootstrap_Navwalker(),
    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
));
?>
                    </div>
                    <div class="social ml-auto">
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
            </nav>
        </div>
    </div>
    <!-- Nav Bar End -->
