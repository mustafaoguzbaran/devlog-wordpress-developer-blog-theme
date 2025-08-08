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

    <!-- Modern Search Results -->
    <section class="modern-search-results section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="search-results-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('search-result-card'); ?>>
                            <div class="result-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="image-link">
                                        <?php the_post_thumbnail('medium', array(
                                            'alt' => get_the_title(),
                                            'class' => 'result-image'
                                        )); ?>
                                        <div class="image-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php the_permalink(); ?>" class="image-placeholder">
                                        <div class="placeholder-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="image-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </a>
                                <?php endif; ?>
                                
                                <?php 
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    $main_category = $categories[0];
                                ?>
                                    <div class="result-category-badge">
                                        <?php echo esc_html($main_category->name); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="result-card-content">
                                <div class="result-meta">
                                    <span class="result-date">
                                        <?php echo get_the_date('j M Y'); ?>
                                    </span>
                                    <span class="result-reading-time">
                                        <?php echo devlog_reading_time(get_the_ID()); ?>
                                    </span>
                                </div>
                                
                                <h2 class="result-card-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php 
                                        $title = get_the_title();
                                        $search_terms = get_search_query();
                                        if ($search_terms) {
                                            $highlighted_title = preg_replace('/(' . preg_quote($search_terms, '/') . ')/i', '<mark>$1</mark>', $title);
                                            echo $highlighted_title;
                                        } else {
                                            echo $title;
                                        }
                                        ?>
                                    </a>
                                </h2>
                                
                                <div class="result-excerpt">
                                    <?php 
                                    $excerpt = '';
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
                                
                                <div class="result-card-footer">
                                    <div class="result-author">
                                        <span class="author-name"><?php the_author(); ?></span>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="read-more-search">
                                        Oku
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Search Pagination -->
                <div class="search-pagination">
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
                <div class="no-search-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h2 class="no-results-title">Sonuç bulunamadı</h2>
                    <p class="no-results-description">
                        <?php if ($search_query) : ?>
                            "<strong><?php echo esc_html($search_query); ?></strong>" aramanız için sonuç bulunamadı.
                        <?php else : ?>
                            Arama yapmak için yukarıdaki formu kullanın.
                        <?php endif; ?>
                    </p>
                    
                    <!-- Search Suggestions -->
                    <div class="search-suggestions-section">
                        <h3 class="suggestions-title">Bunları da deneyebilirsiniz:</h3>
                        <div class="suggestion-list">
                            <div class="suggestion-group">
                                <h4>Popüler Kategoriler</h4>
                                <div class="suggestion-tags">
                                    <?php 
                                    $popular_categories = get_categories(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 5,
                                        'hide_empty' => true
                                    ));
                                    
                                    foreach ($popular_categories as $category) :
                                    ?>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="suggestion-tag">
                                            <?php echo esc_html($category->name); ?>
                                            <span class="tag-count"><?php echo $category->count; ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="suggestion-group">
                                <h4>Popüler Etiketler</h4>
                                <div class="suggestion-tags">
                                    <?php 
                                    $popular_tags = get_tags(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'number' => 8,
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
                        </div>
                    </div>
                    
                    <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn--primary">
                        Tüm Yazıları Görüntüle
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
