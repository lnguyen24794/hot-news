<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Hot_News
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (is_home() && !is_paged()) : ?>
        
        <!-- Top News Start-->
        <div class="top-news">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 tn-left">
                        <div class="row tn-slider">
                            <?php
                            $featured_posts = hot_news_get_featured_posts(2);
                            foreach ($featured_posts as $post) :
                                setup_postdata($post);
                                ?>
                                <div class="col-md-6">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-large'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-450x350-1.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <div class="col-md-6 tn-right">
                        <div class="row">
                            <?php
                            $recent_posts = get_posts(array(
                                'posts_per_page' => 4,
                                'post_status' => 'publish'
                            ));
                            foreach ($recent_posts as $post) :
                                setup_postdata($post);
                                ?>
                                <div class="col-md-6">
                                    <div class="tn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('news-medium'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $recent_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                            wp_reset_postdata(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Top News End-->

        <!-- Category News Start-->
        <?php
        $featured_categories = array(
            get_theme_mod('hot_news_featured_category_1', ''),
            get_theme_mod('hot_news_featured_category_2', ''),
        );
        
        $category_count = 0;
        foreach ($featured_categories as $category_slug) :
            if (empty($category_slug)) continue;
            
            $category = get_category_by_slug($category_slug);
            if (!$category) continue;
            
            $category_posts = hot_news_get_category_posts($category_slug, 3);
            if (empty($category_posts)) continue;
            
            if ($category_count % 2 == 0) echo '<div class="cat-news"><div class="container"><div class="row">';
            ?>
            
            <div class="col-md-6">
                <h2><?php echo esc_html($category->name); ?></h2>
                <div class="row cn-slider">
                    <?php foreach ($category_posts as $post) :
                        setup_postdata($post); ?>
                        <div class="col-md-6">
                            <div class="cn-img">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('news-medium'); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $category_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                <?php endif; ?>
                                <div class="cn-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                    wp_reset_postdata(); ?>
                </div>
            </div>
            
            <?php
            $category_count++;
            if ($category_count % 2 == 0) echo '</div></div></div>';
        endforeach;
        
        // Close the last row if odd number of categories
        if ($category_count % 2 == 1) echo '</div></div></div>';
        ?>
        <!-- Category News End-->
        
        <!-- Tab News Start-->
        <div class="tab-news">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#featured"><?php esc_html_e('Featured News', 'hot-news'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#popular"><?php esc_html_e('Popular News', 'hot-news'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#latest"><?php esc_html_e('Latest News', 'hot-news'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="featured" class="container tab-pane active">
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
                            <div id="popular" class="container tab-pane fade">
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
                            <div id="latest" class="container tab-pane fade">
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
                    
                    <div class="col-md-6">
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#m-viewed"><?php esc_html_e('Most Viewed', 'hot-news'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#m-read"><?php esc_html_e('Most Read', 'hot-news'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#m-recent"><?php esc_html_e('Most Recent', 'hot-news'); ?></a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="m-viewed" class="container tab-pane active">
                                <?php
                                $viewed_posts = hot_news_get_popular_posts(5);
                                foreach ($viewed_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="tn-news">
                                        <div class="tn-img">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('news-small'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $viewed_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </div>
                            <div id="m-read" class="container tab-pane fade">
                                <?php
                                $read_posts = get_posts(array('posts_per_page' => 5, 'orderby' => 'comment_count'));
                                foreach ($read_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="tn-news">
                                        <div class="tn-img">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('news-small'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $read_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="tn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </div>
                            <div id="m-recent" class="container tab-pane fade">
                                <?php
                                $recent_posts = get_posts(array('posts_per_page' => 5));
                                foreach ($recent_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="tn-news">
                                        <div class="tn-img">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('news-small'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $recent_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
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
            </div>
        </div>
        <!-- Tab News End-->

    <?php endif; ?>

    <!-- Main News Start-->
    <div class="main-news">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <?php if (is_home() && !is_paged()) : ?>
                        <h2 style="margin-bottom: 30px; padding-bottom: 15px; border-bottom: 3px double #000000;"><?php esc_html_e('Latest News', 'hot-news'); ?></h2>
                    <?php endif; ?>
                    
                    <div class="row">
                        <?php
                        if (have_posts()) :
                            $post_count = 0;
                            while (have_posts()) :
                                the_post();
                                $post_count++;
                                ?>
                                <div class="col-md-4">
                                    <div class="mn-img">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('news-medium'); ?>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . (($post_count % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                            </a>
                                        <?php endif; ?>
                                        <div class="mn-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                    <?php hot_news_display_meta(); ?>
                                    <?php hot_news_display_badge(); ?>
                                </div>
                                <?php
                            endwhile;
                        else :
                            ?>
                            <div class="col-12">
                                <p><?php esc_html_e('Sorry, no posts were found.', 'hot-news'); ?></p>
                            </div>
                            <?php
                        endif;
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
