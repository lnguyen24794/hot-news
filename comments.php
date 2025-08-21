<?php
/**
 * The template for displaying comments
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Hot_News
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    // You can start editing here -- including this comment!
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            $hot_news_comment_count = get_comments_number();
        if ('1' === $hot_news_comment_count) {
            printf(
                /* translators: 1: title. */
                esc_html__('One thought on &ldquo;%1$s&rdquo;', 'hot-news'),
                '<span>' . get_the_title() . '</span>'
            );
        } else {
            printf( // WPCS: XSS OK.
                /* translators: 1: comment count number, 2: title. */
                esc_html(_nx('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $hot_news_comment_count, 'comments title', 'hot-news')),
                number_format_i18n($hot_news_comment_count),
                '<span>' . get_the_title() . '</span>'
            );
        }
?>
        </h2><!-- .comments-title -->

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
wp_list_comments(array(
    'style'      => 'ol',
    'short_ping' => true,
    'walker'     => new Hot_News_Walker_Comment(),
));
?>
        </ol><!-- .comment-list -->

        <?php
        the_comments_navigation();

// If comments are closed and there are comments, let's leave a little note, shall we?
if (!comments_open()) :
    ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'hot-news'); ?></p>
            <?php
endif;

endif; // Check for have_comments().

// Comment form
$comment_form_args = array(
    'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
    'title_reply_after'  => '</h3>',
    'comment_field' => '<div class="form-group">' .
                      '<textarea id="comment" name="comment" class="form-control" rows="5" placeholder="' . esc_attr__('Your Comment *', 'hot-news') . '" required></textarea>' .
                      '</div>',
    'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary">%4$s</button>',
    'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
    'format' => 'html5',
);

comment_form($comment_form_args);
?>

</div><!-- #comments -->
