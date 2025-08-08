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
    <!-- Modern Category Header -->
    <section class="modern-category-header section">
        <div class="container">
            <div class="category-header-content">
                <!-- Minimal Breadcrumb -->
                <nav class="minimal-breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="breadcrumb-link">
                        <i class="fas fa-home"></i>
                    </a>
                    <span class="breadcrumb-separator">→</span>
                    <span class="breadcrumb-current"><?php single_cat_title(); ?></span>
                </nav>
                
                <!-- Category Title & Info -->
                <div class="category-title-section">
                    <?php 
                    $category = get_queried_object();
                    $category_count = $category->count;
                    ?>
                    
                    <h1 class="modern-category-title">
                        <span class="category-icon">
                            <i class="fas fa-folder-open"></i>
                        </span>
                        <?php single_cat_title(); ?>
                    </h1>
                    
                    <div class="category-info">
                        <span class="category-count-badge">
                            <?php echo $category_count; ?> yazı
                        </span>
                        <?php if (category_description()) : ?>
                            <p class="category-description">
                                <?php echo strip_tags(category_description()); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Compact Search & Filter -->
                <div class="category-controls">
                    <div class="search-wrapper">
                        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="modern-search-form">
                            <input type="search" 
                                   placeholder="Bu kategoride ara..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" 
                                   class="modern-search-input">
                            <input type="hidden" name="category_name" value="<?php echo $category->slug; ?>">
                            <button type="submit" class="modern-search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="category-nav-pills">
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="nav-pill">
                            Tüm Yazılar
                        </a>
                        <?php 
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'hide_empty' => true,
                            'number' => 5
                        ));
                        
                        foreach ($categories as $cat) :
                            $is_current = ($cat->term_id == $category->term_id) ? 'active' : '';
                        ?>
                            <a href="<?php echo get_category_link($cat->term_id); ?>" class="nav-pill <?php echo $is_current; ?>">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Category Posts -->
    <section class="modern-category-posts section">
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="category-posts-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('category-post-card'); ?>>
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
                                
                                <div class="post-category-current">
                                    <?php echo esc_html($category->name); ?>
                                </div>
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
                                    <div class="post-tags-minimal">
                                        <?php 
                                        $tags = get_the_tags();
                                        if ($tags) :
                                            $tag_count = 0;
                                            foreach ($tags as $tag) :
                                                if ($tag_count >= 2) break;
                                        ?>
                                            <span class="post-tag"><?php echo esc_html($tag->name); ?></span>
                                        <?php 
                                                $tag_count++;
                                            endforeach;
                                        endif; 
                                        ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="read-more-category">
                                        Oku
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Category Pagination -->
                <div class="category-pagination">
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
                <div class="no-category-posts">
                    <div class="no-posts-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h2 class="no-posts-title">Bu kategoride yazı yok</h2>
                    <p class="no-posts-description">
                        "<strong><?php single_cat_title(); ?></strong>" kategorisinde henüz yazı yayınlanmamış.
                    </p>
                    
                    <!-- Category Suggestions -->
                    <div class="category-suggestions">
                        <h3 class="suggestions-title">Diğer kategoriler:</h3>
                        <div class="suggestion-categories">
                            <?php 
                            $other_categories = get_categories(array(
                                'orderby' => 'count',
                                'order' => 'DESC',
                                'hide_empty' => true,
                                'number' => 6,
                                'exclude' => $category->term_id
                            ));
                            
                            foreach ($other_categories as $other_cat) :
                            ?>
                                <a href="<?php echo get_category_link($other_cat->term_id); ?>" class="suggestion-category">
                                    <?php echo esc_html($other_cat->name); ?>
                                    <span class="category-count"><?php echo $other_cat->count; ?></span>
                                </a>
                            <?php endforeach; ?>
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
