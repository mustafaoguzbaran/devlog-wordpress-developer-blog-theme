<?php
/**
 * Search Results template
 * 
 * Displays search results for user queries
 * 
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <!-- Modern Search Header -->
    <section class="modern-search-header section">
        <div class="container">
            <div class="search-header-content">
                <!-- Minimal Breadcrumb -->
                <nav class="minimal-breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                    </a>
                    <span class="breadcrumb-separator">→</span>
                    <span class="breadcrumb-current">Arama</span>
                </nav>
                
                <!-- Search Title & Results Info -->
                <div class="search-title-section">
                    <?php
                    $search_query = get_search_query();
                    global $wp_query;
                    $total_results = $wp_query->found_posts;
                    ?>
                    
                    <h1 class="modern-search-title">
                        <?php if ($search_query) : ?>
                            "<span class="search-term"><?php echo esc_html($search_query); ?></span>" araması
                        <?php else : ?>
                            Arama Yap
                        <?php endif; ?>
                    </h1>
                    
                    <?php if ($search_query) : ?>
                        <p class="search-results-info">
                            <?php if ($total_results > 0) : ?>
                                <strong><?php echo $total_results; ?></strong> sonuç bulundu
                            <?php else : ?>
                                Sonuç bulunamadı
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Enhanced Search Form -->
                <div class="search-form-container">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="enhanced-search-form">
                        <div class="search-input-group">
                            <input type="search" 
                                   placeholder="Ne arıyorsunuz?" 
                                   value="<?php echo esc_attr($search_query); ?>" 
                                   name="s" 
                                   class="enhanced-search-input"
                                   autocomplete="off">
                            <button type="submit" class="enhanced-search-btn">
                                <i class="fas fa-search"></i>
                                <span>Ara</span>
                            </button>
                        </div>
                    </form>
                    
                    <?php if ($search_query) : ?>
                        <div class="search-suggestions">
                            <p class="suggestions-title">Öneriler:</p>
                            <div class="suggestion-tags">
                                <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="suggestion-tag">
                                    Tüm Yazılar
                                </a>
                                <?php 
                                $categories = get_categories(array('number' => 4, 'orderby' => 'count', 'order' => 'DESC'));
                                foreach ($categories as $category) :
                                ?>
                                    <a href="<?php echo get_category_link($category->term_id); ?>" class="suggestion-tag">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
                
                <!-- Search Form -->
                <div class="blog-search">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="search-form">
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   placeholder="Blog yazılarında ara..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   class="search-input">
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
                            Tümü
                        </a>
                    <?php endif; ?>
                    <?php 
                    $categories = get_categories(array(
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'hide_empty' => true
                    ));
                    
                    foreach ($categories as $category) :
                    ?>
                        <a href="<?php echo get_category_link($category->term_id); ?>" class="category-filter">
                            <?php echo esc_html($category->name); ?>
                            <span class="category-count"><?php echo $category->count; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="blog-posts section">
        <div class="container">
            <div class="blog-posts__wrapper">
                <!-- Main Content -->
                <div class="blog-posts__main">
                    <?php if (have_posts()) : ?>
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
                                        
                                        <?php 
                                        $categories = get_the_category();
                                        if (!empty($categories)) :
                                            $main_category = $categories[0];
                                        ?>
                                            <div class="blog-post-card__category">
                                                <a href="<?php echo get_category_link($main_category->term_id); ?>">
                                                    <?php echo esc_html($main_category->name); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
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
                                        </div>
                                        
                                        <h2 class="blog-post-card__title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="blog-post-card__excerpt">
                                            <?php 
                                            if (has_excerpt()) {
                                                $excerpt = get_the_excerpt();
                                            } else {
                                                $excerpt = wp_trim_words(get_the_content(), 25, '...');
                                            }
                                            
                                            // Highlight search terms in excerpt
                                            $search_terms = get_search_query();
                                            if ($search_terms) {
                                                $excerpt = preg_replace('/(' . preg_quote($search_terms, '/') . ')/i', '<mark>$1</mark>', $excerpt);
                                            }
                                            echo $excerpt;
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
                                <i class="fas fa-search"></i>
                            </div>
                            <h2 class="no-posts__title">
                                <?php if (get_search_query()) : ?>
                                    Aradığınız içerik bulunamadı
                                <?php else : ?>
                                    Arama yapın
                                <?php endif; ?>
                            </h2>
                            <p class="no-posts__description">
                                <?php if (get_search_query()) : ?>
                                    "<strong><?php echo get_search_query(); ?></strong>" araması için sonuç bulunamadı. Farklı kelimeler deneyebilir veya tüm yazıları görüntüleyebilirsiniz.
                                <?php else : ?>
                                    Blogdaki yazılarda arama yapmak için yukarıdaki arama formunu kullanın.
                                <?php endif; ?>
                            </p>
                            
                            <?php if (get_search_query()) : ?>
                                <!-- Search suggestions -->
                                <div class="search-suggestions">
                                    <h3>Arama Önerileri:</h3>
                                    <ul>
                                        <li>Daha kısa kelimeler kullanmayı deneyin</li>
                                        <li>Farklı kelimeler veya eş anlamlıları deneyin</li>
                                        <li>Daha genel terimler kullanın</li>
                                        <li>Yazım hatası olup olmadığını kontrol edin</li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
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
                
                <!-- Sidebar -->
                <aside class="blog-posts__sidebar">
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">Popüler Yazılar</h3>
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
                                        <span class="popular-post__date">
                                            <?php echo get_the_date('j M Y'); ?>
                                        </span>
                                    </div>
                                </article>
                            <?php 
                            endforeach; 
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">Kategoriler</h3>
                        <div class="category-list">
                            <?php 
                            wp_list_categories(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'show_count' => true,
                                'title_li' => '',
                                'hide_empty' => true
                            ));
                            ?>
                        </div>
                    </div>
                    
                    <div class="sidebar-widget">
                        <h3 class="sidebar-widget__title">Önerilen Aramalar</h3>
                        <div class="suggested-searches">
                            <?php
                            // Get popular tags as suggested searches
                            $popular_tags = get_tags(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'number' => 10,
                                'hide_empty' => true
                            ));
                            
                            foreach ($popular_tags as $tag) :
                            ?>
                                <a href="<?php echo esc_url(home_url('/?s=' . urlencode($tag->name))); ?>" class="suggested-search">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
