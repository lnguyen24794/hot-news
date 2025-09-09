<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
                <li class="breadcrumb-item active" aria-current="page">
                    <?php printf(__('Search Results for: %s', 'hot-news'), '<span>' . get_search_query() . '</span>'); ?>
                </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<main id="primary" class="site-main">
    <!-- Main News Start-->
    <div class="main-news">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <?php if (have_posts()) : ?>
                        
                        <header class="page-header" style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 3px double #000000;">
                            <h1 class="page-title">
                                <?php
                                printf(
                                    /* translators: %s: search query. */
                                    esc_html__('Search Results for: %s', 'hot-news'),
                                    '<span>' . get_search_query() . '</span>'
                                );
                        ?>
                            </h1>
                            <p class="search-results-count">
                                <?php
                        global $wp_query;
                        printf(
                            /* translators: %d: number of search results. */
                            _n('Found %d result', 'Found %d results', $wp_query->found_posts, 'hot-news'),
                            $wp_query->found_posts
                        );
                        ?>
                            </p>
                        </header><!-- .page-header -->
                        
                        <div class="row">
                            <?php
                            $post_count = 0;
                        while (have_posts()) :
                            the_post();
                            $post_count++;
                            ?>
                                <div class="col-md-6">
                                    <div class="search-result-item" style="display: flex; margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="search-thumb" style="flex-shrink: 0; margin-right: 15px;">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('news-small', array('style' => 'width: 100px; height: 70px; object-fit: cover; border-radius: 3px;')); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        <div class="search-content" style="flex: 1;">
                                            <h3 style="font-size: 1.1rem; margin-bottom: 5px;">
                                                <a href="<?php the_permalink(); ?>" style="color: #000; text-decoration: none;">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            <?php hot_news_display_meta(); ?>
                                            <?php hot_news_display_badge(); ?>
                                            <div class="search-excerpt" style="font-size: 0.9rem; color: #666; margin-top: 5px;">
                                                <?php the_excerpt(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            if ($post_count % 2 == 0) {
                                echo '</div><div class="row">';
                            }
                        endwhile;
                        ?>
                        </div>
                        
                        <?php
                        // Pagination
                        the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => __('&laquo; Previous', 'hot-news'),
                        'next_text' => __('Next &raquo;', 'hot-news'),
                        ));
                        ?>
                        
                    <?php else : ?>
                        
                        <header class="page-header" style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 3px double #000000;">
                            <h1 class="page-title">
                                <?php
                                printf(
                                    /* translators: %s: search query. */
                                    esc_html__('Search Results for: %s', 'hot-news'),
                                    '<span>' . get_search_query() . '</span>'
                                );
                        ?>
                            </h1>
                        </header><!-- .page-header -->
                        
                        <div class="no-results" style="text-align: center; padding: 40px 20px; background: #f8f9fa; border-radius: 5px;">
                            <h2><?php esc_html_e('Nothing found', 'hot-news'); ?></h2>
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'hot-news'); ?></p>
                            
                            <div style="max-width: 400px; margin: 20px auto;">
                                <?php get_search_form(); ?>
                            </div>
                            
                            <div style="margin-top: 30px;">
                                <h4><?php esc_html_e('You might want to try:', 'hot-news'); ?></h4>
                                <ul style="list-style: none; padding: 0;">
                                    <li><?php esc_html_e('Check your spelling', 'hot-news'); ?></li>
                                    <li><?php esc_html_e('Try more general keywords', 'hot-news'); ?></li>
                                    <li><?php esc_html_e('Try different keywords', 'hot-news'); ?></li>
                                </ul>
                            </div>
                            
                            <div style="margin-top: 30px;">
                                <h4><?php esc_html_e('Browse Categories:', 'hot-news'); ?></h4>
                                <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 15px;">
                                    <?php
                            $categories = get_categories(array('number' => 6));
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" style="padding: 5px 10px; background: #FF6F61; color: white; text-decoration: none; border-radius: 3px; font-size: 0.9rem;">' . esc_html($category->name) . '</a>';
                        }
                        ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php endif; ?>
                </div>

                <div class="col-lg-3">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Main News End-->
</main><!-- #main -->

<?php
get_footer();
