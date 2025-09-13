<div class="shadow mt-3 mb-3 p-1 rounded-lg">
   <div class="row">
        <div class="col-md-4 pr-0 hot-news-image <?php echo $is_main ? 'max-350' : 'max-160'; ?>">
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
        <div class="hot-news-content col-md-8">
            <?php if ($is_main) : ?>
            <h4 class="card-title hot-news-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h4>
            <?php else : ?>
            <h6 class="hot-news-sm-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h6>
            <?php endif; ?>
        </div>
   </div>
</div>