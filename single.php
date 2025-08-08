<?php
/**
 * The template for displaying single posts
 *
 * @package DevLog
 * @version 1.0.0
 */

get_header(); 

// Track post views
devlog_track_post_views(get_the_ID());
?>

<main class="main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        
        <!-- Article Hero Section -->
        <section class="article-hero">
            <div class="container">
                <?php devlog_breadcrumbs(); ?>
                
                <div class="article-hero__content">
                    <div class="article-meta">
                        <div class="article-meta__categories">
                            <?php echo devlog_get_post_categories(); ?>
                        </div>
                        <time class="article-meta__date" datetime="<?php echo get_the_date('c'); ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo get_the_date('d M Y'); ?>
                        </time>
                        <span class="article-meta__reading-time">
                            <i class="fas fa-clock"></i>
                            <?php echo devlog_get_reading_time(); ?> dk okuma
                        </span>
                    </div>
                    
                    <h1 class="article-title"><?php the_title(); ?></h1>
                    
                    <div class="article-author">
                        <div class="article-author__avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 48); ?>
                        </div>
                        <div class="article-author__info">
                            <span class="article-author__name"><?php the_author(); ?></span>
                            <span class="article-author__title">Developer & Blogger</span>
                        </div>
                    </div>
                </div>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="article-featured-image-wrapper">
                        <div class="article-featured-image">
                            <?php the_post_thumbnail('large', array('class' => 'article-image')); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        
        <!-- Article Content -->
        <section class="article-content">
            <div class="container">
                <div class="article-layout">
                    <article class="article-main">
                        <div class="article-body">
                            <?php the_content(); ?>
                        </div>
                        
                        <div class="article-tags">
                            <?php if (has_tag()) : ?>
                                <h3 class="article-tags__title">
                                    <i class="fas fa-tags"></i>
                                    Etiketler
                                </h3>
                                <div class="article-tags__list">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<a href="' . get_tag_link($tag->term_id) . '" class="tag-item">#' . $tag->name . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="article-share">
                            <h3 class="article-share__title">
                                <i class="fas fa-share-alt"></i>
                                Paylaş
                            </h3>
                            <div class="article-share__buttons">
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                   class="share-btn share-btn--twitter" target="_blank" rel="noopener">
                                    <i class="fab fa-twitter"></i>
                                    Twitter
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                   class="share-btn share-btn--linkedin" target="_blank" rel="noopener">
                                    <i class="fab fa-linkedin"></i>
                                    LinkedIn
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                   class="share-btn share-btn--facebook" target="_blank" rel="noopener">
                                    <i class="fab fa-facebook"></i>
                                    Facebook
                                </a>
                                <button class="share-btn share-btn--copy" onclick="copyToClipboard('<?php echo get_permalink(); ?>')">
                                    <i class="fas fa-link"></i>
                                    Linki Kopyala
                                </button>
                            </div>
                        </div>
                        
                        <?php devlog_post_navigation(); ?>
                    </article>
                    
                    <aside class="article-sidebar">
                        <div class="sidebar-widget sidebar-widget--author">
                            <div class="author-card">
                                <div class="author-card__avatar">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                                </div>
                                <div class="author-card__content">
                                    <h3 class="author-card__name"><?php the_author(); ?></h3>
                                    <p class="author-card__bio"><?php echo get_the_author_meta('description') ?: 'Backend Developer & Tech Blogger'; ?></p>
                                    <div class="author-card__social">
                                        <a href="#" class="author-social-link">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="#" class="author-social-link">
                                            <i class="fab fa-linkedin"></i>
                                        </a>
                                        <a href="#" class="author-social-link">
                                            <i class="fab fa-github"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sidebar-widget sidebar-widget--toc">
                            <h3 class="sidebar-widget__title">
                                <i class="fas fa-list"></i>
                                İçindekiler
                            </h3>
                            <nav class="table-of-contents" id="table-of-contents">
                                <!-- TOC will be generated by JavaScript -->
                            </nav>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        
        <!-- Comments Section -->
        <?php if (comments_open() || get_comments_number()) : ?>
        <section class="comments-section">
            <div class="container">
                <div class="comments-wrapper">
                    <?php comments_template(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
        
    <?php endwhile; endif; ?>
</main>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You can add a toast notification here
        console.log('Link kopyalandı!');
    });
}

// Generate Table of Contents
document.addEventListener('DOMContentLoaded', function() {
    const headings = document.querySelectorAll('.article-body h1, .article-body h2, .article-body h3, .article-body h4');
    const toc = document.getElementById('table-of-contents');
    
    if (headings.length > 0 && toc) {
        let tocHTML = '<ul class="toc-list">';
        
        headings.forEach((heading, index) => {
            const id = 'heading-' + index;
            heading.id = id;
            
            const level = heading.tagName.toLowerCase();
            const text = heading.textContent;
            
            tocHTML += `<li class="toc-item toc-item--${level}">
                <a href="#${id}" class="toc-link">${text}</a>
            </li>`;
        });
        
        tocHTML += '</ul>';
        toc.innerHTML = tocHTML;
    } else if (toc) {
        toc.style.display = 'none';
    }
});
</script>

<?php get_footer(); ?>
