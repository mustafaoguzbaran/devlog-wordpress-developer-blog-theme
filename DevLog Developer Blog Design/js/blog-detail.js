// Blog Detail Page JavaScript

class BlogDetail {
    constructor() {
        this.init();
    }

    init() {
        this.setupTableOfContents();
        this.setupCodeCopyButtons();
        this.setupSocialShare();
        this.setupLikeButton();
        this.setupScrollProgress();
        this.setupReadingTime();
    }

    // Table of Contents functionality
    setupTableOfContents() {
        const tocLinks = document.querySelectorAll('.toc-link');
        const sections = document.querySelectorAll('h2[id], h3[id]');
        
        // Update active link on scroll
        const observerOptions = {
            rootMargin: '-100px 0px -50% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    this.updateActiveTocLink(id);
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });

        // Smooth scroll for TOC links
        tocLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    const headerOffset = 120;
                    const elementPosition = targetSection.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    updateActiveTocLink(activeId) {
        const tocLinks = document.querySelectorAll('.toc-link');
        
        tocLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${activeId}`) {
                link.classList.add('active');
            }
        });
    }

    // Code copy functionality
    setupCodeCopyButtons() {
        const copyButtons = document.querySelectorAll('.copy-btn');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', () => {
                const codeId = button.getAttribute('data-copy');
                const codeElement = document.getElementById(codeId);
                
                if (codeElement) {
                    const text = codeElement.textContent;
                    this.copyToClipboard(text, button);
                }
            });
        });
    }

    async copyToClipboard(text, button) {
        try {
            await navigator.clipboard.writeText(text);
            this.showCopySuccess(button);
        } catch (err) {
            console.error('Kopyalama başarısız:', err);
            this.fallbackCopyToClipboard(text, button);
        }
    }

    fallbackCopyToClipboard(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            this.showCopySuccess(button);
        } catch (err) {
            console.error('Fallback kopyalama başarısız:', err);
        }
        
        document.body.removeChild(textArea);
    }

    showCopySuccess(button) {
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.style.background = 'var(--first-color)';
        button.style.color = 'white';
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.style.background = '';
            button.style.color = '';
        }, 2000);
    }

    // Social sharing functionality
    setupSocialShare() {
        const shareButtons = document.querySelectorAll('.share-btn');
        const pageUrl = encodeURIComponent(window.location.href);
        const pageTitle = encodeURIComponent(document.title);
        
        shareButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (button.classList.contains('twitter')) {
                    const twitterUrl = `https://twitter.com/intent/tweet?url=${pageUrl}&text=${pageTitle}`;
                    window.open(twitterUrl, '_blank', 'width=550,height=420');
                    
                } else if (button.classList.contains('linkedin')) {
                    const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${pageUrl}`;
                    window.open(linkedinUrl, '_blank', 'width=550,height=420');
                    
                } else if (button.classList.contains('facebook')) {
                    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${pageUrl}`;
                    window.open(facebookUrl, '_blank', 'width=550,height=420');
                    
                } else if (button.classList.contains('copy-link')) {
                    this.copyToClipboard(window.location.href, button);
                }
            });
        });
    }

    // Like button functionality
    setupLikeButton() {
        const likeButton = document.querySelector('.like-btn');
        const likeCount = document.querySelector('.like-count');
        
        if (likeButton && likeCount) {
            // Check if already liked
            const isLiked = localStorage.getItem('liked-' + window.location.pathname) === 'true';
            if (isLiked) {
                likeButton.classList.add('liked');
            }
            
            likeButton.addEventListener('click', () => {
                const currentlyLiked = likeButton.classList.contains('liked');
                let currentCount = parseInt(likeCount.textContent);
                
                if (currentlyLiked) {
                    // Unlike
                    likeButton.classList.remove('liked');
                    currentCount--;
                    localStorage.setItem('liked-' + window.location.pathname, 'false');
                } else {
                    // Like
                    likeButton.classList.add('liked');
                    currentCount++;
                    localStorage.setItem('liked-' + window.location.pathname, 'true');
                    
                    // Add animation
                    this.animateLike(likeButton);
                }
                
                likeCount.textContent = currentCount;
            });
        }
    }

    animateLike(button) {
        button.style.transform = 'scale(1.2)';
        setTimeout(() => {
            button.style.transform = '';
        }, 200);
    }

    // Reading progress indicator
    setupScrollProgress() {
        // Create progress bar if not exists
        let progressBar = document.querySelector('.reading-progress');
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.className = 'reading-progress';
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 3px;
                background: var(--first-color);
                z-index: 9999;
                transition: width 0.1s ease;
            `;
            document.body.appendChild(progressBar);
        }
        
        window.addEventListener('scroll', () => {
            const article = document.querySelector('.article-text');
            if (!article) return;
            
            const articleRect = article.getBoundingClientRect();
            const articleTop = articleRect.top + window.pageYOffset;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollTop = window.pageYOffset;
            
            const startReading = articleTop - windowHeight / 2;
            const endReading = articleTop + articleHeight - windowHeight / 2;
            
            if (scrollTop >= startReading && scrollTop <= endReading) {
                const progress = (scrollTop - startReading) / (endReading - startReading);
                progressBar.style.width = Math.min(100, Math.max(0, progress * 100)) + '%';
            } else if (scrollTop < startReading) {
                progressBar.style.width = '0%';
            } else {
                progressBar.style.width = '100%';
            }
        });
    }

    // Reading time calculator
    setupReadingTime() {
        const articleText = document.querySelector('.article-text');
        const readTimeElement = document.querySelector('.article-read-time');
        
        if (articleText && readTimeElement) {
            const text = articleText.textContent || articleText.innerText;
            const wordsPerMinute = 200; // Average reading speed
            const words = text.trim().split(/\s+/).length;
            const readingTime = Math.ceil(words / wordsPerMinute);
            
            // Update reading time in the meta section
            const readTimeText = readTimeElement.querySelector('span') || readTimeElement;
            readTimeText.textContent = `${readingTime} dk okuma`;
        }
    }
}

// Font loading optimization
class FontLoader {
    constructor() {
        this.loadFonts();
    }

    async loadFonts() {
        if ('fonts' in document) {
            try {
                await document.fonts.load('400 16px Nunito');
                await document.fonts.load('600 16px Nunito');
                await document.fonts.load('700 32px Nunito');
                document.body.classList.add('fonts-loaded');
            } catch (error) {
                console.warn('Font loading failed:', error);
            }
        }
    }
}

// Performance optimization
class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.lazyLoadImages();
        this.deferNonCriticalCSS();
    }

    lazyLoadImages() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    deferNonCriticalCSS() {
        const links = document.querySelectorAll('link[rel="stylesheet"][data-defer]');
        links.forEach(link => {
            link.rel = 'stylesheet';
            link.removeAttribute('data-defer');
        });
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BlogDetail();
    new FontLoader();
    new PerformanceOptimizer();
});

// Enhanced mobile scroll behavior
let lastScrollTop = 0;
const header = document.querySelector('.header');

window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (window.innerWidth <= 768) {
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
    }
    
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
}, { passive: true });
