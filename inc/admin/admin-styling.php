<?php
/**
 * WordPress Admin Styling Customization
 * Custom styling for wp-admin interface
 *
 * @package Hot_News
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Hot_News_Admin_Styling
{
    public function __construct()
    {
        // Admin styling
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_styles'));
        
        // Admin customizations
        add_action('admin_head', array($this, 'admin_custom_styles'));
        add_action('login_head', array($this, 'login_custom_styles'));
        
        // Admin footer customization
        add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
        add_filter('update_footer', array($this, 'custom_admin_footer_version'), 11);
        
        // Login page customizations
        add_filter('login_headerurl', array($this, 'custom_login_logo_url'));
        add_filter('login_headertitle', array($this, 'custom_login_logo_title'));
        
        // Dashboard widgets
        add_action('wp_dashboard_setup', array($this, 'add_custom_dashboard_widget'));
        
        // Admin bar customization
        add_action('admin_bar_menu', array($this, 'customize_admin_bar'), 999);
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles()
    {
        wp_enqueue_style(
            'hot-news-admin-style',
            get_template_directory_uri() . '/assets/css/admin-style.css',
            array(),
            HOT_NEWS_VERSION
        );
        
        wp_enqueue_style(
            'hot-news-admin-utilities',
            get_template_directory_uri() . '/assets/css/admin-utilities.css',
            array('hot-news-admin-style'),
            HOT_NEWS_VERSION
        );
    }

    /**
     * Enqueue login styles
     */
    public function enqueue_login_styles()
    {
        wp_enqueue_style(
            'hot-news-login-style',
            get_template_directory_uri() . '/assets/css/login-style.css',
            array(),
            HOT_NEWS_VERSION
        );
    }

    /**
     * Add custom styles to admin head
     */
    public function admin_custom_styles()
    {
        ?>
        <style>
        /* Hot News Admin Theme Colors */
        :root {
            --hot-news-primary: #d63384;
            --hot-news-primary-dark: #b02a5b;
            --hot-news-secondary: #fd7e14;
            --hot-news-success: #198754;
            --hot-news-danger: #dc3545;
            --hot-news-warning: #ffc107;
            --hot-news-info: #0dcaf0;
            --hot-news-light: #f8f9fa;
            --hot-news-dark: #212529;
        }

        /* Admin Bar */
        #wpadminbar {
            background: linear-gradient(135deg, var(--hot-news-primary) 0%, var(--hot-news-primary-dark) 100%);
            box-shadow: 0 2px 10px rgba(214, 51, 132, 0.3);
        }

        #wpadminbar .ab-top-menu > li.hover > .ab-item,
        #wpadminbar .ab-top-menu > li:hover > .ab-item,
        #wpadminbar .ab-top-menu > li > .ab-item:focus,
        #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        /* Admin Menu */
        #adminmenu,
        #adminmenuback,
        #adminmenuwrap {
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        }

        #adminmenu .wp-submenu {
            background: #34495e;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        #adminmenu li.current a.menu-top,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
        #adminmenu a.current:hover div.wp-menu-image:before {
            background: var(--hot-news-primary);
            color: #fff;
            box-shadow: inset 4px 0 0 var(--hot-news-secondary);
        }

        #adminmenu .wp-menu-arrow {
            border-right-color: var(--hot-news-primary);
        }

        #adminmenu li a:hover,
        #adminmenu li.opensub > a.menu-top,
        #adminmenu li > a.menu-top:focus {
            background: rgba(214, 51, 132, 0.1);
            color: #fff;
        }

        /* Menu Icons with Brand Colors */
        #adminmenu div.wp-menu-image:before {
            color: var(--hot-news-secondary);
            transition: all 0.3s ease;
        }

        #adminmenu li.current div.wp-menu-image:before,
        #adminmenu li.wp-has-current-submenu div.wp-menu-image:before,
        #adminmenu li:hover div.wp-menu-image:before {
            color: #fff;
            transform: scale(1.1);
        }

        /* Header */
        .wp-admin #wpwrap {
            background: #f8f9fa;
        }

        .wp-admin .wrap h1,
        .wp-admin .wrap h2 {
            color: var(--hot-news-primary);
            font-weight: 600;
        }

        /* Buttons */
        .button-primary {
            background: var(--hot-news-primary);
            border-color: var(--hot-news-primary-dark);
            text-shadow: none;
            box-shadow: 0 2px 4px rgba(214, 51, 132, 0.3);
        }

        .button-primary:hover,
        .button-primary:focus {
            background: var(--hot-news-primary-dark);
            border-color: var(--hot-news-primary-dark);
            box-shadow: 0 4px 8px rgba(214, 51, 132, 0.4);
        }

        /* Links */
        .wp-admin a {
            color: var(--hot-news-primary);
        }

        .wp-admin a:hover,
        .wp-admin a:active,
        .wp-admin a:focus {
            color: var(--hot-news-primary-dark);
        }

        /* Form Elements */
        .wp-admin input[type="text"]:focus,
        .wp-admin input[type="email"]:focus,
        .wp-admin input[type="url"]:focus,
        .wp-admin input[type="password"]:focus,
        .wp-admin textarea:focus,
        .wp-admin select:focus {
            border-color: var(--hot-news-primary);
            box-shadow: 0 0 0 1px var(--hot-news-primary);
        }

        /* Dashboard Widgets */
        .postbox .hndle {
            background: linear-gradient(135deg, var(--hot-news-light) 0%, #e9ecef 100%);
            border-bottom: 2px solid var(--hot-news-primary);
            color: var(--hot-news-dark);
            font-weight: 600;
        }

        /* Notifications */
        .notice.notice-success,
        .notice.updated {
            border-left-color: var(--hot-news-success);
            background: #d4edda;
        }

        .notice.notice-warning {
            border-left-color: var(--hot-news-warning);
            background: #fff3cd;
        }

        .notice.notice-error {
            border-left-color: var(--hot-news-danger);
            background: #f8d7da;
        }

        /* Responsive Design */
        @media screen and (max-width: 782px) {
            #adminmenu {
                background: var(--hot-news-primary);
            }
        }

        /* Custom Dashboard Widget Styling */
        #hot-news-dashboard-widget .hot-news-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        #hot-news-dashboard-widget .stat-card {
            background: linear-gradient(135deg, var(--hot-news-primary) 0%, var(--hot-news-primary-dark) 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(214, 51, 132, 0.3);
        }

        #hot-news-dashboard-widget .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #hot-news-dashboard-widget .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
        </style>
        <?php
    }

    /**
     * Add custom styles to login head
     */
    public function login_custom_styles()
    {
        ?>
        <style>
        /* Hot News Login Page Styling */
        body.login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
        }

        body.login:before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('<?php echo get_template_directory_uri(); ?>/assets/images/login-bg.jpg') center/cover;
            opacity: 0.1;
            z-index: -1;
        }

        .login #login {
            padding: 8% 0 0;
        }

        .login form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            padding: 40px;
            border: none;
        }

        .login h1 a {
            background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            width: 200px;
            height: 80px;
            margin: 0 auto 30px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
        }

        .login form .input,
        .login input[type="text"],
        .login input[type="password"],
        .login input[type="email"] {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .login form .input:focus,
        .login input[type="text"]:focus,
        .login input[type="password"]:focus,
        .login input[type="email"]:focus {
            background: #fff;
            border-color: #d63384;
            box-shadow: 0 0 0 3px rgba(214, 51, 132, 0.1);
            outline: none;
        }

        .login .button-primary {
            background: linear-gradient(135deg, #d63384 0%, #b02a5b 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            text-shadow: none;
            box-shadow: 0 4px 15px rgba(214, 51, 132, 0.4);
            transition: all 0.3s ease;
            width: 100%;
        }

        .login .button-primary:hover,
        .login .button-primary:focus {
            background: linear-gradient(135deg, #b02a5b 0%, #d63384 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214, 51, 132, 0.5);
        }

        .login #nav a,
        .login #backtoblog a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .login #nav a:hover,
        .login #backtoblog a:hover {
            color: #fd7e14;
        }

        .login .message {
            background: rgba(212, 237, 218, 0.9);
            border: 1px solid rgba(25, 135, 84, 0.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .login #login_error {
            background: rgba(248, 215, 218, 0.9);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        /* Loading Animation */
        .login form .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 15px;
            width: 20px;
            height: 20px;
            border: 2px solid #d63384;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        </style>
        <?php
    }

    /**
     * Custom admin footer
     */
    public function custom_admin_footer()
    {
        $theme_info = wp_get_theme();
        return sprintf(
            '<span id="footer-thankyou">Được phát triển bởi <strong>%s</strong> | Theme: <strong>%s v%s</strong></span>',
            '<a href="' . home_url() . '" target="_blank">Hot News Team</a>',
            $theme_info->get('Name'),
            $theme_info->get('Version')
        );
    }

    /**
     * Custom admin footer version
     */
    public function custom_admin_footer_version()
    {
        return 'WordPress ' . get_bloginfo('version') . ' | Hot News Admin';
    }

    /**
     * Custom login logo URL
     */
    public function custom_login_logo_url()
    {
        return home_url();
    }

    /**
     * Custom login logo title
     */
    public function custom_login_logo_title()
    {
        return get_bloginfo('name') . ' - ' . get_bloginfo('description');
    }

    /**
     * Add custom dashboard widget
     */
    public function add_custom_dashboard_widget()
    {
        wp_add_dashboard_widget(
            'hot-news-dashboard-widget',
            '🔥 Hot News Dashboard',
            array($this, 'dashboard_widget_content')
        );
    }

    /**
     * Dashboard widget content
     */
    public function dashboard_widget_content()
    {
        $post_count = wp_count_posts('post');
        $total_posts = $post_count->publish;
        $total_comments = wp_count_comments()->approved;
        
        // Get theme options
        $theme_options = get_option('hot_news_options', array());
        $contact_email = isset($theme_options['contact_email']) ? $theme_options['contact_email'] : '';
        
        ?>
        <div class="hot-news-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_posts); ?></div>
                <div class="stat-label">Bài viết</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_comments); ?></div>
                <div class="stat-label">Bình luận</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo date('d/m'); ?></div>
                <div class="stat-label">Hôm nay</div>
            </div>
        </div>
        
        <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; margin-top: 15px;">
            <h4 style="margin: 0 0 10px; color: #d63384;">
                <i class="dashicons dashicons-admin-settings"></i> Cài đặt nhanh
            </h4>
            <p style="margin: 10px 0;">
                <a href="<?php echo admin_url('appearance.php?page=hot-news-settings'); ?>" class="button button-primary">
                    <i class="dashicons dashicons-admin-appearance"></i> Cài đặt Theme
                </a>
                <a href="<?php echo admin_url('post-new.php'); ?>" class="button" style="margin-left: 10px;">
                    <i class="dashicons dashicons-edit"></i> Viết bài mới
                </a>
            </p>
            
            <?php if ($contact_email): ?>
            <p style="margin: 15px 0 5px; font-size: 12px; color: #666;">
                <i class="dashicons dashicons-email"></i> 
                Liên hệ: <strong><?php echo esc_html($contact_email); ?></strong>
            </p>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Customize admin bar
     */
    public function customize_admin_bar($wp_admin_bar)
    {
        // Add custom link to theme settings
        $wp_admin_bar->add_node(array(
            'id'    => 'hot-news-settings',
            'title' => '<span class="ab-icon dashicons dashicons-admin-appearance"></span> Hot News',
            'href'  => admin_url('appearance.php?page=hot-news-settings'),
            'meta'  => array(
                'title' => 'Hot News Theme Settings',
            ),
        ));
    }
}

// Initialize admin styling
new Hot_News_Admin_Styling();
