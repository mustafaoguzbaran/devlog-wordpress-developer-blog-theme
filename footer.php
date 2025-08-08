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
                        <h3 class="footer__title"><?php echo esc_html(get_theme_mod('footer_pages_title', 'Sayfalar')); ?></h3>
                        <?php
                        if (has_nav_menu('footer-pages')) {
                            wp_nav_menu(array(
                                'theme_location' => 'footer-pages',
                                'container' => false,
                                'menu_class' => 'footer__list',
                                'depth' => 1,
                                'link_before' => '',
                                'link_after' => '',
                                'fallback_cb' => false
                            ));
                        } else {
                            // Fallback menu if no menu is assigned
                            echo '<ul class="footer__list">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '" class="footer__link">Ana Sayfa</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/#about')) . '" class="footer__link">Hakkımda</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/#experience')) . '" class="footer__link">Tecrübeler</a></li>';
                            echo '<li><a href="' . esc_url(home_url('/#skills')) . '" class="footer__link">Yetenekler</a></li>';
                            echo '</ul>';
                        }
                        ?>
                    </div>
                    
                    <div class="footer__group">
                        <h3 class="footer__title"><?php echo esc_html(get_theme_mod('footer_content_title', 'İçerik')); ?></h3>
                        <?php
                        if (has_nav_menu('footer-content')) {
                            wp_nav_menu(array(
                                'theme_location' => 'footer-content',
                                'container' => false,
                                'menu_class' => 'footer__list',
                                'depth' => 1,
                                'link_before' => '',
                                'link_after' => '',
                                'fallback_cb' => false
                            ));
                        } else {
                            // Fallback menu if no menu is assigned
                            echo '<ul class="footer__list">';
                            echo '<li><a href="' . esc_url(home_url('/#blog')) . '" class="footer__link">Blog</a></li>';
                            echo '<li><a href="' . esc_url(get_post_type_archive_link('project')) . '" class="footer__link">Projeler</a></li>';
                            echo '<li><a href="' . esc_url(get_category_link(get_cat_ID('Makaleler'))) . '" class="footer__link">Makaleler</a></li>';
                            echo '<li><a href="' . esc_url(get_category_link(get_cat_ID('Kaynaklar'))) . '" class="footer__link">Kaynaklar</a></li>';
                            echo '</ul>';
                        }
                        ?>
                    </div>
                    
                    <div class="footer__group">
                        <h3 class="footer__title"><?php echo esc_html(get_theme_mod('footer_contact_title', 'İletişim')); ?></h3>
                        <?php
                        if (has_nav_menu('footer-contact')) {
                            wp_nav_menu(array(
                                'theme_location' => 'footer-contact',
                                'container' => false,
                                'menu_class' => 'footer__list',
                                'depth' => 1,
                                'link_before' => '',
                                'link_after' => '',
                                'fallback_cb' => false
                            ));
                        } else {
                            // Fallback menu if no menu is assigned
                            echo '<ul class="footer__list">';
                            echo '<li><a href="' . esc_url(home_url('/#contact')) . '" class="footer__link">İletişim</a></li>';
                            echo '<li><a href="' . esc_url(get_theme_mod('social_github', '#')) . '" class="footer__link">GitHub</a></li>';
                            echo '<li><a href="' . esc_url(get_theme_mod('social_linkedin', '#')) . '" class="footer__link">LinkedIn</a></li>';
                            echo '<li><a href="' . esc_url(get_theme_mod('social_twitter', '#')) . '" class="footer__link">Twitter</a></li>';
                            echo '</ul>';
                        }
                        ?>
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
