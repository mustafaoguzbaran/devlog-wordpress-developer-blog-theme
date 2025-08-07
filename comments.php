<?php
/**
 * Comments Template
 *
 * @package DevLog
 * @version 1.0.0
 */

if (post_password_required()) {
    return;
}
?>

<div class="comments-area" id="comments">
    <?php if (have_comments()) : ?>
        <div class="comments-header">
            <h2 class="comments-title">
                <i class="fas fa-comments"></i>
                <?php
                $comments_number = get_comments_number();
                if ($comments_number == 1) {
                    echo '1 Yorum';
                } else {
                    echo $comments_number . ' Yorum';
                }
                ?>
            </h2>
        </div>

        <div class="comments-list">
            <?php
            wp_list_comments(array(
                'walker' => new DevLog_Comment_Walker(),
                'style' => 'div',
                'short_ping' => true,
                'avatar_size' => 60,
            ));
            ?>
        </div>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comments-pagination">
                <div class="nav-previous"><?php previous_comments_link('&larr; Önceki Yorumlar'); ?></div>
                <div class="nav-next"><?php next_comments_link('Sonraki Yorumlar &rarr;'); ?></div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <div class="comments-closed">
            <i class="fas fa-lock"></i>
            <p>Bu yazı için yorumlar kapatılmıştır.</p>
        </div>
    <?php endif; ?>

    <?php if (comments_open()) : ?>
        <div class="comment-form-wrapper">
            <h3 class="comment-form-title">
                <i class="fas fa-edit"></i>
                Yorum Yaz
            </h3>
            
            <?php
            $commenter = wp_get_current_commenter();
            $required_text = ' <span class="required">*</span>';
            
            $comment_form_args = array(
                'title_reply' => '',
                'title_reply_to' => 'Yanıtla: %s',
                'cancel_reply_link' => 'Yanıtı İptal Et',
                'label_submit' => 'Yorumu Gönder',
                'submit_button' => '<button type="submit" class="submit-button"><i class="fas fa-paper-plane"></i> %4$s</button>',
                'comment_field' => '<div class="form-group form-group--textarea">
                    <label for="comment">Yorumunuz' . $required_text . '</label>
                    <textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" required="required" placeholder="Düşüncelerinizi paylaşın..."></textarea>
                </div>',
                'fields' => array(
                    'author' => '<div class="form-row">
                        <div class="form-group">
                            <label for="author">İsim' . $required_text . '</label>
                            <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required="required" placeholder="Adınız">
                        </div>',
                    'email' => '<div class="form-group">
                            <label for="email">E-posta' . $required_text . '</label>
                            <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes" required="required" placeholder="E-posta adresiniz">
                        </div>',
                    'url' => '<div class="form-group">
                            <label for="url">Website</label>
                            <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" placeholder="Web siteniz (opsiyonel)">
                        </div>
                    </div>',
                ),
                'comment_notes_before' => '<div class="comment-notes">
                    <p><i class="fas fa-info-circle"></i> E-posta adresiniz yayınlanmayacaktır. Gerekli alanlar * ile işaretlenmiştir.</p>
                </div>',
                'comment_notes_after' => '',
            );
            
            comment_form($comment_form_args);
            ?>
        </div>
    <?php endif; ?>
</div>

<?php
/**
 * Custom Comment Walker
 */
class DevLog_Comment_Walker extends Walker_Comment {
    
    protected function html5_comment($comment, $depth, $args) {
        $tag = ('div' === $args['style']) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($this->has_children ? 'parent comment-item' : 'comment-item', $comment); ?>>
            <article class="comment-body">
                <div class="comment-meta">
                    <div class="comment-author">
                        <div class="comment-author-avatar">
                            <?php
                            if (0 != $args['avatar_size']) {
                                echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'avatar'));
                            }
                            ?>
                        </div>
                        <div class="comment-author-info">
                            <div class="comment-author-name">
                                <?php
                                $comment_author = get_comment_author_link($comment);
                                if ('0' == $comment->comment_approved) {
                                    echo '<span class="comment-pending">' . $comment_author . '</span>';
                                } else {
                                    echo $comment_author;
                                }
                                ?>
                            </div>
                            <div class="comment-metadata">
                                <time datetime="<?php comment_time('c'); ?>" class="comment-date">
                                    <i class="fas fa-clock"></i>
                                    <?php
                                    printf(
                                        '%1$s',
                                        sprintf(
                                            '<span>%1$s</span>',
                                            get_comment_date('d M Y H:i', $comment)
                                        )
                                    );
                                    ?>
                                </time>
                                <?php edit_comment_link('<i class="fas fa-edit"></i> Düzenle', '<span class="edit-link">', '</span>'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="comment-content">
                    <?php if ('0' == $comment->comment_approved) : ?>
                        <div class="comment-awaiting-moderation">
                            <i class="fas fa-hourglass-half"></i>
                            Yorumunuz onay bekliyor.
                        </div>
                    <?php endif; ?>

                    <?php comment_text(); ?>
                </div>

                <div class="comment-reply">
                    <?php
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'add_below' => 'div-comment',
                                'depth' => $depth,
                                'max_depth' => $args['max_depth'],
                                'before' => '<div class="reply-link">',
                                'after' => '</div>',
                                'reply_text' => '<i class="fas fa-reply"></i> Yanıtla'
                            )
                        )
                    );
                    ?>
                </div>
            </article>
        <?php
    }

    public function start_el(&$output, $comment, $depth = 0, $args = null, $id = 0) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;

        if (!empty($args['callback'])) {
            ob_start();
            call_user_func($args['callback'], $comment, $args, $depth);
            $output .= ob_get_clean();
            return;
        }

        if (('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) && $args['short_ping']) {
            ob_start();
            $this->ping($comment, $depth, $args);
            $output .= ob_get_clean();
        } elseif ('html5' === $args['format']) {
            ob_start();
            $this->html5_comment($comment, $depth, $args);
            $output .= ob_get_clean();
        } else {
            ob_start();
            $this->comment($comment, $depth, $args);
            $output .= ob_get_clean();
        }
    }

    protected function ping($comment, $depth, $args) {
        $tag = ('div' == $args['style']) ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('ping-item', $comment); ?>>
            <div class="ping-content">
                <i class="fas fa-link"></i>
                <?php _e('Pingback:'); ?> <?php comment_author_link($comment); ?> <?php edit_comment_link('<i class="fas fa-edit"></i>', '<span class="edit-link">', '</span>'); ?>
            </div>
        <?php
    }
}
?>
