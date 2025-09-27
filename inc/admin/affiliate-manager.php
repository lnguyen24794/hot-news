<?php
/**
 * Affiliate Manager
 * Quản lý affiliate links cho theme Hot News
 *
 * @package Hot_News
 */

if (!defined('ABSPATH')) {
    exit;
}

class Hot_News_Affiliate_Manager
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_hot_news_add_affiliate', array($this, 'ajax_add_affiliate'));
        add_action('wp_ajax_hot_news_update_affiliate', array($this, 'ajax_update_affiliate'));
        add_action('wp_ajax_hot_news_delete_affiliate', array($this, 'ajax_delete_affiliate'));
        add_action('wp_ajax_hot_news_toggle_affiliate', array($this, 'ajax_toggle_affiliate'));

        // Frontend modal
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('wp_footer', array($this, 'add_affiliate_modal'));
        add_action('wp_ajax_hot_news_get_random_affiliate', array($this, 'ajax_get_random_affiliate'));
        add_action('wp_ajax_nopriv_hot_news_get_random_affiliate', array($this, 'ajax_get_random_affiliate'));

        // Create database tables
        add_action('after_setup_theme', array($this, 'create_affiliate_tables'));
    }

    /**
     * Tạo database tables
     */
    public function create_affiliate_tables()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            url varchar(500) NOT NULL,
            image_url varchar(500) NOT NULL,
            is_active tinyint(1) DEFAULT 1,
            click_count int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY is_active (is_active),
            KEY created_at (created_at)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Set version
        update_option('hot_news_affiliate_db_version', '1.0');

        // Create sample data if table is empty
        $this->create_sample_affiliate_data();
    }

    /**
     * Create sample affiliate data for testing
     */
    private function create_sample_affiliate_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        // Check if table is empty
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        if ($count == 0) {
            // Insert sample affiliate data
            $sample_data = array(
                array(
                    'title' => 'iPhone 15 Pro Max - Giá tốt nhất',
                    'url' => 'https://example.com/affiliate-link-1',
                    'image_url' => get_template_directory_uri() . '/assets/images/ads-1.jpg',
                    'is_active' => 1
                ),
                array(
                    'title' => 'Laptop Gaming ROG - Khuyến mãi sốc',
                    'url' => 'https://example.com/affiliate-link-2',
                    'image_url' => get_template_directory_uri() . '/assets/images/ads-2.jpg',
                    'is_active' => 1
                ),
                array(
                    'title' => 'Khóa học Online - Giảm 50%',
                    'url' => 'https://example.com/affiliate-link-3',
                    'image_url' => get_template_directory_uri() . '/assets/images/news-350x223-1.jpg',
                    'is_active' => 0 // Inactive for testing
                )
            );

            foreach ($sample_data as $data) {
                $wpdb->insert($table_name, $data);
            }
        }
    }

    /**
     * Thêm menu admin
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Quản lý Affiliate', 'hot-news'),
            __('Affiliate', 'hot-news'),
            'manage_options',
            'hot-news-affiliate',
            array($this, 'admin_page'),
            'dashicons-admin-links',
            31
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('toplevel_page_hot-news-affiliate' !== $hook) {
            return;
        }

        wp_enqueue_media(); // For image uploads
        wp_enqueue_script('hot-news-affiliate-admin', get_template_directory_uri() . '/assets/js/admin-affiliate.js', array('jquery'), HOT_NEWS_VERSION, true);
        wp_enqueue_style('hot-news-affiliate-admin', get_template_directory_uri() . '/assets/css/admin-affiliate.css', array(), HOT_NEWS_VERSION);

        wp_localize_script('hot-news-affiliate-admin', 'hotNewsAffiliate', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hot_news_affiliate_nonce'),
            'strings' => array(
                'confirm_delete' => __('Bạn có chắc muốn xóa affiliate link này?', 'hot-news'),
                'title_required' => __('Vui lòng nhập tiêu đề', 'hot-news'),
                'url_required' => __('Vui lòng nhập URL', 'hot-news'),
                'image_required' => __('Vui lòng chọn hình ảnh', 'hot-news'),
                'invalid_url' => __('URL không hợp lệ', 'hot-news'),
                'saving' => __('Đang lưu...', 'hot-news'),
                'save' => __('Lưu', 'hot-news'),
                'edit' => __('Chỉnh sửa', 'hot-news'),
                'delete' => __('Xóa', 'hot-news'),
                'success' => __('Thành công!', 'hot-news'),
                'error' => __('Có lỗi xảy ra!', 'hot-news')
            )
        ));
    }

    /**
     * Enqueue frontend scripts
     */
    public function enqueue_frontend_scripts()
    {
        if (is_single()) {
            wp_enqueue_script('hot-news-affiliate-modal', get_template_directory_uri() . '/assets/js/affiliate-modal.js', array('jquery'), HOT_NEWS_VERSION, true);
            wp_enqueue_style('hot-news-affiliate-overlay', get_template_directory_uri() . '/assets/css/affiliate-overlay.css', array(), HOT_NEWS_VERSION);

            // Debug: Check if we have active affiliates
            global $wpdb;
            $table_name = $wpdb->prefix . 'hot_news_affiliates';
            $active_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_active = 1");

            wp_localize_script('hot-news-affiliate-modal', 'hotNewsAffiliateModal', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('hot_news_affiliate_modal_nonce'),
                'show_modal' => get_option('hot_news_affiliate_show_modal', 1),
                'delay' => get_option('hot_news_affiliate_modal_delay', 3000), // 3 seconds
                'debug' => WP_DEBUG,
                'active_count' => $active_count,
                'table_exists' => $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name
            ));
        }
    }

    /**
     * Admin page HTML
     */
    public function admin_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';
        $affiliates = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        ?>
        <div class="wrap hot-news-affiliate-admin">
            <h1 class="wp-heading-inline"><?php _e('Quản lý Affiliate Links', 'hot-news'); ?></h1>
            <a href="#" class="page-title-action add-new-affiliate"><?php _e('Thêm mới', 'hot-news'); ?></a>
            
            <div class="affiliate-stats">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($affiliates); ?></div>
                        <div class="stat-label"><?php _e('Tổng số links', 'hot-news'); ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count(array_filter($affiliates, function ($a) { return $a->is_active; })); ?></div>
                        <div class="stat-label"><?php _e('Links đang hoạt động', 'hot-news'); ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo array_sum(array_column($affiliates, 'click_count')); ?></div>
                        <div class="stat-label"><?php _e('Tổng clicks', 'hot-news'); ?></div>
                    </div>
                </div>
            </div>

            <div class="affiliate-settings-section">
                <h3><?php _e('Cài đặt Modal', 'hot-news'); ?></h3>
                <form method="post" action="options.php">
                    <?php settings_fields('hot_news_affiliate_settings'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Hiển thị Modal', 'hot-news'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="hot_news_affiliate_show_modal" value="1" <?php checked(get_option('hot_news_affiliate_show_modal', 1)); ?>>
                                    <?php _e('Bật modal affiliate trên trang bài viết', 'hot-news'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Thời gian delay (ms)', 'hot-news'); ?></th>
                            <td>
                                <input type="number" name="hot_news_affiliate_modal_delay" value="<?php echo get_option('hot_news_affiliate_modal_delay', 3000); ?>" min="1" max="30000" step="500">
                                <p class="description"><?php _e('Thời gian chờ trước khi hiển thị modal (1-30 giây)', 'hot-news'); ?></p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
                
                <?php if (WP_DEBUG): ?>
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                    <h4 style="margin: 0 0 10px 0; color: #856404;">🔍 Debug Info</h4>
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . 'hot_news_affiliates';
                    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
                    $total_affiliates = $table_exists ? $wpdb->get_var("SELECT COUNT(*) FROM $table_name") : 0;
                    $active_affiliates = $table_exists ? $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_active = 1") : 0;
                    ?>
                    <p style="margin: 5px 0; font-family: monospace; font-size: 13px;">
                        <strong>Table exists:</strong> <?php echo $table_exists ? '✅ Yes' : '❌ No'; ?><br>
                        <strong>Total affiliates:</strong> <?php echo $total_affiliates; ?><br>
                        <strong>Active affiliates:</strong> <?php echo $active_affiliates; ?><br>
                        <strong>Show modal setting:</strong> <?php echo get_option('hot_news_affiliate_show_modal', 1) ? '✅ Enabled' : '❌ Disabled'; ?><br>
                        <strong>Modal delay:</strong> <?php echo get_option('hot_news_affiliate_modal_delay', 3000); ?>ms
                    </p>
                    <?php if ($active_affiliates == 0): ?>
                    <p style="color: var(--hot-news-primary); font-weight: 600;">⚠️ Modal sẽ không hiển thị vì không có affiliate nào đang hoạt động!</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="affiliates-list-section">
                <h3><?php _e('Danh sách Affiliate Links', 'hot-news'); ?></h3>
                
                <div class="affiliates-table-wrapper">
                    <table class="wp-list-table widefat fixed striped affiliates-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;"><?php _e('ID', 'hot-news'); ?></th>
                                <th style="width: 80px;"><?php _e('Hình ảnh', 'hot-news'); ?></th>
                                <th><?php _e('Tiêu đề', 'hot-news'); ?></th>
                                <th><?php _e('URL', 'hot-news'); ?></th>
                                <th style="width: 80px;"><?php _e('Clicks', 'hot-news'); ?></th>
                                <th style="width: 100px;"><?php _e('Trạng thái', 'hot-news'); ?></th>
                                <th style="width: 150px;"><?php _e('Hành động', 'hot-news'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="affiliates-list">
                            <?php if (empty($affiliates)) : ?>
                                <tr class="no-affiliates">
                                    <td colspan="7" style="text-align: center; padding: 40px;">
                                        <div class="empty-state">
                                            <div class="empty-icon">🔗</div>
                                            <h3><?php _e('Chưa có affiliate link nào', 'hot-news'); ?></h3>
                                            <p><?php _e('Nhấn "Thêm mới" để tạo affiliate link đầu tiên của bạn', 'hot-news'); ?></p>
                                            <button type="button" class="button button-primary add-new-affiliate"><?php _e('Thêm affiliate link', 'hot-news'); ?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($affiliates as $affiliate) : ?>
                                    <?php $this->render_affiliate_row($affiliate); ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New/Edit Affiliate Form Modal -->
            <div id="affiliate-modal" class="affiliate-modal" style="display: none;">
                <div class="affiliate-modal-content">
                    <div class="affiliate-modal-header">
                        <h2 id="modal-title"><?php _e('Thêm Affiliate Link', 'hot-news'); ?></h2>
                        <span class="affiliate-modal-close">&times;</span>
                    </div>
                    <div class="affiliate-modal-body">
                        <form id="affiliate-form">
                            <input type="hidden" id="affiliate-id" value="">
                            
                            <div class="form-group">
                                <label for="affiliate-title"><?php _e('Tiêu đề', 'hot-news'); ?> *</label>
                                <input type="text" id="affiliate-title" name="title" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="affiliate-url"><?php _e('URL Affiliate', 'hot-news'); ?> *</label>
                                <input type="url" id="affiliate-url" name="url" required>
                                <small class="description"><?php _e('Nhập URL đầy đủ (bao gồm https://)', 'hot-news'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="affiliate-image"><?php _e('Hình ảnh', 'hot-news'); ?> *</label>
                                <div class="image-upload-area">
                                    <input type="hidden" id="affiliate-image" name="image_url" required>
                                    <div class="image-preview" id="image-preview">
                                        <div class="upload-placeholder">
                                            <div class="upload-icon">📷</div>
                                            <p><?php _e('Click để chọn hình ảnh', 'hot-news'); ?></p>
                                        </div>
                                    </div>
                                    <button type="button" class="button" id="upload-image-btn"><?php _e('Chọn hình ảnh', 'hot-news'); ?></button>
                                    <button type="button" class="button" id="remove-image-btn" style="display: none;"><?php _e('Xóa hình ảnh', 'hot-news'); ?></button>
                                </div>
                                <small class="description"><?php _e('Kích thước khuyến nghị: 400x300px', 'hot-news'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="affiliate-active" name="is_active" checked>
                                    <?php _e('Kích hoạt ngay', 'hot-news'); ?>
                                </label>
                                <small class="description"><?php _e('Chỉ những link được kích hoạt mới hiển thị trong modal', 'hot-news'); ?></small>
                            </div>
                        </form>
                    </div>
                    <div class="affiliate-modal-footer">
                        <button type="button" class="button button-secondary" id="cancel-affiliate"><?php _e('Hủy', 'hot-news'); ?></button>
                        <button type="button" class="button button-primary" id="save-affiliate"><?php _e('Lưu', 'hot-news'); ?></button>
                    </div>
                </div>
            </div>

            <!-- Loading Overlay -->
            <div id="affiliate-loading" class="affiliate-loading" style="display: none;">
                <div class="loading-spinner"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Render affiliate row
     */
    private function render_affiliate_row($affiliate)
    {
        ?>
        <tr data-id="<?php echo $affiliate->id; ?>" class="affiliate-row">
            <td class="affiliate-id">
                <strong><?php echo $affiliate->id; ?></strong>
            </td>
            <td class="affiliate-image">
                <div class="affiliate-image-thumb">
                    <img src="<?php echo esc_url($affiliate->image_url); ?>" alt="<?php echo esc_attr($affiliate->title); ?>">
                </div>
            </td>
            <td class="affiliate-title">
                <strong><?php echo esc_html($affiliate->title); ?></strong>
                <div class="affiliate-meta">
                    <small class="created-date">
                        <?php echo sprintf(__('Tạo: %s', 'hot-news'), date_i18n('d/m/Y H:i', strtotime($affiliate->created_at))); ?>
                    </small>
                </div>
            </td>
            <td class="affiliate-url">
                <a href="<?php echo esc_url($affiliate->url); ?>"  class="affiliate-link">
                    <?php echo esc_html(wp_trim_words($affiliate->url, 6, '...')); ?>
                    <span class="dashicons dashicons-external"></span>
                </a>
                <div class="url-preview">
                    <small class="url-domain"><?php echo esc_html(parse_url($affiliate->url, PHP_URL_HOST)); ?></small>
                </div>
            </td>
            <td class="affiliate-clicks">
                <div class="click-stats">
                    <span class="click-count"><?php echo number_format($affiliate->click_count); ?></span>
                </div>
            </td>
            <td class="affiliate-status">
                <div class="status-control">
                    <label class="status-checkbox-wrapper">
                        <input type="checkbox" class="toggle-status status-checkbox" data-id="<?php echo $affiliate->id; ?>" <?php checked($affiliate->is_active); ?>>
                        <span class="status-label">
                            <?php _e('Hoạt động', 'hot-news'); ?>
                        </span>
                    </label>
                </div>
            </td>
            <td class="affiliate-actions">
                <div class="action-buttons">
                    <button type="button" class="button button-secondary button-small edit-affiliate" data-id="<?php echo $affiliate->id; ?>" title="<?php _e('Chỉnh sửa affiliate', 'hot-news'); ?>">
                        <i class="dashicons dashicons-edit"></i>
                        <span class="button-text"><?php _e('Sửa', 'hot-news'); ?></span>
                    </button>
                    <button type="button" class="button button-secondary button-small delete-affiliate" data-id="<?php echo $affiliate->id; ?>" title="<?php _e('Xóa affiliate', 'hot-news'); ?>">
                        <i class="dashicons dashicons-trash"></i>
                        <span class="button-text"><?php _e('Xóa', 'hot-news'); ?></span>
                    </button>
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * AJAX: Add affiliate
     */
    public function ajax_add_affiliate()
    {
        check_ajax_referer('hot_news_affiliate_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
            return;
        }

        $title = sanitize_text_field($_POST['title']);
        $url = esc_url_raw($_POST['url']);
        $image_url = esc_url_raw($_POST['image_url']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title) || empty($url) || empty($image_url)) {
            wp_send_json_error('Missing required fields');
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $result = $wpdb->insert(
            $table_name,
            array(
                'title' => $title,
                'url' => $url,
                'image_url' => $image_url,
                'is_active' => $is_active
            ),
            array('%s', '%s', '%s', '%d')
        );

        if ($result) {
            $affiliate_id = $wpdb->insert_id;
            $affiliate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $affiliate_id));

            ob_start();
            $this->render_affiliate_row($affiliate);
            $row_html = ob_get_clean();

            wp_send_json_success(array(
                'message' => 'Affiliate đã được thêm thành công!',
                'affiliate' => $affiliate,
                'row_html' => $row_html
            ));
        } else {
            wp_send_json_error('Database error');
        }
    }

    /**
     * AJAX: Update affiliate
     */
    public function ajax_update_affiliate()
    {
        check_ajax_referer('hot_news_affiliate_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
            return;
        }

        $id = intval($_POST['id']);
        $title = sanitize_text_field($_POST['title']);
        $url = esc_url_raw($_POST['url']);
        $image_url = esc_url_raw($_POST['image_url']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($title) || empty($url) || empty($image_url)) {
            wp_send_json_error('Missing required fields');
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $result = $wpdb->update(
            $table_name,
            array(
                'title' => $title,
                'url' => $url,
                'image_url' => $image_url,
                'is_active' => $is_active
            ),
            array('id' => $id),
            array('%s', '%s', '%s', '%d'),
            array('%d')
        );

        if ($result !== false) {
            $affiliate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

            ob_start();
            $this->render_affiliate_row($affiliate);
            $row_html = ob_get_clean();

            wp_send_json_success(array(
                'message' => 'Affiliate đã được cập nhật!',
                'affiliate' => $affiliate,
                'row_html' => $row_html
            ));
        } else {
            wp_send_json_error('Database error');
        }
    }

    /**
     * AJAX: Delete affiliate
     */
    public function ajax_delete_affiliate()
    {
        check_ajax_referer('hot_news_affiliate_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
            return;
        }

        $id = intval($_POST['id']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $result = $wpdb->delete($table_name, array('id' => $id), array('%d'));

        if ($result) {
            wp_send_json_success('Affiliate đã được xóa!');
        } else {
            wp_send_json_error('Database error');
        }
    }

    /**
     * AJAX: Toggle affiliate status
     */
    public function ajax_toggle_affiliate()
    {
        check_ajax_referer('hot_news_affiliate_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
            return;
        }

        $id = intval($_POST['id']);
        $status = intval($_POST['status']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $result = $wpdb->update(
            $table_name,
            array('is_active' => $status),
            array('id' => $id),
            array('%d'),
            array('%d')
        );

        if ($result !== false) {
            wp_send_json_success('Trạng thái đã được cập nhật!');
        } else {
            wp_send_json_error('Database error');
        }
    }

    /**
     * AJAX: Get random affiliate for modal
     */
    public function ajax_get_random_affiliate()
    {
        check_ajax_referer('hot_news_affiliate_modal_nonce', 'nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';

        $affiliate = $wpdb->get_row("SELECT * FROM $table_name WHERE is_active = 1 ORDER BY RAND() LIMIT 1");

        if ($affiliate) {
            // Increment click count
            $wpdb->update(
                $table_name,
                array('click_count' => $affiliate->click_count + 1),
                array('id' => $affiliate->id),
                array('%d'),
                array('%d')
            );

            wp_send_json_success($affiliate);
        } else {
            wp_send_json_error('No active affiliates found');
        }
    }

    /**
     * Add affiliate modal to frontend
     */
    public function add_affiliate_modal()
    {
        if (!is_single() || !get_option('hot_news_affiliate_show_modal', 1)) {
            return;
        }

        // Debug: Check if we have active affiliates
        global $wpdb;
        $table_name = $wpdb->prefix . 'hot_news_affiliates';
        $active_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_active = 1");

        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo "<!-- Affiliate Debug: Adding modal HTML, active count: {$active_count} -->";
        }
        ?>
        <!-- Affiliate Overlay -->
        <div id="affiliateOverlay" class="affiliate-overlay-container" style="display: none;">
            <!-- Dark Background Overlay -->
            <div class="affiliate-backdrop"></div>
            
            <!-- Centered Affiliate Content -->
            <div class="affiliate-popup-content">
                <!-- Close Button -->
                <button type="button" class="affiliate-close-btn" aria-label="<?php esc_attr_e('Đóng', 'hot-news'); ?>">
                    <span>&times;</span>
                </button>
                
                <!-- Main Content -->
                <div class="affiliate-content">
                    <!-- Clickable Image -->
                    <div class="affiliate-image-container">
                        <a id="affiliate-popup-link" href="#" class="affiliate-image-link">
                            <img id="affiliate-popup-image" src="" alt="" class="affiliate-image">
                            <div class="affiliate-text-overlay">
                                <h3 id="affiliate-popup-title" class="affiliate-title"></h3>
                                <p class="affiliate-subtitle"><?php _e('Nhấn để xem chi tiết', 'hot-news'); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Loading State -->
                <div id="affiliate-loading" class="affiliate-loading" style="display: none;">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                        <span class="loading-text"><?php _e('Đang tải...', 'hot-news'); ?></span>
                    </div>
                </div>
                
                <!-- Error State -->
                <div id="affiliate-error" class="affiliate-error" style="display: none;">
                    <div class="error-content">
                        <div class="error-icon">😔</div>
                        <h4 class="error-title"><?php _e('Oops!', 'hot-news'); ?></h4>
                        <p class="error-message" id="error-message"></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize
new Hot_News_Affiliate_Manager();

// Register settings
add_action('admin_init', function () {
    register_setting('hot_news_affiliate_settings', 'hot_news_affiliate_show_modal');
    register_setting('hot_news_affiliate_settings', 'hot_news_affiliate_modal_delay');
});
