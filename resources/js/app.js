import './bootstrap';

// Time capsule countdown functionality
function initializeCountdowns() {
    const countdownElements = document.querySelectorAll('[data-countdown]');

    countdownElements.forEach(element => {
        const unlockDate = new Date(element.dataset.countdown);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = unlockDate.getTime() - now;

            if (distance < 0) {
                element.innerHTML = '<span class="text-green-600 font-bold">Ready to Open!</span>';
                element.classList.add('animate-pulse');
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.innerHTML = `
                <div class="countdown-timer text-2xl font-bold text-blue-800">
                    ${days}d ${hours}h ${minutes}m ${seconds}s
                </div>
            `;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
}

// Form enhancements
function initializeFormEnhancements() {
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            let preview = input.parentNode.querySelector('.file-preview');

            if (!preview) {
                preview = document.createElement('div');
                preview.className = 'file-preview mt-3 grid grid-cols-2 md:grid-cols-4 gap-2';
                input.parentNode.appendChild(preview);
            }

            preview.innerHTML = '';

            files.forEach(file => {
                const item = document.createElement('div');
                item.className = 'bg-blue-50 border border-blue-200 rounded-lg p-2 text-center';

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = 'w-full h-16 object-cover rounded mb-1';
                    img.src = URL.createObjectURL(file);
                    item.appendChild(img);
                }

                const name = document.createElement('p');
                name.className = 'text-xs text-blue-700 truncate';
                name.textContent = file.name;
                item.appendChild(name);

                preview.appendChild(item);
            });
        });
    });
}

// Smooth animations
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements that should animate in
    const animateElements = document.querySelectorAll('.time-capsule-card, .prose, .stats-card');
    animateElements.forEach(el => observer.observe(el));
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 translate-x-full ${
        type === 'success' ? 'bg-green-600' :
            type === 'error' ? 'bg-red-600' :
                type === 'warning' ? 'bg-yellow-600' :
                    'bg-blue-600'
    }`;

    notification.textContent = message;
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 10);

    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Local storage for draft saving
function initializeDraftSaving() {
    const form = document.querySelector('form[action*="capsules"]');
    if (!form) return;

    const titleInput = form.querySelector('input[name="title"]');
    const contentTextarea = form.querySelector('textarea[name="content"]');

    if (!titleInput || !contentTextarea) return;

    // Load draft
    const savedTitle = localStorage.getItem('capsule_draft_title');
    const savedContent = localStorage.getItem('capsule_draft_content');

    if (savedTitle && !titleInput.value) titleInput.value = savedTitle;
    if (savedContent && !contentTextarea.value) contentTextarea.value = savedContent;

    // Save draft on input
    const saveDraft = () => {
        localStorage.setItem('capsule_draft_title', titleInput.value);
        localStorage.setItem('capsule_draft_content', contentTextarea.value);
    };

    titleInput.addEventListener('input', saveDraft);
    contentTextarea.addEventListener('input', saveDraft);

    // Clear draft on successful submit
    form.addEventListener('submit', () => {
        setTimeout(() => {
            localStorage.removeItem('capsule_draft_title');
            localStorage.removeItem('capsule_draft_content');
        }, 1000);
    });
}

// Theme management
function initializeTheme() {
    // Add subtle theme variations based on time of day
    const hour = new Date().getHours();
    const body = document.body;

    if (hour >= 6 && hour < 12) {
        body.classList.add('theme-morning');
    } else if (hour >= 12 && hour < 18) {
        body.classList.add('theme-afternoon');
    } else if (hour >= 18 && hour < 22) {
        body.classList.add('theme-evening');
    } else {
        body.classList.add('theme-night');
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeCountdowns();
    initializeFormEnhancements();
    initializeAnimations();
    initializeDraftSaving();
    initializeTheme();

    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = submitButton.innerHTML + ' <span class="animate-spin inline-block">⏳</span>';
            }
        });
    });
});

// Global utilities
window.TimeCapsule = {
    showNotification,

    // Utility to format time remaining
    formatTimeRemaining: (unlockDate) => {
        const now = new Date().getTime();
        const distance = new Date(unlockDate).getTime() - now;

        if (distance < 0) return 'Ready to open!';

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        if (days > 0) return `${days} days, ${hours} hours`;
        if (hours > 0) return `${hours} hours, ${minutes} minutes`;
        return `${minutes} minutes`;
    },

    // Confirm deletion with more context
    confirmDelete: (itemName) => {
        return confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone and you will lose this time capsule forever.`);
    }
};
