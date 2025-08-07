<?php
/**
 * The main template file
 *
 * @package DevLog
 * @version 1.0.0
 */

get_header(); ?>

<main class="main">
    <!-- Hero Section -->
    <section class="hero section" id="home">
        <div class="hero__container container grid">
            <div class="hero__content">
                <span class="hero__subtitle">Merhaba, Ben</span>
                <h1 class="hero__title">
                    <?php echo esc_html(get_theme_mod('hero_title', 'Backend Developer')); ?>
                    <span class="cursor-blink">|</span>
                </h1>
                <p class="hero__description">
                    <?php echo esc_html(get_theme_mod('hero_description', 'Ölçeklenebilir web uygulamaları ve API\'ler geliştiren deneyimli bir backend developer\'ım. Modern teknolojiler ve en iyi pratikler kullanarak güvenilir çözümler üretiyorum.')); ?>
                </p>
                
                <div class="hero__buttons">
                    <a href="#contact" class="btn btn--primary">İletişime Geç</a>
                    <a href="#about" class="btn btn--secondary">Daha Fazla</a>
                </div>
                
                <div class="hero__socials">
                    <a href="<?php echo esc_url(get_theme_mod('social_github', '#')); ?>" class="hero__social" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('social_linkedin', '#')); ?>" class="hero__social" aria-label="LinkedIn">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('social_twitter', '#')); ?>" class="hero__social" aria-label="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="mailto:<?php echo esc_attr(get_theme_mod('contact_email', 'developer@devlog.com')); ?>" class="hero__social" aria-label="Email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
            </div>
            
            <div class="hero__image">
                <div class="hero__img-bg"></div>
                <?php if (get_theme_mod('hero_image')) : ?>
                    <img src="<?php echo esc_url(get_theme_mod('hero_image')); ?>" alt="Profile" class="hero__img">
                <?php else : ?>
                    <img src="https://i.hizliresim.com/dow4vc6.jpeg" alt="Profile" class="hero__img">
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section" id="about">
        <div class="container">
            <div class="section__header">
                <span class="section__subtitle"><?php echo esc_html(get_theme_mod('about_subtitle', 'Kim Olduğum')); ?></span>
                <h2 class="section__title"><?php echo esc_html(get_theme_mod('about_title', 'Hakkımda')); ?></h2>
            </div>
            
            <div class="about__wrapper">
                <div class="about__main grid">
                    <div class="about__content">
                        <div class="about__text">
                            <h3 class="about__subtitle"><?php echo esc_html(get_theme_mod('about_job_title', 'Backend Developer')); ?></h3>
                            <p class="about__description">
                                <?php echo esc_html(get_theme_mod('about_description', '5+ yıllık deneyime sahip bir backend developer olarak, çeşitli sektörlerde ölçeklenebilir ve performanslı web uygulamaları geliştirdim. Modern teknolojiler ve en iyi pratikler kullanarak, karmaşık iş problemlerini basit ve etkili çözümlere dönüştürmeyi seviyorum.')); ?>
                            </p>
                            
                            <p class="about__description">
                                <?php echo esc_html(get_theme_mod('about_description_2', 'Sürekli öğrenme ve gelişim odaklı yaklaşımımla, yeni teknolojileri takip ediyor ve projelerimde uyguluyorum. Takım çalışmasına değer veriyor, kod kalitesi ve clean architecture prensiplerine odaklanıyorum.')); ?>
                            </p>
                            
                            <div class="about__download">
                                <a href="<?php echo esc_url(devlog_get_cv_download_url()); ?>" class="btn btn--primary" <?php echo get_theme_mod('about_cv_file') ? 'download' : ''; ?>>
                                    <i class="fas fa-download"></i>
                                    <?php echo esc_html(get_theme_mod('about_cv_text', 'CV İndir')); ?>
                                </a>
                                <a href="#contact" class="btn btn--outline">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo esc_html(get_theme_mod('about_contact_text', 'İletişim')); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="about__image">
                        <div class="about__img-wrapper">
                            <?php if (get_theme_mod('about_image')) : ?>
                                <img src="<?php echo esc_url(get_theme_mod('about_image')); ?>" alt="About Me" class="about__img">
                            <?php else : ?>
                                <img src="https://i.hizliresim.com/dow4vc6.jpeg" alt="About Me" class="about__img">
                            <?php endif; ?>
                            <div class="about__img-overlay">
                                <div class="about__img-content">
                                    <i class="fas fa-code"></i>
                                    <span><?php echo esc_html(get_theme_mod('about_overlay_text', 'Backend Developer')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about__features">
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                    <div class="about__feature-card">
                        <div class="about__feature-icon">
                            <i class="<?php echo esc_attr(get_theme_mod("about_feature_{$i}_icon", $i == 1 ? 'fas fa-code' : ($i == 2 ? 'fas fa-rocket' : 'fas fa-shield-alt'))); ?>"></i>
                        </div>
                        <div class="about__feature-content">
                            <h3><?php echo esc_html(get_theme_mod("about_feature_{$i}_title", $i == 1 ? 'Clean Code' : ($i == 2 ? 'Performance' : 'Security'))); ?></h3>
                            <p><?php echo esc_html(get_theme_mod("about_feature_{$i}_description", $i == 1 ? 'Okunabilir, sürdürülebilir ve test edilebilir kod yazımına odaklanıyorum' : ($i == 2 ? 'Optimize edilmiş, hızlı ve ölçeklenebilir backend çözümleri geliştiriyorum' : 'Güvenli API\'ler ve backend sistemleri ile veri güvenliğini sağlıyorum'))); ?></p>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
                
                <div class="about__stats">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <div class="about__stat">
                        <div class="about__stat-icon">
                            <i class="<?php echo esc_attr(get_theme_mod("about_stat_{$i}_icon", $i == 1 ? 'fas fa-calendar-alt' : ($i == 2 ? 'fas fa-project-diagram' : ($i == 3 ? 'fas fa-users' : 'fas fa-tools')))); ?>"></i>
                        </div>
                        <div class="about__stat-content">
                            <span class="about__stat-number"><?php echo esc_html(get_theme_mod("about_stat_{$i}_number", $i == 1 ? '5+' : ($i == 2 ? '50+' : ($i == 3 ? '20+' : '10+')))); ?></span>
                            <span class="about__stat-label"><?php echo esc_html(get_theme_mod("about_stat_{$i}_label", $i == 1 ? 'Yıl Deneyim' : ($i == 2 ? 'Tamamlanan Proje' : ($i == 3 ? 'Mutlu Müşteri' : 'Teknoloji')))); ?></span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="experience section" id="experience">
        <div class="container">
            <div class="section__header">
                <span class="section__subtitle"><?php echo get_theme_mod('experience_subtitle', 'Profesyonel Geçmiş'); ?></span>
                <h2 class="section__title"><?php echo get_theme_mod('experience_title', 'İş Tecrübelerim'); ?></h2>
            </div>
            
            <div class="experience__container">
                <?php 
                $experiences = devlog_get_experiences();
                
                if (!empty($experiences)) :
                    foreach ($experiences as $experience) :
                        $company = get_post_meta($experience->ID, '_devlog_company', true);
                        $position = get_post_meta($experience->ID, '_devlog_position', true);
                        $period = get_post_meta($experience->ID, '_devlog_period', true);
                        $description = get_post_meta($experience->ID, '_devlog_description', true);
                        $technologies = get_post_meta($experience->ID, '_devlog_technologies', true);
                        $projects = get_post_meta($experience->ID, '_devlog_projects', true);
                ?>
                <div class="experience__item">
                    <div class="experience__header">
                        <div class="experience__company">
                            <h3><?php echo esc_html($company ?: $experience->post_title); ?></h3>
                            <?php if ($period) : ?>
                            <span class="experience__period"><?php echo esc_html($period); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($position) : ?>
                        <span class="experience__position"><?php echo esc_html($position); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="experience__content">
                        <?php if ($description) : ?>
                        <p class="experience__description">
                            <?php echo esc_html($description); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($experience->post_content) : ?>
                        <p class="experience__description">
                            <?php echo wp_kses_post($experience->post_content); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php 
                        // Display multiple projects
                        if (!empty($projects) && is_array($projects)) : 
                            foreach ($projects as $project) :
                                if (!empty($project['title'])) :
                        ?>
                        <div class="experience__project">
                            <h4><?php echo esc_html($project['title']); ?></h4>
                            
                            <?php if (!empty($project['description'])) : ?>
                            <p><?php echo esc_html($project['description']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($project['url'])) : ?>
                            <p><a href="<?php echo esc_url($project['url']); ?>" target="_blank" rel="noopener">🔗 Projeyi Görüntüle</a></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($project['technologies'])) : ?>
                            <div class="experience__technologies">
                                <?php 
                                $project_tech_array = array_map('trim', explode(',', $project['technologies']));
                                foreach ($project_tech_array as $tech) :
                                    if (!empty($tech)) :
                                ?>
                                <span class="tech-tag"><?php echo esc_html($tech); ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php 
                                endif;
                            endforeach;
                        endif;
                        
                        // Show general technologies if no project-specific ones
                        if ((empty($projects) || !is_array($projects)) && !empty($technologies)) : 
                        ?>
                        <div class="experience__project">
                            <h4>Kullanılan Teknolojiler</h4>
                            <div class="experience__technologies">
                                <?php 
                                $tech_array = array_map('trim', explode(',', $technologies));
                                foreach ($tech_array as $tech) :
                                    if (!empty($tech)) :
                                ?>
                                <span class="tech-tag"><?php echo esc_html($tech); ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                else : 
                ?>
                <!-- Fallback Content - Eğer hiç experience yoksa -->
                <div class="experience__item">
                    <div class="experience__header">
                        <div class="experience__company">
                            <h3>Tech Solutions Inc.</h3>
                            <span class="experience__period">2021 - Devam Ediyor</span>
                        </div>
                        <span class="experience__position">Senior Backend Developer</span>
                    </div>
                    
                    <div class="experience__content">
                        <p class="experience__description">
                            E-ticaret platformu için mikroservis mimarisi geliştirme ve API tasarımı
                        </p>
                        
                        <div class="experience__project">
                            <h4>Ana Proje: E-Commerce Platform</h4>
                            <p>Günlük 100K+ işlem hacmine sahip e-ticaret platformunun backend altyapısını geliştirdim.</p>
                            
                            <div class="experience__technologies">
                                <span class="tech-tag">Node.js</span>
                                <span class="tech-tag">Express.js</span>
                                <span class="tech-tag">MongoDB</span>
                                <span class="tech-tag">Redis</span>
                                <span class="tech-tag">Docker</span>
                                <span class="tech-tag">AWS</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="experience__item">
                    <div class="experience__header">
                        <div class="experience__company">
                            <h3>Digital Agency Pro</h3>
                            <span class="experience__period">2019 - 2021</span>
                        </div>
                        <span class="experience__position">Backend Developer</span>
                    </div>
                    
                    <div class="experience__content">
                        <p class="experience__description">
                            Kurumsal web uygulamaları ve CMS sistemleri geliştirme
                        </p>
                        
                        <div class="experience__project">
                            <h4>Ana Proje: Corporate CMS</h4>
                            <p>Çoklu dil destekli kurumsal içerik yönetim sistemi geliştirdim.</p>
                            
                            <div class="experience__technologies">
                                <span class="tech-tag">PHP</span>
                                <span class="tech-tag">Laravel</span>
                                <span class="tech-tag">MySQL</span>
                                <span class="tech-tag">Vue.js</span>
                                <span class="tech-tag">Apache</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="experience__item">
                    <div class="experience__header">
                        <div class="experience__company">
                            <h3>StartUp Ventures</h3>
                            <span class="experience__period">2018 - 2019</span>
                        </div>
                        <span class="experience__position">Junior Backend Developer</span>
                    </div>
                    
                    <div class="experience__content">
                        <p class="experience__description">
                            MVP geliştirme ve hızlı prototipleme süreçlerinde yer alma
                        </p>
                        
                        <div class="experience__project">
                            <h4>Ana Proje: Social Platform</h4>
                            <p>Sosyal medya benzeri platform için REST API ve real-time messaging sistemi geliştirdim.</p>
                            
                            <div class="experience__technologies">
                                <span class="tech-tag">Python</span>
                                <span class="tech-tag">Django</span>
                                <span class="tech-tag">PostgreSQL</span>
                                <span class="tech-tag">WebSocket</span>
                                <span class="tech-tag">Heroku</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills section" id="skills">
        <div class="container">
            <div class="section__header">
                <span class="section__subtitle"><?php echo get_theme_mod('skills_subtitle', 'Teknik Bilgiler'); ?></span>
                <h2 class="section__title"><?php echo get_theme_mod('skills_title', 'Yeteneklerim'); ?></h2>
            </div>
            
            <div class="skills__container grid">
                <?php 
                $grouped_skills = devlog_get_skills_grouped();
                
                if (!empty($grouped_skills)) :
                    foreach ($grouped_skills as $category_key => $category_info) :
                        $category_data = $category_info['category_data'];
                        $skills = $category_info['skills'];
                        
                        // Count skills in this category
                        $skill_count = count($skills);
                ?>
                <div class="skills__category" data-category="<?php echo esc_attr($category_key); ?>">
                    <div class="skills__category-header">
                        <div class="skills__category-icon">
                            <i class="<?php echo esc_attr($category_data['icon']); ?>"></i>
                        </div>
                        <div class="skills__category-info">
                            <h3><?php echo esc_html($category_data['name']); ?></h3>
                            <span class="skills__category-count"><?php echo $skill_count; ?> teknoloji</span>
                        </div>
                    </div>
                    
                    <div class="skills__list">
                        <?php foreach ($skills as $skill) :
                            $percentage = get_post_meta($skill->ID, '_devlog_skill_percentage', true);
                            $percentage = $percentage ?: 50; // Default 50% if not set
                            $skill_icon = get_post_meta($skill->ID, '_devlog_skill_icon', true);
                        ?>
                        <div class="skill__item" data-percentage="<?php echo esc_attr($percentage); ?>">
                            <div class="skill__header">
                                <div class="skill__name-wrapper">
                                    <?php if ($skill_icon && $skill_icon !== $category_data['icon']) : ?>
                                        <i class="<?php echo esc_attr($skill_icon); ?> skill__icon"></i>
                                    <?php endif; ?>
                                    <span class="skill__name"><?php echo esc_html($skill->post_title); ?></span>
                                </div>
                                <span class="skill__percentage"><?php echo esc_html($percentage); ?>%</span>
                            </div>
                            <div class="skill__bar">
                                <div class="skill__progress" 
                                     style="width: 0%" 
                                     data-percentage="<?php echo esc_attr($percentage); ?>"
                                     data-skill="<?php echo esc_attr($skill->post_title); ?>">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php 
                    endforeach;
                else : 
                ?>
                <!-- Fallback Content - Eğer hiç skill yoksa -->
                <div class="skills__empty-state">
                    <div class="skills__empty-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Henüz skill eklenmemiş</h3>
                    <p>WordPress admin panelinden skill ekleyebilirsiniz.</p>
                    <a href="<?php echo admin_url('edit.php?post_type=skills'); ?>" class="btn btn--primary">
                        Skill Ekle
                    </a>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($grouped_skills)) : ?>
            <!-- Skills Quick Stats -->
            <div class="skills__stats">
                <?php 
                $stats = devlog_get_skills_statistics();
                if ($stats['total_skills'] > 0) :
                ?>
                <div class="skills__stats-grid">
                    <div class="skill__stat">
                        <span class="skill__stat-number"><?php echo $stats['total_skills']; ?></span>
                        <span class="skill__stat-label">Toplam Teknoloji</span>
                    </div>
                    <div class="skill__stat">
                        <span class="skill__stat-number"><?php echo count($stats['categories']); ?></span>
                        <span class="skill__stat-label">Kategori</span>
                    </div>
                    <div class="skill__stat">
                        <span class="skill__stat-number"><?php echo $stats['average_percentage']; ?>%</span>
                        <span class="skill__stat-label">Ortalama Seviye</span>
                    </div>
                    <?php if ($stats['highest_skill']) : ?>
                    <div class="skill__stat skill__stat--highlight">
                        <span class="skill__stat-number"><?php echo esc_html($stats['highest_skill']['name']); ?></span>
                        <span class="skill__stat-label">En Güçlü Skill (<?php echo $stats['highest_skill']['percentage']; ?>%)</span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Blog Section -->
    <section class="blog section" id="blog">
        <div class="container">
            <div class="section__header">
                <span class="section__subtitle">Son Yazılarım</span>
                <h2 class="section__title">Blog</h2>
            </div>
            
            <div class="blog__container grid">
                <?php
                // Get latest posts
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 6,
                    'post_status' => 'publish'
                ));

                if (!empty($recent_posts)) :
                    foreach ($recent_posts as $post) :
                        $post_id = $post['ID'];
                        $categories = get_the_category($post_id);
                        $main_category = !empty($categories) ? $categories[0]->name : 'Blog';
                ?>
                <article class="blog__card">
                    <div class="blog__image">
                        <?php if (has_post_thumbnail($post_id)) : ?>
                            <?php echo get_the_post_thumbnail($post_id, 'medium', array('alt' => get_the_title($post_id))); ?>
                        <?php else : ?>
                            <img src="https://via.placeholder.com/400x250/333/fff?text=<?php echo urlencode($main_category); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
                        <?php endif; ?>
                        <div class="blog__category"><?php echo esc_html($main_category); ?></div>
                    </div>
                    
                    <div class="blog__content">
                        <div class="blog__meta">
                            <span class="blog__date"><?php echo get_the_date('j F Y', $post_id); ?></span>
                            <span class="blog__read-time"><?php echo devlog_reading_time($post_id); ?></span>
                        </div>
                        
                        <h3 class="blog__title">
                            <a href="<?php echo get_permalink($post_id); ?>"><?php echo get_the_title($post_id); ?></a>
                        </h3>
                        
                        <p class="blog__description">
                            <?php echo wp_trim_words(get_the_excerpt($post_id), 20, '...'); ?>
                        </p>
                        
                        <div class="blog__tags">
                            <?php echo devlog_get_post_categories($post_id); ?>
                        </div>
                    </div>
                </article>
                <?php 
                    endforeach;
                else : 
                    // Default blog cards if no posts exist
                ?>
                <article class="blog__card">
                    <div class="blog__image">
                        <img src="https://via.placeholder.com/400x250/333/fff?text=API+Design" alt="API Design Best Practices">
                        <div class="blog__category">API Design</div>
                    </div>
                    
                    <div class="blog__content">
                        <div class="blog__meta">
                            <span class="blog__date">15 Mart 2024</span>
                            <span class="blog__read-time">5 dk okuma</span>
                        </div>
                        
                        <h3 class="blog__title">
                            <a href="#">RESTful API Tasarımında En İyi Pratikler</a>
                        </h3>
                        
                        <p class="blog__description">
                            Modern web uygulamalarında RESTful API tasarımının önemi ve uygulanması gereken 
                            en iyi pratikler hakkında detaylı bir rehber.
                        </p>
                        
                        <div class="blog__tags">
                            <span class="blog__tag">REST</span>
                            <span class="blog__tag">API</span>
                            <span class="blog__tag">Backend</span>
                        </div>
                    </div>
                </article>
                
                <article class="blog__card">
                    <div class="blog__image">
                        <img src="https://via.placeholder.com/400x250/333/fff?text=Microservices" alt="Microservices Architecture">
                        <div class="blog__category">Architecture</div>
                    </div>
                    
                    <div class="blog__content">
                        <div class="blog__meta">
                            <span class="blog__date">8 Mart 2024</span>
                            <span class="blog__read-time">8 dk okuma</span>
                        </div>
                        
                        <h3 class="blog__title">
                            <a href="#">Mikroservis Mimarisine Geçiş Süreci</a>
                        </h3>
                        
                        <p class="blog__description">
                            Monolitik uygulamalardan mikroservis mimarisine geçiş sürecinde karşılaşılan 
                            zorluklar ve çözüm önerileri.
                        </p>
                        
                        <div class="blog__tags">
                            <span class="blog__tag">Microservices</span>
                            <span class="blog__tag">Architecture</span>
                            <span class="blog__tag">Scalability</span>
                        </div>
                    </div>
                </article>
                
                <article class="blog__card">
                    <div class="blog__image">
                        <img src="https://via.placeholder.com/400x250/333/fff?text=Database" alt="Database Optimization">
                        <div class="blog__category">Database</div>
                    </div>
                    
                    <div class="blog__content">
                        <div class="blog__meta">
                            <span class="blog__date">1 Mart 2024</span>
                            <span class="blog__read-time">6 dk okuma</span>
                        </div>
                        
                        <h3 class="blog__title">
                            <a href="#">Veritabanı Performans Optimizasyonu</a>
                        </h3>
                        
                        <p class="blog__description">
                            Büyük veri setleri ile çalışırken veritabanı performansını artırmak için 
                            kullanabileceğiniz teknikler ve stratejiler.
                        </p>
                        
                        <div class="blog__tags">
                            <span class="blog__tag">Database</span>
                            <span class="blog__tag">Performance</span>
                            <span class="blog__tag">Optimization</span>
                        </div>
                    </div>
                </article>
                <?php endif; ?>
            </div>
            
            <div class="blog__more">
                <?php 
                $blog_page_url = get_permalink(get_option('page_for_posts'));
                if (!$blog_page_url) {
                    $blog_page_url = home_url('/blog/');
                }
                ?>
                <a href="<?php echo esc_url($blog_page_url); ?>" class="btn btn--primary">Tüm Yazıları Gör</a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact section" id="contact">
        <div class="container">
            <div class="section__header">
                <span class="section__subtitle">Benimle İletişime Geçin</span>
                <h2 class="section__title">İletişim</h2>
            </div>
            
            <div class="contact__container grid">
                <div class="contact__content">
                    <p class="contact__description">
                        Projeleriniz hakkında konuşmak, teknik sorularınızı yanıtlamak 
                        veya iş birliği fırsatlarını değerlendirmek için benimle iletişime geçin.
                    </p>
                    
                    <div class="contact__info">
                        <div class="contact__info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h3>Email</h3>
                                <span><?php echo esc_html(get_theme_mod('contact_email', 'developer@devlog.com')); ?></span>
                            </div>
                        </div>
                        
                        <div class="contact__info-item">
                            <i class="fab fa-linkedin"></i>
                            <div>
                                <h3>LinkedIn</h3>
                                <span>linkedin.com/in/devlog</span>
                            </div>
                        </div>
                        
                        <div class="contact__info-item">
                            <i class="fab fa-github"></i>
                            <div>
                                <h3>GitHub</h3>
                                <span>github.com/devlog</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <form class="contact__form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                    <input type="hidden" name="action" value="devlog_contact">
                    <?php wp_nonce_field('devlog_contact_form', 'devlog_contact_nonce'); ?>
                    
                    <div class="contact__form-group">
                        <input type="text" name="contact_name" placeholder="Adınız" class="contact__input" required>
                    </div>
                    
                    <div class="contact__form-group">
                        <input type="email" name="contact_email" placeholder="Email Adresiniz" class="contact__input" required>
                    </div>
                    
                    <div class="contact__form-group">
                        <input type="text" name="contact_subject" placeholder="Konu" class="contact__input" required>
                    </div>
                    
                    <div class="contact__form-group">
                        <textarea name="contact_message" placeholder="Mesajınız" class="contact__input contact__textarea" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn--primary contact__btn">
                        <i class="fas fa-paper-plane"></i>
                        Mesaj Gönder
                    </button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
