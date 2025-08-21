<?php
/**
 * Template for displaying search forms in Hot News
 *
 * @package Hot_News
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-wrapper" style="position: relative;">
        <input type="text" 
               class="search-field" 
               placeholder="<?php echo esc_attr_x('Search news...', 'placeholder', 'hot-news'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               style="width: 100%; height: 40px; padding: 0 50px 0 15px; color: #666666; border: 1px solid #cccccc; border-radius: 4px;">
        <button type="submit" 
                class="search-submit" 
                style="position: absolute; width: 40px; height: 40px; top: 0; right: 0; border: none; background: none; color: #FF6F61; border-radius: 0 4px 4px 0; cursor: pointer;">
            <i class="fa fa-search"></i>
            <span class="screen-reader-text"><?php echo _x('Search', 'submit button', 'hot-news'); ?></span>
        </button>
    </div>
</form>
