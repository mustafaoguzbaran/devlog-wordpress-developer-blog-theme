// ===== Blog List Page JavaScript =====

'use strict';

// ===== Blog Filter System =====
class BlogFilters {
    constructor() {
        this.filterButtons = document.querySelectorAll('.filter-btn');
        this.blogPosts = document.querySelectorAll('.blog-post');
        this.searchInput = document.querySelector('.search-input');
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Category filter buttons
        this.filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleCategoryFilter(e.target);
            });
        });

        // Search input
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.handleSearch(e.target.value);
            });
        }
    }

    handleCategoryFilter(button) {
        const category = button.dataset.filter;
        
        // Update active button
        this.filterButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        
        // Filter posts
        this.blogPosts.forEach(post => {
            const postCategory = post.dataset.category;
            
            if (category === 'all' || postCategory === category) {
                post.style.display = 'block';
                post.style.opacity = '0';
                
                setTimeout(() => {
                    post.style.opacity = '1';
                }, 100);
            } else {
                post.style.opacity = '0';
                
                setTimeout(() => {
                    post.style.display = 'none';
                }, 300);
            }
        });
    }

    handleSearch(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        
        this.blogPosts.forEach(post => {
            const title = post.querySelector('.blog-post__title a').textContent.toLowerCase();
            const excerpt = post.querySelector('.blog-post__excerpt').textContent.toLowerCase();
            const tags = Array.from(post.querySelectorAll('.tag')).map(tag => tag.textContent.toLowerCase());
            
            const searchContent = title + ' ' + excerpt + ' ' + tags.join(' ');
            
            if (term === '' || searchContent.includes(term)) {
                post.style.display = 'block';
                post.style.opacity = '1';
            } else {
                post.style.opacity = '0';
                
                setTimeout(() => {
                    post.style.display = 'none';
                }, 300);
            }
        });
    }
}

// ===== Pagination System =====
class BlogPagination {
    constructor() {
        this.paginationNumbers = document.querySelectorAll('.pagination__number');
        this.prevButton = document.querySelector('.pagination__btn--prev');
        this.nextButton = document.querySelector('.pagination__btn--next');
        this.currentPage = 1;
        this.totalPages = 8; // Example total pages
        this.init();
    }

    init() {
        this.bindEvents();
        this.updatePagination();
    }

    bindEvents() {
        // Page numbers
        this.paginationNumbers.forEach(button => {
            button.addEventListener('click', (e) => {
                const page = parseInt(e.target.textContent);
                if (!isNaN(page)) {
                    this.goToPage(page);
                }
            });
        });

        // Previous/Next buttons
        if (this.prevButton) {
            this.prevButton.addEventListener('click', () => {
                if (this.currentPage > 1) {
                    this.goToPage(this.currentPage - 1);
                }
            });
        }

        if (this.nextButton) {
            this.nextButton.addEventListener('click', () => {
                if (this.currentPage < this.totalPages) {
                    this.goToPage(this.currentPage + 1);
                }
            });
        }
    }

    goToPage(page) {
        this.currentPage = page;
        this.updatePagination();
        
        // Scroll to top of blog posts
        const blogPosts = document.querySelector('.blog-posts');
        if (blogPosts) {
            blogPosts.scrollIntoView({ behavior: 'smooth' });
        }
        
        // Simulate loading new posts (in real app, this would be an API call)
        this.showLoadingState();
        
        setTimeout(() => {
            this.hideLoadingState();
        }, 1000);
    }

    updatePagination() {
        // Update active page number
        this.paginationNumbers.forEach(button => {
            button.classList.remove('active');
            if (parseInt(button.textContent) === this.currentPage) {
                button.classList.add('active');
            }
        });

        // Update prev/next button states
        if (this.prevButton) {
            this.prevButton.disabled = this.currentPage === 1;
        }

        if (this.nextButton) {
            this.nextButton.disabled = this.currentPage === this.totalPages;
        }
    }

    showLoadingState() {
        const blogGrid = document.querySelector('.blog-grid');
        if (blogGrid) {
            blogGrid.style.opacity = '0.5';
            blogGrid.style.pointerEvents = 'none';
        }
    }

    hideLoadingState() {
        const blogGrid = document.querySelector('.blog-grid');
        if (blogGrid) {
            blogGrid.style.opacity = '1';
            blogGrid.style.pointerEvents = 'auto';
        }
    }
}

// ===== Blog Post Animations =====
class BlogAnimations {
    constructor() {
        this.init();
    }

    init() {
        this.observeElements();
    }

    observeElements() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        const blogPosts = document.querySelectorAll('.blog-post');
        blogPosts.forEach((post, index) => {
            // Add stagger delay
            post.style.animationDelay = `${index * 0.1}s`;
            observer.observe(post);
        });
    }
}

// ===== Reading Time Calculator =====
class ReadingTimeCalculator {
    constructor() {
        this.wordsPerMinute = 200; // Average reading speed
        this.init();
    }

    init() {
        this.calculateReadingTimes();
    }

    calculateReadingTimes() {
        const excerpts = document.querySelectorAll('.blog-post__excerpt');
        
        excerpts.forEach(excerpt => {
            const text = excerpt.textContent;
            const wordCount = text.split(/\s+/).length;
            const readingTime = Math.ceil(wordCount / this.wordsPerMinute);
            
            // Update reading time if element exists
            const readingTimeElement = excerpt.closest('.blog-post__content')
                ?.querySelector('.blog-post__read-time');
            
            if (readingTimeElement && readingTime > 0) {
                readingTimeElement.innerHTML = `
                    <i class="fas fa-clock"></i>
                    ${readingTime} dk okuma
                `;
            }
        });
    }
}

// ===== Blog List App =====
class BlogListApp {
    constructor() {
        this.init();
    }

    init() {
        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.initializeComponents();
            });
        } else {
            this.initializeComponents();
        }
    }

    initializeComponents() {
        // Initialize blog-specific components
        new BlogFilters();
        new BlogPagination();
        new BlogAnimations();
        new ReadingTimeCalculator();
        
        // Add blog-specific styles
        this.addBlogStyles();
        
        // Initialize view counter simulation
        this.initViewCounters();
    }

    addBlogStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Blog post animations */
            .blog-post {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            
            .blog-post.animate-in {
                opacity: 1;
                transform: translateY(0);
            }
            
            /* Search highlight */
            .search-highlight {
                background: rgba(231, 76, 60, 0.2);
                border-radius: 2px;
                padding: 1px 2px;
            }
            
            /* Filter transition */
            .blog-post {
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            
            /* Loading state */
            .blog-grid {
                transition: opacity 0.3s ease;
            }
            
            /* Hover effects */
            .blog-post:hover .read-more-btn {
                color: var(--secondary-color);
            }
            
            .blog-post:hover .tag {
                transform: translateY(-1px);
            }
        `;
        document.head.appendChild(style);
    }

    initViewCounters() {
        // Simulate view counters for demo
        const viewElements = document.querySelectorAll('.blog-post__views');
        const viewCounts = [1250, 892, 654, 433, 267, 189];
        
        viewElements.forEach((element, index) => {
            if (viewCounts[index]) {
                element.innerHTML = `
                    <i class="fas fa-eye"></i>
                    ${viewCounts[index].toLocaleString('tr-TR')} görüntüleme
                `;
            }
        });
    }
}

// ===== Start the blog list application =====
new BlogListApp();
