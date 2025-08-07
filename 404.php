<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <div class="container">
        <div class="error-404">
            <div class="error-content">
                <h1 class="error-title">404</h1>
                <h2 class="error-subtitle">Sayfa Bulunamadı</h2>
                <p class="error-description">
                    Aradığınız sayfa bulunamadı. Bu sayfa kaldırılmış, adı değiştirilmiş 
                    veya geçici olarak kullanılamıyor olabilir.
                </p>
                
                <div class="error-actions">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--primary">
                        <i class="fas fa-home"></i>
                        Ana Sayfaya Dön
                    </a>
                    <a href="javascript:history.back()" class="btn btn--secondary">
                        <i class="fas fa-arrow-left"></i>
                        Geri Dön
                    </a>
                </div>
                
                <div class="search-form">
                    <h3>Arama Yapın</h3>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.error-404 {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: var(--spacing-4xl) 0;
}

.error-content {
    max-width: 600px;
}

.error-title {
    font-size: 8rem;
    font-weight: var(--font-weight-bold);
    color: var(--primary-color);
    margin-bottom: var(--spacing-md);
    line-height: 1;
}

.error-subtitle {
    font-size: var(--font-size-2xl);
    font-weight: var(--font-weight-semibold);
    color: var(--text-color);
    margin-bottom: var(--spacing-lg);
}

.error-description {
    font-size: var(--font-size-lg);
    color: var(--text-color-secondary);
    margin-bottom: var(--spacing-2xl);
    line-height: 1.7;
}

.error-actions {
    display: flex;
    gap: var(--spacing-lg);
    justify-content: center;
    margin-bottom: var(--spacing-2xl);
    flex-wrap: wrap;
}

.search-form h3 {
    font-size: var(--font-size-lg);
    color: var(--text-color);
    margin-bottom: var(--spacing-lg);
}

@media screen and (max-width: 768px) {
    .error-title {
        font-size: 6rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php get_footer(); ?>
