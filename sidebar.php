<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hot_News
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<div class="sidebar">
    <?php if (is_active_sidebar('sidebar-1')) : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php else : ?>
        
        <!-- Default sidebar content when no widgets are added -->
        
        <!-- Recent Posts Widget -->
        <div class="sidebar-widget">
            <h2 class="sw-title"><?php esc_html_e('Recent Posts', 'hot-news'); ?></h2>
            <div class="news-list">
                <?php
                $recent_posts = get_posts(array(
                    'posts_per_page' => 5,
                    'post_status' => 'publish'
                ));

        foreach ($recent_posts as $post) :
            setup_postdata($post); ?>
                    <div class="nl-item">
                        <div class="nl-img">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('news-small'); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $recent_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="nl-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </div>
                    </div>
                <?php endforeach;
        wp_reset_postdata(); ?>
            </div>
        </div>
        
        <!-- Google AdSense General Sidebar Widget -->
        <div class="sidebar-widget">
            <div class="image">
                <?php
                $fallback_html = '';
        $sidebar_ad_image = get_theme_mod('hot_news_sidebar_ad_image');
        $sidebar_ad_url = get_theme_mod('hot_news_sidebar_ad_url');

        if ($sidebar_ad_image) {
            if ($sidebar_ad_url) {
                $fallback_html = '<a href="' . esc_url($sidebar_ad_url) . '" target="_blank" rel="noopener">';
                $fallback_html .= '<img src="' . esc_url($sidebar_ad_image) . '" alt="' . esc_attr__('Advertisement', 'hot-news') . '">';
                $fallback_html .= '</a>';
            } else {
                $fallback_html = '<img src="' . esc_url($sidebar_ad_image) . '" alt="' . esc_attr__('Advertisement', 'hot-news') . '">';
            }
        } else {
            $fallback_html = '<div class="ad-placeholder" style="background: #f5f5f5; padding: 40px 20px; text-align: center; color: #666; border: 2px dashed #ddd;">';
            $fallback_html .= '<p>' . esc_html__('Advertisement Space', 'hot-news') . '</p>';
            $fallback_html .= '<small>' . esc_html__('Configure in Google Ads Manager', 'hot-news') . '</small>';
            $fallback_html .= '</div>';
        }

        hot_news_display_ad('sidebar_ad_code', $fallback_html);
        ?>
            </div>
        </div>
        
        <!-- Tab News Widget -->
        <div class="sidebar-widget">
            <div class="tab-news">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#sidebar-featured"><?php esc_html_e('Featured', 'hot-news'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#sidebar-popular"><?php esc_html_e('Popular', 'hot-news'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#sidebar-latest"><?php esc_html_e('Latest', 'hot-news'); ?></a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="sidebar-featured" class="container tab-pane active">
                        <?php
        $featured_posts = hot_news_get_featured_posts(5);
        foreach ($featured_posts as $post) :
            setup_postdata($post); ?>
                            <div class="tn-news">
                                <div class="tn-img">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('news-small'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $featured_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="tn-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                            </div>
                        <?php endforeach;
        wp_reset_postdata(); ?>
                    </div>
                    <div id="sidebar-popular" class="container tab-pane fade">
                        <?php
        $popular_posts = hot_news_get_popular_posts(5);
        foreach ($popular_posts as $post) :
            setup_postdata($post); ?>
                            <div class="tn-news">
                                <div class="tn-img">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('news-small'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $popular_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="tn-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                            </div>
                        <?php endforeach;
        wp_reset_postdata(); ?>
                    </div>
                    <div id="sidebar-latest" class="container tab-pane fade">
                        <?php
        $latest_posts = get_posts(array('posts_per_page' => 5));
        foreach ($latest_posts as $post) :
            setup_postdata($post); ?>
                            <div class="tn-news">
                                <div class="tn-img">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('news-small'); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $latest_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="tn-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                            </div>
                        <?php endforeach;
        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categories Widget -->
        <div class="sidebar-widget">
            <h2 class="sw-title"><?php esc_html_e('News Category', 'hot-news'); ?></h2>
            <div class="category">
                <ul>
                    <?php
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                        'number'  => 7,
                    ));

        if (!empty($categories)) {
            foreach ($categories as $category) {
                echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a><span>(' . $category->count . ')</span></li>';
            }
        } else {
            // Display sample categories if no real categories exist
            $sample_categories = hot_news_get_sample_data('categories');
            foreach ($sample_categories as $category) {
                echo '<li><a href="#">' . esc_html($category['name']) . '</a><span>(' . $category['count'] . ')</span></li>';
            }
        }
        ?>
                </ul>
            </div>
        </div>

        <!-- Tags Widget -->
        <div class="sidebar-widget">
            <h2 class="sw-title"><?php esc_html_e('Tags Cloud', 'hot-news'); ?></h2>
            <div class="tags">
                <?php
                $tags = get_tags(array(
        'orderby' => 'count',
        'order'   => 'DESC',
        'number'  => 10,
                ));

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
            }
        } else {
            // Display sample tags if no real tags exist
            $sample_tags = hot_news_get_sample_data('tags');
            foreach ($sample_tags as $tag) {
                echo '<a href="#">' . esc_html($tag) . '</a>';
            }
        }
        ?>
            </div>
        </div>
        
    <?php endif; ?>
</div>
