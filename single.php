<?php
/**
 * The template for displaying single posts
 *
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <?php devlog_breadcrumbs(); ?>
            
            <article class="single-post-content">
                <header class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    <?php devlog_post_meta(); ?>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>
                </header>
                
                <div class="post-content">
                    <?php the_content(); ?>
                </div>
                
                <footer class="post-footer">
                    <div class="post-tags">
                        <?php echo devlog_get_post_categories(); ?>
                    </div>
                    
                    <?php devlog_post_navigation(); ?>
                </footer>
            </article>
            
            <?php if (comments_open() || get_comments_number()) : ?>
                <div class="comments-section">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>
            
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
