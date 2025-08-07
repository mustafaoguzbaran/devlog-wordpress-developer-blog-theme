    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__brand">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="footer__logo">
                        <?php bloginfo('name'); ?>
                    </a>
                    <p class="footer__description">
                        <?php 
                        $description = get_bloginfo('description');
                        echo $description ? esc_html($description) : 'Backend development ve modern teknolojiler üzerine yazılar.';
                        ?>
                    </p>
                </div>
                
                <div class="footer__links">
                    <div class="footer__group">
                        <h3 class="footer__title">Sayfalar</h3>
                        <ul class="footer__list">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="footer__link">Ana Sayfa</a></li>
                            <li><a href="<?php echo esc_url(home_url('/#about')); ?>" class="footer__link">Hakkımda</a></li>
                            <li><a href="<?php echo esc_url(home_url('/#experience')); ?>" class="footer__link">Tecrübeler</a></li>
                            <li><a href="<?php echo esc_url(home_url('/#skills')); ?>" class="footer__link">Yetenekler</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer__group">
                        <h3 class="footer__title">İçerik</h3>
                        <ul class="footer__list">
                            <li><a href="<?php echo esc_url(home_url('/#blog')); ?>" class="footer__link">Blog</a></li>
                            <li><a href="#" class="footer__link">Projeler</a></li>
                            <li><a href="#" class="footer__link">Makaleler</a></li>
                            <li><a href="#" class="footer__link">Kaynaklar</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer__group">
                        <h3 class="footer__title">İletişim</h3>
                        <ul class="footer__list">
                            <li><a href="<?php echo esc_url(home_url('/#contact')); ?>" class="footer__link">İletişim</a></li>
                            <li><a href="<?php echo esc_url(get_theme_mod('social_github', '#')); ?>" class="footer__link">GitHub</a></li>
                            <li><a href="<?php echo esc_url(get_theme_mod('social_linkedin', '#')); ?>" class="footer__link">LinkedIn</a></li>
                            <li><a href="<?php echo esc_url(get_theme_mod('social_twitter', '#')); ?>" class="footer__link">Twitter</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="footer__bottom">
                <p class="footer__copy">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Tüm hakları saklıdır.
                </p>
                
                <div class="footer__socials">
                    <a href="<?php echo esc_url(get_theme_mod('social_github', '#')); ?>" class="footer__social" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('social_linkedin', '#')); ?>" class="footer__social" aria-label="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('social_twitter', '#')); ?>" class="footer__social" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="mailto:<?php echo esc_attr(get_theme_mod('contact_email', 'developer@devlog.com')); ?>" class="footer__social" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
