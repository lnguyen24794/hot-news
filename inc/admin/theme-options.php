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
            <!-- Header Section -->
            <div class="container-fluid px-0 mb-4">
                <div class="row no-gutters bg-gradient-primary text-white py-3 px-4">
                    <div class="col">
                        <h1 class="h3 mb-0">
                            <i class="fas fa-cog mr-2"></i>
                            Hot News Theme Settings
                        </h1>
                        <p class="mb-0 small opacity-75">Quản lý cài đặt chủ đề của bạn</p>
                    </div>
                </div>
            </div>
            
            <?php settings_errors(); ?>

            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-bottom-0">
                                <h6 class="card-title mb-0 text-muted">
                                    <i class="fas fa-list mr-2"></i>Cài đặt
                                </h6>
                            </div>
                            <div class="list-group list-group-flush">
                                <a href="#contact-info" class="list-group-item list-group-item-action active" id="contact-tab">
                                    <i class="fas fa-address-card mr-2 text-primary"></i>
                                    Thông tin liên hệ
                                </a>
                                <a href="#social-networks" class="list-group-item list-group-item-action" id="social-tab">
                                    <i class="fas fa-share-alt mr-2 text-success"></i>
                                    Mạng xã hội
                                </a>
                                <a href="#general" class="list-group-item list-group-item-action" id="general-tab">
                                    <i class="fas fa-sliders-h mr-2 text-warning"></i>
                                    Tổng quan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="col-lg-9">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields('hot_news_option_group');
                            do_settings_sections('hot_news_option_group');
                            ?>

                            <!-- Contact Info Tab -->
                            <div id="contact-info" class="tab-content-bootstrap active">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-address-card mr-2"></i>
                                            Thông tin liên hệ
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-4">Cập nhật thông tin liên hệ hiển thị trên website của bạn.</p>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_email" class="font-weight-semibold">
                                                        <i class="fas fa-envelope text-primary mr-2"></i>Email liên hệ
                                                    </label>
                                                    <input type="email" id="contact_email" name="hot_news_options[contact_email]" 
                                                           value="<?php echo isset($this->options['contact_email']) ? esc_attr($this->options['contact_email']) : ''; ?>" 
                                                           class="form-control form-control-lg" placeholder="contact@example.com" />
                                                    <small class="form-text text-muted">Email hiển thị trong footer và trang liên hệ</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="contact_phone" class="font-weight-semibold">
                                                        <i class="fas fa-phone text-success mr-2"></i>Số điện thoại
                                                    </label>
                                                    <input type="tel" id="contact_phone" name="hot_news_options[contact_phone]" 
                                                           value="<?php echo isset($this->options['contact_phone']) ? esc_attr($this->options['contact_phone']) : ''; ?>" 
                                                           class="form-control form-control-lg" placeholder="+84 123 456 789" />
                                                    <small class="form-text text-muted">Số điện thoại liên hệ</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="contact_address" class="font-weight-semibold">
                                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>Địa chỉ
                                            </label>
                                            <textarea id="contact_address" name="hot_news_options[contact_address]" 
                                                      class="form-control" rows="3" 
                                                      placeholder="123 Đường ABC, Quận 1, TP.HCM"><?php echo isset($this->options['contact_address']) ? esc_textarea($this->options['contact_address']) : ''; ?></textarea>
                                            <small class="form-text text-muted">Địa chỉ công ty/tổ chức</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="business_hours" class="font-weight-semibold">
                                                <i class="fas fa-clock text-info mr-2"></i>Giờ làm việc
                                            </label>
                                            <input type="text" id="business_hours" name="hot_news_options[business_hours]" 
                                                   value="<?php echo isset($this->options['business_hours']) ? esc_attr($this->options['business_hours']) : ''; ?>" 
                                                   class="form-control form-control-lg" placeholder="Thứ 2 - Thứ 6: 8:00 - 17:30" />
                                            <small class="form-text text-muted">Thời gian hoạt động</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Networks Tab -->
                            <div id="social-networks" class="tab-content-bootstrap">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-share-alt mr-2"></i>
                                            Mạng xã hội
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-4">Thêm đường link đến các trang mạng xã hội. Chỉ những mạng xã hội có URL sẽ được hiển thị.</p>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="facebook_url" class="font-weight-semibold">
                                                        <i class="fab fa-facebook-f text-primary mr-2"></i>Facebook
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                                        </div>
                                                        <input type="url" id="facebook_url" name="hot_news_options[facebook_url]" 
                                                               value="<?php echo isset($this->options['facebook_url']) ? esc_attr($this->options['facebook_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://facebook.com/your-page" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="instagram_url" class="font-weight-semibold">
                                                        <i class="fab fa-instagram text-danger mr-2"></i>Instagram
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                                        </div>
                                                        <input type="url" id="instagram_url" name="hot_news_options[instagram_url]" 
                                                               value="<?php echo isset($this->options['instagram_url']) ? esc_attr($this->options['instagram_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://instagram.com/your-account" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="youtube_url" class="font-weight-semibold">
                                                        <i class="fab fa-youtube text-danger mr-2"></i>YouTube
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                                        </div>
                                                        <input type="url" id="youtube_url" name="hot_news_options[youtube_url]" 
                                                               value="<?php echo isset($this->options['youtube_url']) ? esc_attr($this->options['youtube_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://youtube.com/channel/your-channel" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="twitter_url" class="font-weight-semibold">
                                                        <i class="fab fa-twitter text-info mr-2"></i>Twitter
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                                        </div>
                                                        <input type="url" id="twitter_url" name="hot_news_options[twitter_url]" 
                                                               value="<?php echo isset($this->options['twitter_url']) ? esc_attr($this->options['twitter_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://twitter.com/your-handle" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="linkedin_url" class="font-weight-semibold">
                                                        <i class="fab fa-linkedin-in text-primary mr-2"></i>LinkedIn
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                                        </div>
                                                        <input type="url" id="linkedin_url" name="hot_news_options[linkedin_url]" 
                                                               value="<?php echo isset($this->options['linkedin_url']) ? esc_attr($this->options['linkedin_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://linkedin.com/company/your-company" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="tiktok_url" class="font-weight-semibold">
                                                        <i class="fab fa-tiktok text-dark mr-2"></i>TikTok
                                                    </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fab fa-tiktok"></i></span>
                                                        </div>
                                                        <input type="url" id="tiktok_url" name="hot_news_options[tiktok_url]" 
                                                               value="<?php echo isset($this->options['tiktok_url']) ? esc_attr($this->options['tiktok_url']) : ''; ?>" 
                                                               class="form-control" placeholder="https://tiktok.com/@your-account" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- General Tab -->
                            <div id="general" class="tab-content-bootstrap">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-sliders-h mr-2"></i>
                                            Cài đặt tổng quan
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-4">Các thiết lập chung cho theme.</p>
                                        
                                        <div class="form-group">
                                            <label for="site_description" class="font-weight-semibold">
                                                <i class="fas fa-align-left text-info mr-2"></i>Mô tả website
                                            </label>
                                            <textarea id="site_description" name="hot_news_options[site_description]" 
                                                      class="form-control" rows="4" 
                                                      placeholder="Mô tả ngắn gọn về website của bạn..."><?php echo isset($this->options['site_description']) ? esc_textarea($this->options['site_description']) : ''; ?></textarea>
                                            <small class="form-text text-muted">Mô tả này có thể được sử dụng trong các meta tag hoặc footer</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="copyright_text" class="font-weight-semibold">
                                                <i class="fas fa-copyright text-secondary mr-2"></i>Bản quyền
                                            </label>
                                            <input type="text" id="copyright_text" name="hot_news_options[copyright_text]" 
                                                   value="<?php echo isset($this->options['copyright_text']) ? esc_attr($this->options['copyright_text']) : ''; ?>" 
                                                   class="form-control form-control-lg" placeholder="© 2025 Hot News. All rights reserved." />
                                            <small class="form-text text-muted">Text bản quyền hiển thị ở footer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save mr-2"></i>Lưu thay đổi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
        /* Bootstrap Admin Styles */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0073aa 0%, #005177 100%) !important;
        }
        
        .tab-content-bootstrap {
            display: none;
        }
        
        .tab-content-bootstrap.active {
            display: block;
        }
        
        .list-group-item-action {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .list-group-item-action:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .list-group-item-action.active {
            background-color: #007cba !important;
            color: white !important;
            border-color: #007cba !important;
        }
        
        .list-group-item-action.active i {
            color: white !important;
        }
        
        .card {
            border: none;
            border-radius: 8px;
        }
        
        .card-header {
            border-radius: 8px 8px 0 0 !important;
            border-bottom: none;
            padding: 1rem 1.25rem;
        }
        
        .form-control:focus {
            border-color: #007cba;
            box-shadow: 0 0 0 0.2rem rgba(0, 124, 186, 0.25);
        }
        
        .form-control-lg {
            border-radius: 6px;
            padding: 0.75rem 1rem;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
            color: #6c757d;
        }
        
        .btn-primary {
            background-color: #007cba;
            border-color: #007cba;
        }
        
        .btn-primary:hover {
            background-color: #005177;
            border-color: #005177;
        }
        
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
        
        .font-weight-semibold {
            font-weight: 600;
        }
        
        .opacity-75 {
            opacity: 0.75;
        }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .container-fluid {
                padding: 0 15px;
            }
            
            .bg-gradient-primary {
                margin: 0 -15px 20px;
            }
        }
        
        /* Animation effects */
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Form enhancements */
        .form-group label {
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        
        .form-text {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        </style>

        <!-- Load Bootstrap CSS if not already loaded -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <script>
        jQuery(document).ready(function($) {
            // Bootstrap-style tab switching
            $('.list-group-item-action').on('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and content
                $('.list-group-item-action').removeClass('active');
                $('.tab-content-bootstrap').removeClass('active');
                
                // Add active class to clicked tab
                $(this).addClass('active');
                
                // Show corresponding content
                var target = $(this).attr('href');
                $(target).addClass('active');
                
                // Scroll to top of content area
                $('html, body').animate({
                    scrollTop: $(target).offset().top - 100
                }, 500);
            });
            
            // Form validation feedback
            $('input[required], textarea[required]').on('blur', function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });
            
            // URL validation for social media inputs
            $('input[type="url"]').on('input', function() {
                var url = $(this).val();
                var urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
                
                if (url && !urlPattern.test(url)) {
                    $(this).addClass('is-invalid');
                    
                    // Show or create error message
                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback">Vui lòng nhập URL hợp lệ (ví dụ: https://facebook.com/yourpage)</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });
            
            // Auto-add https:// to URL fields
            $('input[type="url"]').on('blur', function() {
                var url = $(this).val().trim();
                if (url && !url.match(/^https?:\/\//)) {
                    $(this).val('https://' + url);
                }
            });
            
            // Submit button loading state
            $('form').on('submit', function() {
                var $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true);
                $btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Đang lưu...');
                
                // Re-enable after 3 seconds (in case of errors)
                setTimeout(function() {
                    $btn.prop('disabled', false);
                    $btn.html('<i class="fas fa-save mr-2"></i>Lưu thay đổi');
                }, 3000);
            });
            
            // Tooltips for form labels (if Bootstrap is loaded)
            if (typeof $().tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            }
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
        
        // Enqueue Bootstrap CSS
        wp_enqueue_style('bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css', array(), '4.6.2');
        
        // Enqueue FontAwesome for social media icons
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
        
        // Enqueue Bootstrap JS (optional, for enhanced interactions)
        wp_enqueue_script('bootstrap-admin-js', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '4.6.2', true);
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
