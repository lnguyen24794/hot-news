<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(__('Author: %s', 'hot-news'), '<span class="vcard">' . get_the_author() . '</span>');
                    } elseif (is_day()) {
                        printf(__('Day: %s', 'hot-news'), '<span>' . get_the_date() . '</span>');
                    } elseif (is_month()) {
                        printf(__('Month: %s', 'hot-news'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'hot-news')) . '</span>');
                    } elseif (is_year()) {
                        printf(__('Year: %s', 'hot-news'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'hot-news')) . '</span>');
                    } else {
                        esc_html_e('Archives', 'hot-news');
                    }
?>
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
            if (is_category()) {
                printf(__('Category: %s', 'hot-news'), '<span>' . single_cat_title('', false) . '</span>');
            } elseif (is_tag()) {
                printf(__('Tag: %s', 'hot-news'), '<span>' . single_tag_title('', false) . '</span>');
            } elseif (is_author()) {
                printf(__('Author: %s', 'hot-news'), '<span class="vcard">' . get_the_author() . '</span>');
            } elseif (is_day()) {
                printf(__('Day: %s', 'hot-news'), '<span>' . get_the_date() . '</span>');
            } elseif (is_month()) {
                printf(__('Month: %s', 'hot-news'), '<span>' . get_the_date(_x('F Y', 'monthly archives date format', 'hot-news')) . '</span>');
            } elseif (is_year()) {
                printf(__('Year: %s', 'hot-news'), '<span>' . get_the_date(_x('Y', 'yearly archives date format', 'hot-news')) . '</span>');
            } else {
                esc_html_e('Archives', 'hot-news');
            }
?>
                            </h1>
                            <?php
                            if (is_category() || is_tag()) {
                                $description = term_description();
                                if (!empty($description)) {
                                    echo '<div class="archive-description">' . $description . '</div>';
                                }
                            }
?>
                        </header><!-- .page-header -->
                        
                        <div class="row">
                            <?php
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
                                    <div class="excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                </div>
                                <?php
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
                        
                        <div class="no-results">
                            <h1><?php esc_html_e('Nothing here', 'hot-news'); ?></h1>
                            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'hot-news'); ?></p>
                            <?php get_search_form(); ?>
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
