<?php
/**
 * Google Ads Manager
 * Quản lý quảng cáo Google AdSense cho theme Hot News
 *
 * @package Hot_News
 */

if (!defined('ABSPATH')) {
    exit;
}

class Hot_News_Google_Ads_Manager
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_head', array($this, 'add_adsense_head_code'));
        add_action('wp_footer', array($this, 'add_adsense_footer_code'));
    }

    /**
     * Thêm menu admin
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Quản lý Quảng cáo Google', 'hot-news'),
            __('Google Ads', 'hot-news'),
            'manage_options',
            'hot-news-google-ads',
            array($this, 'options_page'),
            'dashicons-megaphone',
            30
        );
    }

    /**
     * Đăng ký các settings
     */
    public function settings_init()
    {
        // Đăng ký settings group
        register_setting('hot_news_google_ads', 'hot_news_google_ads_options');

        // Section chung
        add_settings_section(
            'hot_news_google_ads_general',
            __('Cài đặt chung', 'hot-news'),
            array($this, 'general_section_callback'),
            'hot_news_google_ads'
        );

        // AdSense Auto Ads
        add_settings_field(
            'auto_ads_enabled',
            __('Bật Auto Ads', 'hot-news'),
            array($this, 'auto_ads_enabled_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_general'
        );

        add_settings_field(
            'adsense_client_id',
            __('AdSense Client ID', 'hot-news'),
            array($this, 'adsense_client_id_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_general'
        );

        add_settings_field(
            'auto_ads_code',
            __('Auto Ads Code', 'hot-news'),
            array($this, 'auto_ads_code_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_general'
        );

        // Homepage Ads
        add_settings_section(
            'hot_news_google_ads_homepage',
            __('Quảng cáo Trang chủ', 'hot-news'),
            array($this, 'homepage_section_callback'),
            'hot_news_google_ads'
        );

        // Header Ad
        add_settings_field(
            'header_ad_code',
            __('Header Advertisement', 'hot-news'),
            array($this, 'header_ad_code_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_homepage'
        );

        // Tab News Ad
        add_settings_field(
            'tab_news_ad_code',
            __('Tab News Advertisement', 'hot-news'),
            array($this, 'tab_news_ad_code_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_homepage'
        );

        // Sidebar Ad
        add_settings_field(
            'sidebar_ad_code',
            __('Sidebar Advertisement', 'hot-news'),
            array($this, 'sidebar_ad_code_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_homepage'
        );

        // Single Post Ads
        add_settings_section(
            'hot_news_google_ads_single',
            __('Quảng cáo Trang bài viết', 'hot-news'),
            array($this, 'single_section_callback'),
            'hot_news_google_ads'
        );

        add_settings_field(
            'single_content_top_ad',
            __('Trên nội dung bài viết', 'hot-news'),
            array($this, 'single_content_top_ad_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_single'
        );

        add_settings_field(
            'single_content_middle_ad',
            __('Giữa nội dung bài viết', 'hot-news'),
            array($this, 'single_content_middle_ad_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_single'
        );

        add_settings_field(
            'single_content_bottom_ad',
            __('Cuối nội dung bài viết', 'hot-news'),
            array($this, 'single_content_bottom_ad_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_single'
        );

        add_settings_field(
            'single_sidebar_ad',
            __('Sidebar bài viết', 'hot-news'),
            array($this, 'single_sidebar_ad_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_single'
        );

        // Archive Ads
        add_settings_section(
            'hot_news_google_ads_archive',
            __('Quảng cáo Trang lưu trữ', 'hot-news'),
            array($this, 'archive_section_callback'),
            'hot_news_google_ads'
        );

        add_settings_field(
            'archive_header_ad',
            __('Header Archive', 'hot-news'),
            array($this, 'archive_header_ad_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_archive'
        );

        add_settings_field(
            'archive_sidebar_ad_1',
            __('Sidebar Banner 1', 'hot-news'),
            array($this, 'archive_sidebar_ad_1_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_archive'
        );

        add_settings_field(
            'archive_sidebar_ad_2',
            __('Sidebar Banner 2', 'hot-news'),
            array($this, 'archive_sidebar_ad_2_callback'),
            'hot_news_google_ads',
            'hot_news_google_ads_archive'
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('toplevel_page_hot-news-google-ads' !== $hook) {
            return;
        }

        wp_enqueue_script('hot-news-google-ads-admin', get_template_directory_uri() . '/assets/js/admin-google-ads.js', array('jquery'), HOT_NEWS_VERSION, true);
        wp_enqueue_style('hot-news-google-ads-admin', get_template_directory_uri() . '/assets/css/admin-google-ads.css', array(), HOT_NEWS_VERSION);
    }

    /**
     * Thêm AdSense code vào head
     */
    public function add_adsense_head_code()
    {
        $options = get_option('hot_news_google_ads_options');

        if (isset($options['auto_ads_enabled']) && $options['auto_ads_enabled'] && !empty($options['adsense_client_id'])) {
            echo "\n<!-- Google AdSense Auto Ads -->\n";
            echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . esc_attr($options['adsense_client_id']) . '" crossorigin="anonymous"></script>' . "\n";

            if (!empty($options['auto_ads_code'])) {
                echo "<!-- Custom Auto Ads Code -->\n";
                echo '<script>' . "\n";
                echo wp_unslash($options['auto_ads_code']) . "\n";
                echo '</script>' . "\n";
            }
            echo "<!-- End Google AdSense -->\n\n";
        }
    }

    /**
     * Thêm AdSense code vào footer
     */
    public function add_adsense_footer_code()
    {
        $options = get_option('hot_news_google_ads_options');

        if (isset($options['auto_ads_enabled']) && $options['auto_ads_enabled']) {
            echo "\n<!-- AdSense Auto Ads Script -->\n";
            echo '<script>' . "\n";
            echo '(adsbygoogle = window.adsbygoogle || []).push({});' . "\n";
            echo '</script>' . "\n";
        }
    }

    /**
     * Callback functions
     */
    public function general_section_callback()
    {
        echo '<p>' . __('Cài đặt chung cho Google AdSense. Bạn có thể chọn Auto Ads (tự động) hoặc Manual Ads (thủ công từng vị trí).', 'hot-news') . '</p>';
    }

    public function homepage_section_callback()
    {
        echo '<p>' . __('Cài đặt quảng cáo cho trang chủ. Các vị trí quảng cáo sẽ được hiển thị tại các khu vực khác nhau trên trang chủ.', 'hot-news') . '</p>';
    }

    public function single_section_callback()
    {
        echo '<p>' . __('Cài đặt quảng cáo cho trang bài viết chi tiết. Quảng cáo có thể được đặt trước, giữa và sau nội dung bài viết.', 'hot-news') . '</p>';
    }

    public function archive_section_callback()
    {
        echo '<p>' . __('Cài đặt quảng cáo cho các trang lưu trữ (danh mục, thẻ, tác giả, ngày tháng).', 'hot-news') . '</p>';
    }

    public function auto_ads_enabled_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $checked = isset($options['auto_ads_enabled']) ? $options['auto_ads_enabled'] : 0;
        ?>
        <label class="ads-toggle-switch">
            <input type="checkbox" name="hot_news_google_ads_options[auto_ads_enabled]" value="1" <?php checked(1, $checked); ?> />
            <span class="ads-toggle-slider"></span>
        </label>
        <p class="description">
            <?php _e('Bật để sử dụng Google Auto Ads (tự động hiển thị quảng cáo). Tắt để sử dụng Manual Ads (quản lý thủ công từng vị trí).', 'hot-news'); ?>
        </p>
        <?php
    }

    public function adsense_client_id_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['adsense_client_id']) ? $options['adsense_client_id'] : '';
        ?>
        <input type="text" name="hot_news_google_ads_options[adsense_client_id]" value="<?php echo esc_attr($value); ?>" 
               class="regular-text" placeholder="ca-pub-XXXXXXXXXXXXXXXX" />
        <p class="description">
            <?php _e('Nhập AdSense Client ID của bạn (ví dụ: ca-pub-1234567890123456). Tìm trong Google AdSense > Tài khoản > Thông tin tài khoản.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function auto_ads_code_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['auto_ads_code']) ? $options['auto_ads_code'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[auto_ads_code]" rows="5" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã JavaScript tùy chỉnh cho Auto Ads (tùy chọn). Ví dụ: cấu hình vị trí, loại quảng cáo...', 'hot-news'); ?>
        </p>
        <?php
    }

    // Header Ad
    public function header_ad_code_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['header_ad_code']) ? $options['header_ad_code'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[header_ad_code]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho vị trí header (banner phía trên). Kích thước khuyến nghị: 728x90 hoặc 970x90.', 'hot-news'); ?>
        </p>
        <?php
    }

    // Tab News Ad
    public function tab_news_ad_code_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['tab_news_ad_code']) ? $options['tab_news_ad_code'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[tab_news_ad_code]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho khu vực Tab News trên trang chủ. Kích thước khuyến nghị: 300x250 hoặc 336x280.', 'hot-news'); ?>
        </p>
        <?php
    }

    // Sidebar Ad
    public function sidebar_ad_code_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['sidebar_ad_code']) ? $options['sidebar_ad_code'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[sidebar_ad_code]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho sidebar. Kích thước khuyến nghị: 300x250, 300x600 hoặc responsive.', 'hot-news'); ?>
        </p>
        <?php
    }

    // Single Post Ads
    public function single_content_top_ad_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['single_content_top_ad']) ? $options['single_content_top_ad'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[single_content_top_ad]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense hiển thị trên đầu nội dung bài viết. Kích thước khuyến nghị: 728x90 hoặc responsive.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function single_content_middle_ad_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['single_content_middle_ad']) ? $options['single_content_middle_ad'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[single_content_middle_ad]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense hiển thị giữa nội dung bài viết (sau đoạn thứ 2). Kích thước khuyến nghị: 300x250 hoặc 728x90.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function single_content_bottom_ad_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['single_content_bottom_ad']) ? $options['single_content_bottom_ad'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[single_content_bottom_ad]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense hiển thị cuối nội dung bài viết. Kích thước khuyến nghị: 728x90 hoặc 300x250.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function single_sidebar_ad_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['single_sidebar_ad']) ? $options['single_sidebar_ad'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[single_sidebar_ad]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho sidebar trang bài viết. Kích thước khuyến nghị: 300x250 hoặc 300x600.', 'hot-news'); ?>
        </p>
        <?php
    }

    // Archive Ads
    public function archive_header_ad_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['archive_header_ad']) ? $options['archive_header_ad'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[archive_header_ad]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho header trang lưu trữ. Kích thước khuyến nghị: 728x90.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function archive_sidebar_ad_1_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['archive_sidebar_ad_1']) ? $options['archive_sidebar_ad_1'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[archive_sidebar_ad_1]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho banner 1 trong sidebar trang lưu trữ.', 'hot-news'); ?>
        </p>
        <?php
    }

    public function archive_sidebar_ad_2_callback()
    {
        $options = get_option('hot_news_google_ads_options');
        $value = isset($options['archive_sidebar_ad_2']) ? $options['archive_sidebar_ad_2'] : '';
        ?>
        <textarea name="hot_news_google_ads_options[archive_sidebar_ad_2]" rows="8" cols="50" class="large-text"><?php echo esc_textarea($value); ?></textarea>
        <p class="description">
            <?php _e('Mã AdSense cho banner 2 trong sidebar trang lưu trữ.', 'hot-news'); ?>
        </p>
        <?php
    }

    /**
     * Options page HTML
     */
    public function options_page()
    {
        ?>
        <div class="wrap hot-news-ads-admin">
            <h1><?php _e('Quản lý Quảng cáo Google', 'hot-news'); ?></h1>
            
            <div class="ads-admin-header">
                <div class="ads-mode-status">
                    <?php
                    $options = get_option('hot_news_google_ads_options');
        $auto_ads_enabled = isset($options['auto_ads_enabled']) && $options['auto_ads_enabled'];

        if ($auto_ads_enabled) {
            echo '<span class="ads-mode auto-mode"><i class="dashicons dashicons-admin-generic"></i> ' . __('Chế độ: Auto Ads', 'hot-news') . '</span>';
            echo '<p class="ads-mode-desc">' . __('Google sẽ tự động hiển thị quảng cáo tại các vị trí phù hợp trên website của bạn.', 'hot-news') . '</p>';
        } else {
            echo '<span class="ads-mode manual-mode"><i class="dashicons dashicons-admin-tools"></i> ' . __('Chế độ: Manual Ads', 'hot-news') . '</span>';
            echo '<p class="ads-mode-desc">' . __('Bạn có thể tùy chỉnh và đặt quảng cáo tại từng vị trí cụ thể.', 'hot-news') . '</p>';
        }
        ?>
                </div>
            </div>

            <form action="options.php" method="post">
                <?php
                settings_fields('hot_news_google_ads');
        ?>
                
                <div class="ads-tabs-wrapper">
                    <nav class="nav-tab-wrapper ads-nav-tabs">
                        <a href="#general" class="nav-tab nav-tab-active"><?php _e('Cài đặt chung', 'hot-news'); ?></a>
                        <a href="#homepage" class="nav-tab"><?php _e('Trang chủ', 'hot-news'); ?></a>
                        <a href="#single" class="nav-tab"><?php _e('Bài viết', 'hot-news'); ?></a>
                        <a href="#archive" class="nav-tab"><?php _e('Lưu trữ', 'hot-news'); ?></a>
                    </nav>

                    <div class="tab-content">
                        <!-- General Tab -->
                        <div id="general" class="ads-tab-pane active">
                            <table class="form-table" role="presentation">
                                <?php do_settings_fields('hot_news_google_ads', 'hot_news_google_ads_general'); ?>
                            </table>
                        </div>

                        <!-- Homepage Tab -->
                        <div id="homepage" class="ads-tab-pane">
                            <table class="form-table" role="presentation">
                                <?php do_settings_fields('hot_news_google_ads', 'hot_news_google_ads_homepage'); ?>
                            </table>
                        </div>

                        <!-- Single Tab -->
                        <div id="single" class="ads-tab-pane">
                            <table class="form-table" role="presentation">
                                <?php do_settings_fields('hot_news_google_ads', 'hot_news_google_ads_single'); ?>
                            </table>
                        </div>

                        <!-- Archive Tab -->
                        <div id="archive" class="ads-tab-pane">
                            <table class="form-table" role="presentation">
                                <?php do_settings_fields('hot_news_google_ads', 'hot_news_google_ads_archive'); ?>
                            </table>
                        </div>
                    </div>
                </div>

                <?php submit_button(__('Lưu cài đặt', 'hot-news'), 'primary', 'submit', true, array('class' => 'ads-save-button')); ?>
            </form>

            <div class="ads-help-section">
                <h3><?php _e('Hướng dẫn sử dụng', 'hot-news'); ?></h3>
                <div class="ads-help-grid">
                    <div class="ads-help-card">
                        <h4><?php _e('Auto Ads', 'hot-news'); ?></h4>
                        <ul>
                            <li><?php _e('Bật Auto Ads và nhập AdSense Client ID', 'hot-news'); ?></li>
                            <li><?php _e('Google sẽ tự động đặt quảng cáo', 'hot-news'); ?></li>
                            <li><?php _e('Không cần cấu hình từng vị trí', 'hot-news'); ?></li>
                        </ul>
                    </div>
                    <div class="ads-help-card">
                        <h4><?php _e('Manual Ads', 'hot-news'); ?></h4>
                        <ul>
                            <li><?php _e('Tắt Auto Ads', 'hot-news'); ?></li>
                            <li><?php _e('Nhập mã AdSense cho từng vị trí', 'hot-news'); ?></li>
                            <li><?php _e('Kiểm soát hoàn toàn vị trí và kích thước', 'hot-news'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Khởi tạo class
new Hot_News_Google_Ads_Manager();

/**
 * Helper functions để hiển thị quảng cáo
 */

/**
 * Hiển thị quảng cáo tại vị trí cụ thể
 */
function hot_news_display_ad($position, $fallback_html = '')
{
    $options = get_option('hot_news_google_ads_options');

    // Kiểm tra Auto Ads
    if (isset($options['auto_ads_enabled']) && $options['auto_ads_enabled']) {
        // Auto Ads được bật, không hiển thị manual ads
        return;
    }

    // Manual Ads - hiển thị mã AdSense tại vị trí cụ thể
    if (isset($options[$position]) && !empty($options[$position])) {
        echo '<div class="hot-news-adsense-container">';
        echo wp_unslash($options[$position]);
        echo '</div>';
    } elseif (!empty($fallback_html)) {
        // Hiển thị fallback nếu không có mã AdSense
        echo $fallback_html;
    }
}

/**
 * Kiểm tra Auto Ads có được bật hay không
 */
function hot_news_is_auto_ads_enabled()
{
    $options = get_option('hot_news_google_ads_options');
    return isset($options['auto_ads_enabled']) && $options['auto_ads_enabled'];
}

/**
 * Lấy AdSense Client ID
 */
function hot_news_get_adsense_client_id()
{
    $options = get_option('hot_news_google_ads_options');
    return isset($options['adsense_client_id']) ? $options['adsense_client_id'] : '';
}

/**
 * Thêm quảng cáo vào nội dung bài viết
 */
function hot_news_add_ads_to_content($content)
{
    // Chỉ áp dụng cho single post
    if (!is_single()) {
        return $content;
    }

    $options = get_option('hot_news_google_ads_options');

    // Nếu Auto Ads được bật, không thêm manual ads vào content
    if (isset($options['auto_ads_enabled']) && $options['auto_ads_enabled']) {
        return $content;
    }

    // Thêm quảng cáo trên nội dung
    if (!empty($options['single_content_top_ad'])) {
        $content = '<div class="hot-news-adsense-container ads-content-top">' . wp_unslash($options['single_content_top_ad']) . '</div>' . $content;
    }

    // Thêm quảng cáo giữa nội dung (sau đoạn thứ 2)
    if (!empty($options['single_content_middle_ad'])) {
        $paragraphs = explode('</p>', $content);
        if (count($paragraphs) > 2) {
            $ad_code = '<div class="hot-news-adsense-container ads-content-middle">' . wp_unslash($options['single_content_middle_ad']) . '</div>';
            array_splice($paragraphs, 2, 0, $ad_code);
            $content = implode('</p>', $paragraphs);
        }
    }

    // Thêm quảng cáo cuối nội dung
    if (!empty($options['single_content_bottom_ad'])) {
        $content .= '<div class="hot-news-adsense-container ads-content-bottom">' . wp_unslash($options['single_content_bottom_ad']) . '</div>';
    }

    return $content;
}
add_filter('the_content', 'hot_news_add_ads_to_content');
