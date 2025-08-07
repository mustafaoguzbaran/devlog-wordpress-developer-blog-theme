/*=============== ENHANCED DEVLOG THEME SCRIPT ===============*/
document.addEventListener('DOMContentLoaded', function() {
    console.log('DevLog Theme JavaScript başlatıldı');
    
    // ===== Global Variables =====
    const header = document.getElementById('header');
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');
    const navLinks = document.querySelectorAll('.nav__link');
    const sections = document.querySelectorAll('section[id]');
    const themeToggle = document.getElementById('theme-toggle');

    // ===== Mobile Navigation =====
    class MobileNavigation {
        constructor() {
            console.log('MobileNavigation başlatıldı');
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Show menu
            if (navToggle) {
                navToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Nav toggle clicked');
                    this.showMenu();
                });
            }

            // Hide menu
            if (navClose) {
                navClose.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Nav close clicked');
                    this.hideMenu();
                });
            }

            // Hide menu when clicking nav links
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    this.hideMenu();
                });
            });

            // Hide menu when clicking outside
            document.addEventListener('click', (e) => {
                if (navMenu && navToggle && !navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                    this.hideMenu();
                }
            });

            // Handle escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.hideMenu();
                }
            });
        }

        showMenu() {
            if (navMenu) {
                navMenu.classList.add('show-menu');
                document.body.style.overflow = 'hidden';
                console.log('Menu gösterildi');
            }
        }

        hideMenu() {
            if (navMenu) {
                navMenu.classList.remove('show-menu');
                document.body.style.overflow = 'auto';
                console.log('Menu gizlendi');
            }
        }
    }

    // ===== Theme Switcher =====
    class ThemeSwitcher {
        constructor() {
            this.theme = localStorage.getItem('devlog_theme') || 'dark';
            console.log('ThemeSwitcher başlatıldı, mevcut tema:', this.theme);
            this.init();
        }

        init() {
            this.setTheme(this.theme);
            this.bindEvents();
        }

        bindEvents() {
            if (themeToggle) {
                themeToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    console.log('Theme toggle clicked');
                    this.toggleTheme();
                });
            }
        }

        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            this.setTheme(this.theme);
            localStorage.setItem('devlog_theme', this.theme);
            console.log('Tema değiştirildi:', this.theme);
        }

        setTheme(theme) {
            const body = document.body;
            const themeIcon = themeToggle?.querySelector('i');

            if (theme === 'light') {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                if (themeIcon) {
                    themeIcon.className = 'fas fa-moon';
                }
            } else {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                if (themeIcon) {
                    themeIcon.className = 'fas fa-sun';
                }
            }
        }
    }

    // ===== Smooth Scrolling =====
    class SmoothScroll {
        constructor() {
            console.log('SmoothScroll başlatıldı');
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Handle anchor links
            document.addEventListener('click', (e) => {
                const target = e.target.closest('a[href^="#"]');
                if (target) {
                    e.preventDefault();
                    const targetId = target.getAttribute('href');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        console.log('Smooth scroll to:', targetId);
                        this.scrollToElement(targetElement);
                    }
                }
            });
        }

        scrollToElement(element) {
            const headerHeight = header?.offsetHeight || 0;
            const elementPosition = element.offsetTop - headerHeight - 20;

            window.scrollTo({
                top: elementPosition,
                behavior: 'smooth'
            });
        }
    }

    // ===== Active Navigation Link =====
    class ActiveNavigation {
        constructor() {
            console.log('ActiveNavigation başlatıldı');
            this.init();
        }

        init() {
            this.bindEvents();
            this.updateActiveLink();
        }

        bindEvents() {
            window.addEventListener('scroll', () => {
                this.updateActiveLink();
            });
        }

        updateActiveLink() {
            const scrollY = window.pageYOffset;
            const headerHeight = header?.offsetHeight || 0;

            sections.forEach(section => {
                const sectionHeight = section.offsetHeight;
                const sectionTop = section.offsetTop - headerHeight - 50;
                const sectionId = section.getAttribute('id');
                const navLink = document.querySelector(`.nav__link[href="#${sectionId}"]`);

                if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    navLink?.classList.add('active');
                }
            });
        }
    }

    // ===== Header Scroll Effect =====
    class HeaderScroll {
        constructor() {
            console.log('HeaderScroll başlatıldı');
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            window.addEventListener('scroll', () => {
                this.updateHeader();
            });
        }

        updateHeader() {
            const scrollY = window.pageYOffset;
            
            if (header) {
                if (scrollY >= 50) {
                    header.classList.add('scroll-header');
                } else {
                    header.classList.remove('scroll-header');
                }
            }
        }
    }

    // ===== Back to Top Button =====
    class BackToTop {
        constructor() {
            console.log('BackToTop başlatıldı');
            this.createButton();
            this.init();
        }

        createButton() {
            // Create back to top button if it doesn't exist
            let backToTopBtn = document.getElementById('scroll-top');
            if (!backToTopBtn) {
                backToTopBtn = document.createElement('button');
                backToTopBtn.id = 'scroll-top';
                backToTopBtn.className = 'back-to-top';
                backToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
                backToTopBtn.setAttribute('aria-label', 'Yukarı Çık');
                backToTopBtn.title = 'Yukarı Çık';
                document.body.appendChild(backToTopBtn);
                console.log('Scroll-to-top butonu oluşturuldu');
            }
            this.button = backToTopBtn;
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Show/hide button based on scroll position
            window.addEventListener('scroll', () => {
                this.toggleVisibility();
            });

            // Handle click
            this.button.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('Scroll to top clicked');
                this.scrollToTop();
            });
        }

        toggleVisibility() {
            const scrollY = window.pageYOffset;
            
            if (scrollY >= 300) {
                this.button.classList.add('show');
            } else {
                this.button.classList.remove('show');
            }
        }

        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }

    // ===== Enhanced Skills Animation =====
    class SkillsAnimation {
        constructor() {
            this.animatedSkills = new Set();
            console.log('SkillsAnimation başlatıldı');
            this.init();
        }

        init() {
            this.createObserver();
            this.setupHoverEffects();
        }

        createObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !this.animatedSkills.has(entry.target)) {
                        console.log('Skills bölümü görünür, animasyon başlatılıyor');
                        this.animateSkillsSection(entry.target);
                        this.animatedSkills.add(entry.target);
                    }
                });
            }, { 
                threshold: 0.3,
                rootMargin: '0px 0px -50px 0px'
            });

            const skillsSection = document.getElementById('skills');
            if (skillsSection) {
                observer.observe(skillsSection);
                console.log('Skills section observer eklendi');
            }
        }

        animateSkillsSection(skillsSection) {
            // Animate categories with stagger
            const categories = skillsSection.querySelectorAll('.skills__category');
            console.log('Animasyon başlatıldı, kategori sayısı:', categories.length);
            
            categories.forEach((category, categoryIndex) => {
                setTimeout(() => {
                    category.style.opacity = '0';
                    category.style.transform = 'translateY(30px)';
                    category.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    setTimeout(() => {
                        category.style.opacity = '1';
                        category.style.transform = 'translateY(0)';
                        
                        // Animate skill bars in this category
                        this.animateSkillBars(category, categoryIndex);
                    }, 100);
                }, categoryIndex * 200);
            });

            // Animate stats if present
            setTimeout(() => {
                this.animateStats(skillsSection);
            }, categories.length * 200 + 500);
        }

        animateSkillBars(category, categoryDelay = 0) {
            const skillBars = category.querySelectorAll('.skill__progress');
            console.log('Skill bar animasyonu başlatılıyor, bar sayısı:', skillBars.length);
            
            skillBars.forEach((bar, index) => {
                const percentage = bar.getAttribute('data-percentage') || bar.dataset.percentage || '50';
                const skillName = bar.dataset.skill || 'Skill';
                
                console.log(`${skillName}: ${percentage}% animasyon başlatılıyor`);
                
                // Reset width
                bar.style.width = '0%';
                bar.style.transition = 'none';
                
                setTimeout(() => {
                    bar.style.transition = 'width 1.5s cubic-bezier(0.4, 0, 0.2, 1)';
                    bar.style.width = percentage + '%';
                    
                    // Add counter animation for percentage
                    this.animatePercentageCounter(bar, parseInt(percentage), skillName);
                    
                    // Add pulse effect when animation completes
                    setTimeout(() => {
                        bar.style.boxShadow = '0 0 20px rgba(231, 76, 60, 0.6)';
                        setTimeout(() => {
                            bar.style.boxShadow = '0 2px 4px rgba(231, 76, 60, 0.3)';
                        }, 300);
                    }, 1500);
                    
                }, (index * 150) + (categoryDelay * 100));
            });
        }

        animatePercentageCounter(bar, targetPercentage, skillName) {
            const skillItem = bar.closest('.skill__item');
            const percentageElement = skillItem?.querySelector('.skill__percentage');
            
            if (!percentageElement) return;
            
            let currentPercentage = 0;
            const increment = targetPercentage / 60; // 60 frames for smooth animation
            const duration = 1500; // 1.5 seconds
            const frameDuration = duration / 60;
            
            const counter = setInterval(() => {
                currentPercentage += increment;
                
                if (currentPercentage >= targetPercentage) {
                    currentPercentage = targetPercentage;
                    clearInterval(counter);
                    
                    // Add completion effect
                    percentageElement.style.transform = 'scale(1.1)';
                    setTimeout(() => {
                        percentageElement.style.transform = 'scale(1)';
                    }, 200);
                }
                
                percentageElement.textContent = Math.round(currentPercentage) + '%';
            }, frameDuration);
        }

        animateStats(skillsSection) {
            const statsSection = skillsSection.querySelector('.skills__stats');
            if (!statsSection) return;
            
            const statItems = statsSection.querySelectorAll('.skill__stat');
            console.log('Stats animasyonu başlatılıyor, stat sayısı:', statItems.length);
            
            statItems.forEach((stat, index) => {
                setTimeout(() => {
                    stat.style.opacity = '0';
                    stat.style.transform = 'scale(0.8) translateY(20px)';
                    stat.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                    
                    setTimeout(() => {
                        stat.style.opacity = '1';
                        stat.style.transform = 'scale(1) translateY(0)';
                        
                        // Animate numbers in stats
                        this.animateStatNumber(stat);
                    }, 50);
                }, index * 100);
            });
        }

        animateStatNumber(statElement) {
            const numberElement = statElement.querySelector('.skill__stat-number');
            if (!numberElement) return;
            
            const text = numberElement.textContent;
            const number = parseInt(text.replace(/\D/g, ''));
            
            if (isNaN(number)) return;
            
            let currentNumber = 0;
            const increment = number / 30;
            const suffix = text.replace(/\d/g, '');
            
            const counter = setInterval(() => {
                currentNumber += increment;
                
                if (currentNumber >= number) {
                    currentNumber = number;
                    clearInterval(counter);
                }
                
                numberElement.textContent = Math.round(currentNumber) + suffix;
            }, 50);
        }

        setupHoverEffects() {
            // Enhanced hover effects for skill items
            document.addEventListener('mouseover', (e) => {
                const skillItem = e.target.closest('.skill__item');
                if (skillItem) {
                    this.highlightSkill(skillItem);
                }
            });

            document.addEventListener('mouseout', (e) => {
                const skillItem = e.target.closest('.skill__item');
                if (skillItem) {
                    this.removeHighlight(skillItem);
                }
            });

            // Category hover effects
            document.addEventListener('mouseover', (e) => {
                const category = e.target.closest('.skills__category');
                if (category) {
                    this.highlightCategory(category);
                }
            });

            document.addEventListener('mouseout', (e) => {
                const category = e.target.closest('.skills__category');
                if (category) {
                    this.removeHighlightCategory(category);
                }
            });
        }

        highlightSkill(skillItem) {
            const progressBar = skillItem.querySelector('.skill__progress');
            const percentage = skillItem.querySelector('.skill__percentage');
            
            if (progressBar) {
                progressBar.style.filter = 'brightness(1.2) saturate(1.3)';
                progressBar.style.transform = 'scaleY(1.2)';
            }
            
            if (percentage) {
                percentage.style.transform = 'scale(1.1)';
                percentage.style.background = 'var(--primary-color)';
                percentage.style.color = 'white';
            }
        }

        removeHighlight(skillItem) {
            const progressBar = skillItem.querySelector('.skill__progress');
            const percentage = skillItem.querySelector('.skill__percentage');
            
            if (progressBar) {
                progressBar.style.filter = '';
                progressBar.style.transform = '';
            }
            
            if (percentage) {
                percentage.style.transform = '';
                percentage.style.background = '';
                percentage.style.color = '';
            }
        }

        highlightCategory(category) {
            const icon = category.querySelector('.skills__category-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
                icon.style.boxShadow = '0 10px 25px rgba(231, 76, 60, 0.5)';
            }
        }

        removeHighlightCategory(category) {
            const icon = category.querySelector('.skills__category-icon');
            if (icon) {
                icon.style.transform = '';
                icon.style.boxShadow = '';
            }
        }

        // Method to manually trigger animation (useful for dynamic content)
        triggerAnimation(element) {
            if (element && !this.animatedSkills.has(element)) {
                this.animateSkillsSection(element);
                this.animatedSkills.add(element);
            }
        }

        // Reset animations (useful for SPA-like behavior)
        resetAnimations() {
            this.animatedSkills.clear();
            
            // Reset all skill bars
            document.querySelectorAll('.skill__progress').forEach(bar => {
                bar.style.width = '0%';
                bar.style.transition = 'none';
            });
            
            // Reset all percentages
            document.querySelectorAll('.skill__percentage').forEach(percentage => {
                const skillItem = percentage.closest('.skill__item');
                if (skillItem) {
                    const originalPercentage = skillItem.dataset.percentage || '0';
                    percentage.textContent = '0%';
                }
            });
        }
    }

    // ===== Contact Form Handler =====
    class ContactForm {
        constructor() {
            this.form = document.querySelector('.contact__form');
            console.log('ContactForm başlatıldı');
            this.init();
        }

        init() {
            if (this.form) {
                this.bindEvents();
            }
        }

        bindEvents() {
            this.form.addEventListener('submit', (e) => {
                this.handleSubmit(e);
            });
        }

        async handleSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(this.form);
            const submitBtn = this.form.querySelector('.contact__btn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(devlog_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    this.showMessage('Mesajınız başarıyla gönderildi!', 'success');
                    this.form.reset();
                } else {
                    throw new Error('Form submission failed');
                }
            } catch (error) {
                this.showMessage('Bir hata oluştu. Lütfen tekrar deneyin.', 'error');
            } finally {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        showMessage(message, type) {
            // Remove existing messages
            const existingMessages = this.form.querySelectorAll('.contact__message');
            existingMessages.forEach(msg => msg.remove());

            const messageDiv = document.createElement('div');
            messageDiv.className = `contact__message contact__message--${type}`;
            messageDiv.textContent = message;
            
            this.form.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }
    }

    // ===== Scroll Animations =====
    class ScrollAnimations {
        constructor() {
            this.observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            console.log('ScrollAnimations başlatıldı');
            this.init();
        }

        init() {
            this.createObserver();
            this.observeElements();
        }

        createObserver() {
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                        console.log('Element animasyona eklendi:', entry.target.className);
                        this.observer.unobserve(entry.target);
                    }
                });
            }, this.observerOptions);
        }

        observeElements() {
            const elementsToAnimate = document.querySelectorAll(`
                .hero__content,
                .hero__image,
                .about__content,
                .about__stats,
                .experience__item,
                .skills__category,
                .blog__card,
                .contact__content,
                .contact__form
            `);

            console.log('Animasyon için izlenecek element sayısı:', elementsToAnimate.length);
            elementsToAnimate.forEach(element => {
                this.observer.observe(element);
            });
        }
    }

    // ===== Initialize Components =====
    function initializeComponents() {
        console.log('Tüm bileşenler başlatılıyor...');
        
        new MobileNavigation();
        new ThemeSwitcher();
        new SmoothScroll();
        new ActiveNavigation();
        new HeaderScroll();
        new BackToTop();
        new SkillsAnimation();
        new ContactForm();
        new ScrollAnimations();
        
        // Add CSS for effects
        addDynamicStyles();
        
        console.log('Tüm bileşenler başarıyla başlatıldı!');
    }

    // ===== Add Dynamic Styles =====
    function addDynamicStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Scroll header effect */
            .scroll-header {
                background: rgba(10, 10, 10, 0.98) !important;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            }
            
            /* Animation classes */
            .hero__content,
            .hero__image,
            .about__content,
            .about__stats,
            .experience__item,
            .skills__category,
            .blog__card,
            .contact__content,
            .contact__form {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.8s ease;
            }
            
            .hero__content.animate,
            .hero__image.animate,
            .about__content.animate,
            .about__stats.animate,
            .experience__item.animate,
            .skills__category.animate,
            .blog__card.animate,
            .contact__content.animate,
            .contact__form.animate {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Stagger animation delays */
            .experience__item:nth-child(1).animate { transition-delay: 0.1s; }
            .experience__item:nth-child(2).animate { transition-delay: 0.2s; }
            .experience__item:nth-child(3).animate { transition-delay: 0.3s; }
            
            .skills__category:nth-child(1).animate { transition-delay: 0.1s; }
            .skills__category:nth-child(2).animate { transition-delay: 0.2s; }
            .skills__category:nth-child(3).animate { transition-delay: 0.3s; }
            .skills__category:nth-child(4).animate { transition-delay: 0.4s; }
            
            .blog__card:nth-child(1).animate { transition-delay: 0.1s; }
            .blog__card:nth-child(2).animate { transition-delay: 0.2s; }
            .blog__card:nth-child(3).animate { transition-delay: 0.3s; }
            .blog__card:nth-child(4).animate { transition-delay: 0.4s; }
            .blog__card:nth-child(5).animate { transition-delay: 0.5s; }
            .blog__card:nth-child(6).animate { transition-delay: 0.6s; }
            
            /* Contact form message styles */
            .contact__message {
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 0.5rem;
                font-weight: 500;
                animation: slideInDown 0.3s ease;
            }
            
            .contact__message--success {
                background: rgba(39, 174, 96, 0.1);
                color: #27ae60;
                border: 1px solid rgba(39, 174, 96, 0.3);
            }
            
            .contact__message--error {
                background: rgba(231, 76, 60, 0.1);
                color: #e74c3c;
                border: 1px solid rgba(231, 76, 60, 0.3);
            }
            
            /* Back to top button */
            .back-to-top {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                width: 50px;
                height: 50px;
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                border: none;
                border-radius: 50%;
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transform: translateY(20px);
                transition: all 0.3s ease;
                z-index: 1000;
                box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .back-to-top.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }
            
            .back-to-top:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            }
            
            .back-to-top i {
                font-size: 1.2rem;
            }
            
            @keyframes slideInDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @media screen and (max-width: 768px) {
                .back-to-top {
                    bottom: 1rem;
                    right: 1rem;
                    width: 45px;
                    height: 45px;
                }
            }
        `;
        document.head.appendChild(style);
        console.log('Dinamik CSS stilleri eklendi');
    }

    // Initialize all components
    initializeComponents();
    
    // ===== CV Download Handler =====
    const cvButton = document.querySelector('.btn--primary[href*="download_cv"]');
    if (cvButton) {
        cvButton.addEventListener('click', function(e) {
            console.log('CV download başlatıldı');
            
            // Check if it's a real download link
            if (this.href.includes('download_cv=1')) {
                console.log('CV dosyası indiriliyor...');
                
                // Optional: Show a success message
                setTimeout(() => {
                    if (typeof window.alert !== 'undefined') {
                        // You can replace this with a nicer notification
                        console.log('CV download initiated successfully');
                    }
                }, 100);
            }
        });
    }
});
