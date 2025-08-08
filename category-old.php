<?php
/**
 * Category Archive template
 * 
 * Displays posts in a specific category
 * 
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <!-- Category Header Section -->
    <section class="blog-header section">
        <div class="container">
            <div class="blog-header__content">
                <div class="breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Ana Sayfa</a>
                    <span class="breadcrumb-separator">/</span>
                    <?php if (get_option('page_for_posts')) : ?>
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Blog</a>
                        <span class="breadcrumb-separator">/</span>
                    <?php endif; ?>
                    <span class="breadcrumb-current"><?php single_cat_title(); ?></span>
                </div>
                
                <h1 class="blog-header__title">
                    <i class="fas fa-folder-open"></i>
                    <?php single_cat_title(); ?>
                </h1>
                
                <?php 
                $category = get_queried_object();
                $category_count = $category->count;
                ?>
                
                <p class="blog-header__description">
                    <?php 
                    $category_description = category_description();
                    if ($category_description) :
                        echo strip_tags($category_description);
                    else :
                        echo single_cat_title('', false) . ' kategorisinde ' . $category_count . ' yazı bulunuyor.';
                    endif;
                    ?>
                </p>
                
                <!-- Search Form -->
                <div class="blog-search">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   placeholder="Bu kategoride ara..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   class="search-input">
                            <input type="hidden" name="category_name" value="<?php echo $category->slug; ?>">
                            <button type="submit" class="search-button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Category Filter -->
                <div class="blog-categories">
                    <?php if (get_option('page_for_posts')) : ?>
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="category-filter">
                            <i class="fas fa-th"></i>
                            Tümü
                        </a>
                    <?php endif; ?>
                    <?php 
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'hide_empty' => true
                    ));
                    
                    foreach ($categories as $cat) :
                        $is_active = ($cat->term_id == $category->term_id) ? 'active' : '';
                    ?>
                        <a href="<?php echo get_category_link($cat->term_id); ?>" class="category-filter <?php echo $is_active; ?>">
                            <i class="fas fa-tag"></i>
                            <?php echo esc_html($cat->name); ?>
                            <span class="category-count"><?php echo $cat->count; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Posts Section -->
    <section class="blog-posts section">
        <div class="container">
            <div class="blog-posts__wrapper">
                <!-- Main Content -->
                <div class="blog-posts__main">
                    <?php if (have_posts()) : ?>
                        <!-- Category info bar -->
                        <div class="category-info">
                            <div class="category-meta">
                                <span class="category-posts-count">
                                    <i class="fas fa-file-alt"></i>
                                    <?php echo $category_count; ?> yazı
                                </span>
                                <span class="category-created">
                                    <i class="fas fa-calendar-plus"></i>
                                    Kategori
                                </span>
                            </div>
                            
                            <div class="category-actions">
                                <button class="category-follow" data-category="<?php echo $category->term_id; ?>">
                                    <i class="fas fa-bell"></i>
                                    Takip Et
                                </button>
                                <div class="category-share">
                                    <button class="share-button">
                                        <i class="fas fa-share-alt"></i>
                                        Paylaş
                                    </button>
                                    <div class="share-dropdown">
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_category_link($category->term_id)); ?>&text=<?php echo urlencode(single_cat_title('', false) . ' kategorisindeki yazıları inceleyin'); ?>" target="_blank">
                                            <i class="fab fa-twitter"></i> Twitter
                                        </a>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_category_link($category->term_id)); ?>" target="_blank">
                                            <i class="fab fa-facebook"></i> Facebook
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_category_link($category->term_id)); ?>" target="_blank">
                                            <i class="fab fa-linkedin"></i> LinkedIn
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="blog-posts__grid">
                            <?php while (have_posts()) : the_post(); ?>
                                <article <?php post_class('blog-post-card'); ?>>
                                    <div class="blog-post-card__image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array(
                                                    'alt' => get_the_title(),
                                                    'class' => 'blog-post-card__img'
                                                )); ?>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?php the_permalink(); ?>" class="blog-post-card__img-placeholder">
                                                <i class="fas fa-file-alt"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <div class="blog-post-card__category active">
                                            <a href="<?php echo get_category_link($category->term_id); ?>">
                                                <i class="fas fa-tag"></i>
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <div class="blog-post-card__content">
                                        <div class="blog-post-card__meta">
                                            <span class="blog-post-card__date">
                                                <i class="fas fa-calendar-alt"></i>
                                                <?php echo get_the_date('j F Y'); ?>
                                            </span>
                                            <span class="blog-post-card__read-time">
                                                <i class="fas fa-clock"></i>
                                                <?php echo devlog_reading_time(get_the_ID()); ?>
                                            </span>
                                            <span class="blog-post-card__author">
                                                <i class="fas fa-user"></i>
                                                <?php the_author(); ?>
                                            </span>
                                            <span class="blog-post-card__views">
                                                <i class="fas fa-eye"></i>
                                                <?php echo devlog_get_post_views(get_the_ID()); ?>
                                            </span>
                                        </div>
                                        
                                        <h2 class="blog-post-card__title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="blog-post-card__excerpt">
                                            <?php 
                                            if (has_excerpt()) {
                                                the_excerpt();
                                            } else {
                                                echo wp_trim_words(get_the_content(), 25, '...');
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="blog-post-card__footer">
                                            <div class="blog-post-card__tags">
                                                <?php 
                                                $tags = get_the_tags();
                                                if ($tags) :
                                                    $tag_count = 0;
                                                    foreach ($tags as $tag) :
                                                        if ($tag_count >= 3) break;
                                                ?>
                                                    <a href="<?php echo get_tag_link($tag->term_id); ?>" class="blog-post-card__tag">
                                                        <i class="fas fa-hashtag"></i>
                                                        <?php echo esc_html($tag->name); ?>
                                                    </a>
                                                <?php 
                                                        $tag_count++;
                                                    endforeach;
                                                endif; 
                                                ?>
                                            </div>
                                            
                                            <a href="<?php the_permalink(); ?>" class="blog-post-card__read-more">
                                                Devamını Oku
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="blog-pagination">
                            <?php
                            echo paginate_links(array(
                                'mid_size' => 2,
                                'prev_text' => '<i class="fas fa-chevron-left"></i> Önceki',
                                'next_text' => 'Sonraki <i class="fas fa-chevron-right"></i>',
                                'screen_reader_text' => 'Sayfa navigasyonu'
                            ));
                            ?>
                        </div>
                        
                    <?php else : ?>
                        <div class="no-posts">
                            <div class="no-posts__icon">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <h2 class="no-posts__title">Bu kategoride henüz yazı yok</h2>
                            <p class="no-posts__description">
                                "<strong><?php single_cat_title(); ?></strong>" kategorisinde henüz blog yazısı yayınlanmamış. 
                                Yakında bu konuda yazılar ekleyeceğim.
                            </p>
                            
                            <div class="no-posts__suggestions">
                                <h3>Önerilen Kategoriler:</h3>
                                <div class="suggested-categories">
                                    <?php
                                    $suggested_categories = get_categories(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 5,
                                        'hide_empty' => true,
                                        'exclude' => $category->term_id
                                    ));
                                    
                                    foreach ($suggested_categories as $suggested_cat) :
                                    ?>
                                        <a href="<?php echo get_category_link($suggested_cat->term_id); ?>" class="suggested-category">
                                            <i class="fas fa-tag"></i>
                                            <?php echo esc_html($suggested_cat->name); ?>
                                            <span class="count">(<?php echo $suggested_cat->count; ?>)</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <?php if (get_option('page_for_posts')) : ?>
                                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn--primary">
                                    <i class="fas fa-th"></i>
                                    Tüm Yazıları Gör
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                                    <i class="fas fa-home"></i>
                                    Ana Sayfaya Dön
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <aside class="blog-posts__sidebar">
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">
                            <i class="fas fa-fire"></i>
                            Popüler Yazılar
                        </h3>
                        <div class="popular-posts">
                            <?php
                            $popular_posts = devlog_get_popular_posts(5);
                            
                            foreach ($popular_posts as $post) :
                                setup_postdata($post);
                            ?>
                                <article class="popular-post">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="popular-post__image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('thumbnail', array(
                                                    'alt' => get_the_title()
                                                )); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="popular-post__content">
                                        <h4 class="popular-post__title">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>
                                        </h4>
                                        <div class="popular-post__meta">
                                            <span class="popular-post__date">
                                                <i class="fas fa-calendar"></i>
                                                <?php echo get_the_date('j M Y'); ?>
                                            </span>
                                            <span class="popular-post__views">
                                                <i class="fas fa-eye"></i>
                                                <?php echo devlog_get_post_views(get_the_ID()); ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            <?php 
                            endforeach; 
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">
                            <i class="fas fa-tags"></i>
                            Diğer Kategoriler
                        </h3>
                        <div class="category-list">
                            <?php 
                            $all_categories = get_categories(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'hide_empty' => true,
                                'exclude' => $category->term_id
                            ));
                            
                            foreach ($all_categories as $cat) :
                            ?>
                                <a href="<?php echo get_category_link($cat->term_id); ?>" class="category-item">
                                    <span class="category-name">
                                        <i class="fas fa-folder"></i>
                                        <?php echo esc_html($cat->name); ?>
                                    </span>
                                    <span class="category-count"><?php echo $cat->count; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">
                            <i class="fas fa-hashtag"></i>
                            İlgili Etiketler
                        </h3>
                        <div class="tag-cloud">
                            <?php
                            // Get tags from posts in this category
                            $category_tags = get_terms(array(
                                'taxonomy' => 'post_tag',
                                'object_ids' => get_objects_in_term($category->term_id, 'category'),
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 15
                            ));
                            
                            foreach ($category_tags as $tag) :
                            ?>
                                <a href="<?php echo get_tag_link($tag->term_id); ?>" class="tag-item">
                                    <?php echo esc_html($tag->name); ?>
                                    <span class="tag-count"><?php echo $tag->count; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">
                            <i class="fas fa-archive"></i>
                            Arşiv
                        </h3>
                        <div class="archive-list">
                            <?php wp_get_archives(array(
                                'type' => 'monthly',
                                'limit' => 12,
                                'show_post_count' => true,
                                'format' => 'custom',
                                'before' => '<div class="archive-item"><i class="fas fa-calendar"></i>',
                                'after' => '</div>'
                            )); ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
