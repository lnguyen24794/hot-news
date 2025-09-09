<?php
/**
 * Demo Setup Script for Hot News Theme
 * Run this once to setup sample data and theme options
 *
 * @package Hot_News
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Setup demo data and theme options
 */
function hot_news_setup_demo()
{
    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_die(__('Bạn không có quyền truy cập trang này.', 'hot-news'));
    }

    // Include sample data
    require_once get_template_directory() . '/sample-data.php';

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Cài đặt dữ liệu mẫu Hot News', 'hot-news') . '</h1>';

    // Handle form submission
    if (isset($_POST['import_data']) && wp_verify_nonce($_POST['hot_news_import_nonce'], 'hot_news_import_action')) {
        $import_type = sanitize_text_field($_POST['import_type']);
        $results = array();

        if ($import_type === 'all' || $import_type === 'posts') {
            $created_posts = hot_news_create_sample_posts();
            $results['posts'] = count($created_posts);
            
            // Create pages and menu for 'all' import
            if ($import_type === 'all') {
                $created_pages = hot_news_create_sample_pages();
                $results['pages'] = count($created_pages);
                
                $menu_id = hot_news_create_main_menu();
                $results['menu'] = $menu_id ? true : false;
            }
        }

        if ($import_type === 'all' || $import_type === 'settings') {
            hot_news_setup_sample_theme_options();
            $results['settings'] = true;
        }

        // Display results
        echo '<div class="notice notice-success"><p><strong>' . esc_html__('Import hoàn tất!', 'hot-news') . '</strong></p>';
        if (isset($results['posts'])) {
            echo '<p>' . sprintf(__('Đã tạo/cập nhật %d bài viết', 'hot-news'), $results['posts']) . '</p>';
        }
        if (isset($results['pages'])) {
            echo '<p>' . sprintf(__('Đã tạo/cập nhật %d trang', 'hot-news'), $results['pages']) . '</p>';
        }
        if (isset($results['menu'])) {
            echo '<p>' . esc_html__('Đã tạo menu chính (Trang chủ, Tin Hot, Liên hệ)', 'hot-news') . '</p>';
        }
        if (isset($results['settings'])) {
            echo '<p>' . esc_html__('Đã cập nhật cài đặt theme', 'hot-news') . '</p>';
        }
        echo '</div>';

        set_theme_mod('hot_news_demo_setup_complete', true);
    }

    // Display import form
    ?>
    <div class="card" style="max-width: 800px;">
        <h2><?php esc_html_e('Import dữ liệu mẫu', 'hot-news'); ?></h2>
        <p><?php esc_html_e('Chọn loại dữ liệu bạn muốn import vào website:', 'hot-news'); ?></p>

        <form method="post" action="">
            <?php wp_nonce_field('hot_news_import_action', 'hot_news_import_nonce'); ?>

            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Loại dữ liệu', 'hot-news'); ?></th>
                    <td>
                        <fieldset>
                            <label>
                                <input type="radio" name="import_type" value="all" checked>
                                <strong><?php esc_html_e('Tất cả (Khuyến nghị)', 'hot-news'); ?></strong>
                                <p class="description"><?php esc_html_e('Import bài viết mẫu và cài đặt theme', 'hot-news'); ?></p>
                            </label><br>

                            <label>
                                <input type="radio" name="import_type" value="posts">
                                <?php esc_html_e('Chỉ bài viết mẫu', 'hot-news'); ?>
                                <p class="description"><?php esc_html_e('8 bài viết mẫu với đầy đủ nội dung, hình ảnh, tags', 'hot-news'); ?></p>
                            </label><br>

                            <label>
                                <input type="radio" name="import_type" value="settings">
                                <?php esc_html_e('Chỉ cài đặt theme', 'hot-news'); ?>
                                <p class="description"><?php esc_html_e('Thông tin liên hệ, social links, newsletter', 'hot-news'); ?></p>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="import_data" class="button button-primary" value="<?php esc_attr_e('Bắt đầu Import', 'hot-news'); ?>">
            </p>
        </form>
    </div>

    <div class="card" style="max-width: 800px; margin-top: 20px;">
        <h3><?php esc_html_e('Dữ liệu sẽ được import:', 'hot-news'); ?></h3>
        <ul>
            <li><strong><?php esc_html_e('8 bài viết mẫu', 'hot-news'); ?></strong> - Nội dung tiếng Việt đầy đủ với HTML formatting</li>
            <li><strong><?php esc_html_e('9 danh mục', 'hot-news'); ?></strong> - Thể thao, Công nghệ, Kinh doanh, Giải trí, v.v.</li>
            <li><strong><?php esc_html_e('Thống kê bài viết', 'hot-news'); ?></strong> - Lượt xem, thích, không thích</li>
            <li><strong><?php esc_html_e('Tags và từ khóa', 'hot-news'); ?></strong> - Phân loại chi tiết cho bài viết</li>
            <li><strong><?php esc_html_e('Menu chính', 'hot-news'); ?></strong> - Trang chủ, Tin Hot, Liên hệ</li>
            <li><strong><?php esc_html_e('Trang Liên hệ', 'hot-news'); ?></strong> - Form liên hệ và thông tin</li>
            <li><strong><?php esc_html_e('Cài đặt theme', 'hot-news'); ?></strong> - Thông tin liên hệ, social media</li>
            <li><strong><?php esc_html_e('Bài viết nổi bật & Tin HOT', 'hot-news'); ?></strong> - Đánh dấu bài viết quan trọng</li>
        </ul>

        <h3><?php esc_html_e('Lưu ý quan trọng:', 'hot-news'); ?></h3>
        <ul>
            <li><?php esc_html_e('Dữ liệu sẽ được thêm vào, không ghi đè nội dung hiện có', 'hot-news'); ?></li>
            <li><?php esc_html_e('Nếu bài viết đã tồn tại (cùng tiêu đề), chỉ cập nhật metadata', 'hot-news'); ?></li>
            <li><?php esc_html_e('Có thể chạy lại nhiều lần mà không bị trùng lặp', 'hot-news'); ?></li>
            <li><?php esc_html_e('Hình ảnh mẫu đã có sẵn trong thư mục assets/images', 'hot-news'); ?></li>
        </ul>
    </div>

    <div class="card" style="max-width: 800px; margin-top: 20px;">
        <h3><?php esc_html_e('Các bước tiếp theo:', 'hot-news'); ?></h3>
        <ol>
            <li><a href="<?php echo admin_url('customize.php'); ?>" class="button"><?php esc_html_e('Tùy chỉnh giao diện', 'hot-news'); ?></a></li>
            <li><a href="<?php echo admin_url('nav-menus.php'); ?>" class="button"><?php esc_html_e('Thiết lập menu', 'hot-news'); ?></a></li>
            <li><a href="<?php echo admin_url('edit.php'); ?>" class="button"><?php esc_html_e('Quản lý bài viết', 'hot-news'); ?></a></li>
            <li><a href="<?php echo home_url('/'); ?>" class="button button-secondary"><?php esc_html_e('Xem website', 'hot-news'); ?></a></li>
        </ol>
    </div>

    <?php
    echo '</div>';
}

/**
 * Add admin menu for demo setup
 */
function hot_news_add_demo_setup_menu()
{
    add_theme_page(
        __('Import dữ liệu mẫu', 'hot-news'),
        __('Import dữ liệu mẫu', 'hot-news'),
        'manage_options',
        'hot-news-demo-setup',
        'hot_news_setup_demo'
    );
}
add_action('admin_menu', 'hot_news_add_demo_setup_menu');

/**
 * Add admin notice to run demo setup
 */
function hot_news_demo_setup_notice()
{
    // Check if demo has been setup
    if (get_theme_mod('hot_news_demo_setup_complete')) {
        return;
    }

    // Check if user has permission
    if (!current_user_can('manage_options')) {
        return;
    }

    $screen = get_current_screen();
    if ($screen->id !== 'themes' && $screen->id !== 'appearance_page_hot-news-demo-setup') {
        return;
    }

    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e('Theme Hot News', 'hot-news'); ?></strong> - 
            <?php esc_html_e('Bạn có muốn import dữ liệu mẫu để bắt đầu nhanh chóng không?', 'hot-news'); ?>
            <a href="<?php echo admin_url('themes.php?page=hot-news-demo-setup'); ?>" class="button button-primary">
                <?php esc_html_e('Import dữ liệu mẫu', 'hot-news'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'hot_news_demo_setup_notice');

/**
 * Mark demo setup as complete
 */
function hot_news_mark_demo_complete()
{
    if (isset($_GET['page']) && $_GET['page'] === 'hot-news-demo-setup') {
        set_theme_mod('hot_news_demo_setup_complete', true);
    }
}
add_action('admin_init', 'hot_news_mark_demo_complete');
