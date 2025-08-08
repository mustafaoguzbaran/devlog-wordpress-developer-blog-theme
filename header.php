<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class('dark-theme'); ?>>
    <!-- Header & Navigation -->
    <header class="header" id="header">
        <nav class="nav container">
            <div class="nav__brand">
                <?php echo devlog_get_logo(); ?>
            </div>
            
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="<?php echo esc_url(home_url('/#home')); ?>" class="nav__link">Ana Sayfa</a>
                    </li>
                    <li class="nav__item">
                        <a href="<?php echo esc_url(home_url('/#about')); ?>" class="nav__link">Hakkımda</a>
                    </li>
                    <li class="nav__item">
                        <a href="<?php echo esc_url(home_url('/#experience')); ?>" class="nav__link">Tecrübeler</a>
                    </li>
                    <li class="nav__item">
                        <a href="<?php echo esc_url(home_url('/#skills')); ?>" class="nav__link">Yetenekler</a>
                    </li>
                    <li class="nav__item">
                        <?php if (get_option('page_for_posts')) : ?>
                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="nav__link">Blog</a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/#blog')); ?>" class="nav__link">Blog</a>
                        <?php endif; ?>
                    </li>
                    <li class="nav__item">
                        <a href="<?php echo esc_url(home_url('/#contact')); ?>" class="nav__link">İletişim</a>
                    </li>
                </ul>
                
                <div class="nav__close" id="nav-close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            
            <div class="nav__actions">
                <button class="theme-toggle" id="theme-toggle" aria-label="Tema değiştir">
                    <i class="fas fa-sun"></i>
                </button>
                
                <div class="nav__toggle" id="nav-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </div>
        </nav>
    </header>
