<div class="card main-hot-news shadow">
    <div class="card-img-top hot-news-image text-center <?php echo $is_main ? 'max-350' : 'max-160'; ?>">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('news-full', array('class' => 'img-fluid')); ?>
            </a>
        <?php else : ?>
            <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/news-825x525.jpg'); ?>" alt="<?php the_title_attribute(); ?>" class="w-100">
            </a>
        <?php endif; ?>
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