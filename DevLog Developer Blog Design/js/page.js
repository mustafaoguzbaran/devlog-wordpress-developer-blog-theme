// Page Template JavaScript

class PageManager {
    constructor() {
        this.projects = [];
        this.currentFilter = 'all';
        this.currentPage = 1;
        this.projectsPerPage = 6;
        this.searchTerm = '';
        this.init();
    }

    init() {
        this.setupFilters();
        this.setupSearch();
        this.setupLoadMore();
        this.setupProjectCards();
        this.setupAnimations();
        this.initializeProjects();
    }

    // Initialize project data
    initializeProjects() {
        this.projects = this.getProjectsFromDOM();
        this.updateProjectsDisplay();
    }

    getProjectsFromDOM() {
        const projectCards = document.querySelectorAll('.project-card');
        return Array.from(projectCards).map(card => ({
            element: card,
            category: card.dataset.category || '',
            title: card.querySelector('.project-card__title')?.textContent || '',
            description: card.querySelector('.project-card__description')?.textContent || '',
            tech: Array.from(card.querySelectorAll('.tech-tag')).map(tag => tag.textContent),
            visible: true
        }));
    }

    // Filter functionality
    setupFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Update active state
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Apply filter
                this.currentFilter = button.dataset.filter;
                this.currentPage = 1;
                this.updateProjectsDisplay();
                
                // Add animation
                this.animateFilterChange();
            });
        });
    }

    // Search functionality
    setupSearch() {
        const searchInput = document.querySelector('.search-input');
        let searchTimeout;
        
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchTerm = e.target.value.toLowerCase().trim();
                    this.currentPage = 1;
                    this.updateProjectsDisplay();
                }, 300);
            });
            
            // Clear search on escape
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    this.searchTerm = '';
                    this.updateProjectsDisplay();
                    searchInput.blur();
                }
            });
        }
    }

    // Load more functionality
    setupLoadMore() {
        const loadMoreBtn = document.querySelector('.load-more-btn');
        
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => {
                this.loadMoreProjects();
            });
        }
    }

    loadMoreProjects() {
        const loadMoreBtn = document.querySelector('.load-more-btn');
        
        if (loadMoreBtn) {
            loadMoreBtn.classList.add('loading');
            loadMoreBtn.innerHTML = '<i class="fas fa-spinner"></i> Yükleniyor...';
            
            // Simulate loading delay
            setTimeout(() => {
                this.currentPage++;
                this.updateProjectsDisplay();
                
                loadMoreBtn.classList.remove('loading');
                loadMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Daha Fazla Proje Yükle';
            }, 1000);
        }
    }

    // Update projects display based on filters and search
    updateProjectsDisplay() {
        const filteredProjects = this.getFilteredProjects();
        const projectsToShow = filteredProjects.slice(0, this.currentPage * this.projectsPerPage);
        const loadMoreBtn = document.querySelector('.load-more-btn');
        
        // Hide all projects first
        this.projects.forEach(project => {
            project.element.style.display = 'none';
            project.element.classList.remove('filtered-in');
            project.visible = false;
        });
        
        // Show filtered projects with animation
        projectsToShow.forEach((project, index) => {
            setTimeout(() => {
                project.element.style.display = 'block';
                project.element.classList.add('filtered-in');
                project.visible = true;
            }, index * 100);
        });
        
        // Update load more button visibility
        if (loadMoreBtn) {
            if (projectsToShow.length < filteredProjects.length) {
                loadMoreBtn.style.display = 'inline-flex';
            } else {
                loadMoreBtn.style.display = 'none';
            }
        }
        
        // Update results count (if exists)
        this.updateResultsCount(filteredProjects.length, projectsToShow.length);
    }

    getFilteredProjects() {
        return this.projects.filter(project => {
            // Filter by category
            const categoryMatch = this.currentFilter === 'all' || 
                                project.category.includes(this.currentFilter);
            
            // Filter by search term
            const searchMatch = !this.searchTerm || 
                              project.title.toLowerCase().includes(this.searchTerm) ||
                              project.description.toLowerCase().includes(this.searchTerm) ||
                              project.tech.some(tech => tech.toLowerCase().includes(this.searchTerm));
            
            return categoryMatch && searchMatch;
        });
    }

    updateResultsCount(total, showing) {
        const resultsElement = document.querySelector('.results-count');
        if (resultsElement) {
            if (total === 0) {
                resultsElement.textContent = 'Hiç proje bulunamadı';
            } else if (showing < total) {
                resultsElement.textContent = `${showing} / ${total} proje gösteriliyor`;
            } else {
                resultsElement.textContent = `${total} proje gösteriliyor`;
            }
        }
    }

    // Project card interactions
    setupProjectCards() {
        const projectCards = document.querySelectorAll('.project-card');
        
        projectCards.forEach(card => {
            // Add hover effects
            card.addEventListener('mouseenter', () => {
                this.animateCardHover(card, true);
            });
            
            card.addEventListener('mouseleave', () => {
                this.animateCardHover(card, false);
            });
            
            // Handle detail button clicks
            const detailBtn = card.querySelector('.action-btn[aria-label="Detaylar"]');
            if (detailBtn) {
                detailBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.showProjectDetail(card);
                });
            }
        });
    }

    animateCardHover(card, isHover) {
        const image = card.querySelector('.project-card__image img');
        const overlay = card.querySelector('.project-card__overlay');
        
        if (isHover) {
            if (image) image.style.transform = 'scale(1.05)';
            if (overlay) overlay.style.opacity = '1';
        } else {
            if (image) image.style.transform = 'scale(1)';
            if (overlay) overlay.style.opacity = '0';
        }
    }

    showProjectDetail(card) {
        const title = card.querySelector('.project-card__title')?.textContent;
        const description = card.querySelector('.project-card__description')?.textContent;
        
        // Create modal or redirect to detail page
        this.createProjectModal(title, description);
    }

    createProjectModal(title, description) {
        // Remove existing modal
        const existingModal = document.querySelector('.project-modal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Create modal
        const modal = document.createElement('div');
        modal.className = 'project-modal';
        modal.innerHTML = `
            <div class="modal-backdrop"></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h3>${title}</h3>
                    <button class="modal-close" aria-label="Kapat">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>${description}</p>
                    <p>Bu proje hakkında daha detaylı bilgi yakında eklenecek...</p>
                </div>
            </div>
        `;
        
        // Add modal styles
        const styles = `
            .project-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                animation: fadeIn 0.3s ease;
            }
            
            .modal-backdrop {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
            }
            
            .modal-content {
                position: relative;
                background: var(--container-color);
                border-radius: 12px;
                max-width: 500px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
                border: 1px solid var(--border-color);
                animation: slideUp 0.3s ease;
            }
            
            .modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 20px;
                border-bottom: 1px solid var(--border-color);
            }
            
            .modal-header h3 {
                margin: 0;
                color: var(--title-color);
            }
            
            .modal-close {
                background: none;
                border: none;
                color: var(--text-color-light);
                font-size: 18px;
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.3s ease;
            }
            
            .modal-close:hover {
                color: var(--first-color);
                background: var(--first-color-light);
            }
            
            .modal-body {
                padding: 20px;
                color: var(--text-color);
                line-height: 1.6;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideUp {
                from { transform: translateY(20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
        `;
        
        // Add styles to head if not already added
        if (!document.querySelector('#modal-styles')) {
            const styleSheet = document.createElement('style');
            styleSheet.id = 'modal-styles';
            styleSheet.textContent = styles;
            document.head.appendChild(styleSheet);
        }
        
        // Add modal to body
        document.body.appendChild(modal);
        
        // Setup close functionality
        const closeBtn = modal.querySelector('.modal-close');
        const backdrop = modal.querySelector('.modal-backdrop');
        
        const closeModal = () => {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => modal.remove(), 300);
        };
        
        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', closeModal);
        
        // Close on escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
    }

    // Animation effects
    setupAnimations() {
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, observerOptions);
        
        // Observe elements
        document.querySelectorAll('.project-card, .skill-category, .stat-item').forEach(el => {
            observer.observe(el);
        });
    }

    animateFilterChange() {
        const container = document.querySelector('.projects-container');
        if (container) {
            container.classList.add('loading');
            setTimeout(() => {
                container.classList.remove('loading');
            }, 500);
        }
    }
}

// Skills interaction
class SkillsManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupSkillHovers();
        this.setupSkillAnimations();
    }

    setupSkillHovers() {
        const skillCategories = document.querySelectorAll('.skill-category');
        
        skillCategories.forEach(category => {
            category.addEventListener('mouseenter', () => {
                this.animateSkillCategory(category, true);
            });
            
            category.addEventListener('mouseleave', () => {
                this.animateSkillCategory(category, false);
            });
        });
    }

    animateSkillCategory(category, isHover) {
        const icon = category.querySelector('i');
        const tags = category.querySelectorAll('.skill-tag');
        
        if (isHover) {
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
                icon.style.color = 'var(--first-color)';
            }
            
            tags.forEach((tag, index) => {
                setTimeout(() => {
                    tag.style.transform = 'translateY(-2px)';
                    tag.style.background = 'var(--first-color)';
                    tag.style.color = 'white';
                }, index * 50);
            });
        } else {
            if (icon) {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }
            
            tags.forEach(tag => {
                tag.style.transform = 'translateY(0)';
                tag.style.background = '';
                tag.style.color = '';
            });
        }
    }

    setupSkillAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const tags = entry.target.querySelectorAll('.skill-tag');
                    tags.forEach((tag, index) => {
                        setTimeout(() => {
                            tag.style.animation = 'bounceIn 0.5s ease forwards';
                        }, index * 100);
                    });
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.skill-category').forEach(category => {
            observer.observe(category);
        });
    }
}

// Performance optimization
class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.lazyLoadImages();
        this.optimizeScrollEvents();
        this.preloadCriticalResources();
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

    optimizeScrollEvents() {
        let ticking = false;
        
        const handleScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    // Optimize scroll-dependent operations here
                    ticking = false;
                });
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', handleScroll, { passive: true });
    }

    preloadCriticalResources() {
        // Preload critical fonts
        if ('fonts' in document) {
            document.fonts.load('600 16px Nunito');
            document.fonts.load('700 32px Nunito');
        }
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PageManager();
    new SkillsManager();
    new PerformanceOptimizer();
});

// Add CSS animations
const additionalStyles = `
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3) translateY(20px);
        }
        50% {
            opacity: 1;
            transform: scale(1.05) translateY(-5px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }
`;

// Add styles to head
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);
