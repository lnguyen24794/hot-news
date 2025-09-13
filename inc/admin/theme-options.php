<?php
/**
 * Hot News Theme Options Page
 *
 * @package Hot_News
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Hot_News_Theme_Options
{
    private $options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_plugin_page()
    {
        add_theme_page(
            'Hot News Settings', // Page title
            'Theme Settings',    // Menu title
            'manage_options',    // Capability
            'hot-news-settings', // Menu slug
            array($this, 'create_admin_page') // Callback
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option('hot_news_options');
        ?>
        <div class="wrap">
            <h1><i class="dashicons dashicons-admin-settings"></i> Hot News Theme Settings</h1>
            <?php settings_errors(); ?>
            
            <div class="hot-news-admin-wrapper">
                <nav class="nav-tab-wrapper">
                    <a href="#contact-info" class="nav-tab nav-tab-active">Thông tin liên hệ</a>
                    <a href="#social-networks" class="nav-tab">Mạng xã hội</a>
                    <a href="#general" class="nav-tab">Tổng quan</a>
                </nav>

                <form method="post" action="options.php">
                    <?php
                    settings_fields('hot_news_option_group');
                    do_settings_sections('hot_news_option_group');
                    ?>

                    <!-- Contact Info Tab -->
                    <div id="contact-info" class="tab-content active">
                        <div class="settings-section">
                            <h2>Thông tin liên hệ</h2>
                            <p>Cập nhật thông tin liên hệ hiển thị trên website của bạn.</p>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="contact_email">Email liên hệ</label>
                                    </th>
                                    <td>
                                        <input type="email" id="contact_email" name="hot_news_options[contact_email]" 
                                               value="<?php echo isset($this->options['contact_email']) ? esc_attr($this->options['contact_email']) : ''; ?>" 
                                               class="regular-text" placeholder="contact@example.com" />
                                        <p class="description">Email hiển thị trong footer và trang liên hệ</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="contact_phone">Số điện thoại</label>
                                    </th>
                                    <td>
                                        <input type="tel" id="contact_phone" name="hot_news_options[contact_phone]" 
                                               value="<?php echo isset($this->options['contact_phone']) ? esc_attr($this->options['contact_phone']) : ''; ?>" 
                                               class="regular-text" placeholder="+84 123 456 789" />
                                        <p class="description">Số điện thoại liên hệ</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="contact_address">Địa chỉ</label>
                                    </th>
                                    <td>
                                        <textarea id="contact_address" name="hot_news_options[contact_address]" 
                                                  class="large-text" rows="3" 
                                                  placeholder="123 Đường ABC, Quận 1, TP.HCM"><?php echo isset($this->options['contact_address']) ? esc_textarea($this->options['contact_address']) : ''; ?></textarea>
                                        <p class="description">Địa chỉ công ty/tổ chức</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="business_hours">Giờ làm việc</label>
                                    </th>
                                    <td>
                                        <input type="text" id="business_hours" name="hot_news_options[business_hours]" 
                                               value="<?php echo isset($this->options['business_hours']) ? esc_attr($this->options['business_hours']) : ''; ?>" 
                                               class="regular-text" placeholder="Thứ 2 - Thứ 6: 8:00 - 17:30" />
                                        <p class="description">Thời gian hoạt động</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Social Networks Tab -->
                    <div id="social-networks" class="tab-content">
                        <div class="settings-section">
                            <h2>Mạng xã hội</h2>
                            <p>Thêm đường link đến các trang mạng xã hội. Chỉ những mạng xã hội có URL sẽ được hiển thị.</p>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="facebook_url">
                                            <i class="fab fa-facebook-f"></i> Facebook
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="facebook_url" name="hot_news_options[facebook_url]" 
                                               value="<?php echo isset($this->options['facebook_url']) ? esc_attr($this->options['facebook_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://facebook.com/your-page" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="twitter_url">
                                            <i class="fab fa-twitter"></i> Twitter
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="twitter_url" name="hot_news_options[twitter_url]" 
                                               value="<?php echo isset($this->options['twitter_url']) ? esc_attr($this->options['twitter_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://twitter.com/your-handle" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="instagram_url">
                                            <i class="fab fa-instagram"></i> Instagram
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="instagram_url" name="hot_news_options[instagram_url]" 
                                               value="<?php echo isset($this->options['instagram_url']) ? esc_attr($this->options['instagram_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://instagram.com/your-account" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="linkedin_url">
                                            <i class="fab fa-linkedin-in"></i> LinkedIn
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="linkedin_url" name="hot_news_options[linkedin_url]" 
                                               value="<?php echo isset($this->options['linkedin_url']) ? esc_attr($this->options['linkedin_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://linkedin.com/company/your-company" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="youtube_url">
                                            <i class="fab fa-youtube"></i> YouTube
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="youtube_url" name="hot_news_options[youtube_url]" 
                                               value="<?php echo isset($this->options['youtube_url']) ? esc_attr($this->options['youtube_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://youtube.com/channel/your-channel" />
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="tiktok_url">
                                            <i class="fab fa-tiktok"></i> TikTok
                                        </label>
                                    </th>
                                    <td>
                                        <input type="url" id="tiktok_url" name="hot_news_options[tiktok_url]" 
                                               value="<?php echo isset($this->options['tiktok_url']) ? esc_attr($this->options['tiktok_url']) : ''; ?>" 
                                               class="regular-text" placeholder="https://tiktok.com/@your-account" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- General Tab -->
                    <div id="general" class="tab-content">
                        <div class="settings-section">
                            <h2>Cài đặt tổng quan</h2>
                            <p>Các thiết lập chung cho theme.</p>
                            
                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="site_description">Mô tả website</label>
                                    </th>
                                    <td>
                                        <textarea id="site_description" name="hot_news_options[site_description]" 
                                                  class="large-text" rows="3" 
                                                  placeholder="Mô tả ngắn gọn về website của bạn..."><?php echo isset($this->options['site_description']) ? esc_textarea($this->options['site_description']) : ''; ?></textarea>
                                        <p class="description">Mô tả này có thể được sử dụng trong các meta tag hoặc footer</p>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th scope="row">
                                        <label for="copyright_text">Bản quyền</label>
                                    </th>
                                    <td>
                                        <input type="text" id="copyright_text" name="hot_news_options[copyright_text]" 
                                               value="<?php echo isset($this->options['copyright_text']) ? esc_attr($this->options['copyright_text']) : ''; ?>" 
                                               class="large-text" placeholder="© 2025 Hot News. All rights reserved." />
                                        <p class="description">Text bản quyền hiển thị ở footer</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php submit_button('Lưu thay đổi', 'primary', 'submit', true, array('id' => 'submit-btn')); ?>
                </form>
            </div>
        </div>

        <style>
        .hot-news-admin-wrapper {
            max-width: 1200px;
            margin: 20px 0;
        }
        
        .nav-tab-wrapper {
            border-bottom: 1px solid #ccc;
            margin: 0 0 20px 0;
            padding: 0;
        }
        
        .nav-tab {
            position: relative;
            float: left;
            border: 1px solid #ccc;
            border-bottom: none;
            margin: 0 5px -1px 0;
            padding: 8px 12px;
            font-size: 14px;
            line-height: 1.71428571;
            color: #555;
            text-decoration: none;
            background: #f1f1f1;
        }
        
        .nav-tab:hover,
        .nav-tab:focus {
            background-color: #fff;
            color: #464646;
        }
        
        .nav-tab-active {
            background: #fff;
            border-bottom: 1px solid #fff;
            color: #000;
        }
        
        .tab-content {
            display: none;
            background: #fff;
            border: 1px solid #ccc;
            border-top: none;
            padding: 20px;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .settings-section h2 {
            margin-top: 0;
            color: #23282d;
        }
        
        .form-table th label i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }
        
        #submit-btn {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px 20px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Tab switching
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and content
                $('.nav-tab').removeClass('nav-tab-active');
                $('.tab-content').removeClass('active');
                
                // Add active class to clicked tab
                $(this).addClass('nav-tab-active');
                
                // Show corresponding content
                var target = $(this).attr('href');
                $(target).addClass('active');
            });
        });
        </script>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'hot_news_option_group', // Option group
            'hot_news_options', // Option name
            array($this, 'sanitize') // Sanitize callback
        );
    }

    public function sanitize($input)
    {
        $new_input = array();
        
        // Contact info
        if (isset($input['contact_email']))
            $new_input['contact_email'] = sanitize_email($input['contact_email']);
            
        if (isset($input['contact_phone']))
            $new_input['contact_phone'] = sanitize_text_field($input['contact_phone']);
            
        if (isset($input['contact_address']))
            $new_input['contact_address'] = sanitize_textarea_field($input['contact_address']);
            
        if (isset($input['business_hours']))
            $new_input['business_hours'] = sanitize_text_field($input['business_hours']);

        // Social networks
        $social_networks = ['facebook_url', 'twitter_url', 'instagram_url', 'linkedin_url', 'youtube_url', 'tiktok_url'];
        foreach ($social_networks as $network) {
            if (isset($input[$network]))
                $new_input[$network] = esc_url_raw($input[$network]);
        }

        // General settings
        if (isset($input['site_description']))
            $new_input['site_description'] = sanitize_textarea_field($input['site_description']);
            
        if (isset($input['copyright_text']))
            $new_input['copyright_text'] = sanitize_text_field($input['copyright_text']);

        return $new_input;
    }
    
    public function enqueue_admin_scripts($hook)
    {
        if ('appearance_page_hot-news-settings' !== $hook) {
            return;
        }
        
        // Enqueue FontAwesome for social media icons
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    }
}

// Initialize the options page
if (is_admin()) {
    $hot_news_options = new Hot_News_Theme_Options();
}

// Helper functions to get theme options
function hot_news_get_option($option_name, $default = '')
{
    $options = get_option('hot_news_options');
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

function hot_news_get_contact_info($field = null)
{
    $contact_info = array(
        'email' => hot_news_get_option('contact_email', 'contact@hotnews.vn'),
        'phone' => hot_news_get_option('contact_phone', '+84 123 456 789'),
        'address' => hot_news_get_option('contact_address', '123 Đường Báo Chí, Quận 1, TP.HCM'),
        'business_hours' => hot_news_get_option('business_hours', 'Thứ 2 - Thứ 6: 8:00 - 17:30')
    );
    
    if ($field && isset($contact_info[$field])) {
        return $contact_info[$field];
    }
    
    return $contact_info;
}

function hot_news_get_social_networks($only_filled = true)
{
    $social_networks = array(
        'facebook' => array(
            'url' => hot_news_get_option('facebook_url'),
            'icon' => 'fab fa-facebook-f',
            'name' => 'Facebook'
        ),
        'twitter' => array(
            'url' => hot_news_get_option('twitter_url'),
            'icon' => 'fab fa-twitter',
            'name' => 'Twitter'
        ),
        'instagram' => array(
            'url' => hot_news_get_option('instagram_url'),
            'icon' => 'fab fa-instagram',
            'name' => 'Instagram'
        ),
        'linkedin' => array(
            'url' => hot_news_get_option('linkedin_url'),
            'icon' => 'fab fa-linkedin-in',
            'name' => 'LinkedIn'
        ),
        'youtube' => array(
            'url' => hot_news_get_option('youtube_url'),
            'icon' => 'fab fa-youtube',
            'name' => 'YouTube'
        ),
        'tiktok' => array(
            'url' => hot_news_get_option('tiktok_url'),
            'icon' => 'fab fa-tiktok',
            'name' => 'TikTok'
        )
    );
    
    if ($only_filled) {
        return array_filter($social_networks, function($network) {
            return !empty($network['url']);
        });
    }
    
    return $social_networks;
}

// Set default values on theme activation
function hot_news_set_default_options()
{
    $default_options = array(
        'contact_email' => 'contact@hotnews.vn',
        'contact_phone' => '+84 123 456 789',
        'contact_address' => '123 Đường Báo Chí, Quận 1, TP.HCM',
        'business_hours' => 'Thứ 2 - Thứ 6: 8:00 - 17:30',
        'facebook_url' => 'https://facebook.com/hotnews',
        'twitter_url' => 'https://twitter.com/hotnews',
        'instagram_url' => 'https://instagram.com/hotnews',
        'site_description' => 'Trang tin tức hàng đầu Việt Nam, cập nhật nhanh chóng và chính xác những thông tin mới nhất.',
        'copyright_text' => '© 2025 Hot News. All rights reserved.'
    );
    
    // Only set if options don't exist
    if (!get_option('hot_news_options')) {
        add_option('hot_news_options', $default_options);
    }
}
add_action('after_switch_theme', 'hot_news_set_default_options');
