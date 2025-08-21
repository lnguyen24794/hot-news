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
                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'hot-news'); ?></a></li>
                <?php
                $categories = get_the_category();
                if (!empty($categories)) {
                    $category = $categories[0];
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
                ?>
                <li class="breadcrumb-item active" aria-current="page"><?php esc_html_e('News details', 'hot-news'); ?></li>
            </ol>
        </nav>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Single News Start-->
<div class="single-news">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('sn-container'); ?>>
                        
                        <?php hot_news_display_badge(); ?>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="sn-img">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="sn-content">
                            <h1 class="sn-title"><?php the_title(); ?></h1>
                            
                            <?php hot_news_display_meta(); ?>
                            
                            <div class="entry-content">
                                <?php
                                the_content(sprintf(
                                    wp_kses(
                                        /* translators: %s: Name of current post. Only visible to screen readers */
                                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'hot-news'),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    get_the_title()
                                ));

                                wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'hot-news'),
                                    'after'  => '</div>',
                                ));
                                ?>
                            </div><!-- .entry-content -->

                            <footer class="entry-footer">
                                <?php hot_news_entry_footer(); ?>
                            </footer><!-- .entry-footer -->
                            
                            <?php hot_news_social_share(); ?>
                        </div>
                    </article><!-- #post-<?php the_ID(); ?> -->

                    <?php
                    // Related posts section
                    $related_posts = hot_news_get_related_posts(get_the_ID(), 3);
                    if (!empty($related_posts)) : ?>
                        <div class="sn-related">
                            <h2><?php esc_html_e('Related News', 'hot-news'); ?></h2>
                            <div class="row sn-slider">
                                <?php foreach ($related_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="col-md-4">
                                        <div class="sn-img">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('news-medium'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $related_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                            <div class="sn-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </div>

            <div class="col-lg-4">
                <div class="sidebar">
                    
                    <!-- In This Category Widget -->
                    <div class="sidebar-widget">
                        <h2 class="sw-title"><?php esc_html_e('In This Category', 'hot-news'); ?></h2>
                        <div class="news-list">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                $category_posts = get_posts(array(
                                    'posts_per_page' => 5,
                                    'post__not_in' => array(get_the_ID()),
                                    'category__in' => array($categories[0]->term_id)
                                ));
                                
                                foreach ($category_posts as $post) :
                                    setup_postdata($post); ?>
                                    <div class="nl-item">
                                        <div class="nl-img">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('news-small'); ?>
                                            <?php else : ?>
                                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-350x223-' . ((array_search($post, $category_posts) % 5) + 1) . '.jpg'); ?>" alt="<?php the_title_attribute(); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="nl-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </div>
                                    </div>
                                <?php endforeach;
                                wp_reset_postdata();
                            }
                            ?>
                        </div>
                    </div>
                    
                    <!-- Advertisement Widget -->
                    <div class="sidebar-widget">
                        <div class="image">
                            <?php 
                            $sidebar_ad_image = get_theme_mod('hot_news_sidebar_ad_image');
                            $sidebar_ad_url = get_theme_mod('hot_news_sidebar_ad_url');
                            
                            if ($sidebar_ad_image) :
                                if ($sidebar_ad_url) : ?>
                                    <a href="<?php echo esc_url($sidebar_ad_url); ?>" target="_blank" rel="noopener">
                                        <img src="<?php echo esc_url($sidebar_ad_image); ?>" alt="<?php esc_attr_e('Advertisement', 'hot-news'); ?>">
                                    </a>
                                <?php else : ?>
                                    <img src="<?php echo esc_url($sidebar_ad_image); ?>" alt="<?php esc_attr_e('Advertisement', 'hot-news'); ?>">
                                <?php endif;
                            else : ?>
                                <div class="ad-placeholder" style="background: #f5f5f5; padding: 40px 20px; text-align: center; color: #666; border: 2px dashed #ddd;">
                                    <p><?php esc_html_e('Advertisement Space', 'hot-news'); ?></p>
                                    <small><?php esc_html_e('Configure in Customizer', 'hot-news'); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Tab News Widget -->
                    <div class="sidebar-widget">
                        <div class="tab-news">
                            <ul class="nav nav-pills nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="pill" href="#single-featured"><?php esc_html_e('Featured', 'hot-news'); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#single-popular"><?php esc_html_e('Popular', 'hot-news'); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="pill" href="#single-latest"><?php esc_html_e('Latest', 'hot-news'); ?></a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div id="single-featured" class="container tab-pane active">
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
                                <div id="single-popular" class="container tab-pane fade">
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
                                <div id="single-latest" class="container tab-pane fade">
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
                                
                                foreach ($categories as $category) {
                                    echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a><span>(' . $category->count . ')</span></li>';
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
                            $post_tags = get_the_tags();
                            if ($post_tags) {
                                foreach ($post_tags as $tag) {
                                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
                                }
                            } else {
                                // Show general tags if post has no tags
                                $tags = get_tags(array(
                                    'orderby' => 'count',
                                    'order'   => 'DESC',
                                    'number'  => 10,
                                ));
                                
                                foreach ($tags as $tag) {
                                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
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
<!-- Single News End-->

<?php
get_footer();
