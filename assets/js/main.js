// ===== Modern JavaScript for DevLog WordPress Theme =====

'use strict';

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // ===== DOM Elements =====
    const navMenu = document.getElementById('nav-menu');
    const navToggle = document.getElementById('nav-toggle');
    const navClose = document.getElementById('nav-close');
    const themeToggle = document.getElementById('theme-toggle');
    const navLinks = document.querySelectorAll('.nav__link');
    const sections = document.querySelectorAll('.section');
    const header = document.querySelector('.header');

    // ===== Mobile Navigation =====
    class MobileNavigation {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
        }

        bindEvents() {
            // Show menu
            if (navToggle) {
                navToggle.addEventListener('click', () => {
                    this.showMenu();
                });
            }

            // Hide menu
            if (navClose) {
                navClose.addEventListener('click', () => {
                    this.hideMenu();
                });
            }

            // Hide menu when clicking on nav links
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
            }
        }

        hideMenu() {
            if (navMenu) {
                navMenu.classList.remove('show-menu');
                document.body.style.overflow = 'auto';
            }
        }
    }

    // ===== Theme Switcher =====
    class ThemeSwitcher {
        constructor() {
            this.theme = localStorage.getItem('devlog_theme') || 'dark';
            this.init();
        }

        init() {
            this.setTheme(this.theme);
            this.bindEvents();
        }

        bindEvents() {
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    this.toggleTheme();
                });
            }
        }

        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            this.setTheme(this.theme);
            localStorage.setItem('devlog_theme', this.theme);
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

    // ===== Skills Animation =====
    class SkillsAnimation {
        constructor() {
            this.init();
        }

        init() {
            this.createObserver();
        }

        createObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateSkills(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            const skillsSection = document.getElementById('skills');
            if (skillsSection) {
                observer.observe(skillsSection);
            }
        }

        animateSkills(skillsSection) {
            const skillBars = skillsSection.querySelectorAll('.skill__progress');
            
            skillBars.forEach((bar, index) => {
                setTimeout(() => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                }, index * 200);
            });
        }
    }

    // ===== Contact Form Handler =====
    class ContactForm {
        constructor() {
            this.form = document.querySelector('.contact__form');
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

            elementsToAnimate.forEach(element => {
                this.observer.observe(element);
            });
        }
    }

    // ===== Back to Top Button =====
    class BackToTop {
        constructor() {
            this.init();
        }

        init() {
            this.createButton();
            this.bindEvents();
        }

        createButton() {
            const button = document.createElement('button');
            button.className = 'back-to-top';
            button.innerHTML = '<i class="fas fa-arrow-up"></i>';
            button.setAttribute('aria-label', 'Sayfa başına dön');
            
            document.body.appendChild(button);
            this.button = button;
        }

        bindEvents() {
            window.addEventListener('scroll', () => {
                this.toggleVisibility();
            });
            
            this.button.addEventListener('click', () => {
                this.scrollToTop();
            });
        }

        toggleVisibility() {
            const scrollY = window.pageYOffset;
            
            if (scrollY > 500) {
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

    // ===== Initialize Components =====
    function initializeComponents() {
        new MobileNavigation();
        new ThemeSwitcher();
        new SmoothScroll();
        new ActiveNavigation();
        new HeaderScroll();
        new ScrollAnimations();
        new SkillsAnimation();
        new ContactForm();
        new BackToTop();
        
        // Add CSS for effects
        addDynamicStyles();
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
    }

    // Initialize all components
    initializeComponents();
});
