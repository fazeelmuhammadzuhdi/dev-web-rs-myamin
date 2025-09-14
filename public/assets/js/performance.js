/**
 * Performance Optimization JavaScript
 * Handles lazy loading, critical resource optimization, and user experience improvements
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        lazyLoadThreshold: 0.1,
        debounceDelay: 250,
        throttleDelay: 100,
        preloadDistance: 200,
        cacheTimeout: 300000, // 5 minutes
    };

    // Performance utilities
    const PerformanceUtils = {
        // Debounce function
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Throttle function
        throttle(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        },

        // Check if element is in viewport
        isInViewport(element, threshold = 0) {
            const rect = element.getBoundingClientRect();
            const windowHeight = window.innerHeight || document.documentElement.clientHeight;
            const windowWidth = window.innerWidth || document.documentElement.clientWidth;
            
            return (
                rect.top <= windowHeight * (1 + threshold) &&
                rect.bottom >= -windowHeight * threshold &&
                rect.left <= windowWidth * (1 + threshold) &&
                rect.right >= -windowWidth * threshold
            );
        },

        // Preload resource
        preloadResource(href, as = 'script') {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.href = href;
            link.as = as;
            document.head.appendChild(link);
        },

        // Load script dynamically
        loadScript(src, async = true, defer = true) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.async = async;
                script.defer = defer;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
    };

    // Lazy Loading Manager
    class LazyLoadManager {
        constructor() {
            this.images = [];
            this.observer = null;
            this.init();
        }

        init() {
            this.images = document.querySelectorAll('img[data-src], img[loading="lazy"]');
            this.setupIntersectionObserver();
            this.loadVisibleImages();
        }

        setupIntersectionObserver() {
            if ('IntersectionObserver' in window) {
                this.observer = new IntersectionObserver(
                    this.handleIntersection.bind(this),
                    {
                        rootMargin: `${CONFIG.preloadDistance}px`,
                        threshold: CONFIG.lazyLoadThreshold
                    }
                );

                this.images.forEach(img => {
                    if (img.dataset.src) {
                        this.observer.observe(img);
                    }
                });
            } else {
                // Fallback for older browsers
                this.loadAllImages();
            }
        }

        handleIntersection(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }

        loadImage(img) {
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.classList.add('loaded');
                delete img.dataset.src;
            }
        }

        loadVisibleImages() {
            this.images.forEach(img => {
                if (PerformanceUtils.isInViewport(img)) {
                    this.loadImage(img);
                }
            });
        }

        loadAllImages() {
            this.images.forEach(img => this.loadImage(img));
        }
    }

    // Critical Resource Manager
    class CriticalResourceManager {
        constructor() {
            this.loadedResources = new Set();
            this.init();
        }

        init() {
            this.preloadCriticalResources();
            this.deferNonCriticalResources();
            this.optimizeFonts();
        }

        preloadCriticalResources() {
            const criticalResources = [
                { href: '/assets/css/critical.css', as: 'style' },
                { href: '/assets/fonts/inter-regular.woff2', as: 'font', type: 'font/woff2' },
                { href: '/assets/fonts/inter-semibold.woff2', as: 'font', type: 'font/woff2' }
            ];

            criticalResources.forEach(resource => {
                if (!this.loadedResources.has(resource.href)) {
                    PerformanceUtils.preloadResource(resource.href, resource.as);
                    this.loadedResources.add(resource.href);
                }
            });
        }

        deferNonCriticalResources() {
            const nonCriticalScripts = [
                '/assets/js/analytics.js',
                '/assets/js/chat-widget.js',
                '/assets/js/social-share.js'
            ];

            // Load non-critical scripts after page load
            window.addEventListener('load', () => {
                setTimeout(() => {
                    nonCriticalScripts.forEach(script => {
                        PerformanceUtils.loadScript(script);
                    });
                }, 1000);
            });
        }

        optimizeFonts() {
            // Check if fonts are loaded
            if ('fonts' in document) {
                document.fonts.ready.then(() => {
                    document.body.classList.add('fonts-loaded');
                });
            }
        }
    }

    // Performance Monitor
    class PerformanceMonitor {
        constructor() {
            this.metrics = {};
            this.init();
        }

        init() {
            this.measurePageLoad();
            this.measureUserInteractions();
            this.setupPerformanceObserver();
        }

        measurePageLoad() {
            window.addEventListener('load', () => {
                setTimeout(() => {
                    const navigation = performance.getEntriesByType('navigation')[0];
                    const paint = performance.getEntriesByType('paint');
                    
                    this.metrics = {
                        domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                        loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                        firstPaint: paint.find(entry => entry.name === 'first-paint')?.startTime || 0,
                        firstContentfulPaint: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0,
                        totalLoadTime: navigation.loadEventEnd - navigation.fetchStart
                    };

                    this.reportMetrics();
                }, 0);
            });
        }

        measureUserInteractions() {
            let interactionCount = 0;
            const interactionEvents = ['click', 'keydown', 'scroll', 'touchstart'];

            interactionEvents.forEach(event => {
                document.addEventListener(event, PerformanceUtils.throttle(() => {
                    interactionCount++;
                    this.metrics.interactionCount = interactionCount;
                }, CONFIG.throttleDelay));
            });
        }

        setupPerformanceObserver() {
            if ('PerformanceObserver' in window) {
                const observer = new PerformanceObserver((list) => {
                    list.getEntries().forEach(entry => {
                        if (entry.entryType === 'largest-contentful-paint') {
                            this.metrics.lcp = entry.startTime;
                        }
                        if (entry.entryType === 'first-input') {
                            this.metrics.fid = entry.processingStart - entry.startTime;
                        }
                        if (entry.entryType === 'layout-shift') {
                            if (!this.metrics.cls) this.metrics.cls = 0;
                            if (!entry.hadRecentInput) {
                                this.metrics.cls += entry.value;
                            }
                        }
                    });
                });

                observer.observe({ entryTypes: ['largest-contentful-paint', 'first-input', 'layout-shift'] });
            }
        }

        reportMetrics() {
            // Send metrics to analytics (if available)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'performance_metrics', {
                    custom_map: {
                        'metric_1': 'dom_content_loaded',
                        'metric_2': 'load_complete',
                        'metric_3': 'first_paint',
                        'metric_4': 'first_contentful_paint',
                        'metric_5': 'total_load_time'
                    },
                    dom_content_loaded: Math.round(this.metrics.domContentLoaded),
                    load_complete: Math.round(this.metrics.loadComplete),
                    first_paint: Math.round(this.metrics.firstPaint),
                    first_contentful_paint: Math.round(this.metrics.firstContentfulPaint),
                    total_load_time: Math.round(this.metrics.totalLoadTime)
                });
            }

            // Log to console in development
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                console.log('Performance Metrics:', this.metrics);
            }
        }
    }

    // Accessibility Manager
    class AccessibilityManager {
        constructor() {
            this.init();
        }

        init() {
            this.setupKeyboardNavigation();
            this.setupFocusManagement();
            this.setupScreenReaderSupport();
            this.setupHighContrastMode();
        }

        setupKeyboardNavigation() {
            // Skip links functionality
            const skipLinks = document.querySelectorAll('.skip-link');
            skipLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector(link.getAttribute('href'));
                    if (target) {
                        target.focus();
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });

            // Trap focus in modals
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    const modal = document.querySelector('.modal.show');
                    if (modal) {
                        this.trapFocus(modal, e);
                    }
                }
            });
        }

        setupFocusManagement() {
            // Visible focus indicators
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });

            document.addEventListener('mousedown', () => {
                document.body.classList.remove('keyboard-navigation');
            });
        }

        setupScreenReaderSupport() {
            // Announce dynamic content changes
            const announcer = document.createElement('div');
            announcer.setAttribute('aria-live', 'polite');
            announcer.setAttribute('aria-atomic', 'true');
            announcer.className = 'sr-only';
            document.body.appendChild(announcer);

            // Global function to announce messages
            window.announceToScreenReader = (message) => {
                announcer.textContent = message;
                setTimeout(() => {
                    announcer.textContent = '';
                }, 1000);
            };
        }

        setupHighContrastMode() {
            // Check for high contrast preference
            if (window.matchMedia('(prefers-contrast: high)').matches) {
                document.body.classList.add('high-contrast');
            }

            // Listen for changes
            window.matchMedia('(prefers-contrast: high)').addEventListener('change', (e) => {
                if (e.matches) {
                    document.body.classList.add('high-contrast');
                } else {
                    document.body.classList.remove('high-contrast');
                }
            });
        }

        trapFocus(element, event) {
            const focusableElements = element.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const firstElement = focusableElements[0];
            const lastElement = focusableElements[focusableElements.length - 1];

            if (event.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    event.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    event.preventDefault();
                }
            }
        }
    }

    // Initialize all managers when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        new LazyLoadManager();
        new CriticalResourceManager();
        new PerformanceMonitor();
        new AccessibilityManager();
    });

    // Export for global access
    window.PerformanceUtils = PerformanceUtils;

})();