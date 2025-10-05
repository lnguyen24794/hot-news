<div class="card main-hot-news shadow" <?php echo hot_news_get_sensitive_wrapper_attr(); ?>>
    <div class="card-img-top hot-news-image text-center <?php echo $is_main ? 'max-350' : 'max-160'; ?>" style="position: relative;">
        <?php
        $sensitive_class = hot_news_get_sensitive_class();
$is_sensitive = hot_news_is_sensitive_content();
$thumbnail_class = 'img-fluid';
if ($sensitive_class) {
    $thumbnail_class .= ' ' . $sensitive_class;
}
?>
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('news-full', array('class' => $thumbnail_class)); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-825x525.jpg'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100 <?php echo $sensitive_class; ?>">
            </a>
        <?php endif; ?>
        <?php
// Render overlay for sensitive content
if ($is_sensitive) {
    echo hot_news_render_sensitive_overlay();
}
?>
    </div>
    <div class="card-body hot-news-content p-1">
        <?php if ($is_main) : ?>
        <h3 class="card-title hot-news-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <?php else : ?>
        <h6 class="hot-news-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h6>
        <?php endif; ?>
    </div>
</div>