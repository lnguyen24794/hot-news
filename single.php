<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Hot_News
 */

get_header();
?>

<!-- Breadcrumb Start -->
<div class="breadcrumb-wrap">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Trang chủ', 'hot-news'); ?></a></li>
                <?php
                $categories = get_the_category();
if (!empty($categories)) {
    $category = $categories[0];
    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
}
?>
                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Single News Start-->
<div class="news-feed-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
while (have_posts()) :
    the_post();
    ?>
                <article class="single-post-card">
                    <div class="post-header">
                        <?php hot_news_display_badge(); ?>
                        <h1 class="post-title"><?php the_title(); ?></h1>
                        <?php hot_news_display_meta(); ?>
                    </div>
                    
                    <?php if (has_post_thumbnail()) :
                        $sensitive_class = hot_news_get_sensitive_class();
                        $is_sensitive = hot_news_is_sensitive_content();
                        ?>
                        <div class="post-featured-image <?php echo $is_sensitive ? 'sensitive-wrapper' : ''; ?>" <?php echo hot_news_get_sensitive_wrapper_attr(); ?>>
                            <?php
                                $thumbnail_class = 'img-fluid';
                        if ($sensitive_class) {
                            $thumbnail_class .= ' ' . $sensitive_class;
                        }
                        the_post_thumbnail('large', array('class' => $thumbnail_class));

                        // Render overlay for sensitive content
                        if ($is_sensitive) {
                            echo hot_news_render_sensitive_overlay();
                        }
                        ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <div class="entry-content">
                            <?php
            the_content();

    wp_link_pages(array(
        'before' => '<div class="page-links">' . esc_html__('Trang:', 'hot-news'),
        'after'  => '</div>',
    ));
    ?>
                        </div><!-- .entry-content -->
                        
                        <?php hot_news_display_like_dislike_buttons(); ?>
                        
                        <!-- Post Tags -->
                        <?php
                        $post_tags = get_the_tags();
    if ($post_tags) : ?>
                            <div class="post-tags">
                                <h4><i class="fas fa-tags"></i> Thẻ từ khóa:</h4>
                                <div class="tags-list">
                                    <?php foreach ($post_tags as $tag) : ?>
                                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Post Navigation -->
                        <div class="post-navigation">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php
                $prev_post = get_previous_post();
    if ($prev_post) : ?>
                                        <div class="nav-post nav-prev">
                                            <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-link">
                                                <span class="nav-direction">
                                                    <i class="fas fa-chevron-left"></i> Bài trước
                                                </span>
                                                <span class="nav-title"><?php echo wp_trim_words($prev_post->post_title, 8); ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <?php
    $next_post = get_next_post();
    if ($next_post) : ?>
                                        <div class="nav-post nav-next">
                                            <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-link">
                                                <span class="nav-direction">
                                                    Bài tiếp <i class="fas fa-chevron-right"></i>
                                                </span>
                                                <span class="nav-title"><?php echo wp_trim_words($next_post->post_title, 8); ?></span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Related Posts Section -->
                <?php
                // Related posts section
                $related_posts = hot_news_get_related_posts(get_the_ID(), 3);
    if (!empty($related_posts)) : ?>
                    <div class="related-posts-section">
                        <div class="widget-card">
                            <h3 class="widget-title">
                                <i class="fas fa-newspaper"></i> <?php esc_html_e('Tin liên quan', 'hot-news'); ?>
                            </h3>
                            <div class="row">
                                <?php foreach ($related_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="col-md-4">
                                        <div class="related-post-item">
                                            <div class="related-post-image">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('news-medium', array('class' => 'img-fluid')); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?php the_permalink(); ?>">
                                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $related_posts) % 5) + 1) . '.jpg'); ?>" 
                                                             alt="<?php the_title_attribute(); ?>" class="img-fluid">
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="related-post-content">
                                                <h5 class="related-post-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h5>
                                                <div class="related-post-meta">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar"></i> <?php echo get_the_date('d/m/Y'); ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
    wp_reset_postdata(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                    <?php

endwhile; // End of the loop.
?>
            </div>

            <div class="col-lg-4">
                <div class="advertisement-sidebar">
                    <div class="sticky-ads">
                        <!-- Google AdSense Single Sidebar Ad -->
                        <div class="ad-banner mb-4">
                            <div class="ad-label">Quảng cáo</div>
                            <?php
                            $fallback_html = '';
$sidebar_ad_image = get_theme_mod('hot_news_sidebar_ad_image');
$sidebar_ad_url = get_theme_mod('hot_news_sidebar_ad_url');

if ($sidebar_ad_image) {
    if ($sidebar_ad_url) {
        $fallback_html = '<a href="' . esc_url($sidebar_ad_url) . '" target="_blank" rel="noopener">';
        $fallback_html .= '<img src="' . esc_url($sidebar_ad_image) . '" alt="' . esc_attr__('Advertisement', 'hot-news') . '" class="img-fluid">';
        $fallback_html .= '</a>';
    } else {
        $fallback_html = '<img src="' . esc_url($sidebar_ad_image) . '" alt="' . esc_attr__('Advertisement', 'hot-news') . '" class="img-fluid">';
    }
} else {
    $fallback_html = '<a href="#" target="_blank" rel="noopener">';
    $fallback_html .= '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/ads-1.jpg') . '" alt="Quảng cáo" class="img-fluid">';
    $fallback_html .= '</a>';
}

hot_news_display_ad('single_sidebar_ad', $fallback_html);
?>
                        </div>
                        
                        <!-- Tab News Widget -->
                        <div class="tab-news-widget mb-4">
                            <div class="widget-card">
                                <ul class="nav nav-pills nav-justified mb-3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="pill" href="#single-featured"><?php esc_html_e('Nổi bật', 'hot-news'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#single-popular"><?php esc_html_e('Phổ biến', 'hot-news'); ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="pill" href="#single-latest"><?php esc_html_e('Mới nhất', 'hot-news'); ?></a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div id="single-featured" class="tab-pane active">
                                        <?php
            $featured_posts = hot_news_get_featured_posts(5);
foreach ($featured_posts as $post) :
    setup_postdata($post); ?>
                                            <div class="tab-news-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <?php the_post_thumbnail('news-small', array('class' => 'img-fluid')); ?>
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $featured_posts) % 5) + 1) . '.jpg'); ?>" 
                                                                     alt="<?php the_title_attribute(); ?>" class="img-fluid">
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="tab-news-content">
                                                            <h6 class="tab-news-title">
                                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar"></i> <?php echo get_the_date('d/m/Y'); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
wp_reset_postdata(); ?>
                                    </div>
                                    <div id="single-popular" class="tab-pane fade">
                                        <?php
$popular_posts = hot_news_get_popular_posts(5);
foreach ($popular_posts as $post) :
    setup_postdata($post); ?>
                                            <div class="tab-news-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <?php the_post_thumbnail('news-small', array('class' => 'img-fluid')); ?>
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $popular_posts) % 5) + 1) . '.jpg'); ?>" 
                                                                     alt="<?php the_title_attribute(); ?>" class="img-fluid">
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="tab-news-content">
                                                            <h6 class="tab-news-title">
                                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-eye"></i> <?php echo number_format(get_post_meta(get_the_ID(), '_post_views', true) ?: 0); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
wp_reset_postdata(); ?>
                                    </div>
                                    <div id="single-latest" class="tab-pane fade">
                                        <?php
$latest_posts = get_posts(array('posts_per_page' => 5));
foreach ($latest_posts as $post) :
    setup_postdata($post); ?>
                                            <div class="tab-news-item">
                                                <div class="row no-gutters">
                                                    <div class="col-4">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <?php the_post_thumbnail('news-small', array('class' => 'img-fluid')); ?>
                                                            </a>
                                                        <?php else : ?>
                                                            <a href="<?php the_permalink(); ?>">
                                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $latest_posts) % 5) + 1) . '.jpg'); ?>" 
                                                                     alt="<?php the_title_attribute(); ?>" class="img-fluid">
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="tab-news-content">
                                                            <h6 class="tab-news-title">
                                                                <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 8); ?></a>
                                                            </h6>
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar"></i> <?php echo get_the_date('d/m/Y'); ?>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach;
wp_reset_postdata(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories Widget -->
                        <div class="categories-widget mb-4">
                            <div class="widget-card">
                                <h4 class="widget-title">
                                    <i class="fas fa-folder"></i> <?php esc_html_e('Danh mục tin tức', 'hot-news'); ?>
                                </h4>
                                <div class="categories-list">
                                    <?php
                                    $categories = get_categories(array(
'orderby' => 'count',
'order'   => 'DESC',
'number'  => 8,
                                    ));

foreach ($categories as $category) {
    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">';
    echo '<span class="category-name">' . esc_html($category->name) . '</span>';
    echo '<span class="category-count">' . $category->count . '</span>';
    echo '</a>';
}
?>
                                </div>
                            </div>
                        </div>

                        <!-- Tags Widget -->
                        <div class="tags-widget">
                            <div class="widget-card">
                                <h4 class="widget-title">
                                    <i class="fas fa-tags"></i> <?php esc_html_e('Thẻ từ khóa', 'hot-news'); ?>
                                </h4>
                                <div class="tags-list">
                                    <?php
$current_post_tags = get_the_tags();
if ($current_post_tags) {
    foreach ($current_post_tags as $tag) {
        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link">' . esc_html($tag->name) . '</a>';
    }
} else {
    // Show general tags if post has no tags
    $tags = get_tags(array(
        'orderby' => 'count',
        'order'   => 'DESC',
        'number'  => 12,
    ));

    foreach ($tags as $tag) {
        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link">' . esc_html($tag->name) . '</a>';
    }
}
?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Single News End-->

<?php
get_footer();
