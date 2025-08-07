<?php
/**
 * The template for displaying pages
 *
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <div class="container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <?php devlog_breadcrumbs(); ?>
            
            <article class="page-content">
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="page-body">
                    <?php the_content(); ?>
                </div>
            </article>
            
        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
