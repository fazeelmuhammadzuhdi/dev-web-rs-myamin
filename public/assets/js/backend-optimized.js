/**
 * Backend Optimized JavaScript
 * Performance and Accessibility Focused
 */

(function() {
    'use strict';

    // Performance monitoring
    const performanceMonitor = {
        init: function() {
            this.measurePageLoad();
            this.measureUserInteractions();
        },

        measurePageLoad: function() {
            window.addEventListener('load', () => {
                if ('performance' in window) {
                    const navigation = performance.getEntriesByType('navigation')[0];
                    const paint = performance.getEntriesByType('paint');
                    
                    const metrics = {
                        domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                        loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                        firstPaint: paint.find(entry => entry.name === 'first-paint')?.startTime || 0,
                        firstContentfulPaint: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0
                    };
                    
                    console.log('Performance Metrics:', metrics);
                    
                    // Send to analytics if available
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'performance_metrics', {
                            custom_map: {
                                'metric_1': 'dom_content_loaded',
                                'metric_2': 'load_complete',
                                'metric_3': 'first_paint',
                                'metric_4': 'first_contentful_paint'
                            },
                            dom_content_loaded: Math.round(metrics.domContentLoaded),
                            load_complete: Math.round(metrics.loadComplete),
                            first_paint: Math.round(metrics.firstPaint),
                            first_contentful_paint: Math.round(metrics.firstContentfulPaint)
                        });
                    }
                }
            });
        },

        measureUserInteractions: function() {
            let interactionCount = 0;
            const startTime = Date.now();
            
            document.addEventListener('click', () => {
                interactionCount++;
            });
            
            document.addEventListener('keydown', () => {
                interactionCount++;
            });
            
            // Report interaction metrics after 30 seconds
            setTimeout(() => {
                const sessionDuration = Date.now() - startTime;
                console.log('User Interaction Metrics:', {
                    interactions: interactionCount,
                    sessionDuration: sessionDuration
                });
            }, 30000);
        }
    };

    // Accessibility enhancements
    const accessibilityEnhancer = {
        init: function() {
            this.addKeyboardNavigation();
            this.addScreenReaderSupport();
            this.addFocusManagement();
            this.addARIALabels();
        },

        addKeyboardNavigation: function() {
            // ESC key handling
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    // Close modals
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                    
                    // Close dropdowns
                    const dropdowns = document.querySelectorAll('.dropdown-menu.show');
                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
                
                // Tab navigation enhancement
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            // Remove keyboard navigation class on mouse use
            document.addEventListener('mousedown', () => {
                document.body.classList.remove('keyboard-navigation');
            });
        },

        addScreenReaderSupport: function() {
            // Create live region for announcements
            if (!document.getElementById('aria-live-region')) {
                const liveRegion = document.createElement('div');
                liveRegion.id = 'aria-live-region';
                liveRegion.setAttribute('aria-live', 'polite');
                liveRegion.setAttribute('aria-atomic', 'true');
                liveRegion.className = 'sr-only';
                document.body.appendChild(liveRegion);
            }
            
            // Global announcement function
            window.announceToScreenReader = function(message) {
                const announcer = document.getElementById('aria-live-region');
                if (announcer) {
                    announcer.textContent = message;
                    setTimeout(() => {
                        announcer.textContent = '';
                    }, 1000);
                }
            };
        },

        addFocusManagement: function() {
            // Focus management for modals
            document.addEventListener('shown.bs.modal', (e) => {
                const modal = e.target;
                const focusableElements = modal.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                if (focusableElements.length > 0) {
                    focusableElements[0].focus();
                }
            });
            
            // Return focus to trigger element when modal closes
            document.addEventListener('hidden.bs.modal', (e) => {
                const trigger = document.querySelector(`[data-bs-target="#${e.target.id}"]`);
                if (trigger) {
                    trigger.focus();
                }
            });
        },

        addARIALabels: function() {
            // Add ARIA labels to buttons without text
            const iconButtons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
            iconButtons.forEach(button => {
                const icon = button.querySelector('i[class*="icon-"], i[class*="feather"]');
                if (icon && !button.textContent.trim()) {
                    const iconClass = icon.className;
                    let label = 'Button';
                    
                    if (iconClass.includes('edit')) label = 'Edit';
                    else if (iconClass.includes('delete') || iconClass.includes('trash')) label = 'Delete';
                    else if (iconClass.includes('save')) label = 'Save';
                    else if (iconClass.includes('add') || iconClass.includes('plus')) label = 'Add';
                    else if (iconClass.includes('search')) label = 'Search';
                    else if (iconClass.includes('close')) label = 'Close';
                    
                    button.setAttribute('aria-label', label);
                }
            });
        }
    };

    // Form enhancements
    const formEnhancer = {
        init: function() {
            this.addValidation();
            this.addAutoSave();
            this.addCharacterCounters();
            this.addFileValidation();
        },

        addValidation: function() {
            // Real-time validation
            const forms = document.querySelectorAll('form[novalidate]');
            forms.forEach(form => {
                const inputs = form.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('blur', () => {
                        this.validateField(input);
                    });
                    
                    input.addEventListener('input', () => {
                        if (input.classList.contains('is-invalid')) {
                            this.validateField(input);
                        }
                    });
                });
            });
        },

        validateField: function(field) {
            const value = field.value.trim();
            const isRequired = field.hasAttribute('required');
            const minLength = field.getAttribute('minlength');
            const maxLength = field.getAttribute('maxlength');
            const pattern = field.getAttribute('pattern');
            
            let isValid = true;
            let errorMessage = '';
            
            if (isRequired && !value) {
                isValid = false;
                errorMessage = 'Field ini wajib diisi';
            } else if (minLength && value.length < parseInt(minLength)) {
                isValid = false;
                errorMessage = `Minimal ${minLength} karakter`;
            } else if (maxLength && value.length > parseInt(maxLength)) {
                isValid = false;
                errorMessage = `Maksimal ${maxLength} karakter`;
            } else if (pattern && value && !new RegExp(pattern).test(value)) {
                isValid = false;
                errorMessage = 'Format tidak valid';
            }
            
            if (isValid) {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
                field.setAttribute('aria-invalid', 'false');
            } else {
                field.classList.remove('is-valid');
                field.classList.add('is-invalid');
                field.setAttribute('aria-invalid', 'true');
                
                const errorElement = field.parentNode.querySelector('.invalid-feedback');
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                }
            }
            
            return isValid;
        },

        addAutoSave: function() {
            // Auto-save form data to localStorage
            const forms = document.querySelectorAll('form[data-autosave]');
            forms.forEach(form => {
                const formId = form.id || 'form_' + Date.now();
                const inputs = form.querySelectorAll('input, select, textarea');
                
                // Load saved data
                const savedData = localStorage.getItem(`form_${formId}`);
                if (savedData) {
                    try {
                        const data = JSON.parse(savedData);
                        Object.keys(data).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input && input.type !== 'file') {
                                input.value = data[key];
                            }
                        });
                    } catch (e) {
                        console.error('Error loading saved form data:', e);
                    }
                }
                
                // Save data on input
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        const formData = new FormData(form);
                        const data = {};
                        for (let [key, value] of formData.entries()) {
                            if (key !== 'gambar' && key !== 'file') { // Skip file inputs
                                data[key] = value;
                            }
                        }
                        localStorage.setItem(`form_${formId}`, JSON.stringify(data));
                    });
                });
                
                // Clear saved data on successful submit
                form.addEventListener('submit', () => {
                    localStorage.removeItem(`form_${formId}`);
                });
            });
        },

        addCharacterCounters: function() {
            const counters = document.querySelectorAll('[data-counter]');
            counters.forEach(counter => {
                const target = document.querySelector(counter.getAttribute('data-counter'));
                const maxLength = target.getAttribute('maxlength');
                
                if (target && maxLength) {
                    const updateCounter = () => {
                        const remaining = maxLength - target.value.length;
                        counter.textContent = remaining;
                        
                        if (remaining < 0) {
                            counter.classList.add('text-danger');
                        } else {
                            counter.classList.remove('text-danger');
                        }
                    };
                    
                    target.addEventListener('input', updateCounter);
                    updateCounter();
                }
            });
        },

        addFileValidation: function() {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const maxSize = input.getAttribute('data-max-size');
                        const allowedTypes = input.getAttribute('accept');
                        
                        // Validate file size
                        if (maxSize && file.size > parseInt(maxSize)) {
                            this.showFileError(input, 'Ukuran file terlalu besar');
                            return;
                        }
                        
                        // Validate file type
                        if (allowedTypes) {
                            const types = allowedTypes.split(',').map(type => type.trim());
                            const isValidType = types.some(type => {
                                if (type.startsWith('.')) {
                                    return file.name.toLowerCase().endsWith(type.toLowerCase());
                                } else {
                                    return file.type === type;
                                }
                            });
                            
                            if (!isValidType) {
                                this.showFileError(input, 'Format file tidak didukung');
                                return;
                            }
                        }
                        
                        // Show preview for images
                        if (file.type.startsWith('image/')) {
                            this.showImagePreview(input, file);
                        }
                    }
                });
            });
        },

        showFileError: function(input, message) {
            input.value = '';
            input.classList.add('is-invalid');
            
            let errorElement = input.parentNode.querySelector('.invalid-feedback');
            if (!errorElement) {
                errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                input.parentNode.appendChild(errorElement);
            }
            errorElement.textContent = message;
        },

        showImagePreview: function(input, file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                let preview = input.parentNode.querySelector('.image-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.className = 'image-preview mt-2';
                    input.parentNode.appendChild(preview);
                }
                
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="this.parentNode.remove()">
                        <i class="feather icon-x"></i> Remove
                    </button>
                `;
            };
            reader.readAsDataURL(file);
        }
    };

    // DataTable enhancements
    const dataTableEnhancer = {
        init: function() {
            this.addAccessibility();
            this.addPerformanceOptimizations();
        },

        addAccessibility: function() {
            // Add ARIA labels to DataTable elements
            $(document).on('draw.dt', function() {
                const tables = document.querySelectorAll('.dataTable');
                tables.forEach(table => {
                    // Add role and aria-label
                    table.setAttribute('role', 'table');
                    if (!table.getAttribute('aria-label')) {
                        table.setAttribute('aria-label', 'Data table');
                    }
                    
                    // Add ARIA labels to action buttons
                    const actionButtons = table.querySelectorAll('button[onclick*="hapus"], button[onclick*="edit"]');
                    actionButtons.forEach(button => {
                        if (!button.getAttribute('aria-label')) {
                            const onclick = button.getAttribute('onclick');
                            if (onclick.includes('hapus')) {
                                button.setAttribute('aria-label', 'Hapus data');
                            } else if (onclick.includes('edit')) {
                                button.setAttribute('aria-label', 'Edit data');
                            }
                        }
                    });
                });
            });
        },

        addPerformanceOptimizations: function() {
            // Debounce search
            let searchTimeout;
            $(document).on('search.dt', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    // Custom search logic if needed
                }, 300);
            });
        }
    };

    // Notification system
    const notificationSystem = {
        show: function(message, type = 'info', duration = 3000) {
            // Use existing notification system or create new one
            if (typeof PNotify !== 'undefined') {
                new PNotify({
                    title: this.getTitle(type),
                    text: message,
                    type: type,
                    delay: duration,
                    styling: 'bootstrap4',
                    buttons: {
                        closer: true,
                        sticker: false
                    }
                });
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: this.getTitle(type),
                    text: message,
                    icon: type,
                    timer: duration,
                    showConfirmButton: false
                });
            } else {
                // Fallback to alert
                alert(message);
            }
            
            // Announce to screen reader
            if (window.announceToScreenReader) {
                window.announceToScreenReader(message);
            }
        },

        getTitle: function(type) {
            const titles = {
                'success': 'Berhasil',
                'error': 'Error',
                'warning': 'Peringatan',
                'info': 'Informasi'
            };
            return titles[type] || 'Notifikasi';
        }
    };

    // Global error handler
    const errorHandler = {
        init: function() {
            window.addEventListener('error', (e) => {
                console.error('JavaScript Error:', e.error);
                this.reportError(e.error);
            });
            
            window.addEventListener('unhandledrejection', (e) => {
                console.error('Unhandled Promise Rejection:', e.reason);
                this.reportError(e.reason);
            });
        },

        reportError: function(error) {
            // Send error to logging service
            if (typeof gtag !== 'undefined') {
                gtag('event', 'exception', {
                    description: error.message || error.toString(),
                    fatal: false
                });
            }
        }
    };

    // Initialize all enhancements
    document.addEventListener('DOMContentLoaded', function() {
        performanceMonitor.init();
        accessibilityEnhancer.init();
        formEnhancer.init();
        dataTableEnhancer.init();
        errorHandler.init();
        
        // Make notification system globally available
        window.showNotification = notificationSystem.show.bind(notificationSystem);
        
        // Announce page load
        if (window.announceToScreenReader) {
            window.announceToScreenReader('Halaman telah dimuat');
        }
    });

    // Export for module systems
    if (typeof module !== 'undefined' && module.exports) {
        module.exports = {
            performanceMonitor,
            accessibilityEnhancer,
            formEnhancer,
            dataTableEnhancer,
            notificationSystem,
            errorHandler
        };
    }

})();