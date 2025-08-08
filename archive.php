<?php
/**
 * Archive template (for categories, tags, date archives)
 * 
 * This template is used when WordPress can't find a more specific template
 * 
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <!-- Modern Blog Header -->
    <section class="modern-blog-header section">
        <div class="container">
            <div class="blog-header-content">
                <!-- Minimal Breadcrumb -->
                <nav class="minimal-breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                    </a>
                    <span class="breadcrumb-separator">→</span>
                    <span class="breadcrumb-current"><?php echo devlog_get_blog_page_title(); ?></span>
                </nav>
                
                <!-- Clean Title Section -->
                <div class="blog-title-section">
                    <h1 class="modern-blog-title"><?php echo devlog_get_blog_page_title(); ?></h1>
                    <?php if (devlog_get_blog_page_description()) : ?>
                        <p class="modern-blog-description">
                            <?php echo devlog_get_blog_page_description(); ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Compact Search & Filter -->
                <div class="blog-controls">
                    <div class="search-wrapper">
                        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="modern-search-form">
                            <input type="search" 
                                   placeholder="Ara..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   class="modern-search-input">
                            <button type="submit" class="modern-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="category-pills">
                        <?php if (get_option('page_for_posts')) : ?>
                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="category-pill">
                                Tümü
                            </a>
                        <?php endif; ?>
                        <?php 
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'hide_empty' => true,
                            'number' => 6
                        ));
                        
                        foreach ($categories as $category) :
                            $is_active = is_category($category->term_id) ? 'active' : '';
                        ?>
                            <a href="<?php echo get_category_link($category->term_id); ?>" class="category-pill <?php echo $is_active; ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Blog Posts Grid -->
    <section class="modern-blog-posts section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="modern-posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('modern-post-card'); ?>>
                            <div class="post-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="image-link">
                                        <?php the_post_thumbnail('large', array(
                                            'alt' => get_the_title(),
                                            'class' => 'post-image'
                                        )); ?>
                                        <div class="image-overlay">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="image-placeholder">
                                        <div class="placeholder-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="image-overlay">
                                            <i class="fas fa-arrow-right"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    $main_category = $categories[0];
                                ?>
                                    <div class="post-category-badge">
                                        <?php echo esc_html($main_category->name); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-card-content">
                                <div class="post-meta-minimal">
                                    <span class="post-date">
                                        <?php echo get_the_date('j M Y'); ?>
                                    </span>
                                    <span class="post-reading-time">
                                        <?php echo devlog_reading_time(get_the_ID()); ?>
                                    </span>
                                </div>
                                
                                <h2 class="post-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="post-excerpt">
                                    <?php 
                                    if (has_excerpt()) {
                                        echo wp_trim_words(get_the_excerpt(), 20, '...');
                                    } else {
                                        echo wp_trim_words(get_the_content(), 20, '...');
                                    }
                                    ?>
                                </div>
                                
                                <div class="post-card-footer">
                                    <div class="post-author">
                                        <span class="author-name"><?php the_author(); ?></span>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="read-more-minimal">
                                        Oku
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Clean Pagination -->
                <div class="modern-pagination">
                    <?php
                    echo paginate_links(array(
                        'mid_size' => 1,
                        'prev_text' => '<i class="fas fa-chevron-left"></i>',
                        'next_text' => '<i class="fas fa-chevron-right"></i>',
                        'type' => 'list'
                    ));
                    ?>
                </div>
                
            <?php else : ?>
                <div class="no-posts-modern">
                    <div class="no-posts-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h2 class="no-posts-title">Henüz yazı yok</h2>
                    <p class="no-posts-description">
                        <?php if (is_category()) : ?>
                            "<strong><?php single_cat_title(); ?></strong>" kategorisinde henüz yazı bulunamadı.
                        <?php elseif (is_tag()) : ?>
                            "<strong><?php single_tag_title(); ?></strong>" etiketiyle yazı bulunamadı.
                        <?php else : ?>
                            Bu arşivde henüz yazı bulunmuyor.
                        <?php endif; ?>
                    </p>
                    <?php if (get_option('page_for_posts')) : ?>
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn--primary">
                            Tüm Yazıları Gör
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                            Ana Sayfaya Dön
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
