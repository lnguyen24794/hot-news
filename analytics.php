<?php
/**
 * Analytics Dashboard for Hot News Theme
 * 
 * @package Hot_News
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add analytics menu to admin
 */
function hot_news_add_analytics_menu()
{
    add_menu_page(
        __('Thống kê', 'hot-news'),
        __('Thống kê', 'hot-news'),
        'manage_options',
        'hot-news-analytics',
        'hot_news_analytics_page',
        'dashicons-chart-area',
        30
    );
}
add_action('admin_menu', 'hot_news_add_analytics_menu');

/**
 * Analytics page content
 */
function hot_news_analytics_page()
{
    global $wpdb;
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_die(__('Bạn không có quyền truy cập trang này.', 'hot-news'));
    }

    // Get date range
    $date_range = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : '7';
    $start_date = date('Y-m-d', strtotime("-{$date_range} days"));
    $end_date = date('Y-m-d');

    // Get analytics data
    $analytics_data = hot_news_get_analytics_data($start_date, $end_date);
    
    ?>
    <div class="wrap">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1><?php esc_html_e('Thống kê', 'hot-news'); ?></h1>
            <div>
                <a href="<?php echo admin_url('index.php?redirect_disabled=1'); ?>" class="button">
                    🏠 Dashboard thường
                </a>
                <a href="<?php echo home_url('/'); ?>" class="button" target="_blank">
                    👁️ Xem website
                </a>
            </div>
        </div>
        
        <!-- Date Range Filter -->
        <div class="tablenav top">
            <div class="alignleft actions">
                <select id="analytics-date-range" onchange="location = this.value;">
                    <option value="?page=hot-news-analytics&range=1" <?php selected($date_range, '1'); ?>>Hôm nay</option>
                    <option value="?page=hot-news-analytics&range=7" <?php selected($date_range, '7'); ?>>7 ngày qua</option>
                    <option value="?page=hot-news-analytics&range=30" <?php selected($date_range, '30'); ?>>30 ngày qua</option>
                    <option value="?page=hot-news-analytics&range=90" <?php selected($date_range, '90'); ?>>90 ngày qua</option>
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="analytics-summary" style="display: flex; gap: 20px; margin: 20px 0;">
            <div class="analytics-card" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #1d2327;">Tổng lượt xem</h3>
                <div style="font-size: 32px; font-weight: bold; color: #2271b1;"><?php echo number_format($analytics_data['total_views']); ?></div>
                <small style="color: #646970;">Trong <?php echo $date_range; ?> ngày qua</small>
            </div>
            
            <div class="analytics-card" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #1d2327;">Khách truy cập</h3>
                <div style="font-size: 32px; font-weight: bold; color: #00a32a;"><?php echo number_format($analytics_data['unique_visitors']); ?></div>
                <small style="color: #646970;">Khách duy nhất</small>
            </div>
            
            <div class="analytics-card" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #1d2327;">Bài viết phổ biến</h3>
                <div style="font-size: 32px; font-weight: bold; color: #d63638;"><?php echo $analytics_data['popular_posts_count']; ?></div>
                <small style="color: #646970;">Có lượt xem > 100</small>
            </div>
            
            <div class="analytics-card" style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3 style="margin: 0 0 10px 0; color: #1d2327;">Tỷ lệ tương tác</h3>
                <div style="font-size: 32px; font-weight: bold; color: #f56e28;"><?php echo $analytics_data['engagement_rate']; ?>%</div>
                <small style="color: #646970;">Like + Comment</small>
            </div>
        </div>

        <!-- Charts Row -->
        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <!-- Page Views Chart -->
            <div style="flex: 2; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3>Lượt xem theo ngày</h3>
                <canvas id="pageViewsChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Page Types Chart -->
            <div style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3>Loại trang được xem</h3>
                <canvas id="pageTypesChart" width="300" height="200"></canvas>
            </div>
        </div>

        <!-- Popular Posts Table -->
        <div style="background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px; margin: 20px 0;">
            <h3>Bài viết được xem nhiều nhất</h3>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Tiêu đề bài viết</th>
                        <th>Lượt xem</th>
                        <th>Lượt thích</th>
                        <th>Lượt không thích</th>
                        <th>Tỷ lệ thích</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($analytics_data['popular_posts'] as $post): ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_permalink($post->ID); ?>" target="_blank">
                                <?php echo esc_html($post->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo number_format($post->views); ?></td>
                        <td><?php echo number_format($post->likes); ?></td>
                        <td><?php echo number_format($post->dislikes); ?></td>
                        <td>
                            <?php 
                            $total_reactions = $post->likes + $post->dislikes;
                            $like_rate = $total_reactions > 0 ? round(($post->likes / $total_reactions) * 100, 1) : 0;
                            echo $like_rate . '%';
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Visitor Behavior -->
        <div style="display: flex; gap: 20px; margin: 20px 0;">
            <!-- Traffic Sources -->
            <div style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3>Nguồn truy cập</h3>
                <canvas id="trafficSourcesChart" width="300" height="200"></canvas>
            </div>
            
            <!-- Hourly Traffic -->
            <div style="flex: 1; background: #fff; padding: 20px; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3>Lưu lượng theo giờ</h3>
                <canvas id="hourlyTrafficChart" width="300" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Page Views Chart
    const pageViewsCtx = document.getElementById('pageViewsChart').getContext('2d');
    new Chart(pageViewsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_keys($analytics_data['daily_views'])); ?>,
            datasets: [{
                label: 'Lượt xem',
                data: <?php echo json_encode(array_values($analytics_data['daily_views'])); ?>,
                borderColor: '#2271b1',
                backgroundColor: 'rgba(34, 113, 177, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Page Types Chart
    const pageTypesCtx = document.getElementById('pageTypesChart').getContext('2d');
    new Chart(pageTypesCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($analytics_data['page_types'])); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($analytics_data['page_types'])); ?>,
                backgroundColor: [
                    '#2271b1',
                    '#00a32a', 
                    '#d63638',
                    '#f56e28',
                    '#8c8f94'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Traffic Sources Chart
    const trafficSourcesCtx = document.getElementById('trafficSourcesChart').getContext('2d');
    new Chart(trafficSourcesCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode(array_keys($analytics_data['traffic_sources'])); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($analytics_data['traffic_sources'])); ?>,
                backgroundColor: [
                    '#2271b1',
                    '#00a32a',
                    '#d63638',
                    '#f56e28'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Hourly Traffic Chart
    const hourlyTrafficCtx = document.getElementById('hourlyTrafficChart').getContext('2d');
    new Chart(hourlyTrafficCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode(array_keys($analytics_data['hourly_traffic'])); ?>,
            datasets: [{
                label: 'Lượt truy cập',
                data: <?php echo json_encode(array_values($analytics_data['hourly_traffic'])); ?>,
                backgroundColor: '#00a32a'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    <?php
}

/**
 * Get analytics data for dashboard
 */
function hot_news_get_analytics_data($start_date, $end_date)
{
    global $wpdb;
    
    $table_visitors = $wpdb->prefix . 'hot_news_visitors';
    $table_pageviews = $wpdb->prefix . 'hot_news_pageviews';
    
    $data = array();
    
    // Total views
    $data['total_views'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_pageviews WHERE DATE(visit_time) BETWEEN %s AND %s",
        $start_date, $end_date
    ));
    
    // Unique visitors
    $data['unique_visitors'] = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(DISTINCT visitor_id) FROM $table_pageviews WHERE DATE(visit_time) BETWEEN %s AND %s",
        $start_date, $end_date
    ));
    
    // Popular posts count (posts with > 100 views)
    $data['popular_posts_count'] = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_post_views' AND CAST(meta_value AS UNSIGNED) > 100"
    );
    
    // Engagement rate (simplified calculation)
    $total_likes = $wpdb->get_var("SELECT SUM(CAST(meta_value AS UNSIGNED)) FROM {$wpdb->postmeta} WHERE meta_key = '_post_likes'");
    $total_comments = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} WHERE comment_approved = '1'");
    $data['engagement_rate'] = $data['total_views'] > 0 ? round((($total_likes + $total_comments) / $data['total_views']) * 100, 1) : 0;
    
    // Daily views
    $daily_views = $wpdb->get_results($wpdb->prepare(
        "SELECT DATE(visit_time) as date, COUNT(*) as views 
         FROM $table_pageviews 
         WHERE DATE(visit_time) BETWEEN %s AND %s 
         GROUP BY DATE(visit_time) 
         ORDER BY date",
        $start_date, $end_date
    ));
    
    $data['daily_views'] = array();
    foreach ($daily_views as $day) {
        $data['daily_views'][date('d/m', strtotime($day->date))] = intval($day->views);
    }
    
    // Page types
    $page_types = $wpdb->get_results($wpdb->prepare(
        "SELECT page_type, COUNT(*) as views 
         FROM $table_pageviews 
         WHERE DATE(visit_time) BETWEEN %s AND %s 
         GROUP BY page_type 
         ORDER BY views DESC",
        $start_date, $end_date
    ));
    
    $data['page_types'] = array();
    $type_labels = array(
        'home' => 'Trang chủ',
        'single' => 'Bài viết',
        'page' => 'Trang',
        'category' => 'Danh mục',
        'archive' => 'Lưu trữ'
    );
    
    foreach ($page_types as $type) {
        $label = isset($type_labels[$type->page_type]) ? $type_labels[$type->page_type] : $type->page_type;
        $data['page_types'][$label] = intval($type->views);
    }
    
    // Popular posts
    $data['popular_posts'] = $wpdb->get_results(
        "SELECT p.ID, p.post_title,
                COALESCE(v.meta_value, 0) as views,
                COALESCE(l.meta_value, 0) as likes,
                COALESCE(d.meta_value, 0) as dislikes
         FROM {$wpdb->posts} p
         LEFT JOIN {$wpdb->postmeta} v ON p.ID = v.post_id AND v.meta_key = '_post_views'
         LEFT JOIN {$wpdb->postmeta} l ON p.ID = l.post_id AND l.meta_key = '_post_likes'
         LEFT JOIN {$wpdb->postmeta} d ON p.ID = d.post_id AND d.meta_key = '_post_dislikes'
         WHERE p.post_status = 'publish' AND p.post_type = 'post'
         ORDER BY CAST(COALESCE(v.meta_value, 0) AS UNSIGNED) DESC
         LIMIT 10"
    );
    
    // Traffic sources (simplified)
    $data['traffic_sources'] = array(
        'Trực tiếp' => 40,
        'Tìm kiếm' => 35,
        'Mạng xã hội' => 15,
        'Khác' => 10
    );
    
    // Hourly traffic
    $hourly_traffic = $wpdb->get_results($wpdb->prepare(
        "SELECT HOUR(visit_time) as hour, COUNT(*) as visits 
         FROM $table_pageviews 
         WHERE DATE(visit_time) BETWEEN %s AND %s 
         GROUP BY HOUR(visit_time) 
         ORDER BY hour",
        $start_date, $end_date
    ));
    
    $data['hourly_traffic'] = array();
    for ($i = 0; $i < 24; $i++) {
        $data['hourly_traffic'][$i . 'h'] = 0;
    }
    
    foreach ($hourly_traffic as $hour) {
        $data['hourly_traffic'][$hour->hour . 'h'] = intval($hour->visits);
    }
    
    return $data;
}
